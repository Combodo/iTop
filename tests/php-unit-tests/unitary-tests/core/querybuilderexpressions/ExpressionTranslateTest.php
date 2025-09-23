<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Expression;

class ExpressionTranslateTest extends ItopDataTestCase
{
	/**
	 * @dataProvider TranslationsProvider
	 * @param $sExpressionBefore
	 * @param $sTranslationMap
	 * @param $sExpressionAfter
	 **/
	public function testTranslate($sExpressionBefore, $sTranslationMap, $sExpressionAfter)
	{
		$oExpressionBefore = Expression::FromOQL($sExpressionBefore);
		$aTranslationMap = eval('return '.$sTranslationMap.';');
		$oExpressionAfter = $oExpressionBefore->Translate($aTranslationMap);
		static::assertEquals($sExpressionAfter, $oExpressionAfter->RenderExpression());
	}

	public function TranslationsProvider()
	{
		return [
			'simplest illustration of the concept: field translated into a scalar' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['column1' => new \ScalarExpression('hello')]]",
				'after' => "'hello'"
			],
			'field translated wherever it is in the expression tree' => [
				'before' => "1 + (2 * (3 / (4 - (5 + FLOOR(alias1.column1)))))",
				'map' => "['alias1' => ['column1' => new \ScalarExpression('hello')]]",
				'after' => "(1 + (2 * (3 / (4 - (5 + FLOOR('hello'))))))"
			],
			'each and every occurrences of a field are translated' => [
				'before' => "CONCAT(alias1.column1, alias1.column1)",
				'map' => "['alias1' => ['column1' => new \ScalarExpression('hello')]]",
				'after' => "CONCAT('hello', 'hello')"
			],
			'field translated into a complex expression (decomposition)' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['column1' => \Expression::FromOQL(\"CONCAT(person.first_name, ' ', contact.name)\")]]",
				'after' => "CONCAT(`person`.`first_name`, ' ', `contact`.`name`)"
			],
			'translate several fields at once' => [
				'before' => "CONCAT(`person`.`first_name`, ' ', `contact`.`name`)",
				'map' => "['person' => ['*' => 'table_person'], 'contact' => ['*' => 'table_contact']]",
				'after' => "CONCAT(`table_person`.`first_name`, ' ', `table_contact`.`name`)"
			],
			'translation is done once and only once' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['column1' => \Expression::FromOQL('alias2.column1')], 'alias2' => ['column1' => new \ScalarExpression('translated again?')]]",
				'after' => "`alias2`.`column1`"
			],
			'translation of aliases, basic' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['*' => 'A']]",
				'after' => "`A`.`column1`"
			],
			'translation of aliases, several hits and mappings' => [
				'before' => "CONCAT(alias1.column1, alias1.column2, alias2.column1)",
				'map' => "['alias1' => ['*' => 'A'], 'alias2' => ['*' => 'B']]",
				'after' => "CONCAT(`A`.`column1`, `A`.`column2`, `B`.`column1`)"
			],
			'nothing to change (+ map exceeds translation needs)' => [
				'before' => "CONCAT('hello', 1 + 2, :paramX)",
				'map' => "['alias1' => ['*' => 'alias2']]",
				'after' => "CONCAT('hello', (1 + 2), :paramX)"
			],
		];
	}

	public function testTranslateFailsWhenSomeFieldsAreNotTranslated()
	{
		$oExpressionBefore = Expression::FromOQL('alias1.column1');
		$aTranslationMap = [];
		static::expectException(\CoreException::class);
		static::expectExceptionMessageMatches('/Unknown parent id in translation table/');
		$oExpressionAfter = $oExpressionBefore->Translate($aTranslationMap);
	}

	public function testTranslateCanOptionalyIgnoreUntranslatedFields()
	{
		$oExpressionBefore = Expression::FromOQL('alias1.column1');
		$aTranslationMap = [];
		$oExpressionAfter = $oExpressionBefore->Translate($aTranslationMap, false);
		static::assertTrue(true); // No exception at that point, ok
	}

	/**
	 * @dataProvider TranslateMarksFieldsAsResolvedOrNotProvider
	 * @param $sExpressionBefore
	 * @param $sTranslationMap
	 * @param $bMarkAsResolved
	 * @param $sclassForExpressionAfter
	 **/
	public function testTranslateMarksFieldsAsResolvedOrNot($sExpressionBefore, $sTranslationMap, $bMarkAsResolved, $sclassForExpressionAfter)
	{
		$oExpressionBefore = Expression::FromOQL($sExpressionBefore);
		$aTranslationMap = eval('return '.$sTranslationMap.';');
		$oExpressionAfter = $oExpressionBefore->Translate($aTranslationMap, true, $bMarkAsResolved);
		static::assertIsObject($oExpressionAfter);
		static::assertEquals($sclassForExpressionAfter, get_class($oExpressionAfter));
	}


	public function TranslateMarksFieldsAsResolvedOrNotProvider()
	{
		return [
			'Translation of class/table alias' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['*' => 'alias2']]",
				'mark-as-resolved' => true,
				'class-for-expression-after' => "FieldExpressionResolved"
			],
			'Translation of class/table alias and opt-out on bMarkFieldsAsResolved' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['*' => 'alias2']]",
				'mark-as-resolved' => false,
				'class-for-expression-after' => "FieldExpression"
			],
			'Decomposition of fields' => [
				'before' => "alias1.column1",
				'map' => "['alias1' => ['column1' => new \FieldExpression('col2', 'alias2')]]",
				'mark-as-resolved' => true,
				'class-for-expression-after' => "FieldExpression"
			],
		];
	}
}
