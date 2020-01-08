<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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
     * Whether a offset exists
     * @link  https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
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
     * Offset to retrieve
     * @link  https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if ($this->container->hasParameter($offset)) {
            return $this->container->getParameter($offset);
        }
        if ($this->container->has($offset)) {
            return $this->container->get($offset);
        }

        return;
    }

    /**
     * Offset to set
     * @link  https://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
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
        return;
    }

    /**
     * Offset to unset
     * @link  https://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset){
        if ($this->container->hasParameter($offset)) {
            $this->container->setParameter($offset, null);
            return;
        }

        if ($this->container->has($offset)) {
            $this->container->set($offset, null);
            return;
        }
    }

}