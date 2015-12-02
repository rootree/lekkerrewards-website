<?php

namespace Api\Model\Request;

class Registration
{
    /**
     * @var string
     * @required
     */
    public $email;

    /**
     * @var int
     * @required
     */
    public $qr;

    /**
     * @var string
     * @required
     */
    public $timestamp;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getQr()
    {
        return $this->qr;
    }

    /**
     * @param int $qr
     */
    public function setQr($qr)
    {
        $this->qr = $qr;
    }
}