<?php

namespace Lsw\VsphereClientBundle\Entity;

use Vmwarephp\ManagedObject;

/**
 * Class Entity
 * @package Lsw\VsphereClientBundle\Entity
 */
abstract class Entity
{
    /** @var ManagedObject $managedObject */
    protected $managedObject;

    /**
     * @return ManagedObject
     */
    public function getManagedObject()
    {
        return $this->managedObject;
    }

    /**
     * @param ManagedObject $managedObject
     * @return self
     */
    public function setManagedObject($managedObject)
    {
        $this->managedObject = $managedObject;
        return $this;
    }
}
