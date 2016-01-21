<?php

namespace Lsw\VsphereClientBundle\Client;

/**
 * Class ClientConfiguration
 * @package Lsw\VsphereClientBundle\Client
 */
class ClientConfiguration
{
    /**
     * @var string $endpoint
     */
    private $endpoint;

    /**
     * @var int $port
     */
    private $port;

    /**
     * ClientConfiguration constructor.
     * @param $endpoint string
     * @param $port int
     */
    public function __construct($endpoint, $port)
    {
        $this->endpoint = $endpoint;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }
}
