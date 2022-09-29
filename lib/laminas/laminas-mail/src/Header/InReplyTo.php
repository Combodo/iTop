<?php

namespace Laminas\Mail\Header;

class InReplyTo extends IdentificationField
{
    protected $fieldName = 'In-Reply-To';
    protected static $type = 'in-reply-to';
}
