/**
 * External key grouping (tree display) for iTop DataTables.
 *
 * Activated when a DataTable has the 'sTreeGroupingAttr' option set (via DashletObjectList).
 * Transforms a flat list of objects into an expandable/collapsible tree grouped by parent ID.
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license   http://opensource.org/licenses/AGPL-3.0
 */
var iTopDataTableExternalKeyGrouping = (function ($) {
	'use strict';

	// Inject CSS once
	var bCssInjected = false;
	function _injectCss() {
		if (bCssInjected) {
			return;
		}
		bCssInjected = true;
		$('<style>')
			.prop('type', 'text/css')
			.html(
				'.ibo-datatable--tree-toggle,' +
				'.ibo-datatable--tree-leaf {' +
				'  display: inline-block;' +
				'  width: 16px;' +
				'  margin-right: 4px;' +
				'  text-align: center;' +
				'}' +
				'.ibo-datatable--tree-toggle {' +
				'  cursor: pointer;' +
				'  color: #666;' +
				'}' +
				'.ibo-datatable--tree-toggle:hover {' +
				'  color: #333;' +
				'}' +
				'.ibo-datatable--tree-spacer {' +
				'  display: inline-block;' +
				'}' +
				'.dataTables_scrollHead th.ibo-datatable--tree-sortable {' +
				'  cursor: pointer;' +
				'  user-select: none;' +
				'}' +
				'.dataTables_scrollHead th.ibo-datatable--tree-sortable::after {' +
				'  font-family: "Font Awesome 5 Free";' +
				'  font-weight: 900;' +
				'  content: "\\f0dc";' +
				'  margin-left: 6px;' +
				'  opacity: 0.4;' +
				'  font-size: 0.85em;' +
				'}' +
				'.dataTables_scrollHead th.ibo-datatable--tree-sort-asc::after {' +
				'  content: "\\f0de";' +
				'  opacity: 1;' +
				'}' +
				'.dataTables_scrollHead th.ibo-datatable--tree-sort-desc::after {' +
				'  content: "\\f0dd";' +
				'  opacity: 1;' +
				'}' +
				/* Suppress ::after on the minimised thead clone that DataTables injects
				   into scrollBody on every draw — it copies scrollHead classes verbatim,
				   so our sort-icon classes would show there too without this rule. */
				'.dataTables_scrollBody thead th::after {' +
				'  display: none !important;' +
				'}'
			)
			.appendTo('head');
	}

	/**
	 * Build node map and parent-child relationships from flat data.
	 *
	 * @param {Array}  aData      - flat row array (each item must have an 'id' field)
	 * @param {string} sParentKey - data key that holds the parent object ID (e.g. "MyClass/parent_id/raw")
	 * @returns {{roots: Array, nodeMap: Object}}
	 */
	function _buildTree(aData, sParentKey) {
		var oNodeMap = {};
		var aRoots = [];

		// First pass: create a node for every row
		aData.forEach(function (oRow) {
			oNodeMap[String(oRow.id)] = $.extend(true, {}, oRow, {
				_itop_children: [],
				_itop_has_children: false,
				_itop_parent_node_id: null,
				_itop_depth: 0
			});
		});

		// Second pass: wire parent ↔ child links
		aData.forEach(function (oRow) {
			var sId = String(oRow.id);
			var sParentId = String(oRow[sParentKey] || '');

			if (sParentId && sParentId !== '0' && sParentId !== sId && oNodeMap[sParentId]) {
				oNodeMap[sParentId]._itop_children.push(oNodeMap[sId]);
				oNodeMap[sParentId]._itop_has_children = true;
				oNodeMap[sId]._itop_parent_node_id = sParentId;
			} else {
				aRoots.push(oNodeMap[sId]);
			}
		});

		return {roots: aRoots, nodeMap: oNodeMap};
	}

	/**
	 * Sort an array of nodes by a data key.
	 *
	 * @param {Array}  aNodes   - nodes to sort (not mutated; a copy is returned)
	 * @param {string} sSortKey - data key to compare (uses raw values for numeric/locale sort)
	 * @param {string} sSortDir - 'asc' or 'desc'
	 * @returns {Array}
	 */
	function _sortNodes(aNodes, sSortKey, sSortDir) {
		if (!sSortKey) {
			return aNodes;
		}
		return aNodes.slice().sort(function (a, b) {
			var va = a[sSortKey] !== undefined ? a[sSortKey] : '';
			var vb = b[sSortKey] !== undefined ? b[sSortKey] : '';
			// Numeric-aware locale comparison
			var nCmp = String(va).localeCompare(String(vb), undefined, {numeric: true, sensitivity: 'base'});
			return sSortDir === 'desc' ? -nCmp : nCmp;
		});
	}

	/**
	 * Flatten the tree in depth-first order, sorting siblings at each level.
	 *
	 * @param {Array}  aNodes   - nodes at the current level (roots or children)
	 * @param {number} iDepth   - depth of these nodes (0 = root)
	 * @param {string} sSortKey - data key used for sibling sorting
	 * @param {string} sSortDir - 'asc' or 'desc'
	 * @returns {Array} - flat list in display order
	 */
	function _flattenTree(aNodes, iDepth, sSortKey, sSortDir) {
		var aResult = [];
		var aSorted = _sortNodes(aNodes, sSortKey, sSortDir);
		aSorted.forEach(function (oNode) {
			oNode._itop_depth = iDepth;
			aResult.push(oNode);
			if (oNode._itop_children.length > 0) {
				aResult = aResult.concat(_flattenTree(oNode._itop_children, iDepth + 1, sSortKey, sSortDir));
			}
		});
		return aResult;
	}

	/**
	 * Filter the sorted flat list to hide descendants of collapsed nodes.
	 *
	 * @param {Array}  aSortedData - full DFS-ordered flat list
	 * @param {Object} oNodeMap    - {id → node} map
	 * @param {Set}    oCollapsed  - set of collapsed node IDs
	 * @returns {Array}
	 */
	function _getVisibleData(aSortedData, oNodeMap, oCollapsed) {
		return aSortedData.filter(function (oRow) {
			var sParentId = oRow._itop_parent_node_id;
			while (sParentId) {
				if (oCollapsed.has(sParentId)) {
					return false;
				}
				var oParent = oNodeMap[sParentId];
				sParentId = oParent ? oParent._itop_parent_node_id : null;
			}
			return true;
		});
	}

	/**
	 * Determine the sort key to use when the user clicks column iColIndex.
	 * - For '_key_' columns: sort by friendlyname (human-readable label).
	 * - For all other columns: sort by the '/raw' variant of the attribute.
	 *
	 * @param {DataTables.Api} oDt
	 * @param {number}         iColIndex
	 * @returns {string|null}
	 */
	function _getSortKey(oDt, iColIndex) {
		var oSettings = oDt.settings()[0];
		if (iColIndex < 0 || iColIndex >= oSettings.aoColumns.length) {
			return null;
		}
		var oCol = oSettings.aoColumns[iColIndex];
		if (!oCol) {
			return null;
		}
		var sMData = oCol.mData;
		if (typeof sMData !== 'string') {
			return null;
		}
		if (sMData.match(/\/_key_$/)) {
			// Friendly name is always loaded; it sorts better than the numeric DB id
			return sMData.replace(/\/_key_$/, '/friendlyname');
		}
		// External key attributes store a numeric ID in /raw — use /friendlyname instead
		if (oCol.metadata && typeof oCol.metadata.attribute_type === 'string' &&
			oCol.metadata.attribute_type.indexOf('ExternalKey') !== -1) {
			return sMData + '/friendlyname';
		}
		return sMData + '/raw';
	}

	/**
	 * Initialize hierarchical mode for an already-created DataTable.
	 *
	 * The DataTable must have been created with serverSide:false, paging:false,
	 * ordering:false, and data:[].  This function populates it with tree-structured
	 * data and wires up collapse/expand and column-sort interactions.
	 *
	 * @param {string}         sTableId  - DOM id of the <table> element (without #)
	 * @param {DataTables.Api} oDt       - DataTables API instance
	 * @param {Object}         oConfig
	 * @param {string}         oConfig.parentKey  - data key for parent ID
	 *                                              (e.g. "MyClass/parent_id/raw")
	 * @param {string}         oConfig.ajaxUrl    - URL used to refresh data
	 * @param {Object}         oConfig.ajaxData   - POST params for the search operation
	 * @param {Object}         oConfig.initData   - pre-loaded server data
	 *                                              ({draw, recordsTotal, data:[…]})
	 */
	function setup(sTableId, oDt, oConfig) {
		_injectCss();

		var oState = {
			allData: [],
			sortedData: [],
			nodeMap: {},
			collapsed: new Set(),
			sortKey: null,
			sortDir: 'asc'
		};

		// ── Rebuild tree from oState.allData ──────────────────────────────────────
		function _rebuild() {
			var oTree = _buildTree(oState.allData, oConfig.parentKey);
			oState.nodeMap = oTree.nodeMap;
			oState.sortedData = _flattenTree(oTree.roots, 0, oState.sortKey, oState.sortDir);
		}

		// ── Update DataTable with currently visible rows ───────────────────────────
		function _redraw() {
			var aVisible = _getVisibleData(oState.sortedData, oState.nodeMap, oState.collapsed);
			oDt.clear();
			oDt.rows.add(aVisible);
			oDt.draw(false);
		}

		// ── Add indent spacer + toggle button to the first cell of each row ───────
		function _applyTreeUi() {
			oDt.rows().every(function () {
				var oTr = this.node();
				var oData = this.data();
				if (!oTr || $(oTr).hasClass('ibo-datatable--tree-processed')) {
					return;
				}
				$(oTr).addClass('ibo-datatable--tree-processed');

				var iDepth = oData._itop_depth || 0;
				var sNodeId = String(oData.id);
				var $firstCell = $(oTr).find('td').eq(0);

				// Indentation spacer
				var $spacer = $('<span class="ibo-datatable--tree-spacer"></span>')
					.css('width', (iDepth * 20) + 'px');

				if (oData._itop_has_children) {
					var bCollapsed = oState.collapsed.has(sNodeId);
					var $toggle = $('<span></span>')
						.addClass('ibo-datatable--tree-toggle fas ' + (bCollapsed ? 'fa-caret-right' : 'fa-caret-down'))
						.attr('data-node-id', sNodeId);
					$firstCell.prepend($toggle).prepend($spacer);
				} else {
					$firstCell.prepend($('<span class="ibo-datatable--tree-leaf"></span>')).prepend($spacer);
				}
			});
		}

		// ── Attach sort click handlers to visible column headers ──────────────────
		function _attachSortHandlers() {
			// DataTables with scrollX duplicates the header; the user sees/clicks the
			// copy in .dataTables_scrollHead.  Attach there first, fall back to the
			// table's own <thead> if no wrapper exists.
			var $wrapper = $('#' + sTableId).closest('.dataTables_wrapper');
			var $ths = $wrapper.find('.dataTables_scrollHead thead th');
			if ($ths.length === 0) {
				$ths = $('#' + sTableId + ' thead th');
			}

			$ths.each(function (iIdx) {
				var sSortKey = _getSortKey(oDt, iIdx);
				if (sSortKey) {
					$(this).addClass('ibo-datatable--tree-sortable');
				}
			});

			$ths.off('click.itop-external-key-grouping').on('click.itop-external-key-grouping', function () {
				var iColIndex = $(this).index();
				var sSortKey = _getSortKey(oDt, iColIndex);
				if (!sSortKey) {
					return;
				}

				if (oState.sortKey === sSortKey) {
					oState.sortDir = oState.sortDir === 'asc' ? 'desc' : 'asc';
				} else {
					oState.sortKey = sSortKey;
					oState.sortDir = 'asc';
				}

				// Update visual indicators
				$ths.removeClass('ibo-datatable--tree-sort-asc ibo-datatable--tree-sort-desc');
				$(this).addClass('ibo-datatable--tree-sort-' + oState.sortDir);

				_rebuild();
				_redraw();
			});
		}

		// ── Event: collapse / expand a node ───────────────────────────────────────
		$('#' + sTableId).on('click', '.ibo-datatable--tree-toggle', function (e) {
			e.stopPropagation();
			var sNodeId = $(this).attr('data-node-id');
			if (oState.collapsed.has(sNodeId)) {
				oState.collapsed.delete(sNodeId);
			} else {
				oState.collapsed.add(sNodeId);
			}
			_redraw();
		});

		// ── Event: apply tree UI after every DataTable draw ───────────────────────
		$('#' + sTableId).on('draw.dt', function () {
			_applyTreeUi();
		});

		// ── Event: refresh (triggered externally via refresh.datatable.itop) ──────
		$('#' + sTableId).on('refresh.datatable.itop', function () {
			$.post(oConfig.ajaxUrl, $.extend({}, oConfig.ajaxData, {start: 0, end: 0, draw: 1}))
				.done(function (oResponse) {
					oState.allData = (oResponse && oResponse.data) ? oResponse.data : [];
					oState.collapsed = new Set();
					_rebuild();
					_redraw();
				});
		});

		// ── Bootstrap with pre-loaded initData (no extra round-trip needed) ───────
		if (oConfig.initData && Array.isArray(oConfig.initData.data)) {
			oState.allData = oConfig.initData.data;
		}

		_rebuild();
		_redraw();
		_attachSortHandlers();
	}

	return {setup: setup};

}(jQuery));
