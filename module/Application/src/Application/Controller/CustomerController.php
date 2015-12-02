<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable,
    Zend\Session\Container as SessionContainer ;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Application\Model\Entity\Customer as CustomerEntity;
use Application\Model\Entity\Qr as QrEntity;
use Application\Form\Login as LoginForm;
use Application\Form\Settings as SettingsForm;
use Zend\XmlRpc\Value\DateTime;

class CustomerController extends AbstractAuthController
{
    /**
     * @var int
     */
    private $unAuthorized = 0;

    /**
     * @var CustomerEntity
     */
    private $customer;

    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->customer = $this->getCustomer();

        $routeMatch = $event->getRouteMatch();
        $currentAction = $routeMatch->getParam('action');

        if (!$this->customer && !in_array($currentAction, ['login', 'sign-up'])) {
            $this->unAuthorized = 1;
            return $this->redirect()->toRoute('customer', array(
                'action' => 'login',
                'unAuthorized' => 1
            ));
        } else {
            if (!in_array($currentAction, ['login', 'sign-up'])) {
                $this->userType = self::USER_TYPE_CUSTOMER;

                $viewModel = $event->getViewModel();
                $viewModel->customer = [
                    'name' => $this->customer->getName(),
                    'eMail' => $this->customer->getEMail(),
                ];
            }
            parent::onDispatch($event);
        }
    }

    public function indexAction()
    {
        $return = [];
        /** @var \Application\Service\Customer $customerService  */
        $customerService = $this->getServiceLocator()->get('Application\Service\Customer');

        $return['lastVisits'] = $customerService->getLastVisits($this->customer);
        $return['lastRedeems'] = $customerService->getLastRedeems($this->customer);
        $return['merchants'] = $customerService->merchants($this->customer);

        return new ViewModel($return);
    }

    public function visitsAction()
    {
        return new ViewModel(array());
    }

    public function activityAction()
    {
        $merchantBranchPath = $this->params()->fromRoute('merchant-name', '');

        if (empty($merchantBranchPath)) {
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
        $merchantCustomerRelation = $merchantService->getMerchantCustomerRelation($merchantBranch, $this->customer);

        $redeemsList = null;
        $visitsList = null;

        if ($merchantCustomerRelation) {
            $redeemsList = $merchantService->getLastRedeems($merchantBranch, $this->customer);
            $visitsList = $merchantService->getLastVisits($merchantBranch, $this->customer);
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
            'merchantCustomerRelation' => $merchantCustomerRelation,
            'redeemsList' => $redeemsList,
            'visitsList' => $visitsList,
            'merchantURL' => $merchantURL,
            'merchantBranch' => $merchantBranch
        ]);
    }

    public function settingsAction()
    {
        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['errorMessage'] = null;
        $viewModel['customer'] = $this->customer;
        $viewModel['successUpdate'] = false;

        /** @var \Application\Service\Customer $customerService  */
        $customerService = $this->getServiceLocator()->get('Application\Service\Customer');

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
                $settingsForm->bind($this->customer);
                $settingsForm->setData($data);

                if (!$settingsForm->isValid()) {
                    throw new \Exception($this->getTranslator()->translate('Переданы невалидные параметры.'));
                }

                if (!empty($settingsForm->get('password-new')->getValue())) {
                    if ($settingsForm->get('password-new')->getValue() != $settingsForm->get('password-rep')->getValue()) {
                        throw new \Exception($this->getTranslator()->translate('Будте внимательны при вводе пароля.'));
                    }
                    if ($customerService->encryptPassword($settingsForm->get('password-old')->getValue()) != $this->customer->getPassword()) {
                        throw new \Exception($this->getTranslator()->translate('Текуший пароль указан с ошибкой.'));
                    }
                    $this->customer->setPassword(
                        $customerService->encryptPassword(
                            $settingsForm->get('password-new')->getValue()
                        )
                    );
                }

                $customerService->save($this->customer);

                $viewModel['successUpdate'] = true;
                $viewModel['customer'] = $this->customer;
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
            return $this->redirect()->toRoute('customer', array(
                'action' => 'index',
                'alreadyAuthorised' => 1
            ));
        }

        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['errorMessage'] = null;
        $viewModel['unauthorized'] = $this->unAuthorized;

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
                $identity =  $this->auth($loginData['eMail'], $loginData['password'], $authService);
                if (!$identity) {
                    throw new \Exception($this->getTranslator()->translate('Email или пароль неправельный.'));
                }

                $this->session->userType = self::USER_TYPE_CUSTOMER;
                /** @var CustomerEntity $identity */
                $this->session->qrCode = $identity->getActiveQRCode()->getCode();
                return $this->redirect()->toRoute('customer', array(
                    'action' => 'index'
                ));
            }

        } catch (\Exception $e) {
            $viewModel['badTry'] = true;
            $viewModel['errorMessage'] = $e->getMessage();
        }

        return new ViewModel($viewModel);
    }

    public function qrAction()
    {
        /** @var \Application\Service\Qr $qrService */
        $qrService = $this->serviceLocator->get('Application\Service\Qr');
        $currentQrCode = $this->customer->getActiveQRCode();

        if (array_key_exists('output', $_REQUEST)) {
            var_export($qrService->getHexCode($currentQrCode));
            exit();
            return false;
        }
        header ('Content-Type: image/png');
        $qrService->createQRCode(
            $qrService->getHexCode($currentQrCode)
        );

        return false;
    }

    public function logoutAction()
    {
        $this->userType = $this->session->userType = self::USER_TYPE_GUEST;
        $this->qrCode = $this->session->qrCode = null;
        $authService = $this->getAuthService();
        $authService->clearIdentity();
        return $this->redirect()->toRoute('home');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getAuthService()
    {
        return $this->getCustomerAuthService();
    }

    public function signUpAction()
    {
        if ($this->customer) {
            return $this->redirect()->toRoute('customer', array(
                'action' => 'index',
                'alreadyAuthorised' => 1
            ));
        }

        $viewModel = array();
        $viewModel['badTry'] = false;
        $viewModel['errorMessage'] = null;

        try {

            /** @var \Zend\Http\Request $request */
            $request = $this->getRequest();
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            if ($request->isPost()) {

                $entityManager->beginTransaction();

                $data = $request->getPost();
                if (!$data) {
                    throw new \Exception($this->getTranslator()->translate('Пустой запрос.'));
                }

                $customerForm = new \Application\Form\SignUp($entityManager);
                $customerEntity = new CustomerEntity();
                $customerForm->bind($customerEntity);
                $customerForm->setData($data);
                $customerEntity->setCreatedAt(new \DateTime());
                $customerEntity->setUpdatedAt(new \DateTime());
                if (!$customerForm->isValid()) {
                    throw new \Exception($this->getTranslator()->translate('Неправельные аргументы.'));
                }

                /** @var \Application\Service\Customer $customerService  */
                $customerService = $this->getServiceLocator()->get('Application\Service\Customer');
                $emailUserEntity = $customerService->getUserByEmail($customerEntity->getEmail());
                if ($emailUserEntity) {
                    throw new \Exception($this->getTranslator()->translate('Указанный email уже зарегестрирован.'));
                }

                if ($customerForm->get('password')->getValue() != $customerForm->get('password-rep')->getValue()) {
                    throw new \Exception($this->getTranslator()->translate('Будте внимательны при вводе пароля.'));
                }

                try {

                    /** @var \Application\Service\Qr $qrService */
                    $qrService = $this->serviceLocator->get('Application\Service\Qr');
                    $qrEntity = $qrService->generateQr();

                    $newCustomerEntity = $customerService->createCustomer($customerForm->getData(), $qrEntity);

                    $authService = $this->getAuthService();
                    $result = $this->auth(
                        $newCustomerEntity->getEmail(),
                        $customerForm->get('password')->getValue(),
                        $authService
                    );
                    if (!$result) {
                        throw new \Exception($this->getTranslator()->translate('Проблема в сервисе авторизации.'));
                    }
                    $entityManager->commit();
                    $this->session->userType = self::USER_TYPE_CUSTOMER;
                    $this->session->qrCode = $qrEntity->getCode() ;


                    /** @var \Application\Service\Email $emailService */
                    $emailService = $this->serviceLocator->get('Application\Service\Email');
                    $emailService->sendRegistrationMessage(
                        $customerEntity
                    );

                    return $this->redirect()->toRoute('customer', array(
                        'action' => 'index'
                    ));

                } catch (\RuntimeException $e) {
                    throw new \Exception(sprintf($this->getTranslator()->translate('Внутрянная ошибка сервиса. (%s)'), $e->getMessage()));
                }

            }

        } catch (\Exception $e) {
            $entityManager->rollback();
            $viewModel['badTry'] = true;
            $viewModel['errorMessage'] = $e->getMessage();
        }

        return new ViewModel($viewModel);
    }

    /**
     * @return CustomerEntity
     */
    protected function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        /** @var \Application\Model\Entity\Customer $customerEntity  */
        $authService = $this->getAuthService();
        if (!$authService->hasIdentity()) {
            return false;
        }
        $this->customer = $authService->getIdentity();
        return $this->customer;
    }
}
