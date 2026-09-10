/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Overload of the default dialog widget
$.widget('ui.dialog', $.ui.dialog, {
    _allowInteraction: function (oEvent) {
        const oTarget = $(oEvent.target);
        // If we interact with a CKEditor instance in fullscreen mode, we need to allow it
        // We could check if the current instance is in the dialog, but it's easier to always allow it in fullscreen
        if (oTarget.closest('.ck.ck-fullscreen__main-wrapper, .ck-body-wrapper').length > 0) {
            return true;
        }

        // If that's not a specific case, fall back to the default behavior
        return this._super(oEvent);
    }
});
