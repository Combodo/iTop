From https://github.com/pear/Archive_Tar

Combodo modifications are inside those comments :
```php
// --- START COMBODO modification
// --- END COMBODO modification
```

# iTop 2.5.0 : N°1188
Lib integrated in setup/tar.php, renamed class to the iTop standard (ArchiveTar instead of Archive_Tar)  
Based on [v1.4.4](https://github.com/pear/Archive_Tar/blob/1.4.4/Archive/Tar.php) but with some modifications.

# iTop 2.5.2 : N°2033
Combodo modifications are now wrapped in ITopArchiveTar... But there is still a modification remaining in the code : bigger read buffer to 
improve perf.

The bigger read buffer was submited with [PR#23](https://github.com/pear/Archive_Tar/pull/23), merged and integrated in [v1.4.7](https://github.com/pear/Archive_Tar/blob/1.4.7/Archive/Tar.php). 

# iTop 2.7.0 : N°2150
Integration of [v1.4.7](https://github.com/pear/Archive_Tar/blob/1.4.7/Archive/Tar.php).  
Added also the PEAR.php dependency v1.10.9.