<?php

namespace Laminas\Mime;

use function base64_encode;
use function chunk_split;
use function count;
use function implode;
use function max;
use function md5;
use function microtime;
use function ord;
use function preg_match;
use function rtrim;
use function sprintf;
use function str_replace;
use function strcspn;
use function strlen;
use function strpos;
use function strrpos;
use function strtoupper;
use function substr;
use function substr_replace;
use function trim;

/**
 * Support class for MultiPart Mime Messages
 */
class Mime
{
    // phpcs:disable Generic.Files.LineLength.TooLong
    public const TYPE_OCTETSTREAM         = 'application/octet-stream';
    public const TYPE_TEXT                = 'text/plain';
    public const TYPE_HTML                = 'text/html';
    public const TYPE_ENRICHED            = 'text/enriched';
    public const TYPE_XML                 = 'text/xml';
    public const ENCODING_7BIT            = '7bit';
    public const ENCODING_8BIT            = '8bit';
    public const ENCODING_QUOTEDPRINTABLE = 'quoted-printable';
    public const ENCODING_BASE64          = 'base64';
    public const DISPOSITION_ATTACHMENT   = 'attachment';
    public const DISPOSITION_INLINE       = 'inline';
    public const LINELENGTH               = 72;
    public const LINEEND                  = "\n";
    public const MULTIPART_ALTERNATIVE    = 'multipart/alternative';
    public const MULTIPART_MIXED          = 'multipart/mixed';
    public const MULTIPART_RELATED        = 'multipart/related';
    public const MULTIPART_RELATIVE       = 'multipart/relative';
    public const MULTIPART_REPORT         = 'multipart/report';
    public const MESSAGE_RFC822           = 'message/rfc822';
    public const MESSAGE_DELIVERY_STATUS  = 'message/delivery-status';
    public const CHARSET_REGEX            = '#=\?(?P<charset>[\x21\x23-\x26\x2a\x2b\x2d\x5e\5f\60\x7b-\x7ea-zA-Z0-9]+)\?(?P<encoding>[\x21\x23-\x26\x2a\x2b\x2d\x5e\5f\60\x7b-\x7ea-zA-Z0-9]+)\?(?P<text>[\x21-\x3e\x40-\x7e]+)#';
    // phpcs:enable

    /** @var null|string */
    protected $boundary;

    /** @var int */
    protected static $makeUnique = 0;

    /**
     * Lookup-tables for QuotedPrintable
     *
     * @var string[]
     */
    public static $qpKeys = [
        "\x00",
        "\x01",
        "\x02",
        "\x03",
        "\x04",
        "\x05",
        "\x06",
        "\x07",
        "\x08",
        "\x09",
        "\x0A",
        "\x0B",
        "\x0C",
        "\x0D",
        "\x0E",
        "\x0F",
        "\x10",
        "\x11",
        "\x12",
        "\x13",
        "\x14",
        "\x15",
        "\x16",
        "\x17",
        "\x18",
        "\x19",
        "\x1A",
        "\x1B",
        "\x1C",
        "\x1D",
        "\x1E",
        "\x1F",
        "\x7F",
        "\x80",
        "\x81",
        "\x82",
        "\x83",
        "\x84",
        "\x85",
        "\x86",
        "\x87",
        "\x88",
        "\x89",
        "\x8A",
        "\x8B",
        "\x8C",
        "\x8D",
        "\x8E",
        "\x8F",
        "\x90",
        "\x91",
        "\x92",
        "\x93",
        "\x94",
        "\x95",
        "\x96",
        "\x97",
        "\x98",
        "\x99",
        "\x9A",
        "\x9B",
        "\x9C",
        "\x9D",
        "\x9E",
        "\x9F",
        "\xA0",
        "\xA1",
        "\xA2",
        "\xA3",
        "\xA4",
        "\xA5",
        "\xA6",
        "\xA7",
        "\xA8",
        "\xA9",
        "\xAA",
        "\xAB",
        "\xAC",
        "\xAD",
        "\xAE",
        "\xAF",
        "\xB0",
        "\xB1",
        "\xB2",
        "\xB3",
        "\xB4",
        "\xB5",
        "\xB6",
        "\xB7",
        "\xB8",
        "\xB9",
        "\xBA",
        "\xBB",
        "\xBC",
        "\xBD",
        "\xBE",
        "\xBF",
        "\xC0",
        "\xC1",
        "\xC2",
        "\xC3",
        "\xC4",
        "\xC5",
        "\xC6",
        "\xC7",
        "\xC8",
        "\xC9",
        "\xCA",
        "\xCB",
        "\xCC",
        "\xCD",
        "\xCE",
        "\xCF",
        "\xD0",
        "\xD1",
        "\xD2",
        "\xD3",
        "\xD4",
        "\xD5",
        "\xD6",
        "\xD7",
        "\xD8",
        "\xD9",
        "\xDA",
        "\xDB",
        "\xDC",
        "\xDD",
        "\xDE",
        "\xDF",
        "\xE0",
        "\xE1",
        "\xE2",
        "\xE3",
        "\xE4",
        "\xE5",
        "\xE6",
        "\xE7",
        "\xE8",
        "\xE9",
        "\xEA",
        "\xEB",
        "\xEC",
        "\xED",
        "\xEE",
        "\xEF",
        "\xF0",
        "\xF1",
        "\xF2",
        "\xF3",
        "\xF4",
        "\xF5",
        "\xF6",
        "\xF7",
        "\xF8",
        "\xF9",
        "\xFA",
        "\xFB",
        "\xFC",
        "\xFD",
        "\xFE",
        "\xFF",
    ];

