<?php
function DisplayStatus(WebPage $oPage)
{
    $oPage->set_title(Dict::S('iTopHub:Landing:Status'));
    
    $oPage->add('<table class="module-selection-banner"><tr>');
    $sBannerUrl = utils::GetAbsoluteUrlModulesRoot().'/itop-hub-connector/images/landing-extension.png';
    $oPage->add('<td><img style="max-height:72px; margin-right: 10px;" src="'.$sBannerUrl.'"/><td>');
    $oPage->add('<td><h2>'.Dict::S('iTopHub:LandingWelcome').'</h2><td>');
    $oPage->add('</tr></table>');
    
    $oPage->add('<div class="module-selection-body">');
    // Now scan the extensions and display a report of the extensions brought by the hub
    $sPath = APPROOT.'data/downloaded-extensions/';
    $aExtraDirs = array();
    if (is_dir($sPath))
    {
        $aExtraDirs[] = $sPath; // Also read the extra downloaded-modules directory
    }
    $oExtensionsMap = new iTopExtensionsMap('production', true, $aExtraDirs);
    $oExtensionsMap->LoadChoicesFromDatabase(MetaModel::GetConfig());
    
    foreach($oExtensionsMap->GetAllExtensions() as $oExtension)
    {
        if ($oExtension->sSource == iTopExtension::SOURCE_REMOTE)
        {
            $aCSSClasses = array('landing-extension');
            if ($oExtension->sInstalledVersion === '')
            {
                $aCSSClasses[] = 'landing-installation';
                $sInstallation = Dict::Format('iTopHub:InstallationStatus:Version_NotInstalled', $oExtension->sVersion);
                
            }
            else
            {
                $aCSSClasses[] = 'landing-no-change';
                $sBadge = '<span style="display:inline-block;font-size:8pt;padding:3px;border-radius:4px;color:#fff;background-color:#1c94c4;margin-left:0.5em;margin-right:0.5em">'.Dict::S('iTopHub:InstallationStatus:Installed').'</span>';
                $sInstallation = Dict::Format('iTopHub:InstallationStatus:Installed_Version', $sBadge, $oExtension->sInstalledVersion);
            }

            $oPage->add('<div class="choice">');
            $sCode = $oExtension->sCode;
            $sDir = basename($oExtension->sSourceDir);
            $oPage->add('<input type="checkbox" data-extension-code="'.$sCode.'" data-extension-dir="'.$sDir.'" checked disabled>&nbsp;');
            $oPage->add('<label><b>'.htmlentities($oExtension->sLabel, ENT_QUOTES, 'UTF-8').'</b> '.$sInstallation.'</label>');
            $oPage->add('<div class="description">');
            $oPage->add('<p>');
            if ($oExtension->sDescription != '')
            {
                $oPage->add(htmlentities($oExtension->sDescription, ENT_QUOTES, 'UTF-8').'</br>');
            }
            $oPage->add('</p>');
            $oPage->add('</div>');
            $oPage->add('</div>');
        }
    }
    $oPage->add('</div>');
    $oPage->add('<div style="text-align:center"><button onclick="window.location.href=\'./UI.php\';">'.Dict::S('iTopHub:GoBackToITopBtn').'</button></div>');
}

