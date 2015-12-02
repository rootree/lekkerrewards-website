<?php

namespace Api\Controller;

use Zend\Authentication\Adapter\DbTable,
    Zend\Session\Container as SessionContainer,
    Zend\View\Model\JsonModel ;
use Api\Model\Exception as ApiException;
use Application\Model\Entity\Customer as CustomerEntity;
use Application\Model\Entity\Qr as QrEntity;

class ApiController extends AbstractApiController
{
    public function checkInByQrAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
            $jm = new \JsonMapper();
            $jm->bExceptionOnMissingData = true;

            /** @var \Api\Model\Request\CheckInByQr $checkInByQr */
            $checkInByQr = $jm->map($data, new \Api\Model\Request\CheckInByQr());

            /** @var \Application\Service\Qr $qrService */
            $qrService = $this->serviceLocator->get('Application\Service\Qr');
            $qrEntity = $qrService->getQrByCode($checkInByQr->qr);

            if (!$qrEntity) {
                throw new ApiException(null, ApiException::QR_NOT_FOUND);
            }
            if (!$qrEntity->getFkCustomer()) {
                throw new ApiException(null, ApiException::QR_NOT_REGISTRATED);
            }

            $qrService->lastUpdate($qrEntity);

            $checkingResult =  $this->makeCheckIn(
                $qrEntity->getFkCustomer(),
                $this->handlerTimeStamp($checkInByQr->timestamp)
            );

            /** @var \Application\Service\Email $emailService */
            $emailService = $this->serviceLocator->get('Application\Service\Email');
            $emailService->sendCheckInMessage(
                $checkingResult['summery']['isFirstTime'],
                $this->merchantBranchEntity,
                $checkingResult['summery']['points'],
                $qrEntity->getFkCustomer()
            );

