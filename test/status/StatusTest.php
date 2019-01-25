<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Status;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * 
 */
class StatusTest extends ItopDataTestCase {

    /**
     * 
     */
    public function testStatusGood() {
        try {
            $sUrl = \utils::GetAbsoluteUrlAppRoot() . 'status.php';

            if (function_exists('curl_init')) {
                // If cURL is available, let's use it, since it provides a greater control over the various HTTP/SSL options
                // For instance fopen does not allow to work around the bug: http://stackoverflow.com/questions/18191672/php-curl-ssl-routinesssl23-get-server-helloreason1112
                // by setting the SSLVERSION to 3 as done below.
                // Default options, can be overloaded/extended with the 4th parameter of this method, see above $aCurlOptions
                $aOptions = array(
                    CURLOPT_RETURNTRANSFER => true, // return the content of the request
                    CURLOPT_HEADER => false, // don't return the headers in the output
                    CURLOPT_FOLLOWLOCATION => true, // follow redirects
                    CURLOPT_ENCODING => "", // handle all encodings
                    CURLOPT_USERAGENT => "spider", // who am i
                    CURLOPT_AUTOREFERER => true, // set referer on redirect
                    CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
                    CURLOPT_TIMEOUT => 120, // timeout on response
                    CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                    CURLOPT_SSL_VERIFYPEER => false, // Disabled SSL Cert checks
                    // SSLV3 (CURL_SSLVERSION_SSLv3 = 3) is now considered as obsolete/dangerous: http://disablessl3.com/#why
                    // but it used to be a MUST to prevent a strange SSL error: http://stackoverflow.com/questions/18191672/php-curl-ssl-routinesssl23-get-server-helloreason1112
                    // CURLOPT_SSLVERSION
                    CURLOPT_CUSTOMREQUEST => 'HEAD', //Get only HTTP Code as this page should only return a HTTP Code
                );

                $ch = curl_init($sUrl);
                curl_setopt_array($ch, $aOptions);
                curl_exec($ch);
                $iErr = curl_errno($ch);
                $sErrMsg = curl_error($ch);
                if ($iErr !== 0) {
                    throw new \Exception("Problem opening URL: $sUrl, $sErrMsg");
                }
                curl_close($ch);
            } else {
                // cURL is not available let's try with streams and fopen...
                // By default get_headers uses a GET request to fetch the headers. If you
                // want to send a HEAD request instead, you can do so using a stream context:
                stream_context_set_default(
                        array(
                            'http' => array(
                                'method' => 'HEAD'
                            )
                        )
                );
                $headers = get_headers($sUrl);
                //Undo overriding default context
                stream_context_set_default(
                        array(
                            'http' => array(
                                'method' => 'GET'
                            )
                        )
                );
                $sHttpCode = (int) substr($headers[0], 9, 3);
                if ($sHttpCode != 200) {
                    throw new \Exception("Problem opening URL: $sUrl, $sHttpCode");
                }
            }

            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
    }

}
