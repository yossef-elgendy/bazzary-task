<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\SpecialOffers\Api\Data\SpecialOfferInterface;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer as ResourceModel;

class SpecialOffer extends AbstractModel implements SpecialOfferInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'vendor_special_offer';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getOfferId(): ?int
    {
        $id = $this->getData(self::OFFER_ID);
        return $id !== null ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setOfferId(int $offerId): SpecialOfferInterface
    {
        return $this->setData(self::OFFER_ID, $offerId);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): SpecialOfferInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setDescription(?string $description): SpecialOfferInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritdoc
     */
    public function getImage(): ?string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @inheritdoc
     */
    public function setImage(?string $image): SpecialOfferInterface
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::URL);
    }

    /**
     * @inheritdoc
     */
    public function setUrl(?string $url): SpecialOfferInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritdoc
     */
    public function getIsActive(): bool
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritdoc
     */
    public function setIsActive(bool $isActive): SpecialOfferInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }
}
