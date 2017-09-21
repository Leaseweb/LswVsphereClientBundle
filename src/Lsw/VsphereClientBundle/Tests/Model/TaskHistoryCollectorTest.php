<?php

namespace Lsw\VsphereClientBundle\Tests;

use Lsw\VsphereClientBundle\Entity\TaskHistoryCollector as TaskHistoryCollectorEntity;
use Lsw\VsphereClientBundle\Entity\TaskInfo;
use Lsw\VsphereClientBundle\Model\TaskHistoryCollector;
use Vmwarephp\ManagedObject;
use Vmwarephp\Service;

/**
 * Class TaskHistoryCollectorTest
 * @package Lsw\VsphereClientBundle\Tests
 */
class TaskHistoryCollectorTest extends AbstractTest
{
    /**
     * Test get task history collector method.
     */
    public function testGetTaskHistoryCollector()
    {
        $filter = new \TaskFilterSpec();

        $taskManagerMock = $this->createMock(ManagedObject::class);
        $taskManagerMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('CreateCollectorForTasks'),
                $this->equalTo([0 => ['filter' => $filter]])
            );

        $serviceContent = new \ServiceContent();
        $serviceContent->taskManager = $taskManagerMock;

        $serviceMock = $this->createMock(Service::class);
        $serviceMock->expects($this->once())
            ->method('getServiceContent')
            ->willReturn($serviceContent);

        $taskHistoryCollectorModel = new TaskHistoryCollector($serviceMock);
        $taskHistoryCollector = $taskHistoryCollectorModel->getTaskHistoryCollector();

        $this->assertInstanceOf(TaskHistoryCollectorEntity::class, $taskHistoryCollector);
    }

    /**
     * Test get latest page method.
     */
    public function testGetLatestPage()
    {
        $taskInfo = new \TaskInfo();
        $taskInfo->key = 'task-1234';
        $taskInfo->name = 'PowerOnVM_Task';
        $taskInfo->state = 'running';
        $taskInfo->progress = 0;
        $taskInfo->queueTime = '2017-09-14T10:23:35.052999Z';

        $managedObjectMock = $this->createMock(ManagedObject::class);
        $managedObjectMock->expects($this->at(0))
            ->method('__call')
            ->with(
                $this->equalTo('SetCollectorPageSize'),
                $this->equalTo([0 => ['maxCount' => 100]])
            );
        $managedObjectMock->expects($this->at(1))
            ->method('__call')
            ->with($this->equalTo('getLatestPage'))
            ->willReturn([$taskInfo]);

        $taskHistoryCollector = new TaskHistoryCollectorEntity();
        $taskHistoryCollector->setManagedObject($managedObjectMock);

        $serviceMock = $this->createMock(Service::class);

        $taskHistoryCollectorModel = new TaskHistoryCollector($serviceMock);
        $tasks = $taskHistoryCollectorModel->getLatestPage($taskHistoryCollector, 100);

        $this->assertCount(1, $tasks);
        $this->assertInstanceOf(TaskInfo::class, $tasks[0]);
        $this->assertEquals('task-1234', $tasks[0]->getKey());
        $this->assertEquals('PowerOnVM_Task', $tasks[0]->getName());
        $this->assertEquals('running', $tasks[0]->getState());
        $this->assertEquals(0, $tasks[0]->getProgress());
        $this->assertInstanceOf(\DateTime::class, $tasks[0]->getQueueTime());
    }

    /**
     * Test read next tasks method.
     */
    public function testReadNextTasks()
    {
        $taskInfo = new \TaskInfo();
        $taskInfo->key = 'task-1234';
        $taskInfo->name = 'PowerOnVM_Task';
        $taskInfo->state = 'running';
        $taskInfo->progress = 0;
        $taskInfo->queueTime = '2017-09-14T10:23:35.052999Z';

        $managedObjectMock = $this->createMock(ManagedObject::class);
        $managedObjectMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('ReadNextTasks'),
                $this->equalTo([0 => ['maxCount' => 10]])
            )
            ->willReturn([$taskInfo]);

        $taskHistoryCollector = new TaskHistoryCollectorEntity();
        $taskHistoryCollector->setManagedObject($managedObjectMock);

        $serviceMock = $this->createMock(Service::class);

        $taskHistoryCollectorModel = new TaskHistoryCollector($serviceMock);
        $tasks = $taskHistoryCollectorModel->readNextTasks($taskHistoryCollector);

        $this->assertCount(1, $tasks);
        $this->assertInstanceOf(TaskInfo::class, $tasks[0]);
    }

    /**
     * Test read previous tasks method.
     */
    public function testReadPreviousTasks()
    {
        $taskInfo = new \TaskInfo();
        $taskInfo->key = 'task-1234';
        $taskInfo->name = 'PowerOnVM_Task';
        $taskInfo->state = 'running';
        $taskInfo->progress = 0;
        $taskInfo->queueTime = '2017-09-14T10:23:35.052999Z';

        $managedObjectMock = $this->createMock(ManagedObject::class);
        $managedObjectMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('ReadPreviousTasks'),
                $this->equalTo([0 => ['maxCount' => 10]])
            )
            ->willReturn([$taskInfo]);

        $taskHistoryCollector = new TaskHistoryCollectorEntity();
        $taskHistoryCollector->setManagedObject($managedObjectMock);

        $serviceMock = $this->createMock(Service::class);

        $taskHistoryCollectorModel = new TaskHistoryCollector($serviceMock);
        $tasks = $taskHistoryCollectorModel->readPreviousTasks($taskHistoryCollector);

        $this->assertCount(1, $tasks);
        $this->assertInstanceOf(TaskInfo::class, $tasks[0]);
    }
}
