<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\DesignDocument;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use MFDocument;
use MFElement;
use ModelFactory;
use PHPUnit\Framework\ExpectationFailedException;


/**
 * Class ModelFactoryTest
 *
 * Test XML assembly, and in particular the following verbs
 *
 *                      ┌─────────────────┐
 *                      │                 │
 *     LoadDelta ──────►│  ModelFactory   │
 *                      │                 ├──►GetDelta
 *   ApplyChanges ─────►│   ┌──────────┐  │
 *                      ├───┤MFDocument├──┤
 *      Delete ────────►│   └──────────┘  │
 *    AddChildNode ────►│                 │
 * RedefineChildNode ──►│    MFElement    │
 *      Rename ────────►│                 │
 *    SetChildNode ────►│                 │
 *                      └─────────────────┘
 *
 * @covers ModelFactory
 * @covers MFElement
 *
 */
class ModelFactoryTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		//static::$DEBUG_UNIT_TEST = true;

		$this->RequireOnceItopFile('setup/modelfactory.class.inc.php');
	}

	/**
	 * @param $sInitialXML
	 *
	 * @return \ModelFactory
	 * @throws \Exception
	 */
	protected function MakeVanillaModelFactory($sInitialXML): ModelFactory
	{
		/* @var MFDocument $oFactoryRoot */
		$oFactory = new ModelFactory([]);

		$oInitialDocument = new MFDocument();
		$oInitialDocument->preserveWhiteSpace = false;
		$oInitialDocument->loadXML($sInitialXML);

		$this->SetNonPublicProperty($oFactory, 'oDOMDocument', $oInitialDocument);
		$this->SetNonPublicProperty($oFactory, 'oRoot', $oInitialDocument->firstChild);

		return $oFactory;
	}

	/**
	 * @param $sXML
	 *
	 * @return false|string
	 */
	protected function CanonicalizeXML($sXML)
	{
		// Canonicalize the expected XML (to cope with indentation)
		$oExpectedDocument = new DOMDocument();
		$oExpectedDocument->preserveWhiteSpace = false;
		$oExpectedDocument->loadXML($sXML);
		$oExpectedDocument->formatOutput = true;

		return $oExpectedDocument->saveXML($oExpectedDocument->firstChild);
	}

	/**
	 * @param $sExpected
	 * @param $sActual
	 * @param string $sMessage
	 */
	protected function AssertEqualiTopXML($sExpected, $sActual, string $sMessage = '')
	{
		// Note: assertEquals reports the differences in a diff which is easier to interpret (in PHPStorm)
		// as compared to the report given by assertEqualXMLStructure
		static::assertEquals($this->CanonicalizeXML($sExpected), $this->CanonicalizeXML($sActual), $sMessage);
	}

	/**
	 * Assertion ignoring some of the unexpected decoration brought by DOM Elements.
	 */
	protected function AssertEqualModels(string $sExpectedXML, ModelFactory $oFactory, $sMessage = '')
	{
		$this->AssertEqualiTopXML($sExpectedXML, $oFactory->Dump(null, true), $sMessage);
	}

	/**
	 * @dataProvider ProviderGetPreviousComment
	 * @covers       ModelFactory::GetPreviousComment
	 *
	 * @param $sDeltaXML
	 * @param $sClassName
	 * @param $sExpectedComment
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testGetPreviousComment($sDeltaXML, $sClassName, $sExpectedComment)
	{
		$oFactory = new ModelFactory([]);
		$oDocument = new MFDocument();
		$oDocument->loadXML($sDeltaXML);
		$oXPath = new \DOMXPath($oDocument);
		$sClassName = DesignDocument::XPathQuote($sClassName);
		/** @var MFElement $oClassNode */
		$oClassNode = $oXPath->query("/itop_design/classes/class[@id=$sClassName]")->item(0);
		/** @var \DOMComment|null $oCommentNode */
		$oCommentNode = ModelFactory::GetPreviousComment($oClassNode);

		if (is_null($sExpectedComment)) {
			$this->assertNull($oCommentNode);
		} else {
			$this->assertEquals($sExpectedComment, $oCommentNode->textContent);
		}
	}

	public function ProviderGetPreviousComment()
	{
		$aData = [];

		$aData['No Comment first Class'] = [
			'sDeltaXML'        => '<itop_design>
	<classes>
		<class id="A" _delta="define"/>
	</classes>
</itop_design>',
			'sClassName'       => 'A',
			'sExpectedComment' => null,
		];

		$aData['No Comment other Class'] = [
			'sDeltaXML'        => '<itop_design>
	<classes>
		<!-- Test comment -->
		<class id="B" _delta="define"/>
		<class id="A" _delta="define"/>
	</classes>
</itop_design>',
			'sClassName'       => 'A',
			'sExpectedComment' => null,
		];

		$aData['Comment first class'] = [
			'sDeltaXML'        => '<itop_design>
	<classes>
		<!-- Test comment -->
		<class id="A" _delta="define"/>
	</classes>
</itop_design>',
			'sClassName'       => 'A',
			'sExpectedComment' => ' Test comment ',
		];

		$aData['Comment other Class'] = [
			'sDeltaXML'        => '<itop_design>
	<classes>
		<class id="B" _delta="define"/>
		<!-- Test comment -->
		<class id="A" _delta="define"/>
	</classes>
</itop_design>',
			'sClassName'       => 'A',
			'sExpectedComment' => ' Test comment ',
		];

		return $aData;
	}

	/**
	 * @dataProvider ProviderFlattenDelta
	 * @covers       ModelFactory::FlattenClassesInDelta
	 *
	 * @param $sDeltaXML
	 * @param $sExpectedXML
	 *
	 * @return void
	 * @throws \ReflectionException
	 */
	public function testFlattenDelta($sDeltaXML, $sExpectedXML)
	{
		$oFactory = new ModelFactory([]);
		$oDocument = new MFDocument();
		$oDocument->loadXML($sDeltaXML);
		/* @var MFElement $oDeltaRoot */
		$oDeltaRoot = $oDocument->firstChild;
		/** @var MFElement $oFlattenDeltaRoot */
		if (is_null($sExpectedXML)) {
			$this->expectException(\MFException::class);
		}
		$oFlattenDeltaRoot = $this->InvokeNonPublicMethod(ModelFactory::class, 'FlattenClassesInDelta', $oFactory, [$oDeltaRoot]);
		if (!is_null($sExpectedXML)) {
			$this->AssertEqualiTopXML($sExpectedXML, $oFlattenDeltaRoot->ownerDocument->saveXML());
		}
	}

	public function ProviderFlattenDelta()
	{
		return [
			'Empty delta' => [
				'sDeltaXML'    => '
<itop_design>
</itop_design>',
				'sExpectedXML' => '<itop_design>
</itop_design>',
			],

			'Flat delete' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1_2" _delta="delete"/>
		<class id="C_1_1" _delta="define"/>
		<class id="C_1" _delta="delete"/>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
	<classes>
		<class id="C_1_2" _delta="delete"/>
		<class id="C_1_1" _delta="define"/>
		<class id="C_1" _delta="delete"/>
	</classes>
</itop_design>',
			],

			'flat define root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="define">
			<parent>cmdbAbstractObject</parent>
		</class>
		<class id="C_2" _delta="define">
			<parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
    <class id="C_2" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
  </classes>
</itop_design>',
			],

			'flat force root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="force">
			<parent>cmdbAbstractObject</parent>
		</class>
		<class id="C_2" _delta="force">
			<parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
	<!-- Automatically generated to remove class/C_1 hierarchy -->
    <class id="C_1" _delta="delete_if_exists"/>
    <class id="C_1" _delta="force">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically generated to remove class/C_2 hierarchy -->
    <class id="C_2" _delta="delete_if_exists"/>
    <class id="C_2" _delta="force">
		<parent>cmdbAbstractObject</parent>
	</class>
  </classes>
