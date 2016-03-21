<?php

namespace Lsw\VsphereClientBundle\Entity;

/**
 * Class PerformanceSample
 * @package Lsw\VsphereClientBundle\Entity
 */
class PerformanceSample
{
    /** @var \DateTime $timestamp */
    private $dateTime;

    /** @var int $interval */
    private $interval;

    /** @var int $value */
    private $value;

    /**
     * PerformanceSample constructor.
     *
     * @param $dateTime
     * @param $interval
     * @param $value
     */
    public function __construct($dateTime, $interval, $value)
    {
        $this->dateTime = $dateTime;
        $this->interval = $interval;
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return PerformanceSample
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     * @return PerformanceSample
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return PerformanceSample
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
