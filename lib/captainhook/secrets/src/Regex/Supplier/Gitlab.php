<?php

/**
 * This file is part of CaptainHook Secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\Secrets\Regex\Supplier;

use CaptainHook\Secrets\Regex\Supplier;

/**
 * Gitlab regex
 *
 * Provides the regex to find Gitlab secrets.
 *
 * @package CaptainHook-Secrets
 * @since   Class available since Release 0.9.6
 */
final class Gitlab implements Supplier
{
    /**
     * Sourced from the gitlab secret detection
     * https://github.com/gitlabhq/gitlabhq/blob/master/gems/gitlab-secret_detection/lib/gitleaks.toml#L4-L51
     *
     * @return string[]
     */
    public function patterns(): array
    {
        return [
            // GitLab Personal Access Token
            '#' . Util::OPTIONAL_QUOTE . '(glpat-[0-9a-zA-Z_\\-]{20})' . Util::OPTIONAL_QUOTE . '#',
            // GitLab Pipeline Trigger Token
            '#' . Util::OPTIONAL_QUOTE . '(glptt-[0-9a-zA-Z_\\-]{40})' . Util::OPTIONAL_QUOTE . '#',
            // GitLab Runner Registration Token
            '#' . Util::OPTIONAL_QUOTE . '(GR1348941[0-9a-zA-Z_\\-]{20})' . Util::OPTIONAL_QUOTE . '#',
            // GitLab OAuth Application Secrets
            '#' . Util::OPTIONAL_QUOTE . '(gloas-[0-9a-zA-Z_\\-]{64})' . Util::OPTIONAL_QUOTE . '#',
            // GitLab Feed token
            '#' . Util::OPTIONAL_QUOTE . '(glft-[0-9a-zA-Z_\\-]{20})' . Util::OPTIONAL_QUOTE . '#',
            // GitLab Agent for Kubernetes token
            '#' . Util::OPTIONAL_QUOTE . '(glagent-[0-9a-zA-Z_\\-]{50})' . Util::OPTIONAL_QUOTE . '#',
            // GitLab Incoming email token
            '#' . Util::OPTIONAL_QUOTE . '(glimt-[0-9a-zA-Z_\\-]{25})' . Util::OPTIONAL_QUOTE . '#',
        ];
    }
}