function DoLanding(WebPage $oPage)
{
    $oPage->add_linked_stylesheet(utils::GetAbsoluteUrlModulesRoot().'itop-hub-connector/css/hub.css');
    $oPage->add('<table class="module-selection-banner"><tr>');
    $sBannerUrl = utils::GetAbsoluteUrlModulesRoot().'/itop-hub-connector/images/landing-extension.png';
    $oPage->add('<td><img style="max-height:72px; margin-right: 10px;" src="'.$sBannerUrl.'"/><td>');
    $oPage->add('<td><h2>'.Dict::S('iTopHub:InstallationWelcome').'</h2><td>');
    $oPage->add('</tr></table>');
    
    $oPage->set_title(Dict::S('iTopHub:Landing:Status'));
    
    $oPage->add('<div class="module-selection-body" style="text-align: center; line-height: 14em;"><h2>'.Dict::S('iTopHub:Uncompressing').'</h2></div>');
    
    
    $sProduct = utils::ReadParam('applicationName', '', false, 'raw_data');
    $sVersion = utils::ReadParam('applicationVersion', '', false, 'raw_data');
    $sInstanceUUID = utils::ReadParam('uuidFile', '', false, 'raw_data');
    $sDatabaseUUID = utils::ReadParam('uuidBdd', '', false, 'raw_data');
    $aExtensions = utils::ReadParam('extensions', array(), false, 'raw_data');
    
    // Basic consistency validation
    if ($sProduct != ITOP_APPLICATION)
    {
        throw new Exception("Inconsistent product '$sProduct', expecting '".ITOP_APPLICATION."'");
    }
    
    if ($sVersion != ITOP_VERSION)
    {
        throw new Exception("Inconsistent version '$sVersion', expecting ".ITOP_VERSION."'");
    }
    
    $sFileUUID = (string) trim(@file_get_contents(APPROOT."data/instance.txt"), "{} \n");
    if ($sInstanceUUID != $sFileUUID)
    {
        throw new Exception("Inconsistent file UUID '$sInstanceUUID', expecting ".$sFileUUID."'");
    }
    
    $sDBUUID = (string) trim(DBProperty::GetProperty('database_uuid', ''), '{}');
    if ($sDatabaseUUID != $sDBUUID)
    {
        throw new Exception("Inconsistent database UUID '$sDatabaseUUID', expecting ".$sDBUUID."'");
    }
    
    // Uncompression of extensions in data/downloaded-extensions
    // only newly downloaded extensions reside in this folder
    $i = 0;
    $sPath = APPROOT.'data/downloaded-extensions/';
    if (!is_dir($sPath))
    {
        if (!mkdir($sPath)) throw new Exception("ERROR: Unable to create the directory '$sPath'. Cannot download any extension. Check the access rights on '".dirname('data/downloaded-extensions/')."'");
    }
    else
    {
    	// Make sure that the directory is empty
    	SetupUtils::tidydir($sPath);
    }
    
    foreach($aExtensions as $sBase64Archive)
    {
        $sArchive = base64_decode($sBase64Archive);
        
        $sZipArchiveFile = $sPath."/extension-{$i}.zip";
        file_put_contents($sZipArchiveFile, $sArchive);
        // Expand the content of extension-x.zip into  APPROOT.'data/downloaded-extensions/'
        // where the installation will load the extension automatically
        $oZip = new ZipArchive();
        if (!$oZip->open($sZipArchiveFile))
        {
            throw new Exception('Unable to open "'.$sZipArchiveFile.'" for extraction. Make sure that the directory "'.'data/downloaded-extensions/'.'" is writable for the web server.');
        }
        for($idx  = 0; $idx < $oZip->numFiles; $idx++)
        {
            $sCompressedFile = $oZip->getNameIndex($idx);
            $oZip->extractTo($sPath, $sCompressedFile);
        }
        @$oZip->close();
        @unlink($sZipArchiveFile); // Get rid of the temporary file
        $i++;
    }

    // Now scan the extensions and display a report of the extensions brought by the hub
    $sNextPage = utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'land.php', array('operation' => 'install'));
    $oPage->add_ready_script("window.location.href='$sNextPage'");

}

