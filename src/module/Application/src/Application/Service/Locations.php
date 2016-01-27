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
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('mb')
            ->from('\Application\Model\Entity\MerchantBranch', 'mb')
            ->join('mb.fkMerchant', 'm')
            ->where('m.isActive = 1')
            ->where('mb.isActive = 1');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return MerchantBranchEntity
     */
    public function findMerchantBranch($permalinkPath)
    {
        return $this->merchantBranchRepository->findByPermalinkPath($permalinkPath);
    }
}