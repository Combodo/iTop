//jQuery UI style "widget" for running the installation
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "fieldsorter" the widget name
	$.widget( "itop.hub_installation",
	{
		// default options
		options:
		{
			self_url: '',
			redirect_after_completion_url: '',
			iframe_url: '',
			mysql_bindir: '',
			main_page_url: './UI.php',
			labels: {
				database_backup: 'Database backup...',
				extensions_installation: 'Installation of the extensions...',
				installation_successful: 'Installation successful!',
				rollback: 'iTop configuration has NOT been modified.'
			},
			authent : ''
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
			.addClass('itop-hub_installation');
			
			$( document ).ajaxError( function(event, jqxhr, settings, thrownError) { me._on_ajax_error(event, jqxhr, settings, thrownError);} );
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-hub_installation');
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function(key, value)
		{
			if (this.options[key] != value)
			{
				// If any option changes, clear the cache
				this._clearCache();
			}
			
			this._superApply(arguments);
		},
		check_before_backup: function()
		{
			var me = this;
			$.post(this.options.self_url, {operation: 'check_before_backup', mysql_bindir: this.options.mysql_bindir}, function(data) { me._on_check_before_backup(data) }, 'json');
		},
		_on_check_before_backup: function(data)
		{
			if (data.code == 0)
			{
				$('#backup_status').html(data.message);
				$('#backup_form').show();
			}
			else
			{
				$('#backup_status').html(data.message);
				$('#backup_checkbox').prop('checked', false);
			}
			if ($('#hub_start_installation').hasClass('ui-button'))
			{
				$('#hub_start_installation').button('enable');				
			}
			else
			{
				$('#hub_start_installation').prop('disabled', false);	
			}
		},
		backup: function()
		{
			var me = this;
			$('#hub-installation-progress-text').html('<i class="fas fa-database"></i> '+this.options.labels.database_backup);
			$.post(this.options.self_url, {operation: 'do_backup'}, function(data) { me._on_backup(data) }, 'json');						
		},
		_on_backup: function(data)
		{
			if (data.code == 0)
			{
				this._reportSuccess(data.message);				
				// continue to the compilation
				this.compile();
			}
			else
			{
				this._reportError(data.message);				
			}
		},
		compile: function()
		{
			$('#hub-installation-progress-text').html('<i class="fas fa-cogs"></i> '+this.options.labels.extensions_installation);
			var me = this;
			var aExtensionCodes = [];
			var aExtensionDirs = [];
			$('.choice :input:checked').each(function() { aExtensionCodes.push($(this).attr('data-extension-code'));  aExtensionDirs.push($(this).attr('data-extension-dir')); });
			$.post(this.options.self_url, {operation: 'compile', extension_codes: aExtensionCodes, extension_dirs: aExtensionDirs, authent: this.options.authent}, function(data) { me._on_compile(data) }, 'json');			
		},
		_on_compile: function(data)
		{
			if (data.code == 0)
			{
				this._reportSuccess(data.message);		
				// continue to the move to prod
				this.move_to_prod();
			}
			else
			{
				this._reportError(data.message);				
			}
		},
		move_to_prod: function()
		{
			$('#hub-installation-progress-text').html('<i class="fas fa-cogs"></i> '+this.options.labels.extensions_installation);
			var me = this;
			$.post(this.options.self_url, {operation: 'move_to_production', authent: this.options.authent}, function(data) { me._on_move_to_prod(data) }, 'json');
		},
		_on_move_to_prod: function(data)
		{
			if (data.code == 0)
			{
				this._reportSuccess(data.message);
				$('#hub-installation-progress-text').html('<i class="fas fa-trophy"></i> '+this.options.labels.installation_successful);
				// Report the installation status to iTop Hub
				$('#at_the_end').append('<iframe style="border:0; width:200px; height: 20px;" src="'+this.options.iframe_url+'">Support of IFRAMES required, sorry</iframe>');
				if (this.options.redirect_after_completion_url != '')
				{
					var sUrl = this.options.redirect_after_completion_url;
					window.setTimeout(function() { window.location.href = sUrl; }, 3000);					
				}
			}
			else
			{
				this._reportError(data.message);				
			}
		},
		start_installation: function()
		{
			$('#installation-summary :input').prop('disabled', true); // Prevent changing the settings
			$('#database-backup-fieldset').hide();
			$('#hub-installation-feedback').show();
			if ($('#hub_start_installation').hasClass('ui-button'))
			{
				$('#hub_start_installation').button('disable');				
			}
			else
			{
				$('#hub_start_installation').prop('disabled', true);	
			}
			if ($('#backup_checkbox').prop('checked'))
			{
				this.backup();
			}
			else
			{
				this.compile();
			}
		},
		_reportError: function(sMessage)
		{
			$('#hub-installation-progress-text').html('<span style="color:red; font-size:12pt; line-height:18pt;"><i class="fas'+' fa-exclamation-triangle"></i> '+this.options.labels.rollback+'</span><br/><span style="color:#666; display:block; padding:10px;max-height:10em; overflow: auto;padding-top:0; margin-top:10px; text-align:left;">'+sMessage+'</span>');
			$('#hub_start_installation').val('Go Back to iTop');
			$('#hub_start_installation').prop('disabled', false);
			$('#hub-installation-progress').hide();
			var me = this;
			$('#hub_start_installation').off('click').on('click', function() { window.location.href = me.options.main_page_url; })
		},
		_reportSuccess: function(sMessage)
		{
		},
		_on_ajax_error: function(event, jqxhr, settings, thrownError)
		{
			this._reportError(jqxhr.responseText);
		}
	});
});