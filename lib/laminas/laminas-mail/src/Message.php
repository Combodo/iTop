<?php

namespace Laminas\Mail;

use Laminas\Mail\Header\Bcc;
use Laminas\Mail\Header\Cc;
use Laminas\Mail\Header\ContentType;
use Laminas\Mail\Header\From;
use Laminas\Mail\Header\MimeVersion;
use Laminas\Mail\Header\ReplyTo;
use Laminas\Mail\Header\Sender;
use Laminas\Mail\Header\To;
use Laminas\Mime;
use Traversable;

class Message
{
    /**
     * Content of the message
     *
     * @var string|object|Mime\Message
     */
    protected $body;

    /**
     * @var Headers
     */
    protected $headers;

    /**
     * Message encoding
     *
     * Used to determine whether or not to encode headers; defaults to ASCII.
     *
     * @var string
     */
    protected $encoding = 'ASCII';

    /**
     * Is the message valid?
     *
     * If we don't any From addresses, we're invalid, according to RFC2822.
     *
     * @return bool
     */
    public function isValid()
    {
        $from = $this->getFrom();
        if (! $from instanceof AddressList) {
            return false;
        }
        return (bool) count($from);
    }

    /**
     * Set the message encoding
     *
     * @param  string $encoding
     * @return Message
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        $this->getHeaders()->setEncoding($encoding);
        return $this;
    }

    /**
     * Get the message encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Compose headers
     *
     * @param  Headers $headers
     * @return Message
     */
    public function setHeaders(Headers $headers)
    {
        $this->headers = $headers;
        $headers->setEncoding($this->getEncoding());
        return $this;
    }

    /**
     * Access headers collection
     *
     * Lazy-loads if not already attached.
     *
     * @return Headers
     */
    public function getHeaders()
    {
        if (null === $this->headers) {
            $this->setHeaders(new Headers());
            $date = Header\Date::fromString('Date: ' . date('r'));
            $this->headers->addHeader($date);
        }
        return $this->headers;
    }

    /**
     * Set (overwrite) From addresses
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressList
     * @param  string|null $name
     * @return Message
     */
    public function setFrom($emailOrAddressList, $name = null)
    {
        $this->clearHeaderByName('from');
        return $this->addFrom($emailOrAddressList, $name);
    }

    /**
     * Add a "From" address
     *
     * @param  string|Address|array|AddressList|Traversable $emailOrAddressOrList
     * @param  string|null $name
     * @return Message
     */
    public function addFrom($emailOrAddressOrList, $name = null)
    {
        $addressList = $this->getFrom();
        $this->updateAddressList($addressList, $emailOrAddressOrList, $name, __METHOD__);
        return $this;
    }

    /**
     * Retrieve list of From senders
     *
     * @return AddressList
     */
    public function getFrom()
    {
        return $this->getAddressListFromHeader('from', From::class);
    }

    /**
     * Overwrite the address list in the To recipients
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressList
     * @param  null|string $name
     * @return Message
     */
    public function setTo($emailOrAddressList, $name = null)
    {
        $this->clearHeaderByName('to');
        return $this->addTo($emailOrAddressList, $name);
    }

    /**
     * Add one or more addresses to the To recipients
     *
     * Appends to the list.
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressOrList
     * @param  null|string $name
     * @return Message
     */
    public function addTo($emailOrAddressOrList, $name = null)
    {
        $addressList = $this->getTo();
        $this->updateAddressList($addressList, $emailOrAddressOrList, $name, __METHOD__);
        return $this;
    }

    /**
     * Access the address list of the To header
     *
     * @return AddressList
     */
    public function getTo()
    {
        return $this->getAddressListFromHeader('to', To::class);
    }

    /**
     * Set (overwrite) CC addresses
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressList
     * @param  string|null $name
     * @return Message
     */
    public function setCc($emailOrAddressList, $name = null)
    {
        $this->clearHeaderByName('cc');
        return $this->addCc($emailOrAddressList, $name);
    }

    /**
     * Add a "Cc" address
     *
     * @param  string|Address|array|AddressList|Traversable $emailOrAddressOrList
     * @param  string|null $name
     * @return Message
     */
    public function addCc($emailOrAddressOrList, $name = null)
    {
        $addressList = $this->getCc();
        $this->updateAddressList($addressList, $emailOrAddressOrList, $name, __METHOD__);
        return $this;
    }

