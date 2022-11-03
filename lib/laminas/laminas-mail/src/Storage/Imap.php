<?php

namespace Laminas\Mail\Storage;

use Laminas\Mail;
use Laminas\Mail\Protocol;

class Imap extends AbstractStorage implements Folder\FolderInterface, Writable\WritableInterface
{
    // TODO: with an internal cache we could optimize this class, or create an extra class with
    // such optimizations. Especially the various fetch calls could be combined to one cache call

    /**
     * protocol handler
     * @var null|Protocol\Imap
     */
    protected $protocol;

    /**
     * name of current folder
     * @var string
     */
    protected $currentFolder = '';

    /**
     * IMAP folder delimiter character
     * @var null|string
     */
    protected $delimiter;

    /**
     * IMAP flags to constants translation
     * @var array
     */
    protected static $knownFlags = [
        '\Passed'   => Mail\Storage::FLAG_PASSED,
        '\Answered' => Mail\Storage::FLAG_ANSWERED,
        '\Seen'     => Mail\Storage::FLAG_SEEN,
        '\Unseen'   => Mail\Storage::FLAG_UNSEEN,
        '\Deleted'  => Mail\Storage::FLAG_DELETED,
        '\Draft'    => Mail\Storage::FLAG_DRAFT,
        '\Flagged'  => Mail\Storage::FLAG_FLAGGED,
    ];

    /**
     * IMAP flags to search criteria
     * @var array
     */
    protected static $searchFlags = [
        '\Recent'   => 'RECENT',
        '\Answered' => 'ANSWERED',
        '\Seen'     => 'SEEN',
        '\Unseen'   => 'UNSEEN',
        '\Deleted'  => 'DELETED',
        '\Draft'    => 'DRAFT',
        '\Flagged'  => 'FLAGGED',
    ];

    /**
     * Count messages all messages in current box
     *
     * @param null $flags
     * @throws Exception\RuntimeException
     * @throws Protocol\Exception\RuntimeException
     * @return int number of messages
     */
    public function countMessages($flags = null)
    {
        if (! $this->currentFolder) {
            throw new Exception\RuntimeException('No selected folder to count');
        }

        if ($flags === null) {
            return count($this->protocol->search(['ALL']));
        }

        $params = [];
        foreach ((array) $flags as $flag) {
            if (isset(static::$searchFlags[$flag])) {
                $params[] = static::$searchFlags[$flag];
            } else {
                $params[] = 'KEYWORD';
                $params[] = $this->protocol->escapeString($flag);
            }
        }
        return count($this->protocol->search($params));
    }

    /**
     * get a list of messages with number and size
     *
     * @param int $id number of message
     * @return int|array size of given message of list with all messages as [num => size]
     * @throws Protocol\Exception\RuntimeException
     */
    public function getSize($id = 0)
    {
        if ($id) {
            return $this->protocol->fetch('RFC822.SIZE', $id);
        }
        return $this->protocol->fetch('RFC822.SIZE', 1, INF);
    }

    /**
     * Fetch a message
     *
     * @param int $id number of message
     * @return Message
     * @throws Protocol\Exception\RuntimeException
     */
    public function getMessage($id)
    {
        $data = $this->protocol->fetch(['FLAGS', 'RFC822.HEADER'], $id);
        $header = $data['RFC822.HEADER'];

        $flags = [];
        foreach ($data['FLAGS'] as $flag) {
            $flags[] = static::$knownFlags[$flag] ?? $flag;
        }

        return new $this->messageClass(['handler' => $this, 'id' => $id, 'headers' => $header, 'flags' => $flags]);
    }

    /*
     * Get raw header of message or part
     *
     * @param  int               $id       number of message
     * @param  null|array|string $part     path to part or null for message header
     * @param  int               $topLines include this many lines with header (after an empty line)
     * @param  int $topLines include this many lines with header (after an empty line)
     * @return string raw header
     * @throws Exception\RuntimeException
     * @throws Protocol\Exception\RuntimeException
     */
    public function getRawHeader($id, $part = null, $topLines = 0)
    {
        if ($part !== null) {
            // TODO: implement
            throw new Exception\RuntimeException('not implemented');
        }

        // TODO: toplines
        return $this->protocol->fetch('RFC822.HEADER', $id);
    }

    /*
     * Get raw content of message or part
     *
     * @param  int               $id   number of message
     * @param  null|array|string $part path to part or null for message content
     * @return string raw content
     * @throws Protocol\Exception\RuntimeException
     * @throws Exception\RuntimeException
     */
    public function getRawContent($id, $part = null)
    {
        if ($part !== null) {
            // TODO: implement
            throw new Exception\RuntimeException('not implemented');
        }

        return $this->protocol->fetch('RFC822.TEXT', $id);
    }

