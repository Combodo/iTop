<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form;

use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Exception\OutOfBoundsException;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Util\FormUtil;
use Symfony\Component\Form\Util\InheritDataAwareIterator;
use Symfony\Component\Form\Util\OrderedHashMap;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * Form represents a form.
 *
 * To implement your own form fields, you need to have a thorough understanding
 * of the data flow within a form. A form stores its data in three different
 * representations:
 *
 *   (1) the "model" format required by the form's object
 *   (2) the "normalized" format for internal processing
 *   (3) the "view" format used for display simple fields
 *       or map children model data for compound fields
 *
 * A date field, for example, may store a date as "Y-m-d" string (1) in the
 * object. To facilitate processing in the field, this value is normalized
 * to a DateTime object (2). In the HTML representation of your form, a
 * localized string (3) may be presented to and modified by the user, or it could be an array of values
 * to be mapped to choices fields.
 *
 * In most cases, format (1) and format (2) will be the same. For example,
 * a checkbox field uses a Boolean value for both internal processing and
 * storage in the object. In these cases you need to set a view transformer
 * to convert between formats (2) and (3). You can do this by calling
 * addViewTransformer().
 *
 * In some cases though it makes sense to make format (1) configurable. To
 * demonstrate this, let's extend our above date field to store the value
 * either as "Y-m-d" string or as timestamp. Internally we still want to
 * use a DateTime object for processing. To convert the data from string/integer
 * to DateTime you can set a model transformer by calling
 * addModelTransformer(). The normalized data is then converted to the displayed
 * data as described before.
 *
 * The conversions (1) -> (2) -> (3) use the transform methods of the transformers.
 * The conversions (3) -> (2) -> (1) use the reverseTransform methods of the transformers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @implements \IteratorAggregate<string, FormInterface>
 */
class Form implements \IteratorAggregate, FormInterface, ClearableErrorsInterface
{
    private FormConfigInterface $config;
    private ?FormInterface $parent = null;

    /**
     * A map of FormInterface instances.
     *
     * @var OrderedHashMap<FormInterface>
     */
    private OrderedHashMap $children;

    /**
     * @var FormError[]
     */
    private array $errors = [];

    private bool $submitted = false;

    /**
     * The button that was used to submit the form.
     */
    private FormInterface|ClickableInterface|null $clickedButton = null;

    private mixed $modelData = null;
    private mixed $normData = null;
    private mixed $viewData = null;

    /**
     * The submitted values that don't belong to any children.
     */
    private array $extraData = [];

    /**
     * The transformation failure generated during submission, if any.
     */
    private ?TransformationFailedException $transformationFailure = null;

    /**
     * Whether the form's data has been initialized.
     *
     * When the data is initialized with its default value, that default value
     * is passed through the transformer chain in order to synchronize the
     * model, normalized and view format for the first time. This is done
     * lazily in order to save performance when {@link setData()} is called
     * manually, making the initialization with the configured default value
     * superfluous.
     */
    private bool $defaultDataSet = false;

    /**
     * Whether setData() is currently being called.
     */
    private bool $lockSetData = false;

    private string $name = '';

    /**
     * Whether the form inherits its underlying data from its parent.
     */
    private bool $inheritData;

    private ?PropertyPathInterface $propertyPath = null;

    /**
     * @throws LogicException if a data mapper is not provided for a compound form
     */
    public function __construct(FormConfigInterface $config)
    {
        // Compound forms always need a data mapper, otherwise calls to
        // `setData` and `add` will not lead to the correct population of
        // the child forms.
        if ($config->getCompound() && !$config->getDataMapper()) {
            throw new LogicException('Compound forms need a data mapper.');
        }

        // If the form inherits the data from its parent, it is not necessary
        // to call setData() with the default data.
        if ($this->inheritData = $config->getInheritData()) {
            $this->defaultDataSet = true;
        }

        $this->config = $config;
        $this->children = new OrderedHashMap();
        $this->name = $config->getName();
    }

    public function __clone()
    {
        $this->children = clone $this->children;

        foreach ($this->children as $key => $child) {
            $this->children[$key] = clone $child;
        }
    }

