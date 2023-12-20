!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports):"function"==typeof define&&define.amd?define(["exports"],e):e((t="undefined"!=typeof globalThis?globalThis:t||self).sifter={})}(this,(function(t){"use strict"
const e=t=>(t=t.filter(Boolean)).length<2?t[0]||"":1==i(t)?"["+t.join("")+"]":"(?:"+t.join("|")+")",r=t=>{if(!s(t))return t.join("")
let e="",r=0
const n=()=>{r>1&&(e+="{"+r+"}")}
return t.forEach(((s,o)=>{s!==t[o-1]?(n(),e+=s,r=1):r++})),n(),e},n=t=>{let r=u(t)
return e(r)},s=t=>new Set(t).size!==t.length,o=t=>(t+"").replace(/([\$\(-\+\.\?\[-\^\{-\}])/g,"\\$1"),i=t=>t.reduce(((t,e)=>Math.max(t,l(e))),0),l=t=>u(t).length,u=t=>Array.from(t),a=t=>{if(1===t.length)return[[t]]
let e=[]
const r=t.substring(1)
return a(r).forEach((function(r){let n=r.slice(0)
n[0]=t.charAt(0)+n[0],e.push(n),n=r.slice(0),n.unshift(t.charAt(0)),e.push(n)})),e},f=[[0,65535]]
let c,h
const d={},g={"/":"⁄∕",0:"߀",a:"ⱥɐɑ",aa:"ꜳ",ae:"æǽǣ",ao:"ꜵ",au:"ꜷ",av:"ꜹꜻ",ay:"ꜽ",b:"ƀɓƃ",c:"ꜿƈȼↄ",d:"đɗɖᴅƌꮷԁɦ",e:"ɛǝᴇɇ",f:"ꝼƒ",g:"ǥɠꞡᵹꝿɢ",h:"ħⱨⱶɥ",i:"ɨı",j:"ɉȷ",k:"ƙⱪꝁꝃꝅꞣ",l:"łƚɫⱡꝉꝇꞁɭ",m:"ɱɯϻ",n:"ꞥƞɲꞑᴎлԉ",o:"øǿɔɵꝋꝍᴑ",oe:"œ",oi:"ƣ",oo:"ꝏ",ou:"ȣ",p:"ƥᵽꝑꝓꝕρ",q:"ꝗꝙɋ",r:"ɍɽꝛꞧꞃ",s:"ßȿꞩꞅʂ",t:"ŧƭʈⱦꞇ",th:"þ",tz:"ꜩ",u:"ʉ",v:"ʋꝟʌ",vy:"ꝡ",w:"ⱳ",y:"ƴɏỿ",z:"ƶȥɀⱬꝣ",hv:"ƕ"}
for(let t in g){let e=g[t]||""
for(let r=0;r<e.length;r++){let n=e.substring(r,r+1)
d[n]=t}}const p=new RegExp(Object.keys(d).join("|")+"|[̀-ͯ·ʾʼ]","gu"),m=(t,e="NFKD")=>t.normalize(e),b=t=>u(t).reduce(((t,e)=>t+y(e)),""),y=t=>(t=m(t).toLowerCase().replace(p,(t=>d[t]||"")),m(t,"NFC"))
const w=t=>{const e={},r=(t,r)=>{const s=e[t]||new Set,i=new RegExp("^"+n(s)+"$","iu")
r.match(i)||(s.add(o(r)),e[t]=s)}
for(let e of function*(t){for(const[e,r]of t)for(let t=e;t<=r;t++){let e=String.fromCharCode(t),r=b(e)
r!=e.toLowerCase()&&(r.length>3||0!=r.length&&(yield{folded:r,composed:e,code_point:t}))}}(t))r(e.folded,e.folded),r(e.folded,e.composed)
return e},v=t=>{const r=w(t),s={}
let i=[]
for(let t in r){let e=r[t]
e&&(s[t]=n(e)),t.length>1&&i.push(o(t))}i.sort(((t,e)=>e.length-t.length))
const l=e(i)
return h=new RegExp("^"+l,"u"),s},S=(t,n=1)=>(n=Math.max(n,t.length-1),e(a(t).map((t=>((t,e=1)=>{let n=0
return t=t.map((t=>(c[t]&&(n+=t.length),c[t]||t))),n>=e?r(t):""})(t,n))))),x=(t,n=!0)=>{let s=t.length>1?1:0
return e(t.map((t=>{let e=[]
const o=n?t.length():t.length()-1
for(let r=0;r<o;r++)e.push(S(t.substrs[r]||"",s))
return r(e)})))},j=(t,e)=>{for(const r of e){if(r.start!=t.start||r.end!=t.end)continue
if(r.substrs.join("")!==t.substrs.join(""))continue
let e=t.parts
const n=t=>{for(const r of e){if(r.start===t.start&&r.substr===t.substr)return!1
if(1!=t.length&&1!=r.length){if(t.start<r.start&&t.end>r.start)return!0
if(r.start<t.start&&r.end>t.start)return!0}}return!1}
if(!(r.parts.filter(n).length>0))return!0}return!1}
class _{constructor(){this.parts=[],this.substrs=[],this.start=0,this.end=0}add(t){t&&(this.parts.push(t),this.substrs.push(t.substr),this.start=Math.min(t.start,this.start),this.end=Math.max(t.end,this.end))}last(){return this.parts[this.parts.length-1]}length(){return this.parts.length}clone(t,e){let r=new _,n=JSON.parse(JSON.stringify(this.parts)),s=n.pop()
for(const t of n)r.add(t)
let o=e.substr.substring(0,t-s.start),i=o.length
return r.add({start:s.start,end:s.start+i,length:i,substr:o}),r}}const A=t=>{var e
void 0===c&&(c=v(e||f)),t=b(t)
let r="",n=[new _]
for(let e=0;e<t.length;e++){let s=t.substring(e).match(h)
const o=t.substring(e,e+1),i=s?s[0]:null
let l=[],u=new Set
for(const t of n){const r=t.last()
if(!r||1==r.length||r.end<=e)if(i){const r=i.length
t.add({start:e,end:e+r,length:r,substr:i}),u.add("1")}else t.add({start:e,end:e+1,length:1,substr:o}),u.add("2")
else if(i){let n=t.clone(e,r)
const s=i.length
n.add({start:e,end:e+s,length:s,substr:i}),l.push(n)}else u.add("3")}if(l.length>0){l=l.sort(((t,e)=>t.length()-e.length()))
for(let t of l)j(t,n)||n.push(t)}else if(e>0&&1==u.size&&!u.has("3")){r+=x(n,!1)
let t=new _
const e=n[0]
e&&t.add(e.last()),n=[t]}}return r+=x(n,!0),r},F=(t,e)=>{if(t)return t[e]},E=(t,e)=>{if(t){for(var r,n=e.split(".");(r=n.shift())&&(t=t[r]););return t}},$=(t,e,r)=>{var n,s
return t?(t+="",null==e.regex||-1===(s=t.search(e.regex))?0:(n=e.string.length/t.length,0===s&&(n+=.5),n*r)):0},k=(t,e)=>{var r=t[e]
if("function"==typeof r)return r
r&&!Array.isArray(r)&&(t[e]=[r])},C=(t,e)=>{if(Array.isArray(t))t.forEach(e)
else for(var r in t)t.hasOwnProperty(r)&&e(t[r],r)},z=(t,e)=>"number"==typeof t&&"number"==typeof e?t>e?1:t<e?-1:0:(t=b(t+"").toLowerCase())>(e=b(e+"").toLowerCase())?1:e>t?-1:0
t.Sifter=
/**
   * sifter.js
   * Copyright (c) 2013–2020 Brian Reavis & contributors
   *
   * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
   * file except in compliance with the License. You may obtain a copy of the License at:
   * http://www.apache.org/licenses/LICENSE-2.0
   *
   * Unless required by applicable law or agreed to in writing, software distributed under
   * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
   * ANY KIND, either express or implied. See the License for the specific language
   * governing permissions and limitations under the License.
   *
   * @author Brian Reavis <brian@thirdroute.com>
   */
class{constructor(t,e){this.items=void 0,this.settings=void 0,this.items=t,this.settings=e||{diacritics:!0}}tokenize(t,e,r){if(!t||!t.length)return[]
const n=[],s=t.split(/\s+/)
var i
return r&&(i=new RegExp("^("+Object.keys(r).map(o).join("|")+"):(.*)$")),s.forEach((t=>{let r,s=null,l=null
i&&(r=t.match(i))&&(s=r[1],t=r[2]),t.length>0&&(l=this.settings.diacritics?A(t)||null:o(t),l&&e&&(l="\\b"+l)),n.push({string:t,regex:l?new RegExp(l,"iu"):null,field:s})})),n}getScoreFunction(t,e){var r=this.prepareSearch(t,e)
return this._getScoreFunction(r)}_getScoreFunction(t){const e=t.tokens,r=e.length
if(!r)return function(){return 0}
const n=t.options.fields,s=t.weights,o=n.length,i=t.getAttrFn
if(!o)return function(){return 1}
const l=1===o?function(t,e){const r=n[0].field
return $(i(e,r),t,s[r]||1)}:function(t,e){var r=0
if(t.field){const n=i(e,t.field)
!t.regex&&n?r+=1/o:r+=$(n,t,1)}else C(s,((n,s)=>{r+=$(i(e,s),t,n)}))
return r/o}
return 1===r?function(t){return l(e[0],t)}:"and"===t.options.conjunction?function(t){var n,s=0
for(let r of e){if((n=l(r,t))<=0)return 0
s+=n}return s/r}:function(t){var n=0
return C(e,(e=>{n+=l(e,t)})),n/r}}getSortFunction(t,e){var r=this.prepareSearch(t,e)
return this._getSortFunction(r)}_getSortFunction(t){var e,r=[]
const n=this,s=t.options,o=!t.query&&s.sort_empty?s.sort_empty:s.sort
if("function"==typeof o)return o.bind(this)
const i=function(e,r){return"$score"===e?r.score:t.getAttrFn(n.items[r.id],e)}
if(o)for(let e of o)(t.query||"$score"!==e.field)&&r.push(e)
if(t.query){e=!0
for(let t of r)if("$score"===t.field){e=!1
break}e&&r.unshift({field:"$score",direction:"desc"})}else r=r.filter((t=>"$score"!==t.field))
return r.length?function(t,e){var n,s
for(let o of r){if(s=o.field,n=("desc"===o.direction?-1:1)*z(i(s,t),i(s,e)))return n}return 0}:null}prepareSearch(t,e){const r={}
var n=Object.assign({},e)
if(k(n,"sort"),k(n,"sort_empty"),n.fields){k(n,"fields")
const t=[]
n.fields.forEach((e=>{"string"==typeof e&&(e={field:e,weight:1}),t.push(e),r[e.field]="weight"in e?e.weight:1})),n.fields=t}return{options:n,query:t.toLowerCase().trim(),tokens:this.tokenize(t,n.respect_word_boundaries,r),total:0,items:[],weights:r,getAttrFn:n.nesting?E:F}}search(t,e){var r,n,s=this
n=this.prepareSearch(t,e),e=n.options,t=n.query
const o=e.score||s._getScoreFunction(n)
t.length?C(s.items,((t,s)=>{r=o(t),(!1===e.filter||r>0)&&n.items.push({score:r,id:s})})):C(s.items,((t,e)=>{n.items.push({score:1,id:e})}))
const i=s._getSortFunction(n)
return i&&n.items.sort(i),n.total=n.items.length,"number"==typeof e.limit&&(n.items=n.items.slice(0,e.limit)),n}},t.cmp=z,t.getAttr=F,t.getAttrNesting=E,t.getPattern=A,t.iterate=C,t.propToArray=k,t.scoreValue=$,Object.defineProperty(t,"__esModule",{value:!0})}))
//# sourceMappingURL=sifter.min.js.map