    /** @var string[] */
    public static $qpReplaceValues = [
        "=00",
        "=01",
        "=02",
        "=03",
        "=04",
        "=05",
        "=06",
        "=07",
        "=08",
        "=09",
        "=0A",
        "=0B",
        "=0C",
        "=0D",
        "=0E",
        "=0F",
        "=10",
        "=11",
        "=12",
        "=13",
        "=14",
        "=15",
        "=16",
        "=17",
        "=18",
        "=19",
        "=1A",
        "=1B",
        "=1C",
        "=1D",
        "=1E",
        "=1F",
        "=7F",
        "=80",
        "=81",
        "=82",
        "=83",
        "=84",
        "=85",
        "=86",
        "=87",
        "=88",
        "=89",
        "=8A",
        "=8B",
        "=8C",
        "=8D",
        "=8E",
        "=8F",
        "=90",
        "=91",
        "=92",
        "=93",
        "=94",
        "=95",
        "=96",
        "=97",
        "=98",
        "=99",
        "=9A",
        "=9B",
        "=9C",
        "=9D",
        "=9E",
        "=9F",
        "=A0",
        "=A1",
        "=A2",
        "=A3",
        "=A4",
        "=A5",
        "=A6",
        "=A7",
        "=A8",
        "=A9",
        "=AA",
        "=AB",
        "=AC",
        "=AD",
        "=AE",
        "=AF",
        "=B0",
        "=B1",
        "=B2",
        "=B3",
        "=B4",
        "=B5",
        "=B6",
        "=B7",
        "=B8",
        "=B9",
        "=BA",
        "=BB",
        "=BC",
        "=BD",
        "=BE",
        "=BF",
        "=C0",
        "=C1",
        "=C2",
        "=C3",
        "=C4",
        "=C5",
        "=C6",
        "=C7",
        "=C8",
        "=C9",
        "=CA",
        "=CB",
        "=CC",
        "=CD",
        "=CE",
        "=CF",
        "=D0",
        "=D1",
        "=D2",
        "=D3",
        "=D4",
        "=D5",
        "=D6",
        "=D7",
        "=D8",
        "=D9",
        "=DA",
        "=DB",
        "=DC",
        "=DD",
        "=DE",
        "=DF",
        "=E0",
        "=E1",
        "=E2",
        "=E3",
        "=E4",
        "=E5",
        "=E6",
        "=E7",
        "=E8",
        "=E9",
        "=EA",
        "=EB",
        "=EC",
        "=ED",
        "=EE",
        "=EF",
        "=F0",
        "=F1",
        "=F2",
        "=F3",
        "=F4",
        "=F5",
        "=F6",
        "=F7",
        "=F8",
        "=F9",
        "=FA",
        "=FB",
        "=FC",
        "=FD",
        "=FE",
        "=FF",
    ];
    // @codingStandardsIgnoreStart
    public static $qpKeysString =
         "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F\x7F\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F\xA0\xA1\xA2\xA3\xA4\xA5\xA6\xA7\xA8\xA9\xAA\xAB\xAC\xAD\xAE\xAF\xB0\xB1\xB2\xB3\xB4\xB5\xB6\xB7\xB8\xB9\xBA\xBB\xBC\xBD\xBE\xBF\xC0\xC1\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD6\xD7\xD8\xD9\xDA\xDB\xDC\xDD\xDE\xDF\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF7\xF8\xF9\xFA\xFB\xFC\xFD\xFE\xFF";
    // @codingStandardsIgnoreEnd

    /**
     * Check if the given string is "printable"
     *
     * Checks that a string contains no unprintable characters. If this returns
     * false, encode the string for secure delivery.
     *
     * @param string $str
     * @return bool
     */
    public static function isPrintable($str)
    {
        return strcspn($str, static::$qpKeysString) === strlen($str);
    }

