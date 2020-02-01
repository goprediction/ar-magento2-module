<?php
declare(strict_types=1);

namespace GoPrediction\ActionRecommendation\Controller\Index;

use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 */
class Index extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var ProductStatus
     */
    private $productStatus;
    /**
     * @var ProductVisibility
     */
    private $productVisibility;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ProductStatus $productStatus
     * @param ProductVisibility $productVisibility
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ProductCollectionFactory $productCollectionFactory,
        ProductStatus $productStatus,
        ProductVisibility $productVisibility
    ) {
        parent::__construct($context);

        $this->jsonFactory = $jsonFactory;

        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create();
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
            ->addAttributeToFilter('visibility', ['in' => $this->productVisibility->getVisibleInSiteIds()]);

        $response = ['items' => []];
        foreach ($collection->getItems() as $item) {
            $response['items'][] = ['product_id' => $item->getData('entity_id')];
        }

        return $result->setData($response);
    }
}
