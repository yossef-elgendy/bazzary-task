<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Block\Adminhtml\Offer\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    /**
     * @param Context $context
     */
    public function __construct(
        protected readonly Context $context
    ) {
    }

    /**
     * Get offer ID
     *
     * @return int|null
     */
    public function getOfferId(): ?int
    {
        $id = $this->context->getRequest()->getParam('offer_id');
        return $id ? (int) $id : null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
