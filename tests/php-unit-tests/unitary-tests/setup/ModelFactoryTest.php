<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use MFDocument;
use MFElement;
use ModelFactory;


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
 * @covers ModelFactory
 * @covers MFElement
 *
 */
class ModelFactoryTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/modelfactory.class.inc.php');
	}

	/**
	 * @param $sInitialXML
	 *
	 * @return \ModelFactory
	 * @throws \ReflectionException
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
	 */
	protected function AssertEqualiTopXML($sExpected, $sActual)
	{
		// Note: assertEquals reports the differences in a diff which is easier to interpret (in PHPStorm)
		// as compared to the report given by assertEqualXMLStructure
		static::assertEquals($this->CanonicalizeXML($sExpected), $this->CanonicalizeXML($sActual));
	}

	/**
	 * Assertion ignoring some of the unexpected decoration brought by DOM Elements.
	 */
	protected function AssertEqualModels(string $sExpectedXML, ModelFactory $oFactory)
	{
		return $this->AssertEqualiTopXML($sExpectedXML, $oFactory->Dump(null, true));
	}

	/**
	 * @dataProvider providerDeltas
	 * @covers ModelFactory::LoadDelta
	 * @covers ModelFactory::ApplyChanges
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
	public function providerDeltas()
	{
		// Basic (structure)
		$aDeltas['No change at all'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['No change at all - mini delta'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA/>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
		];
		$aDeltas['_delta="merge" implicit'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="merge" explicit'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="merge" does not handle data'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<nodeB>Ghost busters!!!</nodeB>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
		];
		$aDeltas['_delta="merge" recursively'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];

		// Define or redefine
		$aDeltas['_delta="define" without id'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<nodeB _delta="define"></nodeB>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
		];
		$aDeltas['_delta="define" with id'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="define"></item>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA>
	<item id="toto"></item>
</nodeA>
XML
		];
		$aDeltas['_delta="define" but existing node'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<item id="toto" _delta="define"></item>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="define"></item>
</nodeA>
XML
			,
			'sExpectedXML' => null
		];
		$aDeltas['_delta="redefine" without id'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="redefine" with id'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="redefine" but missing node'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="redefine">Gainsbourg</item>
</nodeA>
XML
			,
			'sExpectedXML' => null
		];
		$aDeltas['_delta="force" without id + missing node'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="force" with id + missing node'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="force" without id + existing node'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="force" with id + existing node'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];

		// Rename
		$aDeltas['rename'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<item id="Kent">Kryptonite</item>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['rename but missing node NOT INTUITIVE!!!'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];

		// Delete
		$aDeltas['_delta="delete" without id'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<nodeB _delta="delete"/>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA/>
XML
		];
		$aDeltas['_delta="delete" with id'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="delete"/>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA/>
XML
		];
		$aDeltas['_delta="delete" but missing node'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="delete"/>
</nodeA>
XML
			,
			'sExpectedXML' => null,
		];
		$aDeltas['_delta="delete_if_exists" without id + existing node'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB>Initial BB</nodeB>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<nodeB _delta="delete_if_exists"/>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA/>
XML
		];
		$aDeltas['_delta="delete_if_exists" with id + existing node'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<item id="toto">Initial BB</item>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="delete_if_exists"/>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA/>
XML
		];
		$aDeltas['_delta="delete_if_exists" without id + missing node'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<nodeB _delta="delete_if_exists"/>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA/>
XML
		];
		$aDeltas['_delta="delete_if_exists" with id + missing node'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<item id="toto" _delta="delete_if_exists"/>
</nodeA>
XML
			,
			'sExpectedXML' => <<<XML
<nodeA/>
XML
		];

		// Conditionals
		$aDeltas['_delta="must_exist"'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="must_exist on missing node"'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
<nodeA>
	<nodeB _delta="must_exist">
		<nodeC _delta="define"/>
	</nodeB>
</nodeA>
XML
			,
			'sExpectedXML' => null,
		];
		$aDeltas['_delta="if_exists on missing node"'] = [
			'sInitialXML' => <<<XML
<nodeA>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="if_exists on existing node"'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB/>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="define_if_not_exists on missing node"'] = [
			'sInitialXML' => <<<XML
<nodeA/>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="define_if_not_exists on existing node"'] = [
			'sInitialXML' => <<<XML
<nodeA>
	<nodeB>Luke Banner</nodeB>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="define_and_must_exits"'] = [
			'sInitialXML' => <<<XML
<nodeA>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
		];
		$aDeltas['_delta="define_then_must_exist"'] = [
			'sInitialXML' => <<<XML
<nodeA>
</nodeA>
XML
			,
			'sDeltaXML' => <<<XML
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
      <nodeD id="Bruce" _delta="define"/>
    </nodeC>
  </nodeB>
</nodeA>
XML
		];

		return $aDeltas;
	}

	/**
	 * @dataProvider providerAlterationAPIs
	 * @covers \ModelFactory::GetDelta
	 * @covers \MFElement::AddChildNode
	 * @covers \MFElement::RedefineChildNode
	 * @covers \MFElement::SetChildNode
	 * @covers \MFElement::Delete
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
	public function providerAlterationAPIs()
	{
		define('CASE_NO_FLAG', <<<XML
<root_tag>
	<container_tag>
		<target_tag></target_tag>
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
			'CASE_NO_FLAG Delete' => [CASE_NO_FLAG , 'Delete', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="removed"/>
  </container_tag>
</root_tag>
XML
			],
			'CASE_ABOVE_A_FLAG Delete' => [CASE_ABOVE_A_FLAG , 'Delete', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="removed"/>
  </container_tag>
</root_tag>
XML
			],
			'CASE_IN_A_DEFINITION Delete' => [CASE_IN_A_DEFINITION , 'Delete', <<<XML
<root_tag>
	<container_tag _alteration="added"/>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_define Delete' => [CASE_FLAG_ON_TARGET_define , 'Delete', <<<XML
<root_tag>
	<container_tag/>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_redefine Delete' => [CASE_FLAG_ON_TARGET_redefine , 'Delete', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="removed"/>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_needed Delete' => [CASE_FLAG_ON_TARGET_needed , 'Delete', <<<XML
<root_tag>
  <container_tag/>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_forced Delete' => [CASE_FLAG_ON_TARGET_forced , 'Delete', <<<XML
<root_tag>
  <container_tag/>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_removed Delete' => [CASE_FLAG_ON_TARGET_removed , 'Delete', null
			],
			'CASE_FLAG_ON_TARGET_old_id Delete' => [CASE_FLAG_ON_TARGET_old_id , 'Delete', <<<XML
<root_tag>
  <container_tag>
      <target_tag id="fraise" _old_id="tagada" _alteration="removed"/>
	</container_tag>
</root_tag>
XML
			],
			'CASE_NO_FLAG AddChildNode' => [CASE_NO_FLAG , 'AddChildNodeToContainer', null
			],
			'CASE_ABOVE_A_FLAG AddChildNode' => [CASE_ABOVE_A_FLAG , 'AddChildNodeToContainer', null
			],
			'CASE_IN_A_DEFINITION AddChildNode' => [CASE_IN_A_DEFINITION , 'AddChildNodeToContainer', null
			],
			'CASE_FLAG_ON_TARGET_define AddChildNode' => [CASE_FLAG_ON_TARGET_define , 'AddChildNodeToContainer', null
			],
			'CASE_FLAG_ON_TARGET_redefine AddChildNode' => [CASE_FLAG_ON_TARGET_redefine , 'AddChildNodeToContainer', null
			],
			'CASE_FLAG_ON_TARGET_needed AddChildNode' => [CASE_FLAG_ON_TARGET_needed , 'AddChildNodeToContainer', null
			],
			'CASE_FLAG_ON_TARGET_forced AddChildNode' => [CASE_FLAG_ON_TARGET_forced , 'AddChildNodeToContainer', null
			],
			'CASE_FLAG_ON_TARGET_removed AddChildNode' => [CASE_FLAG_ON_TARGET_removed , 'AddChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
      <target_tag _alteration="replaced">Hello, I'm a newly added node</target_tag>
	</container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_old_id AddChildNode' => [CASE_FLAG_ON_TARGET_old_id , 'AddChildNodeToContainer', null
			],
			'CASE_MISSING_TARGET AddChildNode' => [CASE_MISSING_TARGET , 'AddChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
      <target_tag _alteration="added">Hello, I'm a newly added node</target_tag>
	</container_tag>
</root_tag>
XML
			],
			'CASE_NO_FLAG RedefineChildNode' => [CASE_NO_FLAG , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_ABOVE_A_FLAG RedefineChildNode' => [CASE_ABOVE_A_FLAG , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_IN_A_DEFINITION RedefineChildNode' => [CASE_IN_A_DEFINITION , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag _alteration="added">
    <target_tag>Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_define RedefineChildNode' => [CASE_FLAG_ON_TARGET_define , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="added">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_redefine RedefineChildNode' => [CASE_FLAG_ON_TARGET_redefine , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			// Note: buggy case ?
			'CASE_FLAG_ON_TARGET_needed RedefineChildNode' => [CASE_FLAG_ON_TARGET_needed , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="needed">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_forced RedefineChildNode' => [CASE_FLAG_ON_TARGET_forced , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="forced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_removed RedefineChildNode' => [CASE_FLAG_ON_TARGET_removed , 'RedefineChildNodeToContainer', null
			],
			'CASE_FLAG_ON_TARGET_old_id RedefineChildNode' => [CASE_FLAG_ON_TARGET_old_id , 'RedefineChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_MISSING_TARGET RedefineChildNode' => [CASE_MISSING_TARGET , 'RedefineChildNodeToContainer', null
			],
			'CASE_NO_FLAG SetChildNode' => [CASE_NO_FLAG , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_ABOVE_A_FLAG SetChildNode' => [CASE_ABOVE_A_FLAG , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_IN_A_DEFINITION SetChildNode' => [CASE_IN_A_DEFINITION , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag _alteration="added">
    <target_tag>Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_define SetChildNode' => [CASE_FLAG_ON_TARGET_define , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="added">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_redefine SetChildNode' => [CASE_FLAG_ON_TARGET_redefine , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			// Note: buggy case ?
			'CASE_FLAG_ON_TARGET_needed SetChildNode' => [CASE_FLAG_ON_TARGET_needed , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="needed">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_forced SetChildNode' => [CASE_FLAG_ON_TARGET_forced , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="forced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_removed SetChildNode' => [CASE_FLAG_ON_TARGET_removed , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_FLAG_ON_TARGET_old_id SetChildNode' => [CASE_FLAG_ON_TARGET_old_id , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _old_id="tagada" _alteration="replaced">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
			'CASE_MISSING_TARGET SetChildNode' => [CASE_MISSING_TARGET , 'SetChildNodeToContainer', <<<XML
<root_tag>
  <container_tag>
    <target_tag _alteration="added">Hello, I'm replacing the previous node</target_tag>
  </container_tag>
</root_tag>
XML
			],
		];
		return $aData;
	}

	/**
	 * @covers \ModelFactory::LoadDelta
	 * @covers \ModelFactory::GetDelta
	 * @covers \ModelFactory::GetDeltaDocument
	 * @dataProvider providerGetDelta
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
	public function providerGetDelta()
	{
		return [
			'no alteration' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond>Roger Moore</james_bond>
	<stairway_to_heaven/>
	<robot id="r2d2"/>
</root_node>
XML
				,
				// Weird, but seems ok as of now
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<itop_design xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="##ITOP_DESIGN_LATEST_VERSION##"/>
XML
				,
			],
			'_alteration="added" singleton' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added"/>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define"/>
</root_node>

XML
			],
			'_alteration="added" with value' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added">Roger Moore</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define">Roger Moore</james_bond>
</root_node>
XML
			],
			'_alteration="added" with subtree' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="added">
		<name>Moore</name>
		<last_name>Roger</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define">
    <name>Moore</name>
    <last_name>Roger</last_name>
  </james_bond>
</root_node>
XML
			],
			'_alteration="forced" singleton' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="forced"/>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="force"/>
</root_node>
XML
			],
			'_alteration="forced" with value' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="forced">Roger Moore</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="force">Roger Moore</james_bond>
</root_node>
XML
			],
			'_alteration="forced" with subtree' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="forced">
		<name>Moore</name>
		<last_name>Roger</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="force">
    <name>Moore</name>
    <last_name>Roger</last_name>
  </james_bond>
</root_node>
XML
			],
			'_alteration="needed" singleton' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="needed"/>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define_if_not_exists"/>
</root_node>
XML
			],
			'_alteration="needed" with value' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="needed">Roger Moore</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define_if_not_exists">Roger Moore</james_bond>
</root_node>
XML
			],
			'_alteration="needed" with subtree' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="needed">
		<name>Moore</name>
		<last_name>Roger</last_name>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define_if_not_exists">
    <name>Moore</name>
    <last_name>Roger</last_name>
  </james_bond>
</root_node>
XML
			],
			'_alteration="replaced" with value' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="replaced">Sean Connery</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="redefine">Sean Connery</james_bond>
</root_node>
XML
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
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
  <james_bond _delta="define">
    <name>Sean</name>
    <last_name>Connery</last_name>
  </james_bond>
</root_node>
XML
			],
			'_alteration="removed"' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond _alteration="removed"/>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
	<james_bond _delta="delete"/>
</root_node>
XML
			],
			'_old_id' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond id="Sean" _old_id="Roger"/>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
	<james_bond  id="Sean" _rename_from="Roger"/>
</root_node>
XML
			],
			'_old_id with subtree' => [
				'sInitialXMLInternal' => <<<XML
<root_node>
	<james_bond id="Sean" _old_id="Roger">
		<subtree _alteration="added">etc.</subtree>
	</james_bond>
</root_node>
XML
				,
				'sExpectedXMLDelta' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root_node>
	<james_bond  id="Sean" _rename_from="Roger">
    <subtree _delta="define">etc.</subtree>	
</james_bond>
</root_node>
XML
			],
		];
	}
}