    /**
     * Retrieve list of CC recipients
     *
     * @return AddressList
     */
    public function getCc()
    {
        return $this->getAddressListFromHeader('cc', Cc::class);
    }

    /**
     * Set (overwrite) BCC addresses
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressList
     * @param  string|null $name
     * @return Message
     */
    public function setBcc($emailOrAddressList, $name = null)
    {
        $this->clearHeaderByName('bcc');
        return $this->addBcc($emailOrAddressList, $name);
    }

    /**
     * Add a "Bcc" address
     *
     * @param  string|Address|array|AddressList|Traversable $emailOrAddressOrList
     * @param  string|null $name
     * @return Message
     */
    public function addBcc($emailOrAddressOrList, $name = null)
    {
        $addressList = $this->getBcc();
        $this->updateAddressList($addressList, $emailOrAddressOrList, $name, __METHOD__);
        return $this;
    }

    /**
     * Retrieve list of BCC recipients
     *
     * @return AddressList
     */
    public function getBcc()
    {
        return $this->getAddressListFromHeader('bcc', Bcc::class);
    }

    /**
     * Overwrite the address list in the Reply-To recipients
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressList
     * @param  null|string $name
     * @return Message
     */
    public function setReplyTo($emailOrAddressList, $name = null)
    {
        $this->clearHeaderByName('reply-to');
        return $this->addReplyTo($emailOrAddressList, $name);
    }

    /**
     * Add one or more addresses to the Reply-To recipients
     *
     * Appends to the list.
     *
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressOrList
     * @param  null|string $name
     * @return Message
     */
    public function addReplyTo($emailOrAddressOrList, $name = null)
    {
        $addressList = $this->getReplyTo();
        $this->updateAddressList($addressList, $emailOrAddressOrList, $name, __METHOD__);
        return $this;
    }

    /**
     * Access the address list of the Reply-To header
     *
     * @return AddressList
     */
    public function getReplyTo()
    {
        return $this->getAddressListFromHeader('reply-to', ReplyTo::class);
    }

    /**
     * setSender
     *
     * @param mixed $emailOrAddress
     * @param mixed $name
     * @return Message
     */
    public function setSender($emailOrAddress, $name = null)
    {
        /** @var Sender $header */
        $header = $this->getHeaderByName('sender', Sender::class);
        $header->setAddress($emailOrAddress, $name);
        return $this;
    }

    /**
     * Retrieve the sender address, if any
     *
     * @return null|Address\AddressInterface
     */
    public function getSender()
    {
        $headers = $this->getHeaders();
        if (! $headers->has('sender')) {
            return null;
        }

        /** @var Sender $header */
        $header = $this->getHeaderByName('sender', Sender::class);
        return $header->getAddress();
    }

    /**
     * Set the message subject header value
     *
     * @param  string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $headers = $this->getHeaders();
        if (! $headers->has('subject')) {
            $header = new Header\Subject();
            $headers->addHeader($header);
        } else {
            $header = $headers->get('subject');
        }
        $header->setSubject($subject);
        $header->setEncoding($this->getEncoding());
        return $this;
    }

    /**
     * Get the message subject header value
     *
     * @return null|string
     */
    public function getSubject()
    {
        $headers = $this->getHeaders();
        if (! $headers->has('subject')) {
            return;
        }
        $header = $headers->get('subject');
        return $header->getFieldValue();
    }