    /**
     * create instance with parameters
     *
     * Supported parameters are
     *
     * - user username
     * - host hostname or ip address of IMAP server [optional, default = 'localhost']
     * - password password for user 'username' [optional, default = '']
     * - port port for IMAP server [optional, default = 110]
     * - ssl 'SSL' or 'TLS' for secure sockets
     * - folder select this folder [optional, default = 'INBOX']
     *
     * @param  array|object|Protocol\Imap $params mail reader specific
     *     parameters or configured Imap protocol object
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @throws Protocol\Exception\RuntimeException
     */
    public function __construct($params)
    {
        $this->has['flags'] = true;

        if ($params instanceof Protocol\Imap) {
            $this->protocol = $params;
            try {
                $this->selectFolder('INBOX');
            } catch (Exception\ExceptionInterface $e) {
                throw new Exception\RuntimeException('cannot select INBOX, is this a valid transport?', 0, $e);
            }
            return;
        }

        $params = ParamsNormalizer::normalizeParams($params);

        if (! isset($params['user'])) {
            throw new Exception\InvalidArgumentException('need at least user in params');
        }

        $host     = $params['host'] ?? 'localhost';
        $password = $params['password'] ?? '';
        $port     = $params['port'] ?? null;
        $ssl      = $params['ssl'] ?? false;
        $folder   = $params['folder'] ?? 'INBOX';

        if (null !== $port) {
            $port = (int) $port;
        }

        if (! is_string($ssl)) {
            $ssl = (bool) $ssl;
        }

        $this->protocol = new Protocol\Imap();

        if (array_key_exists('novalidatecert', $params)) {
            $this->protocol->setNoValidateCert((bool) $params['novalidatecert']);
        }

        $this->protocol->connect((string) $host, $port, $ssl);
        if (! $this->protocol->login((string) $params['user'], (string) $password)) {
            throw new Exception\RuntimeException('cannot login, user or password wrong');
        }
        $this->selectFolder((string) $folder);
    }

    /**
     * Close resource for mail lib.
     *
     * If you need to control, when the resource is closed. Otherwise the
     * destructor would call this.
     */
    public function close()
    {
        $this->currentFolder = '';
        $this->protocol->logout();
    }

    /**
     * Keep the server busy.
     *
     * @throws Exception\RuntimeException
     */
    public function noop()
    {
        if (! $this->protocol->noop()) {
            throw new Exception\RuntimeException('could not do nothing');
        }
    }

    /**
     * Remove a message from server.
     *
     * If you're doing that from a web environment you should be careful and
     * use a uniqueid as parameter if possible to identify the message.
     *
     * @param  int $id number of message
     * @throws Exception\RuntimeException
     */
    public function removeMessage($id)
    {
        if (! $this->protocol->store([Mail\Storage::FLAG_DELETED], $id, null, '+')) {
            throw new Exception\RuntimeException('cannot set deleted flag');
        }
        // TODO: expunge here or at close? we can handle an error here better and are more fail safe
        if (! $this->protocol->expunge()) {
            throw new Exception\RuntimeException('message marked as deleted, but could not expunge');
        }
    }

    /**
     * get unique id for one or all messages
     *
     * if storage does not support unique ids it's the same as the message
     * number.
     *
     * @param int|null $id message number
     * @return array|string message number for given message or all messages as array
     * @throws Protocol\Exception\RuntimeException
     */
    public function getUniqueId($id = null)
    {
        if ($id) {
            return $this->protocol->fetch('UID', $id);
        }

        return $this->protocol->fetch('UID', 1, INF);
    }

    /**
     * get a message number from a unique id
     *
     * I.e. if you have a webmailer that supports deleting messages you should
     * use unique ids as parameter and use this method to translate it to
     * message number right before calling removeMessage()
     *
     * @param string $id unique id
     * @throws Exception\InvalidArgumentException
     * @return int message number
     */
    public function getNumberByUniqueId($id)
    {
        // TODO: use search to find number directly
        $ids = $this->getUniqueId();
        foreach ($ids as $k => $v) {
            if ($v == $id) {
                return $k;
            }
        }

        throw new Exception\InvalidArgumentException('unique id not found');
    }

    /**
     * get root folder or given folder
     *
     * @param  string $rootFolder get folder structure for given folder, else root
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @throws Protocol\Exception\RuntimeException
     * @return Folder root or wanted folder
     */
    public function getFolders($rootFolder = null)
    {
        $folders = $this->protocol->listMailbox((string) $rootFolder);
        if (! $folders) {
            throw new Exception\InvalidArgumentException('folder not found');
        }

        ksort($folders, SORT_STRING);
        $root = new Folder('/', '/', false);
        $stack = [null];
        $folderStack = [null];
        $parentFolder = $root;
        $parent = '';

        foreach ($folders as $globalName => $data) {
            do {
                if (! $parent || strpos($globalName, $parent) === 0) {
                    $pos = strrpos($globalName, $data['delim']);
                    if ($pos === false) {
                        $localName = $globalName;
                    } else {
                        $localName = substr($globalName, $pos + 1);
                    }
                    $selectable = ! $data['flags'] || ! in_array('\\Noselect', $data['flags']);

                    array_push($stack, $parent);
                    $parent = $globalName . $data['delim'];
                    $folder = new Folder($localName, $globalName, $selectable);
                    $parentFolder->$localName = $folder;
                    array_push($folderStack, $parentFolder);
                    $parentFolder = $folder;
                    $this->delimiter = $data['delim'];
                    break;
                } elseif ($stack) {
                    $parent = array_pop($stack);
                    $parentFolder = array_pop($folderStack);
                }
            } while ($stack);
            if (! $stack) {
                throw new Exception\RuntimeException('error while constructing folder tree');
            }
        }

        return $root;
    }

