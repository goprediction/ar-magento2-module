<?php
declare(strict_types=1);

namespace GoPrediction\ActionRecommendation\Controller\Product;

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
class Feed extends Action
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
        $collection->joinAttribute('image', 'catalog_product/image', 'entity_id', null, 'left');
        $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left');
        $collection->joinAttribute('special_price', 'catalog_product/special_price', 'entity_id', null, 'left');
        $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');
        $collection->joinAttribute('url_key', 'catalog_product/url_key', 'entity_id', null, 'inner');

        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
            ->addAttributeToFilter('visibility', ['in' => $this->productVisibility->getVisibleInSiteIds()]);

        $response = ['items' => []];
        foreach ($collection->getItems() as $item) {
            $response['items'][] = [
                'product_id' => $item->getData('entity_id'),
                'additional_fields' => [
                    'name' => $item->getData('name'),
                    'image' => $item->getData('image'),
                    'price' => $item->getData('price'),
                    'special_price' => $item->getData('special_price'),
                    'url_key' => $item->getData('url_key'),
                ]
            ];
        }

        return $result->setData($response);
    }
}
