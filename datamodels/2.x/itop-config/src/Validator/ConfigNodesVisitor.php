<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 14:12
 */

namespace Combodo\iTop\Config\Validator;


use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ConfigNodesVisitor extends NodeVisitorAbstract
{
	private $aAllowedNodeClasses = array();

	public function __construct()
	{
		$this->aAllowedNodeClasses = array(
			Node\Scalar::class,

			Node\Name::class,

			Node\Const_::class,
			Node\Identifier::class,

			Node\Expr\Array_::class,
			Node\Expr\ArrayDimFetch::class,
			Node\Expr\ArrayItem::class,
			Node\Expr\Assign::class,
			Node\Expr\AssignOp::class,
			Node\Expr\AssignRef::class,
			Node\Expr\BinaryOp::class,
			Node\Expr\BitwiseNot::class,
			Node\Expr\BooleanNot::class,
			Node\Expr\Cast::class,
			Node\Expr\ClassConstFetch::class,
			Node\Expr\ConstFetch::class,
			Node\Expr\Instanceof_::class,
			Node\Expr\Isset_::class,
			Node\Expr\List_::class,
			Node\Expr\PostDec::class,
			Node\Expr\PostInc::class,
			Node\Expr\PreDec::class,
			Node\Expr\PreInc::class,
			Node\Expr\Print_::class,
			Node\Stmt\Expression::class,
			Node\Expr\Ternary::class,
			Node\Expr\UnaryMinus::class,
			Node\Expr\UnaryPlus::class,
			Node\Expr\Variable::class,

			Node\Stmt\Const_::class,
			Node\Stmt\Global_::class,
		);
	}

	/**
	 * @param \PhpParser\Node $node
	 *
	 * @return int|\PhpParser\Node|void|null
	 * @throws \Exception
	 */
	public function enterNode(Node $node)
	{
		$this->ValidateNode($node);
	}

	/**
	 * @param \PhpParser\Node $node
	 *
	 * @throws \Exception
	 */
	public function ValidateNode(Node $node)
	{
		foreach ($this->aAllowedNodeClasses as $sAllowedNodeClass)
		{
			if ($node instanceof $sAllowedNodeClass)
			{
				return;
			}
		}

		$this->ThrowInvalidConf($node);
	}

	/**
	 * @param \PhpParser\Node $node
	 *
	 * @throws \Exception
	 */
	private function ThrowInvalidConf(Node $node)
	{
		if (in_array('name', $node->getSubNodeNames()))
		{
			$sMessage = sprintf(
				"Invalid configuration: %s of type %s is forbidden in line %d",
				$node->name,
				$node->getType(),
				$node->getLine()
			);
		}
		elseif (in_array('class', $node->getSubNodeNames()))
		{

			if (in_array('name', $node->class->getSubNodeNames()))
			{
				$sMessage = sprintf(
					"Invalid configuration: usage of the class '%s' (%s) is forbidden in line %d",
					is_object($node->class) ? $node->class->name : $node->class,
					$node->getType(),
					$node->getLine()
				);
			}
			else
			{
				$sMessage = sprintf(
					"Invalid configuration: usage of %s is forbidden in line %d",
					$node->getType(),
					$node->getLine()
				);
			}
		}
		elseif ($node->hasAttribute('name'))
		{
			$sMessage = sprintf(
				"Invalid configuration: %s of type %s is forbidden in line %d",
				$node->getAttribute('name'),
				$node->getType(),
				$node->getLine()
			);
		}
		else
		{
			$sMessage = sprintf(
				"Invalid configuration: %s is forbidden in line %d",
				$node->getType(),
				$node->getLine()
			);
		}

		throw new \Exception($sMessage);
	}
}