    /**
     * Encode a given string with the QUOTED_PRINTABLE mechanism and wrap the lines.
     *
     * @param string $str
     * @param int $lineLength Defaults to {@link LINELENGTH}
     * @param string $lineEnd Defaults to {@link LINEEND}
     * @return string
     */
    public static function encodeQuotedPrintable(
        $str,
        $lineLength = self::LINELENGTH,
        $lineEnd = self::LINEEND
    ) {
        $out = '';
        $str = self::_encodeQuotedPrintable($str);

        // Split encoded text into separate lines
        $initialPtr = 0;
        $strLength  = strlen($str);
        while ($initialPtr < $strLength) {
            $continueAt = $strLength - $initialPtr;

            if ($continueAt > $lineLength) {
                $continueAt = $lineLength;
            }

            $chunk = substr($str, $initialPtr, $continueAt);

            // Ensure we are not splitting across an encoded character
            $endingMarkerPos = strrpos($chunk, '=');
            if ($endingMarkerPos !== false && $endingMarkerPos >= strlen($chunk) - 2) {
                $chunk      = substr($chunk, 0, $endingMarkerPos);
                $continueAt = $endingMarkerPos;
            }

            if (ord($chunk[0]) === 0x2E) { // 0x2E is a dot
                $chunk = '=2E' . substr($chunk, 1);
            }

            // copied from swiftmailer https://git.io/vAXU1
            switch (ord(substr($chunk, strlen($chunk) - 1))) {
                case 0x09: // Horizontal Tab
                    $chunk = substr_replace($chunk, '=09', strlen($chunk) - 1, 1);
                    break;
                case 0x20: // Space
                    $chunk = substr_replace($chunk, '=20', strlen($chunk) - 1, 1);
                    break;
            }

            // Add string and continue
            $out        .= $chunk . '=' . $lineEnd;
            $initialPtr += $continueAt;
        }

        $out = rtrim($out, $lineEnd);
        $out = rtrim($out, '=');
        return $out;
    }

    /**
     * Converts a string into quoted printable format.
     *
     * @param  string $str
     * @return string
     */
    // @codingStandardsIgnoreStart
    private static function _encodeQuotedPrintable($str)
    {
        // @codingStandardsIgnoreEnd
        $str = str_replace('=', '=3D', $str);
        $str = str_replace(static::$qpKeys, static::$qpReplaceValues, $str);
        $str = rtrim($str);
        return $str;
    }

    /**
     * Encode a given string with the QUOTED_PRINTABLE mechanism for Mail Headers.
     *
     * Mail headers depend on an extended quoted printable algorithm otherwise
     * a range of bugs can occur.
     *
     * @param string            $str
     * @param string            $charset
     * @param int               $lineLength       Defaults to {@link LINELENGTH}
     * @param string            $lineEnd          Defaults to {@link LINEEND}
     * @param positive-int|0    $headerNameSize   When folding a line, it is necessary to calculate
     *                                            the length of the entire line (together with the header name).
     *                                            Therefore, you can specify the header name and colon length
     *                                            in this argument to fold the string properly.
     * @return string
     */
    public static function encodeQuotedPrintableHeader(
        $str,
        $charset,
        $lineLength = self::LINELENGTH,
        $lineEnd = self::LINEEND,
        $headerNameSize = 0
    ) {
        // Reduce line-length by the length of the required delimiter, charsets and encoding
        $prefix     = sprintf('=?%s?Q?', $charset);
        $lineLength = $lineLength - strlen($prefix) - 3;

        $str = self::_encodeQuotedPrintable($str);

        // Mail-Header required chars have to be encoded also:
        $str = str_replace(['?', ',', ' ', '_'], ['=3F', '=2C', '=20', '=5F'], $str);

        // initialize first line, we need it anyways
        $lines = [0 => ''];

        // Split encoded text into separate lines
        $tmp = '';
        while (strlen($str) > 0) {
            $currentLine = max(count($lines) - 1, 0);
            $token       = static::getNextQuotedPrintableToken($str);
            $substr      = substr($str, strlen($token));
            $str         = false === $substr ? '' : $substr;

            $tmp .= $token;
            if ($token === '=20') {
                // only if we have a single char token or space, we can append the
                // tempstring it to the current line or start a new line if necessary.
                if ($currentLine === 0) {
                    // The size of the first line should be calculated with the header name.
                    $currentLineLength = strlen($lines[$currentLine] . $tmp) + $headerNameSize;
                } else {
                    $currentLineLength = strlen($lines[$currentLine] . $tmp);
                }

                $lineLimitReached = $currentLineLength > $lineLength;
                $noCurrentLine    = $lines[$currentLine] === '';
                if ($noCurrentLine && $lineLimitReached) {
                    $lines[$currentLine]     = $tmp;
                    $lines[$currentLine + 1] = '';
                } elseif ($lineLimitReached) {
                    $lines[$currentLine + 1] = $tmp;
                } else {
                    $lines[$currentLine] .= $tmp;
                }
                $tmp = '';
            }
            // don't forget to append the rest to the last line
            if (strlen($str) === 0) {
                $lines[$currentLine] .= $tmp;
            }
        }

        // assemble the lines together by pre- and appending delimiters, charset, encoding.
        for ($i = 0, $count = count($lines); $i < $count; $i++) {
            $lines[$i] = " " . $prefix . $lines[$i] . "?=";
        }
        $str = trim(implode($lineEnd, $lines));
        return $str;
    }

