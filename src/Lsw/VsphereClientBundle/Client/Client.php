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
    /**
     * @var Vhost
     */
    private $vhost;

    /**
     * Configures the API client
     * @param $configuration ClientConfiguration
     * @param $credentials Credentials
     * @return Client
     */
    public function configure(ClientConfiguration $configuration, Credentials $credentials)
    {
        $this->vhost = new Vhost(
            sprintf('%s:%d', $configuration->getEndpoint(), $configuration->getPort()),
            $credentials->getUsername(),
            $credentials->getPassword()
        );

        return $this;
    }

    /**
     * @param $id
     * @return \Lsw\VsphereClientBundle\Entity\ResourcePool|null
     */
    public function getResourcePool($id)
    {
        try {
            $resourcePoolModel = new ResourcePool($this->vhost);
            return $resourcePoolModel->findById($id);
        } catch (VsphereObjectNotFoundException $e) {
            return null;
        }
    }
}
