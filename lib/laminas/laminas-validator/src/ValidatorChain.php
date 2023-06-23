<?php // phpcs:disable SlevomatCodingStandard.Namespaces.UnusedUses.UnusedUse

namespace Laminas\Validator;

use Countable;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\PriorityQueue;
use ReturnTypeWillChange;

use function array_replace;
use function assert;
use function count;
use function rsort;

use const SORT_NUMERIC;

/**
 * @psalm-type QueueElement = array{instance: ValidatorInterface, breakChainOnFailure: bool}
 */
class ValidatorChain implements
    Countable,
    ValidatorInterface
{
    /**
     * Default priority at which validators are added
     */
    public const DEFAULT_PRIORITY = 1;

    /** @var ValidatorPluginManager<ValidatorInterface>|null */
    protected $plugins;

    /**
     * Validator chain
     *
     * @var PriorityQueue<QueueElement, int>
     */
    protected $validators;

    /**
     * Array of validation failure messages
     *
     * @var array<string, string>
     */
    protected $messages = [];

    /**
     * Initialize validator chain
     */
    public function __construct()
    {
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->validators = new PriorityQueue();
    }

    /**
     * Return the count of attached validators
     *
     * @return int
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return count($this->validators);
    }

    /**
     * Get plugin manager instance
     *
     * @return ValidatorPluginManager<ValidatorInterface>
     */
    public function getPluginManager()
    {
        if (! $this->plugins) {
            $this->setPluginManager(new ValidatorPluginManager(new ServiceManager()));
        }
        return $this->plugins;
    }

    /**
     * Set plugin manager instance
     *
     * @param  ValidatorPluginManager $plugins Plugin manager
     * @psalm-assert ValidatorPluginManager $this->plugins
     * @return $this
     */
    public function setPluginManager(ValidatorPluginManager $plugins)
    {
        $this->plugins = $plugins;
        return $this;
    }

    /**
     * Retrieve a validator by name
     *
     * @param string|class-string<ValidatorInterface> $name    Name of validator to return
     * @param null|array                              $options Options to pass to validator constructor
     *                                                         (if not already instantiated)
     * @return ValidatorInterface
     * @template T of ValidatorInterface
     * @psalm-param string|class-string<T> $name
     * @psalm-return ValidatorInterface
     */
    public function plugin($name, ?array $options = null)
    {
        $plugins = $this->getPluginManager();
        return $plugins->get($name, $options);
    }

    /**
     * Attach a validator to the end of the chain
     * If $breakChainOnFailure is true, then if the validator fails, the next validator in the chain,
     * if one exists, will not be executed.
     *
     * @param bool $breakChainOnFailure
     * @param int  $priority            Priority at which to enqueue validator; defaults to
     *                                  1 (higher executes earlier)
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function attach(
        ValidatorInterface $validator,
        $breakChainOnFailure = false,
        $priority = self::DEFAULT_PRIORITY
    ) {
        /** @psalm-suppress RedundantCastGivenDocblockType */
        $this->validators->insert(
            [
                'instance'            => $validator,
                'breakChainOnFailure' => (bool) $breakChainOnFailure,
            ],
            $priority
        );

        return $this;
    }

    /**
     * Proxy to attach() to keep BC
     *
     * @deprecated Please use attach()
     *
     * @param  bool                 $breakChainOnFailure
     * @param  int                  $priority
     * @return ValidatorChain Provides a fluent interface
     */
    public function addValidator(
        ValidatorInterface $validator,
        $breakChainOnFailure = false,
        $priority = self::DEFAULT_PRIORITY
    ) {
        return $this->attach($validator, $breakChainOnFailure, $priority);
    }

    /**
     * Adds a validator to the beginning of the chain
     *
     * If $breakChainOnFailure is true, then if the validator fails, the next validator in the chain,
     * if one exists, will not be executed.
     *
     * @param  bool                 $breakChainOnFailure
     * @return $this Provides a fluent interface
     */
    public function prependValidator(ValidatorInterface $validator, $breakChainOnFailure = false)
    {
        $priority = self::DEFAULT_PRIORITY;

        if (! $this->validators->isEmpty()) {
            $extractedNodes = $this->validators->toArray(PriorityQueue::EXTR_PRIORITY);
            rsort($extractedNodes, SORT_NUMERIC);
            $priority = $extractedNodes[0] + 1;
        }

        /** @psalm-suppress RedundantCastGivenDocblockType */
        $this->validators->insert(
            [
                'instance'            => $validator,
                'breakChainOnFailure' => (bool) $breakChainOnFailure,
            ],
            $priority
        );
        return $this;
    }

    /**
     * Use the plugin manager to add a validator by name
     *
     * @param  string|class-string<ValidatorInterface> $name
     * @param  array                                   $options
     * @param  bool                                    $breakChainOnFailure
     * @param  int                                     $priority
     * @return $this
     */
    public function attachByName($name, $options = [], $breakChainOnFailure = false, $priority = self::DEFAULT_PRIORITY)
    {
        if (isset($options['break_chain_on_failure'])) {
            $breakChainOnFailure = (bool) $options['break_chain_on_failure'];
        }

        if (isset($options['breakchainonfailure'])) {
            $breakChainOnFailure = (bool) $options['breakchainonfailure'];
        }

        $this->attach($this->plugin($name, $options), $breakChainOnFailure, $priority);

        return $this;
    }

    /**
     * Proxy to attachByName() to keep BC
     *
     * @deprecated Please use attachByName()
     *
     * @param  string $name
     * @param  array  $options
     * @param  bool   $breakChainOnFailure
     * @return ValidatorChain
     */
    public function addByName($name, $options = [], $breakChainOnFailure = false)
    {
        return $this->attachByName($name, $options, $breakChainOnFailure);
    }

    /**
     * Use the plugin manager to prepend a validator by name
     *
     * @param  string|class-string<ValidatorInterface> $name
     * @param  array                                   $options
     * @param  bool                                    $breakChainOnFailure
     * @return $this
     */
    public function prependByName($name, $options = [], $breakChainOnFailure = false)
    {
        $validator = $this->plugin($name, $options);
        $this->prependValidator($validator, $breakChainOnFailure);
        return $this;
    }

    /**
     * Returns true if and only if $value passes all validations in the chain
     *
     * Validators are run in the order in which they were added to the chain (FIFO).
     *
     * @param  mixed $value
     * @param  mixed $context Extra "context" to provide the validator
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $this->messages = [];
        $result         = true;
        foreach ($this->validators as $element) {
            $validator = $element['instance'];
            assert($validator instanceof ValidatorInterface);
            if ($validator->isValid($value, $context)) {
                continue;
            }
            $result         = false;
            $messages       = $validator->getMessages();
            $this->messages = array_replace($this->messages, $messages);
            if ($element['breakChainOnFailure']) {
                break;
            }
        }
        return $result;
    }

    /**
     * Merge the validator chain with the one given in parameter
     *
     * @return $this
     */
    public function merge(ValidatorChain $validatorChain)
    {
        foreach ($validatorChain->validators->toArray(PriorityQueue::EXTR_BOTH) as $item) {
            $this->attach($item['data']['instance'], $item['data']['breakChainOnFailure'], $item['priority']);
        }

        return $this;
    }

    /**
     * Returns array of validation failure messages
     *
     * @return array<string, string>
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get all the validators
     *
     * @return list<QueueElement>
     */
    public function getValidators()
    {
        return $this->validators->toArray(PriorityQueue::EXTR_DATA);
    }

    /**
     * Invoke chain as command
     *
     * @param  mixed $value
     * @return bool
     */
    public function __invoke($value)
    {
        return $this->isValid($value);
    }

    /**
     * Deep clone handling
     */
    public function __clone()
    {
        $this->validators = clone $this->validators;
    }

    /**
     * Prepare validator chain for serialization
     *
     * Plugin manager (property 'plugins') cannot
     * be serialized. On wakeup the property remains unset
     * and next invocation to getPluginManager() sets
     * the default plugin manager instance (ValidatorPluginManager).
     *
     * @return array
     */
    public function __sleep()
    {
        return ['validators', 'messages'];
    }
}
