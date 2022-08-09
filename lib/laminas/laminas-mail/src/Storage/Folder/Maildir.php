<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mail\Storage\Folder;

use Laminas\Mail\Storage;
use Laminas\Mail\Storage\Exception;
use Laminas\Stdlib\ErrorHandler;

class Maildir extends Storage\Maildir implements FolderInterface
{
    /**
     * root folder for folder structure
     * @var Storage\Folder
     */
    protected $rootFolder;

    /**
     * rootdir of folder structure
     * @var string
     */
    protected $rootdir;

    /**
     * name of current folder
     * @var string
     */
    protected $currentFolder;

    /**
     * delim char for subfolders
     * @var string
     */
    protected $delim;

    /**
     * Create instance with parameters
     *
     * Supported parameters are:
     *
     * - dirname rootdir of maildir structure
     * - delim   delim char for folder structure, default is '.'
     * - folder initial selected folder, default is 'INBOX'
     *
     * @param  $params array mail reader specific parameters
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($params)
    {
        if (is_array($params)) {
            $params = (object) $params;
        }

        if (! isset($params->dirname) || ! is_dir($params->dirname)) {
            throw new Exception\InvalidArgumentException('no valid dirname given in params');
        }

        $this->rootdir = rtrim($params->dirname, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $this->delim = isset($params->delim) ? $params->delim : '.';

        $this->buildFolderTree();
        $this->selectFolder(! empty($params->folder) ? $params->folder : 'INBOX');
        $this->has['top'] = true;
        $this->has['flags'] = true;
    }

    /**
     * find all subfolders and mbox files for folder structure
     *
     * Result is save in Storage\Folder instances with the root in $this->rootFolder.
     * $parentFolder and $parentGlobalName are only used internally for recursion.
     *
     * @throws Exception\RuntimeException
     */
    protected function buildFolderTree()
    {
        $this->rootFolder = new Storage\Folder('/', '/', false);
        $this->rootFolder->INBOX = new Storage\Folder('INBOX', 'INBOX', true);

        ErrorHandler::start(E_WARNING);
        $dh    = opendir($this->rootdir);
        $error = ErrorHandler::stop();
        if (! $dh) {
            throw new Exception\RuntimeException("can't read folders in maildir", 0, $error);
        }
        $dirs = [];

        while (($entry = readdir($dh)) !== false) {
            // maildir++ defines folders must start with .
            if ($entry[0] != '.' || $entry == '.' || $entry == '..') {
                continue;
            }

            if ($this->isMaildir($this->rootdir . $entry)) {
                $dirs[] = $entry;
            }
        }
        closedir($dh);

        sort($dirs);
        $stack = [null];
        $folderStack = [null];
        $parentFolder = $this->rootFolder;
        $parent = '.';

        foreach ($dirs as $dir) {
            do {
                if (strpos($dir, $parent) === 0) {
                    $local = substr($dir, strlen($parent));
                    if (strpos($local, $this->delim) !== false) {
                        throw new Exception\RuntimeException('error while reading maildir');
                    }
                    array_push($stack, $parent);
                    $parent = $dir . $this->delim;
                    $folder = new Storage\Folder($local, substr($dir, 1), true);
                    $parentFolder->$local = $folder;
                    array_push($folderStack, $parentFolder);
                    $parentFolder = $folder;
                    break;
                } elseif ($stack) {
                    $parent = array_pop($stack);
                    $parentFolder = array_pop($folderStack);
                }
            } while ($stack);
            if (! $stack) {
                throw new Exception\RuntimeException('error while reading maildir');
            }
        }
    }

    /**
     * get root folder or given folder
     *
     * @param string $rootFolder get folder structure for given folder, else root
     * @throws \Laminas\Mail\Storage\Exception\InvalidArgumentException
     * @return \Laminas\Mail\Storage\Folder root or wanted folder
     */
    public function getFolders($rootFolder = null)
    {
        if (! $rootFolder || $rootFolder == 'INBOX') {
            return $this->rootFolder;
        }

        // rootdir is same as INBOX in maildir
        if (strpos($rootFolder, 'INBOX' . $this->delim) === 0) {
            $rootFolder = substr($rootFolder, 6);
        }
        $currentFolder = $this->rootFolder;
        $subname = trim($rootFolder, $this->delim);

        while ($currentFolder) {
            ErrorHandler::start(E_NOTICE);
            list($entry, $subname) = explode($this->delim, $subname, 2);
            ErrorHandler::stop();
            $currentFolder = $currentFolder->$entry;
            if (! $subname) {
                break;
            }
        }

        if ($currentFolder->getGlobalName() != rtrim($rootFolder, $this->delim)) {
            throw new Exception\InvalidArgumentException("folder $rootFolder not found");
        }
        return $currentFolder;
    }

    /**
     * select given folder
     *
     * folder must be selectable!
     *
     * @param Storage\Folder|string $globalName global name of folder or
     *     instance for subfolder
     * @throws Exception\RuntimeException
     */
    public function selectFolder($globalName)
    {
        $this->currentFolder = (string) $globalName;

        // getting folder from folder tree for validation
        $folder = $this->getFolders($this->currentFolder);

        try {
            $this->openMaildir($this->rootdir . '.' . $folder->getGlobalName());
        } catch (Exception\ExceptionInterface $e) {
            // check what went wrong
            if (! $folder->isSelectable()) {
                throw new Exception\RuntimeException("{$this->currentFolder} is not selectable", 0, $e);
            }
            // seems like file has vanished; rebuilding folder tree - but it's still an exception
            $this->buildFolderTree();
            throw new Exception\RuntimeException(
                'seems like the maildir has vanished; I have rebuilt the folder tree; '
                . 'search for another folder and try again',
                0,
                $e
            );
        }
    }

    /**
     * get Storage\Folder instance for current folder
     *
     * @return Storage\Folder instance of current folder
     */
    public function getCurrentFolder()
    {
        return $this->currentFolder;
    }
}
