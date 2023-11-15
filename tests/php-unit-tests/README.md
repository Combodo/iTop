# PHP unitary tests

## Where should I add my test?

- Covers an iTop PHP class or method?
  - Most likely in "unitary-tests".
- Covers the consistency of some data through the app?
  - Most likely in "integration-tests".

## How do I make sure that my tests are efficient?


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

## Tips
### Memory limit

As the tests are run in the same process, memory usage
may become an issue as soon as tests are all executed at once.

Fix that in the XML configuration in the PHP section
```xml
<ini name="memory_limit" value="512M"/>
```


### Measure the time spent in a test

Simply cut'n paste the following line at several places within the test function:

```php
if (isset($fStarted)) {echo 'L'.__LINE__.': '.round(microtime(true) - $fStarted, 3)."\n";} $fStarted = microtime(true);
```


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
#### When it is a matter of stars
```php
/*
 * @runTestsInSeparateProcesses
```
This won't work because the comment MUST start with `/**` (two stars) to be considerer by PHPUnit.

#### SetupBeforeClass called more often than expected

`setupBeforeClass` is called once for the class **in a given process**.

Therefore, if the tests are isolated, then `setupBeforeClass` will be called as often as `setUp`.

