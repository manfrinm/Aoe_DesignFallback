<?php
/**
 * @author Dmytro Zavalkin <dmytro.zavalkin@aoe.com>
 */
/**
 * Adminhtml system config array field renderer (fallback field)
 */
class Aoe_DesignFallback_Block_System_Config_Form_Field_Fallback extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('package', array(
            'label' => Mage::helper('aoe_designfallback')->__('Package'),
            'style' => 'width:120px',
        ));
        $this->addColumn('theme', array(
            'label' => Mage::helper('aoe_designfallback')->__('Theme'),
            'style' => 'width:120px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('aoe_designfallback')->__('Add Fallback Item');
        parent::__construct();
    }
}
