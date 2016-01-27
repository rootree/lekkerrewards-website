<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception\DomainException;
use Zend\View\Model\ViewModel;
use Zend\Session\Container; // We need this when using sessions

abstract class AbstractApplicationController extends AbstractActionController
{
    const USER_TYPE_GUEST = 1;
    const USER_TYPE_CUSTOMER = 2;
    const USER_TYPE_OWNER = 4;

    protected $userType = self::USER_TYPE_GUEST;

    protected $session;

    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();
        $viewModel->userType = $this->userType;

        $routeMatch = $event->getRouteMatch();
        if (!$routeMatch) {
            throw new DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $currentController = $routeMatch->getParam('controller').'Controller';

        $viewModel->selectedController = $currentController;
        $viewModel->selectedAction = $action;

        $method = static::getMethodFromAction($action);
        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }
        try {
            $this->session = new Container('lekker');
            if (!empty($this->session->userType)) {
                $this->userType = $viewModel->userType = $this->session->userType;
            }
            if (!empty($this->session->qrCode)) {
                $viewModel->qrCode = $this->session->qrCode;
            }
            $actionResponse = $this->$method();
        } catch (\Exception $e) {

            $sm = $event->getApplication()->getServiceManager();
            $service = $sm->get('ApplicationServiceErrorHandling');
            $service->logException($e);
            $this->getResponse()->setStatusCode(500);
            $actionResponse = new ViewModel([
                'success' => false
            ]);
        }


        $event->setResult($actionResponse);
        return $actionResponse;
    }

    protected $translator;

    public function getTranslator()
    {
        if (!$this->translator)
        {
            $sm = $this->getServiceLocator();
            $this->translator = $sm->get('translator');
        }
        return $this->translator;
    }


    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getCustomerAuthService()
    {
        /** @var \Zend\Authentication\AuthenticationService $authService  */
        return $this->serviceLocator->get('Zend\Authentication\CustomerService');
    }
}
