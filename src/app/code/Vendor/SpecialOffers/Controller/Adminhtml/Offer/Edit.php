<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Vendor\SpecialOffers\Model\SpecialOfferFactory;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer as ResourceModel;

class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Vendor_SpecialOffers::offer_manage';

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param SpecialOfferFactory $offerFactory
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly SpecialOfferFactory $offerFactory,
        private readonly ResourceModel $resourceModel
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Page
     */
    public function execute(): Page
    {
        $offerId = (int) $this->getRequest()->getParam('offer_id');
        $offer = $this->offerFactory->create();

        if ($offerId) {
            $this->resourceModel->load($offer, $offerId);
            if (!$offer->getId()) {
                $this->messageManager->addErrorMessage(__('This offer no longer exists.'));
                return $this->resultPageFactory->create();
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vendor_SpecialOffers::offer_manage');
        $resultPage->getConfig()->getTitle()->prepend(
            $offer->getId() ? $offer->getTitle() : __('New Offer')
        );

        return $resultPage;
    }
}
