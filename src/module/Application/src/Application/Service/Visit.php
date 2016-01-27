<?php

namespace Application\Service;

use Application\Model\Repository\Qr as QrRepository;
use Doctrine\ORM\EntityManager;
use Application\Model\Entity\Qr as QrEntity;
use Application\Model\Entity\Visit as VisitEntity;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Application\Model\Entity\Customer as CustomerEntity;
use Application\Model\Entity\MerchantsCustomers as MerchantCustomerEntity;
use Endroid\QrCode\QrCode;

class Visit
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var QrRepository
     */
    private $visitRepository;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->visitRepository = $entityManager->getRepository('Application\Model\Entity\Visit');
    }

    /**
     * @param MerchantCustomerEntity $merchantCustomerRelation
     * @param $pointsPerVisit
     * @param \DateTime $dateTime
     */
    public function newVisit($merchantCustomerRelation, $pointsPerVisit, $dateTime)
    {
        $visitEntity = new VisitEntity();

        $visitEntity->setCreatedAt($dateTime);
        $visitEntity->setUpdatedAt($dateTime);
        $visitEntity->setFkCustomer($merchantCustomerRelation->getFkCustomer());
        $visitEntity->setFkMerchantBranch($merchantCustomerRelation->getFkMerchantBranch());
        $visitEntity->setFkMerchant($merchantCustomerRelation->getFkMerchant());
        $visitEntity->setStatus(VisitEntity::STATUS_OK);
        $visitEntity->setObtainedPoints($pointsPerVisit);

        $this->entityManager->persist($visitEntity);

        $merchantCustomerRelation->setPoints(
            $merchantCustomerRelation->getPoints() + $visitEntity->getObtainedPoints()
        );
        $merchantCustomerRelation->setVisits($merchantCustomerRelation->getVisits() + 1);
        $merchantCustomerRelation->setUpdatedAt($dateTime);

        $this->entityManager->persist($merchantCustomerRelation);

        $this->entityManager->flush();
    }
}