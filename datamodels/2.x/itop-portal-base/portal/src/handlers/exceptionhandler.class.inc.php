<?php

// Copyright (C) 2010-2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Portal\Handler;

use \Exception;
use \Silex\Application;
use \Symfony\Component\Debug\ExceptionHandler as BaseExceptionHandler;
use \Symfony\Component\Debug\Exception\FlattenException;
use \Symfony\Component\HttpFoundation\Request;
use \IssueLog;
use \Dict;

/**
 * Extends the default ExceptionHandler to provide a better template.
 *
 * @author Guillaume Lajarige
 */
class ExceptionHandler extends BaseExceptionHandler
{
    private $debug;
    private $charset;
    private $handler;
    private $caughtBuffer;
    private $caughtLength;
    private $fileLinkFormat;

    /**
     * Sends a response for the given Exception.
     *
     * To be as fail-safe as possible, the exception is first handled
     * by our simple exception handler, then by the user exception handler.
     * The latter takes precedence and any output from the former is cancelled,
     * if and only if nothing bad happens in this handling path.
     */
    public function handle(\Exception $exception)
    {
        IssueLog::Error('Portal: '.$exception->getMessage());

        parent::handle($exception);
    }

    /**
     * Gets the HTML content associated with the given exception.
     *
     * @param FlattenException $exception A FlattenException instance
     *
     * @return string The content as a string
     */
    public function getContent(FlattenException $exception)
    {
        switch ($exception->getStatusCode()) {
            case 404:
                $title = Dict::S('Error:HTTP:404');
                break;
            default:
                $title = Dict::S('Error:HTTP:500');
        }

        $content = '';
        if ($this->debug) {
            try {
                $count = count($exception->getAllPrevious());
                $total = $count + 1;
                foreach ($exception->toArray() as $position => $e) {
                    $ind = $count - $position + 1;
                    $class = $this->formatClass($e['class']);
                    $message = nl2br($this->escapeHtml($e['message']));
                    $content .= sprintf(<<<EOF
                        <h2 class="block_exception clear_fix">
                            <span class="exception_counter">%d/%d</span>
                            <span class="exception_title">%s%s:</span>
                            <span class="exception_message">%s</span>
                        </h2>
                        <div class="block">
                            <ol class="traces list_exception">

EOF
                        , $ind, $total, $class, $this->formatPath($e['trace'][0]['file'], $e['trace'][0]['line']), $message);
                    foreach ($e['trace'] as $trace) {
                        $content .= '       <li>';
                        if ($trace['function']) {
                            $content .= sprintf('at %s%s%s(%s)', $this->formatClass($trace['class']), $trace['type'], $trace['function'], $this->formatArgs($trace['args']));
                        }
                        if (isset($trace['file']) && isset($trace['line'])) {
                            $content .= $this->formatPath($trace['file'], $trace['line']);
                        }
                        $content .= "</li>\n";
                    }

                    $content .= "    </ol>\n</div>\n";
                }
            } catch (\Exception $e) {
                // something nasty happened and we cannot throw an exception anymore
                if ($this->debug) {
                    $title = sprintf('Exception thrown when handling an exception (%s: %s)', get_class($e), $this->escapeHtml($e->getMessage()));
                } else {
                    $title = 'Whoops, looks like something went wrong.';
                }
            }
        }
        else{
            $content = $exception->getMessage();
        }

        return <<<EOF
            <div id="sf-resetcontent" class="sf-reset">
                <h1>$title</h1>
                <div class="content">$content</div>
            </div>
EOF;
    }

    /**
     * Gets the stylesheet associated with the given exception.
     *
     * @param FlattenException $exception A FlattenException instance
     *
     * @return string The stylesheet as a string
     */
    public function getStylesheet(FlattenException $exception)
    {
        $parentStylesheet = parent::getStylesheet($exception);
        $stylesheet = <<<EOF
.sf-reset .content{ background-color: #FFFFFF; padding: 15px 28px; margin-bottom: 20px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; border: 1px solid #ccc; font-size: 13px; }
EOF;

        return $stylesheet . $parentStylesheet;
    }
}