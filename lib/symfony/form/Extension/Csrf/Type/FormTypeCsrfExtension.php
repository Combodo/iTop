<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Csrf\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Csrf\EventListener\CsrfValidationListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormTypeCsrfExtension extends AbstractTypeExtension
{
    private CsrfTokenManagerInterface $defaultTokenManager;
    private bool $defaultEnabled;
    private string $defaultFieldName;
    private ?TranslatorInterface $translator;
    private ?string $translationDomain;
    private ?ServerParams $serverParams;

    public function __construct(CsrfTokenManagerInterface $defaultTokenManager, bool $defaultEnabled = true, string $defaultFieldName = '_token', TranslatorInterface $translator = null, string $translationDomain = null, ServerParams $serverParams = null)
    {
        $this->defaultTokenManager = $defaultTokenManager;
        $this->defaultEnabled = $defaultEnabled;
        $this->defaultFieldName = $defaultFieldName;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        $this->serverParams = $serverParams;
    }

    /**
     * Adds a CSRF field to the form when the CSRF protection is enabled.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['csrf_protection']) {
            return;
        }

        $builder
            ->addEventSubscriber(new CsrfValidationListener(
                $options['csrf_field_name'],
                $options['csrf_token_manager'],
                $options['csrf_token_id'] ?: ($builder->getName() ?: $builder->getType()->getInnerType()::class),
                $options['csrf_message'],
                $this->translator,
                $this->translationDomain,
                $this->serverParams
            ))
        ;
    }

    /**
     * Adds a CSRF field to the root form view.
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['csrf_protection'] && !$view->parent && $options['compound']) {
            $factory = $form->getConfig()->getFormFactory();
            $tokenId = $options['csrf_token_id'] ?: ($form->getName() ?: $form->getConfig()->getType()->getInnerType()::class);
            $data = (string) $options['csrf_token_manager']->getToken($tokenId);

            $csrfForm = $factory->createNamed($options['csrf_field_name'], HiddenType::class, $data, [
                'block_prefix' => 'csrf_token',
                'mapped' => false,
            ]);

            $view->children[$options['csrf_field_name']] = $csrfForm->createView($view);
        }
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => $this->defaultEnabled,
            'csrf_field_name' => $this->defaultFieldName,
            'csrf_message' => 'The CSRF token is invalid. Please try to resubmit the form.',
            'csrf_token_manager' => $this->defaultTokenManager,
            'csrf_token_id' => null,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
