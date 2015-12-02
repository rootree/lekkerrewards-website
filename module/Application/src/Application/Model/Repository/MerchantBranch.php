<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Category as CategoryEntity;
use Zend\Feed\Exception\RuntimeException;

class MerchantBranch extends EntityRepository
{
    /**
     * @return \Application\Model\Entity\MerchantBranch[]
     */
    public function getAllMerchantBranches()
    {
        return $this->findAll();
    }

    /**
     * @return \Application\Model\Entity\MerchantBranch
     */
    public function findByPermalinkPath($permalinkPath)
    {
        return $this->findOneBy([
            'permalinkPath' => $permalinkPath
        ]);
    }
}