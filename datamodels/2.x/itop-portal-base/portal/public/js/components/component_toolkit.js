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
 * Component toolkit.
 *
 * @since 3.3.0
 */
class ComponentToolkit {

    /**
     * Get the components instances.
     *
     * @param imgEl
     * @returns {{r: number, g: number, b: number}}
     * @constructor
     */
    static GetAverageRGB(imgEl) {

        let blockSize = 5, // only visit every 5 pixels
            defaultRGB = {r: 0, g: 0, b: 0}, // for non-supporting envs
            canvas = document.createElement('canvas'),
            context = canvas.getContext && canvas.getContext('2d'),
            data, width, height,
            i = -4,
            length,
            rgb = {r: 0, g: 0, b: 0},
            count = 0;

        if (!context) {
            return defaultRGB;
        }

        height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
        width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;

        context.drawImage(imgEl, 0, 0);

        try {
            data = context.getImageData(0, 0, width, height);
        } catch (e) {
            /* security error, img on diff domain */
            return defaultRGB;
        }

        length = data.data.length;

        while ((i += blockSize * 4) < length) {
            ++count;
            rgb.r += data.data[i];
            rgb.g += data.data[i + 1];
            rgb.b += data.data[i + 2];
        }

        // ~~ used to floor values
        rgb.r = ~~(rgb.r / count);
        rgb.g = ~~(rgb.g / count);
        rgb.b = ~~(rgb.b / count);

        return rgb;

    }

    /**
     * Install the background dominant color for the image.
     *
     * @param component
     * @constructor
     */
    static InstallBackgroundDominantComponents(component = null) {

        let element = document;
        if (component !== null) {
            element = component;
        }

        element.querySelectorAll('[data-role="image-background-dominant"]').forEach((element) => {
            let rgb = ComponentToolkit.GetAverageRGB(element.querySelector('img'));
            element.style.backgroundColor = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.1)`;
        });
    }

    /**
     *
     * @param component
     * @constructor
     */
    static InstallNavigationTriggerComponents(component = null) {

        let element = document;
        if (component !== null) {
            element = component;
        }

        // list elements...
        element.querySelectorAll('[data-role="navigation-trigger"]').forEach((eElement) => {

            // listen click event
            eElement.addEventListener('click', (oEvent) => {

                // prevent redirection when clicking on a button or a link
                if (oEvent.target.closest("a") || oEvent.target.closest("button")) {
                    return;
                }

                // let's find the closest tile-navigation-trigger
                let eClosestTriggeredElement = oEvent.target.closest('[data-role="navigation-trigger"]');

                // retrieve navigation-trigger url
                let sUrl = eClosestTriggeredElement.getAttribute('data-tile-navigation-url');
                let sToggle = eClosestTriggeredElement.getAttribute('data-toggle');

                // open url
                if (sUrl !== null) {
                    if (sToggle === 'modal') {
                        CombodoModal.OpenUrlInModal(sUrl, true, () => {
                        });
                    } else {
                        document.location.href = sUrl;
                    }
                    oEvent.stopPropagation();
                }

            });

        });
    }
}


