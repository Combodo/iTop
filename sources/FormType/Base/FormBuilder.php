<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FormType\Base;

use Combodo\iTop\FormType\Orm\AttCodeGroupByType;
use Combodo\iTop\FormType\Orm\ValuesFromAttcodeType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class FormBuilder implements FormBuilderInterface, \IteratorAggregate
{
	public array $aModelData = [];

	public function __construct(private FormBuilderInterface $builder)
	{
		$this->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			$this->aModelData = [];
		});
	}

	public function Finalize(): void
	{
		$aCallbacks['query'] =  function (FormEvent $event) {
			if ($event instanceof PostSubmitEvent) {
				$this->aModelData['query'] = $event->getForm()->getData();
				$options['hook_type'] = FormEvents::POST_SUBMIT;
			} else {
				$this->aModelData['query'] = $event->getData()['query'];
				$options['hook_type'] = FormEvents::POST_SET_DATA;
			}
			$options['callback'] = function (FormEvent $event) {
				$this->aModelData['group_by'] = $event->getForm()->getData();
				$oParentForm = $event->getForm()->getParent();
				$aUserOptions = [
					'source_attcode' => 'group_by',
					'source_class' => 'query',
				];
				// TODO Filtrer seulement les données requises dans aModelData
				$aFieldOptions = (new ValuesFromAttcodeType())->BuildOptions($aUserOptions, $this->aModelData);
				if (!is_null($aFieldOptions)) {
					$oParentForm->add('values', ValuesFromAttcodeType::class, $aFieldOptions);
				} else {
					// Remove field and dependencies
					$oParentForm->add('values', HiddenType::class, ['mapped' => false]);
				}
			};
			if ($event instanceof PostSubmitEvent) {
				$oForm = $event->getForm()->getParent();
			} else {
				$oForm = $event->getForm();
			}

			$aUserOptions = [
				'source_class' => 'query',
			];
			// TODO Filtrer seulement les données requises dans aModelData
			$aFieldOptions = (new AttCodeGroupByType())->BuildOptions($aUserOptions, $this->aModelData);
			if (!is_null($aFieldOptions)) {
				$oForm->add('group_by', AttCodeGroupByType::class, array_merge($options, $aFieldOptions));
			} else {
				// Remove field and dependencies
				$oForm->add('group_by', HiddenType::class, ['mapped' => false]);
				$oForm->add('values', HiddenType::class, ['mapped' => false]);
			}
		};

		foreach($aCallbacks as $sField => $cCallback) {
			$this->addEventListener(FormEvents::POST_SET_DATA, $cCallback);
			$this->get($sField)->addEventListener(FormEvents::POST_SUBMIT, $cCallback);
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
		if (!is_subclass_of($type,  AbstractType::class)) {
			throw new \Exception("type must be an instance of AbstractType (found $type)");
		}
		$oType = new $type();
		$aPrerequisites = $oType->GetPrerequisites($options);
		if (is_null($aPrerequisites)) {
			$this->builder->add($child, $type, $options);
		} else {
			$this->aDynamicFields[$child] = [
				'type' => $type,
				'prerequisites' => $aPrerequisites,
				'user_options' => $options,
			];
			$this->builder->add($child, HiddenType::class, ['mapped' => false]);
		}
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