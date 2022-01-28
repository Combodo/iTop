<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\TwigBundle\Tests\DependencyInjection;

use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\RuntimeLoaderPass;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Bundle\TwigBundle\Tests\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;

class TwigExtensionTest extends TestCase
{
    public function testLoadEmptyConfiguration()
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $container->loadFromExtension('twig', []);
        $this->compileContainer($container);

        $this->assertEquals('Twig\Environment', $container->getDefinition('twig')->getClass(), '->load() loads the twig.xml file');

        $this->assertContains('form_div_layout.html.twig', $container->getParameter('twig.form.resources'), '->load() includes default template for form resources');

        // Twig options
        $options = $container->getDefinition('twig')->getArgument(1);
        $this->assertEquals('%kernel.cache_dir%/twig', $options['cache'], '->load() sets default value for cache option');
        $this->assertEquals('%kernel.charset%', $options['charset'], '->load() sets default value for charset option');
        $this->assertEquals('%kernel.debug%', $options['debug'], '->load() sets default value for debug option');
    }

    /**
     * @dataProvider getFormats
     */
    public function testLoadFullConfiguration($format)
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $this->loadFromFile($container, 'full', $format);
        $this->compileContainer($container);

        $this->assertEquals('Twig\Environment', $container->getDefinition('twig')->getClass(), '->load() loads the twig.xml file');

        // Form resources
        $resources = $container->getParameter('twig.form.resources');
        $this->assertContains('form_div_layout.html.twig', $resources, '->load() includes default template for form resources');
        $this->assertContains('MyBundle::form.html.twig', $resources, '->load() merges new templates into form resources');

        // Globals
        $calls = $container->getDefinition('twig')->getMethodCalls();
        $this->assertEquals('app', $calls[0][1][0], '->load() registers services as Twig globals');
        $this->assertEquals(new Reference('twig.app_variable'), $calls[0][1][1]);
        $this->assertEquals('foo', $calls[2][1][0], '->load() registers services as Twig globals');
        $this->assertEquals(new Reference('bar'), $calls[2][1][1], '->load() registers services as Twig globals');
        $this->assertEquals('baz', $calls[3][1][0], '->load() registers variables as Twig globals');
        $this->assertEquals('@qux', $calls[3][1][1], '->load() allows escaping of service identifiers');
        $this->assertEquals('pi', $calls[4][1][0], '->load() registers variables as Twig globals');
        $this->assertEquals(3.14, $calls[4][1][1], '->load() registers variables as Twig globals');

        // Yaml and Php specific configs
        if (\in_array($format, ['yml', 'php'])) {
            $this->assertEquals('bad', $calls[5][1][0], '->load() registers variables as Twig globals');
            $this->assertEquals(['key' => 'foo'], $calls[5][1][1], '->load() registers variables as Twig globals');
        }

        // Twig options
        $options = $container->getDefinition('twig')->getArgument(1);
        $this->assertTrue($options['auto_reload'], '->load() sets the auto_reload option');
        $this->assertTrue($options['autoescape'], '->load() sets the autoescape option');
        $this->assertEquals('stdClass', $options['base_template_class'], '->load() sets the base_template_class option');
        $this->assertEquals('/tmp', $options['cache'], '->load() sets the cache option');
        $this->assertEquals('ISO-8859-1', $options['charset'], '->load() sets the charset option');
        $this->assertTrue($options['debug'], '->load() sets the debug option');
        $this->assertTrue($options['strict_variables'], '->load() sets the strict_variables option');
    }

    /**
     * @dataProvider getFormats
     */
    public function testLoadCustomTemplateEscapingGuesserConfiguration($format)
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $this->loadFromFile($container, 'customTemplateEscapingGuesser', $format);
        $this->compileContainer($container);

        $options = $container->getDefinition('twig')->getArgument(1);
        $this->assertEquals([new Reference('my_project.some_bundle.template_escaping_guesser'), 'guess'], $options['autoescape']);
    }

    /**
     * @dataProvider getFormats
     */
    public function testLoadDefaultTemplateEscapingGuesserConfiguration($format)
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $this->loadFromFile($container, 'empty', $format);
        $this->compileContainer($container);

        $options = $container->getDefinition('twig')->getArgument(1);
        $this->assertEquals('name', $options['autoescape']);
    }

    /**
     * @dataProvider getFormats
     */
    public function testLoadCustomDateFormats($fileFormat)
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $this->loadFromFile($container, 'formats', $fileFormat);
        $this->compileContainer($container);

        $environmentConfigurator = $container->getDefinition('twig.configurator.environment');

        $this->assertSame('Y-m-d', $environmentConfigurator->getArgument(0));
        $this->assertSame('%d', $environmentConfigurator->getArgument(1));
        $this->assertSame('Europe/Berlin', $environmentConfigurator->getArgument(2));
        $this->assertSame(2, $environmentConfigurator->getArgument(3));
        $this->assertSame(',', $environmentConfigurator->getArgument(4));
        $this->assertSame('.', $environmentConfigurator->getArgument(5));
    }

    public function testGlobalsWithDifferentTypesAndValues()
    {
        $globals = [
            'array' => [],
            'false' => false,
            'float' => 2.0,
            'integer' => 3,
            'null' => null,
            'object' => new \stdClass(),
            'string' => 'foo',
            'true' => true,
        ];

        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $container->loadFromExtension('twig', ['globals' => $globals]);
        $this->compileContainer($container);

        $calls = $container->getDefinition('twig')->getMethodCalls();
        foreach (\array_slice($calls, 2) as $call) {
            $this->assertEquals(key($globals), $call[1][0]);
            $this->assertSame(current($globals), $call[1][1]);

            next($globals);
        }
    }

    /**
     * @dataProvider getFormats
     */
    public function testTwigLoaderPaths($format)
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $this->loadFromFile($container, 'full', $format);
        $this->loadFromFile($container, 'extra', $format);
        $this->compileContainer($container);

        $def = $container->getDefinition('twig.loader.native_filesystem');
        $paths = [];
        foreach ($def->getMethodCalls() as $call) {
            if ('addPath' === $call[0] && false === strpos($call[1][0], 'Form')) {
                $paths[] = $call[1];
            }
        }

        $this->assertEquals([
            ['path1'],
            ['path2'],
            ['namespaced_path1', 'namespace1'],
            ['namespaced_path2', 'namespace2'],
            ['namespaced_path3', 'namespace3'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildChildTwigBundle/Resources/views', 'ChildChildChildChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildChildTwigBundle/Resources/views', 'ChildChildChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildTwigBundle/Resources/views', 'ChildChildChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildChildTwigBundle/Resources/views', 'Twig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildTwigBundle/Resources/views', 'Twig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildTwigBundle/Resources/views', 'Twig'],
            [__DIR__.'/Fixtures/Bundle/ChildTwigBundle/Resources/views', 'Twig'],
            [__DIR__.'/Fixtures/Resources/TwigBundle/views', 'Twig'],
            [__DIR__.'/Fixtures/templates/bundles/TwigBundle', 'Twig'],
            [realpath(__DIR__.'/../..').'/Resources/views', 'Twig'],
            [realpath(__DIR__.'/../..').'/Resources/views', '!Twig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildChildTwigBundle/Resources/views', 'ChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildTwigBundle/Resources/views', 'ChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildTwigBundle/Resources/views', 'ChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildTwigBundle/Resources/views', 'ChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildChildTwigBundle/Resources/views', 'ChildChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildChildTwigBundle/Resources/views', 'ChildChildTwig'],
            [__DIR__.'/Fixtures/Bundle/ChildChildTwigBundle/Resources/views', 'ChildChildTwig'],
            [__DIR__.'/Fixtures/Resources/views'],
            [__DIR__.'/Fixtures/templates'],
        ], $paths);
    }

    public function getFormats()
    {
        return [
            ['php'],
            ['yml'],
            ['xml'],
        ];
    }

    /**
     * @dataProvider stopwatchExtensionAvailabilityProvider
     */
    public function testStopwatchExtensionAvailability($debug, $stopwatchEnabled, $expected)
    {
        $container = $this->createContainer();
        $container->setParameter('kernel.debug', $debug);
        if ($stopwatchEnabled) {
            $container->register('debug.stopwatch', 'Symfony\Component\Stopwatch\Stopwatch');
        }
        $container->registerExtension(new TwigExtension());
        $container->loadFromExtension('twig', []);
        $container->setAlias('test.twig.extension.debug.stopwatch', 'twig.extension.debug.stopwatch')->setPublic(true);
        $this->compileContainer($container);

        $tokenParsers = $container->get('test.twig.extension.debug.stopwatch')->getTokenParsers();
        $stopwatchIsAvailable = new \ReflectionProperty($tokenParsers[0], 'stopwatchIsAvailable');
        $stopwatchIsAvailable->setAccessible(true);

        $this->assertSame($expected, $stopwatchIsAvailable->getValue($tokenParsers[0]));
    }

    public function stopwatchExtensionAvailabilityProvider()
    {
        return [
            'debug-and-stopwatch-enabled' => [true, true, true],
            'only-stopwatch-enabled' => [false, true, false],
            'only-debug-enabled' => [true, false, false],
            'debug-and-stopwatch-disabled' => [false, false, false],
        ];
    }

    public function testRuntimeLoader()
    {
        $container = $this->createContainer();
        $container->registerExtension(new TwigExtension());
        $container->loadFromExtension('twig', []);
        $container->setParameter('kernel.environment', 'test');
        $container->setParameter('debug.file_link_format', 'test');
        $container->setParameter('foo', 'FooClass');
        $container->register('http_kernel', 'FooClass');
        $container->register('templating.locator', 'FooClass');
        $container->register('templating.name_parser', 'FooClass');
        $container->register('foo', '%foo%')->addTag('twig.runtime');
        $container->addCompilerPass(new RuntimeLoaderPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

        $loader = $container->getDefinition('twig.runtime_loader');
        $args = $container->getDefinition((string) $loader->getArgument(0))->getArgument(0);
        $this->assertArrayHasKey('Symfony\Component\Form\FormRenderer', $args);
        $this->assertArrayHasKey('FooClass', $args);
        $this->assertEquals('twig.form.renderer', $args['Symfony\Component\Form\FormRenderer']->getValues()[0]);
        $this->assertEquals('foo', $args['FooClass']->getValues()[0]);
    }

    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__.'/Fixtures',
            'kernel.project_dir' => __DIR__,
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => false,
            'kernel.bundles' => [
                'TwigBundle' => 'Symfony\\Bundle\\TwigBundle\\TwigBundle',
                'ChildTwigBundle' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildTwigBundle\\ChildTwigBundle',
                'ChildChildTwigBundle' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildChildTwigBundle\\ChildChildTwigBundle',
                'ChildChildChildTwigBundle' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildChildChildTwigBundle\\ChildChildChildTwigBundle',
                'ChildChildChildChildTwigBundle' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildChildChildChildTwigBundle\\ChildChildChildChildTwigBundle',
            ],
            'kernel.bundles_metadata' => [
                'ChildChildChildChildTwigBundle' => [
                    'namespace' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildChildChildChildTwigBundle\\ChildChildChildChildTwigBundle',
                    'parent' => 'ChildChildChildTwigBundle',
                    'path' => __DIR__.'/Fixtures/Bundle/ChildChildChildChildTwigBundle',
                ],
                'TwigBundle' => [
                    'namespace' => 'Symfony\\Bundle\\TwigBundle',
                    'parent' => null,
                    'path' => realpath(__DIR__.'/../..'),
                ],
                'ChildTwigBundle' => [
                    'namespace' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildTwigBundle\\ChildTwigBundle',
                    'parent' => 'TwigBundle',
                    'path' => __DIR__.'/Fixtures/Bundle/ChildTwigBundle',
                ],
                'ChildChildChildTwigBundle' => [
                    'namespace' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildChildChildTwigBundle\\ChildChildChildTwigBundle',
                    'parent' => 'ChildChildTwigBundle',
                    'path' => __DIR__.'/Fixtures/Bundle/ChildChildChildTwigBundle',
                ],
                'ChildChildTwigBundle' => [
                    'namespace' => 'Symfony\\Bundle\\TwigBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle\\ChildChildTwigBundle\\ChildChildTwigBundle',
                    'parent' => 'ChildTwigBundle',
                    'path' => __DIR__.'/Fixtures/Bundle/ChildChildTwigBundle',
                ],
            ],
        ]));

        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();
    }

    private function loadFromFile(ContainerBuilder $container, $file, $format)
    {
        $locator = new FileLocator(__DIR__.'/Fixtures/'.$format);

        switch ($format) {
            case 'php':
                $loader = new PhpFileLoader($container, $locator);
                break;
            case 'xml':
                $loader = new XmlFileLoader($container, $locator);
                break;
            case 'yml':
                $loader = new YamlFileLoader($container, $locator);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unsupported format: "%s"', $format));
        }

        $loader->load($file.'.'.$format);
    }
}
