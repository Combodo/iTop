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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ColorType extends AbstractType
{
    /**
     * @see https://www.w3.org/TR/html52/sec-forms.html#color-state-typecolor
     */
    private const HTML5_PATTERN = '/^#[0-9a-f]{6}$/i';

    private ?TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }

    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['html5']) {
            return;
        }

        $translator = $this->translator;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, static function (FormEvent $event) use ($translator): void {
            $value = $event->getData();
            if (null === $value || '' === $value) {
                return;
            }

            if (\is_string($value) && preg_match(self::HTML5_PATTERN, $value)) {
                return;
            }

            $messageTemplate = 'This value is not a valid HTML5 color.';
            $messageParameters = [
                '{{ value }}' => \is_scalar($value) ? (string) $value : \gettype($value),
            ];
            $message = $translator?->trans($messageTemplate, $messageParameters, 'validators') ?? $messageTemplate;

            $event->getForm()->addError(new FormError($message, $messageTemplate, $messageParameters));
        });
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'html5' => false,
            'invalid_message' => 'Please select a valid color.',
        ]);

        $resolver->setAllowedTypes('html5', 'bool');
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'color';
    }
}
