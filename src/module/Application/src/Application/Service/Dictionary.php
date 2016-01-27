<?php
namespace Application\Service;

use Application\Model\Repository\MerchantBranch as MerchantBranchRepository;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Application\Model\Entity\Category as CategoryEntity;
use Application\Model\Entity\City as CityEntity;
use Application\Model\Entity\Country as CountryEntity;
use Application\Model\Entity\State as StateEntity;
use Doctrine\ORM\EntityManager;

class Dictionary
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
     * @return CountryEntity[]
     */
    public function getAllCountries()
    {
        $countryRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Country'
        );

        return $countryRepository->findAll();
    }

    /**
     * @return CategoryEntity[]
     */
    public function getAllCategories()
    {
        $categoriesRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Category'
        );

        return $categoriesRepository->findAll();
    }

    /**
     * @return StateEntity[]
     */
    public function getAllStates()
    {
        $statesRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\State'
        );

        return $statesRepository->findAll();
    }

    /**
     * @return CityEntity[]
     */
    public function getAllCities()
    {
        $citiesRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\City'
        );

        return $citiesRepository->findAll();
    }
}