define(["../core","./var/nonce","./var/rquery","../ajax"],function(n,a,o){var t=[],r=/(=)\?(?=&|$)|\?\?/;n.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var o=t.pop()||n.expando+"_"+a++;return this[o]=!0,o}}),n.ajaxPrefilter("json jsonp",function(a,s,e){var c,l,p,i=!1!==a.jsonp&&(r.test(a.url)?"url":"string"==typeof a.data&&!(a.contentType||"").indexOf("application/x-www-form-urlencoded")&&r.test(a.data)&&"data");return i||"jsonp"===a.dataTypes[0]?(c=a.jsonpCallback=n.isFunction(a.jsonpCallback)?a.jsonpCallback():a.jsonpCallback,i?a[i]=a[i].replace(r,"$1"+c):!1!==a.jsonp&&(a.url+=(o.test(a.url)?"&":"?")+a.jsonp+"="+c),a.converters["script json"]=function(){return p||n.error(c+" was not called"),p[0]},a.dataTypes[0]="json",l=window[c],window[c]=function(){p=arguments},e.always(function(){window[c]=l,a[c]&&(a.jsonpCallback=s.jsonpCallback,t.push(c)),p&&n.isFunction(l)&&l(p[0]),p=l=void 0}),"script"):void 0})});