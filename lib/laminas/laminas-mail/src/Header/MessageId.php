<?php

namespace Laminas\Mail\Header;

class MessageId implements HeaderInterface
{
    /**
     * @var string
     */
    protected $messageId;

    public static function fromString($headerLine)
    {
        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);
        $value = HeaderWrap::mimeDecodeValue($value);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'message-id') {
            throw new Exception\InvalidArgumentException('Invalid header line for Message-ID string');
        }

        $header = new static();
        $header->setId($value);

        return $header;
    }

    public function getFieldName()
    {
        return 'Message-ID';
    }

    public function getFieldValue($format = HeaderInterface::FORMAT_RAW)
    {
        return $this->messageId;
    }

    public function setEncoding($encoding)
    {
        // This header must be always in US-ASCII
        return $this;
    }

    public function getEncoding()
    {
        return 'ASCII';
    }

    public function toString()
    {
        return 'Message-ID: ' . $this->getFieldValue();
    }

    /**
     * Set the message id
     *
     * @param string|null $id
     * @return MessageId
     */
    public function setId($id = null)
    {
        if ($id === null) {
            $id = $this->createMessageId();
        } else {
            $id = trim($id, '<>');
        }

        if (! HeaderValue::isValid($id)
            || preg_match("/[\r\n]/", $id)
        ) {
            throw new Exception\InvalidArgumentException('Invalid ID detected');
        }

        $this->messageId = sprintf('<%s>', $id);
        return $this;
    }

    /**
     * Retrieve the message id
     *
     * @return string
     */
    public function getId()
    {
        return $this->messageId;
    }

    /**
     * Creates the Message-ID
     *
     * @return string
     */
    public function createMessageId()
    {
        $time = time();

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $user = $_SERVER['REMOTE_ADDR'];
        } else {
            $user = getmypid();
        }

        $rand = mt_rand();

        if (isset($_SERVER["SERVER_NAME"])) {
            $hostName = $_SERVER["SERVER_NAME"];
        } else {
            $hostName = php_uname('n');
        }

        return sha1($time . $user . $rand) . '@' . $hostName;
    }
}
