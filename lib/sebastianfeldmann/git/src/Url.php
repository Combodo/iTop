<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SebastianFeldmann\Git;

use RuntimeException;

/**
 * Class Url
 *
 * Represents a valid repository URL either http or ssh.
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.8.0
 */
final class Url
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $scheme;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $repoName;

    public function __construct(string $url)
    {
        $parsed         = $this->parseUrl($url);
        $this->url      = $url;
        $this->scheme   = (string) ($parsed['scheme'] ?? '');
        $this->user     = (string) ($parsed['user'] ?? '');
        $this->host     = (string) ($parsed['host'] ?? '');
        $this->path     = (string) ($parsed['path'] ?? '');
        $this->repoName = $this->parseRepoName($this->path);
    }

    /**
     * Is the given url an SSH clone URL
     *
     * @param  string $url
     * @return bool
     */
    public static function isSSHUrl(string $url): bool
    {
        // should not contain http
        // should at least contain one colon
        return !str_contains($url, 'http') && str_contains($url, ':');
    }

    /**
     * Returns the full url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns only the scheme
     *
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Returns only the user
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Returns only the host
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Returns only the path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Returns the repo name (last path segment of the url)
     *
     * @return string
     */
    public function getRepoName(): string
    {
        return $this->repoName;
    }

    /**
     * Detect the url components
     *
     * @param  string $url
     * @return array<string, int|string>
     */
    private function parseUrl(string $url): array
    {
        // By default, GitHub and gitlab urls can't be parsed by parse_url,
        // so we have to make sure we end up with parsable urls
        if (self::isSSHUrl($url)) {
            $url = $this->convertToValidUrl($url);
        }

        $parsed = parse_url($url);

        if (!is_array($parsed)) {
            throw new RuntimeException('can\'t parse repository url');
        }
        return $parsed;
    }

    /**
     * This converts GitHub and gitlab ssh urls to parsable urls
     *
     * @param  string $url
     * @return string
     */
    private function convertToValidUrl(string $url): string
    {
        $url = $this->addMissingScheme($url);
        return $this->replaceColonWithSlash($url);
    }

    /**
     * Find the repo name within the url path
     *
     * @param  string $path
     * @return string
     */
    private function parseRepoName(string $path): string
    {
        $lastSlashPosition = strrpos($path, '/');
        return str_replace('.git', '', substr($path, $lastSlashPosition + 1));
    }

    /**
     * This will add the ssh scheme if it is missing
     *
     * @param  string $url
     * @return string
     */
    private function addMissingScheme(string $url): string
    {
        return str_contains($url, 'ssh://') ? $url : 'ssh://' . $url;
    }

    /**
     * Replace the git@github.com:user/repo with github.com/user/repo
     *
     * @param  string $url
     * @return string
     */
    private function replaceColonWithSlash(string $url): string
    {
        $lastColonPosition = (int)strrpos($url, ':');
        return substr($url, 0, $lastColonPosition) . '/' . substr($url, $lastColonPosition + 1);
    }
}
