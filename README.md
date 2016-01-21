LswVsphereClientBundle
========================

This is a bundle for Symfony that adds the ability to communicate with VMWare vSphere API.

# Installation instructions
To install this bundle in a project, please follow these steps:

* Update composer.json adding the following repositories:

```
{
    "type": "vcs",
    "url": "git@github.com:LeaseWeb/LswVsphereClientBundle.git"
},
{
    "type": "vcs",
    "url": "git@github.com:LeaseWeb/vmwarephp.git"
}
```

* Update composer.json, add the following vendor:

```
"leaseweb/vsphere-client-bundle": "dev-master"
```


* Update AppKernel.php, add:

```
new Lsw\VsphereClientBundle\LswVsphereClientBundle()
```


* Update your services.yml:

```
lsw.vcloud_api_client:
    class: Lsw\VsphereClientBundle\Client\Client
    arguments: []
```


* Run ```composer update``` in your project.


# Usage example

Basic usage example:


```
use Lsw\VsphereClientBundle\Client\Client as VsphereClient;
use Lsw\VsphereClientBundle\Client\ClientConfiguration as VsphereClientConfiguration;
use Lsw\VsphereClientBundle\Client\Credentials as VsphereCredentials;
```

...


```
/**
 * @return VsphereClient
 */
private function getVsphereClient()
{
    /** @var VSphereClient $vSphereService */
    $vSphereService = $this->container->get('lsw.vsphere_api_client');

    $vSphereConfiguration = new VsphereClientConfiguration(
        $this->container->getParameter('vcrp01nl_vcenter_host'),
        (int)$this->container->getParameter('vcrp01nl_vcenter_port')
    );

    $vSphereCredentials = new VsphereCredentials(
        $this->container->getParameter('vcrp01nl_vcenter_username'),
        $this->container->getParameter('vcrp01nl_vcenter_password')
    );

    return $vSphereService->configure($vSphereConfiguration, $vSphereCredentials);
}

public function indexAction(Request $request, $resourcePoolName)
{
    $vSphereAPIClient = $this->getVsphereClient();
    $resourcePool = $vSphereAPIClient->getResourcePool($resourcePoolName);
    var_dump($resourcePool);
    exit;
}
```


