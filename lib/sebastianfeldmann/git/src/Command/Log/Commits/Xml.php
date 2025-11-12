<?php

/**
 * This file is part of SebastianFeldmann/Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Log\Commits;

use SebastianFeldmann\Git\Log\Commit;

/**
 * Class Xml
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.2.0
 */
class Xml
{
    /**
     * XML commit format to parse the git log as xml
     *
     * @var string
     */
    public const FORMAT = '<commit>%n' .
                            '<hash>%h</hash>%n' .
                            '<names><![CDATA[%d]]></names>%n' .
                            '<date>%ci</date>%n' .
                            '<author><![CDATA[%an]]></author>%n' .
                            '<subject><![CDATA[%s]]></subject>%n' .
                            '<body><![CDATA[%n%b%n]]></body>%n' .
                          '</commit>';

    /**
     * Parse log output into list of Commit objects
     *
     * @param  string $output
     * @return array<\SebastianFeldmann\Git\Log\Commit>
     * @throws \Exception
     */
    public static function parseLogOutput(string $output): array
    {
        $log = [];
        $xml = '<?xml version="1.0"?><log>' . $output . '</log>';

        $parsedXML = \simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOERROR);
        if (!$parsedXML) {
            return $log;
        }

        foreach ($parsedXML->commit as $commitXML) {
            $nameRaw = str_replace(['(', ')'], '', trim((string) $commitXML->names));
            $names   = empty($nameRaw) ? [] : array_map('trim', explode(',', $nameRaw));

            $log[]   = new Commit(
                trim((string) $commitXML->hash),
                $names,
                trim((string) $commitXML->subject),
                trim((string) $commitXML->body),
                new \DateTimeImmutable(trim((string) $commitXML->date)),
                trim((string) $commitXML->author)
            );
        }
        return $log;
    }
}