    /**
     * select given folder
     *
     * folder must be selectable!
     *
     * @param  Folder|string $globalName global name of folder or instance for subfolder
     * @throws Exception\RuntimeException
     * @throws Protocol\Exception\RuntimeException
     */
    public function selectFolder($globalName)
    {
        $this->currentFolder = $globalName;
        if (! $this->protocol->select($this->currentFolder)) {
            $this->currentFolder = '';
            throw new Exception\RuntimeException('cannot change folder, maybe it does not exist');
        }
    }

    /**
     * get Folder instance for current folder
     *
     * @return Folder instance of current folder
     */
    public function getCurrentFolder()
    {
        return $this->currentFolder;
    }

    /**
     * create a new folder
     *
     * This method also creates parent folders if necessary. Some mail storages
     * may restrict, which folder may be used as parent or which chars may be
     * used in the folder name
     *
     * @param string $name global name of folder, local name if $parentFolder
     *     is set
     * @param string|Folder $parentFolder parent folder for new folder, else
     *     root folder is parent
     * @throws Exception\RuntimeException
     */
    public function createFolder($name, $parentFolder = null)
    {
        // TODO: we assume / as the hierarchy delim - need to get that from the folder class!
        if ($parentFolder instanceof Folder) {
            $folder = $parentFolder->getGlobalName() . '/' . $name;
        } elseif ($parentFolder !== null) {
            $folder = $parentFolder . '/' . $name;
        } else {
            $folder = $name;
        }

        if (! $this->protocol->create($folder)) {
            throw new Exception\RuntimeException('cannot create folder');
        }
    }

    /**
     * remove a folder
     *
     * @param  string|Folder $name name or instance of folder
     * @throws Exception\RuntimeException
     */
    public function removeFolder($name)
    {
        if ($name instanceof Folder) {
            $name = $name->getGlobalName();
        }

        if (! $this->protocol->delete($name)) {
            throw new Exception\RuntimeException('cannot delete folder');
        }
    }

    /**
     * rename and/or move folder
     *
     * The new name has the same restrictions as in createFolder()
     *
     * @param  string|Folder $oldName name or instance of folder
     * @param  string $newName new global name of folder
     * @throws Exception\RuntimeException
     */
    public function renameFolder($oldName, $newName)
    {
        if ($oldName instanceof Folder) {
            $oldName = $oldName->getGlobalName();
        }

        if (! $this->protocol->rename($oldName, $newName)) {
            throw new Exception\RuntimeException('cannot rename folder');
        }
    }

    /**
     * append a new message to mail storage
     *
     * @param string $message message as string or instance of message class
     * @param null|string|Folder $folder  folder for new message, else current
     *     folder is taken
     * @param null|array $flags set flags for new message, else a default set
     *     is used
     * @throws Exception\RuntimeException
     */
    public function appendMessage($message, $folder = null, $flags = null)
    {
        if ($folder === null) {
            $folder = $this->currentFolder;
        }

        if ($flags === null) {
            $flags = [Mail\Storage::FLAG_SEEN];
        }

        // TODO: handle class instances for $message
        if (! $this->protocol->append($folder, $message, $flags)) {
            throw new Exception\RuntimeException(
                'cannot create message, please check if the folder exists and your flags'
            );
        }
    }

    /**
     * copy an existing message
     *
     * @param int $id number of message
     * @param string|Folder $folder name or instance of target folder
     * @throws Exception\RuntimeException
     */
    public function copyMessage($id, $folder)
    {
        if (! $this->protocol->copy($folder, $id)) {
            throw new Exception\RuntimeException('cannot copy message, does the folder exist?');
        }
    }

    /**
     * move an existing message
     *
     * NOTE: IMAP has no native move command, thus it's emulated with copy and delete
     *
     * @param int $id number of message
     * @param string|Folder $folder name or instance of target folder
     * @throws Exception\RuntimeException
     */
    public function moveMessage($id, $folder)
    {
        $this->copyMessage($id, $folder);
        $this->removeMessage($id);
    }

    /**
     * set flags for message
     *
     * NOTE: this method can't set the recent flag.
     *
     * @param int $id number of message
     * @param array $flags new flags for message
     * @throws Exception\RuntimeException
     */
    public function setFlags($id, $flags)
    {
        if (! $this->protocol->store($flags, $id)) {
            throw new Exception\RuntimeException(
                'cannot set flags, have you tried to set the recent flag or special chars?'
            );
        }
    }

    /**
     * get IMAP delimiter
     *
     * @return string|null
     */
    public function delimiter()
    {
        if (! isset($this->delimiter)) {
            $this->getFolders();
        }
        return $this->delimiter;
    }
}
