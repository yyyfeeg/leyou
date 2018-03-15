"object" != typeof JSON && (JSON = {}),
function() {
    "use strict";
    function f(t) {
        return 10 > t ? "0" + t: t
    }
    function quote(t) {
        return escapable.lastIndex = 0,
        escapable.test(t) ? '"' + t.replace(escapable,
        function(t) {
            var e = meta[t];
            return "string" == typeof e ? e: "\\u" + ("0000" + t.charCodeAt(0).toString(16)).slice( - 4)
        }) + '"': '"' + t + '"'
    }
    function str(t, e) {
        var n, r, o, u, i, a = gap,
        f = e[t];
        switch (f && "object" == typeof f && "function" == typeof f.toJSON && (f = f.toJSON(t)), "function" == typeof rep && (f = rep.call(e, t, f)), typeof f) {
        case "string":
            return quote(f);
        case "number":
            return isFinite(f) ? String(f) : "null";
        case "boolean":
        case "null":
            return String(f);
        case "object":
            if (!f) return "null";
            if (gap += indent, i = [], "[object Array]" === Object.prototype.toString.apply(f)) {
                for (u = f.length, n = 0; u > n; n += 1) i[n] = str(n, f) || "null";
                return o = 0 === i.length ? "[]": gap ? "[\n" + gap + i.join(",\n" + gap) + "\n" + a + "]": "[" + i.join(",") + "]",
                gap = a,
                o
            }
            if (rep && "object" == typeof rep) for (u = rep.length, n = 0; u > n; n += 1)"string" == typeof rep[n] && (r = rep[n], o = str(r, f), o && i.push(quote(r) + (gap ? ": ": ":") + o));
            else for (r in f) Object.prototype.hasOwnProperty.call(f, r) && (o = str(r, f), o && i.push(quote(r) + (gap ? ": ": ":") + o));
            return o = 0 === i.length ? "{}": gap ? "{\n" + gap + i.join(",\n" + gap) + "\n" + a + "}": "{" + i.join(",") + "}",
            gap = a,
            o
        }
    }
    "function" != typeof Date.prototype.toJSON && (Date.prototype.toJSON = function() {
        return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z": null
    },
    String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function() {
        return this.valueOf()
    });
    var cx, escapable, gap, indent, meta, rep;
    "function" != typeof JSON.stringify && (escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, meta = {
        "\b": "\\b",
        "	": "\\t",
        "\n": "\\n",
        "\f": "\\f",
        "\r": "\\r",
        '"': '\\"',
        "\\": "\\\\"
    },
    JSON.stringify = function(t, e, n) {
        var r;
        if (gap = "", indent = "", "number" == typeof n) for (r = 0; n > r; r += 1) indent += " ";
        else "string" == typeof n && (indent = n);
        if (rep = e, e && "function" != typeof e && ("object" != typeof e || "number" != typeof e.length)) throw new Error("JSON.stringify");
        return str("", {
            "": t
        })
    }),
    "function" != typeof JSON.parse && (cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, JSON.parse = function(text, reviver) {
        function walk(t, e) {
            var n, r, o = t[e];
            if (o && "object" == typeof o) for (n in o) Object.prototype.hasOwnProperty.call(o, n) && (r = walk(o, n), void 0 !== r ? o[n] = r: delete o[n]);
            return reviver.call(t, e, o)
        }
        var j;
        if (text = String(text), cx.lastIndex = 0, cx.test(text) && (text = text.replace(cx,
        function(t) {
            return "\\u" + ("0000" + t.charCodeAt(0).toString(16)).slice( - 4)
        })), /^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) return j = eval("(" + text + ")"),
        "function" == typeof reviver ? walk({
            "": j
        },
        "") : j;
        throw new SyntaxError("JSON.parse")
    })
} ();
var BJ_REPORT = function(t) {
    if (t.BJ_REPORT) return t.BJ_REPORT;
    var e = [],
    n = {
        id: 0,
        uin: 0,
        url: "",
        combo: 1,
        ext: {},
        level: 4,
        ignore: [],
        random: 1,
        delay: 1e3,
        submit: null
    },
    r = function(t, e) {
        return Object.prototype.toString.call(t) === "[object " + (e || "Object") + "]"
    },
    o = function(t) {
        var e = typeof t;
        return "object" === e && !!t
    },
    u = function(t) {
        return null === t ? !0 : r(t, "Number") ? !1 : !t
    },
    i = (t.onerror,
    function(t) {
        try {
            if (t.stack) {
                var e = t.stack.match("https?://[^\n]+");
                e = e ? e[0] : "";
                var n = e.match(":(\\d+):(\\d+)");
                n || (n = [0, 0, 0]);
                var r = a(t);
                return {
                    msg: r,
                    rowNum: n[1],
                    colNum: n[2],
                    target: e.replace(n[0], "")
                }
            }
            return t.name && t.message && t.description ? {
                msg: JSON.stringify(t)
            }: t
        } catch(o) {
            return t
        }
    }),
    a = function(t) {
        var e = t.stack.replace(/\n/gi, "").split(/\bat\b/).slice(0, 5).join("@").replace(/\?[^:]+/gi, ""),
        n = t.toString();
        return e.indexOf(n) < 0 && (e = n + "@" + e),
        e
    },
    f = function(t, e) {
        var r = [],
        i = [],
        a = [];
        if (o(t)) {
            t.level = t.level || n.level;
            for (var f in t) {
                var s = t[f];
                if (!u(s)) {
                    if (o(s)) try {
                        s = JSON.stringify(s)
                    } catch(c) {
                        s = "[BJ_REPORT detect value stringify error] " + c.toString()
                    }
                    a.push(f + ":" + s),
                    r.push(f + "=" + encodeURIComponent(s)),
                    i.push(f + "[" + e + "]=" + encodeURIComponent(s))
                }
            }
        }
        return [i.join("&"), a.join(","), r.join("&")]
    },
    s = [],
    c = function(t) {
        if (n.submit) n.submit(t);
        else {
            var e = new Image;
            s.push(e),
            e.src = t
        }
    },
    p = [],
    l = 0,
    g = function(t) {
        if (n.report) {
            for (; e.length;) {
                var o = !1,
                u = e.shift(),
                i = f(u, p.length);
                if (r(n.ignore, "Array")) for (var a = 0,
                s = n.ignore.length; s > a; a++) {
                    var g = n.ignore[a];
                    if (r(g, "RegExp") && g.test(i[1]) || r(g, "Function") && g(u, i[1])) {
                        o = !0;
                        break
                    }
                }
                o || (n.combo ? p.push(i[0]) : c(n.report + i[2] + "&_t=" + +new Date), n.onReport && n.onReport(n.id, u))
            }
            var y = p.length;
            if (y) {
                var d = function() {
                    clearTimeout(l),
                    c(n.report + p.join("&") + "&count=" + y + "&_t=" + +new Date),
                    l = 0,
                    p = []
                };
                t ? d() : l || (l = setTimeout(d, n.delay))
            }
        }
    },
    y = {
        push: function(t) {
            return Math.random() >= n.random ? y: (e.push(o(t) ? i(t) : {
                msg: t
            }), g(), y)
        },
        report: function(t) {
            return t && y.push(t),
            g(!0),
            y
        },
        info: function(t) {
            return t ? (o(t) ? t.level = 2 : t = {
                msg: t,
                level: 2
            },
            y.push(t), y) : y
        },
        debug: function(t) {
            return t ? (o(t) ? t.level = 1 : t = {
                msg: t,
                level: 1
            },
            y.push(t), y) : y
        },
        init: function(t) {
            if (o(t)) for (var e in t) n[e] = t[e];
            var r = parseInt(n.id, 10);
            return r && (/qq\.com$/gi.test(window.location.hostname) && (n.url || (n.url = "//badjs2.qq.com/badjs"), n.uin || (n.uin = parseInt((document.cookie.match(/\buin=\D+(\d+)/) || [])[1], 10))), n.report = n.url + "?id=" + r + "&uin=" + n.uin + "&from=" + encodeURIComponent(location.href) + "&ext=" + JSON.stringify(n.ext) + "&"),
            y
        },
        __onerror__: t.onerror
    };
    return "undefined" != typeof console && console.error && setTimeout(function() {
        var t = ((location.hash || "").match(/([#&])BJ_ERROR=([^&$]+)/) || [])[2];
        t && console.error("BJ_ERROR", decodeURIComponent(t).replace(/(:\d+:\d+)\s*/g, "$1\n"))
    },
    0),
    y
} (window);
"undefined" != typeof exports && ("undefined" != typeof module && module.exports && (exports = module.exports = BJ_REPORT), exports.BJ_REPORT = BJ_REPORT),
function(t) {
    if (t.BJ_REPORT) {
        var e, n = function(e) {
            t.BJ_REPORT.report(e)
        },
        r = t.BJ_REPORT.tryJs = function(t) {
            return t && (n = t),
            r
        },
        o = function(t, e) {
            var n;
            for (n in e) t[n] = e[n]
        },
        u = function(t) {
            return "function" == typeof t
        },
        i = function(r, o) {
            return function() {
                try {
                    return r.apply(this, o || arguments)
                } 
                catch(u) {
//                  if (n(u), u.stack && console && console.error && console.error("[BJ-REPORT]", u.stack), !e) {
//                      var i = t.onerror;
//                      t.onerror = function() {},
//                      e = setTimeout(function() {
//                          t.onerror = i,
//                          e = null
//                      },
//                      50)
//                  }
//                  throw u
                }
            }
        },
        a = function(t) {
            return function() {
                for (var e, n = [], r = 0, o = arguments.length; o > r; r++) e = arguments[r],
                u(e) && (e = i(e)),
                n.push(e);
                return t.apply(this, n)
            }
        },
        f = function(t) {
            return function(e, n) {
                if ("string" == typeof e) try {
                    e = new Function(e)
                } catch(r) {
                    throw r
                }
                var o = [].slice.call(arguments, 2);
                return e = i(e, o.length && o),
                t(e, n)
            }
        },
        s = function(t, e) {
            return function() {
                for (var n, r, o = [], a = 0, f = arguments.length; f > a; a++) n = arguments[a],
                u(n) && (r = i(n)) && (n.tryWrap = r) && (n = r),
                o.push(n);
                return t.apply(e || this, o)
            }
        },
        c = function(t) {
            var e, n;
            for (e in t) n = t[e],
            u(n) && (t[e] = i(n));
            return t
        };
        r.spyJquery = function() {
            var e = t.$;
            if (!e || !e.event) return r;
            var n, o;
            e.zepto ? (n = e.fn.on, o = e.fn.off, e.fn.on = s(n), e.fn.off = function() {
                for (var t, e = [], n = 0, r = arguments.length; r > n; n++) t = arguments[n],
                u(t) && t.tryWrap && (t = t.tryWrap),
                e.push(t);
                return o.apply(this, e)
            }) : window.jQuery && (n = e.event.add, o = e.event.remove, e.event.add = s(n), e.event.remove = function() {
                for (var t, e = [], n = 0, r = arguments.length; r > n; n++) t = arguments[n],
                u(t) && t.tryWrap && (t = t.tryWrap),
                e.push(t);
                return o.apply(this, e)
            });
            var i = e.ajax;
            return i && (e.ajax = function(t, n) {
                return n || (n = t, t = void 0),
                c(n),
                t ? i.call(e, t, n) : i.call(e, n)
            }),
            r
        },
        r.spyModules = function() {
            var e = t.require,
            n = t.define;
            return n && n.amd && e && (t.require = a(e), o(t.require, e), t.define = a(n), o(t.define, n)),
            t.seajs && n && (t.define = function() {
                for (var t, e = [], r = 0, o = arguments.length; o > r; r++) t = arguments[r],
                u(t) && (t = i(t), t.toString = function(t) {
                    return function() {
                        return t.toString()
                    }
                } (arguments[r])),
                e.push(t);
                return n.apply(this, e)
            },
            t.seajs.use = a(t.seajs.use), o(t.define, n)),
            r
        },
        r.spySystem = function() {
            return t.setTimeout = f(t.setTimeout),
            t.setInterval = f(t.setInterval),
            r
        },
        r.spyCustom = function(t) {
            return u(t) ? i(t) : c(t)
        },
        r.spyAll = function() {
            return r.spyJquery().spyModules().spySystem(),
            r
        }
    }
} (window),
BJ_REPORT.init({
    id: 1,
    combo: 1,
    delay: 1e3,
    url: "/badjs",
    ignore: [/Script error/i]
});; !
function(e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function(e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    }: t(e)
} ("undefined" != typeof window ? window: this,
function(e, t) {
    function n(e) {
        var t = "length" in e && e.length,
        n = it.type(e);
        return "function" === n || it.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e
    }
    function r(e, t, n) {
        if (it.isFunction(t)) return it.grep(e,
        function(e, r) {
            return !! t.call(e, r, e) !== n
        });
        if (t.nodeType) return it.grep(e,
        function(e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (ft.test(t)) return it.filter(t, e, n);
            t = it.filter(t, e)
        }
        return it.grep(e,
        function(e) {
            return it.inArray(e, t) >= 0 !== n
        })
    }
    function i(e, t) {
        do e = e[t];
        while (e && 1 !== e.nodeType);
        return e
    }
    function o(e) {
        var t = xt[e] = {};
        return it.each(e.match(bt) || [],
        function(e, n) {
            t[n] = !0
        }),
        t
    }
    function a() {
        ht.addEventListener ? (ht.removeEventListener("DOMContentLoaded", s, !1), e.removeEventListener("load", s, !1)) : (ht.detachEvent("onreadystatechange", s), e.detachEvent("onload", s))
    }
    function s() { (ht.addEventListener || "load" === event.type || "complete" === ht.readyState) && (a(), it.ready())
    }
    function u(e, t, n) {
        if (void 0 === n && 1 === e.nodeType) {
            var r = "data-" + t.replace(Et, "-$1").toLowerCase();
            if (n = e.getAttribute(r), "string" == typeof n) {
                try {
                    n = "true" === n ? !0 : "false" === n ? !1 : "null" === n ? null: +n + "" === n ? +n: Nt.test(n) ? it.parseJSON(n) : n
                } catch(i) {}
                it.data(e, t, n)
            } else n = void 0
        }
        return n
    }
    function l(e) {
        var t;
        for (t in e) if (("data" !== t || !it.isEmptyObject(e[t])) && "toJSON" !== t) return ! 1;
        return ! 0
    }
    function c(e, t, n, r) {
        if (it.acceptData(e)) {
            var i, o, a = it.expando,
            s = e.nodeType,
            u = s ? it.cache: e,
            l = s ? e[a] : e[a] && a;
            if (l && u[l] && (r || u[l].data) || void 0 !== n || "string" != typeof t) return l || (l = s ? e[a] = J.pop() || it.guid++:a),
            u[l] || (u[l] = s ? {}: {
                toJSON: it.noop
            }),
            ("object" == typeof t || "function" == typeof t) && (r ? u[l] = it.extend(u[l], t) : u[l].data = it.extend(u[l].data, t)),
            o = u[l],
            r || (o.data || (o.data = {}), o = o.data),
            void 0 !== n && (o[it.camelCase(t)] = n),
            "string" == typeof t ? (i = o[t], null == i && (i = o[it.camelCase(t)])) : i = o,
            i
        }
    }
    function d(e, t, n) {
        if (it.acceptData(e)) {
            var r, i, o = e.nodeType,
            a = o ? it.cache: e,
            s = o ? e[it.expando] : it.expando;
            if (a[s]) {
                if (t && (r = n ? a[s] : a[s].data)) {
                    it.isArray(t) ? t = t.concat(it.map(t, it.camelCase)) : t in r ? t = [t] : (t = it.camelCase(t), t = t in r ? [t] : t.split(" ")),
                    i = t.length;
                    for (; i--;) delete r[t[i]];
                    if (n ? !l(r) : !it.isEmptyObject(r)) return
                } (n || (delete a[s].data, l(a[s]))) && (o ? it.cleanData([e], !0) : nt.deleteExpando || a != a.window ? delete a[s] : a[s] = null)
            }
        }
    }
    function f() {
        return ! 0
    }
    function p() {
        return ! 1
    }
    function h() {
        try {
            return ht.activeElement
        } catch(e) {}
    }
    function m(e) {
        var t = Ft.split("|"),
        n = e.createDocumentFragment();
        if (n.createElement) for (; t.length;) n.createElement(t.pop());
        return n
    }
    function g(e, t) {
        var n, r, i = 0,
        o = typeof e.getElementsByTagName !== Ct ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll !== Ct ? e.querySelectorAll(t || "*") : void 0;
        if (!o) for (o = [], n = e.childNodes || e; null != (r = n[i]); i++) ! t || it.nodeName(r, t) ? o.push(r) : it.merge(o, g(r, t));
        return void 0 === t || t && it.nodeName(e, t) ? it.merge([e], o) : o
    }
    function v(e) {
        jt.test(e.type) && (e.defaultChecked = e.checked)
    }
    function y(e, t) {
        return it.nodeName(e, "table") && it.nodeName(11 !== t.nodeType ? t: t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }
    function b(e) {
        return e.type = (null !== it.find.attr(e, "type")) + "/" + e.type,
        e
    }
    function x(e) {
        var t = Vt.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"),
        e
    }
    function w(e, t) {
        for (var n, r = 0; null != (n = e[r]); r++) it._data(n, "globalEval", !t || it._data(t[r], "globalEval"))
    }
    function T(e, t) {
        if (1 === t.nodeType && it.hasData(e)) {
            var n, r, i, o = it._data(e),
            a = it._data(t, o),
            s = o.events;
            if (s) {
                delete a.handle,
                a.events = {};
                for (n in s) for (r = 0, i = s[n].length; i > r; r++) it.event.add(t, n, s[n][r])
            }
            a.data && (a.data = it.extend({},
            a.data))
        }
    }
    function C(e, t) {
        var n, r, i;
        if (1 === t.nodeType) {
            if (n = t.nodeName.toLowerCase(), !nt.noCloneEvent && t[it.expando]) {
                i = it._data(t);
                for (r in i.events) it.removeEvent(t, r, i.handle);
                t.removeAttribute(it.expando)
            }
            "script" === n && t.text !== e.text ? (b(t).text = e.text, x(t)) : "object" === n ? (t.parentNode && (t.outerHTML = e.outerHTML), nt.html5Clone && e.innerHTML && !it.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)) : "input" === n && jt.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : "option" === n ? t.defaultSelected = t.selected = e.defaultSelected: ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
        }
    }
    function N(t, n) {
        var r, i = it(n.createElement(t)).appendTo(n.body),
        o = e.getDefaultComputedStyle && (r = e.getDefaultComputedStyle(i[0])) ? r.display: it.css(i[0], "display");
        return i.detach(),
        o
    }
    function E(e) {
        var t = ht,
        n = Zt[e];
        return n || (n = N(e, t), "none" !== n && n || (Kt = (Kt || it("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement), t = (Kt[0].contentWindow || Kt[0].contentDocument).document, t.write(), t.close(), n = N(e, t), Kt.detach()), Zt[e] = n),
        n
    }
    function k(e, t) {
        return {
            get: function() {
                var n = e();
                return null != n ? n ? void delete this.get: (this.get = t).apply(this, arguments) : void 0
            }
        }
    }
    function S(e, t) {
        if (t in e) return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), r = t, i = pn.length; i--;) if (t = pn[i] + n, t in e) return t;
        return r
    }
    function A(e, t) {
        for (var n, r, i, o = [], a = 0, s = e.length; s > a; a++) r = e[a],
        r.style && (o[a] = it._data(r, "olddisplay"), n = r.style.display, t ? (o[a] || "none" !== n || (r.style.display = ""), "" === r.style.display && At(r) && (o[a] = it._data(r, "olddisplay", E(r.nodeName)))) : (i = At(r), (n && "none" !== n || !i) && it._data(r, "olddisplay", i ? n: it.css(r, "display"))));
        for (a = 0; s > a; a++) r = e[a],
        r.style && (t && "none" !== r.style.display && "" !== r.style.display || (r.style.display = t ? o[a] || "": "none"));
        return e
    }
    function D(e, t, n) {
        var r = ln.exec(t);
        return r ? Math.max(0, r[1] - (n || 0)) + (r[2] || "px") : t
    }
    function j(e, t, n, r, i) {
        for (var o = n === (r ? "border": "content") ? 4 : "width" === t ? 1 : 0, a = 0; 4 > o; o += 2)"margin" === n && (a += it.css(e, n + St[o], !0, i)),
        r ? ("content" === n && (a -= it.css(e, "padding" + St[o], !0, i)), "margin" !== n && (a -= it.css(e, "border" + St[o] + "Width", !0, i))) : (a += it.css(e, "padding" + St[o], !0, i), "padding" !== n && (a += it.css(e, "border" + St[o] + "Width", !0, i)));
        return a
    }
    function L(e, t, n) {
        var r = !0,
        i = "width" === t ? e.offsetWidth: e.offsetHeight,
        o = en(e),
        a = nt.boxSizing && "border-box" === it.css(e, "boxSizing", !1, o);
        if (0 >= i || null == i) {
            if (i = tn(e, t, o), (0 > i || null == i) && (i = e.style[t]), rn.test(i)) return i;
            r = a && (nt.boxSizingReliable() || i === e.style[t]),
            i = parseFloat(i) || 0
        }
        return i + j(e, t, n || (a ? "border": "content"), r, o) + "px"
    }
    function H(e, t, n, r, i) {
        return new H.prototype.init(e, t, n, r, i)
    }
    function q() {
        return setTimeout(function() {
            hn = void 0
        }),
        hn = it.now()
    }
    function _(e, t) {
        var n, r = {
            height: e
        },
        i = 0;
        for (t = t ? 1 : 0; 4 > i; i += 2 - t) n = St[i],
        r["margin" + n] = r["padding" + n] = e;
        return t && (r.opacity = r.width = e),
        r
    }
    function M(e, t, n) {
        for (var r, i = (xn[t] || []).concat(xn["*"]), o = 0, a = i.length; a > o; o++) if (r = i[o].call(n, t, e)) return r
    }
    function F(e, t, n) {
        var r, i, o, a, s, u, l, c, d = this,
        f = {},
        p = e.style,
        h = e.nodeType && At(e),
        m = it._data(e, "fxshow");
        n.queue || (s = it._queueHooks(e, "fx"), null == s.unqueued && (s.unqueued = 0, u = s.empty.fire, s.empty.fire = function() {
            s.unqueued || u()
        }), s.unqueued++, d.always(function() {
            d.always(function() {
                s.unqueued--,
                it.queue(e, "fx").length || s.empty.fire()
            })
        })),
        1 === e.nodeType && ("height" in t || "width" in t) && (n.overflow = [p.overflow, p.overflowX, p.overflowY], l = it.css(e, "display"), c = "none" === l ? it._data(e, "olddisplay") || E(e.nodeName) : l, "inline" === c && "none" === it.css(e, "float") && (nt.inlineBlockNeedsLayout && "inline" !== E(e.nodeName) ? p.zoom = 1 : p.display = "inline-block")),
        n.overflow && (p.overflow = "hidden", nt.shrinkWrapBlocks() || d.always(function() {
            p.overflow = n.overflow[0],
            p.overflowX = n.overflow[1],
            p.overflowY = n.overflow[2]
        }));
        for (r in t) if (i = t[r], gn.exec(i)) {
            if (delete t[r], o = o || "toggle" === i, i === (h ? "hide": "show")) {
                if ("show" !== i || !m || void 0 === m[r]) continue;
                h = !0
            }
            f[r] = m && m[r] || it.style(e, r)
        } else l = void 0;
        if (it.isEmptyObject(f))"inline" === ("none" === l ? E(e.nodeName) : l) && (p.display = l);
        else {
            m ? "hidden" in m && (h = m.hidden) : m = it._data(e, "fxshow", {}),
            o && (m.hidden = !h),
            h ? it(e).show() : d.done(function() {
                it(e).hide()
            }),
            d.done(function() {
                var t;
                it._removeData(e, "fxshow");
                for (t in f) it.style(e, t, f[t])
            });
            for (r in f) a = M(h ? m[r] : 0, r, d),
            r in m || (m[r] = a.start, h && (a.end = a.start, a.start = "width" === r || "height" === r ? 1 : 0))
        }
    }
    function O(e, t) {
        var n, r, i, o, a;
        for (n in e) if (r = it.camelCase(n), i = t[r], o = e[n], it.isArray(o) && (i = o[1], o = e[n] = o[0]), n !== r && (e[r] = o, delete e[n]), a = it.cssHooks[r], a && "expand" in a) {
            o = a.expand(o),
            delete e[r];
            for (n in o) n in e || (e[n] = o[n], t[n] = i)
        } else t[r] = i
    }
    function B(e, t, n) {
        var r, i, o = 0,
        a = bn.length,
        s = it.Deferred().always(function() {
            delete u.elem
        }),
        u = function() {
            if (i) return ! 1;
            for (var t = hn || q(), n = Math.max(0, l.startTime + l.duration - t), r = n / l.duration || 0, o = 1 - r, a = 0, u = l.tweens.length; u > a; a++) l.tweens[a].run(o);
            return s.notifyWith(e, [l, o, n]),
            1 > o && u ? n: (s.resolveWith(e, [l]), !1)
        },
        l = s.promise({
            elem: e,
            props: it.extend({},
            t),
            opts: it.extend(!0, {
                specialEasing: {}
            },
            n),
            originalProperties: t,
            originalOptions: n,
            startTime: hn || q(),
            duration: n.duration,
            tweens: [],
            createTween: function(t, n) {
                var r = it.Tween(e, l.opts, t, n, l.opts.specialEasing[t] || l.opts.easing);
                return l.tweens.push(r),
                r
            },
            stop: function(t) {
                var n = 0,
                r = t ? l.tweens.length: 0;
                if (i) return this;
                for (i = !0; r > n; n++) l.tweens[n].run(1);
                return t ? s.resolveWith(e, [l, t]) : s.rejectWith(e, [l, t]),
                this
            }
        }),
        c = l.props;
        for (O(c, l.opts.specialEasing); a > o; o++) if (r = bn[o].call(l, e, c, l.opts)) return r;
        return it.map(c, M, l),
        it.isFunction(l.opts.start) && l.opts.start.call(e, l),
        it.fx.timer(it.extend(u, {
            elem: e,
            anim: l,
            queue: l.opts.queue
        })),
        l.progress(l.opts.progress).done(l.opts.done, l.opts.complete).fail(l.opts.fail).always(l.opts.always)
    }
    function P(e) {
        return function(t, n) {
            "string" != typeof t && (n = t, t = "*");
            var r, i = 0,
            o = t.toLowerCase().match(bt) || [];
            if (it.isFunction(n)) for (; r = o[i++];)"+" === r.charAt(0) ? (r = r.slice(1) || "*", (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n)
        }
    }
    function R(e, t, n, r) {
        function i(s) {
            var u;
            return o[s] = !0,
            it.each(e[s] || [],
            function(e, s) {
                var l = s(t, n, r);
                return "string" != typeof l || a || o[l] ? a ? !(u = l) : void 0 : (t.dataTypes.unshift(l), i(l), !1)
            }),
            u
        }
        var o = {},
        a = e === In;
        return i(t.dataTypes[0]) || !o["*"] && i("*")
    }
    function W(e, t) {
        var n, r, i = it.ajaxSettings.flatOptions || {};
        for (r in t) void 0 !== t[r] && ((i[r] ? e: n || (n = {}))[r] = t[r]);
        return n && it.extend(!0, e, n),
        e
    }
    function $(e, t, n) {
        for (var r, i, o, a, s = e.contents,
        u = e.dataTypes;
        "*" === u[0];) u.shift(),
        void 0 === i && (i = e.mimeType || t.getResponseHeader("Content-Type"));
        if (i) for (a in s) if (s[a] && s[a].test(i)) {
            u.unshift(a);
            break
        }
        if (u[0] in n) o = u[0];
        else {
            for (a in n) {
                if (!u[0] || e.converters[a + " " + u[0]]) {
                    o = a;
                    break
                }
                r || (r = a)
            }
            o = o || r
        }
        return o ? (o !== u[0] && u.unshift(o), n[o]) : void 0
    }
    function z(e, t, n, r) {
        var i, o, a, s, u, l = {},
        c = e.dataTypes.slice();
        if (c[1]) for (a in e.converters) l[a.toLowerCase()] = e.converters[a];
        for (o = c.shift(); o;) if (e.responseFields[o] && (n[e.responseFields[o]] = t), !u && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), u = o, o = c.shift()) if ("*" === o) o = u;
        else if ("*" !== u && u !== o) {
            if (a = l[u + " " + o] || l["* " + o], !a) for (i in l) if (s = i.split(" "), s[1] === o && (a = l[u + " " + s[0]] || l["* " + s[0]])) {
                a === !0 ? a = l[i] : l[i] !== !0 && (o = s[0], c.unshift(s[1]));
                break
            }
            if (a !== !0) if (a && e["throws"]) t = a(t);
            else try {
                t = a(t)
            } catch(d) {
                return {
                    state: "parsererror",
                    error: a ? d: "No conversion from " + u + " to " + o
                }
            }
        }
        return {
            state: "success",
            data: t
        }
    }
    function I(e, t, n, r) {
        var i;
        if (it.isArray(t)) it.each(t,
        function(t, i) {
            n || Jn.test(e) ? r(e, i) : I(e + "[" + ("object" == typeof i ? t: "") + "]", i, n, r)
        });
        else if (n || "object" !== it.type(t)) r(e, t);
        else for (i in t) I(e + "[" + i + "]", t[i], n, r)
    }
    function X() {
        try {
            return new e.XMLHttpRequest
        } catch(t) {}
    }
    function U() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch(t) {}
    }
    function V(e) {
        return it.isWindow(e) ? e: 9 === e.nodeType ? e.defaultView || e.parentWindow: !1
    }
    var J = [],
    Y = J.slice,
    G = J.concat,
    Q = J.push,
    K = J.indexOf,
    Z = {},
    et = Z.toString,
    tt = Z.hasOwnProperty,
    nt = {},
    rt = "1.11.3",
    it = function(e, t) {
        return new it.fn.init(e, t)
    },
    ot = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
    at = /^-ms-/,
    st = /-([\da-z])/gi,
    ut = function(e, t) {
        return t.toUpperCase()
    };
    it.fn = it.prototype = {
        jquery: rt,
        constructor: it,
        selector: "",
        length: 0,
        toArray: function() {
            return Y.call(this)
        },
        get: function(e) {
            return null != e ? 0 > e ? this[e + this.length] : this[e] : Y.call(this)
        },
        pushStack: function(e) {
            var t = it.merge(this.constructor(), e);
            return t.prevObject = this,
            t.context = this.context,
            t
        },
        each: function(e, t) {
            return it.each(this, e, t)
        },
        map: function(e) {
            return this.pushStack(it.map(this,
            function(t, n) {
                return e.call(t, n, t)
            }))
        },
        slice: function() {
            return this.pushStack(Y.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq( - 1)
        },
        eq: function(e) {
            var t = this.length,
            n = +e + (0 > e ? t: 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: Q,
        sort: J.sort,
        splice: J.splice
    },
    it.extend = it.fn.extend = function() {
        var e, t, n, r, i, o, a = arguments[0] || {},
        s = 1,
        u = arguments.length,
        l = !1;
        for ("boolean" == typeof a && (l = a, a = arguments[s] || {},
        s++), "object" == typeof a || it.isFunction(a) || (a = {}), s === u && (a = this, s--); u > s; s++) if (null != (i = arguments[s])) for (r in i) e = a[r],
        n = i[r],
        a !== n && (l && n && (it.isPlainObject(n) || (t = it.isArray(n))) ? (t ? (t = !1, o = e && it.isArray(e) ? e: []) : o = e && it.isPlainObject(e) ? e: {},
        a[r] = it.extend(l, o, n)) : void 0 !== n && (a[r] = n));
        return a
    },
    it.extend({
        expando: "jQuery" + (rt + Math.random()).replace(/\D/g, ""),
        isReady: !0,
        error: function(e) {
            throw new Error(e)
        },
        noop: function() {},
        isFunction: function(e) {
            return "function" === it.type(e)
        },
        isArray: Array.isArray ||
        function(e) {
            return "array" === it.type(e)
        },
        isWindow: function(e) {
            return null != e && e == e.window
        },
        isNumeric: function(e) {
            return ! it.isArray(e) && e - parseFloat(e) + 1 >= 0
        },
        isEmptyObject: function(e) {
            var t;
            for (t in e) return ! 1;
            return ! 0
        },
        isPlainObject: function(e) {
            var t;
            if (!e || "object" !== it.type(e) || e.nodeType || it.isWindow(e)) return ! 1;
            try {
                if (e.constructor && !tt.call(e, "constructor") && !tt.call(e.constructor.prototype, "isPrototypeOf")) return ! 1
            } catch(n) {
                return ! 1
            }
            if (nt.ownLast) for (t in e) return tt.call(e, t);
            for (t in e);
            return void 0 === t || tt.call(e, t)
        },
        type: function(e) {
            return null == e ? e + "": "object" == typeof e || "function" == typeof e ? Z[et.call(e)] || "object": typeof e
        },
        globalEval: function(t) {
            t && it.trim(t) && (e.execScript ||
            function(t) {
                e.eval.call(e, t)
            })(t)
        },
        camelCase: function(e) {
            return e.replace(at, "ms-").replace(st, ut)
        },
        nodeName: function(e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        },
        each: function(e, t, r) {
            var i, o = 0,
            a = e.length,
            s = n(e);
            if (r) {
                if (s) for (; a > o && (i = t.apply(e[o], r), i !== !1); o++);
                else for (o in e) if (i = t.apply(e[o], r), i === !1) break
            } else if (s) for (; a > o && (i = t.call(e[o], o, e[o]), i !== !1); o++);
            else for (o in e) if (i = t.call(e[o], o, e[o]), i === !1) break;
            return e
        },
        trim: function(e) {
            return null == e ? "": (e + "").replace(ot, "")
        },
        makeArray: function(e, t) {
            var r = t || [];
            return null != e && (n(Object(e)) ? it.merge(r, "string" == typeof e ? [e] : e) : Q.call(r, e)),
            r
        },
        inArray: function(e, t, n) {
            var r;
            if (t) {
                if (K) return K.call(t, e, n);
                for (r = t.length, n = n ? 0 > n ? Math.max(0, r + n) : n: 0; r > n; n++) if (n in t && t[n] === e) return n
            }
            return - 1
        },
        merge: function(e, t) {
            for (var n = +t.length,
            r = 0,
            i = e.length; n > r;) e[i++] = t[r++];
            if (n !== n) for (; void 0 !== t[r];) e[i++] = t[r++];
            return e.length = i,
            e
        },
        grep: function(e, t, n) {
            for (var r, i = [], o = 0, a = e.length, s = !n; a > o; o++) r = !t(e[o], o),
            r !== s && i.push(e[o]);
            return i
        },
        map: function(e, t, r) {
            var i, o = 0,
            a = e.length,
            s = n(e),
            u = [];
            if (s) for (; a > o; o++) i = t(e[o], o, r),
            null != i && u.push(i);
            else for (o in e) i = t(e[o], o, r),
            null != i && u.push(i);
            return G.apply([], u)
        },
        guid: 1,
        proxy: function(e, t) {
            var n, r, i;
            return "string" == typeof t && (i = e[t], t = e, e = i),
            it.isFunction(e) ? (n = Y.call(arguments, 2), r = function() {
                return e.apply(t || this, n.concat(Y.call(arguments)))
            },
            r.guid = e.guid = e.guid || it.guid++, r) : void 0
        },
        now: function() {
            return + new Date
        },
        support: nt
    }),
    it.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),
    function(e, t) {
        Z["[object " + t + "]"] = t.toLowerCase()
    });
    var lt = function(e) {
        function t(e, t, n, r) {
            var i, o, a, s, u, l, d, p, h, m;
            if ((t ? t.ownerDocument || t: R) !== H && L(t), t = t || H, n = n || [], s = t.nodeType, "string" != typeof e || !e || 1 !== s && 9 !== s && 11 !== s) return n;
            if (!r && _) {
                if (11 !== s && (i = yt.exec(e))) if (a = i[1]) {
                    if (9 === s) {
                        if (o = t.getElementById(a), !o || !o.parentNode) return n;
                        if (o.id === a) return n.push(o),
                        n
                    } else if (t.ownerDocument && (o = t.ownerDocument.getElementById(a)) && B(t, o) && o.id === a) return n.push(o),
                    n
                } else {
                    if (i[2]) return K.apply(n, t.getElementsByTagName(e)),
                    n;
                    if ((a = i[3]) && w.getElementsByClassName) return K.apply(n, t.getElementsByClassName(a)),
                    n
                }
                if (w.qsa && (!M || !M.test(e))) {
                    if (p = d = P, h = t, m = 1 !== s && e, 1 === s && "object" !== t.nodeName.toLowerCase()) {
                        for (l = E(e), (d = t.getAttribute("id")) ? p = d.replace(xt, "\\$&") : t.setAttribute("id", p), p = "[id='" + p + "'] ", u = l.length; u--;) l[u] = p + f(l[u]);
                        h = bt.test(e) && c(t.parentNode) || t,
                        m = l.join(",")
                    }
                    if (m) try {
                        return K.apply(n, h.querySelectorAll(m)),
                        n
                    } catch(g) {} finally {
                        d || t.removeAttribute("id")
                    }
                }
            }
            return S(e.replace(ut, "$1"), t, n, r)
        }
        function n() {
            function e(n, r) {
                return t.push(n + " ") > T.cacheLength && delete e[t.shift()],
                e[n + " "] = r
            }
            var t = [];
            return e
        }
        function r(e) {
            return e[P] = !0,
            e
        }
        function i(e) {
            var t = H.createElement("div");
            try {
                return !! e(t)
            } catch(n) {
                return ! 1
            } finally {
                t.parentNode && t.parentNode.removeChild(t),
                t = null
            }
        }
        function o(e, t) {
            for (var n = e.split("|"), r = e.length; r--;) T.attrHandle[n[r]] = t
        }
        function a(e, t) {
            var n = t && e,
            r = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || V) - (~e.sourceIndex || V);
            if (r) return r;
            if (n) for (; n = n.nextSibling;) if (n === t) return - 1;
            return e ? 1 : -1
        }
        function s(e) {
            return function(t) {
                var n = t.nodeName.toLowerCase();
                return "input" === n && t.type === e
            }
        }
        function u(e) {
            return function(t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }
        function l(e) {
            return r(function(t) {
                return t = +t,
                r(function(n, r) {
                    for (var i, o = e([], n.length, t), a = o.length; a--;) n[i = o[a]] && (n[i] = !(r[i] = n[i]))
                })
            })
        }
        function c(e) {
            return e && "undefined" != typeof e.getElementsByTagName && e
        }
        function d() {}
        function f(e) {
            for (var t = 0,
            n = e.length,
            r = ""; n > t; t++) r += e[t].value;
            return r
        }
        function p(e, t, n) {
            var r = t.dir,
            i = n && "parentNode" === r,
            o = $++;
            return t.first ?
            function(t, n, o) {
                for (; t = t[r];) if (1 === t.nodeType || i) return e(t, n, o)
            }: function(t, n, a) {
                var s, u, l = [W, o];
                if (a) {
                    for (; t = t[r];) if ((1 === t.nodeType || i) && e(t, n, a)) return ! 0
                } else for (; t = t[r];) if (1 === t.nodeType || i) {
                    if (u = t[P] || (t[P] = {}), (s = u[r]) && s[0] === W && s[1] === o) return l[2] = s[2];
                    if (u[r] = l, l[2] = e(t, n, a)) return ! 0
                }
            }
        }
        function h(e) {
            return e.length > 1 ?
            function(t, n, r) {
                for (var i = e.length; i--;) if (!e[i](t, n, r)) return ! 1;
                return ! 0
            }: e[0]
        }
        function m(e, n, r) {
            for (var i = 0,
            o = n.length; o > i; i++) t(e, n[i], r);
            return r
        }
        function g(e, t, n, r, i) {
            for (var o, a = [], s = 0, u = e.length, l = null != t; u > s; s++)(o = e[s]) && (!n || n(o, r, i)) && (a.push(o), l && t.push(s));
            return a
        }
        function v(e, t, n, i, o, a) {
            return i && !i[P] && (i = v(i)),
            o && !o[P] && (o = v(o, a)),
            r(function(r, a, s, u) {
                var l, c, d, f = [],
                p = [],
                h = a.length,
                v = r || m(t || "*", s.nodeType ? [s] : s, []),
                y = !e || !r && t ? v: g(v, f, e, s, u),
                b = n ? o || (r ? e: h || i) ? [] : a: y;
                if (n && n(y, b, s, u), i) for (l = g(b, p), i(l, [], s, u), c = l.length; c--;)(d = l[c]) && (b[p[c]] = !(y[p[c]] = d));
                if (r) {
                    if (o || e) {
                        if (o) {
                            for (l = [], c = b.length; c--;)(d = b[c]) && l.push(y[c] = d);
                            o(null, b = [], l, u)
                        }
                        for (c = b.length; c--;)(d = b[c]) && (l = o ? et(r, d) : f[c]) > -1 && (r[l] = !(a[l] = d))
                    }
                } else b = g(b === a ? b.splice(h, b.length) : b),
                o ? o(null, a, b, u) : K.apply(a, b)
            })
        }
        function y(e) {
            for (var t, n, r, i = e.length,
            o = T.relative[e[0].type], a = o || T.relative[" "], s = o ? 1 : 0, u = p(function(e) {
                return e === t
            },
            a, !0), l = p(function(e) {
                return et(t, e) > -1
            },
            a, !0), c = [function(e, n, r) {
                var i = !o && (r || n !== A) || ((t = n).nodeType ? u(e, n, r) : l(e, n, r));
                return t = null,
                i
            }]; i > s; s++) if (n = T.relative[e[s].type]) c = [p(h(c), n)];
            else {
                if (n = T.filter[e[s].type].apply(null, e[s].matches), n[P]) {
                    for (r = ++s; i > r && !T.relative[e[r].type]; r++);
                    return v(s > 1 && h(c), s > 1 && f(e.slice(0, s - 1).concat({
                        value: " " === e[s - 2].type ? "*": ""
                    })).replace(ut, "$1"), n, r > s && y(e.slice(s, r)), i > r && y(e = e.slice(r)), i > r && f(e))
                }
                c.push(n)
            }
            return h(c)
        }
        function b(e, n) {
            var i = n.length > 0,
            o = e.length > 0,
            a = function(r, a, s, u, l) {
                var c, d, f, p = 0,
                h = "0",
                m = r && [],
                v = [],
                y = A,
                b = r || o && T.find.TAG("*", l),
                x = W += null == y ? 1 : Math.random() || .1,
                w = b.length;
                for (l && (A = a !== H && a); h !== w && null != (c = b[h]); h++) {
                    if (o && c) {
                        for (d = 0; f = e[d++];) if (f(c, a, s)) {
                            u.push(c);
                            break
                        }
                        l && (W = x)
                    }
                    i && ((c = !f && c) && p--, r && m.push(c))
                }
                if (p += h, i && h !== p) {
                    for (d = 0; f = n[d++];) f(m, v, a, s);
                    if (r) {
                        if (p > 0) for (; h--;) m[h] || v[h] || (v[h] = G.call(u));
                        v = g(v)
                    }
                    K.apply(u, v),
                    l && !r && v.length > 0 && p + n.length > 1 && t.uniqueSort(u)
                }
                return l && (W = x, A = y),
                m
            };
            return i ? r(a) : a
        }
        var x, w, T, C, N, E, k, S, A, D, j, L, H, q, _, M, F, O, B, P = "sizzle" + 1 * new Date,
        R = e.document,
        W = 0,
        $ = 0,
        z = n(),
        I = n(),
        X = n(),
        U = function(e, t) {
            return e === t && (j = !0),
            0
        },
        V = 1 << 31,
        J = {}.hasOwnProperty,
        Y = [],
        G = Y.pop,
        Q = Y.push,
        K = Y.push,
        Z = Y.slice,
        et = function(e, t) {
            for (var n = 0,
            r = e.length; r > n; n++) if (e[n] === t) return n;
            return - 1
        },
        tt = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
        nt = "[\\x20\\t\\r\\n\\f]",
        rt = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
        it = rt.replace("w", "w#"),
        ot = "\\[" + nt + "*(" + rt + ")(?:" + nt + "*([*^$|!~]?=)" + nt + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + it + "))|)" + nt + "*\\]",
        at = ":(" + rt + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + ot + ")*)|.*)\\)|)",
        st = new RegExp(nt + "+", "g"),
        ut = new RegExp("^" + nt + "+|((?:^|[^\\\\])(?:\\\\.)*)" + nt + "+$", "g"),
        lt = new RegExp("^" + nt + "*," + nt + "*"),
        ct = new RegExp("^" + nt + "*([>+~]|" + nt + ")" + nt + "*"),
        dt = new RegExp("=" + nt + "*([^\\]'\"]*?)" + nt + "*\\]", "g"),
        ft = new RegExp(at),
        pt = new RegExp("^" + it + "$"),
        ht = {
            ID: new RegExp("^#(" + rt + ")"),
            CLASS: new RegExp("^\\.(" + rt + ")"),
            TAG: new RegExp("^(" + rt.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + ot),
            PSEUDO: new RegExp("^" + at),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + nt + "*(even|odd|(([+-]|)(\\d*)n|)" + nt + "*(?:([+-]|)" + nt + "*(\\d+)|))" + nt + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + tt + ")$", "i"),
            needsContext: new RegExp("^" + nt + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + nt + "*((?:-\\d)?\\d*)" + nt + "*\\)|)(?=[^-]|$)", "i")
        },
        mt = /^(?:input|select|textarea|button)$/i,
        gt = /^h\d$/i,
        vt = /^[^{]+\{\s*\[native \w/,
        yt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
        bt = /[+~]/,
        xt = /'|\\/g,
        wt = new RegExp("\\\\([\\da-f]{1,6}" + nt + "?|(" + nt + ")|.)", "ig"),
        Tt = function(e, t, n) {
            var r = "0x" + t - 65536;
            return r !== r || n ? t: 0 > r ? String.fromCharCode(r + 65536) : String.fromCharCode(r >> 10 | 55296, 1023 & r | 56320)
        },
        Ct = function() {
            L()
        };
        try {
            K.apply(Y = Z.call(R.childNodes), R.childNodes),
            Y[R.childNodes.length].nodeType
        } catch(Nt) {
            K = {
                apply: Y.length ?
                function(e, t) {
                    Q.apply(e, Z.call(t))
                }: function(e, t) {
                    for (var n = e.length,
                    r = 0; e[n++] = t[r++];);
                    e.length = n - 1
                }
            }
        }
        w = t.support = {},
        N = t.isXML = function(e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return t ? "HTML" !== t.nodeName: !1
        },
        L = t.setDocument = function(e) {
            var t, n, r = e ? e.ownerDocument || e: R;
            return r !== H && 9 === r.nodeType && r.documentElement ? (H = r, q = r.documentElement, n = r.defaultView, n && n !== n.top && (n.addEventListener ? n.addEventListener("unload", Ct, !1) : n.attachEvent && n.attachEvent("onunload", Ct)), _ = !N(r), w.attributes = i(function(e) {
                return e.className = "i",
                !e.getAttribute("className")
            }), w.getElementsByTagName = i(function(e) {
                return e.appendChild(r.createComment("")),
                !e.getElementsByTagName("*").length
            }), w.getElementsByClassName = vt.test(r.getElementsByClassName), w.getById = i(function(e) {
                return q.appendChild(e).id = P,
                !r.getElementsByName || !r.getElementsByName(P).length
            }), w.getById ? (T.find.ID = function(e, t) {
                if ("undefined" != typeof t.getElementById && _) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            },
            T.filter.ID = function(e) {
                var t = e.replace(wt, Tt);
                return function(e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete T.find.ID, T.filter.ID = function(e) {
                var t = e.replace(wt, Tt);
                return function(e) {
                    var n = "undefined" != typeof e.getAttributeNode && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), T.find.TAG = w.getElementsByTagName ?
            function(e, t) {
                return "undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName(e) : w.qsa ? t.querySelectorAll(e) : void 0
            }: function(e, t) {
                var n, r = [],
                i = 0,
                o = t.getElementsByTagName(e);
                if ("*" === e) {
                    for (; n = o[i++];) 1 === n.nodeType && r.push(n);
                    return r
                }
                return o
            },
            T.find.CLASS = w.getElementsByClassName &&
            function(e, t) {
                return _ ? t.getElementsByClassName(e) : void 0
            },
            F = [], M = [], (w.qsa = vt.test(r.querySelectorAll)) && (i(function(e) {
                q.appendChild(e).innerHTML = "<a id='" + P + "'></a><select id='" + P + "-\f]' msallowcapture=''><option selected=''></option></select>",
                e.querySelectorAll("[msallowcapture^='']").length && M.push("[*^$]=" + nt + "*(?:''|\"\")"),
                e.querySelectorAll("[selected]").length || M.push("\\[" + nt + "*(?:value|" + tt + ")"),
                e.querySelectorAll("[id~=" + P + "-]").length || M.push("~="),
                e.querySelectorAll(":checked").length || M.push(":checked"),
                e.querySelectorAll("a#" + P + "+*").length || M.push(".#.+[+~]")
            }), i(function(e) {
                var t = r.createElement("input");
                t.setAttribute("type", "hidden"),
                e.appendChild(t).setAttribute("name", "D"),
                e.querySelectorAll("[name=d]").length && M.push("name" + nt + "*[*^$|!~]?="),
                e.querySelectorAll(":enabled").length || M.push(":enabled", ":disabled"),
                e.querySelectorAll("*,:x"),
                M.push(",.*:")
            })), (w.matchesSelector = vt.test(O = q.matches || q.webkitMatchesSelector || q.mozMatchesSelector || q.oMatchesSelector || q.msMatchesSelector)) && i(function(e) {
                w.disconnectedMatch = O.call(e, "div"),
                O.call(e, "[s!='']:x"),
                F.push("!=", at)
            }), M = M.length && new RegExp(M.join("|")), F = F.length && new RegExp(F.join("|")), t = vt.test(q.compareDocumentPosition), B = t || vt.test(q.contains) ?
            function(e, t) {
                var n = 9 === e.nodeType ? e.documentElement: e,
                r = t && t.parentNode;
                return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)))
            }: function(e, t) {
                if (t) for (; t = t.parentNode;) if (t === e) return ! 0;
                return ! 1
            },
            U = t ?
            function(e, t) {
                if (e === t) return j = !0,
                0;
                var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                return n ? n: (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & n || !w.sortDetached && t.compareDocumentPosition(e) === n ? e === r || e.ownerDocument === R && B(R, e) ? -1 : t === r || t.ownerDocument === R && B(R, t) ? 1 : D ? et(D, e) - et(D, t) : 0 : 4 & n ? -1 : 1)
            }: function(e, t) {
                if (e === t) return j = !0,
                0;
                var n, i = 0,
                o = e.parentNode,
                s = t.parentNode,
                u = [e],
                l = [t];
                if (!o || !s) return e === r ? -1 : t === r ? 1 : o ? -1 : s ? 1 : D ? et(D, e) - et(D, t) : 0;
                if (o === s) return a(e, t);
                for (n = e; n = n.parentNode;) u.unshift(n);
                for (n = t; n = n.parentNode;) l.unshift(n);
                for (; u[i] === l[i];) i++;
                return i ? a(u[i], l[i]) : u[i] === R ? -1 : l[i] === R ? 1 : 0
            },
            r) : H
        },
        t.matches = function(e, n) {
            return t(e, null, null, n)
        },
        t.matchesSelector = function(e, n) {
            if ((e.ownerDocument || e) !== H && L(e), n = n.replace(dt, "='$1']"), !(!w.matchesSelector || !_ || F && F.test(n) || M && M.test(n))) try {
                var r = O.call(e, n);
                if (r || w.disconnectedMatch || e.document && 11 !== e.document.nodeType) return r
            } catch(i) {}
            return t(n, H, null, [e]).length > 0
        },
        t.contains = function(e, t) {
            return (e.ownerDocument || e) !== H && L(e),
            B(e, t)
        },
        t.attr = function(e, t) { (e.ownerDocument || e) !== H && L(e);
            var n = T.attrHandle[t.toLowerCase()],
            r = n && J.call(T.attrHandle, t.toLowerCase()) ? n(e, t, !_) : void 0;
            return void 0 !== r ? r: w.attributes || !_ ? e.getAttribute(t) : (r = e.getAttributeNode(t)) && r.specified ? r.value: null
        },
        t.error = function(e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        },
        t.uniqueSort = function(e) {
            var t, n = [],
            r = 0,
            i = 0;
            if (j = !w.detectDuplicates, D = !w.sortStable && e.slice(0), e.sort(U), j) {
                for (; t = e[i++];) t === e[i] && (r = n.push(i));
                for (; r--;) e.splice(n[r], 1)
            }
            return D = null,
            e
        },
        C = t.getText = function(e) {
            var t, n = "",
            r = 0,
            i = e.nodeType;
            if (i) {
                if (1 === i || 9 === i || 11 === i) {
                    if ("string" == typeof e.textContent) return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling) n += C(e)
                } else if (3 === i || 4 === i) return e.nodeValue
            } else for (; t = e[r++];) n += C(t);
            return n
        },
        T = t.selectors = {
            cacheLength: 50,
            createPseudo: r,
            match: ht,
            attrHandle: {},
            find: {},
            relative: {
                ">": {
                    dir: "parentNode",
                    first: !0
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: !0
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(e) {
                    return e[1] = e[1].replace(wt, Tt),
                    e[3] = (e[3] || e[4] || e[5] || "").replace(wt, Tt),
                    "~=" === e[2] && (e[3] = " " + e[3] + " "),
                    e.slice(0, 4)
                },
                CHILD: function(e) {
                    return e[1] = e[1].toLowerCase(),
                    "nth" === e[1].slice(0, 3) ? (e[3] || t.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && t.error(e[0]),
                    e
                },
                PSEUDO: function(e) {
                    var t, n = !e[6] && e[2];
                    return ht.CHILD.test(e[0]) ? null: (e[3] ? e[2] = e[4] || e[5] || "": n && ft.test(n) && (t = E(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function(e) {
                    var t = e.replace(wt, Tt).toLowerCase();
                    return "*" === e ?
                    function() {
                        return ! 0
                    }: function(e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                },
                CLASS: function(e) {
                    var t = z[e + " "];
                    return t || (t = new RegExp("(^|" + nt + ")" + e + "(" + nt + "|$)")) && z(e,
                    function(e) {
                        return t.test("string" == typeof e.className && e.className || "undefined" != typeof e.getAttribute && e.getAttribute("class") || "")
                    })
                },
                ATTR: function(e, n, r) {
                    return function(i) {
                        var o = t.attr(i, e);
                        return null == o ? "!=" === n: n ? (o += "", "=" === n ? o === r: "!=" === n ? o !== r: "^=" === n ? r && 0 === o.indexOf(r) : "*=" === n ? r && o.indexOf(r) > -1 : "$=" === n ? r && o.slice( - r.length) === r: "~=" === n ? (" " + o.replace(st, " ") + " ").indexOf(r) > -1 : "|=" === n ? o === r || o.slice(0, r.length + 1) === r + "-": !1) : !0
                    }
                },
                CHILD: function(e, t, n, r, i) {
                    var o = "nth" !== e.slice(0, 3),
                    a = "last" !== e.slice( - 4),
                    s = "of-type" === t;
                    return 1 === r && 0 === i ?
                    function(e) {
                        return !! e.parentNode
                    }: function(t, n, u) {
                        var l, c, d, f, p, h, m = o !== a ? "nextSibling": "previousSibling",
                        g = t.parentNode,
                        v = s && t.nodeName.toLowerCase(),
                        y = !u && !s;
                        if (g) {
                            if (o) {
                                for (; m;) {
                                    for (d = t; d = d[m];) if (s ? d.nodeName.toLowerCase() === v: 1 === d.nodeType) return ! 1;
                                    h = m = "only" === e && !h && "nextSibling"
                                }
                                return ! 0
                            }
                            if (h = [a ? g.firstChild: g.lastChild], a && y) {
                                for (c = g[P] || (g[P] = {}), l = c[e] || [], p = l[0] === W && l[1], f = l[0] === W && l[2], d = p && g.childNodes[p]; d = ++p && d && d[m] || (f = p = 0) || h.pop();) if (1 === d.nodeType && ++f && d === t) {
                                    c[e] = [W, p, f];
                                    break
                                }
                            } else if (y && (l = (t[P] || (t[P] = {}))[e]) && l[0] === W) f = l[1];
                            else for (; (d = ++p && d && d[m] || (f = p = 0) || h.pop()) && ((s ? d.nodeName.toLowerCase() !== v: 1 !== d.nodeType) || !++f || (y && ((d[P] || (d[P] = {}))[e] = [W, f]), d !== t)););
                            return f -= i,
                            f === r || f % r === 0 && f / r >= 0
                        }
                    }
                },
                PSEUDO: function(e, n) {
                    var i, o = T.pseudos[e] || T.setFilters[e.toLowerCase()] || t.error("unsupported pseudo: " + e);
                    return o[P] ? o(n) : o.length > 1 ? (i = [e, e, "", n], T.setFilters.hasOwnProperty(e.toLowerCase()) ? r(function(e, t) {
                        for (var r, i = o(e, n), a = i.length; a--;) r = et(e, i[a]),
                        e[r] = !(t[r] = i[a])
                    }) : function(e) {
                        return o(e, 0, i)
                    }) : o
                }
            },
            pseudos: {
                not: r(function(e) {
                    var t = [],
                    n = [],
                    i = k(e.replace(ut, "$1"));
                    return i[P] ? r(function(e, t, n, r) {
                        for (var o, a = i(e, null, r, []), s = e.length; s--;)(o = a[s]) && (e[s] = !(t[s] = o))
                    }) : function(e, r, o) {
                        return t[0] = e,
                        i(t, null, o, n),
                        t[0] = null,
                        !n.pop()
                    }
                }),
                has: r(function(e) {
                    return function(n) {
                        return t(e, n).length > 0
                    }
                }),
                contains: r(function(e) {
                    return e = e.replace(wt, Tt),
                    function(t) {
                        return (t.textContent || t.innerText || C(t)).indexOf(e) > -1
                    }
                }),
                lang: r(function(e) {
                    return pt.test(e || "") || t.error("unsupported lang: " + e),
                    e = e.replace(wt, Tt).toLowerCase(),
                    function(t) {
                        var n;
                        do
                        if (n = _ ? t.lang: t.getAttribute("xml:lang") || t.getAttribute("lang")) return n = n.toLowerCase(),
                        n === e || 0 === n.indexOf(e + "-");
                        while ((t = t.parentNode) && 1 === t.nodeType);
                        return ! 1
                    }
                }),
                target: function(t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                },
                root: function(e) {
                    return e === q
                },
                focus: function(e) {
                    return e === H.activeElement && (!H.hasFocus || H.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                },
                enabled: function(e) {
                    return e.disabled === !1
                },
                disabled: function(e) {
                    return e.disabled === !0
                },
                checked: function(e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                },
                selected: function(e) {
                    return e.parentNode && e.parentNode.selectedIndex,
                    e.selected === !0
                },
                empty: function(e) {
                    for (e = e.firstChild; e; e = e.nextSibling) if (e.nodeType < 6) return ! 1;
                    return ! 0
                },
                parent: function(e) {
                    return ! T.pseudos.empty(e)
                },
                header: function(e) {
                    return gt.test(e.nodeName)
                },
                input: function(e) {
                    return mt.test(e.nodeName)
                },
                button: function(e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                },
                text: function(e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                },
                first: l(function() {
                    return [0]
                }),
                last: l(function(e, t) {
                    return [t - 1]
                }),
                eq: l(function(e, t, n) {
                    return [0 > n ? n + t: n]
                }),
                even: l(function(e, t) {
                    for (var n = 0; t > n; n += 2) e.push(n);
                    return e
                }),
                odd: l(function(e, t) {
                    for (var n = 1; t > n; n += 2) e.push(n);
                    return e
                }),
                lt: l(function(e, t, n) {
                    for (var r = 0 > n ? n + t: n; --r >= 0;) e.push(r);
                    return e
                }),
                gt: l(function(e, t, n) {
                    for (var r = 0 > n ? n + t: n; ++r < t;) e.push(r);
                    return e
                })
            }
        },
        T.pseudos.nth = T.pseudos.eq;
        for (x in {
            radio: !0,
            checkbox: !0,
            file: !0,
            password: !0,
            image: !0
        }) T.pseudos[x] = s(x);
        for (x in {
            submit: !0,
            reset: !0
        }) T.pseudos[x] = u(x);
        return d.prototype = T.filters = T.pseudos,
        T.setFilters = new d,
        E = t.tokenize = function(e, n) {
            var r, i, o, a, s, u, l, c = I[e + " "];
            if (c) return n ? 0 : c.slice(0);
            for (s = e, u = [], l = T.preFilter; s;) { (!r || (i = lt.exec(s))) && (i && (s = s.slice(i[0].length) || s), u.push(o = [])),
                r = !1,
                (i = ct.exec(s)) && (r = i.shift(), o.push({
                    value: r,
                    type: i[0].replace(ut, " ")
                }), s = s.slice(r.length));
                for (a in T.filter) ! (i = ht[a].exec(s)) || l[a] && !(i = l[a](i)) || (r = i.shift(), o.push({
                    value: r,
                    type: a,
                    matches: i
                }), s = s.slice(r.length));
                if (!r) break
            }
            return n ? s.length: s ? t.error(e) : I(e, u).slice(0)
        },
        k = t.compile = function(e, t) {
            var n, r = [],
            i = [],
            o = X[e + " "];
            if (!o) {
                for (t || (t = E(e)), n = t.length; n--;) o = y(t[n]),
                o[P] ? r.push(o) : i.push(o);
                o = X(e, b(i, r)),
                o.selector = e
            }
            return o
        },
        S = t.select = function(e, t, n, r) {
            var i, o, a, s, u, l = "function" == typeof e && e,
            d = !r && E(e = l.selector || e);
            if (n = n || [], 1 === d.length) {
                if (o = d[0] = d[0].slice(0), o.length > 2 && "ID" === (a = o[0]).type && w.getById && 9 === t.nodeType && _ && T.relative[o[1].type]) {
                    if (t = (T.find.ID(a.matches[0].replace(wt, Tt), t) || [])[0], !t) return n;
                    l && (t = t.parentNode),
                    e = e.slice(o.shift().value.length)
                }
                for (i = ht.needsContext.test(e) ? 0 : o.length; i--&&(a = o[i], !T.relative[s = a.type]);) if ((u = T.find[s]) && (r = u(a.matches[0].replace(wt, Tt), bt.test(o[0].type) && c(t.parentNode) || t))) {
                    if (o.splice(i, 1), e = r.length && f(o), !e) return K.apply(n, r),
                    n;
                    break
                }
            }
            return (l || k(e, d))(r, t, !_, n, bt.test(e) && c(t.parentNode) || t),
            n
        },
        w.sortStable = P.split("").sort(U).join("") === P,
        w.detectDuplicates = !!j,
        L(),
        w.sortDetached = i(function(e) {
            return 1 & e.compareDocumentPosition(H.createElement("div"))
        }),
        i(function(e) {
            return e.innerHTML = "<a href='#'></a>",
            "#" === e.firstChild.getAttribute("href")
        }) || o("type|href|height|width",
        function(e, t, n) {
            return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }),
        w.attributes && i(function(e) {
            return e.innerHTML = "<input/>",
            e.firstChild.setAttribute("value", ""),
            "" === e.firstChild.getAttribute("value")
        }) || o("value",
        function(e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
        }),
        i(function(e) {
            return null == e.getAttribute("disabled")
        }) || o(tt,
        function(e, t, n) {
            var r;
            return n ? void 0 : e[t] === !0 ? t.toLowerCase() : (r = e.getAttributeNode(t)) && r.specified ? r.value: null
        }),
        t
    } (e);
    it.find = lt,
    it.expr = lt.selectors,
    it.expr[":"] = it.expr.pseudos,
    it.unique = lt.uniqueSort,
    it.text = lt.getText,
    it.isXMLDoc = lt.isXML,
    it.contains = lt.contains;
    var ct = it.expr.match.needsContext,
    dt = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
    ft = /^.[^:#\[\.,]*$/;
    it.filter = function(e, t, n) {
        var r = t[0];
        return n && (e = ":not(" + e + ")"),
        1 === t.length && 1 === r.nodeType ? it.find.matchesSelector(r, e) ? [r] : [] : it.find.matches(e, it.grep(t,
        function(e) {
            return 1 === e.nodeType
        }))
    },
    it.fn.extend({
        find: function(e) {
            var t, n = [],
            r = this,
            i = r.length;
            if ("string" != typeof e) return this.pushStack(it(e).filter(function() {
                for (t = 0; i > t; t++) if (it.contains(r[t], this)) return ! 0
            }));
            for (t = 0; i > t; t++) it.find(e, r[t], n);
            return n = this.pushStack(i > 1 ? it.unique(n) : n),
            n.selector = this.selector ? this.selector + " " + e: e,
            n
        },
        filter: function(e) {
            return this.pushStack(r(this, e || [], !1))
        },
        not: function(e) {
            return this.pushStack(r(this, e || [], !0))
        },
        is: function(e) {
            return !! r(this, "string" == typeof e && ct.test(e) ? it(e) : e || [], !1).length
        }
    });
    var pt, ht = e.document,
    mt = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,
    gt = it.fn.init = function(e, t) {
        var n, r;
        if (!e) return this;
        if ("string" == typeof e) {
            if (n = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : mt.exec(e), !n || !n[1] && t) return ! t || t.jquery ? (t || pt).find(e) : this.constructor(t).find(e);
            if (n[1]) {
                if (t = t instanceof it ? t[0] : t, it.merge(this, it.parseHTML(n[1], t && t.nodeType ? t.ownerDocument || t: ht, !0)), dt.test(n[1]) && it.isPlainObject(t)) for (n in t) it.isFunction(this[n]) ? this[n](t[n]) : this.attr(n, t[n]);
                return this
            }
            if (r = ht.getElementById(n[2]), r && r.parentNode) {
                if (r.id !== n[2]) return pt.find(e);
                this.length = 1,
                this[0] = r
            }
            return this.context = ht,
            this.selector = e,
            this
        }
        return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : it.isFunction(e) ? "undefined" != typeof pt.ready ? pt.ready(e) : e(it) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), it.makeArray(e, this))
    };
    gt.prototype = it.fn,
    pt = it(ht);
    var vt = /^(?:parents|prev(?:Until|All))/,
    yt = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    it.extend({
        dir: function(e, t, n) {
            for (var r = [], i = e[t]; i && 9 !== i.nodeType && (void 0 === n || 1 !== i.nodeType || !it(i).is(n));) 1 === i.nodeType && r.push(i),
            i = i[t];
            return r
        },
        sibling: function(e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    }),
    it.fn.extend({
        has: function(e) {
            var t, n = it(e, this),
            r = n.length;
            return this.filter(function() {
                for (t = 0; r > t; t++) if (it.contains(this, n[t])) return ! 0
            })
        },
        closest: function(e, t) {
            for (var n, r = 0,
            i = this.length,
            o = [], a = ct.test(e) || "string" != typeof e ? it(e, t || this.context) : 0; i > r; r++) for (n = this[r]; n && n !== t; n = n.parentNode) if (n.nodeType < 11 && (a ? a.index(n) > -1 : 1 === n.nodeType && it.find.matchesSelector(n, e))) {
                o.push(n);
                break
            }
            return this.pushStack(o.length > 1 ? it.unique(o) : o)
        },
        index: function(e) {
            return e ? "string" == typeof e ? it.inArray(this[0], it(e)) : it.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.first().prevAll().length: -1
        },
        add: function(e, t) {
            return this.pushStack(it.unique(it.merge(this.get(), it(e, t))))
        },
        addBack: function(e) {
            return this.add(null == e ? this.prevObject: this.prevObject.filter(e))
        }
    }),
    it.each({
        parent: function(e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t: null
        },
        parents: function(e) {
            return it.dir(e, "parentNode")
        },
        parentsUntil: function(e, t, n) {
            return it.dir(e, "parentNode", n)
        },
        next: function(e) {
            return i(e, "nextSibling")
        },
        prev: function(e) {
            return i(e, "previousSibling")
        },
        nextAll: function(e) {
            return it.dir(e, "nextSibling")
        },
        prevAll: function(e) {
            return it.dir(e, "previousSibling")
        },
        nextUntil: function(e, t, n) {
            return it.dir(e, "nextSibling", n)
        },
        prevUntil: function(e, t, n) {
            return it.dir(e, "previousSibling", n)
        },
        siblings: function(e) {
            return it.sibling((e.parentNode || {}).firstChild, e)
        },
        children: function(e) {
            return it.sibling(e.firstChild)
        },
        contents: function(e) {
            return it.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document: it.merge([], e.childNodes)
        }
    },
    function(e, t) {
        it.fn[e] = function(n, r) {
            var i = it.map(this, t, n);
            return "Until" !== e.slice( - 5) && (r = n),
            r && "string" == typeof r && (i = it.filter(r, i)),
            this.length > 1 && (yt[e] || (i = it.unique(i)), vt.test(e) && (i = i.reverse())),
            this.pushStack(i)
        }
    });
    var bt = /\S+/g,
    xt = {};
    it.Callbacks = function(e) {
        e = "string" == typeof e ? xt[e] || o(e) : it.extend({},
        e);
        var t, n, r, i, a, s, u = [],
        l = !e.once && [],
        c = function(o) {
            for (n = e.memory && o, r = !0, a = s || 0, s = 0, i = u.length, t = !0; u && i > a; a++) if (u[a].apply(o[0], o[1]) === !1 && e.stopOnFalse) {
                n = !1;
                break
            }
            t = !1,
            u && (l ? l.length && c(l.shift()) : n ? u = [] : d.disable())
        },
        d = {
            add: function() {
                if (u) {
                    var r = u.length; !
                    function o(t) {
                        it.each(t,
                        function(t, n) {
                            var r = it.type(n);
                            "function" === r ? e.unique && d.has(n) || u.push(n) : n && n.length && "string" !== r && o(n)
                        })
                    } (arguments),
                    t ? i = u.length: n && (s = r, c(n))
                }
                return this
            },
            remove: function() {
                return u && it.each(arguments,
                function(e, n) {
                    for (var r; (r = it.inArray(n, u, r)) > -1;) u.splice(r, 1),
                    t && (i >= r && i--, a >= r && a--)
                }),
                this
            },
            has: function(e) {
                return e ? it.inArray(e, u) > -1 : !(!u || !u.length)
            },
            empty: function() {
                return u = [],
                i = 0,
                this
            },
            disable: function() {
                return u = l = n = void 0,
                this
            },
            disabled: function() {
                return ! u
            },
            lock: function() {
                return l = void 0,
                n || d.disable(),
                this
            },
            locked: function() {
                return ! l
            },
            fireWith: function(e, n) {
                return ! u || r && !l || (n = n || [], n = [e, n.slice ? n.slice() : n], t ? l.push(n) : c(n)),
                this
            },
            fire: function() {
                return d.fireWith(this, arguments),
                this
            },
            fired: function() {
                return !! r
            }
        };
        return d
    },
    it.extend({
        Deferred: function(e) {
            var t = [["resolve", "done", it.Callbacks("once memory"), "resolved"], ["reject", "fail", it.Callbacks("once memory"), "rejected"], ["notify", "progress", it.Callbacks("memory")]],
            n = "pending",
            r = {
                state: function() {
                    return n
                },
                always: function() {
                    return i.done(arguments).fail(arguments),
                    this
                },
                then: function() {
                    var e = arguments;
                    return it.Deferred(function(n) {
                        it.each(t,
                        function(t, o) {
                            var a = it.isFunction(e[t]) && e[t];
                            i[o[1]](function() {
                                var e = a && a.apply(this, arguments);
                                e && it.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[o[0] + "With"](this === r ? n.promise() : this, a ? [e] : arguments)
                            })
                        }),
                        e = null
                    }).promise()
                },
                promise: function(e) {
                    return null != e ? it.extend(e, r) : r
                }
            },
            i = {};
            return r.pipe = r.then,
            it.each(t,
            function(e, o) {
                var a = o[2],
                s = o[3];
                r[o[1]] = a.add,
                s && a.add(function() {
                    n = s
                },
                t[1 ^ e][2].disable, t[2][2].lock),
                i[o[0]] = function() {
                    return i[o[0] + "With"](this === i ? r: this, arguments),
                    this
                },
                i[o[0] + "With"] = a.fireWith
            }),
            r.promise(i),
            e && e.call(i, i),
            i
        },
        when: function(e) {
            var t, n, r, i = 0,
            o = Y.call(arguments),
            a = o.length,
            s = 1 !== a || e && it.isFunction(e.promise) ? a: 0,
            u = 1 === s ? e: it.Deferred(),
            l = function(e, n, r) {
                return function(i) {
                    n[e] = this,
                    r[e] = arguments.length > 1 ? Y.call(arguments) : i,
                    r === t ? u.notifyWith(n, r) : --s || u.resolveWith(n, r)
                }
            };
            if (a > 1) for (t = new Array(a), n = new Array(a), r = new Array(a); a > i; i++) o[i] && it.isFunction(o[i].promise) ? o[i].promise().done(l(i, r, o)).fail(u.reject).progress(l(i, n, t)) : --s;
            return s || u.resolveWith(r, o),
            u.promise()
        }
    });
    var wt;
    it.fn.ready = function(e) {
        return it.ready.promise().done(e),
        this
    },
    it.extend({
        isReady: !1,
        readyWait: 1,
        holdReady: function(e) {
            e ? it.readyWait++:it.ready(!0)
        },
        ready: function(e) {
            if (e === !0 ? !--it.readyWait: !it.isReady) {
                if (!ht.body) return setTimeout(it.ready);
                it.isReady = !0,
                e !== !0 && --it.readyWait > 0 || (wt.resolveWith(ht, [it]), it.fn.triggerHandler && (it(ht).triggerHandler("ready"), it(ht).off("ready")))
            }
        }
    }),
    it.ready.promise = function(t) {
        if (!wt) if (wt = it.Deferred(), "complete" === ht.readyState) setTimeout(it.ready);
        else if (ht.addEventListener) ht.addEventListener("DOMContentLoaded", s, !1),
        e.addEventListener("load", s, !1);
        else {
            ht.attachEvent("onreadystatechange", s),
            e.attachEvent("onload", s);
            var n = !1;
            try {
                n = null == e.frameElement && ht.documentElement
            } catch(r) {}
            n && n.doScroll && !
            function i() {
                if (!it.isReady) {
                    try {
                        n.doScroll("left")
                    } catch(e) {
                        return setTimeout(i, 50)
                    }
                    a(),
                    it.ready()
                }
            } ()
        }
        return wt.promise(t)
    };
    var Tt, Ct = "undefined";
    for (Tt in it(nt)) break;
    nt.ownLast = "0" !== Tt,
    nt.inlineBlockNeedsLayout = !1,
    it(function() {
        var e, t, n, r;
        n = ht.getElementsByTagName("body")[0],
        n && n.style && (t = ht.createElement("div"), r = ht.createElement("div"), r.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(r).appendChild(t), typeof t.style.zoom !== Ct && (t.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", nt.inlineBlockNeedsLayout = e = 3 === t.offsetWidth, e && (n.style.zoom = 1)), n.removeChild(r))
    }),
    function() {
        var e = ht.createElement("div");
        if (null == nt.deleteExpando) {
            nt.deleteExpando = !0;
            try {
                delete e.test
            } catch(t) {
                nt.deleteExpando = !1
            }
        }
        e = null
    } (),
    it.acceptData = function(e) {
        var t = it.noData[(e.nodeName + " ").toLowerCase()],
        n = +e.nodeType || 1;
        return 1 !== n && 9 !== n ? !1 : !t || t !== !0 && e.getAttribute("classid") === t
    };
    var Nt = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
    Et = /([A-Z])/g;
    it.extend({
        cache: {},
        noData: {
            "applet ": !0,
            "embed ": !0,
            "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        },
        hasData: function(e) {
            return e = e.nodeType ? it.cache[e[it.expando]] : e[it.expando],
            !!e && !l(e)
        },
        data: function(e, t, n) {
            return c(e, t, n)
        },
        removeData: function(e, t) {
            return d(e, t)
        },
        _data: function(e, t, n) {
            return c(e, t, n, !0)
        },
        _removeData: function(e, t) {
            return d(e, t, !0)
        }
    }),
    it.fn.extend({
        data: function(e, t) {
            var n, r, i, o = this[0],
            a = o && o.attributes;
            if (void 0 === e) {
                if (this.length && (i = it.data(o), 1 === o.nodeType && !it._data(o, "parsedAttrs"))) {
                    for (n = a.length; n--;) a[n] && (r = a[n].name, 0 === r.indexOf("data-") && (r = it.camelCase(r.slice(5)), u(o, r, i[r])));
                    it._data(o, "parsedAttrs", !0)
                }
                return i
            }
            return "object" == typeof e ? this.each(function() {
                it.data(this, e)
            }) : arguments.length > 1 ? this.each(function() {
                it.data(this, e, t)
            }) : o ? u(o, e, it.data(o, e)) : void 0
        },
        removeData: function(e) {
            return this.each(function() {
                it.removeData(this, e)
            })
        }
    }),
    it.extend({
        queue: function(e, t, n) {
            var r;
            return e ? (t = (t || "fx") + "queue", r = it._data(e, t), n && (!r || it.isArray(n) ? r = it._data(e, t, it.makeArray(n)) : r.push(n)), r || []) : void 0
        },
        dequeue: function(e, t) {
            t = t || "fx";
            var n = it.queue(e, t),
            r = n.length,
            i = n.shift(),
            o = it._queueHooks(e, t),
            a = function() {
                it.dequeue(e, t)
            };
            "inprogress" === i && (i = n.shift(), r--),
            i && ("fx" === t && n.unshift("inprogress"), delete o.stop, i.call(e, a, o)),
            !r && o && o.empty.fire()
        },
        _queueHooks: function(e, t) {
            var n = t + "queueHooks";
            return it._data(e, n) || it._data(e, n, {
                empty: it.Callbacks("once memory").add(function() {
                    it._removeData(e, t + "queue"),
                    it._removeData(e, n)
                })
            })
        }
    }),
    it.fn.extend({
        queue: function(e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--),
            arguments.length < n ? it.queue(this[0], e) : void 0 === t ? this: this.each(function() {
                var n = it.queue(this, e, t);
                it._queueHooks(this, e),
                "fx" === e && "inprogress" !== n[0] && it.dequeue(this, e)
            })
        },
        dequeue: function(e) {
            return this.each(function() {
                it.dequeue(this, e)
            })
        },
        clearQueue: function(e) {
            return this.queue(e || "fx", [])
        },
        promise: function(e, t) {
            var n, r = 1,
            i = it.Deferred(),
            o = this,
            a = this.length,
            s = function() {--r || i.resolveWith(o, [o])
            };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; a--;) n = it._data(o[a], e + "queueHooks"),
            n && n.empty && (r++, n.empty.add(s));
            return s(),
            i.promise(t)
        }
    });
    var kt = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
    St = ["Top", "Right", "Bottom", "Left"],
    At = function(e, t) {
        return e = t || e,
        "none" === it.css(e, "display") || !it.contains(e.ownerDocument, e)
    },
    Dt = it.access = function(e, t, n, r, i, o, a) {
        var s = 0,
        u = e.length,
        l = null == n;
        if ("object" === it.type(n)) {
            i = !0;
            for (s in n) it.access(e, t, s, n[s], !0, o, a)
        } else if (void 0 !== r && (i = !0, it.isFunction(r) || (a = !0), l && (a ? (t.call(e, r), t = null) : (l = t, t = function(e, t, n) {
            return l.call(it(e), n)
        })), t)) for (; u > s; s++) t(e[s], n, a ? r: r.call(e[s], s, t(e[s], n)));
        return i ? e: l ? t.call(e) : u ? t(e[0], n) : o
    },
    jt = /^(?:checkbox|radio)$/i; !
    function() {
        var e = ht.createElement("input"),
        t = ht.createElement("div"),
        n = ht.createDocumentFragment();
        if (t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", nt.leadingWhitespace = 3 === t.firstChild.nodeType, nt.tbody = !t.getElementsByTagName("tbody").length, nt.htmlSerialize = !!t.getElementsByTagName("link").length, nt.html5Clone = "<:nav></:nav>" !== ht.createElement("nav").cloneNode(!0).outerHTML, e.type = "checkbox", e.checked = !0, n.appendChild(e), nt.appendChecked = e.checked, t.innerHTML = "<textarea>x</textarea>", nt.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, n.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", nt.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, nt.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick",
        function() {
            nt.noCloneEvent = !1
        }), t.cloneNode(!0).click()), null == nt.deleteExpando) {
            nt.deleteExpando = !0;
            try {
                delete t.test
            } catch(r) {
                nt.deleteExpando = !1
            }
        }
    } (),
    function() {
        var t, n, r = ht.createElement("div");
        for (t in {
            submit: !0,
            change: !0,
            focusin: !0
        }) n = "on" + t,
        (nt[t + "Bubbles"] = n in e) || (r.setAttribute(n, "t"), nt[t + "Bubbles"] = r.attributes[n].expando === !1);
        r = null
    } ();
    var Lt = /^(?:input|select|textarea)$/i,
    Ht = /^key/,
    qt = /^(?:mouse|pointer|contextmenu)|click/,
    _t = /^(?:focusinfocus|focusoutblur)$/,
    Mt = /^([^.]*)(?:\.(.+)|)$/;
    it.event = {
        global: {},
        add: function(e, t, n, r, i) {
            var o, a, s, u, l, c, d, f, p, h, m, g = it._data(e);
            if (g) {
                for (n.handler && (u = n, n = u.handler, i = u.selector), n.guid || (n.guid = it.guid++), (a = g.events) || (a = g.events = {}), (c = g.handle) || (c = g.handle = function(e) {
                    return typeof it === Ct || e && it.event.triggered === e.type ? void 0 : it.event.dispatch.apply(c.elem, arguments)
                },
                c.elem = e), t = (t || "").match(bt) || [""], s = t.length; s--;) o = Mt.exec(t[s]) || [],
                p = m = o[1],
                h = (o[2] || "").split(".").sort(),
                p && (l = it.event.special[p] || {},
                p = (i ? l.delegateType: l.bindType) || p, l = it.event.special[p] || {},
                d = it.extend({
                    type: p,
                    origType: m,
                    data: r,
                    handler: n,
                    guid: n.guid,
                    selector: i,
                    needsContext: i && it.expr.match.needsContext.test(i),
                    namespace: h.join(".")
                },
                u), (f = a[p]) || (f = a[p] = [], f.delegateCount = 0, l.setup && l.setup.call(e, r, h, c) !== !1 || (e.addEventListener ? e.addEventListener(p, c, !1) : e.attachEvent && e.attachEvent("on" + p, c))), l.add && (l.add.call(e, d), d.handler.guid || (d.handler.guid = n.guid)), i ? f.splice(f.delegateCount++, 0, d) : f.push(d), it.event.global[p] = !0);
                e = null
            }
        },
        remove: function(e, t, n, r, i) {
            var o, a, s, u, l, c, d, f, p, h, m, g = it.hasData(e) && it._data(e);
            if (g && (c = g.events)) {
                for (t = (t || "").match(bt) || [""], l = t.length; l--;) if (s = Mt.exec(t[l]) || [], p = m = s[1], h = (s[2] || "").split(".").sort(), p) {
                    for (d = it.event.special[p] || {},
                    p = (r ? d.delegateType: d.bindType) || p, f = c[p] || [], s = s[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), u = o = f.length; o--;) a = f[o],
                    !i && m !== a.origType || n && n.guid !== a.guid || s && !s.test(a.namespace) || r && r !== a.selector && ("**" !== r || !a.selector) || (f.splice(o, 1), a.selector && f.delegateCount--, d.remove && d.remove.call(e, a));
                    u && !f.length && (d.teardown && d.teardown.call(e, h, g.handle) !== !1 || it.removeEvent(e, p, g.handle), delete c[p])
                } else for (p in c) it.event.remove(e, p + t[l], n, r, !0);
                it.isEmptyObject(c) && (delete g.handle, it._removeData(e, "events"))
            }
        },
        trigger: function(t, n, r, i) {
            var o, a, s, u, l, c, d, f = [r || ht],
            p = tt.call(t, "type") ? t.type: t,
            h = tt.call(t, "namespace") ? t.namespace.split(".") : [];
            if (s = c = r = r || ht, 3 !== r.nodeType && 8 !== r.nodeType && !_t.test(p + it.event.triggered) && (p.indexOf(".") >= 0 && (h = p.split("."), p = h.shift(), h.sort()), a = p.indexOf(":") < 0 && "on" + p, t = t[it.expando] ? t: new it.Event(p, "object" == typeof t && t), t.isTrigger = i ? 2 : 3, t.namespace = h.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = r), n = null == n ? [t] : it.makeArray(n, [t]), l = it.event.special[p] || {},
            i || !l.trigger || l.trigger.apply(r, n) !== !1)) {
                if (!i && !l.noBubble && !it.isWindow(r)) {
                    for (u = l.delegateType || p, _t.test(u + p) || (s = s.parentNode); s; s = s.parentNode) f.push(s),
                    c = s;
                    c === (r.ownerDocument || ht) && f.push(c.defaultView || c.parentWindow || e)
                }
                for (d = 0; (s = f[d++]) && !t.isPropagationStopped();) t.type = d > 1 ? u: l.bindType || p,
                o = (it._data(s, "events") || {})[t.type] && it._data(s, "handle"),
                o && o.apply(s, n),
                o = a && s[a],
                o && o.apply && it.acceptData(s) && (t.result = o.apply(s, n), t.result === !1 && t.preventDefault());
                if (t.type = p, !i && !t.isDefaultPrevented() && (!l._default || l._default.apply(f.pop(), n) === !1) && it.acceptData(r) && a && r[p] && !it.isWindow(r)) {
                    c = r[a],
                    c && (r[a] = null),
                    it.event.triggered = p;
                    try {
                        r[p]()
                    } catch(m) {}
                    it.event.triggered = void 0,
                    c && (r[a] = c)
                }
                return t.result
            }
        },
        dispatch: function(e) {
            e = it.event.fix(e);
            var t, n, r, i, o, a = [],
            s = Y.call(arguments),
            u = (it._data(this, "events") || {})[e.type] || [],
            l = it.event.special[e.type] || {};
            if (s[0] = e, e.delegateTarget = this, !l.preDispatch || l.preDispatch.call(this, e) !== !1) {
                for (a = it.event.handlers.call(this, e, u), t = 0; (i = a[t++]) && !e.isPropagationStopped();) for (e.currentTarget = i.elem, o = 0; (r = i.handlers[o++]) && !e.isImmediatePropagationStopped();)(!e.namespace_re || e.namespace_re.test(r.namespace)) && (e.handleObj = r, e.data = r.data, n = ((it.event.special[r.origType] || {}).handle || r.handler).apply(i.elem, s), void 0 !== n && (e.result = n) === !1 && (e.preventDefault(), e.stopPropagation()));
                return l.postDispatch && l.postDispatch.call(this, e),
                e.result
            }
        },
        handlers: function(e, t) {
            var n, r, i, o, a = [],
            s = t.delegateCount,
            u = e.target;
            if (s && u.nodeType && (!e.button || "click" !== e.type)) for (; u != this; u = u.parentNode || this) if (1 === u.nodeType && (u.disabled !== !0 || "click" !== e.type)) {
                for (i = [], o = 0; s > o; o++) r = t[o],
                n = r.selector + " ",
                void 0 === i[n] && (i[n] = r.needsContext ? it(n, this).index(u) >= 0 : it.find(n, this, null, [u]).length),
                i[n] && i.push(r);
                i.length && a.push({
                    elem: u,
                    handlers: i
                })
            }
            return s < t.length && a.push({
                elem: this,
                handlers: t.slice(s)
            }),
            a
        },
        fix: function(e) {
            if (e[it.expando]) return e;
            var t, n, r, i = e.type,
            o = e,
            a = this.fixHooks[i];
            for (a || (this.fixHooks[i] = a = qt.test(i) ? this.mouseHooks: Ht.test(i) ? this.keyHooks: {}), r = a.props ? this.props.concat(a.props) : this.props, e = new it.Event(o), t = r.length; t--;) n = r[t],
            e[n] = o[n];
            return e.target || (e.target = o.srcElement || ht),
            3 === e.target.nodeType && (e.target = e.target.parentNode),
            e.metaKey = !!e.metaKey,
            a.filter ? a.filter(e, o) : e
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode: t.keyCode),
                e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(e, t) {
                var n, r, i, o = t.button,
                a = t.fromElement;
                return null == e.pageX && null != t.clientX && (r = e.target.ownerDocument || ht, i = r.documentElement, n = r.body, e.pageX = t.clientX + (i && i.scrollLeft || n && n.scrollLeft || 0) - (i && i.clientLeft || n && n.clientLeft || 0), e.pageY = t.clientY + (i && i.scrollTop || n && n.scrollTop || 0) - (i && i.clientTop || n && n.clientTop || 0)),
                !e.relatedTarget && a && (e.relatedTarget = a === e.target ? t.toElement: a),
                e.which || void 0 === o || (e.which = 1 & o ? 1 : 2 & o ? 3 : 4 & o ? 2 : 0),
                e
            }
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                trigger: function() {
                    if (this !== h() && this.focus) try {
                        return this.focus(),
                        !1
                    } catch(e) {}
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    return this === h() && this.blur ? (this.blur(), !1) : void 0
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function() {
                    return it.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                },
                _default: function(e) {
                    return it.nodeName(e.target, "a")
                }
            },
            beforeunload: {
                postDispatch: function(e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function(e, t, n, r) {
            var i = it.extend(new it.Event, n, {
                type: e,
                isSimulated: !0,
                originalEvent: {}
            });
            r ? it.event.trigger(i, null, t) : it.event.dispatch.call(t, i),
            i.isDefaultPrevented() && n.preventDefault()
        }
    },
    it.removeEvent = ht.removeEventListener ?
    function(e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    }: function(e, t, n) {
        var r = "on" + t;
        e.detachEvent && (typeof e[r] === Ct && (e[r] = null), e.detachEvent(r, n))
    },
    it.Event = function(e, t) {
        return this instanceof it.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && e.returnValue === !1 ? f: p) : this.type = e, t && it.extend(this, t), this.timeStamp = e && e.timeStamp || it.now(), void(this[it.expando] = !0)) : new it.Event(e, t)
    },
    it.Event.prototype = {
        isDefaultPrevented: p,
        isPropagationStopped: p,
        isImmediatePropagationStopped: p,
        preventDefault: function() {
            var e = this.originalEvent;
            this.isDefaultPrevented = f,
            e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        },
        stopPropagation: function() {
            var e = this.originalEvent;
            this.isPropagationStopped = f,
            e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = f,
            e && e.stopImmediatePropagation && e.stopImmediatePropagation(),
            this.stopPropagation()
        }
    },
    it.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    },
    function(e, t) {
        it.event.special[e] = {
            delegateType: t,
            bindType: t,
            handle: function(e) {
                var n, r = this,
                i = e.relatedTarget,
                o = e.handleObj;
                return (!i || i !== r && !it.contains(r, i)) && (e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t),
                n
            }
        }
    }),
    nt.submitBubbles || (it.event.special.submit = {
        setup: function() {
            return it.nodeName(this, "form") ? !1 : void it.event.add(this, "click._submit keypress._submit",
            function(e) {
                var t = e.target,
                n = it.nodeName(t, "input") || it.nodeName(t, "button") ? t.form: void 0;
                n && !it._data(n, "submitBubbles") && (it.event.add(n, "submit._submit",
                function(e) {
                    e._submit_bubble = !0
                }), it._data(n, "submitBubbles", !0))
            })
        },
        postDispatch: function(e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && it.event.simulate("submit", this.parentNode, e, !0))
        },
        teardown: function() {
            return it.nodeName(this, "form") ? !1 : void it.event.remove(this, "._submit")
        }
    }),
    nt.changeBubbles || (it.event.special.change = {
        setup: function() {
            return Lt.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (it.event.add(this, "propertychange._change",
            function(e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), it.event.add(this, "click._change",
            function(e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1),
                it.event.simulate("change", this, e, !0)
            })), !1) : void it.event.add(this, "beforeactivate._change",
            function(e) {
                var t = e.target;
                Lt.test(t.nodeName) && !it._data(t, "changeBubbles") && (it.event.add(t, "change._change",
                function(e) { ! this.parentNode || e.isSimulated || e.isTrigger || it.event.simulate("change", this.parentNode, e, !0)
                }), it._data(t, "changeBubbles", !0))
            })
        },
        handle: function(e) {
            var t = e.target;
            return this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type ? e.handleObj.handler.apply(this, arguments) : void 0
        },
        teardown: function() {
            return it.event.remove(this, "._change"),
            !Lt.test(this.nodeName)
        }
    }),
    nt.focusinBubbles || it.each({
        focus: "focusin",
        blur: "focusout"
    },
    function(e, t) {
        var n = function(e) {
            it.event.simulate(t, e.target, it.event.fix(e), !0)
        };
        it.event.special[t] = {
            setup: function() {
                var r = this.ownerDocument || this,
                i = it._data(r, t);
                i || r.addEventListener(e, n, !0),
                it._data(r, t, (i || 0) + 1)
            },
            teardown: function() {
                var r = this.ownerDocument || this,
                i = it._data(r, t) - 1;
                i ? it._data(r, t, i) : (r.removeEventListener(e, n, !0), it._removeData(r, t))
            }
        }
    }),
    it.fn.extend({
        on: function(e, t, n, r, i) {
            var o, a;
            if ("object" == typeof e) {
                "string" != typeof t && (n = n || t, t = void 0);
                for (o in e) this.on(o, t, n, e[o], i);
                return this
            }
            if (null == n && null == r ? (r = t, n = t = void 0) : null == r && ("string" == typeof t ? (r = n, n = void 0) : (r = n, n = t, t = void 0)), r === !1) r = p;
            else if (!r) return this;
            return 1 === i && (a = r, r = function(e) {
                return it().off(e),
                a.apply(this, arguments)
            },
            r.guid = a.guid || (a.guid = it.guid++)),
            this.each(function() {
                it.event.add(this, e, r, n, t)
            })
        },
        one: function(e, t, n, r) {
            return this.on(e, t, n, r, 1)
        },
        off: function(e, t, n) {
            var r, i;
            if (e && e.preventDefault && e.handleObj) return r = e.handleObj,
            it(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace: r.origType, r.selector, r.handler),
            this;
            if ("object" == typeof e) {
                for (i in e) this.off(i, t, e[i]);
                return this
            }
            return (t === !1 || "function" == typeof t) && (n = t, t = void 0),
            n === !1 && (n = p),
            this.each(function() {
                it.event.remove(this, e, n, t)
            })
        },
        trigger: function(e, t) {
            return this.each(function() {
                it.event.trigger(e, t, this)
            })
        },
        triggerHandler: function(e, t) {
            var n = this[0];
            return n ? it.event.trigger(e, t, n, !0) : void 0
        }
    });
    var Ft = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
    Ot = / jQuery\d+="(?:null|\d+)"/g,
    Bt = new RegExp("<(?:" + Ft + ")[\\s/>]", "i"),
    Pt = /^\s+/,
    Rt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
    Wt = /<([\w:]+)/,
    $t = /<tbody/i,
    zt = /<|&#?\w+;/,
    It = /<(?:script|style|link)/i,
    Xt = /checked\s*(?:[^=]|=\s*.checked.)/i,
    Ut = /^$|\/(?:java|ecma)script/i,
    Vt = /^true\/(.*)/,
    Jt = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
    Yt = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        legend: [1, "<fieldset>", "</fieldset>"],
        area: [1, "<map>", "</map>"],
        param: [1, "<object>", "</object>"],
        thead: [1, "<table>", "</table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: nt.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
    },
    Gt = m(ht),
    Qt = Gt.appendChild(ht.createElement("div"));
    Yt.optgroup = Yt.option,
    Yt.tbody = Yt.tfoot = Yt.colgroup = Yt.caption = Yt.thead,
    Yt.th = Yt.td,
    it.extend({
        clone: function(e, t, n) {
            var r, i, o, a, s, u = it.contains(e.ownerDocument, e);
            if (nt.html5Clone || it.isXMLDoc(e) || !Bt.test("<" + e.nodeName + ">") ? o = e.cloneNode(!0) : (Qt.innerHTML = e.outerHTML, Qt.removeChild(o = Qt.firstChild)), !(nt.noCloneEvent && nt.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || it.isXMLDoc(e))) for (r = g(o), s = g(e), a = 0; null != (i = s[a]); ++a) r[a] && C(i, r[a]);
            if (t) if (n) for (s = s || g(e), r = r || g(o), a = 0; null != (i = s[a]); a++) T(i, r[a]);
            else T(e, o);
            return r = g(o, "script"),
            r.length > 0 && w(r, !u && g(e, "script")),
            r = s = i = null,
            o
        },
        buildFragment: function(e, t, n, r) {
            for (var i, o, a, s, u, l, c, d = e.length,
            f = m(t), p = [], h = 0; d > h; h++) if (o = e[h], o || 0 === o) if ("object" === it.type(o)) it.merge(p, o.nodeType ? [o] : o);
            else if (zt.test(o)) {
                for (s = s || f.appendChild(t.createElement("div")), u = (Wt.exec(o) || ["", ""])[1].toLowerCase(), c = Yt[u] || Yt._default, s.innerHTML = c[1] + o.replace(Rt, "<$1></$2>") + c[2], i = c[0]; i--;) s = s.lastChild;
                if (!nt.leadingWhitespace && Pt.test(o) && p.push(t.createTextNode(Pt.exec(o)[0])), !nt.tbody) for (o = "table" !== u || $t.test(o) ? "<table>" !== c[1] || $t.test(o) ? 0 : s: s.firstChild, i = o && o.childNodes.length; i--;) it.nodeName(l = o.childNodes[i], "tbody") && !l.childNodes.length && o.removeChild(l);
                for (it.merge(p, s.childNodes), s.textContent = ""; s.firstChild;) s.removeChild(s.firstChild);
                s = f.lastChild
            } else p.push(t.createTextNode(o));
            for (s && f.removeChild(s), nt.appendChecked || it.grep(g(p, "input"), v), h = 0; o = p[h++];) if ((!r || -1 === it.inArray(o, r)) && (a = it.contains(o.ownerDocument, o), s = g(f.appendChild(o), "script"), a && w(s), n)) for (i = 0; o = s[i++];) Ut.test(o.type || "") && n.push(o);
            return s = null,
            f
        },
        cleanData: function(e, t) {
            for (var n, r, i, o, a = 0,
            s = it.expando,
            u = it.cache,
            l = nt.deleteExpando,
            c = it.event.special; null != (n = e[a]); a++) if ((t || it.acceptData(n)) && (i = n[s], o = i && u[i])) {
                if (o.events) for (r in o.events) c[r] ? it.event.remove(n, r) : it.removeEvent(n, r, o.handle);
                u[i] && (delete u[i], l ? delete n[s] : typeof n.removeAttribute !== Ct ? n.removeAttribute(s) : n[s] = null, J.push(i))
            }
        }
    }),
    it.fn.extend({
        text: function(e) {
            return Dt(this,
            function(e) {
                return void 0 === e ? it.text(this) : this.empty().append((this[0] && this[0].ownerDocument || ht).createTextNode(e))
            },
            null, e, arguments.length)
        },
        append: function() {
            return this.domManip(arguments,
            function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.appendChild(e)
                }
            })
        },
        prepend: function() {
            return this.domManip(arguments,
            function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        },
        before: function() {
            return this.domManip(arguments,
            function(e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        },
        after: function() {
            return this.domManip(arguments,
            function(e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        },
        remove: function(e, t) {
            for (var n, r = e ? it.filter(e, this) : this, i = 0; null != (n = r[i]); i++) t || 1 !== n.nodeType || it.cleanData(g(n)),
            n.parentNode && (t && it.contains(n.ownerDocument, n) && w(g(n, "script")), n.parentNode.removeChild(n));
            return this
        },
        empty: function() {
            for (var e, t = 0; null != (e = this[t]); t++) {
                for (1 === e.nodeType && it.cleanData(g(e, !1)); e.firstChild;) e.removeChild(e.firstChild);
                e.options && it.nodeName(e, "select") && (e.options.length = 0)
            }
            return this
        },
        clone: function(e, t) {
            return e = null == e ? !1 : e,
            t = null == t ? e: t,
            this.map(function() {
                return it.clone(this, e, t)
            })
        },
        html: function(e) {
            return Dt(this,
            function(e) {
                var t = this[0] || {},
                n = 0,
                r = this.length;
                if (void 0 === e) return 1 === t.nodeType ? t.innerHTML.replace(Ot, "") : void 0;
                if (! ("string" != typeof e || It.test(e) || !nt.htmlSerialize && Bt.test(e) || !nt.leadingWhitespace && Pt.test(e) || Yt[(Wt.exec(e) || ["", ""])[1].toLowerCase()])) {
                    e = e.replace(Rt, "<$1></$2>");
                    try {
                        for (; r > n; n++) t = this[n] || {},
                        1 === t.nodeType && (it.cleanData(g(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch(i) {}
                }
                t && this.empty().append(e)
            },
            null, e, arguments.length)
        },
        replaceWith: function() {
            var e = arguments[0];
            return this.domManip(arguments,
            function(t) {
                e = this.parentNode,
                it.cleanData(g(this)),
                e && e.replaceChild(t, this)
            }),
            e && (e.length || e.nodeType) ? this: this.remove()
        },
        detach: function(e) {
            return this.remove(e, !0)
        },
        domManip: function(e, t) {
            e = G.apply([], e);
            var n, r, i, o, a, s, u = 0,
            l = this.length,
            c = this,
            d = l - 1,
            f = e[0],
            p = it.isFunction(f);
            if (p || l > 1 && "string" == typeof f && !nt.checkClone && Xt.test(f)) return this.each(function(n) {
                var r = c.eq(n);
                p && (e[0] = f.call(this, n, r.html())),
                r.domManip(e, t)
            });
            if (l && (s = it.buildFragment(e, this[0].ownerDocument, !1, this), n = s.firstChild, 1 === s.childNodes.length && (s = n), n)) {
                for (o = it.map(g(s, "script"), b), i = o.length; l > u; u++) r = s,
                u !== d && (r = it.clone(r, !0, !0), i && it.merge(o, g(r, "script"))),
                t.call(this[u], r, u);
                if (i) for (a = o[o.length - 1].ownerDocument, it.map(o, x), u = 0; i > u; u++) r = o[u],
                Ut.test(r.type || "") && !it._data(r, "globalEval") && it.contains(a, r) && (r.src ? it._evalUrl && it._evalUrl(r.src) : it.globalEval((r.text || r.textContent || r.innerHTML || "").replace(Jt, "")));
                s = n = null
            }
            return this
        }
    }),
    it.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    },
    function(e, t) {
        it.fn[e] = function(e) {
            for (var n, r = 0,
            i = [], o = it(e), a = o.length - 1; a >= r; r++) n = r === a ? this: this.clone(!0),
            it(o[r])[t](n),
            Q.apply(i, n.get());
            return this.pushStack(i)
        }
    });
    var Kt, Zt = {}; !
    function() {
        var e;
        nt.shrinkWrapBlocks = function() {
            if (null != e) return e;
            e = !1;
            var t, n, r;
            return n = ht.getElementsByTagName("body")[0],
            n && n.style ? (t = ht.createElement("div"), r = ht.createElement("div"), r.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(r).appendChild(t), typeof t.style.zoom !== Ct && (t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", t.appendChild(ht.createElement("div")).style.width = "5px", e = 3 !== t.offsetWidth), n.removeChild(r), e) : void 0
        }
    } ();
    var en, tn, nn = /^margin/,
    rn = new RegExp("^(" + kt + ")(?!px)[a-z%]+$", "i"),
    on = /^(top|right|bottom|left)$/;
    e.getComputedStyle ? (en = function(t) {
        return t.ownerDocument.defaultView.opener ? t.ownerDocument.defaultView.getComputedStyle(t, null) : e.getComputedStyle(t, null)
    },
    tn = function(e, t, n) {
        var r, i, o, a, s = e.style;
        return n = n || en(e),
        a = n ? n.getPropertyValue(t) || n[t] : void 0,
        n && ("" !== a || it.contains(e.ownerDocument, e) || (a = it.style(e, t)), rn.test(a) && nn.test(t) && (r = s.width, i = s.minWidth, o = s.maxWidth, s.minWidth = s.maxWidth = s.width = a, a = n.width, s.width = r, s.minWidth = i, s.maxWidth = o)),
        void 0 === a ? a: a + ""
    }) : ht.documentElement.currentStyle && (en = function(e) {
        return e.currentStyle
    },
    tn = function(e, t, n) {
        var r, i, o, a, s = e.style;
        return n = n || en(e),
        a = n ? n[t] : void 0,
        null == a && s && s[t] && (a = s[t]),
        rn.test(a) && !on.test(t) && (r = s.left, i = e.runtimeStyle, o = i && i.left, o && (i.left = e.currentStyle.left), s.left = "fontSize" === t ? "1em": a, a = s.pixelLeft + "px", s.left = r, o && (i.left = o)),
        void 0 === a ? a: a + "" || "auto"
    }),
    !
    function() {
        function t() {
            var t, n, r, i;
            n = ht.getElementsByTagName("body")[0],
            n && n.style && (t = ht.createElement("div"), r = ht.createElement("div"), r.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(r).appendChild(t), t.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", o = a = !1, u = !0, e.getComputedStyle && (o = "1%" !== (e.getComputedStyle(t, null) || {}).top, a = "4px" === (e.getComputedStyle(t, null) || {
                width: "4px"
            }).width, i = t.appendChild(ht.createElement("div")), i.style.cssText = t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", i.style.marginRight = i.style.width = "0", t.style.width = "1px", u = !parseFloat((e.getComputedStyle(i, null) || {}).marginRight), t.removeChild(i)), t.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", i = t.getElementsByTagName("td"), i[0].style.cssText = "margin:0;border:0;padding:0;display:none", s = 0 === i[0].offsetHeight, s && (i[0].style.display = "", i[1].style.display = "none", s = 0 === i[0].offsetHeight), n.removeChild(r))
        }
        var n, r, i, o, a, s, u;
        n = ht.createElement("div"),
        n.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        i = n.getElementsByTagName("a")[0],
        (r = i && i.style) && (r.cssText = "float:left;opacity:.5", nt.opacity = "0.5" === r.opacity, nt.cssFloat = !!r.cssFloat, n.style.backgroundClip = "content-box", n.cloneNode(!0).style.backgroundClip = "", nt.clearCloneStyle = "content-box" === n.style.backgroundClip, nt.boxSizing = "" === r.boxSizing || "" === r.MozBoxSizing || "" === r.WebkitBoxSizing, it.extend(nt, {
            reliableHiddenOffsets: function() {
                return null == s && t(),
                s
            },
            boxSizingReliable: function() {
                return null == a && t(),
                a
            },
            pixelPosition: function() {
                return null == o && t(),
                o
            },
            reliableMarginRight: function() {
                return null == u && t(),
                u
            }
        }))
    } (),
    it.swap = function(e, t, n, r) {
        var i, o, a = {};
        for (o in t) a[o] = e.style[o],
        e.style[o] = t[o];
        i = n.apply(e, r || []);
        for (o in t) e.style[o] = a[o];
        return i
    };
    var an = /alpha\([^)]*\)/i,
    sn = /opacity\s*=\s*([^)]*)/,
    un = /^(none|table(?!-c[ea]).+)/,
    ln = new RegExp("^(" + kt + ")(.*)$", "i"),
    cn = new RegExp("^([+-])=(" + kt + ")", "i"),
    dn = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    },
    fn = {
        letterSpacing: "0",
        fontWeight: "400"
    },
    pn = ["Webkit", "O", "Moz", "ms"];
    it.extend({
        cssHooks: {
            opacity: {
                get: function(e, t) {
                    if (t) {
                        var n = tn(e, "opacity");
                        return "" === n ? "1": n
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            "float": nt.cssFloat ? "cssFloat": "styleFloat"
        },
        style: function(e, t, n, r) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var i, o, a, s = it.camelCase(t),
                u = e.style;
                if (t = it.cssProps[s] || (it.cssProps[s] = S(u, s)), a = it.cssHooks[t] || it.cssHooks[s], void 0 === n) return a && "get" in a && void 0 !== (i = a.get(e, !1, r)) ? i: u[t];
                if (o = typeof n, "string" === o && (i = cn.exec(n)) && (n = (i[1] + 1) * i[2] + parseFloat(it.css(e, t)), o = "number"), null != n && n === n && ("number" !== o || it.cssNumber[s] || (n += "px"), nt.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (u[t] = "inherit"), !(a && "set" in a && void 0 === (n = a.set(e, n, r))))) try {
                    u[t] = n
                } catch(l) {}
            }
        },
        css: function(e, t, n, r) {
            var i, o, a, s = it.camelCase(t);
            return t = it.cssProps[s] || (it.cssProps[s] = S(e.style, s)),
            a = it.cssHooks[t] || it.cssHooks[s],
            a && "get" in a && (o = a.get(e, !0, n)),
            void 0 === o && (o = tn(e, t, r)),
            "normal" === o && t in fn && (o = fn[t]),
            "" === n || n ? (i = parseFloat(o), n === !0 || it.isNumeric(i) ? i || 0 : o) : o
        }
    }),
    it.each(["height", "width"],
    function(e, t) {
        it.cssHooks[t] = {
            get: function(e, n, r) {
                return n ? un.test(it.css(e, "display")) && 0 === e.offsetWidth ? it.swap(e, dn,
                function() {
                    return L(e, t, r)
                }) : L(e, t, r) : void 0
            },
            set: function(e, n, r) {
                var i = r && en(e);
                return D(e, n, r ? j(e, t, r, nt.boxSizing && "border-box" === it.css(e, "boxSizing", !1, i), i) : 0)
            }
        }
    }),
    nt.opacity || (it.cssHooks.opacity = {
        get: function(e, t) {
            return sn.test((t && e.currentStyle ? e.currentStyle.filter: e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "": t ? "1": ""
        },
        set: function(e, t) {
            var n = e.style,
            r = e.currentStyle,
            i = it.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")": "",
            o = r && r.filter || n.filter || "";
            n.zoom = 1,
            (t >= 1 || "" === t) && "" === it.trim(o.replace(an, "")) && n.removeAttribute && (n.removeAttribute("filter"), "" === t || r && !r.filter) || (n.filter = an.test(o) ? o.replace(an, i) : o + " " + i)
        }
    }),
    it.cssHooks.marginRight = k(nt.reliableMarginRight,
    function(e, t) {
        return t ? it.swap(e, {
            display: "inline-block"
        },
        tn, [e, "marginRight"]) : void 0
    }),
    it.each({
        margin: "",
        padding: "",
        border: "Width"
    },
    function(e, t) {
        it.cssHooks[e + t] = {
            expand: function(n) {
                for (var r = 0,
                i = {},
                o = "string" == typeof n ? n.split(" ") : [n]; 4 > r; r++) i[e + St[r] + t] = o[r] || o[r - 2] || o[0];
                return i
            }
        },
        nn.test(e) || (it.cssHooks[e + t].set = D)
    }),
    it.fn.extend({
        css: function(e, t) {
            return Dt(this,
            function(e, t, n) {
                var r, i, o = {},
                a = 0;
                if (it.isArray(t)) {
                    for (r = en(e), i = t.length; i > a; a++) o[t[a]] = it.css(e, t[a], !1, r);
                    return o
                }
                return void 0 !== n ? it.style(e, t, n) : it.css(e, t)
            },
            e, t, arguments.length > 1)
        },
        show: function() {
            return A(this, !0)
        },
        hide: function() {
            return A(this)
        },
        toggle: function(e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function() {
                At(this) ? it(this).show() : it(this).hide()
            })
        }
    }),
    it.Tween = H,
    H.prototype = {
        constructor: H,
        init: function(e, t, n, r, i, o) {
            this.elem = e,
            this.prop = n,
            this.easing = i || "swing",
            this.options = t,
            this.start = this.now = this.cur(),
            this.end = r,
            this.unit = o || (it.cssNumber[n] ? "": "px")
        },
        cur: function() {
            var e = H.propHooks[this.prop];
            return e && e.get ? e.get(this) : H.propHooks._default.get(this)
        },
        run: function(e) {
            var t, n = H.propHooks[this.prop];
            return this.pos = t = this.options.duration ? it.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : e,
            this.now = (this.end - this.start) * t + this.start,
            this.options.step && this.options.step.call(this.elem, this.now, this),
            n && n.set ? n.set(this) : H.propHooks._default.set(this),
            this
        }
    },
    H.prototype.init.prototype = H.prototype,
    H.propHooks = {
        _default: {
            get: function(e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = it.css(e.elem, e.prop, ""), t && "auto" !== t ? t: 0) : e.elem[e.prop]
            },
            set: function(e) {
                it.fx.step[e.prop] ? it.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[it.cssProps[e.prop]] || it.cssHooks[e.prop]) ? it.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    },
    H.propHooks.scrollTop = H.propHooks.scrollLeft = {
        set: function(e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    },
    it.easing = {
        linear: function(e) {
            return e
        },
        swing: function(e) {
            return.5 - Math.cos(e * Math.PI) / 2
        }
    },
    it.fx = H.prototype.init,
    it.fx.step = {};
    var hn, mn, gn = /^(?:toggle|show|hide)$/,
    vn = new RegExp("^(?:([+-])=|)(" + kt + ")([a-z%]*)$", "i"),
    yn = /queueHooks$/,
    bn = [F],
    xn = {
        "*": [function(e, t) {
            var n = this.createTween(e, t),
            r = n.cur(),
            i = vn.exec(t),
            o = i && i[3] || (it.cssNumber[e] ? "": "px"),
            a = (it.cssNumber[e] || "px" !== o && +r) && vn.exec(it.css(n.elem, e)),
            s = 1,
            u = 20;
            if (a && a[3] !== o) {
                o = o || a[3],
                i = i || [],
                a = +r || 1;
                do s = s || ".5",
                a /= s,
                it.style(n.elem, e, a + o);
                while (s !== (s = n.cur() / r) && 1 !== s && --u)
            }
            return i && (a = n.start = +a || +r || 0, n.unit = o, n.end = i[1] ? a + (i[1] + 1) * i[2] : +i[2]),
            n
        }]
    };
    it.Animation = it.extend(B, {
        tweener: function(e, t) {
            it.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            for (var n, r = 0,
            i = e.length; i > r; r++) n = e[r],
            xn[n] = xn[n] || [],
            xn[n].unshift(t)
        },
        prefilter: function(e, t) {
            t ? bn.unshift(e) : bn.push(e)
        }
    }),
    it.speed = function(e, t, n) {
        var r = e && "object" == typeof e ? it.extend({},
        e) : {
            complete: n || !n && t || it.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !it.isFunction(t) && t
        };
        return r.duration = it.fx.off ? 0 : "number" == typeof r.duration ? r.duration: r.duration in it.fx.speeds ? it.fx.speeds[r.duration] : it.fx.speeds._default,
        (null == r.queue || r.queue === !0) && (r.queue = "fx"),
        r.old = r.complete,
        r.complete = function() {
            it.isFunction(r.old) && r.old.call(this),
            r.queue && it.dequeue(this, r.queue)
        },
        r
    },
    it.fn.extend({
        fadeTo: function(e, t, n, r) {
            return this.filter(At).css("opacity", 0).show().end().animate({
                opacity: t
            },
            e, n, r)
        },
        animate: function(e, t, n, r) {
            var i = it.isEmptyObject(e),
            o = it.speed(t, n, r),
            a = function() {
                var t = B(this, it.extend({},
                e), o); (i || it._data(this, "finish")) && t.stop(!0)
            };
            return a.finish = a,
            i || o.queue === !1 ? this.each(a) : this.queue(o.queue, a)
        },
        stop: function(e, t, n) {
            var r = function(e) {
                var t = e.stop;
                delete e.stop,
                t(n)
            };
            return "string" != typeof e && (n = t, t = e, e = void 0),
            t && e !== !1 && this.queue(e || "fx", []),
            this.each(function() {
                var t = !0,
                i = null != e && e + "queueHooks",
                o = it.timers,
                a = it._data(this);
                if (i) a[i] && a[i].stop && r(a[i]);
                else for (i in a) a[i] && a[i].stop && yn.test(i) && r(a[i]);
                for (i = o.length; i--;) o[i].elem !== this || null != e && o[i].queue !== e || (o[i].anim.stop(n), t = !1, o.splice(i, 1)); (t || !n) && it.dequeue(this, e)
            })
        },
        finish: function(e) {
            return e !== !1 && (e = e || "fx"),
            this.each(function() {
                var t, n = it._data(this),
                r = n[e + "queue"],
                i = n[e + "queueHooks"],
                o = it.timers,
                a = r ? r.length: 0;
                for (n.finish = !0, it.queue(this, e, []), i && i.stop && i.stop.call(this, !0), t = o.length; t--;) o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                for (t = 0; a > t; t++) r[t] && r[t].finish && r[t].finish.call(this);
                delete n.finish
            })
        }
    }),
    it.each(["toggle", "show", "hide"],
    function(e, t) {
        var n = it.fn[t];
        it.fn[t] = function(e, r, i) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(_(t, !0), e, r, i)
        }
    }),
    it.each({
        slideDown: _("show"),
        slideUp: _("hide"),
        slideToggle: _("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    },
    function(e, t) {
        it.fn[e] = function(e, n, r) {
            return this.animate(t, e, n, r)
        }
    }),
    it.timers = [],
    it.fx.tick = function() {
        var e, t = it.timers,
        n = 0;
        for (hn = it.now(); n < t.length; n++) e = t[n],
        e() || t[n] !== e || t.splice(n--, 1);
        t.length || it.fx.stop(),
        hn = void 0
    },
    it.fx.timer = function(e) {
        it.timers.push(e),
        e() ? it.fx.start() : it.timers.pop()
    },
    it.fx.interval = 13,
    it.fx.start = function() {
        mn || (mn = setInterval(it.fx.tick, it.fx.interval))
    },
    it.fx.stop = function() {
        clearInterval(mn),
        mn = null
    },
    it.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    },
    it.fn.delay = function(e, t) {
        return e = it.fx ? it.fx.speeds[e] || e: e,
        t = t || "fx",
        this.queue(t,
        function(t, n) {
            var r = setTimeout(t, e);
            n.stop = function() {
                clearTimeout(r)
            }
        })
    },
    function() {
        var e, t, n, r, i;
        t = ht.createElement("div"),
        t.setAttribute("className", "t"),
        t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        r = t.getElementsByTagName("a")[0],
        n = ht.createElement("select"),
        i = n.appendChild(ht.createElement("option")),
        e = t.getElementsByTagName("input")[0],
        r.style.cssText = "top:1px",
        nt.getSetAttribute = "t" !== t.className,
        nt.style = /top/.test(r.getAttribute("style")),
        nt.hrefNormalized = "/a" === r.getAttribute("href"),
        nt.checkOn = !!e.value,
        nt.optSelected = i.selected,
        nt.enctype = !!ht.createElement("form").enctype,
        n.disabled = !0,
        nt.optDisabled = !i.disabled,
        e = ht.createElement("input"),
        e.setAttribute("value", ""),
        nt.input = "" === e.getAttribute("value"),
        e.value = "t",
        e.setAttribute("type", "radio"),
        nt.radioValue = "t" === e.value
    } ();
    var wn = /\r/g;
    it.fn.extend({
        val: function(e) {
            var t, n, r, i = this[0];
            return arguments.length ? (r = it.isFunction(e), this.each(function(n) {
                var i;
                1 === this.nodeType && (i = r ? e.call(this, n, it(this).val()) : e, null == i ? i = "": "number" == typeof i ? i += "": it.isArray(i) && (i = it.map(i,
                function(e) {
                    return null == e ? "": e + ""
                })), t = it.valHooks[this.type] || it.valHooks[this.nodeName.toLowerCase()], t && "set" in t && void 0 !== t.set(this, i, "value") || (this.value = i))
            })) : i ? (t = it.valHooks[i.type] || it.valHooks[i.nodeName.toLowerCase()], t && "get" in t && void 0 !== (n = t.get(i, "value")) ? n: (n = i.value, "string" == typeof n ? n.replace(wn, "") : null == n ? "": n)) : void 0
        }
    }),
    it.extend({
        valHooks: {
            option: {
                get: function(e) {
                    var t = it.find.attr(e, "value");
                    return null != t ? t: it.trim(it.text(e))
                }
            },
            select: {
                get: function(e) {
                    for (var t, n, r = e.options,
                    i = e.selectedIndex,
                    o = "select-one" === e.type || 0 > i,
                    a = o ? null: [], s = o ? i + 1 : r.length, u = 0 > i ? s: o ? i: 0; s > u; u++) if (n = r[u], !(!n.selected && u !== i || (nt.optDisabled ? n.disabled: null !== n.getAttribute("disabled")) || n.parentNode.disabled && it.nodeName(n.parentNode, "optgroup"))) {
                        if (t = it(n).val(), o) return t;
                        a.push(t)
                    }
                    return a
                },
                set: function(e, t) {
                    for (var n, r, i = e.options,
                    o = it.makeArray(t), a = i.length; a--;) if (r = i[a], it.inArray(it.valHooks.option.get(r), o) >= 0) try {
                        r.selected = n = !0
                    } catch(s) {
                        r.scrollHeight
                    } else r.selected = !1;
                    return n || (e.selectedIndex = -1),
                    i
                }
            }
        }
    }),
    it.each(["radio", "checkbox"],
    function() {
        it.valHooks[this] = {
            set: function(e, t) {
                return it.isArray(t) ? e.checked = it.inArray(it(e).val(), t) >= 0 : void 0
            }
        },
        nt.checkOn || (it.valHooks[this].get = function(e) {
            return null === e.getAttribute("value") ? "on": e.value
        })
    });
    var Tn, Cn, Nn = it.expr.attrHandle,
    En = /^(?:checked|selected)$/i,
    kn = nt.getSetAttribute,
    Sn = nt.input;
    it.fn.extend({
        attr: function(e, t) {
            return Dt(this, it.attr, e, t, arguments.length > 1)
        },
        removeAttr: function(e) {
            return this.each(function() {
                it.removeAttr(this, e)
            })
        }
    }),
    it.extend({
        attr: function(e, t, n) {
            var r, i, o = e.nodeType;
            return e && 3 !== o && 8 !== o && 2 !== o ? typeof e.getAttribute === Ct ? it.prop(e, t, n) : (1 === o && it.isXMLDoc(e) || (t = t.toLowerCase(), r = it.attrHooks[t] || (it.expr.match.bool.test(t) ? Cn: Tn)), void 0 === n ? r && "get" in r && null !== (i = r.get(e, t)) ? i: (i = it.find.attr(e, t), null == i ? void 0 : i) : null !== n ? r && "set" in r && void 0 !== (i = r.set(e, n, t)) ? i: (e.setAttribute(t, n + ""), n) : void it.removeAttr(e, t)) : void 0
        },
        removeAttr: function(e, t) {
            var n, r, i = 0,
            o = t && t.match(bt);
            if (o && 1 === e.nodeType) for (; n = o[i++];) r = it.propFix[n] || n,
            it.expr.match.bool.test(n) ? Sn && kn || !En.test(n) ? e[r] = !1 : e[it.camelCase("default-" + n)] = e[r] = !1 : it.attr(e, n, ""),
            e.removeAttribute(kn ? n: r)
        },
        attrHooks: {
            type: {
                set: function(e, t) {
                    if (!nt.radioValue && "radio" === t && it.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t),
                        n && (e.value = n),
                        t
                    }
                }
            }
        }
    }),
    Cn = {
        set: function(e, t, n) {
            return t === !1 ? it.removeAttr(e, n) : Sn && kn || !En.test(n) ? e.setAttribute(!kn && it.propFix[n] || n, n) : e[it.camelCase("default-" + n)] = e[n] = !0,
            n
        }
    },
    it.each(it.expr.match.bool.source.match(/\w+/g),
    function(e, t) {
        var n = Nn[t] || it.find.attr;
        Nn[t] = Sn && kn || !En.test(t) ?
        function(e, t, r) {
            var i, o;
            return r || (o = Nn[t], Nn[t] = i, i = null != n(e, t, r) ? t.toLowerCase() : null, Nn[t] = o),
            i
        }: function(e, t, n) {
            return n ? void 0 : e[it.camelCase("default-" + t)] ? t.toLowerCase() : null
        }
    }),
    Sn && kn || (it.attrHooks.value = {
        set: function(e, t, n) {
            return it.nodeName(e, "input") ? void(e.defaultValue = t) : Tn && Tn.set(e, t, n)
        }
    }),
    kn || (Tn = {
        set: function(e, t, n) {
            var r = e.getAttributeNode(n);
            return r || e.setAttributeNode(r = e.ownerDocument.createAttribute(n)),
            r.value = t += "",
            "value" === n || t === e.getAttribute(n) ? t: void 0
        }
    },
    Nn.id = Nn.name = Nn.coords = function(e, t, n) {
        var r;
        return n ? void 0 : (r = e.getAttributeNode(t)) && "" !== r.value ? r.value: null
    },
    it.valHooks.button = {
        get: function(e, t) {
            var n = e.getAttributeNode(t);
            return n && n.specified ? n.value: void 0
        },
        set: Tn.set
    },
    it.attrHooks.contenteditable = {
        set: function(e, t, n) {
            Tn.set(e, "" === t ? !1 : t, n)
        }
    },
    it.each(["width", "height"],
    function(e, t) {
        it.attrHooks[t] = {
            set: function(e, n) {
                return "" === n ? (e.setAttribute(t, "auto"), n) : void 0
            }
        }
    })),
    nt.style || (it.attrHooks.style = {
        get: function(e) {
            return e.style.cssText || void 0
        },
        set: function(e, t) {
            return e.style.cssText = t + ""
        }
    });
    var An = /^(?:input|select|textarea|button|object)$/i,
    Dn = /^(?:a|area)$/i;
    it.fn.extend({
        prop: function(e, t) {
            return Dt(this, it.prop, e, t, arguments.length > 1)
        },
        removeProp: function(e) {
            return e = it.propFix[e] || e,
            this.each(function() {
                try {
                    this[e] = void 0,
                    delete this[e]
                } catch(t) {}
            })
        }
    }),
    it.extend({
        propFix: {
            "for": "htmlFor",
            "class": "className"
        },
        prop: function(e, t, n) {
            var r, i, o, a = e.nodeType;
            return e && 3 !== a && 8 !== a && 2 !== a ? (o = 1 !== a || !it.isXMLDoc(e), o && (t = it.propFix[t] || t, i = it.propHooks[t]), void 0 !== n ? i && "set" in i && void 0 !== (r = i.set(e, n, t)) ? r: e[t] = n: i && "get" in i && null !== (r = i.get(e, t)) ? r: e[t]) : void 0
        },
        propHooks: {
            tabIndex: {
                get: function(e) {
                    var t = it.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : An.test(e.nodeName) || Dn.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        }
    }),
    nt.hrefNormalized || it.each(["href", "src"],
    function(e, t) {
        it.propHooks[t] = {
            get: function(e) {
                return e.getAttribute(t, 4)
            }
        }
    }),
    nt.optSelected || (it.propHooks.selected = {
        get: function(e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex),
            null
        }
    }),
    it.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"],
    function() {
        it.propFix[this.toLowerCase()] = this
    }),
    nt.enctype || (it.propFix.enctype = "encoding");
    var jn = /[\t\r\n\f]/g;
    it.fn.extend({
        addClass: function(e) {
            var t, n, r, i, o, a, s = 0,
            u = this.length,
            l = "string" == typeof e && e;
            if (it.isFunction(e)) return this.each(function(t) {
                it(this).addClass(e.call(this, t, this.className))
            });
            if (l) for (t = (e || "").match(bt) || []; u > s; s++) if (n = this[s], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(jn, " ") : " ")) {
                for (o = 0; i = t[o++];) r.indexOf(" " + i + " ") < 0 && (r += i + " ");
                a = it.trim(r),
                n.className !== a && (n.className = a)
            }
            return this
        },
        removeClass: function(e) {
            var t, n, r, i, o, a, s = 0,
            u = this.length,
            l = 0 === arguments.length || "string" == typeof e && e;
            if (it.isFunction(e)) return this.each(function(t) {
                it(this).removeClass(e.call(this, t, this.className))
            });
            if (l) for (t = (e || "").match(bt) || []; u > s; s++) if (n = this[s], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(jn, " ") : "")) {
                for (o = 0; i = t[o++];) for (; r.indexOf(" " + i + " ") >= 0;) r = r.replace(" " + i + " ", " ");
                a = e ? it.trim(r) : "",
                n.className !== a && (n.className = a)
            }
            return this
        },
        toggleClass: function(e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : this.each(it.isFunction(e) ?
            function(n) {
                it(this).toggleClass(e.call(this, n, this.className, t), t)
            }: function() {
                if ("string" === n) for (var t, r = 0,
                i = it(this), o = e.match(bt) || []; t = o[r++];) i.hasClass(t) ? i.removeClass(t) : i.addClass(t);
                else(n === Ct || "boolean" === n) && (this.className && it._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "": it._data(this, "__className__") || "")
            })
        },
        hasClass: function(e) {
            for (var t = " " + e + " ",
            n = 0,
            r = this.length; r > n; n++) if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(jn, " ").indexOf(t) >= 0) return ! 0;
            return ! 1
        }
    }),
    it.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),
    function(e, t) {
        it.fn[t] = function(e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }),
    it.fn.extend({
        hover: function(e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        },
        bind: function(e, t, n) {
            return this.on(e, null, t, n)
        },
        unbind: function(e, t) {
            return this.off(e, null, t)
        },
        delegate: function(e, t, n, r) {
            return this.on(t, e, n, r)
        },
        undelegate: function(e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var Ln = it.now(),
    Hn = /\?/,
    qn = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    it.parseJSON = function(t) {
        if (e.JSON && e.JSON.parse) return e.JSON.parse(t + "");
        var n, r = null,
        i = it.trim(t + "");
        return i && !it.trim(i.replace(qn,
        function(e, t, i, o) {
            return n && t && (r = 0),
            0 === r ? e: (n = i || t, r += !o - !i, "")
        })) ? Function("return " + i)() : it.error("Invalid JSON: " + t)
    },
    it.parseXML = function(t) {
        var n, r;
        if (!t || "string" != typeof t) return null;
        try {
            e.DOMParser ? (r = new DOMParser, n = r.parseFromString(t, "text/xml")) : (n = new ActiveXObject("Microsoft.XMLDOM"), n.async = "false", n.loadXML(t))
        } catch(i) {
            n = void 0
        }
        return n && n.documentElement && !n.getElementsByTagName("parsererror").length || it.error("Invalid XML: " + t),
        n
    };
    var _n, Mn, Fn = /#.*$/,
    On = /([?&])_=[^&]*/,
    Bn = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
    Pn = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
    Rn = /^(?:GET|HEAD)$/,
    Wn = /^\/\//,
    $n = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,
    zn = {},
    In = {},
    Xn = "*/".concat("*");
    try {
        Mn = location.href
    } catch(Un) {
        Mn = ht.createElement("a"),
        Mn.href = "",
        Mn = Mn.href
    }
    _n = $n.exec(Mn.toLowerCase()) || [],
    it.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Mn,
            type: "GET",
            isLocal: Pn.test(_n[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Xn,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": !0,
                "text json": it.parseJSON,
                "text xml": it.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(e, t) {
            return t ? W(W(e, it.ajaxSettings), t) : W(it.ajaxSettings, e)
        },
        ajaxPrefilter: P(zn),
        ajaxTransport: P(In),
        ajax: function(e, t) {
            function n(e, t, n, r) {
                var i, c, v, y, x, T = t;
                2 !== b && (b = 2, s && clearTimeout(s), l = void 0, a = r || "", w.readyState = e > 0 ? 4 : 0, i = e >= 200 && 300 > e || 304 === e, n && (y = $(d, w, n)), y = z(d, y, w, i), i ? (d.ifModified && (x = w.getResponseHeader("Last-Modified"), x && (it.lastModified[o] = x), x = w.getResponseHeader("etag"), x && (it.etag[o] = x)), 204 === e || "HEAD" === d.type ? T = "nocontent": 304 === e ? T = "notmodified": (T = y.state, c = y.data, v = y.error, i = !v)) : (v = T, (e || !T) && (T = "error", 0 > e && (e = 0))), w.status = e, w.statusText = (t || T) + "", i ? h.resolveWith(f, [c, T, w]) : h.rejectWith(f, [w, T, v]), w.statusCode(g), g = void 0, u && p.trigger(i ? "ajaxSuccess": "ajaxError", [w, d, i ? c: v]), m.fireWith(f, [w, T]), u && (p.trigger("ajaxComplete", [w, d]), --it.active || it.event.trigger("ajaxStop")))
            }
            "object" == typeof e && (t = e, e = void 0),
            t = t || {};
            var r, i, o, a, s, u, l, c, d = it.ajaxSetup({},
            t),
            f = d.context || d,
            p = d.context && (f.nodeType || f.jquery) ? it(f) : it.event,
            h = it.Deferred(),
            m = it.Callbacks("once memory"),
            g = d.statusCode || {},
            v = {},
            y = {},
            b = 0,
            x = "canceled",
            w = {
                readyState: 0,
                getResponseHeader: function(e) {
                    var t;
                    if (2 === b) {
                        if (!c) for (c = {}; t = Bn.exec(a);) c[t[1].toLowerCase()] = t[2];
                        t = c[e.toLowerCase()]
                    }
                    return null == t ? null: t
                },
                getAllResponseHeaders: function() {
                    return 2 === b ? a: null
                },
                setRequestHeader: function(e, t) {
                    var n = e.toLowerCase();
                    return b || (e = y[n] = y[n] || e, v[e] = t),
                    this
                },
                overrideMimeType: function(e) {
                    return b || (d.mimeType = e),
                    this
                },
                statusCode: function(e) {
                    var t;
                    if (e) if (2 > b) for (t in e) g[t] = [g[t], e[t]];
                    else w.always(e[w.status]);
                    return this
                },
                abort: function(e) {
                    var t = e || x;
                    return l && l.abort(t),
                    n(0, t),
                    this
                }
            };
            if (h.promise(w).complete = m.add, w.success = w.done, w.error = w.fail, d.url = ((e || d.url || Mn) + "").replace(Fn, "").replace(Wn, _n[1] + "//"), d.type = t.method || t.type || d.method || d.type, d.dataTypes = it.trim(d.dataType || "*").toLowerCase().match(bt) || [""], null == d.crossDomain && (r = $n.exec(d.url.toLowerCase()), d.crossDomain = !(!r || r[1] === _n[1] && r[2] === _n[2] && (r[3] || ("http:" === r[1] ? "80": "443")) === (_n[3] || ("http:" === _n[1] ? "80": "443")))), d.data && d.processData && "string" != typeof d.data && (d.data = it.param(d.data, d.traditional)), R(zn, d, t, w), 2 === b) return w;
            u = it.event && d.global,
            u && 0 === it.active++&&it.event.trigger("ajaxStart"),
            d.type = d.type.toUpperCase(),
            d.hasContent = !Rn.test(d.type),
            o = d.url,
            d.hasContent || (d.data && (o = d.url += (Hn.test(o) ? "&": "?") + d.data, delete d.data), d.cache === !1 && (d.url = On.test(o) ? o.replace(On, "$1_=" + Ln++) : o + (Hn.test(o) ? "&": "?") + "_=" + Ln++)),
            d.ifModified && (it.lastModified[o] && w.setRequestHeader("If-Modified-Since", it.lastModified[o]), it.etag[o] && w.setRequestHeader("If-None-Match", it.etag[o])),
            (d.data && d.hasContent && d.contentType !== !1 || t.contentType) && w.setRequestHeader("Content-Type", d.contentType),
            w.setRequestHeader("Accept", d.dataTypes[0] && d.accepts[d.dataTypes[0]] ? d.accepts[d.dataTypes[0]] + ("*" !== d.dataTypes[0] ? ", " + Xn + "; q=0.01": "") : d.accepts["*"]);
            for (i in d.headers) w.setRequestHeader(i, d.headers[i]);
            if (d.beforeSend && (d.beforeSend.call(f, w, d) === !1 || 2 === b)) return w.abort();
            x = "abort";
            for (i in {
                success: 1,
                error: 1,
                complete: 1
            }) w[i](d[i]);
            if (l = R(In, d, t, w)) {
                w.readyState = 1,
                u && p.trigger("ajaxSend", [w, d]),
                d.async && d.timeout > 0 && (s = setTimeout(function() {
                    w.abort("timeout")
                },
                d.timeout));
                try {
                    b = 1,
                    l.send(v, n)
                } catch(T) {
                    if (! (2 > b)) throw T;
                    n( - 1, T)
                }
            } else n( - 1, "No Transport");
            return w
        },
        getJSON: function(e, t, n) {
            return it.get(e, t, n, "json")
        },
        getScript: function(e, t) {
            return it.get(e, void 0, t, "script")
        }
    }),
    it.each(["get", "post"],
    function(e, t) {
        it[t] = function(e, n, r, i) {
            return it.isFunction(n) && (i = i || r, r = n, n = void 0),
            it.ajax({
                url: e,
                type: t,
                dataType: i,
                data: n,
                success: r
            })
        }
    }),
    it._evalUrl = function(e) {
        return it.ajax({
            url: e,
            type: "GET",
            dataType: "script",
            async: !1,
            global: !1,
            "throws": !0
        })
    },
    it.fn.extend({
        wrapAll: function(e) {
            if (it.isFunction(e)) return this.each(function(t) {
                it(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = it(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]),
                t.map(function() {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;) e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        },
        wrapInner: function(e) {
            return this.each(it.isFunction(e) ?
            function(t) {
                it(this).wrapInner(e.call(this, t))
            }: function() {
                var t = it(this),
                n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        },
        wrap: function(e) {
            var t = it.isFunction(e);
            return this.each(function(n) {
                it(this).wrapAll(t ? e.call(this, n) : e)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                it.nodeName(this, "body") || it(this).replaceWith(this.childNodes)
            }).end()
        }
    }),
    it.expr.filters.hidden = function(e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !nt.reliableHiddenOffsets() && "none" === (e.style && e.style.display || it.css(e, "display"))
    },
    it.expr.filters.visible = function(e) {
        return ! it.expr.filters.hidden(e)
    };
    var Vn = /%20/g,
    Jn = /\[\]$/,
    Yn = /\r?\n/g,
    Gn = /^(?:submit|button|image|reset|file)$/i,
    Qn = /^(?:input|select|textarea|keygen)/i;
    it.param = function(e, t) {
        var n, r = [],
        i = function(e, t) {
            t = it.isFunction(t) ? t() : null == t ? "": t,
            r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
        };
        if (void 0 === t && (t = it.ajaxSettings && it.ajaxSettings.traditional), it.isArray(e) || e.jquery && !it.isPlainObject(e)) it.each(e,
        function() {
            i(this.name, this.value)
        });
        else for (n in e) I(n, e[n], t, i);
        return r.join("&").replace(Vn, "+")
    },
    it.fn.extend({
        serialize: function() {
            return it.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var e = it.prop(this, "elements");
                return e ? it.makeArray(e) : this
            }).filter(function() {
                var e = this.type;
                return this.name && !it(this).is(":disabled") && Qn.test(this.nodeName) && !Gn.test(e) && (this.checked || !jt.test(e))
            }).map(function(e, t) {
                var n = it(this).val();
                return null == n ? null: it.isArray(n) ? it.map(n,
                function(e) {
                    return {
                        name: t.name,
                        value: e.replace(Yn, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Yn, "\r\n")
                }
            }).get()
        }
    }),
    it.ajaxSettings.xhr = void 0 !== e.ActiveXObject ?
    function() {
        return ! this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && X() || U()
    }: X;
    var Kn = 0,
    Zn = {},
    er = it.ajaxSettings.xhr();
    e.attachEvent && e.attachEvent("onunload",
    function() {
        for (var e in Zn) Zn[e](void 0, !0)
    }),
    nt.cors = !!er && "withCredentials" in er,
    er = nt.ajax = !!er,
    er && it.ajaxTransport(function(e) {
        if (!e.crossDomain || nt.cors) {
            var t;
            return {
                send: function(n, r) {
                    var i, o = e.xhr(),
                    a = ++Kn;
                    if (o.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields) for (i in e.xhrFields) o[i] = e.xhrFields[i];
                    e.mimeType && o.overrideMimeType && o.overrideMimeType(e.mimeType),
                    e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                    for (i in n) void 0 !== n[i] && o.setRequestHeader(i, n[i] + "");
                    o.send(e.hasContent && e.data || null),
                    t = function(n, i) {
                        var s, u, l;
                        if (t && (i || 4 === o.readyState)) if (delete Zn[a], t = void 0, o.onreadystatechange = it.noop, i) 4 !== o.readyState && o.abort();
                        else {
                            l = {},
                            s = o.status,
                            "string" == typeof o.responseText && (l.text = o.responseText);
                            try {
                                u = o.statusText
                            } catch(c) {
                                u = ""
                            }
                            s || !e.isLocal || e.crossDomain ? 1223 === s && (s = 204) : s = l.text ? 200 : 404
                        }
                        l && r(s, u, l, o.getAllResponseHeaders())
                    },
                    e.async ? 4 === o.readyState ? setTimeout(t) : o.onreadystatechange = Zn[a] = t: t()
                },
                abort: function() {
                    t && t(void 0, !0)
                }
            }
        }
    }),
    it.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(e) {
                return it.globalEval(e),
                e
            }
        }
    }),
    it.ajaxPrefilter("script",
    function(e) {
        void 0 === e.cache && (e.cache = !1),
        e.crossDomain && (e.type = "GET", e.global = !1)
    }),
    it.ajaxTransport("script",
    function(e) {
        if (e.crossDomain) {
            var t, n = ht.head || it("head")[0] || ht.documentElement;
            return {
                send: function(r, i) {
                    t = ht.createElement("script"),
                    t.async = !0,
                    e.scriptCharset && (t.charset = e.scriptCharset),
                    t.src = e.url,
                    t.onload = t.onreadystatechange = function(e, n) { (n || !t.readyState || /loaded|complete/.test(t.readyState)) && (t.onload = t.onreadystatechange = null, t.parentNode && t.parentNode.removeChild(t), t = null, n || i(200, "success"))
                    },
                    n.insertBefore(t, n.firstChild)
                },
                abort: function() {
                    t && t.onload(void 0, !0)
                }
            }
        }
    });
    var tr = [],
    nr = /(=)\?(?=&|$)|\?\?/;
    it.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var e = tr.pop() || it.expando + "_" + Ln++;
            return this[e] = !0,
            e
        }
    }),
    it.ajaxPrefilter("json jsonp",
    function(t, n, r) {
        var i, o, a, s = t.jsonp !== !1 && (nr.test(t.url) ? "url": "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && nr.test(t.data) && "data");
        return s || "jsonp" === t.dataTypes[0] ? (i = t.jsonpCallback = it.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, s ? t[s] = t[s].replace(nr, "$1" + i) : t.jsonp !== !1 && (t.url += (Hn.test(t.url) ? "&": "?") + t.jsonp + "=" + i), t.converters["script json"] = function() {
            return a || it.error(i + " was not called"),
            a[0]
        },
        t.dataTypes[0] = "json", o = e[i], e[i] = function() {
            a = arguments
        },
        r.always(function() {
            e[i] = o,
            t[i] && (t.jsonpCallback = n.jsonpCallback, tr.push(i)),
            a && it.isFunction(o) && o(a[0]),
            a = o = void 0
        }), "script") : void 0
    }),
    it.parseHTML = function(e, t, n) {
        if (!e || "string" != typeof e) return null;
        "boolean" == typeof t && (n = t, t = !1),
        t = t || ht;
        var r = dt.exec(e),
        i = !n && [];
        return r ? [t.createElement(r[1])] : (r = it.buildFragment([e], t, i), i && i.length && it(i).remove(), it.merge([], r.childNodes))
    };
    var rr = it.fn.load;
    it.fn.load = function(e, t, n) {
        if ("string" != typeof e && rr) return rr.apply(this, arguments);
        var r, i, o, a = this,
        s = e.indexOf(" ");
        return s >= 0 && (r = it.trim(e.slice(s, e.length)), e = e.slice(0, s)),
        it.isFunction(t) ? (n = t, t = void 0) : t && "object" == typeof t && (o = "POST"),
        a.length > 0 && it.ajax({
            url: e,
            type: o,
            dataType: "html",
            data: t
        }).done(function(e) {
            i = arguments,
            a.html(r ? it("<div>").append(it.parseHTML(e)).find(r) : e)
        }).complete(n &&
        function(e, t) {
            a.each(n, i || [e.responseText, t, e])
        }),
        this
    },
    it.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"],
    function(e, t) {
        it.fn[t] = function(e) {
            return this.on(t, e)
        }
    }),
    it.expr.filters.animated = function(e) {
        return it.grep(it.timers,
        function(t) {
            return e === t.elem
        }).length
    };
    var ir = e.document.documentElement;
    it.offset = {
        setOffset: function(e, t, n) {
            var r, i, o, a, s, u, l, c = it.css(e, "position"),
            d = it(e),
            f = {};
            "static" === c && (e.style.position = "relative"),
            s = d.offset(),
            o = it.css(e, "top"),
            u = it.css(e, "left"),
            l = ("absolute" === c || "fixed" === c) && it.inArray("auto", [o, u]) > -1,
            l ? (r = d.position(), a = r.top, i = r.left) : (a = parseFloat(o) || 0, i = parseFloat(u) || 0),
            it.isFunction(t) && (t = t.call(e, n, s)),
            null != t.top && (f.top = t.top - s.top + a),
            null != t.left && (f.left = t.left - s.left + i),
            "using" in t ? t.using.call(e, f) : d.css(f)
        }
    },
    it.fn.extend({
        offset: function(e) {
            if (arguments.length) return void 0 === e ? this: this.each(function(t) {
                it.offset.setOffset(this, e, t)
            });
            var t, n, r = {
                top: 0,
                left: 0
            },
            i = this[0],
            o = i && i.ownerDocument;
            return o ? (t = o.documentElement, it.contains(t, i) ? (typeof i.getBoundingClientRect !== Ct && (r = i.getBoundingClientRect()), n = V(o), {
                top: r.top + (n.pageYOffset || t.scrollTop) - (t.clientTop || 0),
                left: r.left + (n.pageXOffset || t.scrollLeft) - (t.clientLeft || 0)
            }) : r) : void 0
        },
        position: function() {
            if (this[0]) {
                var e, t, n = {
                    top: 0,
                    left: 0
                },
                r = this[0];
                return "fixed" === it.css(r, "position") ? t = r.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), it.nodeName(e[0], "html") || (n = e.offset()), n.top += it.css(e[0], "borderTopWidth", !0), n.left += it.css(e[0], "borderLeftWidth", !0)),
                {
                    top: t.top - n.top - it.css(r, "marginTop", !0),
                    left: t.left - n.left - it.css(r, "marginLeft", !0)
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var e = this.offsetParent || ir; e && !it.nodeName(e, "html") && "static" === it.css(e, "position");) e = e.offsetParent;
                return e || ir
            })
        }
    }),
    it.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    },
    function(e, t) {
        var n = /Y/.test(t);
        it.fn[e] = function(r) {
            return Dt(this,
            function(e, r, i) {
                var o = V(e);
                return void 0 === i ? o ? t in o ? o[t] : o.document.documentElement[r] : e[r] : void(o ? o.scrollTo(n ? it(o).scrollLeft() : i, n ? i: it(o).scrollTop()) : e[r] = i)
            },
            e, r, arguments.length, null)
        }
    }),
    it.each(["top", "left"],
    function(e, t) {
        it.cssHooks[t] = k(nt.pixelPosition,
        function(e, n) {
            return n ? (n = tn(e, t), rn.test(n) ? it(e).position()[t] + "px": n) : void 0
        })
    }),
    it.each({
        Height: "height",
        Width: "width"
    },
    function(e, t) {
        it.each({
            padding: "inner" + e,
            content: t,
            "": "outer" + e
        },
        function(n, r) {
            it.fn[r] = function(r, i) {
                var o = arguments.length && (n || "boolean" != typeof r),
                a = n || (r === !0 || i === !0 ? "margin": "border");
                return Dt(this,
                function(t, n, r) {
                    var i;
                    return it.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (i = t.documentElement, Math.max(t.body["scroll" + e], i["scroll" + e], t.body["offset" + e], i["offset" + e], i["client" + e])) : void 0 === r ? it.css(t, n, a) : it.style(t, n, r, a)
                },
                t, o ? r: void 0, o, null)
            }
        })
    }),
    it.fn.size = function() {
        return this.length
    },
    it.fn.andSelf = it.fn.addBack,
    "function" == typeof define && define.amd && define("jquery", [],
    function() {
        return it
    });
    var or = e.jQuery,
    ar = e.$;
    return it.noConflict = function(t) {
        return e.$ === it && (e.$ = ar),
        t && e.jQuery === it && (e.jQuery = or),
        it
    },
    typeof t === Ct && (e.jQuery = e.$ = it),
    it
});;
var nie = nie || {}; !
function(t) {
    t.host = function() {
        var t = window.location.protocol,
        e = "";
        return e = t.indexOf("https") > -1 || window.location.hostname.indexOf("163") < 0 ? "https://nie.res.netease.com": "http://res.nie.netease.com"
    } ()
} (jQuery),
!
function(t) {
    t.browser = t.browser ||
    function() {
        var t = navigator.userAgent.toLowerCase(),
        e = /(webkit)[ \/]([\w.]+)/,
        n = /(opera)(?:.*version)?[ \/]([\w.]+)/,
        i = /(msie) ([\w.]+)/,
        o = /(mozilla)(?:.*? rv:([\w.]+))?/,
        r = e.exec(t) || n.exec(t) || i.exec(t) || t.indexOf("compatible") < 0 && o.exec(t) || [];
        return {
            browser: r[1] || "",
            version: r[2] || "0",
            msie: "msie" == r[1]
        }
    } ()
} (jQuery),
!
function() {
    jQuery.cookie = function(t, e, n) {
        if ("undefined" == typeof e) {
            var i = null;
            if (document.cookie && "" != document.cookie) for (var o = document.cookie.split(";"), r = 0; r < o.length; r++) {
                var a = jQuery.trim(o[r]);
                if (a.substring(0, t.length + 1) == t + "=") {
                    i = decodeURIComponent(a.substring(t.length + 1));
                    break
                }
            }
            return i
        }
        n = n || {},
        null === e && (e = "", n.expires = -1);
        var c = "";
        if (n.expires && ("number" == typeof n.expires || n.expires.toUTCString)) {
            var u;
            "number" == typeof n.expires ? (u = new Date, u.setTime(u.getTime() + 24 * n.expires * 60 * 60 * 1e3)) : u = n.expires,
            c = "; expires=" + u.toUTCString()
        }
        var s = n.path ? "; path=" + n.path: "",
        l = n.domain ? "; domain=" + n.domain: "",
        d = n.secure ? "; secure": "";
        document.cookie = [t, "=", encodeURIComponent(e), c, s, l, d].join("")
    }
} (jQuery),
!
function(t) {
    var e = function(t, e) {
        return t << e | t >>> 32 - e
    },
    n = function(t, e) {
        var n, i, o, r, a;
        return o = 2147483648 & t,
        r = 2147483648 & e,
        n = 1073741824 & t,
        i = 1073741824 & e,
        a = (1073741823 & t) + (1073741823 & e),
        n & i ? 2147483648 ^ a ^ o ^ r: n | i ? 1073741824 & a ? 3221225472 ^ a ^ o ^ r: 1073741824 ^ a ^ o ^ r: a ^ o ^ r
    },
    i = function(t, e, n) {
        return t & e | ~t & n
    },
    o = function(t, e, n) {
        return t & n | e & ~n
    },
    r = function(t, e, n) {
        return t ^ e ^ n
    },
    a = function(t, e, n) {
        return e ^ (t | ~n)
    },
    c = function(t, o, r, a, c, u, s) {
        return t = n(t, n(n(i(o, r, a), c), s)),
        n(e(t, u), o)
    },
    u = function(t, i, r, a, c, u, s) {
        return t = n(t, n(n(o(i, r, a), c), s)),
        n(e(t, u), i)
    },
    s = function(t, i, o, a, c, u, s) {
        return t = n(t, n(n(r(i, o, a), c), s)),
        n(e(t, u), i)
    },
    l = function(t, i, o, r, c, u, s) {
        return t = n(t, n(n(a(i, o, r), c), s)),
        n(e(t, u), i)
    },
    d = function(t) {
        for (var e, n = t.length,
        i = n + 8,
        o = (i - i % 64) / 64, r = 16 * (o + 1), a = Array(r - 1), c = 0, u = 0; n > u;) e = (u - u % 4) / 4,
        c = u % 4 * 8,
        a[e] = a[e] | t.charCodeAt(u) << c,
        u++;
        return e = (u - u % 4) / 4,
        c = u % 4 * 8,
        a[e] = a[e] | 128 << c,
        a[r - 2] = n << 3,
        a[r - 1] = n >>> 29,
        a
    },
    f = function(t) {
        var e, n, i = "",
        o = "";
        for (n = 0; 3 >= n; n++) e = t >>> 8 * n & 255,
        o = "0" + e.toString(16),
        i += o.substr(o.length - 2, 2);
        return i
    },
    m = function(t) {
        t = t.replace(/\x0d\x0a/g, "\n");
        for (var e = "",
        n = 0; n < t.length; n++) {
            var i = t.charCodeAt(n);
            128 > i ? e += String.fromCharCode(i) : i > 127 && 2048 > i ? (e += String.fromCharCode(i >> 6 | 192), e += String.fromCharCode(63 & i | 128)) : (e += String.fromCharCode(i >> 12 | 224), e += String.fromCharCode(i >> 6 & 63 | 128), e += String.fromCharCode(63 & i | 128))
        }
        return e
    };
    t.extend(t, {
        md5: function(t) {
            var e, i, o, r, a, h, p, g, y, w = Array(),
            x = 7,
            b = 12,
            v = 17,
            _ = 22,
            j = 5,
            k = 9,
            S = 14,
            z = 20,
            C = 4,
            D = 11,
            q = 16,
            L = 23,
            T = 6,
            P = 10,
            R = 15,
            B = 21;
            for (t = m(t), w = d(t), h = 1732584193, p = 4023233417, g = 2562383102, y = 271733878, e = 0; e < w.length; e += 16) i = h,
            o = p,
            r = g,
            a = y,
            h = c(h, p, g, y, w[e + 0], x, 3614090360),
            y = c(y, h, p, g, w[e + 1], b, 3905402710),
            g = c(g, y, h, p, w[e + 2], v, 606105819),
            p = c(p, g, y, h, w[e + 3], _, 3250441966),
            h = c(h, p, g, y, w[e + 4], x, 4118548399),
            y = c(y, h, p, g, w[e + 5], b, 1200080426),
            g = c(g, y, h, p, w[e + 6], v, 2821735955),
            p = c(p, g, y, h, w[e + 7], _, 4249261313),
            h = c(h, p, g, y, w[e + 8], x, 1770035416),
            y = c(y, h, p, g, w[e + 9], b, 2336552879),
            g = c(g, y, h, p, w[e + 10], v, 4294925233),
            p = c(p, g, y, h, w[e + 11], _, 2304563134),
            h = c(h, p, g, y, w[e + 12], x, 1804603682),
            y = c(y, h, p, g, w[e + 13], b, 4254626195),
            g = c(g, y, h, p, w[e + 14], v, 2792965006),
            p = c(p, g, y, h, w[e + 15], _, 1236535329),
            h = u(h, p, g, y, w[e + 1], j, 4129170786),
            y = u(y, h, p, g, w[e + 6], k, 3225465664),
            g = u(g, y, h, p, w[e + 11], S, 643717713),
            p = u(p, g, y, h, w[e + 0], z, 3921069994),
            h = u(h, p, g, y, w[e + 5], j, 3593408605),
            y = u(y, h, p, g, w[e + 10], k, 38016083),
            g = u(g, y, h, p, w[e + 15], S, 3634488961),
            p = u(p, g, y, h, w[e + 4], z, 3889429448),
            h = u(h, p, g, y, w[e + 9], j, 568446438),
            y = u(y, h, p, g, w[e + 14], k, 3275163606),
            g = u(g, y, h, p, w[e + 3], S, 4107603335),
            p = u(p, g, y, h, w[e + 8], z, 1163531501),
            h = u(h, p, g, y, w[e + 13], j, 2850285829),
            y = u(y, h, p, g, w[e + 2], k, 4243563512),
            g = u(g, y, h, p, w[e + 7], S, 1735328473),
            p = u(p, g, y, h, w[e + 12], z, 2368359562),
            h = s(h, p, g, y, w[e + 5], C, 4294588738),
            y = s(y, h, p, g, w[e + 8], D, 2272392833),
            g = s(g, y, h, p, w[e + 11], q, 1839030562),
            p = s(p, g, y, h, w[e + 14], L, 4259657740),
            h = s(h, p, g, y, w[e + 1], C, 2763975236),
            y = s(y, h, p, g, w[e + 4], D, 1272893353),
            g = s(g, y, h, p, w[e + 7], q, 4139469664),
            p = s(p, g, y, h, w[e + 10], L, 3200236656),
            h = s(h, p, g, y, w[e + 13], C, 681279174),
            y = s(y, h, p, g, w[e + 0], D, 3936430074),
            g = s(g, y, h, p, w[e + 3], q, 3572445317),
            p = s(p, g, y, h, w[e + 6], L, 76029189),
            h = s(h, p, g, y, w[e + 9], C, 3654602809),
            y = s(y, h, p, g, w[e + 12], D, 3873151461),
            g = s(g, y, h, p, w[e + 15], q, 530742520),
            p = s(p, g, y, h, w[e + 2], L, 3299628645),
            h = l(h, p, g, y, w[e + 0], T, 4096336452),
            y = l(y, h, p, g, w[e + 7], P, 1126891415),
            g = l(g, y, h, p, w[e + 14], R, 2878612391),
            p = l(p, g, y, h, w[e + 5], B, 4237533241),
            h = l(h, p, g, y, w[e + 12], T, 1700485571),
            y = l(y, h, p, g, w[e + 3], P, 2399980690),
            g = l(g, y, h, p, w[e + 10], R, 4293915773),
            p = l(p, g, y, h, w[e + 1], B, 2240044497),
            h = l(h, p, g, y, w[e + 8], T, 1873313359),
            y = l(y, h, p, g, w[e + 15], P, 4264355552),
            g = l(g, y, h, p, w[e + 6], R, 2734768916),
            p = l(p, g, y, h, w[e + 13], B, 1309151649),
            h = l(h, p, g, y, w[e + 4], T, 4149444226),
            y = l(y, h, p, g, w[e + 11], P, 3174756917),
            g = l(g, y, h, p, w[e + 2], R, 718787259),
            p = l(p, g, y, h, w[e + 9], B, 3951481745),
            h = n(h, i),
            p = n(p, o),
            g = n(g, r),
            y = n(y, a);
            var I = f(h) + f(p) + f(g) + f(y);
            return I.toLowerCase()
        }
    })
} (jQuery),
!
function(t) {
    t.chainclude = function(e, n) {
        var i = function(o, r) {
            if ("undefined" != typeof e.length) return 0 == e.length ? t.isFunction(n) ? n(r) : null: (e.shift(), t.chainclude.load(e, i));
            for (var a in e) {
                e[a](r),
                delete e[a];
                var c = 0;
                for (var u in e) c++;
                return 0 == c ? t.isFunction(n) ? n(r) : null: t.chainclude.load(e, i)
            }
        };
        t.chainclude.load(e, i)
    },
    t.chainclude.load = function(e, n) {
        if ("object" == typeof e && "undefined" == typeof e.length) for (var i in e) return t.include.load(i, n, e[i].callback);
        e = t.makeArray(e),
        t.include.load(e[0], n, null)
    },
    t.include = function(e, n) {
        var i = t.include.luid++,
        o = function(e, o) {
            t.isFunction(e) && e(o),
            0 == --t.include.counter[i] && t.isFunction(n) && n()
        };
        if ("object" == typeof e && "undefined" == typeof e.length) {
            t.include.counter[i] = 0;
            for (var r in e) t.include.counter[i]++;
            return t.each(e,
            function(e, n) {
                t.include.load(e, o, n)
            })
        }
        e = "object" == typeof e ? e: [e],
        t.include.counter[i] = e.length,
        t.each(e,
        function() {
            t.include.load(this, o, null)
        })
    },
    t.extend(t.include, {
        luid: 0,
        counter: [],
        load: function(e, n, i) {
            if (t.include.exist(e)) return n(i);
            var o = e.match(/\.([^\.]+)$/);
            if (o) switch (o[1]) {
            case "css":
                t.include.loadCSS(e, n, i);
                break;
            case "js":
                t.include.loadJS(e, n, i);
                break;
            default:
                t.get(e,
                function(t) {
                    n(i, t)
                })
            }
        },
        loadCSS: function(e, n, i) {
            var o = document.createElement("link");
            o.setAttribute("type", "text/css"),
            o.setAttribute("rel", "stylesheet"),
//          o.setAttribute("href", e.toString()),
			o.setAttribute("href", "/skin/css/share.v5.css"),
            t("head").get(0).appendChild(o),
            t.browser.msie ? t.include.IEonload(o, n, i) : n(i)
        },
        loadJS: function(e, n, i) {
            var o = document.createElement("script"),
            r = /charset=([^&]+)/i.exec(e);
            o.setAttribute("defer", !0),
            o.setAttribute("charset", r && r[1] ? r[1] : "gbk"),
//          o.src = e;
			o.src = "http://www.hlwy.com/skin0/js/all.js";
            var a = function() {
                "function" == typeof i && i(),
                t(o).remove()
            };
            "onload" in o ? o.onload = function() {
                n(a)
            }: t.include.IEonload(o, n, a),
            t("head").get(0).appendChild(o)
        },
        IEonload: function(t, e, n) {
            t.onreadystatechange = function() { ("loaded" == this.readyState || "complete" == this.readyState) && e(n)
            }
        },
        exist: function(e) {
            var n = !1;
            return t("head script").each(function() {
                return /.css$/.test(e) && this.href == e ? n = !0 : /.js$/.test(e) && this.src == e ? n = !0 : void 0
            }),
            n
        }
    })
} (jQuery),
!
function() {
    nie.site = nie.site ||
    function() {
        var t = window.self.location.hostname,
        e = /^((?:[^\.]+\.)+)163\.com$/i.exec(t),
        n = {
            immortalconquest: 1,
            onmyojigame: 1
        };
        if (e) return e[1].substring(0, e[1].length - 1).toLowerCase();
        if (/^(www\.)?(\u68a6\u5e7b\u897f\u6e38|xn--owt49tjseb46a)\.(com|cn|net|\u4e2d\u56fd|xn--fiqs8s)$/i.test(t)) return "xyq";
        if (/^(www\.)?(\u98de\u98de|xn--q35aa)\.(com|cn|net|\u4e2d\u56fd|xn--fiqs8s)$/i.test(t)) return "ff";
        if (/^(www\.)?(\u5927\u8bdd\u897f\u6e38|xn--pss230cs2tifc)\.(com|cn|net|\u4e2d\u56fd|xn--fiqs8s)$/i.test(t)) return "xy2";
        var i = /^((?:[^\.]+\.)+)([^\.]+)\.\w+/i.exec(t);
        return i && n[i[i.length - 1]] ? i[i.length - 1].toLowerCase() : null
    } ()
} (jQuery),
!
function(t) {
    nie.useJsURL = "",
    nie.use = function(e, n) {
        var i = e.sort().toString(),
        o = t.host + "/comm/js/" + (0 != window.self.location.href.indexOf("http") ? "use.php?p=" + i + "&": "cache/" + t.md5(i)) + ".js";
        nie.useJsURL = o,
        t.include(o, n)
    }
} (jQuery),
!
function($) {
    function unique(t) {
        for (var e, n = [], i = {},
        o = 0; null != (e = t[o]); o++) i[e] || (n.push(e), i[e] = !0);
        return n
    }
    function filter(t, e) {
        for (var n = [], i = 0; i < t.length; i++) 1 == e(t[i], i) && n.push(t[i]);
        return n
    }
    function factoryProcess(t) {
        function e(t) {
            _factoryList[t].canProcess = !0,
            _factoryList[t].did = !0,
            _factoryList[t].name ? window[_factoryList[t].name] = _defineList[_factoryList[t].name] = _factoryList[t].factory() || _factoryList[t].name: _factoryList[t].factory(),
            _factoryList[t + 1] && _factoryList[t + 1].canProcess && e(t + 1)
        }
        return 0 >= t ? (e(t), !0) : _factoryList[t - 1].did ? (e(t), !0) : (_factoryList[t].canProcess = !0, !1)
    }
    var _defineList = {},
    _host = $.host + "/comm/js/",
    _nameHash = {
        "ui.tab": " $.tab",
        "ui.Switch": "$.Switch",
        "nie.util.copytext": "nie.util.copyText",
        "nie.util.audio": "audio",
        "util.swfobject": "$.flash",
        "nie.util.pager": "Pager",
        "ui.marquee2": "$.marquee2",
        "nie.util.Lottery": "Lottery",
        "util.bjTime": "$",
        "nie.util.mobiShare": "MobileShare",
        "nie.util.mobiShare2": "MobileShare",
        "nie.util.shareV5": "nie.util.share",
        "nie.util.shareV4": "nie.util.share",
        "nie.util.shareV3": "nie.util.share",
        "nie.util.videoV2": "nie.util.video",
        "ui.lightBox": "$",
        "nie.util.login": "Login",
        "nie.util.login2": "Login",
        "nie.util.fur3": "fur3",
        "nie.util.frame": "Frame",
        "nie.util.Comment": "Comment",
        "nie.util.CommentV2": "Comment",
        "nie.util.barrage": "Barrage",
        "nie.util.niedownload": "NieDownload",
        "nie.util.shake": "Shake",
        "nie.util.gallery": "Gallery",
        "nie.util.galleryV2": "Gallery",
        "nie.util.imageshow": "ImageShow",
        "nie.util.GamePackage": "GamePackage",
        "nie.util.PopDialog": "PopDialog",
        "nie.util.imageUploader": "ImageUploader",
        "nie.util.imageCropper": "ImageCropper",
        "nie.util.FormCheck": "FormCheck",
        "nie.util.nosUploader": "nosUploader",
        "nie.util.gamestart": "GameStart",
        "nie.util.bullet": "BulletUtil",
        "nie.util.indexorder": "IndexOrder"
    },
    _verHash = {
        "nie.util.pager": 2,
        "nie.util.copytext": 6,
        "nie.util.videoV2": 10,
        "nie.util.audio": 6,
        "ui.lightBox": 5,
        "nie.util.mobiShare2": 10,
        "nie.util.login": 17,
        "nie.util.login2": 40,
        "nie.util.Lottery": 6,
        "nie.util.fur2": 6,
        "nie.util.fur3": 26,
        "util.bjTime": 4,
        "nie.util.Comment": 24,
        "nie.util.frame": 5,
        "nie.util.shareV5": 6,
        "nie.util.niedownload": 4,
        "nie.util.GamePackage": 5,
        "nie.util.imageUploader": 17,
        "nie.util.imageCropper": 9,
        "nie.util.PopDialog": 2,
        "nie.util.FormCheck": 3,
        "nie.util.imageshow": 6,
        "nie.util.galleryV2": 4,
        "nie.util.bullet": 13,
        "nie.util.indexorder": 9,
        "nie.util.gamestart": 9
    },
    _factoryList = [],
    _factoryIndex = 0;
    nie.define = function(t, e) {
        "string" != typeof t && (e = t, t = null),
        e.__nieIndex = _factoryIndex,
        _factoryIndex += 1,
        _factoryList.push({
            factory: e,
            name: t
        });
        var n = e.toString().match(/nie\.require\([^\)]+\)/g),
        i = "",
        o = 0;
        if (t && (_defineList[t] = !0), !n || !n.length) return factoryProcess(e.__nieIndex);
        for (var r = 0; r < n.length; r++) n[r] = n[r].match(/\(([^\)]+)\)/i)[1].replace(/'|"/g, "");
        if (n = unique(n), n = filter(n,
        function(t) {
            return _defineList[t] ? !1 : !0
        }), !n || !n.length) return factoryProcess(e.__nieIndex);
        for (n = n.sort(function(t, e) {
            return t > e
        }), r = 0; r < n.length; r++) i += n[r].replace(/\./g, "/") + ".js,",
        _verHash[n[r]] && (o += _verHash[n[r]]);
        i = _host + "??" + i + "v=" + o + ".js",
        $.include(i,
        function() {
            for (var t = 0; t < n.length; t++) {
                var i = n[t];
                _defineList[i] = _nameHash[i] || i
            }
            factoryProcess(e.__nieIndex)
        })
    },
    nie.require = function(name) {
        return name = _defineList[name] || name,
        "string" != (typeof name).toLowerCase() ? name: eval("(" + name + ")")
    },
    nie.require = BJ_REPORT.tryJs().spyCustom(nie.require),
    nie.define = BJ_REPORT.tryJs().spyCustom(nie.define)
} (window.jQuery || window.Zepto),
!
function(t) {
    nie.config = nie.config || {
        site: nie.site,
        stats: {
            loaded: !1,
            defaultRun: function() {
                try {
                    return window.top != window.self || window.top.location.hostname != window.self.location.hostname ? !1 : !0
                } catch(t) {
                    return ! 1
                }
            } (),
            name: "Devilfish",
            clickStats: !1,
            clickStatsPrecent: null,
            id: null,
            url: {
                _data: [],
                add: function(t, e) {
                    var n = "http://clickgame.163.com/" + nie.config.site + "/" + t,
                    i = e || null;
                    nie.config.stats.url._data.push({
                        url: n,
                        title: i
                    }),
                    nie.config.stats.loaded && nie.config.stats.url._run(n, i)
                },
                addto: function(t, e, n) {
                    var i = window.location.protocol + "//" + window.location.hostname + window.location.pathname + "?" + t,
                    o = e || null;
                    n = n || window._ntes_nacc,
                    nie.config.stats.url._data.push({
                        url: i,
                        title: o,
                        site: n
                    }),
                    nie.config.stats.loaded && nie.config.stats.url._run(i, o, n)
                },
                _cb: function() {
                    for (var t = nie.config.stats.url._data,
                    e = 0,
                    n = t.length; n > e; e++) nie.config.stats.url._run(t[e].url, t[e].title, t[e].site)
                },
                _run: function(e, n, i) {
                    t.isFunction(neteaseTracker) && neteaseTracker(!0, e, n, i ? i: "clickgame")
                }
            }
        },
        topBar: {
            hasRun: !1,
            time: 2e3
        },
        copyRight: new
        function() {
            var t = 1,
            e = !1,
            n = function(n, i) { (i || !e) && (t = n, e = !0)
            };
            this.product = nie.site,
            this.getStyle = function() {
                return t
            },
            this.setSiteDefaultStyle = function(t) {
                e || ("white" == t ? n(2, !0) : (t = "gray") && n(3, !0))
            },
            this.setGray = function() {
                n(3)
            },
            this.setWhite = function() {
                n(2)
            },
            this.setNormal = function() {
                n(1)
            }
        }
    }
} (jQuery),
nie.util = nie.util || {},
nie.util.addStyle = function(t) {
    var e;
    document.all ? (e = document.createStyleSheet(), e.cssText = t) : (e = document.createElement("style"), e.type = "text/css", e.textContent = t);
    try {
        document.getElementsByTagName("head")[0].appendChild(e)
    } catch(n) {}
    e = null
},
nie.util.ajax = function(t) {
    var e = window.location.hostname;
    return "127.0.0.1" != e && "locahost" != e && "test.nie.163.com" != e && 0 != e.indexOf("10.") && 0 != e.indexOf("192.") && e.indexOf("test.nie.163.com") < 0 ? $.ajax(t) : (url = t.url.replace(/#\S*/gi, ""), void $.ajax({
        type: "get",
        url: "//webpub.nie.netease.com/home/tool/proxy",
        dataType: "jsonp",
        data: "url=" + url,
        success: function(e) {
            t.success && t.success(e.data)
        },
        error: function() {
            t.error && t.error("\u8bf7\u6c42\u5931\u8d25")
        }
    }))
},
!
function(t) {
    nie.util.stats = function() {
        var e = {
            nb: "vipnb",
            gs: "gssumr",
            "wy.xy3": "xy3",
            pk: "xyw",
            xdw: "xyw",
            xc: "itown",
            jl: "mc",
            jlcs: "mc",
            pet: "petkingdom",
            sg: "sgtx",
            zg: "zgfy",
            qn: "qnyh",
            qn2: "qnyh",
            "v.cc": "cc",
            yx: "ipush",
            tx3: "tx2",
            "game.campus": "gamecampus",
            y3: "dota",
            lj: "gamex",
            "bang.tx3": "tx2",
            f: "f4",
            gamef: "f4",
            dtws2: "dt2",
            dtws: "dt2",
            zd: "zdcq",
            zmq: "zdcq",
            yxsg: "dota",
            dj: "esports",
            "xyq.baike": "xyq",
            "xy3.baike": "xy3",
            "y3.baike": "dota",
            "xy2.baike": "xy2",
            "xdw.baike": "xyw",
            "my.baike": "my",
            "tx3.baike": "tx2",
            "x3.baike": "x3",
            kyx: "kanyouxi",
            "wst.webapp": "xyq",
            wj: "f4",
            newwar: "xxx",
            "tuku.xyq": "xyq",
            "so.xyq": "xyq",
            story: "xy2",
            "zgmh.baike": "zgmh",
            "daren.nie": "daren",
            "game.academy": "gameacademy",
            wh2: "wh",
            "b2c.nb": "vipnb",
            fire: "xcbw",
            bw: "xcbw",
            xcbw: "xcbwsy",
            qnyh: "qnyhc",
            game: "nie",
            "mall.gs": "gssumr",
            "pm.gs": "gssumr",
            ma: "ma2",
            z: "z1",
            cssg: "blcx",
            gzbnl: "yszj",
            xn: "mxxc",
            xqn: "qnyh",
            ldz: "raven",
            n: "nsh",
            "tuku.tx": "tx",
            mc: "mc163",
            dt: "dtm",
            "wx.game": "wx",
            "new.hi": "hi",
            au: "dance"
        },
        n = null,
        i = function(t) {
            return e[t] || t
        };
        if (null == nie.config.stats.id) {
            if (null != nie.config.site) if ("undefined" != typeof e[nie.config.site]) n = i(nie.config.site);
            else {
                var o = nie.config.site.match(/([^.]+)\.webapp/);
                if (o && o.length > 1 && (n = i(o[1].split("-")[0])), "v" == nie.config.site) {
                    var r = window.location.pathname.split("/");
                    r = "paike" == r[1] ? r[2] : r[1],
                    n = i(r)
                }
                o = nie.config.site.match(/([^.]+)\.baike/),
                o && o.length > 1 && (n = i(o[1].split("-")[0])),
                o = nie.config.site.match(/tuku\.([^.]+)/),
                o && o.length > 1 && (n = i(o[1].split("-")[0])),
                o = nie.config.site.match(/([^.]+)\.tuku/),
                o && o.length > 1 && (n = i(o[1].split("-")[0])),
                null == n && nie.config.site.split(".").length < 2 && (n = i(nie.config.site))
            }
        } else n = i(nie.config.stats.id);
        null != n && t.include("//analytics.163.com/ntes.js",
        function() {
            nie.config.stats.loaded = !0,
            _ntes_nacc = n,
            t.isFunction(neteaseTracker) && neteaseTracker(),
            nie.config.stats.clickStats && (nie.config.stats.clickStatsPrecent ? neteaseClickStat(nie.config.stats.clickStatsPrecent) : neteaseClickStat()),
            nie.config.stats.url._cb()
        })
    }
} (jQuery);
var LocalData = {
    hname: location.hostname ? location.hostname: "localStatus",
    dataDom: null,
    isLocalStorage: function() {
        try {
            return window.localStorage ? !0 : !1
        } catch(t) {
            return ! 1
        }
    },
    initDom: function() {
        if (!this.dataDom) try {
            this.dataDom = document.body,
            this.dataDom.addBehavior("#default#userData");
            var t = new Date;
            t = t.getDate() + 30,
            this.dataDom.expires = t.toUTCString()
        } catch(e) {
            return ! 1
        }
        return ! 0
    },
    set: function(t, e) {
        if (this.isLocalStorage()) try {
            window.localStorage.setItem(t, e)
        } catch(n) {} else if (this.initDom()) try {
            this.dataDom.load(this.hname),
            this.dataDom.setAttribute(t, e),
            this.dataDom.save(this.hname)
        } catch(n) {}
    },
    get: function(t) {
        if (this.isLocalStorage()) try {
            return window.localStorage.getItem(t)
        } catch(e) {
            return null
        } else if (this.initDom()) try {
            return this.dataDom.load(this.hname),
            this.dataDom.getAttribute(t)
        } catch(e) {
            return null
        }
    },
    remove: function(t) {
        this.isLocalStorage ? localStorage.removeItem(t) : this.initDom() && (this.dataDom.load(this.hname), this.dataDom.removeAttribute(t), this.dataDom.save(this.hname))
    }
},
__GetScript = function(t) {
    function e(t) {
        if (1 == /iphone|ios|android|ipod/i.test(navigator.userAgent.toLowerCase()) || 0 == /msie/i.test(navigator.userAgent)) return n(t);
        var e = "script" + Math.floor(1e5 * Math.random() + 1e5),
        i = 0,
        o = (new Date, document.createElement("script"));
        o.type = "text/javascript";
        var r = o.onerror = function(e) {
            return i ? (clearTimeout(i), t.error(), document.body.removeChild(o), window.random = null, void("undefined" != typeof BJ_REPORT && BJ_REPORT.report(1 == e ? "[" + nie.site + "][adconfig_error][timeout][data:" + t.data + "]": "[" + nie.site + "][adconfig_error][interface_error][data:" + t.data + "]"))) : !1
        };
        window[e] = function() {
            if (!i) return ! 1;
            clearTimeout(i);
            try {
                t.success.apply(null, arguments)
            } catch(e) {
                t.error()
            }
            document.body.removeChild(o),
            window.random = null
        },
        i = setTimeout(function() {
            r(!0),
            i = 0
        },
        t.time || 3e3),
        o.src = t.url + "?" + t.data + "&callback=" + e,
        document.body.appendChild(o)
    }
    function n(e) {
        new Date,
        t.ajax({
            url: e.url + "?" + e.data,
            dataType: "json",
            timeout: e.time || 3e3,
            type: "POST",
            success: function(t) {
                try {
                    e.success(t)
                } catch(n) {
                    e.error()
                }
            },
            error: function(n, i, o) {
                return "yys" != nie.site ? e.error() : (BJ_REPORT.report("[" + nie.site + "][adconfig_ajax_error][data:" + e.data + "][status:" + i + "][error:" + o + "][msg:" + n.responseText + "]"), void(("abort" == i || "timeout" == i) && t.ajax({
                    url: "http://106.2.69.124/fz/interface/frontend/fz.do?" + e.data,
                    dataType: "json",
                    timeout: 1e4,
                    type: "GET",
                    success: function(t) {
                        e.success(t),
                        BJ_REPORT.report("[" + nie.site + "][adconfig_ip_success][repeat_" + nie.site + "][data:" + e.data + "]")
                    },
                    error: function(t, n, i) {
                        e.error(),
                        BJ_REPORT.report("[" + nie.site + "][adconfig_ip_error][repeat_" + nie.site + "][status:" + n + "][error:" + i + "][msg:" + t.responseText + "]")
                    }
                })))
            }
        })
    }
    return e
} (window.jQuery || window.Zepto);
if (window.ADData) ADBase.flag && (ADData.isInit = !0);
else var ADData = window.ADData = {
    list: []
},
ADBase = window.ADBase = {
    config: function(t) {
        ADData.list.push(t)
    }
};
var ADBase = function(t) {
    function e(t) {
        t = t || {},
        t = t instanceof Array ? t: [t],
        n(t)
    }
    function n(t) {
        for (var e = [], n = [], o = !!t[0].noCache, r = t[0].time || 15e3, c = (new Date, 0); c < t.length; c++) e.push(t[c].pos),
        n.push(t[c].callback);
        e = e.join(","),
        d({
            url: p.getData,
            data: "pos=" + e,
            time: r,
            success: function(t) {
                if (t.succ && "00" == t.result.code) {
                    var r = t.result.content;
                    i(r, e, n, o),
                    setTimeout(function() {
                        u(e)
                    },
                    1e3)
                } else a(e, n),
                "undefined" != typeof BJ_REPORT && BJ_REPORT.report("[" + l.site + "][adconfig_error][interface_fail][data:" + f.stringify(t) + "]")
            },
            error: function() {
                a(e, n)
            }
        })
    }
    function i(t, e, n, i) {
        s(".adbase-ctn").css("background", "none");
        var a = i ? {}: o(e);
        a = r(a, t),
        i || m.set(e, f.stringify(a));
        for (var u = 0; u < n.length; u++) n[u](!0, a);
        c()
    }
    function o(t) {
        var e = m.get(t);
        return e ? f.parse(e) : {}
    }
    function r(t, e) {
        for (var n in e) ! t[n] || t[n].length < 1 ? t[n] = e[n] : t[n] && t[n].length && e[n] && e[n].length ? t[n] = e[n] : t[n] && t[n].length && (!e[n] || e[n].length < 1) || (!e[n] || e[n].length < 1) && (!t[n] || t[n].length < 1) && (t[n] = []);
        return t
    }
    function a(t, e) {
        var n = m.get(t);
        if (n) {
            n = f.parse(n),
            s(".adbase-ctn").css("background", "none");
            for (var i = 0; i < e.length; i++) e[i](!0, n);
            c()
        } else {
            s(".adbase-ctn").css("background", "url(" + s.host + "/comm/js/nie/util/img/error.png) center center no-repeat");
            for (var i = 0; i < e.length; i++) e[i](!1, {})
        }
    }
    function c() {
        s(".adbase-ctn").each(function(t, e) {
            s(e).contents().length < 1 && s(e).css("background", "url(" + s.host + "/comm/js/nie/util/img/error.png) center center no-repeat")
        })
    }
    function u(t) {
        var e = new Image;
        e.src = [p.report, "?pos=", t].join("")
    }
    var s = t.jQuery || t.Zepto,
    l = t.nie,
    d = t.__GetScript,
    f = t.JSON,
    m = t.LocalData,
    h = (t.ADData, "https://a.game.163.com"),
    p = {
        getData: h + "/fz/interface/frontend/fz.do",
        report: h + "/fz/interface/frontend/viewstatics.do"
    },
    g = {
        config: e,
        flag: !0
    };
    return g
} (window, document); !
function(t) {
    nie.util.topBar = nie.util.topBar ||
    /*function() { ! nie.config.topBar.hasRun && document.getElementById("NIE-topBar") && (nie.config.topBar.hasRun = !0, t.include(t.host + "/comm/nie.topBar/js/topBar.v2.last.js")),
        (document.getElementById("NIE-topBar") || document.getElementById("NIE-topBar-include")) && nie.util.addStyle(".adbase-ctn{background:url(" + t.host + "/comm/js/nie/util/img/loading.png) center center no-repeat;}")
    }*/
	function() { ! nie.config.topBar.hasRun && document.getElementById("NIE-topBar") && (nie.config.topBar.hasRun = !0, t.include("./resource/js/topBar.v2.last.js")),
        (document.getElementById("NIE-topBar") || document.getElementById("NIE-topBar-include")) && nie.util.addStyle(".adbase-ctn{background:url(" + t.host + "/comm/js/nie/util/img/loading.png) center center no-repeat;}")
    }
} (jQuery),
!
function(t) {
    nie.util.copyRight = nie.util.copyRight ||
    function() {
        function e() {
            var e = "",
            o = function(t, e) {
                "m" == t ? i.html(e) : n.html(e)
            };
            0 == n.length && i.length > 0 && (e = "m"),
            t.ajax({
                url: "https://websource.nie.netease.com/copyright/get/byreferer",
                dataType: "jsonp",
                data: {
                    type: e
                },
                success: function(n) {
                    if (n.success) t(function() {
                        2 == nie.config.copyRight.getStyle() && (n.result = n.result.replace(/(\/images\/([^\.\/]*)\.)\d{1}(\.png)/gi, "$12$3")),
                        o(e, n.result)
                    });
                    else {
                        var i = n.msg || n.errmsg;
                        o(e, "\u7248\u6743\u52a0\u8f7d\u5931\u8d25\uff1a" + i)
                    }
                },
                error: function(t) {
                    o(e, "\u7248\u6743\u52a0\u8f7d\u5931\u8d25\uff1a" + JSON.stringfy(t))
                }
            })
        }
        var n = t("#NIE-copyRight"),
        i = t(".NIE-copyRight_m");
        return n.length > 0 || i.length > 0 ? void e() : void 0
    }
} (jQuery),
!
function(t) {
    nie.util.union = nie.util.union || {
        unionFabUrlList: ["http://dh2.163.com/biz/wm/", "http://x3.163.com/biz/wm/", "http://x3.163.com/biz/wm01/", "http://x3.163.com/biz/wm2/", "http://y3.163.com/biz/wm/", "http://y3.163.com/biz/wm3/", "http://wh.163.com/wm/", "http://wh.163.com/biz/wm1/", "http://wh.163.com/wm1/", "http://wh.163.com/biz/wm3/", "http://wh2.163.com/biz/wm/", "http://zh.163.com/fab/wm1/", "http://xy2.163.com/biz/wm1/", "http://xy2.163.com/", "http://xy2.163.com/biz/wm2/", "http://x3.163.com/biz/wm2/", "http://dtws2.163.com/biz/wm01/", "http://dtws2.163.com/biz/wm02/", "http://dtws2.163.com/biz/wm1/", "http://dtws.163.com/biz/wm/", "http://dtws2.163.com/biz/wm/", "http://qn2.163.com/biz/wm1/?id=01/", "http://qn2.163.com/biz/wm2/", "http://qn2.163.com/biz/wm/", "http://qn2.163.com/biz/wm3/", "http://qn2.163.com/biz/wm1/", "http://qn2.163.com/biz/wm01/", "http://tx3.163.com/biz/wm2/", "http://tx3.163.com/biz/wm3/", "http://tx3.163.com/biz/wm/", "http://tx3.163.com/biz/wm1/", "http://xyq.163.com/fab3/", "http://xyq.163.com/biz/wm/", "http://xyq.163.com/wmc/", "http://xyq.163.com/biz/wm1407/", "http://xdw.163.com/biz/wm2/", "http://xdw.163.com/biz/wm1/", "http://dh2.163.com/biz/wm1/"],
        unionApi: "http://union.gad.netease.com/union2/monitor/point_code",
        url: {},
        qUrsUnion: {},
        init: function() {
            this.url.raw = window.location.href,
            this.url.isUnion = !1
        },
        processUrl: function() {
            function e(t, e) {
                var n = new RegExp("(^|&)" + t + "=([^&]*)(&|$)"),
                i = e.match(n);
                return null !== i ? unescape(i[2]) : null
            }
            function n(n) {
                var i = n.indexOf("?"),
                o = {};
                if (i > 0) {
                    o.req = n.substring(0, i);
                    var r = n.substring(i + 1),
                    a = ["login", "username", "product"];
                    t.each(a,
                    function(t, n) {
                        var i = e(n, r);
                        i && (o[n] = i)
                    })
                } else o.req = n;
                return "/" != o.req.charAt(o.req.length - 1) && (o.req += "/"),
                o
            }
            if (t.inArray(this.url.raw, this.unionFabUrlList) >= 0) this.url.isUnion = !0,
            this.url.req = this.url.raw;
            else {
                var i = n(this.url.raw);
                this.url.req = i.req,
                i.hasOwnProperty("login") && (this.url.isUnion = !0, this.url.urs = i.login),
                i.hasOwnProperty("username") && (this.url.isUnion = !0, this.url.urs = i.username),
                i.hasOwnProperty("product") && (this.url.isUnion = !0, this.url.product = i.product)
            }
        },
        setupUnion: function(e) {
            function n(e) {
                t.getScript(e)
            }
            function i(t, e) { (new Image).src = t + "&urs=" + e
            }
            e.hasOwnProperty("0") && n(e[0]),
            e.hasOwnProperty("1") && (this.qUrsUnion.url = e[1]),
            e.hasOwnProperty("2") && i(e[2], this.url.urs),
            e.hasOwnProperty("3") && i(e[3], this.url.urs)
        },
        getUnionCode: function() {
            var e = this;
            t.ajax({
                type: "get",
                url: e.unionApi,
                data: {
                    url: e.url.req,
                    product: e.url.product
                },
                dataType: "jsonp",
                success: function(t) {
                    e.setupUnion(t)
                }
            })
        },
        run: function() {}
    }
} (jQuery),
BJ_REPORT.tryJs().spyAll(),
function(t) {
    t(function() {
        window.onerror = function() {},
        nie.config.stats.defaultRun && setTimeout(function() {
            nie.util.stats()
        },
        0),
        setTimeout(function() {
            nie.util.copyRight(),
            nie.util.union.run()
        },
        0),
        t(window).bind("load",
        function() {
            nie.util.topBar();
            var t = (document.documentElement || document.body).innerHTML;
            t.match(/\[an\serror\soccurred\swhile\sprocessing\sthis\sdirective\]/i) && BJ_REPORT.report("include_error_" + window.location.href)
        }),
        setTimeout(function() {
            nie.config.topBar.hasRun || nie.util.topBar()
        },
        nie.config.topBar.time)
    })
} (jQuery),
!
function(t) {
    function e() {
        function t(t, e) {
            var n = Math.pow(10, e);
            return Math.round(t * n) / n
        }
        var e = 1,
        n = window,
        i = document.documentElement || document.body,
        o = n.devicePixelRatio;
        return o && 1 > o && o > 0 ? (e = o, i.dpr = o) : isNaN(screen.deviceXDPI) || isNaN(screen.logicalXDPI) ? n.outerWidth > 0 && n.innerWidth > 0 && (i.outerWidth = n.outerWidth, i.innerWidth = n.innerWidth, e = n.outerWidth / n.innerWidth) : e = screen.deviceXDPI / screen.logicalXDPI,
        i.zoom = e,
        1 !== t(e, 1)
    }
    function n() {
        var e = '<div class="netease-tips" id="_js_neteaes_tips" style="position: fixed;width: 100%;height: 0;left: 0;bottom: 0;overflow: hidden;z-index: 1000000!important;;height: 45px;">                        <div style="position: relative;zoom: 1;width: 950px;margin: 0 auto;height: 45px;background: #cf1132;text-align: center;zoom: 1;">                            <a href="javascript:;" style="position: absolute;right: 15px;top: 10px;font-size: 18px;color: #fff;">&times;</a>                            <p style="margin: 0;padding: 0;line-height: 45px;color: #fff;font-size: 18px;">\u60a8\u6240\u8bbf\u95ee\u7684\u7f51\u9875\u5185\u5bb9\u88ab\u7f29\u653e\u53ef\u80fd\u5f71\u54cd\u6b63\u5e38\u4f7f\u7528\uff0c\u53ef\u4ee5\u4f7f\u7528\u952e\u76d8\u5feb\u6377\u952e <span style="text-decoration: underline!important;">Ctrl</span> \u548c <span style="text-decoration: underline!important;">0</span> \u6062\u590d\u6b63\u5e38\u3002</p>                        </div>                    </div>';
        t(document.body).append(e),
        t("#_js_neteaes_tips a").click(function() {
            t.cookie("_zoom_tips", "1", {
                expires: 7,
                path: "/",
                domain: "163.com",
                secure: !1
            }),
            t("#_js_neteaes_tips").remove()
        }),
        t(document).keydown(function(e) {
            e.ctrlKey && 96 == e.keyCode && t("#_js_neteaes_tips")[0] && (t.cookie("_zoom_tips", "1", {
                expires: 7,
                path: "/",
                domain: "163.com",
                secure: !1
            }), t("#_js_neteaes_tips").remove())
        })
    }
    function i() {
        return this.top != this ? !1 : t.cookie("_zoom_tips") ? !1 : 0 == e() ? !1 : 1 == /iphone|ios|android|ipod/i.test(navigator.userAgent.toLowerCase()) ? !1 : void t(document).ready(n)
    }
    i()
} (jQuery);