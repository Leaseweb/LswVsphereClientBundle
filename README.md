LswVsphereClientBundle
========================

This project adds the ability to Symfony and Slim Frameworks to communicate with VMWare vSphere API.

It makes use of [our personal and updated fork](https://github.com/LeaseWeb/vmwarephp) of the [vadimcomanescu/vmwarephp](https://github.com/vadimcomanescu/vmwarephp) vSphere API client.

This is not supposed to be a bundle you can just import and use. This is work in progress, a lot has to be done yet, but it is a great start for anyone that needs a code base to interact with vSphere's complex RMI-SOAP API. If you are missing any functionality, feel free to make a pull request. You can find vSphere API documentation [here](http://pubs.vmware.com/vsphere-60/topic/com.vmware.wssdk.apiref.doc/right-pane.html).

# Requirements
* PHP >= 5.5.
* Symfony / Slim framework project (it can work with other frameworks, but we only tested in these two).
* Composer.
* PHP SOAP extension.

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
        'vcenter_host.mycompany.com',
        '443'
    );

    $vSphereCredentials = new VsphereCredentials(
        'username',
        'password'
    );

    return $vSphereService->configure($vSphereConfiguration, $vSphereCredentials);
}

public function indexAction(Request $request, $resourcePoolId)
{
    $vSphereAPIClient = $this->getVsphereClient();
    $resourcePool = $vSphereAPIClient->getResourcePool($resourcePoolId);
    var_dump($resourcePool);
    exit;
}
```


