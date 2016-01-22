<?php

namespace Lsw\VsphereClientBundle\Client;

use Lsw\VsphereClientBundle\Exception\VsphereObjectNotFoundException;
use Lsw\VsphereClientBundle\Model\ResourcePool;
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
}
