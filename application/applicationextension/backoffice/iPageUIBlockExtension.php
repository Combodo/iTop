<?php

/**
 * Implement this interface to add content to any iTopWebPage
 *
 * There are 3 places where content can be added:
 *
 * * The north pane: (normaly empty/hidden) at the top of the page, spanning the whole
 *   width of the page
 * * The south pane: (normaly empty/hidden) at the bottom of the page, spanning the whole
 *   width of the page
 * * The admin banner (two tones gray background) at the left of the global search.
 *   Limited space, use it for short messages
 *
 * Each of the methods of this interface is supposed to return the HTML to be inserted at
 * the specified place and can use the passed iTopWebPage object to add javascript or CSS definitions
 *
 * @api
 * @package     BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iPageUIBlockExtension
{
    /**
     * Add content to the "admin banner"
     *
     * @api
     * @return \Combodo\iTop\Application\UI\Base\iUIBlock|null The Block to add into the page
     */
    public function GetBannerBlock();

    /**
     * Add content to the header of the page
     *
     * @api
     * @return \Combodo\iTop\Application\UI\Base\iUIBlock|null The Block to add into the page
     */
    public function GetHeaderBlock();

    /**
     * Add content to the footer of the page
     *
     * @api
     * @return \Combodo\iTop\Application\UI\Base\iUIBlock|null The Block to add into the page
     */
    public function GetFooterBlock();
}