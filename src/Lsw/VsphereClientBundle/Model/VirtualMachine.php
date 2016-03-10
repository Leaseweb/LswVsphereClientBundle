<?php

namespace Lsw\VsphereClientBundle\Model;

use Lsw\VsphereClientBundle\Entity\GuestNicInfo;
use Lsw\VsphereClientBundle\Entity\VirtualMachine as VirtualMachineEntity;
use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;

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
                ['name', 'network']
            );
        } catch (\Exception $e) {
            throw new VsphereObjectNotFoundException($e->getMessage());
        }

        // Get Nics information
        $nics = $virtualMachineResponse->guest->net;
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
            ->setId($id)
            ->setName($virtualMachineResponse->name)
            ->setGuestNics($guestNics)
            ->setManagedObject($virtualMachineResponse);
        return $virtualMachine;
    }
}
