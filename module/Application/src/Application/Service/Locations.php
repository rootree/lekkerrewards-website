<?php
namespace Application\Service;

use Application\Model\Repository\MerchantBranch as MerchantBranchRepository;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Doctrine\ORM\EntityManager;

class Locations
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MerchantBranchRepository
     */
    private $merchantBranchRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->merchantBranchRepository = $entityManager->getRepository(
            'Application\Model\Entity\MerchantBranch'
        );
    }

    /**
     * @return MerchantBranchEntity[]
     */
    public function getAllMerchantBranches()
    {
        return $this->merchantBranchRepository->getAllMerchantBranches();
    }

    /**
     * @return MerchantBranchEntity
     */
    public function findMerchantBranch($permalinkPath)
    {
        return $this->merchantBranchRepository->findByPermalinkPath($permalinkPath);
    }
}