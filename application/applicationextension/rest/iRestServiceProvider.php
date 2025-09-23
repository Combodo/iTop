<?php

/**
 * Implement this interface to add new operations to the REST/JSON web service
 *
 * @api
 * @package     RESTExtensibilityAPI
 * @since 2.0.1
 */
interface iRestServiceProvider
{
    /**
     * Enumerate services delivered by this class
     *
     * @param string $sVersion The version (e.g. 1.0) supported by the services
     *
     * @return array An array of hash 'verb' => verb, 'description' => description
     * @api
     */
    public function ListOperations($sVersion);

    /**
     * Enumerate services delivered by this class
     *
     * @param string $sVersion The version (e.g. 1.0) supported by the services
     * @param string $sVerb
     * @param array $aParams
     *
     * @return RestResult The standardized result structure (at least a message)
     * @api
     */
    public function ExecOperation($sVersion, $sVerb, $aParams);
}