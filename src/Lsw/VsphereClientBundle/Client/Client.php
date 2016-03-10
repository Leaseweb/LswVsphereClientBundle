<?php

namespace Lsw\VsphereClientBundle\Client;

use Lsw\VsphereClientBundle\Entity\Entity;
use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;
use Lsw\VsphereClientBundle\Model\PerformanceManager;
use Lsw\VsphereClientBundle\Model\ResourcePool;
use Lsw\VsphereClientBundle\Model\VirtualMachine;
use Lsw\VsphereClientBundle\Util\PerformanceMetricFilter;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
     * @return \Lsw\VsphereClientBundle\Entity\ResourcePool|null
     */
    public function getResourcePool($id)
    {
        try {
            $resourcePoolModel = new ResourcePool($this->service);
            return $resourcePoolModel->findById($id);
        } catch (VsphereObjectNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param $id
     * @return \Lsw\VsphereClientBundle\Entity\VirtualMachine|null
     */
    public function getVirtualMachine($id)
    {
        try {
            $virtualMachineModel = new VirtualMachine($this->service);
            return $virtualMachineModel->findById($id);
        } catch (VsphereObjectNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param Entity                    $entity Entity to retrieve the performance from
     * @param PerformanceMetricFilter[] $metricsFilter Metric filters
     *
     * @return null
     */
    public function getPerformanceRealTime(Entity $entity, array $metricsFilter = [])
    {
        try {
            $performanceManagerModel = new PerformanceManager($this->service);
            return $performanceManagerModel->getPerformanceRealTime($entity, $metricsFilter);
        } catch (VsphereObjectNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param Entity                    $entity Entity to retrieve the performance from
     * @param PerformanceMetricFilter[] $metricsFilter Metric filters
     * @param string                    $startDate Start date in datetime format (eg 2016-03-08T12:00:00Z)
     * @param string                    $endDate End date in datetime format (eg 2016-03-08T12:00:00Z)
     * @param int                       $interval Interval in seconds
     *
     * @return null
     */
    public function getPerformance(
        Entity $entity,
        array $metricsFilter = [],
        $startDate = null,
        $endDate = null,
        $interval = 300
    ) {
        try {
            $performanceManagerModel = new PerformanceManager($this->service);
            return $performanceManagerModel->getPerformance($entity, $metricsFilter, $startDate, $endDate, $interval);
        } catch (VsphereObjectNotFoundException $e) {
            return null;
        }
    }
}
