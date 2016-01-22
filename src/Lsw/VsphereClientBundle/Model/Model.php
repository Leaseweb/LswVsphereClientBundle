<?php

namespace Lsw\VsphereClientBundle\Model;

use Vmwarephp\Service;

/**
 * Class Model
 * @package Lsw\VsphereClientBundle\Model
 */
abstract class Model
{
    /** @var Service $service */
    protected $service;

    /**
     * Model constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
