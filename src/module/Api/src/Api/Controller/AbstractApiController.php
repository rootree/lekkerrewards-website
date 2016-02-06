<?php

namespace Api\Controller;

use Zend\Http\Request;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception\DomainException;
use Api\Model\Exception as ApiException;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;

class AbstractApiController extends AbstractActionController
{
    /**
     * @var \Application\Model\Entity\MerchantBranch
     */
    protected $merchantBranchEntity;

    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {
        try {

            $routeMatch = $event->getRouteMatch();
            if (!$routeMatch) {
                throw new DomainException('Missing route matches; unsure how to retrieve action');
            }

            $action = $routeMatch->getParam('action', 'not-found');
            $method = static::getMethodFromAction($action);

            if (!method_exists($this, $method)) {
                $method = 'notFoundAction';
            }

            $apiKey = $this->params()->fromRoute('api-key');

            /** @var \Application\Service\API $apiService */
            $apiService = $this->serviceLocator->get('Application\Service\API');
            $this->merchantBranchEntity = $apiService->getMerchantBranchByAPIKey($apiKey);

            if (!$this->merchantBranchEntity) {
                $method = 'authRequiredAction';
            }

            $actionResponse = $this->$method();

        } catch (ApiException $e) {

            // $this->getResponse()->setStatusCode(400);
            $actionResponse = new JsonModel([
                'success' => false,
                'code'    => $e->getCode(),
                'message'    => (array_key_exists($e->getCode(), ApiException::$messages)
                    ? ApiException::$messages[$e->getCode()]
                    : ''
                )
            ]);

        } catch (\Exception $e) {

            $sm = $event->getApplication()->getServiceManager();
            $service = $sm->get('ApplicationServiceErrorHandling');
            $service->logException($e);

            $this->getResponse()->setStatusCode(500);
            $actionResponse = new JsonModel([
                'message' => $e->getMessage(),
                'success' => false,
                'code'    => ApiException::COMMON_INTERNAL_ERROR
            ]);
        }

        $event->setResult($actionResponse);

        $body = $this->getRequest()->getContent();
        $url = $_SERVER['REMOTE_ADDR'];
        //$url = $actionResponse;

        /** @var \Application\Service\ErrorHandling $service */
        $service = $this->serviceLocator->get('ApplicationServiceErrorHandling');
        $service->logData($url ."\n". $body ."\n". $actionResponse->serialize());

        return $actionResponse;
    }

    public function notFoundAction()
    {
        $this->getResponse()->setStatusCode(404);
        return new JsonModel([
            'success' => false,
            'code'    => ApiException::COMMON_ROUTE_NOT_FOUND
        ]);
    }

    public function authRequiredAction()
    {
        $this->getResponse()->setStatusCode(403);
        return new JsonModel([
            'success' => false,
            'code'    => ApiException::COMMON_WRONG_API_KEY
        ]);
    }

    /**
     * @param \Zend\Http\Request $request
     *
     * @return array
     */
    protected function getPostParams($request)
    {
        $body = $request->getContent();
                return json_decode($body);
    }

    protected function getDiffInHours($interval)
    {
        // Day
        $total = $interval->format('%a');
        //hour
        $total = ($total * 24) + ($interval->h );
        return $total;
    }
}