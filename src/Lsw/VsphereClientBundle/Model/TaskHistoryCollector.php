<?php

namespace Lsw\VsphereClientBundle\Model;

use DateTime;
use Lsw\VsphereClientBundle\Entity\Entity;
use Lsw\VsphereClientBundle\Entity\TaskHistoryCollector as TaskHistoryCollectorEntity;
use Lsw\VsphereClientBundle\Entity\TaskInfo;
use Vmwarephp\ManagedObject;

/**
 * Class TaskHistoryCollector
 * @package Lsw\VsphereClientBundle\Model
 */
class TaskHistoryCollector extends Model
{
    /**
     * @param Entity|null $entity
     * @param string|null $recursion
     * @param DateTime|null $beginTime
     * @param DateTime|null $endTime
     * @param string|null $timeType
     * @return TaskHistoryCollectorEntity
     */
    public function getTaskHistoryCollector(
        Entity $entity = null,
        $recursion = null,
        DateTime $beginTime = null,
        DateTime $endTime = null,
        $timeType = null
    ) {
        $taskManager = $this->service->getServiceContent()->taskManager;

        $filterByEntity = $entity && $recursion
            ? new \TaskFilterSpecByEntity($entity->getManagedObject()->reference, $recursion)
            : null;
        $filterByTime = $beginTime && $endTime
            ? new \TaskFilterSpecByTime($timeType, $beginTime, $endTime)
            : null;

        $filter = new \TaskFilterSpec($filterByEntity, $filterByTime);

        $createCollectorForTasksResponse = $taskManager->CreateCollectorForTasks(['filter' => $filter]);

        $taskHistoryCollector = new TaskHistoryCollectorEntity();
        $taskHistoryCollector->setManagedObject($createCollectorForTasksResponse);

        return $taskHistoryCollector;
    }

    /**
     * @param TaskHistoryCollectorEntity $taskHistoryCollector
     * @return TaskInfo[]
     */
    public function getLatestPage(TaskHistoryCollectorEntity $taskHistoryCollector)
    {
        $latestPage = $taskHistoryCollector->getManagedObject()->getLatestPage();

        $tasks = [];

        foreach ($latestPage as $managedObject) {
            $tasks[] = $this->getTaskInfoFromManagedObject($managedObject);
        }

        return $tasks;
    }

    /**
     * @param TaskHistoryCollectorEntity $taskHistoryCollector
     * @param int|null $maxCount
     * @return TaskInfo[]
     */
    public function readNextPage(TaskHistoryCollectorEntity $taskHistoryCollector, $maxCount = 10)
    {
        $nextPage = $taskHistoryCollector->getManagedObject()->ReadNextTasks(['maxCount' => intval($maxCount)]);

        $tasks = [];

        foreach ($nextPage as $managedObject) {
            $tasks[] = $this->getTaskInfoFromManagedObject($managedObject);
        }

        return $tasks;
    }

    /**
     * @param TaskHistoryCollectorEntity $taskHistoryCollector
     * @param int|null $maxCount
     * @return TaskInfo[]
     */
    public function readPreviousPage(TaskHistoryCollectorEntity $taskHistoryCollector, $maxCount = 10)
    {
        $nextPage = $taskHistoryCollector->getManagedObject()->ReadPreviousTasks(['maxCount' => intval($maxCount)]);

        $tasks = [];

        foreach ($nextPage as $managedObject) {
            $tasks[] = $this->getTaskInfoFromManagedObject($managedObject);
        }

        return $tasks;
    }

    /**
     * @param ManagedObject $managedObject
     * @return TaskInfo
     */
    private function getTaskInfoFromManagedObject($managedObject)
    {
        $taskInfo = new TaskInfo();
        $taskInfo->setKey(strval($managedObject->key));
        $taskInfo->setName(strval($managedObject->name));
        $taskInfo->setState(strval($managedObject->state));
        $taskInfo->setProgress(intval($managedObject->progress));
        $taskInfo->setManagedObject($managedObject);

        return $taskInfo;
    }
}
