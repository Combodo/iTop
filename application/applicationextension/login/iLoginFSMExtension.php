<?php

/**
 * @api
 * @package     LoginExtensibilityAPI
 * @since 2.7.0
 */
interface iLoginFSMExtension extends iLoginExtension
{
    /**
     * Execute action for this login state
     * If a page is displayed, the action must exit at this point
     * if LoginWebPage::LOGIN_FSM_RETURN_ERROR is returned $iErrorCode must be set
     * if LoginWebPage::LOGIN_FSM_RETURN_OK is returned then the login is OK and terminated
     * if LoginWebPage::LOGIN_FSM_CONTINUE is returned then the FSM will proceed to next plugin or state
     *
     * @param string $sLoginState (see LoginWebPage::LOGIN_STATE_...)
     * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
     *
     * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
     * @api
     */
    public function LoginAction($sLoginState, &$iErrorCode);
}