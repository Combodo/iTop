// JavaScript Document

function LinksWidget(id, sLinkedClass, sExtKeyToMe, sExtKeyToRemote, aAttributes)
{
	this.id = id;
	this.sLinkedClass = sLinkedClass;
	this.sExtKeyToMe = sExtKeyToMe;
	this.sExtKeyToRemote = sExtKeyToRemote;
	this.aAttributes = aAttributes;
	this.aLinks = new Array();

	this.Init = function()
	{
		sLinks = $('#'+this.id).val();
		if (sLinks.length > 0)
		{
			this.aLinks = JSON.parse(sLinks);
		}
		this.Refresh();
	}
		
	this.Refresh = function ()
	{
		$('#v_'+this.id).html('<img src="../images/indicator.gif" />');
		sLinks = JSON.stringify(this.aLinks);
		if (this.aLinks.length == 0)
		{
			$('#'+this.id+'_values').empty();		
			$('#'+this.id).val(sLinks);
			$('#'+this.id).trigger('validate');
		}
		else
		{
			$('#'+this.id).val(sLinks);
			$('#'+this.id+'_values').load('ajax.render.php?operation=ui.linkswidget.linkedset&sclass='+this.sLinkedClass+'&sextkeytome='+this.sExtKeyToMe+'&sextkeytoremote='+this.sExtKeyToRemote+'&myid='+this.id,
			{'sset' : sLinks}, function()
				{
					// Refresh the style of the loaded table
					$('#'+this.id+' table.listResults').tableHover();	
		 			$('#'+this.id+' .listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra', 'truncatedList']} ); // sortable and zebra tables
				}
			);
		}
	}
	
	this.OnOk = function()
	{
		this.aObjectBeingLinked = new Array();
		sSelected = 'selected_objects_'+this.id;
		oSelected = document.getElementById(sSelected);
		for(i=0; i<oSelected.length; i++)
		{
			this.aObjectBeingLinked[i] = oSelected.options[i].value;
		}
		this.aPreviousLinks = this.aLinks; // Save the list in case of cancellation
		this.aLinks = new Array(); // rebuild the list of links from scratch
		if (oSelected.length > 0)
		{
			$('#LinkDlg_'+this.id).dialog('open');
		}
		else
		{
			this.Refresh();
			$('#ac_add_'+this.id).attr('disabled', 'disabled');
		}
	}

	this.OnCancel = function()
	{
		// Restore the links to their previous value (just in case)
		this.aLinks = this.aPreviousLinks;
	}
	
	this.OnLinkOk = function()
	{
		$('#LinkDlg_'+this.id).dialog('close');
		for(i=0; i<this.aObjectBeingLinked.length; i++)
		{
			oLink = {};
			oLink[this.sExtKeyToRemote] = this.aObjectBeingLinked[i];
			for(j=0; j<this.aAttributes.length; j++)
			{
				oLink[aAttributes[j]] = $('#'+this.id+'_'+j).val();
			}
			this.aLinks.push(oLink);
		}
		this.Refresh();
		// Grey out the 'Add...' button
		$('#ac_add_'+this.id).attr('disabled', 'disabled');
		return false;
	}
	
	this.OnLinkCancel = function()
	{
		$('#LinkDlg_'+this.id).dialog('close');
		// Restore the links to their previous value (just in case)
		this.aLinks = this.aPreviousLinks;
		// Grey out the 'Add...' button
		$('#ac_add_'+this.id).attr('disabled', 'disabled');
		return false;
	}
	
	this.RemoveLink = function(index)
	{
		this.aLinks.splice(index, 1); // Remove the element at position 'index'
		this.Refresh();
	}
	
	this.AddObject = function()
	{
		linkedObjId = $('#id_ac_'+this.id).val();
		// Clears the selection
		$('#id_ac_'+this.id).val('');
		$('#ac_'+this.id).val('');
		// Add the object to the list
		this.aObjectBeingLinked = new Array();
		this.aObjectBeingLinked[0] = linkedObjId;
		// Add the object to the list of links
		this.aPreviousLinks = this.aLinks; // Save the list in case of cancellation
		$('#LinkDlg_'+this.id).dialog('open');
	}
	
	this.ModifyLink = function(index)
	{
		this.aObjectBeingLinked = new Array();
		this.aObjectBeingLinked[0] = this.aLinks[index][this.sExtKeyToRemote];
		this.aPreviousLinks = this.aLinks; // Save the list in case of cancellation
		// Set the default values of the dialog to the current ones
		for(j=0; j<this.aAttributes.length; j++)
		{
			$('#'+this.id+'_'+j).val(aLinks[index][aAttributes[j]]);
		}
		this.aLinks.splice(index, 1); // Remove the element at position 'index'
		$('#LinkDlg_'+this.id).dialog('open'); // And add it again	
	}
}
