<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer\CollectionFactory;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer as ResourceModel;

class MassStatus extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Vendor_SpecialOffers::offer_manage';

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        Context $context,
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory,
        private readonly ResourceModel $resourceModel
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $status = (int) $this->getRequest()->getParam('status');
        $collectionSize = $collection->getSize();

        foreach ($collection as $offer) {
            $offer->setIsActive((bool) $status);
            $this->resourceModel->save($offer);
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 offer(s) have been updated.', $collectionSize)
        );

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
