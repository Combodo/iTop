<?php


use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContent;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderGoogle;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oLayout = new PageContent();
$oLayout->AddCSSClass('ibo-oauth-wizard');
$oPage = new iTopWebPage(Dict::S('UI:OAuth:Wizard:Page:Title'));
$oPage->SetContentLayout($oLayout);
$sReturnUri = utils::GetAbsoluteUrlAppRoot().'pages/oauth.landing.php';
$sAjaxUri = utils::GetAbsoluteUrlAppRoot().'pages/ajax.oauth.wizard.php';
	
$sJS = <<<JS
let windowObjectReference = null;
let previousUrl = null;

const openSignInWindow = (url, name) => {
   // remove any existing event listeners

   // window features
   const strWindowFeatures =
     'toolbar=no, menubar=no, width=600, height=700, top=100, left=100';

   if (windowObjectReference === null || windowObjectReference.closed) {
     /* if the pointer to the window object in memory does not exist
      or if such pointer exists but the window was closed */
     windowObjectReference = window.open(url, name, strWindowFeatures);
   } else if (previousUrl !== url) {
     /* if the resource to load is different,
      then we load it in the already opened secondary window and then
      we bring such window back on top/in front of its parent window. */
     windowObjectReference = window.open(url, name, strWindowFeatures);
     windowObjectReference.focus();
   } else {
     /* else the window reference must exist and the window
      is not closed; therefore, we can bring it back on top of any other
      window with the focus() method. There would be no need to re-create
      the window or to reload the referenced resource. */
     windowObjectReference.focus();
   }
   let oListener = window.setInterval(function(){
	   windowObjectReference.postMessage('anyone', '$sReturnUri');
   }, 1000);

   
   window.addEventListener("message", function (event){
	   clearInterval(oListener);
	   $.post(
			'$sAjaxUri',
			{
				operation: 'get_display_authentication_results',
				provider: $('[name="provider"]:checked').val(),
				client_id: $('[name="client_id"]').val(),
				client_secret: $('[name="client_secret"]').val(),
				scope: $(this).find('[name="scope"]').val(),
				additional: $(this).find('[name="additional"]').val(),
				redirect_url: event.data,
			},
			function(oData){
				if(oData.status == 'success')
				{   
					oData.data.forEach(function(item, index){
						new Function(item)();
					});
				}					
				$('.ibo-oauth-wizard--form--submit').trigger('leave_loading_state.button.itop');
			}
	);
   }, false);
   // add the listener for receiving a message from the popup
   // assign the previous URL
   previousUrl = url;
 };
JS;

$oPage->add_script($sJS);
$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/pages/backoffice/oauth.wizard.js');

$oOauthInputsPanel = PanelUIBlockFactory::MakeNeutral(Dict::S('UI:OAuth:Wizard:Form:Panel:Title'));
$oOauthInputsPanel->AddCSSClass('ibo-oauth-wizard');

$aOAuthClasses = utils::GetClassesForInterface('Combodo\iTop\Core\Authentication\Client\OAuth\IOAuthClientProvider', '', array('[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]'));
$sFormJs = <<<JS
	$('.ibo-oauth-wizard--form--submit').trigger('enter_loading_state.button.itop');
	$.post(
			'$sAjaxUri',
			{
				operation: 'get_authorization_url',
				provider: $('[name="provider"]:checked').val(),
				client_id: $(this).find('[name="client_id"]').val(),
				client_secret: $(this).find('[name="client_secret"]').val(),
				scope: $(this).find('[name="scope"]').val(),
				additional: $(this).find('[name="additional"]').val()
			},
			function(oData){
				if(oData.status == 'success')
				{   
					openSignInWindow(oData.data.authorization_url, 'coucou')
				}
				else{
					$('.ibo-oauth-wizard--form--submit').trigger('leave_loading_state.button.itop');
				}
			}
	);
	return false;
JS;

$oProviderSelect = new Html('<div class="ibo-oauth-wizard--form--container"><div id="select_layout">');
$oOauthInputsPanel->AddSubBlock($oProviderSelect);
$sIsChecked = ' checked ';
foreach($aOAuthClasses as $sOAuthClass){
	$aColors = $sOAuthClass::GetVendorColors();
	$oProviderSelect->AddHtml('<input type="radio" name="provider" '.$sIsChecked.' value="'.$sOAuthClass::GetVendorName().'" id="layout_'.$sOAuthClass::GetVendorName().'" data-color1="'.$aColors[0].'" data-color2="'.$aColors[1].'" data-color3="'.$aColors[2].'" data-color4="'.$aColors[3].'"><label for="layout_'.$sOAuthClass::GetVendorName().'"><img src="'.$sOAuthClass::GetVendorIcon().'" class="ibo-dashboard--properties--icon" data-role="ibo-dashboard--properties--icon"/></label>');
	$sIsChecked = '';
}
$oForm = FormUIBlockFactory::MakeStandard();
$oForm->AddCSSClasses(['ibo-oauth-wizard--form', 'ibo-oauth-wizard--form-'.strtolower($sOAuthClass::GetVendorName())]);
$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('url', ''));
foreach (['client_id' => Dict::S('UI:OAuth:Wizard:Form:Input:ClientId:Label'),
          'client_secret' => Dict::S('UI:OAuth:Wizard:Form:Input:ClientSecret:Label'),
          'scope' => Dict::S('UI:OAuth:Wizard:Form:Input:Scope:Label'),
          'additional' => Dict::S('UI:OAuth:Wizard:Form:Input:Additional:Label')] as $sName => $sLabel){
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForInputWithLabel($sLabel, $sName, null, null, 'text'));
}
$oRedirectUriInput = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('UI:OAuth:Wizard:Form:Input:RedirectUri:Label'), 'redirect_uri', OAuthClientProviderGoogle::GetRedirectUri(), null, 'text');
$oRedirectUriInput->GetInput()->SetIsReadonly(true);
$oForm->AddSubBlock($oRedirectUriInput);

$oForm->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:OAuth:Wizard:Form:Button:Submit:Label'),'submit', '', true)->AddCSSClass('ibo-oauth-wizard--form--submit'));
$oForm->SetOnSubmitJsCode($sFormJs);
$oOauthInputsPanel->AddSubBlock($oForm);
$oProviderSelect->AddHtml('</div>');
$oOauthInputsPanel->AddHtml('</div>');

$sOnReadyJs = "$('#select_layout').controlgroup(); 	$('.ibo-oauth-wizard--result--panel .ibo-panel--collapsible-toggler').click();";
$oPage->add_ready_script($sOnReadyJs);
$oOauthInputsPanel->AddHtml('<div class="ibo-oauth-wizard--illustration">'.file_get_contents(APPROOT.'images/illustrations/undraw_access_account.svg').'</div>');
$oPage->AddSubBlock($oOauthInputsPanel);

$aOAuthResultDisplayClasses = utils::GetClassesForInterface('Combodo\iTop\Core\Authentication\Client\OAuth\IOAuthClientResultDisplay', '', array('[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]'));
foreach($aOAuthResultDisplayClasses as $sOAuthClass) {
	$oPage->AddSubBlock($sOAuthClass::GetResultDisplayBlock());
}


$oPage->output();
