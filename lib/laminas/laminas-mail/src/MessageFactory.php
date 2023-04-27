<?php

namespace Laminas\Mail;

use Traversable;

class MessageFactory
{
    /**
     * @param array|Traversable $options
     * @return Message
     */
    public static function getInstance($options = [])
    {
        if (! is_array($options) && ! $options instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '"%s" expects an array or Traversable; received "%s"',
                __METHOD__,
                (is_object($options) ? get_class($options) : gettype($options))
            ));
        }

        $message = new Message();

        foreach ($options as $key => $value) {
            $setter = self::getSetterMethod($key);
            if (method_exists($message, $setter)) {
                $message->{$setter}($value);
            }
        }

        return $message;
    }

    /**
     * Generate a setter method name based on a provided key.
     *
     * @param string $key
     * @return string
     */
    private static function getSetterMethod($key)
    {
        return 'set'
            . str_replace(
                ' ',
                '',
                ucwords(
                    strtr(
                        $key,
                        [
                            '-' => ' ',
                            '_' => ' ',
                        ]
                    )
                )
            );
    }
}
