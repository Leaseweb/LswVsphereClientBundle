<?php

namespace Lsw\VsphereClientBundle\Tests\Model;

use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;
use Lsw\VsphereClientBundle\Model\VirtualMachine;
use Lsw\VsphereClientBundle\Tests\AbstractTest;
use Vmwarephp\Service;

/**
 * Class VirtualMachineTest
 * @package Lsw\VsphereClientBundle\Tests\Model
 */
class VirtualMachineTest extends AbstractTest
{
    /**
     * Test power on virtual machine method.
     */
    public function testPowerOn()
    {
        $virtualMachineObject = new \stdClass();
        $virtualMachineObject->id = 'vm-1234';

        $serviceMock = $this->createMock(Service::class);
        $serviceMock->expects($this->once())
            ->method('findOneManagedObject')
            ->with(
                $this->equalTo('VirtualMachine'),
                $this->equalTo('vm-1234'),
                $this->equalTo(['name', 'guest'])
            )
            ->willReturn($virtualMachineObject);
        $serviceMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('PowerOnVM_Task'),
                $this->equalTo([$virtualMachineObject])
            );

        $virtualMachineModel = new VirtualMachine($serviceMock);
        $virtualMachineModel->powerOn('vm-1234');
    }

    /**
     * Test power on virtual machine method with not found exception.
     */
    public function testPowerOnWithVsphereObjectNotFoundException()
    {
        $virtualMachineObject = new \stdClass();
        $virtualMachineObject->id = 'vm-1234';

        $serviceMock = $this->createMock(Service::class);
        $serviceMock->expects($this->once())
            ->method('findOneManagedObject')
            ->willThrowException(new \Exception('Something went wrong'));

        $this->expectException(VsphereObjectNotFoundException::class);
        $this->expectExceptionMessage('Something went wrong');

        $virtualMachineModel = new VirtualMachine($serviceMock);
        $virtualMachineModel->powerOn('vm-1234');
    }

    /**
     * Test power off virtual machine method.
     */
    public function testPowerOff()
    {
        $virtualMachineObject = new \stdClass();
        $virtualMachineObject->id = 'vm-1234';

        $serviceMock = $this->createMock(Service::class);
        $serviceMock->expects($this->once())
            ->method('findOneManagedObject')
            ->with(
                $this->equalTo('VirtualMachine'),
                $this->equalTo('vm-1234'),
                $this->equalTo(['name', 'guest'])
            )
            ->willReturn($virtualMachineObject);
        $serviceMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('PowerOffVM_Task'),
                $this->equalTo([$virtualMachineObject])
            );

        $virtualMachineModel = new VirtualMachine($serviceMock);
        $virtualMachineModel->powerOff('vm-1234');
    }

    /**
     * Test reset virtual machine method.
     */
    public function testReset()
    {
        $virtualMachineObject = new \stdClass();
        $virtualMachineObject->id = 'vm-1234';

        $serviceMock = $this->createMock(Service::class);
        $serviceMock->expects($this->once())
            ->method('findOneManagedObject')
            ->with(
                $this->equalTo('VirtualMachine'),
                $this->equalTo('vm-1234'),
                $this->equalTo(['name', 'guest'])
            )
            ->willReturn($virtualMachineObject);
        $serviceMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('ResetVM_Task'),
                $this->equalTo([$virtualMachineObject])
            );

        $virtualMachineModel = new VirtualMachine($serviceMock);
        $virtualMachineModel->reset('vm-1234');
    }
}