            return new JsonModel($checkingResult);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function checkInByEmailAction()
    {

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $jm = new \JsonMapper();
            $jm->bExceptionOnMissingData = true;

            /** @var \Api\Model\Request\CheckInByEmail $checkInByEmail */
            $checkInByEmail = $jm->map($data, new \Api\Model\Request\CheckInByEmail());

            $validator = new \Zend\Validator\EmailAddress();
            if (!$validator->isValid($checkInByEmail->email)) {
                throw new ApiException(null, ApiException::CUSTOMER_INVALID_EMAIL);
            }

            /** @var \Application\Service\Customer $customerService */
            $customerService = $this->serviceLocator->get('Application\Service\Customer');
            $customerEntity = $customerService->searchByEmail($checkInByEmail->email);

            if (!$customerEntity) {
                throw new ApiException(null, ApiException::CUSTOMER_NOT_FOUND);
            }

            $checkingResult = $this->makeCheckIn(
                $customerEntity,
                $this->handlerTimeStamp($checkInByEmail->timestamp)
            );

            /** @var \Application\Service\Email $emailService */
            $emailService = $this->serviceLocator->get('Application\Service\Email');
            $emailService->sendCheckInMessage(
                $checkingResult['summery']['isFirstTime'],
                $this->merchantBranchEntity,
                $checkingResult['summery']['points'],
                $customerEntity
            );

            return new JsonModel($checkingResult);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    private function handlerTimeStamp($timestamp)
    {
        $config = $this->getServiceLocator()->get('config');
        $timeZone = new \DateTimeZone($config['company']['timezone']);

        $date = new \DateTime();
        $date->setTimestamp(substr($timestamp, 0, -3));
        $date->setTimezone($timeZone);
//var_export($date); exit();
        return $date;
    }

    /**
     * @param $customerEntity
     * @param \DateTime $dateTime
     * @return array
     * @throws ApiException
     */
    private function makeCheckIn($customerEntity, $dateTime)
    {
        /**
         * @var \Application\Service\Merchant $merchantService
         */
        $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
        $merchantCustomerRelation = $merchantService->getMerchantCustomerRelation(
            $this->merchantBranchEntity,
            $customerEntity
        );

        $config = $this->getServiceLocator()->get('config');
        $isFirstTime = 0;

        if (!$merchantCustomerRelation) {

            $merchantCustomerRelation = $merchantService->createMerchantCustomerRelation(
                $this->merchantBranchEntity,
                $customerEntity,
                $dateTime
            );
            $isFirstTime = 1;

        } else {

            $date1 = $merchantCustomerRelation->getUpdatedAt();
            $date2 = $dateTime;
            $diff = $date2->diff($date1);
            $hours = $this->getDiffInHours($diff);

            if ($hours < $config['logic']['coolDown']) {
                throw new ApiException(null, ApiException::QR_COOL_DOWN);
            }
        }

        /** @var \Application\Service\Visit $visitService */
        $visitService = $this->serviceLocator->get('Application\Service\Visit');
        $visitService->newVisit(
            $merchantCustomerRelation,
            $isFirstTime ? $config['logic']['pointsForFirstVisit'] : $config['logic']['pointsPerVisit'],
            $dateTime
        );

        return [
            'success' => true,
            'summery' => [
                'isFirstTime' => $isFirstTime,
                'points' => $merchantCustomerRelation->getPoints(),
                'visits' => $merchantCustomerRelation->getVisits(),
                'redeems' => $merchantCustomerRelation->getRedeems(),
            ],
        ];
    }

    public function registrationAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $jm = new \JsonMapper();
            $jm->bExceptionOnMissingData = true;
            /** @var \Api\Model\Request\Registration $registrationRequest */
            $registrationRequest = $jm->map($data, new \Api\Model\Request\Registration());
            //$dateTime = $this->handlerTimeStamp($registrationRequest->timestamp); // @todo
            $qrEntity = $this->getQrEntity($registrationRequest);
            $customerEntity = $this->getCustomerByEmail($registrationRequest);

            $dateTime = $this->handlerTimeStamp($registrationRequest->timestamp);

            $openPassword = null;
            if (!$customerEntity) {
                list($customerEntity, $openPassword) = $this->createNewCustomer(
                    $qrEntity,
                    $registrationRequest->getEmail(),
                    $dateTime
                );
            } else {
                $qrActivatedEntity = $customerEntity->getActiveQRCode();

                $config = $this->getServiceLocator()->get('config');

                $date2 = clone $dateTime;
                $diff = $date2->diff($qrActivatedEntity->getUpdatedAt());
                $hours = $this->getDiffInHours($diff);

                if (
                    $qrEntity->getCode() != $qrActivatedEntity->getCode() &&
                    $qrActivatedEntity->getSource() == QrEntity::SOURCE_CARD &&
                    $hours <= $config['logic']['canTakeNewCardAfter']
                ) {
                    throw new ApiException(null, ApiException::QR_EXISTS_ACTIVATED);
                }
            }
            $this->connectQrWithCustomer($customerEntity, $qrEntity);

            $checkingResult = $this->makeCheckIn(
                $customerEntity,
                $dateTime
            );

            /** @var \Application\Service\Email $emailService */
            $emailService = $this->serviceLocator->get('Application\Service\Email');
            $emailService->sendGreetingMessage(
                $customerEntity,
                $this->merchantBranchEntity,
                $openPassword
            );

            return new JsonModel($checkingResult);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    /**
     * @param \Api\Model\Request\Registration $registrationRequest
     * @return QrEntity|null
     * @throws ApiException
     */
    private function getQrEntity($registrationRequest)
    {
        /** @var \Application\Service\Qr $qrService */
        $qrService = $this->serviceLocator->get('Application\Service\Qr');
        $qrEntity = $qrService->getQrByCode($registrationRequest->getQr());
        if (!$qrEntity) {
            throw new ApiException(null, ApiException::QR_NOT_FOUND);
        }
        if ($qrEntity->getFkCustomer()) {
            throw new ApiException(null, ApiException::QR_REGISTERED);
        }
        return $qrEntity;
    }

    /**
     * @param \Api\Model\Request\Registration $registrationRequest
     * @return CustomerEntity|null
     * @throws ApiException
     */
    private function getCustomerByEmail($registrationRequest)
    {
        $validator = new \Zend\Validator\EmailAddress();
        if (!$validator->isValid($registrationRequest->getEmail())) {
            throw new ApiException(null, ApiException::CUSTOMER_INVALID_EMAIL);
        }

        /** @var \Application\Service\Customer $customerService */
        $customerService = $this->serviceLocator->get('Application\Service\Customer');
        return $customerService->searchByEmail($registrationRequest->getEmail());
    }

    /**
     * @param CustomerEntity $customerEntity
     * @param QrEntity $qrEntity
     */
    private function connectQrWithCustomer($customerEntity, $qrEntity)
    {
        /** @var \Application\Service\Customer $customerService */
        $customerService = $this->serviceLocator->get('Application\Service\Customer');
        $customerService->connectQrWithCustomer($customerEntity, $qrEntity);
    }

    /**
     * @param $qrEntity
     * @param $email
     * @param \DateTime $dateTime
     * @return CustomerEntity
     */
    private function createNewCustomer($qrEntity, $email, $dateTime)
    {
        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

        /** @var \Application\Service\Customer $customerService */
        $customerService = $this->serviceLocator->get('Application\Service\Customer');

        $passwordOpened = $generator->generateString(8);
        $customerEntity = new CustomerEntity();

        $customerEntity->setCreatedAt($dateTime);
        $customerEntity->setUpdatedAt($dateTime);
        $customerEntity->setPassword($passwordOpened);
        $customerEntity->setEMail($email);
        $customerEntity->setName(substr($email, 0, strpos($email, '@')));

        $customerService->createCustomer($customerEntity, $qrEntity);
        return [$customerEntity, $passwordOpened];
    }

    public function syncAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
            $jm = new \JsonMapper();
            $jm->bExceptionOnMissingData = true;

            /** @var \Api\Model\Request\Sync $redeem */
            $redeemRequest = $jm->map($data, new \Api\Model\Request\Sync());
            $dateTime = $this->handlerTimeStamp($redeemRequest->timestamp);

            /**
             * @var \Application\Service\Merchant $merchantService
             */
            $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
            list(
                $rewardsForUpdateResult,
                $rewardsForDeleteResult
            ) = $merchantService->getUpdatedRewards(
                $this->merchantBranchEntity,
                $dateTime
            );
            $rewardsHistory = $merchantService->getRewardsHistory(
                $this->merchantBranchEntity,
                $dateTime
            );

            return new JsonModel([
                'success' => true,
                'isUpdated' => ($rewardsForUpdateResult || $rewardsForDeleteResult || $rewardsHistory),
                'rewardsForDelete' => $rewardsForDeleteResult,
                'rewardsForUpdate' => $rewardsForUpdateResult,
                'rewardsHistory' => $rewardsHistory,
                'dateTime' => $redeemRequest->timestamp
            ]);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function redeemAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
            $jm = new \JsonMapper();
            $jm->bExceptionOnMissingData = true;

            /** @var \Api\Model\Request\Redeem $redeem */
            $redeemRequest = $jm->map($data, new \Api\Model\Request\Redeem());

            /** @var \Application\Service\Customer $customerService */
            $customerService = $this->serviceLocator->get('Application\Service\Customer');
            $customerEntity = $customerService->searchByEmail($redeemRequest->email);

            if (!$customerEntity) {
                throw new ApiException(null, ApiException::CUSTOMER_NOT_FOUND);
            }

            /**
             * @var \Application\Service\Merchant $merchantService
             */
            $merchantService = $this->getServiceLocator()->get('Application\Service\Merchant');
            $merchantCustomerRelation = $merchantService->getMerchantCustomerRelation(
                $this->merchantBranchEntity,
                $customerEntity
            );

            if (!$merchantCustomerRelation) {
                throw new ApiException(null, ApiException::CUSTOMER_NOT_CONNECTED_TO_MERCHANT);
            }

            $rewardHistory = $merchantService->getRewardByCode($redeemRequest->code);
            if (!$rewardHistory) {
                throw new ApiException(null, ApiException::CUSTOMER_REWARD_NOT_FOUND);
            }

            $dateTime = $this->handlerTimeStamp($redeemRequest->timestamp);
            $redeemResult = $merchantService->redeem($merchantCustomerRelation, $rewardHistory, $dateTime);

            if (!$redeemResult) {
                throw new ApiException(null, ApiException::CUSTOMER_NOT_POINTS);
            }

            /** @var \Application\Service\Email $emailService */
            $emailService = $this->serviceLocator->get('Application\Service\Email');
            $emailService->sendRedeemMessage(
                $customerEntity,
                $this->merchantBranchEntity,
                $rewardHistory
            );

            return new JsonModel([
                'success' => $redeemResult
            ]);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }
}