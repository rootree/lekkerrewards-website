<?php

namespace Application\Service;

use Api\Model\Exception;
use Application\Model\Repository\Qr as QrRepository;
use Doctrine\ORM\EntityManager;
use Application\Model\Entity\Qr as QrEntity;
use Endroid\QrCode\QrCode;

class Qr
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var QrRepository
     */
    private $qrRepository;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->qrRepository = $entityManager->getRepository('Application\Model\Entity\Qr');
    }

    /**
     * @param int $source
     * @return QrEntity
     */
    public function generateQr($source = QrEntity::SOURCE_WEB)
    {
        $qrEntity = new QrEntity();

        $qrEntity->setCreatedAt(new \DateTime());
        $qrEntity->setUpdatedAt(new \DateTime());
        $qrEntity->setCode($this->getRandomCode());
        $qrEntity->setSource($source);
        $qrEntity->setStatus(QrEntity::STATUS_ACTIVATED);

        return $qrEntity;
    }

    private function getRandomCode()
    {
        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

        return sprintf('%06s', $generator->generateInt(0, 999999));
    }

    /**
     * @param $text
     * @throws \Endroid\QrCode\Exceptions\ImageFunctionUnknownException
     */
    public function createQRCode($text)
    {
        $qrCode = new QrCode();
        $qrCode
            ->setText($text)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabelFontSize(16)
            ->render()
        ;
    }

    public function getHexCode(QrEntity $currentQrCode)
    {
        return sprintf(
            '%s|%06s',
            dechex($currentQrCode->getCode() * $currentQrCode->getId()),
            $currentQrCode->getCode()
        );
    }

    /**
     * @param $code
     * @return null|QrEntity
     */
    public function getQrByCode($code)
    {
        return $this->qrRepository->findOneBy(['code' => $code], ['id' => 'desc']);
    }

    /**
     * @param QrEntity $qrEntity
     * @return null|QrEntity
     */
    public function lastUpdate($qrEntity)
    {
        $qrEntity->setLastUsed(new \DateTime());
        $this->entityManager->persist($qrEntity);
        $this->entityManager->flush();
    }

    /**
     * @param \DateTime $dateTime
     * @return QrEntity[]
     */
    public function getUpdatedQrs($dateTime)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('qr')
            ->from('\Application\Model\Entity\Qr', 'qr')
            ->andWhere('qr.updatedAt >= :updatedAt')
            ->andWhere('qr.status >= :status');

        $qb->setParameters(array(
            'updatedAt' => $dateTime,
            'status' => QrEntity::STATUS_ACTIVATED
        ));

        $qrsList = $qb->getQuery()->getResult();

        $updatedQrs = [];

        /** @var QrEntity $qrEntity */
        foreach ($qrsList as $qrEntity) {
            $updatedQrs[$qrEntity->getCode()] = [
                'code' => $qrEntity->getCode(),
                'status' => $qrEntity->getStatus(),
                'source' => $qrEntity->getSource(),
            ];
        }

        return $updatedQrs;
    }
}