</itop_design>',
			],

			'flat redefine root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="redefine">
			<parent>cmdbAbstractObject</parent>
		</class>
		<class id="C_2" _delta="redefine">
			<parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
	<!-- Automatically generated to remove class/C_1 hierarchy -->
    <class id="C_1" _delta="delete"/>
    <class id="C_1" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically generated to remove class/C_2 hierarchy -->
    <class id="C_2" _delta="delete"/>
    <class id="C_2" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
  </classes>
</itop_design>',
			],

			'Simple hierarchy define root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="define">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1">
				<parent>C_1</parent>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="define">
      <parent>C_1</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy delete' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1" _delta="delete"/>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1" _delta="delete"/>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1">
      <parent>C_1</parent>
    </class>
    <!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1">
      <parent>C_1_1</parent>
    </class>
    <!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="delete"/>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="delete"/>
  </classes>
</itop_design>',
			],

			'Complex hierarchy define root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="define">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1">
						<parent>C_1_1_1</parent>
					</class>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1">
					<parent>C_1_2</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="define">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1" _delta="define">
      <parent>C_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="define">
      <parent>C_1_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2" _delta="define">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="define">
      <parent>C_1_2</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy define' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1" _delta="define">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1">
						<parent>C_1_1_1</parent>
					</class>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1" _delta="define">
					<parent>C_1_2</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="define">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1" _delta="define">
      <parent>C_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="define">
      <parent>C_1_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="define">
      <parent>C_1_2</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy force' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1" _delta="force">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1">
						<parent>C_1_1_1</parent>
					</class>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1" _delta="force">
					<parent>C_1_2</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically generated to remove class/C_1_1 hierarchy -->
    <class id="C_1_1" _delta="delete_if_exists"/>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="force">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1" _delta="force">
      <parent>C_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="force">
      <parent>C_1_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2">
      <parent>C_1</parent>
    </class>
	<!-- Automatically generated to remove class/C_1_2_1 hierarchy -->
    <class id="C_1_2_1" _delta="delete_if_exists"/>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="force">
      <parent>C_1_2</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy force root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="force">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1">
						<parent>C_1_1_1</parent>
					</class>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1">
					<parent>C_1_2</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
	<!-- Automatically generated to remove class/C_1 hierarchy -->
    <class id="C_1" _delta="delete_if_exists"/>
    <class id="C_1" _delta="force">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="force">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1" _delta="force">
      <parent>C_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="force">
      <parent>C_1_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2" _delta="force">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="force">
      <parent>C_1_2</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy redefine' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1" _delta="redefine">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1">
						<parent>C_1_1_1</parent>
					</class>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1" _delta="redefine">
					<parent>C_1_2</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="C_1">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically generated to remove class/C_1_1 hierarchy -->
    <class id="C_1_1" _delta="delete"/>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="define">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1" _delta="define">
      <parent>C_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="define">
      <parent>C_1_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2">
      <parent>C_1</parent>
    </class>
	<!-- Automatically generated to remove class/C_1_2_1 hierarchy -->
    <class id="C_1_2_1" _delta="delete"/>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="define">
      <parent>C_1_2</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy redefine root' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="redefine">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
					<class id="C_1_1_1_1">
						<parent>C_1_1_1</parent>
					</class>
				</class>
			</class>
			<class id="C_1_2">
				<parent>C_1</parent>
				<class id="C_1_2_1">
					<parent>C_1_2</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
	<!-- Automatically generated to remove class/C_1 hierarchy -->
    <class id="C_1" _delta="delete"/>
    <class id="C_1" _delta="define">
		<parent>cmdbAbstractObject</parent>
	</class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _delta="define">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1 to classes -->
    <class id="C_1_1_1" _delta="define">
      <parent>C_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_1_1 to classes -->
    <class id="C_1_1_1_1" _delta="define">
      <parent>C_1_1_1</parent>
    </class>
	<!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_2" _delta="define">
      <parent>C_1</parent>
    </class>
	<!-- Automatically moved from class/C_1_2 to classes -->
    <class id="C_1_2_1" _delta="define">
      <parent>C_1_2</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Complex hierarchy define_if_not_exists flattening generates an error' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1" _delta="define_if_not_exists">
				<parent>C_1</parent>
				<class id="C_1_1_1">
					<parent>C_1_1</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => null,
			],

			'Complex hierarchy if_exists flattening generates an error' => [
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1">
			<parent>cmdbAbstractObject</parent>
			<class id="C_1_1" _delta="if_exists">
				<parent>C_1</parent>
				<class id="C_1_1_1" _delta="define">
					<parent>C_1_1</parent>
				</class>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => null,
			],

		];
	}

	/**
	 * @dataProvider ProviderLoadDelta
	 * @covers       ModelFactory::LoadDelta
	 *
	 * @param $sDeltaXML
	 * @param $bHierarchicalClasses
	 * @param $sExpectedXML
	 *
	 * @return void
	 * @throws \DOMFormatException
	 * @throws \MFException
	 */
	public function testLoadDelta($sInitialXML, $sDeltaXML, $sExpectedXML)
	{
		$oFactory = $this->MakeVanillaModelFactory($sInitialXML);
		$oFactoryDocument = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');

		// Load the delta
		$oDocument = new MFDocument();
		$oDocument->loadXML($sDeltaXML);
		/* @var MFElement $oDeltaRoot */
		$oDeltaRoot = $oDocument->firstChild;
		try {
			$oFactory->LoadDelta($oDeltaRoot, $oFactoryDocument);
		}
		catch (\Exception $e) {
			$this->assertNull($sExpectedXML, 'LoadDelta() must fail with exception: '.$e->getMessage());

			return;
		}
		$this->AssertEqualModels($sExpectedXML, $oFactory, 'LoadDelta() must result in a datamodel without hierarchical classes');
	}

	public function ProviderLoadDelta()
	{
		return [
			'empty delta'          => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '<itop_design></itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
			],
			'merge delta lax mode' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '<itop_design>
	<classes>
		<class id="C_1">
            <parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="added">
        <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
			],
			'Add a class'          => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="define">
            <parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="added">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
			],
			'Add a class if not exists (N°6660)' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="define_if_not_exists">
            <parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="needed">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Add a class and subclass in hierarchy' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="define">
            <parent>cmdbAbstractObject</parent>
            <class id="C_1_1">
              <parent>C_1</parent>
			</class>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="added">
      <parent>cmdbAbstractObject</parent>
    </class>
    <!-- Automatically moved from class/C_1 to classes -->
    <class id="C_1_1" _alteration="added">
	  <parent>C_1</parent>
	</class>
  </classes>
