<?php

namespace GoPrediction\ActionRecommendation\Block\Product;

use GoPrediction\ActionRecommendation\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * Products constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->registry = $registry;
        $this->helper = $helper;
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
}
