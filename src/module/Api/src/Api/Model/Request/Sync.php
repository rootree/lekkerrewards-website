<?php

namespace Api\Model\Request;

class Sync
{
    /**
     * @var string
     * @required
     */
    public $timestamp;

    /**
     * @var int
     * @required
     */
    public $visits;
}