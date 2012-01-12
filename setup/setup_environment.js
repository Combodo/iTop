/**
 * Wrapper for the compilation of the whole data model into a given environment
 *  
 * @param string sSelectedModules  CSV list of selected modules
 * @param string sMode 'install' or 'upgrade'
 * @param string sSourceDir The directory containing the source modules (some may be already compiled)
 * @param string sTargetDir The target directory (is created if needed) for compiled modules
 * @return void
 */ 
function AsyncCompileDataModel(sSelectedModules, sMode, sSourceDir, sTargetDir, sWorkspaceDir, OnCompleteFn)
{
	try
	{
		$.post( GetAbsoluteUrlAppRoot()+'setup/ajax.dataloader.php',
						{ 
							'operation': 'compile_data_model',
							'selected_modules': sSelectedModules,
							'mode': sMode,
							'source_dir': sSourceDir,
							'target_dir': sTargetDir,
							'workspace_dir': sWorkspaceDir
						},
						OnCompleteFn, 'html');
	}
	catch(err)
	{
		alert('An exception occured: '+err);
	}
}

/**
 * Wrapper for the creation/update of a given DB/environment
 *  
 * @param string sSelectedModules  CSV list of selected modules
 * @param string sMode 'install' or 'upgrade'
 * @param string sModulesDir The directory in which the modules have been compiled
 * @param string sDBServer Database access...
 * @param string sDBUser ...
 * @param string sDBPwd ...
 * @param string sDBName Name of an existing DB
 * @param string sNewDBName Name of the new DB if sDBName is omitted
 * @param string sDBPrefix Prefix the tables (shared database)
 * @return void
 */ 
function AsyncUpdateDBSchema(sSelectedModules, sMode, sModulesDir, sDBServer, sDBUser, sDBPwd, sDBName, sNewDBName, sDBPrefix, OnCompleteFn)
{
	try
	{
		$.post( GetAbsoluteUrlAppRoot()+'setup/ajax.dataloader.php',
						{ 
							'operation': 'update_db_schema',
							'selected_modules': sSelectedModules,
							'mode': sMode,
							'modules_dir': sModulesDir,
							'db_server': sDBServer,
							'db_user': sDBUser,
							'db_pwd': sDBPwd,
							'db_name': sDBName,
							'new_db_name': sNewDBName,
							'db_prefix': sDBPrefix,
						},
						OnCompleteFn, 'html');
	}
	catch(err)
	{
		alert('An exception occured: '+err);
	}
}

/**
 * Wrapper for the creation/update for the user profiles (does create the admin user at creation), in a given environment
 * @param string sSelectedModules  CSV list of selected modules
 * @param string sMode 'install' or 'upgrade'
 * @param string sModulesDir The directory in which the modules have been compiled
 * @param string sDBServer Database access...
 * @param string sDBUser ...
 * @param string sDBPwd ...
 * @param string sDBName Name of an existing DB
 * @param string sNewDBName Name of the new DB if sDBName is omitted
 * @param string sDBPrefix Prefix the tables (shared database)
 * @param string sAuthUser Credentials for the administrator
 * @param string sAuthPwd
 * @param string sLanguage Language code for the administrator (e.g. 'EN US')
 * @return void
 */ 
function AsyncUpdateProfiles(sSelectedModules, sMode, sModulesDir, sDBServer, sDBUser, sDBPwd, sDBName, sNewDBName, sDBPrefix, sAuthUser, sAuthPwd, sLanguage, OnCompleteFn)
{
	try
	{
		$.post( GetAbsoluteUrlAppRoot()+'setup/ajax.dataloader.php',
						{ 
							'operation': 'after_db_create',
							'selected_modules': sSelectedModules,
							'mode': sMode,
							'modules_dir': sModulesDir,
							'db_server': sDBServer,
							'db_user': sDBUser,
							'db_pwd': sDBPwd,
							'db_name': sDBName,
							'new_db_name': sNewDBName,
							'db_prefix': sDBPrefix,
							'auth_user': sAuthUser,
							'auth_pwd': sAuthPwd,
							'language': sLanguage,
						},
						OnCompleteFn, 'html');
	}
	catch(err)
	{
		alert('An exception occured: '+err);
	}
}
