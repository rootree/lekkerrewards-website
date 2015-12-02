<?php

namespace Api\Model\Request;

class CheckInByEmail
{
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