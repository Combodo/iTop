# Contributing to Emogrifier

Those that wish to contribute bug fixes, new features, refactorings and
clean-up to Emogrifier are more than welcome.

When you contribute, please take the following things into account:


## Contributor Code of Conduct

Please note that this project is released with a
[Contributor Code of Conduct](../CODE_OF_CONDUCT.md). By participating in this
project, you agree to abide by its terms.


## General workflow

This is the workflow for contributing changes to Emogrifier:

1. [Fork the Emogrifier Git repository](https://guides.github.com/activities/forking/).
2. Clone your forked repository and
   [install the development dependencies](#install-the-development-dependencies).
3. Add a local remote "upstream" so you will be able to
   [synchronize your fork with the original Emogrifier repository](https://help.github.com/articles/syncing-a-fork/).
4. Create a local branch for your changes.
5. [Add unit tests for your changes](#unit-test-your-changes).
   These tests should fail without your changes.
6. Add your changes. Your added unit tests now should pass, and no other tests
   should be broken. Check that your changes follow the same
   [coding style](#coding-style) as the rest of the project.
7. Add a changelog entry.
8. [Commit](#git-commits) and push your changes.
9. [Create a pull request](https://help.github.com/articles/about-pull-requests/)
   for your changes. Check that the Travis build is green. (If it is not, fix the
   problems listed by Travis.)
10. [Request a review](https://help.github.com/articles/about-pull-request-reviews/)
    from @oliverklee.
11. Together with him, polish your changes until they are ready to be merged.


## About code reviews

After you have submitted a pull request, the Emogrifier team will review your
changes. This will probably result in quite a few comments on ways to improve
your pull request. The Emogrifier project receives contributions from
developers around the world, so we need the code to be the most consistent,
readable, and maintainable that it can be.

Please do not feel frustrated by this - instead please view this both as our
contribution to your pull request as well as a way to learn more about
improving code quality.

If you would like to know whether an idea would fit in the general strategy of
the Emogrifier project or would like to get feedback on the best architecture
for your ideas, we propose you open a ticket first and discuss your ideas there
first before investing a lot of time in writing code.


## Install the development dependencies

To install the development dependencies (PHPUnit and PHP_CodeSniffer), please
run the following commands:

```shell
composer install
composer require --dev slevomat/coding-standard:^4.0
```

Note that the development dependencies (in particular, for PHP_CodeSniffer)
require PHP 7.0 or later.  The second command installs the PHP_CodeSniffer
dependencies and should be omitted if specifically testing against an earlier
version of PHP, however you will not be able to run the static code analysis.


## Unit-test your changes

Please cover all changes with unit tests and make sure that your code does not
break any existing tests. We will only merge pull requests that include full
code coverage of the fixed bugs and the new features.

To run the existing PHPUnit tests, run this command:

```shell
composer ci:tests:unit
```


## Coding Style

Please use the same coding style (PSR-2) as the rest of the code. Indentation
is four spaces.

We will only merge pull requests that follow the project's coding style.

Please check your code with the provided static code analysis tools:

```shell
composer ci:static
```

Please make your code clean, well-readable and easy to understand.

If you add new methods or fields, please add proper PHPDoc for the new
methods/fields. Please use grammatically correct, complete sentences in the
code documentation.

You can autoformat your code using the following command:

```shell
composer php:fix
```


## Git commits

Commit message should have a <= 50 character summary, optionally followed by a
blank line and a more in depth description of 79 characters per line.

Please use grammatically correct, complete sentences in the commit messages.

Also, please prefix the subject line of the commit message with either
[FEATURE], [TASK], [BUGFIX] OR [CLEANUP]. This makes it faster to see what
a commit is about.


## Creating pull requests (PRs)

When you create a pull request, please
[make your PR editable](https://github.com/blog/2247-improving-collaboration-with-forks).
