/**
 * Detects the current server's Content-Security-Policy to stop the setup if any directive doesn't meet the application's requirements
 *
 * @type {{_FindItopVersionInURI: (function(): string|string), aFlags: {bUnsafeInlineScriptOk: boolean, bUnsafeEvalScriptOk: boolean, bUnsafeInlineStyleOk: boolean}, _TestUnsafeEvalScript: SetupCSPDetection._TestUnsafeEvalScript, _HideContinueButtonIfPolicyNotOk: SetupCSPDetection._HideContinueButtonIfPolicyNotOk, _TestUnsafeInlineStyle: SetupCSPDetection._TestUnsafeInlineStyle, Run: SetupCSPDetection.Run, _TestUnSafeInlineScript: SetupCSPDetection._TestUnSafeInlineScript, _AddErrorAlert: SetupCSPDetection._AddErrorAlert}}
 * @since 2.7.11 3.0.5 3.1.2 3.2.0 N°7075
 */
SetupCSPDetection = {
	aFlags: {
		bUnsafeInlineScriptOk: false,
		bUnsafeEvalScriptOk: false,
		bUnsafeInlineStyleOk: false,
	},
	Run: function () {
		this._TestUnSafeInlineScript();
		this._TestUnsafeEvalScript();
		this._TestUnsafeInlineStyle();
		this._HideContinueButtonIfPolicyNotOk();
	},
	/**
	 * Test if the CSP "unsafe-inline" directive for script-src if enabled, otherwise it forbids the setup to go further
	 * @private
	 */
	_TestUnSafeInlineScript: function() {
		var sBaitElemID = "csp-detection--unsafe-inline-script-bait";

		// Add inline script that should add an element in the DOM
		var sAddedScript = '<script>$("body").append(\'<div id="' + sBaitElemID + '" class="ibo-is-hidden">If this is present in the DOM, then unsafe-inline for scripts policy is allowed</div>\')</script>';
		$("body").append(sAddedScript);

		// Check if element has been added to the DOM
		if ($("#" + sBaitElemID).length === 1) {
			this.aFlags.bUnsafeInlineScriptOk = true;
		} else {
			this._AddErrorAlert("unsafe-inline", "script");
		}
	},
	/**
	 * Test if the CSP "unsafe-eval" directive for script-src if enabled, otherwise it forbids the setup to go further
	 * @private
	 */
	_TestUnsafeEvalScript: function() {
		var sBaitElemID = "csp-detection--unsafe-eval-script-bait";

		// Add inline eval script that should add an element in the DOM
		var sAddedScript = '<script>eval(\'$("body").append(\\\'<div id="' + sBaitElemID + '" class="ibo-is-hidden">If this is present in the DOM, then unsafe-eval for scripts policy is allowed</div>\\\')\')</script>';
		$("body").append(sAddedScript);

		// Check if element has been added to the DOM
		if ($("#" + sBaitElemID).length === 1) {
			this.aFlags.bUnsafeEvalScriptOk = true;
		} else {
			this._AddErrorAlert("unsafe-eval", "script");
		}
	},
	/**
	 * Test if the CSP "unsafe-inline" directive for style-src if enabled, otherwise it forbids the setup to go further
	 * @private
	 */
	_TestUnsafeInlineStyle: function() {
		var sBaitElemID = "csp-detection--unsafe-inline-style-bait";

		// Add inline eval script that should add an element in the DOM
		$("body").append("<div id=\"" + sBaitElemID + "\">If this is present in the DOM and visible, then unsafe-inline for styles policy must be allowed</div>");
		$("body").append("<style>#" + sBaitElemID + " { display: none; }</style>");

		// Check if style has been applied
		if ($("#" + sBaitElemID).is(":visible") === false) {
			this.aFlags.bUnsafeInlineStyleOk = true;
		} else {
			// Remove bait div to avoid polluting the screen
			$("#" + sBaitElemID).remove();
			this._AddErrorAlert("unsafe-inline", "style");
		}
	},
	/**
	 * Hide continue button to prevent setup from going further if any policy is not OK
	 * @private
	 */
	_HideContinueButtonIfPolicyNotOk: function() {
		if (false === this.aFlags.bUnsafeInlineScriptOk || false === this.aFlags.bUnsafeEvalScriptOk || false === this.aFlags.bUnsafeInlineStyleOk) {
			// Hide next button to prevent user from going forward.
			// Note that we don't remove it completely to be able to by-pass it.
			$("#btn_next").addClass("ibo-is-hidden");
		}
	},
	/**
	 * Internal helper to add an error alert in case of failure
	 * @param {String} sPolicyOption e.g. "unsafe-inline", "unsafe-eval", ...
	 * @param {String} sResourceType script|style
	 * @private
	 */
	_AddErrorAlert: function(sPolicyOption, sResourceType) {
		var sFilesType = sResourceType === "script" ? "scripts" : "styles";

		// Add alert in the DOM
		$("<div class=\"message message-error\"><span class=\"message-title\">Error:</span>Your server Content-Security-Policy header doesn't allow <b>" + sPolicyOption + " " + sFilesType + "</b>. Therefore, the application cannot be installed (<a" +
			" href=\"https://www.itophub.io/wiki/page?id=" + this._FindItopVersionInURI() + ":install:security#content-security-policy\" target=\"_blank\">see documentation</a>).</div>")
			.insertAfter(
				$("#wiz_form h1:first")
			);

	},
	/**
	 * Internal helper to find the iTop version as wiki syntax from the script URI
	 * @returns {string|string}
	 * @private
	 */
	_FindItopVersionInURI: function() {
		// First find script tag for the current file
		var sScriptURI = $('script[src*="setup/csp-detection.js"]').attr("src");

		// Extract parameter value from URI
		var regex = new RegExp('[\\?&]' + 'itop_version_wiki_syntax' + '=([^&#]*)');
		var results = regex.exec(sScriptURI);
		return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
	}
};

$(document).ready(function() {
	SetupCSPDetection.Run();
});
