<?php

namespace Application\Service;

use Application\Model\Repository\Owner as OwnerRepository;
use Doctrine\ORM\EntityManager;
use Application\Service\Email as EmailService;
use Application\Service\Qr as QrService;
use Application\Model\Entity\Owner as OwnerEntity;

class Owner
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var OwnerRepository
     */
    private $ownerRepository;

    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(
        EntityManager $entityManager,
        EmailService $emailService
    )
    {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
        $this->ownerRepository = $entityManager->getRepository('Application\Model\Entity\Owner');
    }

    /**
     * @param OwnerEntity $ownerEntity
     *
     * @return OwnerEntity
     */
    public function save($ownerEntity)
    {
        $this->entityManager->persist($ownerEntity);
        $this->entityManager->flush();
        return $ownerEntity;
    }

    /**
     * @param $email
     * @return OwnerEntity
     *
     */
    public function getUserByEmail($email)
    {
        return $this->ownerRepository->findOneBy([
            'eMail' => $email,
        ]);
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function generatePassword($password)
    {
        return md5($password);
    }
}