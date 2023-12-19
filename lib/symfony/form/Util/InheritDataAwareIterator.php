<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Util;

/**
 * Iterator that traverses an array of forms.
 *
 * Contrary to \ArrayIterator, this iterator recognizes changes in the original
 * array during iteration.
 *
 * You can wrap the iterator into a {@link \RecursiveIteratorIterator} in order to
 * enter any child form that inherits its parent's data and iterate the children
 * of that form as well.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class InheritDataAwareIterator extends \IteratorIterator implements \RecursiveIterator
{
    public function getChildren(): static
    {
        return new static($this->current());
    }

    public function hasChildren(): bool
    {
        return (bool) $this->current()->getConfig()->getInheritData();
    }
}
