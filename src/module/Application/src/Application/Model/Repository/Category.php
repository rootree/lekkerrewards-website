<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Category as CategoryEntity;
use Zend\Feed\Exception\RuntimeException;

class Category extends EntityRepository
{
    /**
     * @param \Application\Model\Entity\Category $userEntity
     *
     * @return array
     */
    public function getDownloads($userEntity)
    {
        return $this->findBy([
            'fkUser' => $userEntity
        ]);
    }
}