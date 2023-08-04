# PHP unitary tests

## Where should I add my test?

- Covers an iTop PHP class or method?
  - Most likely in "unitary-tests".
- Covers the consistency of some data through the app?
  - Most likely in "integration-tests".

## How do I make sure that my tests are efficients?


### Derive from the relevant test class

Whenever possible keep it the most simple, hence you should first
attempt to derive from `TestCase`.

Then, you might need to derive from `ItopTestCase`.

Finally, as a last resort, you will use `ItopDataTestCase`.

### Determine the most relevant isolation configuration

Should you have opted for `ItopDataTestCase`, then you will have to follow these steps:

1) Build you test class until it is successfull
2) Run the whole test suite [unitary-tests](unitary-tests)
3) If a false-positive appears, then you will start troubleshooting

### Leave the place clean

Before reading further you will have to you will have to change your mindset:
> Yes I can

... and yes I will have to struggle a little bit more than just implementing a new test case.

FIXME : tools to detect harmful implementations

Principles :
* Configuration
* Data
* Dictionnary

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

This has been proven with [`runClassInSeparateProcessTest.php`](experiments/runClassInSeparateProcessTest.php)