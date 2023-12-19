<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckboxType extends AbstractType
{
    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Unlike in other types, where the data is NULL by default, it
        // needs to be a Boolean here. setData(null) is not acceptable
        // for checkboxes and radio buttons (unless a custom model
        // transformer handles this case).
        // We cannot solve this case via overriding the "data" option, because
        // doing so also calls setDataLocked(true).
        $builder->setData($options['data'] ?? false);
        $builder->addViewTransformer(new BooleanToStringTransformer($options['value'], $options['false_values']));
    }

    /**
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'value' => $options['value'],
            'checked' => null !== $form->getViewData(),
        ]);
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $emptyData = static fn (FormInterface $form, $viewData) => $viewData;

        $resolver->setDefaults([
            'value' => '1',
            'empty_data' => $emptyData,
            'compound' => false,
            'false_values' => [null],
            'invalid_message' => 'The checkbox has an invalid value.',
            'is_empty_callback' => static fn ($modelData): bool => false === $modelData,
        ]);

        $resolver->setAllowedTypes('false_values', 'array');
    }

    public function getBlockPrefix(): string
    {
        return 'checkbox';
    }
}
