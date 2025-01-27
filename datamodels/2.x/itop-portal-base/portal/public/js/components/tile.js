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
 * Component to manipulate a tile.
 *
 * @since 3.3.0
 */
class IpbTile extends Component {

    static {
        Component.PageReady(() => {
            ComponentToolkit.InstallNavigationTriggerComponents();
        });
    }

    /**
     * Constructor.
     *
     * @param sId HTMLElement id
     * @param sName tile name
     */
    constructor(sId, sName) {
        super(sId, sName, (eComponent) => {
            ComponentToolkit.InstallBackgroundDominantComponents(eComponent);
        });

    }

    SetTitle(sText) {
        this.GetComponent().querySelector('.ipb-tile--title').textContent = sText;
    }

    SetDecorationClass(sClassName) {
        this.GetComponent().querySelector('.ipb-tile--decoration').className = `ipb-tile--decoration ${sClassName}`;
    }

    SetIconClass(sClassName) {
        this.GetComponent().querySelector('.ipb-tile--decoration').innerHTML = `<span class="ipb-tile--decoration--icon icon ${sClassName}"></span>`;
    }
}