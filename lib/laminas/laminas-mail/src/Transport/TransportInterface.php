<?php

namespace Laminas\Mail\Transport;

use Laminas\Mail;

/**
 * Interface for mail transports
 */
interface TransportInterface
{
    /**
     * Send a mail message
     *
     * @param \Laminas\Mail\Message $message
     * @return
     */
    public function send(Mail\Message $message);
}