</itop_design>',
			],

			'Delete a class' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete">
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete hierarchically a class' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete">
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete hierarchically a class and subclass' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
	<class id="C_1_1">
	  <parent>C_1</parent>
	</class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete"/>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete hierarchically a class and subclass already deleted' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
	<class id="C_1_1">
	  <parent>C_1</parent>
	</class>
	<class id="C_1_2">
	  <parent>C_1</parent>
	</class>
	<class id="C_1_2_1">
	  <parent>C_1_2</parent>
	</class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1_2" _delta="delete"/>
		<class id="C_1" _delta="delete"/>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1_2" _alteration="removed"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete if exist hierarchically an existing class' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete_if_exists">
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete if exist hierarchically an non existing class' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_2">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete_if_exists">
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_2">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
			],

			'Delete if exist hierarchically a removed class' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete"/>
		<class id="C_1" _delta="delete_if_exists"/>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete if exist hierarchically an existing class and subclass' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
	<class id="C_1_1">
	  <parent>C_1</parent>
	</class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete_if_exists"/>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Delete if exist hierarchically a non existing subclass' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
	<class id="C_1_1">
	  <parent>C_1</parent>
	</class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1_1" _delta="delete"/>
		<class id="C_1" _delta="delete_if_exists"/>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1_1" _alteration="removed"/>
    <class id="C_1" _alteration="removed"/>
  </classes>
</itop_design>',
			],

			'Class comment should be preserved'              => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<!-- Test Comment on class C_1 -->
		<class id="C_1" _delta="define">
            <parent>cmdbAbstractObject</parent>
		</class>
		<!-- Test Comment on merged class -->
		<class id="C_2">
            <parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <!-- Test Comment on class C_1 -->
    <class id="C_1" _alteration="added">
      <parent>cmdbAbstractObject</parent>
    </class>
	<!-- Test Comment on merged class -->
	<class id="C_2" _alteration="added">
        <parent>cmdbAbstractObject</parent>
	</class>
  </classes>
</itop_design>',
			],
			'Delete hierarchically a class and add it again' => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1">
      <parent>cmdbAbstractObject</parent>
    </class>
	<class id="C_1_1">
	  <parent>C_1</parent>
	</class>
	<class id="C_1_1_1">
	  <parent>C_1_1</parent>
	</class>
  </classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
	<classes>
		<class id="C_1" _delta="delete"/>
		<class id="C_1" _delta="define">
			<parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <class id="C_1" _alteration="replaced">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>',
			],
			'merge delta strict'                             => [
				'sInitialXML'  => '
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
  </classes>
</itop_design>',
				'sDeltaXML'    => '<itop_design load="strict">
	<classes>
		<class id="C_1">
            <parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>',
				'sExpectedXML' => null,
			],
		];
	}

	/**
	 * @dataProvider ProviderAlterationByXMLDelta
	 * @covers       ModelFactory::LoadDelta
	 * @covers       ModelFactory::ApplyChanges
	 */
	public function testAlterationByXMLDelta($sInitialXML, $sDeltaXML, $sExpectedXML)
	{
		$oFactory = $this->MakeVanillaModelFactory($sInitialXML);
		$oFactoryRoot = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');

		$oDocument = new MFDocument();
		$oDocument->loadXML($sDeltaXML);
		/* @var MFElement $oDeltaRoot */
		$oDeltaRoot = $oDocument->firstChild;

		if ($sExpectedXML === null) {
			$this->expectException('Exception');
		}
		$oFactory->LoadDelta($oDeltaRoot, $oFactoryRoot);
		$oFactory->ApplyChanges();

		$this->AssertEqualModels($sExpectedXML, $oFactory);
	}

	/**
	 * @return array
	 */
	public function ProviderAlterationByXMLDelta()
	{
		// Basic (structure)
		return [
			'No change at all'                                     => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
			],
			'No change at all - mini delta'                        => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA/>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
			],
			'_delta="merge" implicit'                              => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
			],
			'_delta="merge" explicit (lax)'                        => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="merge"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
			],
			'_delta="merge" does preserve text in lax mode'        => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB>Maintained Text</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>Maintained Text</nodeB>
</nodeA>
XML
				,
			],
			'_delta="merge" recursively'                           => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB>
		<nodeC>
			<nodeD/>
		</nodeC>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>
		<nodeC>
			<nodeD/>
		</nodeC>
	</nodeB>
</nodeA>
XML
				,
			],
			// Define or redefine
			'_delta="define" without id'                           => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="define"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
			],
			'_delta="define" with id'                              => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="define"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<item id="toto"/>
</nodeA>
XML
				,
			],
			'_delta="define" but existing node'                    => [
				'sInitialXML'  => <<<XML
<nodeA>
	<item id="toto"/>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="define"/>
</nodeA>
XML
				,
				'sExpectedXML' => null,
			],
			'_delta="redefine" without id'                         => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="redefine">Gainsbourg</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>Gainsbourg</nodeB>
</nodeA>
XML
				,
			],
			'_delta="redefine" with id'                            => [
				'sInitialXML'  => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="redefine">Gainsbourg</item>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<item id="toto">Gainsbourg</item>
</nodeA>
XML
				,
			],
			'_delta="redefine" but missing node'                   => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="redefine">Gainsbourg</item>
</nodeA>
XML
				,
				'sExpectedXML' => null,
			],
			'_delta="force" without id + missing node'             => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="force">Hulk</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>Hulk</nodeB>
</nodeA>
XML
				,
			],
			'_delta="force" with id + missing node'                => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="force">Hulk</item>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<item id="toto">Hulk</item>
</nodeA>
XML
				,
			],
			'_delta="force" without id + existing node'            => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="force">Gainsbourg</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>Gainsbourg</nodeB>
</nodeA>
XML
				,
			],
			'_delta="force" with id + existing node'               => [
				'sInitialXML'  => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="force">Gainsbourg</item>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<item id="toto">Gainsbourg</item>
</nodeA>
XML
				,
			],
			// Rename
			'rename'                                               => [
				'sInitialXML'  => <<<XML
<nodeA>
	<item id="Kent">Kryptonite</item>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="Superman" _rename_from="Kent"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<item id="Superman">Kryptonite</item>
</nodeA>
XML
				,
			],
			'rename but missing node NOT INTUITIVE!!!'             => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="Superman" _rename_from="Kent"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<item id="Superman"/>
</nodeA>
XML
				,
			],
			// Delete
			'_delta="delete" without id'                           => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="delete"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA/>
XML
				,
			],
			'_delta="delete" with id'                              => [
				'sInitialXML'  => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="delete"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA/>
XML
				,
			],
			'_delta="delete" but missing node'                     => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="delete"/>
</nodeA>
XML
				,
				'sExpectedXML' => null,
			],
			'_delta="delete_if_exists" without id + existing node' => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="delete_if_exists"/>
</nodeA>
XML
				,
				'sExpectedXML' => '<nodeA/>',
			],
			'_delta="delete_if_exists" with id + existing node'    => [
				'sInitialXML'  => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="delete_if_exists"/>
</nodeA>
XML
				,
				'sExpectedXML' => '<nodeA/>',
			],
			'_delta="delete_if_exists" without id + missing node'  => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="delete_if_exists"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA/>
