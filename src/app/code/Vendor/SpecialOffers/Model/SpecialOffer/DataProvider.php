<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Model\SpecialOffer;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $offer) {
            $data = $offer->getData();

            if (isset($data['image']) && $data['image']) {
                $data['image'] = [
                    [
                        'name' => $data['image'],
                        'url' => $this->getMediaUrl($data['image'])
                    ]
                ];
            }

            $this->loadedData[$offer->getId()] = $data;
        }

        $data = $this->dataPersistor->get('vendor_special_offer');
        if (!empty($data)) {
            $offer = $this->collection->getNewEmptyItem();
            $offer->setData($data);
            $this->loadedData[$offer->getId()] = $offer->getData();
            $this->dataPersistor->clear('vendor_special_offer');
        }

        return $this->loadedData ?? [];
    }

    /**
     * Get media URL for image
     *
     * @param string $image
     * @return string
     */
    private function getMediaUrl(string $image): string
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl . 'specialoffers/' . $image;
    }
}
