<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\ClassLoader;

@trigger_error('The '.__NAMESPACE__.'\ClassMapGenerator class is deprecated since Symfony 3.3 and will be removed in 4.0. Use Composer instead.', \E_USER_DEPRECATED);

/**
 * ClassMapGenerator.
 *
 * @author Gyula Sallai <salla016@gmail.com>
 *
 * @deprecated since version 3.3, to be removed in 4.0.
 */
class ClassMapGenerator
{
    /**
     * Generate a class map file.
     *
     * @param array|string $dirs Directories or a single path to search in
     * @param string       $file The name of the class map file
     */
    public static function dump($dirs, $file)
    {
        $dirs = (array) $dirs;
        $maps = [];

        foreach ($dirs as $dir) {
            $maps = array_merge($maps, static::createMap($dir));
        }

        file_put_contents($file, sprintf('<?php return %s;', var_export($maps, true)));
    }

    /**
     * Iterate over all files in the given directory searching for classes.
     *
     * @param \Iterator|string $dir The directory to search in or an iterator
     *
     * @return array A class map array
     */
    public static function createMap($dir)
    {
        if (\is_string($dir)) {
            $dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        }

        $map = [];

        foreach ($dir as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $path = $file->getRealPath() ?: $file->getPathname();

            if ('php' !== pathinfo($path, \PATHINFO_EXTENSION)) {
                continue;
            }

            $classes = self::findClasses($path);

            if (\PHP_VERSION_ID >= 70000) {
                // PHP 7 memory manager will not release after token_get_all(), see https://bugs.php.net/70098
                gc_mem_caches();
            }

            foreach ($classes as $class) {
                $map[$class] = $path;
            }
        }

        return $map;
    }

    /**
     * Extract the classes in the given file.
     *
     * @param string $path The file to check
     *
     * @return array The found classes
     */
    private static function findClasses($path)
    {
        $contents = file_get_contents($path);
        $tokens = token_get_all($contents);

        $nsTokens = [\T_STRING => true, \T_NS_SEPARATOR => true];
        if (\defined('T_NAME_QUALIFIED')) {
            $nsTokens[T_NAME_QUALIFIED] = true;
        }

        $classes = [];

        $namespace = '';
        for ($i = 0; isset($tokens[$i]); ++$i) {
            $token = $tokens[$i];

            if (!isset($token[1])) {
                continue;
            }

            $class = '';

            switch ($token[0]) {
                case \T_NAMESPACE:
                    $namespace = '';
                    // If there is a namespace, extract it
                    while (isset($tokens[++$i][1])) {
                        if (isset($nsTokens[$tokens[$i][0]])) {
                            $namespace .= $tokens[$i][1];
                        }
                    }
                    $namespace .= '\\';
                    break;
                case \T_CLASS:
                case \T_INTERFACE:
                case \T_TRAIT:
                    // Skip usage of ::class constant
                    $isClassConstant = false;
                    for ($j = $i - 1; $j > 0; --$j) {
                        if (!isset($tokens[$j][1])) {
                            break;
                        }

                        if (\T_DOUBLE_COLON === $tokens[$j][0]) {
                            $isClassConstant = true;
                            break;
                        } elseif (!\in_array($tokens[$j][0], [\T_WHITESPACE, \T_DOC_COMMENT, \T_COMMENT])) {
                            break;
                        }
                    }

                    if ($isClassConstant) {
                        break;
                    }

                    // Find the classname
                    while (isset($tokens[++$i][1])) {
                        $t = $tokens[$i];
                        if (\T_STRING === $t[0]) {
                            $class .= $t[1];
                        } elseif ('' !== $class && \T_WHITESPACE === $t[0]) {
                            break;
                        }
                    }

                    $classes[] = ltrim($namespace.$class, '\\');
                    break;
                default:
                    break;
            }
        }

        return $classes;
    }
}
