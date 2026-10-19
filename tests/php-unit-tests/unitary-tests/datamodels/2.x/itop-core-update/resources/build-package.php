<?php

// Usage: php build-package.php <content-dir> <private-key.pem> <output.zip>

[$script, $contentDir, $privateKeyPath, $outputZip] = $argv;

$contentDir   = rtrim(realpath($contentDir), '/');
$manifestName = 'manifest.xml';
$signatureName = 'manifest.xml.sig'; // TODO: use the name the module expects

// 1. List files (excluding manifest and signature), sorted for a stable output
$files = [];
$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator($contentDir, FilesystemIterator::SKIP_DOTS)
);
foreach ($iterator as $file) {
	$relativePath = substr($file->getPathname(), strlen($contentDir) + 1);
	if (in_array($relativePath, [$manifestName, $signatureName], true)) {
		continue;
	}
	$files[] = $relativePath;
}
sort($files);

// 2. Build manifest.xml in your format
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<files>\n";
foreach ($files as $relativePath) {
	$fullPath = "$contentDir/$relativePath";
	$xml .= sprintf(
		"<file><path>%s</path><md5>%s</md5><size>%d</size></file>\n",
		htmlspecialchars($relativePath, ENT_XML1),
		md5_file($fullPath),
		filesize($fullPath)
	);
}
$xml .= "</files>\n";
file_put_contents("$contentDir/$manifestName", $xml);

// 3. Sign the manifest
$privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
openssl_sign($xml, $signature, $privateKey, OPENSSL_ALGO_SHA256);
file_put_contents("$contentDir/$signatureName", $signature); // or base64_encode($signature)

// 4. Zip everything
$zip = new ZipArchive();
$zip->open($outputZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
foreach (array_merge($files, [$manifestName, $signatureName]) as $relativePath) {
	$zip->addFile("$contentDir/$relativePath", $relativePath);
}
$zip->close();

echo "Built $outputZip with ".count($files)." files\n";
