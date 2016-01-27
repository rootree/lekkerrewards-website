<?php
namespace Application\Service;

use Application\Model\Repository\MerchantBranch as MerchantBranchRepository;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Application\Model\Entity\Merchant as MerchantEntity;
use Application\Model\Entity\Customer as CustomerEntity;
use Application\Model\Entity\Visit as VisitEntity;
use Application\Model\Entity\Redeem as RedeemEntity;
use \Application\Model\Entity\Reward as RewardEntity;
use \Application\Model\Entity\RewardHistory as RewardHistoryEntity;
use Application\Model\Entity\MerchantsCustomers as MerchantsCustomersEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class Merchant
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MerchantBranchRepository
     */
    private $merchantRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->merchantRepository = $entityManager->getRepository(
            'Application\Model\Entity\Merchant'
        );
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @return \Application\Model\Entity\Reward[]
     */
    public function getActiveRewards($merchantBranchEntity)
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Reward'
        );
        $rewardsList = $rewardRepository->findBy([
            'fkMerchantBranch' => $merchantBranchEntity,
            'isActive' => 1
        ],[
            'points' => 'asc'
        ]);
        return $rewardsList;
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @return \Application\Model\Entity\Reward
     */
    public function getRewardById($rewardId, $merchantBranchEntity)
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Reward'
        );
        return $rewardRepository->findOneBy([
            'id' => $rewardId,
            'fkMerchantBranch' => $merchantBranchEntity,
        ]);
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @return \Application\Model\Entity\MerchantsCustomers
     */
    public function getCustomerById($customerId, $merchantBranchEntity)
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\MerchantsCustomers'
        );
        return $rewardRepository->findOneBy([
            'fkCustomer' => $customerId,
            'fkMerchantBranch' => $merchantBranchEntity,
        ]);
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param CustomerEntity $customerEntity
     * @return MerchantsCustomersEntity
     */
    public function getMerchantCustomerRelation(
        $merchantBranchEntity,
        $customerEntity
    ) {
        $visitRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\MerchantsCustomers'
        );
        $merchantCustomer = $visitRepository->findOneBy([
            'fkMerchantBranch' => $merchantBranchEntity,
            'fkCustomer' => $customerEntity
        ]);
        return $merchantCustomer;
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param CustomerEntity $customerEntity
     * @param \DateTime $dateTime
     * @return MerchantsCustomersEntity
     */
    public function createMerchantCustomerRelation(
        $merchantBranchEntity,
        $customerEntity,
        $dateTime
    ) {
        $relation = new MerchantsCustomersEntity();
        $relation->setUpdatedAt($dateTime);
        $relation->setCreatedAt($dateTime);
        $relation->setVisits(0);
        $relation->setPoints(0);
        $relation->setRedeems(0);
        $relation->setFirstAt($dateTime);
        $relation->setFkCustomer($customerEntity);
        $relation->setFkMerchant($merchantBranchEntity->getFkMerchant());
        $relation->setFkMerchantBranch($merchantBranchEntity);

        $this->entityManager->persist($relation);
        $this->entityManager->flush();

        return $relation;
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param CustomerEntity $customerEntity
     * @return RedeemEntity[]
     */
    public function getLastRedeems(
        $merchantBranchEntity,
        $customerEntity
    )
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Redeem'
        );
        $redeemsList = $rewardRepository->findBy([
            'fkMerchantBranch' => $merchantBranchEntity,
            'fkCustomer' => $customerEntity
        ],[
            'id' => 'desc'
        ], 10);
        return $redeemsList;
    }

    /**
     * @param string $code
     * @return \Application\Model\Entity\RewardHistory
     */
    public function getRewardByCode($code)
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\RewardHistory'
        );
        return $rewardRepository->findOneBy(['code' => $code],['id' => 'desc']);
    }

    /**
     * @param RewardEntity $rewardEntity
     * @return RedeemEntity[]
     */
    public function getLastRedeemsForReward(RewardEntity $rewardEntity)
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Redeem'
        );
        $redeemsList = $rewardRepository->findBy([
            'fkReward' => $rewardEntity
        ],[
            'id' => 'desc'
        ], 10);
        return $redeemsList;
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @return RedeemEntity[]
     */
    public function getLastRedeemsForMerchantBranch($merchantBranchEntity)
    {
        $rewardRepository = $this->entityManager->getRepository(
            'Application\Model\Entity\Redeem'
        );
        $redeemsList = $rewardRepository->findBy([
            'fkMerchantBranch' => $merchantBranchEntity,
        ],[
            'id' => 'desc'
        ], 10);
        return $redeemsList;
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param int $lastVisitors
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getLastVisitors($merchantBranchEntity, $lastVisitors = 10)
    {
        $sql = "
        SELECT
            v.created_at, m_c.visits, m_c.redeems, c.id, c.name, IF(v.created_at = m_c.created_at, 1, 0) as first_visit
        FROM visit as v
        JOIN customer AS c ON c.id = v.fk_customer
        JOIN merchants__customers AS m_c ON v.fk_customer = m_c.fk_customer
        WHERE
            m_c.fk_merchant_branch = :merchantBranchId
        ORDER BY v.created_at DESC LIMIT $lastVisitors
        ; ";

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('merchantBranchId', $merchantBranchEntity->getId());

        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * @param RewardEntity $rewardEntity
     * @param $name
     * @param $points
     * @return bool
     */
    public function updateReward(RewardEntity $rewardEntity, $name, $points)
    {
        if ($rewardEntity->getName() == $name && $rewardEntity->getPoints() == $points) {
            return false;
        }

        $updateDate = new \DateTime();

        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

        $rewardEntity->setUpdatedAt($updateDate);
        $rewardEntity->setName($name);
        $rewardEntity->setPoints($points);
        $rewardEntity->setCode($generator->generateString(32));

        $this->entityManager->persist($rewardEntity);

        $history = new RewardHistoryEntity();
        $history->setCreatedAt($updateDate);
        $history->setFkReward($rewardEntity);
        $history->setName($rewardEntity->getName());
        $history->setPoints($rewardEntity->getPoints());
        $history->setCode($rewardEntity->getCode());

        $this->entityManager->persist($history);

        $this->entityManager->flush();

        return true;
    }

    /**
     * @param RewardEntity $rewardEntity
     * @return int
     */
    public function getCountOfRedeems(RewardEntity $rewardEntity)
    {
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(u.id) FROM Application\Model\Entity\Redeem u WHERE u.fkReward = ' . $rewardEntity->getId()
        );
        return $query->getSingleScalarResult();
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param CustomerEntity $customerEntity
     * @return VisitEntity[]
     */
    public function getLastVisits(
        $merchantBranchEntity,
        $customerEntity
    )
    {
        $previousMonth = new \DateTime('first day of previous month');
        $previousMonth->setTime(0,0,0);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('i.createdAt')->from('\Application\Model\Entity\Visit', 'i');

        $qb->andWhere($qb->expr()->eq('i.fkMerchantBranch', ':fkMerchantBranch'));
        $qb->andWhere($qb->expr()->eq('i.fkCustomer', ':fkCustomer'));
        $qb->andWhere($qb->expr()->gte('i.createdAt', ':createdAt'));

        $qb->setParameter('fkCustomer', $customerEntity);
        $qb->setParameter('fkMerchantBranch', $merchantBranchEntity);
        $qb->setParameter('createdAt', $previousMonth);

        $result = $qb->getQuery()->execute();

        $visitsList = [];

        /** @var \DateTime $visitDate */
        foreach ($result as $visitDate) {
            $visitsList[] = $visitDate['createdAt']->format('Y-m-d');
        }

        return $visitsList;
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param \DateTime $dateTime
     * @return VisitEntity[]
     */
    public function getUpdatedRewards(
        $merchantBranchEntity,
        $dateTime
    )
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('r')
            ->from('\Application\Model\Entity\Reward', 'r')
            ->where('r.fkMerchantBranch = :fkMerchantBranch')
            ->andWhere('r.updatedAt >= :updatedAt');

        $qb->setParameters(array(
            'fkMerchantBranch' => $merchantBranchEntity->getId(),
            'updatedAt' => $dateTime
        ));

        $rewardsList = $qb->getQuery()->getResult();

        $rewardsForUpdateResult = [];
        $rewardsForDeleteResult = [];

        /** @var RewardEntity $rewardEntity */
        foreach ($rewardsList as $rewardEntity) {
            if ($rewardEntity->getIsActive()) {
                $rewardsForUpdateResult[$rewardEntity->getCode()] = [
                    'name' => $rewardEntity->getName(),
                    'points' => $rewardEntity->getPoints(),
                    'code' => $rewardEntity->getCode(),
                    'id' => $rewardEntity->getId(),
                ];
            } else {
                $rewardsForDeleteResult[] = $rewardEntity->getCode();
            }
        }

        return [
            $rewardsForUpdateResult,
            $rewardsForDeleteResult
        ];
    }

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @param \DateTime $dateTime
     * @return VisitEntity[]
     */
    public function getRewardsHistory(
        $merchantBranchEntity,
        $dateTime
    )
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('rh')
            ->from('\Application\Model\Entity\RewardHistory', 'rh')
            ->join('rh.fkReward', 'r')
            ->orderBy('rh.id', 'ASC')
            ->where('r.fkMerchantBranch = :fkMerchantBranch')
            ->where('rh.createdAt >= :createdAt');

        $qb->setParameters(array(
            /// 'fkMerchantBranch' => $merchantBranchEntity,
            'createdAt' => $dateTime
        ));

        $rewardsHistoryList = $qb->getQuery()->getResult();

        $rewardsHistoryResult = [];

        /** @var RewardHistoryEntity $rewardHistoryEntity */
        foreach ($rewardsHistoryList as $rewardHistoryEntity) {

            $parentCode = $this->getParent($rewardHistoryEntity);

            $rewardsHistoryResult[$rewardHistoryEntity->getCode()] = [
                'name' => $rewardHistoryEntity->getName(),
                'points' => $rewardHistoryEntity->getPoints(),
                'code' => $rewardHistoryEntity->getCode(),
                'parentCode' => $parentCode,
                'id' => $rewardHistoryEntity->getId(),
                'rewardId' => $rewardHistoryEntity->getFkReward()->getId(),
            ];
        }

        return $rewardsHistoryResult;
    }

    /**
     * @param RewardHistoryEntity $rewardHistoryEntity
     * @return string
     */
    private function getParent($rewardHistoryEntity){

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('rh')
            ->from('\Application\Model\Entity\RewardHistory', 'rh')
            ->where('rh.fkReward = :fkReward')
            ->orderBy('rh.id')
        ;

        $qb->setParameters(array(
            'fkReward' => $rewardHistoryEntity->getFkReward()->getId()
        ));

        /** @var \Application\Model\Entity\RewardHistory $rewardsFirst */
        $rewardsFirst = $qb->getQuery()->getResult();
         return $rewardsFirst[0]->getCode();
    }

    /**
     * @param MerchantsCustomersEntity $merchantCustomerEntity
     * @param RewardHistoryEntity $rewardHistoryEntity
     * @param $dateTime
     * @return boolean
     */
    public function redeem($merchantCustomerEntity, $rewardHistoryEntity, $dateTime)
    {
        if ($rewardHistoryEntity->getPoints() > $merchantCustomerEntity->getPoints()) {
            return false;
        }

        $redeem = new RedeemEntity();
        $redeem->setCreatedAt($dateTime);
        $redeem->setUpdatedAt($dateTime);
        $redeem->setFkCustomer($merchantCustomerEntity->getFkCustomer());
        $redeem->setFkMerchant($merchantCustomerEntity->getFkMerchant());
        $redeem->setFkMerchantBranch($merchantCustomerEntity->getFkMerchantBranch());
        $redeem->setfkReward($rewardHistoryEntity->getFkReward());
        $redeem->setFkHistoryReward($rewardHistoryEntity);
        $redeem->setStatus(RedeemEntity::STATUS_ACTIVATED);
        $redeem->setSpent($rewardHistoryEntity->getPoints());
        $redeem->setTotal($merchantCustomerEntity->getPoints());

        $this->entityManager->persist($redeem);

        $merchantCustomerEntity->setPoints(
            $merchantCustomerEntity->getPoints() - $rewardHistoryEntity->getPoints()
        );
        $merchantCustomerEntity->setRedeems($merchantCustomerEntity->getRedeems() + 1);
        $merchantCustomerEntity->setUpdatedAt($dateTime);

        $this->entityManager->persist($merchantCustomerEntity);

        $this->entityManager->flush();
        return true;
    }
}