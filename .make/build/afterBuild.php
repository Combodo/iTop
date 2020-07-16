<?php

$aCommands = [
	'php composer/rmDeniedTestDir.php',
	'bash /tmp/gabuzomeu.sh',
];

$aFailedCommands=[];
foreach ($aCommands as $sCommand)
{
	if (!ExecCommand($sCommand))
	{
		$aFailedCommands[] = $sCommand;
	}
}

if (count($aFailedCommands))
{
	fwrite(STDERR, "afterBuild execution failed:\n" . implode("\n\t", $aFailedCommands) . "\n");
	exit(1);
}

exit(0);

/**
 * Executes a command and returns an array with exit code, stdout and stderr content
 *
 * @param string $cmd - Command to execute
 *
 * @return bool
 * @throws \Exception
 */
function ExecCommand($cmd) {
	$iBeginTime = time();

	$descriptorspec = array(
		0 => array("pipe", "r"),  // stdin
		1 => array("pipe", "w"),  // stdout
		2 => array("pipe", "w"),  // stderr
	);
	$process = proc_open($cmd, $descriptorspec, $pipes, __DIR__ . '/..', null);

	$stdout = stream_get_contents($pipes[1]);
	fclose($pipes[1]);

	$stderr = stream_get_contents($pipes[2]);
	fclose($pipes[2]);

	$iCode = proc_close($process);
	$bRes = (0 !== $iCode);

	$iElapsed = time() - $iBeginTime;
	if ($bRes) {
		fwrite(STDERR, "========= Command failed $cmd \n\t\t=== with status:$iCode \n\t\t=== stderr:$stderr \n\t\t=== stdout: $stdout\n");
	}
	echo "========= ELAPSED:${iElapsed}s \t cmd:$cmd \n";

	if (!empty($stderr))
	 {
		 echo "\t\t=== stderr:$stderr\n";
	 }
	 if (!empty($stdout))
	 {
		 echo "\t\t=== stdout:$stdout\n";
	 }

	return $bRes;
}