XML
				,
			],
			'_delta="delete_if_exists" with id + missing node'     => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<item id="toto" _delta="delete_if_exists"/>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA/>
XML
				,
			],
			// Conditionals
			'_delta="must_exist"'                                  => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="must_exist">
		<nodeC _delta="define"/>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>
		<nodeC/>
	</nodeB>
</nodeA>
XML
				,
			],
			'_delta="must_exist on missing node"'                  => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="must_exist">
		<nodeC _delta="define"/>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => null,
			],
			'_delta="if_exists on missing node (lax)'                   => [
				'sInitialXML'  => <<<XML
<nodeA>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="if_exists">
		<nodeC _delta="define"/>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
</nodeA>
XML
				,
			],
			'_delta="if_exists on missing node (strict)'                   => [
				'sInitialXML'  => <<<XML
<itop_design>
</itop_design>
XML
				,
				'sDeltaXML'    => <<<XML
<itop_design load="strict">
	<nodeB _delta="if_exists">
		<nodeC _delta="define"/>
	</nodeB>
</itop_design>
XML
				,
				'sExpectedXML' => <<<XML
<itop_design>
</itop_design>
XML
				,
			],
			'_delta="if_exists on existing node"'                  => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="if_exists">
		<nodeC _delta="define"/>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>
		<nodeC/>
	</nodeB>
