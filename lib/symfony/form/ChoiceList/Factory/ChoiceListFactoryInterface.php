<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\ChoiceList\Factory;

use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceListView;

/**
 * Creates {@link ChoiceListInterface} instances.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface ChoiceListFactoryInterface
{
    /**
     * Creates a choice list for the given choices.
     *
     * The choices should be passed in the values of the choices array.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param callable|null $filter The callable filtering the choices
     */
    public function createListFromChoices(iterable $choices, callable $value = null, callable $filter = null): ChoiceListInterface;

    /**
     * Creates a choice list that is loaded with the given loader.
     *
     * Optionally, a callable can be passed for generating the choice values.
     * The callable receives the choice as only argument.
     * Null may be passed when the choice list contains the empty value.
     *
     * @param callable|null $filter The callable filtering the choices
     */
    public function createListFromLoader(ChoiceLoaderInterface $loader, callable $value = null, callable $filter = null): ChoiceListInterface;

    /**
     * Creates a view for the given choice list.
     *
     * Callables may be passed for all optional arguments. The callables receive
     * the choice as first and the array key as the second argument.
     *
     *  * The callable for the label and the name should return the generated
     *    label/choice name.
     *  * The callable for the preferred choices should return true or false,
     *    depending on whether the choice should be preferred or not.
     *  * The callable for the grouping should return the group name or null if
     *    a choice should not be grouped.
     *  * The callable for the attributes should return an array of HTML
     *    attributes that will be inserted in the tag of the choice.
     *
     * If no callable is passed, the labels will be generated from the choice
     * keys. The view indices will be generated using an incrementing integer
     * by default.
     *
     * The preferred choices can also be passed as array. Each choice that is
     * contained in that array will be marked as preferred.
     *
     * The attributes can be passed as multi-dimensional array. The keys should
     * match the keys of the choices. The values should be arrays of HTML
     * attributes that should be added to the respective choice.
     *
     * @param array|callable|null $preferredChoices           The preferred choices
     * @param callable|false|null $label                      The callable generating the choice labels;
     *                                                        pass false to discard the label
     * @param array|callable|null $attr                       The callable generating the HTML attributes
     * @param array|callable      $labelTranslationParameters The parameters used to translate the choice labels
     * @param bool                $duplicatePreferredChoices  Whether the preferred choices should be duplicated
     *                                                        on top of the list and in their original position
     *                                                        or only in the top of the list
     */
    public function createView(ChoiceListInterface $list, array|callable $preferredChoices = null, callable|false $label = null, callable $index = null, callable $groupBy = null, array|callable $attr = null, array|callable $labelTranslationParameters = []/* , bool $duplicatePreferredChoices = true */): ChoiceListView;
}
