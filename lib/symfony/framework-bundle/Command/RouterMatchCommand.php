<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Routing\RouterInterface;

/**
 * A console command to test route matching.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final since version 3.4
 */
class RouterMatchCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'router:match';

    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct($router = null)
    {
        if (!$router instanceof RouterInterface) {
            @trigger_error(sprintf('%s() expects an instance of "%s" as first argument since Symfony 3.4. Not passing it is deprecated and will throw a TypeError in 4.0.', __METHOD__, RouterInterface::class), \E_USER_DEPRECATED);

            parent::__construct($router);

            return;
        }

        parent::__construct();

        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     *
     * BC to be removed in 4.0
     */
    public function isEnabled()
    {
        if (null !== $this->router) {
            return parent::isEnabled();
        }
        if (!$this->getContainer()->has('router')) {
            return false;
        }
        $router = $this->getContainer()->get('router');
        if (!$router instanceof RouterInterface) {
            return false;
        }

        return parent::isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputArgument('path_info', InputArgument::REQUIRED, 'A path info'),
                new InputOption('method', null, InputOption::VALUE_REQUIRED, 'Sets the HTTP method'),
                new InputOption('scheme', null, InputOption::VALUE_REQUIRED, 'Sets the URI scheme (usually http or https)'),
                new InputOption('host', null, InputOption::VALUE_REQUIRED, 'Sets the URI host'),
            ])
            ->setDescription('Helps debug routes by simulating a path info match')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> shows which routes match a given request and which don't and for what reason:

  <info>php %command.full_name% /foo</info>

or

  <info>php %command.full_name% /foo --method POST --scheme https --host symfony.com --verbose</info>

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // BC to be removed in 4.0
        if (null === $this->router) {
            $this->router = $this->getContainer()->get('router');
        }

        $io = new SymfonyStyle($input, $output);

        $context = $this->router->getContext();
        if (null !== $method = $input->getOption('method')) {
            $context->setMethod($method);
        }
        if (null !== $scheme = $input->getOption('scheme')) {
            $context->setScheme($scheme);
        }
        if (null !== $host = $input->getOption('host')) {
            $context->setHost($host);
        }

        $matcher = new TraceableUrlMatcher($this->router->getRouteCollection(), $context);

        $traces = $matcher->getTraces($input->getArgument('path_info'));

        $io->newLine();

        $matches = false;
        foreach ($traces as $trace) {
            if (TraceableUrlMatcher::ROUTE_ALMOST_MATCHES == $trace['level']) {
                $io->text(sprintf('Route <info>"%s"</> almost matches but %s', $trace['name'], lcfirst($trace['log'])));
            } elseif (TraceableUrlMatcher::ROUTE_MATCHES == $trace['level']) {
                $io->success(sprintf('Route "%s" matches', $trace['name']));

                $routerDebugCommand = $this->getApplication()->find('debug:router');
                $routerDebugCommand->run(new ArrayInput(['name' => $trace['name']]), $output);

                $matches = true;
            } elseif ($input->getOption('verbose')) {
                $io->text(sprintf('Route "%s" does not match: %s', $trace['name'], $trace['log']));
            }
        }

        if (!$matches) {
            $io->error(sprintf('None of the routes match the path "%s"', $input->getArgument('path_info')));

            return 1;
        }

        return null;
    }
}