</nodeA>
XML
				,
			],
			'_delta="define_if_not_exists on missing node"'        => [
				'sInitialXML'  => <<<XML
<nodeA/>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="define_if_not_exists">The incredible Hulk</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>The incredible Hulk</nodeB>
</nodeA>
XML
				,
			],
			'_delta="define_if_not_exists on existing node"'       => [
				'sInitialXML'  => <<<XML
<nodeA>
	<nodeB>Luke Banner</nodeB>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB _delta="define_if_not_exists">The incredible Hulk</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
	<nodeB>Luke Banner</nodeB>
</nodeA>
XML
				,
			],
			'_delta="define_and_must_exits"'                       => [
				'sInitialXML'  => <<<XML
<nodeA>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB id="Banner" _delta="define"/>
	<nodeB id="Banner" _delta="must_exist">
		<nodeC _delta="define"/>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
  <nodeB id="Banner">
    <nodeC/>
  </nodeB>
</nodeA>
XML
				,
			],
			'_delta="define_then_must_exist"'                      => [
				'sInitialXML'  => <<<XML
<nodeA>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB id="Banner" _delta="define">
		<nodeE/>
	</nodeB>
	<nodeB id="Banner" _delta="must_exist">
		<nodeC _delta="define_if_not_exists">
			<nodeD id="Bruce" _delta="define"/>
		</nodeC>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
  <nodeB id="Banner">
    <nodeE/>
    <nodeC>
      <nodeD id="Bruce"/>
    </nodeC>
  </nodeB>
</nodeA>
XML
				,
			],
			'nested _delta should be cleaned'                      => [
				'sInitialXML'  => <<<XML
<nodeA>
</nodeA>
XML
				,
				'sDeltaXML'    => <<<XML
<nodeA>
	<nodeB id="Banner" _delta="define">
		<nodeC>
			<nodeD id="Bruce" _delta="define"/>
		</nodeC>
	</nodeB>
</nodeA>
XML
				,
				'sExpectedXML' => <<<XML
<nodeA>
  <nodeB id="Banner">
    <nodeC>
      <nodeD id="Bruce"/>
    </nodeC>
  </nodeB>
</nodeA>
XML
				,
			],
			'Class comments are stripped when class is deleted'    => [
				'sInitialXML'  => '
<itop_design>
<classes>
<class id="cmdbAbstractObject"/>
<!-- Test Comment on class C_1 -->
<class id="C_1"/>
</classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
<classes>
<class id="C_1" _delta="delete"/>
</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
<classes>
<class id="cmdbAbstractObject"/>
</classes>
</itop_design>',
			],
			'Class comments are preserved'                         => [
				'sInitialXML'  => '
<itop_design>
<classes>
<class id="cmdbAbstractObject"/>
<!-- Test Comment on class C_1 -->
<class id="C_1"/>
</classes>
</itop_design>',
				'sDeltaXML'    => '
<itop_design>
<classes>
</classes>
</itop_design>',
				'sExpectedXML' => '<itop_design>
<classes>
<class id="cmdbAbstractObject"/>
<!-- Test Comment on class C_1 -->
<class id="C_1"/>
</classes>
</itop_design>',
			],
			'if_exist on removed node does nothing'                => [
				'sInitialXML'  => '
<root>
<nodeA _alteration="removed"/>
</root>',
				'sDeltaXML'    => '
<root>
<nodeA _delta="if_exists">
<nodeB _delta="define"/>
</nodeA>
</root>',
				'sExpectedXML' => '<root/>',
			],
			'if_exist on missing node does nothing'                => [
				'sInitialXML'  => '
<root/>',
				'sDeltaXML'    => '
<root>
<nodeA _delta="if_exists">
<nodeB _delta="define"/>
</nodeA>
</root>',
				'sExpectedXML' => '<root/>',
			],
			'if_exist on existing node merges'                     => [
				'sInitialXML'  => '
<root>
<nodeA/>
</root>',
				'sDeltaXML'    => '
<root>
<nodeA _delta="if_exists">
<nodeB _delta="define"/>
</nodeA>
</root>',
				'sExpectedXML' => '<root>
<nodeA>
<nodeB/>
</nodeA>
</root>',
			],
			'must_exist on removed node does error'                => [
				'sInitialXML'  => '
<root>
<nodeA _alteration="removed"/>
</root>',
				'sDeltaXML'    => '
<root>
<nodeA _delta="must_exist">
<nodeB _delta="define"/>
</nodeA>
</root>',
				'sExpectedXML' => null,
			],
			'must_exist on missing node does error'                => [
				'sInitialXML'  => '
<root/>',
				'sDeltaXML'    => '
<root>
<nodeA _delta="must_exist">
<nodeB _delta="define"/>
</nodeA>
</root>',
				'sExpectedXML' => null,
			],
			'must_exist on existing node merges'                   => [
				'sInitialXML'  => '
<root>
<nodeA/>
</root>',
				'sDeltaXML'    => '
<root>
<nodeA _delta="must_exist">
<nodeB _delta="define"/>
</nodeA>
</root>',
				'sExpectedXML' => '<root>
<nodeA>
<nodeB/>
</nodeA>
</root>',
			],
		];
	}

	/**
	 * @dataProvider ProviderAlterationAPIs
	 * @covers       \ModelFactory::GetDelta
	 * @covers       \MFElement::AddChildNode
	 * @covers       \MFElement::RedefineChildNode
	 * @covers       \MFElement::SetChildNode
	 * @covers       \MFElement::Delete
	 * @throws \MFException
	 */
	public function testAlterationsByAPIs($sInitialXML, $sOperation, $sExpectedXML)
	{
		$oFactory = $this->MakeVanillaModelFactory($sInitialXML);

		if ($sExpectedXML === null) {
			$this->expectException('Exception');
		}
		switch ($sOperation) {
			case 'Delete':
				/* @var MFElement $oTargetNode */
				$oTargetNode = $oFactory->GetNodes('//target_tag', null, false)->item(0);
				$oTargetNode->Delete();
				break;
			case 'AddChildNodeToContainer':
				$oContainerNode = $oFactory->GetNodes('//container_tag', null, false)->item(0);

				$oFactoryRoot = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');
				$oChild = $oFactoryRoot->CreateElement('target_tag', 'Hello, I\'m a newly added node');

				/* @var MFElement $oContainerNode */
				$oContainerNode->AddChildNode($oChild);
				break;

			case 'RedefineChildNodeToContainer':
				$oContainerNode = $oFactory->GetNodes('//container_tag', null, false)->item(0);

				$oFactoryRoot = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');
				$oChild = $oFactoryRoot->CreateElement('target_tag', 'Hello, I\'m replacing the previous node');

				/* @var MFElement $oContainerNode */
				$oContainerNode->RedefineChildNode($oChild);
				break;

			case 'SetChildNodeToContainer':
				$oContainerNode = $oFactory->GetNodes('//container_tag', null, false)->item(0);

				$oFactoryRoot = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');
				$oChild = $oFactoryRoot->CreateElement('target_tag', 'Hello, I\'m replacing the previous node');

				/* @var MFElement $oContainerNode */
				$oContainerNode->SetChildNode($oChild);
				break;

			default:
				static::fail("Unknown operation '$sOperation'");
		}

		if ($sExpectedXML !== null) {
			$this->AssertEqualModels($sExpectedXML, $oFactory);
		}
	}

	/**
	 * @return array[]
	 */
	public function ProviderAlterationAPIs()
	{
		define('CASE_NO_FLAG', <<<XML
<root_tag>
	<container_tag>
		<target_tag/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_ABOVE_A_FLAG', <<<XML
<root_tag>
	<container_tag>
		<target_tag>
			<child_tag _alteration="added">Blah</child_tag>
		</target_tag>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_IN_A_DEFINITION', <<<XML
<root_tag>
	<container_tag _alteration="added">
		<target_tag>
			<child_tag>Blah</child_tag>
		</target_tag>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_FLAG_ON_TARGET_define', <<<XML
<root_tag>
	<container_tag>
		<target_tag _alteration="added"/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_FLAG_ON_TARGET_redefine', <<<XML
<root_tag>
	<container_tag>
		<target_tag _alteration="replaced"/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_FLAG_ON_TARGET_needed', <<<XML
<root_tag>
	<container_tag>
		<target_tag _alteration="needed"/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_FLAG_ON_TARGET_forced', <<<XML
<root_tag>
	<container_tag>
		<target_tag _alteration="forced"/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_FLAG_ON_TARGET_removed', <<<XML
<root_tag>
	<container_tag>
		<target_tag _alteration="removed"/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_FLAG_ON_TARGET_old_id', <<<XML
<root_tag>
	<container_tag>
		<target_tag id="fraise" _old_id="tagada"/>
	</container_tag>
</root_tag>
XML
		);
		define('CASE_MISSING_TARGET', <<<XML
<root_tag>
	<container_tag/>
</root_tag>
XML
		);
		$aData = [
			'CASE_NO_FLAG Delete'                            => [
				CASE_NO_FLAG,
				'Delete',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="removed"/>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_ABOVE_A_FLAG Delete'                       => [
				CASE_ABOVE_A_FLAG,
				'Delete',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="removed"/>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_IN_A_DEFINITION Delete'                    => [
				CASE_IN_A_DEFINITION,
				'Delete',
				<<<XML
<root_tag>
	<container_tag _alteration="added"/>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_define Delete'              => [
				CASE_FLAG_ON_TARGET_define,
				'Delete',
				<<<XML
<root_tag>
	<container_tag/>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_redefine Delete'            => [
				CASE_FLAG_ON_TARGET_redefine,
				'Delete',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="removed"/>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_needed Delete'              => [
				CASE_FLAG_ON_TARGET_needed,
				'Delete',
				<<<XML
<root_tag>
  <container_tag/>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_forced Delete'              => [
				CASE_FLAG_ON_TARGET_forced,
				'Delete',
				<<<XML
<root_tag>
  <container_tag/>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_removed Delete'             => [
				CASE_FLAG_ON_TARGET_removed,
				'Delete',
				null,
			],
			'CASE_FLAG_ON_TARGET_old_id Delete'              => [
				CASE_FLAG_ON_TARGET_old_id,
				'Delete',
				<<<XML
<root_tag>
  <container_tag>
      <target_tag id="fraise" _old_id="tagada" _alteration="removed"/>
	</container_tag>
</root_tag>
XML
				,
			],
			'CASE_NO_FLAG AddChildNode'                      => [
				CASE_NO_FLAG,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_ABOVE_A_FLAG AddChildNode'                 => [
				CASE_ABOVE_A_FLAG,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_IN_A_DEFINITION AddChildNode'              => [
				CASE_IN_A_DEFINITION,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_FLAG_ON_TARGET_define AddChildNode'        => [
				CASE_FLAG_ON_TARGET_define,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_FLAG_ON_TARGET_redefine AddChildNode'      => [
				CASE_FLAG_ON_TARGET_redefine,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_FLAG_ON_TARGET_needed AddChildNode'        => [
				CASE_FLAG_ON_TARGET_needed,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_FLAG_ON_TARGET_forced AddChildNode'        => [
				CASE_FLAG_ON_TARGET_forced,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_FLAG_ON_TARGET_removed AddChildNode'       => [
				CASE_FLAG_ON_TARGET_removed,
				'AddChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
      <target_tag _alteration="replaced">Hello, I'm a newly added node</target_tag>
	</container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_old_id AddChildNode'        => [
				CASE_FLAG_ON_TARGET_old_id,
				'AddChildNodeToContainer',
				null,
			],
			'CASE_MISSING_TARGET AddChildNode'               => [
				CASE_MISSING_TARGET,
				'AddChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
      <target_tag _alteration="added">Hello, I'm a newly added node</target_tag>
	</container_tag>
</root_tag>
XML
				,
			],
			'CASE_NO_FLAG RedefineChildNode'                 => [
				CASE_NO_FLAG,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_ABOVE_A_FLAG RedefineChildNode'            => [
				CASE_ABOVE_A_FLAG,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_IN_A_DEFINITION RedefineChildNode'         => [
				CASE_IN_A_DEFINITION,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag _alteration="added">
    <target_tag>Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_define RedefineChildNode'   => [
				CASE_FLAG_ON_TARGET_define,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="added">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_redefine RedefineChildNode' => [
				CASE_FLAG_ON_TARGET_redefine,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			// Note: buggy case ?
			'CASE_FLAG_ON_TARGET_needed RedefineChildNode'   => [
				CASE_FLAG_ON_TARGET_needed,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="needed">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_forced RedefineChildNode'   => [
				CASE_FLAG_ON_TARGET_forced,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="forced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_removed RedefineChildNode'  => [
				CASE_FLAG_ON_TARGET_removed,
				'RedefineChildNodeToContainer',
				null,
			],
			'CASE_FLAG_ON_TARGET_old_id RedefineChildNode'   => [
				CASE_FLAG_ON_TARGET_old_id,
				'RedefineChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced" _old_id="tagada">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_MISSING_TARGET RedefineChildNode'          => [
				CASE_MISSING_TARGET,
				'RedefineChildNodeToContainer',
				null,
			],
			'CASE_NO_FLAG SetChildNode'                      => [
				CASE_NO_FLAG,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_ABOVE_A_FLAG SetChildNode'                 => [
				CASE_ABOVE_A_FLAG,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_IN_A_DEFINITION SetChildNode'              => [
				CASE_IN_A_DEFINITION,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag _alteration="added">
    <target_tag>Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_define SetChildNode'        => [
				CASE_FLAG_ON_TARGET_define,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="added">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_redefine SetChildNode'      => [
				CASE_FLAG_ON_TARGET_redefine,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			// Note: buggy case ?
			'CASE_FLAG_ON_TARGET_needed SetChildNode'        => [
				CASE_FLAG_ON_TARGET_needed,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="needed">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_forced SetChildNode'        => [
				CASE_FLAG_ON_TARGET_forced,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="forced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_removed SetChildNode'       => [
				CASE_FLAG_ON_TARGET_removed,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_FLAG_ON_TARGET_old_id SetChildNode'        => [
				CASE_FLAG_ON_TARGET_old_id,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _old_id="tagada" _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
			'CASE_MISSING_TARGET SetChildNode'               => [
				CASE_MISSING_TARGET,
				'SetChildNodeToContainer',
				<<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="added">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
				,
			],
		];

		return $aData;
	}

	/**
	 * @covers       \ModelFactory::LoadDelta
	 * @covers       \ModelFactory::GetDelta
	 * @covers       \ModelFactory::GetDeltaDocument
	 * @dataProvider ProviderGetDelta
	 */
	public function testGetDelta($sInitialXMLInternal, $sExpectedXMLDelta)
	{
		// constants aren't accessible in the data provider :(
		$sExpectedXMLDelta = str_replace('##ITOP_DESIGN_LATEST_VERSION##', ITOP_DESIGN_LATEST_VERSION, $sExpectedXMLDelta);

		$oFactory = $this->MakeVanillaModelFactory($sInitialXMLInternal);

		// Get the delta back
		$sNewDeltaXML = $oFactory->GetDelta();

		static::AssertEqualiTopXML($sExpectedXMLDelta, $sNewDeltaXML);
	}

	/**
	 * @return array[]
	 */
	public function ProviderGetDelta()
	{
		return [
			'no alteration'                       => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond>Roger Moore</james_bond>
	<stairway_to_heaven/>
	<robot id="r2d2"/>
</root_node>
XML
				,
				// Weird, but seems ok as of now
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<itop_design xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="##ITOP_DESIGN_LATEST_VERSION##"/>
XML
				,
			],
			'_alteration="added" singleton'       => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added"/>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define"/>
</root_node>

XML
				,
			],
			'_alteration="added" with value'      => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added">Roger Moore</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define">Roger Moore</james_bond>
</root_node>
XML
				,
			],
			'_alteration="added" with subtree'    => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added">
		<name>Moore</name>
		<last_name>Roger</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define">
    <name>Moore</name>
    <last_name>Roger</last_name>
  </james_bond>
</root_node>
XML
				,
			],
			'_alteration="forced" singleton'      => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="forced"/>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="force"/>
</root_node>
XML
				,
			],
			'_alteration="forced" with value'     => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="forced">Roger Moore</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="force">Roger Moore</james_bond>
</root_node>
XML
				,
			],
			'_alteration="forced" with subtree'   => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="forced">
		<name>Moore</name>
		<last_name>Roger</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="force">
    <name>Moore</name>
    <last_name>Roger</last_name>
  </james_bond>
