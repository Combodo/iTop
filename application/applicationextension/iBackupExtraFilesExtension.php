<?php

/**
 * Implement this interface to add files to the backup
 *
 * @api
 * @since 3.2.0
 */
interface iBackupExtraFilesExtension
{
    /**
     * @return string[] Array of relative paths (from app root) for files and directories to be included in the backup
     * @api
     */
    public function GetExtraFilesRelPaths(): array;
}