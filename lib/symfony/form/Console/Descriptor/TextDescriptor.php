<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Console\Descriptor;

use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\ErrorHandler\ErrorRenderer\FileLinkFormatter;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\HttpKernel\Debug\FileLinkFormatter as LegacyFileLinkFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 *
 * @internal
 */
class TextDescriptor extends Descriptor
{
    private FileLinkFormatter|LegacyFileLinkFormatter|null $fileLinkFormatter;

    public function __construct(FileLinkFormatter|LegacyFileLinkFormatter $fileLinkFormatter = null)
    {
        $this->fileLinkFormatter = $fileLinkFormatter;
    }

    protected function describeDefaults(array $options): void
    {
        if ($options['core_types']) {
            $this->output->section('Built-in form types (Symfony\Component\Form\Extension\Core\Type)');
            $shortClassNames = array_map(fn ($fqcn) => $this->formatClassLink($fqcn, \array_slice(explode('\\', $fqcn), -1)[0]), $options['core_types']);
            for ($i = 0, $loopsMax = \count($shortClassNames); $i * 5 < $loopsMax; ++$i) {
                $this->output->writeln(' '.implode(', ', \array_slice($shortClassNames, $i * 5, 5)));
            }
        }

        if ($options['service_types']) {
            $this->output->section('Service form types');
            $this->output->listing(array_map($this->formatClassLink(...), $options['service_types']));
        }

        if (!$options['show_deprecated']) {
            if ($options['extensions']) {
                $this->output->section('Type extensions');
                $this->output->listing(array_map($this->formatClassLink(...), $options['extensions']));
            }

            if ($options['guessers']) {
                $this->output->section('Type guessers');
                $this->output->listing(array_map($this->formatClassLink(...), $options['guessers']));
            }
        }
    }

    protected function describeResolvedFormType(ResolvedFormTypeInterface $resolvedFormType, array $options = []): void
    {
        $this->collectOptions($resolvedFormType);

        if ($options['show_deprecated']) {
            $this->filterOptionsByDeprecated($resolvedFormType);
        }

        $formOptions = $this->normalizeAndSortOptionsColumns(array_filter([
            'own' => $this->ownOptions,
            'overridden' => $this->overriddenOptions,
            'parent' => $this->parentOptions,
            'extension' => $this->extensionOptions,
        ]));

        // setting headers and column order
        $tableHeaders = array_intersect_key([
            'own' => 'Options',
            'overridden' => 'Overridden options',
            'parent' => 'Parent options',
            'extension' => 'Extension options',
        ], $formOptions);

        $this->output->title(sprintf('%s (Block prefix: "%s")', $resolvedFormType->getInnerType()::class, $resolvedFormType->getInnerType()->getBlockPrefix()));

        if ($formOptions) {
            $this->output->table($tableHeaders, $this->buildTableRows($tableHeaders, $formOptions));
        }

        if ($this->parents) {
            $this->output->section('Parent types');
            $this->output->listing(array_map($this->formatClassLink(...), $this->parents));
        }

        if ($this->extensions) {
            $this->output->section('Type extensions');
            $this->output->listing(array_map($this->formatClassLink(...), $this->extensions));
        }
    }

    protected function describeOption(OptionsResolver $optionsResolver, array $options): void
    {
        $definition = $this->getOptionDefinition($optionsResolver, $options['option']);

        $dump = new Dumper($this->output);
        $map = [];
        if ($definition['deprecated']) {
            $map = [
                'Deprecated' => 'deprecated',
                'Deprecation package' => 'deprecationPackage',
                'Deprecation version' => 'deprecationVersion',
                'Deprecation message' => 'deprecationMessage',
            ];
        }
        $map += [
            'Info' => 'info',
            'Required' => 'required',
            'Default' => 'default',
            'Allowed types' => 'allowedTypes',
            'Allowed values' => 'allowedValues',
            'Normalizers' => 'normalizers',
        ];
        $rows = [];
        foreach ($map as $label => $name) {
            $value = \array_key_exists($name, $definition) ? $dump($definition[$name]) : '-';
            if ('default' === $name && isset($definition['lazy'])) {
                $value = "Value: $value\n\nClosure(s): ".$dump($definition['lazy']);
            }

            $rows[] = ["<info>$label</info>", $value];
            $rows[] = new TableSeparator();
        }
        array_pop($rows);

        $this->output->title(sprintf('%s (%s)', $options['type']::class, $options['option']));
        $this->output->table([], $rows);
    }

    private function buildTableRows(array $headers, array $options): array
    {
        $tableRows = [];
        $count = \count(max($options));
        for ($i = 0; $i < $count; ++$i) {
            $cells = [];
            foreach (array_keys($headers) as $group) {
                $option = $options[$group][$i] ?? null;
                if (\is_string($option) && \in_array($option, $this->requiredOptions, true)) {
                    $option .= ' <info>(required)</info>';
                }
                $cells[] = $option;
            }
            $tableRows[] = $cells;
        }

        return $tableRows;
    }

    private function normalizeAndSortOptionsColumns(array $options): array
    {
        foreach ($options as $group => $opts) {
            $sorted = false;
            foreach ($opts as $class => $opt) {
                if (\is_string($class)) {
                    unset($options[$group][$class]);
                }

                if (!\is_array($opt) || 0 === \count($opt)) {
                    continue;
                }

                if (!$sorted) {
                    $options[$group] = [];
                } else {
                    $options[$group][] = null;
                }
                $options[$group][] = sprintf('<info>%s</info>', (new \ReflectionClass($class))->getShortName());
                $options[$group][] = new TableSeparator();

                sort($opt);
                $sorted = true;
                $options[$group] = array_merge($options[$group], $opt);
            }

            if (!$sorted) {
                sort($options[$group]);
            }
        }

        return $options;
    }

    private function formatClassLink(string $class, string $text = null): string
    {
        $text ??= $class;

        if ('' === $fileLink = $this->getFileLink($class)) {
            return $text;
        }

        return sprintf('<href=%s>%s</>', $fileLink, $text);
    }

    private function getFileLink(string $class): string
    {
        if (null === $this->fileLinkFormatter) {
            return '';
        }

        try {
            $r = new \ReflectionClass($class);
        } catch (\ReflectionException) {
            return '';
        }

        return (string) $this->fileLinkFormatter->format($r->getFileName(), $r->getStartLine());
    }
}
