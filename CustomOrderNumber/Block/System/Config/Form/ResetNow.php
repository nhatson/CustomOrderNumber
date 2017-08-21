<?php
namespace Bss\CustomOrderNumber\Block\System\Config\Form;
 
use Magento\Framework\App\Config\ScopeConfigInterface;
 
class ResetNow extends \Magento\Config\Block\System\Config\Form\Field
{
     const BUTTON_TEMPLATE = 'system/config/button/resetnow.phtml';
 
     /**
     * Set template to itself
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::BUTTON_TEMPLATE);
        }
        return $this;
    }
    /**
     * Render button
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxCheckUrl()
    {
        return $this->getUrl('mageworx_alsobought/system_config/collect');
    }
     /**
     * Get the button and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        //$originalData = $element->getOriginalData();
        $this->addData(
            [
                'id'        => 'btn-reset',
                'button_label'     => _('Reset Now'),
                'onclick'   => 'javascript:check(); return false;'
            ]
        );
        return $this->_toHtml();
    }
}
