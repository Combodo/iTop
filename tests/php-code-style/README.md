Code formatting tool used by iTop is PHP-CS-Fixer:
https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/tree/master


to check code style issues (no path provided means whole iTop code base):

```
cd tests/php-code-style/; composer install; cd -
tests/php-code-style/vendor/bin/php-cs-fixer check --config tests/php-code-style/.php-cs-fixer.dist.php [PATH]
```

to respect iTop code standards and re-format (no path provided means whole iTop code base):

```
tests/php-code-style/vendor/bin/php-cs-fixer fix --config tests/php-code-style/.php-cs-fixer.dist.php [PATH]

```