function Swipe(t, n) {
    "use strict";
    function e() {
        w = x.children,
        p = w.length,
        w.length < 2 && (n.continuous = !1),
        h.transitions && n.continuous && w.length < 3,
        E = new Array(w.length),
        m = t.getBoundingClientRect().width || t.offsetWidth,
        x.style.width = w.length * m + "px";
        for (var e = w.length; e--;) {
            var i = w[e];
            i.style.width = m + "px",
            i.setAttribute("data-index", e),
            h.transitions && (i.style.left = e * -m + "px", a(e, b > e ? -m: e > b ? m: 0, 0))
        }
        n.continuous && h.transitions && (a(s(b - 1), -m, 0), a(s(b + 1), m, 0)),
        h.transitions || (x.style.left = b * -m + "px"),
        t.style.visibility = "visible"
    }
    function i() {
        n.continuous ? r(b - 1) : b && r(b - 1)
    }
    function o() {
        n.continuous ? r(b + 1) : b < w.length - 1 && r(b + 1)
    }
    function s(t) {
        return (w.length + t % w.length) % w.length
    }
    function r(t, e) {
        if (b != t) {
            if (h.transitions) {
                var i = Math.abs(b - t) / (b - t);
                if (n.continuous) {
                    var o = i;
                    i = -E[s(t)] / m,
                    i !== o && (t = -i * w.length + t)
                }
                for (var r = Math.abs(b - t) - 1; r--;) a(s((t > b ? t: b) - r - 1), m * i, 0);
                t = s(t),
                a(b, m * i, e || g),
                a(t, 0, e || g),
                n.continuous && a(s(t - i), -(m * i), 0)
            } else t = s(t),
            c(b * -m, t * -m, e || g);
            b = t,
            f(n.callback && n.callback(b, w[b]))
        }
    }
    function a(t, n, e) {
        u(t, n, e),
        E[t] = n
    }
    function u(t, n, e) {
        var i = w[t],
        o = i && i.style;
        o && (o.webkitTransitionDuration = o.MozTransitionDuration = o.msTransitionDuration = o.OTransitionDuration = o.transitionDuration = e + "ms", o.webkitTransform = "translate(" + n + "px,0)translateZ(0)", o.msTransform = o.MozTransform = o.OTransform = "translateX(" + n + "px)")
    }
    function c(t, e, i) {
        if (!i) return void(x.style.left = e + "px");
        var o = +new Date,
        s = setInterval(function() {
            var r = +new Date - o;
            return r > i ? (x.style.left = e + "px", L && d(), n.transitionEnd && n.transitionEnd.call(event, b, w[b]), void clearInterval(s)) : void(x.style.left = (e - t) * (Math.floor(r / i * 100) / 100) + t + "px")
        },
        4)
    }
    function d() {
        T = setTimeout(o, L)
    }
    function l() {
        L = 0,
        clearTimeout(T)
    }
    var v = function() {},
    f = function(t) {
        setTimeout(t || v, 0)
    },
    h = {
        addEventListener: !!window.addEventListener,
        touch: "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
        transitions: function(t) {
            var n = ["transitionProperty", "WebkitTransition", "MozTransition", "OTransition", "msTransition"];
            for (var e in n) if (void 0 !== t.style[n[e]]) return ! 0;
            return ! 1
        } (document.createElement("swipe"))
    };
    if (t) {
        var w, E, m, p, x = t.children[0];
        n = n || {};
        var b = parseInt(n.startSlide, 10) || 0,
        g = n.speed || 500;
        n.continuous = void 0 !== n.continuous ? n.continuous: !0;
        var T, y, L = n.auto || 0,
        k = {},
        D = {},
        M = {
            handleEvent: function(t) {
                switch (t.type) {
                case "touchstart":
                    this.start(t);
                    break;
                case "touchmove":
                    this.move(t);
                    break;
                case "touchend":
                    f(this.end(t));
                    break;
                case "webkitTransitionEnd":
                case "msTransitionEnd":
                case "oTransitionEnd":
                case "otransitionend":
                case "transitionend":
                    f(this.transitionEnd(t));
                    break;
                case "resize":
                    f(e)
                }
                n.stopPropagation && t.stopPropagation()
            },
            start: function(t) {
                var n = t.touches[0];
                k = {
                    x: n.pageX,
                    y: n.pageY,
                    time: +new Date
                },
                y = void 0,
                D = {},
                x.addEventListener("touchmove", this, !1),
                x.addEventListener("touchend", this, !1)
            },
            move: function(t) {
                if (! (t.touches.length > 1 || t.scale && 1 !== t.scale)) {
                    n.disableScroll && t.preventDefault();
                    var e = t.touches[0];
                    D = {
                        x: e.pageX - k.x,
                        y: e.pageY - k.y
                    },
                    "undefined" == typeof y && (y = !!(y || Math.abs(D.x) < Math.abs(D.y))),
                    y || (t.preventDefault(), l(), n.continuous ? (u(s(b - 1), D.x + E[s(b - 1)], 0), u(b, D.x + E[b], 0), u(s(b + 1), D.x + E[s(b + 1)], 0)) : (D.x = D.x / (!b && D.x > 0 || b == w.length - 1 && D.x < 0 ? Math.abs(D.x) / m + 1 : 1), u(b - 1, D.x + E[b - 1], 0), u(b, D.x + E[b], 0), u(b + 1, D.x + E[b + 1], 0)))
                }
            },
            end: function() {
                var t = +new Date - k.time,
                e = Number(t) < 250 && Math.abs(D.x) > 20 || Math.abs(D.x) > m / 2,
                i = !b && D.x > 0 || b == w.length - 1 && D.x < 0;
                n.continuous && (i = !1);
                var o = D.x < 0;
                y || (e && !i ? (o ? (n.continuous ? (a(s(b - 1), -m, 0), a(s(b + 2), m, 0)) : a(b - 1, -m, 0), a(b, E[b] - m, g), a(s(b + 1), E[s(b + 1)] - m, g), b = s(b + 1)) : (n.continuous ? (a(s(b + 1), m, 0), a(s(b - 2), -m, 0)) : a(b + 1, m, 0), a(b, E[b] + m, g), a(s(b - 1), E[s(b - 1)] + m, g), b = s(b - 1)), n.callback && n.callback(b, w[b])) : n.continuous ? (a(s(b - 1), -m, g), a(b, 0, g), a(s(b + 1), m, g)) : (a(b - 1, -m, g), a(b, 0, g), a(b + 1, m, g))),
                x.removeEventListener("touchmove", M, !1),
                x.removeEventListener("touchend", M, !1),
                L = n.auto
            },
            transitionEnd: function(t) {
                parseInt(t.target.getAttribute("data-index"), 10) == b && (L && d(), n.transitionEnd && n.transitionEnd.call(t, b, w[b]))
            }
        };
        return e(),
        L && d(),
        h.addEventListener ? (h.touch && x.addEventListener("touchstart", M, !1), h.transitions && (x.addEventListener("webkitTransitionEnd", M, !1), x.addEventListener("msTransitionEnd", M, !1), x.addEventListener("oTransitionEnd", M, !1), x.addEventListener("otransitionend", M, !1), x.addEventListener("transitionend", M, !1)), window.addEventListener("resize", M, !1)) : window.onresize = function() {
            e()
        },
        {
            setup: function() {
                e()
            },
            slide: function(t, n) {
                l(),
                r(t, n)
            },
            prev: function() {
                l(),
                i()
            },
            next: function() {
                l(),
                o()
            },
            stop: function() {
                l()
            },
            getPos: function() {
                return b
            },
            getNumSlides: function() {
                return p
            },
            kill: function() {
                l(),
                x.style.width = "",
                x.style.left = "";
                for (var t = w.length; t--;) {
                    var n = w[t];
                    n.style.width = "",
                    n.style.left = "",
                    h.transitions && u(t, 0, 0)
                }
                h.addEventListener ? (x.removeEventListener("touchstart", M, !1), x.removeEventListener("webkitTransitionEnd", M, !1), x.removeEventListener("msTransitionEnd", M, !1), x.removeEventListener("oTransitionEnd", M, !1), x.removeEventListener("otransitionend", M, !1), x.removeEventListener("transitionend", M, !1), window.removeEventListener("resize", M, !1)) : window.onresize = null
            }
        }
    }
} (window.jQuery || window.Zepto) && !
function(t) {
    t.fn.Swipe = function(n) {
        return this.each(function() {
            t(this).data("Swipe", new Swipe(t(this)[0], n))
        })
    }
} (window.jQuery || window.Zepto);;
var Index = function() {
    var e = function(ID) {
        i(ID, "3000", !1, !0, !0)
    },
    i = function(e, t, n, i, a) {
        var s = $(e),
        r = n || !1,
        o = i || !1,
        c = a || !1,
        d = s.find(".swipe-con").length,
        h = s.find(".left-arrow"),
        l = s.find(".right-arrow");
        if (! (1 > d)) {
            if (d = s.find(".swipe-con").length, d > 1 && o) for (var u = 0; d > u; u++) s.find(".swipe_nav").append('<span><a href="javascript:;"><i></i><i></i></a></span>');
            for (var v = s.find(".swipe-con").get(0).getElementsByTagName("img"), f = v.length, u = 0; f > u; u++) v[u].attributes["data-src"] && (v[u].src = v[u].attributes["data-src"].value);
            setTimeout(function() {
                s.find(".swipe_nav a").eq(0).addClass("current")
            },
            10);
            // setTimeout(function() {
            //     s.find(".swipe_nav a").eq(0).addClass("current-hover")
            // },
            // 2e3);
            var g = new Swipe(s.find(".swipe").get(0), {
                startSlide: 0,
                speed: 500,
                auto: t,
                continuous: c,
                disableScroll: !1,
                stopPropagation: !1,
                callback: function(e, t) {
                    for (var n = t.getElementsByTagName("img"), i = 0, a = n.length; a > i; i++) n[i].attributes["data-src"] && (n[i].src = n[i].attributes["data-src"].value);
                    s.find(".swipe_nav a").removeClass("current").eq(e).addClass("current"),
                    // setTimeout(function() {
                    //     s.find(".swipe_nav a").removeClass("current-hover").eq(e).addClass("current-hover")
                    // },
                    // 2e3),
                    r && (0 == e ? (h.hide(), l.show()) : e == d - 1 ? l.hide() : (h.show(), l.show()))
                }
            });
            h.click(function() {
                g.prev()
            }),
            l.click(function() {
                g.next()
            }),
            s.find(".swipe_nav a").each(function(e) {
                $(this).bind("click",
                function() {
                    g.slide(e)
                })
            })
        }
    };
    return {
        init: e
    }
} ();
// Index.init('#sliderA');