<?php

declare(strict_types=1);

namespace Laminas\Mail;

use ArrayIterator;
use Countable;
use Iterator;
use Laminas\Loader\PluginClassLocator;
use Laminas\Mail\Header\GenericHeader;
use Laminas\Mail\Header\HeaderInterface;
use ReturnTypeWillChange;
use Traversable;

/**
 * Basic mail headers collection functionality
 *
 * Handles aggregation of headers
 */
class Headers implements Countable, Iterator
{
    /** @var string End of Line for fields */
    public const EOL = "\r\n";

    /** @var string Start of Line when folding */
    public const FOLDING = "\r\n ";

    /**
     * @var null|Header\HeaderLocatorInterface
     */
    private $headerLocator;

    /**
     * @todo Remove for 3.0.0.
     * @var null|PluginClassLocator
     */
    protected $pluginClassLoader;

    /**
     * @var array key names for $headers array
     */
    protected $headersKeys = [];

    /**
     * @var  Header\HeaderInterface[] instances
     */
    protected $headers = [];

    /**
     * Header encoding; defaults to ASCII
     *
     * @var string
     */
    protected $encoding = 'ASCII';

    /**
     * Populates headers from string representation
     *
     * Parses a string for headers, and aggregates them, in order, in the
     * current instance, primarily as strings until they are needed (they
     * will be lazy loaded)
     *
     * @param  string $string
     * @param  string $EOL EOL string; defaults to {@link EOL}
     * @throws Exception\RuntimeException
     * @return Headers
     */
    public static function fromString($string, $EOL = self::EOL)
    {
        $headers     = new static();
        $currentLine = '';
        $emptyLine   = 0;

        // iterate the header lines, some might be continuations
        $lines = explode($EOL, $string);
        $total = count($lines);
        for ($i = 0; $i < $total; $i += 1) {
            $line = $lines[$i];

            if ($line === "") {
                // Empty line indicates end of headers
                // EXCEPT if there are more lines, in which case, there's a possible error condition
                $emptyLine += 1;
                if ($emptyLine > 2) {
                    throw new Exception\RuntimeException('Malformed header detected');
                }
                continue;
            } elseif (preg_match('/^\s*$/', $line)) {
                // skip empty continuation line
                continue;
            }

            if ($emptyLine > 1) {
                throw new Exception\RuntimeException('Malformed header detected');
            }

            // check if a header name is present
            if (preg_match('/^[\x21-\x39\x3B-\x7E]+:.*$/', $line)) {
                if ($currentLine) {
                    // a header name was present, then store the current complete line
                    $headers->addHeaderLine($currentLine);
                }
                $currentLine = trim($line);
                continue;
            }

            // continuation: append to current line
            // recover the whitespace that break the line (unfolding, rfc2822#section-2.2.3)
            if (preg_match('/^\s+.*$/', $line)) {
                $currentLine .= ' ' . trim($line);
                continue;
            }

            // Line does not match header format!
            throw new Exception\RuntimeException(sprintf(
                'Line "%s" does not match header format!',
                $line
            ));
        }
        if ($currentLine) {
            $headers->addHeaderLine($currentLine);
        }
        return $headers;
    }

    /**
     * Set an alternate PluginClassLocator implementation for loading header classes.
     *
     * @deprecated since 2.12.0
     * @todo Remove for version 3.0.0
     * @return $this
     */
    public function setPluginClassLoader(PluginClassLocator $pluginClassLoader)
    {
        // Silenced; can be caught in custom error handlers.
        @trigger_error(sprintf(
            'Since laminas/laminas-mail 2.12.0: Usage of %s is deprecated; use %s::setHeaderLocator() instead',
            __METHOD__,
            __CLASS__
        ), E_USER_DEPRECATED);

        $this->pluginClassLoader = $pluginClassLoader;
        return $this;
    }

    /**
     * Return a PluginClassLocator instance for customizing headers.
     *
     * Lazyloads a Header\HeaderLoader if necessary.
     *
     * @deprecated since 2.12.0
     * @todo Remove for version 3.0.0
     * @return PluginClassLocator
     */
    public function getPluginClassLoader()
    {
        // Silenced; can be caught in custom error handlers.
        @trigger_error(sprintf(
            'Since laminas/laminas-mail 2.12.0: Usage of %s is deprecated; use %s::getHeaderLocator() instead',
            __METHOD__,
            __CLASS__
        ), E_USER_DEPRECATED);

        if (! $this->pluginClassLoader) {
            $this->pluginClassLoader = new Header\HeaderLoader();
        }

        return $this->pluginClassLoader;
    }

    /**
     * Retrieve the header class locator for customizing headers.
     *
     * Lazyloads a Header\HeaderLocator instance if necessary.
     */
    public function getHeaderLocator(): Header\HeaderLocatorInterface
    {
        if (! $this->headerLocator) {
            $this->setHeaderLocator(new Header\HeaderLocator());
        }
        return $this->headerLocator;
    }