</root_node>
XML
				,
			],
			'_alteration="needed" singleton'      => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="needed"/>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define_if_not_exists"/>
</root_node>
XML
				,
			],
			'_alteration="needed" with value'     => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="needed">Roger Moore</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define_if_not_exists">Roger Moore</james_bond>
</root_node>
XML
				,
			],
			'_alteration="needed" with subtree'   => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="needed">
		<name>Moore</name>
		<last_name>Roger</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define_if_not_exists">
    <name>Moore</name>
    <last_name>Roger</last_name>
  </james_bond>
</root_node>
XML
				,
			],
			'_alteration="replaced" with value'   => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="replaced">Sean Connery</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="redefine">Sean Connery</james_bond>
</root_node>
XML
				,
			],
			'_alteration="replaced" with subtree' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added">
		<name>Sean</name>
		<last_name>Connery</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define">
    <name>Sean</name>
    <last_name>Connery</last_name>
  </james_bond>
</root_node>
XML
				,
			],
			'_alteration="removed"'               => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="removed"/>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
	<james_bond _delta="delete"/>
</root_node>
XML
				,
			],
			'_old_id'                             => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond id="Sean" _old_id="Roger"/>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
	<james_bond  id="Sean" _rename_from="Roger"/>
</root_node>
XML
				,
			],
			'_old_id with subtree'                => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond id="Sean" _old_id="Roger">
		<subtree _alteration="added">etc.</subtree>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
	<james_bond  id="Sean" _rename_from="Roger">
    <subtree _delta="define">etc.</subtree>	
