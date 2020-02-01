<?php

namespace GoPrediction\ActionRecommendation\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Products extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var Template\Context
     */
    protected $_context;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Products constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_registry = $registry;
        $this->_context = $context;
        $this->_storeManager = $storeManager;
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getPublicAccessKey()
    {
        return $this->_context->getScopeConfig()->getValue(
            'action_recommendation/general/public_access_key',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getLabel()
    {
        return $this->_context->getScopeConfig()->getValue(
            'action_recommendation/general/label',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCurrentCurrencySymbol()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();
    }
}
