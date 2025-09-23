<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\Helper;

use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @covers WebPage
 */
class CKEditorHelperTest extends ItopTestCase
{
	/**
	 * @dataProvider CheckFilesExistProvider
	 * @param string $sMethodName
	 */
	public function testCheckFilesExist($sMethodName)
	{
		$aFilesRelPaths = CKEditorHelper::$sMethodName();
		foreach ($aFilesRelPaths as $sFileRelPath) {
			$this->assertTrue(file_exists(APPROOT.$sFileRelPath), $sMethodName.' method returns a non existing file: '.$sFileRelPath);
		}
	}

	public function CheckFilesExistProvider(): array
	{
		return [
			'GetJSFilesRelPathsForCKEditor' => ['GetJSFilesRelPathsForCKEditor'],
		];
	}

	/**
	 * @dataProvider DOMSanitizerForCKEditorProvider
	 *
	 * @param $aSanitizerConfiguration
	 * @param $aExpectedCKEditorConfiguration
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testDOMSanitizerForCKEditor($aSanitizerConfiguration, $aExpectedCKEditorConfiguration)
	{
		$oSanitizer = new TestDOMSanitizer();
		$oSanitizer->SetTagsWhiteList($aSanitizerConfiguration['tagsWhiteList']);
		$oSanitizer->SetAttrsWhiteList($aSanitizerConfiguration['attrsWhiteList']);
		$oSanitizer->SetStylesWhiteList($aSanitizerConfiguration['stylesWhiteList']);
		$oSanitizer->SetTagsBlackList($aSanitizerConfiguration['tagsBlackList']);
		$oSanitizer->SetAttrsBlackList($aSanitizerConfiguration['attrsBlackList']);
		
		$aCKEditorConfiguration = CKEditorHelper::GetDOMSanitizerForCKEditor($oSanitizer);
		$this->assertEquals($aExpectedCKEditorConfiguration, $aCKEditorConfiguration);
	}
	
	public function DOMSanitizerForCKEditorProvider(): array
	{
		return [
			'Allow list small dataset' => [
				[
					'tagsWhiteList' => [		
						'html' => array(),
						'p' => array('style', 'class'),
						'a' => array('href', 'name'),
					],
					'attrsWhiteList' => [
						'href' => '/^(https:)/i'
					],
					'stylesWhiteList' => [
						'color',
						'font-size'
					],
					'tagsBlackList' => [],
					'attrsBlackList' => [],
				],
				[
					'allow' => [
						[
							'name' => 'html',
							'attributes' => false,
							'classes' => false,
							'styles' => false
						],
						[
							'name' => 'p',
							'attributes' => false,
							'classes' => true,
							'styles' => [
								'color' => true,
								'font-size' => true
							]
						],
						[
							'name' => 'a',
							'attributes' => [
								'href' => [
									'pattern' => '/^(https:)/i'
								],
								'name' => true
							],
							'classes' => false,
							'styles' => false
						]
					],
					'disallow' => []
				]
			],
			'Allow list medium dataset' => [
				[
					'tagsWhiteList' => [
						'h1' => array('style', 'class'),
						'h2' => array('style', 'class'),
						'h3' => array('style', 'class'),
						'h4' => array('style', 'class'),
						'table' => array('style', 'class', 'width', 'summary', 'align', 'border', 'cellpadding', 'cellspacing', 'style'),
						'tr' => array('style', 'class', 'align', 'valign', 'bgcolor', 'style'),
						'ul' => array(),
						'ol' => array(),
					],
					'attrsWhiteList' => [
						'href' => '/^(https:)/i',
						'src' => '/^(https:)/i',
						'width' => '/^([0-9]+(px|em|%)?)$/i',
						'height' => '/^([0-9]+(px|em|%)?)$/i',
						'align' => '/^(left|right|center|justify)$/i',
						'valign' => '/^(top|middle|bottom)$/i',
						'bgcolor' => '/^#[0-9a-f]{6}$/i',
					],
					'stylesWhiteList' => [
						'color',
						'float',
						'font',
						'font-family',
						'font-size',
						'font-style',
						'height',
						'margin',
						'padding',
						'text-align',
						'vertical-align',
						'width',
						'white-space',
					],
					'tagsBlackList' => [],
					'attrsBlackList' => [],
				],
				[
					'allow' => [
						[
							'name' => 'h1',
							'attributes' => false,
							'classes' => true,
							'styles' => [
								'color' => true,
								'font' => true,
								'font-family' => true,
								'font-size' => true,
								'font-style' => true,
								'height' => true,
								'margin' => true,
								'padding' => true,
								'text-align' => true,
								'vertical-align' => true,
								'width' => true,
								'white-space' => true,
								'float' => true
							]
						],
						[
							'name' => 'h2',
							'attributes' => false,
							'classes' => true,
							'styles' => [
								'color' => true,
								'font' => true,
								'font-family' => true,
								'font-size' => true,
								'font-style' => true,
								'height' => true,
								'margin' => true,
								'padding' => true,
								'text-align' => true,
								'vertical-align' => true,
								'width' => true,
								'white-space' => true,
								'float' => true
							]
						],
						[
							'name' => 'h3',
							'attributes' => false,
							'classes' => true,
							'styles' => [
								'color' => true,
								'font' => true,
								'font-family' => true,
								'font-size' => true,
								'font-style' => true,
								'height' => true,
								'margin' => true,
								'padding' => true,
								'text-align' => true,
								'vertical-align' => true,
								'width' => true,
								'white-space' => true,
								'float' => true
							]
						],
						[
							'name' => 'h4',
							'attributes' => false,
							'classes' => true,
							'styles' => [
								'color' => true,
								'font' => true,
								'font-family' => true,
								'font-size' => true,
								'font-style' => true,
								'height' => true,
								'margin' => true,
								'padding' => true,
								'text-align' => true,
								'vertical-align' => true,
								'width' => true,
								'white-space' => true,
								'float' => true
							]
						],
						[
							'name' => 'table',
							'attributes' => [
								'width' => [
									'pattern' => '/^([0-9]+(px|em|%)?)$/i'
								],
								'summary' => true,
								'align' => [
									'pattern' => '/^(left|right|center|justify)$/i'
								],
								'border' => true,
								'cellpadding' => true,
								'cellspacing' => true,
							],
							'classes' => true,
							'styles' => [
								'color' => true,
								'font' => true,
								'font-family' => true,
								'font-size' => true,
								'font-style' => true,
								'height' => true,
								'margin' => true,
								'padding' => true,
								'text-align' => true,
								'vertical-align' => true,
								'width' => true,
								'white-space' => true,
								'float' => true
							]
						],
						[
							'name' => 'tr',
							'attributes' => [
								'align' => [
									'pattern' => '/^(left|right|center|justify)$/i'
								],
								'valign' => [
									'pattern' => '/^(top|middle|bottom)$/i'
								],
								'bgcolor' => [
									'pattern' => '/^#[0-9a-f]{6}$/i'
								]
							],
							'classes' => true,
							'styles' => [
								'color' => true,
								'font' => true,
								'font-family' => true,
								'font-size' => true,
								'font-style' => true,
								'height' => true,
								'margin' => true,
								'padding' => true,
								'text-align' => true,
								'vertical-align' => true,
								'width' => true,
								'white-space' => true,
								'float' => true
								]
							],
						[
							'name' => 'ul',
							'attributes' => false,
							'classes' => false,
							'styles' => false
						],
						[
							'name' => 'ol',
							'attributes' => false,
							'classes' => false,
							'styles' => false
						]
					],
					'disallow' => []
				]
			],
			'Disallow list small dataset' => [
				[
					'tagsWhiteList' => [],
					'attrsWhiteList' => [],
					'stylesWhiteList' => [],
					'tagsBlackList' => [
						'html',
						'p',
						'a',
					],
					'attrsBlackList' => [
						'href',
					],
				],
				[
					'allow' => [],
					'disallow' => [
						[
							'name' => 'html',
							'attributes' => [
								'href' => true
							],
						],
						[
							'name' => 'p',
							'attributes' => [
								'href' => true
							],
						],
						[
							'name' => 'a',
							'attributes' => [
								'href' => true
							],
						]
					]
				]
			]
		];
	}
}
