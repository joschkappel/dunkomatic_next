/*!
FullCalendar v5.10.1
Docs & License: https://fullcalendar.io/
(c) 2021 Adam Shaw
*/
var FullCalendar=function(e){"use strict";var t=function(e,n){return(t=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(e,t){e.__proto__=t}||function(e,t){for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n])})(e,n)};function n(e,n){if("function"!=typeof n&&null!==n)throw new TypeError("Class extends value "+String(n)+" is not a constructor or null");function r(){this.constructor=e}t(e,n),e.prototype=null===n?Object.create(n):(r.prototype=n.prototype,new r)}var r=function(){return(r=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e}).apply(this,arguments)};function o(e,t,n){if(n||2===arguments.length)for(var r,o=0,i=t.length;o<i;o++)!r&&o in t||(r||(r=Array.prototype.slice.call(t,0,o)),r[o]=t[o]);return e.concat(r||t)}var i,a,s,l,u,c={},d=[],p=/acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i;function f(e,t){for(var n in t)e[n]=t[n];return e}function h(e){var t=e.parentNode;t&&t.removeChild(e)}function v(e,t,n){var r,o,i,a=arguments,s={};for(i in t)"key"==i?r=t[i]:"ref"==i?o=t[i]:s[i]=t[i];if(arguments.length>3)for(n=[n],i=3;i<arguments.length;i++)n.push(a[i]);if(null!=n&&(s.children=n),"function"==typeof e&&null!=e.defaultProps)for(i in e.defaultProps)void 0===s[i]&&(s[i]=e.defaultProps[i]);return g(e,s,r,o,null)}function g(e,t,n,r,o){var a={type:e,props:t,key:n,ref:r,__k:null,__:null,__b:0,__e:null,__d:void 0,__c:null,__h:null,constructor:void 0,__v:null==o?++i.__v:o};return null!=i.vnode&&i.vnode(a),a}function m(e){return e.children}function y(e,t){this.props=e,this.context=t}function E(e,t){if(null==t)return e.__?E(e.__,e.__.__k.indexOf(e)+1):null;for(var n;t<e.__k.length;t++)if(null!=(n=e.__k[t])&&null!=n.__e)return n.__e;return"function"==typeof e.type?E(e):null}function S(e){var t,n;if(null!=(e=e.__)&&null!=e.__c){for(e.__e=e.__c.base=null,t=0;t<e.__k.length;t++)if(null!=(n=e.__k[t])&&null!=n.__e){e.__e=e.__c.base=n.__e;break}return S(e)}}function b(e){(!e.__d&&(e.__d=!0)&&a.push(e)&&!D.__r++||l!==i.debounceRendering)&&((l=i.debounceRendering)||s)(D)}function D(){for(var e;D.__r=a.length;)e=a.sort((function(e,t){return e.__v.__b-t.__v.__b})),a=[],e.some((function(e){var t,n,r,o,i,a;e.__d&&(i=(o=(t=e).__v).__e,(a=t.__P)&&(n=[],(r=f({},o)).__v=o.__v+1,I(a,o,r,t.__n,void 0!==a.ownerSVGElement,null!=o.__h?[i]:null,n,null==i?E(o):i,o.__h),P(n,o),o.__e!=i&&S(o)))}))}function C(e,t,n,r,o,i,a,s,l,u){var p,f,h,v,y,S,b,D=r&&r.__k||d,C=D.length;for(n.__k=[],p=0;p<t.length;p++)if(null!=(v=n.__k[p]=null==(v=t[p])||"boolean"==typeof v?null:"string"==typeof v||"number"==typeof v||"bigint"==typeof v?g(null,v,null,null,v):Array.isArray(v)?g(m,{children:v},null,null,null):v.__b>0?g(v.type,v.props,v.key,null,v.__v):v)){if(v.__=n,v.__b=n.__b+1,null===(h=D[p])||h&&v.key==h.key&&v.type===h.type)D[p]=void 0;else for(f=0;f<C;f++){if((h=D[f])&&v.key==h.key&&v.type===h.type){D[f]=void 0;break}h=null}I(e,v,h=h||c,o,i,a,s,l,u),y=v.__e,(f=v.ref)&&h.ref!=f&&(b||(b=[]),h.ref&&b.push(h.ref,null,v),b.push(f,v.__c||y,v)),null!=y?(null==S&&(S=y),"function"==typeof v.type&&null!=v.__k&&v.__k===h.__k?v.__d=l=w(v,l,e):l=_(e,v,h,D,y,l),u||"option"!==n.type?"function"==typeof n.type&&(n.__d=l):e.value=""):l&&h.__e==l&&l.parentNode!=e&&(l=E(h))}for(n.__e=S,p=C;p--;)null!=D[p]&&("function"==typeof n.type&&null!=D[p].__e&&D[p].__e==n.__d&&(n.__d=E(r,p+1)),O(D[p],D[p]));if(b)for(p=0;p<b.length;p++)H(b[p],b[++p],b[++p])}function w(e,t,n){var r,o;for(r=0;r<e.__k.length;r++)(o=e.__k[r])&&(o.__=e,t="function"==typeof o.type?w(o,t,n):_(n,o,o,e.__k,o.__e,t));return t}function R(e,t){return t=t||[],null==e||"boolean"==typeof e||(Array.isArray(e)?e.some((function(e){R(e,t)})):t.push(e)),t}function _(e,t,n,r,o,i){var a,s,l;if(void 0!==t.__d)a=t.__d,t.__d=void 0;else if(null==n||o!=i||null==o.parentNode)e:if(null==i||i.parentNode!==e)e.appendChild(o),a=null;else{for(s=i,l=0;(s=s.nextSibling)&&l<r.length;l+=2)if(s==o)break e;e.insertBefore(o,i),a=i}return void 0!==a?a:o.nextSibling}function T(e,t,n){"-"===t[0]?e.setProperty(t,n):e[t]=null==n?"":"number"!=typeof n||p.test(t)?n:n+"px"}function k(e,t,n,r,o){var i;e:if("style"===t)if("string"==typeof n)e.style.cssText=n;else{if("string"==typeof r&&(e.style.cssText=r=""),r)for(t in r)n&&t in n||T(e.style,t,"");if(n)for(t in n)r&&n[t]===r[t]||T(e.style,t,n[t])}else if("o"===t[0]&&"n"===t[1])i=t!==(t=t.replace(/Capture$/,"")),t=t.toLowerCase()in e?t.toLowerCase().slice(2):t.slice(2),e.l||(e.l={}),e.l[t+i]=n,n?r||e.addEventListener(t,i?M:x,i):e.removeEventListener(t,i?M:x,i);else if("dangerouslySetInnerHTML"!==t){if(o)t=t.replace(/xlink[H:h]/,"h").replace(/sName$/,"s");else if("href"!==t&&"list"!==t&&"form"!==t&&"tabIndex"!==t&&"download"!==t&&t in e)try{e[t]=null==n?"":n;break e}catch(e){}"function"==typeof n||(null!=n&&(!1!==n||"a"===t[0]&&"r"===t[1])?e.setAttribute(t,n):e.removeAttribute(t))}}function x(e){this.l[e.type+!1](i.event?i.event(e):e)}function M(e){this.l[e.type+!0](i.event?i.event(e):e)}function I(e,t,n,r,o,a,s,l,u){var c,d,p,h,v,g,E,S,b,D,w,R=t.type;if(void 0!==t.constructor)return null;null!=n.__h&&(u=n.__h,l=t.__e=n.__e,t.__h=null,a=[l]),(c=i.__b)&&c(t);try{e:if("function"==typeof R){if(S=t.props,b=(c=R.contextType)&&r[c.__c],D=c?b?b.props.value:c.__:r,n.__c?E=(d=t.__c=n.__c).__=d.__E:("prototype"in R&&R.prototype.render?t.__c=d=new R(S,D):(t.__c=d=new y(S,D),d.constructor=R,d.render=A),b&&b.sub(d),d.props=S,d.state||(d.state={}),d.context=D,d.__n=r,p=d.__d=!0,d.__h=[]),null==d.__s&&(d.__s=d.state),null!=R.getDerivedStateFromProps&&(d.__s==d.state&&(d.__s=f({},d.__s)),f(d.__s,R.getDerivedStateFromProps(S,d.__s))),h=d.props,v=d.state,p)null==R.getDerivedStateFromProps&&null!=d.componentWillMount&&d.componentWillMount(),null!=d.componentDidMount&&d.__h.push(d.componentDidMount);else{if(null==R.getDerivedStateFromProps&&S!==h&&null!=d.componentWillReceiveProps&&d.componentWillReceiveProps(S,D),!d.__e&&null!=d.shouldComponentUpdate&&!1===d.shouldComponentUpdate(S,d.__s,D)||t.__v===n.__v){d.props=S,d.state=d.__s,t.__v!==n.__v&&(d.__d=!1),d.__v=t,t.__e=n.__e,t.__k=n.__k,t.__k.forEach((function(e){e&&(e.__=t)})),d.__h.length&&s.push(d);break e}null!=d.componentWillUpdate&&d.componentWillUpdate(S,d.__s,D),null!=d.componentDidUpdate&&d.__h.push((function(){d.componentDidUpdate(h,v,g)}))}d.context=D,d.props=S,d.state=d.__s,(c=i.__r)&&c(t),d.__d=!1,d.__v=t,d.__P=e,c=d.render(d.props,d.state,d.context),d.state=d.__s,null!=d.getChildContext&&(r=f(f({},r),d.getChildContext())),p||null==d.getSnapshotBeforeUpdate||(g=d.getSnapshotBeforeUpdate(h,v)),w=null!=c&&c.type===m&&null==c.key?c.props.children:c,C(e,Array.isArray(w)?w:[w],t,n,r,o,a,s,l,u),d.base=t.__e,t.__h=null,d.__h.length&&s.push(d),E&&(d.__E=d.__=null),d.__e=!1}else null==a&&t.__v===n.__v?(t.__k=n.__k,t.__e=n.__e):t.__e=N(n.__e,t,n,r,o,a,s,u);(c=i.diffed)&&c(t)}catch(e){t.__v=null,(u||null!=a)&&(t.__e=l,t.__h=!!u,a[a.indexOf(l)]=null),i.__e(e,t,n)}}function P(e,t){i.__c&&i.__c(t,e),e.some((function(t){try{e=t.__h,t.__h=[],e.some((function(e){e.call(t)}))}catch(e){i.__e(e,t.__v)}}))}function N(e,t,n,r,o,i,a,s){var l,u,p,f,v=n.props,g=t.props,m=t.type,y=0;if("svg"===m&&(o=!0),null!=i)for(;y<i.length;y++)if((l=i[y])&&(l===e||(m?l.localName==m:3==l.nodeType))){e=l,i[y]=null;break}if(null==e){if(null===m)return document.createTextNode(g);e=o?document.createElementNS("http://www.w3.org/2000/svg",m):document.createElement(m,g.is&&g),i=null,s=!1}if(null===m)v===g||s&&e.data===g||(e.data=g);else{if(i=i&&d.slice.call(e.childNodes),u=(v=n.props||c).dangerouslySetInnerHTML,p=g.dangerouslySetInnerHTML,!s){if(null!=i)for(v={},f=0;f<e.attributes.length;f++)v[e.attributes[f].name]=e.attributes[f].value;(p||u)&&(p&&(u&&p.__html==u.__html||p.__html===e.innerHTML)||(e.innerHTML=p&&p.__html||""))}if(function(e,t,n,r,o){var i;for(i in n)"children"===i||"key"===i||i in t||k(e,i,null,n[i],r);for(i in t)o&&"function"!=typeof t[i]||"children"===i||"key"===i||"value"===i||"checked"===i||n[i]===t[i]||k(e,i,t[i],n[i],r)}(e,g,v,o,s),p)t.__k=[];else if(y=t.props.children,C(e,Array.isArray(y)?y:[y],t,n,r,o&&"foreignObject"!==m,i,a,e.firstChild,s),null!=i)for(y=i.length;y--;)null!=i[y]&&h(i[y]);s||("value"in g&&void 0!==(y=g.value)&&(y!==e.value||"progress"===m&&!y)&&k(e,"value",y,v.value,!1),"checked"in g&&void 0!==(y=g.checked)&&y!==e.checked&&k(e,"checked",y,v.checked,!1))}return e}function H(e,t,n){try{"function"==typeof e?e(t):e.current=t}catch(e){i.__e(e,n)}}function O(e,t,n){var r,o,a;if(i.unmount&&i.unmount(e),(r=e.ref)&&(r.current&&r.current!==e.__e||H(r,null,t)),n||"function"==typeof e.type||(n=null!=(o=e.__e)),e.__e=e.__d=void 0,null!=(r=e.__c)){if(r.componentWillUnmount)try{r.componentWillUnmount()}catch(e){i.__e(e,t)}r.base=r.__P=null}if(r=e.__k)for(a=0;a<r.length;a++)r[a]&&O(r[a],t,n);null!=o&&h(o)}function A(e,t,n){return this.constructor(e,n)}function L(e,t,n){var r,o,a;i.__&&i.__(e,t),o=(r="function"==typeof n)?null:n&&n.__k||t.__k,a=[],I(t,e=(!r&&n||t).__k=v(m,null,[e]),o||c,c,void 0!==t.ownerSVGElement,!r&&n?[n]:o?null:t.firstChild?d.slice.call(t.childNodes):null,a,!r&&n?n:o?o.__e:t.firstChild,r),P(a,e)}i={__e:function(e,t){for(var n,r,o;t=t.__;)if((n=t.__c)&&!n.__)try{if((r=n.constructor)&&null!=r.getDerivedStateFromError&&(n.setState(r.getDerivedStateFromError(e)),o=n.__d),null!=n.componentDidCatch&&(n.componentDidCatch(e),o=n.__d),o)return n.__E=n}catch(t){e=t}throw e},__v:0},y.prototype.setState=function(e,t){var n;n=null!=this.__s&&this.__s!==this.state?this.__s:this.__s=f({},this.state),"function"==typeof e&&(e=e(f({},n),this.props)),e&&f(n,e),null!=e&&this.__v&&(t&&this.__h.push(t),b(this))},y.prototype.forceUpdate=function(e){this.__v&&(this.__e=!0,e&&this.__h.push(e),b(this))},y.prototype.render=m,a=[],s="function"==typeof Promise?Promise.prototype.then.bind(Promise.resolve()):setTimeout,D.__r=0,u=0;var U,W=[],V=i.__b,F=i.__r,B=i.diffed,z=i.__c,j=i.unmount;function G(){W.forEach((function(e){if(e.__P)try{e.__H.__h.forEach(Y),e.__H.__h.forEach(Z),e.__H.__h=[]}catch(t){e.__H.__h=[],i.__e(t,e.__v)}})),W=[]}i.__b=function(e){V&&V(e)},i.__r=function(e){F&&F(e);var t=e.__c.__H;t&&(t.__h.forEach(Y),t.__h.forEach(Z),t.__h=[])},i.diffed=function(e){B&&B(e);var t=e.__c;t&&t.__H&&t.__H.__h.length&&(1!==W.push(t)&&U===i.requestAnimationFrame||((U=i.requestAnimationFrame)||function(e){var t,n=function(){clearTimeout(r),q&&cancelAnimationFrame(t),setTimeout(e)},r=setTimeout(n,100);q&&(t=requestAnimationFrame(n))})(G))},i.__c=function(e,t){t.some((function(e){try{e.__h.forEach(Y),e.__h=e.__h.filter((function(e){return!e.__||Z(e)}))}catch(n){t.some((function(e){e.__h&&(e.__h=[])})),t=[],i.__e(n,e.__v)}})),z&&z(e,t)},i.unmount=function(e){j&&j(e);var t=e.__c;if(t&&t.__H)try{t.__H.__.forEach(Y)}catch(e){i.__e(e,t.__v)}};var q="function"==typeof requestAnimationFrame;function Y(e){"function"==typeof e.__c&&e.__c()}function Z(e){e.__c=e.__()}function X(e,t){for(var n in e)if("__source"!==n&&!(n in t))return!0;for(var r in t)if("__source"!==r&&e[r]!==t[r])return!0;return!1}function K(e){this.props=e}(K.prototype=new y).isPureReactComponent=!0,K.prototype.shouldComponentUpdate=function(e,t){return X(this.props,e)||X(this.state,t)};var $=i.__b;i.__b=function(e){e.type&&e.type.__f&&e.ref&&(e.props.ref=e.ref,e.ref=null),$&&$(e)};var J=i.__e;i.__e=function(e,t,n){if(e.then)for(var r,o=t;o=o.__;)if((r=o.__c)&&r.__c)return null==t.__e&&(t.__e=n.__e,t.__k=n.__k),r.__c(e,t);J(e,t,n)};var Q=i.unmount;function ee(){this.__u=0,this.t=null,this.__b=null}function te(e){var t=e.__.__c;return t&&t.__e&&t.__e(e)}function ne(){this.u=null,this.o=null}i.unmount=function(e){var t=e.__c;t&&t.__R&&t.__R(),t&&!0===e.__h&&(e.type=null),Q&&Q(e)},(ee.prototype=new y).__c=function(e,t){var n=t.__c,r=this;null==r.t&&(r.t=[]),r.t.push(n);var o=te(r.__v),i=!1,a=function(){i||(i=!0,n.__R=null,o?o(s):s())};n.__R=a;var s=function(){if(!--r.__u){if(r.state.__e){var e=r.state.__e;r.__v.__k[0]=function e(t,n,r){return t&&(t.__v=null,t.__k=t.__k&&t.__k.map((function(t){return e(t,n,r)})),t.__c&&t.__c.__P===n&&(t.__e&&r.insertBefore(t.__e,t.__d),t.__c.__e=!0,t.__c.__P=r)),t}(e,e.__c.__P,e.__c.__O)}var t;for(r.setState({__e:r.__b=null});t=r.t.pop();)t.forceUpdate()}},l=!0===t.__h;r.__u++||l||r.setState({__e:r.__b=r.__v.__k[0]}),e.then(a,a)},ee.prototype.componentWillUnmount=function(){this.t=[]},ee.prototype.render=function(e,t){if(this.__b){if(this.__v.__k){var n=document.createElement("div"),r=this.__v.__k[0].__c;this.__v.__k[0]=function e(t,n,r){return t&&(t.__c&&t.__c.__H&&(t.__c.__H.__.forEach((function(e){"function"==typeof e.__c&&e.__c()})),t.__c.__H=null),null!=(t=function(e,t){for(var n in t)e[n]=t[n];return e}({},t)).__c&&(t.__c.__P===r&&(t.__c.__P=n),t.__c=null),t.__k=t.__k&&t.__k.map((function(t){return e(t,n,r)}))),t}(this.__b,n,r.__O=r.__P)}this.__b=null}var o=t.__e&&v(m,null,e.fallback);return o&&(o.__h=null),[v(m,null,t.__e?null:e.children),o]};var re=function(e,t,n){if(++n[1]===n[0]&&e.o.delete(t),e.props.revealOrder&&("t"!==e.props.revealOrder[0]||!e.o.size))for(n=e.u;n;){for(;n.length>3;)n.pop()();if(n[1]<n[0])break;e.u=n=n[2]}};function oe(e){return this.getChildContext=function(){return e.context},e.children}function ie(e){var t=this,n=e.i;t.componentWillUnmount=function(){L(null,t.l),t.l=null,t.i=null},t.i&&t.i!==n&&t.componentWillUnmount(),e.__v?(t.l||(t.i=n,t.l={nodeType:1,parentNode:n,childNodes:[],appendChild:function(e){this.childNodes.push(e),t.i.appendChild(e)},insertBefore:function(e,n){this.childNodes.push(e),t.i.appendChild(e)},removeChild:function(e){this.childNodes.splice(this.childNodes.indexOf(e)>>>1,1),t.i.removeChild(e)}}),L(v(oe,{context:t.context},e.__v),t.l)):t.l&&t.componentWillUnmount()}(ne.prototype=new y).__e=function(e){var t=this,n=te(t.__v),r=t.o.get(e);return r[0]++,function(o){var i=function(){t.props.revealOrder?(r.push(o),re(t,e,r)):o()};n?n(i):i()}},ne.prototype.render=function(e){this.u=null,this.o=new Map;var t=R(e.children);e.revealOrder&&"b"===e.revealOrder[0]&&t.reverse();for(var n=t.length;n--;)this.o.set(t[n],this.u=[1,0,this.u]);return e.children},ne.prototype.componentDidUpdate=ne.prototype.componentDidMount=function(){var e=this;this.o.forEach((function(t,n){re(e,n,t)}))};var ae="undefined"!=typeof Symbol&&Symbol.for&&Symbol.for("react.element")||60103,se=/^(?:accent|alignment|arabic|baseline|cap|clip(?!PathU)|color|fill|flood|font|glyph(?!R)|horiz|marker(?!H|W|U)|overline|paint|stop|strikethrough|stroke|text(?!L)|underline|unicode|units|v|vector|vert|word|writing|x(?!C))[A-Z]/,le=function(e){return("undefined"!=typeof Symbol&&"symbol"==typeof Symbol()?/fil|che|rad/i:/fil|che|ra/i).test(e)};y.prototype.isReactComponent={},["componentWillMount","componentWillReceiveProps","componentWillUpdate"].forEach((function(e){Object.defineProperty(y.prototype,e,{configurable:!0,get:function(){return this["UNSAFE_"+e]},set:function(t){Object.defineProperty(this,e,{configurable:!0,writable:!0,value:t})}})}));var ue=i.event;function ce(){}function de(){return this.cancelBubble}function pe(){return this.defaultPrevented}i.event=function(e){return ue&&(e=ue(e)),e.persist=ce,e.isPropagationStopped=de,e.isDefaultPrevented=pe,e.nativeEvent=e};var fe={configurable:!0,get:function(){return this.class}},he=i.vnode;i.vnode=function(e){var t=e.type,n=e.props,r=n;if("string"==typeof t){for(var o in r={},n){var i=n[o];"value"===o&&"defaultValue"in n&&null==i||("defaultValue"===o&&"value"in n&&null==n.value?o="value":"download"===o&&!0===i?i="":/ondoubleclick/i.test(o)?o="ondblclick":/^onchange(textarea|input)/i.test(o+t)&&!le(n.type)?o="oninput":/^on(Ani|Tra|Tou|BeforeInp)/.test(o)?o=o.toLowerCase():se.test(o)?o=o.replace(/[A-Z0-9]/,"-$&").toLowerCase():null===i&&(i=void 0),r[o]=i)}"select"==t&&r.multiple&&Array.isArray(r.value)&&(r.value=R(n.children).forEach((function(e){e.props.selected=-1!=r.value.indexOf(e.props.value)}))),"select"==t&&null!=r.defaultValue&&(r.value=R(n.children).forEach((function(e){e.props.selected=r.multiple?-1!=r.defaultValue.indexOf(e.props.value):r.defaultValue==e.props.value}))),e.props=r}t&&n.class!=n.className&&(fe.enumerable="className"in n,null!=n.className&&(r.class=n.className),Object.defineProperty(r,"className",fe)),e.$$typeof=ae,he&&he(e)};var ve=i.__r;i.__r=function(e){ve&&ve(e)},"object"==typeof performance&&"function"==typeof performance.now&&performance.now.bind(performance);var ge="undefined"!=typeof globalThis?globalThis:window;ge.FullCalendarVDom?console.warn("FullCalendar VDOM already loaded"):ge.FullCalendarVDom={Component:y,createElement:v,render:L,createRef:function(){return{current:null}},Fragment:m,createContext:function(e){var t=function(e,t){var n={__c:t="__cC"+u++,__:e,Consumer:function(e,t){return e.children(t)},Provider:function(e){var n,r;return this.getChildContext||(n=[],(r={})[t]=this,this.getChildContext=function(){return r},this.shouldComponentUpdate=function(e){this.props.value!==e.value&&n.some(b)},this.sub=function(e){n.push(e);var t=e.componentWillUnmount;e.componentWillUnmount=function(){n.splice(n.indexOf(e),1),t&&t.call(e)}}),e.children}};return n.Provider.__=n.Consumer.contextType=n}(e),n=t.Provider;return t.Provider=function(){var e=this,t=!this.getChildContext,r=n.apply(this,arguments);if(t){var o=[];this.shouldComponentUpdate=function(t){e.props.value!==t.value&&o.forEach((function(e){e.context=t.value,e.forceUpdate()}))},this.sub=function(e){o.push(e);var t=e.componentWillUnmount;e.componentWillUnmount=function(){o.splice(o.indexOf(e),1),t&&t.call(e)}}}return r},t},createPortal:function(e,t){return v(ie,{__v:e,i:t})},flushToDom:function(){var e=i.debounceRendering,t=[];function n(e){t.push(e)}i.debounceRendering=n,L(v(me,{}),document.createElement("div"));for(;t.length;)t.shift()();i.debounceRendering=e},unmountComponentAtNode:function(e){L(null,e)}};var me=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){return v("div",{})},t.prototype.componentDidMount=function(){this.setState({})},t}(y);var ye=function(){function e(e,t){this.context=e,this.internalEventSource=t}return e.prototype.remove=function(){this.context.dispatch({type:"REMOVE_EVENT_SOURCE",sourceId:this.internalEventSource.sourceId})},e.prototype.refetch=function(){this.context.dispatch({type:"FETCH_EVENT_SOURCES",sourceIds:[this.internalEventSource.sourceId],isRefetch:!0})},Object.defineProperty(e.prototype,"id",{get:function(){return this.internalEventSource.publicId},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"url",{get:function(){return this.internalEventSource.meta.url},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"format",{get:function(){return this.internalEventSource.meta.format},enumerable:!1,configurable:!0}),e}();function Ee(e){e.parentNode&&e.parentNode.removeChild(e)}function Se(e,t){if(e.closest)return e.closest(t);if(!document.documentElement.contains(e))return null;do{if(be(e,t))return e;e=e.parentElement||e.parentNode}while(null!==e&&1===e.nodeType);return null}function be(e,t){return(e.matches||e.matchesSelector||e.msMatchesSelector).call(e,t)}function De(e,t){for(var n=e instanceof HTMLElement?[e]:e,r=[],o=0;o<n.length;o+=1)for(var i=n[o].querySelectorAll(t),a=0;a<i.length;a+=1)r.push(i[a]);return r}var Ce=/(top|left|right|bottom|width|height)$/i;function we(e,t){for(var n in t)Re(e,n,t[n])}function Re(e,t,n){null==n?e.style[t]="":"number"==typeof n&&Ce.test(t)?e.style[t]=n+"px":e.style[t]=n}function _e(e){var t,n;return null!==(n=null===(t=e.composedPath)||void 0===t?void 0:t.call(e)[0])&&void 0!==n?n:e.target}function Te(e){return e.getRootNode?e.getRootNode():document}var ke=0;function xe(){return"fc-dom-"+(ke+=1)}function Me(e){e.preventDefault()}function Ie(e,t,n,r){var o=function(e,t){return function(n){var r=Se(n.target,e);r&&t.call(r,n,r)}}(n,r);return e.addEventListener(t,o),function(){e.removeEventListener(t,o)}}var Pe=["webkitTransitionEnd","otransitionend","oTransitionEnd","msTransitionEnd","transitionend"];function Ne(e,t){var n=function(r){t(r),Pe.forEach((function(t){e.removeEventListener(t,n)}))};Pe.forEach((function(t){e.addEventListener(t,n)}))}function He(e){return r({onClick:e},Oe(e))}function Oe(e){return{tabIndex:0,onKeyDown:function(t){"Enter"!==t.key&&" "!==t.key||(e(t),t.preventDefault())}}}var Ae=0;function Le(){return String(Ae+=1)}function Ue(){document.body.classList.add("fc-not-allowed")}function We(){document.body.classList.remove("fc-not-allowed")}function Ve(e){e.classList.add("fc-unselectable"),e.addEventListener("selectstart",Me)}function Fe(e){e.classList.remove("fc-unselectable"),e.removeEventListener("selectstart",Me)}function Be(e){e.addEventListener("contextmenu",Me)}function ze(e){e.removeEventListener("contextmenu",Me)}function je(e){var t,n,r=[],o=[];for("string"==typeof e?o=e.split(/\s*,\s*/):"function"==typeof e?o=[e]:Array.isArray(e)&&(o=e),t=0;t<o.length;t+=1)"string"==typeof(n=o[t])?r.push("-"===n.charAt(0)?{field:n.substring(1),order:-1}:{field:n,order:1}):"function"==typeof n&&r.push({func:n});return r}function Ge(e,t,n){var r,o;for(r=0;r<n.length;r+=1)if(o=qe(e,t,n[r]))return o;return 0}function qe(e,t,n){return n.func?n.func(e,t):Ye(e[n.field],t[n.field])*(n.order||1)}function Ye(e,t){return e||t?null==t?-1:null==e?1:"string"==typeof e||"string"==typeof t?String(e).localeCompare(String(t)):e-t:0}function Ze(e,t){var n=String(e);return"000".substr(0,t-n.length)+n}function Xe(e,t,n){return"function"==typeof e?e.apply(void 0,t):"string"==typeof e?t.reduce((function(e,t,n){return e.replace("$"+n,t||"")}),e):n}function Ke(e,t){return e-t}function $e(e){return e%1==0}function Je(e){var t=e.querySelector(".fc-scrollgrid-shrink-frame"),n=e.querySelector(".fc-scrollgrid-shrink-cushion");if(!t)throw new Error("needs fc-scrollgrid-shrink-frame className");if(!n)throw new Error("needs fc-scrollgrid-shrink-cushion className");return e.getBoundingClientRect().width-t.getBoundingClientRect().width+n.getBoundingClientRect().width}var Qe=["sun","mon","tue","wed","thu","fri","sat"];function et(e,t){var n=ft(e);return n[2]+=7*t,ht(n)}function tt(e,t){var n=ft(e);return n[2]+=t,ht(n)}function nt(e,t){var n=ft(e);return n[6]+=t,ht(n)}function rt(e,t){return ot(e,t)/7}function ot(e,t){return(t.valueOf()-e.valueOf())/864e5}function it(e,t){var n=lt(e),r=lt(t);return{years:0,months:0,days:Math.round(ot(n,r)),milliseconds:t.valueOf()-r.valueOf()-(e.valueOf()-n.valueOf())}}function at(e,t){var n=st(e,t);return null!==n&&n%7==0?n/7:null}function st(e,t){return gt(e)===gt(t)?Math.round(ot(e,t)):null}function lt(e){return ht([e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate()])}function ut(e,t,n,r){var o=ht([t,0,1+ct(t,n,r)]),i=lt(e),a=Math.round(ot(o,i));return Math.floor(a/7)+1}function ct(e,t,n){var r=7+t-n;return-((7+ht([e,0,r]).getUTCDay()-t)%7)+r-1}function dt(e){return[e.getFullYear(),e.getMonth(),e.getDate(),e.getHours(),e.getMinutes(),e.getSeconds(),e.getMilliseconds()]}function pt(e){return new Date(e[0],e[1]||0,null==e[2]?1:e[2],e[3]||0,e[4]||0,e[5]||0)}function ft(e){return[e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate(),e.getUTCHours(),e.getUTCMinutes(),e.getUTCSeconds(),e.getUTCMilliseconds()]}function ht(e){return 1===e.length&&(e=e.concat([0])),new Date(Date.UTC.apply(Date,e))}function vt(e){return!isNaN(e.valueOf())}function gt(e){return 1e3*e.getUTCHours()*60*60+1e3*e.getUTCMinutes()*60+1e3*e.getUTCSeconds()+e.getUTCMilliseconds()}function mt(e,t,n,r){return{instanceId:Le(),defId:e,range:t,forcedStartTzo:null==n?null:n,forcedEndTzo:null==r?null:r}}var yt=Object.prototype.hasOwnProperty;function Et(e,t){var n={};if(t)for(var r in t){for(var o=[],i=e.length-1;i>=0;i-=1){var a=e[i][r];if("object"==typeof a&&a)o.unshift(a);else if(void 0!==a){n[r]=a;break}}o.length&&(n[r]=Et(o))}for(i=e.length-1;i>=0;i-=1){var s=e[i];for(var l in s)l in n||(n[l]=s[l])}return n}function St(e,t){var n={};for(var r in e)t(e[r],r)&&(n[r]=e[r]);return n}function bt(e,t){var n={};for(var r in e)n[r]=t(e[r],r);return n}function Dt(e){for(var t={},n=0,r=e;n<r.length;n++){t[r[n]]=!0}return t}function Ct(e){var t=[];for(var n in e)t.push(e[n]);return t}function wt(e,t){if(e===t)return!0;for(var n in e)if(yt.call(e,n)&&!(n in t))return!1;for(var n in t)if(yt.call(t,n)&&e[n]!==t[n])return!1;return!0}function Rt(e,t){var n=[];for(var r in e)yt.call(e,r)&&(r in t||n.push(r));for(var r in t)yt.call(t,r)&&e[r]!==t[r]&&n.push(r);return n}function _t(e,t,n){if(void 0===n&&(n={}),e===t)return!0;for(var r in t)if(!(r in e)||!Tt(e[r],t[r],n[r]))return!1;for(var r in e)if(!(r in t))return!1;return!0}function Tt(e,t,n){return e===t||!0===n||!!n&&n(e,t)}function kt(e,t,n,r){void 0===t&&(t=0),void 0===r&&(r=1);var o=[];null==n&&(n=Object.keys(e).length);for(var i=t;i<n;i+=r){var a=e[i];void 0!==a&&o.push(a)}return o}function xt(e,t,n){var r=n.dateEnv,o=n.pluginHooks,i=n.options,a=e.defs,s=e.instances;for(var l in s=St(s,(function(e){return!a[e.defId].recurringDef})),a){var u=a[l];if(u.recurringDef){var c=u.recurringDef.duration;c||(c=u.allDay?i.defaultAllDayEventDuration:i.defaultTimedEventDuration);for(var d=0,p=Mt(u,c,t,r,o.recurringTypes);d<p.length;d++){var f=p[d],h=mt(l,{start:f,end:r.add(f,c)});s[h.instanceId]=h}}}return{defs:a,instances:s}}function Mt(e,t,n,r,o){var i=o[e.recurringDef.typeId].expand(e.recurringDef.typeData,{start:r.subtract(n.start,t),end:n.end},r);return e.allDay&&(i=i.map(lt)),i}var It=["years","months","days","milliseconds"],Pt=/^(-?)(?:(\d+)\.)?(\d+):(\d\d)(?::(\d\d)(?:\.(\d\d\d))?)?/;function Nt(e,t){var n;return"string"==typeof e?function(e){var t=Pt.exec(e);if(t){var n=t[1]?-1:1;return{years:0,months:0,days:n*(t[2]?parseInt(t[2],10):0),milliseconds:n*(60*(t[3]?parseInt(t[3],10):0)*60*1e3+60*(t[4]?parseInt(t[4],10):0)*1e3+1e3*(t[5]?parseInt(t[5],10):0)+(t[6]?parseInt(t[6],10):0))}}return null}(e):"object"==typeof e&&e?Ht(e):"number"==typeof e?Ht(((n={})[t||"milliseconds"]=e,n)):null}function Ht(e){var t={years:e.years||e.year||0,months:e.months||e.month||0,days:e.days||e.day||0,milliseconds:60*(e.hours||e.hour||0)*60*1e3+60*(e.minutes||e.minute||0)*1e3+1e3*(e.seconds||e.second||0)+(e.milliseconds||e.millisecond||e.ms||0)},n=e.weeks||e.week;return n&&(t.days+=7*n,t.specifiedWeeks=!0),t}function Ot(e,t){return{years:e.years+t.years,months:e.months+t.months,days:e.days+t.days,milliseconds:e.milliseconds+t.milliseconds}}function At(e,t){return{years:e.years*t,months:e.months*t,days:e.days*t,milliseconds:e.milliseconds*t}}function Lt(e){return Ut(e)/864e5}function Ut(e){return 31536e6*e.years+2592e6*e.months+864e5*e.days+e.milliseconds}function Wt(e,t){for(var n=null,r=0;r<It.length;r+=1){var o=It[r];if(t[o]){var i=e[o]/t[o];if(!$e(i)||null!==n&&n!==i)return null;n=i}else if(e[o])return null}return n}function Vt(e){var t=e.milliseconds;if(t){if(t%1e3!=0)return{unit:"millisecond",value:t};if(t%6e4!=0)return{unit:"second",value:t/1e3};if(t%36e5!=0)return{unit:"minute",value:t/6e4};if(t)return{unit:"hour",value:t/36e5}}return e.days?e.specifiedWeeks&&e.days%7==0?{unit:"week",value:e.days/7}:{unit:"day",value:e.days}:e.months?{unit:"month",value:e.months}:e.years?{unit:"year",value:e.years}:{unit:"millisecond",value:0}}function Ft(e,t,n){void 0===n&&(n=!1);var r=e.toISOString();return r=r.replace(".000",""),n&&(r=r.replace("T00:00:00Z","")),r.length>10&&(null==t?r=r.replace("Z",""):0!==t&&(r=r.replace("Z",jt(t,!0)))),r}function Bt(e){return e.toISOString().replace(/T.*$/,"")}function zt(e){return Ze(e.getUTCHours(),2)+":"+Ze(e.getUTCMinutes(),2)+":"+Ze(e.getUTCSeconds(),2)}function jt(e,t){void 0===t&&(t=!1);var n=e<0?"-":"+",r=Math.abs(e),o=Math.floor(r/60),i=Math.round(r%60);return t?n+Ze(o,2)+":"+Ze(i,2):"GMT"+n+o+(i?":"+Ze(i,2):"")}function Gt(e,t,n){if(e===t)return!0;var r,o=e.length;if(o!==t.length)return!1;for(r=0;r<o;r+=1)if(!(n?n(e[r],t[r]):e[r]===t[r]))return!1;return!0}function qt(e,t,n){var r,o;return function(){for(var i=[],a=0;a<arguments.length;a++)i[a]=arguments[a];if(r){if(!Gt(r,i)){n&&n(o);var s=e.apply(this,i);t&&t(s,o)||(o=s)}}else o=e.apply(this,i);return r=i,o}}function Yt(e,t,n){var r,o,i=this;return function(a){if(r){if(!wt(r,a)){n&&n(o);var s=e.call(i,a);t&&t(s,o)||(o=s)}}else o=e.call(i,a);return r=a,o}}var Zt={week:3,separator:0,omitZeroMinute:0,meridiem:0,omitCommas:0},Xt={timeZoneName:7,era:6,year:5,month:4,day:2,weekday:2,hour:1,minute:1,second:1},Kt=/\s*([ap])\.?m\.?/i,$t=/,/g,Jt=/\s+/g,Qt=/\u200e/g,en=/UTC|GMT/,tn=function(){function e(e){var t={},n={},r=0;for(var o in e)o in Zt?(n[o]=e[o],r=Math.max(Zt[o],r)):(t[o]=e[o],o in Xt&&(r=Math.max(Xt[o],r)));this.standardDateProps=t,this.extendedSettings=n,this.severity=r,this.buildFormattingFunc=qt(nn)}return e.prototype.format=function(e,t){return this.buildFormattingFunc(this.standardDateProps,this.extendedSettings,t)(e)},e.prototype.formatRange=function(e,t,n,r){var o=this.standardDateProps,i=this.extendedSettings,a=function(e,t,n){if(n.getMarkerYear(e)!==n.getMarkerYear(t))return 5;if(n.getMarkerMonth(e)!==n.getMarkerMonth(t))return 4;if(n.getMarkerDay(e)!==n.getMarkerDay(t))return 2;if(gt(e)!==gt(t))return 1;return 0}(e.marker,t.marker,n.calendarSystem);if(!a)return this.format(e,n);var s=a;!(s>1)||"numeric"!==o.year&&"2-digit"!==o.year||"numeric"!==o.month&&"2-digit"!==o.month||"numeric"!==o.day&&"2-digit"!==o.day||(s=1);var l=this.format(e,n),u=this.format(t,n);if(l===u)return l;var c=nn(function(e,t){var n={};for(var r in e)(!(r in Xt)||Xt[r]<=t)&&(n[r]=e[r]);return n}(o,s),i,n),d=c(e),p=c(t),f=function(e,t,n,r){var o=0;for(;o<e.length;){var i=e.indexOf(t,o);if(-1===i)break;var a=e.substr(0,i);o=i+t.length;for(var s=e.substr(o),l=0;l<n.length;){var u=n.indexOf(r,l);if(-1===u)break;var c=n.substr(0,u);l=u+r.length;var d=n.substr(l);if(a===c&&s===d)return{before:a,after:s}}}return null}(l,d,u,p),h=i.separator||r||n.defaultSeparator||"";return f?f.before+d+h+p+f.after:l+h+u},e.prototype.getLargestUnit=function(){switch(this.severity){case 7:case 6:case 5:return"year";case 4:return"month";case 3:return"week";case 2:return"day";default:return"time"}},e}();function nn(e,t,n){var o=Object.keys(e).length;return 1===o&&"short"===e.timeZoneName?function(e){return jt(e.timeZoneOffset)}:0===o&&t.week?function(e){return function(e,t,n,r,o){var i=[];"long"===o?i.push(n):"short"!==o&&"narrow"!==o||i.push(t);"long"!==o&&"short"!==o||i.push(" ");i.push(r.simpleNumberFormat.format(e)),"rtl"===r.options.direction&&i.reverse();return i.join("")}(n.computeWeekNumber(e.marker),n.weekText,n.weekTextLong,n.locale,t.week)}:function(e,t,n){e=r({},e),t=r({},t),function(e,t){e.timeZoneName&&(e.hour||(e.hour="2-digit"),e.minute||(e.minute="2-digit"));"long"===e.timeZoneName&&(e.timeZoneName="short");t.omitZeroMinute&&(e.second||e.millisecond)&&delete t.omitZeroMinute}(e,t),e.timeZone="UTC";var o,i=new Intl.DateTimeFormat(n.locale.codes,e);if(t.omitZeroMinute){var a=r({},e);delete a.minute,o=new Intl.DateTimeFormat(n.locale.codes,a)}return function(r){var a=r.marker;return function(e,t,n,r,o){e=e.replace(Qt,""),"short"===n.timeZoneName&&(e=function(e,t){var n=!1;e=e.replace(en,(function(){return n=!0,t})),n||(e+=" "+t);return e}(e,"UTC"===o.timeZone||null==t.timeZoneOffset?"UTC":jt(t.timeZoneOffset)));r.omitCommas&&(e=e.replace($t,"").trim());r.omitZeroMinute&&(e=e.replace(":00",""));!1===r.meridiem?e=e.replace(Kt,"").trim():"narrow"===r.meridiem?e=e.replace(Kt,(function(e,t){return t.toLocaleLowerCase()})):"short"===r.meridiem?e=e.replace(Kt,(function(e,t){return t.toLocaleLowerCase()+"m"})):"lowercase"===r.meridiem&&(e=e.replace(Kt,(function(e){return e.toLocaleLowerCase()})));return e=(e=e.replace(Jt," ")).trim()}((o&&!a.getUTCMinutes()?o:i).format(a),r,e,t,n)}}(e,t,n)}function rn(e,t){var n=t.markerToArray(e.marker);return{marker:e.marker,timeZoneOffset:e.timeZoneOffset,array:n,year:n[0],month:n[1],day:n[2],hour:n[3],minute:n[4],second:n[5],millisecond:n[6]}}function on(e,t,n,r){var o=rn(e,n.calendarSystem);return{date:o,start:o,end:t?rn(t,n.calendarSystem):null,timeZone:n.timeZone,localeCodes:n.locale.codes,defaultSeparator:r||n.defaultSeparator}}var an=function(){function e(e){this.cmdStr=e}return e.prototype.format=function(e,t,n){return t.cmdFormatter(this.cmdStr,on(e,null,t,n))},e.prototype.formatRange=function(e,t,n,r){return n.cmdFormatter(this.cmdStr,on(e,t,n,r))},e}(),sn=function(){function e(e){this.func=e}return e.prototype.format=function(e,t,n){return this.func(on(e,null,t,n))},e.prototype.formatRange=function(e,t,n,r){return this.func(on(e,t,n,r))},e}();function ln(e){return"object"==typeof e&&e?new tn(e):"string"==typeof e?new an(e):"function"==typeof e?new sn(e):null}var un={navLinkDayClick:yn,navLinkWeekClick:yn,duration:Nt,bootstrapFontAwesome:yn,buttonIcons:yn,customButtons:yn,defaultAllDayEventDuration:Nt,defaultTimedEventDuration:Nt,nextDayThreshold:Nt,scrollTime:Nt,scrollTimeReset:Boolean,slotMinTime:Nt,slotMaxTime:Nt,dayPopoverFormat:ln,slotDuration:Nt,snapDuration:Nt,headerToolbar:yn,footerToolbar:yn,defaultRangeSeparator:String,titleRangeSeparator:String,forceEventDuration:Boolean,dayHeaders:Boolean,dayHeaderFormat:ln,dayHeaderClassNames:yn,dayHeaderContent:yn,dayHeaderDidMount:yn,dayHeaderWillUnmount:yn,dayCellClassNames:yn,dayCellContent:yn,dayCellDidMount:yn,dayCellWillUnmount:yn,initialView:String,aspectRatio:Number,weekends:Boolean,weekNumberCalculation:yn,weekNumbers:Boolean,weekNumberClassNames:yn,weekNumberContent:yn,weekNumberDidMount:yn,weekNumberWillUnmount:yn,editable:Boolean,viewClassNames:yn,viewDidMount:yn,viewWillUnmount:yn,nowIndicator:Boolean,nowIndicatorClassNames:yn,nowIndicatorContent:yn,nowIndicatorDidMount:yn,nowIndicatorWillUnmount:yn,showNonCurrentDates:Boolean,lazyFetching:Boolean,startParam:String,endParam:String,timeZoneParam:String,timeZone:String,locales:yn,locale:yn,themeSystem:String,dragRevertDuration:Number,dragScroll:Boolean,allDayMaintainDuration:Boolean,unselectAuto:Boolean,dropAccept:yn,eventOrder:je,eventOrderStrict:Boolean,handleWindowResize:Boolean,windowResizeDelay:Number,longPressDelay:Number,eventDragMinDistance:Number,expandRows:Boolean,height:yn,contentHeight:yn,direction:String,weekNumberFormat:ln,eventResizableFromStart:Boolean,displayEventTime:Boolean,displayEventEnd:Boolean,weekText:String,weekTextLong:String,progressiveEventRendering:Boolean,businessHours:yn,initialDate:yn,now:yn,eventDataTransform:yn,stickyHeaderDates:yn,stickyFooterScrollbar:yn,viewHeight:yn,defaultAllDay:Boolean,eventSourceFailure:yn,eventSourceSuccess:yn,eventDisplay:String,eventStartEditable:Boolean,eventDurationEditable:Boolean,eventOverlap:yn,eventConstraint:yn,eventAllow:yn,eventBackgroundColor:String,eventBorderColor:String,eventTextColor:String,eventColor:String,eventClassNames:yn,eventContent:yn,eventDidMount:yn,eventWillUnmount:yn,selectConstraint:yn,selectOverlap:yn,selectAllow:yn,droppable:Boolean,unselectCancel:String,slotLabelFormat:yn,slotLaneClassNames:yn,slotLaneContent:yn,slotLaneDidMount:yn,slotLaneWillUnmount:yn,slotLabelClassNames:yn,slotLabelContent:yn,slotLabelDidMount:yn,slotLabelWillUnmount:yn,dayMaxEvents:yn,dayMaxEventRows:yn,dayMinWidth:Number,slotLabelInterval:Nt,allDayText:String,allDayClassNames:yn,allDayContent:yn,allDayDidMount:yn,allDayWillUnmount:yn,slotMinWidth:Number,navLinks:Boolean,eventTimeFormat:ln,rerenderDelay:Number,moreLinkText:yn,moreLinkHint:yn,selectMinDistance:Number,selectable:Boolean,selectLongPressDelay:Number,eventLongPressDelay:Number,selectMirror:Boolean,eventMaxStack:Number,eventMinHeight:Number,eventMinWidth:Number,eventShortHeight:Number,slotEventOverlap:Boolean,plugins:yn,firstDay:Number,dayCount:Number,dateAlignment:String,dateIncrement:Nt,hiddenDays:yn,monthMode:Boolean,fixedWeekCount:Boolean,validRange:yn,visibleRange:yn,titleFormat:yn,eventInteractive:Boolean,noEventsText:String,viewHint:yn,navLinkHint:yn,closeHint:String,timeHint:String,eventHint:String,moreLinkClick:yn,moreLinkClassNames:yn,moreLinkContent:yn,moreLinkDidMount:yn,moreLinkWillUnmount:yn},cn={eventDisplay:"auto",defaultRangeSeparator:" - ",titleRangeSeparator:" – ",defaultTimedEventDuration:"01:00:00",defaultAllDayEventDuration:{day:1},forceEventDuration:!1,nextDayThreshold:"00:00:00",dayHeaders:!0,initialView:"",aspectRatio:1.35,headerToolbar:{start:"title",center:"",end:"today prev,next"},weekends:!0,weekNumbers:!1,weekNumberCalculation:"local",editable:!1,nowIndicator:!1,scrollTime:"06:00:00",scrollTimeReset:!0,slotMinTime:"00:00:00",slotMaxTime:"24:00:00",showNonCurrentDates:!0,lazyFetching:!0,startParam:"start",endParam:"end",timeZoneParam:"timeZone",timeZone:"local",locales:[],locale:"",themeSystem:"standard",dragRevertDuration:500,dragScroll:!0,allDayMaintainDuration:!1,unselectAuto:!0,dropAccept:"*",eventOrder:"start,-duration,allDay,title",dayPopoverFormat:{month:"long",day:"numeric",year:"numeric"},handleWindowResize:!0,windowResizeDelay:100,longPressDelay:1e3,eventDragMinDistance:5,expandRows:!1,navLinks:!1,selectable:!1,eventMinHeight:15,eventMinWidth:30,eventShortHeight:30},dn={datesSet:yn,eventsSet:yn,eventAdd:yn,eventChange:yn,eventRemove:yn,windowResize:yn,eventClick:yn,eventMouseEnter:yn,eventMouseLeave:yn,select:yn,unselect:yn,loading:yn,_unmount:yn,_beforeprint:yn,_afterprint:yn,_noEventDrop:yn,_noEventResize:yn,_resize:yn,_scrollRequest:yn},pn={buttonText:yn,buttonHints:yn,views:yn,plugins:yn,initialEvents:yn,events:yn,eventSources:yn},fn={headerToolbar:hn,footerToolbar:hn,buttonText:hn,buttonHints:hn,buttonIcons:hn};function hn(e,t){return"object"==typeof e&&"object"==typeof t&&e&&t?wt(e,t):e===t}var vn={type:String,component:yn,buttonText:String,buttonTextKey:String,dateProfileGeneratorClass:yn,usesMinMaxTime:Boolean,classNames:yn,content:yn,didMount:yn,willUnmount:yn};function gn(e){return Et(e,fn)}function mn(e,t){var n={},r={};for(var o in t)o in e&&(n[o]=t[o](e[o]));for(var o in e)o in t||(r[o]=e[o]);return{refined:n,extra:r}}function yn(e){return e}function En(e,t,n,r){for(var o={defs:{},instances:{}},i=An(n),a=0,s=e;a<s.length;a++){var l=Hn(s[a],t,n,r,i);l&&Sn(l,o)}return o}function Sn(e,t){return void 0===t&&(t={defs:{},instances:{}}),t.defs[e.def.defId]=e.def,e.instance&&(t.instances[e.instance.instanceId]=e.instance),t}function bn(e,t){var n=e.instances[t];if(n){var r=e.defs[n.defId],o=wn(e,(function(e){return t=r,n=e,Boolean(t.groupId&&t.groupId===n.groupId);var t,n}));return o.defs[r.defId]=r,o.instances[n.instanceId]=n,o}return{defs:{},instances:{}}}function Dn(){return{defs:{},instances:{}}}function Cn(e,t){return{defs:r(r({},e.defs),t.defs),instances:r(r({},e.instances),t.instances)}}function wn(e,t){var n=St(e.defs,t),r=St(e.instances,(function(e){return n[e.defId]}));return{defs:n,instances:r}}function Rn(e){return Array.isArray(e)?e:"string"==typeof e?e.split(/\s+/):[]}var _n={display:String,editable:Boolean,startEditable:Boolean,durationEditable:Boolean,constraint:yn,overlap:yn,allow:yn,className:Rn,classNames:Rn,color:String,backgroundColor:String,borderColor:String,textColor:String},Tn={display:null,startEditable:null,durationEditable:null,constraints:[],overlap:null,allows:[],backgroundColor:"",borderColor:"",textColor:"",classNames:[]};function kn(e,t){var n=function(e,t){return Array.isArray(e)?En(e,null,t,!0):"object"==typeof e&&e?En([e],null,t,!0):null!=e?String(e):null}(e.constraint,t);return{display:e.display||null,startEditable:null!=e.startEditable?e.startEditable:e.editable,durationEditable:null!=e.durationEditable?e.durationEditable:e.editable,constraints:null!=n?[n]:[],overlap:null!=e.overlap?e.overlap:null,allows:null!=e.allow?[e.allow]:[],backgroundColor:e.backgroundColor||e.color||"",borderColor:e.borderColor||e.color||"",textColor:e.textColor||"",classNames:(e.className||[]).concat(e.classNames||[])}}function xn(e){return e.reduce(Mn,Tn)}function Mn(e,t){return{display:null!=t.display?t.display:e.display,startEditable:null!=t.startEditable?t.startEditable:e.startEditable,durationEditable:null!=t.durationEditable?t.durationEditable:e.durationEditable,constraints:e.constraints.concat(t.constraints),overlap:"boolean"==typeof t.overlap?t.overlap:e.overlap,allows:e.allows.concat(t.allows),backgroundColor:t.backgroundColor||e.backgroundColor,borderColor:t.borderColor||e.borderColor,textColor:t.textColor||e.textColor,classNames:e.classNames.concat(t.classNames)}}var In={id:String,groupId:String,title:String,url:String,interactive:Boolean},Pn={start:yn,end:yn,date:yn,allDay:Boolean},Nn=r(r(r({},In),Pn),{extendedProps:yn});function Hn(e,t,n,r,o){void 0===o&&(o=An(n));var i=On(e,n,o),a=i.refined,s=i.extra,l=function(e,t){var n=null;e&&(n=e.defaultAllDay);null==n&&(n=t.options.defaultAllDay);return n}(t,n),u=function(e,t,n,r){for(var o=0;o<r.length;o+=1){var i=r[o].parse(e,n);if(i){var a=e.allDay;return null==a&&null==(a=t)&&null==(a=i.allDayGuess)&&(a=!1),{allDay:a,duration:i.duration,typeData:i.typeData,typeId:o}}}return null}(a,l,n.dateEnv,n.pluginHooks.recurringTypes);if(u)return(c=Ln(a,s,t?t.sourceId:"",u.allDay,Boolean(u.duration),n)).recurringDef={typeId:u.typeId,typeData:u.typeData,duration:u.duration},{def:c,instance:null};var c,d=function(e,t,n,r){var o,i,a=e.allDay,s=null,l=!1,u=null,c=null!=e.start?e.start:e.date;if(o=n.dateEnv.createMarkerMeta(c))s=o.marker;else if(!r)return null;null!=e.end&&(i=n.dateEnv.createMarkerMeta(e.end));null==a&&(a=null!=t?t:(!o||o.isTimeUnspecified)&&(!i||i.isTimeUnspecified));a&&s&&(s=lt(s));i&&(u=i.marker,a&&(u=lt(u)),s&&u<=s&&(u=null));u?l=!0:r||(l=n.options.forceEventDuration||!1,u=n.dateEnv.add(s,a?n.options.defaultAllDayEventDuration:n.options.defaultTimedEventDuration));return{allDay:a,hasEnd:l,range:{start:s,end:u},forcedStartTzo:o?o.forcedTzo:null,forcedEndTzo:i?i.forcedTzo:null}}(a,l,n,r);return d?{def:c=Ln(a,s,t?t.sourceId:"",d.allDay,d.hasEnd,n),instance:mt(c.defId,d.range,d.forcedStartTzo,d.forcedEndTzo)}:null}function On(e,t,n){return void 0===n&&(n=An(t)),mn(e,n)}function An(e){return r(r(r({},_n),Nn),e.pluginHooks.eventRefiners)}function Ln(e,t,n,o,i,a){for(var s={title:e.title||"",groupId:e.groupId||"",publicId:e.id||"",url:e.url||"",recurringDef:null,defId:Le(),sourceId:n,allDay:o,hasEnd:i,interactive:e.interactive,ui:kn(e,a),extendedProps:r(r({},e.extendedProps||{}),t)},l=0,u=a.pluginHooks.eventDefMemberAdders;l<u.length;l++){var c=u[l];r(s,c(e))}return Object.freeze(s.ui.classNames),Object.freeze(s.extendedProps),s}function Un(e){var t=Math.floor(ot(e.start,e.end))||1,n=lt(e.start);return{start:n,end:tt(n,t)}}function Wn(e,t){void 0===t&&(t=Nt(0));var n=null,r=null;if(e.end){r=lt(e.end);var o=e.end.valueOf()-r.valueOf();o&&o>=Ut(t)&&(r=tt(r,1))}return e.start&&(n=lt(e.start),r&&r<=n&&(r=tt(n,1))),{start:n,end:r}}function Vn(e){var t=Wn(e);return ot(t.start,t.end)>1}function Fn(e,t,n,r){return"year"===r?Nt(n.diffWholeYears(e,t),"year"):"month"===r?Nt(n.diffWholeMonths(e,t),"month"):it(e,t)}function Bn(e,t){var n,r,o=[],i=t.start;for(e.sort(zn),n=0;n<e.length;n+=1)(r=e[n]).start>i&&o.push({start:i,end:r.start}),r.end>i&&(i=r.end);return i<t.end&&o.push({start:i,end:t.end}),o}function zn(e,t){return e.start.valueOf()-t.start.valueOf()}function jn(e,t){var n=e.start,r=e.end,o=null;return null!==t.start&&(n=null===n?t.start:new Date(Math.max(n.valueOf(),t.start.valueOf()))),null!=t.end&&(r=null===r?t.end:new Date(Math.min(r.valueOf(),t.end.valueOf()))),(null===n||null===r||n<r)&&(o={start:n,end:r}),o}function Gn(e,t){return(null===e.start?null:e.start.valueOf())===(null===t.start?null:t.start.valueOf())&&(null===e.end?null:e.end.valueOf())===(null===t.end?null:t.end.valueOf())}function qn(e,t){return(null===e.end||null===t.start||e.end>t.start)&&(null===e.start||null===t.end||e.start<t.end)}function Yn(e,t){return(null===e.start||null!==t.start&&t.start>=e.start)&&(null===e.end||null!==t.end&&t.end<=e.end)}function Zn(e,t){return(null===e.start||t>=e.start)&&(null===e.end||t<e.end)}function Xn(e,t,n,r){var o={},i={},a={},s=[],l=[],u=Qn(e.defs,t);for(var c in e.defs){"inverse-background"===(f=u[(S=e.defs[c]).defId]).display&&(S.groupId?(o[S.groupId]=[],a[S.groupId]||(a[S.groupId]=S)):i[c]=[])}for(var d in e.instances){var p=e.instances[d],f=u[(S=e.defs[p.defId]).defId],h=p.range,v=!S.allDay&&r?Wn(h,r):h,g=jn(v,n);g&&("inverse-background"===f.display?S.groupId?o[S.groupId].push(g):i[p.defId].push(g):"none"!==f.display&&("background"===f.display?s:l).push({def:S,ui:f,instance:p,range:g,isStart:v.start&&v.start.valueOf()===g.start.valueOf(),isEnd:v.end&&v.end.valueOf()===g.end.valueOf()}))}for(var m in o)for(var y=0,E=Bn(o[m],n);y<E.length;y++){var S,b=E[y];f=u[(S=a[m]).defId];s.push({def:S,ui:f,instance:null,range:b,isStart:!1,isEnd:!1})}for(var c in i)for(var D=0,C=Bn(i[c],n);D<C.length;D++){b=C[D];s.push({def:e.defs[c],ui:u[c],instance:null,range:b,isStart:!1,isEnd:!1})}return{bg:s,fg:l}}function Kn(e){return"background"===e.ui.display||"inverse-background"===e.ui.display}function $n(e,t){e.fcSeg=t}function Jn(e){return e.fcSeg||e.parentNode.fcSeg||null}function Qn(e,t){return bt(e,(function(e){return er(e,t)}))}function er(e,t){var n=[];return t[""]&&n.push(t[""]),t[e.defId]&&n.push(t[e.defId]),n.push(e.ui),xn(n)}function tr(e,t){var n=e.map(nr);return n.sort((function(e,n){return Ge(e,n,t)})),n.map((function(e){return e._seg}))}function nr(e){var t=e.eventRange,n=t.def,o=t.instance?t.instance.range:t.range,i=o.start?o.start.valueOf():0,a=o.end?o.end.valueOf():0;return r(r(r({},n.extendedProps),n),{id:n.publicId,start:i,end:a,duration:a-i,allDay:Number(n.allDay),_seg:e})}function rr(e,t){for(var n=t.pluginHooks.isDraggableTransformers,r=e.eventRange,o=r.def,i=r.ui,a=i.startEditable,s=0,l=n;s<l.length;s++){a=(0,l[s])(a,o,i,t)}return a}function or(e,t){return e.isStart&&e.eventRange.ui.durationEditable&&t.options.eventResizableFromStart}function ir(e,t){return e.isEnd&&e.eventRange.ui.durationEditable}function ar(e,t,n,r,o,i,a){var s=n.dateEnv,l=n.options,u=l.displayEventTime,c=l.displayEventEnd,d=e.eventRange.def,p=e.eventRange.instance;null==u&&(u=!1!==r),null==c&&(c=!1!==o);var f=p.range.start,h=p.range.end,v=i||e.start||e.eventRange.range.start,g=a||e.end||e.eventRange.range.end,m=lt(f).valueOf()===lt(v).valueOf(),y=lt(nt(h,-1)).valueOf()===lt(nt(g,-1)).valueOf();return u&&!d.allDay&&(m||y)?(v=m?f:v,g=y?h:g,c&&d.hasEnd?s.formatRange(v,g,t,{forcedStartTzo:i?null:p.forcedStartTzo,forcedEndTzo:a?null:p.forcedEndTzo}):s.format(v,t,{forcedTzo:i?null:p.forcedStartTzo})):""}function sr(e,t,n){var r=e.eventRange.range;return{isPast:r.end<(n||t.start),isFuture:r.start>=(n||t.end),isToday:t&&Zn(t,r.start)}}function lr(e){var t=["fc-event"];return e.isMirror&&t.push("fc-event-mirror"),e.isDraggable&&t.push("fc-event-draggable"),(e.isStartResizable||e.isEndResizable)&&t.push("fc-event-resizable"),e.isDragging&&t.push("fc-event-dragging"),e.isResizing&&t.push("fc-event-resizing"),e.isSelected&&t.push("fc-event-selected"),e.isStart&&t.push("fc-event-start"),e.isEnd&&t.push("fc-event-end"),e.isPast&&t.push("fc-event-past"),e.isToday&&t.push("fc-event-today"),e.isFuture&&t.push("fc-event-future"),t}function ur(e){return e.instance?e.instance.instanceId:e.def.defId+":"+e.range.start.toISOString()}function cr(e,t){var n=e.eventRange,r=n.def,o=n.instance,i=r.url;if(i)return{href:i};var a=t.emitter,s=t.options.eventInteractive;return null==s&&null==(s=r.interactive)&&(s=Boolean(a.hasHandlers("eventClick"))),s?Oe((function(e){a.trigger("eventClick",{el:e.target,event:new xr(t,r,o),jsEvent:e,view:t.viewApi})})):{}}var dr={start:yn,end:yn,allDay:Boolean};function pr(e,t,n){var o=function(e,t){var n=mn(e,dr),o=n.refined,i=n.extra,a=o.start?t.createMarkerMeta(o.start):null,s=o.end?t.createMarkerMeta(o.end):null,l=o.allDay;null==l&&(l=a&&a.isTimeUnspecified&&(!s||s.isTimeUnspecified));return r({range:{start:a?a.marker:null,end:s?s.marker:null},allDay:l},i)}(e,t),i=o.range;if(!i.start)return null;if(!i.end){if(null==n)return null;i.end=t.add(i.start,n)}return o}function fr(e,t){return Gn(e.range,t.range)&&e.allDay===t.allDay&&function(e,t){for(var n in t)if("range"!==n&&"allDay"!==n&&e[n]!==t[n])return!1;for(var n in e)if(!(n in t))return!1;return!0}(e,t)}function hr(e,t,n){return r(r({},vr(e,t,n)),{timeZone:t.timeZone})}function vr(e,t,n){return{start:t.toDate(e.start),end:t.toDate(e.end),startStr:t.formatIso(e.start,{omitTime:n}),endStr:t.formatIso(e.end,{omitTime:n})}}function gr(e,t,n){var r=On({editable:!1},n),o=Ln(r.refined,r.extra,"",e.allDay,!0,n);return{def:o,ui:er(o,t),instance:mt(o.defId,e.range),range:e.range,isStart:!0,isEnd:!0}}function mr(e,t,n){n.emitter.trigger("select",r(r({},yr(e,n)),{jsEvent:t?t.origEvent:null,view:n.viewApi||n.calendarApi.view}))}function yr(e,t){for(var n,o,i={},a=0,s=t.pluginHooks.dateSpanTransforms;a<s.length;a++){var l=s[a];r(i,l(e,t))}return r(i,(n=e,o=t.dateEnv,r(r({},vr(n.range,o,n.allDay)),{allDay:n.allDay}))),i}function Er(e,t,n){var r=n.dateEnv,o=n.options,i=t;return e?(i=lt(i),i=r.add(i,o.defaultAllDayEventDuration)):i=r.add(i,o.defaultTimedEventDuration),i}function Sr(e,t,n,r){var o=Qn(e.defs,t),i={defs:{},instances:{}};for(var a in e.defs){var s=e.defs[a];i.defs[a]=br(s,o[a],n,r)}for(var l in e.instances){var u=e.instances[l];s=i.defs[u.defId];i.instances[l]=Dr(u,s,o[u.defId],n,r)}return i}function br(e,t,n,o){var i=n.standardProps||{};null==i.hasEnd&&t.durationEditable&&(n.startDelta||n.endDelta)&&(i.hasEnd=!0);var a=r(r(r({},e),i),{ui:r(r({},e.ui),i.ui)});n.extendedProps&&(a.extendedProps=r(r({},a.extendedProps),n.extendedProps));for(var s=0,l=o.pluginHooks.eventDefMutationAppliers;s<l.length;s++){(0,l[s])(a,n,o)}return!a.hasEnd&&o.options.forceEventDuration&&(a.hasEnd=!0),a}function Dr(e,t,n,o,i){var a=i.dateEnv,s=o.standardProps&&!0===o.standardProps.allDay,l=o.standardProps&&!1===o.standardProps.hasEnd,u=r({},e);return s&&(u.range=Un(u.range)),o.datesDelta&&n.startEditable&&(u.range={start:a.add(u.range.start,o.datesDelta),end:a.add(u.range.end,o.datesDelta)}),o.startDelta&&n.durationEditable&&(u.range={start:a.add(u.range.start,o.startDelta),end:u.range.end}),o.endDelta&&n.durationEditable&&(u.range={start:u.range.start,end:a.add(u.range.end,o.endDelta)}),l&&(u.range={start:u.range.start,end:Er(t.allDay,u.range.start,i)}),t.allDay&&(u.range={start:lt(u.range.start),end:lt(u.range.end)}),u.range.end<u.range.start&&(u.range.end=Er(t.allDay,u.range.start,i)),u}var Cr=function(){function e(e,t,n){this.type=e,this.getCurrentData=t,this.dateEnv=n}return Object.defineProperty(e.prototype,"calendar",{get:function(){return this.getCurrentData().calendarApi},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"title",{get:function(){return this.getCurrentData().viewTitle},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"activeStart",{get:function(){return this.dateEnv.toDate(this.getCurrentData().dateProfile.activeRange.start)},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"activeEnd",{get:function(){return this.dateEnv.toDate(this.getCurrentData().dateProfile.activeRange.end)},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"currentStart",{get:function(){return this.dateEnv.toDate(this.getCurrentData().dateProfile.currentRange.start)},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"currentEnd",{get:function(){return this.dateEnv.toDate(this.getCurrentData().dateProfile.currentRange.end)},enumerable:!1,configurable:!0}),e.prototype.getOption=function(e){return this.getCurrentData().options[e]},e}(),wr={id:String,defaultAllDay:Boolean,url:String,format:String,events:yn,eventDataTransform:yn,success:yn,failure:yn};function Rr(e,t,n){var r;if(void 0===n&&(n=_r(t)),"string"==typeof e?r={url:e}:"function"==typeof e||Array.isArray(e)?r={events:e}:"object"==typeof e&&e&&(r=e),r){var o=mn(r,n),i=o.refined,a=o.extra,s=function(e,t){for(var n=t.pluginHooks.eventSourceDefs,r=n.length-1;r>=0;r-=1){var o=n[r].parseMeta(e);if(o)return{sourceDefId:r,meta:o}}return null}(i,t);if(s)return{_raw:e,isFetching:!1,latestFetchId:"",fetchRange:null,defaultAllDay:i.defaultAllDay,eventDataTransform:i.eventDataTransform,success:i.success,failure:i.failure,publicId:i.id||"",sourceId:Le(),sourceDefId:s.sourceDefId,meta:s.meta,ui:kn(i,t),extendedProps:a}}return null}function _r(e){return r(r(r({},_n),wr),e.pluginHooks.eventSourceRefiners)}function Tr(e,t){return"function"==typeof e&&(e=e()),null==e?t.createNowMarker():t.createMarker(e)}var kr=function(){function e(){}return e.prototype.getCurrentData=function(){return this.currentDataManager.getCurrentData()},e.prototype.dispatch=function(e){return this.currentDataManager.dispatch(e)},Object.defineProperty(e.prototype,"view",{get:function(){return this.getCurrentData().viewApi},enumerable:!1,configurable:!0}),e.prototype.batchRendering=function(e){e()},e.prototype.updateSize=function(){this.trigger("_resize",!0)},e.prototype.setOption=function(e,t){this.dispatch({type:"SET_OPTION",optionName:e,rawOptionValue:t})},e.prototype.getOption=function(e){return this.currentDataManager.currentCalendarOptionsInput[e]},e.prototype.getAvailableLocaleCodes=function(){return Object.keys(this.getCurrentData().availableRawLocales)},e.prototype.on=function(e,t){var n=this.currentDataManager;n.currentCalendarOptionsRefiners[e]?n.emitter.on(e,t):console.warn("Unknown listener name '"+e+"'")},e.prototype.off=function(e,t){this.currentDataManager.emitter.off(e,t)},e.prototype.trigger=function(e){for(var t,n=[],r=1;r<arguments.length;r++)n[r-1]=arguments[r];(t=this.currentDataManager.emitter).trigger.apply(t,o([e],n))},e.prototype.changeView=function(e,t){var n=this;this.batchRendering((function(){if(n.unselect(),t)if(t.start&&t.end)n.dispatch({type:"CHANGE_VIEW_TYPE",viewType:e}),n.dispatch({type:"SET_OPTION",optionName:"visibleRange",rawOptionValue:t});else{var r=n.getCurrentData().dateEnv;n.dispatch({type:"CHANGE_VIEW_TYPE",viewType:e,dateMarker:r.createMarker(t)})}else n.dispatch({type:"CHANGE_VIEW_TYPE",viewType:e})}))},e.prototype.zoomTo=function(e,t){var n;t=t||"day",n=this.getCurrentData().viewSpecs[t]||this.getUnitViewSpec(t),this.unselect(),n?this.dispatch({type:"CHANGE_VIEW_TYPE",viewType:n.type,dateMarker:e}):this.dispatch({type:"CHANGE_DATE",dateMarker:e})},e.prototype.getUnitViewSpec=function(e){var t,n,r=this.getCurrentData(),o=r.viewSpecs,i=r.toolbarConfig,a=[].concat(i.header?i.header.viewsWithButtons:[],i.footer?i.footer.viewsWithButtons:[]);for(var s in o)a.push(s);for(t=0;t<a.length;t+=1)if((n=o[a[t]])&&n.singleUnit===e)return n;return null},e.prototype.prev=function(){this.unselect(),this.dispatch({type:"PREV"})},e.prototype.next=function(){this.unselect(),this.dispatch({type:"NEXT"})},e.prototype.prevYear=function(){var e=this.getCurrentData();this.unselect(),this.dispatch({type:"CHANGE_DATE",dateMarker:e.dateEnv.addYears(e.currentDate,-1)})},e.prototype.nextYear=function(){var e=this.getCurrentData();this.unselect(),this.dispatch({type:"CHANGE_DATE",dateMarker:e.dateEnv.addYears(e.currentDate,1)})},e.prototype.today=function(){var e=this.getCurrentData();this.unselect(),this.dispatch({type:"CHANGE_DATE",dateMarker:Tr(e.calendarOptions.now,e.dateEnv)})},e.prototype.gotoDate=function(e){var t=this.getCurrentData();this.unselect(),this.dispatch({type:"CHANGE_DATE",dateMarker:t.dateEnv.createMarker(e)})},e.prototype.incrementDate=function(e){var t=this.getCurrentData(),n=Nt(e);n&&(this.unselect(),this.dispatch({type:"CHANGE_DATE",dateMarker:t.dateEnv.add(t.currentDate,n)}))},e.prototype.getDate=function(){var e=this.getCurrentData();return e.dateEnv.toDate(e.currentDate)},e.prototype.formatDate=function(e,t){var n=this.getCurrentData().dateEnv;return n.format(n.createMarker(e),ln(t))},e.prototype.formatRange=function(e,t,n){var r=this.getCurrentData().dateEnv;return r.formatRange(r.createMarker(e),r.createMarker(t),ln(n),n)},e.prototype.formatIso=function(e,t){var n=this.getCurrentData().dateEnv;return n.formatIso(n.createMarker(e),{omitTime:t})},e.prototype.select=function(e,t){var n;n=null==t?null!=e.start?e:{start:e,end:null}:{start:e,end:t};var r=this.getCurrentData(),o=pr(n,r.dateEnv,Nt({days:1}));o&&(this.dispatch({type:"SELECT_DATES",selection:o}),mr(o,null,r))},e.prototype.unselect=function(e){var t=this.getCurrentData();t.dateSelection&&(this.dispatch({type:"UNSELECT_DATES"}),function(e,t){t.emitter.trigger("unselect",{jsEvent:e?e.origEvent:null,view:t.viewApi||t.calendarApi.view})}(e,t))},e.prototype.addEvent=function(e,t){if(e instanceof xr){var n=e._def,r=e._instance;return this.getCurrentData().eventStore.defs[n.defId]||(this.dispatch({type:"ADD_EVENTS",eventStore:Sn({def:n,instance:r})}),this.triggerEventAdd(e)),e}var o,i=this.getCurrentData();if(t instanceof ye)o=t.internalEventSource;else if("boolean"==typeof t)t&&(o=Ct(i.eventSources)[0]);else if(null!=t){var a=this.getEventSourceById(t);if(!a)return console.warn('Could not find an event source with ID "'+t+'"'),null;o=a.internalEventSource}var s=Hn(e,o,i,!1);if(s){var l=new xr(i,s.def,s.def.recurringDef?null:s.instance);return this.dispatch({type:"ADD_EVENTS",eventStore:Sn(s)}),this.triggerEventAdd(l),l}return null},e.prototype.triggerEventAdd=function(e){var t=this;this.getCurrentData().emitter.trigger("eventAdd",{event:e,relatedEvents:[],revert:function(){t.dispatch({type:"REMOVE_EVENTS",eventStore:Mr(e)})}})},e.prototype.getEventById=function(e){var t=this.getCurrentData(),n=t.eventStore,r=n.defs,o=n.instances;for(var i in e=String(e),r){var a=r[i];if(a.publicId===e){if(a.recurringDef)return new xr(t,a,null);for(var s in o){var l=o[s];if(l.defId===a.defId)return new xr(t,a,l)}}}return null},e.prototype.getEvents=function(){var e=this.getCurrentData();return Ir(e.eventStore,e)},e.prototype.removeAllEvents=function(){this.dispatch({type:"REMOVE_ALL_EVENTS"})},e.prototype.getEventSources=function(){var e=this.getCurrentData(),t=e.eventSources,n=[];for(var r in t)n.push(new ye(e,t[r]));return n},e.prototype.getEventSourceById=function(e){var t=this.getCurrentData(),n=t.eventSources;for(var r in e=String(e),n)if(n[r].publicId===e)return new ye(t,n[r]);return null},e.prototype.addEventSource=function(e){var t=this.getCurrentData();if(e instanceof ye)return t.eventSources[e.internalEventSource.sourceId]||this.dispatch({type:"ADD_EVENT_SOURCES",sources:[e.internalEventSource]}),e;var n=Rr(e,t);return n?(this.dispatch({type:"ADD_EVENT_SOURCES",sources:[n]}),new ye(t,n)):null},e.prototype.removeAllEventSources=function(){this.dispatch({type:"REMOVE_ALL_EVENT_SOURCES"})},e.prototype.refetchEvents=function(){this.dispatch({type:"FETCH_EVENT_SOURCES",isRefetch:!0})},e.prototype.scrollToTime=function(e){var t=Nt(e);t&&this.trigger("_scrollRequest",{time:t})},e}(),xr=function(){function e(e,t,n){this._context=e,this._def=t,this._instance=n||null}return e.prototype.setProp=function(e,t){var n,r;if(e in Pn)console.warn("Could not set date-related prop 'name'. Use one of the date-related methods instead.");else if("id"===e)t=In[e](t),this.mutate({standardProps:{publicId:t}});else if(e in In)t=In[e](t),this.mutate({standardProps:(n={},n[e]=t,n)});else if(e in _n){var o=_n[e](t);"color"===e?o={backgroundColor:t,borderColor:t}:"editable"===e?o={startEditable:t,durationEditable:t}:((r={})[e]=t,o=r),this.mutate({standardProps:{ui:o}})}else console.warn("Could not set prop '"+e+"'. Use setExtendedProp instead.")},e.prototype.setExtendedProp=function(e,t){var n;this.mutate({extendedProps:(n={},n[e]=t,n)})},e.prototype.setStart=function(e,t){void 0===t&&(t={});var n=this._context.dateEnv,r=n.createMarker(e);if(r&&this._instance){var o=Fn(this._instance.range.start,r,n,t.granularity);t.maintainDuration?this.mutate({datesDelta:o}):this.mutate({startDelta:o})}},e.prototype.setEnd=function(e,t){void 0===t&&(t={});var n,r=this._context.dateEnv;if((null==e||(n=r.createMarker(e)))&&this._instance)if(n){var o=Fn(this._instance.range.end,n,r,t.granularity);this.mutate({endDelta:o})}else this.mutate({standardProps:{hasEnd:!1}})},e.prototype.setDates=function(e,t,n){void 0===n&&(n={});var r,o,i,a=this._context.dateEnv,s={allDay:n.allDay},l=a.createMarker(e);if(l&&((null==t||(r=a.createMarker(t)))&&this._instance)){var u=this._instance.range;!0===n.allDay&&(u=Un(u));var c=Fn(u.start,l,a,n.granularity);if(r){var d=Fn(u.end,r,a,n.granularity);i=d,(o=c).years===i.years&&o.months===i.months&&o.days===i.days&&o.milliseconds===i.milliseconds?this.mutate({datesDelta:c,standardProps:s}):this.mutate({startDelta:c,endDelta:d,standardProps:s})}else s.hasEnd=!1,this.mutate({datesDelta:c,standardProps:s})}},e.prototype.moveStart=function(e){var t=Nt(e);t&&this.mutate({startDelta:t})},e.prototype.moveEnd=function(e){var t=Nt(e);t&&this.mutate({endDelta:t})},e.prototype.moveDates=function(e){var t=Nt(e);t&&this.mutate({datesDelta:t})},e.prototype.setAllDay=function(e,t){void 0===t&&(t={});var n={allDay:e},r=t.maintainDuration;null==r&&(r=this._context.options.allDayMaintainDuration),this._def.allDay!==e&&(n.hasEnd=r),this.mutate({standardProps:n})},e.prototype.formatRange=function(e){var t=this._context.dateEnv,n=this._instance,r=ln(e);return this._def.hasEnd?t.formatRange(n.range.start,n.range.end,r,{forcedStartTzo:n.forcedStartTzo,forcedEndTzo:n.forcedEndTzo}):t.format(n.range.start,r,{forcedTzo:n.forcedStartTzo})},e.prototype.mutate=function(t){var n=this._instance;if(n){var r=this._def,o=this._context,i=o.getCurrentData().eventStore,a=bn(i,n.instanceId);a=Sr(a,{"":{display:"",startEditable:!0,durationEditable:!0,constraints:[],overlap:null,allows:[],backgroundColor:"",borderColor:"",textColor:"",classNames:[]}},t,o);var s=new e(o,r,n);this._def=a.defs[r.defId],this._instance=a.instances[n.instanceId],o.dispatch({type:"MERGE_EVENTS",eventStore:a}),o.emitter.trigger("eventChange",{oldEvent:s,event:this,relatedEvents:Ir(a,o,n),revert:function(){o.dispatch({type:"RESET_EVENTS",eventStore:i})}})}},e.prototype.remove=function(){var e=this._context,t=Mr(this);e.dispatch({type:"REMOVE_EVENTS",eventStore:t}),e.emitter.trigger("eventRemove",{event:this,relatedEvents:[],revert:function(){e.dispatch({type:"MERGE_EVENTS",eventStore:t})}})},Object.defineProperty(e.prototype,"source",{get:function(){var e=this._def.sourceId;return e?new ye(this._context,this._context.getCurrentData().eventSources[e]):null},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"start",{get:function(){return this._instance?this._context.dateEnv.toDate(this._instance.range.start):null},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"end",{get:function(){return this._instance&&this._def.hasEnd?this._context.dateEnv.toDate(this._instance.range.end):null},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"startStr",{get:function(){var e=this._instance;return e?this._context.dateEnv.formatIso(e.range.start,{omitTime:this._def.allDay,forcedTzo:e.forcedStartTzo}):""},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"endStr",{get:function(){var e=this._instance;return e&&this._def.hasEnd?this._context.dateEnv.formatIso(e.range.end,{omitTime:this._def.allDay,forcedTzo:e.forcedEndTzo}):""},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"id",{get:function(){return this._def.publicId},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"groupId",{get:function(){return this._def.groupId},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"allDay",{get:function(){return this._def.allDay},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"title",{get:function(){return this._def.title},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"url",{get:function(){return this._def.url},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"display",{get:function(){return this._def.ui.display||"auto"},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"startEditable",{get:function(){return this._def.ui.startEditable},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"durationEditable",{get:function(){return this._def.ui.durationEditable},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"constraint",{get:function(){return this._def.ui.constraints[0]||null},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"overlap",{get:function(){return this._def.ui.overlap},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"allow",{get:function(){return this._def.ui.allows[0]||null},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"backgroundColor",{get:function(){return this._def.ui.backgroundColor},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"borderColor",{get:function(){return this._def.ui.borderColor},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"textColor",{get:function(){return this._def.ui.textColor},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"classNames",{get:function(){return this._def.ui.classNames},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"extendedProps",{get:function(){return this._def.extendedProps},enumerable:!1,configurable:!0}),e.prototype.toPlainObject=function(e){void 0===e&&(e={});var t=this._def,n=t.ui,o=this.startStr,i=this.endStr,a={};return t.title&&(a.title=t.title),o&&(a.start=o),i&&(a.end=i),t.publicId&&(a.id=t.publicId),t.groupId&&(a.groupId=t.groupId),t.url&&(a.url=t.url),n.display&&"auto"!==n.display&&(a.display=n.display),e.collapseColor&&n.backgroundColor&&n.backgroundColor===n.borderColor?a.color=n.backgroundColor:(n.backgroundColor&&(a.backgroundColor=n.backgroundColor),n.borderColor&&(a.borderColor=n.borderColor)),n.textColor&&(a.textColor=n.textColor),n.classNames.length&&(a.classNames=n.classNames),Object.keys(t.extendedProps).length&&(e.collapseExtendedProps?r(a,t.extendedProps):a.extendedProps=t.extendedProps),a},e.prototype.toJSON=function(){return this.toPlainObject()},e}();function Mr(e){var t,n,r=e._def,o=e._instance;return{defs:(t={},t[r.defId]=r,t),instances:o?(n={},n[o.instanceId]=o,n):{}}}function Ir(e,t,n){var r=e.defs,o=e.instances,i=[],a=n?n.instanceId:"";for(var s in o){var l=o[s],u=r[l.defId];l.instanceId!==a&&i.push(new xr(t,u,l))}return i}var Pr={};var Nr,Hr=function(){function e(){}return e.prototype.getMarkerYear=function(e){return e.getUTCFullYear()},e.prototype.getMarkerMonth=function(e){return e.getUTCMonth()},e.prototype.getMarkerDay=function(e){return e.getUTCDate()},e.prototype.arrayToMarker=function(e){return ht(e)},e.prototype.markerToArray=function(e){return ft(e)},e}();Nr=Hr,Pr["gregory"]=Nr;var Or=/^\s*(\d{4})(-?(\d{2})(-?(\d{2})([T ](\d{2}):?(\d{2})(:?(\d{2})(\.(\d+))?)?(Z|(([-+])(\d{2})(:?(\d{2}))?))?)?)?)?$/;function Ar(e){var t=Or.exec(e);if(t){var n=new Date(Date.UTC(Number(t[1]),t[3]?Number(t[3])-1:0,Number(t[5]||1),Number(t[7]||0),Number(t[8]||0),Number(t[10]||0),t[12]?1e3*Number("0."+t[12]):0));if(vt(n)){var r=null;return t[13]&&(r=("-"===t[15]?-1:1)*(60*Number(t[16]||0)+Number(t[18]||0))),{marker:n,isTimeUnspecified:!t[6],timeZoneOffset:r}}}return null}var Lr=function(){function e(e){var t=this.timeZone=e.timeZone,n="local"!==t&&"UTC"!==t;e.namedTimeZoneImpl&&n&&(this.namedTimeZoneImpl=new e.namedTimeZoneImpl(t)),this.canComputeOffset=Boolean(!n||this.namedTimeZoneImpl),this.calendarSystem=function(e){return new Pr[e]}(e.calendarSystem),this.locale=e.locale,this.weekDow=e.locale.week.dow,this.weekDoy=e.locale.week.doy,"ISO"===e.weekNumberCalculation&&(this.weekDow=1,this.weekDoy=4),"number"==typeof e.firstDay&&(this.weekDow=e.firstDay),"function"==typeof e.weekNumberCalculation&&(this.weekNumberFunc=e.weekNumberCalculation),this.weekText=null!=e.weekText?e.weekText:e.locale.options.weekText,this.weekTextLong=(null!=e.weekTextLong?e.weekTextLong:e.locale.options.weekTextLong)||this.weekText,this.cmdFormatter=e.cmdFormatter,this.defaultSeparator=e.defaultSeparator}return e.prototype.createMarker=function(e){var t=this.createMarkerMeta(e);return null===t?null:t.marker},e.prototype.createNowMarker=function(){return this.canComputeOffset?this.timestampToMarker((new Date).valueOf()):ht(dt(new Date))},e.prototype.createMarkerMeta=function(e){if("string"==typeof e)return this.parse(e);var t=null;return"number"==typeof e?t=this.timestampToMarker(e):e instanceof Date?(e=e.valueOf(),isNaN(e)||(t=this.timestampToMarker(e))):Array.isArray(e)&&(t=ht(e)),null!==t&&vt(t)?{marker:t,isTimeUnspecified:!1,forcedTzo:null}:null},e.prototype.parse=function(e){var t=Ar(e);if(null===t)return null;var n=t.marker,r=null;return null!==t.timeZoneOffset&&(this.canComputeOffset?n=this.timestampToMarker(n.valueOf()-60*t.timeZoneOffset*1e3):r=t.timeZoneOffset),{marker:n,isTimeUnspecified:t.isTimeUnspecified,forcedTzo:r}},e.prototype.getYear=function(e){return this.calendarSystem.getMarkerYear(e)},e.prototype.getMonth=function(e){return this.calendarSystem.getMarkerMonth(e)},e.prototype.add=function(e,t){var n=this.calendarSystem.markerToArray(e);return n[0]+=t.years,n[1]+=t.months,n[2]+=t.days,n[6]+=t.milliseconds,this.calendarSystem.arrayToMarker(n)},e.prototype.subtract=function(e,t){var n=this.calendarSystem.markerToArray(e);return n[0]-=t.years,n[1]-=t.months,n[2]-=t.days,n[6]-=t.milliseconds,this.calendarSystem.arrayToMarker(n)},e.prototype.addYears=function(e,t){var n=this.calendarSystem.markerToArray(e);return n[0]+=t,this.calendarSystem.arrayToMarker(n)},e.prototype.addMonths=function(e,t){var n=this.calendarSystem.markerToArray(e);return n[1]+=t,this.calendarSystem.arrayToMarker(n)},e.prototype.diffWholeYears=function(e,t){var n=this.calendarSystem;return gt(e)===gt(t)&&n.getMarkerDay(e)===n.getMarkerDay(t)&&n.getMarkerMonth(e)===n.getMarkerMonth(t)?n.getMarkerYear(t)-n.getMarkerYear(e):null},e.prototype.diffWholeMonths=function(e,t){var n=this.calendarSystem;return gt(e)===gt(t)&&n.getMarkerDay(e)===n.getMarkerDay(t)?n.getMarkerMonth(t)-n.getMarkerMonth(e)+12*(n.getMarkerYear(t)-n.getMarkerYear(e)):null},e.prototype.greatestWholeUnit=function(e,t){var n=this.diffWholeYears(e,t);return null!==n?{unit:"year",value:n}:null!==(n=this.diffWholeMonths(e,t))?{unit:"month",value:n}:null!==(n=at(e,t))?{unit:"week",value:n}:null!==(n=st(e,t))?{unit:"day",value:n}:$e(n=function(e,t){return(t.valueOf()-e.valueOf())/36e5}(e,t))?{unit:"hour",value:n}:$e(n=function(e,t){return(t.valueOf()-e.valueOf())/6e4}(e,t))?{unit:"minute",value:n}:$e(n=function(e,t){return(t.valueOf()-e.valueOf())/1e3}(e,t))?{unit:"second",value:n}:{unit:"millisecond",value:t.valueOf()-e.valueOf()}},e.prototype.countDurationsBetween=function(e,t,n){var r;return n.years&&null!==(r=this.diffWholeYears(e,t))?r/(Lt(n)/365):n.months&&null!==(r=this.diffWholeMonths(e,t))?r/function(e){return Lt(e)/30}(n):n.days&&null!==(r=st(e,t))?r/Lt(n):(t.valueOf()-e.valueOf())/Ut(n)},e.prototype.startOf=function(e,t){return"year"===t?this.startOfYear(e):"month"===t?this.startOfMonth(e):"week"===t?this.startOfWeek(e):"day"===t?lt(e):"hour"===t?function(e){return ht([e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate(),e.getUTCHours()])}(e):"minute"===t?function(e){return ht([e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate(),e.getUTCHours(),e.getUTCMinutes()])}(e):"second"===t?function(e){return ht([e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate(),e.getUTCHours(),e.getUTCMinutes(),e.getUTCSeconds()])}(e):null},e.prototype.startOfYear=function(e){return this.calendarSystem.arrayToMarker([this.calendarSystem.getMarkerYear(e)])},e.prototype.startOfMonth=function(e){return this.calendarSystem.arrayToMarker([this.calendarSystem.getMarkerYear(e),this.calendarSystem.getMarkerMonth(e)])},e.prototype.startOfWeek=function(e){return this.calendarSystem.arrayToMarker([this.calendarSystem.getMarkerYear(e),this.calendarSystem.getMarkerMonth(e),e.getUTCDate()-(e.getUTCDay()-this.weekDow+7)%7])},e.prototype.computeWeekNumber=function(e){return this.weekNumberFunc?this.weekNumberFunc(this.toDate(e)):function(e,t,n){var r=e.getUTCFullYear(),o=ut(e,r,t,n);if(o<1)return ut(e,r-1,t,n);var i=ut(e,r+1,t,n);return i>=1?Math.min(o,i):o}(e,this.weekDow,this.weekDoy)},e.prototype.format=function(e,t,n){return void 0===n&&(n={}),t.format({marker:e,timeZoneOffset:null!=n.forcedTzo?n.forcedTzo:this.offsetForMarker(e)},this)},e.prototype.formatRange=function(e,t,n,r){return void 0===r&&(r={}),r.isEndExclusive&&(t=nt(t,-1)),n.formatRange({marker:e,timeZoneOffset:null!=r.forcedStartTzo?r.forcedStartTzo:this.offsetForMarker(e)},{marker:t,timeZoneOffset:null!=r.forcedEndTzo?r.forcedEndTzo:this.offsetForMarker(t)},this,r.defaultSeparator)},e.prototype.formatIso=function(e,t){void 0===t&&(t={});var n=null;return t.omitTimeZoneOffset||(n=null!=t.forcedTzo?t.forcedTzo:this.offsetForMarker(e)),Ft(e,n,t.omitTime)},e.prototype.timestampToMarker=function(e){return"local"===this.timeZone?ht(dt(new Date(e))):"UTC"!==this.timeZone&&this.namedTimeZoneImpl?ht(this.namedTimeZoneImpl.timestampToArray(e)):new Date(e)},e.prototype.offsetForMarker=function(e){return"local"===this.timeZone?-pt(ft(e)).getTimezoneOffset():"UTC"===this.timeZone?0:this.namedTimeZoneImpl?this.namedTimeZoneImpl.offsetForArray(ft(e)):null},e.prototype.toDate=function(e,t){return"local"===this.timeZone?pt(ft(e)):"UTC"===this.timeZone?new Date(e.valueOf()):this.namedTimeZoneImpl?new Date(e.valueOf()-1e3*this.namedTimeZoneImpl.offsetForArray(ft(e))*60):new Date(e.valueOf()-(t||0))},e}(),Ur=[],Wr={code:"en",week:{dow:0,doy:4},direction:"ltr",buttonText:{prev:"prev",next:"next",prevYear:"prev year",nextYear:"next year",year:"year",today:"today",month:"month",week:"week",day:"day",list:"list"},weekText:"W",weekTextLong:"Week",closeHint:"Close",timeHint:"Time",eventHint:"Event",allDayText:"all-day",moreLinkText:"more",noEventsText:"No events to display"},Vr=r(r({},Wr),{buttonHints:{prev:"Previous $0",next:"Next $0",today:function(e,t){return"day"===t?"Today":"This "+e}},viewHint:"$0 view",navLinkHint:"Go to $0",moreLinkHint:function(e){return"Show "+e+" more event"+(1===e?"":"s")}});function Fr(e){for(var t=e.length>0?e[0].code:"en",n=Ur.concat(e),r={en:Vr},o=0,i=n;o<i.length;o++){var a=i[o];r[a.code]=a}return{map:r,defaultCode:t}}function Br(e,t){return"object"!=typeof e||Array.isArray(e)?function(e,t){var n=[].concat(e||[]),r=function(e,t){for(var n=0;n<e.length;n+=1)for(var r=e[n].toLocaleLowerCase().split("-"),o=r.length;o>0;o-=1){var i=r.slice(0,o).join("-");if(t[i])return t[i]}return null}(n,t)||Vr;return zr(e,n,r)}(e,t):zr(e.code,[e.code],e)}function zr(e,t,n){var r=Et([Wr,n],["buttonText"]);delete r.code;var o=r.week;return delete r.week,{codeArg:e,codes:t,week:o,simpleNumberFormat:new Intl.NumberFormat(e),options:r}}function jr(e){var t=Br(e.locale||"en",Fr([]).map);return new Lr(r(r({timeZone:cn.timeZone,calendarSystem:"gregory"},e),{locale:t}))}var Gr,qr={startTime:"09:00",endTime:"17:00",daysOfWeek:[1,2,3,4,5],display:"inverse-background",classNames:"fc-non-business",groupId:"_businessHours"};function Yr(e,t){return En(function(e){var t;t=!0===e?[{}]:Array.isArray(e)?e.filter((function(e){return e.daysOfWeek})):"object"==typeof e&&e?[e]:[];return t=t.map((function(e){return r(r({},qr),e)}))}(e),null,t)}function Zr(e,t){return e.left>=t.left&&e.left<t.right&&e.top>=t.top&&e.top<t.bottom}function Xr(e,t){var n={left:Math.max(e.left,t.left),right:Math.min(e.right,t.right),top:Math.max(e.top,t.top),bottom:Math.min(e.bottom,t.bottom)};return n.left<n.right&&n.top<n.bottom&&n}function Kr(e,t){return{left:Math.min(Math.max(e.left,t.left),t.right),top:Math.min(Math.max(e.top,t.top),t.bottom)}}function $r(e){return{left:(e.left+e.right)/2,top:(e.top+e.bottom)/2}}function Jr(e,t){return{left:e.left-t.left,top:e.top-t.top}}function Qr(){return null==Gr&&(Gr=function(){if("undefined"==typeof document)return!0;var e=document.createElement("div");e.style.position="absolute",e.style.top="0px",e.style.left="0px",e.innerHTML="<table><tr><td><div></div></td></tr></table>",e.querySelector("table").style.height="100px",e.querySelector("div").style.height="100%",document.body.appendChild(e);var t=e.querySelector("div").offsetHeight>0;return document.body.removeChild(e),t}()),Gr}var eo={defs:{},instances:{}},to=function(){function e(){this.getKeysForEventDefs=qt(this._getKeysForEventDefs),this.splitDateSelection=qt(this._splitDateSpan),this.splitEventStore=qt(this._splitEventStore),this.splitIndividualUi=qt(this._splitIndividualUi),this.splitEventDrag=qt(this._splitInteraction),this.splitEventResize=qt(this._splitInteraction),this.eventUiBuilders={}}return e.prototype.splitProps=function(e){var t=this,n=this.getKeyInfo(e),r=this.getKeysForEventDefs(e.eventStore),o=this.splitDateSelection(e.dateSelection),i=this.splitIndividualUi(e.eventUiBases,r),a=this.splitEventStore(e.eventStore,r),s=this.splitEventDrag(e.eventDrag),l=this.splitEventResize(e.eventResize),u={};for(var c in this.eventUiBuilders=bt(n,(function(e,n){return t.eventUiBuilders[n]||qt(no)})),n){var d=n[c],p=a[c]||eo,f=this.eventUiBuilders[c];u[c]={businessHours:d.businessHours||e.businessHours,dateSelection:o[c]||null,eventStore:p,eventUiBases:f(e.eventUiBases[""],d.ui,i[c]),eventSelection:p.instances[e.eventSelection]?e.eventSelection:"",eventDrag:s[c]||null,eventResize:l[c]||null}}return u},e.prototype._splitDateSpan=function(e){var t={};if(e)for(var n=0,r=this.getKeysForDateSpan(e);n<r.length;n++){t[r[n]]=e}return t},e.prototype._getKeysForEventDefs=function(e){var t=this;return bt(e.defs,(function(e){return t.getKeysForEventDef(e)}))},e.prototype._splitEventStore=function(e,t){var n=e.defs,r=e.instances,o={};for(var i in n)for(var a=0,s=t[i];a<s.length;a++){o[p=s[a]]||(o[p]={defs:{},instances:{}}),o[p].defs[i]=n[i]}for(var l in r)for(var u=r[l],c=0,d=t[u.defId];c<d.length;c++){var p;o[p=d[c]]&&(o[p].instances[l]=u)}return o},e.prototype._splitIndividualUi=function(e,t){var n={};for(var r in e)if(r)for(var o=0,i=t[r];o<i.length;o++){var a=i[o];n[a]||(n[a]={}),n[a][r]=e[r]}return n},e.prototype._splitInteraction=function(e){var t={};if(e){var n=this._splitEventStore(e.affectedEvents,this._getKeysForEventDefs(e.affectedEvents)),r=this._getKeysForEventDefs(e.mutatedEvents),o=this._splitEventStore(e.mutatedEvents,r),i=function(r){t[r]||(t[r]={affectedEvents:n[r]||eo,mutatedEvents:o[r]||eo,isEvent:e.isEvent})};for(var a in n)i(a);for(var a in o)i(a)}return t},e}();function no(e,t,n){var o=[];e&&o.push(e),t&&o.push(t);var i={"":xn(o)};return n&&r(i,n),i}function ro(e,t,n,r){return{dow:e.getUTCDay(),isDisabled:Boolean(r&&!Zn(r.activeRange,e)),isOther:Boolean(r&&!Zn(r.currentRange,e)),isToday:Boolean(t&&Zn(t,e)),isPast:Boolean(n?e<n:!!t&&e<t.start),isFuture:Boolean(n?e>n:!!t&&e>=t.end)}}function oo(e,t){var n=["fc-day","fc-day-"+Qe[e.dow]];return e.isDisabled?n.push("fc-day-disabled"):(e.isToday&&(n.push("fc-day-today"),n.push(t.getClass("today"))),e.isPast&&n.push("fc-day-past"),e.isFuture&&n.push("fc-day-future"),e.isOther&&n.push("fc-day-other")),n}var io=ln({year:"numeric",month:"long",day:"numeric"}),ao=ln({week:"long"});function so(e,t,n,o){void 0===n&&(n="day"),void 0===o&&(o=!0);var i=e.dateEnv,a=e.options,s=e.calendarApi,l=i.format(t,"week"===n?ao:io);if(a.navLinks){var u=i.toDate(t),c=function(e){var r="day"===n?a.navLinkDayClick:"week"===n?a.navLinkWeekClick:null;"function"==typeof r?r.call(s,i.toDate(t),e):("string"==typeof r&&(n=r),s.zoomTo(t,n))};return r({title:Xe(a.navLinkHint,[l,u],l),"data-navlink":""},o?He(c):{onClick:c})}return{"aria-label":l}}var lo,uo=null;function co(){return null===uo&&(uo=function(){var e=document.createElement("div");we(e,{position:"absolute",top:-1e3,left:0,border:0,padding:0,overflow:"scroll",direction:"rtl"}),e.innerHTML="<div></div>",document.body.appendChild(e);var t=e.firstChild.getBoundingClientRect().left>e.getBoundingClientRect().left;return Ee(e),t}()),uo}function po(){return lo||(lo=function(){var e=document.createElement("div");e.style.overflow="scroll",e.style.position="absolute",e.style.top="-9999px",e.style.left="-9999px",document.body.appendChild(e);var t=fo(e);return document.body.removeChild(e),t}()),lo}function fo(e){return{x:e.offsetHeight-e.clientHeight,y:e.offsetWidth-e.clientWidth}}function ho(e,t){void 0===t&&(t=!1);var n=window.getComputedStyle(e),r=parseInt(n.borderLeftWidth,10)||0,o=parseInt(n.borderRightWidth,10)||0,i=parseInt(n.borderTopWidth,10)||0,a=parseInt(n.borderBottomWidth,10)||0,s=fo(e),l=s.y-r-o,u={borderLeft:r,borderRight:o,borderTop:i,borderBottom:a,scrollbarBottom:s.x-i-a,scrollbarLeft:0,scrollbarRight:0};return co()&&"rtl"===n.direction?u.scrollbarLeft=l:u.scrollbarRight=l,t&&(u.paddingLeft=parseInt(n.paddingLeft,10)||0,u.paddingRight=parseInt(n.paddingRight,10)||0,u.paddingTop=parseInt(n.paddingTop,10)||0,u.paddingBottom=parseInt(n.paddingBottom,10)||0),u}function vo(e,t,n){void 0===t&&(t=!1);var r=n?e.getBoundingClientRect():go(e),o=ho(e,t),i={left:r.left+o.borderLeft+o.scrollbarLeft,right:r.right-o.borderRight-o.scrollbarRight,top:r.top+o.borderTop,bottom:r.bottom-o.borderBottom-o.scrollbarBottom};return t&&(i.left+=o.paddingLeft,i.right-=o.paddingRight,i.top+=o.paddingTop,i.bottom-=o.paddingBottom),i}function go(e){var t=e.getBoundingClientRect();return{left:t.left+window.pageXOffset,top:t.top+window.pageYOffset,right:t.right+window.pageXOffset,bottom:t.bottom+window.pageYOffset}}function mo(e){for(var t=[];e instanceof HTMLElement;){var n=window.getComputedStyle(e);if("fixed"===n.position)break;/(auto|scroll)/.test(n.overflow+n.overflowY+n.overflowX)&&t.push(e),e=e.parentNode}return t}function yo(e,t,n){var r=!1,o=function(){r||(r=!0,t.apply(this,arguments))},i=function(){r||(r=!0,n&&n.apply(this,arguments))},a=e(o,i);a&&"function"==typeof a.then&&a.then(o,i)}var Eo=function(){function e(){this.handlers={},this.thisContext=null}return e.prototype.setThisContext=function(e){this.thisContext=e},e.prototype.setOptions=function(e){this.options=e},e.prototype.on=function(e,t){!function(e,t,n){(e[t]||(e[t]=[])).push(n)}(this.handlers,e,t)},e.prototype.off=function(e,t){!function(e,t,n){n?e[t]&&(e[t]=e[t].filter((function(e){return e!==n}))):delete e[t]}(this.handlers,e,t)},e.prototype.trigger=function(e){for(var t=[],n=1;n<arguments.length;n++)t[n-1]=arguments[n];for(var r=this.handlers[e]||[],o=this.options&&this.options[e],i=[].concat(o||[],r),a=0,s=i;a<s.length;a++){var l=s[a];l.apply(this.thisContext,t)}},e.prototype.hasHandlers=function(e){return Boolean(this.handlers[e]&&this.handlers[e].length||this.options&&this.options[e])},e}();var So=function(){function e(e,t,n,r){this.els=t;var o=this.originClientRect=e.getBoundingClientRect();n&&this.buildElHorizontals(o.left),r&&this.buildElVerticals(o.top)}return e.prototype.buildElHorizontals=function(e){for(var t=[],n=[],r=0,o=this.els;r<o.length;r++){var i=o[r].getBoundingClientRect();t.push(i.left-e),n.push(i.right-e)}this.lefts=t,this.rights=n},e.prototype.buildElVerticals=function(e){for(var t=[],n=[],r=0,o=this.els;r<o.length;r++){var i=o[r].getBoundingClientRect();t.push(i.top-e),n.push(i.bottom-e)}this.tops=t,this.bottoms=n},e.prototype.leftToIndex=function(e){var t,n=this.lefts,r=this.rights,o=n.length;for(t=0;t<o;t+=1)if(e>=n[t]&&e<r[t])return t},e.prototype.topToIndex=function(e){var t,n=this.tops,r=this.bottoms,o=n.length;for(t=0;t<o;t+=1)if(e>=n[t]&&e<r[t])return t},e.prototype.getWidth=function(e){return this.rights[e]-this.lefts[e]},e.prototype.getHeight=function(e){return this.bottoms[e]-this.tops[e]},e}(),bo=function(){function e(){}return e.prototype.getMaxScrollTop=function(){return this.getScrollHeight()-this.getClientHeight()},e.prototype.getMaxScrollLeft=function(){return this.getScrollWidth()-this.getClientWidth()},e.prototype.canScrollVertically=function(){return this.getMaxScrollTop()>0},e.prototype.canScrollHorizontally=function(){return this.getMaxScrollLeft()>0},e.prototype.canScrollUp=function(){return this.getScrollTop()>0},e.prototype.canScrollDown=function(){return this.getScrollTop()<this.getMaxScrollTop()},e.prototype.canScrollLeft=function(){return this.getScrollLeft()>0},e.prototype.canScrollRight=function(){return this.getScrollLeft()<this.getMaxScrollLeft()},e}(),Do=function(e){function t(t){var n=e.call(this)||this;return n.el=t,n}return n(t,e),t.prototype.getScrollTop=function(){return this.el.scrollTop},t.prototype.getScrollLeft=function(){return this.el.scrollLeft},t.prototype.setScrollTop=function(e){this.el.scrollTop=e},t.prototype.setScrollLeft=function(e){this.el.scrollLeft=e},t.prototype.getScrollWidth=function(){return this.el.scrollWidth},t.prototype.getScrollHeight=function(){return this.el.scrollHeight},t.prototype.getClientHeight=function(){return this.el.clientHeight},t.prototype.getClientWidth=function(){return this.el.clientWidth},t}(bo),Co=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.getScrollTop=function(){return window.pageYOffset},t.prototype.getScrollLeft=function(){return window.pageXOffset},t.prototype.setScrollTop=function(e){window.scroll(window.pageXOffset,e)},t.prototype.setScrollLeft=function(e){window.scroll(e,window.pageYOffset)},t.prototype.getScrollWidth=function(){return document.documentElement.scrollWidth},t.prototype.getScrollHeight=function(){return document.documentElement.scrollHeight},t.prototype.getClientHeight=function(){return document.documentElement.clientHeight},t.prototype.getClientWidth=function(){return document.documentElement.clientWidth},t}(bo),wo=function(){function e(e){this.iconOverrideOption&&this.setIconOverride(e[this.iconOverrideOption])}return e.prototype.setIconOverride=function(e){var t,n;if("object"==typeof e&&e){for(n in t=r({},this.iconClasses),e)t[n]=this.applyIconOverridePrefix(e[n]);this.iconClasses=t}else!1===e&&(this.iconClasses={})},e.prototype.applyIconOverridePrefix=function(e){var t=this.iconOverridePrefix;return t&&0!==e.indexOf(t)&&(e=t+e),e},e.prototype.getClass=function(e){return this.classes[e]||""},e.prototype.getIconClass=function(e,t){var n;return(n=t&&this.rtlIconClasses&&this.rtlIconClasses[e]||this.iconClasses[e])?this.baseIconClass+" "+n:""},e.prototype.getCustomButtonIconClass=function(e){var t;return this.iconOverrideCustomButtonOption&&(t=e[this.iconOverrideCustomButtonOption])?this.baseIconClass+" "+this.applyIconOverridePrefix(t):""},e}();if(wo.prototype.classes={},wo.prototype.iconClasses={},wo.prototype.baseIconClass="",wo.prototype.iconOverridePrefix="","undefined"==typeof FullCalendarVDom)throw new Error("Please import the top-level fullcalendar lib before attempting to import a plugin.");var Ro=FullCalendarVDom.Component,_o=FullCalendarVDom.createElement,To=FullCalendarVDom.render,ko=FullCalendarVDom.createRef,xo=FullCalendarVDom.Fragment,Mo=FullCalendarVDom.createContext,Io=FullCalendarVDom.createPortal,Po=FullCalendarVDom.flushToDom,No=FullCalendarVDom.unmountComponentAtNode,Ho=function(){function e(e,t,n,o){var i=this;this.execFunc=e,this.emitter=t,this.scrollTime=n,this.scrollTimeReset=o,this.handleScrollRequest=function(e){i.queuedRequest=r({},i.queuedRequest||{},e),i.drain()},t.on("_scrollRequest",this.handleScrollRequest),this.fireInitialScroll()}return e.prototype.detach=function(){this.emitter.off("_scrollRequest",this.handleScrollRequest)},e.prototype.update=function(e){e&&this.scrollTimeReset?this.fireInitialScroll():this.drain()},e.prototype.fireInitialScroll=function(){this.handleScrollRequest({time:this.scrollTime})},e.prototype.drain=function(){this.queuedRequest&&this.execFunc(this.queuedRequest)&&(this.queuedRequest=null)},e}(),Oo=Mo({});function Ao(e,t,n,r,o,i,a,s,l,u,c,d,p){return{dateEnv:o,options:n,pluginHooks:a,emitter:u,dispatch:s,getCurrentData:l,calendarApi:c,viewSpec:e,viewApi:t,dateProfileGenerator:r,theme:i,isRtl:"rtl"===n.direction,addResizeHandler:function(e){u.on("_resize",e)},removeResizeHandler:function(e){u.off("_resize",e)},createScrollResponder:function(e){return new Ho(e,u,Nt(n.scrollTime),n.scrollTimeReset)},registerInteractiveComponent:d,unregisterInteractiveComponent:p}}var Lo=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.shouldComponentUpdate=function(e,t){return this.debug&&console.log(Rt(e,this.props),Rt(t,this.state)),!_t(this.props,e,this.propEquality)||!_t(this.state,t,this.stateEquality)},t.addPropsEquality=Wo,t.addStateEquality=Vo,t.contextType=Oo,t}(Ro);Lo.prototype.propEquality={},Lo.prototype.stateEquality={};var Uo=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.contextType=Oo,t}(Lo);function Wo(e){var t=Object.create(this.prototype.propEquality);r(t,e),this.prototype.propEquality=t}function Vo(e){var t=Object.create(this.prototype.stateEquality);r(t,e),this.prototype.stateEquality=t}function Fo(e,t){"function"==typeof e?e(t):e&&(e.current=t)}var Bo=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.uid=Le(),t}return n(t,e),t.prototype.prepareHits=function(){},t.prototype.queryHit=function(e,t,n,r){return null},t.prototype.isValidSegDownEl=function(e){return!this.props.eventDrag&&!this.props.eventResize&&!Se(e,".fc-event-mirror")},t.prototype.isValidDateDownEl=function(e){return!(Se(e,".fc-event:not(.fc-bg-event)")||Se(e,".fc-more-link")||Se(e,"a[data-navlink]")||Se(e,".fc-popover"))},t}(Uo);function zo(e){return{id:Le(),deps:e.deps||[],reducers:e.reducers||[],isLoadingFuncs:e.isLoadingFuncs||[],contextInit:[].concat(e.contextInit||[]),eventRefiners:e.eventRefiners||{},eventDefMemberAdders:e.eventDefMemberAdders||[],eventSourceRefiners:e.eventSourceRefiners||{},isDraggableTransformers:e.isDraggableTransformers||[],eventDragMutationMassagers:e.eventDragMutationMassagers||[],eventDefMutationAppliers:e.eventDefMutationAppliers||[],dateSelectionTransformers:e.dateSelectionTransformers||[],datePointTransforms:e.datePointTransforms||[],dateSpanTransforms:e.dateSpanTransforms||[],views:e.views||{},viewPropsTransformers:e.viewPropsTransformers||[],isPropsValid:e.isPropsValid||null,externalDefTransforms:e.externalDefTransforms||[],viewContainerAppends:e.viewContainerAppends||[],eventDropTransformers:e.eventDropTransformers||[],componentInteractions:e.componentInteractions||[],calendarInteractions:e.calendarInteractions||[],themeClasses:e.themeClasses||{},eventSourceDefs:e.eventSourceDefs||[],cmdFormatter:e.cmdFormatter,recurringTypes:e.recurringTypes||[],namedTimeZonedImpl:e.namedTimeZonedImpl,initialView:e.initialView||"",elementDraggingImpl:e.elementDraggingImpl,optionChangeHandlers:e.optionChangeHandlers||{},scrollGridImpl:e.scrollGridImpl||null,contentTypeHandlers:e.contentTypeHandlers||{},listenerRefiners:e.listenerRefiners||{},optionRefiners:e.optionRefiners||{},propSetHandlers:e.propSetHandlers||{}}}function jo(){var e,t=[],n=[];return function(o,i){return e&&Gt(o,t)&&Gt(i,n)||(e=function(e,t){var n={},o={reducers:[],isLoadingFuncs:[],contextInit:[],eventRefiners:{},eventDefMemberAdders:[],eventSourceRefiners:{},isDraggableTransformers:[],eventDragMutationMassagers:[],eventDefMutationAppliers:[],dateSelectionTransformers:[],datePointTransforms:[],dateSpanTransforms:[],views:{},viewPropsTransformers:[],isPropsValid:null,externalDefTransforms:[],viewContainerAppends:[],eventDropTransformers:[],componentInteractions:[],calendarInteractions:[],themeClasses:{},eventSourceDefs:[],cmdFormatter:null,recurringTypes:[],namedTimeZonedImpl:null,initialView:"",elementDraggingImpl:null,optionChangeHandlers:{},scrollGridImpl:null,contentTypeHandlers:{},listenerRefiners:{},optionRefiners:{},propSetHandlers:{}};function i(e){for(var t=0,a=e;t<a.length;t++){var s=a[t];n[s.id]||(n[s.id]=!0,i(s.deps),u=s,o={reducers:(l=o).reducers.concat(u.reducers),isLoadingFuncs:l.isLoadingFuncs.concat(u.isLoadingFuncs),contextInit:l.contextInit.concat(u.contextInit),eventRefiners:r(r({},l.eventRefiners),u.eventRefiners),eventDefMemberAdders:l.eventDefMemberAdders.concat(u.eventDefMemberAdders),eventSourceRefiners:r(r({},l.eventSourceRefiners),u.eventSourceRefiners),isDraggableTransformers:l.isDraggableTransformers.concat(u.isDraggableTransformers),eventDragMutationMassagers:l.eventDragMutationMassagers.concat(u.eventDragMutationMassagers),eventDefMutationAppliers:l.eventDefMutationAppliers.concat(u.eventDefMutationAppliers),dateSelectionTransformers:l.dateSelectionTransformers.concat(u.dateSelectionTransformers),datePointTransforms:l.datePointTransforms.concat(u.datePointTransforms),dateSpanTransforms:l.dateSpanTransforms.concat(u.dateSpanTransforms),views:r(r({},l.views),u.views),viewPropsTransformers:l.viewPropsTransformers.concat(u.viewPropsTransformers),isPropsValid:u.isPropsValid||l.isPropsValid,externalDefTransforms:l.externalDefTransforms.concat(u.externalDefTransforms),viewContainerAppends:l.viewContainerAppends.concat(u.viewContainerAppends),eventDropTransformers:l.eventDropTransformers.concat(u.eventDropTransformers),calendarInteractions:l.calendarInteractions.concat(u.calendarInteractions),componentInteractions:l.componentInteractions.concat(u.componentInteractions),themeClasses:r(r({},l.themeClasses),u.themeClasses),eventSourceDefs:l.eventSourceDefs.concat(u.eventSourceDefs),cmdFormatter:u.cmdFormatter||l.cmdFormatter,recurringTypes:l.recurringTypes.concat(u.recurringTypes),namedTimeZonedImpl:u.namedTimeZonedImpl||l.namedTimeZonedImpl,initialView:l.initialView||u.initialView,elementDraggingImpl:l.elementDraggingImpl||u.elementDraggingImpl,optionChangeHandlers:r(r({},l.optionChangeHandlers),u.optionChangeHandlers),scrollGridImpl:u.scrollGridImpl||l.scrollGridImpl,contentTypeHandlers:r(r({},l.contentTypeHandlers),u.contentTypeHandlers),listenerRefiners:r(r({},l.listenerRefiners),u.listenerRefiners),optionRefiners:r(r({},l.optionRefiners),u.optionRefiners),propSetHandlers:r(r({},l.propSetHandlers),u.propSetHandlers)})}var l,u}return e&&i(e),i(t),o}(o,i)),t=o,n=i,e}}var Go=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t}(wo);function qo(e,t,n,o){if(t[e])return t[e];var i=function(e,t,n,o){var i=n[e],a=o[e],s=function(e){return i&&null!==i[e]?i[e]:a&&null!==a[e]?a[e]:null},l=s("component"),u=s("superType"),c=null;if(u){if(u===e)throw new Error("Can't have a custom view type that references itself");c=qo(u,t,n,o)}!l&&c&&(l=c.component);if(!l)return null;return{type:e,component:l,defaults:r(r({},c?c.defaults:{}),i?i.rawOptions:{}),overrides:r(r({},c?c.overrides:{}),a?a.rawOptions:{})}}(e,t,n,o);return i&&(t[e]=i),i}Go.prototype.classes={root:"fc-theme-standard",tableCellShaded:"fc-cell-shaded",buttonGroup:"fc-button-group",button:"fc-button fc-button-primary",buttonActive:"fc-button-active"},Go.prototype.baseIconClass="fc-icon",Go.prototype.iconClasses={close:"fc-icon-x",prev:"fc-icon-chevron-left",next:"fc-icon-chevron-right",prevYear:"fc-icon-chevrons-left",nextYear:"fc-icon-chevrons-right"},Go.prototype.rtlIconClasses={prev:"fc-icon-chevron-right",next:"fc-icon-chevron-left",prevYear:"fc-icon-chevrons-right",nextYear:"fc-icon-chevrons-left"},Go.prototype.iconOverrideOption="buttonIcons",Go.prototype.iconOverrideCustomButtonOption="icon",Go.prototype.iconOverridePrefix="fc-icon-";var Yo=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.rootElRef=ko(),t.handleRootEl=function(e){Fo(t.rootElRef,e),t.props.elRef&&Fo(t.props.elRef,e)},t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=t.hookProps;return _o($o,{hookProps:n,didMount:t.didMount,willUnmount:t.willUnmount,elRef:this.handleRootEl},(function(r){return _o(Xo,{hookProps:n,content:t.content,defaultContent:t.defaultContent,backupElRef:e.rootElRef},(function(e,o){return t.children(r,Qo(t.classNames,n),e,o)}))}))},t}(Uo),Zo=Mo(0);function Xo(e){return _o(Zo.Consumer,null,(function(t){return _o(Ko,r({renderId:t},e))}))}var Ko=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.innerElRef=ko(),t}return n(t,e),t.prototype.render=function(){return this.props.children(this.innerElRef,this.renderInnerContent())},t.prototype.componentDidMount=function(){this.updateCustomContent()},t.prototype.componentDidUpdate=function(){this.updateCustomContent()},t.prototype.componentWillUnmount=function(){this.customContentInfo&&this.customContentInfo.destroy&&this.customContentInfo.destroy()},t.prototype.renderInnerContent=function(){var e=this.customContentInfo,t=this.getInnerContent(),n=this.getContentMeta(t);return e&&e.contentKey===n.contentKey?e&&(e.contentVal=t[n.contentKey]):(e&&(e.destroy&&e.destroy(),e=this.customContentInfo=null),n.contentKey&&(e=this.customContentInfo=r({contentKey:n.contentKey,contentVal:t[n.contentKey]},n.buildLifecycleFuncs()))),e?[]:t},t.prototype.getInnerContent=function(){var e=this.props,t=ei(e.content,e.hookProps);return void 0===t&&(t=ei(e.defaultContent,e.hookProps)),null==t?null:t},t.prototype.getContentMeta=function(e){var t=this.context.pluginHooks.contentTypeHandlers,n="",r=null;if(e)for(var o in t)if(void 0!==e[o]){n=o,r=t[o];break}return{contentKey:n,buildLifecycleFuncs:r}},t.prototype.updateCustomContent=function(){this.customContentInfo&&this.customContentInfo.render(this.innerElRef.current||this.props.backupElRef.current,this.customContentInfo.contentVal)},t}(Uo),$o=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.handleRootEl=function(e){t.rootEl=e,t.props.elRef&&Fo(t.props.elRef,e)},t}return n(t,e),t.prototype.render=function(){return this.props.children(this.handleRootEl)},t.prototype.componentDidMount=function(){var e=this.props.didMount;e&&e(r(r({},this.props.hookProps),{el:this.rootEl}))},t.prototype.componentWillUnmount=function(){var e=this.props.willUnmount;e&&e(r(r({},this.props.hookProps),{el:this.rootEl}))},t}(Uo);function Jo(){var e,t,n=[];return function(r,o){return t&&wt(t,o)&&r===e||(e=r,t=o,n=Qo(r,o)),n}}function Qo(e,t){return"function"==typeof e&&(e=e(t)),Rn(e)}function ei(e,t){return"function"==typeof e?e(t,_o):e}var ti=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.normalizeClassNames=Jo(),t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.options,r={view:t.viewApi},o=this.normalizeClassNames(n.viewClassNames,r);return _o($o,{hookProps:r,didMount:n.viewDidMount,willUnmount:n.viewWillUnmount,elRef:e.elRef},(function(t){return e.children(t,["fc-"+e.viewSpec.type+"-view","fc-view"].concat(o))}))},t}(Uo);function ni(e){return bt(e,ri)}function ri(e){var t,n="function"==typeof e?{component:e}:e,o=n.component;return n.content&&(t=n,o=function(e){return _o(Oo.Consumer,null,(function(n){return _o(ti,{viewSpec:n.viewSpec},(function(o,i){var a=r(r({},e),{nextDayThreshold:n.options.nextDayThreshold});return _o(Yo,{hookProps:a,classNames:t.classNames,content:t.content,didMount:t.didMount,willUnmount:t.willUnmount,elRef:o},(function(e,t,n,r){return _o("div",{className:i.concat(t).join(" "),ref:e},r)}))}))}))}),{superType:n.type,component:o,rawOptions:n}}function oi(e,t,n,o){var i=ni(e),a=ni(t.views);return bt(function(e,t){var n,r={};for(n in e)qo(n,r,e,t);for(n in t)qo(n,r,e,t);return r}(i,a),(function(e){return function(e,t,n,o,i){var a=e.overrides.duration||e.defaults.duration||o.duration||n.duration,s=null,l="",u="",c={};if(a&&(s=function(e){var t=JSON.stringify(e),n=ii[t];void 0===n&&(n=Nt(e),ii[t]=n);return n}(a))){var d=Vt(s);l=d.unit,1===d.value&&(u=l,c=t[l]?t[l].rawOptions:{})}var p=function(t){var n=t.buttonText||{},r=e.defaults.buttonTextKey;return null!=r&&null!=n[r]?n[r]:null!=n[e.type]?n[e.type]:null!=n[u]?n[u]:null},f=function(t){var n=t.buttonHints||{},r=e.defaults.buttonTextKey;return null!=r&&null!=n[r]?n[r]:null!=n[e.type]?n[e.type]:null!=n[u]?n[u]:null};return{type:e.type,component:e.component,duration:s,durationUnit:l,singleUnit:u,optionDefaults:e.defaults,optionOverrides:r(r({},c),e.overrides),buttonTextOverride:p(o)||p(n)||e.overrides.buttonText,buttonTextDefault:p(i)||e.defaults.buttonText||p(cn)||e.type,buttonTitleOverride:f(o)||f(n)||e.overrides.buttonHint,buttonTitleDefault:f(i)||e.defaults.buttonHint||f(cn)}}(e,a,t,n,o)}))}var ii={};var ai=function(){function e(e){this.props=e,this.nowDate=Tr(e.nowInput,e.dateEnv),this.initHiddenDays()}return e.prototype.buildPrev=function(e,t,n){var r=this.props.dateEnv,o=r.subtract(r.startOf(t,e.currentRangeUnit),e.dateIncrement);return this.build(o,-1,n)},e.prototype.buildNext=function(e,t,n){var r=this.props.dateEnv,o=r.add(r.startOf(t,e.currentRangeUnit),e.dateIncrement);return this.build(o,1,n)},e.prototype.build=function(e,t,n){void 0===n&&(n=!0);var r,o,i,a,s,l,u,c,d=this.props;return r=this.buildValidRange(),r=this.trimHiddenDays(r),n&&(u=e,e=null!=(c=r).start&&u<c.start?c.start:null!=c.end&&u>=c.end?new Date(c.end.valueOf()-1):u),o=this.buildCurrentRangeInfo(e,t),i=/^(year|month|week|day)$/.test(o.unit),a=this.buildRenderRange(this.trimHiddenDays(o.range),o.unit,i),s=a=this.trimHiddenDays(a),d.showNonCurrentDates||(s=jn(s,o.range)),s=jn(s=this.adjustActiveRange(s),r),l=qn(o.range,r),{validRange:r,currentRange:o.range,currentRangeUnit:o.unit,isRangeAllDay:i,activeRange:s,renderRange:a,slotMinTime:d.slotMinTime,slotMaxTime:d.slotMaxTime,isValid:l,dateIncrement:this.buildDateIncrement(o.duration)}},e.prototype.buildValidRange=function(){var e=this.props.validRangeInput,t="function"==typeof e?e.call(this.props.calendarApi,this.nowDate):e;return this.refineRange(t)||{start:null,end:null}},e.prototype.buildCurrentRangeInfo=function(e,t){var n,r=this.props,o=null,i=null,a=null;return r.duration?(o=r.duration,i=r.durationUnit,a=this.buildRangeFromDuration(e,t,o,i)):(n=this.props.dayCount)?(i="day",a=this.buildRangeFromDayCount(e,t,n)):(a=this.buildCustomVisibleRange(e))?i=r.dateEnv.greatestWholeUnit(a.start,a.end).unit:(i=Vt(o=this.getFallbackDuration()).unit,a=this.buildRangeFromDuration(e,t,o,i)),{duration:o,unit:i,range:a}},e.prototype.getFallbackDuration=function(){return Nt({day:1})},e.prototype.adjustActiveRange=function(e){var t=this.props,n=t.dateEnv,r=t.usesMinMaxTime,o=t.slotMinTime,i=t.slotMaxTime,a=e.start,s=e.end;return r&&(Lt(o)<0&&(a=lt(a),a=n.add(a,o)),Lt(i)>1&&(s=tt(s=lt(s),-1),s=n.add(s,i))),{start:a,end:s}},e.prototype.buildRangeFromDuration=function(e,t,n,r){var o,i,a,s=this.props,l=s.dateEnv,u=s.dateAlignment;if(!u){var c=this.props.dateIncrement;u=c&&Ut(c)<Ut(n)?Vt(c).unit:r}function d(){o=l.startOf(e,u),i=l.add(o,n),a={start:o,end:i}}return Lt(n)<=1&&this.isHiddenDay(o)&&(o=lt(o=this.skipHiddenDays(o,t))),d(),this.trimHiddenDays(a)||(e=this.skipHiddenDays(e,t),d()),a},e.prototype.buildRangeFromDayCount=function(e,t,n){var r,o=this.props,i=o.dateEnv,a=o.dateAlignment,s=0,l=e;a&&(l=i.startOf(l,a)),l=lt(l),r=l=this.skipHiddenDays(l,t);do{r=tt(r,1),this.isHiddenDay(r)||(s+=1)}while(s<n);return{start:l,end:r}},e.prototype.buildCustomVisibleRange=function(e){var t=this.props,n=t.visibleRangeInput,r="function"==typeof n?n.call(t.calendarApi,t.dateEnv.toDate(e)):n,o=this.refineRange(r);return!o||null!=o.start&&null!=o.end?o:null},e.prototype.buildRenderRange=function(e,t,n){return e},e.prototype.buildDateIncrement=function(e){var t,n=this.props.dateIncrement;return n||((t=this.props.dateAlignment)?Nt(1,t):e||Nt({days:1}))},e.prototype.refineRange=function(e){if(e){var t=(n=e,r=this.props.dateEnv,o=null,i=null,n.start&&(o=r.createMarker(n.start)),n.end&&(i=r.createMarker(n.end)),o||i?o&&i&&i<o?null:{start:o,end:i}:null);return t&&(t=Wn(t)),t}var n,r,o,i;return null},e.prototype.initHiddenDays=function(){var e,t=this.props.hiddenDays||[],n=[],r=0;for(!1===this.props.weekends&&t.push(0,6),e=0;e<7;e+=1)(n[e]=-1!==t.indexOf(e))||(r+=1);if(!r)throw new Error("invalid hiddenDays");this.isHiddenDayHash=n},e.prototype.trimHiddenDays=function(e){var t=e.start,n=e.end;return t&&(t=this.skipHiddenDays(t)),n&&(n=this.skipHiddenDays(n,-1,!0)),null==t||null==n||t<n?{start:t,end:n}:null},e.prototype.isHiddenDay=function(e){return e instanceof Date&&(e=e.getUTCDay()),this.isHiddenDayHash[e]},e.prototype.skipHiddenDays=function(e,t,n){for(void 0===t&&(t=1),void 0===n&&(n=!1);this.isHiddenDayHash[(e.getUTCDay()+(n?t:0)+7)%7];)e=tt(e,t);return e},e}();function si(e,t,n){var r=t?t.activeRange:null;return ci({},function(e,t){var n=_r(t),r=[].concat(e.eventSources||[]),o=[];e.initialEvents&&r.unshift(e.initialEvents);e.events&&r.unshift(e.events);for(var i=0,a=r;i<a.length;i++){var s=Rr(a[i],t,n);s&&o.push(s)}return o}(e,n),r,n)}function li(e,t,n,o){var i,a,s=n?n.activeRange:null;switch(t.type){case"ADD_EVENT_SOURCES":return ci(e,t.sources,s,o);case"REMOVE_EVENT_SOURCE":return i=e,a=t.sourceId,St(i,(function(e){return e.sourceId!==a}));case"PREV":case"NEXT":case"CHANGE_DATE":case"CHANGE_VIEW_TYPE":return n?di(e,s,o):e;case"FETCH_EVENT_SOURCES":return pi(e,t.sourceIds?Dt(t.sourceIds):hi(e,o),s,t.isRefetch||!1,o);case"RECEIVE_EVENTS":case"RECEIVE_EVENT_ERROR":return function(e,t,n,o){var i,a=e[t];if(a&&n===a.latestFetchId)return r(r({},e),((i={})[t]=r(r({},a),{isFetching:!1,fetchRange:o}),i));return e}(e,t.sourceId,t.fetchId,t.fetchRange);case"REMOVE_ALL_EVENT_SOURCES":return{};default:return e}}function ui(e){for(var t in e)if(e[t].isFetching)return!0;return!1}function ci(e,t,n,o){for(var i={},a=0,s=t;a<s.length;a++){var l=s[a];i[l.sourceId]=l}return n&&(i=di(i,n,o)),r(r({},e),i)}function di(e,t,n){return pi(e,St(e,(function(e){return function(e,t,n){if(!vi(e,n))return!e.latestFetchId;return!n.options.lazyFetching||!e.fetchRange||e.isFetching||t.start<e.fetchRange.start||t.end>e.fetchRange.end}(e,t,n)})),t,!1,n)}function pi(e,t,n,r,o){var i={};for(var a in e){var s=e[a];t[a]?i[a]=fi(s,n,r,o):i[a]=s}return i}function fi(e,t,n,o){var i=o.options,a=o.calendarApi,s=o.pluginHooks.eventSourceDefs[e.sourceDefId],l=Le();return s.fetch({eventSource:e,range:t,isRefetch:n,context:o},(function(n){var r=n.rawEvents;i.eventSourceSuccess&&(r=i.eventSourceSuccess.call(a,r,n.xhr)||r),e.success&&(r=e.success.call(a,r,n.xhr)||r),o.dispatch({type:"RECEIVE_EVENTS",sourceId:e.sourceId,fetchId:l,fetchRange:t,rawEvents:r})}),(function(n){console.warn(n.message,n),i.eventSourceFailure&&i.eventSourceFailure.call(a,n),e.failure&&e.failure(n),o.dispatch({type:"RECEIVE_EVENT_ERROR",sourceId:e.sourceId,fetchId:l,fetchRange:t,error:n})})),r(r({},e),{isFetching:!0,latestFetchId:l})}function hi(e,t){return St(e,(function(e){return vi(e,t)}))}function vi(e,t){return!t.pluginHooks.eventSourceDefs[e.sourceDefId].ignoreRange}function gi(e,t,n,r,o){switch(t.type){case"RECEIVE_EVENTS":return function(e,t,n,r,o,i){if(t&&n===t.latestFetchId){var a=En(function(e,t,n){var r=n.options.eventDataTransform,o=t?t.eventDataTransform:null;o&&(e=mi(e,o));r&&(e=mi(e,r));return e}(o,t,i),t,i);return r&&(a=xt(a,r,i)),Cn(yi(e,t.sourceId),a)}return e}(e,n[t.sourceId],t.fetchId,t.fetchRange,t.rawEvents,o);case"ADD_EVENTS":return function(e,t,n,r){n&&(t=xt(t,n,r));return Cn(e,t)}(e,t.eventStore,r?r.activeRange:null,o);case"RESET_EVENTS":return t.eventStore;case"MERGE_EVENTS":return Cn(e,t.eventStore);case"PREV":case"NEXT":case"CHANGE_DATE":case"CHANGE_VIEW_TYPE":return r?xt(e,r.activeRange,o):e;case"REMOVE_EVENTS":return function(e,t){var n=e.defs,r=e.instances,o={},i={};for(var a in n)t.defs[a]||(o[a]=n[a]);for(var s in r)!t.instances[s]&&o[r[s].defId]&&(i[s]=r[s]);return{defs:o,instances:i}}(e,t.eventStore);case"REMOVE_EVENT_SOURCE":return yi(e,t.sourceId);case"REMOVE_ALL_EVENT_SOURCES":return wn(e,(function(e){return!e.sourceId}));case"REMOVE_ALL_EVENTS":return{defs:{},instances:{}};default:return e}}function mi(e,t){var n;if(t){n=[];for(var r=0,o=e;r<o.length;r++){var i=o[r],a=t(i);a?n.push(a):null==a&&n.push(i)}}else n=e;return n}function yi(e,t){return wn(e,(function(e){return e.sourceId!==t}))}function Ei(e,t){switch(t.type){case"UNSELECT_DATES":return null;case"SELECT_DATES":return t.selection;default:return e}}function Si(e,t){switch(t.type){case"UNSELECT_EVENT":return"";case"SELECT_EVENT":return t.eventInstanceId;default:return e}}function bi(e,t){var n;switch(t.type){case"UNSET_EVENT_DRAG":return null;case"SET_EVENT_DRAG":return{affectedEvents:(n=t.state).affectedEvents,mutatedEvents:n.mutatedEvents,isEvent:n.isEvent};default:return e}}function Di(e,t){var n;switch(t.type){case"UNSET_EVENT_RESIZE":return null;case"SET_EVENT_RESIZE":return{affectedEvents:(n=t.state).affectedEvents,mutatedEvents:n.mutatedEvents,isEvent:n.isEvent};default:return e}}function Ci(e,t,n,r,o){return{header:e.headerToolbar?wi(e.headerToolbar,e,t,n,r,o):null,footer:e.footerToolbar?wi(e.footerToolbar,e,t,n,r,o):null}}function wi(e,t,n,r,o,i){var a={},s=[],l=!1;for(var u in e){var c=Ri(e[u],t,n,r,o,i);a[u]=c.widgets,s.push.apply(s,c.viewsWithButtons),l=l||c.hasTitle}return{sectionWidgets:a,viewsWithButtons:s,hasTitle:l}}function Ri(e,t,n,r,o,i){var a="rtl"===t.direction,s=t.customButtons||{},l=n.buttonText||{},u=t.buttonText||{},c=n.buttonHints||{},d=t.buttonHints||{},p=e?e.split(" "):[],f=[],h=!1;return{widgets:p.map((function(e){return e.split(",").map((function(e){if("title"===e)return h=!0,{buttonName:e};var n,p,v,g,m,y;if(n=s[e])v=function(e){n.click&&n.click.call(e.target,e,e.target)},(g=r.getCustomButtonIconClass(n))||(g=r.getIconClass(e,a))||(m=n.text),y=n.hint||n.text;else if(p=o[e]){f.push(e),v=function(){i.changeView(e)},(m=p.buttonTextOverride)||(g=r.getIconClass(e,a))||(m=p.buttonTextDefault);var E=p.buttonTextOverride||p.buttonTextDefault;y=Xe(p.buttonTitleOverride||p.buttonTitleDefault||t.viewHint,[E,e],E)}else if(i[e])if(v=function(){i[e]()},(m=l[e])||(g=r.getIconClass(e,a))||(m=u[e]),"prevYear"===e||"nextYear"===e){var S="prevYear"===e?"prev":"next";y=Xe(c[S]||d[S],[u.year||"year","year"],u[e])}else y=function(t){return Xe(c[e]||d[e],[u[t]||t,t],u[e])};return{buttonName:e,buttonClick:v,buttonIcon:g,buttonText:m,buttonHint:y}}))})),viewsWithButtons:f,hasTitle:h}}function _i(e,t,n,r,o){var i=null;"GET"===(e=e.toUpperCase())?t=function(e,t){return e+(-1===e.indexOf("?")?"?":"&")+Ti(t)}(t,n):i=Ti(n);var a=new XMLHttpRequest;a.open(e,t,!0),"GET"!==e&&a.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),a.onload=function(){if(a.status>=200&&a.status<400){var e=!1,t=void 0;try{t=JSON.parse(a.responseText),e=!0}catch(e){}e?r(t,a):o("Failure parsing JSON",a)}else o("Request failed",a)},a.onerror=function(){o("Request failed",a)},a.send(i)}function Ti(e){var t=[];for(var n in e)t.push(encodeURIComponent(n)+"="+encodeURIComponent(e[n]));return t.join("&")}function ki(e,t){for(var n=Ct(t.getCurrentData().eventSources),r=[],o=0,i=e;o<i.length;o++){for(var a=i[o],s=!1,l=0;l<n.length;l+=1)if(n[l]._raw===a){n.splice(l,1),s=!0;break}s||r.push(a)}for(var u=0,c=n;u<c.length;u++){var d=c[u];t.dispatch({type:"REMOVE_EVENT_SOURCE",sourceId:d.sourceId})}for(var p=0,f=r;p<f.length;p++){var h=f[p];t.calendarApi.addEventSource(h)}}var xi=[zo({eventSourceDefs:[{ignoreRange:!0,parseMeta:function(e){return Array.isArray(e.events)?e.events:null},fetch:function(e,t){t({rawEvents:e.eventSource.meta})}}]}),zo({eventSourceDefs:[{parseMeta:function(e){return"function"==typeof e.events?e.events:null},fetch:function(e,t,n){var r=e.context.dateEnv;yo(e.eventSource.meta.bind(null,hr(e.range,r)),(function(e){t({rawEvents:e})}),n)}}]}),zo({eventSourceRefiners:{method:String,extraParams:yn,startParam:String,endParam:String,timeZoneParam:String},eventSourceDefs:[{parseMeta:function(e){return!e.url||"json"!==e.format&&e.format?null:{url:e.url,format:"json",method:(e.method||"GET").toUpperCase(),extraParams:e.extraParams,startParam:e.startParam,endParam:e.endParam,timeZoneParam:e.timeZoneParam}},fetch:function(e,t,n){var o=e.eventSource.meta,i=function(e,t,n){var o,i,a,s,l=n.dateEnv,u=n.options,c={};null==(o=e.startParam)&&(o=u.startParam);null==(i=e.endParam)&&(i=u.endParam);null==(a=e.timeZoneParam)&&(a=u.timeZoneParam);s="function"==typeof e.extraParams?e.extraParams():e.extraParams||{};r(c,s),c[o]=l.formatIso(t.start),c[i]=l.formatIso(t.end),"local"!==l.timeZone&&(c[a]=l.timeZone);return c}(o,e.range,e.context);_i(o.method,o.url,i,(function(e,n){t({rawEvents:e,xhr:n})}),(function(e,t){n({message:e,xhr:t})}))}}]}),zo({recurringTypes:[{parse:function(e,t){if(e.daysOfWeek||e.startTime||e.endTime||e.startRecur||e.endRecur){var n={daysOfWeek:e.daysOfWeek||null,startTime:e.startTime||null,endTime:e.endTime||null,startRecur:e.startRecur?t.createMarker(e.startRecur):null,endRecur:e.endRecur?t.createMarker(e.endRecur):null},r=void 0;return e.duration&&(r=e.duration),!r&&e.startTime&&e.endTime&&(o=e.endTime,i=e.startTime,r={years:o.years-i.years,months:o.months-i.months,days:o.days-i.days,milliseconds:o.milliseconds-i.milliseconds}),{allDayGuess:Boolean(!e.startTime&&!e.endTime),duration:r,typeData:n}}var o,i;return null},expand:function(e,t,n){var r=jn(t,{start:e.startRecur,end:e.endRecur});return r?function(e,t,n,r){var o=e?Dt(e):null,i=lt(n.start),a=n.end,s=[];for(;i<a;){var l=void 0;o&&!o[i.getUTCDay()]||(l=t?r.add(i,t):i,s.push(l)),i=tt(i,1)}return s}(e.daysOfWeek,e.startTime,r,n):[]}}],eventRefiners:{daysOfWeek:yn,startTime:Nt,endTime:Nt,duration:Nt,startRecur:yn,endRecur:yn}}),zo({optionChangeHandlers:{events:function(e,t){ki([e],t)},eventSources:ki}}),zo({isLoadingFuncs:[function(e){return ui(e.eventSources)}],contentTypeHandlers:{html:function(){var e=null,t="";return{render:function(n,r){n===e&&r===t||(n.innerHTML=r),e=n,t=r},destroy:function(){e.innerHTML="",e=null,t=""}}},domNodes:function(){var e=null,t=[];function n(){t.forEach(Ee),t=[],e=null}return{render:function(r,o){var i=Array.prototype.slice.call(o);if(r!==e||!Gt(t,i)){for(var a=0,s=i;a<s.length;a++){var l=s[a];r.appendChild(l)}n()}e=r,t=i},destroy:n}}},propSetHandlers:{dateProfile:function(e,t){t.emitter.trigger("datesSet",r(r({},hr(e.activeRange,t.dateEnv)),{view:t.viewApi}))},eventStore:function(e,t){var n=t.emitter;n.hasHandlers("eventsSet")&&n.trigger("eventsSet",Ir(e,t))}}})];var Mi=function(){function e(e){this.drainedOption=e,this.isRunning=!1,this.isDirty=!1,this.pauseDepths={},this.timeoutId=0}return e.prototype.request=function(e){this.isDirty=!0,this.isPaused()||(this.clearTimeout(),null==e?this.tryDrain():this.timeoutId=setTimeout(this.tryDrain.bind(this),e))},e.prototype.pause=function(e){void 0===e&&(e="");var t=this.pauseDepths;t[e]=(t[e]||0)+1,this.clearTimeout()},e.prototype.resume=function(e,t){void 0===e&&(e="");var n=this.pauseDepths;if(e in n){if(t)delete n[e];else n[e]-=1,n[e]<=0&&delete n[e];this.tryDrain()}},e.prototype.isPaused=function(){return Object.keys(this.pauseDepths).length},e.prototype.tryDrain=function(){if(!this.isRunning&&!this.isPaused()){for(this.isRunning=!0;this.isDirty;)this.isDirty=!1,this.drained();this.isRunning=!1}},e.prototype.clear=function(){this.clearTimeout(),this.isDirty=!1,this.pauseDepths={}},e.prototype.clearTimeout=function(){this.timeoutId&&(clearTimeout(this.timeoutId),this.timeoutId=0)},e.prototype.drained=function(){this.drainedOption&&this.drainedOption()},e}(),Ii=function(){function e(e,t){this.runTaskOption=e,this.drainedOption=t,this.queue=[],this.delayedRunner=new Mi(this.drain.bind(this))}return e.prototype.request=function(e,t){this.queue.push(e),this.delayedRunner.request(t)},e.prototype.pause=function(e){this.delayedRunner.pause(e)},e.prototype.resume=function(e,t){this.delayedRunner.resume(e,t)},e.prototype.drain=function(){for(var e=this.queue;e.length;){for(var t=[],n=void 0;n=e.shift();)this.runTask(n),t.push(n);this.drained(t)}},e.prototype.runTask=function(e){this.runTaskOption&&this.runTaskOption(e)},e.prototype.drained=function(e){this.drainedOption&&this.drainedOption(e)},e}();function Pi(e,t,n){var r;return r=/^(year|month)$/.test(e.currentRangeUnit)?e.currentRange:e.activeRange,n.formatRange(r.start,r.end,ln(t.titleFormat||function(e){var t=e.currentRangeUnit;if("year"===t)return{year:"numeric"};if("month"===t)return{year:"numeric",month:"long"};var n=st(e.currentRange.start,e.currentRange.end);if(null!==n&&n>1)return{year:"numeric",month:"short",day:"numeric"};return{year:"numeric",month:"long",day:"numeric"}}(e)),{isEndExclusive:e.isRangeAllDay,defaultSeparator:t.titleRangeSeparator})}var Ni=function(){function e(e){var t=this;this.computeOptionsData=qt(this._computeOptionsData),this.computeCurrentViewData=qt(this._computeCurrentViewData),this.organizeRawLocales=qt(Fr),this.buildLocale=qt(Br),this.buildPluginHooks=jo(),this.buildDateEnv=qt(Hi),this.buildTheme=qt(Oi),this.parseToolbars=qt(Ci),this.buildViewSpecs=qt(oi),this.buildDateProfileGenerator=Yt(Ai),this.buildViewApi=qt(Li),this.buildViewUiProps=Yt(Vi),this.buildEventUiBySource=qt(Ui,wt),this.buildEventUiBases=qt(Wi),this.parseContextBusinessHours=Yt(Bi),this.buildTitle=qt(Pi),this.emitter=new Eo,this.actionRunner=new Ii(this._handleAction.bind(this),this.updateData.bind(this)),this.currentCalendarOptionsInput={},this.currentCalendarOptionsRefined={},this.currentViewOptionsInput={},this.currentViewOptionsRefined={},this.currentCalendarOptionsRefiners={},this.getCurrentData=function(){return t.data},this.dispatch=function(e){t.actionRunner.request(e)},this.props=e,this.actionRunner.pause();var n={},o=this.computeOptionsData(e.optionOverrides,n,e.calendarApi),i=o.calendarOptions.initialView||o.pluginHooks.initialView,a=this.computeCurrentViewData(i,o,e.optionOverrides,n);e.calendarApi.currentDataManager=this,this.emitter.setThisContext(e.calendarApi),this.emitter.setOptions(a.options);var s,l,u,c=(s=o.calendarOptions,l=o.dateEnv,null!=(u=s.initialDate)?l.createMarker(u):Tr(s.now,l)),d=a.dateProfileGenerator.build(c);Zn(d.activeRange,c)||(c=d.currentRange.start);for(var p={dateEnv:o.dateEnv,options:o.calendarOptions,pluginHooks:o.pluginHooks,calendarApi:e.calendarApi,dispatch:this.dispatch,emitter:this.emitter,getCurrentData:this.getCurrentData},f=0,h=o.pluginHooks.contextInit;f<h.length;f++){(0,h[f])(p)}for(var v=si(o.calendarOptions,d,p),g={dynamicOptionOverrides:n,currentViewType:i,currentDate:c,dateProfile:d,businessHours:this.parseContextBusinessHours(p),eventSources:v,eventUiBases:{},eventStore:{defs:{},instances:{}},renderableEventStore:{defs:{},instances:{}},dateSelection:null,eventSelection:"",eventDrag:null,eventResize:null,selectionConfig:this.buildViewUiProps(p).selectionConfig},m=r(r({},p),g),y=0,E=o.pluginHooks.reducers;y<E.length;y++){var S=E[y];r(g,S(null,null,m))}Fi(g,p)&&this.emitter.trigger("loading",!0),this.state=g,this.updateData(),this.actionRunner.resume()}return e.prototype.resetOptions=function(e,t){var n=this.props;n.optionOverrides=t?r(r({},n.optionOverrides),e):e,this.actionRunner.request({type:"NOTHING"})},e.prototype._handleAction=function(e){var t=this,n=t.props,o=t.state,i=t.emitter,a=function(e,t){var n;switch(t.type){case"SET_OPTION":return r(r({},e),((n={})[t.optionName]=t.rawOptionValue,n));default:return e}}(o.dynamicOptionOverrides,e),s=this.computeOptionsData(n.optionOverrides,a,n.calendarApi),l=function(e,t){switch(t.type){case"CHANGE_VIEW_TYPE":e=t.viewType}return e}(o.currentViewType,e),u=this.computeCurrentViewData(l,s,n.optionOverrides,a);n.calendarApi.currentDataManager=this,i.setThisContext(n.calendarApi),i.setOptions(u.options);var c={dateEnv:s.dateEnv,options:s.calendarOptions,pluginHooks:s.pluginHooks,calendarApi:n.calendarApi,dispatch:this.dispatch,emitter:i,getCurrentData:this.getCurrentData},d=o.currentDate,p=o.dateProfile;this.data&&this.data.dateProfileGenerator!==u.dateProfileGenerator&&(p=u.dateProfileGenerator.build(d)),p=function(e,t,n,r){var o;switch(t.type){case"CHANGE_VIEW_TYPE":return r.build(t.dateMarker||n);case"CHANGE_DATE":return r.build(t.dateMarker);case"PREV":if((o=r.buildPrev(e,n)).isValid)return o;break;case"NEXT":if((o=r.buildNext(e,n)).isValid)return o}return e}(p,e,d=function(e,t){switch(t.type){case"CHANGE_DATE":return t.dateMarker;default:return e}}(d,e),u.dateProfileGenerator),"PREV"!==e.type&&"NEXT"!==e.type&&Zn(p.currentRange,d)||(d=p.currentRange.start);for(var f=li(o.eventSources,e,p,c),h=gi(o.eventStore,e,f,p,c),v=ui(f)&&!u.options.progressiveEventRendering&&o.renderableEventStore||h,g=this.buildViewUiProps(c),m=g.eventUiSingleBase,y=g.selectionConfig,E=this.buildEventUiBySource(f),S={dynamicOptionOverrides:a,currentViewType:l,currentDate:d,dateProfile:p,eventSources:f,eventStore:h,renderableEventStore:v,selectionConfig:y,eventUiBases:this.buildEventUiBases(v.defs,m,E),businessHours:this.parseContextBusinessHours(c),dateSelection:Ei(o.dateSelection,e),eventSelection:Si(o.eventSelection,e),eventDrag:bi(o.eventDrag,e),eventResize:Di(o.eventResize,e)},b=r(r({},c),S),D=0,C=s.pluginHooks.reducers;D<C.length;D++){var w=C[D];r(S,w(o,e,b))}var R=Fi(o,c),_=Fi(S,c);!R&&_?i.trigger("loading",!0):R&&!_&&i.trigger("loading",!1),this.state=S,n.onAction&&n.onAction(e)},e.prototype.updateData=function(){var e,t,n,o,i,a,s,l,u,c=this.props,d=this.state,p=this.data,f=this.computeOptionsData(c.optionOverrides,d.dynamicOptionOverrides,c.calendarApi),h=this.computeCurrentViewData(d.currentViewType,f,c.optionOverrides,d.dynamicOptionOverrides),v=this.data=r(r(r({viewTitle:this.buildTitle(d.dateProfile,h.options,f.dateEnv),calendarApi:c.calendarApi,dispatch:this.dispatch,emitter:this.emitter,getCurrentData:this.getCurrentData},f),h),d),g=f.pluginHooks.optionChangeHandlers,m=p&&p.calendarOptions,y=f.calendarOptions;if(m&&m!==y)for(var E in m.timeZone!==y.timeZone&&(d.eventSources=v.eventSources=(a=v.eventSources,s=d.dateProfile,l=v,u=s?s.activeRange:null,pi(a,hi(a,l),u,!0,l)),d.eventStore=v.eventStore=(e=v.eventStore,t=p.dateEnv,n=v.dateEnv,o=e.defs,i=bt(e.instances,(function(e){var i=o[e.defId];return i.allDay||i.recurringDef?e:r(r({},e),{range:{start:n.createMarker(t.toDate(e.range.start,e.forcedStartTzo)),end:n.createMarker(t.toDate(e.range.end,e.forcedEndTzo))},forcedStartTzo:n.canComputeOffset?null:e.forcedStartTzo,forcedEndTzo:n.canComputeOffset?null:e.forcedEndTzo})})),{defs:o,instances:i})),g)m[E]!==y[E]&&g[E](y[E],v);c.onData&&c.onData(v)},e.prototype._computeOptionsData=function(e,t,n){var r=this.processRawCalendarOptions(e,t),o=r.refinedOptions,i=r.pluginHooks,a=r.localeDefaults,s=r.availableLocaleData;zi(r.extra);var l=this.buildDateEnv(o.timeZone,o.locale,o.weekNumberCalculation,o.firstDay,o.weekText,i,s,o.defaultRangeSeparator),u=this.buildViewSpecs(i.views,e,t,a),c=this.buildTheme(o,i);return{calendarOptions:o,pluginHooks:i,dateEnv:l,viewSpecs:u,theme:c,toolbarConfig:this.parseToolbars(o,e,c,u,n),localeDefaults:a,availableRawLocales:s.map}},e.prototype.processRawCalendarOptions=function(e,t){var n=gn([cn,e,t]),o=n.locales,i=n.locale,a=this.organizeRawLocales(o),s=a.map,l=this.buildLocale(i||a.defaultCode,s).options,u=this.buildPluginHooks(e.plugins||[],xi),c=this.currentCalendarOptionsRefiners=r(r(r(r(r({},un),dn),pn),u.listenerRefiners),u.optionRefiners),d={},p=gn([cn,l,e,t]),f={},h=this.currentCalendarOptionsInput,v=this.currentCalendarOptionsRefined,g=!1;for(var m in p)"plugins"!==m&&(p[m]===h[m]||fn[m]&&m in h&&fn[m](h[m],p[m])?f[m]=v[m]:c[m]?(f[m]=c[m](p[m]),g=!0):d[m]=h[m]);return g&&(this.currentCalendarOptionsInput=p,this.currentCalendarOptionsRefined=f),{rawOptions:this.currentCalendarOptionsInput,refinedOptions:this.currentCalendarOptionsRefined,pluginHooks:u,availableLocaleData:a,localeDefaults:l,extra:d}},e.prototype._computeCurrentViewData=function(e,t,n,r){var o=t.viewSpecs[e];if(!o)throw new Error('viewType "'+e+"\" is not available. Please make sure you've loaded all neccessary plugins");var i=this.processRawViewOptions(o,t.pluginHooks,t.localeDefaults,n,r),a=i.refinedOptions;return zi(i.extra),{viewSpec:o,options:a,dateProfileGenerator:this.buildDateProfileGenerator({dateProfileGeneratorClass:o.optionDefaults.dateProfileGeneratorClass,duration:o.duration,durationUnit:o.durationUnit,usesMinMaxTime:o.optionDefaults.usesMinMaxTime,dateEnv:t.dateEnv,calendarApi:this.props.calendarApi,slotMinTime:a.slotMinTime,slotMaxTime:a.slotMaxTime,showNonCurrentDates:a.showNonCurrentDates,dayCount:a.dayCount,dateAlignment:a.dateAlignment,dateIncrement:a.dateIncrement,hiddenDays:a.hiddenDays,weekends:a.weekends,nowInput:a.now,validRangeInput:a.validRange,visibleRangeInput:a.visibleRange,monthMode:a.monthMode,fixedWeekCount:a.fixedWeekCount}),viewApi:this.buildViewApi(e,this.getCurrentData,t.dateEnv)}},e.prototype.processRawViewOptions=function(e,t,n,o,i){var a=gn([cn,e.optionDefaults,n,o,e.optionOverrides,i]),s=r(r(r(r(r(r({},un),dn),pn),vn),t.listenerRefiners),t.optionRefiners),l={},u=this.currentViewOptionsInput,c=this.currentViewOptionsRefined,d=!1,p={};for(var f in a)a[f]===u[f]?l[f]=c[f]:(a[f]===this.currentCalendarOptionsInput[f]?f in this.currentCalendarOptionsRefined&&(l[f]=this.currentCalendarOptionsRefined[f]):s[f]?l[f]=s[f](a[f]):p[f]=a[f],d=!0);return d&&(this.currentViewOptionsInput=a,this.currentViewOptionsRefined=l),{rawOptions:this.currentViewOptionsInput,refinedOptions:this.currentViewOptionsRefined,extra:p}},e}();function Hi(e,t,n,r,o,i,a,s){var l=Br(t||a.defaultCode,a.map);return new Lr({calendarSystem:"gregory",timeZone:e,namedTimeZoneImpl:i.namedTimeZonedImpl,locale:l,weekNumberCalculation:n,firstDay:r,weekText:o,cmdFormatter:i.cmdFormatter,defaultSeparator:s})}function Oi(e,t){return new(t.themeClasses[e.themeSystem]||Go)(e)}function Ai(e){return new(e.dateProfileGeneratorClass||ai)(e)}function Li(e,t,n){return new Cr(e,t,n)}function Ui(e){return bt(e,(function(e){return e.ui}))}function Wi(e,t,n){var r={"":t};for(var o in e){var i=e[o];i.sourceId&&n[i.sourceId]&&(r[o]=n[i.sourceId])}return r}function Vi(e){var t=e.options;return{eventUiSingleBase:kn({display:t.eventDisplay,editable:t.editable,startEditable:t.eventStartEditable,durationEditable:t.eventDurationEditable,constraint:t.eventConstraint,overlap:"boolean"==typeof t.eventOverlap?t.eventOverlap:void 0,allow:t.eventAllow,backgroundColor:t.eventBackgroundColor,borderColor:t.eventBorderColor,textColor:t.eventTextColor,color:t.eventColor},e),selectionConfig:kn({constraint:t.selectConstraint,overlap:"boolean"==typeof t.selectOverlap?t.selectOverlap:void 0,allow:t.selectAllow},e)}}function Fi(e,t){for(var n=0,r=t.pluginHooks.isLoadingFuncs;n<r.length;n++){if((0,r[n])(e))return!0}return!1}function Bi(e){return Yr(e.options.businessHours,e)}function zi(e,t){for(var n in e)console.warn("Unknown option '"+n+"'"+(t?" for view '"+t+"'":""))}var ji=function(e){function t(t){var n=e.call(this,t)||this;return n.handleData=function(e){n.dataManager?n.setState(e):n.state=e},n.dataManager=new Ni({optionOverrides:t.optionOverrides,calendarApi:t.calendarApi,onData:n.handleData}),n}return n(t,e),t.prototype.render=function(){return this.props.children(this.state)},t.prototype.componentDidUpdate=function(e){var t=this.props.optionOverrides;t!==e.optionOverrides&&this.dataManager.resetOptions(t)},t}(Ro);var Gi=function(e){this.timeZoneName=e},qi=function(){function e(){this.strictOrder=!1,this.allowReslicing=!1,this.maxCoord=-1,this.maxStackCnt=-1,this.levelCoords=[],this.entriesByLevel=[],this.stackCnts={}}return e.prototype.addSegs=function(e){for(var t=[],n=0,r=e;n<r.length;n++){var o=r[n];this.insertEntry(o,t)}return t},e.prototype.insertEntry=function(e,t){var n=this.findInsertion(e);return this.isInsertionValid(n,e)?(this.insertEntryAt(e,n),1):this.handleInvalidInsertion(n,e,t)},e.prototype.isInsertionValid=function(e,t){return(-1===this.maxCoord||e.levelCoord+t.thickness<=this.maxCoord)&&(-1===this.maxStackCnt||e.stackCnt<this.maxStackCnt)},e.prototype.handleInvalidInsertion=function(e,t,n){return this.allowReslicing&&e.touchingEntry?this.splitEntry(t,e.touchingEntry,n):(n.push(t),0)},e.prototype.splitEntry=function(e,t,n){var r=0,i=[],a=e.span,s=t.span;return a.start<s.start&&(r+=this.insertEntry({index:e.index,thickness:e.thickness,span:{start:a.start,end:s.start}},i)),a.end>s.end&&(r+=this.insertEntry({index:e.index,thickness:e.thickness,span:{start:s.end,end:a.end}},i)),r?(n.push.apply(n,o([{index:e.index,thickness:e.thickness,span:$i(s,a)}],i)),r):(n.push(e),0)},e.prototype.insertEntryAt=function(e,t){var n=this.entriesByLevel,r=this.levelCoords;-1===t.lateral?(Ji(r,t.level,t.levelCoord),Ji(n,t.level,[e])):Ji(n[t.level],t.lateral,e),this.stackCnts[Zi(e)]=t.stackCnt},e.prototype.findInsertion=function(e){for(var t=this,n=t.levelCoords,r=t.entriesByLevel,o=t.strictOrder,i=t.stackCnts,a=n.length,s=0,l=-1,u=-1,c=null,d=0,p=0;p<a;p+=1){var f=n[p];if(!o&&f>=s+e.thickness)break;for(var h=r[p],v=void 0,g=Qi(h,e.span.start,Yi),m=g[0]+g[1];(v=h[m])&&v.span.start<e.span.end;){var y=f+v.thickness;y>s&&(s=y,c=v,l=p,u=m),y===s&&(d=Math.max(d,i[Zi(v)]+1)),m+=1}}var E=0;if(c)for(E=l+1;E<a&&n[E]<s;)E+=1;var S=-1;return E<a&&n[E]===s&&(S=Qi(r[E],e.span.end,Yi)[0]),{touchingLevel:l,touchingLateral:u,touchingEntry:c,stackCnt:d,levelCoord:s,level:E,lateral:S}},e.prototype.toRects=function(){for(var e=this.entriesByLevel,t=this.levelCoords,n=e.length,o=[],i=0;i<n;i+=1)for(var a=e[i],s=t[i],l=0,u=a;l<u.length;l++){var c=u[l];o.push(r(r({},c),{levelCoord:s}))}return o},e}();function Yi(e){return e.span.end}function Zi(e){return e.index+":"+e.span.start}function Xi(e){for(var t=[],n=0,r=e;n<r.length;n++){for(var o=r[n],i=[],a={span:o.span,entries:[o]},s=0,l=t;s<l.length;s++){var u=l[s];$i(u.span,a.span)?a={entries:u.entries.concat(a.entries),span:Ki(u.span,a.span)}:i.push(u)}i.push(a),t=i}return t}function Ki(e,t){return{start:Math.min(e.start,t.start),end:Math.max(e.end,t.end)}}function $i(e,t){var n=Math.max(e.start,t.start),r=Math.min(e.end,t.end);return n<r?{start:n,end:r}:null}function Ji(e,t,n){e.splice(t,0,n)}function Qi(e,t,n){var r=0,o=e.length;if(!o||t<n(e[r]))return[0,0];if(t>n(e[o-1]))return[o,0];for(;r<o;){var i=Math.floor(r+(o-r)/2),a=n(e[i]);if(t<a)o=i;else{if(!(t>a))return[i,1];r=i+1}}return[r,0]}var ea=function(){function e(e){this.component=e.component,this.isHitComboAllowed=e.isHitComboAllowed||null}return e.prototype.destroy=function(){},e}();function ta(e,t){return{component:e,el:t.el,useEventCenter:null==t.useEventCenter||t.useEventCenter,isHitComboAllowed:t.isHitComboAllowed||null}}function na(e){var t;return(t={})[e.component.uid]=e,t}var ra={},oa=function(){function e(e,t){this.emitter=new Eo}return e.prototype.destroy=function(){},e.prototype.setMirrorIsVisible=function(e){},e.prototype.setMirrorNeedsRevert=function(e){},e.prototype.setAutoScrollEnabled=function(e){},e}(),ia={},aa={startTime:Nt,duration:Nt,create:Boolean,sourceId:String};function sa(e){var t=mn(e,aa),n=t.refined,r=t.extra;return{startTime:n.startTime||null,duration:n.duration||null,create:null==n.create||n.create,sourceId:n.sourceId,leftoverProps:r}}var la=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this,t=this.props.widgetGroups.map((function(t){return e.renderWidgetGroup(t)}));return _o.apply(void 0,o(["div",{className:"fc-toolbar-chunk"}],t))},t.prototype.renderWidgetGroup=function(e){for(var t=this.props,n=this.context.theme,r=[],i=!0,a=0,s=e;a<s.length;a++){var l=s[a],u=l.buttonName,c=l.buttonClick,d=l.buttonText,p=l.buttonIcon,f=l.buttonHint;if("title"===u)i=!1,r.push(_o("h2",{className:"fc-toolbar-title",id:t.titleId},t.title));else{var h=u===t.activeButton,v=!t.isTodayEnabled&&"today"===u||!t.isPrevEnabled&&"prev"===u||!t.isNextEnabled&&"next"===u,g=["fc-"+u+"-button",n.getClass("button")];h&&g.push(n.getClass("buttonActive")),r.push(_o("button",{type:"button",title:"function"==typeof f?f(t.navUnit):f,disabled:v,"aria-pressed":h,className:g.join(" "),onClick:c},d||(p?_o("span",{className:p}):"")))}}if(r.length>1){var m=i&&n.getClass("buttonGroup")||"";return _o.apply(void 0,o(["div",{className:m}],r))}return r[0]},t}(Uo),ua=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e,t,n=this.props,r=n.model,o=n.extraClassName,i=!1,a=r.sectionWidgets,s=a.center;return a.left?(i=!0,e=a.left):e=a.start,a.right?(i=!0,t=a.right):t=a.end,_o("div",{className:[o||"","fc-toolbar",i?"fc-toolbar-ltr":""].join(" ")},this.renderSection("start",e||[]),this.renderSection("center",s||[]),this.renderSection("end",t||[]))},t.prototype.renderSection=function(e,t){var n=this.props;return _o(la,{key:e,widgetGroups:t,title:n.title,navUnit:n.navUnit,activeButton:n.activeButton,isTodayEnabled:n.isTodayEnabled,isPrevEnabled:n.isPrevEnabled,isNextEnabled:n.isNextEnabled,titleId:n.titleId})},t}(Uo),ca=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.state={availableWidth:null},t.handleEl=function(e){t.el=e,Fo(t.props.elRef,e),t.updateAvailableWidth()},t.handleResize=function(){t.updateAvailableWidth()},t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.state,n=e.aspectRatio,r=["fc-view-harness",n||e.liquid||e.height?"fc-view-harness-active":"fc-view-harness-passive"],o="",i="";return n?null!==t.availableWidth?o=t.availableWidth/n:i=1/n*100+"%":o=e.height||"",_o("div",{"aria-labelledby":e.labeledById,ref:this.handleEl,className:r.join(" "),style:{height:o,paddingBottom:i}},e.children)},t.prototype.componentDidMount=function(){this.context.addResizeHandler(this.handleResize)},t.prototype.componentWillUnmount=function(){this.context.removeResizeHandler(this.handleResize)},t.prototype.updateAvailableWidth=function(){this.el&&this.props.aspectRatio&&this.setState({availableWidth:this.el.offsetWidth})},t}(Uo),da=function(e){function t(t){var n=e.call(this,t)||this;return n.handleSegClick=function(e,t){var r=n.component,o=r.context,i=Jn(t);if(i&&r.isValidSegDownEl(e.target)){var a=Se(e.target,".fc-event-forced-url"),s=a?a.querySelector("a[href]").href:"";o.emitter.trigger("eventClick",{el:t,event:new xr(r.context,i.eventRange.def,i.eventRange.instance),jsEvent:e,view:o.viewApi}),s&&!e.defaultPrevented&&(window.location.href=s)}},n.destroy=Ie(t.el,"click",".fc-event",n.handleSegClick),n}return n(t,e),t}(ea),pa=function(e){function t(t){var n,r,o,i,a,s=e.call(this,t)||this;return s.handleEventElRemove=function(e){e===s.currentSegEl&&s.handleSegLeave(null,s.currentSegEl)},s.handleSegEnter=function(e,t){Jn(t)&&(s.currentSegEl=t,s.triggerEvent("eventMouseEnter",e,t))},s.handleSegLeave=function(e,t){s.currentSegEl&&(s.currentSegEl=null,s.triggerEvent("eventMouseLeave",e,t))},s.removeHoverListeners=(n=t.el,r=".fc-event",o=s.handleSegEnter,i=s.handleSegLeave,Ie(n,"mouseover",r,(function(e,t){if(t!==a){a=t,o(e,t);var n=function(e){a=null,i(e,t),t.removeEventListener("mouseleave",n)};t.addEventListener("mouseleave",n)}}))),s}return n(t,e),t.prototype.destroy=function(){this.removeHoverListeners()},t.prototype.triggerEvent=function(e,t,n){var r=this.component,o=r.context,i=Jn(n);t&&!r.isValidSegDownEl(t.target)||o.emitter.trigger(e,{el:n,event:new xr(o,i.eventRange.def,i.eventRange.instance),jsEvent:t,view:o.viewApi})},t}(ea),fa=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.buildViewContext=qt(Ao),t.buildViewPropTransformers=qt(va),t.buildToolbarProps=qt(ha),t.headerRef=ko(),t.footerRef=ko(),t.interactionsStore={},t.state={viewLabelId:xe()},t.registerInteractiveComponent=function(e,n){var r=ta(e,n),o=[da,pa].concat(t.props.pluginHooks.componentInteractions).map((function(e){return new e(r)}));t.interactionsStore[e.uid]=o,ra[e.uid]=r},t.unregisterInteractiveComponent=function(e){for(var n=0,r=t.interactionsStore[e.uid];n<r.length;n++){r[n].destroy()}delete t.interactionsStore[e.uid],delete ra[e.uid]},t.resizeRunner=new Mi((function(){t.props.emitter.trigger("_resize",!0),t.props.emitter.trigger("windowResize",{view:t.props.viewApi})})),t.handleWindowResize=function(e){var n=t.props.options;n.handleWindowResize&&e.target===window&&t.resizeRunner.request(n.windowResizeDelay)},t}return n(t,e),t.prototype.render=function(){var e,t=this.props,n=t.toolbarConfig,o=t.options,i=this.buildToolbarProps(t.viewSpec,t.dateProfile,t.dateProfileGenerator,t.currentDate,Tr(t.options.now,t.dateEnv),t.viewTitle),a=!1,s="";t.isHeightAuto||t.forPrint?s="":null!=o.height?a=!0:null!=o.contentHeight?s=o.contentHeight:e=Math.max(o.aspectRatio,.5);var l=this.buildViewContext(t.viewSpec,t.viewApi,t.options,t.dateProfileGenerator,t.dateEnv,t.theme,t.pluginHooks,t.dispatch,t.getCurrentData,t.emitter,t.calendarApi,this.registerInteractiveComponent,this.unregisterInteractiveComponent),u=n.header&&n.header.hasTitle?this.state.viewLabelId:"";return _o(Oo.Provider,{value:l},n.header&&_o(ua,r({ref:this.headerRef,extraClassName:"fc-header-toolbar",model:n.header,titleId:u},i)),_o(ca,{liquid:a,height:s,aspectRatio:e,labeledById:u},this.renderView(t),this.buildAppendContent()),n.footer&&_o(ua,r({ref:this.footerRef,extraClassName:"fc-footer-toolbar",model:n.footer,titleId:""},i)))},t.prototype.componentDidMount=function(){var e=this.props;this.calendarInteractions=e.pluginHooks.calendarInteractions.map((function(t){return new t(e)})),window.addEventListener("resize",this.handleWindowResize);var t=e.pluginHooks.propSetHandlers;for(var n in t)t[n](e[n],e)},t.prototype.componentDidUpdate=function(e){var t=this.props,n=t.pluginHooks.propSetHandlers;for(var r in n)t[r]!==e[r]&&n[r](t[r],t)},t.prototype.componentWillUnmount=function(){window.removeEventListener("resize",this.handleWindowResize),this.resizeRunner.clear();for(var e=0,t=this.calendarInteractions;e<t.length;e++){t[e].destroy()}this.props.emitter.trigger("_unmount")},t.prototype.buildAppendContent=function(){var e=this.props,t=e.pluginHooks.viewContainerAppends.map((function(t){return t(e)}));return _o.apply(void 0,o([xo,{}],t))},t.prototype.renderView=function(e){for(var t=e.pluginHooks,n=e.viewSpec,o={dateProfile:e.dateProfile,businessHours:e.businessHours,eventStore:e.renderableEventStore,eventUiBases:e.eventUiBases,dateSelection:e.dateSelection,eventSelection:e.eventSelection,eventDrag:e.eventDrag,eventResize:e.eventResize,isHeightAuto:e.isHeightAuto,forPrint:e.forPrint},i=0,a=this.buildViewPropTransformers(t.viewPropsTransformers);i<a.length;i++){var s=a[i];r(o,s.transform(o,e))}var l=n.component;return _o(l,r({},o))},t}(Lo);function ha(e,t,n,r,o,i){var a=n.build(o,void 0,!1),s=n.buildPrev(t,r,!1),l=n.buildNext(t,r,!1);return{title:i,activeButton:e.type,navUnit:e.singleUnit,isTodayEnabled:a.isValid&&!Zn(t.currentRange,o),isPrevEnabled:s.isValid,isNextEnabled:l.isValid}}function va(e){return e.map((function(e){return new e}))}var ga=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.state={forPrint:!1},t.handleBeforePrint=function(){t.setState({forPrint:!0})},t.handleAfterPrint=function(){t.setState({forPrint:!1})},t}return n(t,e),t.prototype.render=function(){var e=this.props,t=e.options,n=this.state.forPrint,r=n||"auto"===t.height||"auto"===t.contentHeight,o=r||null==t.height?"":t.height,i=["fc",n?"fc-media-print":"fc-media-screen","fc-direction-"+t.direction,e.theme.getClass("root")];return Qr()||i.push("fc-liquid-hack"),e.children(i,o,r,n)},t.prototype.componentDidMount=function(){var e=this.props.emitter;e.on("_beforeprint",this.handleBeforePrint),e.on("_afterprint",this.handleAfterPrint)},t.prototype.componentWillUnmount=function(){var e=this.props.emitter;e.off("_beforeprint",this.handleBeforePrint),e.off("_afterprint",this.handleAfterPrint)},t}(Uo);function ma(e,t){return ln(!e||t>10?{weekday:"short"}:t>1?{weekday:"short",month:"numeric",day:"numeric",omitCommas:!0}:{weekday:"long"})}var ya="fc-col-header-cell";function Ea(e){return e.text}var Sa=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.context,t=e.dateEnv,n=e.options,o=e.theme,i=e.viewApi,a=this.props,s=a.date,l=a.dateProfile,u=ro(s,a.todayRange,null,l),c=[ya].concat(oo(u,o)),d=t.format(s,a.dayHeaderFormat),p=!u.isDisabled&&a.colCnt>1?so(this.context,s):{},f=r(r(r({date:t.toDate(s),view:i},a.extraHookProps),{text:d}),u);return _o(Yo,{hookProps:f,classNames:n.dayHeaderClassNames,content:n.dayHeaderContent,defaultContent:Ea,didMount:n.dayHeaderDidMount,willUnmount:n.dayHeaderWillUnmount},(function(e,t,n,o){return _o("th",r({ref:e,role:"columnheader",className:c.concat(t).join(" "),"data-date":u.isDisabled?void 0:Bt(s),colSpan:a.colSpan},a.extraDataAttrs),_o("div",{className:"fc-scrollgrid-sync-inner"},!u.isDisabled&&_o("a",r({ref:n,className:["fc-col-header-cell-cushion",a.isSticky?"fc-sticky":""].join(" ")},p),o)))}))},t}(Uo),ba=ln({weekday:"long"}),Da=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.dateEnv,o=t.theme,i=t.viewApi,a=t.options,s=tt(new Date(2592e5),e.dow),l={dow:e.dow,isDisabled:!1,isFuture:!1,isPast:!1,isToday:!1,isOther:!1},u=[ya].concat(oo(l,o),e.extraClassNames||[]),c=n.format(s,e.dayHeaderFormat),d=r(r(r(r({date:s},l),{view:i}),e.extraHookProps),{text:c});return _o(Yo,{hookProps:d,classNames:a.dayHeaderClassNames,content:a.dayHeaderContent,defaultContent:Ea,didMount:a.dayHeaderDidMount,willUnmount:a.dayHeaderWillUnmount},(function(t,o,i,a){return _o("th",r({ref:t,role:"columnheader",className:u.concat(o).join(" "),colSpan:e.colSpan},e.extraDataAttrs),_o("div",{className:"fc-scrollgrid-sync-inner"},_o("a",{"aria-label":n.format(s,ba),className:["fc-col-header-cell-cushion",e.isSticky?"fc-sticky":""].join(" "),ref:i},a)))}))},t}(Uo),Ca=function(e){function t(t,n){var r=e.call(this,t,n)||this;return r.initialNowDate=Tr(n.options.now,n.dateEnv),r.initialNowQueriedMs=(new Date).valueOf(),r.state=r.computeTiming().currentState,r}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.state;return e.children(t.nowDate,t.todayRange)},t.prototype.componentDidMount=function(){this.setTimeout()},t.prototype.componentDidUpdate=function(e){e.unit!==this.props.unit&&(this.clearTimeout(),this.setTimeout())},t.prototype.componentWillUnmount=function(){this.clearTimeout()},t.prototype.computeTiming=function(){var e=this.props,t=this.context,n=nt(this.initialNowDate,(new Date).valueOf()-this.initialNowQueriedMs),r=t.dateEnv.startOf(n,e.unit),o=t.dateEnv.add(r,Nt(1,e.unit)),i=o.valueOf()-n.valueOf();return i=Math.min(864e5,i),{currentState:{nowDate:r,todayRange:wa(r)},nextState:{nowDate:o,todayRange:wa(o)},waitMs:i}},t.prototype.setTimeout=function(){var e=this,t=this.computeTiming(),n=t.nextState,r=t.waitMs;this.timeoutId=setTimeout((function(){e.setState(n,(function(){e.setTimeout()}))}),r)},t.prototype.clearTimeout=function(){this.timeoutId&&clearTimeout(this.timeoutId)},t.contextType=Oo,t}(Ro);function wa(e){var t=lt(e);return{start:t,end:tt(t,1)}}var Ra=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.createDayHeaderFormatter=qt(_a),t}return n(t,e),t.prototype.render=function(){var e=this.context,t=this.props,n=t.dates,r=t.dateProfile,o=t.datesRepDistinctDays,i=t.renderIntro,a=this.createDayHeaderFormatter(e.options.dayHeaderFormat,o,n.length);return _o(Ca,{unit:"day"},(function(e,t){return _o("tr",{role:"row"},i&&i("day"),n.map((function(e){return o?_o(Sa,{key:e.toISOString(),date:e,dateProfile:r,todayRange:t,colCnt:n.length,dayHeaderFormat:a}):_o(Da,{key:e.getUTCDay(),dow:e.getUTCDay(),dayHeaderFormat:a})})))}))},t}(Uo);function _a(e,t,n){return e||ma(t,n)}var Ta=function(){function e(e,t){for(var n=e.start,r=e.end,o=[],i=[],a=-1;n<r;)t.isHiddenDay(n)?o.push(a+.5):(a+=1,o.push(a),i.push(n)),n=tt(n,1);this.dates=i,this.indices=o,this.cnt=i.length}return e.prototype.sliceRange=function(e){var t=this.getDateDayIndex(e.start),n=this.getDateDayIndex(tt(e.end,-1)),r=Math.max(0,t),o=Math.min(this.cnt-1,n);return(r=Math.ceil(r))<=(o=Math.floor(o))?{firstIndex:r,lastIndex:o,isStart:t===r,isEnd:n===o}:null},e.prototype.getDateDayIndex=function(e){var t=this.indices,n=Math.floor(ot(this.dates[0],e));return n<0?t[0]-1:n>=t.length?t[t.length-1]+1:t[n]},e}(),ka=function(){function e(e,t){var n,r,o,i=e.dates;if(t){for(r=i[0].getUTCDay(),n=1;n<i.length&&i[n].getUTCDay()!==r;n+=1);o=Math.ceil(i.length/n)}else o=1,n=i.length;this.rowCnt=o,this.colCnt=n,this.daySeries=e,this.cells=this.buildCells(),this.headerDates=this.buildHeaderDates()}return e.prototype.buildCells=function(){for(var e=[],t=0;t<this.rowCnt;t+=1){for(var n=[],r=0;r<this.colCnt;r+=1)n.push(this.buildCell(t,r));e.push(n)}return e},e.prototype.buildCell=function(e,t){var n=this.daySeries.dates[e*this.colCnt+t];return{key:n.toISOString(),date:n}},e.prototype.buildHeaderDates=function(){for(var e=[],t=0;t<this.colCnt;t+=1)e.push(this.cells[0][t].date);return e},e.prototype.sliceRange=function(e){var t=this.colCnt,n=this.daySeries.sliceRange(e),r=[];if(n)for(var o=n.firstIndex,i=n.lastIndex,a=o;a<=i;){var s=Math.floor(a/t),l=Math.min((s+1)*t,i+1);r.push({row:s,firstCol:a%t,lastCol:(l-1)%t,isStart:n.isStart&&a===o,isEnd:n.isEnd&&l-1===i}),a=l}return r},e}(),xa=function(){function e(){this.sliceBusinessHours=qt(this._sliceBusinessHours),this.sliceDateSelection=qt(this._sliceDateSpan),this.sliceEventStore=qt(this._sliceEventStore),this.sliceEventDrag=qt(this._sliceInteraction),this.sliceEventResize=qt(this._sliceInteraction),this.forceDayIfListItem=!1}return e.prototype.sliceProps=function(e,t,n,r){for(var i=[],a=4;a<arguments.length;a++)i[a-4]=arguments[a];var s=e.eventUiBases,l=this.sliceEventStore.apply(this,o([e.eventStore,s,t,n],i));return{dateSelectionSegs:this.sliceDateSelection.apply(this,o([e.dateSelection,s,r],i)),businessHourSegs:this.sliceBusinessHours.apply(this,o([e.businessHours,t,n,r],i)),fgEventSegs:l.fg,bgEventSegs:l.bg,eventDrag:this.sliceEventDrag.apply(this,o([e.eventDrag,s,t,n],i)),eventResize:this.sliceEventResize.apply(this,o([e.eventResize,s,t,n],i)),eventSelection:e.eventSelection}},e.prototype.sliceNowDate=function(e,t){for(var n=[],r=2;r<arguments.length;r++)n[r-2]=arguments[r];return this._sliceDateSpan.apply(this,o([{range:{start:e,end:nt(e,1)},allDay:!1},{},t],n))},e.prototype._sliceBusinessHours=function(e,t,n,r){for(var i=[],a=4;a<arguments.length;a++)i[a-4]=arguments[a];return e?this._sliceEventStore.apply(this,o([xt(e,Ma(t,Boolean(n)),r),{},t,n],i)).bg:[]},e.prototype._sliceEventStore=function(e,t,n,r){for(var o=[],i=4;i<arguments.length;i++)o[i-4]=arguments[i];if(e){var a=Xn(e,t,Ma(n,Boolean(r)),r);return{bg:this.sliceEventRanges(a.bg,o),fg:this.sliceEventRanges(a.fg,o)}}return{bg:[],fg:[]}},e.prototype._sliceInteraction=function(e,t,n,r){for(var o=[],i=4;i<arguments.length;i++)o[i-4]=arguments[i];if(!e)return null;var a=Xn(e.mutatedEvents,t,Ma(n,Boolean(r)),r);return{segs:this.sliceEventRanges(a.fg,o),affectedInstances:e.affectedEvents.instances,isEvent:e.isEvent}},e.prototype._sliceDateSpan=function(e,t,n){for(var r=[],i=3;i<arguments.length;i++)r[i-3]=arguments[i];if(!e)return[];for(var a=gr(e,t,n),s=this.sliceRange.apply(this,o([e.range],r)),l=0,u=s;l<u.length;l++){var c=u[l];c.eventRange=a}return s},e.prototype.sliceEventRanges=function(e,t){for(var n=[],r=0,o=e;r<o.length;r++){var i=o[r];n.push.apply(n,this.sliceEventRange(i,t))}return n},e.prototype.sliceEventRange=function(e,t){var n=e.range;this.forceDayIfListItem&&"list-item"===e.ui.display&&(n={start:n.start,end:tt(n.start,1)});for(var r=this.sliceRange.apply(this,o([n],t)),i=0,a=r;i<a.length;i++){var s=a[i];s.eventRange=e,s.isStart=e.isStart&&s.isStart,s.isEnd=e.isEnd&&s.isEnd}return r},e}();function Ma(e,t){var n=e.activeRange;return t?n:{start:nt(n.start,e.slotMinTime.milliseconds),end:nt(n.end,e.slotMaxTime.milliseconds-864e5)}}function Ia(e,t,n){var r=e.mutatedEvents.instances;for(var o in r)if(!Yn(t.validRange,r[o].range))return!1;return Na({eventDrag:e},n)}function Pa(e,t,n){return!!Yn(t.validRange,e.range)&&Na({dateSelection:e},n)}function Na(e,t){var n=t.getCurrentData(),o=r({businessHours:n.businessHours,dateSelection:"",eventStore:n.eventStore,eventUiBases:n.eventUiBases,eventSelection:"",eventDrag:null,eventResize:null},e);return(t.pluginHooks.isPropsValid||Ha)(o,t)}function Ha(e,t,n,o){return void 0===n&&(n={}),!(e.eventDrag&&!function(e,t,n,o){var i=t.getCurrentData(),a=e.eventDrag,s=a.mutatedEvents,l=s.defs,u=s.instances,c=Qn(l,a.isEvent?e.eventUiBases:{"":i.selectionConfig});o&&(c=bt(c,o));var d=(v=e.eventStore,g=a.affectedEvents.instances,{defs:v.defs,instances:St(v.instances,(function(e){return!g[e.instanceId]}))}),p=d.defs,f=d.instances,h=Qn(p,e.eventUiBases);var v,g;for(var m in u){var y=u[m],E=y.range,S=c[y.defId],b=l[y.defId];if(!Oa(S.constraints,E,d,e.businessHours,t))return!1;var D=t.options.eventOverlap,C="function"==typeof D?D:null;for(var w in f){var R=f[w];if(qn(E,R.range)){if(!1===h[R.defId].overlap&&a.isEvent)return!1;if(!1===S.overlap)return!1;if(C&&!C(new xr(t,p[R.defId],R),new xr(t,b,y)))return!1}}for(var _=i.eventStore,T=0,k=S.allows;T<k.length;T++){var x=k[T],M=r(r({},n),{range:y.range,allDay:b.allDay}),I=_.defs[b.defId],P=_.instances[m],N=void 0;if(N=I?new xr(t,I,P):new xr(t,b),!x(yr(M,t),N))return!1}}return!0}(e,t,n,o))&&!(e.dateSelection&&!function(e,t,n,o){var i=e.eventStore,a=i.defs,s=i.instances,l=e.dateSelection,u=l.range,c=t.getCurrentData().selectionConfig;o&&(c=o(c));if(!Oa(c.constraints,u,i,e.businessHours,t))return!1;var d=t.options.selectOverlap,p="function"==typeof d?d:null;for(var f in s){var h=s[f];if(qn(u,h.range)){if(!1===c.overlap)return!1;if(p&&!p(new xr(t,a[h.defId],h),null))return!1}}for(var v=0,g=c.allows;v<g.length;v++){if(!(0,g[v])(yr(r(r({},n),l),t),null))return!1}return!0}(e,t,n,o))}function Oa(e,t,n,r,o){for(var i=0,a=e;i<a.length;i++){if(!Ua(Aa(a[i],t,n,r,o),t))return!1}return!0}function Aa(e,t,n,r,o){return"businessHours"===e?La(xt(r,t,o)):"string"==typeof e?La(wn(n,(function(t){return t.groupId===e}))):"object"==typeof e&&e?La(xt(e,t,o)):[]}function La(e){var t=e.instances,n=[];for(var r in t)n.push(t[r].range);return n}function Ua(e,t){for(var n=0,r=e;n<r.length;n++){if(Yn(r[n],t))return!0}return!1}var Wa=/^(visible|hidden)$/,Va=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.handleEl=function(e){t.el=e,Fo(t.props.elRef,e)},t}return n(t,e),t.prototype.render=function(){var e=this.props,t=e.liquid,n=e.liquidIsAbsolute,r=t&&n,o=["fc-scroller"];return t&&(n?o.push("fc-scroller-liquid-absolute"):o.push("fc-scroller-liquid")),_o("div",{ref:this.handleEl,className:o.join(" "),style:{overflowX:e.overflowX,overflowY:e.overflowY,left:r&&-(e.overcomeLeft||0)||"",right:r&&-(e.overcomeRight||0)||"",bottom:r&&-(e.overcomeBottom||0)||"",marginLeft:!r&&-(e.overcomeLeft||0)||"",marginRight:!r&&-(e.overcomeRight||0)||"",marginBottom:!r&&-(e.overcomeBottom||0)||"",maxHeight:e.maxHeight||""}},e.children)},t.prototype.needsXScrolling=function(){if(Wa.test(this.props.overflowX))return!1;for(var e=this.el,t=this.el.getBoundingClientRect().width-this.getYScrollbarWidth(),n=e.children,r=0;r<n.length;r+=1){if(n[r].getBoundingClientRect().width>t)return!0}return!1},t.prototype.needsYScrolling=function(){if(Wa.test(this.props.overflowY))return!1;for(var e=this.el,t=this.el.getBoundingClientRect().height-this.getXScrollbarWidth(),n=e.children,r=0;r<n.length;r+=1){if(n[r].getBoundingClientRect().height>t)return!0}return!1},t.prototype.getXScrollbarWidth=function(){return Wa.test(this.props.overflowX)?0:this.el.offsetHeight-this.el.clientHeight},t.prototype.getYScrollbarWidth=function(){return Wa.test(this.props.overflowY)?0:this.el.offsetWidth-this.el.clientWidth},t}(Uo),Fa=function(){function e(e){var t=this;this.masterCallback=e,this.currentMap={},this.depths={},this.callbackMap={},this.handleValue=function(e,n){var r=t,o=r.depths,i=r.currentMap,a=!1,s=!1;null!==e?(a=n in i,i[n]=e,o[n]=(o[n]||0)+1,s=!0):(o[n]-=1,o[n]||(delete i[n],delete t.callbackMap[n],a=!0)),t.masterCallback&&(a&&t.masterCallback(null,String(n)),s&&t.masterCallback(e,String(n)))}}return e.prototype.createRef=function(e){var t=this,n=this.callbackMap[e];return n||(n=this.callbackMap[e]=function(n){t.handleValue(n,String(e))}),n},e.prototype.collect=function(e,t,n){return kt(this.currentMap,e,t,n)},e.prototype.getAll=function(){return Ct(this.currentMap)},e}();function Ba(e){for(var t=0,n=0,r=De(e,".fc-scrollgrid-shrink");n<r.length;n++){var o=r[n];t=Math.max(t,Je(o))}return Math.ceil(t)}function za(e,t){return e.liquid&&t.liquid}function ja(e,t){return null!=t.maxHeight||za(e,t)}function Ga(e,t,n,r){var o=n.expandRows;return"function"==typeof t.content?t.content(n):_o("table",{role:"presentation",className:[t.tableClassName,e.syncRowHeights?"fc-scrollgrid-sync-table":""].join(" "),style:{minWidth:n.tableMinWidth,width:n.clientWidth,height:o?n.clientHeight:""}},n.tableColGroupNode,_o(r?"thead":"tbody",{role:"presentation"},"function"==typeof t.rowContent?t.rowContent(n):t.rowContent))}function qa(e,t){return Gt(e,t,wt)}function Ya(e,t){for(var n=[],r=0,i=e;r<i.length;r++)for(var a=i[r],s=a.span||1,l=0;l<s;l+=1)n.push(_o("col",{style:{width:"shrink"===a.width?Za(t):a.width||"",minWidth:a.minWidth||""}}));return _o.apply(void 0,o(["colgroup",{}],n))}function Za(e){return null==e?4:e}function Xa(e){for(var t=0,n=e;t<n.length;t++){if("shrink"===n[t].width)return!0}return!1}function Ka(e,t){var n=["fc-scrollgrid",t.theme.getClass("table")];return e&&n.push("fc-scrollgrid-liquid"),n}function $a(e,t){var n=["fc-scrollgrid-section","fc-scrollgrid-section-"+e.type,e.className];return t&&e.liquid&&null==e.maxHeight&&n.push("fc-scrollgrid-section-liquid"),e.isSticky&&n.push("fc-scrollgrid-section-sticky"),n}function Ja(e){return _o("div",{className:"fc-scrollgrid-sticky-shim",style:{width:e.clientWidth,minWidth:e.tableMinWidth}})}function Qa(e){var t=e.stickyHeaderDates;return null!=t&&"auto"!==t||(t="auto"===e.height||"auto"===e.viewHeight),t}function es(e){var t=e.stickyFooterScrollbar;return null!=t&&"auto"!==t||(t="auto"===e.height||"auto"===e.viewHeight),t}var ts=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.processCols=qt((function(e){return e}),qa),t.renderMicroColGroup=qt(Ya),t.scrollerRefs=new Fa,t.scrollerElRefs=new Fa(t._handleScrollerEl.bind(t)),t.state={shrinkWidth:null,forceYScrollbars:!1,scrollerClientWidths:{},scrollerClientHeights:{}},t.handleSizing=function(){t.setState(r({shrinkWidth:t.computeShrinkWidth()},t.computeScrollerDims()))},t}return n(t,e),t.prototype.render=function(){var e=this,t=e.props,n=e.state,r=e.context,i=t.sections||[],a=this.processCols(t.cols),s=this.renderMicroColGroup(a,n.shrinkWidth),l=Ka(t.liquid,r);t.collapsibleWidth&&l.push("fc-scrollgrid-collapsible");for(var u,c=i.length,d=0,p=[],f=[],h=[];d<c&&"header"===(u=i[d]).type;)p.push(this.renderSection(u,s,!0)),d+=1;for(;d<c&&"body"===(u=i[d]).type;)f.push(this.renderSection(u,s,!1)),d+=1;for(;d<c&&"footer"===(u=i[d]).type;)h.push(this.renderSection(u,s,!0)),d+=1;var v=!Qr(),g={role:"rowgroup"};return _o("table",{role:"grid",className:l.join(" "),style:{height:t.height}},Boolean(!v&&p.length)&&_o.apply(void 0,o(["thead",g],p)),Boolean(!v&&f.length)&&_o.apply(void 0,o(["tbody",g],f)),Boolean(!v&&h.length)&&_o.apply(void 0,o(["tfoot",g],h)),v&&_o.apply(void 0,o(o(o(["tbody",g],p),f),h)))},t.prototype.renderSection=function(e,t,n){return"outerContent"in e?_o(xo,{key:e.key},e.outerContent):_o("tr",{key:e.key,role:"presentation",className:$a(e,this.props.liquid).join(" ")},this.renderChunkTd(e,t,e.chunk,n))},t.prototype.renderChunkTd=function(e,t,n,r){if("outerContent"in n)return n.outerContent;var o=this.props,i=this.state,a=i.forceYScrollbars,s=i.scrollerClientWidths,l=i.scrollerClientHeights,u=ja(o,e),c=za(o,e),d=o.liquid?a?"scroll":u?"auto":"hidden":"visible",p=e.key,f=Ga(e,n,{tableColGroupNode:t,tableMinWidth:"",clientWidth:o.collapsibleWidth||void 0===s[p]?null:s[p],clientHeight:void 0!==l[p]?l[p]:null,expandRows:e.expandRows,syncRowHeights:!1,rowSyncHeights:[],reportRowHeightChange:function(){}},r);return _o(r?"th":"td",{ref:n.elRef,role:"presentation"},_o("div",{className:"fc-scroller-harness"+(c?" fc-scroller-harness-liquid":"")},_o(Va,{ref:this.scrollerRefs.createRef(p),elRef:this.scrollerElRefs.createRef(p),overflowY:d,overflowX:o.liquid?"hidden":"visible",maxHeight:e.maxHeight,liquid:c,liquidIsAbsolute:!0},f)))},t.prototype._handleScrollerEl=function(e,t){var n=function(e,t){for(var n=0,r=e;n<r.length;n++){var o=r[n];if(o.key===t)return o}return null}(this.props.sections,t);n&&Fo(n.chunk.scrollerElRef,e)},t.prototype.componentDidMount=function(){this.handleSizing(),this.context.addResizeHandler(this.handleSizing)},t.prototype.componentDidUpdate=function(){this.handleSizing()},t.prototype.componentWillUnmount=function(){this.context.removeResizeHandler(this.handleSizing)},t.prototype.computeShrinkWidth=function(){return Xa(this.props.cols)?Ba(this.scrollerElRefs.getAll()):0},t.prototype.computeScrollerDims=function(){var e=po(),t=this.scrollerRefs,n=this.scrollerElRefs,r=!1,o={},i={};for(var a in t.currentMap){var s=t.currentMap[a];if(s&&s.needsYScrolling()){r=!0;break}}for(var l=0,u=this.props.sections;l<u.length;l++){a=u[l].key;var c=n.currentMap[a];if(c){var d=c.parentNode;o[a]=Math.floor(d.getBoundingClientRect().width-(r?e.y:0)),i[a]=Math.floor(d.getBoundingClientRect().height)}}return{forceYScrollbars:r,scrollerClientWidths:o,scrollerClientHeights:i}},t}(Uo);ts.addStateEquality({scrollerClientWidths:wt,scrollerClientHeights:wt});var ns=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.elRef=ko(),t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.options,r=e.seg,o=r.eventRange,i=o.ui,a={event:new xr(t,o.def,o.instance),view:t.viewApi,timeText:e.timeText,textColor:i.textColor,backgroundColor:i.backgroundColor,borderColor:i.borderColor,isDraggable:!e.disableDragging&&rr(r,t),isStartResizable:!e.disableResizing&&or(r,t),isEndResizable:!e.disableResizing&&ir(r),isMirror:Boolean(e.isDragging||e.isResizing||e.isDateSelecting),isStart:Boolean(r.isStart),isEnd:Boolean(r.isEnd),isPast:Boolean(e.isPast),isFuture:Boolean(e.isFuture),isToday:Boolean(e.isToday),isSelected:Boolean(e.isSelected),isDragging:Boolean(e.isDragging),isResizing:Boolean(e.isResizing)},s=lr(a).concat(i.classNames);return _o(Yo,{hookProps:a,classNames:n.eventClassNames,content:n.eventContent,defaultContent:e.defaultContent,didMount:n.eventDidMount,willUnmount:n.eventWillUnmount,elRef:this.elRef},(function(t,n,r,o){return e.children(t,s.concat(n),r,o,a)}))},t.prototype.componentDidMount=function(){$n(this.elRef.current,this.props.seg)},t.prototype.componentDidUpdate=function(e){var t=this.props.seg;t!==e.seg&&$n(this.elRef.current,t)},t}(Uo),rs=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=e.seg,o=t.options.eventTimeFormat||e.defaultTimeFormat,i=ar(n,o,t,e.defaultDisplayEventTime,e.defaultDisplayEventEnd);return _o(ns,{seg:n,timeText:i,disableDragging:e.disableDragging,disableResizing:e.disableResizing,defaultContent:e.defaultContent||os,isDragging:e.isDragging,isResizing:e.isResizing,isDateSelecting:e.isDateSelecting,isSelected:e.isSelected,isPast:e.isPast,isFuture:e.isFuture,isToday:e.isToday},(function(o,i,a,s,l){return _o("a",r({className:e.extraClassNames.concat(i).join(" "),style:{borderColor:l.borderColor,backgroundColor:l.backgroundColor},ref:o},cr(n,t)),_o("div",{className:"fc-event-main",ref:a,style:{color:l.textColor}},s),l.isStartResizable&&_o("div",{className:"fc-event-resizer fc-event-resizer-start"}),l.isEndResizable&&_o("div",{className:"fc-event-resizer fc-event-resizer-end"}))}))},t}(Uo);function os(e){return _o("div",{className:"fc-event-main-frame"},e.timeText&&_o("div",{className:"fc-event-time"},e.timeText),_o("div",{className:"fc-event-title-container"},_o("div",{className:"fc-event-title fc-sticky"},e.event.title||_o(xo,null," "))))}var is=function(e){return _o(Oo.Consumer,null,(function(t){var n=t.options,r={isAxis:e.isAxis,date:t.dateEnv.toDate(e.date),view:t.viewApi};return _o(Yo,{hookProps:r,classNames:n.nowIndicatorClassNames,content:n.nowIndicatorContent,didMount:n.nowIndicatorDidMount,willUnmount:n.nowIndicatorWillUnmount},e.children)}))},as=ln({day:"numeric"}),ss=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.options,r=ls({date:e.date,dateProfile:e.dateProfile,todayRange:e.todayRange,showDayNumber:e.showDayNumber,extraProps:e.extraHookProps,viewApi:t.viewApi,dateEnv:t.dateEnv});return _o(Xo,{hookProps:r,content:n.dayCellContent,defaultContent:e.defaultContent},e.children)},t}(Uo);function ls(e){var t=e.date,n=e.dateEnv,o=ro(t,e.todayRange,null,e.dateProfile);return r(r(r({date:n.toDate(t),view:e.viewApi},o),{dayNumberText:e.showDayNumber?n.format(t,as):""}),e.extraProps)}var us=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.refineHookProps=Yt(ls),t.normalizeClassNames=Jo(),t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.options,r=this.refineHookProps({date:e.date,dateProfile:e.dateProfile,todayRange:e.todayRange,showDayNumber:e.showDayNumber,extraProps:e.extraHookProps,viewApi:t.viewApi,dateEnv:t.dateEnv}),o=oo(r,t.theme).concat(r.isDisabled?[]:this.normalizeClassNames(n.dayCellClassNames,r)),i=r.isDisabled?{}:{"data-date":Bt(e.date)};return _o($o,{hookProps:r,didMount:n.dayCellDidMount,willUnmount:n.dayCellWillUnmount,elRef:e.elRef},(function(t){return e.children(t,o,i,r.isDisabled)}))},t}(Uo);function cs(e){return _o("div",{className:"fc-"+e})}var ds=function(e){return _o(ns,{defaultContent:ps,seg:e.seg,timeText:"",disableDragging:!0,disableResizing:!0,isDragging:!1,isResizing:!1,isDateSelecting:!1,isSelected:!1,isPast:e.isPast,isFuture:e.isFuture,isToday:e.isToday},(function(e,t,n,r,o){return _o("div",{ref:e,className:["fc-bg-event"].concat(t).join(" "),style:{backgroundColor:o.backgroundColor}},r)}))};function ps(e){return e.event.title&&_o("div",{className:"fc-event-title"},e.event.title)}var fs=function(e){return _o(Oo.Consumer,null,(function(t){var n=t.dateEnv,r=t.options,o=e.date,i=r.weekNumberFormat||e.defaultFormat,a=n.computeWeekNumber(o),s=n.format(o,i);return _o(Yo,{hookProps:{num:a,text:s,date:o},classNames:r.weekNumberClassNames,content:r.weekNumberContent,defaultContent:hs,didMount:r.weekNumberDidMount,willUnmount:r.weekNumberWillUnmount},e.children)}))};function hs(e){return e.text}var vs=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.state={titleId:xe()},t.handleRootEl=function(e){t.rootEl=e,t.props.elRef&&Fo(t.props.elRef,e)},t.handleDocumentMouseDown=function(e){var n=_e(e);t.rootEl.contains(n)||t.handleCloseClick()},t.handleDocumentKeyDown=function(e){"Escape"===e.key&&t.handleCloseClick()},t.handleCloseClick=function(){var e=t.props.onClose;e&&e()},t}return n(t,e),t.prototype.render=function(){var e=this.context,t=e.theme,n=e.options,o=this.props,i=this.state,a=["fc-popover",t.getClass("popover")].concat(o.extraClassNames||[]);return Io(_o("div",r({id:o.id,className:a.join(" "),"aria-labelledby":i.titleId},o.extraAttrs,{ref:this.handleRootEl}),_o("div",{className:"fc-popover-header "+t.getClass("popoverHeader")},_o("span",{className:"fc-popover-title",id:i.titleId},o.title),_o("span",{className:"fc-popover-close "+t.getIconClass("close"),title:n.closeHint,onClick:this.handleCloseClick})),_o("div",{className:"fc-popover-body "+t.getClass("popoverContent")},o.children)),o.parentEl)},t.prototype.componentDidMount=function(){document.addEventListener("mousedown",this.handleDocumentMouseDown),document.addEventListener("keydown",this.handleDocumentKeyDown),this.updateSize()},t.prototype.componentWillUnmount=function(){document.removeEventListener("mousedown",this.handleDocumentMouseDown),document.removeEventListener("keydown",this.handleDocumentKeyDown)},t.prototype.updateSize=function(){var e=this.context.isRtl,t=this.props,n=t.alignmentEl,r=t.alignGridTop,o=this.rootEl,i=function(e){for(var t=mo(e),n=e.getBoundingClientRect(),r=0,o=t;r<o.length;r++){var i=Xr(n,o[r].getBoundingClientRect());if(!i)return null;n=i}return n}(n);if(i){var a=o.getBoundingClientRect(),s=r?Se(n,".fc-scrollgrid").getBoundingClientRect().top:i.top,l=e?i.right-a.width:i.left;s=Math.max(s,10),l=Math.min(l,document.documentElement.clientWidth-10-a.width),l=Math.max(l,10);var u=o.offsetParent.getBoundingClientRect();we(o,{top:s-u.top,left:l-u.left})}},t}(Uo),gs=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.handleRootEl=function(e){t.rootEl=e,e?t.context.registerInteractiveComponent(t,{el:e,useEventCenter:!1}):t.context.unregisterInteractiveComponent(t)},t}return n(t,e),t.prototype.render=function(){var e=this.context,t=e.options,n=e.dateEnv,r=this.props,o=r.startDate,i=r.todayRange,a=r.dateProfile,s=n.format(o,t.dayPopoverFormat);return _o(us,{date:o,dateProfile:a,todayRange:i,elRef:this.handleRootEl},(function(e,t,n){return _o(vs,{elRef:e,id:r.id,title:s,extraClassNames:["fc-more-popover"].concat(t),extraAttrs:n,parentEl:r.parentEl,alignmentEl:r.alignmentEl,alignGridTop:r.alignGridTop,onClose:r.onClose},_o(ss,{date:o,dateProfile:a,todayRange:i},(function(e,t){return t&&_o("div",{className:"fc-more-popover-misc",ref:e},t)})),r.children)}))},t.prototype.queryHit=function(e,t,n,o){var i=this.rootEl,a=this.props;return e>=0&&e<n&&t>=0&&t<o?{dateProfile:a.dateProfile,dateSpan:r({allDay:!0,range:{start:a.startDate,end:a.endDate}},a.extraDateSpan),dayEl:i,rect:{left:0,top:0,right:n,bottom:o},layer:1}:null},t}(Bo),ms=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.linkElRef=ko(),t.state={isPopoverOpen:!1,popoverId:xe()},t.handleClick=function(e){var n=t,r=n.props,o=n.context,i=o.options.moreLinkClick,a=Es(r).start;function s(e){var t=e.eventRange,n=t.def,r=t.instance,i=t.range;return{event:new xr(o,n,r),start:o.dateEnv.toDate(i.start),end:o.dateEnv.toDate(i.end),isStart:e.isStart,isEnd:e.isEnd}}"function"==typeof i&&(i=i({date:a,allDay:Boolean(r.allDayDate),allSegs:r.allSegs.map(s),hiddenSegs:r.hiddenSegs.map(s),jsEvent:e,view:o.viewApi})),i&&"popover"!==i?"string"==typeof i&&o.calendarApi.zoomTo(a,i):t.setState({isPopoverOpen:!0})},t.handlePopoverClose=function(){t.setState({isPopoverOpen:!1})},t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=this.state;return _o(Oo.Consumer,null,(function(r){var o=r.viewApi,i=r.options,a=r.calendarApi,s=i.moreLinkText,l=t.moreCnt,u=Es(t),c="function"==typeof s?s.call(a,l):"+"+l+" "+s,d=Xe(i.moreLinkHint,[l],c),p={num:l,shortText:"+"+l,text:c,view:o};return _o(xo,null,Boolean(t.moreCnt)&&_o(Yo,{elRef:e.linkElRef,hookProps:p,classNames:i.moreLinkClassNames,content:i.moreLinkContent,defaultContent:t.defaultContent||ys,didMount:i.moreLinkDidMount,willUnmount:i.moreLinkWillUnmount},(function(r,o,i,a){return t.children(r,["fc-more-link"].concat(o),i,a,e.handleClick,d,n.isPopoverOpen,n.isPopoverOpen?n.popoverId:"")})),n.isPopoverOpen&&_o(gs,{id:n.popoverId,startDate:u.start,endDate:u.end,dateProfile:t.dateProfile,todayRange:t.todayRange,extraDateSpan:t.extraDateSpan,parentEl:e.parentEl,alignmentEl:t.alignmentElRef.current,alignGridTop:t.alignGridTop,onClose:e.handlePopoverClose},t.popoverContent()))}))},t.prototype.componentDidMount=function(){this.updateParentEl()},t.prototype.componentDidUpdate=function(){this.updateParentEl()},t.prototype.updateParentEl=function(){this.linkElRef.current&&(this.parentEl=Se(this.linkElRef.current,".fc-view-harness"))},t}(Uo);function ys(e){return e.text}function Es(e){if(e.allDayDate)return{start:e.allDayDate,end:tt(e.allDayDate,1)};var t,n=e.hiddenSegs;return{start:Ss(n),end:(t=n,t.reduce(Ds).eventRange.range.end)}}function Ss(e){return e.reduce(bs).eventRange.range.start}function bs(e,t){return e.eventRange.range.start<t.eventRange.range.start?e:t}function Ds(e,t){return e.eventRange.range.end>t.eventRange.range.end?e:t}var Cs=function(e){function t(t,n){void 0===n&&(n={});var o=e.call(this)||this;return o.isRendering=!1,o.isRendered=!1,o.currentClassNames=[],o.customContentRenderId=0,o.handleAction=function(e){switch(e.type){case"SET_EVENT_DRAG":case"SET_EVENT_RESIZE":o.renderRunner.tryDrain()}},o.handleData=function(e){o.currentData=e,o.renderRunner.request(e.calendarOptions.rerenderDelay)},o.handleRenderRequest=function(){if(o.isRendering){o.isRendered=!0;var e=o.currentData;To(_o(ga,{options:e.calendarOptions,theme:e.theme,emitter:e.emitter},(function(t,n,i,a){return o.setClassNames(t),o.setHeight(n),_o(Zo.Provider,{value:o.customContentRenderId},_o(fa,r({isHeightAuto:i,forPrint:a},e)))})),o.el)}else o.isRendered&&(o.isRendered=!1,No(o.el),o.setClassNames([]),o.setHeight(""));Po()},o.el=t,o.renderRunner=new Mi(o.handleRenderRequest),new Ni({optionOverrides:n,calendarApi:o,onAction:o.handleAction,onData:o.handleData}),o}return n(t,e),Object.defineProperty(t.prototype,"view",{get:function(){return this.currentData.viewApi},enumerable:!1,configurable:!0}),t.prototype.render=function(){var e=this.isRendering;e?this.customContentRenderId+=1:this.isRendering=!0,this.renderRunner.request(),e&&this.updateSize()},t.prototype.destroy=function(){this.isRendering&&(this.isRendering=!1,this.renderRunner.request())},t.prototype.updateSize=function(){e.prototype.updateSize.call(this),Po()},t.prototype.batchRendering=function(e){this.renderRunner.pause("batchRendering"),e(),this.renderRunner.resume("batchRendering")},t.prototype.pauseRendering=function(){this.renderRunner.pause("pauseRendering")},t.prototype.resumeRendering=function(){this.renderRunner.resume("pauseRendering",!0)},t.prototype.resetOptions=function(e,t){this.currentDataManager.resetOptions(e,t)},t.prototype.setClassNames=function(e){if(!Gt(e,this.currentClassNames)){for(var t=this.el.classList,n=0,r=this.currentClassNames;n<r.length;n++){var o=r[n];t.remove(o)}for(var i=0,a=e;i<a.length;i++){o=a[i];t.add(o)}this.currentClassNames=e}},t.prototype.setHeight=function(e){Re(this.el,"height",e)},t}(kr);ia.touchMouseIgnoreWait=500;var ws=0,Rs=0,_s=!1,Ts=function(){function e(e){var t=this;this.subjectEl=null,this.selector="",this.handleSelector="",this.shouldIgnoreMove=!1,this.shouldWatchScroll=!0,this.isDragging=!1,this.isTouchDragging=!1,this.wasTouchScroll=!1,this.handleMouseDown=function(e){if(!t.shouldIgnoreMouse()&&function(e){return 0===e.button&&!e.ctrlKey}(e)&&t.tryStart(e)){var n=t.createEventFromMouse(e,!0);t.emitter.trigger("pointerdown",n),t.initScrollWatch(n),t.shouldIgnoreMove||document.addEventListener("mousemove",t.handleMouseMove),document.addEventListener("mouseup",t.handleMouseUp)}},this.handleMouseMove=function(e){var n=t.createEventFromMouse(e);t.recordCoords(n),t.emitter.trigger("pointermove",n)},this.handleMouseUp=function(e){document.removeEventListener("mousemove",t.handleMouseMove),document.removeEventListener("mouseup",t.handleMouseUp),t.emitter.trigger("pointerup",t.createEventFromMouse(e)),t.cleanup()},this.handleTouchStart=function(e){if(t.tryStart(e)){t.isTouchDragging=!0;var n=t.createEventFromTouch(e,!0);t.emitter.trigger("pointerdown",n),t.initScrollWatch(n);var r=e.target;t.shouldIgnoreMove||r.addEventListener("touchmove",t.handleTouchMove),r.addEventListener("touchend",t.handleTouchEnd),r.addEventListener("touchcancel",t.handleTouchEnd),window.addEventListener("scroll",t.handleTouchScroll,!0)}},this.handleTouchMove=function(e){var n=t.createEventFromTouch(e);t.recordCoords(n),t.emitter.trigger("pointermove",n)},this.handleTouchEnd=function(e){if(t.isDragging){var n=e.target;n.removeEventListener("touchmove",t.handleTouchMove),n.removeEventListener("touchend",t.handleTouchEnd),n.removeEventListener("touchcancel",t.handleTouchEnd),window.removeEventListener("scroll",t.handleTouchScroll,!0),t.emitter.trigger("pointerup",t.createEventFromTouch(e)),t.cleanup(),t.isTouchDragging=!1,ws+=1,setTimeout((function(){ws-=1}),ia.touchMouseIgnoreWait)}},this.handleTouchScroll=function(){t.wasTouchScroll=!0},this.handleScroll=function(e){if(!t.shouldIgnoreMove){var n=window.pageXOffset-t.prevScrollX+t.prevPageX,r=window.pageYOffset-t.prevScrollY+t.prevPageY;t.emitter.trigger("pointermove",{origEvent:e,isTouch:t.isTouchDragging,subjectEl:t.subjectEl,pageX:n,pageY:r,deltaX:n-t.origPageX,deltaY:r-t.origPageY})}},this.containerEl=e,this.emitter=new Eo,e.addEventListener("mousedown",this.handleMouseDown),e.addEventListener("touchstart",this.handleTouchStart,{passive:!0}),1===(Rs+=1)&&window.addEventListener("touchmove",ks,{passive:!1})}return e.prototype.destroy=function(){this.containerEl.removeEventListener("mousedown",this.handleMouseDown),this.containerEl.removeEventListener("touchstart",this.handleTouchStart,{passive:!0}),(Rs-=1)||window.removeEventListener("touchmove",ks,{passive:!1})},e.prototype.tryStart=function(e){var t=this.querySubjectEl(e),n=e.target;return!(!t||this.handleSelector&&!Se(n,this.handleSelector))&&(this.subjectEl=t,this.isDragging=!0,this.wasTouchScroll=!1,!0)},e.prototype.cleanup=function(){_s=!1,this.isDragging=!1,this.subjectEl=null,this.destroyScrollWatch()},e.prototype.querySubjectEl=function(e){return this.selector?Se(e.target,this.selector):this.containerEl},e.prototype.shouldIgnoreMouse=function(){return ws||this.isTouchDragging},e.prototype.cancelTouchScroll=function(){this.isDragging&&(_s=!0)},e.prototype.initScrollWatch=function(e){this.shouldWatchScroll&&(this.recordCoords(e),window.addEventListener("scroll",this.handleScroll,!0))},e.prototype.recordCoords=function(e){this.shouldWatchScroll&&(this.prevPageX=e.pageX,this.prevPageY=e.pageY,this.prevScrollX=window.pageXOffset,this.prevScrollY=window.pageYOffset)},e.prototype.destroyScrollWatch=function(){this.shouldWatchScroll&&window.removeEventListener("scroll",this.handleScroll,!0)},e.prototype.createEventFromMouse=function(e,t){var n=0,r=0;return t?(this.origPageX=e.pageX,this.origPageY=e.pageY):(n=e.pageX-this.origPageX,r=e.pageY-this.origPageY),{origEvent:e,isTouch:!1,subjectEl:this.subjectEl,pageX:e.pageX,pageY:e.pageY,deltaX:n,deltaY:r}},e.prototype.createEventFromTouch=function(e,t){var n,r,o=e.touches,i=0,a=0;return o&&o.length?(n=o[0].pageX,r=o[0].pageY):(n=e.pageX,r=e.pageY),t?(this.origPageX=n,this.origPageY=r):(i=n-this.origPageX,a=r-this.origPageY),{origEvent:e,isTouch:!0,subjectEl:this.subjectEl,pageX:n,pageY:r,deltaX:i,deltaY:a}},e}();function ks(e){_s&&e.preventDefault()}var xs=function(){function e(){this.isVisible=!1,this.sourceEl=null,this.mirrorEl=null,this.sourceElRect=null,this.parentNode=document.body,this.zIndex=9999,this.revertDuration=0}return e.prototype.start=function(e,t,n){this.sourceEl=e,this.sourceElRect=this.sourceEl.getBoundingClientRect(),this.origScreenX=t-window.pageXOffset,this.origScreenY=n-window.pageYOffset,this.deltaX=0,this.deltaY=0,this.updateElPosition()},e.prototype.handleMove=function(e,t){this.deltaX=e-window.pageXOffset-this.origScreenX,this.deltaY=t-window.pageYOffset-this.origScreenY,this.updateElPosition()},e.prototype.setIsVisible=function(e){e?this.isVisible||(this.mirrorEl&&(this.mirrorEl.style.display=""),this.isVisible=e,this.updateElPosition()):this.isVisible&&(this.mirrorEl&&(this.mirrorEl.style.display="none"),this.isVisible=e)},e.prototype.stop=function(e,t){var n=this,r=function(){n.cleanup(),t()};e&&this.mirrorEl&&this.isVisible&&this.revertDuration&&(this.deltaX||this.deltaY)?this.doRevertAnimation(r,this.revertDuration):setTimeout(r,0)},e.prototype.doRevertAnimation=function(e,t){var n=this.mirrorEl,r=this.sourceEl.getBoundingClientRect();n.style.transition="top "+t+"ms,left "+t+"ms",we(n,{left:r.left,top:r.top}),Ne(n,(function(){n.style.transition="",e()}))},e.prototype.cleanup=function(){this.mirrorEl&&(Ee(this.mirrorEl),this.mirrorEl=null),this.sourceEl=null},e.prototype.updateElPosition=function(){this.sourceEl&&this.isVisible&&we(this.getMirrorEl(),{left:this.sourceElRect.left+this.deltaX,top:this.sourceElRect.top+this.deltaY})},e.prototype.getMirrorEl=function(){var e=this.sourceElRect,t=this.mirrorEl;return t||((t=this.mirrorEl=this.sourceEl.cloneNode(!0)).classList.add("fc-unselectable"),t.classList.add("fc-event-dragging"),we(t,{position:"fixed",zIndex:this.zIndex,visibility:"",boxSizing:"border-box",width:e.right-e.left,height:e.bottom-e.top,right:"auto",bottom:"auto",margin:0}),this.parentNode.appendChild(t)),t},e}(),Ms=function(e){function t(t,n){var r=e.call(this)||this;return r.handleScroll=function(){r.scrollTop=r.scrollController.getScrollTop(),r.scrollLeft=r.scrollController.getScrollLeft(),r.handleScrollChange()},r.scrollController=t,r.doesListening=n,r.scrollTop=r.origScrollTop=t.getScrollTop(),r.scrollLeft=r.origScrollLeft=t.getScrollLeft(),r.scrollWidth=t.getScrollWidth(),r.scrollHeight=t.getScrollHeight(),r.clientWidth=t.getClientWidth(),r.clientHeight=t.getClientHeight(),r.clientRect=r.computeClientRect(),r.doesListening&&r.getEventTarget().addEventListener("scroll",r.handleScroll),r}return n(t,e),t.prototype.destroy=function(){this.doesListening&&this.getEventTarget().removeEventListener("scroll",this.handleScroll)},t.prototype.getScrollTop=function(){return this.scrollTop},t.prototype.getScrollLeft=function(){return this.scrollLeft},t.prototype.setScrollTop=function(e){this.scrollController.setScrollTop(e),this.doesListening||(this.scrollTop=Math.max(Math.min(e,this.getMaxScrollTop()),0),this.handleScrollChange())},t.prototype.setScrollLeft=function(e){this.scrollController.setScrollLeft(e),this.doesListening||(this.scrollLeft=Math.max(Math.min(e,this.getMaxScrollLeft()),0),this.handleScrollChange())},t.prototype.getClientWidth=function(){return this.clientWidth},t.prototype.getClientHeight=function(){return this.clientHeight},t.prototype.getScrollWidth=function(){return this.scrollWidth},t.prototype.getScrollHeight=function(){return this.scrollHeight},t.prototype.handleScrollChange=function(){},t}(bo),Is=function(e){function t(t,n){return e.call(this,new Do(t),n)||this}return n(t,e),t.prototype.getEventTarget=function(){return this.scrollController.el},t.prototype.computeClientRect=function(){return vo(this.scrollController.el)},t}(Ms),Ps=function(e){function t(t){return e.call(this,new Co,t)||this}return n(t,e),t.prototype.getEventTarget=function(){return window},t.prototype.computeClientRect=function(){return{left:this.scrollLeft,right:this.scrollLeft+this.clientWidth,top:this.scrollTop,bottom:this.scrollTop+this.clientHeight}},t.prototype.handleScrollChange=function(){this.clientRect=this.computeClientRect()},t}(Ms),Ns="function"==typeof performance?performance.now:Date.now,Hs=function(){function e(){var e=this;this.isEnabled=!0,this.scrollQuery=[window,".fc-scroller"],this.edgeThreshold=50,this.maxVelocity=300,this.pointerScreenX=null,this.pointerScreenY=null,this.isAnimating=!1,this.scrollCaches=null,this.everMovedUp=!1,this.everMovedDown=!1,this.everMovedLeft=!1,this.everMovedRight=!1,this.animate=function(){if(e.isAnimating){var t=e.computeBestEdge(e.pointerScreenX+window.pageXOffset,e.pointerScreenY+window.pageYOffset);if(t){var n=Ns();e.handleSide(t,(n-e.msSinceRequest)/1e3),e.requestAnimation(n)}else e.isAnimating=!1}}}return e.prototype.start=function(e,t,n){this.isEnabled&&(this.scrollCaches=this.buildCaches(n),this.pointerScreenX=null,this.pointerScreenY=null,this.everMovedUp=!1,this.everMovedDown=!1,this.everMovedLeft=!1,this.everMovedRight=!1,this.handleMove(e,t))},e.prototype.handleMove=function(e,t){if(this.isEnabled){var n=e-window.pageXOffset,r=t-window.pageYOffset,o=null===this.pointerScreenY?0:r-this.pointerScreenY,i=null===this.pointerScreenX?0:n-this.pointerScreenX;o<0?this.everMovedUp=!0:o>0&&(this.everMovedDown=!0),i<0?this.everMovedLeft=!0:i>0&&(this.everMovedRight=!0),this.pointerScreenX=n,this.pointerScreenY=r,this.isAnimating||(this.isAnimating=!0,this.requestAnimation(Ns()))}},e.prototype.stop=function(){if(this.isEnabled){this.isAnimating=!1;for(var e=0,t=this.scrollCaches;e<t.length;e++){t[e].destroy()}this.scrollCaches=null}},e.prototype.requestAnimation=function(e){this.msSinceRequest=e,requestAnimationFrame(this.animate)},e.prototype.handleSide=function(e,t){var n=e.scrollCache,r=this.edgeThreshold,o=r-e.distance,i=o*o/(r*r)*this.maxVelocity*t,a=1;switch(e.name){case"left":a=-1;case"right":n.setScrollLeft(n.getScrollLeft()+i*a);break;case"top":a=-1;case"bottom":n.setScrollTop(n.getScrollTop()+i*a)}},e.prototype.computeBestEdge=function(e,t){for(var n=this.edgeThreshold,r=null,o=0,i=this.scrollCaches;o<i.length;o++){var a=i[o],s=a.clientRect,l=e-s.left,u=s.right-e,c=t-s.top,d=s.bottom-t;l>=0&&u>=0&&c>=0&&d>=0&&(c<=n&&this.everMovedUp&&a.canScrollUp()&&(!r||r.distance>c)&&(r={scrollCache:a,name:"top",distance:c}),d<=n&&this.everMovedDown&&a.canScrollDown()&&(!r||r.distance>d)&&(r={scrollCache:a,name:"bottom",distance:d}),l<=n&&this.everMovedLeft&&a.canScrollLeft()&&(!r||r.distance>l)&&(r={scrollCache:a,name:"left",distance:l}),u<=n&&this.everMovedRight&&a.canScrollRight()&&(!r||r.distance>u)&&(r={scrollCache:a,name:"right",distance:u}))}return r},e.prototype.buildCaches=function(e){return this.queryScrollEls(e).map((function(e){return e===window?new Ps(!1):new Is(e,!1)}))},e.prototype.queryScrollEls=function(e){for(var t=[],n=0,r=this.scrollQuery;n<r.length;n++){var o=r[n];"object"==typeof o?t.push(o):t.push.apply(t,Array.prototype.slice.call(Te(e).querySelectorAll(o)))}return t},e}(),Os=function(e){function t(t,n){var r=e.call(this,t)||this;r.containerEl=t,r.delay=null,r.minDistance=0,r.touchScrollAllowed=!0,r.mirrorNeedsRevert=!1,r.isInteracting=!1,r.isDragging=!1,r.isDelayEnded=!1,r.isDistanceSurpassed=!1,r.delayTimeoutId=null,r.onPointerDown=function(e){r.isDragging||(r.isInteracting=!0,r.isDelayEnded=!1,r.isDistanceSurpassed=!1,Ve(document.body),Be(document.body),e.isTouch||e.origEvent.preventDefault(),r.emitter.trigger("pointerdown",e),r.isInteracting&&!r.pointer.shouldIgnoreMove&&(r.mirror.setIsVisible(!1),r.mirror.start(e.subjectEl,e.pageX,e.pageY),r.startDelay(e),r.minDistance||r.handleDistanceSurpassed(e)))},r.onPointerMove=function(e){if(r.isInteracting){if(r.emitter.trigger("pointermove",e),!r.isDistanceSurpassed){var t=r.minDistance,n=e.deltaX,o=e.deltaY;n*n+o*o>=t*t&&r.handleDistanceSurpassed(e)}r.isDragging&&("scroll"!==e.origEvent.type&&(r.mirror.handleMove(e.pageX,e.pageY),r.autoScroller.handleMove(e.pageX,e.pageY)),r.emitter.trigger("dragmove",e))}},r.onPointerUp=function(e){r.isInteracting&&(r.isInteracting=!1,Fe(document.body),ze(document.body),r.emitter.trigger("pointerup",e),r.isDragging&&(r.autoScroller.stop(),r.tryStopDrag(e)),r.delayTimeoutId&&(clearTimeout(r.delayTimeoutId),r.delayTimeoutId=null))};var o=r.pointer=new Ts(t);return o.emitter.on("pointerdown",r.onPointerDown),o.emitter.on("pointermove",r.onPointerMove),o.emitter.on("pointerup",r.onPointerUp),n&&(o.selector=n),r.mirror=new xs,r.autoScroller=new Hs,r}return n(t,e),t.prototype.destroy=function(){this.pointer.destroy(),this.onPointerUp({})},t.prototype.startDelay=function(e){var t=this;"number"==typeof this.delay?this.delayTimeoutId=setTimeout((function(){t.delayTimeoutId=null,t.handleDelayEnd(e)}),this.delay):this.handleDelayEnd(e)},t.prototype.handleDelayEnd=function(e){this.isDelayEnded=!0,this.tryStartDrag(e)},t.prototype.handleDistanceSurpassed=function(e){this.isDistanceSurpassed=!0,this.tryStartDrag(e)},t.prototype.tryStartDrag=function(e){this.isDelayEnded&&this.isDistanceSurpassed&&(this.pointer.wasTouchScroll&&!this.touchScrollAllowed||(this.isDragging=!0,this.mirrorNeedsRevert=!1,this.autoScroller.start(e.pageX,e.pageY,this.containerEl),this.emitter.trigger("dragstart",e),!1===this.touchScrollAllowed&&this.pointer.cancelTouchScroll()))},t.prototype.tryStopDrag=function(e){this.mirror.stop(this.mirrorNeedsRevert,this.stopDrag.bind(this,e))},t.prototype.stopDrag=function(e){this.isDragging=!1,this.emitter.trigger("dragend",e)},t.prototype.setIgnoreMove=function(e){this.pointer.shouldIgnoreMove=e},t.prototype.setMirrorIsVisible=function(e){this.mirror.setIsVisible(e)},t.prototype.setMirrorNeedsRevert=function(e){this.mirrorNeedsRevert=e},t.prototype.setAutoScrollEnabled=function(e){this.autoScroller.isEnabled=e},t}(oa),As=function(){function e(e){this.origRect=go(e),this.scrollCaches=mo(e).map((function(e){return new Is(e,!0)}))}return e.prototype.destroy=function(){for(var e=0,t=this.scrollCaches;e<t.length;e++){t[e].destroy()}},e.prototype.computeLeft=function(){for(var e=this.origRect.left,t=0,n=this.scrollCaches;t<n.length;t++){var r=n[t];e+=r.origScrollLeft-r.getScrollLeft()}return e},e.prototype.computeTop=function(){for(var e=this.origRect.top,t=0,n=this.scrollCaches;t<n.length;t++){var r=n[t];e+=r.origScrollTop-r.getScrollTop()}return e},e.prototype.isWithinClipping=function(e,t){for(var n,r,o={left:e,top:t},i=0,a=this.scrollCaches;i<a.length;i++){var s=a[i];if(n=s.getEventTarget(),r=void 0,"HTML"!==(r=n.tagName)&&"BODY"!==r&&!Zr(o,s.clientRect))return!1}return!0},e}();var Ls=function(){function e(e,t){var n=this;this.useSubjectCenter=!1,this.requireInitial=!0,this.initialHit=null,this.movingHit=null,this.finalHit=null,this.handlePointerDown=function(e){var t=n.dragging;n.initialHit=null,n.movingHit=null,n.finalHit=null,n.prepareHits(),n.processFirstCoord(e),n.initialHit||!n.requireInitial?(t.setIgnoreMove(!1),n.emitter.trigger("pointerdown",e)):t.setIgnoreMove(!0)},this.handleDragStart=function(e){n.emitter.trigger("dragstart",e),n.handleMove(e,!0)},this.handleDragMove=function(e){n.emitter.trigger("dragmove",e),n.handleMove(e)},this.handlePointerUp=function(e){n.releaseHits(),n.emitter.trigger("pointerup",e)},this.handleDragEnd=function(e){n.movingHit&&n.emitter.trigger("hitupdate",null,!0,e),n.finalHit=n.movingHit,n.movingHit=null,n.emitter.trigger("dragend",e)},this.droppableStore=t,e.emitter.on("pointerdown",this.handlePointerDown),e.emitter.on("dragstart",this.handleDragStart),e.emitter.on("dragmove",this.handleDragMove),e.emitter.on("pointerup",this.handlePointerUp),e.emitter.on("dragend",this.handleDragEnd),this.dragging=e,this.emitter=new Eo}return e.prototype.processFirstCoord=function(e){var t,n={left:e.pageX,top:e.pageY},r=n,o=e.subjectEl;o instanceof HTMLElement&&(r=Kr(r,t=go(o)));var i=this.initialHit=this.queryHitForOffset(r.left,r.top);if(i){if(this.useSubjectCenter&&t){var a=Xr(t,i.rect);a&&(r=$r(a))}this.coordAdjust=Jr(r,n)}else this.coordAdjust={left:0,top:0}},e.prototype.handleMove=function(e,t){var n=this.queryHitForOffset(e.pageX+this.coordAdjust.left,e.pageY+this.coordAdjust.top);!t&&Us(this.movingHit,n)||(this.movingHit=n,this.emitter.trigger("hitupdate",n,!1,e))},e.prototype.prepareHits=function(){this.offsetTrackers=bt(this.droppableStore,(function(e){return e.component.prepareHits(),new As(e.el)}))},e.prototype.releaseHits=function(){var e=this.offsetTrackers;for(var t in e)e[t].destroy();this.offsetTrackers={}},e.prototype.queryHitForOffset=function(e,t){var n=this.droppableStore,r=this.offsetTrackers,o=null;for(var i in n){var a=n[i].component,s=r[i];if(s&&s.isWithinClipping(e,t)){var l=s.computeLeft(),u=s.computeTop(),c=e-l,d=t-u,p=s.origRect,f=p.right-p.left,h=p.bottom-p.top;if(c>=0&&c<f&&d>=0&&d<h){var v=a.queryHit(c,d,f,h);v&&Yn(v.dateProfile.activeRange,v.dateSpan.range)&&(!o||v.layer>o.layer)&&(v.componentId=i,v.context=a.context,v.rect.left+=l,v.rect.right+=l,v.rect.top+=u,v.rect.bottom+=u,o=v)}}}return o},e}();function Us(e,t){return!e&&!t||Boolean(e)===Boolean(t)&&fr(e.dateSpan,t.dateSpan)}function Ws(e,t){for(var n,o,i={},a=0,s=t.pluginHooks.datePointTransforms;a<s.length;a++){var l=s[a];r(i,l(e,t))}return r(i,(n=e,{date:(o=t.dateEnv).toDate(n.range.start),dateStr:o.formatIso(n.range.start,{omitTime:n.allDay}),allDay:n.allDay})),i}var Vs=function(e){function t(t){var n=e.call(this,t)||this;n.handlePointerDown=function(e){var t=n.dragging,r=e.origEvent.target;t.setIgnoreMove(!n.component.isValidDateDownEl(r))},n.handleDragEnd=function(e){var t=n.component;if(!n.dragging.pointer.wasTouchScroll){var o=n.hitDragging,i=o.initialHit,a=o.finalHit;if(i&&a&&Us(i,a)){var s=t.context,l=r(r({},Ws(i.dateSpan,s)),{dayEl:i.dayEl,jsEvent:e.origEvent,view:s.viewApi||s.calendarApi.view});s.emitter.trigger("dateClick",l)}}},n.dragging=new Os(t.el),n.dragging.autoScroller.isEnabled=!1;var o=n.hitDragging=new Ls(n.dragging,na(t));return o.emitter.on("pointerdown",n.handlePointerDown),o.emitter.on("dragend",n.handleDragEnd),n}return n(t,e),t.prototype.destroy=function(){this.dragging.destroy()},t}(ea),Fs=function(e){function t(t){var n=e.call(this,t)||this;n.dragSelection=null,n.handlePointerDown=function(e){var t=n,r=t.component,o=t.dragging,i=r.context.options.selectable&&r.isValidDateDownEl(e.origEvent.target);o.setIgnoreMove(!i),o.delay=e.isTouch?function(e){var t=e.context.options,n=t.selectLongPressDelay;null==n&&(n=t.longPressDelay);return n}(r):null},n.handleDragStart=function(e){n.component.context.calendarApi.unselect(e)},n.handleHitUpdate=function(e,t){var o=n.component.context,i=null,a=!1;if(e){var s=n.hitDragging.initialHit;e.componentId===s.componentId&&n.isHitComboAllowed&&!n.isHitComboAllowed(s,e)||(i=function(e,t,n){var o=e.dateSpan,i=t.dateSpan,a=[o.range.start,o.range.end,i.range.start,i.range.end];a.sort(Ke);for(var s={},l=0,u=n;l<u.length;l++){var c=(0,u[l])(e,t);if(!1===c)return null;c&&r(s,c)}return s.range={start:a[0],end:a[3]},s.allDay=o.allDay,s}(s,e,o.pluginHooks.dateSelectionTransformers)),i&&Pa(i,e.dateProfile,o)||(a=!0,i=null)}i?o.dispatch({type:"SELECT_DATES",selection:i}):t||o.dispatch({type:"UNSELECT_DATES"}),a?Ue():We(),t||(n.dragSelection=i)},n.handlePointerUp=function(e){n.dragSelection&&(mr(n.dragSelection,e,n.component.context),n.dragSelection=null)};var o=t.component.context.options,i=n.dragging=new Os(t.el);i.touchScrollAllowed=!1,i.minDistance=o.selectMinDistance||0,i.autoScroller.isEnabled=o.dragScroll;var a=n.hitDragging=new Ls(n.dragging,na(t));return a.emitter.on("pointerdown",n.handlePointerDown),a.emitter.on("dragstart",n.handleDragStart),a.emitter.on("hitupdate",n.handleHitUpdate),a.emitter.on("pointerup",n.handlePointerUp),n}return n(t,e),t.prototype.destroy=function(){this.dragging.destroy()},t}(ea);var Bs=function(e){function t(n){var o=e.call(this,n)||this;o.subjectEl=null,o.subjectSeg=null,o.isDragging=!1,o.eventRange=null,o.relevantEvents=null,o.receivingContext=null,o.validMutation=null,o.mutatedRelevantEvents=null,o.handlePointerDown=function(e){var t=e.origEvent.target,n=o,r=n.component,i=n.dragging,a=i.mirror,s=r.context.options,l=r.context;o.subjectEl=e.subjectEl;var u=o.subjectSeg=Jn(e.subjectEl),c=(o.eventRange=u.eventRange).instance.instanceId;o.relevantEvents=bn(l.getCurrentData().eventStore,c),i.minDistance=e.isTouch?0:s.eventDragMinDistance,i.delay=e.isTouch&&c!==r.props.eventSelection?function(e){var t=e.context.options,n=t.eventLongPressDelay;null==n&&(n=t.longPressDelay);return n}(r):null,s.fixedMirrorParent?a.parentNode=s.fixedMirrorParent:a.parentNode=Se(t,".fc"),a.revertDuration=s.dragRevertDuration;var d=r.isValidSegDownEl(t)&&!Se(t,".fc-event-resizer");i.setIgnoreMove(!d),o.isDragging=d&&e.subjectEl.classList.contains("fc-event-draggable")},o.handleDragStart=function(e){var t=o.component.context,n=o.eventRange,r=n.instance.instanceId;e.isTouch?r!==o.component.props.eventSelection&&t.dispatch({type:"SELECT_EVENT",eventInstanceId:r}):t.dispatch({type:"UNSELECT_EVENT"}),o.isDragging&&(t.calendarApi.unselect(e),t.emitter.trigger("eventDragStart",{el:o.subjectEl,event:new xr(t,n.def,n.instance),jsEvent:e.origEvent,view:t.viewApi}))},o.handleHitUpdate=function(e,t){if(o.isDragging){var n=o.relevantEvents,r=o.hitDragging.initialHit,i=o.component.context,a=null,s=null,l=null,u=!1,c={affectedEvents:n,mutatedEvents:{defs:{},instances:{}},isEvent:!0};if(e){var d=(a=e.context).options;i===a||d.editable&&d.droppable?(s=function(e,t,n){var r=e.dateSpan,o=t.dateSpan,i=r.range.start,a=o.range.start,s={};r.allDay!==o.allDay&&(s.allDay=o.allDay,s.hasEnd=t.context.options.allDayMaintainDuration,o.allDay&&(i=lt(i)));var l=Fn(i,a,e.context.dateEnv,e.componentId===t.componentId?e.largeUnit:null);l.milliseconds&&(s.allDay=!1);for(var u={datesDelta:l,standardProps:s},c=0,d=n;c<d.length;c++){(0,d[c])(u,e,t)}return u}(r,e,a.getCurrentData().pluginHooks.eventDragMutationMassagers))&&(l=Sr(n,a.getCurrentData().eventUiBases,s,a),c.mutatedEvents=l,Ia(c,e.dateProfile,a)||(u=!0,s=null,l=null,c.mutatedEvents={defs:{},instances:{}})):a=null}o.displayDrag(a,c),u?Ue():We(),t||(i===a&&Us(r,e)&&(s=null),o.dragging.setMirrorNeedsRevert(!s),o.dragging.setMirrorIsVisible(!e||!Te(o.subjectEl).querySelector(".fc-event-mirror")),o.receivingContext=a,o.validMutation=s,o.mutatedRelevantEvents=l)}},o.handlePointerUp=function(){o.isDragging||o.cleanup()},o.handleDragEnd=function(e){if(o.isDragging){var t=o.component.context,n=t.viewApi,i=o,a=i.receivingContext,s=i.validMutation,l=o.eventRange.def,u=o.eventRange.instance,c=new xr(t,l,u),d=o.relevantEvents,p=o.mutatedRelevantEvents,f=o.hitDragging.finalHit;if(o.clearDrag(),t.emitter.trigger("eventDragStop",{el:o.subjectEl,event:c,jsEvent:e.origEvent,view:n}),s){if(a===t){var h=new xr(t,p.defs[l.defId],u?p.instances[u.instanceId]:null);t.dispatch({type:"MERGE_EVENTS",eventStore:p});for(var v={oldEvent:c,event:h,relatedEvents:Ir(p,t,u),revert:function(){t.dispatch({type:"MERGE_EVENTS",eventStore:d})}},g={},m=0,y=t.getCurrentData().pluginHooks.eventDropTransformers;m<y.length;m++){var E=y[m];r(g,E(s,t))}t.emitter.trigger("eventDrop",r(r(r({},v),g),{el:e.subjectEl,delta:s.datesDelta,jsEvent:e.origEvent,view:n})),t.emitter.trigger("eventChange",v)}else if(a){var S={event:c,relatedEvents:Ir(d,t,u),revert:function(){t.dispatch({type:"MERGE_EVENTS",eventStore:d})}};t.emitter.trigger("eventLeave",r(r({},S),{draggedEl:e.subjectEl,view:n})),t.dispatch({type:"REMOVE_EVENTS",eventStore:d}),t.emitter.trigger("eventRemove",S);var b=p.defs[l.defId],D=p.instances[u.instanceId],C=new xr(a,b,D);a.dispatch({type:"MERGE_EVENTS",eventStore:p});var w={event:C,relatedEvents:Ir(p,a,D),revert:function(){a.dispatch({type:"REMOVE_EVENTS",eventStore:p})}};a.emitter.trigger("eventAdd",w),e.isTouch&&a.dispatch({type:"SELECT_EVENT",eventInstanceId:u.instanceId}),a.emitter.trigger("drop",r(r({},Ws(f.dateSpan,a)),{draggedEl:e.subjectEl,jsEvent:e.origEvent,view:f.context.viewApi})),a.emitter.trigger("eventReceive",r(r({},w),{draggedEl:e.subjectEl,view:f.context.viewApi}))}}else t.emitter.trigger("_noEventDrop")}o.cleanup()};var i=o.component.context.options,a=o.dragging=new Os(n.el);a.pointer.selector=t.SELECTOR,a.touchScrollAllowed=!1,a.autoScroller.isEnabled=i.dragScroll;var s=o.hitDragging=new Ls(o.dragging,ra);return s.useSubjectCenter=n.useEventCenter,s.emitter.on("pointerdown",o.handlePointerDown),s.emitter.on("dragstart",o.handleDragStart),s.emitter.on("hitupdate",o.handleHitUpdate),s.emitter.on("pointerup",o.handlePointerUp),s.emitter.on("dragend",o.handleDragEnd),o}return n(t,e),t.prototype.destroy=function(){this.dragging.destroy()},t.prototype.displayDrag=function(e,t){var n=this.component.context,r=this.receivingContext;r&&r!==e&&(r===n?r.dispatch({type:"SET_EVENT_DRAG",state:{affectedEvents:t.affectedEvents,mutatedEvents:{defs:{},instances:{}},isEvent:!0}}):r.dispatch({type:"UNSET_EVENT_DRAG"})),e&&e.dispatch({type:"SET_EVENT_DRAG",state:t})},t.prototype.clearDrag=function(){var e=this.component.context,t=this.receivingContext;t&&t.dispatch({type:"UNSET_EVENT_DRAG"}),e!==t&&e.dispatch({type:"UNSET_EVENT_DRAG"})},t.prototype.cleanup=function(){this.subjectSeg=null,this.isDragging=!1,this.eventRange=null,this.relevantEvents=null,this.receivingContext=null,this.validMutation=null,this.mutatedRelevantEvents=null},t.SELECTOR=".fc-event-draggable, .fc-event-resizable",t}(ea);var zs=function(e){function t(t){var n=e.call(this,t)||this;n.draggingSegEl=null,n.draggingSeg=null,n.eventRange=null,n.relevantEvents=null,n.validMutation=null,n.mutatedRelevantEvents=null,n.handlePointerDown=function(e){var t=n.component,r=Jn(n.querySegEl(e)),o=n.eventRange=r.eventRange;n.dragging.minDistance=t.context.options.eventDragMinDistance,n.dragging.setIgnoreMove(!n.component.isValidSegDownEl(e.origEvent.target)||e.isTouch&&n.component.props.eventSelection!==o.instance.instanceId)},n.handleDragStart=function(e){var t=n.component.context,r=n.eventRange;n.relevantEvents=bn(t.getCurrentData().eventStore,n.eventRange.instance.instanceId);var o=n.querySegEl(e);n.draggingSegEl=o,n.draggingSeg=Jn(o),t.calendarApi.unselect(),t.emitter.trigger("eventResizeStart",{el:o,event:new xr(t,r.def,r.instance),jsEvent:e.origEvent,view:t.viewApi})},n.handleHitUpdate=function(e,t,r){var o=n.component.context,i=n.relevantEvents,a=n.hitDragging.initialHit,s=n.eventRange.instance,l=null,u=null,c=!1,d={affectedEvents:i,mutatedEvents:{defs:{},instances:{}},isEvent:!0};e&&(e.componentId===a.componentId&&n.isHitComboAllowed&&!n.isHitComboAllowed(a,e)||(l=function(e,t,n,r){var o=e.context.dateEnv,i=e.dateSpan.range.start,a=t.dateSpan.range.start,s=Fn(i,a,o,e.largeUnit);if(n){if(o.add(r.start,s)<r.end)return{startDelta:s}}else if(o.add(r.end,s)>r.start)return{endDelta:s};return null}(a,e,r.subjectEl.classList.contains("fc-event-resizer-start"),s.range)));l&&(u=Sr(i,o.getCurrentData().eventUiBases,l,o),d.mutatedEvents=u,Ia(d,e.dateProfile,o)||(c=!0,l=null,u=null,d.mutatedEvents=null)),u?o.dispatch({type:"SET_EVENT_RESIZE",state:d}):o.dispatch({type:"UNSET_EVENT_RESIZE"}),c?Ue():We(),t||(l&&Us(a,e)&&(l=null),n.validMutation=l,n.mutatedRelevantEvents=u)},n.handleDragEnd=function(e){var t=n.component.context,o=n.eventRange.def,i=n.eventRange.instance,a=new xr(t,o,i),s=n.relevantEvents,l=n.mutatedRelevantEvents;if(t.emitter.trigger("eventResizeStop",{el:n.draggingSegEl,event:a,jsEvent:e.origEvent,view:t.viewApi}),n.validMutation){var u=new xr(t,l.defs[o.defId],i?l.instances[i.instanceId]:null);t.dispatch({type:"MERGE_EVENTS",eventStore:l});var c={oldEvent:a,event:u,relatedEvents:Ir(l,t,i),revert:function(){t.dispatch({type:"MERGE_EVENTS",eventStore:s})}};t.emitter.trigger("eventResize",r(r({},c),{el:n.draggingSegEl,startDelta:n.validMutation.startDelta||Nt(0),endDelta:n.validMutation.endDelta||Nt(0),jsEvent:e.origEvent,view:t.viewApi})),t.emitter.trigger("eventChange",c)}else t.emitter.trigger("_noEventResize");n.draggingSeg=null,n.relevantEvents=null,n.validMutation=null};var o=t.component,i=n.dragging=new Os(t.el);i.pointer.selector=".fc-event-resizer",i.touchScrollAllowed=!1,i.autoScroller.isEnabled=o.context.options.dragScroll;var a=n.hitDragging=new Ls(n.dragging,na(t));return a.emitter.on("pointerdown",n.handlePointerDown),a.emitter.on("dragstart",n.handleDragStart),a.emitter.on("hitupdate",n.handleHitUpdate),a.emitter.on("dragend",n.handleDragEnd),n}return n(t,e),t.prototype.destroy=function(){this.dragging.destroy()},t.prototype.querySegEl=function(e){return Se(e.subjectEl,".fc-event")},t}(ea);var js=function(){function e(e){var t=this;this.context=e,this.isRecentPointerDateSelect=!1,this.matchesCancel=!1,this.matchesEvent=!1,this.onSelect=function(e){e.jsEvent&&(t.isRecentPointerDateSelect=!0)},this.onDocumentPointerDown=function(e){var n=t.context.options.unselectCancel,r=_e(e.origEvent);t.matchesCancel=!!Se(r,n),t.matchesEvent=!!Se(r,Bs.SELECTOR)},this.onDocumentPointerUp=function(e){var n=t.context,r=t.documentPointer,o=n.getCurrentData();if(!r.wasTouchScroll){if(o.dateSelection&&!t.isRecentPointerDateSelect){var i=n.options.unselectAuto;!i||i&&t.matchesCancel||n.calendarApi.unselect(e)}o.eventSelection&&!t.matchesEvent&&n.dispatch({type:"UNSELECT_EVENT"})}t.isRecentPointerDateSelect=!1};var n=this.documentPointer=new Ts(document);n.shouldIgnoreMove=!0,n.shouldWatchScroll=!1,n.emitter.on("pointerdown",this.onDocumentPointerDown),n.emitter.on("pointerup",this.onDocumentPointerUp),e.emitter.on("select",this.onSelect)}return e.prototype.destroy=function(){this.context.emitter.off("select",this.onSelect),this.documentPointer.destroy()},e}(),Gs={fixedMirrorParent:yn},qs={dateClick:yn,eventDragStart:yn,eventDragStop:yn,eventDrop:yn,eventResizeStart:yn,eventResizeStop:yn,eventResize:yn,drop:yn,eventReceive:yn,eventLeave:yn},Ys=function(){function e(e,t){var n=this;this.receivingContext=null,this.droppableEvent=null,this.suppliedDragMeta=null,this.dragMeta=null,this.handleDragStart=function(e){n.dragMeta=n.buildDragMeta(e.subjectEl)},this.handleHitUpdate=function(e,t,o){var i=n.hitDragging.dragging,a=null,s=null,l=!1,u={affectedEvents:{defs:{},instances:{}},mutatedEvents:{defs:{},instances:{}},isEvent:n.dragMeta.create};e&&(a=e.context,n.canDropElOnCalendar(o.subjectEl,a)&&(s=function(e,t,n){for(var o=r({},t.leftoverProps),i=0,a=n.pluginHooks.externalDefTransforms;i<a.length;i++){var s=a[i];r(o,s(e,t))}var l=On(o,n),u=l.refined,c=l.extra,d=Ln(u,c,t.sourceId,e.allDay,n.options.forceEventDuration||Boolean(t.duration),n),p=e.range.start;e.allDay&&t.startTime&&(p=n.dateEnv.add(p,t.startTime));var f=t.duration?n.dateEnv.add(p,t.duration):Er(e.allDay,p,n),h=mt(d.defId,{start:p,end:f});return{def:d,instance:h}}(e.dateSpan,n.dragMeta,a),u.mutatedEvents=Sn(s),(l=!Ia(u,e.dateProfile,a))&&(u.mutatedEvents={defs:{},instances:{}},s=null))),n.displayDrag(a,u),i.setMirrorIsVisible(t||!s||!document.querySelector(".fc-event-mirror")),l?Ue():We(),t||(i.setMirrorNeedsRevert(!s),n.receivingContext=a,n.droppableEvent=s)},this.handleDragEnd=function(e){var t=n,o=t.receivingContext,i=t.droppableEvent;if(n.clearDrag(),o&&i){var a=n.hitDragging.finalHit,s=a.context.viewApi,l=n.dragMeta;if(o.emitter.trigger("drop",r(r({},Ws(a.dateSpan,o)),{draggedEl:e.subjectEl,jsEvent:e.origEvent,view:s})),l.create){var u=Sn(i);o.dispatch({type:"MERGE_EVENTS",eventStore:u}),e.isTouch&&o.dispatch({type:"SELECT_EVENT",eventInstanceId:i.instance.instanceId}),o.emitter.trigger("eventReceive",{event:new xr(o,i.def,i.instance),relatedEvents:[],revert:function(){o.dispatch({type:"REMOVE_EVENTS",eventStore:u})},draggedEl:e.subjectEl,view:s})}}n.receivingContext=null,n.droppableEvent=null};var o=this.hitDragging=new Ls(e,ra);o.requireInitial=!1,o.emitter.on("dragstart",this.handleDragStart),o.emitter.on("hitupdate",this.handleHitUpdate),o.emitter.on("dragend",this.handleDragEnd),this.suppliedDragMeta=t}return e.prototype.buildDragMeta=function(e){return"object"==typeof this.suppliedDragMeta?sa(this.suppliedDragMeta):"function"==typeof this.suppliedDragMeta?sa(this.suppliedDragMeta(e)):sa((t=function(e,t){var n=ia.dataAttrPrefix,r=(n?n+"-":"")+t;return e.getAttribute("data-"+r)||""}(e,"event"))?JSON.parse(t):{create:!1});var t},e.prototype.displayDrag=function(e,t){var n=this.receivingContext;n&&n!==e&&n.dispatch({type:"UNSET_EVENT_DRAG"}),e&&e.dispatch({type:"SET_EVENT_DRAG",state:t})},e.prototype.clearDrag=function(){this.receivingContext&&this.receivingContext.dispatch({type:"UNSET_EVENT_DRAG"})},e.prototype.canDropElOnCalendar=function(e,t){var n=t.options.dropAccept;return"function"==typeof n?n.call(t.calendarApi,e):"string"!=typeof n||!n||Boolean(be(e,n))},e}();ia.dataAttrPrefix="";var Zs=function(){function e(e,t){var n=this;void 0===t&&(t={}),this.handlePointerDown=function(e){var t=n.dragging,r=n.settings,o=r.minDistance,i=r.longPressDelay;t.minDistance=null!=o?o:e.isTouch?0:cn.eventDragMinDistance,t.delay=e.isTouch?null!=i?i:cn.longPressDelay:0},this.handleDragStart=function(e){e.isTouch&&n.dragging.delay&&e.subjectEl.classList.contains("fc-event")&&n.dragging.mirror.getMirrorEl().classList.add("fc-event-selected")},this.settings=t;var r=this.dragging=new Os(e);r.touchScrollAllowed=!1,null!=t.itemSelector&&(r.pointer.selector=t.itemSelector),null!=t.appendTo&&(r.mirror.parentNode=t.appendTo),r.emitter.on("pointerdown",this.handlePointerDown),r.emitter.on("dragstart",this.handleDragStart),new Ys(r,t.eventData)}return e.prototype.destroy=function(){this.dragging.destroy()},e}(),Xs=function(e){function t(t){var n=e.call(this,t)||this;n.shouldIgnoreMove=!1,n.mirrorSelector="",n.currentMirrorEl=null,n.handlePointerDown=function(e){n.emitter.trigger("pointerdown",e),n.shouldIgnoreMove||n.emitter.trigger("dragstart",e)},n.handlePointerMove=function(e){n.shouldIgnoreMove||n.emitter.trigger("dragmove",e)},n.handlePointerUp=function(e){n.emitter.trigger("pointerup",e),n.shouldIgnoreMove||n.emitter.trigger("dragend",e)};var r=n.pointer=new Ts(t);return r.emitter.on("pointerdown",n.handlePointerDown),r.emitter.on("pointermove",n.handlePointerMove),r.emitter.on("pointerup",n.handlePointerUp),n}return n(t,e),t.prototype.destroy=function(){this.pointer.destroy()},t.prototype.setIgnoreMove=function(e){this.shouldIgnoreMove=e},t.prototype.setMirrorIsVisible=function(e){if(e)this.currentMirrorEl&&(this.currentMirrorEl.style.visibility="",this.currentMirrorEl=null);else{var t=this.mirrorSelector?document.querySelector(this.mirrorSelector):null;t&&(this.currentMirrorEl=t,t.style.visibility="hidden")}},t}(oa),Ks=function(){function e(e,t){var n=document;e===document||e instanceof Element?(n=e,t=t||{}):t=e||{};var r=this.dragging=new Xs(n);"string"==typeof t.itemSelector?r.pointer.selector=t.itemSelector:n===document&&(r.pointer.selector="[data-event]"),"string"==typeof t.mirrorSelector&&(r.mirrorSelector=t.mirrorSelector),new Ys(r,t.eventData)}return e.prototype.destroy=function(){this.dragging.destroy()},e}(),$s=zo({componentInteractions:[Vs,Fs,Bs,zs],calendarInteractions:[js],elementDraggingImpl:Os,optionRefiners:Gs,listenerRefiners:qs}),Js=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.headerElRef=ko(),t}return n(t,e),t.prototype.renderSimpleLayout=function(e,t){var n=this.props,r=this.context,o=[],i=Qa(r.options);return e&&o.push({type:"header",key:"header",isSticky:i,chunk:{elRef:this.headerElRef,tableClassName:"fc-col-header",rowContent:e}}),o.push({type:"body",key:"body",liquid:!0,chunk:{content:t}}),_o(ti,{viewSpec:r.viewSpec},(function(e,t){return _o("div",{ref:e,className:["fc-daygrid"].concat(t).join(" ")},_o(ts,{liquid:!n.isHeightAuto&&!n.forPrint,collapsibleWidth:n.forPrint,cols:[],sections:o}))}))},t.prototype.renderHScrollLayout=function(e,t,n,r){var o=this.context.pluginHooks.scrollGridImpl;if(!o)throw new Error("No ScrollGrid implementation");var i=this.props,a=this.context,s=!i.forPrint&&Qa(a.options),l=!i.forPrint&&es(a.options),u=[];return e&&u.push({type:"header",key:"header",isSticky:s,chunks:[{key:"main",elRef:this.headerElRef,tableClassName:"fc-col-header",rowContent:e}]}),u.push({type:"body",key:"body",liquid:!0,chunks:[{key:"main",content:t}]}),l&&u.push({type:"footer",key:"footer",isSticky:!0,chunks:[{key:"main",content:Ja}]}),_o(ti,{viewSpec:a.viewSpec},(function(e,t){return _o("div",{ref:e,className:["fc-daygrid"].concat(t).join(" ")},_o(o,{liquid:!i.isHeightAuto&&!i.forPrint,collapsibleWidth:i.forPrint,colGroups:[{cols:[{span:n,minWidth:r}]}],sections:u}))}))},t}(Bo);function Qs(e,t){for(var n=[],r=0;r<t;r+=1)n[r]=[];for(var o=0,i=e;o<i.length;o++){var a=i[o];n[a.row].push(a)}return n}function el(e,t){for(var n=[],r=0;r<t;r+=1)n[r]=[];for(var o=0,i=e;o<i.length;o++){var a=i[o];n[a.firstCol].push(a)}return n}function tl(e,t){var n=[];if(e){for(a=0;a<t;a+=1)n[a]={affectedInstances:e.affectedInstances,isEvent:e.isEvent,segs:[]};for(var r=0,o=e.segs;r<o.length;r++){var i=o[r];n[i.row].segs.push(i)}}else for(var a=0;a<t;a+=1)n[a]=null;return n}var nl=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=so(this.context,e.date);return _o(ss,{date:e.date,dateProfile:e.dateProfile,todayRange:e.todayRange,showDayNumber:e.showDayNumber,extraHookProps:e.extraHookProps,defaultContent:rl},(function(n,o){return(o||e.forceDayTop)&&_o("div",{className:"fc-daygrid-day-top",ref:n},_o("a",r({id:e.dayNumberId,className:"fc-daygrid-day-number"},t),o||_o(xo,null," ")))}))},t}(Uo);function rl(e){return e.dayNumberText}var ol=ln({hour:"numeric",minute:"2-digit",omitZeroMinute:!0,meridiem:"narrow"});function il(e){var t=e.eventRange.ui.display;return"list-item"===t||"auto"===t&&!e.eventRange.def.allDay&&e.firstCol===e.lastCol&&e.isStart&&e.isEnd}var al=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props;return _o(rs,r({},e,{extraClassNames:["fc-daygrid-event","fc-daygrid-block-event","fc-h-event"],defaultTimeFormat:ol,defaultDisplayEventEnd:e.defaultDisplayEventEnd,disableResizing:!e.seg.eventRange.def.allDay}))},t}(Uo),sl=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.options.eventTimeFormat||ol,o=ar(e.seg,n,t,!0,e.defaultDisplayEventEnd);return _o(ns,{seg:e.seg,timeText:o,defaultContent:ll,isDragging:e.isDragging,isResizing:!1,isDateSelecting:!1,isSelected:e.isSelected,isPast:e.isPast,isFuture:e.isFuture,isToday:e.isToday},(function(n,o,i,a){return _o("a",r({className:["fc-daygrid-event","fc-daygrid-dot-event"].concat(o).join(" "),ref:n},cr(e.seg,t)),a)}))},t}(Uo);function ll(e){return _o(xo,null,_o("div",{className:"fc-daygrid-event-dot",style:{borderColor:e.borderColor||e.backgroundColor}}),e.timeText&&_o("div",{className:"fc-event-time"},e.timeText),_o("div",{className:"fc-event-title"},e.event.title||_o(xo,null," ")))}var ul=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.compileSegs=qt(cl),t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.compileSegs(e.singlePlacements),n=t.allSegs,o=t.invisibleSegs;return _o(ms,{dateProfile:e.dateProfile,todayRange:e.todayRange,allDayDate:e.allDayDate,moreCnt:e.moreCnt,allSegs:n,hiddenSegs:o,alignmentElRef:e.alignmentElRef,alignGridTop:e.alignGridTop,extraDateSpan:e.extraDateSpan,popoverContent:function(){var t=(e.eventDrag?e.eventDrag.affectedInstances:null)||(e.eventResize?e.eventResize.affectedInstances:null)||{};return _o(xo,null,n.map((function(n){var o=n.eventRange.instance.instanceId;return _o("div",{className:"fc-daygrid-event-harness",key:o,style:{visibility:t[o]?"hidden":""}},il(n)?_o(sl,r({seg:n,isDragging:!1,isSelected:o===e.eventSelection,defaultDisplayEventEnd:!1},sr(n,e.todayRange))):_o(al,r({seg:n,isDragging:!1,isResizing:!1,isDateSelecting:!1,isSelected:o===e.eventSelection,defaultDisplayEventEnd:!1},sr(n,e.todayRange))))})))}},(function(e,t,n,o,i,a,s,l){return _o("a",r({ref:e,className:["fc-daygrid-more-link"].concat(t).join(" "),title:a,"aria-expanded":s,"aria-controls":l},He(i)),o)}))},t}(Uo);function cl(e){for(var t=[],n=[],r=0,o=e;r<o.length;r++){var i=o[r];t.push(i.seg),i.isVisible||n.push(i.seg)}return{allSegs:t,invisibleSegs:n}}var dl=ln({week:"narrow"}),pl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.rootElRef=ko(),t.state={dayNumberId:xe()},t.handleRootEl=function(e){Fo(t.rootElRef,e),Fo(t.props.elRef,e)},t}return n(t,e),t.prototype.render=function(){var e=this,t=e.context,n=e.props,o=e.state,i=e.rootElRef,a=n.date,s=n.dateProfile,l=so(t,a,"week");return _o(us,{date:a,dateProfile:s,todayRange:n.todayRange,showDayNumber:n.showDayNumber,extraHookProps:n.extraHookProps,elRef:this.handleRootEl},(function(e,t,u,c){return _o("td",r({ref:e,role:"gridcell",className:["fc-daygrid-day"].concat(t,n.extraClassNames||[]).join(" ")},u,n.extraDataAttrs,n.showDayNumber?{"aria-labelledby":o.dayNumberId}:{}),_o("div",{className:"fc-daygrid-day-frame fc-scrollgrid-sync-inner",ref:n.innerElRef},n.showWeekNumber&&_o(fs,{date:a,defaultFormat:dl},(function(e,t,n,o){return _o("a",r({ref:e,className:["fc-daygrid-week-number"].concat(t).join(" ")},l),o)})),!c&&_o(nl,{date:a,dateProfile:s,showDayNumber:n.showDayNumber,dayNumberId:o.dayNumberId,forceDayTop:n.forceDayTop,todayRange:n.todayRange,extraHookProps:n.extraHookProps}),_o("div",{className:"fc-daygrid-day-events",ref:n.fgContentElRef},n.fgContent,_o("div",{className:"fc-daygrid-day-bottom",style:{marginTop:n.moreMarginTop}},_o(ul,{allDayDate:a,singlePlacements:n.singlePlacements,moreCnt:n.moreCnt,alignmentElRef:i,alignGridTop:!n.showDayNumber,extraDateSpan:n.extraDateSpan,dateProfile:n.dateProfile,eventSelection:n.eventSelection,eventDrag:n.eventDrag,eventResize:n.eventResize,todayRange:n.todayRange}))),_o("div",{className:"fc-daygrid-day-bg"},n.bgContent)))}))},t}(Bo);function fl(e,t,n,r,o,i,a){var s=new vl;s.allowReslicing=!0,s.strictOrder=r,!0===t||!0===n?(s.maxCoord=i,s.hiddenConsumes=!0):"number"==typeof t?s.maxStackCnt=t:"number"==typeof n&&(s.maxStackCnt=n,s.hiddenConsumes=!0);for(var l=[],u=[],c=0;c<e.length;c+=1){var d=o[(R=e[c]).eventRange.instance.instanceId];null!=d?l.push({index:c,thickness:d,span:{start:R.firstCol,end:R.lastCol+1}}):u.push(R)}for(var p=s.addSegs(l),f=function(e,t,n){for(var r=function(e,t){for(var n=[],r=0;r<t;r+=1)n.push([]);for(var o=0,i=e;o<i.length;o++){var a=i[o];for(r=a.span.start;r<a.span.end;r+=1)n[r].push(a)}return n}(e,n.length),o=[],i=[],a=[],s=0;s<n.length;s+=1){for(var l=r[s],u=[],c=0,d=0,p=0,f=l;p<f.length;p++){var h=t[(y=f[p]).index];u.push({seg:hl(h,s,s+1,n),isVisible:!0,isAbsolute:!1,absoluteTop:y.levelCoord,marginTop:y.levelCoord-c}),c=y.levelCoord+y.thickness}var v=[];c=0,d=0;for(var g=0,m=l;g<m.length;g++){h=t[(y=m[g]).index];var y,E=y.span.end-y.span.start>1,S=y.span.start===s;d+=y.levelCoord-c,c=y.levelCoord+y.thickness,E?(d+=y.thickness,S&&v.push({seg:hl(h,y.span.start,y.span.end,n),isVisible:!0,isAbsolute:!0,absoluteTop:y.levelCoord,marginTop:0})):S&&(v.push({seg:hl(h,y.span.start,y.span.end,n),isVisible:!0,isAbsolute:!1,absoluteTop:y.levelCoord,marginTop:d}),d=0)}o.push(u),i.push(v),a.push(d)}return{singleColPlacements:o,multiColPlacements:i,leftoverMargins:a}}(s.toRects(),e,a),h=f.singleColPlacements,v=f.multiColPlacements,g=f.leftoverMargins,m=[],y=[],E=0,S=u;E<S.length;E++){v[(R=S[E]).firstCol].push({seg:R,isVisible:!1,isAbsolute:!0,absoluteTop:0,marginTop:0});for(var b=R.firstCol;b<=R.lastCol;b+=1)h[b].push({seg:hl(R,b,b+1,a),isVisible:!1,isAbsolute:!1,absoluteTop:0,marginTop:0})}for(b=0;b<a.length;b+=1)m.push(0);for(var D=0,C=p;D<C.length;D++){var w=C[D],R=e[w.index],_=w.span;v[_.start].push({seg:hl(R,_.start,_.end,a),isVisible:!1,isAbsolute:!0,absoluteTop:0,marginTop:0});for(b=_.start;b<_.end;b+=1)m[b]+=1,h[b].push({seg:hl(R,b,b+1,a),isVisible:!1,isAbsolute:!1,absoluteTop:0,marginTop:0})}for(b=0;b<a.length;b+=1)y.push(g[b]);return{singleColPlacements:h,multiColPlacements:v,moreCnts:m,moreMarginTops:y}}function hl(e,t,n,o){if(e.firstCol===t&&e.lastCol===n-1)return e;var i=e.eventRange,a=i.range,s=jn(a,{start:o[t].date,end:tt(o[n-1].date,1)});return r(r({},e),{firstCol:t,lastCol:n-1,eventRange:{def:i.def,ui:r(r({},i.ui),{durationEditable:!1}),instance:i.instance,range:s},isStart:e.isStart&&s.start.valueOf()===a.start.valueOf(),isEnd:e.isEnd&&s.end.valueOf()===a.end.valueOf()})}var vl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.hiddenConsumes=!1,t.forceHidden={},t}return n(t,e),t.prototype.addSegs=function(t){for(var n=this,r=e.prototype.addSegs.call(this,t),o=this.entriesByLevel,i=function(e){return!n.forceHidden[Zi(e)]},a=0;a<o.length;a+=1)o[a]=o[a].filter(i);return r},t.prototype.handleInvalidInsertion=function(t,n,o){var i=this.entriesByLevel,a=this.forceHidden,s=t.touchingEntry,l=t.touchingLevel,u=t.touchingLateral;if(this.hiddenConsumes&&s){var c=Zi(s);if(!a[c])if(this.allowReslicing){var d=r(r({},s),{span:$i(s.span,n.span)});a[Zi(d)]=!0,i[l][u]=d,this.splitEntry(s,n,o)}else a[c]=!0,o.push(s)}return e.prototype.handleInvalidInsertion.call(this,t,n,o)},t}(qi),gl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.cellElRefs=new Fa,t.frameElRefs=new Fa,t.fgElRefs=new Fa,t.segHarnessRefs=new Fa,t.rootElRef=ko(),t.state={framePositions:null,maxContentHeight:null,eventInstanceHeights:{}},t}return n(t,e),t.prototype.render=function(){var e=this,t=this,n=t.props,r=t.state,o=t.context.options,i=n.cells.length,a=el(n.businessHourSegs,i),s=el(n.bgEventSegs,i),l=el(this.getHighlightSegs(),i),u=el(this.getMirrorSegs(),i),c=fl(tr(n.fgEventSegs,o.eventOrder),n.dayMaxEvents,n.dayMaxEventRows,o.eventOrderStrict,r.eventInstanceHeights,r.maxContentHeight,n.cells),d=c.singleColPlacements,p=c.multiColPlacements,f=c.moreCnts,h=c.moreMarginTops,v=n.eventDrag&&n.eventDrag.affectedInstances||n.eventResize&&n.eventResize.affectedInstances||{};return _o("tr",{ref:this.rootElRef,role:"row"},n.renderIntro&&n.renderIntro(),n.cells.map((function(t,r){var o=e.renderFgSegs(r,n.forPrint?d[r]:p[r],n.todayRange,v),i=e.renderFgSegs(r,function(e,t){if(!e.length)return[];var n=function(e){for(var t={},n=0,r=e;n<r.length;n++)for(var o=0,i=r[n];o<i.length;o++){var a=i[o];t[a.seg.eventRange.instance.instanceId]=a.absoluteTop}return t}(t);return e.map((function(e){return{seg:e,isVisible:!0,isAbsolute:!0,absoluteTop:n[e.eventRange.instance.instanceId],marginTop:0}}))}(u[r],p),n.todayRange,{},Boolean(n.eventDrag),Boolean(n.eventResize),!1);return _o(pl,{key:t.key,elRef:e.cellElRefs.createRef(t.key),innerElRef:e.frameElRefs.createRef(t.key),dateProfile:n.dateProfile,date:t.date,showDayNumber:n.showDayNumbers,showWeekNumber:n.showWeekNumbers&&0===r,forceDayTop:n.showWeekNumbers,todayRange:n.todayRange,eventSelection:n.eventSelection,eventDrag:n.eventDrag,eventResize:n.eventResize,extraHookProps:t.extraHookProps,extraDataAttrs:t.extraDataAttrs,extraClassNames:t.extraClassNames,extraDateSpan:t.extraDateSpan,moreCnt:f[r],moreMarginTop:h[r],singlePlacements:d[r],fgContentElRef:e.fgElRefs.createRef(t.key),fgContent:_o(xo,null,_o(xo,null,o),_o(xo,null,i)),bgContent:_o(xo,null,e.renderFillSegs(l[r],"highlight"),e.renderFillSegs(a[r],"non-business"),e.renderFillSegs(s[r],"bg-event"))})})))},t.prototype.componentDidMount=function(){this.updateSizing(!0)},t.prototype.componentDidUpdate=function(e,t){var n=this.props;this.updateSizing(!wt(e,n))},t.prototype.getHighlightSegs=function(){var e=this.props;return e.eventDrag&&e.eventDrag.segs.length?e.eventDrag.segs:e.eventResize&&e.eventResize.segs.length?e.eventResize.segs:e.dateSelectionSegs},t.prototype.getMirrorSegs=function(){var e=this.props;return e.eventResize&&e.eventResize.segs.length?e.eventResize.segs:[]},t.prototype.renderFgSegs=function(e,t,n,o,i,a,s){var l=this.context,u=this.props.eventSelection,c=this.state.framePositions,d=1===this.props.cells.length,p=i||a||s,f=[];if(c)for(var h=0,v=t;h<v.length;h++){var g=v[h],m=g.seg,y=m.eventRange.instance.instanceId,E=y+":"+e,S=g.isVisible&&!o[y],b=g.isAbsolute,D="",C="";b&&(l.isRtl?(C=0,D=c.lefts[m.lastCol]-c.lefts[m.firstCol]):(D=0,C=c.rights[m.firstCol]-c.rights[m.lastCol])),f.push(_o("div",{className:"fc-daygrid-event-harness"+(b?" fc-daygrid-event-harness-abs":""),key:E,ref:p?null:this.segHarnessRefs.createRef(E),style:{visibility:S?"":"hidden",marginTop:b?"":g.marginTop,top:b?g.absoluteTop:"",left:D,right:C}},il(m)?_o(sl,r({seg:m,isDragging:i,isSelected:y===u,defaultDisplayEventEnd:d},sr(m,n))):_o(al,r({seg:m,isDragging:i,isResizing:a,isDateSelecting:s,isSelected:y===u,defaultDisplayEventEnd:d},sr(m,n)))))}return f},t.prototype.renderFillSegs=function(e,t){var n=this.context.isRtl,i=this.props.todayRange,a=this.state.framePositions,s=[];if(a)for(var l=0,u=e;l<u.length;l++){var c=u[l],d=n?{right:0,left:a.lefts[c.lastCol]-a.lefts[c.firstCol]}:{left:0,right:a.rights[c.firstCol]-a.rights[c.lastCol]};s.push(_o("div",{key:ur(c.eventRange),className:"fc-daygrid-bg-harness",style:d},"bg-event"===t?_o(ds,r({seg:c},sr(c,i))):cs(t)))}return _o.apply(void 0,o([xo,{}],s))},t.prototype.updateSizing=function(e){var t=this.props,n=this.frameElRefs;if(!t.forPrint&&null!==t.clientWidth){if(e){var o=t.cells.map((function(e){return n.currentMap[e.key]}));if(o.length){var i=this.rootElRef.current;this.setState({framePositions:new So(i,o,!0,!1)})}}var a=this.state.eventInstanceHeights,s=this.queryEventInstanceHeights(),l=!0===t.dayMaxEvents||!0===t.dayMaxEventRows;this.setState({eventInstanceHeights:r(r({},a),s),maxContentHeight:l?this.computeMaxContentHeight():null})}},t.prototype.queryEventInstanceHeights=function(){var e=this.segHarnessRefs.currentMap,t={};for(var n in e){var r=Math.round(e[n].getBoundingClientRect().height),o=n.split(":")[0];t[o]=Math.max(t[o]||0,r)}return t},t.prototype.computeMaxContentHeight=function(){var e=this.props.cells[0].key,t=this.cellElRefs.currentMap[e],n=this.fgElRefs.currentMap[e];return t.getBoundingClientRect().bottom-n.getBoundingClientRect().top},t.prototype.getCellEls=function(){var e=this.cellElRefs.currentMap;return this.props.cells.map((function(t){return e[t.key]}))},t}(Bo);gl.addStateEquality({eventInstanceHeights:wt});var ml=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.splitBusinessHourSegs=qt(Qs),t.splitBgEventSegs=qt(Qs),t.splitFgEventSegs=qt(Qs),t.splitDateSelectionSegs=qt(Qs),t.splitEventDrag=qt(tl),t.splitEventResize=qt(tl),t.rowRefs=new Fa,t.handleRootEl=function(e){t.rootEl=e,e?t.context.registerInteractiveComponent(t,{el:e,isHitComboAllowed:t.props.isHitComboAllowed}):t.context.unregisterInteractiveComponent(t)},t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=t.dateProfile,r=t.dayMaxEventRows,o=t.dayMaxEvents,i=t.expandRows,a=t.cells.length,s=this.splitBusinessHourSegs(t.businessHourSegs,a),l=this.splitBgEventSegs(t.bgEventSegs,a),u=this.splitFgEventSegs(t.fgEventSegs,a),c=this.splitDateSelectionSegs(t.dateSelectionSegs,a),d=this.splitEventDrag(t.eventDrag,a),p=this.splitEventResize(t.eventResize,a),f=!0===o||!0===r;return f&&!i&&(f=!1,r=null,o=null),_o("div",{className:["fc-daygrid-body",f?"fc-daygrid-body-balanced":"fc-daygrid-body-unbalanced",i?"":"fc-daygrid-body-natural"].join(" "),ref:this.handleRootEl,style:{width:t.clientWidth,minWidth:t.tableMinWidth}},_o(Ca,{unit:"day"},(function(f,h){return _o(xo,null,_o("table",{role:"presentation",className:"fc-scrollgrid-sync-table",style:{width:t.clientWidth,minWidth:t.tableMinWidth,height:i?t.clientHeight:""}},t.colGroupNode,_o("tbody",{role:"presentation"},t.cells.map((function(i,f){return _o(gl,{ref:e.rowRefs.createRef(f),key:i.length?i[0].date.toISOString():f,showDayNumbers:a>1,showWeekNumbers:t.showWeekNumbers,todayRange:h,dateProfile:n,cells:i,renderIntro:t.renderRowIntro,businessHourSegs:s[f],eventSelection:t.eventSelection,bgEventSegs:l[f].filter(yl),fgEventSegs:u[f],dateSelectionSegs:c[f],eventDrag:d[f],eventResize:p[f],dayMaxEvents:o,dayMaxEventRows:r,clientWidth:t.clientWidth,clientHeight:t.clientHeight,forPrint:t.forPrint})})))))})))},t.prototype.prepareHits=function(){this.rowPositions=new So(this.rootEl,this.rowRefs.collect().map((function(e){return e.getCellEls()[0]})),!1,!0),this.colPositions=new So(this.rootEl,this.rowRefs.currentMap[0].getCellEls(),!0,!1)},t.prototype.queryHit=function(e,t){var n=this.colPositions,o=this.rowPositions,i=n.leftToIndex(e),a=o.topToIndex(t);if(null!=a&&null!=i){var s=this.props.cells[a][i];return{dateProfile:this.props.dateProfile,dateSpan:r({range:this.getCellRange(a,i),allDay:!0},s.extraDateSpan),dayEl:this.getCellEl(a,i),rect:{left:n.lefts[i],right:n.rights[i],top:o.tops[a],bottom:o.bottoms[a]},layer:0}}return null},t.prototype.getCellEl=function(e,t){return this.rowRefs.currentMap[e].getCellEls()[t]},t.prototype.getCellRange=function(e,t){var n=this.props.cells[e][t].date;return{start:n,end:tt(n,1)}},t}(Bo);function yl(e){return e.eventRange.def.allDay}var El=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.forceDayIfListItem=!0,t}return n(t,e),t.prototype.sliceRange=function(e,t){return t.sliceRange(e)},t}(xa),Sl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.slicer=new El,t.tableRef=ko(),t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context;return _o(ml,r({ref:this.tableRef},this.slicer.sliceProps(e,e.dateProfile,e.nextDayThreshold,t,e.dayTableModel),{dateProfile:e.dateProfile,cells:e.dayTableModel.cells,colGroupNode:e.colGroupNode,tableMinWidth:e.tableMinWidth,renderRowIntro:e.renderRowIntro,dayMaxEvents:e.dayMaxEvents,dayMaxEventRows:e.dayMaxEventRows,showWeekNumbers:e.showWeekNumbers,expandRows:e.expandRows,headerAlignElRef:e.headerAlignElRef,clientWidth:e.clientWidth,clientHeight:e.clientHeight,forPrint:e.forPrint}))},t}(Bo),bl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.buildDayTableModel=qt(Dl),t.headerRef=ko(),t.tableRef=ko(),t}return n(t,e),t.prototype.render=function(){var e=this,t=this.context,n=t.options,r=t.dateProfileGenerator,o=this.props,i=this.buildDayTableModel(o.dateProfile,r),a=n.dayHeaders&&_o(Ra,{ref:this.headerRef,dateProfile:o.dateProfile,dates:i.headerDates,datesRepDistinctDays:1===i.rowCnt}),s=function(t){return _o(Sl,{ref:e.tableRef,dateProfile:o.dateProfile,dayTableModel:i,businessHours:o.businessHours,dateSelection:o.dateSelection,eventStore:o.eventStore,eventUiBases:o.eventUiBases,eventSelection:o.eventSelection,eventDrag:o.eventDrag,eventResize:o.eventResize,nextDayThreshold:n.nextDayThreshold,colGroupNode:t.tableColGroupNode,tableMinWidth:t.tableMinWidth,dayMaxEvents:n.dayMaxEvents,dayMaxEventRows:n.dayMaxEventRows,showWeekNumbers:n.weekNumbers,expandRows:!o.isHeightAuto,headerAlignElRef:e.headerElRef,clientWidth:t.clientWidth,clientHeight:t.clientHeight,forPrint:o.forPrint})};return n.dayMinWidth?this.renderHScrollLayout(a,s,i.colCnt,n.dayMinWidth):this.renderSimpleLayout(a,s)},t}(Js);function Dl(e,t){var n=new Ta(e.renderRange,t);return new ka(n,/year|month|week/.test(e.currentRangeUnit))}var Cl=zo({initialView:"dayGridMonth",views:{dayGrid:{component:bl,dateProfileGeneratorClass:function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.buildRenderRange=function(t,n,r){var o,i=this.props.dateEnv,a=e.prototype.buildRenderRange.call(this,t,n,r),s=a.start,l=a.end;(/^(year|month)$/.test(n)&&(s=i.startOfWeek(s),(o=i.startOfWeek(l)).valueOf()!==l.valueOf()&&(l=et(o,1))),this.props.monthMode&&this.props.fixedWeekCount)&&(l=et(l,6-Math.ceil(rt(s,l))));return{start:s,end:l}},t}(ai)},dayGridDay:{type:"dayGrid",duration:{days:1}},dayGridWeek:{type:"dayGrid",duration:{weeks:1}},dayGridMonth:{type:"dayGrid",duration:{months:1},monthMode:!0,fixedWeekCount:!0}}}),wl=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.getKeyInfo=function(){return{allDay:{},timed:{}}},t.prototype.getKeysForDateSpan=function(e){return e.allDay?["allDay"]:["timed"]},t.prototype.getKeysForEventDef=function(e){return e.allDay?Kn(e)?["timed","allDay"]:["allDay"]:["timed"]},t}(to),Rl=ln({hour:"numeric",minute:"2-digit",omitZeroMinute:!0,meridiem:"short"});function _l(e){var t=["fc-timegrid-slot","fc-timegrid-slot-label",e.isLabeled?"fc-scrollgrid-shrink":"fc-timegrid-slot-minor"];return _o(Oo.Consumer,null,(function(n){if(!e.isLabeled)return _o("td",{className:t.join(" "),"data-time":e.isoTimeStr});var r=n.dateEnv,o=n.options,i=n.viewApi,a=null==o.slotLabelFormat?Rl:Array.isArray(o.slotLabelFormat)?ln(o.slotLabelFormat[0]):ln(o.slotLabelFormat),s={level:0,time:e.time,date:r.toDate(e.date),view:i,text:r.format(e.date,a)};return _o(Yo,{hookProps:s,classNames:o.slotLabelClassNames,content:o.slotLabelContent,defaultContent:Tl,didMount:o.slotLabelDidMount,willUnmount:o.slotLabelWillUnmount},(function(n,r,o,i){return _o("td",{ref:n,className:t.concat(r).join(" "),"data-time":e.isoTimeStr},_o("div",{className:"fc-timegrid-slot-label-frame fc-scrollgrid-shrink-frame"},_o("div",{className:"fc-timegrid-slot-label-cushion fc-scrollgrid-shrink-cushion",ref:o},i)))}))}))}function Tl(e){return e.text}var kl=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){return this.props.slatMetas.map((function(e){return _o("tr",{key:e.key},_o(_l,r({},e)))}))},t}(Uo),xl=ln({week:"short"}),Ml=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.allDaySplitter=new wl,t.headerElRef=ko(),t.rootElRef=ko(),t.scrollerElRef=ko(),t.state={slatCoords:null},t.handleScrollTopRequest=function(e){var n=t.scrollerElRef.current;n&&(n.scrollTop=e)},t.renderHeadAxis=function(e,n){void 0===n&&(n="");var o=t.context.options,i=t.props.dateProfile.renderRange,a=1===ot(i.start,i.end)?so(t.context,i.start,"week"):{};return o.weekNumbers&&"day"===e?_o(fs,{date:i.start,defaultFormat:xl},(function(e,t,o,i){return _o("th",{ref:e,"aria-hidden":!0,className:["fc-timegrid-axis","fc-scrollgrid-shrink"].concat(t).join(" ")},_o("div",{className:"fc-timegrid-axis-frame fc-scrollgrid-shrink-frame fc-timegrid-axis-frame-liquid",style:{height:n}},_o("a",r({ref:o,className:"fc-timegrid-axis-cushion fc-scrollgrid-shrink-cushion fc-scrollgrid-sync-inner"},a),i)))})):_o("th",{"aria-hidden":!0,className:"fc-timegrid-axis"},_o("div",{className:"fc-timegrid-axis-frame",style:{height:n}}))},t.renderTableRowAxis=function(e){var n=t.context,r=n.options,o=n.viewApi,i={text:r.allDayText,view:o};return _o(Yo,{hookProps:i,classNames:r.allDayClassNames,content:r.allDayContent,defaultContent:Il,didMount:r.allDayDidMount,willUnmount:r.allDayWillUnmount},(function(t,n,r,o){return _o("td",{ref:t,"aria-hidden":!0,className:["fc-timegrid-axis","fc-scrollgrid-shrink"].concat(n).join(" ")},_o("div",{className:"fc-timegrid-axis-frame fc-scrollgrid-shrink-frame"+(null==e?" fc-timegrid-axis-frame-liquid":""),style:{height:e}},_o("span",{className:"fc-timegrid-axis-cushion fc-scrollgrid-shrink-cushion fc-scrollgrid-sync-inner",ref:r},o)))}))},t.handleSlatCoords=function(e){t.setState({slatCoords:e})},t}return n(t,e),t.prototype.renderSimpleLayout=function(e,t,n){var r=this.context,o=this.props,i=[],a=Qa(r.options);return e&&i.push({type:"header",key:"header",isSticky:a,chunk:{elRef:this.headerElRef,tableClassName:"fc-col-header",rowContent:e}}),t&&(i.push({type:"body",key:"all-day",chunk:{content:t}}),i.push({type:"body",key:"all-day-divider",outerContent:_o("tr",{role:"presentation",className:"fc-scrollgrid-section"},_o("td",{className:"fc-timegrid-divider "+r.theme.getClass("tableCellShaded")}))})),i.push({type:"body",key:"body",liquid:!0,expandRows:Boolean(r.options.expandRows),chunk:{scrollerElRef:this.scrollerElRef,content:n}}),_o(ti,{viewSpec:r.viewSpec,elRef:this.rootElRef},(function(e,t){return _o("div",{className:["fc-timegrid"].concat(t).join(" "),ref:e},_o(ts,{liquid:!o.isHeightAuto&&!o.forPrint,collapsibleWidth:o.forPrint,cols:[{width:"shrink"}],sections:i}))}))},t.prototype.renderHScrollLayout=function(e,t,n,r,o,i,a){var s=this,l=this.context.pluginHooks.scrollGridImpl;if(!l)throw new Error("No ScrollGrid implementation");var u=this.context,c=this.props,d=!c.forPrint&&Qa(u.options),p=!c.forPrint&&es(u.options),f=[];e&&f.push({type:"header",key:"header",isSticky:d,syncRowHeights:!0,chunks:[{key:"axis",rowContent:function(e){return _o("tr",{role:"presentation"},s.renderHeadAxis("day",e.rowSyncHeights[0]))}},{key:"cols",elRef:this.headerElRef,tableClassName:"fc-col-header",rowContent:e}]}),t&&(f.push({type:"body",key:"all-day",syncRowHeights:!0,chunks:[{key:"axis",rowContent:function(e){return _o("tr",{role:"presentation"},s.renderTableRowAxis(e.rowSyncHeights[0]))}},{key:"cols",content:t}]}),f.push({key:"all-day-divider",type:"body",outerContent:_o("tr",{role:"presentation",className:"fc-scrollgrid-section"},_o("td",{colSpan:2,className:"fc-timegrid-divider "+u.theme.getClass("tableCellShaded")}))}));var h=u.options.nowIndicator;return f.push({type:"body",key:"body",liquid:!0,expandRows:Boolean(u.options.expandRows),chunks:[{key:"axis",content:function(e){return _o("div",{className:"fc-timegrid-axis-chunk"},_o("table",{"aria-hidden":!0,style:{height:e.expandRows?e.clientHeight:""}},e.tableColGroupNode,_o("tbody",null,_o(kl,{slatMetas:i}))),_o("div",{className:"fc-timegrid-now-indicator-container"},_o(Ca,{unit:h?"minute":"day"},(function(e){var t=h&&a&&a.safeComputeTop(e);return"number"==typeof t?_o(is,{isAxis:!0,date:e},(function(e,n,r,o){return _o("div",{ref:e,className:["fc-timegrid-now-indicator-arrow"].concat(n).join(" "),style:{top:t}},o)})):null}))))}},{key:"cols",scrollerElRef:this.scrollerElRef,content:n}]}),p&&f.push({key:"footer",type:"footer",isSticky:!0,chunks:[{key:"axis",content:Ja},{key:"cols",content:Ja}]}),_o(ti,{viewSpec:u.viewSpec,elRef:this.rootElRef},(function(e,t){return _o("div",{className:["fc-timegrid"].concat(t).join(" "),ref:e},_o(l,{liquid:!c.isHeightAuto&&!c.forPrint,collapsibleWidth:!1,colGroups:[{width:"shrink",cols:[{width:"shrink"}]},{cols:[{span:r,minWidth:o}]}],sections:f}))}))},t.prototype.getAllDayMaxEventProps=function(){var e=this.context.options,t=e.dayMaxEvents,n=e.dayMaxEventRows;return!0!==t&&!0!==n||(t=void 0,n=5),{dayMaxEvents:t,dayMaxEventRows:n}},t}(Bo);function Il(e){return e.text}var Pl=function(){function e(e,t,n){this.positions=e,this.dateProfile=t,this.slotDuration=n}return e.prototype.safeComputeTop=function(e){var t=this.dateProfile;if(Zn(t.currentRange,e)){var n=lt(e),r=e.valueOf()-n.valueOf();if(r>=Ut(t.slotMinTime)&&r<Ut(t.slotMaxTime))return this.computeTimeTop(Nt(r))}return null},e.prototype.computeDateTop=function(e,t){return t||(t=lt(e)),this.computeTimeTop(Nt(e.valueOf()-t.valueOf()))},e.prototype.computeTimeTop=function(e){var t,n,r=this.positions,o=this.dateProfile,i=r.els.length,a=(e.milliseconds-Ut(o.slotMinTime))/Ut(this.slotDuration);return a=Math.max(0,a),a=Math.min(i,a),t=Math.floor(a),n=a-(t=Math.min(t,i-1)),r.tops[t]+r.getHeight(t)*n},e}(),Nl=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=t.options,o=e.slatElRefs;return _o("tbody",null,e.slatMetas.map((function(i,a){var s={time:i.time,date:t.dateEnv.toDate(i.date),view:t.viewApi},l=["fc-timegrid-slot","fc-timegrid-slot-lane",i.isLabeled?"":"fc-timegrid-slot-minor"];return _o("tr",{key:i.key,ref:o.createRef(i.key)},e.axis&&_o(_l,r({},i)),_o(Yo,{hookProps:s,classNames:n.slotLaneClassNames,content:n.slotLaneContent,didMount:n.slotLaneDidMount,willUnmount:n.slotLaneWillUnmount},(function(e,t,n,r){return _o("td",{ref:e,className:l.concat(t).join(" "),"data-time":i.isoTimeStr},r)})))})))},t}(Uo),Hl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.rootElRef=ko(),t.slatElRefs=new Fa,t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context;return _o("div",{ref:this.rootElRef,className:"fc-timegrid-slots"},_o("table",{"aria-hidden":!0,className:t.theme.getClass("table"),style:{minWidth:e.tableMinWidth,width:e.clientWidth,height:e.minHeight}},e.tableColGroupNode,_o(Nl,{slatElRefs:this.slatElRefs,axis:e.axis,slatMetas:e.slatMetas})))},t.prototype.componentDidMount=function(){this.updateSizing()},t.prototype.componentDidUpdate=function(){this.updateSizing()},t.prototype.componentWillUnmount=function(){this.props.onCoords&&this.props.onCoords(null)},t.prototype.updateSizing=function(){var e,t=this.context,n=this.props;n.onCoords&&null!==n.clientWidth&&(this.rootElRef.current.offsetHeight&&n.onCoords(new Pl(new So(this.rootElRef.current,(e=this.slatElRefs.currentMap,n.slatMetas.map((function(t){return e[t.key]}))),!1,!0),this.props.dateProfile,t.options.slotDuration)))},t}(Uo);function Ol(e,t){var n,r=[];for(n=0;n<t;n+=1)r.push([]);if(e)for(n=0;n<e.length;n+=1)r[e[n].col].push(e[n]);return r}function Al(e,t){var n=[];if(e){for(a=0;a<t;a+=1)n[a]={affectedInstances:e.affectedInstances,isEvent:e.isEvent,segs:[]};for(var r=0,o=e.segs;r<o.length;r++){var i=o[r];n[i.col].segs.push(i)}}else for(var a=0;a<t;a+=1)n[a]=null;return n}var Ll=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.rootElRef=ko(),t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props;return _o(ms,{allDayDate:null,moreCnt:t.hiddenSegs.length,allSegs:t.hiddenSegs,hiddenSegs:t.hiddenSegs,alignmentElRef:this.rootElRef,defaultContent:Ul,extraDateSpan:t.extraDateSpan,dateProfile:t.dateProfile,todayRange:t.todayRange,popoverContent:function(){return Xl(t.hiddenSegs,t)}},(function(n,r,o,i,a,s,l,u){return _o("a",{ref:function(t){Fo(n,t),Fo(e.rootElRef,t)},className:["fc-timegrid-more-link"].concat(r).join(" "),style:{top:t.top,bottom:t.bottom},onClick:a,title:s,"aria-expanded":l,"aria-controls":u},_o("div",{ref:o,className:"fc-timegrid-more-link-inner fc-sticky"},i))}))},t}(Uo);function Ul(e){return e.shortText}function Wl(e,t,n){var o=new qi;null!=t&&(o.strictOrder=t),null!=n&&(o.maxStackCnt=n);var i,a,s,l=Xi(o.addSegs(e)),u=function(e){var t=e.entriesByLevel,n=zl((function(e,t){return e+":"+t}),(function(o,i){var a=Vl(function(e,t,n){for(var r=e.levelCoords,o=e.entriesByLevel,i=o[t][n],a=r[t]+i.thickness,s=r.length,l=t;l<s&&r[l]<a;l+=1);for(;l<s;l+=1){for(var u=o[l],c=void 0,d=Qi(u,i.span.start,Yi),p=d[0]+d[1],f=p;(c=u[f])&&c.span.start<i.span.end;)f+=1;if(p<f)return{level:l,lateralStart:p,lateralEnd:f}}return null}(e,o,i),n),s=t[o][i];return[r(r({},s),{nextLevelNodes:a[0]}),s.thickness+a[1]]}));return Vl(t.length?{level:0,lateralStart:0,lateralEnd:t[0].length}:null,n)[0]}(o);return i=u,a=1,s=zl((function(e,t,n){return Zi(e)}),(function(e,t,n){var o,i=e.nextLevelNodes,l=e.thickness,u=l+n,c=l/u,d=[];if(i.length)for(var p=0,f=i;p<f.length;p++){var h=f[p];if(void 0===o)o=(v=s(h,t,u))[0],d.push(v[1]);else{var v=s(h,o,0);d.push(v[1])}}else o=a;var g=(o-t)*c;return[o-g,r(r({},e),{thickness:g,nextLevelNodes:d})]})),{segRects:function(e){var t=[],n=zl((function(e,t,n){return Zi(e)}),(function(e,n,i){var a=r(r({},e),{levelCoord:n,stackDepth:i,stackForward:0});return t.push(a),a.stackForward=o(e.nextLevelNodes,n+e.thickness,i+1)+1}));function o(e,t,r){for(var o=0,i=0,a=e;i<a.length;i++){var s=a[i];o=Math.max(n(s,t,r),o)}return o}return o(e,0,0),t}(u=i.map((function(e){return s(e,0,0)[1]}))),hiddenGroups:l}}function Vl(e,t){if(!e)return[[],0];for(var n=e.level,r=e.lateralStart,o=e.lateralEnd,i=r,a=[];i<o;)a.push(t(n,i)),i+=1;return a.sort(Fl),[a.map(Bl),a[0][1]]}function Fl(e,t){return t[1]-e[1]}function Bl(e){return e[0]}function zl(e,t){var n={};return function(){for(var r=[],o=0;o<arguments.length;o++)r[o]=arguments[o];var i=e.apply(void 0,r);return i in n?n[i]:n[i]=t.apply(void 0,r)}}function jl(e,t,n,r){void 0===n&&(n=null),void 0===r&&(r=0);var o=[];if(n)for(var i=0;i<e.length;i+=1){var a=e[i],s=n.computeDateTop(a.start,t),l=Math.max(s+(r||0),n.computeDateTop(a.end,t));o.push({start:Math.round(s),end:Math.round(l)})}return o}var Gl=ln({hour:"numeric",minute:"2-digit",meridiem:!1}),ql=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=["fc-timegrid-event","fc-v-event"];return this.props.isShort&&e.push("fc-timegrid-event-short"),_o(rs,r({},this.props,{defaultTimeFormat:Gl,extraClassNames:e}))},t}(Uo),Yl=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props;return _o(ss,{date:e.date,dateProfile:e.dateProfile,todayRange:e.todayRange,extraHookProps:e.extraHookProps},(function(e,t){return t&&_o("div",{className:"fc-timegrid-col-misc",ref:e},t)}))},t}(Uo),Zl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.sortEventSegs=qt(tr),t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=this.context,o=n.options.selectMirror,i=t.eventDrag&&t.eventDrag.segs||t.eventResize&&t.eventResize.segs||o&&t.dateSelectionSegs||[],a=t.eventDrag&&t.eventDrag.affectedInstances||t.eventResize&&t.eventResize.affectedInstances||{},s=this.sortEventSegs(t.fgEventSegs,n.options.eventOrder);return _o(us,{elRef:t.elRef,date:t.date,dateProfile:t.dateProfile,todayRange:t.todayRange,extraHookProps:t.extraHookProps},(function(n,l,u){return _o("td",r({ref:n,role:"gridcell",className:["fc-timegrid-col"].concat(l,t.extraClassNames||[]).join(" ")},u,t.extraDataAttrs),_o("div",{className:"fc-timegrid-col-frame"},_o("div",{className:"fc-timegrid-col-bg"},e.renderFillSegs(t.businessHourSegs,"non-business"),e.renderFillSegs(t.bgEventSegs,"bg-event"),e.renderFillSegs(t.dateSelectionSegs,"highlight")),_o("div",{className:"fc-timegrid-col-events"},e.renderFgSegs(s,a,!1,!1,!1)),_o("div",{className:"fc-timegrid-col-events"},e.renderFgSegs(i,{},Boolean(t.eventDrag),Boolean(t.eventResize),Boolean(o))),_o("div",{className:"fc-timegrid-now-indicator-container"},e.renderNowIndicator(t.nowIndicatorSegs)),_o(Yl,{date:t.date,dateProfile:t.dateProfile,todayRange:t.todayRange,extraHookProps:t.extraHookProps})))}))},t.prototype.renderFgSegs=function(e,t,n,r,o){var i=this.props;return i.forPrint?Xl(e,i):this.renderPositionedFgSegs(e,t,n,r,o)},t.prototype.renderPositionedFgSegs=function(e,t,n,o,i){var a=this,s=this.context.options,l=s.eventMaxStack,u=s.eventShortHeight,c=s.eventOrderStrict,d=s.eventMinHeight,p=this.props,f=p.date,h=p.slatCoords,v=p.eventSelection,g=p.todayRange,m=p.nowDate,y=n||o||i,E=function(e,t,n,r){for(var o=[],i=[],a=0;a<e.length;a+=1){var s=t[a];s?o.push({index:a,thickness:1,span:s}):i.push(e[a])}for(var l=Wl(o,n,r),u=l.segRects,c=l.hiddenGroups,d=[],p=0,f=u;p<f.length;p++){var h=f[p];d.push({seg:e[h.index],rect:h})}for(var v=0,g=i;v<g.length;v++){var m=g[v];d.push({seg:m,rect:null})}return{segPlacements:d,hiddenGroups:c}}(e,jl(e,f,h,d),c,l),S=E.segPlacements,b=E.hiddenGroups;return _o(xo,null,this.renderHiddenGroups(b,e),S.map((function(e){var s=e.seg,l=e.rect,c=s.eventRange.instance.instanceId,d=y||Boolean(!t[c]&&l),p=Kl(l&&l.span),f=!y&&l?a.computeSegHStyle(l):{left:0,right:0},h=Boolean(l)&&l.stackForward>0,E=Boolean(l)&&l.span.end-l.span.start<u;return _o("div",{className:"fc-timegrid-event-harness"+(h?" fc-timegrid-event-harness-inset":""),key:c,style:r(r({visibility:d?"":"hidden"},p),f)},_o(ql,r({seg:s,isDragging:n,isResizing:o,isDateSelecting:i,isSelected:c===v,isShort:E},sr(s,g,m))))})))},t.prototype.renderHiddenGroups=function(e,t){var n=this.props,r=n.extraDateSpan,o=n.dateProfile,i=n.todayRange,a=n.nowDate,s=n.eventSelection,l=n.eventDrag,u=n.eventResize;return _o(xo,null,e.map((function(e){var n,c,d=Kl(e.span),p=(n=e.entries,c=t,n.map((function(e){return c[e.index]})));return _o(Ll,{key:Ft(Ss(p)),hiddenSegs:p,top:d.top,bottom:d.bottom,extraDateSpan:r,dateProfile:o,todayRange:i,nowDate:a,eventSelection:s,eventDrag:l,eventResize:u})})))},t.prototype.renderFillSegs=function(e,t){var n=this.props,o=this.context,i=jl(e,n.date,n.slatCoords,o.options.eventMinHeight).map((function(o,i){var a=e[i];return _o("div",{key:ur(a.eventRange),className:"fc-timegrid-bg-harness",style:Kl(o)},"bg-event"===t?_o(ds,r({seg:a},sr(a,n.todayRange,n.nowDate))):cs(t))}));return _o(xo,null,i)},t.prototype.renderNowIndicator=function(e){var t=this.props,n=t.slatCoords,r=t.date;return n?e.map((function(e,t){return _o(is,{isAxis:!1,date:r,key:t},(function(t,o,i,a){return _o("div",{ref:t,className:["fc-timegrid-now-indicator-line"].concat(o).join(" "),style:{top:n.computeDateTop(e.start,r)}},a)}))})):null},t.prototype.computeSegHStyle=function(e){var t,n,r=this.context,o=r.isRtl,i=r.options.slotEventOverlap,a=e.levelCoord,s=e.levelCoord+e.thickness;i&&(s=Math.min(1,a+2*(s-a))),o?(t=1-s,n=a):(t=a,n=1-s);var l={zIndex:e.stackDepth+1,left:100*t+"%",right:100*n+"%"};return i&&!e.stackForward&&(l[o?"marginLeft":"marginRight"]=20),l},t}(Uo);function Xl(e,t){var n=t.todayRange,o=t.nowDate,i=t.eventSelection,a=t.eventDrag,s=t.eventResize,l=(a?a.affectedInstances:null)||(s?s.affectedInstances:null)||{};return _o(xo,null,e.map((function(e){var t=e.eventRange.instance.instanceId;return _o("div",{key:t,style:{visibility:l[t]?"hidden":""}},_o(ql,r({seg:e,isDragging:!1,isResizing:!1,isDateSelecting:!1,isSelected:t===i,isShort:!1},sr(e,n,o))))})))}function Kl(e){return e?{top:e.start,bottom:-e.end}:{top:"",bottom:""}}var $l=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.splitFgEventSegs=qt(Ol),t.splitBgEventSegs=qt(Ol),t.splitBusinessHourSegs=qt(Ol),t.splitNowIndicatorSegs=qt(Ol),t.splitDateSelectionSegs=qt(Ol),t.splitEventDrag=qt(Al),t.splitEventResize=qt(Al),t.rootElRef=ko(),t.cellElRefs=new Fa,t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=this.context.options.nowIndicator&&t.slatCoords&&t.slatCoords.safeComputeTop(t.nowDate),r=t.cells.length,o=this.splitFgEventSegs(t.fgEventSegs,r),i=this.splitBgEventSegs(t.bgEventSegs,r),a=this.splitBusinessHourSegs(t.businessHourSegs,r),s=this.splitNowIndicatorSegs(t.nowIndicatorSegs,r),l=this.splitDateSelectionSegs(t.dateSelectionSegs,r),u=this.splitEventDrag(t.eventDrag,r),c=this.splitEventResize(t.eventResize,r);return _o("div",{className:"fc-timegrid-cols",ref:this.rootElRef},_o("table",{role:"presentation",style:{minWidth:t.tableMinWidth,width:t.clientWidth}},t.tableColGroupNode,_o("tbody",{role:"presentation"},_o("tr",{role:"row"},t.axis&&_o("td",{"aria-hidden":!0,className:"fc-timegrid-col fc-timegrid-axis"},_o("div",{className:"fc-timegrid-col-frame"},_o("div",{className:"fc-timegrid-now-indicator-container"},"number"==typeof n&&_o(is,{isAxis:!0,date:t.nowDate},(function(e,t,r,o){return _o("div",{ref:e,className:["fc-timegrid-now-indicator-arrow"].concat(t).join(" "),style:{top:n}},o)}))))),t.cells.map((function(n,r){return _o(Zl,{key:n.key,elRef:e.cellElRefs.createRef(n.key),dateProfile:t.dateProfile,date:n.date,nowDate:t.nowDate,todayRange:t.todayRange,extraHookProps:n.extraHookProps,extraDataAttrs:n.extraDataAttrs,extraClassNames:n.extraClassNames,extraDateSpan:n.extraDateSpan,fgEventSegs:o[r],bgEventSegs:i[r],businessHourSegs:a[r],nowIndicatorSegs:s[r],dateSelectionSegs:l[r],eventDrag:u[r],eventResize:c[r],slatCoords:t.slatCoords,eventSelection:t.eventSelection,forPrint:t.forPrint})}))))))},t.prototype.componentDidMount=function(){this.updateCoords()},t.prototype.componentDidUpdate=function(){this.updateCoords()},t.prototype.updateCoords=function(){var e,t=this.props;t.onColCoords&&null!==t.clientWidth&&t.onColCoords(new So(this.rootElRef.current,(e=this.cellElRefs.currentMap,t.cells.map((function(t){return e[t.key]}))),!0,!1))},t}(Uo);var Jl=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.processSlotOptions=qt(Ql),t.state={slatCoords:null},t.handleRootEl=function(e){e?t.context.registerInteractiveComponent(t,{el:e,isHitComboAllowed:t.props.isHitComboAllowed}):t.context.unregisterInteractiveComponent(t)},t.handleScrollRequest=function(e){var n=t.props.onScrollTopRequest,r=t.state.slatCoords;if(n&&r){if(e.time){var o=r.computeTimeTop(e.time);(o=Math.ceil(o))&&(o+=1),n(o)}return!0}return!1},t.handleColCoords=function(e){t.colCoords=e},t.handleSlatCoords=function(e){t.setState({slatCoords:e}),t.props.onSlatCoords&&t.props.onSlatCoords(e)},t}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.state;return _o("div",{className:"fc-timegrid-body",ref:this.handleRootEl,style:{width:e.clientWidth,minWidth:e.tableMinWidth}},_o(Hl,{axis:e.axis,dateProfile:e.dateProfile,slatMetas:e.slatMetas,clientWidth:e.clientWidth,minHeight:e.expandRows?e.clientHeight:"",tableMinWidth:e.tableMinWidth,tableColGroupNode:e.axis?e.tableColGroupNode:null,onCoords:this.handleSlatCoords}),_o($l,{cells:e.cells,axis:e.axis,dateProfile:e.dateProfile,businessHourSegs:e.businessHourSegs,bgEventSegs:e.bgEventSegs,fgEventSegs:e.fgEventSegs,dateSelectionSegs:e.dateSelectionSegs,eventSelection:e.eventSelection,eventDrag:e.eventDrag,eventResize:e.eventResize,todayRange:e.todayRange,nowDate:e.nowDate,nowIndicatorSegs:e.nowIndicatorSegs,clientWidth:e.clientWidth,tableMinWidth:e.tableMinWidth,tableColGroupNode:e.tableColGroupNode,slatCoords:t.slatCoords,onColCoords:this.handleColCoords,forPrint:e.forPrint}))},t.prototype.componentDidMount=function(){this.scrollResponder=this.context.createScrollResponder(this.handleScrollRequest)},t.prototype.componentDidUpdate=function(e){this.scrollResponder.update(e.dateProfile!==this.props.dateProfile)},t.prototype.componentWillUnmount=function(){this.scrollResponder.detach()},t.prototype.queryHit=function(e,t){var n=this.context,o=n.dateEnv,i=n.options,a=this.colCoords,s=this.props.dateProfile,l=this.state.slatCoords,u=this.processSlotOptions(this.props.slotDuration,i.snapDuration),c=u.snapDuration,d=u.snapsPerSlot,p=a.leftToIndex(e),f=l.positions.topToIndex(t);if(null!=p&&null!=f){var h=this.props.cells[p],v=l.positions.tops[f],g=l.positions.getHeight(f),m=(t-v)/g,y=f*d+Math.floor(m*d),E=this.props.cells[p].date,S=Ot(s.slotMinTime,At(c,y)),b=o.add(E,S),D=o.add(b,c);return{dateProfile:s,dateSpan:r({range:{start:b,end:D},allDay:!1},h.extraDateSpan),dayEl:a.els[p],rect:{left:a.lefts[p],right:a.rights[p],top:v,bottom:v+g},layer:0}}return null},t}(Bo);function Ql(e,t){var n=t||e,r=Wt(e,n);return null===r&&(n=e,r=1),{snapDuration:n,snapsPerSlot:r}}var eu=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.sliceRange=function(e,t){for(var n=[],r=0;r<t.length;r+=1){var o=jn(e,t[r]);o&&n.push({start:o.start,end:o.end,isStart:o.start.valueOf()===e.start.valueOf(),isEnd:o.end.valueOf()===e.end.valueOf(),col:r})}return n},t}(xa),tu=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.buildDayRanges=qt(nu),t.slicer=new eu,t.timeColsRef=ko(),t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=this.context,o=t.dateProfile,i=t.dayTableModel,a=n.options.nowIndicator,s=this.buildDayRanges(i,o,n.dateEnv);return _o(Ca,{unit:a?"minute":"day"},(function(l,u){return _o(Jl,r({ref:e.timeColsRef},e.slicer.sliceProps(t,o,null,n,s),{forPrint:t.forPrint,axis:t.axis,dateProfile:o,slatMetas:t.slatMetas,slotDuration:t.slotDuration,cells:i.cells[0],tableColGroupNode:t.tableColGroupNode,tableMinWidth:t.tableMinWidth,clientWidth:t.clientWidth,clientHeight:t.clientHeight,expandRows:t.expandRows,nowDate:l,nowIndicatorSegs:a&&e.slicer.sliceNowDate(l,n,s),todayRange:u,onScrollTopRequest:t.onScrollTopRequest,onSlatCoords:t.onSlatCoords}))}))},t}(Bo);function nu(e,t,n){for(var r=[],o=0,i=e.headerDates;o<i.length;o++){var a=i[o];r.push({start:n.add(a,t.slotMinTime),end:n.add(a,t.slotMaxTime)})}return r}var ru=[{hours:1},{minutes:30},{minutes:15},{seconds:30},{seconds:15}];function ou(e,t,n,r,o){for(var i=new Date(0),a=e,s=Nt(0),l=n||function(e){var t,n,r;for(t=ru.length-1;t>=0;t-=1)if(null!==(r=Wt(n=Nt(ru[t]),e))&&r>1)return n;return e}(r),u=[];Ut(a)<Ut(t);){var c=o.add(i,a),d=null!==Wt(s,l);u.push({date:c,time:a,key:c.toISOString(),isoTimeStr:zt(c),isLabeled:d}),a=Ot(a,r),s=Ot(s,r)}return u}var iu=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.buildTimeColsModel=qt(au),t.buildSlatMetas=qt(ou),t}return n(t,e),t.prototype.render=function(){var e=this,t=this.context,n=t.options,o=t.dateEnv,i=t.dateProfileGenerator,a=this.props,s=a.dateProfile,l=this.buildTimeColsModel(s,i),u=this.allDaySplitter.splitProps(a),c=this.buildSlatMetas(s.slotMinTime,s.slotMaxTime,n.slotLabelInterval,n.slotDuration,o),d=n.dayMinWidth,p=!d,f=d,h=n.dayHeaders&&_o(Ra,{dates:l.headerDates,dateProfile:s,datesRepDistinctDays:!0,renderIntro:p?this.renderHeadAxis:null}),v=!1!==n.allDaySlot&&function(t){return _o(Sl,r({},u.allDay,{dateProfile:s,dayTableModel:l,nextDayThreshold:n.nextDayThreshold,tableMinWidth:t.tableMinWidth,colGroupNode:t.tableColGroupNode,renderRowIntro:p?e.renderTableRowAxis:null,showWeekNumbers:!1,expandRows:!1,headerAlignElRef:e.headerElRef,clientWidth:t.clientWidth,clientHeight:t.clientHeight,forPrint:a.forPrint},e.getAllDayMaxEventProps()))},g=function(t){return _o(tu,r({},u.timed,{dayTableModel:l,dateProfile:s,axis:p,slotDuration:n.slotDuration,slatMetas:c,forPrint:a.forPrint,tableColGroupNode:t.tableColGroupNode,tableMinWidth:t.tableMinWidth,clientWidth:t.clientWidth,clientHeight:t.clientHeight,onSlatCoords:e.handleSlatCoords,expandRows:t.expandRows,onScrollTopRequest:e.handleScrollTopRequest}))};return f?this.renderHScrollLayout(h,v,g,l.colCnt,d,c,this.state.slatCoords):this.renderSimpleLayout(h,v,g)},t}(Ml);function au(e,t){var n=new Ta(e.renderRange,t);return new ka(n,!1)}var su=zo({initialView:"timeGridWeek",optionRefiners:{allDaySlot:Boolean},views:{timeGrid:{component:iu,usesMinMaxTime:!0,allDaySlot:!0,slotDuration:"00:30:00",slotEventOverlap:!0},timeGridDay:{type:"timeGrid",duration:{days:1}},timeGridWeek:{type:"timeGrid",duration:{weeks:1}}}}),lu=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.state={textId:xe()},t}return n(t,e),t.prototype.render=function(){var e=this.context,t=e.theme,n=e.dateEnv,o=e.options,i=e.viewApi,a=this.props,s=a.cellId,l=a.dayDate,u=a.todayRange,c=this.state.textId,d=ro(l,u),p=o.listDayFormat?n.format(l,o.listDayFormat):"",f=o.listDaySideFormat?n.format(l,o.listDaySideFormat):"",h=r({date:n.toDate(l),view:i,textId:c,text:p,sideText:f,navLinkAttrs:so(this.context,l),sideNavLinkAttrs:so(this.context,l,"day",!1)},d),v=["fc-list-day"].concat(oo(d,t));return _o(Yo,{hookProps:h,classNames:o.dayHeaderClassNames,content:o.dayHeaderContent,defaultContent:uu,didMount:o.dayHeaderDidMount,willUnmount:o.dayHeaderWillUnmount},(function(e,n,r,o){return _o("tr",{ref:e,className:v.concat(n).join(" "),"data-date":Bt(l)},_o("th",{scope:"colgroup",colSpan:3,id:s,"aria-labelledby":c},_o("div",{className:"fc-list-day-cushion "+t.getClass("tableCellShaded"),ref:r},o)))}))},t}(Uo);function uu(e){return _o(xo,null,e.text&&_o("a",r({id:e.textId,className:"fc-list-day-text"},e.navLinkAttrs),e.text),e.sideText&&_o("a",r({"aria-hidden":!0,className:"fc-list-day-side-text"},e.sideNavLinkAttrs),e.sideText))}var cu=ln({hour:"numeric",minute:"2-digit",meridiem:"short"}),du=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t.prototype.render=function(){var e=this.props,t=this.context,n=e.seg,o=e.timeHeaderId,i=e.eventHeaderId,a=e.dateHeaderId,s=t.options.eventTimeFormat||cu;return _o(ns,{seg:n,timeText:"",disableDragging:!0,disableResizing:!0,defaultContent:function(){return function(e,t){var n=cr(e,t);return _o("a",r({},n),e.eventRange.def.title)}(n,t)},isPast:e.isPast,isFuture:e.isFuture,isToday:e.isToday,isSelected:e.isSelected,isDragging:e.isDragging,isResizing:e.isResizing,isDateSelecting:e.isDateSelecting},(function(e,r,l,u,c){return _o("tr",{className:["fc-list-event",c.event.url?"fc-event-forced-url":""].concat(r).join(" "),ref:e},function(e,t,n,r,o){var i=n.options;if(!1!==i.displayEventTime){var a=e.eventRange.def,s=e.eventRange.instance,l=!1,u=void 0;if(a.allDay?l=!0:Vn(e.eventRange.range)?e.isStart?u=ar(e,t,n,null,null,s.range.start,e.end):e.isEnd?u=ar(e,t,n,null,null,e.start,s.range.end):l=!0:u=ar(e,t,n),l){var c={text:n.options.allDayText,view:n.viewApi};return _o(Yo,{hookProps:c,classNames:i.allDayClassNames,content:i.allDayContent,defaultContent:pu,didMount:i.allDayDidMount,willUnmount:i.allDayWillUnmount},(function(e,t,n,i){return _o("td",{ref:e,headers:r+" "+o,className:["fc-list-event-time"].concat(t).join(" ")},i)}))}return _o("td",{className:"fc-list-event-time"},u)}return null}(n,s,t,o,a),_o("td",{"aria-hidden":!0,className:"fc-list-event-graphic"},_o("span",{className:"fc-list-event-dot",style:{borderColor:c.borderColor||c.backgroundColor}})),_o("td",{ref:l,headers:i+" "+a,className:"fc-list-event-title"},u))}))},t}(Uo);function pu(e){return e.text}var fu=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.computeDateVars=qt(vu),t.eventStoreToSegs=qt(t._eventStoreToSegs),t.state={timeHeaderId:xe(),eventHeaderId:xe(),dateHeaderIdRoot:xe()},t.setRootEl=function(e){e?t.context.registerInteractiveComponent(t,{el:e}):t.context.unregisterInteractiveComponent(t)},t}return n(t,e),t.prototype.render=function(){var e=this,t=this.props,n=this.context,r=["fc-list",n.theme.getClass("table"),!1!==n.options.stickyHeaderDates?"fc-list-sticky":""],o=this.computeDateVars(t.dateProfile),i=o.dayDates,a=o.dayRanges,s=this.eventStoreToSegs(t.eventStore,t.eventUiBases,a);return _o(ti,{viewSpec:n.viewSpec,elRef:this.setRootEl},(function(n,o){return _o("div",{ref:n,className:r.concat(o).join(" ")},_o(Va,{liquid:!t.isHeightAuto,overflowX:t.isHeightAuto?"visible":"hidden",overflowY:t.isHeightAuto?"visible":"auto"},s.length>0?e.renderSegList(s,i):e.renderEmptyMessage()))}))},t.prototype.renderEmptyMessage=function(){var e=this.context,t=e.options,n=e.viewApi,r={text:t.noEventsText,view:n};return _o(Yo,{hookProps:r,classNames:t.noEventsClassNames,content:t.noEventsContent,defaultContent:hu,didMount:t.noEventsDidMount,willUnmount:t.noEventsWillUnmount},(function(e,t,n,r){return _o("div",{className:["fc-list-empty"].concat(t).join(" "),ref:e},_o("div",{className:"fc-list-empty-cushion",ref:n},r))}))},t.prototype.renderSegList=function(e,t){var n=this.context,o=n.theme,i=n.options,a=this.state,s=a.timeHeaderId,l=a.eventHeaderId,u=a.dateHeaderIdRoot,c=function(e){var t,n,r=[];for(t=0;t<e.length;t+=1)(r[(n=e[t]).dayIndex]||(r[n.dayIndex]=[])).push(n);return r}(e);return _o(Ca,{unit:"day"},(function(e,n){for(var a=[],d=0;d<c.length;d+=1){var p=c[d];if(p){var f=Bt(t[d]),h=u+"-"+f;a.push(_o(lu,{key:f,cellId:h,dayDate:t[d],todayRange:n}));for(var v=0,g=p=tr(p,i.eventOrder);v<g.length;v++){var m=g[v];a.push(_o(du,r({key:f+":"+m.eventRange.instance.instanceId,seg:m,isDragging:!1,isResizing:!1,isDateSelecting:!1,isSelected:!1,timeHeaderId:s,eventHeaderId:l,dateHeaderId:h},sr(m,n,e))))}}}return _o("table",{className:"fc-list-table "+o.getClass("table")},_o("thead",null,_o("tr",null,_o("th",{scope:"col",id:s},i.timeHint),_o("th",{scope:"col","aria-hidden":!0}),_o("th",{scope:"col",id:l},i.eventHint))),_o("tbody",null,a))}))},t.prototype._eventStoreToSegs=function(e,t,n){return this.eventRangesToSegs(Xn(e,t,this.props.dateProfile.activeRange,this.context.options.nextDayThreshold).fg,n)},t.prototype.eventRangesToSegs=function(e,t){for(var n=[],r=0,o=e;r<o.length;r++){var i=o[r];n.push.apply(n,this.eventRangeToSegs(i,t))}return n},t.prototype.eventRangeToSegs=function(e,t){var n,r,o,i=this.context.dateEnv,a=this.context.options.nextDayThreshold,s=e.range,l=e.def.allDay,u=[];for(n=0;n<t.length;n+=1)if((r=jn(s,t[n]))&&(o={component:this,eventRange:e,start:r.start,end:r.end,isStart:e.isStart&&r.start.valueOf()===s.start.valueOf(),isEnd:e.isEnd&&r.end.valueOf()===s.end.valueOf(),dayIndex:n},u.push(o),!o.isEnd&&!l&&n+1<t.length&&s.end<i.add(t[n+1].start,a))){o.end=s.end,o.isEnd=!0;break}return u},t}(Bo);function hu(e){return e.text}function vu(e){for(var t=lt(e.renderRange.start),n=e.renderRange.end,r=[],o=[];t<n;)r.push(t),o.push({start:t,end:tt(t,1)}),t=tt(t,1);return{dayDates:r,dayRanges:o}}function gu(e){return!1===e?null:ln(e)}var mu=zo({optionRefiners:{listDayFormat:gu,listDaySideFormat:gu,noEventsClassNames:yn,noEventsContent:yn,noEventsDidMount:yn,noEventsWillUnmount:yn},views:{list:{component:fu,buttonTextKey:"list",listDayFormat:{month:"long",day:"numeric",year:"numeric"}},listDay:{type:"list",duration:{days:1},listDayFormat:{weekday:"long"}},listWeek:{type:"list",duration:{weeks:1},listDayFormat:{weekday:"long"},listDaySideFormat:{month:"long",day:"numeric",year:"numeric"}},listMonth:{type:"list",duration:{month:1},listDaySideFormat:{weekday:"long"}},listYear:{type:"list",duration:{year:1},listDaySideFormat:{weekday:"long"}}}}),yu=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return n(t,e),t}(wo);yu.prototype.classes={root:"fc-theme-bootstrap",table:"table-bordered",tableCellShaded:"table-active",buttonGroup:"btn-group",button:"btn btn-primary",buttonActive:"active",popover:"popover",popoverHeader:"popover-header",popoverContent:"popover-body"},yu.prototype.baseIconClass="fa",yu.prototype.iconClasses={close:"fa-times",prev:"fa-chevron-left",next:"fa-chevron-right",prevYear:"fa-angle-double-left",nextYear:"fa-angle-double-right"},yu.prototype.rtlIconClasses={prev:"fa-chevron-right",next:"fa-chevron-left",prevYear:"fa-angle-double-right",nextYear:"fa-angle-double-left"},yu.prototype.iconOverrideOption="bootstrapFontAwesome",yu.prototype.iconOverrideCustomButtonOption="bootstrapFontAwesome",yu.prototype.iconOverridePrefix="fa-";var Eu=zo({themeClasses:{bootstrap:yu}}),Su="https://www.googleapis.com/calendar/v3/calendars";var bu=zo({eventSourceDefs:[{parseMeta:function(e){var t=e.googleCalendarId;return!t&&e.url&&(t=function(e){var t;if(/^[^/]+@([^/.]+\.)*(google|googlemail|gmail)\.com$/.test(e))return e;if((t=/^https:\/\/www.googleapis.com\/calendar\/v3\/calendars\/([^/]*)/.exec(e))||(t=/^https?:\/\/www.google.com\/calendar\/feeds\/([^/]*)/.exec(e)))return decodeURIComponent(t[1]);return null}(e.url)),t?{googleCalendarId:t,googleCalendarApiKey:e.googleCalendarApiKey,googleCalendarApiBase:e.googleCalendarApiBase,extraParams:e.extraParams}:null},fetch:function(e,t,n){var o=e.context,i=o.dateEnv,a=o.options,s=e.eventSource.meta,l=s.googleCalendarApiKey||a.googleCalendarApiKey;if(l){var u=function(e){var t=e.googleCalendarApiBase;t||(t=Su);return t+"/"+encodeURIComponent(e.googleCalendarId)+"/events"}(s),c=s.extraParams,d="function"==typeof c?c():c,p=function(e,t,n,o){var i,a,s;o.canComputeOffset?(a=o.formatIso(e.start),s=o.formatIso(e.end)):(a=tt(e.start,-1).toISOString(),s=tt(e.end,1).toISOString());i=r(r({},n||{}),{key:t,timeMin:a,timeMax:s,singleEvents:!0,maxResults:9999}),"local"!==o.timeZone&&(i.timeZone=o.timeZone);return i}(e.range,l,d,i);_i("GET",u,p,(function(e,r){var o,i;e.error?n({message:"Google Calendar API: "+e.error.message,errors:e.error.errors,xhr:r}):t({rawEvents:(o=e.items,i=p.timeZone,o.map((function(e){return function(e,t){var n=e.htmlLink||null;n&&t&&(n=function(e,t){return e.replace(/(\?.*?)?(#|$)/,(function(e,n,r){return(n?n+"&":"?")+t+r}))}(n,"ctz="+t));return{id:e.id,title:e.summary,start:e.start.dateTime||e.start.date,end:e.end.dateTime||e.end.date,url:n,location:e.location,description:e.description,attachments:e.attachments||[],extendedProps:(e.extendedProperties||{}).shared||{}}}(e,i)}))),xhr:r})}),(function(e,t){n({message:e,xhr:t})}))}else n({message:"Specify a googleCalendarApiKey. See http://fullcalendar.io/docs/google_calendar/"})}}],optionRefiners:{googleCalendarApiKey:String},eventSourceRefiners:{googleCalendarApiKey:String,googleCalendarId:String,googleCalendarApiBase:String,extraParams:yn}});return xi.push($s,Cl,su,mu,Eu,bu),e.BASE_OPTION_DEFAULTS=cn,e.BASE_OPTION_REFINERS=un,e.BaseComponent=Uo,e.BgEvent=ds,e.BootstrapTheme=yu,e.Calendar=Cs,e.CalendarApi=kr,e.CalendarContent=fa,e.CalendarDataManager=Ni,e.CalendarDataProvider=ji,e.CalendarRoot=ga,e.Component=Ro,e.ContentHook=Xo,e.CustomContentRenderContext=Zo,e.DateComponent=Bo,e.DateEnv=Lr,e.DateProfileGenerator=ai,e.DayCellContent=ss,e.DayCellRoot=us,e.DayGridView=bl,e.DayHeader=Ra,e.DaySeriesModel=Ta,e.DayTable=Sl,e.DayTableModel=ka,e.DayTableSlicer=El,e.DayTimeCols=tu,e.DayTimeColsSlicer=eu,e.DayTimeColsView=iu,e.DelayedRunner=Mi,e.Draggable=Zs,e.ElementDragging=oa,e.ElementScrollController=Do,e.Emitter=Eo,e.EventApi=xr,e.EventRoot=ns,e.EventSourceApi=ye,e.FeaturefulElementDragging=Os,e.Fragment=xo,e.Interaction=ea,e.ListView=fu,e.MoreLinkRoot=ms,e.MountHook=$o,e.NamedTimeZoneImpl=Gi,e.NowIndicatorRoot=is,e.NowTimer=Ca,e.PointerDragging=Ts,e.PositionCache=So,e.RefMap=Fa,e.RenderHook=Yo,e.ScrollController=bo,e.ScrollResponder=Ho,e.Scroller=Va,e.SegHierarchy=qi,e.SimpleScrollGrid=ts,e.Slicer=xa,e.Splitter=to,e.StandardEvent=rs,e.Table=ml,e.TableDateCell=Sa,e.TableDowCell=Da,e.TableView=Js,e.Theme=wo,e.ThirdPartyDraggable=Ks,e.TimeCols=Jl,e.TimeColsSlatsCoords=Pl,e.TimeColsView=Ml,e.ViewApi=Cr,e.ViewContextType=Oo,e.ViewRoot=ti,e.WeekNumberRoot=fs,e.WindowScrollController=Co,e.addDays=tt,e.addDurations=Ot,e.addMs=nt,e.addWeeks=et,e.allowContextMenu=ze,e.allowSelection=Fe,e.applyMutationToEventStore=Sr,e.applyStyle=we,e.applyStyleProp=Re,e.asCleanDays=function(e){return e.years||e.months||e.milliseconds?0:e.days},e.asRoughMinutes=function(e){return Ut(e)/6e4},e.asRoughMs=Ut,e.asRoughSeconds=function(e){return Ut(e)/1e3},e.binarySearch=Qi,e.buildClassNameNormalizer=Jo,e.buildDayRanges=nu,e.buildDayTableModel=Dl,e.buildEntryKey=Zi,e.buildEventApis=Ir,e.buildEventRangeKey=ur,e.buildHashFromArray=function(e,t){for(var n={},r=0;r<e.length;r+=1){var o=t(e[r],r);n[o[0]]=o[1]}return n},e.buildIsoString=Ft,e.buildNavLinkAttrs=so,e.buildSegCompareObj=nr,e.buildSegTimeText=ar,e.buildSlatMetas=ou,e.buildTimeColsModel=au,e.collectFromHash=kt,e.combineEventUis=xn,e.compareByFieldSpec=qe,e.compareByFieldSpecs=Ge,e.compareNumbers=Ke,e.compareObjs=_t,e.computeEarliestSegStart=Ss,e.computeEdges=ho,e.computeFallbackHeaderFormat=ma,e.computeHeightAndMargins=function(e){return e.getBoundingClientRect().height+function(e){var t=window.getComputedStyle(e);return parseInt(t.marginTop,10)+parseInt(t.marginBottom,10)}(e)},e.computeInnerRect=vo,e.computeRect=go,e.computeSegDraggable=rr,e.computeSegEndResizable=ir,e.computeSegStartResizable=or,e.computeShrinkWidth=Ba,e.computeSmallestCellWidth=Je,e.computeVisibleDayRange=Wn,e.config=ia,e.constrainPoint=Kr,e.createAriaClickAttrs=He,e.createContext=Mo,e.createDuration=Nt,e.createElement=_o,e.createEmptyEventStore=Dn,e.createEventInstance=mt,e.createEventUi=kn,e.createFormatter=ln,e.createPlugin=zo,e.createPortal=Io,e.createRef=ko,e.diffDates=Fn,e.diffDayAndTime=it,e.diffDays=ot,e.diffPoints=Jr,e.diffWeeks=rt,e.diffWholeDays=st,e.diffWholeWeeks=at,e.disableCursor=Ue,e.elementClosest=Se,e.elementMatches=be,e.enableCursor=We,e.eventTupleToStore=Sn,e.filterEventStoreDefs=wn,e.filterHash=St,e.findDirectChildren=function(e,t){for(var n=e instanceof HTMLElement?[e]:e,r=[],o=0;o<n.length;o+=1)for(var i=n[o].children,a=0;a<i.length;a+=1){var s=i[a];t&&!be(s,t)||r.push(s)}return r},e.findElements=De,e.flexibleCompare=Ye,e.flushToDom=Po,e.formatDate=function(e,t){void 0===t&&(t={});var n=jr(t),r=ln(t),o=n.createMarkerMeta(e);return o?n.format(o.marker,r,{forcedTzo:o.forcedTzo}):""},e.formatDayString=Bt,e.formatIsoTimeString=zt,e.formatRange=function(e,t,n){var r=jr("object"==typeof n&&n?n:{}),o=ln(n),i=r.createMarkerMeta(e),a=r.createMarkerMeta(t);return i&&a?r.formatRange(i.marker,a.marker,o,{forcedStartTzo:i.forcedTzo,forcedEndTzo:a.forcedTzo,isEndExclusive:n.isEndExclusive,defaultSeparator:cn.defaultRangeSeparator}):""},e.getAllowYScrolling=ja,e.getCanVGrowWithinCell=Qr,e.getClippingParents=mo,e.getDateMeta=ro,e.getDayClassNames=oo,e.getDefaultEventEnd=Er,e.getElRoot=Te,e.getElSeg=Jn,e.getEntrySpanEnd=Yi,e.getEventClassNames=lr,e.getEventTargetViaRoot=_e,e.getIsRtlScrollbarOnLeft=co,e.getRectCenter=$r,e.getRelevantEvents=bn,e.getScrollGridClassNames=Ka,e.getScrollbarWidths=po,e.getSectionClassNames=$a,e.getSectionHasLiquidHeight=za,e.getSegAnchorAttrs=cr,e.getSegMeta=sr,e.getSlotClassNames=function(e,t){var n=["fc-slot","fc-slot-"+Qe[e.dow]];return e.isDisabled?n.push("fc-slot-disabled"):(e.isToday&&(n.push("fc-slot-today"),n.push(t.getClass("today"))),e.isPast&&n.push("fc-slot-past"),e.isFuture&&n.push("fc-slot-future")),n},e.getStickyFooterScrollbar=es,e.getStickyHeaderDates=Qa,e.getUnequalProps=Rt,e.getUniqueDomId=xe,e.globalLocales=Ur,e.globalPlugins=xi,e.greatestDurationDenominator=Vt,e.groupIntersectingEntries=Xi,e.guid=Le,e.hasBgRendering=Kn,e.hasShrinkWidth=Xa,e.identity=yn,e.interactionSettingsStore=ra,e.interactionSettingsToStore=na,e.intersectRanges=jn,e.intersectRects=Xr,e.intersectSpans=$i,e.isArraysEqual=Gt,e.isColPropsEqual=qa,e.isDateSelectionValid=Pa,e.isDateSpansEqual=fr,e.isInt=$e,e.isInteractionValid=Ia,e.isMultiDayRange=Vn,e.isPropsEqual=wt,e.isPropsValid=Ha,e.isValidDate=vt,e.joinSpans=Ki,e.listenBySelector=Ie,e.mapHash=bt,e.memoize=qt,e.memoizeArraylike=function(e,t,n){var r=this,o=[],i=[];return function(a){for(var s=o.length,l=a.length,u=0;u<s;u+=1)if(a[u]){if(!Gt(o[u],a[u])){n&&n(i[u]);var c=e.apply(r,a[u]);t&&t(c,i[u])||(i[u]=c)}}else n&&n(i[u]);for(;u<l;u+=1)i[u]=e.apply(r,a[u]);return o=a,i.splice(l),i}},e.memoizeHashlike=function(e,t,n){var r=this,o={},i={};return function(a){var s={};for(var l in a)if(i[l])if(Gt(o[l],a[l]))s[l]=i[l];else{n&&n(i[l]);var u=e.apply(r,a[l]);s[l]=t&&t(u,i[l])?i[l]:u}else s[l]=e.apply(r,a[l]);return o=a,i=s,s}},e.memoizeObjArg=Yt,e.mergeEventStores=Cn,e.multiplyDuration=At,e.padStart=Ze,e.parseBusinessHours=Yr,e.parseClassNames=Rn,e.parseDragMeta=sa,e.parseEventDef=Ln,e.parseFieldSpecs=je,e.parseMarker=Ar,e.pointInsideRect=Zr,e.preventContextMenu=Be,e.preventDefault=Me,e.preventSelection=Ve,e.rangeContainsMarker=Zn,e.rangeContainsRange=Yn,e.rangesEqual=Gn,e.rangesIntersect=qn,e.refineEventDef=On,e.refineProps=mn,e.removeElement=Ee,e.removeExact=function(e,t){for(var n=0,r=0;r<e.length;)e[r]===t?(e.splice(r,1),n+=1):r+=1;return n},e.render=To,e.renderChunkContent=Ga,e.renderFill=cs,e.renderMicroColGroup=Ya,e.renderScrollShim=Ja,e.requestJson=_i,e.sanitizeShrinkWidth=Za,e.setElSeg=$n,e.setRef=Fo,e.sliceEventStore=Xn,e.sliceEvents=function(e,t){return Xn(e.eventStore,e.eventUiBases,e.dateProfile.activeRange,t?e.nextDayThreshold:null).fg},e.sortEventSegs=tr,e.startOfDay=lt,e.translateRect=function(e,t,n){return{left:e.left+t,right:e.right+t,top:e.top+n,bottom:e.bottom+n}},e.triggerDateSelect=mr,e.unmountComponentAtNode=No,e.unpromisify=yo,e.version="5.10.1",e.whenTransitionDone=Ne,e.wholeDivideDurations=Wt,Object.defineProperty(e,"__esModule",{value:!0}),e}({});
[].push.apply(FullCalendar.globalLocales,function(){"use strict";function e(e){return"Tag"===e||"Monat"===e?"r":"Jahr"===e?"s":""}function t(e){return"Tag"===e||"Monat"===e?"r":"Jahr"===e?"s":""}return[{code:"af",week:{dow:1,doy:4},buttonText:{prev:"Vorige",next:"Volgende",today:"Vandag",year:"Jaar",month:"Maand",week:"Week",day:"Dag",list:"Agenda"},allDayText:"Heeldag",moreLinkText:"Addisionele",noEventsText:"Daar is geen gebeurtenisse nie"},{code:"ar-dz",week:{dow:0,doy:4},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"ar-kw",week:{dow:0,doy:12},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"ar-ly",week:{dow:6,doy:12},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"ar-ma",week:{dow:6,doy:12},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"ar-sa",week:{dow:0,doy:6},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"ar-tn",week:{dow:1,doy:4},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"ar",week:{dow:6,doy:12},direction:"rtl",buttonText:{prev:"السابق",next:"التالي",today:"اليوم",month:"شهر",week:"أسبوع",day:"يوم",list:"أجندة"},weekText:"أسبوع",allDayText:"اليوم كله",moreLinkText:"أخرى",noEventsText:"أي أحداث لعرض"},{code:"az",week:{dow:1,doy:4},buttonText:{prev:"Əvvəl",next:"Sonra",today:"Bu Gün",month:"Ay",week:"Həftə",day:"Gün",list:"Gündəm"},weekText:"Həftə",allDayText:"Bütün Gün",moreLinkText:function(e){return"+ daha çox "+e},noEventsText:"Göstərmək üçün hadisə yoxdur"},{code:"bg",week:{dow:1,doy:7},buttonText:{prev:"назад",next:"напред",today:"днес",month:"Месец",week:"Седмица",day:"Ден",list:"График"},allDayText:"Цял ден",moreLinkText:function(e){return"+още "+e},noEventsText:"Няма събития за показване"},{code:"bn",week:{dow:0,doy:6},buttonText:{prev:"পেছনে",next:"সামনে",today:"আজ",month:"মাস",week:"সপ্তাহ",day:"দিন",list:"তালিকা"},weekText:"সপ্তাহ",allDayText:"সারাদিন",moreLinkText:function(e){return"+অন্যান্য "+e},noEventsText:"কোনো ইভেন্ট নেই"},{code:"bs",week:{dow:1,doy:7},buttonText:{prev:"Prošli",next:"Sljedeći",today:"Danas",month:"Mjesec",week:"Sedmica",day:"Dan",list:"Raspored"},weekText:"Sed",allDayText:"Cijeli dan",moreLinkText:function(e){return"+ još "+e},noEventsText:"Nema događaja za prikazivanje"},{code:"ca",week:{dow:1,doy:4},buttonText:{prev:"Anterior",next:"Següent",today:"Avui",month:"Mes",week:"Setmana",day:"Dia",list:"Agenda"},weekText:"Set",allDayText:"Tot el dia",moreLinkText:"més",noEventsText:"No hi ha esdeveniments per mostrar"},{code:"cs",week:{dow:1,doy:4},buttonText:{prev:"Dříve",next:"Později",today:"Nyní",month:"Měsíc",week:"Týden",day:"Den",list:"Agenda"},weekText:"Týd",allDayText:"Celý den",moreLinkText:function(e){return"+další: "+e},noEventsText:"Žádné akce k zobrazení"},{code:"cy",week:{dow:1,doy:4},buttonText:{prev:"Blaenorol",next:"Nesaf",today:"Heddiw",year:"Blwyddyn",month:"Mis",week:"Wythnos",day:"Dydd",list:"Rhestr"},weekText:"Wythnos",allDayText:"Trwy'r dydd",moreLinkText:"Mwy",noEventsText:"Dim digwyddiadau"},{code:"da",week:{dow:1,doy:4},buttonText:{prev:"Forrige",next:"Næste",today:"I dag",month:"Måned",week:"Uge",day:"Dag",list:"Agenda"},weekText:"Uge",allDayText:"Hele dagen",moreLinkText:"flere",noEventsText:"Ingen arrangementer at vise"},{code:"de-at",week:{dow:1,doy:4},buttonText:{prev:"Zurück",next:"Vor",today:"Heute",year:"Jahr",month:"Monat",week:"Woche",day:"Tag",list:"Terminübersicht"},weekText:"KW",weekTextLong:"Woche",allDayText:"Ganztägig",moreLinkText:function(e){return"+ weitere "+e},noEventsText:"Keine Ereignisse anzuzeigen",buttonHints:{prev:t=>`Vorherige${e(t)} ${t}`,next:t=>`Nächste${e(t)} ${t}`,today:t=>"Tag"===t?"Heute":`Diese${e(t)} ${t}`},viewHint:e=>e+("Woche"===e?"n":"Monat"===e?"s":"es")+"ansicht",navLinkHint:"Gehe zu $0",moreLinkHint:e=>"Zeige "+(1===e?"ein weiteres Ereignis":e+" weitere Ereignisse"),closeHint:"Schließen",timeHint:"Uhrzeit",eventHint:"Ereignis"},{code:"de",week:{dow:1,doy:4},buttonText:{prev:"Zurück",next:"Vor",today:"Heute",year:"Jahr",month:"Monat",week:"Woche",day:"Tag",list:"Terminübersicht"},weekText:"KW",weekTextLong:"Woche",allDayText:"Ganztägig",moreLinkText:function(e){return"+ weitere "+e},noEventsText:"Keine Ereignisse anzuzeigen",buttonHints:{prev:e=>`Vorherige${t(e)} ${e}`,next:e=>`Nächste${t(e)} ${e}`,today:e=>"Tag"===e?"Heute":`Diese${t(e)} ${e}`},viewHint:e=>e+("Woche"===e?"n":"Monat"===e?"s":"es")+"ansicht",navLinkHint:"Gehe zu $0",moreLinkHint:e=>"Zeige "+(1===e?"ein weiteres Ereignis":e+" weitere Ereignisse"),closeHint:"Schließen",timeHint:"Uhrzeit",eventHint:"Ereignis"},{code:"el",week:{dow:1,doy:4},buttonText:{prev:"Προηγούμενος",next:"Επόμενος",today:"Σήμερα",month:"Μήνας",week:"Εβδομάδα",day:"Ημέρα",list:"Ατζέντα"},weekText:"Εβδ",allDayText:"Ολοήμερο",moreLinkText:"περισσότερα",noEventsText:"Δεν υπάρχουν γεγονότα προς εμφάνιση"},{code:"en-au",week:{dow:1,doy:4},buttonHints:{prev:"Previous $0",next:"Next $0",today:"This $0"},viewHint:"$0 view",navLinkHint:"Go to $0",moreLinkHint:e=>`Show ${e} more event${1===e?"":"s"}`},{code:"en-gb",week:{dow:1,doy:4},buttonHints:{prev:"Previous $0",next:"Next $0",today:"This $0"},viewHint:"$0 view",navLinkHint:"Go to $0",moreLinkHint:e=>`Show ${e} more event${1===e?"":"s"}`},{code:"en-nz",week:{dow:1,doy:4},buttonHints:{prev:"Previous $0",next:"Next $0",today:"This $0"},viewHint:"$0 view",navLinkHint:"Go to $0",moreLinkHint:e=>`Show ${e} more event${1===e?"":"s"}`},{code:"eo",week:{dow:1,doy:4},buttonText:{prev:"Antaŭa",next:"Sekva",today:"Hodiaŭ",month:"Monato",week:"Semajno",day:"Tago",list:"Tagordo"},weekText:"Sm",allDayText:"Tuta tago",moreLinkText:"pli",noEventsText:"Neniuj eventoj por montri"},{code:"es",week:{dow:0,doy:6},buttonText:{prev:"Ant",next:"Sig",today:"Hoy",month:"Mes",week:"Semana",day:"Día",list:"Agenda"},weekText:"Sm",allDayText:"Todo el día",moreLinkText:"más",noEventsText:"No hay eventos para mostrar"},{code:"es",week:{dow:1,doy:4},buttonText:{prev:"Ant",next:"Sig",today:"Hoy",month:"Mes",week:"Semana",day:"Día",list:"Agenda"},buttonHints:{prev:"$0 antes",next:"$0 siguiente",today:e=>"Día"===e?"Hoy":("Semana"===e?"Esta":"Este")+" "+e.toLocaleLowerCase()},viewHint:e=>"Vista "+("Semana"===e?"de la":"del")+" "+e.toLocaleLowerCase(),weekText:"Sm",weekTextLong:"Semana",allDayText:"Todo el día",moreLinkText:"más",moreLinkHint:e=>`Mostrar ${e} eventos más`,noEventsText:"No hay eventos para mostrar",navLinkHint:"Ir al $0",closeHint:"Cerrar",timeHint:"La hora",eventHint:"Evento"},{code:"et",week:{dow:1,doy:4},buttonText:{prev:"Eelnev",next:"Järgnev",today:"Täna",month:"Kuu",week:"Nädal",day:"Päev",list:"Päevakord"},weekText:"näd",allDayText:"Kogu päev",moreLinkText:function(e){return"+ veel "+e},noEventsText:"Kuvamiseks puuduvad sündmused"},{code:"eu",week:{dow:1,doy:7},buttonText:{prev:"Aur",next:"Hur",today:"Gaur",month:"Hilabetea",week:"Astea",day:"Eguna",list:"Agenda"},weekText:"As",allDayText:"Egun osoa",moreLinkText:"gehiago",noEventsText:"Ez dago ekitaldirik erakusteko"},{code:"fa",week:{dow:6,doy:12},direction:"rtl",buttonText:{prev:"قبلی",next:"بعدی",today:"امروز",month:"ماه",week:"هفته",day:"روز",list:"برنامه"},weekText:"هف",allDayText:"تمام روز",moreLinkText:function(e){return"بیش از "+e},noEventsText:"هیچ رویدادی به نمایش"},{code:"fi",week:{dow:1,doy:4},buttonText:{prev:"Edellinen",next:"Seuraava",today:"Tänään",month:"Kuukausi",week:"Viikko",day:"Päivä",list:"Tapahtumat"},weekText:"Vk",allDayText:"Koko päivä",moreLinkText:"lisää",noEventsText:"Ei näytettäviä tapahtumia"},{code:"fr",buttonText:{prev:"Précédent",next:"Suivant",today:"Aujourd'hui",year:"Année",month:"Mois",week:"Semaine",day:"Jour",list:"Mon planning"},weekText:"Sem.",allDayText:"Toute la journée",moreLinkText:"en plus",noEventsText:"Aucun événement à afficher"},{code:"fr-ch",week:{dow:1,doy:4},buttonText:{prev:"Précédent",next:"Suivant",today:"Courant",year:"Année",month:"Mois",week:"Semaine",day:"Jour",list:"Mon planning"},weekText:"Sm",allDayText:"Toute la journée",moreLinkText:"en plus",noEventsText:"Aucun événement à afficher"},{code:"fr",week:{dow:1,doy:4},buttonText:{prev:"Précédent",next:"Suivant",today:"Aujourd'hui",year:"Année",month:"Mois",week:"Semaine",day:"Jour",list:"Planning"},weekText:"Sem.",allDayText:"Toute la journée",moreLinkText:"en plus",noEventsText:"Aucun événement à afficher"},{code:"gl",week:{dow:1,doy:4},buttonText:{prev:"Ant",next:"Seg",today:"Hoxe",month:"Mes",week:"Semana",day:"Día",list:"Axenda"},weekText:"Sm",allDayText:"Todo o día",moreLinkText:"máis",noEventsText:"Non hai eventos para amosar"},{code:"he",direction:"rtl",buttonText:{prev:"הקודם",next:"הבא",today:"היום",month:"חודש",week:"שבוע",day:"יום",list:"סדר יום"},allDayText:"כל היום",moreLinkText:"אחר",noEventsText:"אין אירועים להצגה",weekText:"שבוע"},{code:"hi",week:{dow:0,doy:6},buttonText:{prev:"पिछला",next:"अगला",today:"आज",month:"महीना",week:"सप्ताह",day:"दिन",list:"कार्यसूची"},weekText:"हफ्ता",allDayText:"सभी दिन",moreLinkText:function(e){return"+अधिक "+e},noEventsText:"कोई घटनाओं को प्रदर्शित करने के लिए"},{code:"hr",week:{dow:1,doy:7},buttonText:{prev:"Prijašnji",next:"Sljedeći",today:"Danas",month:"Mjesec",week:"Tjedan",day:"Dan",list:"Raspored"},weekText:"Tje",allDayText:"Cijeli dan",moreLinkText:function(e){return"+ još "+e},noEventsText:"Nema događaja za prikaz"},{code:"hu",week:{dow:1,doy:4},buttonText:{prev:"vissza",next:"előre",today:"ma",month:"Hónap",week:"Hét",day:"Nap",list:"Lista"},weekText:"Hét",allDayText:"Egész nap",moreLinkText:"további",noEventsText:"Nincs megjeleníthető esemény"},{code:"hy-am",week:{dow:1,doy:4},buttonText:{prev:"Նախորդ",next:"Հաջորդ",today:"Այսօր",month:"Ամիս",week:"Շաբաթ",day:"Օր",list:"Օրվա ցուցակ"},weekText:"Շաբ",allDayText:"Ամբողջ օր",moreLinkText:function(e){return"+ ևս "+e},noEventsText:"Բացակայում է իրադարձությունը ցուցադրելու"},{code:"id",week:{dow:1,doy:7},buttonText:{prev:"mundur",next:"maju",today:"hari ini",month:"Bulan",week:"Minggu",day:"Hari",list:"Agenda"},weekText:"Mg",allDayText:"Sehari penuh",moreLinkText:"lebih",noEventsText:"Tidak ada acara untuk ditampilkan"},{code:"is",week:{dow:1,doy:4},buttonText:{prev:"Fyrri",next:"Næsti",today:"Í dag",month:"Mánuður",week:"Vika",day:"Dagur",list:"Dagskrá"},weekText:"Vika",allDayText:"Allan daginn",moreLinkText:"meira",noEventsText:"Engir viðburðir til að sýna"},{code:"it",week:{dow:1,doy:4},buttonText:{prev:"Prec",next:"Succ",today:"Oggi",month:"Mese",week:"Settimana",day:"Giorno",list:"Agenda"},weekText:"Sm",allDayText:"Tutto il giorno",moreLinkText:function(e){return"+altri "+e},noEventsText:"Non ci sono eventi da visualizzare"},{code:"ja",buttonText:{prev:"前",next:"次",today:"今日",month:"月",week:"週",day:"日",list:"予定リスト"},weekText:"週",allDayText:"終日",moreLinkText:function(e){return"他 "+e+" 件"},noEventsText:"表示する予定はありません"},{code:"ka",week:{dow:1,doy:7},buttonText:{prev:"წინა",next:"შემდეგი",today:"დღეს",month:"თვე",week:"კვირა",day:"დღე",list:"დღის წესრიგი"},weekText:"კვ",allDayText:"მთელი დღე",moreLinkText:function(e){return"+ კიდევ "+e},noEventsText:"ღონისძიებები არ არის"},{code:"kk",week:{dow:1,doy:7},buttonText:{prev:"Алдыңғы",next:"Келесі",today:"Бүгін",month:"Ай",week:"Апта",day:"Күн",list:"Күн тәртібі"},weekText:"Не",allDayText:"Күні бойы",moreLinkText:function(e){return"+ тағы "+e},noEventsText:"Көрсету үшін оқиғалар жоқ"},{code:"km",week:{dow:1,doy:4},buttonText:{prev:"មុន",next:"បន្ទាប់",today:"ថ្ងៃនេះ",year:"ឆ្នាំ",month:"ខែ",week:"សប្តាហ៍",day:"ថ្ងៃ",list:"បញ្ជី"},weekText:"សប្តាហ៍",allDayText:"ពេញមួយថ្ងៃ",moreLinkText:"ច្រើនទៀត",noEventsText:"គ្មានព្រឹត្តិការណ៍ត្រូវបង្ហាញ"},{code:"ko",buttonText:{prev:"이전달",next:"다음달",today:"오늘",month:"월",week:"주",day:"일",list:"일정목록"},weekText:"주",allDayText:"종일",moreLinkText:"개",noEventsText:"일정이 없습니다"},{code:"ku",week:{dow:6,doy:12},direction:"rtl",buttonText:{prev:"پێشتر",next:"دواتر",today:"ئەمڕو",month:"مانگ",week:"هەفتە",day:"ڕۆژ",list:"بەرنامە"},weekText:"هەفتە",allDayText:"هەموو ڕۆژەکە",moreLinkText:"زیاتر",noEventsText:"هیچ ڕووداوێك نیە"},{code:"lb",week:{dow:1,doy:4},buttonText:{prev:"Zréck",next:"Weider",today:"Haut",month:"Mount",week:"Woch",day:"Dag",list:"Terminiwwersiicht"},weekText:"W",allDayText:"Ganzen Dag",moreLinkText:"méi",noEventsText:"Nee Evenementer ze affichéieren"},{code:"lt",week:{dow:1,doy:4},buttonText:{prev:"Atgal",next:"Pirmyn",today:"Šiandien",month:"Mėnuo",week:"Savaitė",day:"Diena",list:"Darbotvarkė"},weekText:"SAV",allDayText:"Visą dieną",moreLinkText:"daugiau",noEventsText:"Nėra įvykių rodyti"},{code:"lv",week:{dow:1,doy:4},buttonText:{prev:"Iepr.",next:"Nāk.",today:"Šodien",month:"Mēnesis",week:"Nedēļa",day:"Diena",list:"Dienas kārtība"},weekText:"Ned.",allDayText:"Visu dienu",moreLinkText:function(e){return"+vēl "+e},noEventsText:"Nav notikumu"},{code:"mk",buttonText:{prev:"претходно",next:"следно",today:"Денес",month:"Месец",week:"Недела",day:"Ден",list:"График"},weekText:"Сед",allDayText:"Цел ден",moreLinkText:function(e){return"+повеќе "+e},noEventsText:"Нема настани за прикажување"},{code:"ms",week:{dow:1,doy:7},buttonText:{prev:"Sebelum",next:"Selepas",today:"hari ini",month:"Bulan",week:"Minggu",day:"Hari",list:"Agenda"},weekText:"Mg",allDayText:"Sepanjang hari",moreLinkText:function(e){return"masih ada "+e+" acara"},noEventsText:"Tiada peristiwa untuk dipaparkan"},{code:"nb",week:{dow:1,doy:4},buttonText:{prev:"Forrige",next:"Neste",today:"I dag",month:"Måned",week:"Uke",day:"Dag",list:"Agenda"},weekText:"Uke",weekTextLong:"Uke",allDayText:"Hele dagen",moreLinkText:"til",noEventsText:"Ingen hendelser å vise",buttonHints:{prev:"Forrige $0",next:"Neste $0",today:"Nåværende $0"},viewHint:"$0 visning",navLinkHint:"Gå til $0",moreLinkHint:e=>`Vis ${e} flere hendelse${1===e?"":"r"}`},{code:"ne",week:{dow:7,doy:1},buttonText:{prev:"अघिल्लो",next:"अर्को",today:"आज",month:"महिना",week:"हप्ता",day:"दिन",list:"सूची"},weekText:"हप्ता",allDayText:"दिनभरि",moreLinkText:"थप लिंक",noEventsText:"देखाउनको लागि कुनै घटनाहरू छैनन्"},{code:"nl",week:{dow:1,doy:4},buttonText:{prev:"Vorige",next:"Volgende",today:"Vandaag",year:"Jaar",month:"Maand",week:"Week",day:"Dag",list:"Agenda"},allDayText:"Hele dag",moreLinkText:"extra",noEventsText:"Geen evenementen om te laten zien"},{code:"nn",week:{dow:1,doy:4},buttonText:{prev:"Førre",next:"Neste",today:"I dag",month:"Månad",week:"Veke",day:"Dag",list:"Agenda"},weekText:"Veke",allDayText:"Heile dagen",moreLinkText:"til",noEventsText:"Ingen hendelser å vise"},{code:"pl",week:{dow:1,doy:4},buttonText:{prev:"Poprzedni",next:"Następny",today:"Dziś",month:"Miesiąc",week:"Tydzień",day:"Dzień",list:"Plan dnia"},weekText:"Tydz",allDayText:"Cały dzień",moreLinkText:"więcej",noEventsText:"Brak wydarzeń do wyświetlenia"},{code:"pt-br",buttonText:{prev:"Anterior",next:"Próximo",today:"Hoje",month:"Mês",week:"Semana",day:"Dia",list:"Lista"},weekText:"Sm",allDayText:"dia inteiro",moreLinkText:function(e){return"mais +"+e},noEventsText:"Não há eventos para mostrar"},{code:"pt",week:{dow:1,doy:4},buttonText:{prev:"Anterior",next:"Seguinte",today:"Hoje",month:"Mês",week:"Semana",day:"Dia",list:"Agenda"},weekText:"Sem",allDayText:"Todo o dia",moreLinkText:"mais",noEventsText:"Não há eventos para mostrar"},{code:"ro",week:{dow:1,doy:7},buttonText:{prev:"precedentă",next:"următoare",today:"Azi",month:"Lună",week:"Săptămână",day:"Zi",list:"Agendă"},weekText:"Săpt",allDayText:"Toată ziua",moreLinkText:function(e){return"+alte "+e},noEventsText:"Nu există evenimente de afișat"},{code:"ru",week:{dow:1,doy:4},buttonText:{prev:"Пред",next:"След",today:"Сегодня",month:"Месяц",week:"Неделя",day:"День",list:"Повестка дня"},weekText:"Нед",allDayText:"Весь день",moreLinkText:function(e){return"+ ещё "+e},noEventsText:"Нет событий для отображения"},{code:"si-lk",week:{dow:1,doy:4},buttonText:{prev:"පෙර",next:"පසු",today:"අද",month:"මාසය",week:"සතිය",day:"දවස",list:"ලැයිස්තුව"},weekText:"සති",allDayText:"සියලු",moreLinkText:"තවත්",noEventsText:"මුකුත් නැත"},{code:"sk",week:{dow:1,doy:4},buttonText:{prev:"Predchádzajúci",next:"Nasledujúci",today:"Dnes",month:"Mesiac",week:"Týždeň",day:"Deň",list:"Rozvrh"},weekText:"Ty",allDayText:"Celý deň",moreLinkText:function(e){return"+ďalšie: "+e},noEventsText:"Žiadne akcie na zobrazenie"},{code:"sl",week:{dow:1,doy:7},buttonText:{prev:"Prejšnji",next:"Naslednji",today:"Trenutni",month:"Mesec",week:"Teden",day:"Dan",list:"Dnevni red"},weekText:"Teden",allDayText:"Ves dan",moreLinkText:"več",noEventsText:"Ni dogodkov za prikaz"},{code:"sm",buttonText:{prev:"Talu ai",next:"Mulimuli atu",today:"Aso nei",month:"Masina",week:"Vaiaso",day:"Aso",list:"Faasologa"},weekText:"Vaiaso",allDayText:"Aso atoa",moreLinkText:"sili atu",noEventsText:"Leai ni mea na tutupu"},{code:"sq",week:{dow:1,doy:4},buttonText:{prev:"mbrapa",next:"Përpara",today:"sot",month:"Muaj",week:"Javë",day:"Ditë",list:"Listë"},weekText:"Ja",allDayText:"Gjithë ditën",moreLinkText:function(e){return"+më tepër "+e},noEventsText:"Nuk ka evente për të shfaqur"},{code:"sr-cyrl",week:{dow:1,doy:7},buttonText:{prev:"Претходна",next:"следећи",today:"Данас",month:"Месец",week:"Недеља",day:"Дан",list:"Планер"},weekText:"Сед",allDayText:"Цео дан",moreLinkText:function(e){return"+ још "+e},noEventsText:"Нема догађаја за приказ"},{code:"sr",week:{dow:1,doy:7},buttonText:{prev:"Prethodna",next:"Sledeći",today:"Danas",month:"Mеsеc",week:"Nеdеlja",day:"Dan",list:"Planеr"},weekText:"Sed",allDayText:"Cеo dan",moreLinkText:function(e){return"+ još "+e},noEventsText:"Nеma događaja za prikaz"},{code:"sv",week:{dow:1,doy:4},buttonText:{prev:"Förra",next:"Nästa",today:"Idag",month:"Månad",week:"Vecka",day:"Dag",list:"Program"},buttonHints:{prev:e=>`Föregående ${e.toLocaleLowerCase()}`,next:e=>`Nästa ${e.toLocaleLowerCase()}`,today:e=>("Program"===e?"Detta":"Denna")+" "+e.toLocaleLowerCase()},viewHint:"$0 vy",navLinkHint:"Gå till $0",moreLinkHint:e=>`Visa ytterligare ${e} händelse${1===e?"":"r"}`,weekText:"v.",weekTextLong:"Vecka",allDayText:"Heldag",moreLinkText:"till",noEventsText:"Inga händelser att visa",closeHint:"Stäng",timeHint:"Klockan",eventHint:"Händelse"},{code:"ta-in",week:{dow:1,doy:4},buttonText:{prev:"முந்தைய",next:"அடுத்தது",today:"இன்று",month:"மாதம்",week:"வாரம்",day:"நாள்",list:"தினசரி அட்டவணை"},weekText:"வாரம்",allDayText:"நாள் முழுவதும்",moreLinkText:function(e){return"+ மேலும் "+e},noEventsText:"காண்பிக்க நிகழ்வுகள் இல்லை"},{code:"th",week:{dow:1,doy:4},buttonText:{prev:"ก่อนหน้า",next:"ถัดไป",prevYear:"ปีก่อนหน้า",nextYear:"ปีถัดไป",year:"ปี",today:"วันนี้",month:"เดือน",week:"สัปดาห์",day:"วัน",list:"กำหนดการ"},weekText:"สัปดาห์",allDayText:"ตลอดวัน",moreLinkText:"เพิ่มเติม",noEventsText:"ไม่มีกิจกรรมที่จะแสดง"},{code:"tr",week:{dow:1,doy:7},buttonText:{prev:"geri",next:"ileri",today:"bugün",month:"Ay",week:"Hafta",day:"Gün",list:"Ajanda"},weekText:"Hf",allDayText:"Tüm gün",moreLinkText:"daha fazla",noEventsText:"Gösterilecek etkinlik yok"},{code:"ug",buttonText:{month:"ئاي",week:"ھەپتە",day:"كۈن",list:"كۈنتەرتىپ"},allDayText:"پۈتۈن كۈن"},{code:"uk",week:{dow:1,doy:7},buttonText:{prev:"Попередній",next:"далі",today:"Сьогодні",month:"Місяць",week:"Тиждень",day:"День",list:"Порядок денний"},weekText:"Тиж",allDayText:"Увесь день",moreLinkText:function(e){return"+ще "+e+"..."},noEventsText:"Немає подій для відображення"},{code:"uz",buttonText:{month:"Oy",week:"Xafta",day:"Kun",list:"Kun tartibi"},allDayText:"Kun bo'yi",moreLinkText:function(e){return"+ yana "+e},noEventsText:"Ko'rsatish uchun voqealar yo'q"},{code:"vi",week:{dow:1,doy:4},buttonText:{prev:"Trước",next:"Tiếp",today:"Hôm nay",month:"Tháng",week:"Tuần",day:"Ngày",list:"Lịch biểu"},weekText:"Tu",allDayText:"Cả ngày",moreLinkText:function(e){return"+ thêm "+e},noEventsText:"Không có sự kiện để hiển thị"},{code:"zh-cn",week:{dow:1,doy:4},buttonText:{prev:"上月",next:"下月",today:"今天",month:"月",week:"周",day:"日",list:"日程"},weekText:"周",allDayText:"全天",moreLinkText:function(e){return"另外 "+e+" 个"},noEventsText:"没有事件显示"},{code:"zh-tw",buttonText:{prev:"上月",next:"下月",today:"今天",month:"月",week:"週",day:"天",list:"活動列表"},weekText:"周",allDayText:"整天",moreLinkText:"顯示更多",noEventsText:"没有任何活動"}]}());
(function() {
  var AjaxMonitor, Bar, DocumentMonitor, ElementMonitor, ElementTracker, EventLagMonitor, Evented, Events, NoTargetError, Pace, RequestIntercept, SOURCE_KEYS, Scaler, SocketRequestTracker, XHRRequestTracker, animation, avgAmplitude, bar, cancelAnimation, cancelAnimationFrame, defaultOptions, extend, extendNative, getFromDOM, getIntercept, handlePushState, ignoreStack, init, now, options, requestAnimationFrame, result, runAnimation, scalers, shouldIgnoreURL, shouldTrack, source, sources, uniScaler, _WebSocket, _XDomainRequest, _XMLHttpRequest, _i, _intercept, _len, _pushState, _ref, _ref1, _replaceState,
    __slice = [].slice,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  defaultOptions = {
    catchupTime: 100,
    initialRate: .03,
    minTime: 250,
    ghostTime: 100,
    maxProgressPerFrame: 20,
    easeFactor: 1.25,
    startOnPageLoad: true,
    restartOnPushState: true,
    restartOnRequestAfter: 500,
    target: 'body',
    elements: {
      checkInterval: 100,
      selectors: ['body']
    },
    eventLag: {
      minSamples: 10,
      sampleCount: 3,
      lagThreshold: 3
    },
    ajax: {
      trackMethods: ['GET'],
      trackWebSockets: true,
      ignoreURLs: []
    }
  };

  now = function() {
    var _ref;
    return (_ref = typeof performance !== "undefined" && performance !== null ? typeof performance.now === "function" ? performance.now() : void 0 : void 0) != null ? _ref : +(new Date);
  };

  requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

  cancelAnimationFrame = window.cancelAnimationFrame || window.mozCancelAnimationFrame;

  if (requestAnimationFrame == null) {
    requestAnimationFrame = function(fn) {
      return setTimeout(fn, 50);
    };
    cancelAnimationFrame = function(id) {
      return clearTimeout(id);
    };
  }

  runAnimation = function(fn) {
    var last, tick;
    last = now();
    tick = function() {
      var diff;
      diff = now() - last;
      if (diff >= 33) {
        last = now();
        return fn(diff, function() {
          return requestAnimationFrame(tick);
        });
      } else {
        return setTimeout(tick, 33 - diff);
      }
    };
    return tick();
  };

  result = function() {
    var args, key, obj;
    obj = arguments[0], key = arguments[1], args = 3 <= arguments.length ? __slice.call(arguments, 2) : [];
    if (typeof obj[key] === 'function') {
      return obj[key].apply(obj, args);
    } else {
      return obj[key];
    }
  };

  extend = function() {
    var key, out, source, sources, val, _i, _len;
    out = arguments[0], sources = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
    for (_i = 0, _len = sources.length; _i < _len; _i++) {
      source = sources[_i];
      if (source) {
        for (key in source) {
          if (!__hasProp.call(source, key)) continue;
          val = source[key];
          if ((out[key] != null) && typeof out[key] === 'object' && (val != null) && typeof val === 'object') {
            extend(out[key], val);
          } else {
            out[key] = val;
          }
        }
      }
    }
    return out;
  };

  avgAmplitude = function(arr) {
    var count, sum, v, _i, _len;
    sum = count = 0;
    for (_i = 0, _len = arr.length; _i < _len; _i++) {
      v = arr[_i];
      sum += Math.abs(v);
      count++;
    }
    return sum / count;
  };

  getFromDOM = function(key, json) {
    var data, e, el;
    if (key == null) {
      key = 'options';
    }
    if (json == null) {
      json = true;
    }
    el = document.querySelector("[data-pace-" + key + "]");
    if (!el) {
      return;
    }
    data = el.getAttribute("data-pace-" + key);
    if (!json) {
      return data;
    }
    try {
      return JSON.parse(data);
    } catch (_error) {
      e = _error;
      return typeof console !== "undefined" && console !== null ? console.error("Error parsing inline pace options", e) : void 0;
    }
  };

  Evented = (function() {
    function Evented() {}

    Evented.prototype.on = function(event, handler, ctx, once) {
      var _base;
      if (once == null) {
        once = false;
      }
      if (this.bindings == null) {
        this.bindings = {};
      }
      if ((_base = this.bindings)[event] == null) {
        _base[event] = [];
      }
      return this.bindings[event].push({
        handler: handler,
        ctx: ctx,
        once: once
      });
    };

    Evented.prototype.once = function(event, handler, ctx) {
      return this.on(event, handler, ctx, true);
    };

    Evented.prototype.off = function(event, handler) {
      var i, _ref, _results;
      if (((_ref = this.bindings) != null ? _ref[event] : void 0) == null) {
        return;
      }
      if (handler == null) {
        return delete this.bindings[event];
      } else {
        i = 0;
        _results = [];
        while (i < this.bindings[event].length) {
          if (this.bindings[event][i].handler === handler) {
            _results.push(this.bindings[event].splice(i, 1));
          } else {
            _results.push(i++);
          }
        }
        return _results;
      }
    };

    Evented.prototype.trigger = function() {
      var args, ctx, event, handler, i, once, _ref, _ref1, _results;
      event = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if ((_ref = this.bindings) != null ? _ref[event] : void 0) {
        i = 0;
        _results = [];
        while (i < this.bindings[event].length) {
          _ref1 = this.bindings[event][i], handler = _ref1.handler, ctx = _ref1.ctx, once = _ref1.once;
          handler.apply(ctx != null ? ctx : this, args);
          if (once) {
            _results.push(this.bindings[event].splice(i, 1));
          } else {
            _results.push(i++);
          }
        }
        return _results;
      }
    };

    return Evented;

  })();

  Pace = window.Pace || {};

  window.Pace = Pace;

  extend(Pace, Evented.prototype);

  options = Pace.options = extend({}, defaultOptions, window.paceOptions, getFromDOM());

  _ref = ['ajax', 'document', 'eventLag', 'elements'];
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    source = _ref[_i];
    if (options[source] === true) {
      options[source] = defaultOptions[source];
    }
  }

  NoTargetError = (function(_super) {
    __extends(NoTargetError, _super);

    function NoTargetError() {
      _ref1 = NoTargetError.__super__.constructor.apply(this, arguments);
      return _ref1;
    }

    return NoTargetError;

  })(Error);

  Bar = (function() {
    function Bar() {
      this.progress = 0;
    }

    Bar.prototype.getElement = function() {
      var targetElement;
      if (this.el == null) {
        targetElement = document.querySelector(options.target);
        if (!targetElement) {
          throw new NoTargetError;
        }
        this.el = document.createElement('div');
        this.el.className = "pace pace-active";
        document.body.className = document.body.className.replace(/pace-done/g, '');
        document.body.className += ' pace-running';
        this.el.innerHTML = '<div class="pace-progress">\n  <div class="pace-progress-inner"></div>\n</div>\n<div class="pace-activity"></div>';
        if (targetElement.firstChild != null) {
          targetElement.insertBefore(this.el, targetElement.firstChild);
        } else {
          targetElement.appendChild(this.el);
        }
      }
      return this.el;
    };

    Bar.prototype.finish = function() {
      var el;
      el = this.getElement();
      el.className = el.className.replace('pace-active', '');
      el.className += ' pace-inactive';
      document.body.className = document.body.className.replace('pace-running', '');
      return document.body.className += ' pace-done';
    };

    Bar.prototype.update = function(prog) {
      this.progress = prog;
      return this.render();
    };

    Bar.prototype.destroy = function() {
      try {
        this.getElement().parentNode.removeChild(this.getElement());
      } catch (_error) {
        NoTargetError = _error;
      }
      return this.el = void 0;
    };

    Bar.prototype.render = function() {
      var el, key, progressStr, transform, _j, _len1, _ref2;
      if (document.querySelector(options.target) == null) {
        return false;
      }
      el = this.getElement();
      transform = "translate3d(" + this.progress + "%, 0, 0)";
      _ref2 = ['webkitTransform', 'msTransform', 'transform'];
      for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
        key = _ref2[_j];
        el.children[0].style[key] = transform;
      }
      if (!this.lastRenderedProgress || this.lastRenderedProgress | 0 !== this.progress | 0) {
        el.children[0].setAttribute('data-progress-text', "" + (this.progress | 0) + "%");
        if (this.progress >= 100) {
          progressStr = '99';
        } else {
          progressStr = this.progress < 10 ? "0" : "";
          progressStr += this.progress | 0;
        }
        el.children[0].setAttribute('data-progress', "" + progressStr);
      }
      return this.lastRenderedProgress = this.progress;
    };

    Bar.prototype.done = function() {
      return this.progress >= 100;
    };

    return Bar;

  })();

  Events = (function() {
    function Events() {
      this.bindings = {};
    }

    Events.prototype.trigger = function(name, val) {
      var binding, _j, _len1, _ref2, _results;
      if (this.bindings[name] != null) {
        _ref2 = this.bindings[name];
        _results = [];
        for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
          binding = _ref2[_j];
          _results.push(binding.call(this, val));
        }
        return _results;
      }
    };

    Events.prototype.on = function(name, fn) {
      var _base;
      if ((_base = this.bindings)[name] == null) {
        _base[name] = [];
      }
      return this.bindings[name].push(fn);
    };

    return Events;

  })();

  _XMLHttpRequest = window.XMLHttpRequest;

  _XDomainRequest = window.XDomainRequest;

  _WebSocket = window.WebSocket;

  extendNative = function(to, from) {
    var e, key, _results;
    _results = [];
    for (key in from.prototype) {
      try {
        if ((to[key] == null) && typeof from[key] !== 'function') {
          if (typeof Object.defineProperty === 'function') {
            _results.push(Object.defineProperty(to, key, {
              get: function() {
                return from.prototype[key];
              },
              configurable: true,
              enumerable: true
            }));
          } else {
            _results.push(to[key] = from.prototype[key]);
          }
        } else {
          _results.push(void 0);
        }
      } catch (_error) {
        e = _error;
      }
    }
    return _results;
  };

  ignoreStack = [];

  Pace.ignore = function() {
    var args, fn, ret;
    fn = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
    ignoreStack.unshift('ignore');
    ret = fn.apply(null, args);
    ignoreStack.shift();
    return ret;
  };

  Pace.track = function() {
    var args, fn, ret;
    fn = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
    ignoreStack.unshift('track');
    ret = fn.apply(null, args);
    ignoreStack.shift();
    return ret;
  };

  shouldTrack = function(method) {
    var _ref2;
    if (method == null) {
      method = 'GET';
    }
    if (ignoreStack[0] === 'track') {
      return 'force';
    }
    if (!ignoreStack.length && options.ajax) {
      if (method === 'socket' && options.ajax.trackWebSockets) {
        return true;
      } else if (_ref2 = method.toUpperCase(), __indexOf.call(options.ajax.trackMethods, _ref2) >= 0) {
        return true;
      }
    }
    return false;
  };

  RequestIntercept = (function(_super) {
    __extends(RequestIntercept, _super);

    function RequestIntercept() {
      var monitorXHR,
        _this = this;
      RequestIntercept.__super__.constructor.apply(this, arguments);
      monitorXHR = function(req) {
        var _open;
        _open = req.open;
        return req.open = function(type, url, async) {
          if (shouldTrack(type)) {
            _this.trigger('request', {
              type: type,
              url: url,
              request: req
            });
          }
          return _open.apply(req, arguments);
        };
      };
      window.XMLHttpRequest = function(flags) {
        var req;
        req = new _XMLHttpRequest(flags);
        monitorXHR(req);
        return req;
      };
      try {
        extendNative(window.XMLHttpRequest, _XMLHttpRequest);
      } catch (_error) {}
      if (_XDomainRequest != null) {
        window.XDomainRequest = function() {
          var req;
          req = new _XDomainRequest;
          monitorXHR(req);
          return req;
        };
        try {
          extendNative(window.XDomainRequest, _XDomainRequest);
        } catch (_error) {}
      }
      if ((_WebSocket != null) && options.ajax.trackWebSockets) {
        window.WebSocket = function(url, protocols) {
          var req;
          if (protocols != null) {
            req = new _WebSocket(url, protocols);
          } else {
            req = new _WebSocket(url);
          }
          if (shouldTrack('socket')) {
            _this.trigger('request', {
              type: 'socket',
              url: url,
              protocols: protocols,
              request: req
            });
          }
          return req;
        };
        try {
          extendNative(window.WebSocket, _WebSocket);
        } catch (_error) {}
      }
    }

    return RequestIntercept;

  })(Events);

  _intercept = null;

  getIntercept = function() {
    if (_intercept == null) {
      _intercept = new RequestIntercept;
    }
    return _intercept;
  };

  shouldIgnoreURL = function(url) {
    var pattern, _j, _len1, _ref2;
    _ref2 = options.ajax.ignoreURLs;
    for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
      pattern = _ref2[_j];
      if (typeof pattern === 'string') {
        if (url.indexOf(pattern) !== -1) {
          return true;
        }
      } else {
        if (pattern.test(url)) {
          return true;
        }
      }
    }
    return false;
  };

  getIntercept().on('request', function(_arg) {
    var after, args, request, type, url;
    type = _arg.type, request = _arg.request, url = _arg.url;
    if (shouldIgnoreURL(url)) {
      return;
    }
    if (!Pace.running && (options.restartOnRequestAfter !== false || shouldTrack(type) === 'force')) {
      args = arguments;
      after = options.restartOnRequestAfter || 0;
      if (typeof after === 'boolean') {
        after = 0;
      }
      return setTimeout(function() {
        var stillActive, _j, _len1, _ref2, _ref3, _results;
        if (type === 'socket') {
          stillActive = request.readyState < 2;
        } else {
          stillActive = (0 < (_ref2 = request.readyState) && _ref2 < 4);
        }
        if (stillActive) {
          Pace.restart();
          _ref3 = Pace.sources;
          _results = [];
          for (_j = 0, _len1 = _ref3.length; _j < _len1; _j++) {
            source = _ref3[_j];
            if (source instanceof AjaxMonitor) {
              source.watch.apply(source, args);
              break;
            } else {
              _results.push(void 0);
            }
          }
          return _results;
        }
      }, after);
    }
  });

  AjaxMonitor = (function() {
    function AjaxMonitor() {
      var _this = this;
      this.elements = [];
      getIntercept().on('request', function() {
        return _this.watch.apply(_this, arguments);
      });
    }

    AjaxMonitor.prototype.watch = function(_arg) {
      var request, tracker, type, url;
      type = _arg.type, request = _arg.request, url = _arg.url;
      if (shouldIgnoreURL(url)) {
        return;
      }
      if (type === 'socket') {
        tracker = new SocketRequestTracker(request);
      } else {
        tracker = new XHRRequestTracker(request);
      }
      return this.elements.push(tracker);
    };

    return AjaxMonitor;

  })();

  XHRRequestTracker = (function() {
    function XHRRequestTracker(request) {
      var event, size, _j, _len1, _onreadystatechange, _ref2,
        _this = this;
      this.progress = 0;
      if (window.ProgressEvent != null) {
        size = null;
        request.addEventListener('progress', function(evt) {
          if (evt.lengthComputable) {
            return _this.progress = 100 * evt.loaded / evt.total;
          } else {
            return _this.progress = _this.progress + (100 - _this.progress) / 2;
          }
        }, false);
        _ref2 = ['load', 'abort', 'timeout', 'error'];
        for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
          event = _ref2[_j];
          request.addEventListener(event, function() {
            return _this.progress = 100;
          }, false);
        }
      } else {
        _onreadystatechange = request.onreadystatechange;
        request.onreadystatechange = function() {
          var _ref3;
          if ((_ref3 = request.readyState) === 0 || _ref3 === 4) {
            _this.progress = 100;
          } else if (request.readyState === 3) {
            _this.progress = 50;
          }
          return typeof _onreadystatechange === "function" ? _onreadystatechange.apply(null, arguments) : void 0;
        };
      }
    }

    return XHRRequestTracker;

  })();

  SocketRequestTracker = (function() {
    function SocketRequestTracker(request) {
      var event, _j, _len1, _ref2,
        _this = this;
      this.progress = 0;
      _ref2 = ['error', 'open'];
      for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
        event = _ref2[_j];
        request.addEventListener(event, function() {
          return _this.progress = 100;
        }, false);
      }
    }

    return SocketRequestTracker;

  })();

  ElementMonitor = (function() {
    function ElementMonitor(options) {
      var selector, _j, _len1, _ref2;
      if (options == null) {
        options = {};
      }
      this.elements = [];
      if (options.selectors == null) {
        options.selectors = [];
      }
      _ref2 = options.selectors;
      for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
        selector = _ref2[_j];
        this.elements.push(new ElementTracker(selector));
      }
    }

    return ElementMonitor;

  })();

  ElementTracker = (function() {
    function ElementTracker(selector) {
      this.selector = selector;
      this.progress = 0;
      this.check();
    }

    ElementTracker.prototype.check = function() {
      var _this = this;
      if (document.querySelector(this.selector)) {
        return this.done();
      } else {
        return setTimeout((function() {
          return _this.check();
        }), options.elements.checkInterval);
      }
    };

    ElementTracker.prototype.done = function() {
      return this.progress = 100;
    };

    return ElementTracker;

  })();

  DocumentMonitor = (function() {
    DocumentMonitor.prototype.states = {
      loading: 0,
      interactive: 50,
      complete: 100
    };

    function DocumentMonitor() {
      var _onreadystatechange, _ref2,
        _this = this;
      this.progress = (_ref2 = this.states[document.readyState]) != null ? _ref2 : 100;
      _onreadystatechange = document.onreadystatechange;
      document.onreadystatechange = function() {
        if (_this.states[document.readyState] != null) {
          _this.progress = _this.states[document.readyState];
        }
        return typeof _onreadystatechange === "function" ? _onreadystatechange.apply(null, arguments) : void 0;
      };
    }

    return DocumentMonitor;

  })();

  EventLagMonitor = (function() {
    function EventLagMonitor() {
      var avg, interval, last, points, samples,
        _this = this;
      this.progress = 0;
      avg = 0;
      samples = [];
      points = 0;
      last = now();
      interval = setInterval(function() {
        var diff;
        diff = now() - last - 50;
        last = now();
        samples.push(diff);
        if (samples.length > options.eventLag.sampleCount) {
          samples.shift();
        }
        avg = avgAmplitude(samples);
        if (++points >= options.eventLag.minSamples && avg < options.eventLag.lagThreshold) {
          _this.progress = 100;
          return clearInterval(interval);
        } else {
          return _this.progress = 100 * (3 / (avg + 3));
        }
      }, 50);
    }

    return EventLagMonitor;

  })();

  Scaler = (function() {
    function Scaler(source) {
      this.source = source;
      this.last = this.sinceLastUpdate = 0;
      this.rate = options.initialRate;
      this.catchup = 0;
      this.progress = this.lastProgress = 0;
      if (this.source != null) {
        this.progress = result(this.source, 'progress');
      }
    }

    Scaler.prototype.tick = function(frameTime, val) {
      var scaling;
      if (val == null) {
        val = result(this.source, 'progress');
      }
      if (val >= 100) {
        this.done = true;
      }
      if (val === this.last) {
        this.sinceLastUpdate += frameTime;
      } else {
        if (this.sinceLastUpdate) {
          this.rate = (val - this.last) / this.sinceLastUpdate;
        }
        this.catchup = (val - this.progress) / options.catchupTime;
        this.sinceLastUpdate = 0;
        this.last = val;
      }
      if (val > this.progress) {
        this.progress += this.catchup * frameTime;
      }
      scaling = 1 - Math.pow(this.progress / 100, options.easeFactor);
      this.progress += scaling * this.rate * frameTime;
      this.progress = Math.min(this.lastProgress + options.maxProgressPerFrame, this.progress);
      this.progress = Math.max(0, this.progress);
      this.progress = Math.min(100, this.progress);
      this.lastProgress = this.progress;
      return this.progress;
    };

    return Scaler;

  })();

  sources = null;

  scalers = null;

  bar = null;

  uniScaler = null;

  animation = null;

  cancelAnimation = null;

  Pace.running = false;

  handlePushState = function() {
    if (options.restartOnPushState) {
      return Pace.restart();
    }
  };

  if (window.history.pushState != null) {
    _pushState = window.history.pushState;
    window.history.pushState = function() {
      handlePushState();
      return _pushState.apply(window.history, arguments);
    };
  }

  if (window.history.replaceState != null) {
    _replaceState = window.history.replaceState;
    window.history.replaceState = function() {
      handlePushState();
      return _replaceState.apply(window.history, arguments);
    };
  }

  SOURCE_KEYS = {
    ajax: AjaxMonitor,
    elements: ElementMonitor,
    document: DocumentMonitor,
    eventLag: EventLagMonitor
  };

  (init = function() {
    var type, _j, _k, _len1, _len2, _ref2, _ref3, _ref4;
    Pace.sources = sources = [];
    _ref2 = ['ajax', 'elements', 'document', 'eventLag'];
    for (_j = 0, _len1 = _ref2.length; _j < _len1; _j++) {
      type = _ref2[_j];
      if (options[type] !== false) {
        sources.push(new SOURCE_KEYS[type](options[type]));
      }
    }
    _ref4 = (_ref3 = options.extraSources) != null ? _ref3 : [];
    for (_k = 0, _len2 = _ref4.length; _k < _len2; _k++) {
      source = _ref4[_k];
      sources.push(new source(options));
    }
    Pace.bar = bar = new Bar;
    scalers = [];
    return uniScaler = new Scaler;
  })();

  Pace.stop = function() {
    Pace.trigger('stop');
    Pace.running = false;
    bar.destroy();
    cancelAnimation = true;
    if (animation != null) {
      if (typeof cancelAnimationFrame === "function") {
        cancelAnimationFrame(animation);
      }
      animation = null;
    }
    return init();
  };

  Pace.restart = function() {
    Pace.trigger('restart');
    Pace.stop();
    return Pace.start();
  };

  Pace.go = function() {
    var start;
    Pace.running = true;
    bar.render();
    start = now();
    cancelAnimation = false;
    return animation = runAnimation(function(frameTime, enqueueNextFrame) {
      var avg, count, done, element, elements, i, j, remaining, scaler, scalerList, sum, _j, _k, _len1, _len2, _ref2;
      remaining = 100 - bar.progress;
      count = sum = 0;
      done = true;
      for (i = _j = 0, _len1 = sources.length; _j < _len1; i = ++_j) {
        source = sources[i];
        scalerList = scalers[i] != null ? scalers[i] : scalers[i] = [];
        elements = (_ref2 = source.elements) != null ? _ref2 : [source];
        for (j = _k = 0, _len2 = elements.length; _k < _len2; j = ++_k) {
          element = elements[j];
          scaler = scalerList[j] != null ? scalerList[j] : scalerList[j] = new Scaler(element);
          done &= scaler.done;
          if (scaler.done) {
            continue;
          }
          count++;
          sum += scaler.tick(frameTime);
        }
      }
      avg = sum / count;
      bar.update(uniScaler.tick(frameTime, avg));
      if (bar.done() || done || cancelAnimation) {
        bar.update(100);
        Pace.trigger('done');
        return setTimeout(function() {
          bar.finish();
          Pace.running = false;
          return Pace.trigger('hide');
        }, Math.max(options.ghostTime, Math.max(options.minTime - (now() - start), 0)));
      } else {
        return enqueueNextFrame();
      }
    });
  };

  Pace.start = function(_options) {
    extend(options, _options);
    Pace.running = true;
    try {
      bar.render();
    } catch (_error) {
      NoTargetError = _error;
    }
    if (!document.querySelector('.pace')) {
      return setTimeout(Pace.start, 50);
    } else {
      Pace.trigger('start');
      return Pace.go();
    }
  };

  if (typeof define === 'function' && define.amd) {
    define(['pace'], function() {
      return Pace;
    });
  } else if (typeof exports === 'object') {
    module.exports = Pace;
  } else {
    if (options.startOnPageLoad) {
      Pace.start();
    }
  }

}).call(this);

/*! Summernote v0.8.20 | (c) 2013- Alan Hong and contributors | MIT license */
!function(t,e){if("object"==typeof exports&&"object"==typeof module)module.exports=e(require("jQuery"));else if("function"==typeof define&&define.amd)define(["jQuery"],e);else{var n="object"==typeof exports?e(require("jQuery")):e(t.jQuery);for(var o in n)("object"==typeof exports?exports:t)[o]=n[o]}}(self,(function(t){return(()=>{"use strict";var e={9770:(t,e,n)=>{var o=n(1145),i=n.n(o);i().summernote=i().summernote||{lang:{}},i().extend(i().summernote.lang,{"en-US":{font:{bold:"Bold",italic:"Italic",underline:"Underline",clear:"Remove Font Style",height:"Line Height",name:"Font Family",strikethrough:"Strikethrough",subscript:"Subscript",superscript:"Superscript",size:"Font Size",sizeunit:"Font Size Unit"},image:{image:"Picture",insert:"Insert Image",resizeFull:"Resize full",resizeHalf:"Resize half",resizeQuarter:"Resize quarter",resizeNone:"Original size",floatLeft:"Float Left",floatRight:"Float Right",floatNone:"Remove float",shapeRounded:"Shape: Rounded",shapeCircle:"Shape: Circle",shapeThumbnail:"Shape: Thumbnail",shapeNone:"Shape: None",dragImageHere:"Drag image or text here",dropImage:"Drop image or Text",selectFromFiles:"Select from files",maximumFileSize:"Maximum file size",maximumFileSizeError:"Maximum file size exceeded.",url:"Image URL",remove:"Remove Image",original:"Original"},video:{video:"Video",videoLink:"Video Link",insert:"Insert Video",url:"Video URL",providers:"(YouTube, Google Drive, Vimeo, Vine, Instagram, DailyMotion, Youku, Peertube)"},link:{link:"Link",insert:"Insert Link",unlink:"Unlink",edit:"Edit",textToDisplay:"Text to display",url:"To what URL should this link go?",openInNewWindow:"Open in new window",useProtocol:"Use default protocol"},table:{table:"Table",addRowAbove:"Add row above",addRowBelow:"Add row below",addColLeft:"Add column left",addColRight:"Add column right",delRow:"Delete row",delCol:"Delete column",delTable:"Delete table"},hr:{insert:"Insert Horizontal Rule"},style:{style:"Style",p:"Normal",blockquote:"Quote",pre:"Code",h1:"Header 1",h2:"Header 2",h3:"Header 3",h4:"Header 4",h5:"Header 5",h6:"Header 6"},lists:{unordered:"Unordered list",ordered:"Ordered list"},options:{help:"Help",fullscreen:"Full Screen",codeview:"Code View"},paragraph:{paragraph:"Paragraph",outdent:"Outdent",indent:"Indent",left:"Align left",center:"Align center",right:"Align right",justify:"Justify full"},color:{recent:"Recent Color",more:"More Color",background:"Background Color",foreground:"Text Color",transparent:"Transparent",setTransparent:"Set transparent",reset:"Reset",resetToDefault:"Reset to default",cpSelect:"Select"},shortcut:{shortcuts:"Keyboard shortcuts",close:"Close",textFormatting:"Text formatting",action:"Action",paragraphFormatting:"Paragraph formatting",documentStyle:"Document Style",extraKeys:"Extra keys"},help:{escape:"Escape",insertParagraph:"Insert Paragraph",undo:"Undo the last command",redo:"Redo the last command",tab:"Tab",untab:"Untab",bold:"Set a bold style",italic:"Set a italic style",underline:"Set a underline style",strikethrough:"Set a strikethrough style",removeFormat:"Clean a style",justifyLeft:"Set left align",justifyCenter:"Set center align",justifyRight:"Set right align",justifyFull:"Set full align",insertUnorderedList:"Toggle unordered list",insertOrderedList:"Toggle ordered list",outdent:"Outdent on current paragraph",indent:"Indent on current paragraph",formatPara:"Change current block's format as a paragraph(P tag)",formatH1:"Change current block's format as H1",formatH2:"Change current block's format as H2",formatH3:"Change current block's format as H3",formatH4:"Change current block's format as H4",formatH5:"Change current block's format as H5",formatH6:"Change current block's format as H6",insertHorizontalRule:"Insert horizontal rule","linkDialog.show":"Show Link Dialog"},history:{undo:"Undo",redo:"Redo"},specialChar:{specialChar:"SPECIAL CHARACTERS",select:"Select Special characters"},output:{noSelection:"No Selection Made!"}}})},1145:e=>{e.exports=t}},n={};function o(t){var i=n[t];if(void 0!==i)return i.exports;var r=n[t]={exports:{}};return e[t](r,r.exports,o),r.exports}o.n=t=>{var e=t&&t.__esModule?()=>t.default:()=>t;return o.d(e,{a:e}),e},o.d=(t,e)=>{for(var n in e)o.o(e,n)&&!o.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:e[n]})},o.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),o.r=t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})};var i={};return(()=>{o.r(i);var t=o(1145),e=o.n(t),n=(o(9770),["sans-serif","serif","monospace","cursive","fantasy"]);function r(t){return-1===e().inArray(t.toLowerCase(),n)?"'".concat(t,"'"):t}var a,s=navigator.userAgent,l=/MSIE|Trident/i.test(s);if(l){var c=/MSIE (\d+[.]\d+)/.exec(s);c&&(a=parseFloat(c[1])),(c=/Trident\/.*rv:([0-9]{1,}[.0-9]{0,})/.exec(s))&&(a=parseFloat(c[1]))}var u=/Edge\/\d+/.test(s),d="ontouchstart"in window||navigator.MaxTouchPoints>0||navigator.msMaxTouchPoints>0,h=l?"DOMCharacterDataModified DOMSubtreeModified DOMNodeInserted":"input";const f={isMac:navigator.appVersion.indexOf("Mac")>-1,isMSIE:l,isEdge:u,isFF:!u&&/firefox/i.test(s),isPhantom:/PhantomJS/i.test(s),isWebkit:!u&&/webkit/i.test(s),isChrome:!u&&/chrome/i.test(s),isSafari:!u&&/safari/i.test(s)&&!/chrome/i.test(s),browserVersion:a,isSupportTouch:d,isFontInstalled:function(t){var e="Comic Sans MS"===t?"Courier New":"Comic Sans MS",n="mmmmmmmmmmwwwww",o=document.createElement("canvas").getContext("2d");o.font="200px '"+e+"'";var i=o.measureText(n).width;return o.font="200px "+r(t)+', "'+e+'"',i!==o.measureText(n).width},isW3CRangeSupport:!!document.createRange,inputEventName:h,genericFontFamilies:n,validFontName:r};var p=0;const m={eq:function(t){return function(e){return t===e}},eq2:function(t,e){return t===e},peq2:function(t){return function(e,n){return e[t]===n[t]}},ok:function(){return!0},fail:function(){return!1},self:function(t){return t},not:function(t){return function(){return!t.apply(t,arguments)}},and:function(t,e){return function(n){return t(n)&&e(n)}},invoke:function(t,e){return function(){return t[e].apply(t,arguments)}},resetUniqueId:function(){p=0},uniqueId:function(t){var e=++p+"";return t?t+e:e},rect2bnd:function(t){var n=e()(document);return{top:t.top+n.scrollTop(),left:t.left+n.scrollLeft(),width:t.right-t.left,height:t.bottom-t.top}},invertObject:function(t){var e={};for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[t[n]]=n);return e},namespaceToCamel:function(t,e){return(e=e||"")+t.split(".").map((function(t){return t.substring(0,1).toUpperCase()+t.substring(1)})).join("")},debounce:function(t,e,n){var o;return function(){var i=this,r=arguments,a=function(){o=null,n||t.apply(i,r)},s=n&&!o;clearTimeout(o),o=setTimeout(a,e),s&&t.apply(i,r)}},isValidUrl:function(t){return/[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/gi.test(t)}};function v(t){return t[0]}function g(t){return t[t.length-1]}function b(t){return t.slice(1)}function y(t,e){if(t&&t.length&&e){if(t.indexOf)return-1!==t.indexOf(e);if(t.contains)return t.contains(e)}return!1}const k={head:v,last:g,initial:function(t){return t.slice(0,t.length-1)},tail:b,prev:function(t,e){if(t&&t.length&&e){var n=t.indexOf(e);return-1===n?null:t[n-1]}return null},next:function(t,e){if(t&&t.length&&e){var n=t.indexOf(e);return-1===n?null:t[n+1]}return null},find:function(t,e){for(var n=0,o=t.length;n<o;n++){var i=t[n];if(e(i))return i}},contains:y,all:function(t,e){for(var n=0,o=t.length;n<o;n++)if(!e(t[n]))return!1;return!0},sum:function(t,e){return e=e||m.self,t.reduce((function(t,n){return t+e(n)}),0)},from:function(t){for(var e=[],n=t.length,o=-1;++o<n;)e[o]=t[o];return e},isEmpty:function(t){return!t||!t.length},clusterBy:function(t,e){return t.length?b(t).reduce((function(t,n){var o=g(t);return e(g(o),n)?o[o.length]=n:t[t.length]=[n],t}),[[v(t)]]):[]},compact:function(t){for(var e=[],n=0,o=t.length;n<o;n++)t[n]&&e.push(t[n]);return e},unique:function(t){for(var e=[],n=0,o=t.length;n<o;n++)y(e,t[n])||e.push(t[n]);return e}};var w=String.fromCharCode(160);function C(t){return t&&e()(t).hasClass("note-editable")}function x(t){return t=t.toUpperCase(),function(e){return e&&e.nodeName.toUpperCase()===t}}function S(t){return t&&3===t.nodeType}function T(t){return t&&/^BR|^IMG|^HR|^IFRAME|^BUTTON|^INPUT|^AUDIO|^VIDEO|^EMBED/.test(t.nodeName.toUpperCase())}function E(t){return!C(t)&&(t&&/^DIV|^P|^LI|^H[1-7]/.test(t.nodeName.toUpperCase()))}var I=x("PRE"),$=x("LI");var N=x("TABLE"),P=x("DATA");function R(t){return!(H(t)||L(t)||A(t)||E(t)||N(t)||D(t)||P(t))}function L(t){return t&&/^UL|^OL/.test(t.nodeName.toUpperCase())}var A=x("HR");function F(t){return t&&/^TD|^TH/.test(t.nodeName.toUpperCase())}var D=x("BLOCKQUOTE");function H(t){return F(t)||D(t)||C(t)}var z=x("A");var B=x("BODY");var M=f.isMSIE&&f.browserVersion<11?"&nbsp;":"<br>";function O(t){return S(t)?t.nodeValue.length:t?t.childNodes.length:0}function U(t){var e=O(t);return 0===e||(!S(t)&&1===e&&t.innerHTML===M||!(!k.all(t.childNodes,S)||""!==t.innerHTML))}function j(t){T(t)||O(t)||(t.innerHTML=M)}function W(t,e){for(;t;){if(e(t))return t;if(C(t))break;t=t.parentNode}return null}function K(t,e){e=e||m.fail;var n=[];return W(t,(function(t){return C(t)||n.push(t),e(t)})),n}function V(t,e){e=e||m.fail;for(var n=[];t&&!e(t);)n.push(t),t=t.nextSibling;return n}function q(t,e){var n=e.nextSibling,o=e.parentNode;return n?o.insertBefore(t,n):o.appendChild(t),t}function _(t,n){return e().each(n,(function(e,n){t.appendChild(n)})),t}function G(t){return 0===t.offset}function Y(t){return t.offset===O(t.node)}function Z(t){return G(t)||Y(t)}function Q(t,e){for(;t&&t!==e;){if(0!==J(t))return!1;t=t.parentNode}return!0}function X(t,e){if(!e)return!1;for(;t&&t!==e;){if(J(t)!==O(t.parentNode)-1)return!1;t=t.parentNode}return!0}function J(t){for(var e=0;t=t.previousSibling;)e+=1;return e}function tt(t){return!!(t&&t.childNodes&&t.childNodes.length)}function et(t,e){var n,o;if(0===t.offset){if(C(t.node))return null;n=t.node.parentNode,o=J(t.node)}else tt(t.node)?o=O(n=t.node.childNodes[t.offset-1]):(n=t.node,o=e?0:t.offset-1);return{node:n,offset:o}}function nt(t,e){var n,o;if(O(t.node)===t.offset){if(C(t.node))return null;var i=it(t.node);i?(n=i,o=0):(n=t.node.parentNode,o=J(t.node)+1)}else tt(t.node)?(n=t.node.childNodes[t.offset],o=0):(n=t.node,o=e?O(t.node):t.offset+1);return{node:n,offset:o}}function ot(t,e){var n,o=0;if(U(t.node))return null===t.node?null:{node:n=t.node.nextSibling,offset:o=0};if(O(t.node)===t.offset){if(C(t.node))return null;n=t.node.parentNode,o=J(t.node)+1,C(n)&&(n=t.node.nextSibling,o=0)}else if(tt(t.node)){if(o=0,U(n=t.node.childNodes[t.offset]))return U(t.node.nextSibling)?null:{node:t.node.nextSibling,offset:o}}else if(n=t.node,o=e?O(t.node):t.offset+1,U(n))return null;return{node:n,offset:o}}function it(t){if(t.nextSibling&&t.parent===t.nextSibling.parent)return S(t.nextSibling)?t.nextSibling:it(t.nextSibling)}function rt(t,e){return t.node===e.node&&t.offset===e.offset}function at(t,e){var n=e&&e.isSkipPaddingBlankHTML,o=e&&e.isNotSplitEdgePoint,i=e&&e.isDiscardEmptySplits;if(i&&(n=!0),Z(t)&&(S(t.node)||o)){if(G(t))return t.node;if(Y(t))return t.node.nextSibling}if(S(t.node))return t.node.splitText(t.offset);var r=t.node.childNodes[t.offset],a=q(t.node.cloneNode(!1),t.node);return _(a,V(r)),n||(j(t.node),j(a)),i&&(U(t.node)&&ct(t.node),U(a))?(ct(a),t.node.nextSibling):a}function st(t,e,n){var o=K(e.node,m.eq(t));return o.length?1===o.length?at(e,n):o.reduce((function(t,o){return t===e.node&&(t=at(e,n)),at({node:o,offset:t?J(t):O(o)},n)})):null}function lt(t){return document.createElement(t)}function ct(t,e){if(t&&t.parentNode){if(t.removeNode)return t.removeNode(e);var n=t.parentNode;if(!e){for(var o=[],i=0,r=t.childNodes.length;i<r;i++)o.push(t.childNodes[i]);for(var a=0,s=o.length;a<s;a++)n.insertBefore(o[a],t)}n.removeChild(t)}}var ut=x("TEXTAREA");function dt(t,e){var n=ut(t[0])?t.val():t.html();return e?n.replace(/[\n\r]/g,""):n}const ht={NBSP_CHAR:w,ZERO_WIDTH_NBSP_CHAR:"\ufeff",blank:M,emptyPara:"<p>".concat(M,"</p>"),makePredByNodeName:x,isEditable:C,isControlSizing:function(t){return t&&e()(t).hasClass("note-control-sizing")},isText:S,isElement:function(t){return t&&1===t.nodeType},isVoid:T,isPara:E,isPurePara:function(t){return E(t)&&!$(t)},isHeading:function(t){return t&&/^H[1-7]/.test(t.nodeName.toUpperCase())},isInline:R,isBlock:m.not(R),isBodyInline:function(t){return R(t)&&!W(t,E)},isBody:B,isParaInline:function(t){return R(t)&&!!W(t,E)},isPre:I,isList:L,isTable:N,isData:P,isCell:F,isBlockquote:D,isBodyContainer:H,isAnchor:z,isDiv:x("DIV"),isLi:$,isBR:x("BR"),isSpan:x("SPAN"),isB:x("B"),isU:x("U"),isS:x("S"),isI:x("I"),isImg:x("IMG"),isTextarea:ut,deepestChildIsEmpty:function(t){do{if(null===t.firstElementChild||""===t.firstElementChild.innerHTML)break}while(t=t.firstElementChild);return U(t)},isEmpty:U,isEmptyAnchor:m.and(z,U),isClosestSibling:function(t,e){return t.nextSibling===e||t.previousSibling===e},withClosestSiblings:function(t,e){e=e||m.ok;var n=[];return t.previousSibling&&e(t.previousSibling)&&n.push(t.previousSibling),n.push(t),t.nextSibling&&e(t.nextSibling)&&n.push(t.nextSibling),n},nodeLength:O,isLeftEdgePoint:G,isRightEdgePoint:Y,isEdgePoint:Z,isLeftEdgeOf:Q,isRightEdgeOf:X,isLeftEdgePointOf:function(t,e){return G(t)&&Q(t.node,e)},isRightEdgePointOf:function(t,e){return Y(t)&&X(t.node,e)},prevPoint:et,nextPoint:nt,nextPointWithEmptyNode:ot,isSamePoint:rt,isVisiblePoint:function(t){if(S(t.node)||!tt(t.node)||U(t.node))return!0;var e=t.node.childNodes[t.offset-1],n=t.node.childNodes[t.offset];return!((e&&!T(e)||n&&!T(n))&&!N(n))},prevPointUntil:function(t,e){for(;t;){if(e(t))return t;t=et(t)}return null},nextPointUntil:function(t,e){for(;t;){if(e(t))return t;t=nt(t)}return null},isCharPoint:function(t){if(!S(t.node))return!1;var e=t.node.nodeValue.charAt(t.offset-1);return e&&" "!==e&&e!==w},isSpacePoint:function(t){if(!S(t.node))return!1;var e=t.node.nodeValue.charAt(t.offset-1);return" "===e||e===w},walkPoint:function(t,e,n,o){for(var i=t;i&&(n(i),!rt(i,e));){i=ot(i,o&&t.node!==i.node&&e.node!==i.node)}},ancestor:W,singleChildAncestor:function(t,e){for(t=t.parentNode;t&&1===O(t);){if(e(t))return t;if(C(t))break;t=t.parentNode}return null},listAncestor:K,lastAncestor:function(t,e){var n=K(t);return k.last(n.filter(e))},listNext:V,listPrev:function(t,e){e=e||m.fail;for(var n=[];t&&!e(t);)n.push(t),t=t.previousSibling;return n},listDescendant:function(t,e){var n=[];return e=e||m.ok,function o(i){t!==i&&e(i)&&n.push(i);for(var r=0,a=i.childNodes.length;r<a;r++)o(i.childNodes[r])}(t),n},commonAncestor:function(t,e){for(var n=K(t),o=e;o;o=o.parentNode)if(n.indexOf(o)>-1)return o;return null},wrap:function(t,n){var o=t.parentNode,i=e()("<"+n+">")[0];return o.insertBefore(i,t),i.appendChild(t),i},insertAfter:q,appendChildNodes:_,position:J,hasChildren:tt,makeOffsetPath:function(t,e){return K(e,m.eq(t)).map(J).reverse()},fromOffsetPath:function(t,e){for(var n=t,o=0,i=e.length;o<i;o++)n=n.childNodes.length<=e[o]?n.childNodes[n.childNodes.length-1]:n.childNodes[e[o]];return n},splitTree:st,splitPoint:function(t,e){var n,o,i=e?E:H,r=K(t.node,i),a=k.last(r)||t.node;i(a)?(n=r[r.length-2],o=a):o=(n=a).parentNode;var s=n&&st(n,t,{isSkipPaddingBlankHTML:e,isNotSplitEdgePoint:e});return s||o!==t.node||(s=t.node.childNodes[t.offset]),{rightNode:s,container:o}},create:lt,createText:function(t){return document.createTextNode(t)},remove:ct,removeWhile:function(t,e){for(;t&&!C(t)&&e(t);){var n=t.parentNode;ct(t),t=n}},replace:function(t,e){if(t.nodeName.toUpperCase()===e.toUpperCase())return t;var n=lt(e);return t.style.cssText&&(n.style.cssText=t.style.cssText),_(n,k.from(t.childNodes)),q(n,t),ct(t),n},html:function(t,e){var n=dt(t);if(e){n=(n=n.replace(/<(\/?)(\b(?!!)[^>\s]*)(.*?)(\s*\/?>)/g,(function(t,e,n){n=n.toUpperCase();var o=/^DIV|^TD|^TH|^P|^LI|^H[1-7]/.test(n)&&!!e,i=/^BLOCKQUOTE|^TABLE|^TBODY|^TR|^HR|^UL|^OL/.test(n);return t+(o||i?"\n":"")}))).trim()}return n},value:dt,posFromPlaceholder:function(t){var n=e()(t),o=n.offset(),i=n.outerHeight(!0);return{left:o.left,top:o.top+i}},attachEvents:function(t,e){Object.keys(e).forEach((function(n){t.on(n,e[n])}))},detachEvents:function(t,e){Object.keys(e).forEach((function(n){t.off(n,e[n])}))},isCustomStyleTag:function(t){return t&&!S(t)&&k.contains(t.classList,"note-styletag")}};function ft(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var pt=function(){function t(n,o){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.$note=n,this.memos={},this.modules={},this.layoutInfo={},this.options=e().extend(!0,{},o),e().summernote.ui=e().summernote.ui_template(this.options),this.ui=e().summernote.ui,this.initialize()}var n,o,i;return n=t,o=[{key:"initialize",value:function(){return this.layoutInfo=this.ui.createLayout(this.$note),this._initialize(),this.$note.hide(),this}},{key:"destroy",value:function(){this._destroy(),this.$note.removeData("summernote"),this.ui.removeLayout(this.$note,this.layoutInfo)}},{key:"reset",value:function(){var t=this.isDisabled();this.code(ht.emptyPara),this._destroy(),this._initialize(),t&&this.disable()}},{key:"_initialize",value:function(){var t=this;this.options.id=m.uniqueId(e().now()),this.options.container=this.options.container||this.layoutInfo.editor;var n=e().extend({},this.options.buttons);Object.keys(n).forEach((function(e){t.memo("button."+e,n[e])}));var o=e().extend({},this.options.modules,e().summernote.plugins||{});Object.keys(o).forEach((function(e){t.module(e,o[e],!0)})),Object.keys(this.modules).forEach((function(e){t.initializeModule(e)}))}},{key:"_destroy",value:function(){var t=this;Object.keys(this.modules).reverse().forEach((function(e){t.removeModule(e)})),Object.keys(this.memos).forEach((function(e){t.removeMemo(e)})),this.triggerEvent("destroy",this)}},{key:"code",value:function(t){var e=this.invoke("codeview.isActivated");if(void 0===t)return this.invoke("codeview.sync"),e?this.layoutInfo.codable.val():this.layoutInfo.editable.html();e?this.invoke("codeview.sync",t):this.layoutInfo.editable.html(t),this.$note.val(t),this.triggerEvent("change",t,this.layoutInfo.editable)}},{key:"isDisabled",value:function(){return"false"===this.layoutInfo.editable.attr("contenteditable")}},{key:"enable",value:function(){this.layoutInfo.editable.attr("contenteditable",!0),this.invoke("toolbar.activate",!0),this.triggerEvent("disable",!1),this.options.editing=!0}},{key:"disable",value:function(){this.invoke("codeview.isActivated")&&this.invoke("codeview.deactivate"),this.layoutInfo.editable.attr("contenteditable",!1),this.options.editing=!1,this.invoke("toolbar.deactivate",!0),this.triggerEvent("disable",!0)}},{key:"triggerEvent",value:function(){var t=k.head(arguments),e=k.tail(k.from(arguments)),n=this.options.callbacks[m.namespaceToCamel(t,"on")];n&&n.apply(this.$note[0],e),this.$note.trigger("summernote."+t,e)}},{key:"initializeModule",value:function(t){var e=this.modules[t];e.shouldInitialize=e.shouldInitialize||m.ok,e.shouldInitialize()&&(e.initialize&&e.initialize(),e.events&&ht.attachEvents(this.$note,e.events))}},{key:"module",value:function(t,e,n){if(1===arguments.length)return this.modules[t];this.modules[t]=new e(this),n||this.initializeModule(t)}},{key:"removeModule",value:function(t){var e=this.modules[t];e.shouldInitialize()&&(e.events&&ht.detachEvents(this.$note,e.events),e.destroy&&e.destroy()),delete this.modules[t]}},{key:"memo",value:function(t,e){if(1===arguments.length)return this.memos[t];this.memos[t]=e}},{key:"removeMemo",value:function(t){this.memos[t]&&this.memos[t].destroy&&this.memos[t].destroy(),delete this.memos[t]}},{key:"createInvokeHandlerAndUpdateState",value:function(t,e){var n=this;return function(o){n.createInvokeHandler(t,e)(o),n.invoke("buttons.updateCurrentStyle")}}},{key:"createInvokeHandler",value:function(t,n){var o=this;return function(i){i.preventDefault();var r=e()(i.target);o.invoke(t,n||r.closest("[data-value]").data("value"),r)}}},{key:"invoke",value:function(){var t=k.head(arguments),e=k.tail(k.from(arguments)),n=t.split("."),o=n.length>1,i=o&&k.head(n),r=o?k.last(n):k.head(n),a=this.modules[i||"editor"];return!i&&this[r]?this[r].apply(this,e):a&&a[r]&&a.shouldInitialize()?a[r].apply(a,e):void 0}}],o&&ft(n.prototype,o),i&&ft(n,i),t}();function mt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}function vt(t,e){var n,o,i=t.parentElement(),r=document.body.createTextRange(),a=k.from(i.childNodes);for(n=0;n<a.length;n++)if(!ht.isText(a[n])){if(r.moveToElementText(a[n]),r.compareEndPoints("StartToStart",t)>=0)break;o=a[n]}if(0!==n&&ht.isText(a[n-1])){var s=document.body.createTextRange(),l=null;s.moveToElementText(o||i),s.collapse(!o),l=o?o.nextSibling:i.firstChild;var c=t.duplicate();c.setEndPoint("StartToStart",s);for(var u=c.text.replace(/[\r\n]/g,"").length;u>l.nodeValue.length&&l.nextSibling;)u-=l.nodeValue.length,l=l.nextSibling;l.nodeValue;e&&l.nextSibling&&ht.isText(l.nextSibling)&&u===l.nodeValue.length&&(u-=l.nodeValue.length,l=l.nextSibling),i=l,n=u}return{cont:i,offset:n}}function gt(t){var e=document.body.createTextRange(),n=function t(e,n){var o,i;if(ht.isText(e)){var r=ht.listPrev(e,m.not(ht.isText)),a=k.last(r).previousSibling;o=a||e.parentNode,n+=k.sum(k.tail(r),ht.nodeLength),i=!a}else{if(o=e.childNodes[n]||e,ht.isText(o))return t(o,0);n=0,i=!1}return{node:o,collapseToStart:i,offset:n}}(t.node,t.offset);return e.moveToElementText(n.node),e.collapse(n.collapseToStart),e.moveStart("character",n.offset),e}e().fn.extend({summernote:function(){var t=e().type(k.head(arguments)),n="string"===t,o="object"===t,i=e().extend({},e().summernote.options,o?k.head(arguments):{});i.langInfo=e().extend(!0,{},e().summernote.lang["en-US"],e().summernote.lang[i.lang]),i.icons=e().extend(!0,{},e().summernote.options.icons,i.icons),i.tooltip="auto"===i.tooltip?!f.isSupportTouch:i.tooltip,this.each((function(t,n){var o=e()(n);if(!o.data("summernote")){var r=new pt(o,i);o.data("summernote",r),o.data("summernote").triggerEvent("init",r.layoutInfo)}}));var r=this.first();if(r.length){var a=r.data("summernote");if(n)return a.invoke.apply(a,k.from(arguments));i.focus&&a.invoke("editor.focus")}return this}});var bt=function(){function t(e,n,o,i){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.sc=e,this.so=n,this.ec=o,this.eo=i,this.isOnEditable=this.makeIsOn(ht.isEditable),this.isOnList=this.makeIsOn(ht.isList),this.isOnAnchor=this.makeIsOn(ht.isAnchor),this.isOnCell=this.makeIsOn(ht.isCell),this.isOnData=this.makeIsOn(ht.isData)}var n,o,i;return n=t,o=[{key:"nativeRange",value:function(){if(f.isW3CRangeSupport){var t=document.createRange();return t.setStart(this.sc,this.so),t.setEnd(this.ec,this.eo),t}var e=gt({node:this.sc,offset:this.so});return e.setEndPoint("EndToEnd",gt({node:this.ec,offset:this.eo})),e}},{key:"getPoints",value:function(){return{sc:this.sc,so:this.so,ec:this.ec,eo:this.eo}}},{key:"getStartPoint",value:function(){return{node:this.sc,offset:this.so}}},{key:"getEndPoint",value:function(){return{node:this.ec,offset:this.eo}}},{key:"select",value:function(){var t=this.nativeRange();if(f.isW3CRangeSupport){var e=document.getSelection();e.rangeCount>0&&e.removeAllRanges(),e.addRange(t)}else t.select();return this}},{key:"scrollIntoView",value:function(t){var n=e()(t).height();return t.scrollTop+n<this.sc.offsetTop&&(t.scrollTop+=Math.abs(t.scrollTop+n-this.sc.offsetTop)),this}},{key:"normalize",value:function(){var e=function(t,e){if(!t)return t;if(ht.isVisiblePoint(t)&&(!ht.isEdgePoint(t)||ht.isRightEdgePoint(t)&&!e||ht.isLeftEdgePoint(t)&&e||ht.isRightEdgePoint(t)&&e&&ht.isVoid(t.node.nextSibling)||ht.isLeftEdgePoint(t)&&!e&&ht.isVoid(t.node.previousSibling)||ht.isBlock(t.node)&&ht.isEmpty(t.node)))return t;var n=ht.ancestor(t.node,ht.isBlock),o=!1;if(!o){var i=ht.prevPoint(t)||{node:null};o=(ht.isLeftEdgePointOf(t,n)||ht.isVoid(i.node))&&!e}var r=!1;if(!r){var a=ht.nextPoint(t)||{node:null};r=(ht.isRightEdgePointOf(t,n)||ht.isVoid(a.node))&&e}if(o||r){if(ht.isVisiblePoint(t))return t;e=!e}return(e?ht.nextPointUntil(ht.nextPoint(t),ht.isVisiblePoint):ht.prevPointUntil(ht.prevPoint(t),ht.isVisiblePoint))||t},n=e(this.getEndPoint(),!1),o=this.isCollapsed()?n:e(this.getStartPoint(),!0);return new t(o.node,o.offset,n.node,n.offset)}},{key:"nodes",value:function(t,e){t=t||m.ok;var n=e&&e.includeAncestor,o=e&&e.fullyContains,i=this.getStartPoint(),r=this.getEndPoint(),a=[],s=[];return ht.walkPoint(i,r,(function(e){var i;ht.isEditable(e.node)||(o?(ht.isLeftEdgePoint(e)&&s.push(e.node),ht.isRightEdgePoint(e)&&k.contains(s,e.node)&&(i=e.node)):i=n?ht.ancestor(e.node,t):e.node,i&&t(i)&&a.push(i))}),!0),k.unique(a)}},{key:"commonAncestor",value:function(){return ht.commonAncestor(this.sc,this.ec)}},{key:"expand",value:function(e){var n=ht.ancestor(this.sc,e),o=ht.ancestor(this.ec,e);if(!n&&!o)return new t(this.sc,this.so,this.ec,this.eo);var i=this.getPoints();return n&&(i.sc=n,i.so=0),o&&(i.ec=o,i.eo=ht.nodeLength(o)),new t(i.sc,i.so,i.ec,i.eo)}},{key:"collapse",value:function(e){return e?new t(this.sc,this.so,this.sc,this.so):new t(this.ec,this.eo,this.ec,this.eo)}},{key:"splitText",value:function(){var e=this.sc===this.ec,n=this.getPoints();return ht.isText(this.ec)&&!ht.isEdgePoint(this.getEndPoint())&&this.ec.splitText(this.eo),ht.isText(this.sc)&&!ht.isEdgePoint(this.getStartPoint())&&(n.sc=this.sc.splitText(this.so),n.so=0,e&&(n.ec=n.sc,n.eo=this.eo-this.so)),new t(n.sc,n.so,n.ec,n.eo)}},{key:"deleteContents",value:function(){if(this.isCollapsed())return this;var n=this.splitText(),o=n.nodes(null,{fullyContains:!0}),i=ht.prevPointUntil(n.getStartPoint(),(function(t){return!k.contains(o,t.node)})),r=[];return e().each(o,(function(t,e){var n=e.parentNode;i.node!==n&&1===ht.nodeLength(n)&&r.push(n),ht.remove(e,!1)})),e().each(r,(function(t,e){ht.remove(e,!1)})),new t(i.node,i.offset,i.node,i.offset).normalize()}},{key:"makeIsOn",value:function(t){return function(){var e=ht.ancestor(this.sc,t);return!!e&&e===ht.ancestor(this.ec,t)}}},{key:"isLeftEdgeOf",value:function(t){if(!ht.isLeftEdgePoint(this.getStartPoint()))return!1;var e=ht.ancestor(this.sc,t);return e&&ht.isLeftEdgeOf(this.sc,e)}},{key:"isCollapsed",value:function(){return this.sc===this.ec&&this.so===this.eo}},{key:"wrapBodyInlineWithPara",value:function(){if(ht.isBodyContainer(this.sc)&&ht.isEmpty(this.sc))return this.sc.innerHTML=ht.emptyPara,new t(this.sc.firstChild,0,this.sc.firstChild,0);var e,n=this.normalize();if(ht.isParaInline(this.sc)||ht.isPara(this.sc))return n;if(ht.isInline(n.sc)){var o=ht.listAncestor(n.sc,m.not(ht.isInline));e=k.last(o),ht.isInline(e)||(e=o[o.length-2]||n.sc.childNodes[n.so])}else e=n.sc.childNodes[n.so>0?n.so-1:0];if(e){var i=ht.listPrev(e,ht.isParaInline).reverse();if((i=i.concat(ht.listNext(e.nextSibling,ht.isParaInline))).length){var r=ht.wrap(k.head(i),"p");ht.appendChildNodes(r,k.tail(i))}}return this.normalize()}},{key:"insertNode",value:function(t){var e=this;(ht.isText(t)||ht.isInline(t))&&(e=this.wrapBodyInlineWithPara().deleteContents());var n=ht.splitPoint(e.getStartPoint(),ht.isInline(t));return n.rightNode?(n.rightNode.parentNode.insertBefore(t,n.rightNode),ht.isEmpty(n.rightNode)&&ht.isPara(t)&&n.rightNode.parentNode.removeChild(n.rightNode)):n.container.appendChild(t),t}},{key:"pasteHTML",value:function(t){t=e().trim(t);var n=e()("<div></div>").html(t)[0],o=k.from(n.childNodes),i=this,r=!1;return i.so>=0&&(o=o.reverse(),r=!0),o=o.map((function(t){return i.insertNode(t)})),r&&(o=o.reverse()),o}},{key:"toString",value:function(){var t=this.nativeRange();return f.isW3CRangeSupport?t.toString():t.text}},{key:"getWordRange",value:function(e){var n=this.getEndPoint();if(!ht.isCharPoint(n))return this;var o=ht.prevPointUntil(n,(function(t){return!ht.isCharPoint(t)}));return e&&(n=ht.nextPointUntil(n,(function(t){return!ht.isCharPoint(t)}))),new t(o.node,o.offset,n.node,n.offset)}},{key:"getWordsRange",value:function(e){var n=this.getEndPoint(),o=function(t){return!ht.isCharPoint(t)&&!ht.isSpacePoint(t)};if(o(n))return this;var i=ht.prevPointUntil(n,o);return e&&(n=ht.nextPointUntil(n,o)),new t(i.node,i.offset,n.node,n.offset)}},{key:"getWordsMatchRange",value:function(e){var n=this.getEndPoint(),o=ht.prevPointUntil(n,(function(o){if(!ht.isCharPoint(o)&&!ht.isSpacePoint(o))return!0;var i=new t(o.node,o.offset,n.node,n.offset),r=e.exec(i.toString());return r&&0===r.index})),i=new t(o.node,o.offset,n.node,n.offset),r=i.toString(),a=e.exec(r);return a&&a[0].length===r.length?i:null}},{key:"bookmark",value:function(t){return{s:{path:ht.makeOffsetPath(t,this.sc),offset:this.so},e:{path:ht.makeOffsetPath(t,this.ec),offset:this.eo}}}},{key:"paraBookmark",value:function(t){return{s:{path:k.tail(ht.makeOffsetPath(k.head(t),this.sc)),offset:this.so},e:{path:k.tail(ht.makeOffsetPath(k.last(t),this.ec)),offset:this.eo}}}},{key:"getClientRects",value:function(){return this.nativeRange().getClientRects()}}],o&&mt(n.prototype,o),i&&mt(n,i),t}();const yt={create:function(t,e,n,o){if(4===arguments.length)return new bt(t,e,n,o);if(2===arguments.length)return new bt(t,e,n=t,o=e);var i=this.createFromSelection();if(!i&&1===arguments.length){var r=arguments[0];return ht.isEditable(r)&&(r=r.lastChild),this.createFromBodyElement(r,ht.emptyPara===arguments[0].innerHTML)}return i},createFromBodyElement:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=this.createFromNode(t);return n.collapse(e)},createFromSelection:function(){var t,e,n,o;if(f.isW3CRangeSupport){var i=document.getSelection();if(!i||0===i.rangeCount)return null;if(ht.isBody(i.anchorNode))return null;var r=i.getRangeAt(0);t=r.startContainer,e=r.startOffset,n=r.endContainer,o=r.endOffset}else{var a=document.selection.createRange(),s=a.duplicate();s.collapse(!1);var l=a;l.collapse(!0);var c=vt(l,!0),u=vt(s,!1);ht.isText(c.node)&&ht.isLeftEdgePoint(c)&&ht.isTextNode(u.node)&&ht.isRightEdgePoint(u)&&u.node.nextSibling===c.node&&(c=u),t=c.cont,e=c.offset,n=u.cont,o=u.offset}return new bt(t,e,n,o)},createFromNode:function(t){var e=t,n=0,o=t,i=ht.nodeLength(o);return ht.isVoid(e)&&(n=ht.listPrev(e).length-1,e=e.parentNode),ht.isBR(o)?(i=ht.listPrev(o).length-1,o=o.parentNode):ht.isVoid(o)&&(i=ht.listPrev(o).length,o=o.parentNode),this.create(e,n,o,i)},createFromNodeBefore:function(t){return this.createFromNode(t).collapse(!0)},createFromNodeAfter:function(t){return this.createFromNode(t).collapse()},createFromBookmark:function(t,e){var n=ht.fromOffsetPath(t,e.s.path),o=e.s.offset,i=ht.fromOffsetPath(t,e.e.path),r=e.e.offset;return new bt(n,o,i,r)},createFromParaBookmark:function(t,e){var n=t.s.offset,o=t.e.offset,i=ht.fromOffsetPath(k.head(e),t.s.path),r=ht.fromOffsetPath(k.last(e),t.e.path);return new bt(i,n,r,o)}};var kt={BACKSPACE:8,TAB:9,ENTER:13,ESCAPE:27,SPACE:32,DELETE:46,LEFT:37,UP:38,RIGHT:39,DOWN:40,NUM0:48,NUM1:49,NUM2:50,NUM3:51,NUM4:52,NUM5:53,NUM6:54,NUM7:55,NUM8:56,B:66,E:69,I:73,J:74,K:75,L:76,R:82,S:83,U:85,V:86,Y:89,Z:90,SLASH:191,LEFTBRACKET:219,BACKSLASH:220,RIGHTBRACKET:221,HOME:36,END:35,PAGEUP:33,PAGEDOWN:34};const wt={isEdit:function(t){return k.contains([kt.BACKSPACE,kt.TAB,kt.ENTER,kt.SPACE,kt.DELETE],t)},isMove:function(t){return k.contains([kt.LEFT,kt.UP,kt.RIGHT,kt.DOWN],t)},isNavigation:function(t){return k.contains([kt.HOME,kt.END,kt.PAGEUP,kt.PAGEDOWN],t)},nameFromCode:m.invertObject(kt),code:kt};function Ct(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var xt=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.stack=[],this.stackOffset=-1,this.context=e,this.$editable=e.layoutInfo.editable,this.editable=this.$editable[0]}var e,n,o;return e=t,(n=[{key:"makeSnapshot",value:function(){var t=yt.create(this.editable);return{contents:this.$editable.html(),bookmark:t&&t.isOnEditable()?t.bookmark(this.editable):{s:{path:[],offset:0},e:{path:[],offset:0}}}}},{key:"applySnapshot",value:function(t){null!==t.contents&&this.$editable.html(t.contents),null!==t.bookmark&&yt.createFromBookmark(this.editable,t.bookmark).select()}},{key:"rewind",value:function(){this.$editable.html()!==this.stack[this.stackOffset].contents&&this.recordUndo(),this.stackOffset=0,this.applySnapshot(this.stack[this.stackOffset])}},{key:"commit",value:function(){this.stack=[],this.stackOffset=-1,this.recordUndo()}},{key:"reset",value:function(){this.stack=[],this.stackOffset=-1,this.$editable.html(""),this.recordUndo()}},{key:"undo",value:function(){this.$editable.html()!==this.stack[this.stackOffset].contents&&this.recordUndo(),this.stackOffset>0&&(this.stackOffset--,this.applySnapshot(this.stack[this.stackOffset]))}},{key:"redo",value:function(){this.stack.length-1>this.stackOffset&&(this.stackOffset++,this.applySnapshot(this.stack[this.stackOffset]))}},{key:"recordUndo",value:function(){this.stackOffset++,this.stack.length>this.stackOffset&&(this.stack=this.stack.slice(0,this.stackOffset)),this.stack.push(this.makeSnapshot()),this.stack.length>this.context.options.historyLimit&&(this.stack.shift(),this.stackOffset-=1)}}])&&Ct(e.prototype,n),o&&Ct(e,o),t}();function St(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Tt=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var n,o,i;return n=t,o=[{key:"jQueryCSS",value:function(t,n){var o={};return e().each(n,(function(e,n){o[n]=t.css(n)})),o}},{key:"fromNode",value:function(t){var e=this.jQueryCSS(t,["font-family","font-size","text-align","list-style-type","line-height"])||{},n=t[0].style.fontSize||e["font-size"];return e["font-size"]=parseInt(n,10),e["font-size-unit"]=n.match(/[a-z%]+$/),e}},{key:"stylePara",value:function(t,n){e().each(t.nodes(ht.isPara,{includeAncestor:!0}),(function(t,o){e()(o).css(n)}))}},{key:"styleNodes",value:function(t,n){t=t.splitText();var o=n&&n.nodeName||"SPAN",i=!(!n||!n.expandClosestSibling),r=!(!n||!n.onlyPartialContains);if(t.isCollapsed())return[t.insertNode(ht.create(o))];var a=ht.makePredByNodeName(o),s=t.nodes(ht.isText,{fullyContains:!0}).map((function(t){return ht.singleChildAncestor(t,a)||ht.wrap(t,o)}));if(i){if(r){var l=t.nodes();a=m.and(a,(function(t){return k.contains(l,t)}))}return s.map((function(t){var n=ht.withClosestSiblings(t,a),o=k.head(n),i=k.tail(n);return e().each(i,(function(t,e){ht.appendChildNodes(o,e.childNodes),ht.remove(e)})),k.head(n)}))}return s}},{key:"current",value:function(t){var n=e()(ht.isElement(t.sc)?t.sc:t.sc.parentNode),o=this.fromNode(n);try{o=e().extend(o,{"font-bold":document.queryCommandState("bold")?"bold":"normal","font-italic":document.queryCommandState("italic")?"italic":"normal","font-underline":document.queryCommandState("underline")?"underline":"normal","font-subscript":document.queryCommandState("subscript")?"subscript":"normal","font-superscript":document.queryCommandState("superscript")?"superscript":"normal","font-strikethrough":document.queryCommandState("strikethrough")?"strikethrough":"normal","font-family":document.queryCommandValue("fontname")||o["font-family"]})}catch(t){}if(t.isOnList()){var i=["circle","disc","disc-leading-zero","square"].indexOf(o["list-style-type"])>-1;o["list-style"]=i?"unordered":"ordered"}else o["list-style"]="none";var r=ht.ancestor(t.sc,ht.isPara);if(r&&r.style["line-height"])o["line-height"]=r.style.lineHeight;else{var a=parseInt(o["line-height"],10)/parseInt(o["font-size"],10);o["line-height"]=a.toFixed(1)}return o.anchor=t.isOnAnchor()&&ht.ancestor(t.sc,ht.isAnchor),o.ancestors=ht.listAncestor(t.sc,ht.isEditable),o.range=t,o}}],o&&St(n.prototype,o),i&&St(n,i),t}();function Et(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var It=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var n,o,i;return n=t,o=[{key:"insertOrderedList",value:function(t){this.toggleList("OL",t)}},{key:"insertUnorderedList",value:function(t){this.toggleList("UL",t)}},{key:"indent",value:function(t){var n=this,o=yt.create(t).wrapBodyInlineWithPara(),i=o.nodes(ht.isPara,{includeAncestor:!0}),r=k.clusterBy(i,m.peq2("parentNode"));e().each(r,(function(t,o){var i=k.head(o);if(ht.isLi(i)){var r=n.findList(i.previousSibling);r?o.map((function(t){return r.appendChild(t)})):(n.wrapList(o,i.parentNode.nodeName),o.map((function(t){return t.parentNode})).map((function(t){return n.appendToPrevious(t)})))}else e().each(o,(function(t,n){e()(n).css("marginLeft",(function(t,e){return(parseInt(e,10)||0)+25}))}))})),o.select()}},{key:"outdent",value:function(t){var n=this,o=yt.create(t).wrapBodyInlineWithPara(),i=o.nodes(ht.isPara,{includeAncestor:!0}),r=k.clusterBy(i,m.peq2("parentNode"));e().each(r,(function(t,o){var i=k.head(o);ht.isLi(i)?n.releaseList([o]):e().each(o,(function(t,n){e()(n).css("marginLeft",(function(t,e){return(e=parseInt(e,10)||0)>25?e-25:""}))}))})),o.select()}},{key:"toggleList",value:function(t,n){var o=this,i=yt.create(n).wrapBodyInlineWithPara(),r=i.nodes(ht.isPara,{includeAncestor:!0}),a=i.paraBookmark(r),s=k.clusterBy(r,m.peq2("parentNode"));if(k.find(r,ht.isPurePara)){var l=[];e().each(s,(function(e,n){l=l.concat(o.wrapList(n,t))})),r=l}else{var c=i.nodes(ht.isList,{includeAncestor:!0}).filter((function(n){return!e().nodeName(n,t)}));c.length?e().each(c,(function(e,n){ht.replace(n,t)})):r=this.releaseList(s,!0)}yt.createFromParaBookmark(a,r).select()}},{key:"wrapList",value:function(t,e){var n=k.head(t),o=k.last(t),i=ht.isList(n.previousSibling)&&n.previousSibling,r=ht.isList(o.nextSibling)&&o.nextSibling,a=i||ht.insertAfter(ht.create(e||"UL"),o);return t=t.map((function(t){return ht.isPurePara(t)?ht.replace(t,"LI"):t})),ht.appendChildNodes(a,t),r&&(ht.appendChildNodes(a,k.from(r.childNodes)),ht.remove(r)),t}},{key:"releaseList",value:function(t,n){var o=this,i=[];return e().each(t,(function(t,r){var a=k.head(r),s=k.last(r),l=n?ht.lastAncestor(a,ht.isList):a.parentNode,c=l.parentNode;if("LI"===l.parentNode.nodeName)r.map((function(t){var e=o.findNextSiblings(t);c.nextSibling?c.parentNode.insertBefore(t,c.nextSibling):c.parentNode.appendChild(t),e.length&&(o.wrapList(e,l.nodeName),t.appendChild(e[0].parentNode))})),0===l.children.length&&c.removeChild(l),0===c.childNodes.length&&c.parentNode.removeChild(c);else{var u=l.childNodes.length>1?ht.splitTree(l,{node:s.parentNode,offset:ht.position(s)+1},{isSkipPaddingBlankHTML:!0}):null,d=ht.splitTree(l,{node:a.parentNode,offset:ht.position(a)},{isSkipPaddingBlankHTML:!0});r=n?ht.listDescendant(d,ht.isLi):k.from(d.childNodes).filter(ht.isLi),!n&&ht.isList(l.parentNode)||(r=r.map((function(t){return ht.replace(t,"P")}))),e().each(k.from(r).reverse(),(function(t,e){ht.insertAfter(e,l)}));var h=k.compact([l,d,u]);e().each(h,(function(t,n){var o=[n].concat(ht.listDescendant(n,ht.isList));e().each(o.reverse(),(function(t,e){ht.nodeLength(e)||ht.remove(e,!0)}))}))}i=i.concat(r)})),i}},{key:"appendToPrevious",value:function(t){return t.previousSibling?ht.appendChildNodes(t.previousSibling,[t]):this.wrapList([t],"LI")}},{key:"findList",value:function(t){return t?k.find(t.children,(function(t){return["OL","UL"].indexOf(t.nodeName)>-1})):null}},{key:"findNextSiblings",value:function(t){for(var e=[];t.nextSibling;)e.push(t.nextSibling),t=t.nextSibling;return e}}],o&&Et(n.prototype,o),i&&Et(n,i),t}();function $t(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Nt=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.bullet=new It,this.options=e.options}var n,o,i;return n=t,o=[{key:"insertTab",value:function(t,e){var n=ht.createText(new Array(e+1).join(ht.NBSP_CHAR));(t=t.deleteContents()).insertNode(n,!0),(t=yt.create(n,e)).select()}},{key:"insertParagraph",value:function(t,n){n=(n=(n=n||yt.create(t)).deleteContents()).wrapBodyInlineWithPara();var o,i=ht.ancestor(n.sc,ht.isPara);if(i){if(ht.isLi(i)&&(ht.isEmpty(i)||ht.deepestChildIsEmpty(i)))return void this.bullet.toggleList(i.parentNode.nodeName);var r=null;if(1===this.options.blockquoteBreakingLevel?r=ht.ancestor(i,ht.isBlockquote):2===this.options.blockquoteBreakingLevel&&(r=ht.lastAncestor(i,ht.isBlockquote)),r){o=e()(ht.emptyPara)[0],ht.isRightEdgePoint(n.getStartPoint())&&ht.isBR(n.sc.nextSibling)&&e()(n.sc.nextSibling).remove();var a=ht.splitTree(r,n.getStartPoint(),{isDiscardEmptySplits:!0});a?a.parentNode.insertBefore(o,a):ht.insertAfter(o,r)}else{o=ht.splitTree(i,n.getStartPoint());var s=ht.listDescendant(i,ht.isEmptyAnchor);s=s.concat(ht.listDescendant(o,ht.isEmptyAnchor)),e().each(s,(function(t,e){ht.remove(e)})),(ht.isHeading(o)||ht.isPre(o)||ht.isCustomStyleTag(o))&&ht.isEmpty(o)&&(o=ht.replace(o,"p"))}}else{var l=n.sc.childNodes[n.so];o=e()(ht.emptyPara)[0],l?n.sc.insertBefore(o,l):n.sc.appendChild(o)}yt.create(o,0).normalize().select().scrollIntoView(t)}}],o&&$t(n.prototype,o),i&&$t(n,i),t}();function Pt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Rt=function t(e,n,o,i){var r={colPos:0,rowPos:0},a=[],s=[];function l(t,e,n,o,i,r,s){var l={baseRow:n,baseCell:o,isRowSpan:i,isColSpan:r,isVirtual:s};a[t]||(a[t]=[]),a[t][e]=l}function c(t,e,n,o){return{baseCell:t.baseCell,action:e,virtualTable:{rowIndex:n,cellIndex:o}}}function u(t,e){if(!a[t])return e;if(!a[t][e])return e;for(var n=e;a[t][n];)if(n++,!a[t][n])return n}function d(t,e){var n=u(t.rowIndex,e.cellIndex),o=e.colSpan>1,i=e.rowSpan>1,a=t.rowIndex===r.rowPos&&e.cellIndex===r.colPos;l(t.rowIndex,n,t,e,i,o,!1);var s=e.attributes.rowSpan?parseInt(e.attributes.rowSpan.value,10):0;if(s>1)for(var c=1;c<s;c++){var d=t.rowIndex+c;h(d,n,e,a),l(d,n,t,e,!0,o,!0)}var f=e.attributes.colSpan?parseInt(e.attributes.colSpan.value,10):0;if(f>1)for(var p=1;p<f;p++){var m=u(t.rowIndex,n+p);h(t.rowIndex,m,e,a),l(t.rowIndex,m,t,e,i,!0,!0)}}function h(t,e,n,o){t===r.rowPos&&r.colPos>=n.cellIndex&&n.cellIndex<=e&&!o&&r.colPos++}function f(e){switch(n){case t.where.Column:if(e.isColSpan)return t.resultAction.SubtractSpanCount;break;case t.where.Row:if(!e.isVirtual&&e.isRowSpan)return t.resultAction.AddCell;if(e.isRowSpan)return t.resultAction.SubtractSpanCount}return t.resultAction.RemoveCell}function p(e){switch(n){case t.where.Column:if(e.isColSpan)return t.resultAction.SumSpanCount;if(e.isRowSpan&&e.isVirtual)return t.resultAction.Ignore;break;case t.where.Row:if(e.isRowSpan)return t.resultAction.SumSpanCount;if(e.isColSpan&&e.isVirtual)return t.resultAction.Ignore}return t.resultAction.AddCell}this.getActionList=function(){for(var e=n===t.where.Row?r.rowPos:-1,i=n===t.where.Column?r.colPos:-1,l=0,u=!0;u;){var d=e>=0?e:l,h=i>=0?i:l,m=a[d];if(!m)return u=!1,s;var v=m[h];if(!v)return u=!1,s;var g=t.resultAction.Ignore;switch(o){case t.requestAction.Add:g=p(v);break;case t.requestAction.Delete:g=f(v)}s.push(c(v,g,d,h)),l++}return s},e&&e.tagName&&("td"===e.tagName.toLowerCase()||"th"===e.tagName.toLowerCase())&&(r.colPos=e.cellIndex,e.parentElement&&e.parentElement.tagName&&"tr"===e.parentElement.tagName.toLowerCase()&&(r.rowPos=e.parentElement.rowIndex)),function(){for(var t=i.rows,e=0;e<t.length;e++)for(var n=t[e].cells,o=0;o<n.length;o++)d(t[e],n[o])}()};Rt.where={Row:0,Column:1},Rt.requestAction={Add:0,Delete:1},Rt.resultAction={Ignore:0,SubtractSpanCount:1,RemoveCell:2,AddCell:3,SumSpanCount:4};var Lt=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var n,o,i;return n=t,o=[{key:"tab",value:function(t,e){var n=ht.ancestor(t.commonAncestor(),ht.isCell),o=ht.ancestor(n,ht.isTable),i=ht.listDescendant(o,ht.isCell),r=k[e?"prev":"next"](i,n);r&&yt.create(r,0).select()}},{key:"addRow",value:function(t,n){for(var o=ht.ancestor(t.commonAncestor(),ht.isCell),i=e()(o).closest("tr"),r=this.recoverAttributes(i),a=e()("<tr"+r+"></tr>"),s=new Rt(o,Rt.where.Row,Rt.requestAction.Add,e()(i).closest("table")[0]).getActionList(),l=0;l<s.length;l++){var c=s[l],u=this.recoverAttributes(c.baseCell);switch(c.action){case Rt.resultAction.AddCell:a.append("<td"+u+">"+ht.blank+"</td>");break;case Rt.resultAction.SumSpanCount:if("top"===n&&(c.baseCell.parent?c.baseCell.closest("tr").rowIndex:0)<=i[0].rowIndex){var d=e()("<div></div>").append(e()("<td"+u+">"+ht.blank+"</td>").removeAttr("rowspan")).html();a.append(d);break}var h=parseInt(c.baseCell.rowSpan,10);h++,c.baseCell.setAttribute("rowSpan",h)}}if("top"===n)i.before(a);else{if(o.rowSpan>1){var f=i[0].rowIndex+(o.rowSpan-2);return void e()(e()(i).parent().find("tr")[f]).after(e()(a))}i.after(a)}}},{key:"addCol",value:function(t,n){var o=ht.ancestor(t.commonAncestor(),ht.isCell),i=e()(o).closest("tr");e()(i).siblings().push(i);for(var r=new Rt(o,Rt.where.Column,Rt.requestAction.Add,e()(i).closest("table")[0]).getActionList(),a=0;a<r.length;a++){var s=r[a],l=this.recoverAttributes(s.baseCell);switch(s.action){case Rt.resultAction.AddCell:"right"===n?e()(s.baseCell).after("<td"+l+">"+ht.blank+"</td>"):e()(s.baseCell).before("<td"+l+">"+ht.blank+"</td>");break;case Rt.resultAction.SumSpanCount:if("right"===n){var c=parseInt(s.baseCell.colSpan,10);c++,s.baseCell.setAttribute("colSpan",c)}else e()(s.baseCell).before("<td"+l+">"+ht.blank+"</td>")}}}},{key:"recoverAttributes",value:function(t){var e="";if(!t)return e;for(var n=t.attributes||[],o=0;o<n.length;o++)"id"!==n[o].name.toLowerCase()&&n[o].specified&&(e+=" "+n[o].name+"='"+n[o].value+"'");return e}},{key:"deleteRow",value:function(t){for(var n=ht.ancestor(t.commonAncestor(),ht.isCell),o=e()(n).closest("tr"),i=o.children("td, th").index(e()(n)),r=o[0].rowIndex,a=new Rt(n,Rt.where.Row,Rt.requestAction.Delete,e()(o).closest("table")[0]).getActionList(),s=0;s<a.length;s++)if(a[s]){var l=a[s].baseCell,c=a[s].virtualTable,u=l.rowSpan&&l.rowSpan>1,d=u?parseInt(l.rowSpan,10):0;switch(a[s].action){case Rt.resultAction.Ignore:continue;case Rt.resultAction.AddCell:var h=o.next("tr")[0];if(!h)continue;var f=o[0].cells[i];u&&(d>2?(d--,h.insertBefore(f,h.cells[i]),h.cells[i].setAttribute("rowSpan",d),h.cells[i].innerHTML=""):2===d&&(h.insertBefore(f,h.cells[i]),h.cells[i].removeAttribute("rowSpan"),h.cells[i].innerHTML=""));continue;case Rt.resultAction.SubtractSpanCount:u&&(d>2?(d--,l.setAttribute("rowSpan",d),c.rowIndex!==r&&l.cellIndex===i&&(l.innerHTML="")):2===d&&(l.removeAttribute("rowSpan"),c.rowIndex!==r&&l.cellIndex===i&&(l.innerHTML="")));continue;case Rt.resultAction.RemoveCell:continue}}o.remove()}},{key:"deleteCol",value:function(t){for(var n=ht.ancestor(t.commonAncestor(),ht.isCell),o=e()(n).closest("tr"),i=o.children("td, th").index(e()(n)),r=new Rt(n,Rt.where.Column,Rt.requestAction.Delete,e()(o).closest("table")[0]).getActionList(),a=0;a<r.length;a++)if(r[a])switch(r[a].action){case Rt.resultAction.Ignore:continue;case Rt.resultAction.SubtractSpanCount:var s=r[a].baseCell;if(s.colSpan&&s.colSpan>1){var l=s.colSpan?parseInt(s.colSpan,10):0;l>2?(l--,s.setAttribute("colSpan",l),s.cellIndex===i&&(s.innerHTML="")):2===l&&(s.removeAttribute("colSpan"),s.cellIndex===i&&(s.innerHTML=""))}continue;case Rt.resultAction.RemoveCell:ht.remove(r[a].baseCell,!0);continue}}},{key:"createTable",value:function(t,n,o){for(var i,r=[],a=0;a<t;a++)r.push("<td>"+ht.blank+"</td>");i=r.join("");for(var s,l=[],c=0;c<n;c++)l.push("<tr>"+i+"</tr>");s=l.join("");var u=e()("<table>"+s+"</table>");return o&&o.tableClassName&&u.addClass(o.tableClassName),u[0]}},{key:"deleteTable",value:function(t){var n=ht.ancestor(t.commonAncestor(),ht.isCell);e()(n).closest("table").remove()}}],o&&Pt(n.prototype,o),i&&Pt(n,i),t}();function At(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Ft=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.$note=n.layoutInfo.note,this.$editor=n.layoutInfo.editor,this.$editable=n.layoutInfo.editable,this.options=n.options,this.lang=this.options.langInfo,this.editable=this.$editable[0],this.lastRange=null,this.snapshot=null,this.style=new Tt,this.table=new Lt,this.typing=new Nt(n),this.bullet=new It,this.history=new xt(n),this.context.memo("help.escape",this.lang.help.escape),this.context.memo("help.undo",this.lang.help.undo),this.context.memo("help.redo",this.lang.help.redo),this.context.memo("help.tab",this.lang.help.tab),this.context.memo("help.untab",this.lang.help.untab),this.context.memo("help.insertParagraph",this.lang.help.insertParagraph),this.context.memo("help.insertOrderedList",this.lang.help.insertOrderedList),this.context.memo("help.insertUnorderedList",this.lang.help.insertUnorderedList),this.context.memo("help.indent",this.lang.help.indent),this.context.memo("help.outdent",this.lang.help.outdent),this.context.memo("help.formatPara",this.lang.help.formatPara),this.context.memo("help.insertHorizontalRule",this.lang.help.insertHorizontalRule),this.context.memo("help.fontName",this.lang.help.fontName);for(var i=["bold","italic","underline","strikethrough","superscript","subscript","justifyLeft","justifyCenter","justifyRight","justifyFull","formatBlock","removeFormat","backColor"],r=0,a=i.length;r<a;r++)this[i[r]]=function(t){return function(e){o.beforeCommand(),document.execCommand(t,!1,e),o.afterCommand(!0)}}(i[r]),this.context.memo("help."+i[r],this.lang.help[i[r]]);this.fontName=this.wrapCommand((function(t){return o.fontStyling("font-family",f.validFontName(t))})),this.fontSize=this.wrapCommand((function(t){var e=o.currentStyle()["font-size-unit"];return o.fontStyling("font-size",t+e)})),this.fontSizeUnit=this.wrapCommand((function(t){var e=o.currentStyle()["font-size"];return o.fontStyling("font-size",e+t)}));for(var s=1;s<=6;s++)this["formatH"+s]=function(t){return function(){o.formatBlock("H"+t)}}(s),this.context.memo("help.formatH"+s,this.lang.help["formatH"+s]);this.insertParagraph=this.wrapCommand((function(){o.typing.insertParagraph(o.editable)})),this.insertOrderedList=this.wrapCommand((function(){o.bullet.insertOrderedList(o.editable)})),this.insertUnorderedList=this.wrapCommand((function(){o.bullet.insertUnorderedList(o.editable)})),this.indent=this.wrapCommand((function(){o.bullet.indent(o.editable)})),this.outdent=this.wrapCommand((function(){o.bullet.outdent(o.editable)})),this.insertNode=this.wrapCommand((function(t){o.isLimited(e()(t).text().length)||(o.getLastRange().insertNode(t),o.setLastRange(yt.createFromNodeAfter(t).select()))})),this.insertText=this.wrapCommand((function(t){if(!o.isLimited(t.length)){var e=o.getLastRange().insertNode(ht.createText(t));o.setLastRange(yt.create(e,ht.nodeLength(e)).select())}})),this.pasteHTML=this.wrapCommand((function(t){if(!o.isLimited(t.length)){t=o.context.invoke("codeview.purify",t);var e=o.getLastRange().pasteHTML(t);o.setLastRange(yt.createFromNodeAfter(k.last(e)).select())}})),this.formatBlock=this.wrapCommand((function(t,e){var n=o.options.callbacks.onApplyCustomStyle;n?n.call(o,e,o.context,o.onFormatBlock):o.onFormatBlock(t,e)})),this.insertHorizontalRule=this.wrapCommand((function(){var t=o.getLastRange().insertNode(ht.create("HR"));t.nextSibling&&o.setLastRange(yt.create(t.nextSibling,0).normalize().select())})),this.lineHeight=this.wrapCommand((function(t){o.style.stylePara(o.getLastRange(),{lineHeight:t})})),this.createLink=this.wrapCommand((function(t){var n=t.url,i=t.text,r=t.isNewWindow,a=t.checkProtocol,s=t.range||o.getLastRange(),l=i.length-s.toString().length;if(!(l>0&&o.isLimited(l))){var c=s.toString()!==i;"string"==typeof n&&(n=n.trim()),o.options.onCreateLink?n=o.options.onCreateLink(n):a&&(n=/^([A-Za-z][A-Za-z0-9+-.]*\:|#|\/)/.test(n)?n:o.options.defaultProtocol+n);var u=[];if(c){var d=(s=s.deleteContents()).insertNode(e()("<A>"+i+"</A>")[0]);u.push(d)}else u=o.style.styleNodes(s,{nodeName:"A",expandClosestSibling:!0,onlyPartialContains:!0});e().each(u,(function(t,o){e()(o).attr("href",n),r?e()(o).attr("target","_blank"):e()(o).removeAttr("target")})),o.setLastRange(o.createRangeFromList(u).select())}})),this.color=this.wrapCommand((function(t){var e=t.foreColor,n=t.backColor;e&&document.execCommand("foreColor",!1,e),n&&document.execCommand("backColor",!1,n)})),this.foreColor=this.wrapCommand((function(t){document.execCommand("foreColor",!1,t)})),this.insertTable=this.wrapCommand((function(t){var e=t.split("x");o.getLastRange().deleteContents().insertNode(o.table.createTable(e[0],e[1],o.options))})),this.removeMedia=this.wrapCommand((function(){var t=e()(o.restoreTarget()).parent();t.closest("figure").length?t.closest("figure").remove():t=e()(o.restoreTarget()).detach(),o.context.triggerEvent("media.delete",t,o.$editable)})),this.floatMe=this.wrapCommand((function(t){var n=e()(o.restoreTarget());n.toggleClass("note-float-left","left"===t),n.toggleClass("note-float-right","right"===t),n.css("float","none"===t?"":t)})),this.resize=this.wrapCommand((function(t){var n=e()(o.restoreTarget());0===(t=parseFloat(t))?n.css("width",""):n.css({width:100*t+"%",height:""})}))}var n,o,i;return n=t,o=[{key:"initialize",value:function(){var t=this;this.$editable.on("keydown",(function(e){if(e.keyCode===wt.code.ENTER&&t.context.triggerEvent("enter",e),t.context.triggerEvent("keydown",e),t.snapshot=t.history.makeSnapshot(),t.hasKeyShortCut=!1,e.isDefaultPrevented()||(t.options.shortcuts?t.hasKeyShortCut=t.handleKeyMap(e):t.preventDefaultEditableShortCuts(e)),t.isLimited(1,e)){var n=t.getLastRange();if(n.eo-n.so==0)return!1}t.setLastRange(),t.options.recordEveryKeystroke&&!1===t.hasKeyShortCut&&t.history.recordUndo()})).on("keyup",(function(e){t.setLastRange(),t.context.triggerEvent("keyup",e)})).on("focus",(function(e){t.setLastRange(),t.context.triggerEvent("focus",e)})).on("blur",(function(e){t.context.triggerEvent("blur",e)})).on("mousedown",(function(e){t.context.triggerEvent("mousedown",e)})).on("mouseup",(function(e){t.setLastRange(),t.history.recordUndo(),t.context.triggerEvent("mouseup",e)})).on("scroll",(function(e){t.context.triggerEvent("scroll",e)})).on("paste",(function(e){t.setLastRange(),t.context.triggerEvent("paste",e)})).on("input",(function(){t.isLimited(0)&&t.snapshot&&t.history.applySnapshot(t.snapshot)})),this.$editable.attr("spellcheck",this.options.spellCheck),this.$editable.attr("autocorrect",this.options.spellCheck),this.options.disableGrammar&&this.$editable.attr("data-gramm",!1),this.$editable.html(ht.html(this.$note)||ht.emptyPara),this.$editable.on(f.inputEventName,m.debounce((function(){t.context.triggerEvent("change",t.$editable.html(),t.$editable)}),10)),this.$editable.on("focusin",(function(e){t.context.triggerEvent("focusin",e)})).on("focusout",(function(e){t.context.triggerEvent("focusout",e)})),this.options.airMode?this.options.overrideContextMenu&&this.$editor.on("contextmenu",(function(e){return t.context.triggerEvent("contextmenu",e),!1})):(this.options.width&&this.$editor.outerWidth(this.options.width),this.options.height&&this.$editable.outerHeight(this.options.height),this.options.maxHeight&&this.$editable.css("max-height",this.options.maxHeight),this.options.minHeight&&this.$editable.css("min-height",this.options.minHeight)),this.history.recordUndo(),this.setLastRange()}},{key:"destroy",value:function(){this.$editable.off()}},{key:"handleKeyMap",value:function(t){var e=this.options.keyMap[f.isMac?"mac":"pc"],n=[];t.metaKey&&n.push("CMD"),t.ctrlKey&&!t.altKey&&n.push("CTRL"),t.shiftKey&&n.push("SHIFT");var o=wt.nameFromCode[t.keyCode];o&&n.push(o);var i=e[n.join("+")];if("TAB"!==o||this.options.tabDisable)if(i){if(!1!==this.context.invoke(i))return t.preventDefault(),!0}else wt.isEdit(t.keyCode)&&this.afterCommand();else this.afterCommand();return!1}},{key:"preventDefaultEditableShortCuts",value:function(t){(t.ctrlKey||t.metaKey)&&k.contains([66,73,85],t.keyCode)&&t.preventDefault()}},{key:"isLimited",value:function(t,e){return t=t||0,(void 0===e||!(wt.isMove(e.keyCode)||wt.isNavigation(e.keyCode)||e.ctrlKey||e.metaKey||k.contains([wt.code.BACKSPACE,wt.code.DELETE],e.keyCode)))&&this.options.maxTextLength>0&&this.$editable.text().length+t>this.options.maxTextLength}},{key:"createRange",value:function(){return this.focus(),this.setLastRange(),this.getLastRange()}},{key:"createRangeFromList",value:function(t){var e=yt.createFromNodeBefore(k.head(t)).getStartPoint(),n=yt.createFromNodeAfter(k.last(t)).getEndPoint();return yt.create(e.node,e.offset,n.node,n.offset)}},{key:"setLastRange",value:function(t){t?this.lastRange=t:(this.lastRange=yt.create(this.editable),0===e()(this.lastRange.sc).closest(".note-editable").length&&(this.lastRange=yt.createFromBodyElement(this.editable)))}},{key:"getLastRange",value:function(){return this.lastRange||this.setLastRange(),this.lastRange}},{key:"saveRange",value:function(t){t&&this.getLastRange().collapse().select()}},{key:"restoreRange",value:function(){this.lastRange&&(this.lastRange.select(),this.focus())}},{key:"saveTarget",value:function(t){this.$editable.data("target",t)}},{key:"clearTarget",value:function(){this.$editable.removeData("target")}},{key:"restoreTarget",value:function(){return this.$editable.data("target")}},{key:"currentStyle",value:function(){var t=yt.create();return t&&(t=t.normalize()),t?this.style.current(t):this.style.fromNode(this.$editable)}},{key:"styleFromNode",value:function(t){return this.style.fromNode(t)}},{key:"undo",value:function(){this.context.triggerEvent("before.command",this.$editable.html()),this.history.undo(),this.context.triggerEvent("change",this.$editable.html(),this.$editable)}},{key:"commit",value:function(){this.context.triggerEvent("before.command",this.$editable.html()),this.history.commit(),this.context.triggerEvent("change",this.$editable.html(),this.$editable)}},{key:"redo",value:function(){this.context.triggerEvent("before.command",this.$editable.html()),this.history.redo(),this.context.triggerEvent("change",this.$editable.html(),this.$editable)}},{key:"beforeCommand",value:function(){this.context.triggerEvent("before.command",this.$editable.html()),document.execCommand("styleWithCSS",!1,this.options.styleWithCSS),this.focus()}},{key:"afterCommand",value:function(t){this.normalizeContent(),this.history.recordUndo(),t||this.context.triggerEvent("change",this.$editable.html(),this.$editable)}},{key:"tab",value:function(){var t=this.getLastRange();if(t.isCollapsed()&&t.isOnCell())this.table.tab(t);else{if(0===this.options.tabSize)return!1;this.isLimited(this.options.tabSize)||(this.beforeCommand(),this.typing.insertTab(t,this.options.tabSize),this.afterCommand())}}},{key:"untab",value:function(){var t=this.getLastRange();if(t.isCollapsed()&&t.isOnCell())this.table.tab(t,!0);else if(0===this.options.tabSize)return!1}},{key:"wrapCommand",value:function(t){return function(){this.beforeCommand(),t.apply(this,arguments),this.afterCommand()}}},{key:"insertImage",value:function(t,n){var o,i=this;return(o=t,e().Deferred((function(t){var n=e()("<img>");n.one("load",(function(){n.off("error abort"),t.resolve(n)})).one("error abort",(function(){n.off("load").detach(),t.reject(n)})).css({display:"none"}).appendTo(document.body).attr("src",o)})).promise()).then((function(t){i.beforeCommand(),"function"==typeof n?n(t):("string"==typeof n&&t.attr("data-filename",n),t.css("width",Math.min(i.$editable.width(),t.width()))),t.show(),i.getLastRange().insertNode(t[0]),i.setLastRange(yt.createFromNodeAfter(t[0]).select()),i.afterCommand()})).fail((function(t){i.context.triggerEvent("image.upload.error",t)}))}},{key:"insertImagesAsDataURL",value:function(t){var n=this;e().each(t,(function(t,o){var i=o.name;n.options.maximumImageFileSize&&n.options.maximumImageFileSize<o.size?n.context.triggerEvent("image.upload.error",n.lang.image.maximumFileSizeError):function(t){return e().Deferred((function(n){e().extend(new FileReader,{onload:function(t){var e=t.target.result;n.resolve(e)},onerror:function(t){n.reject(t)}}).readAsDataURL(t)})).promise()}(o).then((function(t){return n.insertImage(t,i)})).fail((function(){n.context.triggerEvent("image.upload.error")}))}))}},{key:"insertImagesOrCallback",value:function(t){this.options.callbacks.onImageUpload?this.context.triggerEvent("image.upload",t):this.insertImagesAsDataURL(t)}},{key:"getSelectedText",value:function(){var t=this.getLastRange();return t.isOnAnchor()&&(t=yt.createFromNode(ht.ancestor(t.sc,ht.isAnchor))),t.toString()}},{key:"onFormatBlock",value:function(t,n){if(document.execCommand("FormatBlock",!1,f.isMSIE?"<"+t+">":t),n&&n.length&&(n[0].tagName.toUpperCase()!==t.toUpperCase()&&(n=n.find(t)),n&&n.length)){var o=this.createRange(),i=e()([o.sc,o.ec]).closest(t);i.removeClass();var r=n[0].className||"";r&&i.addClass(r)}}},{key:"formatPara",value:function(){this.formatBlock("P")}},{key:"fontStyling",value:function(t,n){var o=this.getLastRange();if(""!==o){var i=this.style.styleNodes(o);if(this.$editor.find(".note-status-output").html(""),e()(i).css(t,n),o.isCollapsed()){var r=k.head(i);r&&!ht.nodeLength(r)&&(r.innerHTML=ht.ZERO_WIDTH_NBSP_CHAR,yt.createFromNode(r.firstChild).select(),this.setLastRange(),this.$editable.data("bogus",r))}else this.setLastRange(this.createRangeFromList(i).select())}else{var a=e().now();this.$editor.find(".note-status-output").html('<div id="note-status-output-'+a+'" class="alert alert-info">'+this.lang.output.noSelection+"</div>"),setTimeout((function(){e()("#note-status-output-"+a).remove()}),5e3)}}},{key:"unlink",value:function(){var t=this.getLastRange();if(t.isOnAnchor()){var e=ht.ancestor(t.sc,ht.isAnchor);(t=yt.createFromNode(e)).select(),this.setLastRange(),this.beforeCommand(),document.execCommand("unlink"),this.afterCommand()}}},{key:"getLinkInfo",value:function(){var t=this.getLastRange().expand(ht.isAnchor),n=e()(k.head(t.nodes(ht.isAnchor))),o={range:t,text:t.toString(),url:n.length?n.attr("href"):""};return n.length&&(o.isNewWindow="_blank"===n.attr("target")),o}},{key:"addRow",value:function(t){var e=this.getLastRange(this.$editable);e.isCollapsed()&&e.isOnCell()&&(this.beforeCommand(),this.table.addRow(e,t),this.afterCommand())}},{key:"addCol",value:function(t){var e=this.getLastRange(this.$editable);e.isCollapsed()&&e.isOnCell()&&(this.beforeCommand(),this.table.addCol(e,t),this.afterCommand())}},{key:"deleteRow",value:function(){var t=this.getLastRange(this.$editable);t.isCollapsed()&&t.isOnCell()&&(this.beforeCommand(),this.table.deleteRow(t),this.afterCommand())}},{key:"deleteCol",value:function(){var t=this.getLastRange(this.$editable);t.isCollapsed()&&t.isOnCell()&&(this.beforeCommand(),this.table.deleteCol(t),this.afterCommand())}},{key:"deleteTable",value:function(){var t=this.getLastRange(this.$editable);t.isCollapsed()&&t.isOnCell()&&(this.beforeCommand(),this.table.deleteTable(t),this.afterCommand())}},{key:"resizeTo",value:function(t,e,n){var o;if(n){var i=t.y/t.x,r=e.data("ratio");o={width:r>i?t.x:t.y/r,height:r>i?t.x*r:t.y}}else o={width:t.x,height:t.y};e.css(o)}},{key:"hasFocus",value:function(){return this.$editable.is(":focus")}},{key:"focus",value:function(){this.hasFocus()||this.$editable.focus()}},{key:"isEmpty",value:function(){return ht.isEmpty(this.$editable[0])||ht.emptyPara===this.$editable.html()}},{key:"empty",value:function(){this.context.invoke("code",ht.emptyPara)}},{key:"normalizeContent",value:function(){this.$editable[0].normalize()}}],o&&At(n.prototype,o),i&&At(n,i),t}();function Dt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Ht=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=e,this.$editable=e.layoutInfo.editable}var e,n,o;return e=t,(n=[{key:"initialize",value:function(){this.$editable.on("paste",this.pasteByEvent.bind(this))}},{key:"pasteByEvent",value:function(t){var e=this,n=t.originalEvent.clipboardData;if(n&&n.items&&n.items.length){var o=n.items.length>1?n.items[1]:k.head(n.items);"file"===o.kind&&-1!==o.type.indexOf("image/")?(this.context.invoke("editor.insertImagesOrCallback",[o.getAsFile()]),t.preventDefault()):"string"===o.kind&&this.context.invoke("editor.isLimited",n.getData("Text").length)&&t.preventDefault()}else if(window.clipboardData){var i=window.clipboardData.getData("text");this.context.invoke("editor.isLimited",i.length)&&t.preventDefault()}setTimeout((function(){e.context.invoke("editor.afterCommand")}),10)}}])&&Dt(e.prototype,n),o&&Dt(e,o),t}();function zt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Bt=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.$eventListener=e()(document),this.$editor=n.layoutInfo.editor,this.$editable=n.layoutInfo.editable,this.options=n.options,this.lang=this.options.langInfo,this.documentEventHandlers={},this.$dropzone=e()(['<div class="note-dropzone">','<div class="note-dropzone-message"></div>',"</div>"].join("")).prependTo(this.$editor)}var n,o,i;return n=t,o=[{key:"initialize",value:function(){this.options.disableDragAndDrop?(this.documentEventHandlers.onDrop=function(t){t.preventDefault()},this.$eventListener=this.$dropzone,this.$eventListener.on("drop",this.documentEventHandlers.onDrop)):this.attachDragAndDropEvent()}},{key:"attachDragAndDropEvent",value:function(){var t=this,n=e()(),o=this.$dropzone.find(".note-dropzone-message");this.documentEventHandlers.onDragenter=function(e){var i=t.context.invoke("codeview.isActivated"),r=t.$editor.width()>0&&t.$editor.height()>0;i||n.length||!r||(t.$editor.addClass("dragover"),t.$dropzone.width(t.$editor.width()),t.$dropzone.height(t.$editor.height()),o.text(t.lang.image.dragImageHere)),n=n.add(e.target)},this.documentEventHandlers.onDragleave=function(o){(n=n.not(o.target)).length&&"BODY"!==o.target.nodeName||(n=e()(),t.$editor.removeClass("dragover"))},this.documentEventHandlers.onDrop=function(){n=e()(),t.$editor.removeClass("dragover")},this.$eventListener.on("dragenter",this.documentEventHandlers.onDragenter).on("dragleave",this.documentEventHandlers.onDragleave).on("drop",this.documentEventHandlers.onDrop),this.$dropzone.on("dragenter",(function(){t.$dropzone.addClass("hover"),o.text(t.lang.image.dropImage)})).on("dragleave",(function(){t.$dropzone.removeClass("hover"),o.text(t.lang.image.dragImageHere)})),this.$dropzone.on("drop",(function(n){var o=n.originalEvent.dataTransfer;n.preventDefault(),o&&o.files&&o.files.length?(t.$editable.focus(),t.context.invoke("editor.insertImagesOrCallback",o.files)):e().each(o.types,(function(n,i){if(!(i.toLowerCase().indexOf("_moz_")>-1)){var r=o.getData(i);i.toLowerCase().indexOf("text")>-1?t.context.invoke("editor.pasteHTML",r):e()(r).each((function(e,n){t.context.invoke("editor.insertNode",n)}))}}))})).on("dragover",!1)}},{key:"destroy",value:function(){var t=this;Object.keys(this.documentEventHandlers).forEach((function(e){t.$eventListener.off(e.substr(2).toLowerCase(),t.documentEventHandlers[e])})),this.documentEventHandlers={}}}],o&&zt(n.prototype,o),i&&zt(n,i),t}();function Mt(t,e){var n="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!n){if(Array.isArray(t)||(n=function(t,e){if(!t)return;if("string"==typeof t)return Ot(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return Ot(t,e)}(t))||e&&t&&"number"==typeof t.length){n&&(t=n);var o=0,i=function(){};return{s:i,n:function(){return o>=t.length?{done:!0}:{done:!1,value:t[o++]}},e:function(t){throw t},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var r,a=!0,s=!1;return{s:function(){n=n.call(t)},n:function(){var t=n.next();return a=t.done,t},e:function(t){s=!0,r=t},f:function(){try{a||null==n.return||n.return()}finally{if(s)throw r}}}}function Ot(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,o=new Array(e);n<e;n++)o[n]=t[n];return o}function Ut(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var jt=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=e,this.$editor=e.layoutInfo.editor,this.$editable=e.layoutInfo.editable,this.$codable=e.layoutInfo.codable,this.options=e.options,this.CodeMirrorConstructor=window.CodeMirror,this.options.codemirror.CodeMirrorConstructor&&(this.CodeMirrorConstructor=this.options.codemirror.CodeMirrorConstructor)}var e,n,o;return e=t,n=[{key:"sync",value:function(t){var e=this.isActivated(),n=this.CodeMirrorConstructor;e&&(t?n?this.$codable.data("cmEditor").getDoc().setValue(t):this.$codable.val(t):n&&this.$codable.data("cmEditor").save())}},{key:"initialize",value:function(){var t=this;this.$codable.on("keyup",(function(e){e.keyCode===wt.code.ESCAPE&&t.deactivate()}))}},{key:"isActivated",value:function(){return this.$editor.hasClass("codeview")}},{key:"toggle",value:function(){this.isActivated()?this.deactivate():this.activate(),this.context.triggerEvent("codeview.toggled")}},{key:"purify",value:function(t){if(this.options.codeviewFilter&&(t=t.replace(this.options.codeviewFilterRegex,""),this.options.codeviewIframeFilter)){var e=this.options.codeviewIframeWhitelistSrc.concat(this.options.codeviewIframeWhitelistSrcBase);t=t.replace(/(<iframe.*?>.*?(?:<\/iframe>)?)/gi,(function(t){if(/<.+src(?==?('|"|\s)?)[\s\S]+src(?=('|"|\s)?)[^>]*?>/i.test(t))return"";var n,o=Mt(e);try{for(o.s();!(n=o.n()).done;){var i=n.value;if(new RegExp('src="(https?:)?//'+i.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")+'/(.+)"').test(t))return t}}catch(t){o.e(t)}finally{o.f()}return""}))}return t}},{key:"activate",value:function(){var t=this,e=this.CodeMirrorConstructor;if(this.$codable.val(ht.html(this.$editable,this.options.prettifyHtml)),this.$codable.height(this.$editable.height()),this.context.invoke("toolbar.updateCodeview",!0),this.context.invoke("airPopover.updateCodeview",!0),this.$editor.addClass("codeview"),this.$codable.focus(),e){var n=e.fromTextArea(this.$codable[0],this.options.codemirror);if(this.options.codemirror.tern){var o=new e.TernServer(this.options.codemirror.tern);n.ternServer=o,n.on("cursorActivity",(function(t){o.updateArgHints(t)}))}n.on("blur",(function(e){t.context.triggerEvent("blur.codeview",n.getValue(),e)})),n.on("change",(function(){t.context.triggerEvent("change.codeview",n.getValue(),n)})),n.setSize(null,this.$editable.outerHeight()),this.$codable.data("cmEditor",n)}else this.$codable.on("blur",(function(e){t.context.triggerEvent("blur.codeview",t.$codable.val(),e)})),this.$codable.on("input",(function(){t.context.triggerEvent("change.codeview",t.$codable.val(),t.$codable)}))}},{key:"deactivate",value:function(){if(this.CodeMirrorConstructor){var t=this.$codable.data("cmEditor");this.$codable.val(t.getValue()),t.toTextArea()}var e=this.purify(ht.value(this.$codable,this.options.prettifyHtml)||ht.emptyPara),n=this.$editable.html()!==e;this.$editable.html(e),this.$editable.height(this.options.height?this.$codable.height():"auto"),this.$editor.removeClass("codeview"),n&&this.context.triggerEvent("change",this.$editable.html(),this.$editable),this.$editable.focus(),this.context.invoke("toolbar.updateCodeview",!1),this.context.invoke("airPopover.updateCodeview",!1)}},{key:"destroy",value:function(){this.isActivated()&&this.deactivate()}}],n&&Ut(e.prototype,n),o&&Ut(e,o),t}();function Wt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Kt=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.$document=e()(document),this.$statusbar=n.layoutInfo.statusbar,this.$editable=n.layoutInfo.editable,this.$codable=n.layoutInfo.codable,this.options=n.options}var n,o,i;return n=t,(o=[{key:"initialize",value:function(){var t=this;this.options.airMode||this.options.disableResizeEditor?this.destroy():this.$statusbar.on("mousedown",(function(e){e.preventDefault(),e.stopPropagation();var n=t.$editable.offset().top-t.$document.scrollTop(),o=t.$codable.offset().top-t.$document.scrollTop(),i=function(e){var i=e.clientY-(n+24),r=e.clientY-(o+24);i=t.options.minheight>0?Math.max(i,t.options.minheight):i,i=t.options.maxHeight>0?Math.min(i,t.options.maxHeight):i,r=t.options.minheight>0?Math.max(r,t.options.minheight):r,r=t.options.maxHeight>0?Math.min(r,t.options.maxHeight):r,t.$editable.height(i),t.$codable.height(r)};t.$document.on("mousemove",i).one("mouseup",(function(){t.$document.off("mousemove",i)}))}))}},{key:"destroy",value:function(){this.$statusbar.off(),this.$statusbar.addClass("locked")}}])&&Wt(n.prototype,o),i&&Wt(n,i),t}();function Vt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var qt=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.$editor=n.layoutInfo.editor,this.$toolbar=n.layoutInfo.toolbar,this.$editable=n.layoutInfo.editable,this.$codable=n.layoutInfo.codable,this.$window=e()(window),this.$scrollbar=e()("html, body"),this.scrollbarClassName="note-fullscreen-body",this.onResize=function(){o.resizeTo({h:o.$window.height()-o.$toolbar.outerHeight()})}}var n,o,i;return n=t,(o=[{key:"resizeTo",value:function(t){this.$editable.css("height",t.h),this.$codable.css("height",t.h),this.$codable.data("cmeditor")&&this.$codable.data("cmeditor").setsize(null,t.h)}},{key:"toggle",value:function(){this.$editor.toggleClass("fullscreen");var t=this.isFullscreen();this.$scrollbar.toggleClass(this.scrollbarClassName,t),t?(this.$editable.data("orgHeight",this.$editable.css("height")),this.$editable.data("orgMaxHeight",this.$editable.css("maxHeight")),this.$editable.css("maxHeight",""),this.$window.on("resize",this.onResize).trigger("resize")):(this.$window.off("resize",this.onResize),this.resizeTo({h:this.$editable.data("orgHeight")}),this.$editable.css("maxHeight",this.$editable.css("orgMaxHeight"))),this.context.invoke("toolbar.updateFullscreen",t)}},{key:"isFullscreen",value:function(){return this.$editor.hasClass("fullscreen")}},{key:"destroy",value:function(){this.$scrollbar.removeClass(this.scrollbarClassName)}}])&&Vt(n.prototype,o),i&&Vt(n,i),t}();function _t(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Gt=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.$document=e()(document),this.$editingArea=n.layoutInfo.editingArea,this.options=n.options,this.lang=this.options.langInfo,this.events={"summernote.mousedown":function(t,e){o.update(e.target,e)&&e.preventDefault()},"summernote.keyup summernote.scroll summernote.change summernote.dialog.shown":function(){o.update()},"summernote.disable summernote.blur":function(){o.hide()},"summernote.codeview.toggled":function(){o.update()}}}var n,o,i;return n=t,o=[{key:"initialize",value:function(){var t=this;this.$handle=e()(['<div class="note-handle">','<div class="note-control-selection">','<div class="note-control-selection-bg"></div>','<div class="note-control-holder note-control-nw"></div>','<div class="note-control-holder note-control-ne"></div>','<div class="note-control-holder note-control-sw"></div>','<div class="',this.options.disableResizeImage?"note-control-holder":"note-control-sizing",' note-control-se"></div>',this.options.disableResizeImage?"":'<div class="note-control-selection-info"></div>',"</div>","</div>"].join("")).prependTo(this.$editingArea),this.$handle.on("mousedown",(function(e){if(ht.isControlSizing(e.target)){e.preventDefault(),e.stopPropagation();var n=t.$handle.find(".note-control-selection").data("target"),o=n.offset(),i=t.$document.scrollTop(),r=function(e){t.context.invoke("editor.resizeTo",{x:e.clientX-o.left,y:e.clientY-(o.top-i)},n,!e.shiftKey),t.update(n[0],e)};t.$document.on("mousemove",r).one("mouseup",(function(e){e.preventDefault(),t.$document.off("mousemove",r),t.context.invoke("editor.afterCommand")})),n.data("ratio")||n.data("ratio",n.height()/n.width())}})),this.$handle.on("wheel",(function(e){e.preventDefault(),t.update()}))}},{key:"destroy",value:function(){this.$handle.remove()}},{key:"update",value:function(t,n){if(this.context.isDisabled())return!1;var o=ht.isImg(t),i=this.$handle.find(".note-control-selection");if(this.context.invoke("imagePopover.update",t,n),o){var r=e()(t),a=r.position(),s={left:a.left+parseInt(r.css("marginLeft"),10),top:a.top+parseInt(r.css("marginTop"),10)},l={w:r.outerWidth(!1),h:r.outerHeight(!1)};i.css({display:"block",left:s.left,top:s.top,width:l.w,height:l.h}).data("target",r);var c=new Image;c.src=r.attr("src");var u=l.w+"x"+l.h+" ("+this.lang.image.original+": "+c.width+"x"+c.height+")";i.find(".note-control-selection-info").text(u),this.context.invoke("editor.saveTarget",t)}else this.hide();return o}},{key:"hide",value:function(){this.context.invoke("editor.clearTarget"),this.$handle.children().hide()}}],o&&_t(n.prototype,o),i&&_t(n,i),t}();function Yt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Zt=/^([A-Za-z][A-Za-z0-9+-.]*\:[\/]{2}|tel:|mailto:[A-Z0-9._%+-]+@|xmpp:[A-Z0-9._%+-]+@)?(www\.)?(.+)$/i,Qt=function(){function t(e){var n=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=e,this.options=e.options,this.events={"summernote.keyup":function(t,e){e.isDefaultPrevented()||n.handleKeyup(e)},"summernote.keydown":function(t,e){n.handleKeydown(e)}}}var n,o,i;return n=t,(o=[{key:"initialize",value:function(){this.lastWordRange=null}},{key:"destroy",value:function(){this.lastWordRange=null}},{key:"replace",value:function(){if(this.lastWordRange){var t=this.lastWordRange.toString(),n=t.match(Zt);if(n&&(n[1]||n[2])){var o=n[1]?t:"http://"+t,i=this.options.showDomainOnlyForAutolink?t.replace(/^(?:https?:\/\/)?(?:tel?:?)?(?:mailto?:?)?(?:xmpp?:?)?(?:www\.)?/i,"").split("/")[0]:t,r=e()("<a></a>").html(i).attr("href",o)[0];this.context.options.linkTargetBlank&&e()(r).attr("target","_blank"),this.lastWordRange.insertNode(r),this.lastWordRange=null,this.context.invoke("editor.focus")}}}},{key:"handleKeydown",value:function(t){if(k.contains([wt.code.ENTER,wt.code.SPACE],t.keyCode)){var e=this.context.invoke("editor.createRange").getWordRange();this.lastWordRange=e}}},{key:"handleKeyup",value:function(t){k.contains([wt.code.ENTER,wt.code.SPACE],t.keyCode)&&this.replace()}}])&&Yt(n.prototype,o),i&&Yt(n,i),t}();function Xt(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Jt=function(){function t(e){var n=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.$note=e.layoutInfo.note,this.events={"summernote.change":function(){n.$note.val(e.invoke("code"))}}}var e,n,o;return e=t,(n=[{key:"shouldInitialize",value:function(){return ht.isTextarea(this.$note[0])}}])&&Xt(e.prototype,n),o&&Xt(e,o),t}();function te(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var ee=function(){function t(e){var n=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=e,this.options=e.options.replace||{},this.keys=[wt.code.ENTER,wt.code.SPACE,wt.code.PERIOD,wt.code.COMMA,wt.code.SEMICOLON,wt.code.SLASH],this.previousKeydownCode=null,this.events={"summernote.keyup":function(t,e){e.isDefaultPrevented()||n.handleKeyup(e)},"summernote.keydown":function(t,e){n.handleKeydown(e)}}}var e,n,o;return e=t,(n=[{key:"shouldInitialize",value:function(){return!!this.options.match}},{key:"initialize",value:function(){this.lastWord=null}},{key:"destroy",value:function(){this.lastWord=null}},{key:"replace",value:function(){if(this.lastWord){var t=this,e=this.lastWord.toString();this.options.match(e,(function(e){if(e){var n="";if("string"==typeof e?n=ht.createText(e):e instanceof jQuery?n=e[0]:e instanceof Node&&(n=e),!n)return;t.lastWord.insertNode(n),t.lastWord=null,t.context.invoke("editor.focus")}}))}}},{key:"handleKeydown",value:function(t){if(this.previousKeydownCode&&k.contains(this.keys,this.previousKeydownCode))this.previousKeydownCode=t.keyCode;else{if(k.contains(this.keys,t.keyCode)){var e=this.context.invoke("editor.createRange").getWordRange();this.lastWord=e}this.previousKeydownCode=t.keyCode}}},{key:"handleKeyup",value:function(t){k.contains(this.keys,t.keyCode)&&this.replace()}}])&&te(e.prototype,n),o&&te(e,o),t}();function ne(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var oe=function(){function t(e){var n=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=e,this.$editingArea=e.layoutInfo.editingArea,this.options=e.options,!0===this.options.inheritPlaceholder&&(this.options.placeholder=this.context.$note.attr("placeholder")||this.options.placeholder),this.events={"summernote.init summernote.change":function(){n.update()},"summernote.codeview.toggled":function(){n.update()}}}var n,o,i;return n=t,(o=[{key:"shouldInitialize",value:function(){return!!this.options.placeholder}},{key:"initialize",value:function(){var t=this;this.$placeholder=e()('<div class="note-placeholder"></div>'),this.$placeholder.on("click",(function(){t.context.invoke("focus")})).html(this.options.placeholder).prependTo(this.$editingArea),this.update()}},{key:"destroy",value:function(){this.$placeholder.remove()}},{key:"update",value:function(){var t=!this.context.invoke("codeview.isActivated")&&this.context.invoke("editor.isEmpty");this.$placeholder.toggle(t)}}])&&ne(n.prototype,o),i&&ne(n,i),t}();function ie(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var re=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.ui=e().summernote.ui,this.context=n,this.$toolbar=n.layoutInfo.toolbar,this.options=n.options,this.lang=this.options.langInfo,this.invertedKeyMap=m.invertObject(this.options.keyMap[f.isMac?"mac":"pc"])}var n,o,i;return n=t,o=[{key:"representShortcut",value:function(t){var e=this.invertedKeyMap[t];return this.options.shortcuts&&e?(f.isMac&&(e=e.replace("CMD","⌘").replace("SHIFT","⇧"))," ("+(e=e.replace("BACKSLASH","\\").replace("SLASH","/").replace("LEFTBRACKET","[").replace("RIGHTBRACKET","]"))+")"):""}},{key:"button",value:function(t){return!this.options.tooltip&&t.tooltip&&delete t.tooltip,t.container=this.options.container,this.ui.button(t)}},{key:"initialize",value:function(){this.addToolbarButtons(),this.addImagePopoverButtons(),this.addLinkPopoverButtons(),this.addTablePopoverButtons(),this.fontInstalledMap={}}},{key:"destroy",value:function(){delete this.fontInstalledMap}},{key:"isFontInstalled",value:function(t){return Object.prototype.hasOwnProperty.call(this.fontInstalledMap,t)||(this.fontInstalledMap[t]=f.isFontInstalled(t)||k.contains(this.options.fontNamesIgnoreCheck,t)),this.fontInstalledMap[t]}},{key:"isFontDeservedToAdd",value:function(t){return""!==(t=t.toLowerCase())&&this.isFontInstalled(t)&&-1===f.genericFontFamilies.indexOf(t)}},{key:"colorPalette",value:function(t,n,o,i){var r=this;return this.ui.buttonGroup({className:"note-color "+t,children:[this.button({className:"note-current-color-button",contents:this.ui.icon(this.options.icons.font+" note-recent-color"),tooltip:n,click:function(t){var n=e()(t.currentTarget);o&&i?r.context.invoke("editor.color",{backColor:n.attr("data-backColor"),foreColor:n.attr("data-foreColor")}):o?r.context.invoke("editor.color",{backColor:n.attr("data-backColor")}):i&&r.context.invoke("editor.color",{foreColor:n.attr("data-foreColor")})},callback:function(t){var e=t.find(".note-recent-color");o&&(e.css("background-color",r.options.colorButton.backColor),t.attr("data-backColor",r.options.colorButton.backColor)),i?(e.css("color",r.options.colorButton.foreColor),t.attr("data-foreColor",r.options.colorButton.foreColor)):e.css("color","transparent")}}),this.button({className:"dropdown-toggle",contents:this.ui.dropdownButtonContents("",this.options),tooltip:this.lang.color.more,data:{toggle:"dropdown"}}),this.ui.dropdown({items:(o?['<div class="note-palette">','<div class="note-palette-title">'+this.lang.color.background+"</div>","<div>",'<button type="button" class="note-color-reset btn btn-light btn-default" data-event="backColor" data-value="transparent">',this.lang.color.transparent,"</button>","</div>",'<div class="note-holder" data-event="backColor">\x3c!-- back colors --\x3e</div>',"<div>",'<button type="button" class="note-color-select btn btn-light btn-default" data-event="openPalette" data-value="backColorPicker-'+this.options.id+'">',this.lang.color.cpSelect,"</button>",'<input type="color" id="backColorPicker-'+this.options.id+'" class="note-btn note-color-select-btn" value="'+this.options.colorButton.backColor+'" data-event="backColorPalette-'+this.options.id+'">',"</div>",'<div class="note-holder-custom" id="backColorPalette-'+this.options.id+'" data-event="backColor"></div>',"</div>"].join(""):"")+(i?['<div class="note-palette">','<div class="note-palette-title">'+this.lang.color.foreground+"</div>","<div>",'<button type="button" class="note-color-reset btn btn-light btn-default" data-event="removeFormat" data-value="foreColor">',this.lang.color.resetToDefault,"</button>","</div>",'<div class="note-holder" data-event="foreColor">\x3c!-- fore colors --\x3e</div>',"<div>",'<button type="button" class="note-color-select btn btn-light btn-default" data-event="openPalette" data-value="foreColorPicker-'+this.options.id+'">',this.lang.color.cpSelect,"</button>",'<input type="color" id="foreColorPicker-'+this.options.id+'" class="note-btn note-color-select-btn" value="'+this.options.colorButton.foreColor+'" data-event="foreColorPalette-'+this.options.id+'">',"</div>",'<div class="note-holder-custom" id="foreColorPalette-'+this.options.id+'" data-event="foreColor"></div>',"</div>"].join(""):""),callback:function(t){t.find(".note-holder").each((function(t,n){var o=e()(n);o.append(r.ui.palette({colors:r.options.colors,colorsName:r.options.colorsName,eventName:o.data("event"),container:r.options.container,tooltip:r.options.tooltip}).render())}));var n=[["#FFFFFF","#FFFFFF","#FFFFFF","#FFFFFF","#FFFFFF","#FFFFFF","#FFFFFF","#FFFFFF"]];t.find(".note-holder-custom").each((function(t,o){var i=e()(o);i.append(r.ui.palette({colors:n,colorsName:n,eventName:i.data("event"),container:r.options.container,tooltip:r.options.tooltip}).render())})),t.find("input[type=color]").each((function(n,o){e()(o).change((function(){var n=t.find("#"+e()(this).data("event")).find(".note-color-btn").first(),o=this.value.toUpperCase();n.css("background-color",o).attr("aria-label",o).attr("data-value",o).attr("data-original-title",o),n.click()}))}))},click:function(n){n.stopPropagation();var o=e()("."+t).find(".note-dropdown-menu"),i=e()(n.target),a=i.data("event"),s=i.attr("data-value");if("openPalette"===a){var l=o.find("#"+s),c=e()(o.find("#"+l.data("event")).find(".note-color-row")[0]),u=c.find(".note-color-btn").last().detach(),d=l.val();u.css("background-color",d).attr("aria-label",d).attr("data-value",d).attr("data-original-title",d),c.prepend(u),l.click()}else{if(k.contains(["backColor","foreColor"],a)){var h="backColor"===a?"background-color":"color",f=i.closest(".note-color").find(".note-recent-color"),p=i.closest(".note-color").find(".note-current-color-button");f.css(h,s),p.attr("data-"+a,s)}r.context.invoke("editor."+a,s)}}})]}).render()}},{key:"addToolbarButtons",value:function(){var t=this;this.context.memo("button.style",(function(){return t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents(t.ui.icon(t.options.icons.magic),t.options),tooltip:t.lang.style.style,data:{toggle:"dropdown"}}),t.ui.dropdown({className:"dropdown-style",items:t.options.styleTags,title:t.lang.style.style,template:function(e){"string"==typeof e&&(e={tag:e,title:Object.prototype.hasOwnProperty.call(t.lang.style,e)?t.lang.style[e]:e});var n=e.tag,o=e.title;return"<"+n+(e.style?' style="'+e.style+'" ':"")+(e.className?' class="'+e.className+'"':"")+">"+o+"</"+n+">"},click:t.context.createInvokeHandler("editor.formatBlock")})]).render()}));for(var n=function(e,n){var o=t.options.styleTags[e];t.context.memo("button.style."+o,(function(){return t.button({className:"note-btn-style-"+o,contents:'<div data-value="'+o+'">'+o.toUpperCase()+"</div>",tooltip:t.lang.style[o],click:t.context.createInvokeHandler("editor.formatBlock")}).render()}))},o=0,i=this.options.styleTags.length;o<i;o++)n(o);this.context.memo("button.bold",(function(){return t.button({className:"note-btn-bold",contents:t.ui.icon(t.options.icons.bold),tooltip:t.lang.font.bold+t.representShortcut("bold"),click:t.context.createInvokeHandlerAndUpdateState("editor.bold")}).render()})),this.context.memo("button.italic",(function(){return t.button({className:"note-btn-italic",contents:t.ui.icon(t.options.icons.italic),tooltip:t.lang.font.italic+t.representShortcut("italic"),click:t.context.createInvokeHandlerAndUpdateState("editor.italic")}).render()})),this.context.memo("button.underline",(function(){return t.button({className:"note-btn-underline",contents:t.ui.icon(t.options.icons.underline),tooltip:t.lang.font.underline+t.representShortcut("underline"),click:t.context.createInvokeHandlerAndUpdateState("editor.underline")}).render()})),this.context.memo("button.clear",(function(){return t.button({contents:t.ui.icon(t.options.icons.eraser),tooltip:t.lang.font.clear+t.representShortcut("removeFormat"),click:t.context.createInvokeHandler("editor.removeFormat")}).render()})),this.context.memo("button.strikethrough",(function(){return t.button({className:"note-btn-strikethrough",contents:t.ui.icon(t.options.icons.strikethrough),tooltip:t.lang.font.strikethrough+t.representShortcut("strikethrough"),click:t.context.createInvokeHandlerAndUpdateState("editor.strikethrough")}).render()})),this.context.memo("button.superscript",(function(){return t.button({className:"note-btn-superscript",contents:t.ui.icon(t.options.icons.superscript),tooltip:t.lang.font.superscript,click:t.context.createInvokeHandlerAndUpdateState("editor.superscript")}).render()})),this.context.memo("button.subscript",(function(){return t.button({className:"note-btn-subscript",contents:t.ui.icon(t.options.icons.subscript),tooltip:t.lang.font.subscript,click:t.context.createInvokeHandlerAndUpdateState("editor.subscript")}).render()})),this.context.memo("button.fontname",(function(){var n=t.context.invoke("editor.currentStyle");return t.options.addDefaultFonts&&e().each(n["font-family"].split(","),(function(e,n){n=n.trim().replace(/['"]+/g,""),t.isFontDeservedToAdd(n)&&-1===t.options.fontNames.indexOf(n)&&t.options.fontNames.push(n)})),t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents('<span class="note-current-fontname"></span>',t.options),tooltip:t.lang.font.name,data:{toggle:"dropdown"}}),t.ui.dropdownCheck({className:"dropdown-fontname",checkClassName:t.options.icons.menuCheck,items:t.options.fontNames.filter(t.isFontInstalled.bind(t)),title:t.lang.font.name,template:function(t){return'<span style="font-family: '+f.validFontName(t)+'">'+t+"</span>"},click:t.context.createInvokeHandlerAndUpdateState("editor.fontName")})]).render()})),this.context.memo("button.fontsize",(function(){return t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents('<span class="note-current-fontsize"></span>',t.options),tooltip:t.lang.font.size,data:{toggle:"dropdown"}}),t.ui.dropdownCheck({className:"dropdown-fontsize",checkClassName:t.options.icons.menuCheck,items:t.options.fontSizes,title:t.lang.font.size,click:t.context.createInvokeHandlerAndUpdateState("editor.fontSize")})]).render()})),this.context.memo("button.fontsizeunit",(function(){return t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents('<span class="note-current-fontsizeunit"></span>',t.options),tooltip:t.lang.font.sizeunit,data:{toggle:"dropdown"}}),t.ui.dropdownCheck({className:"dropdown-fontsizeunit",checkClassName:t.options.icons.menuCheck,items:t.options.fontSizeUnits,title:t.lang.font.sizeunit,click:t.context.createInvokeHandlerAndUpdateState("editor.fontSizeUnit")})]).render()})),this.context.memo("button.color",(function(){return t.colorPalette("note-color-all",t.lang.color.recent,!0,!0)})),this.context.memo("button.forecolor",(function(){return t.colorPalette("note-color-fore",t.lang.color.foreground,!1,!0)})),this.context.memo("button.backcolor",(function(){return t.colorPalette("note-color-back",t.lang.color.background,!0,!1)})),this.context.memo("button.ul",(function(){return t.button({contents:t.ui.icon(t.options.icons.unorderedlist),tooltip:t.lang.lists.unordered+t.representShortcut("insertUnorderedList"),click:t.context.createInvokeHandler("editor.insertUnorderedList")}).render()})),this.context.memo("button.ol",(function(){return t.button({contents:t.ui.icon(t.options.icons.orderedlist),tooltip:t.lang.lists.ordered+t.representShortcut("insertOrderedList"),click:t.context.createInvokeHandler("editor.insertOrderedList")}).render()}));var r=this.button({contents:this.ui.icon(this.options.icons.alignLeft),tooltip:this.lang.paragraph.left+this.representShortcut("justifyLeft"),click:this.context.createInvokeHandler("editor.justifyLeft")}),a=this.button({contents:this.ui.icon(this.options.icons.alignCenter),tooltip:this.lang.paragraph.center+this.representShortcut("justifyCenter"),click:this.context.createInvokeHandler("editor.justifyCenter")}),s=this.button({contents:this.ui.icon(this.options.icons.alignRight),tooltip:this.lang.paragraph.right+this.representShortcut("justifyRight"),click:this.context.createInvokeHandler("editor.justifyRight")}),l=this.button({contents:this.ui.icon(this.options.icons.alignJustify),tooltip:this.lang.paragraph.justify+this.representShortcut("justifyFull"),click:this.context.createInvokeHandler("editor.justifyFull")}),c=this.button({contents:this.ui.icon(this.options.icons.outdent),tooltip:this.lang.paragraph.outdent+this.representShortcut("outdent"),click:this.context.createInvokeHandler("editor.outdent")}),u=this.button({contents:this.ui.icon(this.options.icons.indent),tooltip:this.lang.paragraph.indent+this.representShortcut("indent"),click:this.context.createInvokeHandler("editor.indent")});this.context.memo("button.justifyLeft",m.invoke(r,"render")),this.context.memo("button.justifyCenter",m.invoke(a,"render")),this.context.memo("button.justifyRight",m.invoke(s,"render")),this.context.memo("button.justifyFull",m.invoke(l,"render")),this.context.memo("button.outdent",m.invoke(c,"render")),this.context.memo("button.indent",m.invoke(u,"render")),this.context.memo("button.paragraph",(function(){return t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents(t.ui.icon(t.options.icons.alignLeft),t.options),tooltip:t.lang.paragraph.paragraph,data:{toggle:"dropdown"}}),t.ui.dropdown([t.ui.buttonGroup({className:"note-align",children:[r,a,s,l]}),t.ui.buttonGroup({className:"note-list",children:[c,u]})])]).render()})),this.context.memo("button.height",(function(){return t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents(t.ui.icon(t.options.icons.textHeight),t.options),tooltip:t.lang.font.height,data:{toggle:"dropdown"}}),t.ui.dropdownCheck({items:t.options.lineHeights,checkClassName:t.options.icons.menuCheck,className:"dropdown-line-height",title:t.lang.font.height,click:t.context.createInvokeHandler("editor.lineHeight")})]).render()})),this.context.memo("button.table",(function(){return t.ui.buttonGroup([t.button({className:"dropdown-toggle",contents:t.ui.dropdownButtonContents(t.ui.icon(t.options.icons.table),t.options),tooltip:t.lang.table.table,data:{toggle:"dropdown"}}),t.ui.dropdown({title:t.lang.table.table,className:"note-table",items:['<div class="note-dimension-picker">','<div class="note-dimension-picker-mousecatcher" data-event="insertTable" data-value="1x1"></div>','<div class="note-dimension-picker-highlighted"></div>','<div class="note-dimension-picker-unhighlighted"></div>',"</div>",'<div class="note-dimension-display">1 x 1</div>'].join("")})],{callback:function(e){e.find(".note-dimension-picker-mousecatcher").css({width:t.options.insertTableMaxSize.col+"em",height:t.options.insertTableMaxSize.row+"em"}).mouseup(t.context.createInvokeHandler("editor.insertTable")).on("mousemove",t.tableMoveHandler.bind(t))}}).render()})),this.context.memo("button.link",(function(){return t.button({contents:t.ui.icon(t.options.icons.link),tooltip:t.lang.link.link+t.representShortcut("linkDialog.show"),click:t.context.createInvokeHandler("linkDialog.show")}).render()})),this.context.memo("button.picture",(function(){return t.button({contents:t.ui.icon(t.options.icons.picture),tooltip:t.lang.image.image,click:t.context.createInvokeHandler("imageDialog.show")}).render()})),this.context.memo("button.video",(function(){return t.button({contents:t.ui.icon(t.options.icons.video),tooltip:t.lang.video.video,click:t.context.createInvokeHandler("videoDialog.show")}).render()})),this.context.memo("button.hr",(function(){return t.button({contents:t.ui.icon(t.options.icons.minus),tooltip:t.lang.hr.insert+t.representShortcut("insertHorizontalRule"),click:t.context.createInvokeHandler("editor.insertHorizontalRule")}).render()})),this.context.memo("button.fullscreen",(function(){return t.button({className:"btn-fullscreen note-codeview-keep",contents:t.ui.icon(t.options.icons.arrowsAlt),tooltip:t.lang.options.fullscreen,click:t.context.createInvokeHandler("fullscreen.toggle")}).render()})),this.context.memo("button.codeview",(function(){return t.button({className:"btn-codeview note-codeview-keep",contents:t.ui.icon(t.options.icons.code),tooltip:t.lang.options.codeview,click:t.context.createInvokeHandler("codeview.toggle")}).render()})),this.context.memo("button.redo",(function(){return t.button({contents:t.ui.icon(t.options.icons.redo),tooltip:t.lang.history.redo+t.representShortcut("redo"),click:t.context.createInvokeHandler("editor.redo")}).render()})),this.context.memo("button.undo",(function(){return t.button({contents:t.ui.icon(t.options.icons.undo),tooltip:t.lang.history.undo+t.representShortcut("undo"),click:t.context.createInvokeHandler("editor.undo")}).render()})),this.context.memo("button.help",(function(){return t.button({contents:t.ui.icon(t.options.icons.question),tooltip:t.lang.options.help,click:t.context.createInvokeHandler("helpDialog.show")}).render()}))}},{key:"addImagePopoverButtons",value:function(){var t=this;this.context.memo("button.resizeFull",(function(){return t.button({contents:'<span class="note-fontsize-10">100%</span>',tooltip:t.lang.image.resizeFull,click:t.context.createInvokeHandler("editor.resize","1")}).render()})),this.context.memo("button.resizeHalf",(function(){return t.button({contents:'<span class="note-fontsize-10">50%</span>',tooltip:t.lang.image.resizeHalf,click:t.context.createInvokeHandler("editor.resize","0.5")}).render()})),this.context.memo("button.resizeQuarter",(function(){return t.button({contents:'<span class="note-fontsize-10">25%</span>',tooltip:t.lang.image.resizeQuarter,click:t.context.createInvokeHandler("editor.resize","0.25")}).render()})),this.context.memo("button.resizeNone",(function(){return t.button({contents:t.ui.icon(t.options.icons.rollback),tooltip:t.lang.image.resizeNone,click:t.context.createInvokeHandler("editor.resize","0")}).render()})),this.context.memo("button.floatLeft",(function(){return t.button({contents:t.ui.icon(t.options.icons.floatLeft),tooltip:t.lang.image.floatLeft,click:t.context.createInvokeHandler("editor.floatMe","left")}).render()})),this.context.memo("button.floatRight",(function(){return t.button({contents:t.ui.icon(t.options.icons.floatRight),tooltip:t.lang.image.floatRight,click:t.context.createInvokeHandler("editor.floatMe","right")}).render()})),this.context.memo("button.floatNone",(function(){return t.button({contents:t.ui.icon(t.options.icons.rollback),tooltip:t.lang.image.floatNone,click:t.context.createInvokeHandler("editor.floatMe","none")}).render()})),this.context.memo("button.removeMedia",(function(){return t.button({contents:t.ui.icon(t.options.icons.trash),tooltip:t.lang.image.remove,click:t.context.createInvokeHandler("editor.removeMedia")}).render()}))}},{key:"addLinkPopoverButtons",value:function(){var t=this;this.context.memo("button.linkDialogShow",(function(){return t.button({contents:t.ui.icon(t.options.icons.link),tooltip:t.lang.link.edit,click:t.context.createInvokeHandler("linkDialog.show")}).render()})),this.context.memo("button.unlink",(function(){return t.button({contents:t.ui.icon(t.options.icons.unlink),tooltip:t.lang.link.unlink,click:t.context.createInvokeHandler("editor.unlink")}).render()}))}},{key:"addTablePopoverButtons",value:function(){var t=this;this.context.memo("button.addRowUp",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.rowAbove),tooltip:t.lang.table.addRowAbove,click:t.context.createInvokeHandler("editor.addRow","top")}).render()})),this.context.memo("button.addRowDown",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.rowBelow),tooltip:t.lang.table.addRowBelow,click:t.context.createInvokeHandler("editor.addRow","bottom")}).render()})),this.context.memo("button.addColLeft",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.colBefore),tooltip:t.lang.table.addColLeft,click:t.context.createInvokeHandler("editor.addCol","left")}).render()})),this.context.memo("button.addColRight",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.colAfter),tooltip:t.lang.table.addColRight,click:t.context.createInvokeHandler("editor.addCol","right")}).render()})),this.context.memo("button.deleteRow",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.rowRemove),tooltip:t.lang.table.delRow,click:t.context.createInvokeHandler("editor.deleteRow")}).render()})),this.context.memo("button.deleteCol",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.colRemove),tooltip:t.lang.table.delCol,click:t.context.createInvokeHandler("editor.deleteCol")}).render()})),this.context.memo("button.deleteTable",(function(){return t.button({className:"btn-md",contents:t.ui.icon(t.options.icons.trash),tooltip:t.lang.table.delTable,click:t.context.createInvokeHandler("editor.deleteTable")}).render()}))}},{key:"build",value:function(t,e){for(var n=0,o=e.length;n<o;n++){for(var i=e[n],r=Array.isArray(i)?i[0]:i,a=Array.isArray(i)?1===i.length?[i[0]]:i[1]:[i],s=this.ui.buttonGroup({className:"note-"+r}).render(),l=0,c=a.length;l<c;l++){var u=this.context.memo("button."+a[l]);u&&s.append("function"==typeof u?u(this.context):u)}s.appendTo(t)}}},{key:"updateCurrentStyle",value:function(t){var n=t||this.$toolbar,o=this.context.invoke("editor.currentStyle");if(this.updateBtnStates(n,{".note-btn-bold":function(){return"bold"===o["font-bold"]},".note-btn-italic":function(){return"italic"===o["font-italic"]},".note-btn-underline":function(){return"underline"===o["font-underline"]},".note-btn-subscript":function(){return"subscript"===o["font-subscript"]},".note-btn-superscript":function(){return"superscript"===o["font-superscript"]},".note-btn-strikethrough":function(){return"strikethrough"===o["font-strikethrough"]}}),o["font-family"]){var i=o["font-family"].split(",").map((function(t){return t.replace(/[\'\"]/g,"").replace(/\s+$/,"").replace(/^\s+/,"")})),r=k.find(i,this.isFontInstalled.bind(this));n.find(".dropdown-fontname a").each((function(t,n){var o=e()(n),i=o.data("value")+""==r+"";o.toggleClass("checked",i)})),n.find(".note-current-fontname").text(r).css("font-family",r)}if(o["font-size"]){var a=o["font-size"];n.find(".dropdown-fontsize a").each((function(t,n){var o=e()(n),i=o.data("value")+""==a+"";o.toggleClass("checked",i)})),n.find(".note-current-fontsize").text(a);var s=o["font-size-unit"];n.find(".dropdown-fontsizeunit a").each((function(t,n){var o=e()(n),i=o.data("value")+""==s+"";o.toggleClass("checked",i)})),n.find(".note-current-fontsizeunit").text(s)}if(o["line-height"]){var l=o["line-height"];n.find(".dropdown-line-height a").each((function(t,n){var o=e()(n),i=e()(n).data("value")+""==l+"";o.toggleClass("checked",i)})),n.find(".note-current-line-height").text(l)}}},{key:"updateBtnStates",value:function(t,n){var o=this;e().each(n,(function(e,n){o.ui.toggleBtnActive(t.find(e),n())}))}},{key:"tableMoveHandler",value:function(t){var n,o=e()(t.target.parentNode),i=o.next(),r=o.find(".note-dimension-picker-mousecatcher"),a=o.find(".note-dimension-picker-highlighted"),s=o.find(".note-dimension-picker-unhighlighted");if(void 0===t.offsetX){var l=e()(t.target).offset();n={x:t.pageX-l.left,y:t.pageY-l.top}}else n={x:t.offsetX,y:t.offsetY};var c=Math.ceil(n.x/18)||1,u=Math.ceil(n.y/18)||1;a.css({width:c+"em",height:u+"em"}),r.data("value",c+"x"+u),c>3&&c<this.options.insertTableMaxSize.col&&s.css({width:c+1+"em"}),u>3&&u<this.options.insertTableMaxSize.row&&s.css({height:u+1+"em"}),i.html(c+" x "+u)}}],o&&ie(n.prototype,o),i&&ie(n,i),t}();function ae(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var se=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.$window=e()(window),this.$document=e()(document),this.ui=e().summernote.ui,this.$note=n.layoutInfo.note,this.$editor=n.layoutInfo.editor,this.$toolbar=n.layoutInfo.toolbar,this.$editable=n.layoutInfo.editable,this.$statusbar=n.layoutInfo.statusbar,this.options=n.options,this.isFollowing=!1,this.followScroll=this.followScroll.bind(this)}var n,o,i;return n=t,(o=[{key:"shouldInitialize",value:function(){return!this.options.airMode}},{key:"initialize",value:function(){var t=this;this.options.toolbar=this.options.toolbar||[],this.options.toolbar.length?this.context.invoke("buttons.build",this.$toolbar,this.options.toolbar):this.$toolbar.hide(),this.options.toolbarContainer&&this.$toolbar.appendTo(this.options.toolbarContainer),this.changeContainer(!1),this.$note.on("summernote.keyup summernote.mouseup summernote.change",(function(){t.context.invoke("buttons.updateCurrentStyle")})),this.context.invoke("buttons.updateCurrentStyle"),this.options.followingToolbar&&this.$window.on("scroll resize",this.followScroll)}},{key:"destroy",value:function(){this.$toolbar.children().remove(),this.options.followingToolbar&&this.$window.off("scroll resize",this.followScroll)}},{key:"followScroll",value:function(){if(this.$editor.hasClass("fullscreen"))return!1;var t=this.$editor.outerHeight(),n=this.$editor.width(),o=this.$toolbar.height(),i=this.$statusbar.height(),r=0;this.options.otherStaticBar&&(r=e()(this.options.otherStaticBar).outerHeight());var a=this.$document.scrollTop(),s=this.$editor.offset().top,l=s-r,c=s+t-r-o-i;!this.isFollowing&&a>l&&a<c-o?(this.isFollowing=!0,this.$editable.css({marginTop:this.$toolbar.outerHeight()}),this.$toolbar.css({position:"fixed",top:r,width:n,zIndex:1e3})):this.isFollowing&&(a<l||a>c)&&(this.isFollowing=!1,this.$toolbar.css({position:"relative",top:0,width:"100%",zIndex:"auto"}),this.$editable.css({marginTop:""}))}},{key:"changeContainer",value:function(t){t?this.$toolbar.prependTo(this.$editor):this.options.toolbarContainer&&this.$toolbar.appendTo(this.options.toolbarContainer),this.options.followingToolbar&&this.followScroll()}},{key:"updateFullscreen",value:function(t){this.ui.toggleBtnActive(this.$toolbar.find(".btn-fullscreen"),t),this.changeContainer(t)}},{key:"updateCodeview",value:function(t){this.ui.toggleBtnActive(this.$toolbar.find(".btn-codeview"),t),t?this.deactivate():this.activate()}},{key:"activate",value:function(t){var e=this.$toolbar.find("button");t||(e=e.not(".note-codeview-keep")),this.ui.toggleBtn(e,!0)}},{key:"deactivate",value:function(t){var e=this.$toolbar.find("button");t||(e=e.not(".note-codeview-keep")),this.ui.toggleBtn(e,!1)}}])&&ae(n.prototype,o),i&&ae(n,i),t}();function le(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var ce=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.$body=e()(document.body),this.$editor=n.layoutInfo.editor,this.options=n.options,this.lang=this.options.langInfo,n.memo("help.linkDialog.show",this.options.langInfo.help["linkDialog.show"])}var n,o,i;return n=t,(o=[{key:"initialize",value:function(){var t=this.options.dialogsInBody?this.$body:this.options.container,n=['<div class="form-group note-form-group">','<label for="note-dialog-link-txt-'.concat(this.options.id,'" class="note-form-label">').concat(this.lang.link.textToDisplay,"</label>"),'<input id="note-dialog-link-txt-'.concat(this.options.id,'" class="note-link-text form-control note-form-control note-input" type="text"/>'),"</div>",'<div class="form-group note-form-group">','<label for="note-dialog-link-url-'.concat(this.options.id,'" class="note-form-label">').concat(this.lang.link.url,"</label>"),'<input id="note-dialog-link-url-'.concat(this.options.id,'" class="note-link-url form-control note-form-control note-input" type="text" value="http://"/>'),"</div>",this.options.disableLinkTarget?"":e()("<div></div>").append(this.ui.checkbox({className:"sn-checkbox-open-in-new-window",text:this.lang.link.openInNewWindow,checked:!0}).render()).html(),e()("<div></div>").append(this.ui.checkbox({className:"sn-checkbox-use-protocol",text:this.lang.link.useProtocol,checked:!0}).render()).html()].join(""),o='<input type="button" href="#" class="'.concat("btn btn-primary note-btn note-btn-primary note-link-btn",'" value="').concat(this.lang.link.insert,'" disabled>');this.$dialog=this.ui.dialog({className:"link-dialog",title:this.lang.link.insert,fade:this.options.dialogsFade,body:n,footer:o}).render().appendTo(t)}},{key:"destroy",value:function(){this.ui.hideDialog(this.$dialog),this.$dialog.remove()}},{key:"bindEnterKey",value:function(t,e){t.on("keypress",(function(t){t.keyCode===wt.code.ENTER&&(t.preventDefault(),e.trigger("click"))}))}},{key:"toggleLinkBtn",value:function(t,e,n){this.ui.toggleBtn(t,e.val()&&n.val())}},{key:"showLinkDialog",value:function(t){var n=this;return e().Deferred((function(e){var o=n.$dialog.find(".note-link-text"),i=n.$dialog.find(".note-link-url"),r=n.$dialog.find(".note-link-btn"),a=n.$dialog.find(".sn-checkbox-open-in-new-window input[type=checkbox]"),s=n.$dialog.find(".sn-checkbox-use-protocol input[type=checkbox]");n.ui.onDialogShown(n.$dialog,(function(){n.context.triggerEvent("dialog.shown"),!t.url&&m.isValidUrl(t.text)&&(t.url=t.text),o.on("input paste propertychange",(function(){t.text=o.val(),n.toggleLinkBtn(r,o,i)})).val(t.text),i.on("input paste propertychange",(function(){t.text||o.val(i.val()),n.toggleLinkBtn(r,o,i)})).val(t.url),f.isSupportTouch||i.trigger("focus"),n.toggleLinkBtn(r,o,i),n.bindEnterKey(i,r),n.bindEnterKey(o,r);var l=void 0!==t.isNewWindow?t.isNewWindow:n.context.options.linkTargetBlank;a.prop("checked",l);var c=!t.url&&n.context.options.useProtocol;s.prop("checked",c),r.one("click",(function(r){r.preventDefault(),e.resolve({range:t.range,url:i.val(),text:o.val(),isNewWindow:a.is(":checked"),checkProtocol:s.is(":checked")}),n.ui.hideDialog(n.$dialog)}))})),n.ui.onDialogHidden(n.$dialog,(function(){o.off(),i.off(),r.off(),"pending"===e.state()&&e.reject()})),n.ui.showDialog(n.$dialog)})).promise()}},{key:"show",value:function(){var t=this,e=this.context.invoke("editor.getLinkInfo");this.context.invoke("editor.saveRange"),this.showLinkDialog(e).then((function(e){t.context.invoke("editor.restoreRange"),t.context.invoke("editor.createLink",e)})).fail((function(){t.context.invoke("editor.restoreRange")}))}}])&&le(n.prototype,o),i&&le(n,i),t}();function ue(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var de=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.options=n.options,this.events={"summernote.keyup summernote.mouseup summernote.change summernote.scroll":function(){o.update()},"summernote.disable summernote.dialog.shown":function(){o.hide()},"summernote.blur":function(t,e){e.originalEvent&&e.originalEvent.relatedTarget&&o.$popover[0].contains(e.originalEvent.relatedTarget)||o.hide()}}}var n,o,i;return n=t,(o=[{key:"shouldInitialize",value:function(){return!k.isEmpty(this.options.popover.link)}},{key:"initialize",value:function(){this.$popover=this.ui.popover({className:"note-link-popover",callback:function(t){t.find(".popover-content,.note-popover-content").prepend('<span><a target="_blank"></a>&nbsp;</span>')}}).render().appendTo(this.options.container);var t=this.$popover.find(".popover-content,.note-popover-content");this.context.invoke("buttons.build",t,this.options.popover.link),this.$popover.on("mousedown",(function(t){t.preventDefault()}))}},{key:"destroy",value:function(){this.$popover.remove()}},{key:"update",value:function(){if(this.context.invoke("editor.hasFocus")){var t=this.context.invoke("editor.getLastRange");if(t.isCollapsed()&&t.isOnAnchor()){var n=ht.ancestor(t.sc,ht.isAnchor),o=e()(n).attr("href");this.$popover.find("a").attr("href",o).text(o);var i=ht.posFromPlaceholder(n),r=e()(this.options.container).offset();i.top-=r.top,i.left-=r.left,this.$popover.css({display:"block",left:i.left,top:i.top})}else this.hide()}else this.hide()}},{key:"hide",value:function(){this.$popover.hide()}}])&&ue(n.prototype,o),i&&ue(n,i),t}();function he(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var fe=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.$body=e()(document.body),this.$editor=n.layoutInfo.editor,this.options=n.options,this.lang=this.options.langInfo}var n,o,i;return n=t,(o=[{key:"initialize",value:function(){var t="";if(this.options.maximumImageFileSize){var e=Math.floor(Math.log(this.options.maximumImageFileSize)/Math.log(1024)),n=1*(this.options.maximumImageFileSize/Math.pow(1024,e)).toFixed(2)+" "+" KMGTP"[e]+"B";t="<small>".concat(this.lang.image.maximumFileSize+" : "+n,"</small>")}var o=this.options.dialogsInBody?this.$body:this.options.container,i=['<div class="form-group note-form-group note-group-select-from-files">','<label for="note-dialog-image-file-'+this.options.id+'" class="note-form-label">'+this.lang.image.selectFromFiles+"</label>",'<input id="note-dialog-image-file-'+this.options.id+'" class="note-image-input form-control-file note-form-control note-input" ',' type="file" name="files" accept="'+this.options.acceptImageFileTypes+'" multiple="multiple"/>',t,"</div>",'<div class="form-group note-group-image-url">','<label for="note-dialog-image-url-'+this.options.id+'" class="note-form-label">'+this.lang.image.url+"</label>",'<input id="note-dialog-image-url-'+this.options.id+'" class="note-image-url form-control note-form-control note-input" type="text"/>',"</div>"].join(""),r='<input type="button" href="#" class="'.concat("btn btn-primary note-btn note-btn-primary note-image-btn",'" value="').concat(this.lang.image.insert,'" disabled>');this.$dialog=this.ui.dialog({title:this.lang.image.insert,fade:this.options.dialogsFade,body:i,footer:r}).render().appendTo(o)}},{key:"destroy",value:function(){this.ui.hideDialog(this.$dialog),this.$dialog.remove()}},{key:"bindEnterKey",value:function(t,e){t.on("keypress",(function(t){t.keyCode===wt.code.ENTER&&(t.preventDefault(),e.trigger("click"))}))}},{key:"show",value:function(){var t=this;this.context.invoke("editor.saveRange"),this.showImageDialog().then((function(e){t.ui.hideDialog(t.$dialog),t.context.invoke("editor.restoreRange"),"string"==typeof e?t.options.callbacks.onImageLinkInsert?t.context.triggerEvent("image.link.insert",e):t.context.invoke("editor.insertImage",e):t.context.invoke("editor.insertImagesOrCallback",e)})).fail((function(){t.context.invoke("editor.restoreRange")}))}},{key:"showImageDialog",value:function(){var t=this;return e().Deferred((function(e){var n=t.$dialog.find(".note-image-input"),o=t.$dialog.find(".note-image-url"),i=t.$dialog.find(".note-image-btn");t.ui.onDialogShown(t.$dialog,(function(){t.context.triggerEvent("dialog.shown"),n.replaceWith(n.clone().on("change",(function(t){e.resolve(t.target.files||t.target.value)})).val("")),o.on("input paste propertychange",(function(){t.ui.toggleBtn(i,o.val())})).val(""),f.isSupportTouch||o.trigger("focus"),i.click((function(t){t.preventDefault(),e.resolve(o.val())})),t.bindEnterKey(o,i)})),t.ui.onDialogHidden(t.$dialog,(function(){n.off(),o.off(),i.off(),"pending"===e.state()&&e.reject()})),t.ui.showDialog(t.$dialog)}))}}])&&he(n.prototype,o),i&&he(n,i),t}();function pe(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var me=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.editable=n.layoutInfo.editable[0],this.options=n.options,this.events={"summernote.disable summernote.dialog.shown":function(){o.hide()},"summernote.blur":function(t,e){e.originalEvent&&e.originalEvent.relatedTarget&&o.$popover[0].contains(e.originalEvent.relatedTarget)||o.hide()}}}var n,o,i;return n=t,o=[{key:"shouldInitialize",value:function(){return!k.isEmpty(this.options.popover.image)}},{key:"initialize",value:function(){this.$popover=this.ui.popover({className:"note-image-popover"}).render().appendTo(this.options.container);var t=this.$popover.find(".popover-content,.note-popover-content");this.context.invoke("buttons.build",t,this.options.popover.image),this.$popover.on("mousedown",(function(t){t.preventDefault()}))}},{key:"destroy",value:function(){this.$popover.remove()}},{key:"update",value:function(t,n){if(ht.isImg(t)){var o=e()(t).offset(),i=e()(this.options.container).offset(),r={};this.options.popatmouse?(r.left=n.pageX-20,r.top=n.pageY):r=o,r.top-=i.top,r.left-=i.left,this.$popover.css({display:"block",left:r.left,top:r.top})}else this.hide()}},{key:"hide",value:function(){this.$popover.hide()}}],o&&pe(n.prototype,o),i&&pe(n,i),t}();function ve(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var ge=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.options=n.options,this.events={"summernote.mousedown":function(t,e){o.update(e.target)},"summernote.keyup summernote.scroll summernote.change":function(){o.update()},"summernote.disable summernote.dialog.shown":function(){o.hide()},"summernote.blur":function(t,e){e.originalEvent&&e.originalEvent.relatedTarget&&o.$popover[0].contains(e.originalEvent.relatedTarget)||o.hide()}}}var n,o,i;return n=t,o=[{key:"shouldInitialize",value:function(){return!k.isEmpty(this.options.popover.table)}},{key:"initialize",value:function(){this.$popover=this.ui.popover({className:"note-table-popover"}).render().appendTo(this.options.container);var t=this.$popover.find(".popover-content,.note-popover-content");this.context.invoke("buttons.build",t,this.options.popover.table),f.isFF&&document.execCommand("enableInlineTableEditing",!1,!1),this.$popover.on("mousedown",(function(t){t.preventDefault()}))}},{key:"destroy",value:function(){this.$popover.remove()}},{key:"update",value:function(t){if(this.context.isDisabled())return!1;var n=ht.isCell(t)||ht.isCell(null==t?void 0:t.parentElement);if(n){var o=ht.posFromPlaceholder(t),i=e()(this.options.container).offset();o.top-=i.top,o.left-=i.left,this.$popover.css({display:"block",left:o.left,top:o.top})}else this.hide();return n}},{key:"hide",value:function(){this.$popover.hide()}}],o&&ve(n.prototype,o),i&&ve(n,i),t}();function be(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var ye=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.$body=e()(document.body),this.$editor=n.layoutInfo.editor,this.options=n.options,this.lang=this.options.langInfo}var n,o,i;return n=t,(o=[{key:"initialize",value:function(){var t=this.options.dialogsInBody?this.$body:this.options.container,e=['<div class="form-group note-form-group row-fluid">','<label for="note-dialog-video-url-'.concat(this.options.id,'" class="note-form-label">').concat(this.lang.video.url,' <small class="text-muted">').concat(this.lang.video.providers,"</small></label>"),'<input id="note-dialog-video-url-'.concat(this.options.id,'" class="note-video-url form-control note-form-control note-input" type="text"/>'),"</div>"].join(""),n='<input type="button" href="#" class="'.concat("btn btn-primary note-btn note-btn-primary note-video-btn",'" value="').concat(this.lang.video.insert,'" disabled>');this.$dialog=this.ui.dialog({title:this.lang.video.insert,fade:this.options.dialogsFade,body:e,footer:n}).render().appendTo(t)}},{key:"destroy",value:function(){this.ui.hideDialog(this.$dialog),this.$dialog.remove()}},{key:"bindEnterKey",value:function(t,e){t.on("keypress",(function(t){t.keyCode===wt.code.ENTER&&(t.preventDefault(),e.trigger("click"))}))}},{key:"createVideoNode",value:function(t){var n,o=t.match(/\/\/(?:(?:www|m)\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w|-]{11})(?:(?:[\?&]t=)(\S+))?$/),i=t.match(/(?:\.|\/\/)drive\.google\.com\/file\/d\/(.[a-zA-Z0-9_-]*)\/view/),r=t.match(/(?:www\.|\/\/)instagram\.com\/p\/(.[a-zA-Z0-9_-]*)/),a=t.match(/\/\/vine\.co\/v\/([a-zA-Z0-9]+)/),s=t.match(/\/\/(player\.)?vimeo\.com\/([a-z]*\/)*(\d+)[?]?.*/),l=t.match(/.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/),c=t.match(/\/\/v\.youku\.com\/v_show\/id_(\w+)=*\.html/),u=t.match(/\/\/(.*)\/videos\/watch\/([^?]*)(?:\?(?:start=(\w*))?(?:&stop=(\w*))?(?:&loop=([10]))?(?:&autoplay=([10]))?(?:&muted=([10]))?)?/),d=t.match(/\/\/v\.qq\.com.*?vid=(.+)/),h=t.match(/\/\/v\.qq\.com\/x?\/?(page|cover).*?\/([^\/]+)\.html\??.*/),f=t.match(/^.+.(mp4|m4v)$/),p=t.match(/^.+.(ogg|ogv)$/),m=t.match(/^.+.(webm)$/),v=t.match(/(?:www\.|\/\/)facebook\.com\/([^\/]+)\/videos\/([0-9]+)/);if(o&&11===o[1].length){var g=o[1],b=0;if(void 0!==o[2]){var y=o[2].match(/^(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s)?$/);if(y)for(var k=[3600,60,1],w=0,C=k.length;w<C;w++)b+=void 0!==y[w+1]?k[w]*parseInt(y[w+1],10):0}n=e()("<iframe>").attr("frameborder",0).attr("src","//www.youtube.com/embed/"+g+(b>0?"?start="+b:"")).attr("width","640").attr("height","360")}else if(i&&i[0].length)n=e()("<iframe>").attr("frameborder",0).attr("src","https://drive.google.com/file/d/"+i[1]+"/preview").attr("width","640").attr("height","480");else if(r&&r[0].length)n=e()("<iframe>").attr("frameborder",0).attr("src","https://instagram.com/p/"+r[1]+"/embed/").attr("width","612").attr("height","710").attr("scrolling","no").attr("allowtransparency","true");else if(a&&a[0].length)n=e()("<iframe>").attr("frameborder",0).attr("src",a[0]+"/embed/simple").attr("width","600").attr("height","600").attr("class","vine-embed");else if(s&&s[3].length)n=e()("<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen>").attr("frameborder",0).attr("src","//player.vimeo.com/video/"+s[3]).attr("width","640").attr("height","360");else if(l&&l[2].length)n=e()("<iframe>").attr("frameborder",0).attr("src","//www.dailymotion.com/embed/video/"+l[2]).attr("width","640").attr("height","360");else if(c&&c[1].length)n=e()("<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen>").attr("frameborder",0).attr("height","498").attr("width","510").attr("src","//player.youku.com/embed/"+c[1]);else if(u&&u[0].length){var x=0;"undefined"!==u[2]&&(x=u[2]);var S=0;"undefined"!==u[3]&&(S=u[3]);var T=0;"undefined"!==u[4]&&(T=u[4]);var E=0;"undefined"!==u[5]&&(E=u[5]);var I=0;"undefined"!==u[6]&&(I=u[6]),n=e()('<iframe allowfullscreen sandbox="allow-same-origin allow-scripts allow-popups">').attr("frameborder",0).attr("src","//"+u[1]+"/videos/embed/"+u[2]+"?loop="+T+"&autoplay="+E+"&muted="+I+(x>0?"&start="+x:"")+(S>0?"&end="+b:"")).attr("width","560").attr("height","315")}else if(d&&d[1].length||h&&h[2].length){var $=d&&d[1].length?d[1]:h[2];n=e()("<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen>").attr("frameborder",0).attr("height","310").attr("width","500").attr("src","https://v.qq.com/txp/iframe/player.html?vid="+$+"&amp;auto=0")}else if(f||p||m)n=e()("<video controls>").attr("src",t).attr("width","640").attr("height","360");else{if(!v||!v[0].length)return!1;n=e()("<iframe>").attr("frameborder",0).attr("src","https://www.facebook.com/plugins/video.php?href="+encodeURIComponent(v[0])+"&show_text=0&width=560").attr("width","560").attr("height","301").attr("scrolling","no").attr("allowtransparency","true")}return n.addClass("note-video-clip"),n[0]}},{key:"show",value:function(){var t=this,e=this.context.invoke("editor.getSelectedText");this.context.invoke("editor.saveRange"),this.showVideoDialog(e).then((function(e){t.ui.hideDialog(t.$dialog),t.context.invoke("editor.restoreRange");var n=t.createVideoNode(e);n&&t.context.invoke("editor.insertNode",n)})).fail((function(){t.context.invoke("editor.restoreRange")}))}},{key:"showVideoDialog",value:function(){var t=this;return e().Deferred((function(e){var n=t.$dialog.find(".note-video-url"),o=t.$dialog.find(".note-video-btn");t.ui.onDialogShown(t.$dialog,(function(){t.context.triggerEvent("dialog.shown"),n.on("input paste propertychange",(function(){t.ui.toggleBtn(o,n.val())})),f.isSupportTouch||n.trigger("focus"),o.click((function(t){t.preventDefault(),e.resolve(n.val())})),t.bindEnterKey(n,o)})),t.ui.onDialogHidden(t.$dialog,(function(){n.off(),o.off(),"pending"===e.state()&&e.reject()})),t.ui.showDialog(t.$dialog)}))}}])&&be(n.prototype,o),i&&be(n,i),t}();function ke(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var we=function(){function t(n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.$body=e()(document.body),this.$editor=n.layoutInfo.editor,this.options=n.options,this.lang=this.options.langInfo}var n,o,i;return n=t,o=[{key:"initialize",value:function(){var t=this.options.dialogsInBody?this.$body:this.options.container,e=['<p class="text-center">','<a href="http://summernote.org/" target="_blank" rel="noopener noreferrer">Summernote 0.8.20</a> · ','<a href="https://github.com/summernote/summernote" target="_blank" rel="noopener noreferrer">Project</a> · ','<a href="https://github.com/summernote/summernote/issues" target="_blank" rel="noopener noreferrer">Issues</a>',"</p>"].join("");this.$dialog=this.ui.dialog({title:this.lang.options.help,fade:this.options.dialogsFade,body:this.createShortcutList(),footer:e,callback:function(t){t.find(".modal-body,.note-modal-body").css({"max-height":300,overflow:"scroll"})}}).render().appendTo(t)}},{key:"destroy",value:function(){this.ui.hideDialog(this.$dialog),this.$dialog.remove()}},{key:"createShortcutList",value:function(){var t=this,n=this.options.keyMap[f.isMac?"mac":"pc"];return Object.keys(n).map((function(o){var i=n[o],r=e()('<div><div class="help-list-item"></div></div>');return r.append(e()("<label><kbd>"+o+"</kdb></label>").css({width:180,"margin-right":10})).append(e()("<span></span>").html(t.context.memo("help."+i)||i)),r.html()})).join("")}},{key:"showHelpDialog",value:function(){var t=this;return e().Deferred((function(e){t.ui.onDialogShown(t.$dialog,(function(){t.context.triggerEvent("dialog.shown"),e.resolve()})),t.ui.showDialog(t.$dialog)})).promise()}},{key:"show",value:function(){var t=this;this.context.invoke("editor.saveRange"),this.showHelpDialog().then((function(){t.context.invoke("editor.restoreRange")}))}}],o&&ke(n.prototype,o),i&&ke(n,i),t}();function Ce(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var xe=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.options=n.options,this.hidable=!0,this.onContextmenu=!1,this.pageX=null,this.pageY=null,this.events={"summernote.contextmenu":function(t){o.options.editing&&(t.preventDefault(),t.stopPropagation(),o.onContextmenu=!0,o.update(!0))},"summernote.mousedown":function(t,e){o.pageX=e.pageX,o.pageY=e.pageY},"summernote.keyup summernote.mouseup summernote.scroll":function(t,e){o.options.editing&&!o.onContextmenu&&(o.pageX=e.pageX,o.pageY=e.pageY,o.update()),o.onContextmenu=!1},"summernote.disable summernote.change summernote.dialog.shown summernote.blur":function(){o.hide()},"summernote.focusout":function(){o.$popover.is(":active,:focus")||o.hide()}}}var n,o,i;return n=t,(o=[{key:"shouldInitialize",value:function(){return this.options.airMode&&!k.isEmpty(this.options.popover.air)}},{key:"initialize",value:function(){var t=this;this.$popover=this.ui.popover({className:"note-air-popover"}).render().appendTo(this.options.container);var e=this.$popover.find(".popover-content");this.context.invoke("buttons.build",e,this.options.popover.air),this.$popover.on("mousedown",(function(){t.hidable=!1})),this.$popover.on("mouseup",(function(){t.hidable=!0}))}},{key:"destroy",value:function(){this.$popover.remove()}},{key:"update",value:function(t){var n=this.context.invoke("editor.currentStyle");if(!n.range||n.range.isCollapsed()&&!t)this.hide();else{var o={left:this.pageX,top:this.pageY},i=e()(this.options.container).offset();o.top-=i.top,o.left-=i.left,this.$popover.css({display:"block",left:Math.max(o.left,0)+-5,top:o.top+5}),this.context.invoke("buttons.updateCurrentStyle",this.$popover)}}},{key:"updateCodeview",value:function(t){this.ui.toggleBtnActive(this.$popover.find(".btn-codeview"),t),t&&this.hide()}},{key:"hide",value:function(){this.hidable&&this.$popover.hide()}}])&&Ce(n.prototype,o),i&&Ce(n,i),t}();function Se(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}var Te=function(){function t(n){var o=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.context=n,this.ui=e().summernote.ui,this.$editable=n.layoutInfo.editable,this.options=n.options,this.hint=this.options.hint||[],this.direction=this.options.hintDirection||"bottom",this.hints=Array.isArray(this.hint)?this.hint:[this.hint],this.events={"summernote.keyup":function(t,e){e.isDefaultPrevented()||o.handleKeyup(e)},"summernote.keydown":function(t,e){o.handleKeydown(e)},"summernote.disable summernote.dialog.shown summernote.blur":function(){o.hide()}}}var n,o,i;return n=t,o=[{key:"shouldInitialize",value:function(){return this.hints.length>0}},{key:"initialize",value:function(){var t=this;this.lastWordRange=null,this.matchingWord=null,this.$popover=this.ui.popover({className:"note-hint-popover",hideArrow:!0,direction:""}).render().appendTo(this.options.container),this.$popover.hide(),this.$content=this.$popover.find(".popover-content,.note-popover-content"),this.$content.on("click",".note-hint-item",(function(n){t.$content.find(".active").removeClass("active"),e()(n.currentTarget).addClass("active"),t.replace()})),this.$popover.on("mousedown",(function(t){t.preventDefault()}))}},{key:"destroy",value:function(){this.$popover.remove()}},{key:"selectItem",value:function(t){this.$content.find(".active").removeClass("active"),t.addClass("active"),this.$content[0].scrollTop=t[0].offsetTop-this.$content.innerHeight()/2}},{key:"moveDown",value:function(){var t=this.$content.find(".note-hint-item.active"),e=t.next();if(e.length)this.selectItem(e);else{var n=t.parent().next();n.length||(n=this.$content.find(".note-hint-group").first()),this.selectItem(n.find(".note-hint-item").first())}}},{key:"moveUp",value:function(){var t=this.$content.find(".note-hint-item.active"),e=t.prev();if(e.length)this.selectItem(e);else{var n=t.parent().prev();n.length||(n=this.$content.find(".note-hint-group").last()),this.selectItem(n.find(".note-hint-item").last())}}},{key:"replace",value:function(){var t=this.$content.find(".note-hint-item.active");if(t.length){var n=this.nodeFromItem(t);if(null!==this.matchingWord&&0===this.matchingWord.length)this.lastWordRange.so=this.lastWordRange.eo;else if(null!==this.matchingWord&&this.matchingWord.length>0&&!this.lastWordRange.isCollapsed()){var o=this.lastWordRange.eo-this.lastWordRange.so-this.matchingWord.length;o>0&&(this.lastWordRange.so+=o)}if(this.lastWordRange.insertNode(n),"next"===this.options.hintSelect){var i=document.createTextNode("");e()(n).after(i),yt.createFromNodeBefore(i).select()}else yt.createFromNodeAfter(n).select();this.lastWordRange=null,this.hide(),this.context.invoke("editor.focus"),this.context.triggerEvent("change",this.$editable.html(),this.$editable)}}},{key:"nodeFromItem",value:function(t){var e=this.hints[t.data("index")],n=t.data("item"),o=e.content?e.content(n):n;return"string"==typeof o&&(o=ht.createText(o)),o}},{key:"createItemTemplates",value:function(t,n){var o=this.hints[t];return n.map((function(n){var i=e()('<div class="note-hint-item"></div>');return i.append(o.template?o.template(n):n+""),i.data({index:t,item:n}),i}))}},{key:"handleKeydown",value:function(t){this.$popover.is(":visible")&&(t.keyCode===wt.code.ENTER?(t.preventDefault(),this.replace()):t.keyCode===wt.code.UP?(t.preventDefault(),this.moveUp()):t.keyCode===wt.code.DOWN&&(t.preventDefault(),this.moveDown()))}},{key:"searchKeyword",value:function(t,e,n){var o=this.hints[t];if(o&&o.match.test(e)&&o.search){var i=o.match.exec(e);this.matchingWord=i[0],o.search(i[1],n)}else n()}},{key:"createGroup",value:function(t,n){var o=this,i=e()('<div class="note-hint-group note-hint-group-'+t+'"></div>');return this.searchKeyword(t,n,(function(e){(e=e||[]).length&&(i.html(o.createItemTemplates(t,e)),o.show())})),i}},{key:"handleKeyup",value:function(t){var n=this;if(!k.contains([wt.code.ENTER,wt.code.UP,wt.code.DOWN],t.keyCode)){var o,i,r=this.context.invoke("editor.getLastRange");if("words"===this.options.hintMode){if(o=r.getWordsRange(r),i=o.toString(),this.hints.forEach((function(t){if(t.match.test(i))return o=r.getWordsMatchRange(t.match),!1})),!o)return void this.hide();i=o.toString()}else o=r.getWordRange(),i=o.toString();if(this.hints.length&&i){this.$content.empty();var a=m.rect2bnd(k.last(o.getClientRects())),s=e()(this.options.container).offset();a&&(a.top-=s.top,a.left-=s.left,this.$popover.hide(),this.lastWordRange=o,this.hints.forEach((function(t,e){t.match.test(i)&&n.createGroup(e,i).appendTo(n.$content)})),this.$content.find(".note-hint-item:first").addClass("active"),"top"===this.direction?this.$popover.css({left:a.left,top:a.top-this.$popover.outerHeight()-5}):this.$popover.css({left:a.left,top:a.top+a.height+5}))}else this.hide()}}},{key:"show",value:function(){this.$popover.show()}},{key:"hide",value:function(){this.$popover.hide()}}],o&&Se(n.prototype,o),i&&Se(n,i),t}();function Ee(t){return Ee="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},Ee(t)}function Ie(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}e().summernote=e().extend(e().summernote,{version:"0.8.20",plugins:{},dom:ht,range:yt,lists:k,options:{langInfo:e().summernote.lang["en-US"],editing:!0,modules:{editor:Ft,clipboard:Ht,dropzone:Bt,codeview:jt,statusbar:Kt,fullscreen:qt,handle:Gt,hintPopover:Te,autoLink:Qt,autoSync:Jt,autoReplace:ee,placeholder:oe,buttons:re,toolbar:se,linkDialog:ce,linkPopover:de,imageDialog:fe,imagePopover:me,tablePopover:ge,videoDialog:ye,helpDialog:we,airPopover:xe},buttons:{},lang:"en-US",followingToolbar:!1,toolbarPosition:"top",otherStaticBar:"",codeviewKeepButton:!1,toolbar:[["style",["style"]],["font",["bold","underline","clear"]],["fontname",["fontname"]],["color",["color"]],["para",["ul","ol","paragraph"]],["table",["table"]],["insert",["link","picture","video"]],["view",["fullscreen","codeview","help"]]],popatmouse:!0,popover:{image:[["resize",["resizeFull","resizeHalf","resizeQuarter","resizeNone"]],["float",["floatLeft","floatRight","floatNone"]],["remove",["removeMedia"]]],link:[["link",["linkDialogShow","unlink"]]],table:[["add",["addRowDown","addRowUp","addColLeft","addColRight"]],["delete",["deleteRow","deleteCol","deleteTable"]]],air:[["color",["color"]],["font",["bold","underline","clear"]],["para",["ul","paragraph"]],["table",["table"]],["insert",["link","picture"]],["view",["fullscreen","codeview"]]]},airMode:!1,overrideContextMenu:!1,width:null,height:null,linkTargetBlank:!0,useProtocol:!0,defaultProtocol:"http://",focus:!1,tabDisabled:!1,tabSize:4,styleWithCSS:!1,shortcuts:!0,textareaAutoSync:!0,tooltip:"auto",container:null,maxTextLength:0,blockquoteBreakingLevel:2,spellCheck:!0,disableGrammar:!1,placeholder:null,inheritPlaceholder:!1,recordEveryKeystroke:!1,historyLimit:200,showDomainOnlyForAutolink:!1,hintMode:"word",hintSelect:"after",hintDirection:"bottom",styleTags:["p","blockquote","pre","h1","h2","h3","h4","h5","h6"],fontNames:["Arial","Arial Black","Comic Sans MS","Courier New","Helvetica Neue","Helvetica","Impact","Lucida Grande","Tahoma","Times New Roman","Verdana"],fontNamesIgnoreCheck:[],addDefaultFonts:!0,fontSizes:["8","9","10","11","12","14","18","24","36"],fontSizeUnits:["px","pt"],colors:[["#000000","#424242","#636363","#9C9C94","#CEC6CE","#EFEFEF","#F7F7F7","#FFFFFF"],["#FF0000","#FF9C00","#FFFF00","#00FF00","#00FFFF","#0000FF","#9C00FF","#FF00FF"],["#F7C6CE","#FFE7CE","#FFEFC6","#D6EFD6","#CEDEE7","#CEE7F7","#D6D6E7","#E7D6DE"],["#E79C9C","#FFC69C","#FFE79C","#B5D6A5","#A5C6CE","#9CC6EF","#B5A5D6","#D6A5BD"],["#E76363","#F7AD6B","#FFD663","#94BD7B","#73A5AD","#6BADDE","#8C7BC6","#C67BA5"],["#CE0000","#E79439","#EFC631","#6BA54A","#4A7B8C","#3984C6","#634AA5","#A54A7B"],["#9C0000","#B56308","#BD9400","#397B21","#104A5A","#085294","#311873","#731842"],["#630000","#7B3900","#846300","#295218","#083139","#003163","#21104A","#4A1031"]],colorsName:[["Black","Tundora","Dove Gray","Star Dust","Pale Slate","Gallery","Alabaster","White"],["Red","Orange Peel","Yellow","Green","Cyan","Blue","Electric Violet","Magenta"],["Azalea","Karry","Egg White","Zanah","Botticelli","Tropical Blue","Mischka","Twilight"],["Tonys Pink","Peach Orange","Cream Brulee","Sprout","Casper","Perano","Cold Purple","Careys Pink"],["Mandy","Rajah","Dandelion","Olivine","Gulf Stream","Viking","Blue Marguerite","Puce"],["Guardsman Red","Fire Bush","Golden Dream","Chelsea Cucumber","Smalt Blue","Boston Blue","Butterfly Bush","Cadillac"],["Sangria","Mai Tai","Buddha Gold","Forest Green","Eden","Venice Blue","Meteorite","Claret"],["Rosewood","Cinnamon","Olive","Parsley","Tiber","Midnight Blue","Valentino","Loulou"]],colorButton:{foreColor:"#000000",backColor:"#FFFF00"},lineHeights:["1.0","1.2","1.4","1.5","1.6","1.8","2.0","3.0"],tableClassName:"table table-bordered",insertTableMaxSize:{col:10,row:10},dialogsInBody:!1,dialogsFade:!1,maximumImageFileSize:null,acceptImageFileTypes:"image/*",callbacks:{onBeforeCommand:null,onBlur:null,onBlurCodeview:null,onChange:null,onChangeCodeview:null,onDialogShown:null,onEnter:null,onFocus:null,onImageLinkInsert:null,onImageUpload:null,onImageUploadError:null,onInit:null,onKeydown:null,onKeyup:null,onMousedown:null,onMouseup:null,onPaste:null,onScroll:null},codemirror:{mode:"text/html",htmlMode:!0,lineNumbers:!0},codeviewFilter:!0,codeviewFilterRegex:/<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*?>/gi,codeviewIframeFilter:!0,codeviewIframeWhitelistSrc:[],codeviewIframeWhitelistSrcBase:["www.youtube.com","www.youtube-nocookie.com","www.facebook.com","vine.co","instagram.com","player.vimeo.com","www.dailymotion.com","player.youku.com","jumpingbean.tv","v.qq.com"],keyMap:{pc:{ESC:"escape",ENTER:"insertParagraph","CTRL+Z":"undo","CTRL+Y":"redo",TAB:"tab","SHIFT+TAB":"untab","CTRL+B":"bold","CTRL+I":"italic","CTRL+U":"underline","CTRL+SHIFT+S":"strikethrough","CTRL+BACKSLASH":"removeFormat","CTRL+SHIFT+L":"justifyLeft","CTRL+SHIFT+E":"justifyCenter","CTRL+SHIFT+R":"justifyRight","CTRL+SHIFT+J":"justifyFull","CTRL+SHIFT+NUM7":"insertUnorderedList","CTRL+SHIFT+NUM8":"insertOrderedList","CTRL+LEFTBRACKET":"outdent","CTRL+RIGHTBRACKET":"indent","CTRL+NUM0":"formatPara","CTRL+NUM1":"formatH1","CTRL+NUM2":"formatH2","CTRL+NUM3":"formatH3","CTRL+NUM4":"formatH4","CTRL+NUM5":"formatH5","CTRL+NUM6":"formatH6","CTRL+ENTER":"insertHorizontalRule","CTRL+K":"linkDialog.show"},mac:{ESC:"escape",ENTER:"insertParagraph","CMD+Z":"undo","CMD+SHIFT+Z":"redo",TAB:"tab","SHIFT+TAB":"untab","CMD+B":"bold","CMD+I":"italic","CMD+U":"underline","CMD+SHIFT+S":"strikethrough","CMD+BACKSLASH":"removeFormat","CMD+SHIFT+L":"justifyLeft","CMD+SHIFT+E":"justifyCenter","CMD+SHIFT+R":"justifyRight","CMD+SHIFT+J":"justifyFull","CMD+SHIFT+NUM7":"insertUnorderedList","CMD+SHIFT+NUM8":"insertOrderedList","CMD+LEFTBRACKET":"outdent","CMD+RIGHTBRACKET":"indent","CMD+NUM0":"formatPara","CMD+NUM1":"formatH1","CMD+NUM2":"formatH2","CMD+NUM3":"formatH3","CMD+NUM4":"formatH4","CMD+NUM5":"formatH5","CMD+NUM6":"formatH6","CMD+ENTER":"insertHorizontalRule","CMD+K":"linkDialog.show"}},icons:{align:"note-icon-align",alignCenter:"note-icon-align-center",alignJustify:"note-icon-align-justify",alignLeft:"note-icon-align-left",alignRight:"note-icon-align-right",rowBelow:"note-icon-row-below",colBefore:"note-icon-col-before",colAfter:"note-icon-col-after",rowAbove:"note-icon-row-above",rowRemove:"note-icon-row-remove",colRemove:"note-icon-col-remove",indent:"note-icon-align-indent",outdent:"note-icon-align-outdent",arrowsAlt:"note-icon-arrows-alt",bold:"note-icon-bold",caret:"note-icon-caret",circle:"note-icon-circle",close:"note-icon-close",code:"note-icon-code",eraser:"note-icon-eraser",floatLeft:"note-icon-float-left",floatRight:"note-icon-float-right",font:"note-icon-font",frame:"note-icon-frame",italic:"note-icon-italic",link:"note-icon-link",unlink:"note-icon-chain-broken",magic:"note-icon-magic",menuCheck:"note-icon-menu-check",minus:"note-icon-minus",orderedlist:"note-icon-orderedlist",pencil:"note-icon-pencil",picture:"note-icon-picture",question:"note-icon-question",redo:"note-icon-redo",rollback:"note-icon-rollback",square:"note-icon-square",strikethrough:"note-icon-strikethrough",subscript:"note-icon-subscript",superscript:"note-icon-superscript",table:"note-icon-table",textHeight:"note-icon-text-height",trash:"note-icon-trash",underline:"note-icon-underline",undo:"note-icon-undo",unorderedlist:"note-icon-unorderedlist",video:"note-icon-video"}}});var $e=function(){function t(e,n,o,i){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.markup=e,this.children=n,this.options=o,this.callback=i}var n,o,i;return n=t,(o=[{key:"render",value:function(t){var n=e()(this.markup);if(this.options&&this.options.contents&&n.html(this.options.contents),this.options&&this.options.className&&n.addClass(this.options.className),this.options&&this.options.data&&e().each(this.options.data,(function(t,e){n.attr("data-"+t,e)})),this.options&&this.options.click&&n.on("click",this.options.click),this.children){var o=n.find(".note-children-container");this.children.forEach((function(t){t.render(o.length?o:n)}))}return this.callback&&this.callback(n,this.options),this.options&&this.options.callback&&this.options.callback(n),t&&t.append(n),n}}])&&Ie(n.prototype,o),i&&Ie(n,i),t}();const Ne=function(t,e){return function(){var n="object"===Ee(arguments[1])?arguments[1]:arguments[0],o=Array.isArray(arguments[0])?arguments[0]:[];return n&&n.children&&(o=n.children),new $e(t,o,n,e)}};function Pe(t){return Pe="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},Pe(t)}var Re=Ne('<div class="note-editor note-frame card"></div>'),Le=Ne('<div class="note-toolbar card-header" role="toolbar"></div>'),Ae=Ne('<div class="note-editing-area"></div>'),Fe=Ne('<textarea class="note-codable" aria-multiline="true"></textarea>'),De=Ne('<div class="note-editable card-block" contentEditable="true" role="textbox" aria-multiline="true"></div>'),He=Ne(['<output class="note-status-output" role="status" aria-live="polite"></output>','<div class="note-statusbar" role="status">','<div class="note-resizebar" aria-label="Resize">','<div class="note-icon-bar"></div>','<div class="note-icon-bar"></div>','<div class="note-icon-bar"></div>',"</div>","</div>"].join("")),ze=Ne('<div class="note-editor note-airframe"></div>'),Be=Ne(['<div class="note-editable" contentEditable="true" role="textbox" aria-multiline="true"></div>','<output class="note-status-output" role="status" aria-live="polite"></output>'].join("")),Me=Ne('<div class="note-btn-group btn-group"></div>'),Oe=Ne('<div class="note-dropdown-menu dropdown-menu" role="list"></div>',(function(t,e){var n=Array.isArray(e.items)?e.items.map((function(t){var n="string"==typeof t?t:t.value||"",o=e.template?e.template(t):t,i="object"===Pe(t)?t.option:void 0;return'<a class="dropdown-item" href="#" '+('data-value="'+n+'"'+(void 0!==i?' data-option="'+i+'"':""))+' role="listitem" aria-label="'+n+'">'+o+"</a>"})).join(""):e.items;t.html(n).attr({"aria-label":e.title}),e&&e.codeviewKeepButton&&t.addClass("note-codeview-keep")})),Ue=function(t){return t},je=Ne('<div class="note-dropdown-menu dropdown-menu note-check" role="list"></div>',(function(t,e){var n=Array.isArray(e.items)?e.items.map((function(t){var n="string"==typeof t?t:t.value||"",o=e.template?e.template(t):t;return'<a class="dropdown-item" href="#" data-value="'+n+'" role="listitem" aria-label="'+t+'">'+qe(e.checkClassName)+" "+o+"</a>"})).join(""):e.items;t.html(n).attr({"aria-label":e.title}),e&&e.codeviewKeepButton&&t.addClass("note-codeview-keep")})),We=Ne('<div class="modal note-modal" aria-hidden="false" tabindex="-1" role="dialog"></div>',(function(t,e){e.fade&&t.addClass("fade"),t.attr({"aria-label":e.title}),t.html(['<div class="modal-dialog">','<div class="modal-content">',e.title?'<div class="modal-header"><h4 class="modal-title">'+e.title+'</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button></div>':"",'<div class="modal-body">'+e.body+"</div>",e.footer?'<div class="modal-footer">'+e.footer+"</div>":"","</div>","</div>"].join(""))})),Ke=Ne(['<div class="note-popover popover in">','<div class="arrow"></div>','<div class="popover-content note-children-container"></div>',"</div>"].join(""),(function(t,e){var n=void 0!==e.direction?e.direction:"bottom";t.addClass(n),e.hideArrow&&t.find(".arrow").hide()})),Ve=Ne('<div class="form-check"></div>',(function(t,e){t.html(['<label class="form-check-label"'+(e.id?' for="note-'+e.id+'"':"")+">",'<input type="checkbox" class="form-check-input"'+(e.id?' id="note-'+e.id+'"':""),e.checked?" checked":"",' aria-label="'+(e.text?e.text:"")+'"',' aria-checked="'+(e.checked?"true":"false")+'"/>'," "+(e.text?e.text:"")+"</label>"].join(""))})),qe=function(t,e){return t.match(/^</)?t:"<"+(e=e||"i")+' class="'+t+'"></'+e+">"};e().summernote=e().extend(e().summernote,{ui_template:function(t){return{editor:Re,toolbar:Le,editingArea:Ae,codable:Fe,editable:De,statusbar:He,airEditor:ze,airEditable:Be,buttonGroup:Me,dropdown:Oe,dropdownButtonContents:Ue,dropdownCheck:je,dialog:We,popover:Ke,icon:qe,checkbox:Ve,options:t,palette:function(e,n){return Ne('<div class="note-color-palette"></div>',(function(e,n){for(var o=[],i=0,r=n.colors.length;i<r;i++){for(var a=n.eventName,s=n.colors[i],l=n.colorsName[i],c=[],u=0,d=s.length;u<d;u++){var h=s[u],f=l[u];c.push(['<button type="button" class="note-color-btn"','style="background-color:',h,'" ','data-event="',a,'" ','data-value="',h,'" ','title="',f,'" ','aria-label="',f,'" ','data-toggle="button" tabindex="-1"></button>'].join(""))}o.push('<div class="note-color-row">'+c.join("")+"</div>")}e.html(o.join("")),n.tooltip&&e.find(".note-color-btn").tooltip({container:n.container||t.container,trigger:"hover",placement:"bottom"})}))(e,n)},button:function(n,o){return Ne('<button type="button" class="note-btn btn btn-light btn-sm" tabindex="-1"></button>',(function(n,o){o&&o.tooltip&&n.attr({title:o.tooltip,"aria-label":o.tooltip}).tooltip({container:o.container||t.container,trigger:"hover",placement:"bottom"}).on("click",(function(t){e()(t.currentTarget).tooltip("hide")})),o&&o.codeviewButton&&n.addClass("note-codeview-keep")}))(n,o)},toggleBtn:function(t,e){t.toggleClass("disabled",!e),t.attr("disabled",!e)},toggleBtnActive:function(t,e){t.toggleClass("active",e)},onDialogShown:function(t,e){t.one("shown.bs.modal",e)},onDialogHidden:function(t,e){t.one("hidden.bs.modal",e)},showDialog:function(t){t.modal("show")},hideDialog:function(t){t.modal("hide")},createLayout:function(e){var n=(t.airMode?ze([Ae([Fe(),Be()])]):"bottom"===t.toolbarPosition?Re([Ae([Fe(),De()]),Le(),He()]):Re([Le(),Ae([Fe(),De()]),He()])).render();return n.insertAfter(e),{note:e,editor:n,toolbar:n.find(".note-toolbar"),editingArea:n.find(".note-editing-area"),editable:n.find(".note-editable"),codable:n.find(".note-codable"),statusbar:n.find(".note-statusbar")}},removeLayout:function(t,e){t.html(e.editable.html()),e.editor.remove(),t.show()}}},interface:"bs4"}),e().summernote.options.styleTags=["p",{title:"Blockquote",tag:"blockquote",className:"blockquote",value:"blockquote"},"pre","h1","h2","h3","h4","h5","h6"]})(),i})()}));
/*! Summernote v0.8.20 | (c) 2013- Alan Hong and contributors | MIT license */
!function(e,t){if("object"==typeof exports&&"object"==typeof module)module.exports=t();else if("function"==typeof define&&define.amd)define([],t);else{var r=t();for(var i in r)("object"==typeof exports?exports:e)[i]=r[i]}}(self,(function(){return(e=jQuery).extend(e.summernote.lang,{"de-DE":{font:{bold:"Fett",italic:"Kursiv",underline:"Unterstrichen",clear:"Zurücksetzen",height:"Zeilenhöhe",name:"Schriftart",strikethrough:"Durchgestrichen",subscript:"Tiefgestellt",superscript:"Hochgestellt",size:"Schriftgröße"},image:{image:"Bild",insert:"Bild einfügen",resizeFull:"Originalgröße",resizeHalf:"1/2 Größe",resizeQuarter:"1/4 Größe",floatLeft:"Linksbündig",floatRight:"Rechtsbündig",floatNone:"Kein Textfluss",shapeRounded:"Abgerundete Ecken",shapeCircle:"Kreisförmig",shapeThumbnail:'"Vorschaubild"',shapeNone:"Kein Rahmen",dragImageHere:"Bild hierher ziehen",dropImage:"Bild oder Text nehmen",selectFromFiles:"Datei auswählen",maximumFileSize:"Maximale Dateigröße",maximumFileSizeError:"Maximale Dateigröße überschritten",url:"Bild URL",remove:"Bild entfernen",original:"Original"},video:{video:"Video",videoLink:"Videolink",insert:"Video einfügen",url:"Video URL",providers:"(YouTube, Vimeo, Vine, Instagram, DailyMotion oder Youku)"},link:{link:"Link",insert:"Link einfügen",unlink:"Link entfernen",edit:"Bearbeiten",textToDisplay:"Anzeigetext",url:"Link URL",openInNewWindow:"In neuem Fenster öffnen",useProtocol:"Standardprotokoll verwenden"},table:{table:"Tabelle",addRowAbove:"+ Zeile oberhalb",addRowBelow:"+ Zeile unterhalb",addColLeft:"+ Spalte links",addColRight:"+ Spalte rechts",delRow:"Zeile löschen",delCol:"Spalte löschen",delTable:"Tabelle löschen"},hr:{insert:"Horizontale Linie einfügen"},style:{style:"Stil",normal:"Normal",p:"Normal",blockquote:"Zitat",pre:"Quellcode",h1:"Überschrift 1",h2:"Überschrift 2",h3:"Überschrift 3",h4:"Überschrift 4",h5:"Überschrift 5",h6:"Überschrift 6"},lists:{unordered:"Aufzählung",ordered:"Nummerierung"},options:{help:"Hilfe",fullscreen:"Vollbild",codeview:"Quellcode anzeigen"},paragraph:{paragraph:"Absatz",outdent:"Einzug verkleinern",indent:"Einzug vergrößern",left:"Links ausrichten",center:"Zentriert ausrichten",right:"Rechts ausrichten",justify:"Blocksatz"},color:{recent:"Letzte Farbe",more:"Weitere Farben",background:"Hintergrundfarbe",foreground:"Schriftfarbe",transparent:"Transparenz",setTransparent:"Transparenz setzen",reset:"Zurücksetzen",resetToDefault:"Auf Standard zurücksetzen"},shortcut:{shortcuts:"Tastenkürzel",close:"Schließen",textFormatting:"Textformatierung",action:"Aktion",paragraphFormatting:"Absatzformatierung",documentStyle:"Dokumentenstil",extraKeys:"Weitere Tasten"},help:{insertParagraph:"Absatz einfügen",undo:"Letzte Anweisung rückgängig",redo:"Letzte Anweisung wiederholen",tab:"Einzug hinzufügen",untab:"Einzug entfernen",bold:"Schrift Fett",italic:"Schrift Kursiv",underline:"Unterstreichen",strikethrough:"Durchstreichen",removeFormat:"Entfernt Format",justifyLeft:"Linksbündig",justifyCenter:"Mittig",justifyRight:"Rechtsbündig",justifyFull:"Blocksatz",insertUnorderedList:"Unnummerierte Liste",insertOrderedList:"Nummerierte Liste",outdent:"Aktuellen Absatz ausrücken",indent:"Aktuellen Absatz einrücken",formatPara:"Formatiert aktuellen Block als Absatz (P-Tag)",formatH1:"Formatiert aktuellen Block als H1",formatH2:"Formatiert aktuellen Block als H2",formatH3:"Formatiert aktuellen Block als H3",formatH4:"Formatiert aktuellen Block als H4",formatH5:"Formatiert aktuellen Block als H5",formatH6:"Formatiert aktuellen Block als H6",insertHorizontalRule:"Fügt eine horizontale Linie ein","linkDialog.show":"Zeigt den Linkdialog"},history:{undo:"Rückgängig",redo:"Wiederholen"},specialChar:{specialChar:"Sonderzeichen",select:"Zeichen auswählen"}}}),{};var e}));
/*!
 * AdminLTE v3.0.5 (https://adminlte.io)
 * Copyright 2014-2020 Colorlib <http://colorlib.com>
 * Licensed under MIT (https://github.com/ColorlibHQ/AdminLTE/blob/master/LICENSE)
 */
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
  typeof define === 'function' && define.amd ? define(['exports'], factory) :
  (global = global || self, factory(global.adminlte = {}));
}(this, (function (exports) { 'use strict';

  /**
   * --------------------------------------------
   * AdminLTE ControlSidebar.js
   * License MIT
   * --------------------------------------------
   */
  var ControlSidebar = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'ControlSidebar';
    var DATA_KEY = 'lte.controlsidebar';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      COLLAPSED: "collapsed" + EVENT_KEY,
      EXPANDED: "expanded" + EVENT_KEY
    };
    var Selector = {
      CONTROL_SIDEBAR: '.control-sidebar',
      CONTROL_SIDEBAR_CONTENT: '.control-sidebar-content',
      DATA_TOGGLE: '[data-widget="control-sidebar"]',
      CONTENT: '.content-wrapper',
      HEADER: '.main-header',
      FOOTER: '.main-footer'
    };
    var ClassName = {
      CONTROL_SIDEBAR_ANIMATE: 'control-sidebar-animate',
      CONTROL_SIDEBAR_OPEN: 'control-sidebar-open',
      CONTROL_SIDEBAR_SLIDE: 'control-sidebar-slide-open',
      LAYOUT_FIXED: 'layout-fixed',
      NAVBAR_FIXED: 'layout-navbar-fixed',
      NAVBAR_SM_FIXED: 'layout-sm-navbar-fixed',
      NAVBAR_MD_FIXED: 'layout-md-navbar-fixed',
      NAVBAR_LG_FIXED: 'layout-lg-navbar-fixed',
      NAVBAR_XL_FIXED: 'layout-xl-navbar-fixed',
      FOOTER_FIXED: 'layout-footer-fixed',
      FOOTER_SM_FIXED: 'layout-sm-footer-fixed',
      FOOTER_MD_FIXED: 'layout-md-footer-fixed',
      FOOTER_LG_FIXED: 'layout-lg-footer-fixed',
      FOOTER_XL_FIXED: 'layout-xl-footer-fixed'
    };
    var Default = {
      controlsidebarSlide: true,
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'l'
    };
    /**
     * Class Definition
     * ====================================================
     */

    var ControlSidebar = /*#__PURE__*/function () {
      function ControlSidebar(element, config) {
        this._element = element;
        this._config = config;

        this._init();
      } // Public


      var _proto = ControlSidebar.prototype;

      _proto.collapse = function collapse() {
        // Show the control sidebar
        if (this._config.controlsidebarSlide) {
          $('html').addClass(ClassName.CONTROL_SIDEBAR_ANIMATE);
          $('body').removeClass(ClassName.CONTROL_SIDEBAR_SLIDE).delay(300).queue(function () {
            $(Selector.CONTROL_SIDEBAR).hide();
            $('html').removeClass(ClassName.CONTROL_SIDEBAR_ANIMATE);
            $(this).dequeue();
          });
        } else {
          $('body').removeClass(ClassName.CONTROL_SIDEBAR_OPEN);
        }

        var collapsedEvent = $.Event(Event.COLLAPSED);
        $(this._element).trigger(collapsedEvent);
      };

      _proto.show = function show() {
        // Collapse the control sidebar
        if (this._config.controlsidebarSlide) {
          $('html').addClass(ClassName.CONTROL_SIDEBAR_ANIMATE);
          $(Selector.CONTROL_SIDEBAR).show().delay(10).queue(function () {
            $('body').addClass(ClassName.CONTROL_SIDEBAR_SLIDE).delay(300).queue(function () {
              $('html').removeClass(ClassName.CONTROL_SIDEBAR_ANIMATE);
              $(this).dequeue();
            });
            $(this).dequeue();
          });
        } else {
          $('body').addClass(ClassName.CONTROL_SIDEBAR_OPEN);
        }

        var expandedEvent = $.Event(Event.EXPANDED);
        $(this._element).trigger(expandedEvent);
      };

      _proto.toggle = function toggle() {
        var shouldClose = $('body').hasClass(ClassName.CONTROL_SIDEBAR_OPEN) || $('body').hasClass(ClassName.CONTROL_SIDEBAR_SLIDE);

        if (shouldClose) {
          // Close the control sidebar
          this.collapse();
        } else {
          // Open the control sidebar
          this.show();
        }
      } // Private
      ;

      _proto._init = function _init() {
        var _this = this;

        this._fixHeight();

        this._fixScrollHeight();

        $(window).resize(function () {
          _this._fixHeight();

          _this._fixScrollHeight();
        });
        $(window).scroll(function () {
          if ($('body').hasClass(ClassName.CONTROL_SIDEBAR_OPEN) || $('body').hasClass(ClassName.CONTROL_SIDEBAR_SLIDE)) {
            _this._fixScrollHeight();
          }
        });
      };

      _proto._fixScrollHeight = function _fixScrollHeight() {
        var heights = {
          scroll: $(document).height(),
          window: $(window).height(),
          header: $(Selector.HEADER).outerHeight(),
          footer: $(Selector.FOOTER).outerHeight()
        };
        var positions = {
          bottom: Math.abs(heights.window + $(window).scrollTop() - heights.scroll),
          top: $(window).scrollTop()
        };
        var navbarFixed = false;
        var footerFixed = false;

        if ($('body').hasClass(ClassName.LAYOUT_FIXED)) {
          if ($('body').hasClass(ClassName.NAVBAR_FIXED) || $('body').hasClass(ClassName.NAVBAR_SM_FIXED) || $('body').hasClass(ClassName.NAVBAR_MD_FIXED) || $('body').hasClass(ClassName.NAVBAR_LG_FIXED) || $('body').hasClass(ClassName.NAVBAR_XL_FIXED)) {
            if ($(Selector.HEADER).css("position") === "fixed") {
              navbarFixed = true;
            }
          }

          if ($('body').hasClass(ClassName.FOOTER_FIXED) || $('body').hasClass(ClassName.FOOTER_SM_FIXED) || $('body').hasClass(ClassName.FOOTER_MD_FIXED) || $('body').hasClass(ClassName.FOOTER_LG_FIXED) || $('body').hasClass(ClassName.FOOTER_XL_FIXED)) {
            if ($(Selector.FOOTER).css("position") === "fixed") {
              footerFixed = true;
            }
          }

          if (positions.top === 0 && positions.bottom === 0) {
            $(Selector.CONTROL_SIDEBAR).css('bottom', heights.footer);
            $(Selector.CONTROL_SIDEBAR).css('top', heights.header);
            $(Selector.CONTROL_SIDEBAR + ', ' + Selector.CONTROL_SIDEBAR + ' ' + Selector.CONTROL_SIDEBAR_CONTENT).css('height', heights.window - (heights.header + heights.footer));
          } else if (positions.bottom <= heights.footer) {
            if (footerFixed === false) {
              $(Selector.CONTROL_SIDEBAR).css('bottom', heights.footer - positions.bottom);
              $(Selector.CONTROL_SIDEBAR + ', ' + Selector.CONTROL_SIDEBAR + ' ' + Selector.CONTROL_SIDEBAR_CONTENT).css('height', heights.window - (heights.footer - positions.bottom));
            } else {
              $(Selector.CONTROL_SIDEBAR).css('bottom', heights.footer);
            }
          } else if (positions.top <= heights.header) {
            if (navbarFixed === false) {
              $(Selector.CONTROL_SIDEBAR).css('top', heights.header - positions.top);
              $(Selector.CONTROL_SIDEBAR + ', ' + Selector.CONTROL_SIDEBAR + ' ' + Selector.CONTROL_SIDEBAR_CONTENT).css('height', heights.window - (heights.header - positions.top));
            } else {
              $(Selector.CONTROL_SIDEBAR).css('top', heights.header);
            }
          } else {
            if (navbarFixed === false) {
              $(Selector.CONTROL_SIDEBAR).css('top', 0);
              $(Selector.CONTROL_SIDEBAR + ', ' + Selector.CONTROL_SIDEBAR + ' ' + Selector.CONTROL_SIDEBAR_CONTENT).css('height', heights.window);
            } else {
              $(Selector.CONTROL_SIDEBAR).css('top', heights.header);
            }
          }
        }
      };

      _proto._fixHeight = function _fixHeight() {
        var heights = {
          window: $(window).height(),
          header: $(Selector.HEADER).outerHeight(),
          footer: $(Selector.FOOTER).outerHeight()
        };

        if ($('body').hasClass(ClassName.LAYOUT_FIXED)) {
          var sidebarHeight = heights.window - heights.header;

          if ($('body').hasClass(ClassName.FOOTER_FIXED) || $('body').hasClass(ClassName.FOOTER_SM_FIXED) || $('body').hasClass(ClassName.FOOTER_MD_FIXED) || $('body').hasClass(ClassName.FOOTER_LG_FIXED) || $('body').hasClass(ClassName.FOOTER_XL_FIXED)) {
            if ($(Selector.FOOTER).css("position") === "fixed") {
              sidebarHeight = heights.window - heights.header - heights.footer;
            }
          }

          $(Selector.CONTROL_SIDEBAR + ' ' + Selector.CONTROL_SIDEBAR_CONTENT).css('height', sidebarHeight);

          if (typeof $.fn.overlayScrollbars !== 'undefined') {
            $(Selector.CONTROL_SIDEBAR + ' ' + Selector.CONTROL_SIDEBAR_CONTENT).overlayScrollbars({
              className: this._config.scrollbarTheme,
              sizeAutoCapable: true,
              scrollbars: {
                autoHide: this._config.scrollbarAutoHide,
                clickScrolling: true
              }
            });
          }
        }
      } // Static
      ;

      ControlSidebar._jQueryInterface = function _jQueryInterface(operation) {
        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          var _options = $.extend({}, Default, $(this).data());

          if (!data) {
            data = new ControlSidebar(this, _options);
            $(this).data(DATA_KEY, data);
          }

          if (data[operation] === 'undefined') {
            throw new Error(operation + " is not a function");
          }

          data[operation]();
        });
      };

      return ControlSidebar;
    }();
    /**
     *
     * Data Api implementation
     * ====================================================
     */


    $(document).on('click', Selector.DATA_TOGGLE, function (event) {
      event.preventDefault();

      ControlSidebar._jQueryInterface.call($(this), 'toggle');
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = ControlSidebar._jQueryInterface;
    $.fn[NAME].Constructor = ControlSidebar;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return ControlSidebar._jQueryInterface;
    };

    return ControlSidebar;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE Layout.js
   * License MIT
   * --------------------------------------------
   */
  var Layout = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'Layout';
    var DATA_KEY = 'lte.layout';
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Selector = {
      HEADER: '.main-header',
      MAIN_SIDEBAR: '.main-sidebar',
      SIDEBAR: '.main-sidebar .sidebar',
      CONTENT: '.content-wrapper',
      BRAND: '.brand-link',
      CONTENT_HEADER: '.content-header',
      WRAPPER: '.wrapper',
      CONTROL_SIDEBAR: '.control-sidebar',
      CONTROL_SIDEBAR_CONTENT: '.control-sidebar-content',
      CONTROL_SIDEBAR_BTN: '[data-widget="control-sidebar"]',
      LAYOUT_FIXED: '.layout-fixed',
      FOOTER: '.main-footer',
      PUSHMENU_BTN: '[data-widget="pushmenu"]',
      LOGIN_BOX: '.login-box',
      REGISTER_BOX: '.register-box'
    };
    var ClassName = {
      HOLD: 'hold-transition',
      SIDEBAR: 'main-sidebar',
      CONTENT_FIXED: 'content-fixed',
      SIDEBAR_FOCUSED: 'sidebar-focused',
      LAYOUT_FIXED: 'layout-fixed',
      NAVBAR_FIXED: 'layout-navbar-fixed',
      FOOTER_FIXED: 'layout-footer-fixed',
      LOGIN_PAGE: 'login-page',
      REGISTER_PAGE: 'register-page',
      CONTROL_SIDEBAR_SLIDE_OPEN: 'control-sidebar-slide-open',
      CONTROL_SIDEBAR_OPEN: 'control-sidebar-open'
    };
    var Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'l',
      panelAutoHeight: true,
      loginRegisterAutoHeight: true
    };
    /**
     * Class Definition
     * ====================================================
     */

    var Layout = /*#__PURE__*/function () {
      function Layout(element, config) {
        this._config = config;
        this._element = element;

        this._init();
      } // Public


      var _proto = Layout.prototype;

      _proto.fixLayoutHeight = function fixLayoutHeight(extra) {
        if (extra === void 0) {
          extra = null;
        }

        var control_sidebar = 0;

        if ($('body').hasClass(ClassName.CONTROL_SIDEBAR_SLIDE_OPEN) || $('body').hasClass(ClassName.CONTROL_SIDEBAR_OPEN) || extra == 'control_sidebar') {
          control_sidebar = $(Selector.CONTROL_SIDEBAR_CONTENT).height();
        }

        var heights = {
          window: $(window).height(),
          header: $(Selector.HEADER).length !== 0 ? $(Selector.HEADER).outerHeight() : 0,
          footer: $(Selector.FOOTER).length !== 0 ? $(Selector.FOOTER).outerHeight() : 0,
          sidebar: $(Selector.SIDEBAR).length !== 0 ? $(Selector.SIDEBAR).height() : 0,
          control_sidebar: control_sidebar
        };

        var max = this._max(heights);

        var offset = this._config.panelAutoHeight;

        if (offset === true) {
          offset = 0;
        }

        if (offset !== false) {
          if (max == heights.control_sidebar) {
            $(Selector.CONTENT).css('min-height', max + offset);
          } else if (max == heights.window) {
            $(Selector.CONTENT).css('min-height', max + offset - heights.header - heights.footer);
          } else {
            $(Selector.CONTENT).css('min-height', max + offset - heights.header);
          }

          if (this._isFooterFixed()) {
            $(Selector.CONTENT).css('min-height', parseFloat($(Selector.CONTENT).css('min-height')) + heights.footer);
          }
        }

        if ($('body').hasClass(ClassName.LAYOUT_FIXED)) {
          if (offset !== false) {
            $(Selector.CONTENT).css('min-height', max + offset - heights.header - heights.footer);
          }

          if (typeof $.fn.overlayScrollbars !== 'undefined') {
            $(Selector.SIDEBAR).overlayScrollbars({
              className: this._config.scrollbarTheme,
              sizeAutoCapable: true,
              scrollbars: {
                autoHide: this._config.scrollbarAutoHide,
                clickScrolling: true
              }
            });
          }
        }
      };

      _proto.fixLoginRegisterHeight = function fixLoginRegisterHeight() {
        if ($(Selector.LOGIN_BOX + ', ' + Selector.REGISTER_BOX).length === 0) {
          $('body, html').css('height', 'auto');
        } else if ($(Selector.LOGIN_BOX + ', ' + Selector.REGISTER_BOX).length !== 0) {
          var box_height = $(Selector.LOGIN_BOX + ', ' + Selector.REGISTER_BOX).height();

          if ($('body').css('min-height') !== box_height) {
            $('body').css('min-height', box_height);
          }
        }
      } // Private
      ;

      _proto._init = function _init() {
        var _this = this;

        // Activate layout height watcher
        this.fixLayoutHeight();

        if (this._config.loginRegisterAutoHeight === true) {
          this.fixLoginRegisterHeight();
        } else if (Number.isInteger(this._config.loginRegisterAutoHeight)) {
          setInterval(this.fixLoginRegisterHeight, this._config.loginRegisterAutoHeight);
        }

        $(Selector.SIDEBAR).on('collapsed.lte.treeview expanded.lte.treeview', function () {
          _this.fixLayoutHeight();
        });
        $(Selector.PUSHMENU_BTN).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
          _this.fixLayoutHeight();
        });
        $(Selector.CONTROL_SIDEBAR_BTN).on('collapsed.lte.controlsidebar', function () {
          _this.fixLayoutHeight();
        }).on('expanded.lte.controlsidebar', function () {
          _this.fixLayoutHeight('control_sidebar');
        });
        $(window).resize(function () {
          _this.fixLayoutHeight();
        });
        setTimeout(function () {
          $('body.hold-transition').removeClass('hold-transition');
        }, 50);
      };

      _proto._max = function _max(numbers) {
        // Calculate the maximum number in a list
        var max = 0;
        Object.keys(numbers).forEach(function (key) {
          if (numbers[key] > max) {
            max = numbers[key];
          }
        });
        return max;
      };

      _proto._isFooterFixed = function _isFooterFixed() {
        return $('.main-footer').css('position') === 'fixed';
      } // Static
      ;

      Layout._jQueryInterface = function _jQueryInterface(config) {
        if (config === void 0) {
          config = '';
        }

        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          var _options = $.extend({}, Default, $(this).data());

          if (!data) {
            data = new Layout($(this), _options);
            $(this).data(DATA_KEY, data);
          }

          if (config === 'init' || config === '') {
            data['_init']();
          } else if (config === 'fixLayoutHeight' || config === 'fixLoginRegisterHeight') {
            data[config]();
          }
        });
      };

      return Layout;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(window).on('load', function () {
      Layout._jQueryInterface.call($('body'));
    });
    $(Selector.SIDEBAR + ' a').on('focusin', function () {
      $(Selector.MAIN_SIDEBAR).addClass(ClassName.SIDEBAR_FOCUSED);
    });
    $(Selector.SIDEBAR + ' a').on('focusout', function () {
      $(Selector.MAIN_SIDEBAR).removeClass(ClassName.SIDEBAR_FOCUSED);
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = Layout._jQueryInterface;
    $.fn[NAME].Constructor = Layout;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return Layout._jQueryInterface;
    };

    return Layout;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE PushMenu.js
   * License MIT
   * --------------------------------------------
   */
  var PushMenu = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'PushMenu';
    var DATA_KEY = 'lte.pushmenu';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      COLLAPSED: "collapsed" + EVENT_KEY,
      SHOWN: "shown" + EVENT_KEY
    };
    var Default = {
      autoCollapseSize: 992,
      enableRemember: false,
      noTransitionAfterReload: true
    };
    var Selector = {
      TOGGLE_BUTTON: '[data-widget="pushmenu"]',
      SIDEBAR_MINI: '.sidebar-mini',
      SIDEBAR_COLLAPSED: '.sidebar-collapse',
      BODY: 'body',
      OVERLAY: '#sidebar-overlay',
      WRAPPER: '.wrapper'
    };
    var ClassName = {
      COLLAPSED: 'sidebar-collapse',
      OPEN: 'sidebar-open',
      CLOSED: 'sidebar-closed'
    };
    /**
     * Class Definition
     * ====================================================
     */

    var PushMenu = /*#__PURE__*/function () {
      function PushMenu(element, options) {
        this._element = element;
        this._options = $.extend({}, Default, options);

        if (!$(Selector.OVERLAY).length) {
          this._addOverlay();
        }

        this._init();
      } // Public


      var _proto = PushMenu.prototype;

      _proto.expand = function expand() {
        if (this._options.autoCollapseSize) {
          if ($(window).width() <= this._options.autoCollapseSize) {
            $(Selector.BODY).addClass(ClassName.OPEN);
          }
        }

        $(Selector.BODY).removeClass(ClassName.COLLAPSED).removeClass(ClassName.CLOSED);

        if (this._options.enableRemember) {
          localStorage.setItem("remember" + EVENT_KEY, ClassName.OPEN);
        }

        var shownEvent = $.Event(Event.SHOWN);
        $(this._element).trigger(shownEvent);
      };

      _proto.collapse = function collapse() {
        if (this._options.autoCollapseSize) {
          if ($(window).width() <= this._options.autoCollapseSize) {
            $(Selector.BODY).removeClass(ClassName.OPEN).addClass(ClassName.CLOSED);
          }
        }

        $(Selector.BODY).addClass(ClassName.COLLAPSED);

        if (this._options.enableRemember) {
          localStorage.setItem("remember" + EVENT_KEY, ClassName.COLLAPSED);
        }

        var collapsedEvent = $.Event(Event.COLLAPSED);
        $(this._element).trigger(collapsedEvent);
      };

      _proto.toggle = function toggle() {
        if (!$(Selector.BODY).hasClass(ClassName.COLLAPSED)) {
          this.collapse();
        } else {
          this.expand();
        }
      };

      _proto.autoCollapse = function autoCollapse(resize) {
        if (resize === void 0) {
          resize = false;
        }

        if (this._options.autoCollapseSize) {
          if ($(window).width() <= this._options.autoCollapseSize) {
            if (!$(Selector.BODY).hasClass(ClassName.OPEN)) {
              this.collapse();
            }
          } else if (resize == true) {
            if ($(Selector.BODY).hasClass(ClassName.OPEN)) {
              $(Selector.BODY).removeClass(ClassName.OPEN);
            } else if ($(Selector.BODY).hasClass(ClassName.CLOSED)) {
              this.expand();
            }
          }
        }
      };

      _proto.remember = function remember() {
        if (this._options.enableRemember) {
          var toggleState = localStorage.getItem("remember" + EVENT_KEY);

          if (toggleState == ClassName.COLLAPSED) {
            if (this._options.noTransitionAfterReload) {
              $("body").addClass('hold-transition').addClass(ClassName.COLLAPSED).delay(50).queue(function () {
                $(this).removeClass('hold-transition');
                $(this).dequeue();
              });
            } else {
              $("body").addClass(ClassName.COLLAPSED);
            }
          } else {
            if (this._options.noTransitionAfterReload) {
              $("body").addClass('hold-transition').removeClass(ClassName.COLLAPSED).delay(50).queue(function () {
                $(this).removeClass('hold-transition');
                $(this).dequeue();
              });
            } else {
              $("body").removeClass(ClassName.COLLAPSED);
            }
          }
        }
      } // Private
      ;

      _proto._init = function _init() {
        var _this = this;

        this.remember();
        this.autoCollapse();
        $(window).resize(function () {
          _this.autoCollapse(true);
        });
      };

      _proto._addOverlay = function _addOverlay() {
        var _this2 = this;

        var overlay = $('<div />', {
          id: 'sidebar-overlay'
        });
        overlay.on('click', function () {
          _this2.collapse();
        });
        $(Selector.WRAPPER).append(overlay);
      } // Static
      ;

      PushMenu._jQueryInterface = function _jQueryInterface(operation) {
        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          var _options = $.extend({}, Default, $(this).data());

          if (!data) {
            data = new PushMenu(this, _options);
            $(this).data(DATA_KEY, data);
          }

          if (typeof operation === 'string' && operation.match(/collapse|expand|toggle/)) {
            data[operation]();
          }
        });
      };

      return PushMenu;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(document).on('click', Selector.TOGGLE_BUTTON, function (event) {
      event.preventDefault();
      var button = event.currentTarget;

      if ($(button).data('widget') !== 'pushmenu') {
        button = $(button).closest(Selector.TOGGLE_BUTTON);
      }

      PushMenu._jQueryInterface.call($(button), 'toggle');
    });
    $(window).on('load', function () {
      PushMenu._jQueryInterface.call($(Selector.TOGGLE_BUTTON));
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = PushMenu._jQueryInterface;
    $.fn[NAME].Constructor = PushMenu;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return PushMenu._jQueryInterface;
    };

    return PushMenu;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE Treeview.js
   * License MIT
   * --------------------------------------------
   */
  var Treeview = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'Treeview';
    var DATA_KEY = 'lte.treeview';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      SELECTED: "selected" + EVENT_KEY,
      EXPANDED: "expanded" + EVENT_KEY,
      COLLAPSED: "collapsed" + EVENT_KEY,
      LOAD_DATA_API: "load" + EVENT_KEY
    };
    var Selector = {
      LI: '.nav-item',
      LINK: '.nav-link',
      TREEVIEW_MENU: '.nav-treeview',
      OPEN: '.menu-open',
      DATA_WIDGET: '[data-widget="treeview"]'
    };
    var ClassName = {
      LI: 'nav-item',
      LINK: 'nav-link',
      TREEVIEW_MENU: 'nav-treeview',
      OPEN: 'menu-open',
      SIDEBAR_COLLAPSED: 'sidebar-collapse'
    };
    var Default = {
      trigger: Selector.DATA_WIDGET + " " + Selector.LINK,
      animationSpeed: 300,
      accordion: true,
      expandSidebar: false,
      sidebarButtonSelector: '[data-widget="pushmenu"]'
    };
    /**
     * Class Definition
     * ====================================================
     */

    var Treeview = /*#__PURE__*/function () {
      function Treeview(element, config) {
        this._config = config;
        this._element = element;
      } // Public


      var _proto = Treeview.prototype;

      _proto.init = function init() {
        this._setupListeners();
      };

      _proto.expand = function expand(treeviewMenu, parentLi) {
        var _this = this;

        var expandedEvent = $.Event(Event.EXPANDED);

        if (this._config.accordion) {
          var openMenuLi = parentLi.siblings(Selector.OPEN).first();
          var openTreeview = openMenuLi.find(Selector.TREEVIEW_MENU).first();
          this.collapse(openTreeview, openMenuLi);
        }

        treeviewMenu.stop().slideDown(this._config.animationSpeed, function () {
          parentLi.addClass(ClassName.OPEN);
          $(_this._element).trigger(expandedEvent);
        });

        if (this._config.expandSidebar) {
          this._expandSidebar();
        }
      };

      _proto.collapse = function collapse(treeviewMenu, parentLi) {
        var _this2 = this;

        var collapsedEvent = $.Event(Event.COLLAPSED);
        treeviewMenu.stop().slideUp(this._config.animationSpeed, function () {
          parentLi.removeClass(ClassName.OPEN);
          $(_this2._element).trigger(collapsedEvent);
          treeviewMenu.find(Selector.OPEN + " > " + Selector.TREEVIEW_MENU).slideUp();
          treeviewMenu.find(Selector.OPEN).removeClass(ClassName.OPEN);
        });
      };

      _proto.toggle = function toggle(event) {
        var $relativeTarget = $(event.currentTarget);
        var $parent = $relativeTarget.parent();
        var treeviewMenu = $parent.find('> ' + Selector.TREEVIEW_MENU);

        if (!treeviewMenu.is(Selector.TREEVIEW_MENU)) {
          if (!$parent.is(Selector.LI)) {
            treeviewMenu = $parent.parent().find('> ' + Selector.TREEVIEW_MENU);
          }

          if (!treeviewMenu.is(Selector.TREEVIEW_MENU)) {
            return;
          }
        }

        event.preventDefault();
        var parentLi = $relativeTarget.parents(Selector.LI).first();
        var isOpen = parentLi.hasClass(ClassName.OPEN);

        if (isOpen) {
          this.collapse($(treeviewMenu), parentLi);
        } else {
          this.expand($(treeviewMenu), parentLi);
        }
      } // Private
      ;

      _proto._setupListeners = function _setupListeners() {
        var _this3 = this;

        $(document).on('click', this._config.trigger, function (event) {
          _this3.toggle(event);
        });
      };

      _proto._expandSidebar = function _expandSidebar() {
        if ($('body').hasClass(ClassName.SIDEBAR_COLLAPSED)) {
          $(this._config.sidebarButtonSelector).PushMenu('expand');
        }
      } // Static
      ;

      Treeview._jQueryInterface = function _jQueryInterface(config) {
        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          var _options = $.extend({}, Default, $(this).data());

          if (!data) {
            data = new Treeview($(this), _options);
            $(this).data(DATA_KEY, data);
          }

          if (config === 'init') {
            data[config]();
          }
        });
      };

      return Treeview;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(window).on(Event.LOAD_DATA_API, function () {
      $(Selector.DATA_WIDGET).each(function () {
        Treeview._jQueryInterface.call($(this), 'init');
      });
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = Treeview._jQueryInterface;
    $.fn[NAME].Constructor = Treeview;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return Treeview._jQueryInterface;
    };

    return Treeview;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE DirectChat.js
   * License MIT
   * --------------------------------------------
   */
  var DirectChat = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'DirectChat';
    var DATA_KEY = 'lte.directchat';
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      TOGGLED: "toggled{EVENT_KEY}"
    };
    var Selector = {
      DATA_TOGGLE: '[data-widget="chat-pane-toggle"]',
      DIRECT_CHAT: '.direct-chat'
    };
    var ClassName = {
      DIRECT_CHAT_OPEN: 'direct-chat-contacts-open'
    };
    /**
     * Class Definition
     * ====================================================
     */

    var DirectChat = /*#__PURE__*/function () {
      function DirectChat(element, config) {
        this._element = element;
      }

      var _proto = DirectChat.prototype;

      _proto.toggle = function toggle() {
        $(this._element).parents(Selector.DIRECT_CHAT).first().toggleClass(ClassName.DIRECT_CHAT_OPEN);
        var toggledEvent = $.Event(Event.TOGGLED);
        $(this._element).trigger(toggledEvent);
      } // Static
      ;

      DirectChat._jQueryInterface = function _jQueryInterface(config) {
        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          if (!data) {
            data = new DirectChat($(this));
            $(this).data(DATA_KEY, data);
          }

          data[config]();
        });
      };

      return DirectChat;
    }();
    /**
     *
     * Data Api implementation
     * ====================================================
     */


    $(document).on('click', Selector.DATA_TOGGLE, function (event) {
      if (event) event.preventDefault();

      DirectChat._jQueryInterface.call($(this), 'toggle');
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = DirectChat._jQueryInterface;
    $.fn[NAME].Constructor = DirectChat;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return DirectChat._jQueryInterface;
    };

    return DirectChat;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE TodoList.js
   * License MIT
   * --------------------------------------------
   */
  var TodoList = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'TodoList';
    var DATA_KEY = 'lte.todolist';
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Selector = {
      DATA_TOGGLE: '[data-widget="todo-list"]'
    };
    var ClassName = {
      TODO_LIST_DONE: 'done'
    };
    var Default = {
      onCheck: function onCheck(item) {
        return item;
      },
      onUnCheck: function onUnCheck(item) {
        return item;
      }
    };
    /**
     * Class Definition
     * ====================================================
     */

    var TodoList = /*#__PURE__*/function () {
      function TodoList(element, config) {
        this._config = config;
        this._element = element;

        this._init();
      } // Public


      var _proto = TodoList.prototype;

      _proto.toggle = function toggle(item) {
        item.parents('li').toggleClass(ClassName.TODO_LIST_DONE);

        if (!$(item).prop('checked')) {
          this.unCheck($(item));
          return;
        }

        this.check(item);
      };

      _proto.check = function check(item) {
        this._config.onCheck.call(item);
      };

      _proto.unCheck = function unCheck(item) {
        this._config.onUnCheck.call(item);
      } // Private
      ;

      _proto._init = function _init() {
        var that = this;
        $(Selector.DATA_TOGGLE).find('input:checkbox:checked').parents('li').toggleClass(ClassName.TODO_LIST_DONE);
        $(Selector.DATA_TOGGLE).on('change', 'input:checkbox', function (event) {
          that.toggle($(event.target));
        });
      } // Static
      ;

      TodoList._jQueryInterface = function _jQueryInterface(config) {
        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          var _options = $.extend({}, Default, $(this).data());

          if (!data) {
            data = new TodoList($(this), _options);
            $(this).data(DATA_KEY, data);
          }

          if (config === 'init') {
            data[config]();
          }
        });
      };

      return TodoList;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(window).on('load', function () {
      TodoList._jQueryInterface.call($(Selector.DATA_TOGGLE));
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = TodoList._jQueryInterface;
    $.fn[NAME].Constructor = TodoList;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return TodoList._jQueryInterface;
    };

    return TodoList;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE CardWidget.js
   * License MIT
   * --------------------------------------------
   */
  var CardWidget = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'CardWidget';
    var DATA_KEY = 'lte.cardwidget';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      EXPANDED: "expanded" + EVENT_KEY,
      COLLAPSED: "collapsed" + EVENT_KEY,
      MAXIMIZED: "maximized" + EVENT_KEY,
      MINIMIZED: "minimized" + EVENT_KEY,
      REMOVED: "removed" + EVENT_KEY
    };
    var ClassName = {
      CARD: 'card',
      COLLAPSED: 'collapsed-card',
      COLLAPSING: 'collapsing-card',
      EXPANDING: 'expanding-card',
      WAS_COLLAPSED: 'was-collapsed',
      MAXIMIZED: 'maximized-card'
    };
    var Selector = {
      DATA_REMOVE: '[data-card-widget="remove"]',
      DATA_COLLAPSE: '[data-card-widget="collapse"]',
      DATA_MAXIMIZE: '[data-card-widget="maximize"]',
      CARD: "." + ClassName.CARD,
      CARD_HEADER: '.card-header',
      CARD_BODY: '.card-body',
      CARD_FOOTER: '.card-footer',
      COLLAPSED: "." + ClassName.COLLAPSED
    };
    var Default = {
      animationSpeed: 'normal',
      collapseTrigger: Selector.DATA_COLLAPSE,
      removeTrigger: Selector.DATA_REMOVE,
      maximizeTrigger: Selector.DATA_MAXIMIZE,
      collapseIcon: 'fa-minus',
      expandIcon: 'fa-plus',
      maximizeIcon: 'fa-expand',
      minimizeIcon: 'fa-compress'
    };

    var CardWidget = /*#__PURE__*/function () {
      function CardWidget(element, settings) {
        this._element = element;
        this._parent = element.parents(Selector.CARD).first();

        if (element.hasClass(ClassName.CARD)) {
          this._parent = element;
        }

        this._settings = $.extend({}, Default, settings);
      }

      var _proto = CardWidget.prototype;

      _proto.collapse = function collapse() {
        var _this = this;

        this._parent.addClass(ClassName.COLLAPSING).children(Selector.CARD_BODY + ", " + Selector.CARD_FOOTER).slideUp(this._settings.animationSpeed, function () {
          _this._parent.addClass(ClassName.COLLAPSED).removeClass(ClassName.COLLAPSING);
        });

        this._parent.find('> ' + Selector.CARD_HEADER + ' ' + this._settings.collapseTrigger + ' .' + this._settings.collapseIcon).addClass(this._settings.expandIcon).removeClass(this._settings.collapseIcon);

        var collapsed = $.Event(Event.COLLAPSED);

        this._element.trigger(collapsed, this._parent);
      };

      _proto.expand = function expand() {
        var _this2 = this;

        this._parent.addClass(ClassName.EXPANDING).children(Selector.CARD_BODY + ", " + Selector.CARD_FOOTER).slideDown(this._settings.animationSpeed, function () {
          _this2._parent.removeClass(ClassName.COLLAPSED).removeClass(ClassName.EXPANDING);
        });

        this._parent.find('> ' + Selector.CARD_HEADER + ' ' + this._settings.collapseTrigger + ' .' + this._settings.expandIcon).addClass(this._settings.collapseIcon).removeClass(this._settings.expandIcon);

        var expanded = $.Event(Event.EXPANDED);

        this._element.trigger(expanded, this._parent);
      };

      _proto.remove = function remove() {
        this._parent.slideUp();

        var removed = $.Event(Event.REMOVED);

        this._element.trigger(removed, this._parent);
      };

      _proto.toggle = function toggle() {
        if (this._parent.hasClass(ClassName.COLLAPSED)) {
          this.expand();
          return;
        }

        this.collapse();
      };

      _proto.maximize = function maximize() {
        this._parent.find(this._settings.maximizeTrigger + ' .' + this._settings.maximizeIcon).addClass(this._settings.minimizeIcon).removeClass(this._settings.maximizeIcon);

        this._parent.css({
          'height': this._parent.height(),
          'width': this._parent.width(),
          'transition': 'all .15s'
        }).delay(150).queue(function () {
          $(this).addClass(ClassName.MAXIMIZED);
          $('html').addClass(ClassName.MAXIMIZED);

          if ($(this).hasClass(ClassName.COLLAPSED)) {
            $(this).addClass(ClassName.WAS_COLLAPSED);
          }

          $(this).dequeue();
        });

        var maximized = $.Event(Event.MAXIMIZED);

        this._element.trigger(maximized, this._parent);
      };

      _proto.minimize = function minimize() {
        this._parent.find(this._settings.maximizeTrigger + ' .' + this._settings.minimizeIcon).addClass(this._settings.maximizeIcon).removeClass(this._settings.minimizeIcon);

        this._parent.css('cssText', 'height:' + this._parent[0].style.height + ' !important;' + 'width:' + this._parent[0].style.width + ' !important; transition: all .15s;').delay(10).queue(function () {
          $(this).removeClass(ClassName.MAXIMIZED);
          $('html').removeClass(ClassName.MAXIMIZED);
          $(this).css({
            'height': 'inherit',
            'width': 'inherit'
          });

          if ($(this).hasClass(ClassName.WAS_COLLAPSED)) {
            $(this).removeClass(ClassName.WAS_COLLAPSED);
          }

          $(this).dequeue();
        });

        var MINIMIZED = $.Event(Event.MINIMIZED);

        this._element.trigger(MINIMIZED, this._parent);
      };

      _proto.toggleMaximize = function toggleMaximize() {
        if (this._parent.hasClass(ClassName.MAXIMIZED)) {
          this.minimize();
          return;
        }

        this.maximize();
      } // Private
      ;

      _proto._init = function _init(card) {
        var _this3 = this;

        this._parent = card;
        $(this).find(this._settings.collapseTrigger).click(function () {
          _this3.toggle();
        });
        $(this).find(this._settings.maximizeTrigger).click(function () {
          _this3.toggleMaximize();
        });
        $(this).find(this._settings.removeTrigger).click(function () {
          _this3.remove();
        });
      } // Static
      ;

      CardWidget._jQueryInterface = function _jQueryInterface(config) {
        var data = $(this).data(DATA_KEY);

        var _options = $.extend({}, Default, $(this).data());

        if (!data) {
          data = new CardWidget($(this), _options);
          $(this).data(DATA_KEY, typeof config === 'string' ? data : config);
        }

        if (typeof config === 'string' && config.match(/collapse|expand|remove|toggle|maximize|minimize|toggleMaximize/)) {
          data[config]();
        } else if (typeof config === 'object') {
          data._init($(this));
        }
      };

      return CardWidget;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(document).on('click', Selector.DATA_COLLAPSE, function (event) {
      if (event) {
        event.preventDefault();
      }

      CardWidget._jQueryInterface.call($(this), 'toggle');
    });
    $(document).on('click', Selector.DATA_REMOVE, function (event) {
      if (event) {
        event.preventDefault();
      }

      CardWidget._jQueryInterface.call($(this), 'remove');
    });
    $(document).on('click', Selector.DATA_MAXIMIZE, function (event) {
      if (event) {
        event.preventDefault();
      }

      CardWidget._jQueryInterface.call($(this), 'toggleMaximize');
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = CardWidget._jQueryInterface;
    $.fn[NAME].Constructor = CardWidget;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return CardWidget._jQueryInterface;
    };

    return CardWidget;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE CardRefresh.js
   * License MIT
   * --------------------------------------------
   */
  var CardRefresh = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'CardRefresh';
    var DATA_KEY = 'lte.cardrefresh';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      LOADED: "loaded" + EVENT_KEY,
      OVERLAY_ADDED: "overlay.added" + EVENT_KEY,
      OVERLAY_REMOVED: "overlay.removed" + EVENT_KEY
    };
    var ClassName = {
      CARD: 'card'
    };
    var Selector = {
      CARD: "." + ClassName.CARD,
      DATA_REFRESH: '[data-card-widget="card-refresh"]'
    };
    var Default = {
      source: '',
      sourceSelector: '',
      params: {},
      trigger: Selector.DATA_REFRESH,
      content: '.card-body',
      loadInContent: true,
      loadOnInit: true,
      responseType: '',
      overlayTemplate: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
      onLoadStart: function onLoadStart() {},
      onLoadDone: function onLoadDone(response) {
        return response;
      }
    };

    var CardRefresh = /*#__PURE__*/function () {
      function CardRefresh(element, settings) {
        this._element = element;
        this._parent = element.parents(Selector.CARD).first();
        this._settings = $.extend({}, Default, settings);
        this._overlay = $(this._settings.overlayTemplate);

        if (element.hasClass(ClassName.CARD)) {
          this._parent = element;
        }

        if (this._settings.source === '') {
          throw new Error('Source url was not defined. Please specify a url in your CardRefresh source option.');
        }
      }

      var _proto = CardRefresh.prototype;

      _proto.load = function load() {
        this._addOverlay();

        this._settings.onLoadStart.call($(this));

        $.get(this._settings.source, this._settings.params, function (response) {
          if (this._settings.loadInContent) {
            if (this._settings.sourceSelector != '') {
              response = $(response).find(this._settings.sourceSelector).html();
            }

            this._parent.find(this._settings.content).html(response);
          }

          this._settings.onLoadDone.call($(this), response);

          this._removeOverlay();
        }.bind(this), this._settings.responseType !== '' && this._settings.responseType);
        var loadedEvent = $.Event(Event.LOADED);
        $(this._element).trigger(loadedEvent);
      };

      _proto._addOverlay = function _addOverlay() {
        this._parent.append(this._overlay);

        var overlayAddedEvent = $.Event(Event.OVERLAY_ADDED);
        $(this._element).trigger(overlayAddedEvent);
      };

      _proto._removeOverlay = function _removeOverlay() {
        this._parent.find(this._overlay).remove();

        var overlayRemovedEvent = $.Event(Event.OVERLAY_REMOVED);
        $(this._element).trigger(overlayRemovedEvent);
      };

      // Private
      _proto._init = function _init(card) {
        var _this = this;

        $(this).find(this._settings.trigger).on('click', function () {
          _this.load();
        });

        if (this._settings.loadOnInit) {
          this.load();
        }
      } // Static
      ;

      CardRefresh._jQueryInterface = function _jQueryInterface(config) {
        var data = $(this).data(DATA_KEY);

        var _options = $.extend({}, Default, $(this).data());

        if (!data) {
          data = new CardRefresh($(this), _options);
          $(this).data(DATA_KEY, typeof config === 'string' ? data : config);
        }

        if (typeof config === 'string' && config.match(/load/)) {
          data[config]();
        } else {
          data._init($(this));
        }
      };

      return CardRefresh;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(document).on('click', Selector.DATA_REFRESH, function (event) {
      if (event) {
        event.preventDefault();
      }

      CardRefresh._jQueryInterface.call($(this), 'load');
    });
    $(document).ready(function () {
      $(Selector.DATA_REFRESH).each(function () {
        CardRefresh._jQueryInterface.call($(this));
      });
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = CardRefresh._jQueryInterface;
    $.fn[NAME].Constructor = CardRefresh;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return CardRefresh._jQueryInterface;
    };

    return CardRefresh;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE Dropdown.js
   * License MIT
   * --------------------------------------------
   */
  var Dropdown = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'Dropdown';
    var DATA_KEY = 'lte.dropdown';
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Selector = {
      NAVBAR: '.navbar',
      DROPDOWN_MENU: '.dropdown-menu',
      DROPDOWN_MENU_ACTIVE: '.dropdown-menu.show',
      DROPDOWN_TOGGLE: '[data-toggle="dropdown"]'
    };
    var ClassName = {
      DROPDOWN_HOVER: 'dropdown-hover',
      DROPDOWN_RIGHT: 'dropdown-menu-right'
    };
    var Default = {};
    /**
     * Class Definition
     * ====================================================
     */

    var Dropdown = /*#__PURE__*/function () {
      function Dropdown(element, config) {
        this._config = config;
        this._element = element;
      } // Public


      var _proto = Dropdown.prototype;

      _proto.toggleSubmenu = function toggleSubmenu() {
        this._element.siblings().show().toggleClass("show");

        if (!this._element.next().hasClass('show')) {
          this._element.parents('.dropdown-menu').first().find('.show').removeClass("show").hide();
        }

        this._element.parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
          $('.dropdown-submenu .show').removeClass("show").hide();
        });
      };

      _proto.fixPosition = function fixPosition() {
        var elm = $(Selector.DROPDOWN_MENU_ACTIVE);

        if (elm.length !== 0) {
          if (elm.hasClass(ClassName.DROPDOWN_RIGHT)) {
            elm.css('left', 'inherit');
            elm.css('right', 0);
          } else {
            elm.css('left', 0);
            elm.css('right', 'inherit');
          }

          var offset = elm.offset();
          var width = elm.width();
          var windowWidth = $(window).width();
          var visiblePart = windowWidth - offset.left;

          if (offset.left < 0) {
            elm.css('left', 'inherit');
            elm.css('right', offset.left - 5);
          } else {
            if (visiblePart < width) {
              elm.css('left', 'inherit');
              elm.css('right', 0);
            }
          }
        }
      } // Static
      ;

      Dropdown._jQueryInterface = function _jQueryInterface(config) {
        return this.each(function () {
          var data = $(this).data(DATA_KEY);

          var _config = $.extend({}, Default, $(this).data());

          if (!data) {
            data = new Dropdown($(this), _config);
            $(this).data(DATA_KEY, data);
          }

          if (config === 'toggleSubmenu' || config == 'fixPosition') {
            data[config]();
          }
        });
      };

      return Dropdown;
    }();
    /**
     * Data API
     * ====================================================
     */


    $(Selector.DROPDOWN_MENU + ' ' + Selector.DROPDOWN_TOGGLE).on("click", function (event) {
      event.preventDefault();
      event.stopPropagation();

      Dropdown._jQueryInterface.call($(this), 'toggleSubmenu');
    });
    $(Selector.NAVBAR + ' ' + Selector.DROPDOWN_TOGGLE).on("click", function (event) {
      event.preventDefault();
      setTimeout(function () {
        Dropdown._jQueryInterface.call($(this), 'fixPosition');
      }, 1);
    });
    /**
     * jQuery API
     * ====================================================
     */

    $.fn[NAME] = Dropdown._jQueryInterface;
    $.fn[NAME].Constructor = Dropdown;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return Dropdown._jQueryInterface;
    };

    return Dropdown;
  }(jQuery);

  /**
   * --------------------------------------------
   * AdminLTE Toasts.js
   * License MIT
   * --------------------------------------------
   */
  var Toasts = function ($) {
    /**
     * Constants
     * ====================================================
     */
    var NAME = 'Toasts';
    var DATA_KEY = 'lte.toasts';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $.fn[NAME];
    var Event = {
      INIT: "init" + EVENT_KEY,
      CREATED: "created" + EVENT_KEY,
      REMOVED: "removed" + EVENT_KEY
    };
    var Selector = {
      BODY: 'toast-body',
      CONTAINER_TOP_RIGHT: '#toastsContainerTopRight',
      CONTAINER_TOP_LEFT: '#toastsContainerTopLeft',
      CONTAINER_BOTTOM_RIGHT: '#toastsContainerBottomRight',
      CONTAINER_BOTTOM_LEFT: '#toastsContainerBottomLeft'
    };
    var ClassName = {
      TOP_RIGHT: 'toasts-top-right',
      TOP_LEFT: 'toasts-top-left',
      BOTTOM_RIGHT: 'toasts-bottom-right',
      BOTTOM_LEFT: 'toasts-bottom-left',
      FADE: 'fade'
    };
    var Position = {
      TOP_RIGHT: 'topRight',
      TOP_LEFT: 'topLeft',
      BOTTOM_RIGHT: 'bottomRight',
      BOTTOM_LEFT: 'bottomLeft'
    };
    var Default = {
      position: Position.TOP_RIGHT,
      fixed: true,
      autohide: false,
      autoremove: true,
      delay: 1000,
      fade: true,
      icon: null,
      image: null,
      imageAlt: null,
      imageHeight: '25px',
      title: null,
      subtitle: null,
      close: true,
      body: null,
      class: null
    };
    /**
     * Class Definition
     * ====================================================
     */

    var Toasts = /*#__PURE__*/function () {
      function Toasts(element, config) {
        this._config = config;

        this._prepareContainer();

        var initEvent = $.Event(Event.INIT);
        $('body').trigger(initEvent);
      } // Public


      var _proto = Toasts.prototype;

      _proto.create = function create() {
        var toast = $('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"/>');
        toast.data('autohide', this._config.autohide);
        toast.data('animation', this._config.fade);

        if (this._config.class) {
          toast.addClass(this._config.class);
        }

        if (this._config.delay && this._config.delay != 500) {
          toast.data('delay', this._config.delay);
        }

        var toast_header = $('<div class="toast-header">');

        if (this._config.image != null) {
          var toast_image = $('<img />').addClass('rounded mr-2').attr('src', this._config.image).attr('alt', this._config.imageAlt);

          if (this._config.imageHeight != null) {
            toast_image.height(this._config.imageHeight).width('auto');
          }

          toast_header.append(toast_image);
        }

        if (this._config.icon != null) {
          toast_header.append($('<i />').addClass('mr-2').addClass(this._config.icon));
        }

        if (this._config.title != null) {
          toast_header.append($('<strong />').addClass('mr-auto').html(this._config.title));
        }

        if (this._config.subtitle != null) {
          toast_header.append($('<small />').html(this._config.subtitle));
        }

        if (this._config.close == true) {
          var toast_close = $('<button data-dismiss="toast" />').attr('type', 'button').addClass('ml-2 mb-1 close').attr('aria-label', 'Close').append('<span aria-hidden="true">&times;</span>');

          if (this._config.title == null) {
            toast_close.toggleClass('ml-2 ml-auto');
          }

          toast_header.append(toast_close);
        }

        toast.append(toast_header);

        if (this._config.body != null) {
          toast.append($('<div class="toast-body" />').html(this._config.body));
        }

        $(this._getContainerId()).prepend(toast);
        var createdEvent = $.Event(Event.CREATED);
        $('body').trigger(createdEvent);
        toast.toast('show');

        if (this._config.autoremove) {
          toast.on('hidden.bs.toast', function () {
            $(this).delay(200).remove();
            var removedEvent = $.Event(Event.REMOVED);
            $('body').trigger(removedEvent);
          });
        }
      } // Static
      ;

      _proto._getContainerId = function _getContainerId() {
        if (this._config.position == Position.TOP_RIGHT) {
          return Selector.CONTAINER_TOP_RIGHT;
        } else if (this._config.position == Position.TOP_LEFT) {
          return Selector.CONTAINER_TOP_LEFT;
        } else if (this._config.position == Position.BOTTOM_RIGHT) {
          return Selector.CONTAINER_BOTTOM_RIGHT;
        } else if (this._config.position == Position.BOTTOM_LEFT) {
          return Selector.CONTAINER_BOTTOM_LEFT;
        }
      };

      _proto._prepareContainer = function _prepareContainer() {
        if ($(this._getContainerId()).length === 0) {
          var container = $('<div />').attr('id', this._getContainerId().replace('#', ''));

          if (this._config.position == Position.TOP_RIGHT) {
            container.addClass(ClassName.TOP_RIGHT);
          } else if (this._config.position == Position.TOP_LEFT) {
            container.addClass(ClassName.TOP_LEFT);
          } else if (this._config.position == Position.BOTTOM_RIGHT) {
            container.addClass(ClassName.BOTTOM_RIGHT);
          } else if (this._config.position == Position.BOTTOM_LEFT) {
            container.addClass(ClassName.BOTTOM_LEFT);
          }

          $('body').append(container);
        }

        if (this._config.fixed) {
          $(this._getContainerId()).addClass('fixed');
        } else {
          $(this._getContainerId()).removeClass('fixed');
        }
      } // Static
      ;

      Toasts._jQueryInterface = function _jQueryInterface(option, config) {
        return this.each(function () {
          var _options = $.extend({}, Default, config);

          var toast = new Toasts($(this), _options);

          if (option === 'create') {
            toast[option]();
          }
        });
      };

      return Toasts;
    }();
    /**
     * jQuery API
     * ====================================================
     */


    $.fn[NAME] = Toasts._jQueryInterface;
    $.fn[NAME].Constructor = Toasts;

    $.fn[NAME].noConflict = function () {
      $.fn[NAME] = JQUERY_NO_CONFLICT;
      return Toasts._jQueryInterface;
    };

    return Toasts;
  }(jQuery);

  exports.CardRefresh = CardRefresh;
  exports.CardWidget = CardWidget;
  exports.ControlSidebar = ControlSidebar;
  exports.DirectChat = DirectChat;
  exports.Dropdown = Dropdown;
  exports.Layout = Layout;
  exports.PushMenu = PushMenu;
  exports.Toasts = Toasts;
  exports.TodoList = TodoList;
  exports.Treeview = Treeview;

  Object.defineProperty(exports, '__esModule', { value: true });

})));
//# sourceMappingURL=adminlte.js.map