    /**
     * @todo Return self when we update to 7.4 or later as minimum PHP version.
     * @return $this
     */
    public function setHeaderLocator(Header\HeaderLocatorInterface $headerLocator)
    {
        $this->headerLocator = $headerLocator;
        return $this;
    }

    /**
     * Set the header encoding
     *
     * @param  string $encoding
     * @return Headers
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        foreach ($this as $header) {
            $header->setEncoding($encoding);
        }
        return $this;
    }

    /**
     * Get the header encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Add many headers at once
     *
     * Expects an array (or Traversable object) of type/value pairs.
     *
     * @param  array|Traversable $headers
     * @throws Exception\InvalidArgumentException
     * @return Headers
     */
    public function addHeaders($headers)
    {
        if (! is_array($headers) && ! $headers instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected array or Traversable; received "%s"',
                (is_object($headers) ? get_class($headers) : gettype($headers))
            ));
        }

        foreach ($headers as $name => $value) {
            if (is_int($name)) {
                if (is_string($value)) {
                    $this->addHeaderLine($value);
                } elseif (is_array($value) && count($value) == 1) {
                    $this->addHeaderLine(key($value), current($value));
                } elseif (is_array($value) && count($value) == 2) {
                    $this->addHeaderLine($value[0], $value[1]);
                } elseif ($value instanceof Header\HeaderInterface) {
                    $this->addHeader($value);
                }
            } elseif (is_string($name)) {
                $this->addHeaderLine($name, $value);
            }
        }

        return $this;
    }

    /**
     * Add a raw header line, either in name => value, or as a single string 'name: value'
     *
     * This method allows for lazy-loading in that the parsing and instantiation of HeaderInterface object
     * will be delayed until they are retrieved by either get() or current()
     *
     * @throws Exception\InvalidArgumentException
     * @param  string $headerFieldNameOrLine
     * @param  string $fieldValue optional
     * @return Headers
     */
    public function addHeaderLine($headerFieldNameOrLine, $fieldValue = null)
    {
        if (! is_string($headerFieldNameOrLine)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects its first argument to be a string; received "%s"',
                __METHOD__,
                (is_object($headerFieldNameOrLine)
                ? get_class($headerFieldNameOrLine)
                : gettype($headerFieldNameOrLine))
            ));
        }

        if ($fieldValue === null) {
            $headers = $this->loadHeader($headerFieldNameOrLine);
            $headers = is_array($headers) ? $headers : [$headers];
            foreach ($headers as $header) {
                $this->addHeader($header);
            }
        } elseif (is_array($fieldValue)) {
            foreach ($fieldValue as $i) {
                $this->addHeader(Header\GenericMultiHeader::fromString($headerFieldNameOrLine . ':' . $i));
            }
        } else {
            $this->addHeader(Header\GenericHeader::fromString($headerFieldNameOrLine . ':' . $fieldValue));
        }

        return $this;
    }

    /**
     * Add a Header\Interface to this container, for raw values see {@link addHeaderLine()} and {@link addHeaders()}
     *
     * @param  Header\HeaderInterface $header
     * @return Headers
     */
    public function addHeader(Header\HeaderInterface $header)
    {
        $key = $this->normalizeFieldName($header->getFieldName());
        $this->headersKeys[] = $key;
        $this->headers[] = $header;
        if ($this->getEncoding() !== 'ASCII') {
            $header->setEncoding($this->getEncoding());
        }
        return $this;
    }

    /**
     * Remove a Header from the container
     *
     * @param  string|Header\HeaderInterface field name or specific header instance to remove
     * @return bool
     */
    public function removeHeader($instanceOrFieldName)
    {
        if (! $instanceOrFieldName instanceof Header\HeaderInterface && ! is_string($instanceOrFieldName)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires a string or %s instance; received %s',
                __METHOD__,
                Header\HeaderInterface::class,
                is_object($instanceOrFieldName) ? get_class($instanceOrFieldName) : gettype($instanceOrFieldName)
            ));
        }

        if ($instanceOrFieldName instanceof Header\HeaderInterface) {
            $indexes = array_keys($this->headers, $instanceOrFieldName, true);
        }

        if (is_string($instanceOrFieldName)) {
            $key = $this->normalizeFieldName($instanceOrFieldName);
            $indexes = array_keys($this->headersKeys, $key, true);
        }

        if (! empty($indexes)) {
            foreach ($indexes as $index) {
                unset($this->headersKeys[$index]);
                unset($this->headers[$index]);
            }
            return true;
        }

        return false;
    }

    /**
     * Clear all headers
     *
     * Removes all headers from queue
     *
     * @return Headers
     */
    public function clearHeaders()
    {
        $this->headers = $this->headersKeys = [];
        return $this;
    }

    /**
     * Get all headers of a certain name/type
     *
     * @param  string $name
     * @return bool|ArrayIterator|Header\HeaderInterface Returns false if there is no headers with $name in this
     * contain, an ArrayIterator if the header is a MultipleHeadersInterface instance and finally returns
     * HeaderInterface for the rest of cases.
     */
    public function get($name)
    {
        $key = $this->normalizeFieldName($name);
        $results = [];

        foreach (array_keys($this->headersKeys, $key, true) as $index) {
            if ($this->headers[$index] instanceof Header\GenericHeader) {
                $results[] = $this->lazyLoadHeader($index);
            } else {
                $results[] = $this->headers[$index];
            }
        }

        switch (count($results)) {
            case 0:
                return false;
            case 1:
                if ($results[0] instanceof Header\MultipleHeadersInterface) {
                    return new ArrayIterator($results);
                }
                return $results[0];
            default:
                return new ArrayIterator($results);
        }
    }

    /**
     * Test for existence of a type of header
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        $name = $this->normalizeFieldName($name);
        return in_array($name, $this->headersKeys, true);
    }

    /**
     * Advance the pointer for this object as an iterator
     *
     */
    #[ReturnTypeWillChange]
    public function next()
    {
        next($this->headers);
    }

    /**
     * Return the current key for this object as an iterator
     *
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function key()
    {
        return key($this->headers);
    }

    /**
     * Is this iterator still valid?
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function valid()
    {
        return (current($this->headers) !== false);
    }

    /**
     * Reset the internal pointer for this object as an iterator
     *
     */
    #[ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->headers);
    }

    /**
     * Return the current value for this iterator, lazy loading it if need be
     *
     * @return Header\HeaderInterface
     */
    #[ReturnTypeWillChange]
    public function current()
    {
        $current = current($this->headers);
        if ($current instanceof Header\GenericHeader) {
            $current = $this->lazyLoadHeader(key($this->headers));
        }
        return $current;
    }

    /**
     * Return the number of headers in this contain, if all headers have not been parsed, actual count could
     * increase if MultipleHeader objects exist in the Request/Response.  If you need an exact count, iterate
     *
     * @return int count of currently known headers
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return count($this->headers);
    }

    /**
     * Render all headers at once
     *
     * This method handles the normal iteration of headers; it is up to the
     * concrete classes to prepend with the appropriate status/request line.
     *
     * @return string
     */
    public function toString()
    {
        $headers = '';
        foreach ($this as $header) {
            if ($str = $header->toString()) {
                $headers .= $str . self::EOL;
            }
        }

        return $headers;
    }

    /**
     * Return the headers container as an array
     *
     * @param  bool $format Return the values in Mime::Encoded or in Raw format
     * @return array
     * @todo determine how to produce single line headers, if they are supported
     */
    public function toArray($format = Header\HeaderInterface::FORMAT_RAW)
    {
        $headers = [];
        /* @var $header Header\HeaderInterface */
        foreach ($this->headers as $header) {
            if ($header instanceof Header\MultipleHeadersInterface) {
                $name = $header->getFieldName();
                if (! isset($headers[$name])) {
                    $headers[$name] = [];
                }
                $headers[$name][] = $header->getFieldValue($format);
            } else {
                $headers[$header->getFieldName()] = $header->getFieldValue($format);
            }
        }
        return $headers;
    }

    /**
     * By calling this, it will force parsing and loading of all headers, after this count() will be accurate
     *
     * @return bool
     */
    public function forceLoading()
    {
        foreach ($this as $item) {
            // $item should now be loaded
        }
        return true;
    }

    /**
     * Create Header object from header line
     *
     * @param string $headerLine
     * @return Header\HeaderInterface|Header\HeaderInterface[]
     */
    public function loadHeader($headerLine)
    {
        list($name) = Header\GenericHeader::splitHeaderLine($headerLine);

        /** @var HeaderInterface $class */
        $class = $this->resolveHeaderClass($name);
        return $class::fromString($headerLine);
    }

    /**
     * @param $index
     * @return mixed
     */
    protected function lazyLoadHeader($index)
    {
        $current = $this->headers[$index];

        $key   = $this->headersKeys[$index];

        /** @var GenericHeader $class */
        $class = $this->resolveHeaderClass($key);

        $encoding = $current->getEncoding();
        $headers  = $class::fromString($current->toString());
        if (is_array($headers)) {
            $current = array_shift($headers);
            $current->setEncoding($encoding);
            $this->headers[$index] = $current;
            foreach ($headers as $header) {
                $header->setEncoding($encoding);
                $this->headersKeys[] = $key;
                $this->headers[]     = $header;
            }
            return $current;
        }

        $current = $headers;
        $current->setEncoding($encoding);
        $this->headers[$index] = $current;
        return $current;
    }

    /**
     * Normalize a field name
     *
     * @param  string $fieldName
     * @return string
     */
    protected function normalizeFieldName($fieldName)
    {
        return str_replace(['-', '_', ' ', '.'], '', strtolower($fieldName));
    }

    /**
     * @param string $key
     * @return string
     */
    private function resolveHeaderClass($key)
    {
        if ($this->pluginClassLoader) {
            return $this->pluginClassLoader->load($key) ?: Header\GenericHeader::class;
        }
        return $this->getHeaderLocator()->get($key, Header\GenericHeader::class);
    }
}
