<?php

namespace Lsw\VsphereClientBundle\Entity;

use DateTime;

/**
 * Class TaskInfo
 * @package Lsw\VsphereClientBundle\Entity
 */
class TaskInfo extends Entity
{
    /** @var string $key */
    private $key;

    /** @var string $name */
    private $name;

    /** @var string $state */
    private $state;

    /** @var int $progress */
    private $progress;

    /** @var DateTime $queueTime */
    private $queueTime;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return TaskInfo
     */
    public function setKey($key)
    {
        $this->key = $key;
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
     * @return TaskInfo
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return TaskInfo
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     * @return TaskInfo
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getQueueTime()
    {
        return $this->queueTime;
    }

    /**
     * @param DateTime $queueTime
     * @return TaskInfo
     */
    public function setQueueTime(DateTime $queueTime)
    {
        $this->queueTime = $queueTime;
        return $this;
    }
}
