<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Customer")
 */
class Customer extends Entity\Customer
{
    const GENDER_MAN = 1;
    const GENDER_WOMAN = 2;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\Qr", mappedBy="fkCustomer")
     * @ORM\JoinColumn(name="fk_customer", referencedColumnName="id")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $QRList;

    public function __construct()
    {
        $this->QRList = new ArrayCollection();
    }

    /**
     * Get QR List
     *
     * @return \Application\Model\Entity\Qr[]
     */
    public function getQRList()
    {
        return $this->QRList;
    }

    /**
     * @return Qr|null
     */
    public function getActiveQRCode()
    {
        $list = $this->getQRList();
        /** @var \Application\Model\Entity\Qr $QR */
        foreach ($list as $QR) {
            if ($QR->getStatus() == \Application\Model\Entity\Qr::STATUS_ACTIVATED) {
                return $QR;
            }
        }
        return null;
    }
}
