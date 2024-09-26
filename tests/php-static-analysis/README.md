# PHP static analysis

- [Installation](#installation)
- [Usages](#usages)
  - [Analysing a package](#analysing-a-package)
  - [Analysing a module](#analysing-a-module)
- [Configuration](#configuration)
  - [Adjust local configuration to your needs](#adjust-local-configuration-to-your-needs)
  - [Adjust configuration for a particular CI repository / job](#adjust-configuration-for-a-particular-ci-repository--job)

## Installation
- Install dependencies by running `composer install` in this folder
- You should be all set! 🚀

## Usages
### Analysing a package
_Do this if you want to analyse the whole iTop package (iTop core, extensions, third-party libs, ...)_

- Make sure you ran a setup on your iTop as it will analyse the `env-production` folder
- Open a prompt in your iTop folder
- Run the following command
  ```bash
  tests/php-static-analysis/vendor/bin/phpstan analyse \
    --configuration ./tests/php-static-analysis/config/for-package.dist.neon \
    --error-format raw
  ```
  
You will then have an output like this listing all errors:
```bash
tests/php-static-analysis/vendor/bin/phpstan analyse \
  --configuration ./tests/php-static-analysis/config/for-package.dist.neon \
  --error-format raw
  
 1049/1049 [============================] 100%

<ITOP>\addons\userrights\userrightsprofile.class.inc.php:552:Call to static method InitSharedClassProperties() on an unknown class SharedObject.
<ITOP>\addons\userrights\userrightsprofile.db.class.inc.php:927:Call to static method GetSharedClassProperties() on an unknown class SharedObject.
<ITOP>\addons\userrights\userrightsprojection.class.inc.php:722:Access to an undefined property UserRightsProjection::$m_aClassProjs.
<ITOP>\application\applicationextension.inc.php:295:Method AbstractPreferencesExtension::ApplyPreferences() should return bool but return statement is missing.
<ITOP>\application\cmdbabstract.class.inc.php:1010:Class utils referenced with incorrect case: Utils.
[...]
```

### Analysing a module
_Do this if you only want to analyse one or more modules within this iTop but not the whole package_

- Make sure you ran a setup on your iTop as it will analyse the `env-production` folder
- Open a prompt in your iTop folder
- Run the following command
  ```
  tests/php-static-analysis/vendor/bin/phpstan analyse \
    --configuration ./tests/php-static-analysis/config/for-package.dist.neon \
    --error-format raw \
    env-production/<MODULE_CODE_1> [env-production/<MODULE_CODE_2> ...]
  ```

You will then have an output like this listing all errors:
```
  tests/php-static-analysis/vendor/bin/phpstan analyse \
    --configuration ./tests/php-static-analysis/config/for-module.dist.neon \
    --error-format raw \
    env-production/authent-ldap env-production/itop-oauth-client
  
 49/49 [============================] 100%

<ITOP>\env-production\authent-ldap\model.authent-ldap.php:79:Undefined variable: $hDS
<ITOP>\env-production\authent-ldap\model.authent-ldap.php:80:Undefined variable: $name
<ITOP>\env-production\authent-ldap\model.authent-ldap.php:80:Undefined variable: $value
<ITOP>\env-production\itop-oauth-client\vendor\composer\InstalledVersions.php:105:Parameter $parser of method Composer\InstalledVersions::satisfies() has invalid type Composer\Semver\VersionParser.
[...]
```

## Configuration
### Adjust local configuration to your needs
#### Define which PHP version to run the analysis for
The way we configured PHPStan in this project changes how it will find the PHP version to run the analysis for. \
By default PHPStan check the information from the composer.json file, but we changed that (via the `config/php-includes/set-php-version-from-process.php` include) so it used the PHP 
version currently ran by the CLI.

So all you have to do is either:
- Prepend your command line with the path of the executable of the desired PHP version
- Change the default PHP interpreter in your IDE settings

#### Change some parameters for a local run
If you want to change some particular settings (eg. the memory limit, the rules level, ...) for a local run of the analysis you have 2 choices.

##### Method 1: CLI parameter
For most parameters there is a good chance you can just add the parameter and its value in your command, which will override the one defined in the configuration file. \
Below are some example, but your can find the complete reference [here](https://phpstan.org/user-guide/command-line-usage).

```bash
--memory-limit 1G
--level 5
--error-format raw
[...]
```

**Pros** Quick and easy to try different parameters \
**Cons** Parameters aren't saved, so you'll have to remember them and put them again next time

##### Method 2: Configuration file
Crafting your own configuration file gives you the ability to fine tune any parameters, it's way more powerful but can also quickly lead to crashes if you mess with the symbols discovery (classes, ...). \
But mostly it can be saved, shared, re-used; which is it's main purpose.

It is recommended that you create your configuration file from scratch and that you include the `base.dist.neon` so you are bootstrapped for the symbols discovery. Then you can override any parameter. \
Check [the documentation](https://phpstan.org/config-reference#multiple-files) for more information.

```neon
includes:
    - base.dist.neon

parameters:
    # Override parameters here
```

#### Analyse only one (or some) folder(s) quicker
It's pretty easy and good news you don't need to create a new configuration file or change an existing one. \
Just adapt and use command lines from the [usages section](#usages) and add the folders you want to analyse at the end of the command, exactly like when analysing modules.

For example if you want to analyse just `<ITOP>/setup` and `<ITOP>/sources`, use something like:
```
tests/php-static-analysis/vendor/bin/phpstan analyse \
  --configuration ./tests/php-static-analysis/config/for-package.dist.neon \
  --error-format raw \
  setup sources
```

### Adjust configuration for a particular CI repository / job
TODO