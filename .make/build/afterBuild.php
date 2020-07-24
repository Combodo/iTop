<?php
$iBeginTime = time();

$aCommands = [
	'php composer/rmDeniedTestDir.php',
	'php build/commands/setupCssCompiler.php',
//	'bash /tmp/gabuzomeu.sh',
];

$aFailedCommands=[];
foreach ($aCommands as $sCommand)
{
	if (!ExecCommand($sCommand))
	{
		$aFailedCommands[] = $sCommand;
	}
}

$iElapsed = time() - $iBeginTime;

if (count($aFailedCommands))
{
	fwrite(STDERR, "\nafterBuild execution failed! (in ${iElapsed}s)\n");
	fwrite(STDERR, "List of failling commands:\n - " . implode("\n - ", $aFailedCommands) . "\n");
	exit(1);
}


echo "\nDone (${iElapsed}s)\n";
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


	echo sprintf("command: %s", str_pad("$cmd ", 50));

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
	$bSuccess = (0 === $iCode);

	$iElapsed = time() - $iBeginTime;
	if (!$bSuccess) {
		fwrite(STDERR, sprintf(
			"\nCOMMAND FAILED! (%s) \n - status:%s \n - stderr:%s \n - stdout: %s\n - elapsed:%ss\n\n",
			$cmd,
			$iCode,
			rtrim($stderr),
			rtrim($stdout),
			$iElapsed
		));
	}
	else
	{
		echo "| elapsed:${iElapsed}s \n";
	}

	if (!empty($stderr))
	 {
		 fwrite(STDERR, "$stderr\n");
	 }
	 if (!empty($stdout))
	 {
		 echo "stdout :$stdout\n\n";
	 }

	return $bSuccess;
}
