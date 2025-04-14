<?php

/*
 * This file is part of the SymfonyCasts DynamicForms package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfonycasts\DynamicForms;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\ClearableErrorsInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * Wraps the normal form builder & to add addDynamic() to it.
 *
 * @author Ryan Weaver
 */
class DynamicFormBuilder implements FormBuilderInterface, \IteratorAggregate
{
    /**
     * @var DependentFieldConfig[]
     */
    private array $dependentFieldConfigs = [];

    /**
     * The actual form that this builder is turned into.
     */
    private FormInterface $form;

    private array $preSetDataDependencyData = [];
    private array $postSubmitDependencyData = [];

    public function __construct(private FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $this->form = $event->getForm();
            $this->preSetDataDependencyData = [];
            $this->initializeListeners();

            // A fake hidden field where we can "store" an error if a dependent form
            // field is suddenly invalid because its previous data was invalid
            // and a field it depends on just changed (e.g. user selected "Michigan"
            // as a state, then the user changed "Country" from "USA" to "Mexico"
            // and so now "Michigan" is invalid). In this case, we clear the error
            // on the actual field, but store a "fake" error here, which won't be
            // rendered, but will prevent the form from being valid.
            if (!$this->form->has('__dynamic_error')) {
                $this->form->add('__dynamic_error', HiddenType::class, [
                    'mapped' => false,
                    'error_bubbling' => false,
                ]);
            }
        }, 100);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $this->postSubmitDependencyData = [];
        });
        // guarantee later than core ValidationListener
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $this->clearDataOnTransformationError($event);
        }, -1);
    }

    public function addDependent(string $name, string|array $dependencies, callable $callback): self
    {
        $dependencies = (array) $dependencies;

        $this->dependentFieldConfigs[] = new DependentFieldConfig($name, $dependencies, $callback);

        return $this;
    }

    public function storePreSetDataDependencyData(FormEvent $event): void
    {
        $dependency = $event->getForm()->getName();
        $this->preSetDataDependencyData[$dependency] = $event->getData();

        $this->executeReadyCallbacks($this->preSetDataDependencyData, FormEvents::PRE_SET_DATA);
    }

    public function storePostSubmitDependencyData(FormEvent $event): void
    {
        $dependency = $event->getForm()->getName();
        $this->postSubmitDependencyData[$dependency] = $event->getForm()->getData();

        $this->executeReadyCallbacks($this->postSubmitDependencyData, FormEvents::POST_SUBMIT);
    }

    public function clearDataOnTransformationError(FormEvent $event): void
    {
        $form = $event->getForm();
        $transformationErrorsCleared = false;
        foreach ($this->dependentFieldConfigs as $dependentFieldConfig) {
            if (!$form->has($dependentFieldConfig->name)) {
                continue;
            }

            $subForm = $form->get($dependentFieldConfig->name);
            if ($subForm->getTransformationFailure() && $subForm instanceof ClearableErrorsInterface) {
                $subForm->clearErrors();
                $transformationErrorsCleared = true;
            }
        }

        if ($transformationErrorsCleared) {
            // We've cleared the error, but the bad data remains on the field.
            // We need to make sure that the form doesn't submit successfully,
            // but we also don't want to render a validation error on any field.
            // So, we jam the error into a hidden field, which doesn't render errors.
            if ($form->get('__dynamic_error')->isValid()) {
                $form->get('__dynamic_error')->addError(new FormError('Some dynamic fields have errors.'));
            }
        }
    }

    private function executeReadyCallbacks(array $availableDependencyData, string $eventName): void
    {
        foreach ($this->dependentFieldConfigs as $dependentFieldConfig) {
            if ($dependentFieldConfig->isReady($availableDependencyData, $eventName)) {
                $dynamicField = $dependentFieldConfig->execute($availableDependencyData, $eventName);
                $name = $dependentFieldConfig->name;

                if (!$dynamicField->shouldBeAdded()) {
                    $this->form->remove($name);

                    continue;
                }

                $this->builder->add($name, $dynamicField->getType(), $dynamicField->getOptions());

                $this->initializeListeners([$name]);
                // auto initialize mimics FormBuilder::getForm() behavior
                $field = $this->builder->get($name)->setAutoInitialize(false)->getForm();
                $this->form->add($field);
            }
        }
    }

    private function initializeListeners(?array $fieldsToConsider = null): void
    {
        $registeredFields = [];
        foreach ($this->dependentFieldConfigs as $dynamicField) {
            foreach ($dynamicField->dependencies as $dependency) {
                if ($fieldsToConsider && !\in_array($dependency, $fieldsToConsider)) {
                    continue;
                }

                // skip dependencies that are possibly not *yet* part of the form
                if (!$this->builder->has($dependency)) {
                    continue;
                }

                if (\in_array($dependency, $registeredFields)) {
                    continue;
                }

                $registeredFields[] = $dependency;

                $this->builder->get($dependency)->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'storePreSetDataDependencyData']);
                $this->builder->get($dependency)->addEventListener(FormEvents::POST_SUBMIT, [$this, 'storePostSubmitDependencyData']);
            }
        }
    }

    /*
     * ----------------------------------------
     *
     * Pure decoration methods below.
     *
     * ----------------------------------------
     */

    public function count(): int
    {
        return $this->builder->count();
    }

    /**
     * @param string|FormBuilderInterface $child
     */
    public function add($child, ?string $type = null, array $options = []): static
    {
        $this->builder->add($child, $type, $options);

        return $this;
    }

    public function create(string $name, ?string $type = null, array $options = []): FormBuilderInterface
    {
        return $this->builder->create($name, $type, $options);
    }

    public function get(string $name): FormBuilderInterface
    {
        return $this->builder->get($name);
    }

    public function remove(string $name): static
    {
        $this->builder->remove($name);

        return $this;
    }

    public function has(string $name): bool
    {
        return $this->builder->has($name);
    }

    public function all(): array
    {
        return $this->builder->all();
    }

    public function getForm(): FormInterface
    {
        return $this->builder->getForm();
    }

    public function addEventListener(string $eventName, callable $listener, int $priority = 0): static
    {
        $this->builder->addEventListener($eventName, $listener, $priority);

        return $this;
    }

    public function addEventSubscriber(EventSubscriberInterface $subscriber): static
    {
        $this->builder->addEventSubscriber($subscriber);

        return $this;
    }

    public function addViewTransformer(DataTransformerInterface $viewTransformer, bool $forcePrepend = false): static
    {
        $this->builder->addViewTransformer($viewTransformer, $forcePrepend);

        return $this;
    }

    public function resetViewTransformers(): static
    {
        $this->builder->resetViewTransformers();

        return $this;
    }

    public function addModelTransformer(DataTransformerInterface $modelTransformer, bool $forceAppend = false): static
    {
        $this->builder->addModelTransformer($modelTransformer, $forceAppend);

        return $this;
    }

    public function resetModelTransformers(): static
    {
        $this->builder->resetModelTransformers();

        return $this;
    }

    public function setAttribute(string $name, mixed $value): static
    {
        $this->builder->setAttribute($name, $value);

        return $this;
    }

    public function setAttributes(array $attributes): static
    {
        $this->builder->setAttributes($attributes);

        return $this;
    }

    public function setDataMapper(?DataMapperInterface $dataMapper = null): static
    {
        $this->builder->setDataMapper($dataMapper);

        return $this;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->builder->setDisabled($disabled);

        return $this;
    }

    public function setEmptyData(mixed $emptyData): static
    {
        $this->builder->setEmptyData($emptyData);

        return $this;
    }

    public function setErrorBubbling(bool $errorBubbling): static
    {
        $this->builder->setErrorBubbling($errorBubbling);

        return $this;
    }

    public function setInheritData(bool $inheritData): static
    {
        $this->builder->setInheritData($inheritData);

        return $this;
    }

    public function setMapped(bool $mapped): static
    {
        $this->builder->setMapped($mapped);

        return $this;
    }

    public function setMethod(string $method): static
    {
        $this->builder->setMethod($method);

        return $this;
    }

    /**
     * @param string|PropertyPathInterface|null $propertyPath
     */
    public function setPropertyPath($propertyPath): static
    {
        $this->builder->setPropertyPath($propertyPath);

        return $this;
    }

    public function setRequired(bool $required): static
    {
        $this->builder->setRequired($required);

        return $this;
    }

    public function setAction(?string $action): static
    {
        $this->builder->setAction($action);

        return $this;
    }

    public function setCompound(bool $compound): static
    {
        $this->builder->setCompound($compound);

        return $this;
    }

    public function setDataLocked(bool $locked): static
    {
        $this->builder->setDataLocked($locked);

        return $this;
    }

    public function setFormFactory(FormFactoryInterface $formFactory): static
    {
        $this->builder->setFormFactory($formFactory);

        return $this;
    }

    public function setType(?ResolvedFormTypeInterface $type): static
    {
        $this->builder->setType($type);

        return $this;
    }

    public function setRequestHandler(?RequestHandlerInterface $requestHandler): static
    {
        $this->builder->setRequestHandler($requestHandler);

        return $this;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->builder->getAttribute($name, $default);
    }

    public function hasAttribute(string $name): bool
    {
        return $this->builder->hasAttribute($name);
    }

    public function getAttributes(): array
    {
        return $this->builder->getAttributes();
    }

    public function getDataMapper(): ?DataMapperInterface
    {
        return $this->builder->getDataMapper();
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->builder->getEventDispatcher();
    }

    public function getName(): string
    {
        return $this->builder->getName();
    }

    public function getPropertyPath(): ?PropertyPathInterface
    {
        return $this->builder->getPropertyPath();
    }

    public function getRequestHandler(): RequestHandlerInterface
    {
        return $this->builder->getRequestHandler();
    }

    public function getType(): ResolvedFormTypeInterface
    {
        return $this->builder->getType();
    }

    public function setByReference(bool $byReference): static
    {
        $this->builder->setByReference($byReference);

        return $this;
    }

    public function setData(mixed $data): static
    {
        $this->builder->setData($data);

        return $this;
    }

    public function setAutoInitialize(bool $initialize): static
    {
        $this->builder->setAutoInitialize($initialize);

        return $this;
    }

    public function getFormConfig(): FormConfigInterface
    {
        return $this->builder->getFormConfig();
    }

    public function setIsEmptyCallback(?callable $isEmptyCallback): static
    {
        $this->builder->setIsEmptyCallback($isEmptyCallback);

        return $this;
    }

    public function getMapped(): bool
    {
        return $this->builder->getMapped();
    }

    public function getByReference(): bool
    {
        return $this->builder->getByReference();
    }

    public function getInheritData(): bool
    {
        return $this->builder->getInheritData();
    }

    public function getCompound(): bool
    {
        return $this->builder->getCompound();
    }

    public function getViewTransformers(): array
    {
        return $this->builder->getViewTransformers();
    }

    public function getModelTransformers(): array
    {
        return $this->builder->getModelTransformers();
    }

    public function getRequired(): bool
    {
        return $this->builder->getRequired();
    }

    public function getDisabled(): bool
    {
        return $this->builder->getDisabled();
    }

    public function getErrorBubbling(): bool
    {
        return $this->builder->getErrorBubbling();
    }

    public function getEmptyData(): mixed
    {
        return $this->builder->getEmptyData();
    }

    public function getData(): mixed
    {
        return $this->builder->getData();
    }

    public function getDataClass(): ?string
    {
        return $this->builder->getDataClass();
    }

    public function getDataLocked(): bool
    {
        return $this->builder->getDataLocked();
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->builder->getFormFactory();
    }

    public function getAction(): string
    {
        return $this->builder->getAction();
    }

    public function getMethod(): string
    {
        return $this->builder->getMethod();
    }

    public function getAutoInitialize(): bool
    {
        return $this->builder->getAutoInitialize();
    }

    public function getOptions(): array
    {
        return $this->builder->getOptions();
    }

    public function hasOption(string $name): bool
    {
        return $this->builder->hasOption($name);
    }

    public function getOption(string $name, mixed $default = null): mixed
    {
        return $this->builder->getOption($name, $default);
    }

    public function getIsEmptyCallback(): ?callable
    {
        return $this->builder->getIsEmptyCallback();
    }

    public function getIterator(): \Traversable
    {
        return $this->builder;
    }
}
