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
CKEDITOR.dialog.add("anchor",function(c){function d(b,a){return b.createFakeElement(b.document.createElement("a",{attributes:a}),"cke_anchor","anchor")}return{title:c.lang.link.anchor.title,minWidth:300,minHeight:60,getModel:function(b){var a=b.getSelection();b=a.getRanges()[0];a=a.getSelectedElement();b.shrink(CKEDITOR.SHRINK_ELEMENT);(a=b.getEnclosedNode())&&a.type===CKEDITOR.NODE_TEXT&&(a=a.getParent());b=a&&a.type===CKEDITOR.NODE_ELEMENT&&("anchor"===a.data("cke-real-element-type")||a.is("a"))?
a:void 0;return b||null},onOk:function(){var b=CKEDITOR.tools.trim(this.getValueOf("info","txtName")),b={id:b,name:b,"data-cke-saved-name":b},a=this.getModel(c);a?a.data("cke-realelement")?(b=d(c,b),b.replace(a),CKEDITOR.env.ie&&c.getSelection().selectElement(b)):a.setAttributes(b):(a=(a=c.getSelection())&&a.getRanges()[0],a.collapsed?(b=d(c,b),a.insertNode(b)):(CKEDITOR.env.ie&&9>CKEDITOR.env.version&&(b["class"]="cke_anchor"),b=new CKEDITOR.style({element:"a",attributes:b}),b.type=CKEDITOR.STYLE_INLINE,
b.applyToRange(a)))},onShow:function(){var b=c.getSelection(),a=this.getModel(c),d=a&&a.data("cke-realelement");if(a=d?CKEDITOR.plugins.link.tryRestoreFakeAnchor(c,a):CKEDITOR.plugins.link.getSelectedLink(c)){var e=a.data("cke-saved-name");this.setValueOf("info","txtName",e||"");!d&&b.selectElement(a)}this.getContentElement("info","txtName").focus()},contents:[{id:"info",label:c.lang.link.anchor.title,accessKey:"I",elements:[{type:"text",id:"txtName",label:c.lang.link.anchor.name,required:!0,validate:function(){return this.getValue()?
!0:(alert(c.lang.link.anchor.errorName),!1)}}]}]}});