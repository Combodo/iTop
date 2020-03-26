# CAS

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
