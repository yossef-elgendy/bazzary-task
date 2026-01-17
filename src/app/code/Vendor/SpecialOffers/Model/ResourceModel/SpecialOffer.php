<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SpecialOffer extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'vendor_special_offer_resource';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('vendor_special_offers', 'offer_id');
    }
}
