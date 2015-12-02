<?php

namespace Application\Controller;

use Application\Entity\RewardHistory;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable,
    Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception\DomainException;
use \Application\Model\Entity\Owner as OwnerEntity;
use \Application\Model\Entity\Reward as RewardEntity;
use Application\Form\Login as LoginForm;
use Application\Form\OwnerSettings as SettingsForm;


class BusinessController extends AbstractAuthController
{
    /**
     * @var OwnerEntity
     */
    private $owner;

    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->owner = $this->getOwner();

        $routeMatch = $event->getRouteMatch();
        $currentAction = $routeMatch->getParam('action');

        if (!$this->owner && !in_array($currentAction, ['login', 'index'])) {
            return $this->redirect()->toRoute(
                'business', array(
                    'action' => 'login',
                    'unauthorized' => 1
                )
            );
        } else {
            if (!in_array($currentAction, ['login', 'index'])) {
                $this->userType = self::USER_TYPE_OWNER;
            }
            parent::onDispatch($event);
        }
    }

    public function indexAction()
    {
        return new ViewModel(array());
    }

    public function dashboardAction()
    {
        $merchantBranchEntity = $this->getOwner()->getFkMerchantBranch();

        /** @var \Application\Service\Statistics $statisticsService */
        $statisticsService = $this->getServiceLocator()->get('Application\Service\Statistics');

        $return = [];

        $return['newAndReturned'] = $statisticsService->getNewAndReturned($merchantBranchEntity, 7);
        $return['lastVisitors'] = $statisticsService->getLastVisits($merchantBranchEntity);
        $return['gender'] = $statisticsService->getGenders($merchantBranchEntity);
        $return['ages'] = $statisticsService->getAges($merchantBranchEntity);

        return new ViewModel($return);
    }

    public function rewardsAction()
    {
        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['successUpdate'] = false;
        $viewModel['errorMessage'] = null;

        try {

            /** @var \Zend\Http\Request $request */
            $request = $this->getRequest();

            if ($request->isPost()) {

                $data = $request->getPost();
                if (!$data) {
                    throw new \Exception($this->getTranslator()->translate('Пустой запрос.'));
                }

                $rewardEntity = new RewardEntity();

                /** @var \Doctrine\ORM\EntityManager $entityManager */
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $rewardForm = new \Application\Form\Reward($entityManager);
                $rewardForm->bind($rewardEntity);
                $rewardForm->setData($data);

                if (!$rewardForm->isValid()) {
                    throw new \Exception($this->getTranslator()->translate('Неправельные аргументы.'));
                }

                try {

                    $factory = new \RandomLib\Factory;
                    $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

                    $rewardEntity->setCreatedAt(new \DateTime());
                    $rewardEntity->setUpdatedAt(new \DateTime());
                    $rewardEntity->setFkMerchant($this->getOwner()->getFkMerchant());
                    $rewardEntity->setFkMerchantBranch($this->getOwner()->getFkMerchantBranch());
                    $rewardEntity->setFkOwner($this->getOwner());
                    $rewardEntity->setIsActive(RewardEntity::STATUS_ACTIVE);
                    $rewardEntity->setCode($generator->generateString(32));

                    $entityManager->persist($rewardEntity);
                    $entityManager->flush();

                    $rewardHistoryEntity = new RewardHistory();
                    $rewardHistoryEntity->setCreatedAt(new \DateTime());
                    $rewardHistoryEntity->setFkReward($rewardEntity);
                    $rewardHistoryEntity->setName($rewardEntity->getName());
                    $rewardHistoryEntity->setPoints($rewardEntity->getPoints());
                    $rewardHistoryEntity->setCode($rewardEntity->getCode());

                    $entityManager->persist($rewardHistoryEntity);
                    $entityManager->flush();

                    $_POST = [];
                    $viewModel['successUpdate'] = true;

                } catch (\RuntimeException $e) {
                    throw new \Exception(
                        sprintf($this->getTranslator()->translate('Внутрянная ошибка сервиса. (%s)'), $e->getMessage())
                    );
                }
            }

        } catch (\Exception $e) {
            $viewModel['badTry'] = true;
            $viewModel['errorMessage'] = $e->getMessage();
        }

        /** @var \Application\Service\Merchant $merchantService */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
        $viewModel['rewards'] = $merchantService->getActiveRewards($this->getOwner()->getFkMerchantBranch());

        return new ViewModel($viewModel);
    }

    /**
     * @return RewardEntity|null
     */
    private function getReward()
    {
        $rewardId = $this->params()->fromRoute('id', 0);
        if (!$rewardId) {
            return null;
        }

        /** @var \Application\Service\Merchant $merchantService */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
        $rewardEntity = $merchantService->getRewardById($rewardId, $this->getOwner()->getFkMerchantBranch());
        return $rewardEntity;
    }

    /**
     * @return \Application\Model\Entity\MerchantsCustomers|null
     */
    private function getCustomerRelationById()
    {
        $customerId = $this->params()->fromRoute('id', 0);
        if (!$customerId) {
            return null;
        }

        /** @var \Application\Service\Merchant $merchantService */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
        $clientEntity = $merchantService->getCustomerById($customerId, $this->getOwner()->getFkMerchantBranch());
        return $clientEntity;
    }

    public function archiveRewardAction()
    {
        $rewardEntity = $this->getReward();
        if (!$rewardEntity) {
            return $this->redirect()->toRoute(
                'business', array(
                    'action' => 'rewards'
                )
            );
        }

        $rewardEntity->setIsActive(RewardEntity::STATUS_NO_ACTIVE);
        $rewardEntity->setUpdatedAt(new \DateTime());

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $entityManager->persist($rewardEntity);
        $entityManager->flush();

        return $this->redirect()->toRoute(
            'business', array(
                'action' => 'rewards'
            )
        );
    }

    public function rewardAction()
    {
        $rewardEntity = $this->getReward();
        if (!$rewardEntity) {
            return $this->redirect()->toRoute(
                'business', array(
                    'action' => 'rewards'
                )
            );
        }

        /** @var \Application\Service\Merchant $merchantService */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');

        $return = [];
        $return['badTry'] = false;
        $return['successUpdate'] = false;
        $return['errorMessage'] = null;

        try {

            /** @var \Zend\Stdlib\RequestInterface $request */
            $request = $this->getRequest();

            if ($request->isPost()) {

                $data = $request->getPost();
                if (!$data) {
                    throw new \Exception($this->getTranslator()->translate('Ошибка при вводе.'));
                }

                /** @var \Doctrine\ORM\EntityManager $entityManager */
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $rewardForm = new \Application\Form\Reward($entityManager);
                //$rewardForm->bind($rewardEntity);
                $rewardForm->setData($data);

                if (!$rewardForm->isValid()) {
                    throw new \Exception($this->getTranslator()->translate('Неправельные аргументы.'));
                }

                $merchantService->updateReward(
                    $rewardEntity,
                    $rewardForm->get('name')->getValue(),
                    $rewardForm->get('points')->getValue()
                );

                $return['successUpdate'] = true;
            }

        } catch (\Exception $e) {
            $return['badTry'] = true;
            $return['errorMessage'] = $e->getMessage();
        }

        $return['rewardEntity'] = $rewardEntity;
        $return['redeemedCount'] = $merchantService->getCountOfRedeems($rewardEntity);
        $return['changedHistory'] = $rewardEntity->getChangesHistory();
        $return['lastRedeems'] = $merchantService->getLastRedeemsForReward($rewardEntity);

        return new ViewModel($return);
    }

    public function clientAction()
    {
        $customerRelationEntity = $this->getCustomerRelationById();
        if (!$customerRelationEntity) {
            return $this->redirect()->toRoute(
                'business', array(
                    'action' => 'clients'
                )
            );
        }

        /** @var \Application\Service\Merchant $merchantService */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');

        $return = [];
        $return['customerRelationEntity'] = $customerRelationEntity;
        $return['lastVisits'] = $merchantService->getLastVisits(
            $this->getOwner()->getFkMerchantBranch(),
            $customerRelationEntity->getFkCustomer()
        );
        $return['lastRedeems'] = $merchantService->getLastRedeems(
            $this->getOwner()->getFkMerchantBranch(),
            $customerRelationEntity->getFkCustomer()
        );

        return new ViewModel($return);
    }

    public function searchClientAction()
    {
        $return = [];
        $searchFor = $this->params()->fromQuery('term', null);
        if (!$searchFor) {
            return new JsonModel($return);
        }

        /** @var \Application\Service\Customer $customerService */
        $customerService = $this->getServiceLocator()->get('Application\Service\Customer');
        $return = $customerService->searchBy($searchFor, $this->getOwner()->getFkMerchantBranch());

        return new JsonModel($return);
    }

    public function clientsAction()
    {
        /** @var \Application\Service\Merchant $merchantService */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');

        /** @var \Application\Service\Statistics $statisticsService */
        $statisticsService = $this->getServiceLocator()->get('Application\Service\Statistics');

        $return = [];

        $return['newAndReturned'] = $statisticsService->getNewAndReturned(
            $this->getOwner()->getFkMerchantBranch(),
            $days = 20
        );
        $return['lastVisitors'] = $merchantService->getLastVisitors(
            $this->getOwner()->getFkMerchantBranch()
        );
        $return['lastRedeems'] = $merchantService->getLastRedeemsForMerchantBranch(
            $this->getOwner()->getFkMerchantBranch()
        );

        return new ViewModel($return);
    }


    public function companyAction()
    {
        $merchantBranch = $this->owner->getFkMerchantBranch();
        $merchant = $this->owner->getFkMerchantBranch()->getFkMerchant();

        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['errorMessage'] = null;

        $viewModel['successUpdate'] = false;

        /** @var \Application\Service\Owner $ownerService */
        $ownerService = $this->getServiceLocator()->get('Application\Service\Owner');

        try {

            /** @var \Zend\Stdlib\RequestInterface $request */
            $request = $this->getRequest();

            if ($request->isPost()) {

                $data = $request->getPost();
                if (!$data) {
                    throw new \Exception($this->getTranslator()->translate('Ошибка при вводе.'));
                }

                $merchant->setFacebook($data['facebook']);
                $merchant->setTwitter($data['twitter']);
                $merchant->setYelpId($data['yelpId']);
                $merchant->setWebsite($data['website']);

                $merchantBranch->setPhoneNumber($data['phoneNumber']);
                $merchantBranch->setEMail($data['email']);

                /** @var \Doctrine\ORM\EntityManager $entityManager */
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($merchantBranch);
                $entityManager->persist($merchant);
                $entityManager->flush();

                $ownerService->save($this->owner->getFkMerchantBranch());

                $viewModel['successUpdate'] = true;
            }

        } catch (\Exception $e) {
            $viewModel['badTry'] = true;
            $viewModel['errorMessage'] = $e->getMessage();
        }

        $viewModel['merchantBranch'] = $merchantBranch;

        return new ViewModel($viewModel);
    }

    public function settingsAction()
    {
        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['errorMessage'] = null;
        $viewModel['owner'] = $this->owner;
        $viewModel['successUpdate'] = false;

        /** @var \Application\Service\Owner $ownerService */
        $ownerService = $this->getServiceLocator()->get('Application\Service\Owner');

        try {

            /** @var \Zend\Stdlib\RequestInterface $request */
            $request = $this->getRequest();

            if ($request->isPost()) {

                $data = $request->getPost();
                if (!$data) {
                    throw new \Exception($this->getTranslator()->translate('Ошибка при вводе.'));
                }

                if (!empty($data['day']) && !empty($data['month']) && !empty($data['year'])) {
                    $data['birthday'] = sprintf(
                        '%d-%d-%d',
                        $data['year'],
                        $data['month'],
                        $data['day']
                    );
                }

                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $settingsForm = new SettingsForm($entityManager);
                $settingsForm->bind($this->owner);
                $settingsForm->setData($data);

                if (!$settingsForm->isValid()) {
                    throw new \Exception($this->getTranslator()->translate('Переданы невалидные параметры.'));
                }
                if (!empty($settingsForm->get('password-new')->getValue())) {
                    if ($settingsForm->get('password-new')->getValue() != $settingsForm->get('password-rep')->getValue()) {
                        throw new \Exception($this->getTranslator()->translate('Будте внимательны при вводе пароля.'));
                    }
                    if ($ownerService->generatePassword($settingsForm->get('password-old')->getValue()) != $this->customer->getPassword()) {
                        throw new \Exception($this->getTranslator()->translate('Текуший пароль указан с ошибкой.'));
                    }
                    $this->owner->setPassword(
                        $ownerService->generatePassword(
                            $settingsForm->get('password-new')->getValue()
                        )
                    );
                }

                $ownerService->save($this->owner);

                $viewModel['successUpdate'] = true;
                $viewModel['owner'] = $this->owner;
            }

        } catch (\Exception $e) {
            $viewModel['badTry'] = true;
            $viewModel['errorMessage'] = $e->getMessage();
        }

        return new ViewModel($viewModel);
    }

    public function loginAction()
    {
        $authService = $this->getAuthService();
        if ($authService->hasIdentity()) {
            return $this->redirect()->toRoute(
                'business', array(
                    'action' => 'dashboard'
                )
            );
        }

        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['errorMessage'] = null;

        try {

            $request = $this->getRequest();
            if ($request->isPost()) {

                $data = $request->getPost();
                if (!$data) {
                    throw new \Exception($this->getTranslator()->translate('Ошибка при вводе.'));
                }

                $loginForm = new LoginForm();
                /** @var \Zend\Stdlib\RequestInterface $request */

                $loginForm->setData($data);
                if (!$loginForm->isValid()) {
                    throw new \Exception($this->getTranslator()->translate('Переданы невалидные параметры.'));
                }

                $loginData = $loginForm->getData();
                $identity = $this->auth($loginData['eMail'], $loginData['password'], $authService);
                if (!$identity) {
                    throw new \Exception($this->getTranslator()->translate('Email или пароль неправельный.'));
                }

                $this->session->userType = self::USER_TYPE_OWNER;
                return $this->redirect()->toRoute(
                    'business', array(
                        'action' => 'dashboard'
                    )
                );
            }

        } catch (\Exception $e) {
            $viewModel['badTry'] = true;
            $viewModel['errorMessage'] = $e->getMessage();
        }

        return new ViewModel($viewModel);
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getAuthService()
    {
        /** @var \Zend\Authentication\AuthenticationService $authService */
        return $this->serviceLocator->get('Zend\Authentication\BusinessService');
    }

    public function logoutAction()
    {
        $this->userType = $this->session->userType = self::USER_TYPE_GUEST;
        $authService = $this->getAuthService();
        $authService->clearIdentity();
        return $this->redirect()->toRoute('home');
    }

    /**
     * @return \Application\Model\Entity\Owner
     */
    protected function getOwner()
    {
        if ($this->owner) {
            return $this->owner;
        }

        $authService = $this->getAuthService();
        if (!$authService->hasIdentity()) {
            return false;
        }

        $this->owner = $authService->getIdentity();
        return $this->owner;
    }
}
