<?php

/**
 * Minimal REST response structure. Derive this structure to add response data and error codes.
 *
 * @api
 * @package     RESTAPI
 * @since 2.0.1
 */
class RestResult
{
    /**
     * Result: no issue has been encountered
     * @api
     */
    const OK = 0;
    /**
     * Result: missing/wrong credentials or the user does not have enough rights to perform the requested operation
     * @api
     */
    const UNAUTHORIZED = 1;
    /**
     * Result: the parameter 'version' is missing
     * @api
     */
    const MISSING_VERSION = 2;
    /**
     * Result: the parameter 'json_data' is missing
     * @api
     */
    const MISSING_JSON = 3;
    /**
     * Result: the input structure is not a valid JSON string
     * @api
     */
    const INVALID_JSON = 4;
    /**
     * Result: the parameter 'auth_user' is missing, authentication aborted
     * @api
     */
    const MISSING_AUTH_USER = 5;
    /**
     * Result: the parameter 'auth_pwd' is missing, authentication aborted
     * @api
     */
    const MISSING_AUTH_PWD = 6;
    /**
     * Result: no operation is available for the specified version
     * @api
     */
    const UNSUPPORTED_VERSION = 10;
    /**
     * Result: the requested operation is not valid for the specified version
     * @api
     */
    const UNKNOWN_OPERATION = 11;
    /**
     * Result: the requested operation cannot be performed because it can cause data (integrity) loss
     * @api
     */
    const UNSAFE = 12;
    /**
     * Result: the request page number is not valid. It must be an integer greater than 0
     * @api
     */
    const INVALID_PAGE = 13;
    /**
     * Result: the operation could not be performed, see the message for troubleshooting
     * @api
     */
    const INTERNAL_ERROR = 100;

    /**
     * Default constructor - ok!
     * @api
     */
    public function __construct()
    {
        $this->code = RestResult::OK;
    }

    /**
     * Result code
     * @var int
     * @api
     */
    public $code;
    /**
     * Result message
     * @var string
     * @api
     */
    public $message;

    /**
     * Sanitize the content of this result to hide sensitive information
     */
    public function SanitizeContent()
    {
        // The default implementation does nothing
    }
}