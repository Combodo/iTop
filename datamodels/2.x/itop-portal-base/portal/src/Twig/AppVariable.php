<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\Twig;

use ArrayAccess;
use Symfony\Bridge\Twig\AppVariable as DecoratedAppVariable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AppVariable
 *
 * @package Combodo\iTop\Portal\Twig
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.7.0
 */
class AppVariable implements ArrayAccess
{
    /** @var ContainerInterface */
    private $container;

    /** @var DecoratedAppVariable */
    private $decorated;


    public function __construct(DecoratedAppVariable $decorated, ContainerInterface $container = null)
    {
        $this->decorated = $decorated;
        $this->container = $container;
    }

    public function __call($name, $arguments)
    {
        if ($this->silexApplicationEmulation->offsetExists($name)) {
            return $this->silexApplicationEmulation->offsetGet($name);
        }

        return $this->decorated->$name(...$arguments); //WARNING: use of http://php.net/manual/fr/functions.arguments.php#functions.variable-arg-list
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        if ($this->container->hasParameter($offset)) {
            return true;
        }
        if ($this->container->has($offset)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
	#[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
	    if ($this->container->hasParameter($offset)) {
		    return $this->container->getParameter($offset);
	    }
	    if ($this->container->has($offset)) {
		    return $this->container->get($offset);
	    }

	    return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {

        if ($this->container->hasParameter($offset)) {
            $this->container->setParameter($offset, $value);
            return;
        }

        if ($this->container->has($offset)) {
            $this->container->set($offset, $value);
            return;
        }

        if (is_object($value)) {
            $this->container->set($offset, $value);
            return;
        }

        $this->container->setParameter($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        if ($this->container->hasParameter($offset)) {
            $this->container->setParameter($offset, null);
        } else if ($this->container->has($offset)) {
	        $this->container->set($offset, null);
        }
    }

}