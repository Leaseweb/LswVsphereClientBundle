<?php

namespace Lsw\VsphereClientBundle\Entity;

/**
 * Class ResourcePool
 * @package Lsw\VsphereClientBundle\Entity
 */
class ResourcePool extends Entity
{
    /** @var string $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var int $memoryAllocated Memory allocated (in MB) */
    private $memoryAllocated;

    /** @var int $cpuAllocated CPU allocated (in MHz) */
    private $cpuAllocated;

    /** @var int $memoryFree Memory available (in MB) */
    private $memoryFree;

    /** @var int $cpuFree CPU available (in MHz) */
    private $cpuFree;

    /** @var array $virtualMachineIds Child Virtual Machine ids */
    private $virtualMachineIds;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ResourcePool
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
     * @return ResourcePool
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getMemoryAllocated()
    {
        return $this->memoryAllocated;
    }

    /**
     * @param int $memoryAllocated
     * @return ResourcePool
     */
    public function setMemoryAllocated($memoryAllocated)
    {
        $this->memoryAllocated = $memoryAllocated;
        return $this;
    }

    /**
     * @return int
     */
    public function getCpuAllocated()
    {
        return $this->cpuAllocated;
    }

    /**
     * @param int $cpuAllocated
     * @return ResourcePool
     */
    public function setCpuAllocated($cpuAllocated)
    {
        $this->cpuAllocated = $cpuAllocated;
        return $this;
    }

    /**
     * @return int
     */
    public function getMemoryFree()
    {
        return $this->memoryFree;
    }

    /**
     * @param int $memoryFree
     * @return ResourcePool
     */
    public function setMemoryFree($memoryFree)
    {
        $this->memoryFree = $memoryFree;
        return $this;
    }

    /**
     * @return int
     */
    public function getCpuFree()
    {
        return $this->cpuFree;
    }

    /**
     * @param int $cpuFree
     * @return ResourcePool
     */
    public function setCpuFree($cpuFree)
    {
        $this->cpuFree = $cpuFree;
        return $this;
    }

    /**
     * @return array
     */
    public function getVirtualMachineIds()
    {
        return $this->virtualMachineIds;
    }

    /**
     * @param array $virtualMachineIds
     * @return ResourcePool
     */
    public function setVirtualMachineIds($virtualMachineIds)
    {
        $this->virtualMachineIds = $virtualMachineIds;
        return $this;
    }
}
