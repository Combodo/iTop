<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * Plumbing for iTop custom form builder.
 */
class ResolvedFormTypeFactory implements ResolvedFormTypeFactoryInterface
{
	public function createResolvedType(FormTypeInterface $type, array $typeExtensions, ?ResolvedFormTypeInterface $parent = null): ResolvedFormTypeInterface
	{
		return new ResolvedFormType($type, $typeExtensions, $parent);
	}
}