<?php

class Aoe_DesignFallback_Model_Design_Package extends Mage_Core_Model_Design_Package
{
    const XML_PATH_DESIGN_FALLBACK = 'design/fallback/fallback';

    /**
     * Generated fallback scheme
     *
     * @var array
     */
    static $_fallbackScheme = null;
    private $_areas = array('frontend', 'adminhtml');

    /**
     * Check for files existence by specified scheme
     *
     * @param string $file
     * @param array &$params
     * @param array $fallbackScheme
     * @return string
     */
    protected function _fallback($file, array &$params, array $fallbackScheme = array(array()))
    {
        return parent::_fallback($file, $params, $this->_getFallbackScheme($params));
    }

    /**
     * Get fallback scheme from configuration
     *
     * @param array $defaults (optional). Needed for resolving default package and theme for duplicates elimination
     * @return array
     */
    protected function _getFallbackScheme(array $defaults = array())
    {
        if (self::$_fallbackScheme === null) {
            $store = Mage::app()->getStore($this->getStore());

            /** @var Aoe_DesignFallback_Model_System_Config_Backend_Fallback $model */
            $model = Mage::getModel('aoe_designfallback/system_config_backend_fallback');
            $model->setPath(self::XML_PATH_DESIGN_FALLBACK)
                ->setValue(Mage::getStoreConfig('design/fallback/fallback', $store))
                ->setWebsite($store->getWebsite()->getCode())
                ->setStore($store->getCode())
                ->afterLoad();
            $fallbackConfiguration = $model->getValue();

            self::$_fallbackScheme = array();
            foreach ($this->_areas as $_area) {
                foreach ($fallbackConfiguration as $item) {
                    $packageName = $this->_resolveConfiguration($item['package']);
                    if (!empty($packageName)) { // empty values will be evaluated to current package ...
                        if (!$this->designPackageExists($packageName, $_area)) {
                            $packageName = Mage_Core_Model_Design_Package::DEFAULT_PACKAGE;
                        }
                    } else {
                        $packageName = $defaults['_package'];
                    }

                    $themeName = $this->_resolveConfiguration($item['theme']);
                    if (empty($themeName)) {
                        $themeName = $defaults['_theme'];
                    }

                    $params = array(
                        '_package' => $packageName,
                        '_theme' => $themeName,
                    );

                    // avoid exact duplicates that are neighbours
                    if ($params !== end(self::$_fallbackScheme)) {
                        self::$_fallbackScheme[$_area][] = $params;
                    }
                }
            }
        }
        $currentArea = isset($defaults['_area']) && !in_array($defaults['_area'], array('header', 'footer')) ? $defaults['_area'] : $this->getArea();

        return self::$_fallbackScheme[$currentArea];
    }

    /**
     * Resolve configuration.
     * Values wrapped in {...} will be looked up in configuration.
     * Example: {design/package/name}
     *
     * @param $value
     * @return string
     */
    protected function _resolveConfiguration($value)
    {
        $value = trim($value);
        if (strtolower($value == '[current]')) {
            // empty value will be in ->updateParamDefaults().
            // to the current package and theme taking type-specific themes and design exceptions
            // (System -> Design) into account
            $value = null;
        } elseif ($value[0] == '{' && $value[strlen($value) - 1] == '}') {
            $value = substr($value, 1, -1);
            $value = Mage::getStoreConfig($value, $this->getStore());
        }

        return $value;
    }
}
