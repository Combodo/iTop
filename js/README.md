In order to better separate our developments from the embedded third-party libs, files should be moved in either the /components or /lib folders.

IMPORTANT: Before moving a file, we should first consider if: 
- The file is used in an iTop extension that should be compatible with the iTop version currently under developments
- The file is still used in iTop itself (or if we can consider removing it for clean up)