<?php

namespace Application\Controller;

use Zend\View\Model\JsonModel;

class AjaxController extends AbstractApplicationController
{
    public function feedbackAction()
    {
        $viewModel = array();
        $viewModel['errorMessage'] = null;
        $viewModel['successUpdate'] = false;

        try {
            /** @var \Zend\Stdlib\RequestInterface $request */
            $request = $this->getRequest();
            if (!$request->isPost()) {
                throw new \Exception($this->getTranslator()->translate('Ошибка при вводе.'));
            }

            $data = $request->getPost();
            if (!$data) {
                throw new \Exception($this->getTranslator()->translate('Ошибка при вводе.'));
            }

            /** @var \Application\Service\Email $emailService  */
            $emailService = $this->getServiceLocator()->get('Application\Service\Email');
            $emailService->sendFeedbackMessage($data);

            $viewModel['successUpdate'] = true;

        } catch (\Exception $e) {
            $viewModel['errorMessage'] = $e->getMessage();
        }

        return new JsonModel($viewModel);
    }
}
