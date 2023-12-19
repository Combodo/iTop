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
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\StringToFloatTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberType extends AbstractType
{
    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new NumberToLocalizedStringTransformer(
            $options['scale'],
            $options['grouping'],
            $options['rounding_mode'],
            $options['html5'] ? 'en' : null
        ));

        if ('string' === $options['input']) {
            $builder->addModelTransformer(new StringToFloatTransformer($options['scale']));
        }
    }

    /**
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['html5']) {
            $view->vars['type'] = 'number';

            if (!isset($view->vars['attr']['step'])) {
                $view->vars['attr']['step'] = 'any';
            }
        } else {
            $view->vars['attr']['inputmode'] = 0 === $options['scale'] ? 'numeric' : 'decimal';
        }
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // default scale is locale specific (usually around 3)
            'scale' => null,
            'grouping' => false,
            'rounding_mode' => \NumberFormatter::ROUND_HALFUP,
            'compound' => false,
            'input' => 'number',
            'html5' => false,
            'invalid_message' => 'Please enter a number.',
        ]);

        $resolver->setAllowedValues('rounding_mode', [
            \NumberFormatter::ROUND_FLOOR,
            \NumberFormatter::ROUND_DOWN,
            \NumberFormatter::ROUND_HALFDOWN,
            \NumberFormatter::ROUND_HALFEVEN,
            \NumberFormatter::ROUND_HALFUP,
            \NumberFormatter::ROUND_UP,
            \NumberFormatter::ROUND_CEILING,
        ]);
        $resolver->setAllowedValues('input', ['number', 'string']);
        $resolver->setAllowedTypes('scale', ['null', 'int']);
        $resolver->setAllowedTypes('html5', 'bool');

        $resolver->setNormalizer('grouping', static function (Options $options, $value) {
            if (true === $value && $options['html5']) {
                throw new LogicException('Cannot use the "grouping" option when the "html5" option is enabled.');
            }

            return $value;
        });
    }

    public function getBlockPrefix(): string
    {
        return 'number';
    }
}
