<?php

namespace Lsw\VsphereClientBundle\Factory;

use DateTime;
use Lsw\VsphereClientBundle\Entity\Task;
use Lsw\VsphereClientBundle\Entity\TaskInfo;

/**
 * Class TaskInfoFactory
 * @package Lsw\VsphereClientBundle\Factory
 */
class TaskInfoFactory
{
    /**
     * @param \TaskInfo $taskInfoObject
     * @return TaskInfo
     */
    public static function buildFromTaskInfoObject(\TaskInfo $taskInfoObject)
    {
        $taskInfo = new TaskInfo();
        $taskInfo->setKey(strval($taskInfoObject->key));
        $taskInfo->setName(strval($taskInfoObject->name));
        $taskInfo->setState(strval($taskInfoObject->state));
        $taskInfo->setProgress(intval($taskInfoObject->progress));
        $taskInfo->setQueueTime(new DateTime($taskInfoObject->queueTime));

        $task = new Task();
        $task->setManagedObject($taskInfoObject->task);
        $task->setInfo($taskInfo);

        $taskInfo->setTask($task);

        return $taskInfo;
    }
}
