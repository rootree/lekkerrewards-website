<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Application\Model\Entity\Merchant as MerchantEntity;
use Doctrine\ORM\Query\Expr\Join as Join;

class Statistics
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getLastVisits($merchantBranchEntity)
    {
        $previousMonth = new \DateTime('first day of previous month');
        $previousMonth->setTime(0, 0, 0);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->
        select('i.createdAt, COUNT(i.id) AS visits')->
        from('\Application\Model\Entity\Visit', 'i')->
        groupBy('i.createdAt');

        $qb->andWhere($qb->expr()->eq('i.fkMerchantBranch', ':fkMerchantBranch'));
        $qb->andWhere($qb->expr()->gte('i.createdAt', ':createdAt'));

        $qb->setParameter('fkMerchantBranch', $merchantBranchEntity);
        $qb->setParameter('createdAt', $previousMonth);

        $result = $qb->getQuery()->execute();

        $visitsList = [];

        /** @var \DateTime $visitDate */
        foreach ($result as $visitDate) {
            $dateF = $visitDate['createdAt']->format('Y-m-d');
            $visitsList[$dateF] = [$dateF, $visitDate['visits']];
        }

        return $visitsList;
    }

    public function getNewAndReturned($merchantBranchEntity, $lastDays)
    {
        $sql = "
        SELECT
            DATE_FORMAT(v.created_at, '%d %b') as created_at,
          SUM(CASE WHEN v.created_at = m_c.created_at THEN 1 ELSE 0 END) AS 'new',
          SUM(CASE WHEN v.created_at != m_c.created_at THEN 1 ELSE 0 END) AS 'returned'

        FROM merchants__customers as m_c
        JOIN  visit AS v ON v.fk_customer = m_c.fk_customer
        WHERE
            m_c.fk_merchant_branch = :merchantBranchId AND
            (v.created_at BETWEEN DATE_SUB(NOW(), INTERVAL :lastDays DAY) AND NOW())
        GROUP BY (v.created_at);
        ";

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('merchantBranchId', $merchantBranchEntity->getId());
        $stmt->bindValue('lastDays', $lastDays);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $retentionList = [];

        for ($i = 0; $i < $lastDays; $i++) {
            $retentionList[date('d M', strtotime(' -'.$i.' day'))] = [0,0];
        }

        foreach ($result as $row) {
            $retentionList[$row['created_at']] = [$row['new'], $row['returned']];
        }

        $retentionList = array_reverse($retentionList);
        return $retentionList;
    }

    public function getGenders($merchantBranchEntity)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->
        select('c.gender, COUNT(i.id) AS customers')->
        from('Application\Model\Entity\MerchantsCustomers', 'i')->
        leftJoin('Application\Model\Entity\Customer', 'c', Join::WITH, 'c.id = i.fkCustomer')->
        groupBy('c.gender');

        $qb->andWhere($qb->expr()->eq('i.fkMerchantBranch', ':fkMerchantBranch'));
        $qb->setParameter('fkMerchantBranch', $merchantBranchEntity);

        $result = $qb->getQuery()->execute();

        $genderList = [];

        foreach ($result as $row) {
            $genderList[$row['gender']] = $row['customers'];
        }

        return $genderList;
    }

    public function getAges($merchantBranchEntity)
    {
        $sql = "
            SELECT
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) < 18 THEN 1 ELSE 0 END) AS '<18',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 18 AND 24 THEN 1 ELSE 0 END) AS '18-24',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 50 AND 54 THEN 1 ELSE 0 END) AS '50-54',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) > 55 THEN 1 ELSE 0 END) AS '>55',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 30 AND 34 THEN 1 ELSE 0 END) AS '30-34',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 35 AND 39 THEN 1 ELSE 0 END) AS '35-39',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 40 AND 44 THEN 1 ELSE 0 END) AS '40-44',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 45 AND 49 THEN 1 ELSE 0 END) AS '45-49',
        SUM(CASE WHEN YEAR(CURDATE()) - YEAR(c.birthday) BETWEEN 25 AND 29 THEN 1 ELSE 0 END) AS '25-29'
            FROM merchants__customers as m_c
            JOIN customer as c ON c.id = m_c.fk_customer
            WHERE
                m_c.fk_merchant_branch = :merchantBranchId
        ";

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('merchantBranchId', $merchantBranchEntity->getId());

        $stmt->execute();
        $result = $stmt->fetchAll();

        $genderList = [];

        foreach ($result[0] as $age => $count) {
            $genderList[$age] = $count;
        }

        return $genderList;
    }
}