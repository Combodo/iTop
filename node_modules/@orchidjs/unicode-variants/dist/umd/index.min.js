/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports):"function"==typeof define&&define.amd?define(["exports"],e):e((t="undefined"!=typeof globalThis?globalThis:t||self).diacritics={})}(this,(function(t){"use strict"
const e=t=>(t=t.filter(Boolean)).length<2?t[0]||"":1==a(t)?"["+t.join("")+"]":"(?:"+t.join("|")+")",n=t=>{if(!r(t))return t.join("")
let e="",n=0
const s=()=>{n>1&&(e+="{"+n+"}")}
return t.forEach(((r,o)=>{r!==t[o-1]?(s(),e+=r,n=1):n++})),s(),e},s=t=>{let n=i(t)
return e(n)},r=t=>new Set(t).size!==t.length,o=t=>(t+"").replace(/([\$\(\)\*\+\.\?\[\]\^\{\|\}\\])/gu,"\\$1"),a=t=>t.reduce(((t,e)=>Math.max(t,l(e))),0),l=t=>i(t).length,i=t=>Array.from(t),u=t=>{if(1===t.length)return[[t]]
let e=[]
const n=t.substring(1)
return u(n).forEach((function(n){let s=n.slice(0)
s[0]=t.charAt(0)+s[0],e.push(s),s=n.slice(0),s.unshift(t.charAt(0)),e.push(s)})),e},h=[[0,65535]]
let d
t.unicode_map=void 0
const c={},f={"/":"⁄∕",0:"߀",a:"ⱥɐɑ",aa:"ꜳ",ae:"æǽǣ",ao:"ꜵ",au:"ꜷ",av:"ꜹꜻ",ay:"ꜽ",b:"ƀɓƃ",c:"ꜿƈȼↄ",d:"đɗɖᴅƌꮷԁɦ",e:"ɛǝᴇɇ",f:"ꝼƒ",g:"ǥɠꞡᵹꝿɢ",h:"ħⱨⱶɥ",i:"ɨı",j:"ɉȷ",k:"ƙⱪꝁꝃꝅꞣ",l:"łƚɫⱡꝉꝇꞁɭ",m:"ɱɯϻ",n:"ꞥƞɲꞑᴎлԉ",o:"øǿɔɵꝋꝍᴑ",oe:"œ",oi:"ƣ",oo:"ꝏ",ou:"ȣ",p:"ƥᵽꝑꝓꝕρ",q:"ꝗꝙɋ",r:"ɍɽꝛꞧꞃ",s:"ßȿꞩꞅʂ",t:"ŧƭʈⱦꞇ",th:"þ",tz:"ꜩ",u:"ʉ",v:"ʋꝟʌ",vy:"ꝡ",w:"ⱳ",y:"ƴɏỿ",z:"ƶȥɀⱬꝣ",hv:"ƕ"}
for(let t in f){let e=f[t]||""
for(let n=0;n<e.length;n++){let s=e.substring(n,n+1)
c[s]=t}}const g=new RegExp(Object.keys(c).join("|")+"|[̀-ͯ·ʾʼ]","gu"),p=e=>{void 0===t.unicode_map&&(t.unicode_map=j(e||h))},b=(t,e="NFKD")=>t.normalize(e),m=t=>i(t).reduce(((t,e)=>t+w(e)),""),w=t=>(t=b(t).toLowerCase().replace(g,(t=>c[t]||"")),b(t,"NFC"))
function*y(t){for(const[e,n]of t)for(let t=e;t<=n;t++){let e=String.fromCharCode(t),n=m(e)
n!=e.toLowerCase()&&(n.length>3||0!=n.length&&(yield{folded:n,composed:e,code_point:t}))}}const _=t=>{const e={},n=(t,n)=>{const r=e[t]||new Set,a=new RegExp("^"+s(r)+"$","iu")
n.match(a)||(r.add(o(n)),e[t]=r)}
for(let e of y(t))n(e.folded,e.folded),n(e.folded,e.composed)
return e},j=t=>{const n=_(t),r={}
let a=[]
for(let t in n){let e=n[t]
e&&(r[t]=s(e)),t.length>1&&a.push(o(t))}a.sort(((t,e)=>e.length-t.length))
const l=e(a)
return d=new RegExp("^"+l,"u"),r},x=(e,s=1)=>{let r=0
return e=e.map((e=>(t.unicode_map[e]&&(r+=e.length),t.unicode_map[e]||e))),r>=s?n(e):""},S=(t,n=1)=>(n=Math.max(n,t.length-1),e(u(t).map((t=>x(t,n))))),v=(t,s=!0)=>{let r=t.length>1?1:0
return e(t.map((t=>{let e=[]
const o=s?t.length():t.length()-1
for(let n=0;n<o;n++)e.push(S(t.substrs[n]||"",r))
return n(e)})))},z=(t,e)=>{for(const n of e){if(n.start!=t.start||n.end!=t.end)continue
if(n.substrs.join("")!==t.substrs.join(""))continue
let e=t.parts
const s=t=>{for(const n of e){if(n.start===t.start&&n.substr===t.substr)return!1
if(1!=t.length&&1!=n.length){if(t.start<n.start&&t.end>n.start)return!0
if(n.start<t.start&&n.end>t.start)return!0}}return!1}
if(!(n.parts.filter(s).length>0))return!0}return!1}
class M{constructor(){this.parts=[],this.substrs=[],this.start=0,this.end=0}add(t){t&&(this.parts.push(t),this.substrs.push(t.substr),this.start=Math.min(t.start,this.start),this.end=Math.max(t.end,this.end))}last(){return this.parts[this.parts.length-1]}length(){return this.parts.length}clone(t,e){let n=new M,s=JSON.parse(JSON.stringify(this.parts)),r=s.pop()
for(const t of s)n.add(t)
let o=e.substr.substring(0,t-r.start),a=o.length
return n.add({start:r.start,end:r.start+a,length:a,substr:o}),n}}t._asciifold=w,t.asciifold=m,t.code_points=h,t.escape_regex=o,t.generateMap=j,t.generateSets=_,t.generator=y,t.getPattern=t=>{p(),t=m(t)
let e="",n=[new M]
for(let s=0;s<t.length;s++){let r=t.substring(s).match(d)
const o=t.substring(s,s+1),a=r?r[0]:null
let l=[],i=new Set
for(const t of n){const e=t.last()
if(!e||1==e.length||e.end<=s)if(a){const e=a.length
t.add({start:s,end:s+e,length:e,substr:a}),i.add("1")}else t.add({start:s,end:s+1,length:1,substr:o}),i.add("2")
else if(a){let n=t.clone(s,e)
const r=a.length
n.add({start:s,end:s+r,length:r,substr:a}),l.push(n)}else i.add("3")}if(l.length>0){l=l.sort(((t,e)=>t.length()-e.length()))
for(let t of l)z(t,n)||n.push(t)}else if(s>0&&1==i.size&&!i.has("3")){e+=v(n,!1)
let t=new M
const s=n[0]
s&&t.add(s.last()),n=[t]}}return e+=v(n,!0),e},t.initialize=p,t.mapSequence=x,t.normalize=b,t.substringsToPattern=S,Object.defineProperty(t,"__esModule",{value:!0})}))
//# sourceMappingURL=index.min.js.map
