<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\WebProfilerBundle\Twig;

use Symfony\Component\HttpKernel\DataCollector\Util\ValueExporter;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Twig\Environment;
use Twig\Extension\ProfilerExtension;
use Twig\Profiler\Profile;
use Twig\TwigFunction;

/**
 * Twig extension for the profiler.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class WebProfilerExtension extends ProfilerExtension
{
    /**
     * @var ValueExporter
     */
    private $valueExporter;

    /**
     * @var HtmlDumper
     */
    private $dumper;

    /**
     * @var resource
     */
    private $output;

    /**
     * @var int
     */
    private $stackLevel = 0;

    public function __construct(HtmlDumper $dumper = null)
    {
        $this->dumper = $dumper ?: new HtmlDumper();
        $this->dumper->setOutput($this->output = fopen('php://memory', 'r+b'));
    }

    public function enter(Profile $profile)
    {
        ++$this->stackLevel;
    }

    public function leave(Profile $profile)
    {
        if (0 === --$this->stackLevel) {
            $this->dumper->setOutput($this->output = fopen('php://memory', 'r+b'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $profilerDump = function (Environment $env, $value, $maxDepth = 0) {
            return $value instanceof Data ? $this->dumpData($env, $value, $maxDepth) : twig_escape_filter($env, $this->dumpValue($value));
        };

        return [
            new TwigFunction('profiler_dump', $profilerDump, ['is_safe' => ['html'], 'needs_environment' => true]),
            new TwigFunction('profiler_dump_log', [$this, 'dumpLog'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function dumpData(Environment $env, Data $data, $maxDepth = 0)
    {
        $this->dumper->setCharset($env->getCharset());
        $this->dumper->dump($data, null, [
            'maxDepth' => $maxDepth,
        ]);

        $dump = stream_get_contents($this->output, -1, 0);
        rewind($this->output);
        ftruncate($this->output, 0);

        return str_replace("\n</pre", '</pre', rtrim($dump));
    }

    public function dumpLog(Environment $env, $message, Data $context = null)
    {
        $message = twig_escape_filter($env, $message);
        $message = preg_replace('/&quot;(.*?)&quot;/', '&quot;<b>$1</b>&quot;', $message);

        if (null === $context || false === strpos($message, '{')) {
            return '<span class="dump-inline">'.$message.'</span>';
        }

        $replacements = [];
        foreach ($context as $k => $v) {
            $k = '{'.twig_escape_filter($env, $k).'}';
            $replacements['&quot;<b>'.$k.'</b>&quot;'] = $replacements['&quot;'.$k.'&quot;'] = $replacements[$k] = $this->dumpData($env, $v);
        }

        return '<span class="dump-inline">'.strtr($message, $replacements).'</span>';
    }

    /**
     * @deprecated since 3.2, to be removed in 4.0. Use the dumpData() method instead.
     */
    public function dumpValue($value)
    {
        @trigger_error(sprintf('The %s() method is deprecated since Symfony 3.2 and will be removed in 4.0. Use the dumpData() method instead.', __METHOD__), \E_USER_DEPRECATED);

        if (null === $this->valueExporter) {
            $this->valueExporter = new ValueExporter();
        }

        return $this->valueExporter->exportValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'profiler';
    }
}
