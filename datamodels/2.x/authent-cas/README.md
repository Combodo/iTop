# CAS

## CAS Server
In order to test the refactoring of the integration of Central Authentication Server (*CAS*) Single Sign On in iTop, a CAS server is needed. 

Such a server was installed and configured on a stand alone Virtual Box VM, which image "**XUbuntu 18.04 - CAS.ova**" is available on the NAS. By booting the VM you can quickly deploy a [CAS server](https://cas-server.combodo.net:8443/cas) connected to a [LDAP server](https://cas-server.combodo.net/phpldapadmin).

The login to the machine is **combodo/c8mb0do** and then you can *sudo* if needed.

It's better to connect to the machine via SSH in order to have a bigger and resizable console, copy/paste capability, etc.

**Notes**
 - The VM requires **6 GB** of memory to run.
 - `chrony` is installed on the system. To resynchronize the date/time of the VM, simply run: `sudo chronyd -q`


The VM has two network interfaces configured, and can be reached at **cas-server.combodo.net** (192.168.56.200).

Once the VM is up and running, the CAS server itself is located at: <https://cas-server.combodo.net:8443/cas>

For more information about the Tomcat and CAS configuration, have a look at: <https://cas-server.combodo.net:8443>

The Tomcat administration login is **admin/admin**.

The following user accounts are available in LDAP *(For each of these users the password is equal to the login)*:
 - agavalda
 - bhinault
 - bvian
 - cmonet
 - jverne
 - lfignon
 - rpoulidor

### iTop Configuration to use CAS

You can configure an iTop 2.x to use CAS for authentication, but this requires the download of the **phpCAS** library: [JASIG phpCAS](https://github.com/apereo/phpCAS).

To do so, set or change the following parameters in the iTop configuration file:

```php     
'allowed_login_types' => 'cas|form|basic|external', //'cas' first, means login will be automatically redirected to CAS

'cas_include_path' => '/usr/share/php', // Path to the folder containing the CAS.php file
'cas_host' => 'cas-server.combodo.net',
'cas_port' => 8443,
'cas_context' => '/cas',
'cas_debug' => true,
'cas_version' => 'S1',
```

## authent-cas extension

This extension is a quick port of the previous implementation of the the CAS SSO into iTop. For backward compatibility it retains the same parameters (which are part of the global config and not part of the *authent-cas module* configuration).

The library used is still **phpCAS** (now under the Apereo umbrella and more or less maintained...) <https://github.com/apereo/phpCAS>.

This extension shows the usage of a custom template for the button.

### Remaining work to be done

 - Decoupling of the **automatic provisionning** system from CAS Authentication
