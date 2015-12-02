<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Category as CategoryEntity;
use Zend\Feed\Exception\RuntimeException;
use DoctrineModule\Authentication\Adapter\ObjectRepository as BaseObjectRepository;
use Zend\Authentication\Result as AuthenticationResult;

class Owner extends EntityRepository
{

}