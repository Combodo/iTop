<?php

/**
 * Get approot.inc.php
 * Move to a function for allowing a better testing
 * 
 * @param string $sAppRootFilename
 * @throws \Exception
 */
function StatusGetAppRoot($sAppRootFilename = 'approot.inc.php') 
{
        $sAppRootFile = dirname(__FILE__).'/'.$sAppRootFilename;

        /*
        * Check that the approot file exists and has the appropriate access rights
        */
        if (!file_exists($sAppRootFile) || !is_readable($sAppRootFile)) 
        {
            throw new \Exception($sAppRootFile . ' is not readable');
        }
        require_once($sAppRootFile);
}

/**
 * Check iTop's config File existence and readability
 * Move to a function for allowing a better testing
 * 
 * @param string $sConfigFilename
 * @throws \Exception
 */
function StatusCheckConfigFile($sConfigFilename = 'config-itop.php')
{
        StatusGetAppRoot();
        
        $sConfigFile = APPCONF.ITOP_DEFAULT_ENV.'/'.$sConfigFilename;

        /**
         * Check that the configuration file exists and has the appropriate access rights
         */  
        if (!file_exists($sConfigFile) || !is_readable($sConfigFile)) 
        {
                throw new \Exception($sConfigFile . ' is not readable');
        }
}

/**
 * Start iTop's application for checking with its internal basic test every it's alright (DB connection, ...)
 * Move to a function for allowing a better testing
 * 
 * @param \Config $oConfig
 */
function StatusStartup(\Config $oConfig = null)
{
        StatusCheckConfigFile();
        
        require_once(APPROOT.'/core/cmdbobject.class.inc.php');
        require_once(APPROOT.'/application/utils.inc.php');
        require_once(APPROOT.'/core/contexttag.class.inc.php');
        
        $soConfigFile = (empty($oConfig)) ? ITOP_DEFAULT_CONFIG_FILE  : $oConfig;
        
        //Check if aplication could be started
        \MetaModel::Startup($soConfigFile, true /* $bModelOnly */);
}
