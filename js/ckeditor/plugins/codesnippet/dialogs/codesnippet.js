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
(function(){CKEDITOR.dialog.add("codeSnippet",function(c){var b=c._.codesnippet.langs,d=c.lang.codesnippet,g=document.documentElement.clientHeight,e=[],f;e.push([c.lang.common.notSet,""]);for(f in b)e.push([b[f],f]);b=CKEDITOR.document.getWindow().getViewPaneSize();c=Math.min(b.width-70,800);b=b.height/1.5;650>g&&(b=g-220);return{title:d.title,minHeight:200,resizable:CKEDITOR.DIALOG_RESIZE_NONE,contents:[{id:"info",elements:[{id:"lang",type:"select",label:d.language,items:e,setup:function(a){a.ready&&
a.data.lang&&this.setValue(a.data.lang);!CKEDITOR.env.gecko||a.data.lang&&a.ready||(this.getInputElement().$.selectedIndex=-1)},commit:function(a){a.setData("lang",this.getValue())}},{id:"code",type:"textarea",label:d.codeContents,setup:function(a){this.setValue(a.data.code)},commit:function(a){a.setData("code",this.getValue())},required:!0,validate:CKEDITOR.dialog.validate.notEmpty(d.emptySnippetError),inputStyle:"cursor:auto;width:"+c+"px;height:"+b+"px;tab-size:4;text-align:left;","class":"cke_source"}]}]}})})();