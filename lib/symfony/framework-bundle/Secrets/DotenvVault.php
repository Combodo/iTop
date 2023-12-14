<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Secrets;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DotenvVault extends AbstractVault
{
    private string $dotenvFile;

    public function __construct(string $dotenvFile)
    {
        $this->dotenvFile = strtr($dotenvFile, '/', \DIRECTORY_SEPARATOR);
    }

    public function generateKeys(bool $override = false): bool
    {
        $this->lastMessage = 'The dotenv vault doesn\'t encrypt secrets thus doesn\'t need keys.';

        return false;
    }

    public function seal(string $name, string $value): void
    {
        $this->lastMessage = null;
        $this->validateName($name);
        $v = str_replace("'", "'\\''", $value);

        $content = is_file($this->dotenvFile) ? file_get_contents($this->dotenvFile) : '';
        $content = preg_replace("/^$name=((\\\\'|'[^']++')++|.*)/m", "$name='$v'", $content, -1, $count);

        if (!$count) {
            $content .= "$name='$v'\n";
        }

        file_put_contents($this->dotenvFile, $content);

        $this->lastMessage = sprintf('Secret "%s" %s in "%s".', $name, $count ? 'added' : 'updated', $this->getPrettyPath($this->dotenvFile));
    }

    public function reveal(string $name): ?string
    {
        $this->lastMessage = null;
        $this->validateName($name);
        $v = $_ENV[$name] ?? (str_starts_with($name, 'HTTP_') ? null : ($_SERVER[$name] ?? null));

        if ('' === ($v ?? '')) {
            $this->lastMessage = sprintf('Secret "%s" not found in "%s".', $name, $this->getPrettyPath($this->dotenvFile));

            return null;
        }

        return $v;
    }

    public function remove(string $name): bool
    {
        $this->lastMessage = null;
        $this->validateName($name);

        $content = is_file($this->dotenvFile) ? file_get_contents($this->dotenvFile) : '';
        $content = preg_replace("/^$name=((\\\\'|'[^']++')++|.*)\n?/m", '', $content, -1, $count);

        if ($count) {
            file_put_contents($this->dotenvFile, $content);
            $this->lastMessage = sprintf('Secret "%s" removed from file "%s".', $name, $this->getPrettyPath($this->dotenvFile));

            return true;
        }

        $this->lastMessage = sprintf('Secret "%s" not found in "%s".', $name, $this->getPrettyPath($this->dotenvFile));

        return false;
    }

    public function list(bool $reveal = false): array
    {
        $this->lastMessage = null;
        $secrets = [];

        foreach ($_ENV as $k => $v) {
            if ('' !== ($v ?? '') && preg_match('/^\w+$/D', $k)) {
                $secrets[$k] = $reveal ? $v : null;
            }
        }

        foreach ($_SERVER as $k => $v) {
            if ('' !== ($v ?? '') && preg_match('/^\w+$/D', $k)) {
                $secrets[$k] = $reveal ? $v : null;
            }
        }

        return $secrets;
    }
}
