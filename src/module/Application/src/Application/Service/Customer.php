<?php

namespace Application\Service;

use Application\Model\Repository\Customer as CustomerRepository;
use Doctrine\ORM\EntityManager;
use Application\Service\Email as EmailService;
use Application\Service\Qr as QrService;
use Application\Model\Entity\Customer as CustomerEntity;
use Application\Model\Entity\Qr as QrEntity;
use Application\Model\Entity\Redeem as RedeemEntity;
use Application\Model\Entity\MerchantBranch as MerchantBranchEntity;
use Application\Model\Entity\Merchant as MerchantEntity;

class Customer
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var EmailService
     */
    private $emailService;

    /**
     * @var QrService
     */
    private $qrService;

    public function __construct(
        EntityManager $entityManager,
        EmailService $emailService,
        QrService $qrService
    )
    {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
        $this->qrService = $qrService;
        $this->customerRepository = $entityManager->getRepository('Application\Model\Entity\Customer');
    }

    /**
     * @param CustomerEntity $customerEntity
     *
     * @return CustomerEntity
     */
    public function save($customerEntity)
    {
        $this->entityManager->persist($customerEntity);
        $this->entityManager->flush();
        return $customerEntity;
    }

    /**
     * @param $email
     * @return CustomerEntity
     *
     */
    public function getUserByEmail($email)
    {
        return $this->customerRepository->findOneBy(
            [
                'eMail' => $email,
            ]
        );
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function encryptPassword($password)
    {
        return md5($password);
    }

    /**
     * @param CustomerEntity $newCustomerEntity
     * @param QrEntity $qrEntity
     * @return CustomerEntity
     */
    public function createCustomer($newCustomerEntity, QrEntity $qrEntity)
    {
        $newCustomerEntity->setPassword($this->encryptPassword($newCustomerEntity->getPassword()));
        // $newCustomerEntity->setCreatedAt(new \DateTime());
        // $newCustomerEntity->setUpdatedAt(new \DateTime());

        $qrEntity->setFkCustomer($newCustomerEntity);
        $this->entityManager->persist($qrEntity);

        $newCustomerEntity = $this->save($newCustomerEntity);
        return $newCustomerEntity;
    }


    public function getLastVisits($customerEntity)
    {
        $visitRepository = $this->entityManager->getRepository('Application\Model\Entity\Visit');
        return $visitRepository->findBy(
            [
                'fkCustomer' => $customerEntity,
            ], [
            'id' => 'desc'
        ], 9
        );
    }

    public function getLastRedeems($customerEntity)
    {
        $redeemRepository = $this->entityManager->getRepository('Application\Model\Entity\Redeem');
        return $redeemRepository->findBy(
            [
                'fkCustomer' => $customerEntity,
            ], [
            'id' => 'desc'
        ], 9
        );
    }

    public function merchants($customerEntity)
    {
        $merchantsCustomersRepository = $this->entityManager->getRepository('Application\Model\Entity\MerchantsCustomers');
        return $merchantsCustomersRepository->findBy(
            [
                'fkCustomer' => $customerEntity,
            ], [
            'id' => 'desc'
        ]
        );
    }

    /**
     * @param string $email
     * @return CustomerEntity
     */
    public function searchByEmail($email)
    {
        return $this->customerRepository->findOneBy(
            [
                'eMail' => $email,
            ], [
                'id' => 'desc'
            ]
        );
    }

    /**
     * @param CustomerEntity $customerEntity
     * @param QrEntity $qrEntity
     */
    public function connectQrWithCustomer($customerEntity, $qrEntity)
    {
        $QRList = $customerEntity->getQRList();
        /** @var QrEntity $oldQrEntity */
        foreach ($QRList as $oldQrEntity) {
            if ($oldQrEntity->getStatus() == QrEntity::STATUS_ACTIVATED) {
                $oldQrEntity->setStatus(QrEntity::STATUS_DEACTIVATED);
                $oldQrEntity->setUpdatedAt(new \DateTime());
                $this->entityManager->persist($oldQrEntity);
            }
        }

        $qrEntity->setUpdatedAt(new \DateTime());
        $qrEntity->setStatus(QrEntity::STATUS_ACTIVATED);
        $qrEntity->setFkCustomer($customerEntity);

        $this->entityManager->persist($qrEntity);
        $this->entityManager->flush();
    }

    public function searchBy($searchFor, $merchantBranchEntity)
    {
        $sql = "
        SELECT
            c.name, c.e_mail, c.id
        FROM merchants__customers as m_c
        JOIN customer AS c ON c.id = m_c.fk_customer
        WHERE
            m_c.fk_merchant_branch = :merchantBranchId AND
            (c.name LIKE :searchFor OR SUBSTRING_INDEX(c.e_mail, '@', 1) LIKE :searchForMail)
        ORDER BY c.name
        ;";

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('merchantBranchId', $merchantBranchEntity->getId());
        $stmt->bindValue('searchFor', '%' . $searchFor . '%');

        if (strpos($searchFor, '@') === false) {
            $stmt->bindValue('searchForMail', '%' . $searchFor . '%');
        } else {
            $stmt->bindValue('searchForMail', '%' . substr($searchFor, 0, strpos($searchFor, '@')) . '%');
        }

        $stmt->execute();
        $result = $stmt->fetchAll();

        $customersList = [];

        foreach ($result as $row) {
            $mail = substr($row['e_mail'], 0, strpos($row['e_mail'], '@') + 1) . '...';
            $customersList[] = [
                'value' => $mail,
                'data' => [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'e_mail' => $mail
                ]
            ];
        }

        return $customersList;
    }
}