    public function getConfig(): FormConfigInterface
    {
        return $this->config;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPropertyPath(): ?PropertyPathInterface
    {
        if ($this->propertyPath || $this->propertyPath = $this->config->getPropertyPath()) {
            return $this->propertyPath;
        }

        if ('' === $this->name) {
            return null;
        }

        $parent = $this->parent;

        while ($parent?->getConfig()->getInheritData()) {
            $parent = $parent->getParent();
        }

        if ($parent && null === $parent->getConfig()->getDataClass()) {
            $this->propertyPath = new PropertyPath('['.$this->name.']');
        } else {
            $this->propertyPath = new PropertyPath($this->name);
        }

        return $this->propertyPath;
    }

    public function isRequired(): bool
    {
        if (null === $this->parent || $this->parent->isRequired()) {
            return $this->config->getRequired();
        }

        return false;
    }

    public function isDisabled(): bool
    {
        if (null === $this->parent || !$this->parent->isDisabled()) {
            return $this->config->getDisabled();
        }

        return true;
    }

    public function setParent(FormInterface $parent = null): static
    {
        if (1 > \func_num_args()) {
            trigger_deprecation('symfony/form', '6.2', 'Calling "%s()" without any arguments is deprecated, pass null explicitly instead.', __METHOD__);
        }

        if ($this->submitted) {
            throw new AlreadySubmittedException('You cannot set the parent of a submitted form.');
        }

        if (null !== $parent && '' === $this->name) {
            throw new LogicException('A form with an empty name cannot have a parent form.');
        }

        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?FormInterface
    {
        return $this->parent;
    }

    public function getRoot(): FormInterface
    {
        return $this->parent ? $this->parent->getRoot() : $this;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function setData(mixed $modelData): static
    {
        // If the form is submitted while disabled, it is set to submitted, but the data is not
        // changed. In such cases (i.e. when the form is not initialized yet) don't
        // abort this method.
        if ($this->submitted && $this->defaultDataSet) {
            throw new AlreadySubmittedException('You cannot change the data of a submitted form.');
        }

        // If the form inherits its parent's data, disallow data setting to
        // prevent merge conflicts
        if ($this->inheritData) {
            throw new RuntimeException('You cannot change the data of a form inheriting its parent data.');
        }

        // Don't allow modifications of the configured data if the data is locked
        if ($this->config->getDataLocked() && $modelData !== $this->config->getData()) {
            return $this;
        }

        if (\is_object($modelData) && !$this->config->getByReference()) {
            $modelData = clone $modelData;
        }

        if ($this->lockSetData) {
            throw new RuntimeException('A cycle was detected. Listeners to the PRE_SET_DATA event must not call setData(). You should call setData() on the FormEvent object instead.');
        }

        $this->lockSetData = true;
        $dispatcher = $this->config->getEventDispatcher();

        // Hook to change content of the model data before transformation and mapping children
        if ($dispatcher->hasListeners(FormEvents::PRE_SET_DATA)) {
            $event = new PreSetDataEvent($this, $modelData);
            $dispatcher->dispatch($event, FormEvents::PRE_SET_DATA);
            $modelData = $event->getData();
        }

        // Treat data as strings unless a transformer exists
        if (\is_scalar($modelData) && !$this->config->getViewTransformers() && !$this->config->getModelTransformers()) {
            $modelData = (string) $modelData;
        }

        // Synchronize representations - must not change the content!
        // Transformation exceptions are not caught on initialization
        $normData = $this->modelToNorm($modelData);
        $viewData = $this->normToView($normData);

        // Validate if view data matches data class (unless empty)
        if (!FormUtil::isEmpty($viewData)) {
            $dataClass = $this->config->getDataClass();

            if (null !== $dataClass && !$viewData instanceof $dataClass) {
                $actualType = get_debug_type($viewData);

                throw new LogicException('The form\'s view data is expected to be a "'.$dataClass.'", but it is a "'.$actualType.'". You can avoid this error by setting the "data_class" option to null or by adding a view transformer that transforms "'.$actualType.'" to an instance of "'.$dataClass.'".');
            }
        }

        $this->modelData = $modelData;
        $this->normData = $normData;
        $this->viewData = $viewData;
        $this->defaultDataSet = true;
        $this->lockSetData = false;

        // Compound forms don't need to invoke this method if they don't have children
        if (\count($this->children) > 0) {
            // Update child forms from the data (unless their config data is locked)
            $this->config->getDataMapper()->mapDataToForms($viewData, new \RecursiveIteratorIterator(new InheritDataAwareIterator($this->children)));
        }

        if ($dispatcher->hasListeners(FormEvents::POST_SET_DATA)) {
            $event = new PostSetDataEvent($this, $modelData);
            $dispatcher->dispatch($event, FormEvents::POST_SET_DATA);
        }

        return $this;
    }

    public function getData(): mixed
    {
        if ($this->inheritData) {
            if (!$this->parent) {
                throw new RuntimeException('The form is configured to inherit its parent\'s data, but does not have a parent.');
            }

            return $this->parent->getData();
        }

        if (!$this->defaultDataSet) {
            if ($this->lockSetData) {
                throw new RuntimeException('A cycle was detected. Listeners to the PRE_SET_DATA event must not call getData() if the form data has not already been set. You should call getData() on the FormEvent object instead.');
            }

            $this->setData($this->config->getData());
        }

        return $this->modelData;
    }

    public function getNormData(): mixed
    {
        if ($this->inheritData) {
            if (!$this->parent) {
                throw new RuntimeException('The form is configured to inherit its parent\'s data, but does not have a parent.');
            }

            return $this->parent->getNormData();
        }

        if (!$this->defaultDataSet) {
            if ($this->lockSetData) {
                throw new RuntimeException('A cycle was detected. Listeners to the PRE_SET_DATA event must not call getNormData() if the form data has not already been set.');
            }

            $this->setData($this->config->getData());
        }

        return $this->normData;
    }

    public function getViewData(): mixed
    {
        if ($this->inheritData) {
            if (!$this->parent) {
                throw new RuntimeException('The form is configured to inherit its parent\'s data, but does not have a parent.');
            }

            return $this->parent->getViewData();
        }

        if (!$this->defaultDataSet) {
            if ($this->lockSetData) {
                throw new RuntimeException('A cycle was detected. Listeners to the PRE_SET_DATA event must not call getViewData() if the form data has not already been set.');
            }

            $this->setData($this->config->getData());
        }

        return $this->viewData;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function initialize(): static
    {
        if (null !== $this->parent) {
            throw new RuntimeException('Only root forms should be initialized.');
        }

        // Guarantee that the *_SET_DATA events have been triggered once the
        // form is initialized. This makes sure that dynamically added or
        // removed fields are already visible after initialization.
        if (!$this->defaultDataSet) {
            $this->setData($this->config->getData());
        }

        return $this;
    }

    public function handleRequest(mixed $request = null): static
    {
        $this->config->getRequestHandler()->handleRequest($this, $request);

        return $this;
    }

    public function submit(mixed $submittedData, bool $clearMissing = true): static
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('A form can only be submitted once.');
        }

        // Initialize errors in the very beginning so we're sure
        // they are collectable during submission only
        $this->errors = [];

        // Obviously, a disabled form should not change its data upon submission.
        if ($this->isDisabled()) {
            $this->submitted = true;

            return $this;
        }

        // The data must be initialized if it was not initialized yet.
        // This is necessary to guarantee that the *_SET_DATA listeners
        // are always invoked before submit() takes place.
        if (!$this->defaultDataSet) {
            $this->setData($this->config->getData());
        }

        // Treat false as NULL to support binding false to checkboxes.
        // Don't convert NULL to a string here in order to determine later
        // whether an empty value has been submitted or whether no value has
        // been submitted at all. This is important for processing checkboxes
        // and radio buttons with empty values.
        if (false === $submittedData) {
            $submittedData = null;
        } elseif (\is_scalar($submittedData)) {
            $submittedData = (string) $submittedData;
        } elseif ($this->config->getRequestHandler()->isFileUpload($submittedData)) {
            if (!$this->config->getOption('allow_file_upload')) {
                $submittedData = null;
                $this->transformationFailure = new TransformationFailedException('Submitted data was expected to be text or number, file upload given.');
            }
        } elseif (\is_array($submittedData) && !$this->config->getCompound() && !$this->config->getOption('multiple', false)) {
            $submittedData = null;
            $this->transformationFailure = new TransformationFailedException('Submitted data was expected to be text or number, array given.');
        }

        $dispatcher = $this->config->getEventDispatcher();

        $modelData = null;
        $normData = null;
        $viewData = null;

        try {
            if (null !== $this->transformationFailure) {
                throw $this->transformationFailure;
            }

            // Hook to change content of the data submitted by the browser
            if ($dispatcher->hasListeners(FormEvents::PRE_SUBMIT)) {
                $event = new PreSubmitEvent($this, $submittedData);
                $dispatcher->dispatch($event, FormEvents::PRE_SUBMIT);
                $submittedData = $event->getData();
            }

            // Check whether the form is compound.
            // This check is preferable over checking the number of children,
            // since forms without children may also be compound.
            // (think of empty collection forms)
            if ($this->config->getCompound()) {
                if (!\is_array($submittedData ??= [])) {
                    throw new TransformationFailedException('Compound forms expect an array or NULL on submission.');
                }

                foreach ($this->children as $name => $child) {
                    $isSubmitted = \array_key_exists($name, $submittedData);

                    if ($isSubmitted || $clearMissing) {
                        $child->submit($isSubmitted ? $submittedData[$name] : null, $clearMissing);
                        unset($submittedData[$name]);

                        if (null !== $this->clickedButton) {
                            continue;
                        }

                        if ($child instanceof ClickableInterface && $child->isClicked()) {
                            $this->clickedButton = $child;

                            continue;
                        }

                        if (method_exists($child, 'getClickedButton') && null !== $child->getClickedButton()) {
                            $this->clickedButton = $child->getClickedButton();
                        }
                    }
                }

                $this->extraData = $submittedData;
            }

            // Forms that inherit their parents' data also are not processed,
            // because then it would be too difficult to merge the changes in
            // the child and the parent form. Instead, the parent form also takes
            // changes in the grandchildren (i.e. children of the form that inherits
            // its parent's data) into account.
            // (see InheritDataAwareIterator below)
            if (!$this->inheritData) {
                // If the form is compound, the view data is merged with the data
                // of the children using the data mapper.
                // If the form is not compound, the view data is assigned to the submitted data.
                $viewData = $this->config->getCompound() ? $this->viewData : $submittedData;

                if (FormUtil::isEmpty($viewData)) {
                    $emptyData = $this->config->getEmptyData();

                    if ($emptyData instanceof \Closure) {
                        $emptyData = $emptyData($this, $viewData);
                    }

                    $viewData = $emptyData;
                }

                // Merge form data from children into existing view data
                // It is not necessary to invoke this method if the form has no children,
                // even if it is compound.
                if (\count($this->children) > 0) {
                    // Use InheritDataAwareIterator to process children of
                    // descendants that inherit this form's data.
                    // These descendants will not be submitted normally (see the check
                    // for $this->config->getInheritData() above)
                    $this->config->getDataMapper()->mapFormsToData(
                        new \RecursiveIteratorIterator(new InheritDataAwareIterator($this->children)),
                        $viewData
                    );
                }

                // Normalize data to unified representation
                $normData = $this->viewToNorm($viewData);

                // Hook to change content of the data in the normalized
                // representation
                if ($dispatcher->hasListeners(FormEvents::SUBMIT)) {
                    $event = new SubmitEvent($this, $normData);
                    $dispatcher->dispatch($event, FormEvents::SUBMIT);
                    $normData = $event->getData();
                }

                // Synchronize representations - must not change the content!
                $modelData = $this->normToModel($normData);
                $viewData = $this->normToView($normData);
            }
        } catch (TransformationFailedException $e) {
            $this->transformationFailure = $e;

            // If $viewData was not yet set, set it to $submittedData so that
            // the erroneous data is accessible on the form.
            // Forms that inherit data never set any data, because the getters
            // forward to the parent form's getters anyway.
            if (null === $viewData && !$this->inheritData) {
                $viewData = $submittedData;
            }
        }

        $this->submitted = true;
        $this->modelData = $modelData;
        $this->normData = $normData;
        $this->viewData = $viewData;

        if ($dispatcher->hasListeners(FormEvents::POST_SUBMIT)) {
            $event = new PostSubmitEvent($this, $viewData);
            $dispatcher->dispatch($event, FormEvents::POST_SUBMIT);
        }

        return $this;
    }

    public function addError(FormError $error): static
    {
        if (null === $error->getOrigin()) {
            $error->setOrigin($this);
        }

        if ($this->parent && $this->config->getErrorBubbling()) {
            $this->parent->addError($error);
        } else {
            $this->errors[] = $error;
        }

        return $this;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function isSynchronized(): bool
    {
        return null === $this->transformationFailure;
    }

    public function getTransformationFailure(): ?Exception\TransformationFailedException
    {
        return $this->transformationFailure;
    }

    public function isEmpty(): bool
    {
        foreach ($this->children as $child) {
            if (!$child->isEmpty()) {
                return false;
            }
        }

        if (null !== $isEmptyCallback = $this->config->getIsEmptyCallback()) {
            return $isEmptyCallback($this->modelData);
        }

        return FormUtil::isEmpty($this->modelData)
            // arrays, countables
            || (is_countable($this->modelData) && 0 === \count($this->modelData))
            // traversables that are not countable
            || ($this->modelData instanceof \Traversable && 0 === iterator_count($this->modelData));
    }

    public function isValid(): bool
    {
        if (!$this->submitted) {
            throw new LogicException('Cannot check if an unsubmitted form is valid. Call Form::isSubmitted() and ensure that it\'s true before calling Form::isValid().');
        }

        if ($this->isDisabled()) {
            return true;
        }

        return 0 === \count($this->getErrors(true));
    }

    /**
     * Returns the button that was used to submit the form.
     */
    public function getClickedButton(): FormInterface|ClickableInterface|null
    {
        if ($this->clickedButton) {
            return $this->clickedButton;
        }

        return $this->parent && method_exists($this->parent, 'getClickedButton') ? $this->parent->getClickedButton() : null;
    }

    public function getErrors(bool $deep = false, bool $flatten = true): FormErrorIterator
    {
        $errors = $this->errors;

        // Copy the errors of nested forms to the $errors array
        if ($deep) {
            foreach ($this as $child) {
                /** @var FormInterface $child */
                if ($child->isSubmitted() && $child->isValid()) {
                    continue;
                }

                $iterator = $child->getErrors(true, $flatten);

                if (0 === \count($iterator)) {
                    continue;
                }

                if ($flatten) {
                    foreach ($iterator as $error) {
                        $errors[] = $error;
                    }
                } else {
                    $errors[] = $iterator;
                }
            }
        }

        return new FormErrorIterator($this, $errors);
    }

    public function clearErrors(bool $deep = false): static
    {
        $this->errors = [];

        if ($deep) {
            // Clear errors from children
            foreach ($this as $child) {
                if ($child instanceof ClearableErrorsInterface) {
                    $child->clearErrors(true);
                }
            }
        }

        return $this;
    }

    public function all(): array
    {
        return iterator_to_array($this->children);
    }

    public function add(FormInterface|string $child, string $type = null, array $options = []): static
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('You cannot add children to a submitted form.');
        }

        if (!$this->config->getCompound()) {
            throw new LogicException('You cannot add children to a simple form. Maybe you should set the option "compound" to true?');
        }

        if (!$child instanceof FormInterface) {
            if (!\is_string($child) && !\is_int($child)) {
                throw new UnexpectedTypeException($child, 'string or Symfony\Component\Form\FormInterface');
            }

            $child = (string) $child;

            // Never initialize child forms automatically
            $options['auto_initialize'] = false;

            if (null === $type && null === $this->config->getDataClass()) {
                $type = TextType::class;
            }

            if (null === $type) {
                $child = $this->config->getFormFactory()->createForProperty($this->config->getDataClass(), $child, null, $options);
            } else {
                $child = $this->config->getFormFactory()->createNamed($child, $type, null, $options);
            }
        } elseif ($child->getConfig()->getAutoInitialize()) {
            throw new RuntimeException(sprintf('Automatic initialization is only supported on root forms. You should set the "auto_initialize" option to false on the field "%s".', $child->getName()));
        }

        $this->children[$child->getName()] = $child;

        $child->setParent($this);

        // If setData() is currently being called, there is no need to call
        // mapDataToForms() here, as mapDataToForms() is called at the end
        // of setData() anyway. Not doing this check leads to an endless
        // recursion when initializing the form lazily and an event listener
        // (such as ResizeFormListener) adds fields depending on the data:
        //
        //  * setData() is called, the form is not initialized yet
        //  * add() is called by the listener (setData() is not complete, so
        //    the form is still not initialized)
        //  * getViewData() is called
        //  * setData() is called since the form is not initialized yet
        //  * ... endless recursion ...
        //
        // Also skip data mapping if setData() has not been called yet.
        // setData() will be called upon form initialization and data mapping
        // will take place by then.
        if (!$this->lockSetData && $this->defaultDataSet && !$this->inheritData) {
            $viewData = $this->getViewData();
            $this->config->getDataMapper()->mapDataToForms(
                $viewData,
                new \RecursiveIteratorIterator(new InheritDataAwareIterator(new \ArrayIterator([$child->getName() => $child])))
            );
        }

        return $this;
    }

    public function remove(string $name): static
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('You cannot remove children from a submitted form.');
        }

