<?php

namespace Laminas\Mail\Protocol\Smtp\Auth;

use Laminas\Mail\Protocol\Smtp;

/**
 * Performs LOGIN authentication
 */
class Login extends Smtp
{
    /**
     * LOGIN username
     *
     * @var string
     */
    protected $username;

    /**
     * LOGIN password
     *
     * @var string
     */
    protected $password;

    /**
     * Constructor.
     *
     * @param  string $host   (Default: 127.0.0.1)
     * @param  int    $port   (Default: null)
     * @param  array  $config Auth-specific parameters
     */
    public function __construct($host = '127.0.0.1', $port = null, $config = null)
    {
        // Did we receive a configuration array?
        $origConfig = $config;
        if (is_array($host)) {
            // Merge config array with principal array, if provided
            if (is_array($config)) {
                $config = array_replace_recursive($host, $config);
            } else {
                $config = $host;
            }
        }

        if (is_array($config)) {
            if (isset($config['username'])) {
                $this->setUsername($config['username']);
            }
            if (isset($config['password'])) {
                $this->setPassword($config['password']);
            }
        }

        // Call parent with original arguments
        parent::__construct($host, $port, $origConfig);
    }

    /**
     * Perform LOGIN authentication with supplied credentials
     *
     */
    public function auth()
    {
        // Ensure AUTH has not already been initiated.
        parent::auth();

        $this->_send('AUTH LOGIN');
        $this->_expect(334);
        $this->_send(base64_encode($this->getUsername()));
        $this->_expect(334);
        $this->_send(base64_encode($this->getPassword()));
        $this->_expect(235);
        $this->auth = true;
    }

    /**
     * Set value for username
     *
     * @param  string $username
     * @return Login
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set value for password
     *
     * @param  string $password
     * @return Login
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
