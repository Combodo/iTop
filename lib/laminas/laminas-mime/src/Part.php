<?php

/**
 * @see       https://github.com/laminas/laminas-mime for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mime/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mime/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mime;

/**
 * Class representing a MIME part.
 */
class Part
{
    public $type = Mime::TYPE_OCTETSTREAM;
    public $encoding = Mime::ENCODING_8BIT;
    public $id;
    public $disposition;
    public $filename;
    public $description;
    public $charset;
    public $boundary;
    public $location;
    public $language;
    protected $content;
    protected $isStream = false;
    protected $filters = [];

    /**
     * create a new Mime Part.
     * The (unencoded) content of the Part as passed
     * as a string or stream
     *
     * @param mixed $content  String or Stream containing the content
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($content = '')
    {
        $this->setContent($content);
    }

    /**
     * @todo error checking for setting $type
     * @todo error checking for setting $encoding
     */

    /**
     * Set type
     * @param string $type
     * @return self
     */
    public function setType($type = Mime::TYPE_OCTETSTREAM)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set encoding
     * @param string $encoding
     * @return self
     */
    public function setEncoding($encoding = Mime::ENCODING_8BIT)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Get encoding
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set id
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set disposition
     * @param string $disposition
     * @return self
     */
    public function setDisposition($disposition)
    {
        $this->disposition = $disposition;
        return $this;
    }

    /**
     * Get disposition
     * @return string
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * Set description
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set filename
     * @param string $fileName
     * @return self
     */
    public function setFileName($fileName)
    {
        $this->filename = $fileName;
        return $this;
    }

    /**
     * Get filename
     * @return string
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * Set charset
     * @param string $type
     * @return self
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * Get charset
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set boundary
     * @param string $boundary
     * @return self
     */
    public function setBoundary($boundary)
    {
        $this->boundary = $boundary;
        return $this;
    }

    /**
     * Get boundary
     * @return string
     */
    public function getBoundary()
    {
        return $this->boundary;
    }

    /**
     * Set location
     * @param string $location
     * @return self
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get location
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set language
     * @param string $language
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set content
     * @param mixed $content  String or Stream containing the content
     * @throws Exception\InvalidArgumentException
     * @return self
     */
    public function setContent($content)
    {
        if (! is_string($content) && ! is_resource($content)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Content must be string or resource; received "%s"',
                is_object($content) ? get_class($content) : gettype($content)
            ));
        }
        $this->content = $content;
        if (is_resource($content)) {
            $this->isStream = true;
        }

        return $this;
    }

    /**
     * Set isStream
     * @param bool $isStream
     * @return self
     */
    public function setIsStream($isStream = false)
    {
        $this->isStream = (bool) $isStream;
        return $this;
    }

    /**
     * Get isStream
     * @return bool
     */
    public function getIsStream()
    {
        return $this->isStream;
    }

    /**
     * Set filters
     * @param array $filters
     * @return self
     */
    public function setFilters($filters = [])
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Get Filters
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * check if this part can be read as a stream.
     * if true, getEncodedStream can be called, otherwise
     * only getContent can be used to fetch the encoded
     * content of the part
     *
     * @return bool
     */
    public function isStream()
    {
        return $this->isStream;
    }

    /**
     * if this was created with a stream, return a filtered stream for
     * reading the content. very useful for large file attachments.
     *
     * @param string $EOL
     * @return resource
     * @throws Exception\RuntimeException if not a stream or unable to append filter
     */
    public function getEncodedStream($EOL = Mime::LINEEND)
    {
        if (! $this->isStream) {
            throw new Exception\RuntimeException('Attempt to get a stream from a string part');
        }

        //stream_filter_remove(); // ??? is that right?
        switch ($this->encoding) {
            case Mime::ENCODING_QUOTEDPRINTABLE:
                if (array_key_exists(Mime::ENCODING_QUOTEDPRINTABLE, $this->filters)) {
                    stream_filter_remove($this->filters[Mime::ENCODING_QUOTEDPRINTABLE]);
                }
                $filter = stream_filter_append(
                    $this->content,
                    'convert.quoted-printable-encode',
                    STREAM_FILTER_READ,
                    [
                        'line-length'      => 76,
                        'line-break-chars' => $EOL
                    ]
                );
                $this->filters[Mime::ENCODING_QUOTEDPRINTABLE] = $filter;
                if (! is_resource($filter)) {
                    throw new Exception\RuntimeException('Failed to append quoted-printable filter');
                }
                break;
            case Mime::ENCODING_BASE64:
                if (array_key_exists(Mime::ENCODING_BASE64, $this->filters)) {
                    stream_filter_remove($this->filters[Mime::ENCODING_BASE64]);
                }
                $filter = stream_filter_append(
                    $this->content,
                    'convert.base64-encode',
                    STREAM_FILTER_READ,
                    [
                        'line-length'      => 76,
                        'line-break-chars' => $EOL
                    ]
                );
                $this->filters[Mime::ENCODING_BASE64] = $filter;
                if (! is_resource($filter)) {
                    throw new Exception\RuntimeException('Failed to append base64 filter');
                }
                break;
            default:
        }
        return $this->content;
    }

    /**
     * Get the Content of the current Mime Part in the given encoding.
     *
     * @param string $EOL
     * @return string
     */
    public function getContent($EOL = Mime::LINEEND)
    {
        if ($this->isStream) {
            $encodedStream         = $this->getEncodedStream($EOL);
            $encodedStreamContents = stream_get_contents($encodedStream);
            $streamMetaData        = stream_get_meta_data($encodedStream);

            if (isset($streamMetaData['seekable']) && $streamMetaData['seekable']) {
                rewind($encodedStream);
            }

            return $encodedStreamContents;
        }
        return Mime::encode($this->content, $this->encoding, $EOL);
    }

    /**
     * Get the RAW unencoded content from this part
     * @return string
     */
    public function getRawContent()
    {
        if ($this->isStream) {
            return stream_get_contents($this->content);
        }
        return $this->content;
    }

    /**
     * Create and return the array of headers for this MIME part
     *
     * @access public
     * @param string $EOL
     * @return array
     */
    public function getHeadersArray($EOL = Mime::LINEEND)
    {
        $headers = [];

        $contentType = $this->type;
        if ($this->charset) {
            $contentType .= '; charset=' . $this->charset;
        }

        if ($this->boundary) {
            $contentType .= ';' . $EOL
                          . " boundary=\"" . $this->boundary . '"';
        }

        $headers[] = ['Content-Type', $contentType];

        if ($this->encoding) {
            $headers[] = ['Content-Transfer-Encoding', $this->encoding];
        }

        if ($this->id) {
            $headers[]  = ['Content-ID', '<' . $this->id . '>'];
        }

        if ($this->disposition) {
            $disposition = $this->disposition;
            if ($this->filename) {
                $disposition .= '; filename="' . $this->filename . '"';
            }
            $headers[] = ['Content-Disposition', $disposition];
        }

        if ($this->description) {
            $headers[] = ['Content-Description', $this->description];
        }

        if ($this->location) {
            $headers[] = ['Content-Location', $this->location];
        }

        if ($this->language) {
            $headers[] = ['Content-Language', $this->language];
        }

        return $headers;
    }

    /**
     * Return the headers for this part as a string
     *
     * @param string $EOL
     * @return String
     */
    public function getHeaders($EOL = Mime::LINEEND)
    {
        $res = '';
        foreach ($this->getHeadersArray($EOL) as $header) {
            $res .= $header[0] . ': ' . $header[1] . $EOL;
        }

        return $res;
    }
}
