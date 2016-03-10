<?php

namespace Lsw\VsphereClientBundle\Entity;

/**
 * Class VirtualMachine
 * @package Lsw\VsphereClientBundle\Entity
 */
class VirtualMachine extends Entity
{
    /** @var string $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var GuestNicInfo[] $guestNics */
    private $guestNics;

    /**
     * VirtualMachine constructor.
     */
    public function __construct()
    {
        $this->guestNics = [];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return VirtualMachine
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return VirtualMachine
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return GuestNicInfo[]
     */
    public function getGuestNics()
    {
        return $this->guestNics;
    }

    /**
     * @param GuestNicInfo[] $guestNics
     * @return VirtualMachine
     */
    public function setGuestNics($guestNics)
    {
        $this->guestNics = $guestNics;
        return $this;
    }
}
