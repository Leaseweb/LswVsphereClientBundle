<?php

namespace Lsw\VsphereClientBundle\Model;

use Lsw\VsphereClientBundle\Entity\Entity;
use Lsw\VsphereClientBundle\Entity\PerformanceSample;
use Lsw\VsphereClientBundle\Exception\VsphereUnknownException;
use Lsw\VsphereClientBundle\Util\PerformanceMetricFilter;
use Vmwarephp\ManagedObject;

/**
 * Class PerformanceManager
 * @package Lsw\VsphereClientBundle\Model
 */
class PerformanceManager extends Model
{
    /**
     * @param Entity                    $entity Entity to retrieve the performance from
     * @param PerformanceMetricFilter[] $metricsFilter Metric filters
     *
     * @return PerformanceSample[]
     * @throws VsphereUnknownException
     */
    public function getPerformanceRealTime(Entity $entity, array $metricsFilter = [])
    {
        $managedObject = $entity->getManagedObject();

        // Get the performance provider, which contains the real-time interval
        $perfManager = $managedObject->perfManager;
        try {
            $perfProvider = $perfManager->QueryPerfProviderSummary([
                'entity' => $managedObject->reference
            ]);
        } catch (\Exception $e) {
            throw new VsphereUnknownException(
                'Unknown exception trying to get the performance provider summary: ' . $e->getMessage()
            );
        }

        return $this->getPerformance($entity, $metricsFilter, null, null, $perfProvider->refreshRate);
    }

    /**
     * todo: make this method work with gauges (tested with network -> counter)
     *
     * @param Entity                    $entity Entity to retrieve the performance from
     * @param PerformanceMetricFilter[] $metricsFilter Metric filters
     * @param string                    $startDate Start date in datetime format (eg 2016-03-08T12:00:00Z)
     * @param string                    $endDate End date in datetime format (eg 2016-03-08T12:00:00Z)
     * @param int                       $interval Interval in seconds
     *
     * @return PerformanceSample[]
     * @throws VsphereUnknownException
     */
    public function getPerformance(
        Entity $entity,
        array $metricsFilter = [],
        $startDate = null,
        $endDate = null,
        $interval = null
    ) {
        $managedObject = $entity->getManagedObject();

        // Get the performance manager
        $perfManager = $managedObject->perfManager;

        // Get available Performance Metrics
        $perfMetricIds = $this->getPerfMetricIds(
            $perfManager,
            $managedObject,
            $metricsFilter,
            $startDate,
            $endDate,
            $interval
        );

        // Finally query to get the usage
        try {
            $performance = $perfManager->QueryPerf([
                'querySpec' => [
                    new \PerfQuerySpec(
                        $managedObject->reference,
                        $startDate,
                        $endDate,
                        null,
                        $perfMetricIds,
                        $interval,
                        null
                    )
                ]
            ]);
        } catch (\Exception $e) {
            throw new VsphereUnknownException(
                'Unknown exception trying to get the performance: ' . $e->getMessage()
            );
        }

        if (!empty($performance) && !empty($performance[0])) {
            // [0] references to the first requested querySpec (see QueryPerf call), as we only request one, it will
            // always return an array with only one position
            $performance = $performance[0];

            // This starts to get complicated. At this moment, we have a $performance variable that contains two arrays:
            //  - sampleInfo: an array that specifies the timestamps and their corresponding intervals.
            //  - values: an array of arrays. Each of the positions contains a set of values of the same size,
            //    containing the metrics for each of the requested metric IDs in the same order as the request.
            // We have to iterate over the requested filters (only those that matched a metric) and dump the values.
            $performanceResults = [];

            for ($i = 0; $i < count($metricsFilter); $i++) {
                $metricFilter = $metricsFilter[$i];
                // Check if the filter matched a metric, otherwise continue
                if (!$metricFilter->getPerfMetricId()) {
                    continue;
                }

                $filterName = $metricFilter->getFilterName();
                $performanceResults[$filterName] = [];

                // Dump the values and timestamps for all the samples
                for ($j = 0; $j < count($performance->sampleInfo); $j++) {

                    $dateTime = new \DateTime($performance->sampleInfo[$j]->timestamp);
                    $interval = $performance->sampleInfo[$j]->interval;
                    $value = 0;
                    if ($performance->value[$i]->value[$j] > 1) {
                        $value = $performance->value[$i]->value[$j] * $performance->sampleInfo[$j]->interval;
                    }

                    $performanceResults[$filterName][] = new PerformanceSample($dateTime, $interval, $value);
                }
            }

            return $performanceResults;
        }
        return null;
    }

