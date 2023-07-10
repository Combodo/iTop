<?php
/**
 * This file is only here for compatibility reasons.
 * It will be removed in future iTop versions (N°6533)
 *
 * @deprecated 3.0.0 N°3663 Exception classes were moved to `/application/exceptions`, use autoloader instead of require !
 */

require_once __DIR__ . '/../approot.inc.php';

DeprecatedCallsLog::NotifyDeprecatedFile('Classes were moved to /application/exceptions and can be used directly with the autoloader');
