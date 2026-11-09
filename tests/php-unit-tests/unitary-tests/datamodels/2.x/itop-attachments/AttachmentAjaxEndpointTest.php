<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\ItopAttachment;

use Combodo\iTop\Attachments\Enum\AttachmentAjaxErrorEnum;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use UserRequest;

class AttachmentAjaxEndpointTest extends ItopDataTestCase
{
	public const  USE_TRANSACTION         = false;
	private const AUTHENTICATION_PASSWORD = 'tagada-Secret,007';

	protected function setUp(): void
	{
		parent::setUp();

		$this->BackupConfiguration();
		$this->AddLoginModeAndSaveConfiguration('url');

		$this->CreateTestOrganization();
		$this->CreateUserRequest(101);
	}

	/**
	 * @dataProvider AjaxEndpointAccessProvider
	 */
	public function testAjaxEndpointAccess(string $sProfile, array $aQueryParams, int $iExpectedHttpCode, mixed $oExpectedResponse = ''): void
	{
		$sLogin = 'user-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles[$sProfile], self::AUTHENTICATION_PASSWORD);

		$aResponse = $this->CallAttachmentEndpointAs($sLogin, $aQueryParams);

		if (is_array($oExpectedResponse)) {
			$aJson = json_decode($aResponse['response'], true);
			foreach ($oExpectedResponse as $sKey => $sValue) {
				$this->assertEquals($oExpectedResponse[$sKey], $aJson[$sKey], "Expected JSON key '$sKey' to contain '$sValue'");
			}
		} else {
			$this->assertEquals($oExpectedResponse, $aResponse['response']);
		}

		$this->assertSame($iExpectedHttpCode, $aResponse['code']);
	}

	public function AjaxEndpointAccessProvider(): array
	{
		$this->RequireOnceItopFile('datamodels/2.x/itop-attachments/src/Enum/AttachmentAjaxErrorEnum.php');

		return [
			'Service Desk Agent: missing obj_class' => [
				'Service Desk Agent',
				[
					'operation' => 'add',
				],
				200,
				[
					'error_code' => AttachmentAjaxErrorEnum::MISSING_ARG_OBJ_CLASS->value,
				],
			],
			'Service Desk Agent: missing temp_id'   => [
				'Service Desk Agent',
				[
					'operation' => 'add',
					'obj_class' => UserRequest::class,
				],
				200,
				[
					'error_code' => AttachmentAjaxErrorEnum::MISSING_ARG_TEMP_ID->value,
				],
			],
			'Service Desk Agent: empty file'        => [
				'Service Desk Agent',
				[
					'operation' => 'add',
					'obj_class' => UserRequest::class,
					'temp_id'   => '123456',
				],
				200,
				[
					'error_code' => AttachmentAjaxErrorEnum::UPLOAD_FILE_EMPTY->value,
				],
			],
			'Service Desk Agent: access restricted' => [
				'Document author',
				[
					'operation' => 'add',
					'obj_class' => UserRequest::class,
					'temp_id'   => '123456',
					'file'      => curl_file_create(__FILE__, 'text/plain', 'test.txt'),
				],
				200,
				[
					'error_code' => AttachmentAjaxErrorEnum::ACTION_NOT_ALLOWED->value,
				],
			],
			'portal user'                           => [
				'Portal user',
				[],
				302, // redirect to portal
				[],
			],
			'Service Desk Agent: preview'           => [
				'Service Desk Agent',
				[
					'operation' => 'refresh_attachments_render',
					'objclass'  => 'UserRequest',
					'objkey'    => 101,
					'temp_id'   => '123456',
				],
				200,
				'No attachment.',
			],
		];
	}

	private function CallAttachmentEndpointAs(string $sLogin, array $aQueryParams = []): array
	{
		$sResponse = $this->CallItopUri(
			'env-production/itop-attachments/ajax.itop-attachment.php',
			array_merge($aQueryParams, [
				'auth_user' => $sLogin,
				'auth_pwd'  => self::AUTHENTICATION_PASSWORD,
			]),
			[
				CURLOPT_HTTPHEADER => ['X-Combodo-Ajax:1'],
				CURLOPT_POST       => 0,
			],
			true
		);

		return [
			'code'     => $this->aLastCurlGetInfo['http_code'],
			'response' => $sResponse,
		];
	}
}
