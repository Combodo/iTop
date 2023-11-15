# PHP unitary tests
## Where should I add my test?

- Covers an iTop PHP class or method?
  - Most likely in "unitary-tests".
- Covers the consistency of some data through the app?
  - Most likely in "integration-tests".

## Tips

### Measure the time spent in a test

Simply cut'n paste the following line at several places within the test function:

```php
if (isset($fStarted)) {echo 'L'.__LINE__.': '.round(microtime(true) - $fStarted, 3)."\n";} $fStarted = microtime(true);
```
