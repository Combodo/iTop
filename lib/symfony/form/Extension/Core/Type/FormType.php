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

use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\DataAccessor\CallbackAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\ChainAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\PropertyPathAccessor;
use Symfony\Component\Form\Extension\Core\DataMapper\DataMapper;
use Symfony\Component\Form\Extension\Core\EventListener\TrimListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Contracts\Translation\TranslatableInterface;

class FormType extends BaseType
{
    private DataMapper $dataMapper;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->dataMapper = new DataMapper(new ChainAccessor([
            new CallbackAccessor(),
            new PropertyPathAccessor($propertyAccessor ?? PropertyAccess::createPropertyAccessor()),
        ]));
    }

    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $isDataOptionSet = \array_key_exists('data', $options);

        $builder
            ->setRequired($options['required'])
            ->setErrorBubbling($options['error_bubbling'])
            ->setEmptyData($options['empty_data'])
            ->setPropertyPath($options['property_path'])
            ->setMapped($options['mapped'])
            ->setByReference($options['by_reference'])
            ->setInheritData($options['inherit_data'])
            ->setCompound($options['compound'])
            ->setData($isDataOptionSet ? $options['data'] : null)
            ->setDataLocked($isDataOptionSet)
            ->setDataMapper($options['compound'] ? $this->dataMapper : null)
            ->setMethod($options['method'])
            ->setAction($options['action']);

        if ($options['trim']) {
            $builder->addEventSubscriber(new TrimListener());
        }

        $builder->setIsEmptyCallback($options['is_empty_callback']);
    }

    /**
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $name = $form->getName();
        $helpTranslationParameters = $options['help_translation_parameters'];

        if ($view->parent) {
            if ('' === $name) {
                throw new LogicException('Form node with empty name can be used only as root form node.');
            }

            // Complex fields are read-only if they themselves or their parents are.
            if (!isset($view->vars['attr']['readonly']) && isset($view->parent->vars['attr']['readonly']) && false !== $view->parent->vars['attr']['readonly']) {
                $view->vars['attr']['readonly'] = true;
            }

            $helpTranslationParameters = array_merge($view->parent->vars['help_translation_parameters'], $helpTranslationParameters);
        }

        $formConfig = $form->getConfig();
        $view->vars = array_replace($view->vars, [
            'errors' => $form->getErrors(),
            'valid' => $form->isSubmitted() ? $form->isValid() : true,
            'value' => $form->getViewData(),
            'data' => $form->getNormData(),
            'required' => $form->isRequired(),
            'label_attr' => $options['label_attr'],
            'help' => $options['help'],
            'help_attr' => $options['help_attr'],
            'help_html' => $options['help_html'],
            'help_translation_parameters' => $helpTranslationParameters,
            'compound' => $formConfig->getCompound(),
            'method' => $formConfig->getMethod(),
            'action' => $formConfig->getAction(),
            'submitted' => $form->isSubmitted(),
        ]);
    }

    /**
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $multipart = false;

        foreach ($view->children as $child) {
            if ($child->vars['multipart']) {
                $multipart = true;
                break;
            }
        }

        $view->vars['multipart'] = $multipart;
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        // Derive "data_class" option from passed "data" object
        $dataClass = static fn (Options $options) => isset($options['data']) && \is_object($options['data']) ? $options['data']::class : null;

        // Derive "empty_data" closure from "data_class" option
        $emptyData = static function (Options $options) {
            $class = $options['data_class'];

            if (null !== $class) {
                return static fn (FormInterface $form) => $form->isEmpty() && !$form->isRequired() ? null : new $class();
            }

            return static fn (FormInterface $form) => $form->getConfig()->getCompound() ? [] : '';
        };

        // Wrap "post_max_size_message" in a closure to translate it lazily
        $uploadMaxSizeMessage = static fn (Options $options) => static fn () => $options['post_max_size_message'];

        // For any form that is not represented by a single HTML control,
        // errors should bubble up by default
        $errorBubbling = static fn (Options $options) => $options['compound'] && !$options['inherit_data'];

        // If data is given, the form is locked to that data
        // (independent of its value)
        $resolver->setDefined([
            'data',
        ]);

        $resolver->setDefaults([
            'data_class' => $dataClass,
            'empty_data' => $emptyData,
            'trim' => true,
            'required' => true,
            'property_path' => null,
            'mapped' => true,
            'by_reference' => true,
            'error_bubbling' => $errorBubbling,
            'label_attr' => [],
            'inherit_data' => false,
            'compound' => true,
            'method' => 'POST',
            // According to RFC 2396 (http://www.ietf.org/rfc/rfc2396.txt)
            // section 4.2., empty URIs are considered same-document references
            'action' => '',
            'post_max_size_message' => 'The uploaded file was too large. Please try to upload a smaller file.',
            'upload_max_size_message' => $uploadMaxSizeMessage, // internal
            'allow_file_upload' => false,
            'help' => null,
            'help_attr' => [],
            'help_html' => false,
            'help_translation_parameters' => [],
            'invalid_message' => 'This value is not valid.',
            'invalid_message_parameters' => [],
            'is_empty_callback' => null,
            'getter' => null,
            'setter' => null,
        ]);

        $resolver->setAllowedTypes('label_attr', 'array');
        $resolver->setAllowedTypes('action', 'string');
        $resolver->setAllowedTypes('upload_max_size_message', ['callable']);
        $resolver->setAllowedTypes('help', ['string', 'null', TranslatableInterface::class]);
        $resolver->setAllowedTypes('help_attr', 'array');
        $resolver->setAllowedTypes('help_html', 'bool');
        $resolver->setAllowedTypes('is_empty_callback', ['null', 'callable']);
        $resolver->setAllowedTypes('getter', ['null', 'callable']);
        $resolver->setAllowedTypes('setter', ['null', 'callable']);

        $resolver->setInfo('getter', 'A callable that accepts two arguments (the view data and the current form field) and must return a value.');
        $resolver->setInfo('setter', 'A callable that accepts three arguments (a reference to the view data, the submitted value and the current form field).');
    }

    public function getParent(): ?string
    {
        return null;
    }

    public function getBlockPrefix(): string
    {
        return 'form';
    }
}
