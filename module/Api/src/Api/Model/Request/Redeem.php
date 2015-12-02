<?php

namespace Api\Model\Request;

class Redeem
{
    /**
     * @var string
     * @required
     */
    public $code;

    /**
     * @var string
     * @required
     */
    public $email;

    /**
     * @var string
     * @required
     */
    public $timestamp;
}