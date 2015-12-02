<?php

namespace Api\Model\Request;

class CheckInByQr
{
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
}