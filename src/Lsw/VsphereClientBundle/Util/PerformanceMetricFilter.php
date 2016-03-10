<?php

namespace Lsw\VsphereClientBundle\Util;

/**
 * Class PerformanceMetricFilter
 * @package Lsw\VsphereClientBundle\Util
 */
class PerformanceMetricFilter
{
    /** @var string $filterName */
    private $filterName;

    /** @var string $metricName */
    private $metricName;

    /** @var string $metricGroup */
    private $metricGroup;

    /** @var string $metricUnit */
    private $metricUnit;

    /** @var int $perfMetricId */
    private $perfMetricId;

    /** @var string $instanceName */
    private $instanceName;

    /**
     * PerformanceMetricFilter constructor.
     * @param string $filterName Filter identifier (should be unique)
     * @param string $metricName
     * @param string $metricGroup
     * @param string $metricUnit
     * @param string|null $instanceName
     */
    public function __construct($filterName, $metricName, $metricGroup, $metricUnit, $instanceName = null)
    {
        $this->filterName = $filterName;
        $this->metricName = $metricName;
        $this->metricGroup = $metricGroup;
        $this->metricUnit = $metricUnit;
        $this->instanceName = $instanceName;

        // This field can be filled by the elements that use this filter for caching purposes
        $this->perfMetricId = null;
    }

    /**
     * @return string
     */
    public function getFilterName()
    {
        return $this->filterName;
    }

    /**
     * @param string $filterName
     * @return PerformanceMetricFilter
     */
    public function setFilterName($filterName)
    {
        $this->filterName = $filterName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetricName()
    {
        return $this->metricName;
    }

    /**
     * @param string $metricName
     * @return PerformanceMetricFilter
     */
    public function setMetricName($metricName)
    {
        $this->metricName = $metricName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetricGroup()
    {
        return $this->metricGroup;
    }

    /**
     * @param string $metricGroup
     * @return PerformanceMetricFilter
     */
    public function setMetricGroup($metricGroup)
    {
        $this->metricGroup = $metricGroup;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetricUnit()
    {
        return $this->metricUnit;
    }

    /**
     * @param string $metricUnit
     * @return PerformanceMetricFilter
     */
    public function setMetricUnit($metricUnit)
    {
        $this->metricUnit = $metricUnit;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerfMetricId()
    {
        return $this->perfMetricId;
    }

    /**
     * @param int $perfMetricId
     * @return PerformanceMetricFilter
     */
    public function setPerfMetricId($perfMetricId)
    {
        $this->perfMetricId = $perfMetricId;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     * @param string $instanceName
     * @return PerformanceMetricFilter
     */
    public function setInstanceName($instanceName)
    {
        $this->instanceName = $instanceName;
        return $this;
    }
}
