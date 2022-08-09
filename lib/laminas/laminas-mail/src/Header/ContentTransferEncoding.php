<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mail\Header;

class ContentTransferEncoding implements HeaderInterface
{
    /**
     * Allowed Content-Transfer-Encoding parameters specified by RFC 1521
     * (reduced set)
     * @var array
     */
    protected static $allowedTransferEncodings = [
        '7bit',
        '8bit',
        'quoted-printable',
        'base64',
        'binary',
        /*
         * not implemented:
         * x-token: 'X-'
         */
    ];

    /**
     * @var string
     */
    protected $transferEncoding;

    /**
     * @var array
     */
    protected $parameters = [];

    public static function fromString($headerLine)
    {
        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);
        $value = HeaderWrap::mimeDecodeValue($value);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'content-transfer-encoding') {
            throw new Exception\InvalidArgumentException('Invalid header line for Content-Transfer-Encoding string');
        }

        $header = new static();
        $header->setTransferEncoding($value);

        return $header;
    }

    public function getFieldName()
    {
        return 'Content-Transfer-Encoding';
    }

    public function getFieldValue($format = HeaderInterface::FORMAT_RAW)
    {
        return $this->transferEncoding;
    }

    public function setEncoding($encoding)
    {
        // Header must be always in US-ASCII
        return $this;
    }

    public function getEncoding()
    {
        return 'ASCII';
    }

    public function toString()
    {
        return 'Content-Transfer-Encoding: ' . $this->getFieldValue();
    }

    /**
     * Set the content transfer encoding
     *
     * @param  string $transferEncoding
     * @throws Exception\InvalidArgumentException
     * @return $this
     */
    public function setTransferEncoding($transferEncoding)
    {
        // Per RFC 1521, the value of the header is not case sensitive
        $transferEncoding = strtolower($transferEncoding);

        if (! in_array($transferEncoding, static::$allowedTransferEncodings)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects one of "'. implode(', ', static::$allowedTransferEncodings) . '"; received "%s"',
                __METHOD__,
                (string) $transferEncoding
            ));
        }
        $this->transferEncoding = $transferEncoding;
        return $this;
    }

    /**
     * Retrieve the content transfer encoding
     *
     * @return string
     */
    public function getTransferEncoding()
    {
        return $this->transferEncoding;
    }
}
