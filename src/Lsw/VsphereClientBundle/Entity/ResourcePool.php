<?php

namespace Lsw\VsphereClientBundle\Entity;

/**
 * Class ResourcePool
 * @package Lsw\VsphereClientBundle\Entity
 */
class ResourcePool
{
    /** @var string $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var int Memory allocated (in MB) */
    private $memoryAllocated;

    /** @var int $cpuAllocated (in MHz) */
    private $cpuAllocated;

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
}
