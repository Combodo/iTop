<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Maps choices to/from checkbox forms.
 *
 * A {@link ChoiceListInterface} implementation is used to find the
 * corresponding string values for the choices. Each checkbox form whose "value"
 * option corresponds to any of the selected values is marked as selected.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CheckboxListMapper implements DataMapperInterface
{
    /**
     * @return void
     */
    public function mapDataToForms(mixed $choices, \Traversable $checkboxes)
    {
        if (!\is_array($choices ??= [])) {
            throw new UnexpectedTypeException($choices, 'array');
        }

        foreach ($checkboxes as $checkbox) {
            $value = $checkbox->getConfig()->getOption('value');
            $checkbox->setData(\in_array($value, $choices, true));
        }
    }

    /**
     * @return void
     */
    public function mapFormsToData(\Traversable $checkboxes, mixed &$choices)
    {
        if (!\is_array($choices)) {
            throw new UnexpectedTypeException($choices, 'array');
        }

        $values = [];

        foreach ($checkboxes as $checkbox) {
            if ($checkbox->getData()) {
                // construct an array of choice values
                $values[] = $checkbox->getConfig()->getOption('value');
            }
        }

        $choices = $values;
    }
}
