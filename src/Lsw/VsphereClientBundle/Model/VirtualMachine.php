<?php

namespace Lsw\VsphereClientBundle\Model;

use Lsw\VsphereClientBundle\Entity\GuestNicInfo;
use Lsw\VsphereClientBundle\Entity\VirtualMachine as VirtualMachineEntity;
use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;
use Vmwarephp\ManagedObject;

/**
 * Class VirtualMachine
 * @package Lsw\VsphereClientBundle\Model
 */
class VirtualMachine extends Model
{
    /**
     * @param $id
     * @return VirtualMachineEntity
     * @throws VSphereObjectNotFoundException
     */
    public function findById($id)
    {
        // Get the Virtual Machine information
        try {
            $virtualMachineResponse = $this->service->findOneManagedObject(
                'VirtualMachine',
                $id,
                ['name', 'guest']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        return $this->getVirtualMachineFromManagedObject($virtualMachineResponse);
    }

    /**
     * @return VirtualMachineEntity[]
     * @throws VSphereObjectNotFoundException
     */
    public function findAll()
    {
        // Get the Virtual Machine information
        try {
            $virtualMachinesResponse = $this->service->findAllManagedObjects(
                'VirtualMachine',
                ['name', 'guest']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        // Gather the information for each of the virtual machines
        $virtualMachines = [];
        foreach ($virtualMachinesResponse as $virtualMachine) {
            $virtualMachines[] = $this->getVirtualMachineFromManagedObject($virtualMachine);
        }
        return $virtualMachines;
    }

    /**
     * @param string $id
     * @return ManagedObject
     * @throws VsphereObjectNotFoundException
     */
    public function powerOn($id)
    {
        try {
            $virtualMachineResponse = $this->service->findOneManagedObject(
                'VirtualMachine',
                $id,
                ['name', 'guest']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        return $this->service->PowerOnVM_Task($virtualMachineResponse);
    }

    /**
     * @param string $id
     * @return ManagedObject
     * @throws VsphereObjectNotFoundException
     */
    public function powerOff($id)
    {
        try {
            $virtualMachineResponse = $this->service->findOneManagedObject(
                'VirtualMachine',
                $id,
                ['name', 'guest']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        return $this->service->PowerOffVM_Task($virtualMachineResponse);
    }

    /**
     * @param string $id
     * @return ManagedObject
     * @throws VsphereObjectNotFoundException
     */
    public function reset($id)
    {
        try {
            $virtualMachineResponse = $this->service->findOneManagedObject(
                'VirtualMachine',
                $id,
                ['name', 'guest']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        return $this->service->ResetVM_Task($virtualMachineResponse);
    }

    /**
     * @param $managedObject
     * @return VirtualMachineEntity
     */
    private function getVirtualMachineFromManagedObject($managedObject)
    {
        // Get Nics information
        $nics = $managedObject->guest->net;
        $guestNics = [];
        if (!empty($nics)) {
            foreach ($nics as $nic) {
                $guestNic = new GuestNicInfo();
                $guestNic
                    ->setConfigId($nic->deviceConfigId)
                    ->setNetworkId($nic->network)
                    ->setManagedObject($nic);
                $guestNics[] = $guestNic;
            }
        }

        // Create the Virtual Machine Object
        $virtualMachine = new VirtualMachineEntity();
        $virtualMachine
            ->setId($managedObject->reference->_)
            ->setName($managedObject->name)
            ->setGuestNics($guestNics)
            ->setManagedObject($managedObject);
        return $virtualMachine;
    }
}
