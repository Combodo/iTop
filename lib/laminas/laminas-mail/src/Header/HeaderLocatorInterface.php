<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\Mail\Header;

/**
 * Interface detailing how to resolve header names to classes.
 */
interface HeaderLocatorInterface
{
    public function get(string $name, ?string $default = null): ?string;

    public function has(string $name): bool;

    public function add(string $name, string $class): void;

    public function remove(string $name): void;
}
