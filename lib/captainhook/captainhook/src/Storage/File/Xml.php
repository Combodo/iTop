<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Storage\File;

use CaptainHook\App\Storage\File;
use RuntimeException;

/**
 * Class Xml
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.2.0
 */
final class Xml extends File
{
    /**
     * Read the xml file and return a SimpleXML object.
     *
     * @return \SimpleXMLElement
     */
    public function read()
    {
        $old    = libxml_use_internal_errors(true);
        $xml    = simplexml_load_file($this->path);
        $errors = libxml_get_errors();
        libxml_use_internal_errors($old);

        if (count($errors) || $xml === false) {
            throw new RuntimeException('xml file \'' . $this->path . '\': ' . $errors[0]->message);
        }
        return $xml;
    }
}
