<?php
/**
 * @author Dmytro Zavalkin <dmytro.zavalkin@aoe.com>
 *
 * @method array getValue()
 */
class Aoe_DesignFallback_Model_System_Config_Backend_Fallback extends Mage_Adminhtml_Model_System_Config_Backend_Serialized_Array
{
    const DEFAULT_CONFIGURATION = 'a:3:{s:18:"_1392295069766_766";a:2:{s:7:"package";s:9:"[current]";s:5:"theme";s:9:"[current]";}s:18:"_1392295179888_888";a:2:{s:7:"package";s:9:"[current]";s:5:"theme";s:7:"default";}s:18:"_1392295198661_661";a:2:{s:7:"package";s:4:"base";s:5:"theme";s:7:"default";}}';

    protected $_eventPrefix = 'core_config_backend_aoe_designfallback_fallback';

    protected function _afterLoad()
    {
        if (!is_array($this->getValue())) {
            $value = $this->getValue();
            // migrate from old versions without backend/frontend models
            if (empty($value) || @unserialize($value) === false) {
                $this->setValue(self::DEFAULT_CONFIGURATION);
            }
        }

        parent::_afterLoad();
    }
}
