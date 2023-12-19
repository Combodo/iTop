<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\ChoiceList\Loader;

use Symfony\Component\Form\ChoiceList\ChoiceListInterface;

/**
 * Loads a choice list.
 *
 * The methods {@link loadChoicesForValues()} and {@link loadValuesForChoices()}
 * can be used to load the list only partially in cases where a fully-loaded
 * list is not necessary.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface ChoiceLoaderInterface
{
    /**
     * Loads a list of choices.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param callable|null $value The callable which generates the values
     *                             from choices
     */
    public function loadChoiceList(callable $value = null): ChoiceListInterface;

    /**
     * Loads the choices corresponding to the given values.
     *
     * The choices are returned with the same keys and in the same order as the
     * corresponding values in the given array.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param string[]      $values An array of choice values. Non-existing
     *                              values in this array are ignored
     * @param callable|null $value  The callable generating the choice values
     */
    public function loadChoicesForValues(array $values, callable $value = null): array;

    /**
     * Loads the values corresponding to the given choices.
     *
     * The values are returned with the same keys and in the same order as the
     * corresponding choices in the given array.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param array         $choices An array of choices. Non-existing choices in
     *                               this array are ignored
     * @param callable|null $value   The callable generating the choice values
     *
     * @return string[]
     */
    public function loadValuesForChoices(array $choices, callable $value = null): array;
}
