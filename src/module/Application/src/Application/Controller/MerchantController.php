<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MerchantController extends AbstractApplicationController
{
    public function indexAction()
    {
        $merchantCustomerRelation = null;

        $merchantBranchPath = $this->params()->fromRoute('merchant-name', '');
        if (!$merchantBranchPath) {
            return $this->redirect()->toRoute('index', array(
                'action' => 'locations'
            ));
        }

        /**
         * @var \Application\Service\Locations $locationsService
         */
        $locationsService = $this->getServiceLocator()->get('Application\Service\Locations');
        $merchantBranch = $locationsService->findMerchantBranch($merchantBranchPath);

        if (!$merchantBranch) {
            return $this->redirect()->toRoute('index', array(
                'action' => 'locations'
            ));
        }

        /**
         * @var \Application\Service\Merchant $merchantService
         */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
        $rewards = $merchantService->getActiveRewards($merchantBranch);

        if ($this->userType == self::USER_TYPE_CUSTOMER) {
            /** @var \Application\Model\Entity\Customer $customerEntity  */
            $authService = $this->getCustomerAuthService();
            if ($authService->hasIdentity()) {
                $customerEntity = $authService->getIdentity();
                $merchantCustomerRelation = $merchantService->getMerchantCustomerRelation($merchantBranch, $customerEntity);
            }
        }

        /**
         * @var \Application\Service\Store $storeService
         */
        $storeService = $this->getServiceLocator()->get('Application\Service\Store');
        $merchantURL = $storeService->getURL(
            $merchantBranch->getFkMerchant()->getId(),
            \Application\Service\Store::TYPE_MERCHANT_LOGO
        );

        return new ViewModel([
            'rewards' => $rewards,
            'availablePoints' => $merchantCustomerRelation ? $merchantCustomerRelation->getPoints() : 0,
            'merchantURL' => $merchantURL,
            'merchantBranch' => $merchantBranch
        ]);
    }

    public function logoAction(){

        $merchantId = $this->params()->fromRoute('id', 0);
        if (!$merchantId) {
            return $this->redirect()->toRoute('home');
        }

        /**
         * @var \Application\Service\Store $storeService
         */
        $storeService = $this->getServiceLocator()->get('Application\Service\Store');
        $merchantPath = $storeService->getPath(
            $merchantId,
            \Application\Service\Store::TYPE_MERCHANT_LOGO
        );

        header ('Content-Type: image/jpg');
        echo file_get_contents($merchantPath);

        return false;
    }
}
