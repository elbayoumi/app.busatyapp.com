const Ct=()=>{};var ge={};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const je=function(e){const t=[];let n=0;for(let r=0;r<e.length;r++){let i=e.charCodeAt(r);i<128?t[n++]=i:i<2048?(t[n++]=i>>6|192,t[n++]=i&63|128):(i&64512)===55296&&r+1<e.length&&(e.charCodeAt(r+1)&64512)===56320?(i=65536+((i&1023)<<10)+(e.charCodeAt(++r)&1023),t[n++]=i>>18|240,t[n++]=i>>12&63|128,t[n++]=i>>6&63|128,t[n++]=i&63|128):(t[n++]=i>>12|224,t[n++]=i>>6&63|128,t[n++]=i&63|128)}return t},kt=function(e){const t=[];let n=0,r=0;for(;n<e.length;){const i=e[n++];if(i<128)t[r++]=String.fromCharCode(i);else if(i>191&&i<224){const a=e[n++];t[r++]=String.fromCharCode((i&31)<<6|a&63)}else if(i>239&&i<365){const a=e[n++],s=e[n++],c=e[n++],l=((i&7)<<18|(a&63)<<12|(s&63)<<6|c&63)-65536;t[r++]=String.fromCharCode(55296+(l>>10)),t[r++]=String.fromCharCode(56320+(l&1023))}else{const a=e[n++],s=e[n++];t[r++]=String.fromCharCode((i&15)<<12|(a&63)<<6|s&63)}}return t.join("")},Ve={byteToCharMap_:null,charToByteMap_:null,byteToCharMapWebSafe_:null,charToByteMapWebSafe_:null,ENCODED_VALS_BASE:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",get ENCODED_VALS(){return this.ENCODED_VALS_BASE+"+/="},get ENCODED_VALS_WEBSAFE(){return this.ENCODED_VALS_BASE+"-_."},HAS_NATIVE_SUPPORT:typeof atob=="function",encodeByteArray(e,t){if(!Array.isArray(e))throw Error("encodeByteArray takes an array as a parameter");this.init_();const n=t?this.byteToCharMapWebSafe_:this.byteToCharMap_,r=[];for(let i=0;i<e.length;i+=3){const a=e[i],s=i+1<e.length,c=s?e[i+1]:0,l=i+2<e.length,o=l?e[i+2]:0,h=a>>2,u=(a&3)<<4|c>>4;let p=(c&15)<<2|o>>6,B=o&63;l||(B=64,s||(p=64)),r.push(n[h],n[u],n[p],n[B])}return r.join("")},encodeString(e,t){return this.HAS_NATIVE_SUPPORT&&!t?btoa(e):this.encodeByteArray(je(e),t)},decodeString(e,t){return this.HAS_NATIVE_SUPPORT&&!t?atob(e):kt(this.decodeStringToByteArray(e,t))},decodeStringToByteArray(e,t){this.init_();const n=t?this.charToByteMapWebSafe_:this.charToByteMap_,r=[];for(let i=0;i<e.length;){const a=n[e.charAt(i++)],c=i<e.length?n[e.charAt(i)]:0;++i;const o=i<e.length?n[e.charAt(i)]:64;++i;const u=i<e.length?n[e.charAt(i)]:64;if(++i,a==null||c==null||o==null||u==null)throw new Ot;const p=a<<2|c>>4;if(r.push(p),o!==64){const B=c<<4&240|o>>2;if(r.push(B),u!==64){const Dt=o<<6&192|u;r.push(Dt)}}}return r},init_(){if(!this.byteToCharMap_){this.byteToCharMap_={},this.charToByteMap_={},this.byteToCharMapWebSafe_={},this.charToByteMapWebSafe_={};for(let e=0;e<this.ENCODED_VALS.length;e++)this.byteToCharMap_[e]=this.ENCODED_VALS.charAt(e),this.charToByteMap_[this.byteToCharMap_[e]]=e,this.byteToCharMapWebSafe_[e]=this.ENCODED_VALS_WEBSAFE.charAt(e),this.charToByteMapWebSafe_[this.byteToCharMapWebSafe_[e]]=e,e>=this.ENCODED_VALS_BASE.length&&(this.charToByteMap_[this.ENCODED_VALS_WEBSAFE.charAt(e)]=e,this.charToByteMapWebSafe_[this.ENCODED_VALS.charAt(e)]=e)}}};class Ot extends Error{constructor(){super(...arguments),this.name="DecodeBase64StringError"}}const Mt=function(e){const t=je(e);return Ve.encodeByteArray(t,!0)},Ue=function(e){return Mt(e).replace(/\./g,"")},Rt=function(e){try{return Ve.decodeString(e,!0)}catch(t){console.error("base64Decode failed: ",t)}return null};/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Nt(){if(typeof self<"u")return self;if(typeof window<"u")return window;if(typeof global<"u")return global;throw new Error("Unable to locate global object.")}/**
 * @license
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Bt=()=>Nt().__FIREBASE_DEFAULTS__,$t=()=>{if(typeof process>"u"||typeof ge>"u")return;const e=ge.__FIREBASE_DEFAULTS__;if(e)return JSON.parse(e)},Pt=()=>{if(typeof document>"u")return;let e;try{e=document.cookie.match(/__FIREBASE_DEFAULTS__=([^;]+)/)}catch{return}const t=e&&Rt(e[1]);return t&&JSON.parse(t)},Ft=()=>{try{return Ct()||Bt()||$t()||Pt()}catch(e){console.info(`Unable to get __FIREBASE_DEFAULTS__ due to: ${e}`);return}},Ke=()=>{var e;return(e=Ft())==null?void 0:e.config};/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Lt{constructor(){this.reject=()=>{},this.resolve=()=>{},this.promise=new Promise((t,n)=>{this.resolve=t,this.reject=n})}wrapCallback(t){return(n,r)=>{n?this.reject(n):this.resolve(r),typeof t=="function"&&(this.promise.catch(()=>{}),t.length===1?t(n):t(n,r))}}}function xt(){const e=typeof chrome=="object"?chrome.runtime:typeof browser=="object"?browser.runtime:void 0;return typeof e=="object"&&e.id!==void 0}function We(){try{return typeof indexedDB=="object"}catch{return!1}}function ze(){return new Promise((e,t)=>{try{let n=!0;const r="validate-browser-context-for-indexeddb-analytics-module",i=self.indexedDB.open(r);i.onsuccess=()=>{i.result.close(),n||self.indexedDB.deleteDatabase(r),e(!0)},i.onupgradeneeded=()=>{n=!1},i.onerror=()=>{var a;t(((a=i.error)==null?void 0:a.message)||"")}}catch(n){t(n)}})}function Ht(){return!(typeof navigator>"u"||!navigator.cookieEnabled)}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const jt="FirebaseError";class C extends Error{constructor(t,n,r){super(n),this.code=t,this.customData=r,this.name=jt,Object.setPrototypeOf(this,C.prototype),Error.captureStackTrace&&Error.captureStackTrace(this,N.prototype.create)}}class N{constructor(t,n,r){this.service=t,this.serviceName=n,this.errors=r}create(t,...n){const r=n[0]||{},i=`${this.service}/${t}`,a=this.errors[t],s=a?Vt(a,r):"Error",c=`${this.serviceName}: ${s} (${i}).`;return new C(i,c,r)}}function Vt(e,t){return e.replace(Ut,(n,r)=>{const i=t[r];return i!=null?String(i):`<${r}?>`})}const Ut=/\{\$([^}]+)}/g;function $(e,t){if(e===t)return!0;const n=Object.keys(e),r=Object.keys(t);for(const i of n){if(!r.includes(i))return!1;const a=e[i],s=t[i];if(me(a)&&me(s)){if(!$(a,s))return!1}else if(a!==s)return!1}for(const i of r)if(!n.includes(i))return!1;return!0}function me(e){return e!==null&&typeof e=="object"}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Kt=1e3,Wt=2,zt=14400*1e3,qt=.5;function be(e,t=Kt,n=Wt){const r=t*Math.pow(n,e),i=Math.round(qt*r*(Math.random()-.5)*2);return Math.min(zt,r+i)}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function qe(e){return e&&e._delegate?e._delegate:e}class w{constructor(t,n,r){this.name=t,this.instanceFactory=n,this.type=r,this.multipleInstances=!1,this.serviceProps={},this.instantiationMode="LAZY",this.onInstanceCreated=null}setInstantiationMode(t){return this.instantiationMode=t,this}setMultipleInstances(t){return this.multipleInstances=t,this}setServiceProps(t){return this.serviceProps=t,this}setInstanceCreatedCallback(t){return this.onInstanceCreated=t,this}}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const _="[DEFAULT]";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Gt{constructor(t,n){this.name=t,this.container=n,this.component=null,this.instances=new Map,this.instancesDeferred=new Map,this.instancesOptions=new Map,this.onInitCallbacks=new Map}get(t){const n=this.normalizeInstanceIdentifier(t);if(!this.instancesDeferred.has(n)){const r=new Lt;if(this.instancesDeferred.set(n,r),this.isInitialized(n)||this.shouldAutoInitialize())try{const i=this.getOrInitializeService({instanceIdentifier:n});i&&r.resolve(i)}catch{}}return this.instancesDeferred.get(n).promise}getImmediate(t){const n=this.normalizeInstanceIdentifier(t==null?void 0:t.identifier),r=(t==null?void 0:t.optional)??!1;if(this.isInitialized(n)||this.shouldAutoInitialize())try{return this.getOrInitializeService({instanceIdentifier:n})}catch(i){if(r)return null;throw i}else{if(r)return null;throw Error(`Service ${this.name} is not available`)}}getComponent(){return this.component}setComponent(t){if(t.name!==this.name)throw Error(`Mismatching Component ${t.name} for Provider ${this.name}.`);if(this.component)throw Error(`Component for ${this.name} has already been provided`);if(this.component=t,!!this.shouldAutoInitialize()){if(Jt(t))try{this.getOrInitializeService({instanceIdentifier:_})}catch{}for(const[n,r]of this.instancesDeferred.entries()){const i=this.normalizeInstanceIdentifier(n);try{const a=this.getOrInitializeService({instanceIdentifier:i});r.resolve(a)}catch{}}}}clearInstance(t=_){this.instancesDeferred.delete(t),this.instancesOptions.delete(t),this.instances.delete(t)}async delete(){const t=Array.from(this.instances.values());await Promise.all([...t.filter(n=>"INTERNAL"in n).map(n=>n.INTERNAL.delete()),...t.filter(n=>"_delete"in n).map(n=>n._delete())])}isComponentSet(){return this.component!=null}isInitialized(t=_){return this.instances.has(t)}getOptions(t=_){return this.instancesOptions.get(t)||{}}initialize(t={}){const{options:n={}}=t,r=this.normalizeInstanceIdentifier(t.instanceIdentifier);if(this.isInitialized(r))throw Error(`${this.name}(${r}) has already been initialized`);if(!this.isComponentSet())throw Error(`Component ${this.name} has not been registered yet`);const i=this.getOrInitializeService({instanceIdentifier:r,options:n});for(const[a,s]of this.instancesDeferred.entries()){const c=this.normalizeInstanceIdentifier(a);r===c&&s.resolve(i)}return i}onInit(t,n){const r=this.normalizeInstanceIdentifier(n),i=this.onInitCallbacks.get(r)??new Set;i.add(t),this.onInitCallbacks.set(r,i);const a=this.instances.get(r);return a&&t(a,r),()=>{i.delete(t)}}invokeOnInitCallbacks(t,n){const r=this.onInitCallbacks.get(n);if(r)for(const i of r)try{i(t,n)}catch{}}getOrInitializeService({instanceIdentifier:t,options:n={}}){let r=this.instances.get(t);if(!r&&this.component&&(r=this.component.instanceFactory(this.container,{instanceIdentifier:Yt(t),options:n}),this.instances.set(t,r),this.instancesOptions.set(t,n),this.invokeOnInitCallbacks(r,t),this.component.onInstanceCreated))try{this.component.onInstanceCreated(this.container,t,r)}catch{}return r||null}normalizeInstanceIdentifier(t=_){return this.component?this.component.multipleInstances?t:_:t}shouldAutoInitialize(){return!!this.component&&this.component.instantiationMode!=="EXPLICIT"}}function Yt(e){return e===_?void 0:e}function Jt(e){return e.instantiationMode==="EAGER"}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Xt{constructor(t){this.name=t,this.providers=new Map}addComponent(t){const n=this.getProvider(t.name);if(n.isComponentSet())throw new Error(`Component ${t.name} has already been registered with ${this.name}`);n.setComponent(t)}addOrOverwriteComponent(t){this.getProvider(t.name).isComponentSet()&&this.providers.delete(t.name),this.addComponent(t)}getProvider(t){if(this.providers.has(t))return this.providers.get(t);const n=new Gt(t,this);return this.providers.set(t,n),n}getProviders(){return Array.from(this.providers.values())}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var d;(function(e){e[e.DEBUG=0]="DEBUG",e[e.VERBOSE=1]="VERBOSE",e[e.INFO=2]="INFO",e[e.WARN=3]="WARN",e[e.ERROR=4]="ERROR",e[e.SILENT=5]="SILENT"})(d||(d={}));const Qt={debug:d.DEBUG,verbose:d.VERBOSE,info:d.INFO,warn:d.WARN,error:d.ERROR,silent:d.SILENT},Zt=d.INFO,en={[d.DEBUG]:"log",[d.VERBOSE]:"log",[d.INFO]:"info",[d.WARN]:"warn",[d.ERROR]:"error"},tn=(e,t,...n)=>{if(t<e.logLevel)return;const r=new Date().toISOString(),i=en[t];if(i)console[i](`[${r}]  ${e.name}:`,...n);else throw new Error(`Attempted to log a message with an invalid logType (value: ${t})`)};class Ge{constructor(t){this.name=t,this._logLevel=Zt,this._logHandler=tn,this._userLogHandler=null}get logLevel(){return this._logLevel}set logLevel(t){if(!(t in d))throw new TypeError(`Invalid value "${t}" assigned to \`logLevel\``);this._logLevel=t}setLogLevel(t){this._logLevel=typeof t=="string"?Qt[t]:t}get logHandler(){return this._logHandler}set logHandler(t){if(typeof t!="function")throw new TypeError("Value assigned to `logHandler` must be a function");this._logHandler=t}get userLogHandler(){return this._userLogHandler}set userLogHandler(t){this._userLogHandler=t}debug(...t){this._userLogHandler&&this._userLogHandler(this,d.DEBUG,...t),this._logHandler(this,d.DEBUG,...t)}log(...t){this._userLogHandler&&this._userLogHandler(this,d.VERBOSE,...t),this._logHandler(this,d.VERBOSE,...t)}info(...t){this._userLogHandler&&this._userLogHandler(this,d.INFO,...t),this._logHandler(this,d.INFO,...t)}warn(...t){this._userLogHandler&&this._userLogHandler(this,d.WARN,...t),this._logHandler(this,d.WARN,...t)}error(...t){this._userLogHandler&&this._userLogHandler(this,d.ERROR,...t),this._logHandler(this,d.ERROR,...t)}}const nn=(e,t)=>t.some(n=>e instanceof n);let we,ye;function rn(){return we||(we=[IDBDatabase,IDBObjectStore,IDBIndex,IDBCursor,IDBTransaction])}function an(){return ye||(ye=[IDBCursor.prototype.advance,IDBCursor.prototype.continue,IDBCursor.prototype.continuePrimaryKey])}const Ye=new WeakMap,ee=new WeakMap,Je=new WeakMap,U=new WeakMap,se=new WeakMap;function sn(e){const t=new Promise((n,r)=>{const i=()=>{e.removeEventListener("success",a),e.removeEventListener("error",s)},a=()=>{n(I(e.result)),i()},s=()=>{r(e.error),i()};e.addEventListener("success",a),e.addEventListener("error",s)});return t.then(n=>{n instanceof IDBCursor&&Ye.set(n,e)}).catch(()=>{}),se.set(t,e),t}function on(e){if(ee.has(e))return;const t=new Promise((n,r)=>{const i=()=>{e.removeEventListener("complete",a),e.removeEventListener("error",s),e.removeEventListener("abort",s)},a=()=>{n(),i()},s=()=>{r(e.error||new DOMException("AbortError","AbortError")),i()};e.addEventListener("complete",a),e.addEventListener("error",s),e.addEventListener("abort",s)});ee.set(e,t)}let te={get(e,t,n){if(e instanceof IDBTransaction){if(t==="done")return ee.get(e);if(t==="objectStoreNames")return e.objectStoreNames||Je.get(e);if(t==="store")return n.objectStoreNames[1]?void 0:n.objectStore(n.objectStoreNames[0])}return I(e[t])},set(e,t,n){return e[t]=n,!0},has(e,t){return e instanceof IDBTransaction&&(t==="done"||t==="store")?!0:t in e}};function cn(e){te=e(te)}function ln(e){return e===IDBDatabase.prototype.transaction&&!("objectStoreNames"in IDBTransaction.prototype)?function(t,...n){const r=e.call(K(this),t,...n);return Je.set(r,t.sort?t.sort():[t]),I(r)}:an().includes(e)?function(...t){return e.apply(K(this),t),I(Ye.get(this))}:function(...t){return I(e.apply(K(this),t))}}function dn(e){return typeof e=="function"?ln(e):(e instanceof IDBTransaction&&on(e),nn(e,rn())?new Proxy(e,te):e)}function I(e){if(e instanceof IDBRequest)return sn(e);if(U.has(e))return U.get(e);const t=dn(e);return t!==e&&(U.set(e,t),se.set(t,e)),t}const K=e=>se.get(e);function x(e,t,{blocked:n,upgrade:r,blocking:i,terminated:a}={}){const s=indexedDB.open(e,t),c=I(s);return r&&s.addEventListener("upgradeneeded",l=>{r(I(s.result),l.oldVersion,l.newVersion,I(s.transaction),l)}),n&&s.addEventListener("blocked",l=>n(l.oldVersion,l.newVersion,l)),c.then(l=>{a&&l.addEventListener("close",()=>a()),i&&l.addEventListener("versionchange",o=>i(o.oldVersion,o.newVersion,o))}).catch(()=>{}),c}function W(e,{blocked:t}={}){const n=indexedDB.deleteDatabase(e);return t&&n.addEventListener("blocked",r=>t(r.oldVersion,r)),I(n).then(()=>{})}const un=["get","getKey","getAll","getAllKeys","count"],fn=["put","add","delete","clear"],z=new Map;function Ie(e,t){if(!(e instanceof IDBDatabase&&!(t in e)&&typeof t=="string"))return;if(z.get(t))return z.get(t);const n=t.replace(/FromIndex$/,""),r=t!==n,i=fn.includes(n);if(!(n in(r?IDBIndex:IDBObjectStore).prototype)||!(i||un.includes(n)))return;const a=async function(s,...c){const l=this.transaction(s,i?"readwrite":"readonly");let o=l.store;return r&&(o=o.index(c.shift())),(await Promise.all([o[n](...c),i&&l.done]))[0]};return z.set(t,a),a}cn(e=>({...e,get:(t,n,r)=>Ie(t,n)||e.get(t,n,r),has:(t,n)=>!!Ie(t,n)||e.has(t,n)}));/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class hn{constructor(t){this.container=t}getPlatformInfoString(){return this.container.getProviders().map(n=>{if(pn(n)){const r=n.getImmediate();return`${r.library}/${r.version}`}else return null}).filter(n=>n).join(" ")}}function pn(e){const t=e.getComponent();return(t==null?void 0:t.type)==="VERSION"}const ne="@firebase/app",Ee="0.14.3";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const E=new Ge("@firebase/app"),gn="@firebase/app-compat",mn="@firebase/analytics-compat",bn="@firebase/analytics",wn="@firebase/app-check-compat",yn="@firebase/app-check",In="@firebase/auth",En="@firebase/auth-compat",Tn="@firebase/database",An="@firebase/data-connect",_n="@firebase/database-compat",Sn="@firebase/functions",vn="@firebase/functions-compat",Dn="@firebase/installations",Cn="@firebase/installations-compat",kn="@firebase/messaging",On="@firebase/messaging-compat",Mn="@firebase/performance",Rn="@firebase/performance-compat",Nn="@firebase/remote-config",Bn="@firebase/remote-config-compat",$n="@firebase/storage",Pn="@firebase/storage-compat",Fn="@firebase/firestore",Ln="@firebase/ai",xn="@firebase/firestore-compat",Hn="firebase";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const re="[DEFAULT]",jn={[ne]:"fire-core",[gn]:"fire-core-compat",[bn]:"fire-analytics",[mn]:"fire-analytics-compat",[yn]:"fire-app-check",[wn]:"fire-app-check-compat",[In]:"fire-auth",[En]:"fire-auth-compat",[Tn]:"fire-rtdb",[An]:"fire-data-connect",[_n]:"fire-rtdb-compat",[Sn]:"fire-fn",[vn]:"fire-fn-compat",[Dn]:"fire-iid",[Cn]:"fire-iid-compat",[kn]:"fire-fcm",[On]:"fire-fcm-compat",[Mn]:"fire-perf",[Rn]:"fire-perf-compat",[Nn]:"fire-rc",[Bn]:"fire-rc-compat",[$n]:"fire-gcs",[Pn]:"fire-gcs-compat",[Fn]:"fire-fst",[xn]:"fire-fst-compat",[Ln]:"fire-vertex","fire-js":"fire-js",[Hn]:"fire-js-all"};/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const P=new Map,Vn=new Map,ie=new Map;function Te(e,t){try{e.container.addComponent(t)}catch(n){E.debug(`Component ${t.name} failed to register with FirebaseApp ${e.name}`,n)}}function T(e){const t=e.name;if(ie.has(t))return E.debug(`There were multiple attempts to register component ${t}.`),!1;ie.set(t,e);for(const n of P.values())Te(n,e);for(const n of Vn.values())Te(n,e);return!0}function H(e,t){const n=e.container.getProvider("heartbeat").getImmediate({optional:!0});return n&&n.triggerHeartbeat(),e.container.getProvider(t)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Un={"no-app":"No Firebase App '{$appName}' has been created - call initializeApp() first","bad-app-name":"Illegal App name: '{$appName}'","duplicate-app":"Firebase App named '{$appName}' already exists with different options or config","app-deleted":"Firebase App named '{$appName}' already deleted","server-app-deleted":"Firebase Server App has been deleted","no-options":"Need to provide options, when not being deployed to hosting via source.","invalid-app-argument":"firebase.{$appName}() takes either no argument or a Firebase App instance.","invalid-log-argument":"First argument to `onLog` must be null or a function.","idb-open":"Error thrown when opening IndexedDB. Original error: {$originalErrorMessage}.","idb-get":"Error thrown when reading from IndexedDB. Original error: {$originalErrorMessage}.","idb-set":"Error thrown when writing to IndexedDB. Original error: {$originalErrorMessage}.","idb-delete":"Error thrown when deleting from IndexedDB. Original error: {$originalErrorMessage}.","finalization-registry-not-supported":"FirebaseServerApp deleteOnDeref field defined but the JS runtime does not support FinalizationRegistry.","invalid-server-app-environment":"FirebaseServerApp is not for use in browser environments."},A=new N("app","Firebase",Un);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Kn{constructor(t,n,r){this._isDeleted=!1,this._options={...t},this._config={...n},this._name=n.name,this._automaticDataCollectionEnabled=n.automaticDataCollectionEnabled,this._container=r,this.container.addComponent(new w("app",()=>this,"PUBLIC"))}get automaticDataCollectionEnabled(){return this.checkDestroyed(),this._automaticDataCollectionEnabled}set automaticDataCollectionEnabled(t){this.checkDestroyed(),this._automaticDataCollectionEnabled=t}get name(){return this.checkDestroyed(),this._name}get options(){return this.checkDestroyed(),this._options}get config(){return this.checkDestroyed(),this._config}get container(){return this._container}get isDeleted(){return this._isDeleted}set isDeleted(t){this._isDeleted=t}checkDestroyed(){if(this.isDeleted)throw A.create("app-deleted",{appName:this._name})}}function Xe(e,t={}){let n=e;typeof t!="object"&&(t={name:t});const r={name:re,automaticDataCollectionEnabled:!0,...t},i=r.name;if(typeof i!="string"||!i)throw A.create("bad-app-name",{appName:String(i)});if(n||(n=Ke()),!n)throw A.create("no-options");const a=P.get(i);if(a){if($(n,a.options)&&$(r,a.config))return a;throw A.create("duplicate-app",{appName:i})}const s=new Xt(i);for(const l of ie.values())s.addComponent(l);const c=new Kn(n,r,s);return P.set(i,c),c}function Wn(e=re){const t=P.get(e);if(!t&&e===re&&Ke())return Xe();if(!t)throw A.create("no-app",{appName:e});return t}function b(e,t,n){let r=jn[e]??e;n&&(r+=`-${n}`);const i=r.match(/\s|\//),a=t.match(/\s|\//);if(i||a){const s=[`Unable to register library "${r}" with version "${t}":`];i&&s.push(`library name "${r}" contains illegal characters (whitespace or "/")`),i&&a&&s.push("and"),a&&s.push(`version name "${t}" contains illegal characters (whitespace or "/")`),E.warn(s.join(" "));return}T(new w(`${r}-version`,()=>({library:r,version:t}),"VERSION"))}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const zn="firebase-heartbeat-database",qn=1,O="firebase-heartbeat-store";let q=null;function Qe(){return q||(q=x(zn,qn,{upgrade:(e,t)=>{switch(t){case 0:try{e.createObjectStore(O)}catch(n){console.warn(n)}}}}).catch(e=>{throw A.create("idb-open",{originalErrorMessage:e.message})})),q}async function Gn(e){try{const n=(await Qe()).transaction(O),r=await n.objectStore(O).get(Ze(e));return await n.done,r}catch(t){if(t instanceof C)E.warn(t.message);else{const n=A.create("idb-get",{originalErrorMessage:t==null?void 0:t.message});E.warn(n.message)}}}async function Ae(e,t){try{const r=(await Qe()).transaction(O,"readwrite");await r.objectStore(O).put(t,Ze(e)),await r.done}catch(n){if(n instanceof C)E.warn(n.message);else{const r=A.create("idb-set",{originalErrorMessage:n==null?void 0:n.message});E.warn(r.message)}}}function Ze(e){return`${e.name}!${e.options.appId}`}/**
 * @license
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Yn=1024,Jn=30;class Xn{constructor(t){this.container=t,this._heartbeatsCache=null;const n=this.container.getProvider("app").getImmediate();this._storage=new Zn(n),this._heartbeatsCachePromise=this._storage.read().then(r=>(this._heartbeatsCache=r,r))}async triggerHeartbeat(){var t,n;try{const i=this.container.getProvider("platform-logger").getImmediate().getPlatformInfoString(),a=_e();if(((t=this._heartbeatsCache)==null?void 0:t.heartbeats)==null&&(this._heartbeatsCache=await this._heartbeatsCachePromise,((n=this._heartbeatsCache)==null?void 0:n.heartbeats)==null)||this._heartbeatsCache.lastSentHeartbeatDate===a||this._heartbeatsCache.heartbeats.some(s=>s.date===a))return;if(this._heartbeatsCache.heartbeats.push({date:a,agent:i}),this._heartbeatsCache.heartbeats.length>Jn){const s=er(this._heartbeatsCache.heartbeats);this._heartbeatsCache.heartbeats.splice(s,1)}return this._storage.overwrite(this._heartbeatsCache)}catch(r){E.warn(r)}}async getHeartbeatsHeader(){var t;try{if(this._heartbeatsCache===null&&await this._heartbeatsCachePromise,((t=this._heartbeatsCache)==null?void 0:t.heartbeats)==null||this._heartbeatsCache.heartbeats.length===0)return"";const n=_e(),{heartbeatsToSend:r,unsentEntries:i}=Qn(this._heartbeatsCache.heartbeats),a=Ue(JSON.stringify({version:2,heartbeats:r}));return this._heartbeatsCache.lastSentHeartbeatDate=n,i.length>0?(this._heartbeatsCache.heartbeats=i,await this._storage.overwrite(this._heartbeatsCache)):(this._heartbeatsCache.heartbeats=[],this._storage.overwrite(this._heartbeatsCache)),a}catch(n){return E.warn(n),""}}}function _e(){return new Date().toISOString().substring(0,10)}function Qn(e,t=Yn){const n=[];let r=e.slice();for(const i of e){const a=n.find(s=>s.agent===i.agent);if(a){if(a.dates.push(i.date),Se(n)>t){a.dates.pop();break}}else if(n.push({agent:i.agent,dates:[i.date]}),Se(n)>t){n.pop();break}r=r.slice(1)}return{heartbeatsToSend:n,unsentEntries:r}}class Zn{constructor(t){this.app=t,this._canUseIndexedDBPromise=this.runIndexedDBEnvironmentCheck()}async runIndexedDBEnvironmentCheck(){return We()?ze().then(()=>!0).catch(()=>!1):!1}async read(){if(await this._canUseIndexedDBPromise){const n=await Gn(this.app);return n!=null&&n.heartbeats?n:{heartbeats:[]}}else return{heartbeats:[]}}async overwrite(t){if(await this._canUseIndexedDBPromise){const r=await this.read();return Ae(this.app,{lastSentHeartbeatDate:t.lastSentHeartbeatDate??r.lastSentHeartbeatDate,heartbeats:t.heartbeats})}else return}async add(t){if(await this._canUseIndexedDBPromise){const r=await this.read();return Ae(this.app,{lastSentHeartbeatDate:t.lastSentHeartbeatDate??r.lastSentHeartbeatDate,heartbeats:[...r.heartbeats,...t.heartbeats]})}else return}}function Se(e){return Ue(JSON.stringify({version:2,heartbeats:e})).length}function er(e){if(e.length===0)return-1;let t=0,n=e[0].date;for(let r=1;r<e.length;r++)e[r].date<n&&(n=e[r].date,t=r);return t}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function tr(e){T(new w("platform-logger",t=>new hn(t),"PRIVATE")),T(new w("heartbeat",t=>new Xn(t),"PRIVATE")),b(ne,Ee,e),b(ne,Ee,"esm2020"),b("fire-js","")}tr("");var nr="firebase",rr="12.3.0";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */b(nr,rr,"app");const et="@firebase/installations",oe="0.6.19";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const tt=1e4,nt=`w:${oe}`,rt="FIS_v2",ir="https://firebaseinstallations.googleapis.com/v1",ar=3600*1e3,sr="installations",or="Installations";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const cr={"missing-app-config-values":'Missing App configuration value: "{$valueName}"',"not-registered":"Firebase Installation is not registered.","installation-not-found":"Firebase Installation not found.","request-failed":'{$requestName} request failed with error "{$serverCode} {$serverStatus}: {$serverMessage}"',"app-offline":"Could not process request. Application offline.","delete-pending-registration":"Can't delete installation while there is a pending registration request."},v=new N(sr,or,cr);function it(e){return e instanceof C&&e.code.includes("request-failed")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function at({projectId:e}){return`${ir}/projects/${e}/installations`}function st(e){return{token:e.token,requestStatus:2,expiresIn:dr(e.expiresIn),creationTime:Date.now()}}async function ot(e,t){const r=(await t.json()).error;return v.create("request-failed",{requestName:e,serverCode:r.code,serverMessage:r.message,serverStatus:r.status})}function ct({apiKey:e}){return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":e})}function lr(e,{refreshToken:t}){const n=ct(e);return n.append("Authorization",ur(t)),n}async function lt(e){const t=await e();return t.status>=500&&t.status<600?e():t}function dr(e){return Number(e.replace("s","000"))}function ur(e){return`${rt} ${e}`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function fr({appConfig:e,heartbeatServiceProvider:t},{fid:n}){const r=at(e),i=ct(e),a=t.getImmediate({optional:!0});if(a){const o=await a.getHeartbeatsHeader();o&&i.append("x-firebase-client",o)}const s={fid:n,authVersion:rt,appId:e.appId,sdkVersion:nt},c={method:"POST",headers:i,body:JSON.stringify(s)},l=await lt(()=>fetch(r,c));if(l.ok){const o=await l.json();return{fid:o.fid||n,registrationStatus:2,refreshToken:o.refreshToken,authToken:st(o.authToken)}}else throw await ot("Create Installation",l)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function dt(e){return new Promise(t=>{setTimeout(t,e)})}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function hr(e){return btoa(String.fromCharCode(...e)).replace(/\+/g,"-").replace(/\//g,"_")}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const pr=/^[cdef][\w-]{21}$/,ae="";function gr(){try{const e=new Uint8Array(17);(self.crypto||self.msCrypto).getRandomValues(e),e[0]=112+e[0]%16;const n=mr(e);return pr.test(n)?n:ae}catch{return ae}}function mr(e){return hr(e).substr(0,22)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function j(e){return`${e.appName}!${e.appId}`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ut=new Map;function ft(e,t){const n=j(e);ht(n,t),br(n,t)}function ht(e,t){const n=ut.get(e);if(n)for(const r of n)r(t)}function br(e,t){const n=wr();n&&n.postMessage({key:e,fid:t}),yr()}let S=null;function wr(){return!S&&"BroadcastChannel"in self&&(S=new BroadcastChannel("[Firebase] FID Change"),S.onmessage=e=>{ht(e.data.key,e.data.fid)}),S}function yr(){ut.size===0&&S&&(S.close(),S=null)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Ir="firebase-installations-database",Er=1,D="firebase-installations-store";let G=null;function ce(){return G||(G=x(Ir,Er,{upgrade:(e,t)=>{switch(t){case 0:e.createObjectStore(D)}}})),G}async function F(e,t){const n=j(e),i=(await ce()).transaction(D,"readwrite"),a=i.objectStore(D),s=await a.get(n);return await a.put(t,n),await i.done,(!s||s.fid!==t.fid)&&ft(e,t.fid),t}async function pt(e){const t=j(e),r=(await ce()).transaction(D,"readwrite");await r.objectStore(D).delete(t),await r.done}async function V(e,t){const n=j(e),i=(await ce()).transaction(D,"readwrite"),a=i.objectStore(D),s=await a.get(n),c=t(s);return c===void 0?await a.delete(n):await a.put(c,n),await i.done,c&&(!s||s.fid!==c.fid)&&ft(e,c.fid),c}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function le(e){let t;const n=await V(e.appConfig,r=>{const i=Tr(r),a=Ar(e,i);return t=a.registrationPromise,a.installationEntry});return n.fid===ae?{installationEntry:await t}:{installationEntry:n,registrationPromise:t}}function Tr(e){const t=e||{fid:gr(),registrationStatus:0};return gt(t)}function Ar(e,t){if(t.registrationStatus===0){if(!navigator.onLine){const i=Promise.reject(v.create("app-offline"));return{installationEntry:t,registrationPromise:i}}const n={fid:t.fid,registrationStatus:1,registrationTime:Date.now()},r=_r(e,n);return{installationEntry:n,registrationPromise:r}}else return t.registrationStatus===1?{installationEntry:t,registrationPromise:Sr(e)}:{installationEntry:t}}async function _r(e,t){try{const n=await fr(e,t);return F(e.appConfig,n)}catch(n){throw it(n)&&n.customData.serverCode===409?await pt(e.appConfig):await F(e.appConfig,{fid:t.fid,registrationStatus:0}),n}}async function Sr(e){let t=await ve(e.appConfig);for(;t.registrationStatus===1;)await dt(100),t=await ve(e.appConfig);if(t.registrationStatus===0){const{installationEntry:n,registrationPromise:r}=await le(e);return r||n}return t}function ve(e){return V(e,t=>{if(!t)throw v.create("installation-not-found");return gt(t)})}function gt(e){return vr(e)?{fid:e.fid,registrationStatus:0}:e}function vr(e){return e.registrationStatus===1&&e.registrationTime+tt<Date.now()}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Dr({appConfig:e,heartbeatServiceProvider:t},n){const r=Cr(e,n),i=lr(e,n),a=t.getImmediate({optional:!0});if(a){const o=await a.getHeartbeatsHeader();o&&i.append("x-firebase-client",o)}const s={installation:{sdkVersion:nt,appId:e.appId}},c={method:"POST",headers:i,body:JSON.stringify(s)},l=await lt(()=>fetch(r,c));if(l.ok){const o=await l.json();return st(o)}else throw await ot("Generate Auth Token",l)}function Cr(e,{fid:t}){return`${at(e)}/${t}/authTokens:generate`}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function de(e,t=!1){let n;const r=await V(e.appConfig,a=>{if(!mt(a))throw v.create("not-registered");const s=a.authToken;if(!t&&Mr(s))return a;if(s.requestStatus===1)return n=kr(e,t),a;{if(!navigator.onLine)throw v.create("app-offline");const c=Nr(a);return n=Or(e,c),c}});return n?await n:r.authToken}async function kr(e,t){let n=await De(e.appConfig);for(;n.authToken.requestStatus===1;)await dt(100),n=await De(e.appConfig);const r=n.authToken;return r.requestStatus===0?de(e,t):r}function De(e){return V(e,t=>{if(!mt(t))throw v.create("not-registered");const n=t.authToken;return Br(n)?{...t,authToken:{requestStatus:0}}:t})}async function Or(e,t){try{const n=await Dr(e,t),r={...t,authToken:n};return await F(e.appConfig,r),n}catch(n){if(it(n)&&(n.customData.serverCode===401||n.customData.serverCode===404))await pt(e.appConfig);else{const r={...t,authToken:{requestStatus:0}};await F(e.appConfig,r)}throw n}}function mt(e){return e!==void 0&&e.registrationStatus===2}function Mr(e){return e.requestStatus===2&&!Rr(e)}function Rr(e){const t=Date.now();return t<e.creationTime||e.creationTime+e.expiresIn<t+ar}function Nr(e){const t={requestStatus:1,requestTime:Date.now()};return{...e,authToken:t}}function Br(e){return e.requestStatus===1&&e.requestTime+tt<Date.now()}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function $r(e){const t=e,{installationEntry:n,registrationPromise:r}=await le(t);return r?r.catch(console.error):de(t).catch(console.error),n.fid}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Pr(e,t=!1){const n=e;return await Fr(n),(await de(n,t)).token}async function Fr(e){const{registrationPromise:t}=await le(e);t&&await t}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Lr(e){if(!e||!e.options)throw Y("App Configuration");if(!e.name)throw Y("App Name");const t=["projectId","apiKey","appId"];for(const n of t)if(!e.options[n])throw Y(n);return{appName:e.name,projectId:e.options.projectId,apiKey:e.options.apiKey,appId:e.options.appId}}function Y(e){return v.create("missing-app-config-values",{valueName:e})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const bt="installations",xr="installations-internal",Hr=e=>{const t=e.getProvider("app").getImmediate(),n=Lr(t),r=H(t,"heartbeat");return{app:t,appConfig:n,heartbeatServiceProvider:r,_delete:()=>Promise.resolve()}},jr=e=>{const t=e.getProvider("app").getImmediate(),n=H(t,bt).getImmediate();return{getId:()=>$r(n),getToken:i=>Pr(n,i)}};function Vr(){T(new w(bt,Hr,"PUBLIC")),T(new w(xr,jr,"PRIVATE"))}Vr();b(et,oe);b(et,oe,"esm2020");/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const L="analytics",Ur="firebase_id",Kr="origin",Wr=60*1e3,zr="https://firebase.googleapis.com/v1alpha/projects/-/apps/{app-id}/webConfig",ue="https://www.googletagmanager.com/gtag/js";/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const f=new Ge("@firebase/analytics");/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const qr={"already-exists":"A Firebase Analytics instance with the appId {$id}  already exists. Only one Firebase Analytics instance can be created for each appId.","already-initialized":"initializeAnalytics() cannot be called again with different options than those it was initially called with. It can be called again with the same options to return the existing instance, or getAnalytics() can be used to get a reference to the already-initialized instance.","already-initialized-settings":"Firebase Analytics has already been initialized.settings() must be called before initializing any Analytics instanceor it will have no effect.","interop-component-reg-failed":"Firebase Analytics Interop Component failed to instantiate: {$reason}","invalid-analytics-context":"Firebase Analytics is not supported in this environment. Wrap initialization of analytics in analytics.isSupported() to prevent initialization in unsupported environments. Details: {$errorInfo}","indexeddb-unavailable":"IndexedDB unavailable or restricted in this environment. Wrap initialization of analytics in analytics.isSupported() to prevent initialization in unsupported environments. Details: {$errorInfo}","fetch-throttle":"The config fetch request timed out while in an exponential backoff state. Unix timestamp in milliseconds when fetch request throttling ends: {$throttleEndTimeMillis}.","config-fetch-failed":"Dynamic config fetch failed: [{$httpStatus}] {$responseMessage}","no-api-key":'The "apiKey" field is empty in the local Firebase config. Firebase Analytics requires this field tocontain a valid API key.',"no-app-id":'The "appId" field is empty in the local Firebase config. Firebase Analytics requires this field tocontain a valid app ID.',"no-client-id":'The "client_id" field is empty.',"invalid-gtag-resource":"Trusted Types detected an invalid gtag resource: {$gtagURL}."},g=new N("analytics","Analytics",qr);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Gr(e){if(!e.startsWith(ue)){const t=g.create("invalid-gtag-resource",{gtagURL:e});return f.warn(t.message),""}return e}function wt(e){return Promise.all(e.map(t=>t.catch(n=>n)))}function Yr(e,t){let n;return window.trustedTypes&&(n=window.trustedTypes.createPolicy(e,t)),n}function Jr(e,t){const n=Yr("firebase-js-sdk-policy",{createScriptURL:Gr}),r=document.createElement("script"),i=`${ue}?l=${e}&id=${t}`;r.src=n?n==null?void 0:n.createScriptURL(i):i,r.async=!0,document.head.appendChild(r)}function Xr(e){let t=[];return Array.isArray(window[e])?t=window[e]:window[e]=t,t}async function Qr(e,t,n,r,i,a){const s=r[i];try{if(s)await t[s];else{const l=(await wt(n)).find(o=>o.measurementId===i);l&&await t[l.appId]}}catch(c){f.error(c)}e("config",i,a)}async function Zr(e,t,n,r,i){try{let a=[];if(i&&i.send_to){let s=i.send_to;Array.isArray(s)||(s=[s]);const c=await wt(n);for(const l of s){const o=c.find(u=>u.measurementId===l),h=o&&t[o.appId];if(h)a.push(h);else{a=[];break}}}a.length===0&&(a=Object.values(t)),await Promise.all(a),e("event",r,i||{})}catch(a){f.error(a)}}function ei(e,t,n,r){async function i(a,...s){try{if(a==="event"){const[c,l]=s;await Zr(e,t,n,c,l)}else if(a==="config"){const[c,l]=s;await Qr(e,t,n,r,c,l)}else if(a==="consent"){const[c,l]=s;e("consent",c,l)}else if(a==="get"){const[c,l,o]=s;e("get",c,l,o)}else if(a==="set"){const[c]=s;e("set",c)}else e(a,...s)}catch(c){f.error(c)}}return i}function ti(e,t,n,r,i){let a=function(...s){window[r].push(arguments)};return window[i]&&typeof window[i]=="function"&&(a=window[i]),window[i]=ei(a,e,t,n),{gtagCore:a,wrappedGtag:window[i]}}function ni(e){const t=window.document.getElementsByTagName("script");for(const n of Object.values(t))if(n.src&&n.src.includes(ue)&&n.src.includes(e))return n;return null}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const ri=30,ii=1e3;class ai{constructor(t={},n=ii){this.throttleMetadata=t,this.intervalMillis=n}getThrottleMetadata(t){return this.throttleMetadata[t]}setThrottleMetadata(t,n){this.throttleMetadata[t]=n}deleteThrottleMetadata(t){delete this.throttleMetadata[t]}}const yt=new ai;function si(e){return new Headers({Accept:"application/json","x-goog-api-key":e})}async function oi(e){var s;const{appId:t,apiKey:n}=e,r={method:"GET",headers:si(n)},i=zr.replace("{app-id}",t),a=await fetch(i,r);if(a.status!==200&&a.status!==304){let c="";try{const l=await a.json();(s=l.error)!=null&&s.message&&(c=l.error.message)}catch{}throw g.create("config-fetch-failed",{httpStatus:a.status,responseMessage:c})}return a.json()}async function ci(e,t=yt,n){const{appId:r,apiKey:i,measurementId:a}=e.options;if(!r)throw g.create("no-app-id");if(!i){if(a)return{measurementId:a,appId:r};throw g.create("no-api-key")}const s=t.getThrottleMetadata(r)||{backoffCount:0,throttleEndTimeMillis:Date.now()},c=new ui;return setTimeout(async()=>{c.abort()},Wr),It({appId:r,apiKey:i,measurementId:a},s,c,t)}async function It(e,{throttleEndTimeMillis:t,backoffCount:n},r,i=yt){var c;const{appId:a,measurementId:s}=e;try{await li(r,t)}catch(l){if(s)return f.warn(`Timed out fetching this Firebase app's measurement ID from the server. Falling back to the measurement ID ${s} provided in the "measurementId" field in the local Firebase config. [${l==null?void 0:l.message}]`),{appId:a,measurementId:s};throw l}try{const l=await oi(e);return i.deleteThrottleMetadata(a),l}catch(l){const o=l;if(!di(o)){if(i.deleteThrottleMetadata(a),s)return f.warn(`Failed to fetch this Firebase app's measurement ID from the server. Falling back to the measurement ID ${s} provided in the "measurementId" field in the local Firebase config. [${o==null?void 0:o.message}]`),{appId:a,measurementId:s};throw l}const h=Number((c=o==null?void 0:o.customData)==null?void 0:c.httpStatus)===503?be(n,i.intervalMillis,ri):be(n,i.intervalMillis),u={throttleEndTimeMillis:Date.now()+h,backoffCount:n+1};return i.setThrottleMetadata(a,u),f.debug(`Calling attemptFetch again in ${h} millis`),It(e,u,r,i)}}function li(e,t){return new Promise((n,r)=>{const i=Math.max(t-Date.now(),0),a=setTimeout(n,i);e.addEventListener(()=>{clearTimeout(a),r(g.create("fetch-throttle",{throttleEndTimeMillis:t}))})})}function di(e){if(!(e instanceof C)||!e.customData)return!1;const t=Number(e.customData.httpStatus);return t===429||t===500||t===503||t===504}class ui{constructor(){this.listeners=[]}addEventListener(t){this.listeners.push(t)}abort(){this.listeners.forEach(t=>t())}}async function fi(e,t,n,r,i){if(i&&i.global){e("event",n,r);return}else{const a=await t,s={...r,send_to:a};e("event",n,s)}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function hi(){if(We())try{await ze()}catch(e){return f.warn(g.create("indexeddb-unavailable",{errorInfo:e==null?void 0:e.toString()}).message),!1}else return f.warn(g.create("indexeddb-unavailable",{errorInfo:"IndexedDB is not available in this environment."}).message),!1;return!0}async function pi(e,t,n,r,i,a,s){const c=ci(e);c.then(p=>{n[p.measurementId]=p.appId,e.options.measurementId&&p.measurementId!==e.options.measurementId&&f.warn(`The measurement ID in the local Firebase config (${e.options.measurementId}) does not match the measurement ID fetched from the server (${p.measurementId}). To ensure analytics events are always sent to the correct Analytics property, update the measurement ID field in the local config or remove it from the local config.`)}).catch(p=>f.error(p)),t.push(c);const l=hi().then(p=>{if(p)return r.getId()}),[o,h]=await Promise.all([c,l]);ni(a)||Jr(a,o.measurementId),i("js",new Date);const u=(s==null?void 0:s.config)??{};return u[Kr]="firebase",u.update=!0,h!=null&&(u[Ur]=h),i("config",o.measurementId,u),o.measurementId}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class gi{constructor(t){this.app=t}_delete(){return delete k[this.app.options.appId],Promise.resolve()}}let k={},Ce=[];const ke={};let J="dataLayer",mi="gtag",Oe,Et,Me=!1;function bi(){const e=[];if(xt()&&e.push("This is a browser extension environment."),Ht()||e.push("Cookies are not available."),e.length>0){const t=e.map((r,i)=>`(${i+1}) ${r}`).join(" "),n=g.create("invalid-analytics-context",{errorInfo:t});f.warn(n.message)}}function wi(e,t,n){bi();const r=e.options.appId;if(!r)throw g.create("no-app-id");if(!e.options.apiKey)if(e.options.measurementId)f.warn(`The "apiKey" field is empty in the local Firebase config. This is needed to fetch the latest measurement ID for this Firebase app. Falling back to the measurement ID ${e.options.measurementId} provided in the "measurementId" field in the local Firebase config.`);else throw g.create("no-api-key");if(k[r]!=null)throw g.create("already-exists",{id:r});if(!Me){Xr(J);const{wrappedGtag:a,gtagCore:s}=ti(k,Ce,ke,J,mi);Et=a,Oe=s,Me=!0}return k[r]=pi(e,Ce,ke,t,Oe,J,n),new gi(e)}function yi(e=Wn()){e=qe(e);const t=H(e,L);return t.isInitialized()?t.getImmediate():Ii(e)}function Ii(e,t={}){const n=H(e,L);if(n.isInitialized()){const i=n.getImmediate();if($(t,n.getOptions()))return i;throw g.create("already-initialized")}return n.initialize({options:t})}function Ei(e,t,n,r){e=qe(e),fi(Et,k[e.app.options.appId],t,n,r).catch(i=>f.error(i))}const Re="@firebase/analytics",Ne="0.10.18";function Ti(){T(new w(L,(t,{options:n})=>{const r=t.getProvider("app").getImmediate(),i=t.getProvider("installations-internal").getImmediate();return wi(r,i,n)},"PUBLIC")),T(new w("analytics-internal",e,"PRIVATE")),b(Re,Ne),b(Re,Ne,"esm2020");function e(t){try{const n=t.getProvider(L).getImmediate();return{logEvent:(r,i,a)=>Ei(n,r,i,a)}}catch(n){throw g.create("interop-component-reg-failed",{reason:n})}}}Ti();/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Ai="/firebase-messaging-sw.js",_i="/firebase-cloud-messaging-push-scope",Tt="BDOU99-h67HcA6JeFXHbSNMu7e2yNNu3RzoMj8TM4W88jITfq7ZmPvIM1Iv-4_l2LxQcYwhqby2xGpWwzjfAnG4",Si="https://fcmregistrations.googleapis.com/v1",At="google.c.a.c_id",vi="google.c.a.c_l",Di="google.c.a.ts",Ci="google.c.a.e",Be=1e4;var $e;(function(e){e[e.DATA_MESSAGE=1]="DATA_MESSAGE",e[e.DISPLAY_NOTIFICATION=3]="DISPLAY_NOTIFICATION"})($e||($e={}));/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express
 * or implied. See the License for the specific language governing permissions and limitations under
 * the License.
 */var M;(function(e){e.PUSH_RECEIVED="push-received",e.NOTIFICATION_CLICKED="notification-clicked"})(M||(M={}));/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function y(e){const t=new Uint8Array(e);return btoa(String.fromCharCode(...t)).replace(/=/g,"").replace(/\+/g,"-").replace(/\//g,"_")}function ki(e){const t="=".repeat((4-e.length%4)%4),n=(e+t).replace(/\-/g,"+").replace(/_/g,"/"),r=atob(n),i=new Uint8Array(r.length);for(let a=0;a<r.length;++a)i[a]=r.charCodeAt(a);return i}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const X="fcm_token_details_db",Oi=5,Pe="fcm_token_object_Store";async function Mi(e){if("databases"in indexedDB&&!(await indexedDB.databases()).map(a=>a.name).includes(X))return null;let t=null;return(await x(X,Oi,{upgrade:async(r,i,a,s)=>{if(i<2||!r.objectStoreNames.contains(Pe))return;const c=s.objectStore(Pe),l=await c.index("fcmSenderId").get(e);if(await c.clear(),!!l){if(i===2){const o=l;if(!o.auth||!o.p256dh||!o.endpoint)return;t={token:o.fcmToken,createTime:o.createTime??Date.now(),subscriptionOptions:{auth:o.auth,p256dh:o.p256dh,endpoint:o.endpoint,swScope:o.swScope,vapidKey:typeof o.vapidKey=="string"?o.vapidKey:y(o.vapidKey)}}}else if(i===3){const o=l;t={token:o.fcmToken,createTime:o.createTime,subscriptionOptions:{auth:y(o.auth),p256dh:y(o.p256dh),endpoint:o.endpoint,swScope:o.swScope,vapidKey:y(o.vapidKey)}}}else if(i===4){const o=l;t={token:o.fcmToken,createTime:o.createTime,subscriptionOptions:{auth:y(o.auth),p256dh:y(o.p256dh),endpoint:o.endpoint,swScope:o.swScope,vapidKey:y(o.vapidKey)}}}}}})).close(),await W(X),await W("fcm_vapid_details_db"),await W("undefined"),Ri(t)?t:null}function Ri(e){if(!e||!e.subscriptionOptions)return!1;const{subscriptionOptions:t}=e;return typeof e.createTime=="number"&&e.createTime>0&&typeof e.token=="string"&&e.token.length>0&&typeof t.auth=="string"&&t.auth.length>0&&typeof t.p256dh=="string"&&t.p256dh.length>0&&typeof t.endpoint=="string"&&t.endpoint.length>0&&typeof t.swScope=="string"&&t.swScope.length>0&&typeof t.vapidKey=="string"&&t.vapidKey.length>0}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Ni="firebase-messaging-database",Bi=1,R="firebase-messaging-store";let Q=null;function _t(){return Q||(Q=x(Ni,Bi,{upgrade:(e,t)=>{switch(t){case 0:e.createObjectStore(R)}}})),Q}async function $i(e){const t=St(e),r=await(await _t()).transaction(R).objectStore(R).get(t);if(r)return r;{const i=await Mi(e.appConfig.senderId);if(i)return await fe(e,i),i}}async function fe(e,t){const n=St(e),i=(await _t()).transaction(R,"readwrite");return await i.objectStore(R).put(t,n),await i.done,t}function St({appConfig:e}){return e.appId}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Pi={"missing-app-config-values":'Missing App configuration value: "{$valueName}"',"only-available-in-window":"This method is available in a Window context.","only-available-in-sw":"This method is available in a service worker context.","permission-default":"The notification permission was not granted and dismissed instead.","permission-blocked":"The notification permission was not granted and blocked instead.","unsupported-browser":"This browser doesn't support the API's required to use the Firebase SDK.","indexed-db-unsupported":"This browser doesn't support indexedDb.open() (ex. Safari iFrame, Firefox Private Browsing, etc)","failed-service-worker-registration":"We are unable to register the default service worker. {$browserErrorMessage}","token-subscribe-failed":"A problem occurred while subscribing the user to FCM: {$errorInfo}","token-subscribe-no-token":"FCM returned no token when subscribing the user to push.","token-unsubscribe-failed":"A problem occurred while unsubscribing the user from FCM: {$errorInfo}","token-update-failed":"A problem occurred while updating the user from FCM: {$errorInfo}","token-update-no-token":"FCM returned no token when updating the user to push.","use-sw-after-get-token":"The useServiceWorker() method may only be called once and must be called before calling getToken() to ensure your service worker is used.","invalid-sw-registration":"The input to useServiceWorker() must be a ServiceWorkerRegistration.","invalid-bg-handler":"The input to setBackgroundMessageHandler() must be a function.","invalid-vapid-key":"The public VAPID key must be a string.","use-vapid-key-after-get-token":"The usePublicVapidKey() method may only be called once and must be called before calling getToken() to ensure your VAPID key is used."},m=new N("messaging","Messaging",Pi);/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Fi(e,t){const n=await pe(e),r=vt(t),i={method:"POST",headers:n,body:JSON.stringify(r)};let a;try{a=await(await fetch(he(e.appConfig),i)).json()}catch(s){throw m.create("token-subscribe-failed",{errorInfo:s==null?void 0:s.toString()})}if(a.error){const s=a.error.message;throw m.create("token-subscribe-failed",{errorInfo:s})}if(!a.token)throw m.create("token-subscribe-no-token");return a.token}async function Li(e,t){const n=await pe(e),r=vt(t.subscriptionOptions),i={method:"PATCH",headers:n,body:JSON.stringify(r)};let a;try{a=await(await fetch(`${he(e.appConfig)}/${t.token}`,i)).json()}catch(s){throw m.create("token-update-failed",{errorInfo:s==null?void 0:s.toString()})}if(a.error){const s=a.error.message;throw m.create("token-update-failed",{errorInfo:s})}if(!a.token)throw m.create("token-update-no-token");return a.token}async function xi(e,t){const r={method:"DELETE",headers:await pe(e)};try{const a=await(await fetch(`${he(e.appConfig)}/${t}`,r)).json();if(a.error){const s=a.error.message;throw m.create("token-unsubscribe-failed",{errorInfo:s})}}catch(i){throw m.create("token-unsubscribe-failed",{errorInfo:i==null?void 0:i.toString()})}}function he({projectId:e}){return`${Si}/projects/${e}/registrations`}async function pe({appConfig:e,installations:t}){const n=await t.getToken();return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":e.apiKey,"x-goog-firebase-installations-auth":`FIS ${n}`})}function vt({p256dh:e,auth:t,endpoint:n,vapidKey:r}){const i={web:{endpoint:n,auth:t,p256dh:e}};return r!==Tt&&(i.web.applicationPubKey=r),i}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const Hi=10080*60*1e3;async function ji(e){const t=await Ui(e.swRegistration,e.vapidKey),n={vapidKey:e.vapidKey,swScope:e.swRegistration.scope,endpoint:t.endpoint,auth:y(t.getKey("auth")),p256dh:y(t.getKey("p256dh"))},r=await $i(e.firebaseDependencies);if(r){if(Ki(r.subscriptionOptions,n))return Date.now()>=r.createTime+Hi?Vi(e,{token:r.token,createTime:Date.now(),subscriptionOptions:n}):r.token;try{await xi(e.firebaseDependencies,r.token)}catch(i){console.warn(i)}return Fe(e.firebaseDependencies,n)}else return Fe(e.firebaseDependencies,n)}async function Vi(e,t){try{const n=await Li(e.firebaseDependencies,t),r={...t,token:n,createTime:Date.now()};return await fe(e.firebaseDependencies,r),n}catch(n){throw n}}async function Fe(e,t){const r={token:await Fi(e,t),createTime:Date.now(),subscriptionOptions:t};return await fe(e,r),r.token}async function Ui(e,t){const n=await e.pushManager.getSubscription();return n||e.pushManager.subscribe({userVisibleOnly:!0,applicationServerKey:ki(t)})}function Ki(e,t){const n=t.vapidKey===e.vapidKey,r=t.endpoint===e.endpoint,i=t.auth===e.auth,a=t.p256dh===e.p256dh;return n&&r&&i&&a}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Le(e){const t={from:e.from,collapseKey:e.collapse_key,messageId:e.fcmMessageId};return Wi(t,e),zi(t,e),qi(t,e),t}function Wi(e,t){if(!t.notification)return;e.notification={};const n=t.notification.title;n&&(e.notification.title=n);const r=t.notification.body;r&&(e.notification.body=r);const i=t.notification.image;i&&(e.notification.image=i);const a=t.notification.icon;a&&(e.notification.icon=a)}function zi(e,t){t.data&&(e.data=t.data)}function qi(e,t){var i,a,s,c;if(!t.fcmOptions&&!((i=t.notification)!=null&&i.click_action))return;e.fcmOptions={};const n=((a=t.fcmOptions)==null?void 0:a.link)??((s=t.notification)==null?void 0:s.click_action);n&&(e.fcmOptions.link=n);const r=(c=t.fcmOptions)==null?void 0:c.analytics_label;r&&(e.fcmOptions.analyticsLabel=r)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Gi(e){return typeof e=="object"&&!!e&&At in e}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Yi(e){if(!e||!e.options)throw Z("App Configuration Object");if(!e.name)throw Z("App Name");const t=["projectId","apiKey","appId","messagingSenderId"],{options:n}=e;for(const r of t)if(!n[r])throw Z(r);return{appName:e.name,projectId:n.projectId,apiKey:n.apiKey,appId:n.appId,senderId:n.messagingSenderId}}function Z(e){return m.create("missing-app-config-values",{valueName:e})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */class Ji{constructor(t,n,r){this.deliveryMetricsExportedToBigQueryEnabled=!1,this.onBackgroundMessageHandler=null,this.onMessageHandler=null,this.logEvents=[],this.isLogServiceStarted=!1;const i=Yi(t);this.firebaseDependencies={app:t,appConfig:i,installations:n,analyticsProvider:r}}_delete(){return Promise.resolve()}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Xi(e){try{e.swRegistration=await navigator.serviceWorker.register(Ai,{scope:_i}),e.swRegistration.update().catch(()=>{}),await Qi(e.swRegistration)}catch(t){throw m.create("failed-service-worker-registration",{browserErrorMessage:t==null?void 0:t.message})}}async function Qi(e){return new Promise((t,n)=>{const r=setTimeout(()=>n(new Error(`Service worker not registered after ${Be} ms`)),Be),i=e.installing||e.waiting;e.active?(clearTimeout(r),t()):i?i.onstatechange=a=>{var s;((s=a.target)==null?void 0:s.state)==="activated"&&(i.onstatechange=null,clearTimeout(r),t())}:(clearTimeout(r),n(new Error("No incoming service worker found.")))})}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function Zi(e,t){if(!t&&!e.swRegistration&&await Xi(e),!(!t&&e.swRegistration)){if(!(t instanceof ServiceWorkerRegistration))throw m.create("invalid-sw-registration");e.swRegistration=t}}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function ea(e,t){t?e.vapidKey=t:e.vapidKey||(e.vapidKey=Tt)}/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function ta(e,t){if(!navigator)throw m.create("only-available-in-window");if(Notification.permission==="default"&&await Notification.requestPermission(),Notification.permission!=="granted")throw m.create("permission-blocked");return await ea(e,t==null?void 0:t.vapidKey),await Zi(e,t==null?void 0:t.serviceWorkerRegistration),ji(e)}/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function na(e,t,n){const r=ra(t);(await e.firebaseDependencies.analyticsProvider.get()).logEvent(r,{message_id:n[At],message_name:n[vi],message_time:n[Di],message_device_time:Math.floor(Date.now()/1e3)})}function ra(e){switch(e){case M.NOTIFICATION_CLICKED:return"notification_open";case M.PUSH_RECEIVED:return"notification_foreground";default:throw new Error}}/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */async function ia(e,t){const n=t.data;if(!n.isFirebaseMessaging)return;e.onMessageHandler&&n.messageType===M.PUSH_RECEIVED&&(typeof e.onMessageHandler=="function"?e.onMessageHandler(Le(n)):e.onMessageHandler.next(Le(n)));const r=n.data;Gi(r)&&r[Ci]==="1"&&await na(e,n.messageType,r)}const xe="@firebase/messaging",He="0.12.23";/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */const aa=e=>{const t=new Ji(e.getProvider("app").getImmediate(),e.getProvider("installations-internal").getImmediate(),e.getProvider("analytics-internal"));return navigator.serviceWorker.addEventListener("message",n=>ia(t,n)),t},sa=e=>{const t=e.getProvider("messaging").getImmediate();return{getToken:r=>ta(t,r)}};function oa(){T(new w("messaging",aa,"PUBLIC")),T(new w("messaging-internal",sa,"PRIVATE")),b(xe,He),b(xe,He,"esm2020")}oa();const ca={apiKey:"AIzaSyAsbepfeItKyWtyJQG9maPA9B4UgdAtTSQ",authDomain:"busaty-app.firebaseapp.com",projectId:"busaty-app",storageBucket:"busaty-app.firebasestorage.app",messagingSenderId:"66220404803",appId:"1:66220404803:web:7f65593649a98ba46124f6",measurementId:"G-T869W7J5H3"},la=Xe(ca);yi(la);require("./bootstrap");"serviceWorker"in navigator&&navigator.serviceWorker.register("/firebase-messaging-sw.js").then(e=>{console.log("Service Worker registered with scope:",e.scope)}).catch(e=>{console.error("Service Worker registration failed:",e)});
