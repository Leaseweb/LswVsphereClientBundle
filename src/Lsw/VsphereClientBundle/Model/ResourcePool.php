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
        // Get the Resource Pool information
        try {
            $resourcePoolResponse = $this->service->findOneManagedObject(
                'ResourcePool',
                $id,
                ['name', 'config', 'resourcePool', 'vm']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        /** @var \ResourceConfigSpec $rpConfig */
        $rpConfig = $resourcePoolResponse->config;

        // Allocated CPU and memory
        $cpuAllocated = $rpConfig->cpuAllocation->limit;
        $memoryAllocated = $rpConfig->memoryAllocation->limit;

        // Free CPU and memory
        $cpuFree = $cpuAllocated;
        $memoryFree = $memoryAllocated;
        $subResourcePools = $resourcePoolResponse->resourcePool;
        if (!empty($subResourcePools) && !empty($subResourcePools[0])) {
            foreach ($subResourcePools as $subResourcePool) {
                $cpuFree = $this->substractResource($cpuFree, $subResourcePool->config->cpuAllocation->limit);
                $memoryFree = $this->substractResource($memoryFree, $subResourcePool->config->memoryAllocation->limit);
            }
        }

        // Virtual Machine IDs in this Resource Pool
        $virtualMachines = $resourcePoolResponse->vm;
        $virtualMachineIds = [];
        if (!empty($virtualMachines) && !empty($virtualMachines[0])) {
            foreach ($virtualMachines as $virtualMachine) {
                $virtualMachineIds[] = $virtualMachine->reference->_;
            }
        }

        // Create the Resource Pool Entity
        $resourcePool = new ResourcePoolEntity();
        $resourcePool
            ->setId($id)
            ->setName($resourcePoolResponse->name)
            ->setCpuAllocated($cpuAllocated)
            ->setMemoryAllocated($memoryAllocated)
            ->setCpuFree($cpuFree)
            ->setMemoryFree($memoryFree)
            ->setVirtualMachineIds($virtualMachineIds)
            ->setManagedObject($resourcePoolResponse);

        return $resourcePool;
    }

    /**
     * Substracts allocated resources from a total count. It filters resources smaller than zero (=unlimited)
     * @param int|float $resource
     * @param int|float $toSubstract
     *
     * @return int|float
     */
    private function substractResource($resource, $toSubstract)
    {
        return ($toSubstract > 0) ? ($resource - $toSubstract) : $resource;
    }
}
