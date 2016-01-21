<?php

namespace Lsw\VsphereClientBundle\Model;

use Vmwarephp\Vhost;

/**
 * Class Model
 * @package Lsw\VsphereClientBundle\Model
 */
abstract class Model
{
    /** @var Vhost $vhost */
    protected $vhost;

    /**
     * Model constructor.
     * @param Vhost $vhost
     */
    public function __construct(Vhost $vhost)
    {
        $this->vhost = $vhost;
    }
}
