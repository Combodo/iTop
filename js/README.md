# JS for iTop

## File location

In order to better separate our developments from the embedded third-party libs, files should be moved in either the /components or /lib folders.

IMPORTANT: Before moving a file, we should first consider if:

- The file is used in an iTop extension that should be compatible with the iTop version currently under developments
- The file is still used in iTop itself (or if we can consider removing it for clean up)

## Leading semicolon

When concataining 2 files with code execution, we might have a prb.

For example :

```
// file A
(A)()
// file B
(B)()
```

Having `(A)()(B)()` is different than `(A)();(B)();` !

To avoid those problems simply add a leading semicolon on all JS files containing `()` at their end.

See https://stackoverflow.com/a/23056287