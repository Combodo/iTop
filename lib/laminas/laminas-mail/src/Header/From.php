<?php

namespace Laminas\Mail\Header;

class From extends AbstractAddressList
{
    protected $fieldName = 'From';
    protected static $type = 'from';
}
