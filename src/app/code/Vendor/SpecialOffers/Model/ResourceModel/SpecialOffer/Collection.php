<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\SpecialOffers\Model\SpecialOffer;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'offer_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'vendor_special_offer_collection';

    /**
     * Initialize collection model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(SpecialOffer::class, ResourceModel::class);
    }

    /**
     * Filter collection by active status
     *
     * @return $this
     */
    public function addActiveFilter(): self
    {
        return $this->addFieldToFilter('is_active', 1);
    }
}
