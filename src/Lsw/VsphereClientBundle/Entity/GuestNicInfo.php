<?php

namespace Lsw\VsphereClientBundle\Entity;

/**
 * Class GuestNicInfo
 * @package Lsw\VsphereClientBundle\Entity
 */
class GuestNicInfo extends Entity
{
    /** @var string $networkId */
    private $networkId;

    /** @var int $configId */
    private $configId;

    /**
     * @return string
     */
    public function getNetworkId()
    {
        return $this->networkId;
    }

    /**
     * @param string $networkId
     * @return GuestNicInfo
     */
    public function setNetworkId($networkId)
    {
        $this->networkId = $networkId;
        return $this;
    }

    /**
     * @return int
     */
    public function getConfigId()
    {
        return $this->configId;
    }

    /**
     * @param int $configId
     * @return GuestNicInfo
     */
    public function setConfigId($configId)
    {
        $this->configId = $configId;
        return $this;
    }
}
