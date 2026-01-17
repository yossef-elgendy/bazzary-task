<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Block\BlockInterface;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer\Collection;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer\CollectionFactory;

class SpecialOffersSlider extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Vendor_SpecialOffers::widget/slider.phtml';

    /**
     * @var Collection|null
     */
    private ?Collection $offersCollection = null;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get active special offers
     *
     * @return Collection
     */
    public function getOffers(): Collection
    {
        if ($this->offersCollection === null) {
            $this->offersCollection = $this->collectionFactory->create();
            $this->offersCollection->addActiveFilter();
            $this->offersCollection->setOrder('created_at', 'DESC');

            $limit = (int) $this->getData('offers_count') ?: 8;
            $this->offersCollection->setPageSize($limit);
        }

        return $this->offersCollection;
    }

    /**
     * Get image URL for offer
     *
     * @param string|null $image
     * @return string
     */
    public function getImageUrl(?string $image): string
    {
        if (!$image) {
            return $this->getViewFileUrl('Vendor_SpecialOffers::images/placeholder.png');
        }

        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl . 'specialoffers/' . $image;
    }

    /**
     * Get number of items per row
     *
     * @return int
     */
    public function getItemsPerRow(): int
    {
        return (int) ($this->getData('items_per_row') ?: 4);
    }

    /**
     * Get slider title
     *
     * @return string
     */
    public function getSliderTitle(): string
    {
        return (string) ($this->getData('slider_title') ?: __('Special Offers'));
    }

    /**
     * Check if slider has offers
     *
     * @return bool
     */
    public function hasOffers(): bool
    {
        return $this->getOffers()->getSize() > 0;
    }
}
