<?php

namespace Laminas\Mail\Header;

use Laminas\Mail\Address;
use Laminas\Mail\AddressList;
use Laminas\Mail\Headers;
use Laminas\Mail\Storage\Exception\RuntimeException;
use Throwable;

/**
 * Base class for headers composing address lists (to, from, cc, bcc, reply-to)
 */
abstract class AbstractAddressList implements HeaderInterface
{
    private const IDNA_ERROR_MAP = [
        IDNA_ERROR_EMPTY_LABEL => 'empty label',
        IDNA_ERROR_LABEL_TOO_LONG => 'label too long',
        IDNA_ERROR_DOMAIN_NAME_TOO_LONG => 'domain name too long',
        IDNA_ERROR_LEADING_HYPHEN => 'leading hyphen',
        IDNA_ERROR_TRAILING_HYPHEN => 'trailing hyphen',
        IDNA_ERROR_HYPHEN_3_4 => 'consecutive hyphens',
        IDNA_ERROR_LEADING_COMBINING_MARK => 'leading combining mark',
        IDNA_ERROR_DISALLOWED => 'disallowed',
        IDNA_ERROR_PUNYCODE => 'invalid punycode encoding',
        IDNA_ERROR_LABEL_HAS_DOT => 'has dot',
        IDNA_ERROR_INVALID_ACE_LABEL => 'label not in ASCII encoding',
        IDNA_ERROR_BIDI => 'fails bidirectional criteria',
        IDNA_ERROR_CONTEXTJ => 'one or more characters fail CONTEXTJ rule',
    ];

    /**
     * @var AddressList
     */
    protected $addressList;

    /**
     * @var string Normalized field name
     */
    protected $fieldName;

    /**
     * Header encoding
     *
     * @var string
     */
    protected $encoding = 'ASCII';

    /**
     * @var string lower case field name
     */
    protected static $type;

    public static function fromString($headerLine)
    {
        list($fieldName, $fieldValue) = GenericHeader::splitHeaderLine($headerLine);
        if (strtolower($fieldName) !== static::$type) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid header line for "%s" string',
                __CLASS__
            ));
        }

        // split value on ","
        $fieldValue = str_replace(Headers::FOLDING, ' ', $fieldValue);
        $fieldValue = preg_replace('/[^:]+:([^;]*);/', '$1,', $fieldValue);
        $values = ListParser::parse($fieldValue);

        $wasEncoded = false;
        $addresses = array_map(
            function ($value) use (&$wasEncoded) {
                $decodedValue = HeaderWrap::mimeDecodeValue($value);
                $wasEncoded = $wasEncoded || ($decodedValue !== $value);

                $value = trim($decodedValue);

                $comments = self::getComments($value);
                $value = self::stripComments($value);

                $value = preg_replace(
                    [
                        '#(?<!\\\)"(.*)(?<!\\\)"#',            // quoted-text
                        '#\\\([\x01-\x09\x0b\x0c\x0e-\x7f])#', // quoted-pair
                    ],
                    [
                        '\\1',
                        '\\1',
                    ],
                    $value
                );

                return empty($value) ? null : Address::fromString($value, $comments);
            },
            $values
        );
        $addresses = array_filter($addresses);

        $header = new static();
        if ($wasEncoded) {
            $header->setEncoding('UTF-8');
        }

        /** @var AddressList $addressList */
        $addressList = $header->getAddressList();
        foreach ($addresses as $address) {
            $addressList->add($address);
        }

        return $header;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Safely convert UTF-8 encoded domain name to ASCII
     * @param string $domainName the UTF-8 encoded email
     * @return string
     */
    protected function idnToAscii($domainName): string
    {
        $ascii = idn_to_ascii($domainName, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46, $conversionInfo);
        if (false !== $ascii) {
            return $ascii;
        }

        $messages = [];
        $errors   = (int) $conversionInfo['errors'];

        foreach (self::IDNA_ERROR_MAP as $flag => $message) {
            if (($flag & $errors) === $flag) {
                $messages[] = $message;
            }
        }

        throw new RuntimeException(sprintf(
            'Failed encoding domain due to errors: %s',
            implode(', ', $messages)
        ));
    }

    public function getFieldValue($format = HeaderInterface::FORMAT_RAW)
    {
        $emails   = [];
        $encoding = $this->getEncoding();

        foreach ($this->getAddressList() as $address) {
            $email = $address->getEmail();
            $name  = $address->getName();

            // quote $name if value requires so
            if (! empty($name) && (false !== strpos($name, ',') || false !== strpos($name, ';'))) {
                // FIXME: what if name contains double quote?
                $name = sprintf('"%s"', $name);
            }

            if ($format === HeaderInterface::FORMAT_ENCODED
                && 'ASCII' !== $encoding
            ) {
                if (! empty($name)) {
                    $name = HeaderWrap::mimeEncodeValue($name, $encoding);
                }

                if (preg_match('/^(.+)@([^@]+)$/', $email, $matches)) {
                    $localPart = $matches[1];
                    $hostname  = $this->idnToAscii($matches[2]);
                    $email = sprintf('%s@%s', $localPart, $hostname);
                }
            }

            if (empty($name)) {
                $emails[] = $email;
            } else {
                $emails[] = sprintf('%s <%s>', $name, $email);
            }
        }

        // Ensure the values are valid before sending them.
        if ($format !== HeaderInterface::FORMAT_RAW) {
            foreach ($emails as $email) {
                HeaderValue::assertValid($email);
            }
        }

        return implode(',' . Headers::FOLDING, $emails);
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set address list for this header
     *
     * @param  AddressList $addressList
     */
    public function setAddressList(AddressList $addressList)
    {
        $this->addressList = $addressList;
    }

    /**
     * Get address list managed by this header
     *
     * @return AddressList
     */
    public function getAddressList()
    {
        if (null === $this->addressList) {
            $this->setAddressList(new AddressList());
        }
        return $this->addressList;
    }

    public function toString()
    {
        $name  = $this->getFieldName();
        $value = $this->getFieldValue(HeaderInterface::FORMAT_ENCODED);
        return (empty($value)) ? '' : sprintf('%s: %s', $name, $value);
    }

    /**
     * Retrieve comments from value, if any.
     *
     * Supposed to be private, protected as a workaround for PHP bug 68194
     *
     * @param string $value
     * @return string
     */
    protected static function getComments($value)
    {
        $matches = [];
        preg_match_all(
            '/\\(
                (?P<comment>(
                    \\\\.|
                    [^\\\\)]
                )+)
            \\)/x',
            $value,
            $matches
        );
        return isset($matches['comment']) ? implode(', ', $matches['comment']) : '';
    }

    /**
     * Strip all comments from value, if any.
     *
     * Supposed to be private, protected as a workaround for PHP bug 68194
     *
     * @param string $value
     * @return string
     */
    protected static function stripComments($value)
    {
        return preg_replace(
            '/\\(
                (
                    \\\\.|
                    [^\\\\)]
                )+
            \\)/x',
            '',
            $value
        );
    }
}
