<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form;

/**
 * The central registry of the Form component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface FormRegistryInterface
{
    /**
     * Returns a form type by name.
     *
     * This methods registers the type extensions from the form extensions.
     *
     * @throws Exception\InvalidArgumentException if the type cannot be retrieved from any extension
     */
    public function getType(string $name): ResolvedFormTypeInterface;

    /**
     * Returns whether the given form type is supported.
     */
    public function hasType(string $name): bool;

    /**
     * Returns the guesser responsible for guessing types.
     */
    public function getTypeGuesser(): ?FormTypeGuesserInterface;

    /**
     * Returns the extensions loaded by the framework.
     *
     * @return FormExtensionInterface[]
     */
    public function getExtensions(): array;
}
