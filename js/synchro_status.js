// jQuery UI style "widget" for displaying a graph

////////////////////////////////////////////////////////////////////////////////
//
// graph
//
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "synchro_status" the widget name
	$.widget( "itop.synchro_status",
	{
		// default options
		options:
		{
			data: [],
			labels: {

			},
		},
	
		// the constructor
		_create: function()
		{
			var me = this;
			this.element
			.addClass('itop-synchro-status');
			console.log('synchro_status, data=', this.options.data);
			
			this.max_replicas = this.options.data.reduce(function(max, d) { return Math.max(max, d.nb_replica_total); }, 0);
			this._draw();
		},
		// called when created, and later when changing options
		_refresh: function()
		{
		},
		_draw: function ()
		{
			var detailsHeight = 600;
			var historyHeight = 50
			var textLineHeight = 20;
			var width = 1000;
			var padding = 10;
			var itemWidth = 10;
			var itemPadding = 1;
			var me = this;
			var svg = d3.select(this.element[0])
			.append('svg')
				.attr('width', '100%')
				.attr('height', historyHeight + padding + detailsHeight)
			.append('g');
			var max_replicas = this.max_replicas;
			var rectHeight = function(n) { 
				return n*historyHeight/max_replicas;
			};
			var details = svg.append('g');

			details.append('rect')
				.attr('y', historyHeight + padding)
				.attr('height', detailsHeight)
				.attr('width', 20)
				.attr('class', 'ibo-synchro-details-replicas');
			
			details.append('text')
					.attr('y', historyHeight + padding + 0.5*detailsHeight + 0.5*textLineHeight)
					.attr('x', 30)
					.attr('text-anchor', 'start')
					.text('Replicas (0)')
					.attr('class', 'ibo-synchro-details-replicas');

			var aReplicaStatus = 
			[
				{id: 'ignored', height: detailsHeight/4, yOffset: 0, opacity: 1.0, label: 'Ignored (0)' },
				{id: 'disappeared', height: detailsHeight/4, yOffset: 0, opacity: 1.0, label: 'Disappeared (0)' },
				{id: 'existing', height: detailsHeight/4, yOffset: 0, opacity: 1.0, label: 'Existing (0)' },
				{id: 'new', height: detailsHeight/4, yOffset: 0, opacity: 1.0, label: 'New (0)' }
			]
			
			var yOffset = 0;
			
			for(var k in aReplicaStatus)
			{
				yOffset += aReplicaStatus[k].yOffset;
				
				details.append('rect')
					.attr('y', historyHeight + padding + yOffset + 1)
					.attr('x', width/2 - 10)
					.attr('height', aReplicaStatus[k].height - 2)
					.attr('width', 20)
					.attr('class', 'ibo-synchro-replica-status ibo-synchro-details-'+aReplicaStatus[k].id);
				details.append('text')
					.attr('y', historyHeight + padding + yOffset + 0.5*aReplicaStatus[k].height + 0.5*textLineHeight)
					.attr('x', width/2 + 20)
					.attr('text-anchor', 'start')
					.attr('class', 'ibo-synchro-replica-status ibo-synchro-details-'+aReplicaStatus[k].id)
					.text(aReplicaStatus[k].label);
					
				yOffset	+= aReplicaStatus[k].height;
			}
			
			var aObjectStatus = 
			[
				{id: 'no_action', height: detailsHeight/16, yOffset: detailsHeight/4, opacity: 1.0, label: 'No Action (0)' },
				{id: 'deleted', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Deleted (0)' },
				{id: 'obsoleted', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Obsoleted (0)' },
				{id: 'errors', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Errors (0)' },

				{id: 'updated', height: detailsHeight/12, yOffset: 0, opacity: 1.0, label: 'Updated (0)' },
				{id: 'unchanged', height: detailsHeight/12, yOffset: 0, opacity: 1.0, label: 'Unchanged (0)' },
				{id: 'errors', height: detailsHeight/12, yOffset: 0, opacity: 1.0, label: 'Errors (0)' },

				{id: 'unchanged', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Unchanged (0)' },
				{id: 'updated', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Updated (0)' },
				{id: 'created', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Created (0)' },
				{id: 'errors', height: detailsHeight/16, yOffset: 0, opacity: 1.0, label: 'Errors (0)' },
			]
			
			var yOffset = 0;
			
			for(var k in aObjectStatus)
			{
				yOffset += aObjectStatus[k].yOffset;
				
				details.append('rect')
					.attr('y', historyHeight + padding + yOffset + 1)
					.attr('x', width - 20)
					.attr('height', aObjectStatus[k].height - 2)
					.attr('width', 20)
					.attr('class', 'ibo-synchro-object-status ibo-synchro-details-'+aObjectStatus[k].id);
				details.append('text')
					.attr('y', historyHeight + padding + yOffset + 0.5*aObjectStatus[k].height + 0.5*textLineHeight)
					.attr('x', width - 30)
					.attr('text-anchor', 'end')
					.attr('class', 'ibo-synchro-object-status ibo-synchro-details-'+aObjectStatus[k].id)
					.text(aObjectStatus[k].label);
					
				yOffset	+= aObjectStatus[k].height;
			}
			
			svg.selectAll('.ibo-synchro-log-item')
			.data(this.options.data)
			.enter().append('rect')
				.attr('class', 'synchro_log')
				.attr('width', itemWidth - itemPadding)
				.attr('x', function(d,i) { return itemWidth*(me.options.data.length - i) })
				.attr('y', function(d,i) { return historyHeight - rectHeight(d.nb_replica_total) })
				.attr('height', function(d, i) { return rectHeight(d.nb_replica_total); })
				.attr('fill', '#929fb1')
				.attr('class', 'ibo-synchro-log-item')
				.attr('data-log-index', function(d, i) { return i; })
				.attr('data-tooltip-content', function(d) { return d.end_date+' - '+d.nb_replica_total+' replicas'; })
				.on('click', me._onClick);
				
			svg.selectAll('rect.ibo-synchro-log-item').each(function() { tippy(this, { content: $(this).attr('data-tooltip-content') } ); } );			
		},
		_onClick: function(d)
		{
			var detailsHeight = 600;
			var historyHeight = 50
			var textLineHeight = 16;
			var width = 1000;
			var padding = 10;
			var variableHeight = detailsHeight - 12*textLineHeight;
			var svg = d3.select('#synchro_status_widget svg');
			replicas_text = svg.select('text.ibo-synchro-details-replicas');
			replicas_text.text('Replicas ('+d.nb_replica_total+')');

			var aReplicaStatus = 
			[
				{id: 'ignored', height: textLineHeight + (variableHeight * d.repl_ignored/d.nb_replica_total), opacity: (d.repl_ignored > 0) ? 1.0 : 0.4, label: 'Ignored ('+d.repl_ignored+')' },
				{id: 'disappeared', height: 4*textLineHeight + (variableHeight * d.repl_disappeared/d.nb_replica_total), opacity: (d.repl_disappeared > 0) ? 1.0 : 0.4, label: 'Disappeared ('+d.repl_disappeared+')' },
				{id: 'existing', height: 3*textLineHeight + (variableHeight * d.repl_existing/d.nb_replica_total), opacity: (d.repl_existing > 0) ? 1.0 : 0.4, label: 'Existing ('+d.repl_existing+')' },
				{id: 'new', height: 4*textLineHeight + (variableHeight * d.repl_new/d.nb_replica_total), opacity: (d.repl_new > 0) ? 1.0 : 0.4, label: 'New ('+d.repl_new+')' }
			];
			yOffset = historyHeight + padding;
			for(var k in aReplicaStatus)
			{
				aReplicaStatus[k].yOffset = yOffset;
				yOffset += aReplicaStatus[k].height;
			}
			
			svg.selectAll('rect.ibo-synchro-replica-status')
				.data(aReplicaStatus)
				.transition()
					.attr('opacity', d => d.opacity)
					.attr('y', d => d.yOffset)
					.attr('height', d => d.height - 2);
				
			svg.selectAll('text.ibo-synchro-replica-status')
				.data(aReplicaStatus)
				.text(d => d.label )
				.transition()
					.attr('opacity', d => d.opacity)
					.attr('y', d => d.yOffset + d.height/2 + textLineHeight/2);
					
			variableHeight = variableHeight - aReplicaStatus[0].height;

			var aObjectStatus = 
			[
				{id: 'no_action', height: textLineHeight + variableHeight*d.obj_disappeared_no_action/d.nb_replica_total, opacity: (d.obj_disappeared_no_action > 0) ? 1.0 : 0.4, label: 'No Action ('+d.obj_disappeared_no_action+')' },
				{id: 'deleted', height: textLineHeight + variableHeight*d.obj_deleted/d.nb_replica_total, opacity: (d.obj_deleted > 0) ? 1.0 : 0.4, label: 'Deleted ('+d.obj_deleted+')' },
				{id: 'obsoleted', height: textLineHeight + variableHeight*d.obj_obsoleted/d.nb_replica_total, opacity: (d.obj_obsoleted > 0) ? 1.0 : 0.4, label: 'Obsoleted ('+d.obj_obsoleted+')' },
				{id: 'errors', height: textLineHeight + variableHeight*d.obj_disappeared_errors/d.nb_replica_total, opacity: (d.obj_disappeared_errors > 0) ? 1.0 : 0.4, label: 'Errors ('+d.obj_disappeared_errors+')' },

				{id: 'updated', height: textLineHeight + variableHeight*d.obj_updated/d.nb_replica_total, opacity: (d.obj_updated > 0) ? 1.0 : 0.4, label: 'Updated ('+d.obj_updated+')' },
				{id: 'unchanged', height: textLineHeight + variableHeight*d.obj_unchanged/d.nb_replica_total, opacity: (d.obj_unchanged > 0) ? 1.0 : 0.4, label: 'Unchanged ('+d.obj_unchanged+')' },
				{id: 'errors', height: textLineHeight + variableHeight*d.obj_updated_errors/d.nb_replica_total, opacity: (d.obj_updated_errors > 0) ? 1.0 : 0.4, label: 'Errors ('+d.obj_updated_errors+')' },

				{id: 'unchanged', height: textLineHeight + variableHeight*d.obj_new_unchanged/d.nb_replica_total, opacity: (d.obj_new_unchanged > 0) ? 1.0 : 0.4, label: 'Unchanged ('+d.obj_new_unchanged+')' },
				{id: 'updated', height: textLineHeight + variableHeight*d.obj_new_updated/d.nb_replica_total, opacity: (d.obj_new_updated > 0) ? 1.0 : 0.4, label: 'Updated ('+d.obj_new_updated+')' },
				{id: 'created', height: textLineHeight + variableHeight*d.obj_created/d.nb_replica_total, opacity: (d.obj_created > 0) ? 1.0 : 0.4, label: 'Created ('+d.obj_created+')' },
				{id: 'errors', height: textLineHeight + variableHeight*d.obj_new_errors/d.nb_replica_total, opacity: (d.obj_new_errors > 0) ? 1.0 : 0.4, label: 'Errors ('+d.obj_new_errors+')' },
			]

			yOffset = historyHeight + padding + aReplicaStatus[0].height;
			for(var k in aObjectStatus)
			{
				aObjectStatus[k].yOffset = yOffset;
				yOffset += aObjectStatus[k].height;
			}
			
			svg.selectAll('rect.ibo-synchro-object-status')
				.data(aObjectStatus)
				.transition()
					.attr('opacity', d => d.opacity)
					.attr('y', d => d.yOffset)
					.attr('height', d => d.height - 2);
				
			svg.selectAll('text.ibo-synchro-object-status')
				.data(aObjectStatus)
				.text(d => d.label )
				.transition()
					.attr('opacity', d => d.opacity)
					.attr('y', d => d.yOffset + d.height/2 + textLineHeight/2);
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			var sId = this.element.attr('id');
			this.element
			.removeClass('itop-synchro-status');
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
		draw: function()
		{
		}
	});	
});