function DoInstall(WebPage $oPage)
{
	$sUID = hash('sha256', rand());
	file_put_contents(APPROOT.'data/hub/compile_authent', $sUID);
	
    $oPage->add_linked_stylesheet(utils::GetAbsoluteUrlModulesRoot().'itop-hub-connector/css/hub.css');
    $oPage->add('<table class="module-selection-banner"><tr>');
    $sBannerUrl = utils::GetAbsoluteUrlModulesRoot().'/itop-hub-connector/images/landing-extension.png';
    $oPage->add('<td><img style="max-height:72px; margin-right: 10px;" src="'.$sBannerUrl.'"/><td>');
    $oPage->add('<td><h2>'.Dict::S('iTopHub:InstallationWelcome').'</h2><td>');
    $oPage->add('</tr></table>');
    
    $oPage->set_title(Dict::S('iTopHub:Landing:Install'));
    $oPage->add('<div id="installation-summary" class="module-selection-body" style="position: relative">');
    
    
    // Now scan the extensions and display a report of the extensions brought by the hub
    // Now scan the extensions and display a report of the extensions brought by the hub
    $sPath = APPROOT.'data/downloaded-extensions/';
    $aExtraDirs = array();
    if (is_dir($sPath))
    {
        $aExtraDirs[] = $sPath; // Also read the extra downloaded-modules directory
    }
    $oExtensionsMap = new iTopExtensionsMap('production', true, $aExtraDirs);
    $oExtensionsMap->LoadChoicesFromDatabase(MetaModel::GetConfig());

    foreach($oExtensionsMap->GetAllExtensions() as $oExtension)
    {
        if ($oExtension->sSource == iTopExtension::SOURCE_REMOTE)
        {
        	if (count($oExtension->aMissingDependencies) > 0)
        	{
        		$oPage->add('<div class="choice">');
        		$oPage->add('<input type="checkbox" disabled>&nbsp;');
        		$sTitle = Dict::Format('iTopHub:InstallationEffect:MissingDependencies_Details', implode(', ', $oExtension->aMissingDependencies));
        		$oPage->add('<label><b>'.htmlentities($oExtension->sLabel, ENT_QUOTES, 'UTF-8').'</b> <span style="color:red" title="'.$sTitle.'">'.Dict::S('iTopHub:InstallationEffect:MissingDependencies').'<span></label>');
        		$oPage->add('<div class="description">');
        		$oPage->add('<p>');
        		if ($oExtension->sDescription != '')
        		{
        			$oPage->add(htmlentities($oExtension->sDescription, ENT_QUOTES, 'UTF-8').'</br>');
        		}
        		$oPage->add('</p>');
        		$oPage->add('</div>');
        		$oPage->add('</div>');
        	}
        	else
        	{
	            $aCSSClasses = array('landing-extension');
	            if ($oExtension->sInstalledVersion === '')
	            {
	                $aCSSClasses[] = 'landing-installation';
	                $sInstallation = Dict::Format('iTopHub:InstallationEffect:Install', $oExtension->sVersion);
	            }
	            else if ($oExtension->sInstalledVersion == $oExtension->sVersion)
	            {
	                $aCSSClasses[] = 'landing-no-change';
	                $sInstallation = Dict::Format('iTopHub:InstallationEffect:NoChange', $oExtension->sVersion);
	             }
	            else if (version_compare($oExtension->sInstalledVersion, $oExtension->sVersion, '<'))
	            {
	                $aCSSClasses[] = 'landing-upgrade';
	                $sInstallation = Dict::Format('iTopHub:InstallationEffect:Upgrade', $oExtension->sInstalledVersion, $oExtension->sVersion);
	            }
	            else
	            {
	                $aCSSClasses[] = 'landing-downgrade';
	                $sInstallation = Dict::Format('iTopHub:InstallationEffect:Downgrade', $oExtension->sInstalledVersion, $oExtension->sVersion);
	            }
	            $oPage->add('<div class="choice">');
	            $sCode = $oExtension->sCode;
	            $sDir = basename($oExtension->sSourceDir);
	            $oPage->add('<input type="checkbox" checked disabled data-extension-code="'.$sCode.'" data-extension-dir="'.$sDir.'">&nbsp;');
	            $oPage->add('<label><b>'.htmlentities($oExtension->sLabel, ENT_QUOTES, 'UTF-8').'</b> '.$sInstallation.'</label>');
	            $oPage->add('<div class="description">');
	            $oPage->add('<p>');
	            if ($oExtension->sDescription != '')
	            {
	                $oPage->add(htmlentities($oExtension->sDescription, ENT_QUOTES, 'UTF-8').'</br>');
	            }
	            $oPage->add('</p>');
	            $oPage->add('</div>');
	            $oPage->add('</div>');
        	}
        }
    }
    
    $oPage->add('<div id="hub-installation-feedback">');
    $oPage->add('<div id="hub-installation-progress-text">'.Dict::S('iTopHub:DatabaseBackupProgress').'</div>');
    $oPage->add('<div id="hub-installation-progress"></div>');
    $oPage->add('</div>');
    
    $oPage->add('</div>'); // module-selection-body


	$oPage->add_linked_stylesheet('../css/font-awesome/css/all.min.css');
	$oPage->add_linked_stylesheet('../css/font-awesome/css/v4-shims.min.css');


    $oPage->add('<div id="hub_installation_widget"></div>');
    $oPage->add('<fieldset id="database-backup-fieldset"><legend>'.Dict::S('iTopHub:DBBackupLabel').'</legend>');
    $oPage->add('<div id="backup_form"><input id="backup_checkbox" type="checkbox" checked><label for="backup_checkbox"> '.Dict::S('iTopHub:DBBackupSentence').'</label></div>');
    $oPage->add('<div id="backup_status"></div>');
    $oPage->add('</fieldset>');
    $oPage->add('<p style="text-align: center"><input type="button" id="hub_start_installation" type="button" disabled value="'.Dict::S('iTopHub:DeployBtn').'"/></p>');
    
    $sIframeUrl = utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'launch.php', array('target' => 'inform_after_setup'));
    $sStatusPageUrl = utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'land.php', array('operation' => 'done'));
    
    $aWidgetParams = array(
        'self_url' => utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'ajax.php', array('maintenance' => true)),
        'iframe_url' => $sIframeUrl, 
        'redirect_after_completion_url' => $sStatusPageUrl,
        'mysql_bindir' => MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''),
    	'labels' => array(
			'database_backup' => Dict::S('iTopHub:InstallationProgress:DatabaseBackup'),
    		'extensions_installation' => Dict::S('iTopHub:InstallationProgress:ExtensionsInstallation'),
    		'installation_successful' => Dict::S('iTopHub:InstallationProgress:InstallationSuccessful'),
    		'rollback' => Dict::S('iTopHub:ConfigurationSafelyReverted'),
    	),
	    'authent' => $sUID,
    );
    
    $sWidgetParams = json_encode($aWidgetParams);
    
    $oPage->add_ready_script("$('#hub_installation_widget').hub_installation($sWidgetParams);");
    $oPage->add_ready_script("$('#hub_start_installation').click(function() { $('#hub_installation_widget').hub_installation('start_installation');} );");
    $oPage->add_ready_script("$('#hub_installation_widget').hub_installation('check_before_backup');");
    $oPage->add('<div id="debug"></div>');
}


