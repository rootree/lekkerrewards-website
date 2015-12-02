<?php

namespace Application\Service;

use Application\Model\Repository\Customer as CustomerRepository;
use Doctrine\ORM\EntityManager;
use Application\Service\Email as EmailService;
use Application\Service\Qr as QrService;
use Application\Model\Entity\Customer as CustomerEntity;
use Application\Model\Entity\Qr as QrEntity;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Application\Model\Entity\Merchant as MerchantEntity;

class API
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $apiKey
     * @return null|\Application\Model\Entity\MerchantBranch
     */
    public function getMerchantBranchByAPIKey($apiKey)
    {
        $merchantBranchRepository = $this->entityManager->getRepository('Application\Model\Entity\MerchantBranch');
        return $merchantBranchRepository->findOneBy(
            [
                'apiKey' => $apiKey,
            ]
        );
    }
}