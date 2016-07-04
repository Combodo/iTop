# Contributing to Emogrifier

Those that wish to contribute bug fixes, new features, refactorings and
clean-up to Emogrifier are more than welcome.

When you contribute, please take the following things into account:


## General workflow

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
run the following command:

    composer install


## Unit-test your changes

Please cover all changes with unit tests and make sure that your code does not
break any existing tests. We will only merge pull request that include full
code coverage of the fixed bugs and the new features.

To run the existing PHPUnit tests, run this command:

    vendor/bin/phpunit Tests/


## Coding Style

Please use the same coding style (PSR-2) as the rest of the code. Indentation
is four spaces.

We will only merge pull requests that follow the project's coding style.

Please check your code with the provided PHP_CodeSniffer standard:

    vendor/bin/phpcs --standard=Configuration/PhpCodeSniffer/Standards/Emogrifier/ Classes/ Tests/

Please make your code clean, well-readable and easy to understand.

If you add new methods or fields, please add proper PHPDoc for the new
methods/fields. Please use grammatically correct, complete sentences in the
code documentation.


## Git commits

Git commits should have a <= 50 character summary, optionally followed by a
blank line and a more in depth description of 79 characters per line.

[Please squash related commits together](http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html).

If you already have a commit and work on it, you can also
[amend the first commit](https://nathanhoad.net/git-amend-your-last-commit).

Please use grammatically correct, complete sentences in the commit messages.

Also, please prefix the subject line of the commit message with either
[FEATURE], [TASK], [BUGFIX] OR [CLEANUP]. This makes it faster to see what
a commit is about.