<?php
namespace Application\Controller;

use Zend\Authentication\Adapter\DbTable,
    Zend\Session\Container as SessionContainer;

abstract class AbstractAuthController extends AbstractApplicationController
{
    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    abstract protected function getAuthService();

    protected function auth($email, $password, \Zend\Authentication\AuthenticationService $authService)
    {
        $adapter = $authService->getAdapter();

        $adapter->setIdentityValue($email);
        $adapter->setCredentialValue($password);

        /** @var \Zend\Authentication\Result $authResult  */
        $authResult = $authService->authenticate();
        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();
            $authService->getStorage()->write($identity);
            return $identity;
        } else {
            return false;
        }
    }
}