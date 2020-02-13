<?php

namespace GoPrediction\ActionRecommendation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(Context $context, StoreManagerInterface $storeManager)
    {
        $this->context = $context;
        $this->storeManager = $storeManager;

        parent::__construct($context);
    }

    public function getPublicAccessKey()
    {
        return $this->context->getScopeConfig()->getValue(
            'action_recommendation/general/public_access_key',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getLabel()
    {
        return $this->context->getScopeConfig()->getValue(
            'action_recommendation/general/label',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCurrentCurrencySymbol()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();
    }
}
