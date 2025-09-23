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
 * Base element.
 *
 * @since 3.3.0
 */
class BaseElement extends HTMLElement {

    static {
        BaseElement.PageReady(() => {
            BaseElement.InstallNavigationTriggerComponents();
            BaseElement.InstallBackgroundDominantComponents();
        });
    }

    /**
     * Compute elements style color based on dominant color of an image.
     * Target image is defined with [data-role="image-dominant-color"] attribute set on a child element.
     * Color can have transparency component by setting data-dominant-transparency attribute.
     * The color is applied on style element defined by data-dominant-style-attribute attribute.
     * If no element is provided, document is used to search image background elements in.
     *
     * @param element element on witch to apply the background color
     * @constructor
     */
    static InstallBackgroundDominantComponents(element = null) {

        if (element === null) {
            element = document;
        }

        element.querySelectorAll('[data-role="image-dominant-color"]').forEach((element) => {

            // image used to compute color
            let imageId = element.getAttribute('data-dominant-image-id');

            // destination style attribute
            let styleAttribute = 'backgroundColor';
            if (element.hasAttribute('data-dominant-style-attribute')) {
                styleAttribute = element.getAttribute('data-dominant-style-attribute');
            }

            // transparency
            let imageTransparency = 1;
            if (element.hasAttribute('data-dominant-transparency')) {
                imageTransparency = element.getAttribute('data-dominant-transparency');
            }

            // get image average components
            let rgb = ImageToolkit.GetImageDominantRGB(document.getElementById(imageId));

            // set the background
            element.style[styleAttribute] = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${imageTransparency})`;
        });
    }

    /**
     * Open a navigation url by clicking navigation trigger elements.
     * The url is defined in the data-tile-navigation-url attribute.
     * The navigation may open a modal or redirect the page.
     * If no element is provided, document is used to search navigation trigger elements in.
     * Links and buttons have the highest priority, so the navigation will not be performed.
     *
     * @param element element where to search navigation trigger elements
     * @constructor
     */
    static InstallNavigationTriggerComponents(element = null) {

        if (element === null) {
            element = document;
        }

        // list elements...
        element.querySelectorAll('[data-role="navigation-trigger"]').forEach((eElement) => {

            // listen click event
            eElement.addEventListener('click', (oEvent) => {

                // prevent redirection when clicking on a button or a link that are not navigation-trigger
                if (oEvent.target.closest("a") && oEvent.target.closest("a").getAttribute('data-role') !== 'navigation-trigger' || oEvent.target.closest("button") && oEvent.target.closest("button").getAttribute('data-role') !== 'navigation-trigger') {
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

    /**
     * Toolkit to register callback on document ready.
     *
     * @param oFn callback function
     */
    static PageReady(oFn) {
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(oFn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", oFn);
        }
    }
}