    /**
     * Retrieves the first token from a quoted printable string.
     *
     * @param  string $str
     * @return string
     */
    private static function getNextQuotedPrintableToken($str)
    {
        if (0 === strpos($str, '=')) {
            $token = substr($str, 0, 3);
        } else {
            $token = substr($str, 0, 1);
        }
        return $token;
    }

    /**
     * Encode a given string in mail header compatible base64 encoding.
     *
     * @param string $str
     * @param string $charset
     * @param int $lineLength Defaults to {@link LINELENGTH}
     * @param string $lineEnd Defaults to {@link LINEEND}
     * @return string
     */
    public static function encodeBase64Header(
        $str,
        $charset,
        $lineLength = self::LINELENGTH,
        $lineEnd = self::LINEEND
    ) {
        $prefix          = '=?' . $charset . '?B?';
        $suffix          = '?=';
        $remainingLength = $lineLength - strlen($prefix) - strlen($suffix);

        $encodedValue = static::encodeBase64($str, $remainingLength, $lineEnd);
        $encodedValue = str_replace($lineEnd, $suffix . $lineEnd . ' ' . $prefix, $encodedValue);
        $encodedValue = $prefix . $encodedValue . $suffix;
        return $encodedValue;
    }

    /**
     * Encode a given string in base64 encoding and break lines
     * according to the maximum linelength.
     *
     * @param string $str
     * @param int $lineLength Defaults to {@link LINELENGTH}
     * @param string $lineEnd Defaults to {@link LINEEND}
     * @return string
     */
    public static function encodeBase64(
        $str,
        $lineLength = self::LINELENGTH,
        $lineEnd = self::LINEEND
    ) {
        $lineLength = $lineLength - ($lineLength % 4);
        return rtrim(chunk_split(base64_encode($str), $lineLength, $lineEnd));
    }

    /**
     * Constructor
     *
     * @param null|string $boundary
     * @access public
     */
    public function __construct($boundary = null)
    {
        // This string needs to be somewhat unique
        if ($boundary === null) {
            $this->boundary = '=_' . md5(microtime(1) . static::$makeUnique++);
        } else {
            $this->boundary = $boundary;
        }
    }

    // phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName.NotCamelCaps

    /**
     * Encode the given string with the given encoding.
     *
     * @param string $str
     * @param string $encoding
     * @param string $EOL EOL string; defaults to {@link LINEEND}
     * @return string
     */
    public static function encode($str, $encoding, $EOL = self::LINEEND)
    {
        switch ($encoding) {
            case self::ENCODING_BASE64:
                return static::encodeBase64($str, self::LINELENGTH, $EOL);

            case self::ENCODING_QUOTEDPRINTABLE:
                return static::encodeQuotedPrintable($str, self::LINELENGTH, $EOL);

            default:
                /**
                 * @todo 7Bit and 8Bit is currently handled the same way.
                 */
                return $str;
        }
    }

    /**
     * Return a MIME boundary
     *
     * @access public
     * @return string
     */
    public function boundary()
    {
        return $this->boundary;
    }

    /**
     * Return a MIME boundary line
     *
     * @param string $EOL Defaults to {@link LINEEND}
     * @access public
     * @return string
     */
    public function boundaryLine($EOL = self::LINEEND)
    {
        return $EOL . '--' . $this->boundary . $EOL;
    }

    /**
     * Return MIME ending
     *
     * @param string $EOL Defaults to {@link LINEEND}
     * @access public
     * @return string
     */
    public function mimeEnd($EOL = self::LINEEND)
    {
        return $EOL . '--' . $this->boundary . '--' . $EOL;
    }

    /**
     * Detect MIME charset
     *
     * Extract parts according to https://tools.ietf.org/html/rfc2047#section-2
     *
     * @param string $str
     * @return string
     */
    public static function mimeDetectCharset($str)
    {
        if (preg_match(self::CHARSET_REGEX, $str, $matches)) {
            return strtoupper($matches['charset']);
        }

        return 'ASCII';
    }
}
