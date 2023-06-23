<?php

namespace Laminas\Mail\Header;

interface HeaderInterface
{
    /**
     * Format value in Mime-Encoding (Quoted-Printable). Result is valid US-ASCII string
     *
     * @var bool
     */
    public const FORMAT_ENCODED = true;

    /**
     * Return value in internal encoding which is usually UTF-8
     *
     * @var bool
     */
    public const FORMAT_RAW     = false;

    /**
     * Factory to generate a header object from a string
     *
     * @param string $headerLine
     * @return static
     * @throws Exception\InvalidArgumentException If the header does not match with RFC 2822 definition.
     * @see http://tools.ietf.org/html/rfc2822#section-2.2
     */
    public static function fromString($headerLine);

    /**
     * Retrieve header name
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Retrieve header value
     *
     * @param  bool $format Return the value in Mime::Encoded or in Raw format
     * @return string
     */
    public function getFieldValue($format = self::FORMAT_RAW);

    /**
     * Set header encoding
     *
     * @param  string $encoding
     * @return $this
     */
    public function setEncoding($encoding);

    /**
     * Get header encoding
     *
     * @return string
     */
    public function getEncoding();

    /**
     * Cast to string
     *
     * Returns in form of "NAME: VALUE"
     *
     * @return string
     */
    public function toString();
}
