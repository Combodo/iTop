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
use Symfony\Contracts\Service\ResetInterface;

/**
 * Caches the choice lists created by the decorated factory.
 *
 * To cache a list based on its options, arguments must be decorated
 * by a {@see Cache\AbstractStaticOption} implementation.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class CachingFactoryDecorator implements ChoiceListFactoryInterface, ResetInterface
{
    private ChoiceListFactoryInterface $decoratedFactory;

    /**
     * @var ChoiceListInterface[]
     */
    private array $lists = [];

    /**
     * @var ChoiceListView[]
     */
    private array $views = [];

    /**
     * Generates a SHA-256 hash for the given value.
     *
     * Optionally, a namespace string can be passed. Calling this method will
     * the same values, but different namespaces, will return different hashes.
     *
     * @return string The SHA-256 hash
     *
     * @internal
     */
    public static function generateHash(mixed $value, string $namespace = ''): string
    {
        if (\is_object($value)) {
            $value = spl_object_hash($value);
        } elseif (\is_array($value)) {
            array_walk_recursive($value, static function (&$v) {
                if (\is_object($v)) {
                    $v = spl_object_hash($v);
                }
            });
        }

        return hash('sha256', $namespace.':'.serialize($value));
    }

    public function __construct(ChoiceListFactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    /**
     * Returns the decorated factory.
     */
    public function getDecoratedFactory(): ChoiceListFactoryInterface
    {
        return $this->decoratedFactory;
    }

    public function createListFromChoices(iterable $choices, mixed $value = null, mixed $filter = null): ChoiceListInterface
    {
        if ($choices instanceof \Traversable) {
            $choices = iterator_to_array($choices);
        }

        $cache = true;
        // Only cache per value and filter when needed. The value is not validated on purpose.
        // The decorated factory may decide which values to accept and which not.
        if ($value instanceof Cache\ChoiceValue) {
            $value = $value->getOption();
        } elseif ($value) {
            $cache = false;
        }
        if ($filter instanceof Cache\ChoiceFilter) {
            $filter = $filter->getOption();
        } elseif ($filter) {
            $cache = false;
        }

        if (!$cache) {
            return $this->decoratedFactory->createListFromChoices($choices, $value, $filter);
        }

        $hash = self::generateHash([$choices, $value, $filter], 'fromChoices');

        if (!isset($this->lists[$hash])) {
            $this->lists[$hash] = $this->decoratedFactory->createListFromChoices($choices, $value, $filter);
        }

        return $this->lists[$hash];
    }

    public function createListFromLoader(ChoiceLoaderInterface $loader, mixed $value = null, mixed $filter = null): ChoiceListInterface
    {
        $cache = true;

        if ($loader instanceof Cache\ChoiceLoader) {
            $loader = $loader->getOption();
        } else {
            $cache = false;
        }

        if ($value instanceof Cache\ChoiceValue) {
            $value = $value->getOption();
        } elseif ($value) {
            $cache = false;
        }

        if ($filter instanceof Cache\ChoiceFilter) {
            $filter = $filter->getOption();
        } elseif ($filter) {
            $cache = false;
        }

        if (!$cache) {
            return $this->decoratedFactory->createListFromLoader($loader, $value, $filter);
        }

        $hash = self::generateHash([$loader, $value, $filter], 'fromLoader');

        if (!isset($this->lists[$hash])) {
            $this->lists[$hash] = $this->decoratedFactory->createListFromLoader($loader, $value, $filter);
        }

        return $this->lists[$hash];
    }

    /**
     * @param bool $duplicatePreferredChoices
     */
    public function createView(ChoiceListInterface $list, mixed $preferredChoices = null, mixed $label = null, mixed $index = null, mixed $groupBy = null, mixed $attr = null, mixed $labelTranslationParameters = []/* , bool $duplicatePreferredChoices = true */): ChoiceListView
    {
        $duplicatePreferredChoices = \func_num_args() > 7 ? func_get_arg(7) : true;
        $cache = true;

        if ($preferredChoices instanceof Cache\PreferredChoice) {
            $preferredChoices = $preferredChoices->getOption();
        } elseif ($preferredChoices) {
            $cache = false;
        }

        if ($label instanceof Cache\ChoiceLabel) {
            $label = $label->getOption();
        } elseif (null !== $label) {
            $cache = false;
        }

        if ($index instanceof Cache\ChoiceFieldName) {
            $index = $index->getOption();
        } elseif ($index) {
            $cache = false;
        }

        if ($groupBy instanceof Cache\GroupBy) {
            $groupBy = $groupBy->getOption();
        } elseif ($groupBy) {
            $cache = false;
        }

        if ($attr instanceof Cache\ChoiceAttr) {
            $attr = $attr->getOption();
        } elseif ($attr) {
            $cache = false;
        }

        if ($labelTranslationParameters instanceof Cache\ChoiceTranslationParameters) {
            $labelTranslationParameters = $labelTranslationParameters->getOption();
        } elseif ([] !== $labelTranslationParameters) {
            $cache = false;
        }

        if (!$cache) {
            return $this->decoratedFactory->createView(
                $list,
                $preferredChoices,
                $label,
                $index,
                $groupBy,
                $attr,
                $labelTranslationParameters,
                $duplicatePreferredChoices,
            );
        }

        $hash = self::generateHash([$list, $preferredChoices, $label, $index, $groupBy, $attr, $labelTranslationParameters, $duplicatePreferredChoices]);

        if (!isset($this->views[$hash])) {
            $this->views[$hash] = $this->decoratedFactory->createView(
                $list,
                $preferredChoices,
                $label,
                $index,
                $groupBy,
                $attr,
                $labelTranslationParameters,
                $duplicatePreferredChoices,
            );
        }

        return $this->views[$hash];
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->lists = [];
        $this->views = [];
        Cache\AbstractStaticOption::reset();
    }
}
