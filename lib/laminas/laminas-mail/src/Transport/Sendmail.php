<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mail\Transport;

use Laminas\Mail;
use Laminas\Mail\Address\AddressInterface;
use Laminas\Mail\Header\HeaderInterface;
use Traversable;

/**
 * Class for sending email via the PHP internal mail() function
 */
class Sendmail implements TransportInterface
{
    /**
     * Config options for sendmail parameters
     *
     * @var string
     */
    protected $parameters;

    /**
     * Callback to use when sending mail; typically, {@link mailHandler()}
     *
     * @var callable
     */
    protected $callable;

    /**
     * error information
     * @var string
     */
    protected $errstr;

    /**
     * @var string
     */
    protected $operatingSystem;

    /**
     * Constructor.
     *
     * @param  null|string|array|Traversable $parameters OPTIONAL (Default: null)
     */
    public function __construct($parameters = null)
    {
        if ($parameters !== null) {
            $this->setParameters($parameters);
        }
        $this->callable = [$this, 'mailHandler'];
    }

    /**
     * Set sendmail parameters
     *
     * Used to populate the additional_parameters argument to mail()
     *
     * @param  null|string|array|Traversable $parameters
     * @throws \Laminas\Mail\Transport\Exception\InvalidArgumentException
     * @return Sendmail
     */
    public function setParameters($parameters)
    {
        if ($parameters === null || is_string($parameters)) {
            $this->parameters = $parameters;
            return $this;
        }

        if (! is_array($parameters) && ! $parameters instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects a string, array, or Traversable object of parameters; received "%s"',
                __METHOD__,
                (is_object($parameters) ? get_class($parameters) : gettype($parameters))
            ));
        }

        $string = '';
        foreach ($parameters as $param) {
            $string .= ' ' . $param;
        }

        $this->parameters = trim($string);
        return $this;
    }

    /**
     * Set callback to use for mail
     *
     * Primarily for testing purposes, but could be used to curry arguments.
     *
     * @param  callable $callable
     * @throws \Laminas\Mail\Transport\Exception\InvalidArgumentException
     * @return Sendmail
     */
    public function setCallable($callable)
    {
        if (! is_callable($callable)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects a callable argument; received "%s"',
                __METHOD__,
                (is_object($callable) ? get_class($callable) : gettype($callable))
            ));
        }
        $this->callable = $callable;
        return $this;
    }

    /**
     * Send a message
     *
     * @param  \Laminas\Mail\Message $message
     */
    public function send(Mail\Message $message)
    {
        $to      = $this->prepareRecipients($message);
        $subject = $this->prepareSubject($message);
        $body    = $this->prepareBody($message);
        $headers = $this->prepareHeaders($message);
        $params  = $this->prepareParameters($message);

        // On *nix platforms, we need to replace \r\n with \n
        // sendmail is not an SMTP server, it is a unix command - it expects LF
        if (! $this->isWindowsOs()) {
            $to      = str_replace("\r\n", "\n", $to);
            $subject = str_replace("\r\n", "\n", $subject);
            $body    = str_replace("\r\n", "\n", $body);
            $headers = str_replace("\r\n", "\n", $headers);
        }

        call_user_func($this->callable, $to, $subject, $body, $headers, $params);
    }

    /**
     * Prepare recipients list
     *
     * @param  \Laminas\Mail\Message $message
     * @throws \Laminas\Mail\Transport\Exception\RuntimeException
     * @return string
     */
    protected function prepareRecipients(Mail\Message $message)
    {
        $headers = $message->getHeaders();

        $hasTo = $headers->has('to');
        if (! $hasTo && ! $headers->has('cc') && ! $headers->has('bcc')) {
            throw new Exception\RuntimeException(
                'Invalid email; contains no at least one of "To", "Cc", and "Bcc" header'
            );
        }

        if (! $hasTo) {
            return '';
        }

        /** @var Mail\Header\To $to */
        $to   = $headers->get('to');
        $list = $to->getAddressList();
        if (0 == count($list)) {
            throw new Exception\RuntimeException('Invalid "To" header; contains no addresses');
        }

        // If not on Windows, return normal string
        if (! $this->isWindowsOs()) {
            return $to->getFieldValue(HeaderInterface::FORMAT_ENCODED);
        }

        // Otherwise, return list of emails
        $addresses = [];
        foreach ($list as $address) {
            $addresses[] = $address->getEmail();
        }
        $addresses = implode(', ', $addresses);
        return $addresses;
    }

    /**
     * Prepare the subject line string
     *
     * @param  \Laminas\Mail\Message $message
     * @return string
     */
    protected function prepareSubject(Mail\Message $message)
    {
        $headers = $message->getHeaders();
        if (! $headers->has('subject')) {
            return;
        }
        $header = $headers->get('subject');
        return $header->getFieldValue(HeaderInterface::FORMAT_ENCODED);
    }

    /**
     * Prepare the body string
     *
     * @param  \Laminas\Mail\Message $message
     * @return string
     */
    protected function prepareBody(Mail\Message $message)
    {
        if (! $this->isWindowsOs()) {
            // *nix platforms can simply return the body text
            return $message->getBodyText();
        }

        // On windows, lines beginning with a full stop need to be fixed
        $text = $message->getBodyText();
        $text = str_replace("\n.", "\n..", $text);
        return $text;
    }

    /**
     * Prepare the textual representation of headers
     *
     * @param  \Laminas\Mail\Message $message
     * @return string
     */
    protected function prepareHeaders(Mail\Message $message)
    {
        // Strip the "to" and "subject" headers
        $headers = clone $message->getHeaders();
        $headers->removeHeader('To');
        $headers->removeHeader('Subject');

        /** @var Mail\Header\From $from Sanitize the From header*/
        $from = $headers->get('From');
        if ($from) {
            foreach ($from->getAddressList() as $address) {
                if (strpos($address->getEmail(), '\\"') !== false) {
                    throw new Exception\RuntimeException('Potential code injection in From header');
                }
            }
        }
        return $headers->toString();
    }

    /**
     * Prepare additional_parameters argument
     *
     * Basically, overrides the MAIL FROM envelope with either the Sender or
     * From address.
     *
     * @param  \Laminas\Mail\Message $message
     * @return string
     */
    protected function prepareParameters(Mail\Message $message)
    {
        if ($this->isWindowsOs()) {
            return;
        }

        $parameters = (string) $this->parameters;
        if (preg_match('/(^| )\-f.+/', $parameters)) {
            return $parameters;
        }

        $sender = $message->getSender();
        if ($sender instanceof AddressInterface) {
            $parameters .= ' -f' . \escapeshellarg($sender->getEmail());
            return $parameters;
        }

        $from = $message->getFrom();
        if (count($from)) {
            $from->rewind();
            $sender      = $from->current();
            $parameters .= ' -f' . \escapeshellarg($sender->getEmail());
            return $parameters;
        }

        return $parameters;
    }

    /**
     * Send mail using PHP native mail()
     *
     * @param  string $to
     * @param  string $subject
     * @param  string $message
     * @param  string $headers
     * @param  $parameters
     * @throws \Laminas\Mail\Transport\Exception\RuntimeException
     */
    public function mailHandler($to, $subject, $message, $headers, $parameters)
    {
        set_error_handler([$this, 'handleMailErrors']);
        if ($parameters === null) {
            $result = mail($to, $subject, $message, $headers);
        } else {
            $result = mail($to, $subject, $message, $headers, $parameters);
        }
        restore_error_handler();

        if ($this->errstr !== null || ! $result) {
            $errstr = $this->errstr;
            if (empty($errstr)) {
                $errstr = 'Unknown error';
            }
            throw new Exception\RuntimeException('Unable to send mail: ' . $errstr);
        }
    }

    /**
     * Temporary error handler for PHP native mail().
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @param array  $errcontext
     * @return bool always true
     */
    public function handleMailErrors($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null)
    {
        $this->errstr = $errstr;
        return true;
    }

    /**
     * Is this a windows OS?
     *
     * @return bool
     */
    protected function isWindowsOs()
    {
        if (! $this->operatingSystem) {
            $this->operatingSystem = strtoupper(substr(PHP_OS, 0, 3));
        }
        return ($this->operatingSystem == 'WIN');
    }
}
