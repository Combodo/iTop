/*
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * Base component.
 *
 * @since 3.3.0
 */
class Component {

    // store the components instances
    static aList = [];

    /**
     * Constructor.
     *
     * @param sId HTMLElement id
     * @param sName component name
     * @param oComponentReadyCallback callback to execute when the component is ready
     */
    constructor(sId, sName = '', oComponentReadyCallback = null) {
        this.sId = sId;
        this.sName = sName;
        this.oComponentReadyCallback = oComponentReadyCallback;
        Component.aList[sId] = this;
        this.#Init();
    }

    /**
     * Initialize the component.
     */
    #Init() {
        this.eComponent = document.getElementById(this.sId);
        if (this.oComponentReadyCallback) {
            this.oComponentReadyCallback(this.eComponent);
        }
    }

    /**
     * Get the HTMLElement id.
     *
     */
    GetId() {
        return this.sId;
    }

    /**
     * Get the HTMLElement name.
     *
     */
    GetName() {
        return this.sName;
    }

    /**
     * Get the HTMLElement.
     *
     */
    GetComponent() {
        return this.eComponent;
    }

    /**
     * Return a component instance.
     *
     * @param id
     */
    static GetInstance(id) {
        return Component.aList[id];
    }

    /**
     * Toolkit to register callback on document ready.
     *
     * @param oFn
     */
    static PageReady(oFn) {
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(oFn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", oFn);
        }
    }

}