    /**
     * Set the message body
     *
     * @param  null|string|\Laminas\Mime\Message|object $body
     * @throws Exception\InvalidArgumentException
     * @return Message
     */
    public function setBody($body)
    {
        if (! is_string($body) && $body !== null) {
            if (! is_object($body)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s expects a string or object argument; received "%s"',
                    __METHOD__,
                    gettype($body)
                ));
            }
            if (! $body instanceof Mime\Message) {
                if (! method_exists($body, '__toString')) {
                    throw new Exception\InvalidArgumentException(sprintf(
                        '%s expects object arguments of type %s or implementing __toString();'
                        . ' object of type "%s" received',
                        __METHOD__,
                        Mime\Message::class,
                        get_class($body)
                    ));
                }
            }
        }
        $this->body = $body;

        if (! $this->body instanceof Mime\Message) {
            return $this;
        }

        // Get headers, and set Mime-Version header
        $headers = $this->getHeaders();
        $this->getHeaderByName('mime-version', MimeVersion::class);

        // Multipart content headers
        if ($this->body->isMultiPart()) {
            $mime   = $this->body->getMime();

            /** @var ContentType $header */
            $header = $this->getHeaderByName('content-type', ContentType::class);
            $header->setType('multipart/mixed');
            $header->addParameter('boundary', $mime->boundary());
            return $this;
        }

        // MIME single part headers
        $parts = $this->body->getParts();
        if (! empty($parts)) {
            $part = array_shift($parts);
            $headers->addHeaders($part->getHeadersArray("\r\n"));
        }
        return $this;
    }

    /**
     * Return the currently set message body
     *
     * @return object|string|Mime\Message
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the string-serialized message body text
     *
     * @return string
     */
    public function getBodyText()
    {
        if ($this->body instanceof Mime\Message) {
            return $this->body->generateMessage(Headers::EOL);
        }

        return (string) $this->body;
    }

    /**
     * Retrieve a header by name
     *
     * If not found, instantiates one based on $headerClass.
     *
     * @param  string $headerName
     * @param  string $headerClass
     * @return Header\HeaderInterface|\ArrayIterator header instance or collection of headers
     */
    protected function getHeaderByName($headerName, $headerClass)
    {
        $headers = $this->getHeaders();
        if ($headers->has($headerName)) {
            $header = $headers->get($headerName);
        } else {
            $header = new $headerClass();
            $headers->addHeader($header);
        }
        return $header;
    }

    /**
     * Clear a header by name
     *
     * @param  string $headerName
     */
    protected function clearHeaderByName($headerName)
    {
        $this->getHeaders()->removeHeader($headerName);
    }

    /**
     * Retrieve the AddressList from a named header
     *
     * Used with To, From, Cc, Bcc, and ReplyTo headers. If the header does not
     * exist, instantiates it.
     *
     * @param  string $headerName
     * @param  string $headerClass
     * @throws Exception\DomainException
     * @return AddressList
     */
    protected function getAddressListFromHeader($headerName, $headerClass)
    {
        $header = $this->getHeaderByName($headerName, $headerClass);
        if (! $header instanceof Header\AbstractAddressList) {
            throw new Exception\DomainException(sprintf(
                'Cannot grab address list from header of type "%s"; not an AbstractAddressList implementation',
                get_class($header)
            ));
        }
        return $header->getAddressList();
    }

    /**
     * Update an address list
     *
     * Proxied to this from addFrom, addTo, addCc, addBcc, and addReplyTo.
     *
     * @param  AddressList $addressList
     * @param  string|Address\AddressInterface|array|AddressList|Traversable $emailOrAddressOrList
     * @param  null|string $name
     * @param  string $callingMethod
     * @throws Exception\InvalidArgumentException
     */
    protected function updateAddressList(AddressList $addressList, $emailOrAddressOrList, $name, $callingMethod)
    {
        if ($emailOrAddressOrList instanceof Traversable) {
            foreach ($emailOrAddressOrList as $address) {
                $addressList->add($address);
            }
            return;
        }
        if (is_array($emailOrAddressOrList)) {
            $addressList->addMany($emailOrAddressOrList);
            return;
        }
        if (! is_string($emailOrAddressOrList) && ! $emailOrAddressOrList instanceof Address\AddressInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects a string, AddressInterface, array, AddressList, or Traversable as its first argument;'
                . ' received "%s"',
                $callingMethod,
                (is_object($emailOrAddressOrList) ? get_class($emailOrAddressOrList) : gettype($emailOrAddressOrList))
            ));
        }

        if (is_string($emailOrAddressOrList) && $name === null) {
            $addressList->addFromString($emailOrAddressOrList);
            return;
        }

        $addressList->add($emailOrAddressOrList, $name);
    }

    /**
     * Serialize to string
     *
     * @return string
     */
    public function toString()
    {
        $headers = $this->getHeaders();
        return $headers->toString()
               . Headers::EOL
               . $this->getBodyText();
    }

    /**
     * Instantiate from raw message string
     *
     * @todo   Restore body to Mime\Message
     * @param  string $rawMessage
     * @return Message
     */
    public static function fromString($rawMessage)
    {
        $message = new static();

        /** @var Headers $headers */
        $headers = null;
        $content = null;
        Mime\Decode::splitMessage($rawMessage, $headers, $content, Headers::EOL);
        if ($headers->has('mime-version')) {
            // todo - restore body to mime\message
        }
        $message->setHeaders($headers);
        $message->setBody($content);
        return $message;
    }
}
