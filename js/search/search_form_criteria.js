//iTop Search form criteria
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_criteria' the widget name
	$.widget( 'itop.search_form_criteria',
	{
		// default options
		options:
		{
			// Default values for the criteria
			'ref': '',
			'operator': '=',
			'values': [],
			'oql': '',
			'is_removable': true, // Not used for now. If we come to show locked criterion they will need to have this flag set to false.

			'field': {
				'label': '',
				'allowed_values': null,
				'is_null_allowed': false,
				'has_index': false,
			},
			// Available operators. They can be extended or restricted by derivated widgets (see this._initOperators() for more informations)
			'available_operators': {
				'=': {
					'label': Dict.S('UI:Search:Criteria:Operator:Default:Equals'),
					'code': 'equals',
					'rank': 10,
				},
				'empty': {
					'label': Dict.S('UI:Search:Criteria:Operator:Default:Empty'),
					'code': 'empty',
					'rank': 90,
				},
				'not_empty': {
					'label': Dict.S('UI:Search:Criteria:Operator:Default:NotEmpty'),
					'code': 'not_empty',
					'rank': 100,
				},
			},

			'init_opened': false,
			'is_modified': false, // TODO: change this on value change and remove oql property value
		},

		// Operators
		operators: null,
		// Form handler
		handler: null,
		// Keys that should not trigger an event in filter/autocomplete inputs
		filtered_keys: [9, 16, 17, 18, 19, 27, 33, 34, 35, 36, 37, 38, 39, 40], // Tab, Shift, Ctrl, Alt, Pause, Esc, Page Up/Down, Home, End, Left/Up/Right/Down arrows

		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('search_form_criteria');

			// Init properties (complexe type properties would be static if not initialized with a simple type variable...)
			this.operators = {};


            // Choose the default operator
            this._initChooseDefaultOperator();

			// Init operators
			this._initOperators();


			// Link search form handler
			this.handler = this.element.closest('.search_form_handler');

			// Bind events
			this._bindEvents();

			this._prepareElement();
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('search_form_criteria');
		},
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._super( key, value );
		},

		// Protected methods
		// - Init operators by cleaning up available operators and ordering them.
		//   Note: A null operator or an operator with a rank "false" will be removed.
		_initOperators: function()
		{
			// Reset operators
			this.operators = {};



			// Cancel empty/not_empty operators if field can't be null
			if(this.options.field.is_null_allowed === false)
			{
				this.options.available_operators.empty = null;
				this.options.available_operators.not_empty = null;
			}

			// Temp array to sort operators
			var aSortable = [];
			for(var sOpIdx in this.options.available_operators)
			{
				var oOp = this.options.available_operators[sOpIdx];

				// Some operator can be disabled by the derivated widget, so we check it.
				if(oOp !== null && oOp.rank !== false)
				{
					aSortable.push([sOpIdx, oOp.rank]);
				}
			}

			// Sort the array
			aSortable.sort(function(a, b){
				return a[1] - b[1];
			})

			// Populate this.operators
			for(var iIdx in aSortable)
			{
				var sOpIdx = aSortable[iIdx][0];
				this.operators[sOpIdx] = this.options.available_operators[sOpIdx];
			}

			// Fallback operator in case the current operator is not available. Should not happen.
			if(this.operators[this.options.operator] === undefined)
			{
				this.options.operator = Object.keys(this.operators)[0];
			}
		},
        _initChooseDefaultOperator: function()
		{
            //if the class has an index, in order to maximize the performance, we force the default operator to "equal"
			if (this.options.field.has_index && this.options.available_operators['='] != null && typeof this.options.available_operators['='] == 'object' && this.options.values.length == 0)
            {
                this.options.operator = '=';
                this.options.available_operators['='].rank = -1;//we want it to be the first displayed
            }
        },
		// - Bind external events
		_bindEvents: function()
		{
			var me = this;

			// Get criteria data
			this.element.on('itop.search.criteria.get_data', function(oEvent, oData){
				return me._onGetData(oData);
			});

			// Get/SetCurrentValues callbacks handler
			this.element.on('itop.search.criteria.get_current_values itop.search.criteria.set_current_values', function(oEvent, oData){
				oEvent.stopPropagation();

				var callback = me.options[oEvent.type+'_callback'];

				if(typeof callback === 'string')
				{
					return me[callback](oEvent, oData);
				}
				else if(typeof callback === 'function')
				{
					return callback(me, oEvent, oData);
				}
				else
				{
					me._trace('search form criteria: callback type must be a function or a existing function name of the widget');
					return false;
				}
			});

			// Close criteria
			this.element.on('itop.search.criteria.close', function(){
				me._apply();
				me._close();
			});

			this.element
				.on('input.form_criteria_add_title_on_value_change, change.form_criteria_add_title_on_value_change, non_interactive_change.form_criteria_add_title_on_value_change', 'input', function() {
					var inputElmt = $(this)
					inputElmt.attr('title', inputElmt.val());
				})
				.trigger('input')
			;
		},
		// - Cinematic
		//   - Open / Close criteria
		_open: function()
		{
			// Inform handler that a criteria is opening
			this.handler.triggerHandler('itop.search.criteria.opening');

			// Open criteria
			this._resetOperators();
			// - Open it first
			this.element.addClass('opened');
			// - Then only check if more menu is to close to the right side (otherwise we might not have the right element's position)
			var iFormWidth = this.element.closest('.search_form_handler').outerWidth();
			var iFormLeftPos = this.element.closest('.search_form_handler').offset().left;
			var iContentWidth = this.element.find('.sfc_form_group').outerWidth();
			var iContentLeftPos = this.element.find('.sfc_form_group').offset().left;
			if( (iContentWidth + iContentLeftPos) > (iFormWidth + iFormLeftPos - 10 /* Security margin */) )
			{
				this.element.addClass('opened_left');
			}

			// Focus on right input
			var oOpElemRadioChecked = this.element.find('.sfc_fg_operator .sfc_op_radio:checked');
			var oOpElemInputFirst = oOpElemRadioChecked.closest('.sfc_fg_operator').find('.sfc_op_content input[type="text"]:first');

			oOpElemInputFirst.filter(':not([data-no-auto-focus])').trigger('click').trigger('focus');

			this.element.find('.sfc_form_group').removeClass('advanced');

			if (!oOpElemInputFirst.is(':visible'))
			{
				this.element.find('.sfc_form_group').addClass('advanced');
			}

		},
		_close: function()
		{
			this.element.removeClass('opened_left');
			this.element.removeClass('opened');
			this._unmarkAsDraft();
		},
		_closeAll: function()
		{
			this.element.closest('.search_form_handler').find('.search_form_criteria').each(function(){
				$(this).triggerHandler('itop.search.criteria.close');
			});
		},
		_remove: function()
		{
			this.element.remove();

			var bHadValues = (Array.isArray(this.options.values) && (this.options.values.length > 0));
			this.handler.triggerHandler('itop.search.criteria.removed', {had_values: bHadValues});
		},
		//   - Mark / Unmark criteria as draft (new value not applied)
		_markAsDraft: function()
		{
			this.element.addClass('draft');
		},
		_unmarkAsDraft: function()
		{
			this.element.removeClass('draft');
		},
		//   - Apply / Cancel new value
		_apply: function()
		{
			// Find active operator
			var oActiveOpElem = this.element.find('.sfc_op_radio:checked').closest('.sfc_fg_operator');
			if(oActiveOpElem.length === 0)
			{
				this._trace('Could not apply new value as there seems to be no active operator.');
				return false;
			}

			// Get value from operator (polymorphic method)
			var sCallback = '_get' + this._toCamelCase(oActiveOpElem.attr('data-operator-code')) + 'OperatorValues';
			if(this[sCallback] === undefined)
			{
				this._trace('Callback ' + sCallback + ' is undefined, using _getOperatorValues instead.');
				sCallback = '_getOperatorValues';
			}
			var aValues = this[sCallback](oActiveOpElem);

			// Update widget
			var sOperator = oActiveOpElem.find('.sfc_op_radio').val();

			if( (this._getValuesAsText() !== this._getValuesAsText(aValues)) || (this.options.operator !== sOperator) )
			{
				this.is_modified = true;
				this.options.oql = '';
				this.options.values = aValues;
				this.options.operator = sOperator;
				this._setTitle();
				this._unmarkAsDraft();

				// Trigger event to handler
				this.handler.triggerHandler('itop.search.criteria.value_changed');
			}
		},


		// Event callbacks
		// - Internal events
		_onButtonSearch: function()
		{
			// Note: We do exactly as for apply, the form handler will manage the difference.
			this._onButtonApply();
		},
		_onButtonApply: function()
		{
			this._apply();
			this._close();
		},
		_onButtonCancel: function()
		{
			this._close();
		},
		_onButtonMore: function()
		{
			this.element.find('.sfc_form_group').addClass('advanced');
		},
		_onButtonLess: function()
		{
			this.element.find('.sfc_form_group').removeClass('advanced');
		},
		// - External events
        /**
		 *
         * @param oData
         * @return {*}|null return oCriteriaData or null if there is no value
         * @private
         */
		_onGetData: function(oData)
		{
            var bHasToReturnNull = true;
            // for operations without input text (empty/not empty) no values are present
            if (this.options.values.length == 0)
			{
				bHasToReturnNull = false;
			}
            for (oValue in this.options.values) {
				if (oValue.value != '')
				{
                    bHasToReturnNull = false;
				}
            };
            if (bHasToReturnNull)
			{
				return null;
			}

			var oCriteriaData = {
				'ref': this.options.ref,
				'operator': this.options.operator,
				'values': this.options.values,
				'is_removable': this.options.is_removable,
				'oql': this.options.oql,

				// Field data
				'class': this.options.field.class,
				'class_alias': this.options.field.class_alias,
				'code': this.options.field.code,
				'widget': this.options.field.widget,
			};
			return oCriteriaData;
		},


		// DOM element helpers
		// - Prepare element DOM structure
		_prepareElement: function()
		{
			var me = this;

			// Prepare base DOM structure
			this.element
				.append('<div class="sfc_header"><div class="sfc_title"></div><a class="sfc_toggle" href="#" aria-label="'+Dict.S('UI:Search:Criteria:Toggle')+'" data-tooltip-content="'+Dict.S('UI:Search:Criteria:Toggle')+'"><span class="fas fa-caret-down"></span></a></div>')
				.append('<div class="sfc_form_group ibo-form-group"><div class="sfc_fg_operators"></div><div class="sfc_fg_buttons"></div></div>');

			// Bind events
			// Note: No event to handle criteria closing when clicking outside of it as it is already handle by the form handler.
			// - Toggler
			this.element.on('click', '.sfc_toggle, .sfc_title', function(oEvent){
				// Prevent anchor
				oEvent.preventDefault();
                oEvent.stopPropagation();

				// First memorize if current criteria is close
				var bOpen = !me.element.hasClass('opened');
				// Then close every criterion
				me._closeAll();
				// Finally open current criteria if necessary
				if(bOpen === true)
				{
					me._open();
				}
			});
			this.element.on('keydown', function(oEvent){
				// Apply if "enter" key
				if(oEvent.key === 'Enter')
				{
					me._apply();

					// Keep criteria open only on Ctrl + Enter.
					if(oEvent.ctrlKey === false)
					{
						me._close();
					}
				}
				// Close if "escape" key
				else if(oEvent.key === 'Escape')
				{
					me._close();
				}
			});

			// Removable / locked decoration
			if(this.options.is_removable === true)
			{
				this.element.find('.sfc_header').append('<a class="sfc_close" href="#" aria-label="'+Dict.S('UI:Search:Criteria:Remove')+'" data-tooltip-content="'+Dict.S('UI:Search:Criteria:Remove')+'"><span class="fas fa-times"></span></a>');
				this.element.find('.sfc_close').on('click', function(oEvent){
					// Prevent anchor
					oEvent.preventDefault();

					me._remove();
				});
			}
			else
			{
				this.element.addClass('locked');
				this.element.find('.sfc_header').append('<span class="sfc_locked" aria-label="'+Dict.S('UI:Search:Criteria:Locked')+'" data-tooltip-content="'+Dict.S('UI:Search:Criteria:Locked')+'"><span class="fas fa-lock"></span></span>');
			}

			// Form group
			this._prepareOperators();
			this._prepareButtons();

			// Fill criteria
			// - Title
			this._setTitle();

			// Init opened to improve UX (toggle & focus in main operator's input)
			if(this.options.init_opened === true)
			{
				this._closeAll();
				this._open();
			}
		},
		// - Prepare the available operators for the criteria
		//   Meant for overloading.
		_prepareOperators: function()
		{
			for(var sOpIdx in this.operators)
			{
				var oOp = this.operators[sOpIdx];
				var sMethod = '_prepare' + this._toCamelCase(oOp.code) + 'Operator';

				// Create DOM element from template
				var oOpElem = $(this._getOperatorTemplate())
					.uniqueId()
					.appendTo(this.element.find('.sfc_fg_operators'));

				// Prepare operator's base elements
				this._prepareOperator(oOpElem, sOpIdx, oOp);

				// Prepare operator's specific elements
				if(this[sMethod] !== undefined)
				{
					this[sMethod](oOpElem, sOpIdx, oOp);
				}
				else
				{
					this._prepareDefaultOperator(oOpElem, sOpIdx, oOp);
				}
			}
		},
		// - Prepare the buttons (DOM and events) for a criteria
		_prepareButtons: function()
		{
			var me = this;

			// DOM elements
			this.element.find('.sfc_fg_buttons')
				.append('<button type="button" name="search" class="sfc_fg_button sfc_fg_search ibo-button ibo-is-neutral ibo-is-regular">' + Dict.S('UI:Button:Search') + '</button>')
				.append('<button type="button" name="apply" class="sfc_fg_button sfc_fg_apply ibo-button ibo-is-neutral ibo-is-regular">' + Dict.S('UI:Button:Apply') + '</button>')
				.append('<button type="button" name="cancel" class="sfc_fg_button sfc_fg_cancel ibo-button ibo-is-neutral ibo-is-regular">' + Dict.S('UI:Button:Cancel') + '</button>')
				.append('<button type="button" name="more" class="sfc_fg_button sfc_fg_more ibo-button ibo-is-neutral ibo-is-alternative">' + Dict.S('UI:Button:More') + '<span' + ' class="fas fa-angle-double-down"></span></button>')
				.append('<button type="button" name="less" class="sfc_fg_button sfc_fg_less">' + Dict.S('UI:Button:Less') + '<span' + ' class="fas fa-angle-double-up"></span></button>');

			// Events
			this.element.find('.sfc_fg_button').on('click', function(oEvent){
				oEvent.preventDefault();
				oEvent.stopPropagation();

				var sCallback = '_onButton' + me._toCamelCase($(this).attr('name'));
				me[sCallback]();
			});
		},
		// - Reset all operators but active one
		_resetOperators: function()
		{
			var me = this;

			// Reset all operators
			this.element.find('.sfc_fg_operator').each(function(){
				var sCallback = '_reset' + me._toCamelCase($(this).attr('data-operator-code')) + 'Operator';
				if(me[sCallback] === undefined)
				{
					sCallback = '_resetOperator';
				}
				me[sCallback]($(this));
			});

			// Set value on current operator
			var sCurrentOpCode = this.operators[this.options.operator].code;
			this.element.find('.sfc_fg_operator[data-operator-code="' + sCurrentOpCode + '"]').each(function(){
				// Check radio (we don't use .trigger('click'), otherwise the criteria will be seen as draft.
				$(this).find('.sfc_op_radio').prop('checked', true);

				// Reset values
				var sCallback = '_set' + me._toCamelCase(sCurrentOpCode) + 'OperatorValues';
				if(me[sCallback] === undefined)
				{
					sCallback = '_setOperatorValues';
				}
				me[sCallback]($(this), me.options.values);
			});
		},
		// - Compute the title string
		_computeTitle: function(sTitle)
		{
			if(sTitle !== undefined)
			{
				return sTitle;
			}


			var sCallback = '_compute' + this._toCamelCase(this.operators[this.options.operator].code) + 'OperatorTitle';
			if(this[sCallback] !== undefined)
			{
				var sCallbackTitle = this[sCallback](sTitle);
				if (sCallbackTitle !== undefined)
				{
					sTitle = sCallbackTitle;
					return sTitle;
				}
			}


			var sValueAsText = this._getValuesAsText();
			var sOperator = (sValueAsText !== '') ? this.operators[this.options.operator].code : 'Any';
			var sDictEntry = 'UI:Search:Criteria:Title:' + this._toCamelCase(this.options.field.widget) + ':' + this._toCamelCase(sOperator);

			// Fallback to default widget dict entry if none exists for the current widget
			if(Dict.S(sDictEntry) === sDictEntry)
			{
				sDictEntry = 'UI:Search:Criteria:Title:Default:' + this._toCamelCase(sOperator);
			}

			sTitle = Dict.Format(sDictEntry, this.options.field.label, '<span class="sfc_values">'+sValueAsText+'</span>');

			// Last chande fallback
			if(sTitle === sDictEntry)
			{
				sTitle = this.options.label;
			}

			return sTitle;
		},
        _computeEmptyOperatorTitle: function(sTitle) {
            if (sTitle !== undefined) {
                return sTitle;
            }

            sTitle = Dict.Format('UI:Search:Criteria:Title:Default:Empty', this.options.field.label);

            return sTitle;
        },
        _computeNotEmptyOperatorTitle: function(sTitle) {
            if (sTitle !== undefined) {
                return sTitle;
            }

            sTitle = Dict.Format('UI:Search:Criteria:Title:Default:NotEmpty', this.options.field.label);

            return sTitle;
        },
		// - Set the title element
		_setTitle: function(sTitle)
		{
			sTitle = this._computeTitle(sTitle);

			var titleElem = this.element.find('.sfc_title');

			titleElem.html(sTitle);
			titleElem.attr('aria-label', titleElem.text());
			titleElem.attr('data-tooltip-content', titleElem.text());
			CombodoTooltip.InitTooltipFromMarkup(titleElem, true);
		},

		// Operators helpers
		// - Return a HTML template for operators
		_getOperatorTemplate: function()
		{
			return '<div class="sfc_fg_operator"><label><input type="radio" class="sfc_op_radio" name="operator" /><span class="sfc_op_name"></span><span class="sfc_op_content"></span></label></div>';
		},
		// Prepare operator's DOM element
		// - Base preparation, always called
		_prepareOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;
			var sInputId = oOp.code + '_' + oOpElem.attr('id');

			// Set radio
			oOpElem.find('.sfc_op_radio').val(sOpIdx).trigger('non_interactive_change');
			oOpElem.find('.sfc_op_radio').attr('id', sInputId);

			// Set label
			oOpElem.find('.sfc_op_name').text(oOp.label);
			oOpElem.find('> label').attr('for', sInputId);

			// Set helper classes
			oOpElem.addClass('sfc_fg_operator_' + oOp.code)
				.attr('data-operator-code', oOp.code);

			// Bind events
			// - Check radio button on click and mark criteria as draft
			oOpElem.on('click focusin', function(){
				var bIsChecked = oOpElem.find('.sfc_op_radio').prop('checked');

				if(bIsChecked === false)
				{
					oOpElem.find('.sfc_op_radio').prop('checked', true);
					me._markAsDraft();
				}
			});
		},
		// - Fallback for operator that has no dedicated callback
		_prepareDefaultOperator: function(oOpElem, sOpIdx, oOp)
		{
			var me = this;

			// DOM element
			var oOpContentElem = $('<input type="text" />');
			oOpContentElem.val(this._getValuesAsText()).trigger('non_interactive_change');

			// Events
			// - Focus input on click (radio, label, ...)
			oOpElem.on('click', ':not(input[type="text"], select)', function(oEvent) {
				// Stopping propagation like this instead of oEvent.stopPropagation() as the event could be used by something.
				if ($(oEvent.target).is('input[type="text"], select')) {
					return;
				}
				oOpContentElem.focus();
			});
			// - Mark as draft on key typing
			oOpContentElem.on('keydown', function(oEvent){
				me._markAsDraft();
			});

			oOpElem.find('.sfc_op_content').append(oOpContentElem);
		},
		_prepareEmptyOperator: function(oOpElem, sOpIdx, oOp)
		{
			// Do nothing as only the label is necessary
		},
		_prepareNotEmptyOperator: function(oOpElem, sOpIdx, oOp)
		{
			// Do nothing as only the label is necessary
		},
		// Reset operator's state
		// - Fallback for operator that has no dedicated callback
		_resetOperator: function(oOpElem)
		{
			oOpElem.find('.sfc_op_content input').val('').trigger('non_interactive_change');
		},
		// Get operator's values
		// - Fallback for operators without a specific callback
		_getOperatorValues: function(oOpElem)
		{
			var aValues = [];

			oOpElem.find('.sfc_op_content input').each(function(){
				var sValue = $(this).val();
				aValues.push({value: sValue, label: sValue});
			});

			return aValues;
		},
		// Set operator's values
		// - Fallback for operators without a specific callback
		_setOperatorValues: function(oOpElem, aValues)
		{
			if(aValues.length === 0)
			{
				return false;
			}

			oOpElem.find('.sfc_op_content input').each(function(){
				$(this).val(aValues[0].value).trigger('non_interactive_change');
			});

			return true;
		},


		// Values helpers
		// - Check if criteria has allowed values either preloaded or through autocomplete
		_hasAllowedValues: function()
		{
			return ( (this.options.field.allowed_values !== undefined) && (this.options.field.allowed_values !== null) );
		},
		// - Check if criteria has preloaded allowed values (as opposed to autocomplete)
		_hasPreloadedAllowedValues: function()
		{
			if(this._hasAllowedValues() && (this.options.field.allowed_values.values !== undefined) && (this.options.field.allowed_values.values !== null))
			{
				return true;
			}

			return false;
		},
		// - Return the preloaded allowed values (not coming from autocomplete)
		_getPreloadedAllowedValues: function()
		{
			return (this._hasPreloadedAllowedValues()) ? this.options.field.allowed_values.values : {};
		},
		// - Check if criteria has allowed values that should be loaded through autocomplete
		_hasAutocompleteAllowedValues: function()
		{
			if(this._hasAllowedValues() && (this.options.field.allowed_values.autocomplete === true) )
			{
				return true;
			}

			return false;
		},
		// - Return the allowed values from the autocomplete
		_getAutocompleteAllowedValues: function()
		{
			// Meant for overloading.
		},
		// - Return current values
		_getValues: function()
		{
			return this.options.values;
		},
		// - Convert values to a standard string
		_getValuesAsText: function(aRawValues)
		{
			if (aRawValues == undefined)
			{
				aRawValues = this._getValues();
			}


			var aValues = [];
			for(var iValueIdx in aRawValues)
			{
				var sEscapedLabel = $('<div />').text(aRawValues[iValueIdx].label).html();
				aValues.push(sEscapedLabel);
			}

			return aValues.join(', ');
		},
		// - Make an OQL expression from the criteria values and operator
		_makeOQLExpression: function()
		{
			var aValues = [];
			var sOQL = '';

			for(var iValueIdx in this.options.values)
			{
				aValues.push( '\'' + this.options.values[iValueIdx].value + '\'' );
			}
			sOQL += '(`' + this.options.ref + '`) ' + this.options.operator + ' ' + aValues.join(', ') + ')';

			return sOQL;
		},


		// Global helpers
		// - Converts a snake_case string to CamelCase
		_toCamelCase: function(sString)
		{
			if( (sString === undefined) || (sString === null) )
			{
				return sString;
			}

			var aParts = sString.split('_');

			for(var i in aParts)
			{
				aParts[i] = aParts[i].charAt(0).toUpperCase() + aParts[i].substr(1);
			}

			return aParts.join('');
		},
		// - Return if the given keycode is among filtered
		_isFilteredKey: function(iKeyCode)
		{
			return (this.filtered_keys.indexOf(iKeyCode) >= 0);
		},


		// Debug helpers
		// - Show a trace in the javascript console
		_trace: function(sMessage, oData)
		{
			if(window.console)
			{
				if(oData !== undefined)
				{
					console.log('Search form criteria: ' + sMessage, oData);
				}
				else
				{
					console.log('Search form criteria: ' + sMessage);
				}
			}
		},
		// - Show current options
		showOptions: function()
		{
			this._trace('Options', this.options);
		}
	});
});
