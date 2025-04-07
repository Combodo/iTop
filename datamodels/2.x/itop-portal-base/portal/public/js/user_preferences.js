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

class UserPreferences {

	constructor(sUrl) {
		this.sUrl = sUrl;
	}

	setPreference(key, value) {

		let $data = new FormData();
		$data.append("key", key);
		$data.append("value", value);

		fetch(this.sUrl, {
			method: "POST",
			body: $data,
		}).then(
			(response) => {
				if (!response.ok) {
					throw new Error(`Network response was not ok: ${response.statusText}`);
				}
				return response.json();
			}
		).catch(
			(error) => {
				console.error('Unable to set user preference:', error);
			}
		);
	}



}