try
{
    require_once(APPROOT.'/application/application.inc.php');
    require_once(APPROOT.'/setup/setuppage.class.inc.php');
    require_once(APPROOT.'/setup/extensionsmap.class.inc.php');
    require_once(APPROOT.'/application/startup.inc.php');
    require_once(APPROOT.'/application/loginwebpage.class.inc.php');

    LoginWebPage::DoLoginEx(null, true /* $bMustBeAdmin */); // Check user rights and prompt if needed
    if (MetaModel::GetConfig()->Get('demo_mode')) throw new Exception('Sorry the installation of extensions is not allowed in demo mode');
    
    $oPage = new SetupPage(''); // Title will be set later, depending on $sOperation
    $oPage->add_linked_script(utils::GetAbsoluteUrlModulesRoot().'itop-hub-connector/js/hub.js');
    $oPage->add_linked_stylesheet('../css/font-combodo/font-combodo.css');
    
    $oPage->add_style("div.choice { margin: 0.5em;}");
    $oPage->add_style("div.choice a { text-decoration:none; font-weight: bold; color: #1C94C4 }");
    $oPage->add_style("div.description { margin-left: 2em; }");
    $oPage->add_style("div.description p { margin-top: 0.25em; margin-bottom: 0.5em; }");
    $oPage->add_style(".choice-disabled { color: #999; }");
    

    $sOperation = utils::ReadParam('operation', 'land');
    
    switch($sOperation)
    {
        case 'done':
        DisplayStatus($oPage);
        break;
        
        case 'install':
        if (!file_exists(APPROOT.'data/hub'))
        {
	        mkdir(APPROOT.'data/hub');
        }
        DoInstall($oPage);
        break;
            
        case 'land':
        default:
        DoLanding($oPage);
    }    
    
    $oPage->output();

}
catch(Exception $e)
{
    require_once(APPROOT.'/setup/setuppage.class.inc.php');
    $oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
    $oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");
    $oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));
    $oP->output();
    
    if (MetaModel::IsLogEnabledIssue())
    {
        if (MetaModel::IsValidClass('EventIssue'))
        {
            $oLog = new EventIssue();
            
            $oLog->Set('message', $e->getMessage());
            $oLog->Set('userinfo', '');
            $oLog->Set('issue', 'PHP Exception');
            $oLog->Set('impact', 'Page could not be displayed');
            $oLog->Set('callstack', $e->getTrace());
            $oLog->Set('data', array());
            $oLog->DBInsertNoReload();
        }
        
        IssueLog::Error($e->getMessage());
    }
}
