<?php

namespace Lsw\VsphereClientBundle\Entity;

/**
 * Class Task
 * @package Lsw\VsphereClientBundle\Entity
 */
class Task extends Entity
{
    /** @var TaskInfo $info */
    private $info;

    /**
     * @return TaskInfo
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param TaskInfo $info
     * @return Task
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }
}