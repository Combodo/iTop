<?php

/**
 * @see       https://github.com/laminas/laminas-mail for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mail/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mail/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mail;

use Countable;
use Iterator;

class AddressList implements Countable, Iterator
{
    /**
     * List of Address objects we're managing
     *
     * @var array
     */
    protected $addresses = [];

    /**
     * Add an address to the list
     *
     * @param  string|Address\AddressInterface $emailOrAddress
     * @param  null|string $name
     * @throws Exception\InvalidArgumentException
     * @return AddressList
     */
    public function add($emailOrAddress, $name = null)
    {
        if (is_string($emailOrAddress)) {
            $emailOrAddress = $this->createAddress($emailOrAddress, $name);
        }

        if (! $emailOrAddress instanceof Address\AddressInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an email address or %s\Address object as its first argument; received "%s"',
                __METHOD__,
                __NAMESPACE__,
                (is_object($emailOrAddress) ? get_class($emailOrAddress) : gettype($emailOrAddress))
            ));
        }

        $email = strtolower($emailOrAddress->getEmail());
        if ($this->has($email)) {
            return $this;
        }

        $this->addresses[$email] = $emailOrAddress;
        return $this;
    }

    /**
     * Add many addresses at once
     *
     * If an email key is provided, it will be used as the email, and the value
     * as the name. Otherwise, the value is passed as the sole argument to add(),
     * and, as such, can be either email strings or Address\AddressInterface objects.
     *
     * @param  array $addresses
     * @throws Exception\RuntimeException
     * @return AddressList
     */
    public function addMany(array $addresses)
    {
        foreach ($addresses as $key => $value) {
            if (is_int($key) || is_numeric($key)) {
                $this->add($value);
                continue;
            }

            if (! is_string($key)) {
                throw new Exception\RuntimeException(sprintf(
                    'Invalid key type in provided addresses array ("%s")',
                    (is_object($key) ? get_class($key) : var_export($key, 1))
                ));
            }

            $this->add($key, $value);
        }
        return $this;
    }

    /**
     * Add an address to the list from any valid string format, such as
     *  - "Laminas Dev" <dev@laminas.com>
     *  - dev@laminas.com
     *
     * @param string $address
     * @param null|string $comment Comment associated with the address, if any.
     * @throws Exception\InvalidArgumentException
     * @return AddressList
     */
    public function addFromString($address, $comment = null)
    {
        $this->add(Address::fromString($address, $comment));
        return $this;
    }

    /**
     * Merge another address list into this one
     *
     * @param  AddressList $addressList
     * @return AddressList
     */
    public function merge(AddressList $addressList)
    {
        foreach ($addressList as $address) {
            $this->add($address);
        }
        return $this;
    }

    /**
     * Does the email exist in this list?
     *
     * @param  string $email
     * @return bool
     */
    public function has($email)
    {
        $email = strtolower($email);
        return isset($this->addresses[$email]);
    }

    /**
     * Get an address by email
     *
     * @param  string $email
     * @return bool|Address\AddressInterface
     */
    public function get($email)
    {
        $email = strtolower($email);
        if (! isset($this->addresses[$email])) {
            return false;
        }

        return $this->addresses[$email];
    }

    /**
     * Delete an address from the list
     *
     * @param  string $email
     * @return bool
     */
    public function delete($email)
    {
        $email = strtolower($email);
        if (! isset($this->addresses[$email])) {
            return false;
        }

        unset($this->addresses[$email]);
        return true;
    }

    /**
     * Return count of addresses
     *
     * @return int
     */
    public function count()
    {
        return count($this->addresses);
    }

    /**
     * Rewind iterator
     *
     * @return mixed the value of the first addresses element, or false if the addresses is
     * empty.
     * @see addresses
     */
    public function rewind()
    {
        return reset($this->addresses);
    }

    /**
     * Return current item in iteration
     *
     * @return Address
     */
    public function current()
    {
        return current($this->addresses);
    }

    /**
     * Return key of current item of iteration
     *
     * @return string
     */
    public function key()
    {
        return key($this->addresses);
    }

    /**
     * Move to next item
     *
     * @return mixed the addresses value in the next place that's pointed to by the
     * internal array pointer, or false if there are no more elements.
     * @see addresses
     */
    public function next()
    {
        return next($this->addresses);
    }

    /**
     * Is the current item of iteration valid?
     *
     * @return bool
     */
    public function valid()
    {
        $key = key($this->addresses);
        return ($key !== null && $key !== false);
    }

    /**
     * Create an address object
     *
     * @param  string $email
     * @param  string|null $name
     * @return Address
     */
    protected function createAddress($email, $name)
    {
        return new Address($email, $name);
    }
}
