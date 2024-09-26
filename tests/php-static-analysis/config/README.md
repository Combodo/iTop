## Disclaimer
DON'T modify the following files without knowledge and discussing with the team:
- base.dist.neon
- for-package.dist.neon
- for-module.dist.neon

## Purpose of these files
### base.dist.neon
This configuration file contains the common parameters for all analysis, whereas it is a package, a module or something specific. Among others:
- Rules level for analysis
- PHP version to compare
- Necessary files for autoloaders discovery and such
- ...

This file should not be modified for your specific needs, you should always include it and override the desired parameters. \
See how it is done in `for-package.dist.neon` and `for-module.dist.neon` or on the documentation [here](https://phpstan.org/config-reference#multiple-files).

### for-package.dist.neon
This configuration file contains the parameters to analyse a package (iTop core, modules, third-party libs).

### for-module.dist.neon
This configuration file contains the parameters to analyse one or more modules only.

## How / when can I modify these files?
**You CAN'T!** \
Well, unless there is a good reason and you talked about it with the team. But you should never modify them for a specific need on your local environment.

- If you have a particular need for your local environment (eg. increase memory limit, change rules levels, analyse only a specific folder), check the [Configuration section](../#configuration) of the main README.md.
- If you feel like there is need for an adjustment in the default configurations, discuss it with th team and make a PR.