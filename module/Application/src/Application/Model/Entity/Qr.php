<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Qr
 *
 * @ORM\Table(name="qr", indexes={@ORM\Index(name="customer", columns={"fk_customer"})})
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Qr")
 */
class Qr extends Entity\Qr
{
    const STATUS_ACTIVATED = 1;
    const STATUS_PREPARED = 2;
    const STATUS_DEACTIVATED = 0;

    const SOURCE_WEB = 1;
    const SOURCE_CARD = 2;
}
