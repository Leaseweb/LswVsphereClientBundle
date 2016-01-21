<?php

namespace Lsw\VsphereClientBundle\Model;

use Lsw\VsphereClientBundle\Entity\ResourcePool as ResourcePoolEntity;
use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;

/**
 * Class ResourcePool
 * @package Lsw\VsphereClientBundle\Model
 */
class ResourcePool extends Model
{
    /**
     * @param $id
     * @return ResourcePoolEntity
     * @throws VSphereObjectNotFoundException
     */
    public function findById($id)
    {
        try {
            $resourcePoolResponse = $this->vhost->findOneManagedObject(
                'ResourcePool',
                $id,
                ['name', 'config', 'resourcePool']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        $resourcePool = new ResourcePoolEntity();

        /** @var \ResourceConfigSpec $rpConfig */
        $rpConfig = $resourcePoolResponse->config;

        /** @var \ResourceAllocationInfo $cpuAllocation */
        $cpuAllocation = $rpConfig->cpuAllocation;

        /** @var \ResourceAllocationInfo $memoryAllocation */
        $memoryAllocation = $rpConfig->memoryAllocation;

        $resourcePool
            ->setId($id)
            ->setName($resourcePoolResponse->name)
            ->setCpuAllocated($cpuAllocation->limit)
            ->setMemoryAllocated($memoryAllocation->limit);

        return $resourcePool;
    }
}
