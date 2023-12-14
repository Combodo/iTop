# PHP unitary tests

Documentation on creating and maintaining tests in iTop.


Table of content:
<!-- TOC -->
* [Prerequisites](#prerequisites)
* [Create an iTop PHPUnit test](#create-an-itop-phpunit-test)
* [Tips: generic PHPUnit](#tips-generic-phpunit)
* [Tips: iTop tests](#tips-itop-tests)
* [Test performances](#test-performances)
* [PHPUnit process isolation](#phpunit-process-isolation)
<!-- TOC -->



## Prerequisites

### PHPUnit configuration file 
A default file is located in `/tests/php-unit-tests/phpunit.xml.dist`

If you need to customize it, copy it to `phpunit.xml` (not versioned). 

### PHP configuration
* PHPUnit configuration file
  - `memory_limit`: as the tests are for the most part ran in the same process, memory usage may become an issue! A default value is set in default PHPUnit configuration XML file, don't hesitate to update it if needed
* PHP CLI php.ini
  - enable OpCache
  - disable Xdebug (xdebug.mode=off) : huge performance improvements (between X2 and X3), and we can still debug using PHPStorm !

### Dependencies
Whereas iTop dependencies are bundled inside its repository, the tests dependencies are not, and must be added manually. To do so, run `composer install` in the `/tests/php-unit-tests` directory. 

### iTop instance prerequisites to run its test suite
Install iTop with default setup options :
- Configuration Management options : everything checked
- Service Management for Enterprises
- Simple Ticket Management + Customer portal
- Simple Change Management

Plus :
-  Additional ITIL tickets : check "Known Errors Management and FAQ"





## Create an iTop PHPUnit test

### Where should I add my test?
- Covers an iTop PHP class or method? => Most likely in the `unitary-tests` directory
- Covers the consistency of some data through the app? => Most likely in `integration-tests` directory

### iTop test parent classes
iTop provides PHPUnit TestCase children that provides some helpers and setUp/tearDown overrides : 
- `\Combodo\iTop\Test\UnitTest\ItopTestCase` : for the most simple iTop tests
- `\Combodo\iTop\Test\UnitTest\ItopDataTestCase` : to get a started metamodel and have cleanup of CRUD operations on iTop objects (transactions by default)
- `\Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase` : to test a non standard datamodel (available since iTop 2.7.9, 3.0.4, 3.1.0 N°6097)


### Naming convention
* to test `MyClass` class then create a `MyClassTest` class
* to test `MyMethod` method, the corresponding test method should be named `testMyMethod`

Source [PHPUnit Manual – Chapter 2. Writing Tests for PHPUnit](https://docs.phpunit.de/en/9.6/writing-tests-for-phpunit.html#writing-tests-for-phpunit)






## Tips: generic PHPUnit

### Disable a test
```php
$this->markTestSkipped('explanation');
```

### Test an exception
Just before calling the code throwing the exception, call `\PHPUnit\Framework\TestCase::expectException`. You might also use `expectExceptionMessage` and/or `expectExceptionMessageMatches`.

Example : 

```php
		// Try to delete the tag, must complain !
		$this->expectException(DeleteException::class);
		$this->expectExceptionMatches('/'.$this->GetKey().'/');
		$oTagData->DBDelete();
```

Warning : when the condition is met the test is finished and following code will be ignored !

Another way to do is using try/catch blocks, for example : 
```php
        $validator = new FormValidator();
 
        try {
            $validator->validate(
                new DateTimeImmutable('2020-01-01'),
                new DateTimeImmutable('1999-01-01'),
                -3,
                ''
            );
            $this->fail('FormValidationException was not thrown');
	} catch (AssertionFailedError $e) {
		throw $e; // handles the fail() call just above
        } catch (FormValidationException $e) {
            $this->assertSame(
                [
                    'End must be after start',
                    'The new id must be greater than 0',
                    'Description can not be empty',
                ],
                $e->getErrors()
            );
        }
```





## Tips: iTop tests

### Load an iTop class which is outside the autoloader
When running a test extending ItopDataTestCase you'll get all of the iTop classes loaded (Composer autoloader + iTop autoloader including installed modules)

For ItopTestCase files, you may need to load specific iTop classes that aren't part of the Composer autoloader. If so, since N°5608 (introduced in iTop 2.7.9, 3.0.3, 3.1.0-1) you can use :
- ItopTestCase::RequireOnceItopFile
- ItopTestCase::RequireOnceUnitTestFile

### Add a User context
Use `UserRights::Login()`






## Test performances

### Measure the time spent in a test

Simply cut'n paste the following line at several places within the test function:

```php
if (isset($fStarted)) {echo 'L'.__LINE__.': '.round(microtime(true) - $fStarted, 3)."\n";} $fStarted = microtime(true);
```

### Derive from the relevant test class

Whenever possible keep it the most simple, hence you should first
attempt to derive from `TestCase`.

Then, you might need to derive from `ItopTestCase`.

Finally, as a last resort, you will use `ItopDataTestCase`.

### Determine the most relevant isolation configuration

Should you have opted for `ItopDataTestCase`, then you will have to follow these steps:

1) Build you test class until it is successfull, without process isolation.
2) Run the whole test suite [unitary-tests](unitary-tests)
3) If a false-positive appears, then you will start troubleshooting. One advise: be positive!

### Leave the place clean

To check your code against polluting coding patterns, run the test [integration-tests/DetectStaticPollutionTest.php](integration-tests/DetectStaticPollutionTest.php)
It will tell you if something is wrong, either in your code, or anywhere else in the tests.
Fortunately, it will give you an alternative.

Detected patterns:
* ContextTag::addTag()
* EventService::RegisterListener()
* Dict::Add()


By the way, some patterns do not pollute, because they are handled by the test framework:
* Configuration : automatically reset after test class execution
* UserRights : a logoff is performed after each test execution
* Dict::SetUserLanguage: the user language is reset after each test execution

See also `@beforeClass` and `@afterClass` to handle cleanup.

If you can't, then ok you will have to isolate it!






## PHPUnit process isolation

### Understand tests interactions

With PHPStorm, select two tests, right click to get the context menu, then `run`.

You will have both tests executed and you will be able to figure out if the first one has an impact on the second one.

### About process isolation
#### Isolation with PHPUnit

By default, tests are run in a single process launched by PHPUnit.

If process isolation is configured for some tests, then those tests
will be executed in a separate process. The main process will
continue executing non isolated tests.

#### Cost of isolation

The cost of isolating a very basic `TestCase` is approximately 4 ms.

The cost of isolating an `ItopDataTestCase` is approximately 800 ms.

### Isolation within iTop

#### At the test level (preferred)
Add annotation `@runInSeparateProcess`
Each and every test case will run in a separate
process.

Note : before N°6658 (3.0.4 / 3.1.1 / 3.2.0) we were also adding the `@backupGlobals disabled`
and `@preserveGlobalState disabled` annotations. This is no longer necessary as the first has this default value
already, and the second one is now set in iTopTestCase as a PHP class attribute.

#### At the test class level
Add annotation `@runTestsInSeparateProcesses`
Each and every test case in the class will run in a separate
process.

#### Globally (never do that)
Set it into [phpunit.xml.dist](phpunit.xml.dist)

### Further enhancements
The annotation [`@runClassInSeparateProcess`](https://docs.phpunit.de/en/10.0/attributes.html?highlight=runclassinseparateprocess#runclassinseparateprocess) is supposed to do the perfect job, but it is buggy  [(See Issue 5230)](https://github.com/sebastianbergmann/phpunit/issues/5230) and it has
the exact same effect as `@runTestsInSeparateProcesses`.

Note : this option is documented only in the [attributes part of the documentation](https://docs.phpunit.de/en/10.0/attributes.html).

### Traps

#### Doc block comment format : when it is a matter of stars
```php
/*
 * @runTestsInSeparateProcesses
```
This won't work because the comment MUST start with `/**` (two stars) to be considerer by PHPUnit.

#### SetupBeforeClass called more often than expected when running in separate processes

`setupBeforeClass` is called once for the class **in a given process**.

Therefore, if the tests are isolated, then `setupBeforeClass` will be called as often as `setUp`.

