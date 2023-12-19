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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A submit button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SubmitType extends AbstractType implements SubmitButtonTypeInterface
{
    /**
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['clicked'] = $form->isClicked();

        if (!$options['validate']) {
            $view->vars['attr']['formnovalidate'] = true;
        }
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('validate', true);
        $resolver->setAllowedTypes('validate', 'bool');
    }

    public function getParent(): ?string
    {
        return ButtonType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'submit';
    }
}
