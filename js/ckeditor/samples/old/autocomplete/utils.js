/*
 * Copyright (C) 2013-2019 Combodo SARL
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
var autocompleteUtils={generateData:function(a,c){return Object.keys(a).sort().map(function(a,b){return{id:b,name:c+a}})},getAsyncDataCallback:function(a){return function(c,d,b){setTimeout(function(){b(a.filter(function(a){return 0===a.name.indexOf(c)}))},500*Math.random())}},getSyncDataCallback:function(a){return function(c,d,b){b(a.filter(function(a){return 0===a.name.indexOf(c)}))}},getTextTestCallback:function(a,c,d){function b(a,c){var b=a.slice(0,c),e=a.slice(c),b=b.match(f);return!b||d&&e&&
!e.match(/^\s/)?null:{start:b.index,end:c}}var f=function(){var b=a+"\\w",b=c?b+("{"+c+",}"):b+"*";return new RegExp(b+"$")}();return function(a){return a.collapsed?CKEDITOR.plugins.textMatch.match(a,b):null}}};