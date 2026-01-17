<?php
declare(strict_types=1);

namespace Vendor\SpecialOffers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Vendor\SpecialOffers\Model\SpecialOfferFactory;
use Vendor\SpecialOffers\Model\ResourceModel\SpecialOffer as ResourceModel;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Vendor_SpecialOffers::offer_manage';

    /**
     * @param Context $context
     * @param SpecialOfferFactory $offerFactory
     * @param ResourceModel $resourceModel
     * @param DataPersistorInterface $dataPersistor
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        private readonly SpecialOfferFactory $offerFactory,
        private readonly ResourceModel $resourceModel,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly ImageUploader $imageUploader
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
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $offerId = (int) ($data['offer_id'] ?? 0);
            $offer = $this->offerFactory->create();

            if ($offerId) {
                $this->resourceModel->load($offer, $offerId);
                if (!$offer->getId()) {
                    $this->messageManager->addErrorMessage(__('This offer no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            // Process image
            if (isset($data['image']) && is_array($data['image'])) {
                if (!empty($data['image'][0]['name'])) {
                    $imageName = $data['image'][0]['name'];
                    // Check if this is a new upload (file exists in tmp directory)
                    $isNewUpload = isset($data['image'][0]['tmp_name'])
                        || (isset($data['image'][0]['url']) && strpos($data['image'][0]['url'], '/tmp/') !== false);

                    if ($isNewUpload) {
                        try {
                            $this->imageUploader->moveFileFromTmp($imageName);
                        } catch (\Exception $e) {
                            // File might already be moved, continue with save
                        }
                    }
                    $data['image'] = $imageName;
                } else {
                    $data['image'] = null;
                }
            } else {
                // Keep existing image if not explicitly removed
                if ($offerId && $offer->getImage()) {
                    $data['image'] = $offer->getImage();
                } else {
                    $data['image'] = null;
                }
            }

            // Validate URL
            if (!empty($data['url']) && !filter_var($data['url'], FILTER_VALIDATE_URL)) {
                $this->messageManager->addErrorMessage(__('Please enter a valid URL.'));
                $this->dataPersistor->set('vendor_special_offer', $data);
                return $resultRedirect->setPath('*/*/edit', ['offer_id' => $offerId]);
            }

            $offer->setData($data);

            try {
                $this->resourceModel->save($offer);
                $this->messageManager->addSuccessMessage(__('The offer has been saved.'));
                $this->dataPersistor->clear('vendor_special_offer');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['offer_id' => $offer->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the offer.'));
            }

            $this->dataPersistor->set('vendor_special_offer', $data);
            return $resultRedirect->setPath('*/*/edit', ['offer_id' => $offerId]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
