<?php

namespace Lsw\VsphereClientBundle\Client;

use DateTime;
use Lsw\VsphereClientBundle\Entity\Entity;
use Lsw\VsphereClientBundle\Entity\TaskHistoryCollector as TaskHistoryCollectorEntity;
use Lsw\VsphereClientBundle\Entity\TaskInfo;
use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;
use Lsw\VsphereClientBundle\Exception\VsphereUnknownException;
use Lsw\VsphereClientBundle\Model\PerformanceManager;
use Lsw\VsphereClientBundle\Model\ResourcePool;
use Lsw\VsphereClientBundle\Model\TaskHistoryCollector;
use Lsw\VsphereClientBundle\Model\VirtualMachine;
use Lsw\VsphereClientBundle\Util\PerformanceMetricFilter;
use Vmwarephp\ManagedObject;
use Vmwarephp\Vhost;

/**
 * Class Client
 * @package Lsw\VsphereClientBundle\Client
 */
class Client
{
    /** @var \Vmwarephp\Service $service */
    private $service;

    /**
     * Configures the API client
     * @param $configuration ClientConfiguration
     * @param $credentials Credentials
     * @return Client
     */
    public function configure(ClientConfiguration $configuration, Credentials $credentials)
    {
        $vhost = new Vhost(
            sprintf('%s:%d', $configuration->getEndpoint(), $configuration->getPort()),
            $credentials->getUsername(),
            $credentials->getPassword()
        );

        $this->service = $vhost->getService();

        return $this;
    }

    /**
     * @param $id
     * @return \Lsw\VsphereClientBundle\Entity\ResourcePool
     * @throws VsphereObjectNotFoundException
     */
    public function getResourcePool($id)
    {
        $resourcePoolModel = new ResourcePool($this->service);
        return $resourcePoolModel->findById($id);
    }

    /**
     * @param $id
     * @return \Lsw\VsphereClientBundle\Entity\VirtualMachine
     * @throws VsphereObjectNotFoundException
     */
    public function getVirtualMachine($id)
    {
        $virtualMachineModel = new VirtualMachine($this->service);
        return $virtualMachineModel->findById($id);
    }

    /**
     * @return \Lsw\VsphereClientBundle\Entity\VirtualMachine[]
     */
    public function getVirtualMachines()
    {
        try {
            $virtualMachineModel = new VirtualMachine($this->service);
            return $virtualMachineModel->findAll();
        } catch (VsphereObjectNotFoundException $e) {
            return [];
        }
    }

    /**
     * @param string $id
     * @return ManagedObject
     */
    public function powerOnVirtualMachine($id)
    {
        $virtualMachineModel = new VirtualMachine($this->service);

        return $virtualMachineModel->powerOn($id);
    }

    /**
     * @param string $id
     * @return ManagedObject
     */
    public function powerOffVirtualMachine($id)
    {
        $virtualMachineModel = new VirtualMachine($this->service);

        return $virtualMachineModel->powerOff($id);
    }

    /**
     * @param string $id
     * @return ManagedObject
     */
    public function resetVirtualMachine($id)
    {
        $virtualMachineModel = new VirtualMachine($this->service);

        return $virtualMachineModel->reset($id);
    }

    /**
     * @param Entity                    $entity Entity to retrieve the performance from
     * @param PerformanceMetricFilter[] $metricsFilter Metric filters
     *
     * @return \Lsw\VsphereClientBundle\Entity\PerformanceSample[]
     * @throws VsphereUnknownException
     */
    public function getPerformanceRealTime(Entity $entity, array $metricsFilter = [])
    {
        $performanceManagerModel = new PerformanceManager($this->service);
        return $performanceManagerModel->getPerformanceRealTime($entity, $metricsFilter);
    }

    /**
     * @param Entity                    $entity Entity to retrieve the performance from
     * @param PerformanceMetricFilter[] $metricsFilter Metric filters
     * @param string                    $startDate Start date in datetime format (eg 2016-03-08T12:00:00Z)
     * @param string                    $endDate End date in datetime format (eg 2016-03-08T12:00:00Z)
     * @param int                       $interval Interval in seconds
     *
     * @return \Lsw\VsphereClientBundle\Entity\PerformanceSample[]
     * @throws VsphereUnknownException
     */
    public function getPerformance(
        Entity $entity,
        array $metricsFilter = [],
        $startDate = null,
        $endDate = null,
        $interval = null
    ) {
        $performanceManagerModel = new PerformanceManager($this->service);
        return $performanceManagerModel->getPerformance($entity, $metricsFilter, $startDate, $endDate, $interval);
    }

    /**
     * @param Entity $entity
     * @param DateTime|null $beginTime
     * @param DateTime|null $endTime
     * @param string $recursion
     * @param string $timeType
     * @return TaskHistoryCollectorEntity
     */
    public function getTaskHistoryCollectorByEntityAndTime(
        Entity $entity,
        DateTime $beginTime = null,
        DateTime $endTime = null,
        $recursion = 'self',
        $timeType = 'queuedTime'
    ) {
        $taskHistoryCollectorModel = new TaskHistoryCollector($this->service);

        return $taskHistoryCollectorModel->getTaskHistoryCollector(
            $entity,
            $recursion,
            $beginTime,
            $endTime,
            $timeType
        );
    }

    /**
     * @param TaskHistoryCollectorEntity $taskHistoryCollector
     * @return TaskInfo[]
     */
    public function getTasksLatestPage(TaskHistoryCollectorEntity $taskHistoryCollector)
    {
        $taskHistoryCollectorModel = new TaskHistoryCollector($this->service);

        return $taskHistoryCollectorModel->getLatestPage($taskHistoryCollector);
    }

    /**
     * @param TaskHistoryCollectorEntity $taskHistoryCollector
     * @return TaskInfo[]
     */
    public function getTasksNextPage(TaskHistoryCollectorEntity $taskHistoryCollector)
    {
        $taskHistoryCollectorModel = new TaskHistoryCollector($this->service);

        return $taskHistoryCollectorModel->readNextPage($taskHistoryCollector);
    }

    /**
     * @param TaskHistoryCollectorEntity $taskHistoryCollector
     * @return TaskInfo[]
     */
    public function getTasksPreviousPage(TaskHistoryCollectorEntity $taskHistoryCollector)
    {
        $taskHistoryCollectorModel = new TaskHistoryCollector($this->service);

        return $taskHistoryCollectorModel->readPreviousPage($taskHistoryCollector);
    }
}
