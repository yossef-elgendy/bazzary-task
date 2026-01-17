<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Vendor\SpecialOffers\Model\SpecialOfferFactory;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer as ResourceModel;

class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Vendor_SpecialOffers::offer_manage';

    /**
     * @param Context $context
     * @param SpecialOfferFactory $offerFactory
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        Context $context,
        private readonly SpecialOfferFactory $offerFactory,
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
        $resultRedirect = $this->resultRedirectFactory->create();
        $offerId = (int) $this->getRequest()->getParam('offer_id');

        if ($offerId) {
            try {
                $offer = $this->offerFactory->create();
                $this->resourceModel->load($offer, $offerId);

                if ($offer->getId()) {
                    $this->resourceModel->delete($offer);
                    $this->messageManager->addSuccessMessage(__('The offer has been deleted.'));
                } else {
                    $this->messageManager->addErrorMessage(__('This offer no longer exists.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['offer_id' => $offerId]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
