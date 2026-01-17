<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Api\Data;

interface SpecialOfferInterface
{
    public const OFFER_ID = 'offer_id';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const IMAGE = 'image';
    public const URL = 'url';
    public const IS_ACTIVE = 'is_active';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * Get offer ID
     *
     * @return int|null
     */
    public function getOfferId(): ?int;

    /**
     * Set offer ID
     *
     * @param int $offerId
     * @return $this
     */
    public function setOfferId(int $offerId): self;

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self;

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set description
     *
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self;

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * Set image
     *
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self;

    /**
     * Get URL
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Set URL
     *
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): self;

    /**
     * Get is active
     *
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self;

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;
}
