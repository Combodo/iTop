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

/**
 * A decorator to filter choices only when they are loaded or partially loaded.
 *
 * @author Jules Pietri <jules@heahprod.com>
 */
class FilterChoiceLoaderDecorator extends AbstractChoiceLoader
{
    private ChoiceLoaderInterface $decoratedLoader;
    private \Closure $filter;

    public function __construct(ChoiceLoaderInterface $loader, callable $filter)
    {
        $this->decoratedLoader = $loader;
        $this->filter = $filter(...);
    }

    protected function loadChoices(): iterable
    {
        $list = $this->decoratedLoader->loadChoiceList();

        if (array_values($list->getValues()) === array_values($structuredValues = $list->getStructuredValues())) {
            return array_filter(array_combine($list->getOriginalKeys(), $list->getChoices()), $this->filter);
        }

        foreach ($structuredValues as $group => $values) {
            if (\is_array($values)) {
                if ($values && $filtered = array_filter($list->getChoicesForValues($values), $this->filter)) {
                    $choices[$group] = $filtered;
                }
                continue;
                // filter empty groups
            }

            if ($filtered = array_filter($list->getChoicesForValues([$values]), $this->filter)) {
                $choices[$group] = $filtered[0];
            }
        }

        return $choices ?? [];
    }

    public function loadChoicesForValues(array $values, callable $value = null): array
    {
        return array_filter($this->decoratedLoader->loadChoicesForValues($values, $value), $this->filter);
    }

    public function loadValuesForChoices(array $choices, callable $value = null): array
    {
        return $this->decoratedLoader->loadValuesForChoices(array_filter($choices, $this->filter), $value);
    }
}
