<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Bundle\FrameworkBundle\CacheWarmer\ConfigBuilderCacheWarmer;
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\Config\Resource\SelfCheckingResourceChecker;
use Symfony\Component\Config\ResourceCheckerConfigCacheFactory;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\DependencyInjection\Config\ContainerParametersResourceChecker;
use Symfony\Component\DependencyInjection\EnvVarProcessor;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ReverseContainer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcherInterfaceComponentAlias;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter;
use Symfony\Component\HttpKernel\EventListener\LocaleAwareListener;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Runtime\Runner\Symfony\HttpKernelRunner;
use Symfony\Component\Runtime\Runner\Symfony\ResponseRunner;
use Symfony\Component\Runtime\SymfonyRuntime;
use Symfony\Component\String\LazyString;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Workflow\WorkflowEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

return static function (ContainerConfigurator $container) {
    // this parameter is used at compile time in RegisterListenersPass
    $container->parameters()->set('event_dispatcher.event_aliases', array_merge(
        class_exists(ConsoleEvents::class) ? ConsoleEvents::ALIASES : [],
        class_exists(FormEvents::class) ? FormEvents::ALIASES : [],
        KernelEvents::ALIASES,
        class_exists(WorkflowEvents::class) ? WorkflowEvents::ALIASES : []
    ));

    $container->services()

        ->set('parameter_bag', ContainerBag::class)
            ->args([
                service('service_container'),
            ])
        ->alias(ContainerBagInterface::class, 'parameter_bag')
        ->alias(ParameterBagInterface::class, 'parameter_bag')

        ->set('event_dispatcher', EventDispatcher::class)
            ->public()
            ->tag('container.hot_path')
            ->tag('event_dispatcher.dispatcher', ['name' => 'event_dispatcher'])
        ->alias(EventDispatcherInterfaceComponentAlias::class, 'event_dispatcher')
        ->alias(EventDispatcherInterface::class, 'event_dispatcher')

        ->set('http_kernel', HttpKernel::class)
            ->public()
            ->args([
                service('event_dispatcher'),
                service('controller_resolver'),
                service('request_stack'),
                service('argument_resolver'),
            ])
            ->tag('container.hot_path')
            ->tag('container.preload', ['class' => HttpKernelRunner::class])
            ->tag('container.preload', ['class' => ResponseRunner::class])
            ->tag('container.preload', ['class' => SymfonyRuntime::class])
        ->alias(HttpKernelInterface::class, 'http_kernel')

        ->set('request_stack', RequestStack::class)
            ->public()
        ->alias(RequestStack::class, 'request_stack')

        ->set('http_cache', HttpCache::class)
            ->args([
                service('kernel'),
                service('http_cache.store'),
                service('esi')->nullOnInvalid(),
                abstract_arg('options'),
            ])
            ->tag('container.hot_path')

        ->set('http_cache.store', Store::class)
            ->args([
                param('kernel.cache_dir').'/http_cache',
            ])
        ->alias(StoreInterface::class, 'http_cache.store')

        ->set('url_helper', UrlHelper::class)
            ->args([
                service('request_stack'),
                service('router.request_context')->ignoreOnInvalid(),
            ])
        ->alias(UrlHelper::class, 'url_helper')

        ->set('cache_warmer', CacheWarmerAggregate::class)
            ->public()
            ->args([
                tagged_iterator('kernel.cache_warmer'),
                param('kernel.debug'),
                sprintf('%s/%sDeprecations.log', param('kernel.build_dir'), param('kernel.container_class')),
            ])
            ->tag('container.no_preload')

        ->set('cache_clearer', ChainCacheClearer::class)
            ->public()
            ->args([
                tagged_iterator('kernel.cache_clearer'),
            ])
            ->tag('container.private', ['package' => 'symfony/framework-bundle', 'version' => '5.2'])

        ->set('kernel')
            ->synthetic()
            ->public()
        ->alias(KernelInterface::class, 'kernel')

        ->set('filesystem', Filesystem::class)
            ->public()
            ->tag('container.private', ['package' => 'symfony/framework-bundle', 'version' => '5.2'])
        ->alias(Filesystem::class, 'filesystem')

        ->set('file_locator', FileLocator::class)
            ->args([
                service('kernel'),
            ])
        ->alias(FileLocator::class, 'file_locator')

        ->set('uri_signer', UriSigner::class)
            ->args([
                param('kernel.secret'),
            ])
        ->alias(UriSigner::class, 'uri_signer')

        ->set('config_cache_factory', ResourceCheckerConfigCacheFactory::class)
            ->args([
                tagged_iterator('config_cache.resource_checker'),
            ])

        ->set('dependency_injection.config.container_parameters_resource_checker', ContainerParametersResourceChecker::class)
            ->args([
                service('service_container'),
            ])
            ->tag('config_cache.resource_checker', ['priority' => -980])

        ->set('config.resource.self_checking_resource_checker', SelfCheckingResourceChecker::class)
            ->tag('config_cache.resource_checker', ['priority' => -990])

        ->set('services_resetter', ServicesResetter::class)
            ->public()

        ->set('reverse_container', ReverseContainer::class)
            ->args([
                service('service_container'),
                service_locator([]),
            ])
        ->alias(ReverseContainer::class, 'reverse_container')

        ->set('locale_aware_listener', LocaleAwareListener::class)
            ->args([
                [], // locale aware services
                service('request_stack'),
            ])
            ->tag('kernel.event_subscriber')

        ->set('container.env_var_processor', EnvVarProcessor::class)
            ->args([
                service('service_container'),
                tagged_iterator('container.env_var_loader'),
            ])
            ->tag('container.env_var_processor')

        ->set('slugger', AsciiSlugger::class)
            ->args([
                param('kernel.default_locale'),
            ])
            ->tag('kernel.locale_aware')
        ->alias(SluggerInterface::class, 'slugger')

        ->set('container.getenv', \Closure::class)
            ->factory([\Closure::class, 'fromCallable'])
            ->args([
                [service('service_container'), 'getEnv'],
            ])
            ->tag('routing.expression_language_function', ['function' => 'env'])

        // inherit from this service to lazily access env vars
        ->set('container.env', LazyString::class)
            ->abstract()
            ->factory([LazyString::class, 'fromCallable'])
            ->args([
                service('container.getenv'),
            ])
        ->set('config_builder.warmer', ConfigBuilderCacheWarmer::class)
            ->args([service(KernelInterface::class), service('logger')->nullOnInvalid()])
            ->tag('kernel.cache_warmer')
    ;
};
