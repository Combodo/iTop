// jQuery UI style "widget" for managing the "xlsx-exporter"
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "xlsxexporter" the widget name
	$.widget( "itop.xlsxexporter",
	{
		// default options
		options:
		{
			filter: '',
			ajax_page_url: '',
			labels: {dialog_title: 'Excel Export', export_button: 'Export', cancel_button: 'Cancel', download_button: 'Download', complete: 'Complete', cancelled: 'Cancelled' }
		},
	
		// the constructor
		_create: function()
		{
			this.element
			.addClass('itop-xlsxexporter');
			
			this.sToken = null;
			this.ajaxCall = null;
			this.oProgressBar = $('.progress-bar', this.element);
			this.oStatusMessage = $('.status-message', this.element);
			$('.progress', this.element).hide();
			$('.statistics', this.element).hide();
			
			var me = this;
			
			this.element.dialog({
				title: this.options.labels.dialog_title,
				modal: true,
				width: 500,
				height: 300,
				buttons: [
				    { text: this.options.labels.export_button, 'class': 'export-button', click: function() {
				    	me._start();
				    } },
				    { text: this.options.labels.cancel_button, 'class': 'cancel-button', click: function() {
				    	$(this).dialog( "close" );
				    } },
				],
				close: function() { me._abort(); $(this).remove(); }
			});
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		destroy: function()
		{
			this.element
			.removeClass('itop-xlsxexporter');
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._superApply(arguments);
		},
		_start: function()
		{
			var me = this;
			$('.export-options', this.element).hide();
			$('.progress', this.element).show();
			var bAdvanced = $('#export-advanced-mode').prop('checked');
			this.bAutoDownload = $('#export-auto-download').prop('checked');
			$('.export-button', this.element.parent()).button('disable');
			
			this.oProgressBar.progressbar({
				 value: 0,
				 change: function() {
					 var progressLabel = $('.progress-label', me.element);
					 progressLabel.text( $(this).progressbar( "value" ) + "%" );
				 },
				 complete: function() {
					 var progressLabel = $('.progress-label', me.element);
					 progressLabel.text( me.options.labels['complete'] );
				 }
			});

			//TODO disable the "export" button
			this.ajaxCall = $.post(this.options.ajax_page_url, {filter: this.options.filter, operation: 'xlsx_start', advanced: bAdvanced}, function(data) {
				this.ajaxCall = null;
				if (data && data.status == 'ok')
				{
					me.sToken = data.token;
					me._run();
				}
				else
				{
					if (data == null)
					{
						me.oStatusMessage.html('Unexpected error (operation=xlsx_start).');	
						me.oProgressBar.progressbar({value: 100});
					}
					else
					{
						me.oStatusMessage.html(data.message);										
					}
				}
			}, 'json');

		},
		_abort: function()
		{
			$('.cancel-button', this.element.parent()).button('disable');
			this.oStatusMessage.html(this.options.labels['cancelled']);
			this.oProgressBar.progressbar({value: 100});
			if (this.sToken != null)
			{
				// Cancel the operation in progress... or cleanup a completed export
				// TODO
				if (this.ajaxCall)
				{
					this.ajaxCall.abort();
					this.ajaxClass = null;
				}
				var me = this;
				$.post(this.options.ajax_page_url, {token: this.sToken, operation: 'xlsx_abort'}, function(data) {
					me.sToken = null;
				});
			}
		},
		_run: function()
		{
			var me = this;
			this.ajaxCall = $.post(this.options.ajax_page_url, {token: this.sToken, operation: 'xlsx_run'}, function(data) {
				this.ajaxCall = null;
				if (data == null)
				{
					me.oStatusMessage.html('Unexpected error (operation=xlsx_run).');
					me.oProgressBar.progressbar({value: 100});			
				}
				else if (data.status == 'error')
				{
					me.oStatusMessage.html(data.message);
					me.oProgressBar.progressbar({value: 100});
				}
				else if (data.status == 'done')
				{
					me.oStatusMessage.html(data.message);
					me.oProgressBar.progressbar({value: 100});
					$('.stats-data', this.element).html(data.statistics);
					me._on_completion();
				}
				else
				{
					// continue running the export in the background
					me.oStatusMessage.html(data.message);
					me.oProgressBar.progressbar({value: data.percentage});
					me._run();
				}
			}, 'json');
		},
		_on_completion: function()
		{
			var me = this;
			$('.progress', this.element).html('<form class="download-form" method="post" action="'+this.options.ajax_page_url+'"><input type="hidden" name="operation" value="xlsx_download"/><input type="hidden" name="token" value="'+this.sToken+'"/><button type="submit">'+this.options.labels['download_button']+'</button></form>');
			$('.download-form button', this.element).button().click(function() { me.sToken = null; window.setTimeout(function() { me.element.dialog('close'); }, 100); return true;});
			if (this.bAutoDownload)
			{
				me.sToken = null;
				$('.download-form').submit();
				this.element.dialog('close');
			}
			else
			{
				$('.statistics', this.element).show();
				$('.statistics .stats-toggle', this.element).click(function() { $(this).toggleClass('closed'); });
			}
		}
	});	
});

function XlsxExportDialog(sFilter)
{
	var sUrl = GetAbsoluteUrlAppRoot()+'pages/ajax.render.php';
	$.post(sUrl, {operation: 'xlsx_export_dialog', filter: sFilter}, function(data) {
		$('body').append(data);
	});
}
