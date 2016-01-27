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

    /**
     * @param MerchantBranchEntity $merchantBranchEntity
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getLastVisits($merchantBranchEntity)
    {
        $previousMonth = new \DateTime('first day of previous month');
        $previousMonth->setTime(0, 0, 0);

        $sql = "
        SELECT
            DATE_FORMAT(i.created_at, '%Y-%m-%d') as created_at, COUNT(i.id) AS visits
        FROM visit as i
        WHERE
            i.created_at >= :createdAt AND
            i.fk_merchant_branch = :fkMerchantBranch
        GROUP BY (DATE_FORMAT(i.created_at, '%Y-%m-%d'));
        ";

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('createdAt', $previousMonth->format("Y-m-d"));
        $stmt->bindValue('fkMerchantBranch', $merchantBranchEntity->getId());

        $stmt->execute();
        $result = $stmt->fetchAll();

        $visitsList = [];

        /** @var \DateTime $visitDate */
        foreach ($result as $visitDate) {
            $dateF = $visitDate['created_at'];
            $visitsList[$dateF] = [$dateF, $visitDate['visits']];
        }

        return $visitsList;
    }

    public function getNewAndReturned($merchantBranchEntity, $lastDays)
    {
        $lastDays++;

        $sql = "
        SELECT
            DATE_FORMAT(v.created_at, '%d %b') as created_at,
          SUM(CASE WHEN v.created_at = m_c.created_at THEN 1 ELSE 0 END) AS 'new',
          SUM(CASE WHEN v.created_at != m_c.created_at THEN 1 ELSE 0 END) AS 'returned'

        FROM merchants__customers as m_c
        JOIN  visit AS v ON v.fk_customer = m_c.fk_customer
        WHERE
            m_c.fk_merchant_branch = :merchantBranchId AND
            (v.created_at BETWEEN DATE_SUB(NOW(), INTERVAL :lastDays DAY) AND DATE_ADD(NOW(), INTERVAL 1 DAY))
        GROUP BY (created_at);
        ";

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('merchantBranchId', $merchantBranchEntity->getId());
        $stmt->bindValue('lastDays', $lastDays);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $retentionList = [];

        $firstDayToDrop = null;
        for ($i = 0; $i <= $lastDays; $i++) {
            $day = date('d M', strtotime(' -'.$i.' day'));
            if (!$firstDayToDrop) {
                $firstDayToDrop = $day;
            }
            $retentionList[$day] = [0,0];
        }

        foreach ($result as $row) {
            $retentionList[$row['created_at']] = [$row['new'], $row['returned']];
        }

        unset($retentionList[$day]);

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
        SUM(CASE WHEN c.birthday IS NULL THEN 1 ELSE 0 END) AS 'N/A',
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