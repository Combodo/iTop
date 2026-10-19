<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\ItopCoreUpdate;

use Combodo\iTop\CoreUpdate\Service\CoreUpdater;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class SignatureValidationTest extends ItopTestCase
{
	private const RESOURCES_DIRECTORY = __DIR__.'/resources/';

	private string $sWorkDirectory = '';
	private string $sPublicKey;
	private string $sDestinationDirectory;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('datamodels/2.x/itop-core-update/src/Service/CoreUpdater.php');

		$this->sWorkDirectory = __DIR__.'/.signature-test-'.bin2hex(random_bytes(8));
		mkdir($this->sWorkDirectory, 0700, true);
		$this->sDestinationDirectory = $this->sWorkDirectory.'/installation';
		mkdir($this->sDestinationDirectory, 0700);

		$this->sPublicKey = file_get_contents(self::RESOURCES_DIRECTORY.'dummy-public.pem');
		file_put_contents($this->sDestinationDirectory.'/installation-sentinel.txt', 'unchanged');
	}

	protected function tearDown(): void
	{
		static::RecurseRmdir($this->sWorkDirectory);
		parent::tearDown();
	}

	public function testValidSignedPackageIsAccepted(): void
	{
		// Expected API: verify with an injected test public key before extracting to the supplied destination.
		CoreUpdater::ValidateAndExtractDownloadedFile(
			self::RESOURCES_DIRECTORY.'dummy-signed.zip',
			$this->sPublicKey,
			$this->sDestinationDirectory
		);

		$this->assertSame(
			file_get_contents(self::RESOURCES_DIRECTORY.'package/file1'),
			file_get_contents($this->sDestinationDirectory.'/file1')
		);
	}

	public function testPackageIsRejectedBeforeExtractionWhenManifestWasAlteredAfterSigning(): void
	{
		$this->AssertRejectedBeforeExtraction(self::RESOURCES_DIRECTORY.'dummy-altered-manifest.zip');
	}

	public function testPackageIsRejectedBeforeExtractionWhenCoveredFileWasAltered(): void
	{
		$this->AssertRejectedBeforeExtraction(self::RESOURCES_DIRECTORY.'dummy-altered-content.zip');
	}

	public function testPackageWithInvalidSignatureIsRejectedBeforeExtraction(): void
	{
		$this->AssertRejectedBeforeExtraction(self::RESOURCES_DIRECTORY.'dummy-badly-signed.zip');
	}

	public function testPackageWithoutSignatureIsRejectedBeforeExtraction(): void
	{
		$this->AssertRejectedBeforeExtraction(self::RESOURCES_DIRECTORY.'dummy-unsigned.zip');
	}

	public function testPackageWithoutManifestIsRejectedBeforeExtraction(): void
	{
		$this->AssertRejectedBeforeExtraction(self::RESOURCES_DIRECTORY.'dummy-missing-manifest.zip');
	}

	private function AssertRejectedBeforeExtraction(string $sArchivePath): void
	{
		$aInstallationBefore = $this->SnapshotDirectory($this->sDestinationDirectory);
		$bRejected = false;
		try {
			CoreUpdater::ValidateAndExtractDownloadedFile(
				$sArchivePath,
				$this->sPublicKey,
				$this->sDestinationDirectory
			);
		} catch (\UnexpectedValueException $e) {
			$bRejected = true;
		}

		$this->assertTrue($bRejected, 'The invalid update package was not rejected.');
		$this->assertSame($aInstallationBefore, $this->SnapshotDirectory($this->sDestinationDirectory));
		$this->assertFileDoesNotExist($this->sDestinationDirectory.'/file1');
	}

	private function SnapshotDirectory(string $sDirectory): array
	{
		$aSnapshot = [];
		$oIterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($sDirectory, RecursiveDirectoryIterator::SKIP_DOTS)
		);
		foreach ($oIterator as $oFile) {
			if ($oFile->isFile()) {
				$sRelativePath = substr($oFile->getPathname(), strlen($sDirectory) + 1);
				$aSnapshot[$sRelativePath] = hash_file('sha256', $oFile->getPathname());
			}
		}
		ksort($aSnapshot);

		return $aSnapshot;
	}

}
