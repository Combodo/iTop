<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Connection;
use Psr\Log\LogLevel;
use Symfony\Bundle\FullStack;
use Symfony\Component\Asset\Package;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Lock\Lock;
use Symfony\Component\Lock\Store\SemaphoreStore;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\RateLimiter\Policy\TokenBucketLimiter;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\WebLink\HttpHeaderSerializer;
use Symfony\Component\Workflow\WorkflowEvents;

/**
 * FrameworkExtension configuration structure.
 */
class Configuration implements ConfigurationInterface
{
    private $debug;

    /**
     * @param bool $debug Whether debugging is enabled or not
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('framework');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->beforeNormalization()
                ->ifTrue(function ($v) { return !isset($v['assets']) && isset($v['templating']) && class_exists(Package::class); })
                ->then(function ($v) {
                    $v['assets'] = [];

                    return $v;
                })
            ->end()
            ->fixXmlConfig('enabled_locale')
            ->children()
                ->scalarNode('secret')->end()
                ->scalarNode('http_method_override')
                    ->info("Set true to enable support for the '_method' request parameter to determine the intended HTTP method on POST requests. Note: When using the HttpCache, you need to call the method in your front controller instead")
                    ->defaultTrue()
                ->end()
                ->scalarNode('ide')->defaultNull()->end()
                ->booleanNode('test')->end()
                ->scalarNode('default_locale')->defaultValue('en')->end()
                ->booleanNode('set_locale_from_accept_language')
                    ->info('Whether to use the Accept-Language HTTP header to set the Request locale (only when the "_locale" request attribute is not passed).')
                    ->defaultFalse()
                ->end()
                ->booleanNode('set_content_language_from_locale')
                    ->info('Whether to set the Content-Language HTTP header on the Response using the Request locale.')
                    ->defaultFalse()
                ->end()
                ->arrayNode('enabled_locales')
                    ->info('Defines the possible locales for the application. This list is used for generating translations files, but also to restrict which locales are allowed when it is set from Accept-Language header (using "set_locale_from_accept_language").')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('trusted_hosts')
                    ->beforeNormalization()->ifString()->then(function ($v) { return [$v]; })->end()
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('trusted_proxies')->end()
                ->arrayNode('trusted_headers')
                    ->fixXmlConfig('trusted_header')
                    ->performNoDeepMerging()
                    ->defaultValue(['x-forwarded-for', 'x-forwarded-port', 'x-forwarded-proto'])
                    ->beforeNormalization()->ifString()->then(function ($v) { return $v ? array_map('trim', explode(',', $v)) : []; })->end()
                    ->enumPrototype()
                        ->values([
                            'forwarded',
                            'x-forwarded-for', 'x-forwarded-host', 'x-forwarded-proto', 'x-forwarded-port', 'x-forwarded-prefix',
                        ])
                    ->end()
                ->end()
                ->scalarNode('error_controller')
                    ->defaultValue('error_controller')
                ->end()
            ->end()
        ;

        $willBeAvailable = static function (string $package, string $class, string $parentPackage = null) {
            $parentPackages = (array) $parentPackage;
            $parentPackages[] = 'symfony/framework-bundle';

            return ContainerBuilder::willBeAvailable($package, $class, $parentPackages, true);
        };

        $enableIfStandalone = static function (string $package, string $class) use ($willBeAvailable) {
            return !class_exists(FullStack::class) && $willBeAvailable($package, $class) ? 'canBeDisabled' : 'canBeEnabled';
        };

        $this->addCsrfSection($rootNode);
        $this->addFormSection($rootNode, $enableIfStandalone);
        $this->addHttpCacheSection($rootNode);
        $this->addEsiSection($rootNode);
        $this->addSsiSection($rootNode);
        $this->addFragmentsSection($rootNode);
        $this->addProfilerSection($rootNode);
        $this->addWorkflowSection($rootNode);
        $this->addRouterSection($rootNode);
        $this->addSessionSection($rootNode);
        $this->addRequestSection($rootNode);
        $this->addAssetsSection($rootNode, $enableIfStandalone);
        $this->addTranslatorSection($rootNode, $enableIfStandalone);
        $this->addValidationSection($rootNode, $enableIfStandalone, $willBeAvailable);
        $this->addAnnotationsSection($rootNode, $willBeAvailable);
        $this->addSerializerSection($rootNode, $enableIfStandalone, $willBeAvailable);
        $this->addPropertyAccessSection($rootNode, $willBeAvailable);
        $this->addPropertyInfoSection($rootNode, $enableIfStandalone);
        $this->addCacheSection($rootNode, $willBeAvailable);
        $this->addPhpErrorsSection($rootNode);
        $this->addExceptionsSection($rootNode);
        $this->addWebLinkSection($rootNode, $enableIfStandalone);
        $this->addLockSection($rootNode, $enableIfStandalone);
        $this->addMessengerSection($rootNode, $enableIfStandalone);
        $this->addRobotsIndexSection($rootNode);
        $this->addHttpClientSection($rootNode, $enableIfStandalone);
        $this->addMailerSection($rootNode, $enableIfStandalone);
        $this->addSecretsSection($rootNode);
        $this->addNotifierSection($rootNode, $enableIfStandalone);
        $this->addRateLimiterSection($rootNode, $enableIfStandalone);
        $this->addUidSection($rootNode, $enableIfStandalone);

        return $treeBuilder;
    }

    private function addSecretsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('secrets')
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('vault_directory')->defaultValue('%kernel.project_dir%/config/secrets/%kernel.runtime_environment%')->cannotBeEmpty()->end()
                        ->scalarNode('local_dotenv_file')->defaultValue('%kernel.project_dir%/.env.%kernel.environment%.local')->end()
                        ->scalarNode('decryption_env_var')->defaultValue('base64:default::SYMFONY_DECRYPTION_SECRET')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addCsrfSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('csrf_protection')
                    ->treatFalseLike(['enabled' => false])
                    ->treatTrueLike(['enabled' => true])
                    ->treatNullLike(['enabled' => true])
                    ->addDefaultsIfNotSet()
                    ->children()
                        // defaults to framework.session.enabled && !class_exists(FullStack::class) && interface_exists(CsrfTokenManagerInterface::class)
                        ->booleanNode('enabled')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addFormSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('form')
                    ->info('form configuration')
                    ->{$enableIfStandalone('symfony/form', Form::class)}()
                    ->children()
                        ->arrayNode('csrf_protection')
                            ->treatFalseLike(['enabled' => false])
                            ->treatTrueLike(['enabled' => true])
                            ->treatNullLike(['enabled' => true])
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultNull()->end() // defaults to framework.csrf_protection.enabled
                                ->scalarNode('field_name')->defaultValue('_token')->end()
                            ->end()
                        ->end()
                        // to be set to false in Symfony 6.0
                        ->booleanNode('legacy_error_messages')
                            ->defaultTrue()
                            ->validate()
                                ->ifTrue()
                                ->then(function ($v) {
                                    trigger_deprecation('symfony/framework-bundle', '5.2', 'Setting the "framework.form.legacy_error_messages" option to "true" is deprecated. It will have no effect as of Symfony 6.0.');

                                    return $v;
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addHttpCacheSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('http_cache')
                    ->info('HTTP cache configuration')
                    ->canBeEnabled()
                    ->fixXmlConfig('private_header')
                    ->children()
                        ->booleanNode('debug')->defaultValue('%kernel.debug%')->end()
                        ->enumNode('trace_level')
                            ->values(['none', 'short', 'full'])
                        ->end()
                        ->scalarNode('trace_header')->end()
                        ->integerNode('default_ttl')->end()
                        ->arrayNode('private_headers')
                            ->performNoDeepMerging()
                            ->scalarPrototype()->end()
                        ->end()
                        ->booleanNode('allow_reload')->end()
                        ->booleanNode('allow_revalidate')->end()
                        ->integerNode('stale_while_revalidate')->end()
                        ->integerNode('stale_if_error')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addEsiSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('esi')
                    ->info('esi configuration')
                    ->canBeEnabled()
                ->end()
            ->end()
        ;
    }

    private function addSsiSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('ssi')
                    ->info('ssi configuration')
                    ->canBeEnabled()
                ->end()
            ->end();
    }

    private function addFragmentsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('fragments')
                    ->info('fragments configuration')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('hinclude_default_template')->defaultNull()->end()
                        ->scalarNode('path')->defaultValue('/_fragment')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addProfilerSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('profiler')
                    ->info('profiler configuration')
                    ->canBeEnabled()
                    ->children()
                        ->booleanNode('collect')->defaultTrue()->end()
                        ->scalarNode('collect_parameter')->defaultNull()->info('The name of the parameter to use to enable or disable collection on a per request basis')->end()
                        ->booleanNode('only_exceptions')->defaultFalse()->end()
                        ->booleanNode('only_main_requests')->defaultFalse()->end()
                        ->booleanNode('only_master_requests')->setDeprecated('symfony/framework-bundle', '5.3', 'Option "%node%" at "%path%" is deprecated, use "only_main_requests" instead.')->defaultFalse()->end()
                        ->scalarNode('dsn')->defaultValue('file:%kernel.cache_dir%/profiler')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addWorkflowSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->fixXmlConfig('workflow')
            ->children()
                ->arrayNode('workflows')
                    ->canBeEnabled()
                    ->beforeNormalization()
                        ->always(function ($v) {
                            if (\is_array($v) && true === $v['enabled']) {
                                $workflows = $v;
                                unset($workflows['enabled']);

                                if (1 === \count($workflows) && isset($workflows[0]['enabled']) && 1 === \count($workflows[0])) {
                                    $workflows = [];
                                }

                                if (1 === \count($workflows) && isset($workflows['workflows']) && !array_is_list($workflows['workflows']) && !empty(array_diff(array_keys($workflows['workflows']), ['audit_trail', 'type', 'marking_store', 'supports', 'support_strategy', 'initial_marking', 'places', 'transitions']))) {
                                    $workflows = $workflows['workflows'];
                                }

                                foreach ($workflows as $key => $workflow) {
                                    if (isset($workflow['enabled']) && false === $workflow['enabled']) {
                                        throw new LogicException(sprintf('Cannot disable a single workflow. Remove the configuration for the workflow "%s" instead.', $workflow['name']));
                                    }

                                    unset($workflows[$key]['enabled']);
                                }

                                $v = [
                                    'enabled' => true,
                                    'workflows' => $workflows,
                                ];
                            }

                            return $v;
                        })
                    ->end()
                    ->children()
                        ->arrayNode('workflows')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->fixXmlConfig('support')
                                ->fixXmlConfig('place')
                                ->fixXmlConfig('transition')
                                ->fixXmlConfig('event_to_dispatch', 'events_to_dispatch')
                                ->children()
                                    ->arrayNode('audit_trail')
                                        ->canBeEnabled()
                                    ->end()
                                    ->enumNode('type')
                                        ->values(['workflow', 'state_machine'])
                                        ->defaultValue('state_machine')
                                    ->end()
                                    ->arrayNode('marking_store')
                                        ->children()
                                            ->enumNode('type')
                                                ->values(['method'])
                                            ->end()
                                            ->scalarNode('property')
                                                ->defaultValue('marking')
                                            ->end()
                                            ->scalarNode('service')
                                                ->cannotBeEmpty()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->arrayNode('supports')
                                        ->beforeNormalization()
                                            ->ifString()
                                            ->then(function ($v) { return [$v]; })
                                        ->end()
                                        ->prototype('scalar')
                                            ->cannotBeEmpty()
                                            ->validate()
                                                ->ifTrue(function ($v) { return !class_exists($v) && !interface_exists($v, false); })
                                                ->thenInvalid('The supported class or interface "%s" does not exist.')
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->scalarNode('support_strategy')
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->arrayNode('initial_marking')
                                        ->beforeNormalization()->castToArray()->end()
                                        ->defaultValue([])
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->variableNode('events_to_dispatch')
                                        ->defaultValue(null)
                                        ->validate()
                                            ->ifTrue(function ($v) {
                                                if (null === $v) {
                                                    return false;
                                                }
                                                if (!\is_array($v)) {
                                                    return true;
                                                }

                                                foreach ($v as $value) {
                                                    if (!\is_string($value)) {
                                                        return true;
                                                    }
                                                    if (class_exists(WorkflowEvents::class) && !\in_array($value, WorkflowEvents::ALIASES)) {
                                                        return true;
                                                    }
                                                }

                                                return false;
                                            })
                                            ->thenInvalid('The value must be "null" or an array of workflow events (like ["workflow.enter"]).')
                                        ->end()
                                        ->info('Select which Transition events should be dispatched for this Workflow')
                                        ->example(['workflow.enter', 'workflow.transition'])
                                    ->end()
                                    ->arrayNode('places')
                                        ->beforeNormalization()
                                            ->always()
                                            ->then(function ($places) {
                                                // It's an indexed array of shape  ['place1', 'place2']
                                                if (isset($places[0]) && \is_string($places[0])) {
                                                    return array_map(function (string $place) {
                                                        return ['name' => $place];
                                                    }, $places);
                                                }

                                                // It's an indexed array, we let the validation occur
                                                if (isset($places[0]) && \is_array($places[0])) {
                                                    return $places;
                                                }

                                                foreach ($places as $name => $place) {
                                                    if (\is_array($place) && \array_key_exists('name', $place)) {
                                                        continue;
                                                    }
                                                    $place['name'] = $name;
                                                    $places[$name] = $place;
                                                }

                                                return array_values($places);
                                            })
                                        ->end()
                                        ->isRequired()
                                        ->requiresAtLeastOneElement()
                                        ->prototype('array')
                                            ->children()
                                                ->scalarNode('name')
                                                    ->isRequired()
                                                    ->cannotBeEmpty()
                                                ->end()
                                                ->arrayNode('metadata')
                                                    ->normalizeKeys(false)
                                                    ->defaultValue([])
                                                    ->example(['color' => 'blue', 'description' => 'Workflow to manage article.'])
                                                    ->prototype('variable')
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->arrayNode('transitions')
                                        ->beforeNormalization()
                                            ->always()
                                            ->then(function ($transitions) {
                                                // It's an indexed array, we let the validation occur
                                                if (isset($transitions[0]) && \is_array($transitions[0])) {
                                                    return $transitions;
                                                }

                                                foreach ($transitions as $name => $transition) {
                                                    if (\is_array($transition) && \array_key_exists('name', $transition)) {
                                                        continue;
                                                    }
                                                    $transition['name'] = $name;
                                                    $transitions[$name] = $transition;
                                                }

                                                return $transitions;
                                            })
                                        ->end()
                                        ->isRequired()
                                        ->requiresAtLeastOneElement()
                                        ->prototype('array')
                                            ->children()
                                                ->scalarNode('name')
                                                    ->isRequired()
                                                    ->cannotBeEmpty()
                                                ->end()
                                                ->scalarNode('guard')
                                                    ->cannotBeEmpty()
                                                    ->info('An expression to block the transition')
                                                    ->example('is_fully_authenticated() and is_granted(\'ROLE_JOURNALIST\') and subject.getTitle() == \'My first article\'')
                                                ->end()
                                                ->arrayNode('from')
                                                    ->beforeNormalization()
                                                        ->ifString()
                                                        ->then(function ($v) { return [$v]; })
                                                    ->end()
                                                    ->requiresAtLeastOneElement()
                                                    ->prototype('scalar')
                                                        ->cannotBeEmpty()
                                                    ->end()
                                                ->end()
                                                ->arrayNode('to')
                                                    ->beforeNormalization()
                                                        ->ifString()
                                                        ->then(function ($v) { return [$v]; })
                                                    ->end()
                                                    ->requiresAtLeastOneElement()
                                                    ->prototype('scalar')
                                                        ->cannotBeEmpty()
                                                    ->end()
                                                ->end()
                                                ->arrayNode('metadata')
                                                    ->normalizeKeys(false)
                                                    ->defaultValue([])
                                                    ->example(['color' => 'blue', 'description' => 'Workflow to manage article.'])
                                                    ->prototype('variable')
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->arrayNode('metadata')
                                        ->normalizeKeys(false)
                                        ->defaultValue([])
                                        ->example(['color' => 'blue', 'description' => 'Workflow to manage article.'])
                                        ->prototype('variable')
                                        ->end()
                                    ->end()
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) {
                                        return $v['supports'] && isset($v['support_strategy']);
                                    })
                                    ->thenInvalid('"supports" and "support_strategy" cannot be used together.')
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) {
                                        return !$v['supports'] && !isset($v['support_strategy']);
                                    })
                                    ->thenInvalid('"supports" or "support_strategy" should be configured.')
                                ->end()
                                ->beforeNormalization()
                                        ->always()
                                        ->then(function ($values) {
                                            // Special case to deal with XML when the user wants an empty array
                                            if (\array_key_exists('event_to_dispatch', $values) && null === $values['event_to_dispatch']) {
                                                $values['events_to_dispatch'] = [];
                                                unset($values['event_to_dispatch']);
                                            }

                                            return $values;
                                        })
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addRouterSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('router')
                    ->info('router configuration')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('resource')->isRequired()->end()
                        ->scalarNode('type')->end()
                        ->scalarNode('default_uri')
                            ->info('The default URI used to generate URLs in a non-HTTP context')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('http_port')->defaultValue(80)->end()
                        ->scalarNode('https_port')->defaultValue(443)->end()
                        ->scalarNode('strict_requirements')
                            ->info(
                                "set to true to throw an exception when a parameter does not match the requirements\n".
                                "set to false to disable exceptions when a parameter does not match the requirements (and return null instead)\n".
                                "set to null to disable parameter checks against requirements\n".
                                "'true' is the preferred configuration in development mode, while 'false' or 'null' might be preferred in production"
                            )
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('utf8')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSessionSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('session')
                    ->info('session configuration')
                    ->canBeEnabled()
                    ->beforeNormalization()
                        ->ifTrue(function ($v) {
                            return \is_array($v) && isset($v['storage_id']) && isset($v['storage_factory_id']);
                        })
                        ->thenInvalid('You cannot use both "storage_id" and "storage_factory_id" at the same time under "framework.session"')
                    ->end()
                    ->children()
                        ->scalarNode('storage_id')->defaultValue('session.storage.native')->end()
                        ->scalarNode('storage_factory_id')->defaultNull()->end()
                        ->scalarNode('handler_id')->defaultValue('session.handler.native_file')->end()
                        ->scalarNode('name')
                            ->validate()
                                ->ifTrue(function ($v) {
                                    parse_str($v, $parsed);

                                    return implode('&', array_keys($parsed)) !== (string) $v;
                                })
                                ->thenInvalid('Session name %s contains illegal character(s)')
                            ->end()
                        ->end()
                        ->scalarNode('cookie_lifetime')->end()
                        ->scalarNode('cookie_path')->end()
                        ->scalarNode('cookie_domain')->end()
                        ->enumNode('cookie_secure')->values([true, false, 'auto'])->end()
                        ->booleanNode('cookie_httponly')->defaultTrue()->end()
                        ->enumNode('cookie_samesite')->values([null, Cookie::SAMESITE_LAX, Cookie::SAMESITE_STRICT, Cookie::SAMESITE_NONE])->defaultNull()->end()
                        ->booleanNode('use_cookies')->end()
                        ->scalarNode('gc_divisor')->end()
                        ->scalarNode('gc_probability')->defaultValue(1)->end()
                        ->scalarNode('gc_maxlifetime')->end()
                        ->scalarNode('save_path')->defaultValue('%kernel.cache_dir%/sessions')->end()
                        ->integerNode('metadata_update_threshold')
                            ->defaultValue(0)
                            ->info('seconds to wait between 2 session metadata updates')
                        ->end()
                        ->integerNode('sid_length')
                            ->min(22)
                            ->max(256)
                        ->end()
                        ->integerNode('sid_bits_per_character')
                            ->min(4)
                            ->max(6)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addRequestSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('request')
                    ->info('request configuration')
                    ->canBeEnabled()
                    ->fixXmlConfig('format')
                    ->children()
                        ->arrayNode('formats')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifTrue(function ($v) { return \is_array($v) && isset($v['mime_type']); })
                                    ->then(function ($v) { return $v['mime_type']; })
                                ->end()
                                ->beforeNormalization()->castToArray()->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addAssetsSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('assets')
                    ->info('assets configuration')
                    ->{$enableIfStandalone('symfony/asset', Package::class)}()
                    ->fixXmlConfig('base_url')
                    ->children()
                        ->booleanNode('strict_mode')
                            ->info('Throw an exception if an entry is missing from the manifest.json')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('version_strategy')->defaultNull()->end()
                        ->scalarNode('version')->defaultNull()->end()
                        ->scalarNode('version_format')->defaultValue('%%s?%%s')->end()
                        ->scalarNode('json_manifest_path')->defaultNull()->end()
                        ->scalarNode('base_path')->defaultValue('')->end()
                        ->arrayNode('base_urls')
                            ->requiresAtLeastOneElement()
                            ->beforeNormalization()->castToArray()->end()
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return isset($v['version_strategy']) && isset($v['version']);
                        })
                        ->thenInvalid('You cannot use both "version_strategy" and "version" at the same time under "assets".')
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return isset($v['version_strategy']) && isset($v['json_manifest_path']);
                        })
                        ->thenInvalid('You cannot use both "version_strategy" and "json_manifest_path" at the same time under "assets".')
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return isset($v['version']) && isset($v['json_manifest_path']);
                        })
                        ->thenInvalid('You cannot use both "version" and "json_manifest_path" at the same time under "assets".')
                    ->end()
                    ->fixXmlConfig('package')
                    ->children()
                        ->arrayNode('packages')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->fixXmlConfig('base_url')
                                ->children()
                                    ->booleanNode('strict_mode')
                                        ->info('Throw an exception if an entry is missing from the manifest.json')
                                        ->defaultFalse()
                                    ->end()
                                    ->scalarNode('version_strategy')->defaultNull()->end()
                                    ->scalarNode('version')
                                        ->beforeNormalization()
                                        ->ifTrue(function ($v) { return '' === $v; })
                                        ->then(function ($v) { return; })
                                        ->end()
                                    ->end()
                                    ->scalarNode('version_format')->defaultNull()->end()
                                    ->scalarNode('json_manifest_path')->defaultNull()->end()
                                    ->scalarNode('base_path')->defaultValue('')->end()
                                    ->arrayNode('base_urls')
                                        ->requiresAtLeastOneElement()
                                        ->beforeNormalization()->castToArray()->end()
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) {
                                        return isset($v['version_strategy']) && isset($v['version']);
                                    })
                                    ->thenInvalid('You cannot use both "version_strategy" and "version" at the same time under "assets" packages.')
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) {
                                        return isset($v['version_strategy']) && isset($v['json_manifest_path']);
                                    })
                                    ->thenInvalid('You cannot use both "version_strategy" and "json_manifest_path" at the same time under "assets" packages.')
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) {
                                        return isset($v['version']) && isset($v['json_manifest_path']);
                                    })
                                    ->thenInvalid('You cannot use both "version" and "json_manifest_path" at the same time under "assets" packages.')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addTranslatorSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('translator')
                    ->info('translator configuration')
                    ->{$enableIfStandalone('symfony/translation', Translator::class)}()
                    ->fixXmlConfig('fallback')
                    ->fixXmlConfig('path')
                    ->fixXmlConfig('enabled_locale')
                    ->fixXmlConfig('provider')
                    ->children()
                        ->arrayNode('fallbacks')
                            ->info('Defaults to the value of "default_locale".')
                            ->beforeNormalization()->ifString()->then(function ($v) { return [$v]; })->end()
                            ->prototype('scalar')->end()
                            ->defaultValue([])
                        ->end()
                        ->booleanNode('logging')->defaultValue(false)->end()
                        ->scalarNode('formatter')->defaultValue('translator.formatter.default')->end()
                        ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/translations')->end()
                        ->scalarNode('default_path')
                            ->info('The default path used to load translations')
                            ->defaultValue('%kernel.project_dir%/translations')
                        ->end()
                        ->arrayNode('paths')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('enabled_locales')
                            ->setDeprecated('symfony/framework-bundle', '5.3', 'Option "%node%" at "%path%" is deprecated, set the "framework.enabled_locales" option instead.')
                            ->prototype('scalar')->end()
                            ->defaultValue([])
                        ->end()
                        ->arrayNode('pseudo_localization')
                            ->canBeEnabled()
                            ->fixXmlConfig('localizable_html_attribute')
                            ->children()
                                ->booleanNode('accents')->defaultTrue()->end()
                                ->floatNode('expansion_factor')
                                    ->min(1.0)
                                    ->defaultValue(1.0)
                                ->end()
                                ->booleanNode('brackets')->defaultTrue()->end()
                                ->booleanNode('parse_html')->defaultFalse()->end()
                                ->arrayNode('localizable_html_attributes')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('providers')
                            ->info('Translation providers you can read/write your translations from')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->fixXmlConfig('domain')
                                ->fixXmlConfig('locale')
                                ->children()
                                    ->scalarNode('dsn')->end()
                                    ->arrayNode('domains')
                                        ->prototype('scalar')->end()
                                        ->defaultValue([])
                                    ->end()
                                    ->arrayNode('locales')
                                        ->prototype('scalar')->end()
                                        ->defaultValue([])
                                        ->info('If not set, all locales listed under framework.enabled_locales are used.')
                                    ->end()
                                ->end()
                            ->end()
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addValidationSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone, callable $willBeAvailable)
    {
        $rootNode
            ->children()
                ->arrayNode('validation')
                    ->info('validation configuration')
                    ->{$enableIfStandalone('symfony/validator', Validation::class)}()
                    ->children()
                        ->scalarNode('cache')->end()
                        ->booleanNode('enable_annotations')->{!class_exists(FullStack::class) && (\PHP_VERSION_ID >= 80000 || $willBeAvailable('doctrine/annotations', Annotation::class, 'symfony/validator')) ? 'defaultTrue' : 'defaultFalse'}()->end()
                        ->arrayNode('static_method')
                            ->defaultValue(['loadValidatorMetadata'])
                            ->prototype('scalar')->end()
                            ->treatFalseLike([])
                            ->validate()->castToArray()->end()
                        ->end()
                        ->scalarNode('translation_domain')->defaultValue('validators')->end()
                        ->enumNode('email_validation_mode')->values(['html5', 'loose', 'strict'])->end()
                        ->arrayNode('mapping')
                            ->addDefaultsIfNotSet()
                            ->fixXmlConfig('path')
                            ->children()
                                ->arrayNode('paths')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('not_compromised_password')
                            ->canBeDisabled()
                            ->children()
                                ->booleanNode('enabled')
                                    ->defaultTrue()
                                    ->info('When disabled, compromised passwords will be accepted as valid.')
                                ->end()
                                ->scalarNode('endpoint')
                                    ->defaultNull()
                                    ->info('API endpoint for the NotCompromisedPassword Validator.')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('auto_mapping')
                            ->info('A collection of namespaces for which auto-mapping will be enabled by default, or null to opt-in with the EnableAutoMapping constraint.')
                            ->example([
                                'App\\Entity\\' => [],
                                'App\\WithSpecificLoaders\\' => ['validator.property_info_loader'],
                            ])
                            ->useAttributeAsKey('namespace')
                            ->normalizeKeys(false)
                            ->beforeNormalization()
                                ->ifArray()
                                ->then(function (array $values): array {
                                    foreach ($values as $k => $v) {
                                        if (isset($v['service'])) {
                                            continue;
                                        }

                                        if (isset($v['namespace'])) {
                                            $values[$k]['services'] = [];
                                            continue;
                                        }

                                        if (!\is_array($v)) {
                                            $values[$v]['services'] = [];
                                            unset($values[$k]);
                                            continue;
                                        }

                                        $tmp = $v;
                                        unset($values[$k]);
                                        $values[$k]['services'] = $tmp;
                                    }

                                    return $values;
                                })
                            ->end()
                            ->arrayPrototype()
                                ->fixXmlConfig('service')
                                ->children()
                                    ->arrayNode('services')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addAnnotationsSection(ArrayNodeDefinition $rootNode, callable $willBeAvailable)
    {
        $doctrineCache = $willBeAvailable('doctrine/cache', Cache::class, 'doctrine/annotations');
        $psr6Cache = $willBeAvailable('symfony/cache', PsrCachedReader::class, 'doctrine/annotations');

        $rootNode
            ->children()
                ->arrayNode('annotations')
                    ->info('annotation configuration')
                    ->{$willBeAvailable('doctrine/annotations', Annotation::class) ? 'canBeDisabled' : 'canBeEnabled'}()
                    ->children()
                        ->scalarNode('cache')->defaultValue(($doctrineCache || $psr6Cache) ? 'php_array' : 'none')->end()
                        ->scalarNode('file_cache_dir')->defaultValue('%kernel.cache_dir%/annotations')->end()
                        ->booleanNode('debug')->defaultValue($this->debug)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSerializerSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone, callable $willBeAvailable)
    {
        $rootNode
            ->children()
                ->arrayNode('serializer')
                    ->info('serializer configuration')
                    ->{$enableIfStandalone('symfony/serializer', Serializer::class)}()
                    ->children()
                        ->booleanNode('enable_annotations')->{!class_exists(FullStack::class) && (\PHP_VERSION_ID >= 80000 || $willBeAvailable('doctrine/annotations', Annotation::class, 'symfony/serializer')) ? 'defaultTrue' : 'defaultFalse'}()->end()
                        ->scalarNode('name_converter')->end()
                        ->scalarNode('circular_reference_handler')->end()
                        ->scalarNode('max_depth_handler')->end()
                        ->arrayNode('mapping')
                            ->addDefaultsIfNotSet()
                            ->fixXmlConfig('path')
                            ->children()
                                ->arrayNode('paths')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('default_context')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->defaultValue([])
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addPropertyAccessSection(ArrayNodeDefinition $rootNode, callable $willBeAvailable)
    {
        $rootNode
            ->children()
                ->arrayNode('property_access')
                    ->addDefaultsIfNotSet()
                    ->info('Property access configuration')
                    ->{$willBeAvailable('symfony/property-access', PropertyAccessor::class) ? 'canBeDisabled' : 'canBeEnabled'}()
                    ->children()
                        ->booleanNode('magic_call')->defaultFalse()->end()
                        ->booleanNode('magic_get')->defaultTrue()->end()
                        ->booleanNode('magic_set')->defaultTrue()->end()
                        ->booleanNode('throw_exception_on_invalid_index')->defaultFalse()->end()
                        ->booleanNode('throw_exception_on_invalid_property_path')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addPropertyInfoSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('property_info')
                    ->info('Property info configuration')
                    ->{$enableIfStandalone('symfony/property-info', PropertyInfoExtractorInterface::class)}()
                ->end()
            ->end()
        ;
    }

    private function addCacheSection(ArrayNodeDefinition $rootNode, callable $willBeAvailable)
    {
        $rootNode
            ->children()
                ->arrayNode('cache')
                    ->info('Cache configuration')
                    ->addDefaultsIfNotSet()
                    ->fixXmlConfig('pool')
                    ->children()
                        ->scalarNode('prefix_seed')
                            ->info('Used to namespace cache keys when using several apps with the same shared backend')
                            ->defaultValue('_%kernel.project_dir%.%kernel.container_class%')
                            ->example('my-application-name/%kernel.environment%')
                        ->end()
                        ->scalarNode('app')
                            ->info('App related cache pools configuration')
                            ->defaultValue('cache.adapter.filesystem')
                        ->end()
                        ->scalarNode('system')
                            ->info('System related cache pools configuration')
                            ->defaultValue('cache.adapter.system')
                        ->end()
                        ->scalarNode('directory')->defaultValue('%kernel.cache_dir%/pools/app')->end()
                        ->scalarNode('default_doctrine_provider')->end()
                        ->scalarNode('default_psr6_provider')->end()
                        ->scalarNode('default_redis_provider')->defaultValue('redis://localhost')->end()
                        ->scalarNode('default_memcached_provider')->defaultValue('memcached://localhost')->end()
                        ->scalarNode('default_doctrine_dbal_provider')->defaultValue('database_connection')->end()
                        ->scalarNode('default_pdo_provider')->defaultValue($willBeAvailable('doctrine/dbal', Connection::class) && class_exists(DoctrineAdapter::class) ? 'database_connection' : null)->end()
                        ->arrayNode('pools')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->fixXmlConfig('adapter')
                                ->beforeNormalization()
                                    ->ifTrue(function ($v) { return isset($v['provider']) && \is_array($v['adapters'] ?? $v['adapter'] ?? null) && 1 < \count($v['adapters'] ?? $v['adapter']); })
                                    ->thenInvalid('Pool cannot have a "provider" while more than one adapter is defined')
                                ->end()
                                ->children()
                                    ->arrayNode('adapters')
                                        ->performNoDeepMerging()
                                        ->info('One or more adapters to chain for creating the pool, defaults to "cache.app".')
                                        ->beforeNormalization()->castToArray()->end()
                                        ->beforeNormalization()
                                            ->always()->then(function ($values) {
                                                if ([0] === array_keys($values) && \is_array($values[0])) {
                                                    return $values[0];
                                                }
                                                $adapters = [];

                                                foreach ($values as $k => $v) {
                                                    if (\is_int($k) && \is_string($v)) {
                                                        $adapters[] = $v;
                                                    } elseif (!\is_array($v)) {
                                                        $adapters[$k] = $v;
                                                    } elseif (isset($v['provider'])) {
                                                        $adapters[$v['provider']] = $v['name'] ?? $v;
                                                    } else {
                                                        $adapters[] = $v['name'] ?? $v;
                                                    }
                                                }

                                                return $adapters;
                                            })
                                        ->end()
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->scalarNode('tags')->defaultNull()->end()
                                    ->booleanNode('public')->defaultFalse()->end()
                                    ->scalarNode('default_lifetime')
                                        ->info('Default lifetime of the pool')
                                        ->example('"600" for 5 minutes expressed in seconds, "PT5M" for five minutes expressed as ISO 8601 time interval, or "5 minutes" as a date expression')
                                    ->end()
                                    ->scalarNode('provider')
                                        ->info('Overwrite the setting from the default provider for this adapter.')
                                    ->end()
                                    ->scalarNode('early_expiration_message_bus')
                                        ->example('"messenger.default_bus" to send early expiration events to the default Messenger bus.')
                                    ->end()
                                    ->scalarNode('clearer')->end()
                                ->end()
                            ->end()
                            ->validate()
                                ->ifTrue(function ($v) { return isset($v['cache.app']) || isset($v['cache.system']); })
                                ->thenInvalid('"cache.app" and "cache.system" are reserved names')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addPhpErrorsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('php_errors')
                    ->info('PHP errors handling configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('log')
                            ->info('Use the application logger instead of the PHP logger for logging PHP errors.')
                            ->example('"true" to use the default configuration: log all errors. "false" to disable. An integer bit field of E_* constants, or an array mapping E_* constants to log levels.')
                            ->defaultValue($this->debug)
                            ->treatNullLike($this->debug)
                            ->beforeNormalization()
                                ->ifArray()
                                ->then(function (array $v): array {
                                    if (!($v[0]['type'] ?? false)) {
                                        return $v;
                                    }

                                    // Fix XML normalization

                                    $ret = [];
                                    foreach ($v as ['type' => $type, 'logLevel' => $logLevel]) {
                                        $ret[$type] = $logLevel;
                                    }

                                    return $ret;
                                })
                            ->end()
                            ->validate()
                                ->ifTrue(function ($v) { return !(\is_int($v) || \is_bool($v) || \is_array($v)); })
                                ->thenInvalid('The "php_errors.log" parameter should be either an integer, a boolean, or an array')
                            ->end()
                        ->end()
                        ->booleanNode('throw')
                            ->info('Throw PHP errors as \ErrorException instances.')
                            ->defaultValue($this->debug)
                            ->treatNullLike($this->debug)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addExceptionsSection(ArrayNodeDefinition $rootNode)
    {
        $logLevels = (new \ReflectionClass(LogLevel::class))->getConstants();

        $rootNode
            ->children()
                ->arrayNode('exceptions')
                    ->info('Exception handling configuration')
                    ->beforeNormalization()
                        ->ifArray()
                        ->then(function (array $v): array {
                            if (!\array_key_exists('exception', $v)) {
                                return $v;
                            }

                            // Fix XML normalization
                            $data = isset($v['exception'][0]) ? $v['exception'] : [$v['exception']];
                            $exceptions = [];
                            foreach ($data as $exception) {
                                $config = [];
                                if (\array_key_exists('log-level', $exception)) {
                                    $config['log_level'] = $exception['log-level'];
                                }
                                if (\array_key_exists('status-code', $exception)) {
                                    $config['status_code'] = $exception['status-code'];
                                }
                                $exceptions[$exception['name']] = $config;
                            }

                            return $exceptions;
                        })
                    ->end()
                    ->prototype('array')
                        ->fixXmlConfig('exception')
                        ->children()
                            ->scalarNode('log_level')
                                ->info('The level of log message. Null to let Symfony decide.')
                                ->validate()
                                    ->ifTrue(function ($v) use ($logLevels) { return !\in_array($v, $logLevels); })
                                    ->thenInvalid(sprintf('The log level is not valid. Pick one among "%s".', implode('", "', $logLevels)))
                                ->end()
                                ->defaultNull()
                            ->end()
                            ->scalarNode('status_code')
                                ->info('The status code of the response. Null to let Symfony decide.')
                                ->validate()
                                    ->ifTrue(function ($v) { return $v < 100 || $v > 599; })
                                    ->thenInvalid('The status code is not valid. Pick a value between 100 and 599.')
                                ->end()
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addLockSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('lock')
                    ->info('Lock configuration')
                    ->{$enableIfStandalone('symfony/lock', Lock::class)}()
                    ->beforeNormalization()
                        ->ifString()->then(function ($v) { return ['enabled' => true, 'resources' => $v]; })
                    ->end()
                    ->beforeNormalization()
                        ->ifTrue(function ($v) { return \is_array($v) && !isset($v['enabled']); })
                        ->then(function ($v) { return $v + ['enabled' => true]; })
                    ->end()
                    ->beforeNormalization()
                        ->ifTrue(function ($v) { return \is_array($v) && !isset($v['resources']) && !isset($v['resource']); })
                        ->then(function ($v) {
                            $e = $v['enabled'];
                            unset($v['enabled']);

                            return ['enabled' => $e, 'resources' => $v];
                        })
                    ->end()
                    ->addDefaultsIfNotSet()
                    ->fixXmlConfig('resource')
                    ->children()
                        ->arrayNode('resources')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->requiresAtLeastOneElement()
                            ->defaultValue(['default' => [class_exists(SemaphoreStore::class) && SemaphoreStore::isSupported() ? 'semaphore' : 'flock']])
                            ->beforeNormalization()
                                ->ifString()->then(function ($v) { return ['default' => $v]; })
                            ->end()
                            ->beforeNormalization()
                                ->ifTrue(function ($v) { return \is_array($v) && array_is_list($v); })
                                ->then(function ($v) {
                                    $resources = [];
                                    foreach ($v as $resource) {
                                        $resources[] = \is_array($resource) && isset($resource['name'])
                                            ? [$resource['name'] => $resource['value']]
                                            : ['default' => $resource]
                                        ;
                                    }

                                    return array_merge_recursive([], ...$resources);
                                })
                            ->end()
                            ->prototype('array')
                                ->performNoDeepMerging()
                                ->beforeNormalization()->ifString()->then(function ($v) { return [$v]; })->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addWebLinkSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('web_link')
                    ->info('web links configuration')
                    ->{$enableIfStandalone('symfony/weblink', HttpHeaderSerializer::class)}()
                ->end()
            ->end()
        ;
    }

    private function addMessengerSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('messenger')
                    ->info('Messenger configuration')
                    ->{$enableIfStandalone('symfony/messenger', MessageBusInterface::class)}()
                    ->fixXmlConfig('transport')
                    ->fixXmlConfig('bus', 'buses')
                    ->validate()
                        ->ifTrue(function ($v) { return isset($v['buses']) && \count($v['buses']) > 1 && null === $v['default_bus']; })
                        ->thenInvalid('You must specify the "default_bus" if you define more than one bus.')
                    ->end()
                    ->validate()
                        ->ifTrue(static function ($v): bool { return isset($v['buses']) && null !== $v['default_bus'] && !isset($v['buses'][$v['default_bus']]); })
                        ->then(static function (array $v): void { throw new InvalidConfigurationException(sprintf('The specified default bus "%s" is not configured. Available buses are "%s".', $v['default_bus'], implode('", "', array_keys($v['buses'])))); })
                    ->end()
                    ->children()
                        ->arrayNode('routing')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('message_class')
                            ->beforeNormalization()
                                ->always()
                                ->then(function ($config) {
                                    if (!\is_array($config)) {
                                        return [];
                                    }
                                    // If XML config with only one routing attribute
                                    if (2 === \count($config) && isset($config['message-class']) && isset($config['sender'])) {
                                        $config = [0 => $config];
                                    }

                                    $newConfig = [];
                                    foreach ($config as $k => $v) {
                                        if (!\is_int($k)) {
                                            $newConfig[$k] = [
                                                'senders' => $v['senders'] ?? (\is_array($v) ? array_values($v) : [$v]),
                                            ];
                                        } else {
                                            $newConfig[$v['message-class']]['senders'] = array_map(
                                                function ($a) {
                                                    return \is_string($a) ? $a : $a['service'];
                                                },
                                                array_values($v['sender'])
                                            );
                                        }
                                    }

                                    return $newConfig;
                                })
                            ->end()
                            ->prototype('array')
                                ->performNoDeepMerging()
                                ->children()
                                    ->arrayNode('senders')
                                        ->requiresAtLeastOneElement()
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('serializer')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('default_serializer')
                                    ->defaultValue('messenger.transport.native_php_serializer')
                                    ->info('Service id to use as the default serializer for the transports.')
                                ->end()
                                ->arrayNode('symfony_serializer')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('format')->defaultValue('json')->info('Serialization format for the messenger.transport.symfony_serializer service (which is not the serializer used by default).')->end()
                                        ->arrayNode('context')
                                            ->normalizeKeys(false)
                                            ->useAttributeAsKey('name')
                                            ->defaultValue([])
                                            ->info('Context array for the messenger.transport.symfony_serializer service (which is not the serializer used by default).')
                                            ->prototype('variable')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('transports')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function (string $dsn) {
                                        return ['dsn' => $dsn];
                                    })
                                ->end()
                                ->fixXmlConfig('option')
                                ->children()
                                    ->scalarNode('dsn')->end()
                                    ->scalarNode('serializer')->defaultNull()->info('Service id of a custom serializer to use.')->end()
                                    ->arrayNode('options')
                                        ->normalizeKeys(false)
                                        ->defaultValue([])
                                        ->prototype('variable')
                                        ->end()
                                    ->end()
                                    ->scalarNode('failure_transport')
                                        ->defaultNull()
                                        ->info('Transport name to send failed messages to (after all retries have failed).')
                                    ->end()
                                    ->arrayNode('retry_strategy')
                                        ->addDefaultsIfNotSet()
                                        ->beforeNormalization()
                                            ->always(function ($v) {
                                                if (isset($v['service']) && (isset($v['max_retries']) || isset($v['delay']) || isset($v['multiplier']) || isset($v['max_delay']))) {
                                                    throw new \InvalidArgumentException('The "service" cannot be used along with the other "retry_strategy" options.');
                                                }

                                                return $v;
                                            })
                                        ->end()
                                        ->children()
                                            ->scalarNode('service')->defaultNull()->info('Service id to override the retry strategy entirely')->end()
                                            ->integerNode('max_retries')->defaultValue(3)->min(0)->end()
                                            ->integerNode('delay')->defaultValue(1000)->min(0)->info('Time in ms to delay (or the initial value when multiplier is used)')->end()
                                            ->floatNode('multiplier')->defaultValue(2)->min(1)->info('If greater than 1, delay will grow exponentially for each retry: this delay = (delay * (multiple ^ retries))')->end()
                                            ->integerNode('max_delay')->defaultValue(0)->min(0)->info('Max time in ms that a retry should ever be delayed (0 = infinite)')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('failure_transport')
                            ->defaultNull()
                            ->info('Transport name to send failed messages to (after all retries have failed).')
                        ->end()
                        ->booleanNode('reset_on_message')
                            ->defaultNull()
                            ->info('Reset container services after each message.')
                        ->end()
                        ->scalarNode('default_bus')->defaultNull()->end()
                        ->arrayNode('buses')
                            ->defaultValue(['messenger.bus.default' => ['default_middleware' => true, 'middleware' => []]])
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->enumNode('default_middleware')
                                        ->values([true, false, 'allow_no_handlers'])
                                        ->defaultTrue()
                                    ->end()
                                    ->arrayNode('middleware')
                                        ->performNoDeepMerging()
                                        ->beforeNormalization()
                                            ->ifTrue(function ($v) { return \is_string($v) || (\is_array($v) && !\is_int(key($v))); })
                                            ->then(function ($v) { return [$v]; })
                                        ->end()
                                        ->defaultValue([])
                                        ->arrayPrototype()
                                            ->beforeNormalization()
                                                ->always()
                                                ->then(function ($middleware): array {
                                                    if (!\is_array($middleware)) {
                                                        return ['id' => $middleware];
                                                    }
                                                    if (isset($middleware['id'])) {
                                                        return $middleware;
                                                    }
                                                    if (1 < \count($middleware)) {
                                                        throw new \InvalidArgumentException('Invalid middleware at path "framework.messenger": a map with a single factory id as key and its arguments as value was expected, '.json_encode($middleware).' given.');
                                                    }

                                                    return [
                                                        'id' => key($middleware),
                                                        'arguments' => current($middleware),
                                                    ];
                                                })
                                            ->end()
                                            ->fixXmlConfig('argument')
                                            ->children()
                                                ->scalarNode('id')->isRequired()->cannotBeEmpty()->end()
                                                ->arrayNode('arguments')
                                                    ->normalizeKeys(false)
                                                    ->defaultValue([])
                                                    ->prototype('variable')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addRobotsIndexSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->booleanNode('disallow_search_engine_index')
                    ->info('Enabled by default when debug is enabled.')
                    ->defaultValue($this->debug)
                    ->treatNullLike($this->debug)
                ->end()
            ->end()
        ;
    }

    private function addHttpClientSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('http_client')
                    ->info('HTTP Client configuration')
                    ->{$enableIfStandalone('symfony/http-client', HttpClient::class)}()
                    ->fixXmlConfig('scoped_client')
                    ->beforeNormalization()
                        ->always(function ($config) {
                            if (empty($config['scoped_clients']) || !\is_array($config['default_options']['retry_failed'] ?? null)) {
                                return $config;
                            }

                            foreach ($config['scoped_clients'] as &$scopedConfig) {
                                if (!isset($scopedConfig['retry_failed']) || true === $scopedConfig['retry_failed']) {
                                    $scopedConfig['retry_failed'] = $config['default_options']['retry_failed'];
                                    continue;
                                }
                                if (\is_array($scopedConfig['retry_failed'])) {
                                    $scopedConfig['retry_failed'] = $scopedConfig['retry_failed'] + $config['default_options']['retry_failed'];
                                }
                            }

                            return $config;
                        })
                    ->end()
                    ->children()
                        ->integerNode('max_host_connections')
                            ->info('The maximum number of connections to a single host.')
                        ->end()
                        ->arrayNode('default_options')
                            ->fixXmlConfig('header')
                            ->children()
                                ->arrayNode('headers')
                                    ->info('Associative array: header => value(s).')
                                    ->useAttributeAsKey('name')
                                    ->normalizeKeys(false)
                                    ->variablePrototype()->end()
                                ->end()
                                ->integerNode('max_redirects')
                                    ->info('The maximum number of redirects to follow.')
                                ->end()
                                ->scalarNode('http_version')
                                    ->info('The default HTTP version, typically 1.1 or 2.0, leave to null for the best version.')
                                ->end()
                                ->arrayNode('resolve')
                                    ->info('Associative array: domain => IP.')
                                    ->useAttributeAsKey('host')
                                    ->beforeNormalization()
                                        ->always(function ($config) {
                                            if (!\is_array($config)) {
                                                return [];
                                            }
                                            if (!isset($config['host'], $config['value']) || \count($config) > 2) {
                                                return $config;
                                            }

                                            return [$config['host'] => $config['value']];
                                        })
                                    ->end()
                                    ->normalizeKeys(false)
                                    ->scalarPrototype()->end()
                                ->end()
                                ->scalarNode('proxy')
                                    ->info('The URL of the proxy to pass requests through or null for automatic detection.')
                                ->end()
                                ->scalarNode('no_proxy')
                                    ->info('A comma separated list of hosts that do not require a proxy to be reached.')
                                ->end()
                                ->floatNode('timeout')
                                    ->info('The idle timeout, defaults to the "default_socket_timeout" ini parameter.')
                                ->end()
                                ->floatNode('max_duration')
                                    ->info('The maximum execution time for the request+response as a whole.')
                                ->end()
                                ->scalarNode('bindto')
                                    ->info('A network interface name, IP address, a host name or a UNIX socket to bind to.')
                                ->end()
                                ->booleanNode('verify_peer')
                                    ->info('Indicates if the peer should be verified in an SSL/TLS context.')
                                ->end()
                                ->booleanNode('verify_host')
                                    ->info('Indicates if the host should exist as a certificate common name.')
                                ->end()
                                ->scalarNode('cafile')
                                    ->info('A certificate authority file.')
                                ->end()
                                ->scalarNode('capath')
                                    ->info('A directory that contains multiple certificate authority files.')
                                ->end()
                                ->scalarNode('local_cert')
                                    ->info('A PEM formatted certificate file.')
                                ->end()
                                ->scalarNode('local_pk')
                                    ->info('A private key file.')
                                ->end()
                                ->scalarNode('passphrase')
                                    ->info('The passphrase used to encrypt the "local_pk" file.')
                                ->end()
                                ->scalarNode('ciphers')
                                    ->info('A list of SSL/TLS ciphers separated by colons, commas or spaces (e.g. "RC3-SHA:TLS13-AES-128-GCM-SHA256"...)')
                                ->end()
                                ->arrayNode('peer_fingerprint')
                                    ->info('Associative array: hashing algorithm => hash(es).')
                                    ->normalizeKeys(false)
                                    ->children()
                                        ->variableNode('sha1')->end()
                                        ->variableNode('pin-sha256')->end()
                                        ->variableNode('md5')->end()
                                    ->end()
                                ->end()
                                ->append($this->addHttpClientRetrySection())
                            ->end()
                        ->end()
                        ->scalarNode('mock_response_factory')
                            ->info('The id of the service that should generate mock responses. It should be either an invokable or an iterable.')
                        ->end()
                        ->arrayNode('scoped_clients')
                            ->useAttributeAsKey('name')
                            ->normalizeKeys(false)
                            ->arrayPrototype()
                                ->fixXmlConfig('header')
                                ->beforeNormalization()
                                    ->always()
                                    ->then(function ($config) {
                                        if (!class_exists(HttpClient::class)) {
                                            throw new LogicException('HttpClient support cannot be enabled as the component is not installed. Try running "composer require symfony/http-client".');
                                        }

                                        return \is_array($config) ? $config : ['base_uri' => $config];
                                    })
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) { return !isset($v['scope']) && !isset($v['base_uri']); })
                                    ->thenInvalid('Either "scope" or "base_uri" should be defined.')
                                ->end()
                                ->validate()
                                    ->ifTrue(function ($v) { return !empty($v['query']) && !isset($v['base_uri']); })
                                    ->thenInvalid('"query" applies to "base_uri" but no base URI is defined.')
                                ->end()
                                ->children()
                                    ->scalarNode('scope')
                                        ->info('The regular expression that the request URL must match before adding the other options. When none is provided, the base URI is used instead.')
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('base_uri')
                                        ->info('The URI to resolve relative URLs, following rules in RFC 3985, section 2.')
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('auth_basic')
                                        ->info('An HTTP Basic authentication "username:password".')
                                    ->end()
                                    ->scalarNode('auth_bearer')
                                        ->info('A token enabling HTTP Bearer authorization.')
                                    ->end()
                                    ->scalarNode('auth_ntlm')
                                        ->info('A "username:password" pair to use Microsoft NTLM authentication (requires the cURL extension).')
                                    ->end()
                                    ->arrayNode('query')
                                        ->info('Associative array of query string values merged with the base URI.')
                                        ->useAttributeAsKey('key')
                                        ->beforeNormalization()
                                            ->always(function ($config) {
                                                if (!\is_array($config)) {
                                                    return [];
                                                }
                                                if (!isset($config['key'], $config['value']) || \count($config) > 2) {
                                                    return $config;
                                                }

                                                return [$config['key'] => $config['value']];
                                            })
                                        ->end()
                                        ->normalizeKeys(false)
                                        ->scalarPrototype()->end()
                                    ->end()
                                    ->arrayNode('headers')
                                        ->info('Associative array: header => value(s).')
                                        ->useAttributeAsKey('name')
                                        ->normalizeKeys(false)
                                        ->variablePrototype()->end()
                                    ->end()
                                    ->integerNode('max_redirects')
                                        ->info('The maximum number of redirects to follow.')
                                    ->end()
                                    ->scalarNode('http_version')
                                        ->info('The default HTTP version, typically 1.1 or 2.0, leave to null for the best version.')
                                    ->end()
                                    ->arrayNode('resolve')
                                        ->info('Associative array: domain => IP.')
                                        ->useAttributeAsKey('host')
                                        ->beforeNormalization()
                                            ->always(function ($config) {
                                                if (!\is_array($config)) {
                                                    return [];
                                                }
                                                if (!isset($config['host'], $config['value']) || \count($config) > 2) {
                                                    return $config;
                                                }

                                                return [$config['host'] => $config['value']];
                                            })
                                        ->end()
                                        ->normalizeKeys(false)
                                        ->scalarPrototype()->end()
                                    ->end()
                                    ->scalarNode('proxy')
                                        ->info('The URL of the proxy to pass requests through or null for automatic detection.')
                                    ->end()
                                    ->scalarNode('no_proxy')
                                        ->info('A comma separated list of hosts that do not require a proxy to be reached.')
                                    ->end()
                                    ->floatNode('timeout')
                                        ->info('The idle timeout, defaults to the "default_socket_timeout" ini parameter.')
                                    ->end()
                                    ->floatNode('max_duration')
                                        ->info('The maximum execution time for the request+response as a whole.')
                                    ->end()
                                    ->scalarNode('bindto')
                                        ->info('A network interface name, IP address, a host name or a UNIX socket to bind to.')
                                    ->end()
                                    ->booleanNode('verify_peer')
                                        ->info('Indicates if the peer should be verified in an SSL/TLS context.')
                                    ->end()
                                    ->booleanNode('verify_host')
                                        ->info('Indicates if the host should exist as a certificate common name.')
                                    ->end()
                                    ->scalarNode('cafile')
                                        ->info('A certificate authority file.')
                                    ->end()
                                    ->scalarNode('capath')
                                        ->info('A directory that contains multiple certificate authority files.')
                                    ->end()
                                    ->scalarNode('local_cert')
                                        ->info('A PEM formatted certificate file.')
                                    ->end()
                                    ->scalarNode('local_pk')
                                        ->info('A private key file.')
                                    ->end()
                                    ->scalarNode('passphrase')
                                        ->info('The passphrase used to encrypt the "local_pk" file.')
                                    ->end()
                                    ->scalarNode('ciphers')
                                        ->info('A list of SSL/TLS ciphers separated by colons, commas or spaces (e.g. "RC3-SHA:TLS13-AES-128-GCM-SHA256"...)')
                                    ->end()
                                    ->arrayNode('peer_fingerprint')
                                        ->info('Associative array: hashing algorithm => hash(es).')
                                        ->normalizeKeys(false)
                                        ->children()
                                            ->variableNode('sha1')->end()
                                            ->variableNode('pin-sha256')->end()
                                            ->variableNode('md5')->end()
                                        ->end()
                                    ->end()
                                    ->append($this->addHttpClientRetrySection())
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addHttpClientRetrySection()
    {
        $root = new NodeBuilder();

        return $root
            ->arrayNode('retry_failed')
                ->fixXmlConfig('http_code')
                ->canBeEnabled()
                ->addDefaultsIfNotSet()
                ->beforeNormalization()
                    ->always(function ($v) {
                        if (isset($v['retry_strategy']) && (isset($v['http_codes']) || isset($v['delay']) || isset($v['multiplier']) || isset($v['max_delay']) || isset($v['jitter']))) {
                            throw new \InvalidArgumentException('The "retry_strategy" option cannot be used along with the "http_codes", "delay", "multiplier", "max_delay" or "jitter" options.');
                        }

                        return $v;
                    })
                ->end()
                ->children()
                    ->scalarNode('retry_strategy')->defaultNull()->info('service id to override the retry strategy')->end()
                    ->arrayNode('http_codes')
                        ->performNoDeepMerging()
                        ->beforeNormalization()
                            ->ifArray()
                            ->then(static function ($v) {
                                $list = [];
                                foreach ($v as $key => $val) {
                                    if (is_numeric($val)) {
                                        $list[] = ['code' => $val];
                                    } elseif (\is_array($val)) {
                                        if (isset($val['code']) || isset($val['methods'])) {
                                            $list[] = $val;
                                        } else {
                                            $list[] = ['code' => $key, 'methods' => $val];
                                        }
                                    } elseif (true === $val || null === $val) {
                                        $list[] = ['code' => $key];
                                    }
                                }

                                return $list;
                            })
                        ->end()
                        ->useAttributeAsKey('code')
                        ->arrayPrototype()
                            ->fixXmlConfig('method')
                            ->children()
                                ->integerNode('code')->end()
                                ->arrayNode('methods')
                                    ->beforeNormalization()
                                    ->ifArray()
                                        ->then(function ($v) {
                                            return array_map('strtoupper', $v);
                                        })
                                    ->end()
                                    ->prototype('scalar')->end()
                                    ->info('A list of HTTP methods that triggers a retry for this status code. When empty, all methods are retried')
                                ->end()
                            ->end()
                        ->end()
                        ->info('A list of HTTP status code that triggers a retry')
                    ->end()
                    ->integerNode('max_retries')->defaultValue(3)->min(0)->end()
                    ->integerNode('delay')->defaultValue(1000)->min(0)->info('Time in ms to delay (or the initial value when multiplier is used)')->end()
                    ->floatNode('multiplier')->defaultValue(2)->min(1)->info('If greater than 1, delay will grow exponentially for each retry: delay * (multiple ^ retries)')->end()
                    ->integerNode('max_delay')->defaultValue(0)->min(0)->info('Max time in ms that a retry should ever be delayed (0 = infinite)')->end()
                    ->floatNode('jitter')->defaultValue(0.1)->min(0)->max(1)->info('Randomness in percent (between 0 and 1) to apply to the delay')->end()
                ->end()
        ;
    }

    private function addMailerSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('mailer')
                    ->info('Mailer configuration')
                    ->{$enableIfStandalone('symfony/mailer', Mailer::class)}()
                    ->validate()
                        ->ifTrue(function ($v) { return isset($v['dsn']) && \count($v['transports']); })
                        ->thenInvalid('"dsn" and "transports" cannot be used together.')
                    ->end()
                    ->fixXmlConfig('transport')
                    ->fixXmlConfig('header')
                    ->children()
                        ->scalarNode('message_bus')->defaultNull()->info('The message bus to use. Defaults to the default bus if the Messenger component is installed.')->end()
                        ->scalarNode('dsn')->defaultNull()->end()
                        ->arrayNode('transports')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('envelope')
                            ->info('Mailer Envelope configuration')
                            ->children()
                                ->scalarNode('sender')->end()
                                ->arrayNode('recipients')
                                    ->performNoDeepMerging()
                                    ->beforeNormalization()
                                    ->ifArray()
                                        ->then(function ($v) {
                                            return array_filter(array_values($v));
                                        })
                                    ->end()
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('headers')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->normalizeKeys(false)
                                ->beforeNormalization()
                                    ->ifTrue(function ($v) { return !\is_array($v) || array_keys($v) !== ['value']; })
                                    ->then(function ($v) { return ['value' => $v]; })
                                ->end()
                                ->children()
                                    ->variableNode('value')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addNotifierSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('notifier')
                    ->info('Notifier configuration')
                    ->{$enableIfStandalone('symfony/notifier', Notifier::class)}()
                    ->fixXmlConfig('chatter_transport')
                    ->children()
                        ->arrayNode('chatter_transports')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                    ->fixXmlConfig('texter_transport')
                    ->children()
                        ->arrayNode('texter_transports')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                    ->children()
                        ->booleanNode('notification_on_failed_messages')->defaultFalse()->end()
                    ->end()
                    ->children()
                        ->arrayNode('channel_policy')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->beforeNormalization()->ifString()->then(function (string $v) { return [$v]; })->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->fixXmlConfig('admin_recipient')
                    ->children()
                        ->arrayNode('admin_recipients')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('email')->cannotBeEmpty()->end()
                                    ->scalarNode('phone')->defaultValue('')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addRateLimiterSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('rate_limiter')
                    ->info('Rate limiter configuration')
                    ->{$enableIfStandalone('symfony/rate-limiter', TokenBucketLimiter::class)}()
                    ->fixXmlConfig('limiter')
                    ->beforeNormalization()
                        ->ifTrue(function ($v) { return \is_array($v) && !isset($v['limiters']) && !isset($v['limiter']); })
                        ->then(function (array $v) {
                            $newV = [
                                'enabled' => $v['enabled'] ?? true,
                            ];
                            unset($v['enabled']);

                            $newV['limiters'] = $v;

                            return $newV;
                        })
                    ->end()
                    ->children()
                        ->arrayNode('limiters')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('lock_factory')
                                        ->info('The service ID of the lock factory used by this limiter (or null to disable locking)')
                                        ->defaultValue('lock.factory')
                                    ->end()
                                    ->scalarNode('cache_pool')
                                        ->info('The cache pool to use for storing the current limiter state')
                                        ->defaultValue('cache.rate_limiter')
                                    ->end()
                                    ->scalarNode('storage_service')
                                        ->info('The service ID of a custom storage implementation, this precedes any configured "cache_pool"')
                                        ->defaultNull()
                                    ->end()
                                    ->enumNode('policy')
                                        ->info('The algorithm to be used by this limiter')
                                        ->isRequired()
                                        ->values(['fixed_window', 'token_bucket', 'sliding_window', 'no_limit'])
                                    ->end()
                                    ->integerNode('limit')
                                        ->info('The maximum allowed hits in a fixed interval or burst')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('interval')
                                        ->info('Configures the fixed interval if "policy" is set to "fixed_window" or "sliding_window". The value must be a number followed by "second", "minute", "hour", "day", "week" or "month" (or their plural equivalent).')
                                    ->end()
                                    ->arrayNode('rate')
                                        ->info('Configures the fill rate if "policy" is set to "token_bucket"')
                                        ->children()
                                            ->scalarNode('interval')
                                                ->info('Configures the rate interval. The value must be a number followed by "second", "minute", "hour", "day", "week" or "month" (or their plural equivalent).')
                                            ->end()
                                            ->integerNode('amount')->info('Amount of tokens to add each interval')->defaultValue(1)->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addUidSection(ArrayNodeDefinition $rootNode, callable $enableIfStandalone)
    {
        $rootNode
            ->children()
                ->arrayNode('uid')
                    ->info('Uid configuration')
                    ->{$enableIfStandalone('symfony/uid', UuidFactory::class)}()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('default_uuid_version')
                            ->defaultValue(6)
                            ->values([6, 4, 1])
                        ->end()
                        ->enumNode('name_based_uuid_version')
                            ->defaultValue(5)
                            ->values([5, 3])
                        ->end()
                        ->scalarNode('name_based_uuid_namespace')
                            ->cannotBeEmpty()
                        ->end()
                        ->enumNode('time_based_uuid_version')
                            ->defaultValue(6)
                            ->values([6, 1])
                        ->end()
                        ->scalarNode('time_based_uuid_node')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
