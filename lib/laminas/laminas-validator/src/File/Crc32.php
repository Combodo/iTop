<?php

/**
 * @see       https://github.com/laminas/laminas-validator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-validator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-validator/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Validator\File;

/**
 * Validator for the crc32 hash of given files
 */
class Crc32 extends Hash
{
    use FileInformationTrait;

    /**
     * @const string Error constants
     */
    const DOES_NOT_MATCH = 'fileCrc32DoesNotMatch';
    const NOT_DETECTED   = 'fileCrc32NotDetected';
    const NOT_FOUND      = 'fileCrc32NotFound';

    /**
     * @var array Error message templates
     */
    protected $messageTemplates = [
        self::DOES_NOT_MATCH => 'File does not match the given crc32 hashes',
        self::NOT_DETECTED   => 'A crc32 hash could not be evaluated for the given file',
        self::NOT_FOUND      => 'File is not readable or does not exist',
    ];

    /**
     * Options for this validator
     *
     * @var string
     */
    protected $options = [
        'algorithm' => 'crc32',
        'hash'      => null,
    ];

    /**
     * Returns all set crc32 hashes
     *
     * @return array
     */
    public function getCrc32()
    {
        return $this->getHash();
    }

    /**
     * Sets the crc32 hash for one or multiple files
     *
     * @param  string|array $options
     * @return $this Provides a fluent interface
     */
    public function setCrc32($options)
    {
        $this->setHash($options);
        return $this;
    }

    /**
     * Adds the crc32 hash for one or multiple files
     *
     * @param  string|array $options
     * @return $this Provides a fluent interface
     */
    public function addCrc32($options)
    {
        $this->addHash($options);
        return $this;
    }

    /**
     * Returns true if and only if the given file confirms the set hash
     *
     * @param  string|array $value Filename to check for hash
     * @param  array        $file  File data from \Laminas\File\Transfer\Transfer (optional)
     * @return bool
     */
    public function isValid($value, $file = null)
    {
        $fileInfo = $this->getFileInfo($value, $file);

        $this->setValue($fileInfo['filename']);

        // Is file readable ?
        if (empty($fileInfo['file']) || false === is_readable($fileInfo['file'])) {
            $this->error(self::NOT_FOUND);
            return false;
        }

        $hashes   = array_unique(array_keys($this->getHash()));
        $filehash = hash_file('crc32', $fileInfo['file']);
        if ($filehash === false) {
            $this->error(self::NOT_DETECTED);
            return false;
        }

        foreach ($hashes as $hash) {
            if ($filehash === $hash) {
                return true;
            }
        }

        $this->error(self::DOES_NOT_MATCH);
        return false;
    }
}
