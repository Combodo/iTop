<?php

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Traversable;

class FormBuilder implements FormBuilderInterface, \IteratorAggregate
{
	private ?DependencyHandler $oDependencyHandler = null;
	private array $aFormBlocks = [];

	/**
	 * Constructor.
	 *
	 * @param FormBuilderInterface $builder
	 */
	public function __construct(private FormBuilderInterface $builder)
	{
		$this->InitFormBlocks();
	}

	public function add(string|FormBuilderInterface $child, ?string $type = null, array $options = []): static
	{
		$this->builder->add($child, $type, $options);
		return $this;
	}

	private function InitFormBlocks()
	{
		$oFormBlock = $this->builder->getOption('form_block');
		if (is_null($oFormBlock)) {
			return;
		}

		$aDependentBlocks = [];
		/** @var \Combodo\iTop\Forms\Block\FormBlock $oSubFormBlock */
		foreach ($oFormBlock->GetSubFormBlocks() as $oSubFormBlock) {
			$this->aFormBlocks[$oSubFormBlock->getName()] = $oSubFormBlock;
			if ($oSubFormBlock->HasConnections()) {
				$this->builder->add($oSubFormBlock->GetName(), HiddenType::class);
				$aDependentBlocks[] = $oSubFormBlock;
			} else {
				$this->add($oSubFormBlock->GetName(), $oSubFormBlock->GetFormType(), $oSubFormBlock->getOptions());
			}
		}

		if (count($aDependentBlocks) > 0) {
			$this->oDependencyHandler = new DependencyHandler($this, $aDependentBlocks);
		}
	}

	public function GetFormBlock(string $sName): ?AbstractFormBlock
	{
		return $this->aFormBlocks[$sName] ?? null;
	}

	// pure decoration of FormBuilderInterface

	public function getIterator(): Traversable
	{
		return $this->builder->getIterator();
	}

	public function count(): int
	{
		return $this->builder->count();
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

	public function setDataMapper(?DataMapperInterface $dataMapper): static
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

	public function setRequired(bool $required): static
	{
		$this->builder->setRequired($required);
		return $this;
	}

	public function setPropertyPath(PropertyPathInterface|string|null $propertyPath): static
	{
		$this->builder->setPropertyPath($propertyPath);
		return $this;
	}

	public function setMapped(bool $mapped): static
	{
		$this->builder->setMapped($mapped);
		return $this;
	}

	public function setByReference(bool $byReference): static
	{
		$this->builder->setByReference($byReference);
		return $this;
	}

	public function setInheritData(bool $inheritData): static
	{
		$this->builder->setInheritData($inheritData);
		return $this;
	}

	public function setCompound(bool $compound): static
	{
		$this->builder->setCompound($compound);
		return $this;
	}

	public function setType(ResolvedFormTypeInterface $type): static
	{
		$this->builder->setType($type);
		return $this;
	}

	public function setData(mixed $data): static
	{
		$this->builder->setData($data);
		return $this;
	}

	public function setDataLocked(bool $locked): static
	{
		$this->builder->setDataLocked($locked);
		return $this;
	}

	public function setFormFactory(FormFactoryInterface $formFactory)
	{
		$this->builder->setFormFactory($formFactory);
	}

	public function setAction(string $action): static
	{
		$this->builder->setAction($action);
		return $this;
	}

	public function setMethod(string $method): static
	{
		$this->builder->setMethod($method);
		return $this;
	}

	public function setRequestHandler(RequestHandlerInterface $requestHandler): static
	{
		$this->builder->setRequestHandler($requestHandler);
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

	public function getType(): ResolvedFormTypeInterface
	{
		return $this->builder->getType();
	}

	public function getViewTransformers(): array
	{
		return $this->builder->getViewTransformers();
	}

	public function getModelTransformers(): array
	{
		return $this->builder->getModelTransformers();
	}

	public function getDataMapper(): ?DataMapperInterface
	{
		return $this->builder->getDataMapper();
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

	public function getAttributes(): array
	{
		return $this->builder->getAttributes();
	}

	public function hasAttribute(string $name): bool
	{
		return $this->builder->hasAttribute($name);
	}

	public function getAttribute(string $name, mixed $default = null): mixed
	{
		return $this->builder->getAttribute($name, $default);
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

	public function getRequestHandler(): RequestHandlerInterface
	{
		return $this->builder->getRequestHandler();
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
}