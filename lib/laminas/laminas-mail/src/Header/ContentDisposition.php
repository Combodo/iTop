<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mail\Header;

use Laminas\Mail\Headers;
use Laminas\Mime\Mime;

class ContentDisposition implements UnstructuredInterface
{
    /**
     * 78 chars (RFC 2822) - (semicolon + space (Header::FOLDING))
     *
     * @var int
     */
    const MAX_PARAMETER_LENGTH = 76;

    /**
     * @var string
     */
    protected $disposition = 'inline';

    /**
     * Header encoding
     *
     * @var string
     */
    protected $encoding = 'ASCII';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @inheritDoc
     */
    public static function fromString($headerLine)
    {
        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);
        $value = HeaderWrap::mimeDecodeValue($value);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'content-disposition') {
            throw new Exception\InvalidArgumentException('Invalid header line for Content-Disposition string');
        }

        $value  = str_replace(Headers::FOLDING, ' ', $value);
        $parts = explode(';', $value, 2);

        $header = new static();
        $header->setDisposition($parts[0]);

        if (isset($parts[1])) {
            $values = ListParser::parse(trim($parts[1]), [';', '=']);
            $length = count($values);
            $continuedValues = [];

            for ($i = 0; $i < $length; $i += 2) {
                $value = $values[$i + 1];
                $value = trim($value, "'\" \t\n\r\0\x0B");
                $name = trim($values[$i], "'\" \t\n\r\0\x0B");

                if (strpos($name, '*')) {
                    list($name, $count) = explode('*', $name);
                    if (! isset($continuedValues[$name])) {
                        $continuedValues[$name] = [];
                    }
                    $continuedValues[$name][$count] = $value;
                } else {
                    $header->setParameter($name, $value);
                }
            }

            foreach ($continuedValues as $name => $values) {
                $value = '';
                for ($i = 0; $i < count($values); $i++) {
                    if (! isset($values[$i])) {
                        throw new Exception\InvalidArgumentException(
                            'Invalid header line for Content-Disposition string - incomplete continuation'
                        );
                    }
                    $value .= $values[$i];
                }
                $header->setParameter($name, $value);
            }
        }

        return $header;
    }

    /**
     * @inheritDoc
     */
    public function getFieldName()
    {
        return 'Content-Disposition';
    }

    /**
     * @inheritDoc
     */
    public function getFieldValue($format = HeaderInterface::FORMAT_RAW)
    {
        $result = $this->disposition;
        if (empty($this->parameters)) {
            return $result;
        }

        foreach ($this->parameters as $attribute => $value) {
            $valueIsEncoded = false;
            if (HeaderInterface::FORMAT_ENCODED === $format && ! Mime::isPrintable($value)) {
                $value = $this->getEncodedValue($value);
                $valueIsEncoded = true;
            }

            $line = sprintf('%s="%s"', $attribute, $value);

            if (strlen($line) < self::MAX_PARAMETER_LENGTH) {
                $lines = explode(Headers::FOLDING, $result);

                if (count($lines) === 1) {
                    $existingLineLength = strlen('Content-Disposition: ' . $result);
                } else {
                    $existingLineLength = 1 + strlen($lines[count($lines) - 1]);
                }

                if ((2 + $existingLineLength + strlen($line)) <= self::MAX_PARAMETER_LENGTH) {
                    $result .= '; ' . $line;
                } else {
                    $result .= ';' . Headers::FOLDING . $line;
                }
            } else {
                // Use 'continuation' per RFC 2231
                $maxValueLength = strlen($value);
                do {
                    $maxValueLength = ceil(0.6 * $maxValueLength);
                } while ($maxValueLength > self::MAX_PARAMETER_LENGTH);

                if ($valueIsEncoded) {
                    $encodedLength = strlen($value);
                    $value = HeaderWrap::mimeDecodeValue($value);
                    $decodedLength = strlen($value);
                    $maxValueLength -= ($encodedLength - $decodedLength);
                }

                $valueParts = str_split($value, $maxValueLength);
                $i = 0;
                foreach ($valueParts as $valuePart) {
                    $attributePart = $attribute . '*' . $i++;
                    if ($valueIsEncoded) {
                        $valuePart = $this->getEncodedValue($valuePart);
                    }
                    $result .= sprintf(';%s%s="%s"', Headers::FOLDING, $attributePart, $valuePart);
                }
            }
        }

        return $result;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function getEncodedValue($value)
    {
        $configuredEncoding = $this->encoding;
        $this->encoding = 'UTF-8';
        $value = HeaderWrap::wrap($value, $this);
        $this->encoding = $configuredEncoding;
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return 'Content-Disposition: ' . $this->getFieldValue(HeaderInterface::FORMAT_ENCODED);
    }

    /**
     * Set the content disposition
     * Expected values include 'inline', 'attachment'
     *
     * @param string $disposition
     * @return ContentDisposition
     */
    public function setDisposition($disposition)
    {
        $this->disposition = strtolower($disposition);
        return $this;
    }

    /**
     * Retrieve the content disposition
     *
     * @return string
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * Add a parameter pair
     *
     * @param string $name
     * @param string $value
     * @return ContentDisposition
     */
    public function setParameter($name, $value)
    {
        $name  = strtolower($name);

        if (! HeaderValue::isValid($name)) {
            throw new Exception\InvalidArgumentException(
                'Invalid content-disposition parameter name detected'
            );
        }
        // '5' here is for the quotes & equal sign in `name="value"`,
        // and the space & semicolon for line folding
        if ((strlen($name) + 5) >= self::MAX_PARAMETER_LENGTH) {
            throw new Exception\InvalidArgumentException(
                'Invalid content-disposition parameter name detected (too long)'
            );
        }

        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * Get all parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Get a parameter by name
     *
     * @param string $name
     * @return null|string
     */
    public function getParameter($name)
    {
        $name = strtolower($name);
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return null;
    }

    /**
     * Remove a named parameter
     *
     * @param string $name
     * @return bool
     */
    public function removeParameter($name)
    {
        $name = strtolower($name);
        if (isset($this->parameters[$name])) {
            unset($this->parameters[$name]);
            return true;
        }
        return false;
    }
}