    /**
     * @param                           $perfManager
     * @param ManagedObject             $managedObject
     * @param PerformanceMetricFilter[] $metricsFilter
     * @param string                    $startDate
     * @param string                    $endDate
     * @param int|null                  $interval
     *
     * @return array
     * @throws VsphereUnknownException
     */
    private function getPerfMetricIds(
        $perfManager,
        $managedObject,
        array &$metricsFilter = [],
        $startDate = null,
        $endDate = null,
        $interval = null
    ) {
        try {
            $perfMetricIds = $perfManager->QueryAvailablePerfMetric([
                'entity' => $managedObject->reference,
                'beginTime' => $startDate,
                'endTime' => $endDate,
                'intervalId' => $interval
            ]);
        } catch (\Exception $e) {
            throw new VsphereUnknownException(
                'Unknown exception trying to get the available performance metrics: ' . $e->getMessage()
            );
        }

        // Convert results into an array of IDs
        $perfMetricIdsInt = [];
        if (!empty($perfMetricIds)) {
            foreach ($perfMetricIds as $perfMetricId) {
                $perfMetricIdsInt[] = $perfMetricId->counterId;
            }

            // Get information about Performance Metrics retrieved above
            try {
                $perfCounters = $perfManager->QueryPerfCounter([
                    'counterId' => $perfMetricIdsInt
                ]);
            } catch (\Exception $e) {
                throw new VsphereUnknownException(
                    'Unknown exception trying to get the performance counters information: ' . $e->getMessage()
                );
            }

            // Filter for the metrics we need to request
            if (!empty($metricsFilter)) {
                return $this->filterMetrics($perfCounters, $perfMetricIds, $metricsFilter);
            }

            return $perfMetricIds;
        }
        return [];
    }

    /**
     * todo: redesign this method with a lower complexity + clean design
     * @param \PerfCounterInfo[]        $perfCounters
     * @param \PerfMetricId[]           $perfMetricIds
     * @param PerformanceMetricFilter[] $metricsFilter
     * @return array
     */
    private function filterMetrics($perfCounters, $perfMetricIds, array &$metricsFilter)
    {
        $filteredPerfMetricIds = [];
        $filteredPerfMetricIdsInt = [];

        /** @var PerformanceMetricFilter $metricFilter */
        foreach ($metricsFilter as $metricFilter) {
            /** @var \PerfCounterInfo $perfCounter */
            foreach ($perfCounters as $perfCounter) {
                if (in_array($perfCounter->key, $filteredPerfMetricIdsInt)) {
                    continue;
                }

                /** @var \ElementDescription $nameInfo */
                $nameInfo = $perfCounter->nameInfo;
                /** @var \ElementDescription $groupInfo */
                $groupInfo = $perfCounter->groupInfo;
                /** @var \ElementDescription $unitInfo */
                $unitInfo = $perfCounter->unitInfo;

                // Check if the Performance Counter matches the filter
                if ($nameInfo->key == $metricFilter->getMetricName() &&
                    $groupInfo->key == $metricFilter->getMetricGroup() &&
                    $unitInfo->key == $metricFilter->getMetricUnit()
                ) {
                    $metricFilterInstance = $metricFilter->getInstanceName();

                    // If it matches, look for the corresponding ID
                    foreach ($perfMetricIds as $perfMetricId) {
                        if ($perfMetricId->counterId == $perfCounter->key &&
                            ($metricFilterInstance === null || $perfMetricId->instance == $metricFilterInstance)) {
                            // Assign the perfMetricId to the filtered results
                            $filteredPerfMetricIds[] = $perfMetricId;

                            // Assign the counter ID to the filter
                            $metricFilter->setPerfMetricId($perfMetricId->counterId);

                            $filteredPerfMetricIdsInt[] = $perfCounter->key;
                            break;
                        }
                    }
                }
            }
        }

        return $filteredPerfMetricIds;
    }
}
