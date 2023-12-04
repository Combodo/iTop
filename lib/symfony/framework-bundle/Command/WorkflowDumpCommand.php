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

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Symfony\Component\Workflow\Dumper\MermaidDumper;
use Symfony\Component\Workflow\Dumper\PlantUmlDumper;
use Symfony\Component\Workflow\Dumper\StateMachineGraphvizDumper;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\StateMachine;

/**
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 *
 * @final
 */
#[AsCommand(name: 'workflow:dump', description: 'Dump a workflow')]
class WorkflowDumpCommand extends Command
{
    /**
     * string is the service id.
     *
     * @var array<string, Definition>
     */
    private array $definitions = [];

    private ServiceLocator $workflows;

    private const DUMP_FORMAT_OPTIONS = [
        'puml',
        'mermaid',
        'dot',
    ];

    public function __construct($workflows)
    {
        parent::__construct();

        if ($workflows instanceof ServiceLocator) {
            $this->workflows = $workflows;
        } elseif (\is_array($workflows)) {
            $this->definitions = $workflows;
            trigger_deprecation('symfony/framework-bundle', '6.2', 'Passing an array of definitions in "%s()" is deprecated. Inject a ServiceLocator filled with all workflows instead.', __METHOD__);
        } else {
            throw new \TypeError(sprintf('Argument 1 passed to "%s()" must be an array or a ServiceLocator, "%s" given.', __METHOD__, \gettype($workflows)));
        }
    }

    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('name', InputArgument::REQUIRED, 'A workflow name'),
                new InputArgument('marking', InputArgument::IS_ARRAY, 'A marking (a list of places)'),
                new InputOption('label', 'l', InputOption::VALUE_REQUIRED, 'Label a graph'),
                new InputOption('with-metadata', null, InputOption::VALUE_NONE, 'Include the workflow\'s metadata in the dumped graph', null),
                new InputOption('dump-format', null, InputOption::VALUE_REQUIRED, 'The dump format ['.implode('|', self::DUMP_FORMAT_OPTIONS).']', 'dot'),
            ])
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command dumps the graphical representation of a
workflow in different formats

<info>DOT</info>:  %command.full_name% <workflow name> | dot -Tpng > workflow.png
<info>PUML</info>: %command.full_name% <workflow name> --dump-format=puml | java -jar plantuml.jar -p > workflow.png
<info>MERMAID</info>: %command.full_name% <workflow name> --dump-format=mermaid | mmdc -o workflow.svg
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $workflowName = $input->getArgument('name');

        if (isset($this->workflows)) {
            if (!$this->workflows->has($workflowName)) {
                throw new InvalidArgumentException(sprintf('The workflow named "%s" cannot be found.', $workflowName));
            }
            $workflow = $this->workflows->get($workflowName);
            $type = $workflow instanceof StateMachine ? 'state_machine' : 'workflow';
            $definition = $workflow->getDefinition();
        } elseif (isset($this->definitions['workflow.'.$workflowName])) {
            $definition = $this->definitions['workflow.'.$workflowName];
            $type = 'workflow';
        } elseif (isset($this->definitions['state_machine.'.$workflowName])) {
            $definition = $this->definitions['state_machine.'.$workflowName];
            $type = 'state_machine';
        }

        if (null === $definition) {
            throw new InvalidArgumentException(sprintf('No service found for "workflow.%1$s" nor "state_machine.%1$s".', $workflowName));
        }

        switch ($input->getOption('dump-format')) {
            case 'puml':
                $transitionType = 'workflow' === $type ? PlantUmlDumper::WORKFLOW_TRANSITION : PlantUmlDumper::STATEMACHINE_TRANSITION;
                $dumper = new PlantUmlDumper($transitionType);
                break;

            case 'mermaid':
                $transitionType = 'workflow' === $type ? MermaidDumper::TRANSITION_TYPE_WORKFLOW : MermaidDumper::TRANSITION_TYPE_STATEMACHINE;
                $dumper = new MermaidDumper($transitionType);
                break;

            case 'dot':
            default:
                $dumper = ('workflow' === $type) ? new GraphvizDumper() : new StateMachineGraphvizDumper();
        }

        $marking = new Marking();

        foreach ($input->getArgument('marking') as $place) {
            $marking->mark($place);
        }

        $options = [
            'name' => $workflowName,
            'with-metadata' => $input->getOption('with-metadata'),
            'nofooter' => true,
            'label' => $input->getOption('label'),
        ];
        $output->writeln($dumper->dump($definition, $marking, $options));

        return 0;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('name')) {
            if (isset($this->workflows)) {
                $suggestions->suggestValues(array_keys($this->workflows->getProvidedServices()));
            } else {
                $suggestions->suggestValues(array_keys($this->definitions));
            }
        }

        if ($input->mustSuggestOptionValuesFor('dump-format')) {
            $suggestions->suggestValues(self::DUMP_FORMAT_OPTIONS);
        }
    }
}
