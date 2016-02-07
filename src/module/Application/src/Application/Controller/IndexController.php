<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractApplicationController
{
    public function changeLanguageAction()
    {
        // New Container will get he Language Session if the SessionManager already knows the language session.
        $config = $this->serviceLocator->get('config');

        $session = new Container('language');
        $language = $this->params()->fromRoute('language', $config['locale']['default']);

        if (array_key_exists($language, $config['locale']['available'])) {
            $locale = $config['locale']['available'][$language];
            $session->language = $locale;
            $this->serviceLocator->get('translator')->setLocale($session->language);
        }

        return $this->redirect()->toUrl($this->getRequest()->getHeader('Referer')->uri()->getPath());
    }

    public function indexAction()
    {
        // var_dump(md5('support@lekkerrewards.nl' . \Application\Service\Email::UN_SUBSCRIBE_HASH_WORD)); exit();

        $isSubscribed = false;

        $unSubscribeHash = $this->params()->fromQuery('unsubscribe', '');
        if ($unSubscribeHash) {

            /** @var \Application\Service\Customer $customerService */
            $customerService = $this->getServiceLocator()->get('Application\Service\Customer');
            $isSubscribed = $customerService->unSubscribeByUnSubscribeHash($unSubscribeHash);
        }

        return new ViewModel(array(
            'isSubscribed' => $isSubscribed
        ));
    }

    public function supportAction()
    {
        return new ViewModel(array());
    }

    public function privacyPolicyAction()
    {
        return new ViewModel(array());
    }

    public function termsOfUseAction()
    {
        return new ViewModel(array());
    }

    public function aboutAction()
    {
        return new ViewModel(array());
    }

    public function locationsAction()
    {
        $return = [];

        /**
         * @var \Application\Service\Locations $locationsService
         */
        $locationsService = $this->getServiceLocator()->get('Application\Service\Locations');
        $return['merchants'] = $locationsService->getAllMerchantBranches();

        return new ViewModel($return);
    }

    /*

    public function listOfUsersAction()
    {
        $users = $this->getObjectManager()->getRepository('\Application\Entity\User')->findAll();

        return new ViewModel(array('users' => $users));
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $user = new User();
            $user->setFullName($this->getRequest()->getPost('fullname'));

            $this->getObjectManager()->persist($user);
            $this->getObjectManager()->flush();
            $newId = $user->getId();

            return $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $user = $this->getObjectManager()->find('\Application\Entity\User', $id);

        if ($this->request->isPost()) {
            $user->setFullName($this->getRequest()->getPost('fullname'));

            $this->getObjectManager()->persist($user);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('home');
        }

        return new ViewModel(array('user' => $user));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $user = $this->getObjectManager()->find('\Application\Entity\User', $id);

        if ($this->request->isPost()) {
            $this->getObjectManager()->remove($user);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('home');
        }

        return new ViewModel(array('user' => $user));
    }

    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->_objectManager;
    }*/
}
