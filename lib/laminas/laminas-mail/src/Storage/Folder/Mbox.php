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

class Mbox extends Storage\Mbox implements FolderInterface
{
    /**
     * Storage\Folder root folder for folder structure
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
     * Create instance with parameters
     *
     * Disallowed parameters are:
     * - filename use \Laminas\Mail\Storage\Mbox for a single file
     *
     * Supported parameters are:
     *
     * - dirname rootdir of mbox structure
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

        if (isset($params->filename)) {
            throw new Exception\InvalidArgumentException(sprintf('use %s for a single file', Storage\Mbox::class));
        }

        if (! isset($params->dirname) || ! is_dir($params->dirname)) {
            throw new Exception\InvalidArgumentException('no valid dirname given in params');
        }

        $this->rootdir = rtrim($params->dirname, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $this->buildFolderTree($this->rootdir);
        $this->selectFolder(! empty($params->folder) ? $params->folder : 'INBOX');
        $this->has['top']      = true;
        $this->has['uniqueid'] = false;
    }

    /**
     * find all subfolders and mbox files for folder structure
     *
     * Result is save in Storage\Folder instances with the root in $this->rootFolder.
     * $parentFolder and $parentGlobalName are only used internally for recursion.
     *
     * @param string $currentDir call with root dir, also used for recursion.
     * @param Storage\Folder|null $parentFolder used for recursion
     * @param string $parentGlobalName used for recursion
     * @throws Exception\InvalidArgumentException
     */
    protected function buildFolderTree($currentDir, $parentFolder = null, $parentGlobalName = '')
    {
        if (! $parentFolder) {
            $this->rootFolder = new Storage\Folder('/', '/', false);
            $parentFolder = $this->rootFolder;
        }

        ErrorHandler::start(E_WARNING);
        $dh = opendir($currentDir);
        ErrorHandler::stop();
        if (! $dh) {
            throw new Exception\InvalidArgumentException("can't read dir $currentDir");
        }
        while (($entry = readdir($dh)) !== false) {
            // ignore hidden files for mbox
            if ($entry[0] == '.') {
                continue;
            }
            $absoluteEntry = $currentDir . $entry;
            $globalName = $parentGlobalName . DIRECTORY_SEPARATOR . $entry;
            if (is_file($absoluteEntry) && $this->isMboxFile($absoluteEntry)) {
                $parentFolder->$entry = new Storage\Folder($entry, $globalName);
                continue;
            }
            if (! is_dir($absoluteEntry) /* || $entry == '.' || $entry == '..' */) {
                continue;
            }
            $folder = new Storage\Folder($entry, $globalName, false);
            $parentFolder->$entry = $folder;
            $this->buildFolderTree($absoluteEntry . DIRECTORY_SEPARATOR, $folder, $globalName);
        }

        closedir($dh);
    }

    /**
     * get root folder or given folder
     *
     * @param string $rootFolder get folder structure for given folder, else root
     * @return Storage\Folder root or wanted folder
     * @throws Exception\InvalidArgumentException
     */
    public function getFolders($rootFolder = null)
    {
        if (! $rootFolder) {
            return $this->rootFolder;
        }

        $currentFolder = $this->rootFolder;
        $subname = trim($rootFolder, DIRECTORY_SEPARATOR);
        while ($currentFolder) {
            ErrorHandler::start(E_NOTICE);
            list($entry, $subname) = explode(DIRECTORY_SEPARATOR, $subname, 2);
            ErrorHandler::stop();
            $currentFolder = $currentFolder->$entry;
            if (! $subname) {
                break;
            }
        }

        if ($currentFolder->getGlobalName() != DIRECTORY_SEPARATOR . trim($rootFolder, DIRECTORY_SEPARATOR)) {
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
            $this->openMboxFile($this->rootdir . $folder->getGlobalName());
        } catch (Exception\ExceptionInterface $e) {
            // check what went wrong
            if (! $folder->isSelectable()) {
                throw new Exception\RuntimeException("{$this->currentFolder} is not selectable", 0, $e);
            }
            // seems like file has vanished; rebuilding folder tree - but it's still an exception
            $this->buildFolderTree($this->rootdir);
            throw new Exception\RuntimeException(
                'seems like the mbox file has vanished; I have rebuilt the folder tree; '
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
     * @throws Exception\ExceptionInterface
     */
    public function getCurrentFolder()
    {
        return $this->currentFolder;
    }

    /**
     * magic method for serialize()
     *
     * with this method you can cache the mbox class
     *
     * @return array name of variables
     */
    public function __sleep()
    {
        return array_merge(parent::__sleep(), ['currentFolder', 'rootFolder', 'rootdir']);
    }

    /**
     * magic method for unserialize(), with this method you can cache the mbox class
     */
    public function __wakeup()
    {
        // if cache is stall selectFolder() rebuilds the tree on error
        parent::__wakeup();
    }
}