        if (isset($this->children[$name])) {
            if (!$this->children[$name]->isSubmitted()) {
                $this->children[$name]->setParent(null);
            }

            unset($this->children[$name]);
        }

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->children[$name]);
    }

    public function get(string $name): FormInterface
    {
        if (isset($this->children[$name])) {
            return $this->children[$name];
        }

        throw new OutOfBoundsException(sprintf('Child "%s" does not exist.', $name));
    }

    /**
     * Returns whether a child with the given name exists (implements the \ArrayAccess interface).
     *
     * @param string $name The name of the child
     */
    public function offsetExists(mixed $name): bool
    {
        return $this->has($name);
    }

    /**
     * Returns the child with the given name (implements the \ArrayAccess interface).
     *
     * @param string $name The name of the child
     *
     * @throws OutOfBoundsException if the named child does not exist
     */
    public function offsetGet(mixed $name): FormInterface
    {
        return $this->get($name);
    }

    /**
     * Adds a child to the form (implements the \ArrayAccess interface).
     *
     * @param string        $name  Ignored. The name of the child is used
     * @param FormInterface $child The child to be added
     *
     * @throws AlreadySubmittedException if the form has already been submitted
     * @throws LogicException            when trying to add a child to a non-compound form
     *
     * @see self::add()
     */
    public function offsetSet(mixed $name, mixed $child): void
    {
        $this->add($child);
    }

    /**
     * Removes the child with the given name from the form (implements the \ArrayAccess interface).
     *
     * @param string $name The name of the child to remove
     *
     * @throws AlreadySubmittedException if the form has already been submitted
     */
    public function offsetUnset(mixed $name): void
    {
        $this->remove($name);
    }

    /**
     * Returns the iterator for this group.
     *
     * @return \Traversable<string, FormInterface>
     */
    public function getIterator(): \Traversable
    {
        return $this->children;
    }

    /**
     * Returns the number of form children (implements the \Countable interface).
     */
    public function count(): int
    {
        return \count($this->children);
    }

    public function createView(FormView $parent = null): FormView
    {
        if (null === $parent && $this->parent) {
            $parent = $this->parent->createView();
        }

        $type = $this->config->getType();
        $options = $this->config->getOptions();

        // The methods createView(), buildView() and finishView() are called
        // explicitly here in order to be able to override either of them
        // in a custom resolved form type.
        $view = $type->createView($this, $parent);

        $type->buildView($view, $this, $options);

        foreach ($this->children as $name => $child) {
            $view->children[$name] = $child->createView($view);
        }

        $this->sort($view->children);

        $type->finishView($view, $this, $options);

        return $view;
    }

    /**
     * Sorts view fields based on their priority value.
     */
    private function sort(array &$children): void
    {
        $c = [];
        $i = 0;
        $needsSorting = false;
        foreach ($children as $name => $child) {
            $c[$name] = ['p' => $child->vars['priority'] ?? 0, 'i' => $i++];

            if (0 !== $c[$name]['p']) {
                $needsSorting = true;
            }
        }

        if (!$needsSorting) {
            return;
        }

        uksort($children, static fn ($a, $b): int => [$c[$b]['p'], $c[$a]['i']] <=> [$c[$a]['p'], $c[$b]['i']]);
    }

    /**
     * Normalizes the underlying data if a model transformer is set.
     *
     * @throws TransformationFailedException If the underlying data cannot be transformed to "normalized" format
     */
    private function modelToNorm(mixed $value): mixed
    {
        try {
            foreach ($this->config->getModelTransformers() as $transformer) {
                $value = $transformer->transform($value);
            }
        } catch (TransformationFailedException $exception) {
            throw new TransformationFailedException(sprintf('Unable to transform data for property path "%s": ', $this->getPropertyPath()).$exception->getMessage(), $exception->getCode(), $exception, $exception->getInvalidMessage(), $exception->getInvalidMessageParameters());
        }

        return $value;
    }

    /**
     * Reverse transforms a value if a model transformer is set.
     *
     * @throws TransformationFailedException If the value cannot be transformed to "model" format
     */
    private function normToModel(mixed $value): mixed
    {
        try {
            $transformers = $this->config->getModelTransformers();

            for ($i = \count($transformers) - 1; $i >= 0; --$i) {
                $value = $transformers[$i]->reverseTransform($value);
            }
        } catch (TransformationFailedException $exception) {
            throw new TransformationFailedException(sprintf('Unable to reverse value for property path "%s": ', $this->getPropertyPath()).$exception->getMessage(), $exception->getCode(), $exception, $exception->getInvalidMessage(), $exception->getInvalidMessageParameters());
        }

        return $value;
    }

    /**
     * Transforms the value if a view transformer is set.
     *
     * @throws TransformationFailedException If the normalized value cannot be transformed to "view" format
     */
    private function normToView(mixed $value): mixed
    {
        // Scalar values should  be converted to strings to
        // facilitate differentiation between empty ("") and zero (0).
        // Only do this for simple forms, as the resulting value in
        // compound forms is passed to the data mapper and thus should
        // not be converted to a string before.
        if (!($transformers = $this->config->getViewTransformers()) && !$this->config->getCompound()) {
            return null === $value || \is_scalar($value) ? (string) $value : $value;
        }

        try {
            foreach ($transformers as $transformer) {
                $value = $transformer->transform($value);
            }
        } catch (TransformationFailedException $exception) {
            throw new TransformationFailedException(sprintf('Unable to transform value for property path "%s": ', $this->getPropertyPath()).$exception->getMessage(), $exception->getCode(), $exception, $exception->getInvalidMessage(), $exception->getInvalidMessageParameters());
        }

        return $value;
    }

    /**
     * Reverse transforms a value if a view transformer is set.
     *
     * @throws TransformationFailedException If the submitted value cannot be transformed to "normalized" format
     */
    private function viewToNorm(mixed $value): mixed
    {
        if (!$transformers = $this->config->getViewTransformers()) {
            return '' === $value ? null : $value;
        }

        try {
            for ($i = \count($transformers) - 1; $i >= 0; --$i) {
                $value = $transformers[$i]->reverseTransform($value);
            }
        } catch (TransformationFailedException $exception) {
            throw new TransformationFailedException(sprintf('Unable to reverse value for property path "%s": ', $this->getPropertyPath()).$exception->getMessage(), $exception->getCode(), $exception, $exception->getInvalidMessage(), $exception->getInvalidMessageParameters());
        }

        return $value;
    }
}