</james_bond>
</root_node>
XML
				,
			],

			'Class Comments are kept for created classes' => [
				'sInitialXMLInternal' => <<<XML
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <!-- Test Comment on class C_1 -->
    <class id="C_1" _alteration="added">
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
</itop_design>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<itop_design>
	<classes>
		<!-- Test Comment on class C_1 -->
		<class id="C_1" _delta="define">
            <parent>cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>
XML
				,
			],
			'Class Comments should be preserved'          => [
				'sInitialXMLInternal' => <<<XML
<itop_design>
  <classes>
    <class id="cmdbAbstractObject"/>
    <!-- Test Comment on class C_1 -->
    <class id="C_1" _alteration="added">
      <parent>cmdbAbstractObject</parent>
    </class>
	<!-- Test Comment on merged class -->
	<class id="C_2">
        <parent _alteration="added">cmdbAbstractObject</parent>
	</class>
  </classes>
</itop_design>
XML
				,
				'sExpectedXMLDelta'   => <<<XML
<itop_design>
	<classes>
		<!-- Test Comment on class C_1 -->
		<class id="C_1" _delta="define">
            <parent>cmdbAbstractObject</parent>
		</class>
		<!-- Test Comment on merged class -->
		<class id="C_2">
            <parent _delta="define">cmdbAbstractObject</parent>
		</class>
	</classes>
</itop_design>
XML
				,
			],
		];
	}

	/**
	 * @param $aClasses
	 * @param \ModelFactory $oFactory
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function CreateClasses($aClasses, ModelFactory $oFactory): void
	{
		foreach ($aClasses as $aClass) {
			$sClassName = $aClass['name'];
			$sModuleName = $aClass['module'];
			$sParent = $aClass['parent'];

			$oNode = $oFactory->CreateElement('class');
			$oNode->setAttribute('id', $sClassName);
			$oNode->setAttribute('_created_in', $sModuleName);
			$oDoc = $oNode->ownerDocument;
			foreach (array('properties', 'fields', 'methods', 'presentation') as $sElementName) {
				$oElement = $oDoc->createElement($sElementName);
				$oNode->appendChild($oElement);
			}
			$oParent = $oDoc->createElement('parent', $sParent);
			$oNode->appendChild($oParent);

			$oFactory->AddClass($oNode, $sModuleName);
		}
	}

	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testAddClass($aClasses, $sExpectedXML)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);

		$this->AssertEqualModels($sExpectedXML, $oFactory, 'The classes are added without hierarchy (not under an existing class)');
	}

	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testListRootClasses($aClasses, $sExpectedXML, $aExpectedRootClasses)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);

		$oRootClasses = $oFactory->ListRootClasses();
		$aRootClasses = [];
		/** @var MFElement $oRootClass */
		foreach ($oRootClasses as $oRootClass) {
			$aRootClasses[] = $oRootClass->getAttribute('id');
		}

		sort($aRootClasses);
		$aDiff = array_diff($aExpectedRootClasses, $aRootClasses);
		$this->assertCount(0, $aDiff);
	}

	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testClassNameExists($aClasses, $sExpectedXML, $aExpectedRootClasses, $aExpectedClasses, $aExpectedClassNotExist)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);

		foreach ($aExpectedClasses as $sExpectedClassExist) {
			$this->assertTrue($this->InvokeNonPublicMethod(ModelFactory::class, 'ClassNameExists', $oFactory, [$sExpectedClassExist]));
		}
		foreach ($aExpectedClassNotExist as $sExpectedClassNotExist) {
			$this->assertFalse($this->InvokeNonPublicMethod(ModelFactory::class, 'ClassNameExists', $oFactory, [$sExpectedClassNotExist]));
		}
	}

	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testListClasses($aClasses, $sExpectedXML, $aExpectedRootClasses, $aExpectedClasses, $aExpectedClassNotExist, $aExpectedClassesByModule)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);

		foreach ($aExpectedClassesByModule as $sModule => $aExpectedClasses) {
			$oClasses = $oFactory->ListClasses($sModule);
			$aFoundClasses = [];
			/** @var MFElement $oClass */
			foreach ($oClasses as $oClass) {
				$aFoundClasses[] = $oClass->getAttribute('id');
			}

			sort($aFoundClasses);
			$aDiff = array_diff($aExpectedClasses, $aFoundClasses);
			$this->assertCount(0, $aDiff);
		}
	}

	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testListAllClasses($aClasses, $sExpectedXML, $aExpectedRootClasses, $aExpectedClasses)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);
		$oClasses = $oFactory->ListAllClasses();
		$aFoundClasses = [];
		/** @var MFElement $oClass */
		foreach ($oClasses as $oClass) {
			$aFoundClasses[] = $oClass->getAttribute('id');
		}

		sort($aFoundClasses);
		$aDiff = array_diff($aExpectedClasses, $aFoundClasses);
		$this->assertCount(0, $aDiff);
	}


	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testGetClass($aClasses, $sExpectedXML, $aExpectedRootClasses, $aExpectedClasses)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);

		foreach ($aExpectedClasses as $sClassName) {
			$oClass = $oFactory->GetClass($sClassName);
			$this->assertInstanceOf(\DOMNode::class, $oClass);
		}
	}

	/**
	 * @dataProvider ProviderAddClass
	 * @return void
	 * @throws \Exception
	 */
	public function testGetChildClasses($aClasses, $sExpectedXML, $aExpectedRootClasses, $aExpectedClasses, $aExpectedClassNotExist, $aExpectedClassesByModule, $aExpectedChildClasses)
	{
		$oFactory = new ModelFactory([]);
		$this->CreateClasses($aClasses, $oFactory);

		foreach ($aExpectedChildClasses as $sClassName => $aChildClasses) {
			$oClassNode = $oFactory->GetClass($sClassName);
			$oClasses = $oFactory->GetChildClasses($oClassNode);
			$aFoundClasses = [];
			/** @var MFElement $oClass */
			foreach ($oClasses as $oClass) {
				$aFoundClasses[] = $oClass->getAttribute('id');
			}
			$aDiff = array_diff($aChildClasses, $aFoundClasses);
			$sMessage = "Children of $sClassName awaited [".implode(', ', $aChildClasses)."] got [".implode(', ', $aFoundClasses)."]";
			$this->assertCount(0, $aDiff, $sMessage);
		}

		$this->assertTrue(true);
	}

	/**
	 * @return array
	 */
	public function ProviderAddClass()
	{
		$aClasses = [
			"1 root class"           => [
				'aClasses'                 => [
					['name' => 'A', 'module' => 'M', 'parent' => 'cmdbAbstractObject'],
				],
				'sExpectedXML'             => '<itop_design xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.1">
  <loaded_modules/>
  <classes>
    <class id="DBObject"/>
    <class id="CMDBObject"/>
    <class id="cmdbAbstractObject"/>
    <class id="A" _created_in="M" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
  <dictionaries/>
  <menus/>
  <meta/>
  <events/>
</itop_design>',
				'aExpectedRootClasses'     => [],
				'aExpectedClasses'         => ['A'],
				'aExpectedClassNotExist'   => ['B'],
				'aExpectedClassesByModule' => ['M' => ['A']],
				'aExpectedChildClasses'    => ['A' => []],
			],
			'2 root classes'         => [
				'aClasses'                 => [
					['name' => 'A', 'module' => 'M', 'parent' => 'cmdbAbstractObject'],
					['name' => 'B', 'module' => 'M2', 'parent' => 'cmdbAbstractObject'],
				],
				'sExpectedXML'             => '<itop_design xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.1">
  <loaded_modules/>
  <classes>
    <class id="DBObject"/>
    <class id="CMDBObject"/>
    <class id="cmdbAbstractObject"/>
    <class id="A" _created_in="M" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>cmdbAbstractObject</parent>
    </class>
    <class id="B" _created_in="M2" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>cmdbAbstractObject</parent>
    </class>
  </classes>
  <dictionaries/>
  <menus/>
  <meta/>
  <events/>
</itop_design>',
				'aExpectedRootClasses'     => [],
				'aExpectedClasses'         => ['A', 'B'],
				'aExpectedClassNotExist'   => ['C'],
				'aExpectedClassesByModule' => ['M' => ['A'], 'M2' => ['B']],
				'aExpectedChildClasses'    => ['A' => [], 'B' => []],
			],
			'2 hierarchical classes' => [
				'aClasses'                 => [
					['name' => 'A', 'module' => 'M', 'parent' => 'cmdbAbstractObject'],
					['name' => 'B', 'module' => 'M2', 'parent' => 'A'],
				],
				'sExpectedXML'             => '<itop_design xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.1">
  <loaded_modules/>
  <classes>
    <class id="DBObject"/>
    <class id="CMDBObject"/>
    <class id="cmdbAbstractObject"/>
    <class id="A" _created_in="M" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>cmdbAbstractObject</parent>
    </class>
    <class id="B" _created_in="M2" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>A</parent>
    </class>
  </classes>
  <dictionaries/>
  <menus/>
  <meta/>
  <events/>
</itop_design>',
				'aExpectedRootClasses'     => ['A'],
				'aExpectedClasses'         => ['A', 'B'],
				'aExpectedClassNotExist'   => ['C'],
				'aExpectedClassesByModule' => ['M' => ['A'], 'M2' => ['B']],
				'aExpectedChildClasses'    => ['A' => ['B'], 'B' => []],
			],
			'4 mixed classes'        => [
				'aClasses'                 => [
					['name' => 'A', 'module' => 'M', 'parent' => 'cmdbAbstractObject'],
					['name' => 'B', 'module' => 'M2', 'parent' => 'A'],
					['name' => 'C', 'module' => 'M3', 'parent' => 'cmdbAbstractObject'],
					['name' => 'D', 'module' => 'M3', 'parent' => 'B'],
				],
				'sExpectedXML'             => '<itop_design xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.1">
  <loaded_modules/>
  <classes>
    <class id="DBObject"/>
    <class id="CMDBObject"/>
    <class id="cmdbAbstractObject"/>
    <class id="A" _created_in="M" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>cmdbAbstractObject</parent>
    </class>
    <class id="B" _created_in="M2" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>A</parent>
    </class>
    <class id="C" _created_in="M3" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>cmdbAbstractObject</parent>
    </class>
    <class id="D" _created_in="M3" _alteration="added">
      <properties/>
      <fields/>
      <methods/>
      <presentation/>
      <parent>B</parent>
    </class>
  </classes>
  <dictionaries/>
  <menus/>
  <meta/>
  <events/>
</itop_design>',
				'aExpectedRootClasses'     => ['A'],
				'aExpectedClasses'         => ['A', 'B', 'C', 'D'],
				'aExpectedClassNotExist'   => ['E'],
				'aExpectedClassesByModule' => ['M' => ['A'], 'M2' => ['B'], 'M3' => ['C', 'D']],
				'aExpectedChildClasses'    => ['A' => ['B'], 'B' => ['D'], 'C' => [], 'D' => []],
			],
		];

		return $aClasses;
	}

	/**
	 * @dataProvider ProviderLoadDeltaMode
	 * @param $sInitialXML
	 * @param $sDeltaXML
	 * @param $sMode
	 * @param $sExpectedXML
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testLoadDeltaMode($sInitialXML, $sDeltaXML, $sExpectedXMLInLaxMode, $sExpectedXMLInStrictMode)
	{
		// Load in Lax mode
		$oFactory = $this->MakeVanillaModelFactory($sInitialXML);
		$oFactoryDocument = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');

		$oDocument = new MFDocument();
		$oDocument->loadXML($sDeltaXML);
		/* @var MFElement $oDeltaRoot */
		$oDeltaRoot = $oDocument->firstChild;
		try {
			$oFactory->LoadDelta($oDeltaRoot, $oFactoryDocument, ModelFactory::LOAD_DELTA_MODE_LAX);
			$this->AssertEqualModels($sExpectedXMLInLaxMode, $oFactory, 'LoadDelta(lax) did not produce the expected result');
		}
		catch (ExpectationFailedException $e) {
			throw $e;
		}
		catch (\Exception $e) {
			$this->assertNull($sExpectedXMLInLaxMode, 'LoadDelta(lax) should not have failed with exception: '.$e->getMessage());
		}

		// Load in Strict mode
		$oFactory = $this->MakeVanillaModelFactory($sInitialXML);
		$oFactoryDocument = $this->GetNonPublicProperty($oFactory, 'oDOMDocument');

		$oDocument = new MFDocument();
		$oDocument->loadXML($sDeltaXML);
		/* @var MFElement $oDeltaRoot */
		$oDeltaRoot = $oDocument->firstChild;
		try {
			$oFactory->LoadDelta($oDeltaRoot, $oFactoryDocument, ModelFactory::LOAD_DELTA_MODE_STRICT);
			$this->AssertEqualModels($sExpectedXMLInStrictMode, $oFactory, 'LoadDelta(strict) did not produce the expected result');
		}
		catch (ExpectationFailedException $e) {
			throw $e;
		}
		catch (\Exception $e) {
			$this->assertNull($sExpectedXMLInStrictMode, 'LoadDelta(strict) should not have failed with exception: '.$e->getMessage());
		}
	}


	public function ProviderLoadDeltaMode()
	{
		return [
			'merge delta have different behavior depending on the mode' => [
				'sInitialXML'  => '
<itop_design>
  <nodeA>
  </nodeA>
</itop_design>',
				'sDeltaXML'    => '<itop_design>
	<nodeA>
		<nodeB id="C_1">
			<parent>cmdbAbstractObject</parent>
		</nodeB>
	</nodeA>
</itop_design>',
				'sExpectedXMLInLaxMode' => '<itop_design>
  <nodeA>
    <nodeB id="C_1" _alteration="added">
        <parent>cmdbAbstractObject</parent>
    </nodeB>
  </nodeA>
</itop_design>',
				'sExpectedXMLInStrictMode' => null,
			],
			'mode specified in delta takes precedence' => [
				'sInitialXML'  => '
<itop_design>
  <nodeA>
  </nodeA>
</itop_design>',
				'sDeltaXML'    => '<itop_design load="strict">
	<nodeA>
		<nodeB id="C_1">
			<parent>cmdbAbstractObject</parent>
		</nodeB>
	</nodeA>
</itop_design>',
				'sExpectedXMLInLaxMode' => null,
				'sExpectedXMLInStrictMode' => null,
			],
			'merge leaf nodes have different behavior depending on the mode' => [
				'sInitialXML'  => '
<itop_design>
  <nodeA>Test</nodeA>
</itop_design>',
				'sDeltaXML'    => '<itop_design>
	<nodeA>Taste</nodeA>
</itop_design>',
				'sExpectedXMLInLaxMode' => '<itop_design>
  <nodeA _alteration="replaced">Taste</nodeA>
</itop_design>',
				'sExpectedXMLInStrictMode' => null,
			],
			'merge existing leaf nodes without text have same behavior' => [
				'sInitialXML'  => '
<itop_design>
  <nodeA/>
</itop_design>',
				'sDeltaXML'    => '<itop_design>
	<nodeA/>
</itop_design>',
				'sExpectedXMLInLaxMode' => '<itop_design>
  <nodeA/>
</itop_design>',
				'sExpectedXMLInStrictMode' => '<itop_design>
  <nodeA/>
</itop_design>',
			],
			'merge non-existing leaf nodes without text have different behavior' => [
				'sInitialXML'  => '
<itop_design>
</itop_design>',
				'sDeltaXML'    => '<itop_design>
	<nodeA/>
</itop_design>',
				'sExpectedXMLInLaxMode' => '<itop_design>
  <nodeA/>
</itop_design>',
				'sExpectedXMLInStrictMode' => null,
			],
			'merge non-existing nodes with sub-nodes defined' => [
				'sInitialXML'  => '
<itop_design>
</itop_design>',
				'sDeltaXML'    => '<itop_design>
	<nodeA>
		<nodeB _delta="define"/>
	</nodeA>
</itop_design>',
				'sExpectedXMLInLaxMode' => '<itop_design>
	<nodeA>
		<nodeB _alteration="added"/>
	</nodeA>
</itop_design>',
				'sExpectedXMLInStrictMode' => '<itop_design>
	<nodeA>
		<nodeB _alteration="added"/>
	</nodeA>
</itop_design>',
			],
		];
	}
}
