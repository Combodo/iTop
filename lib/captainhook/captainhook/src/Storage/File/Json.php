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
use stdClass;

/**
 * Class Json
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
final class Json extends File
{
    /**
     * Read and decode the json file
     *
     * @param  bool $assoc
     * @return \stdClass|array<string, mixed>|null
     */
    public function read(bool $assoc = false): array|stdClass|null
    {
        $json = json_decode(parent::read(), $assoc);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid json file');
        }
        return $json;
    }

    /**
     * Read the file and decode to assoc array
     *
     * @return array<string, mixed>
     */
    public function readAssoc(): array
    {
        return (array) ($this->read(true) ?? []);
    }

    /**
     * Encode content to json and write to disk
     *
     * @param  mixed $content
     * @param  int   $options
     * @return void
     */
    public function write($content, $options = 448): void
    {
        $json = json_encode($content, $options) . ($options & JSON_PRETTY_PRINT ? "\n" : '');
        parent::write($json);
    }
}
