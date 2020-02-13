<?php

namespace GoPrediction\ActionRecommendation\Block;

use GoPrediction\ActionRecommendation\Helper\Data;
use Magento\Framework\View\Element\Template;

class Checkout extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Products constructor.
     * @param Template\Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->helper = $helper;
    }

    public function getHelper()
    {
        return $this->helper;
    }
}
