<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ButtonTypeInterface;

/**
 * A reset button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResetType extends AbstractType implements ButtonTypeInterface
{
    public function getParent(): ?string
    {
        return ButtonType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'reset';
    }
}
