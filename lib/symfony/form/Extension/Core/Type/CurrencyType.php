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
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractType
{
    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choice_loader' => function (Options $options) {
                if (!class_exists(Intl::class)) {
                    throw new LogicException(sprintf('The "symfony/intl" component is required to use "%s". Try running "composer require symfony/intl".', static::class));
                }

                $choiceTranslationLocale = $options['choice_translation_locale'];

                return ChoiceList::loader($this, new IntlCallbackChoiceLoader(static fn () => array_flip(Currencies::getNames($choiceTranslationLocale))), $choiceTranslationLocale);
            },
            'choice_translation_domain' => false,
            'choice_translation_locale' => null,
            'invalid_message' => 'Please select a valid currency.',
        ]);

        $resolver->setAllowedTypes('choice_translation_locale', ['null', 'string']);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'currency';
    }
}
