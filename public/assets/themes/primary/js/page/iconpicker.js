/*!
 * Font Awesome Icon Picker
 * https://farbelous.github.io/fontawesome-iconpicker/
 *
 * @author Javi Aguilar, itsjavi.com
 * @license MIT License
 * @see https://github.com/farbelous/fontawesome-iconpicker/blob/master/LICENSE
 */


(function(e) {
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], e);
    } else {
        e(jQuery);
    }
})(function(j) {
    j.ui = j.ui || {};
    var e = j.ui.version = "1.12.1";
    (function() {
        var r, y = Math.max, x = Math.abs, s = /left|center|right/, i = /top|center|bottom/, f = /[\+\-]\d+(\.[\d]+)?%?/, l = /^\w+/, c = /%$/, a = j.fn.pos;
        function q(e, a, t) {
            return [ parseFloat(e[0]) * (c.test(e[0]) ? a / 100 : 1), parseFloat(e[1]) * (c.test(e[1]) ? t / 100 : 1) ];
        }
        function C(e, a) {
            return parseInt(j.css(e, a), 10) || 0;
        }
        function t(e) {
            var a = e[0];
            if (a.nodeType === 9) {
                return {
                    width: e.width(),
                    height: e.height(),
                    offset: {
                        top: 0,
                        left: 0
                    }
                };
            }
            if (j.isWindow(a)) {
                return {
                    width: e.width(),
                    height: e.height(),
                    offset: {
                        top: e.scrollTop(),
                        left: e.scrollLeft()
                    }
                };
            }
            if (a.preventDefault) {
                return {
                    width: 0,
                    height: 0,
                    offset: {
                        top: a.pageY,
                        left: a.pageX
                    }
                };
            }
            return {
                width: e.outerWidth(),
                height: e.outerHeight(),
                offset: e.offset()
            };
        }
        j.pos = {
            scrollbarWidth: function() {
                if (r !== undefined) {
                    return r;
                }
                var e, a, t = j("<div " + "style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'>" + "<div style='height:100px;width:auto;'></div></div>"), s = t.children()[0];
                j("body").append(t);
                e = s.offsetWidth;
                t.css("overflow", "scroll");
                a = s.offsetWidth;
                if (e === a) {
                    a = t[0].clientWidth;
                }
                t.remove();
                return r = e - a;
            },
            getScrollInfo: function(e) {
                var a = e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"), t = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y"), s = a === "scroll" || a === "auto" && e.width < e.element[0].scrollWidth, r = t === "scroll" || t === "auto" && e.height < e.element[0].scrollHeight;
                return {
                    width: r ? j.pos.scrollbarWidth() : 0,
                    height: s ? j.pos.scrollbarWidth() : 0
                };
            },
            getWithinInfo: function(e) {
                var a = j(e || window), t = j.isWindow(a[0]), s = !!a[0] && a[0].nodeType === 9, r = !t && !s;
                return {
                    element: a,
                    isWindow: t,
                    isDocument: s,
                    offset: r ? j(e).offset() : {
                        left: 0,
                        top: 0
                    },
                    scrollLeft: a.scrollLeft(),
                    scrollTop: a.scrollTop(),
                    width: a.outerWidth(),
                    height: a.outerHeight()
                };
            }
        };
        j.fn.pos = function(h) {
            if (!h || !h.of) {
                return a.apply(this, arguments);
            }
            h = j.extend({}, h);
            var m, p, d, u, T, e, g = j(h.of), b = j.pos.getWithinInfo(h.within), k = j.pos.getScrollInfo(b), w = (h.collision || "flip").split(" "), v = {};
            e = t(g);
            if (g[0].preventDefault) {
                h.at = "left top";
            }
            p = e.width;
            d = e.height;
            u = e.offset;
            T = j.extend({}, u);
            j.each([ "my", "at" ], function() {
                var e = (h[this] || "").split(" "), a, t;
                if (e.length === 1) {
                    e = s.test(e[0]) ? e.concat([ "center" ]) : i.test(e[0]) ? [ "center" ].concat(e) : [ "center", "center" ];
                }
                e[0] = s.test(e[0]) ? e[0] : "center";
                e[1] = i.test(e[1]) ? e[1] : "center";
                a = f.exec(e[0]);
                t = f.exec(e[1]);
                v[this] = [ a ? a[0] : 0, t ? t[0] : 0 ];
                h[this] = [ l.exec(e[0])[0], l.exec(e[1])[0] ];
            });
            if (w.length === 1) {
                w[1] = w[0];
            }
            if (h.at[0] === "right") {
                T.left += p;
            } else if (h.at[0] === "center") {
                T.left += p / 2;
            }
            if (h.at[1] === "bottom") {
                T.top += d;
            } else if (h.at[1] === "center") {
                T.top += d / 2;
            }
            m = q(v.at, p, d);
            T.left += m[0];
            T.top += m[1];
            return this.each(function() {
                var t, e, f = j(this), l = f.outerWidth(), c = f.outerHeight(), a = C(this, "marginLeft"), s = C(this, "marginTop"), r = l + a + C(this, "marginRight") + k.width, i = c + s + C(this, "marginBottom") + k.height, o = j.extend({}, T), n = q(v.my, f.outerWidth(), f.outerHeight());
                if (h.my[0] === "right") {
                    o.left -= l;
                } else if (h.my[0] === "center") {
                    o.left -= l / 2;
                }
                if (h.my[1] === "bottom") {
                    o.top -= c;
                } else if (h.my[1] === "center") {
                    o.top -= c / 2;
                }
                o.left += n[0];
                o.top += n[1];
                t = {
                    marginLeft: a,
                    marginTop: s
                };
                j.each([ "left", "top" ], function(e, a) {
                    if (j.ui.pos[w[e]]) {
                        j.ui.pos[w[e]][a](o, {
                            targetWidth: p,
                            targetHeight: d,
                            elemWidth: l,
                            elemHeight: c,
                            collisionPosition: t,
                            collisionWidth: r,
                            collisionHeight: i,
                            offset: [ m[0] + n[0], m[1] + n[1] ],
                            my: h.my,
                            at: h.at,
                            within: b,
                            elem: f
                        });
                    }
                });
                if (h.using) {
                    e = function(e) {
                        var a = u.left - o.left, t = a + p - l, s = u.top - o.top, r = s + d - c, i = {
                            target: {
                                element: g,
                                left: u.left,
                                top: u.top,
                                width: p,
                                height: d
                            },
                            element: {
                                element: f,
                                left: o.left,
                                top: o.top,
                                width: l,
                                height: c
                            },
                            horizontal: t < 0 ? "left" : a > 0 ? "right" : "center",
                            vertical: r < 0 ? "top" : s > 0 ? "bottom" : "middle"
                        };
                        if (p < l && x(a + t) < p) {
                            i.horizontal = "center";
                        }
                        if (d < c && x(s + r) < d) {
                            i.vertical = "middle";
                        }
                        if (y(x(a), x(t)) > y(x(s), x(r))) {
                            i.important = "horizontal";
                        } else {
                            i.important = "vertical";
                        }
                        h.using.call(this, e, i);
                    };
                }
                f.offset(j.extend(o, {
                    using: e
                }));
            });
        };
        j.ui.pos = {
            _trigger: function(e, a, t, s) {
                if (a.elem) {
                    a.elem.trigger({
                        type: t,
                        position: e,
                        positionData: a,
                        triggered: s
                    });
                }
            },
            fit: {
                left: function(e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "fitLeft");
                    var t = a.within, s = t.isWindow ? t.scrollLeft : t.offset.left, r = t.width, i = e.left - a.collisionPosition.marginLeft, f = s - i, l = i + a.collisionWidth - r - s, c;
                    if (a.collisionWidth > r) {
                        if (f > 0 && l <= 0) {
                            c = e.left + f + a.collisionWidth - r - s;
                            e.left += f - c;
                        } else if (l > 0 && f <= 0) {
                            e.left = s;
                        } else {
                            if (f > l) {
                                e.left = s + r - a.collisionWidth;
                            } else {
                                e.left = s;
                            }
                        }
                    } else if (f > 0) {
                        e.left += f;
                    } else if (l > 0) {
                        e.left -= l;
                    } else {
                        e.left = y(e.left - i, e.left);
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "fitLeft");
                },
                top: function(e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "fitTop");
                    var t = a.within, s = t.isWindow ? t.scrollTop : t.offset.top, r = a.within.height, i = e.top - a.collisionPosition.marginTop, f = s - i, l = i + a.collisionHeight - r - s, c;
                    if (a.collisionHeight > r) {
                        if (f > 0 && l <= 0) {
                            c = e.top + f + a.collisionHeight - r - s;
                            e.top += f - c;
                        } else if (l > 0 && f <= 0) {
                            e.top = s;
                        } else {
                            if (f > l) {
                                e.top = s + r - a.collisionHeight;
                            } else {
                                e.top = s;
                            }
                        }
                    } else if (f > 0) {
                        e.top += f;
                    } else if (l > 0) {
                        e.top -= l;
                    } else {
                        e.top = y(e.top - i, e.top);
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "fitTop");
                }
            },
            flip: {
                left: function(e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "flipLeft");
                    var t = a.within, s = t.offset.left + t.scrollLeft, r = t.width, i = t.isWindow ? t.scrollLeft : t.offset.left, f = e.left - a.collisionPosition.marginLeft, l = f - i, c = f + a.collisionWidth - r - i, o = a.my[0] === "left" ? -a.elemWidth : a.my[0] === "right" ? a.elemWidth : 0, n = a.at[0] === "left" ? a.targetWidth : a.at[0] === "right" ? -a.targetWidth : 0, h = -2 * a.offset[0], m, p;
                    if (l < 0) {
                        m = e.left + o + n + h + a.collisionWidth - r - s;
                        if (m < 0 || m < x(l)) {
                            e.left += o + n + h;
                        }
                    } else if (c > 0) {
                        p = e.left - a.collisionPosition.marginLeft + o + n + h - i;
                        if (p > 0 || x(p) < c) {
                            e.left += o + n + h;
                        }
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "flipLeft");
                },
                top: function(e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "flipTop");
                    var t = a.within, s = t.offset.top + t.scrollTop, r = t.height, i = t.isWindow ? t.scrollTop : t.offset.top, f = e.top - a.collisionPosition.marginTop, l = f - i, c = f + a.collisionHeight - r - i, o = a.my[1] === "top", n = o ? -a.elemHeight : a.my[1] === "bottom" ? a.elemHeight : 0, h = a.at[1] === "top" ? a.targetHeight : a.at[1] === "bottom" ? -a.targetHeight : 0, m = -2 * a.offset[1], p, d;
                    if (l < 0) {
                        d = e.top + n + h + m + a.collisionHeight - r - s;
                        if (d < 0 || d < x(l)) {
                            e.top += n + h + m;
                        }
                    } else if (c > 0) {
                        p = e.top - a.collisionPosition.marginTop + n + h + m - i;
                        if (p > 0 || x(p) < c) {
                            e.top += n + h + m;
                        }
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "flipTop");
                }
            },
            flipfit: {
                left: function() {
                    j.ui.pos.flip.left.apply(this, arguments);
                    j.ui.pos.fit.left.apply(this, arguments);
                },
                top: function() {
                    j.ui.pos.flip.top.apply(this, arguments);
                    j.ui.pos.fit.top.apply(this, arguments);
                }
            }
        };
        (function() {
            var e, a, t, s, r, i = document.getElementsByTagName("body")[0], f = document.createElement("div");
            e = document.createElement(i ? "div" : "body");
            t = {
                visibility: "hidden",
                width: 0,
                height: 0,
                border: 0,
                margin: 0,
                background: "none"
            };
            if (i) {
                j.extend(t, {
                    position: "absolute",
                    left: "-1000px",
                    top: "-1000px"
                });
            }
            for (r in t) {
                e.style[r] = t[r];
            }
            e.appendChild(f);
            a = i || document.documentElement;
            a.insertBefore(e, a.firstChild);
            f.style.cssText = "position: absolute; left: 10.7432222px;";
            s = j(f).offset().left;
            j.support.offsetFractions = s > 10 && s < 11;
            e.innerHTML = "";
            a.removeChild(e);
        })();
    })();
    var a = j.ui.position;
});

(function(e) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], e);
    } else if (window.jQuery && !window.jQuery.fn.iconpicker) {
        e(window.jQuery);
    }
})(function(c) {
    "use strict";
    var f = {
        isEmpty: function(e) {
            return e === false || e === "" || e === null || e === undefined;
        },
        isEmptyObject: function(e) {
            return this.isEmpty(e) === true || e.length === 0;
        },
        isElement: function(e) {
            return c(e).length > 0;
        },
        isString: function(e) {
            return typeof e === "string" || e instanceof String;
        },
        isArray: function(e) {
            return c.isArray(e);
        },
        inArray: function(e, a) {
            return c.inArray(e, a) !== -1;
        },
        throwError: function(e) {
            throw "Font Awesome Icon Picker Exception: " + e;
        }
    };
    var t = function(e, a) {
        this._id = t._idCounter++;
        this.element = c(e).addClass("iconpicker-element");
        this._trigger("iconpickerCreate", {
            iconpickerValue: this.iconpickerValue
        });
        this.options = c.extend({}, t.defaultOptions, this.element.data(), a);
        this.options.templates = c.extend({}, t.defaultOptions.templates, this.options.templates);
        this.options.originalPlacement = this.options.placement;
        this.container = f.isElement(this.options.container) ? c(this.options.container) : false;
        if (this.container === false) {
            if (this.element.is(".dropdown-toggle")) {
                this.container = c("~ .dropdown-menu:first", this.element);
            } else {
                this.container = this.element.is("input,textarea,button,.btn") ? this.element.parent() : this.element;
            }
        }
        this.container.addClass("iconpicker-container");
        if (this.isDropdownMenu()) {
            this.options.placement = "inline";
        }
        this.input = this.element.is("input,textarea") ? this.element.addClass("iconpicker-input") : false;
        if (this.input === false) {
            this.input = this.container.find(this.options.input);
            if (!this.input.is("input,textarea")) {
                this.input = false;
            }
        }
        this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component);
        if (this.component.length === 0) {
            this.component = false;
        } else {
            this.component.find("i").addClass("iconpicker-component");
        }
        this._createPopover();
        this._createIconpicker();
        if (this.getAcceptButton().length === 0) {
            this.options.mustAccept = false;
        }
        if (this.isInputGroup()) {
            this.container.parent().append(this.popover);
        } else {
            this.container.append(this.popover);
        }
        this._bindElementEvents();
        this._bindWindowEvents();
        this.update(this.options.selected);
        if (this.isInline()) {
            this.show();
        }
        this._trigger("iconpickerCreated", {
            iconpickerValue: this.iconpickerValue
        });
    };
    t._idCounter = 0;
    t.defaultOptions = {
        title: false,
        selected: false,
        defaultValue: false,
        placement: "bottom",
        collision: "none",
        animation: true,
        hideOnSelect: false,
        showFooter: false,
        searchInFooter: false,
        mustAccept: false,
        selectedCustomClass: "bg-primary",
        icons: [],
        fullClassFormatter: function(e) {
            return e;
        },
        input: "input,.iconpicker-input",
        inputSearch: false,
        container: false,
        component: ".input-group-addon,.iconpicker-component",
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' + ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
        }
    };
    t.batch = function(e, a) {
        var t = Array.prototype.slice.call(arguments, 2);
        return c(e).each(function() {
            var e = c(this).data("iconpicker");
            if (!!e) {
                e[a].apply(e, t);
            }
        });
    };
    t.prototype = {
        constructor: t,
        options: {},
        _id: 0,
        _trigger: function(e, a) {
            a = a || {};
            this.element.trigger(c.extend({
                type: e,
                iconpickerInstance: this
            }, a));
        },
        _createPopover: function() {
            this.popover = c(this.options.templates.popover);
            var e = this.popover.find(".popover-title");
            if (!!this.options.title) {
                e.append(c('<div class="popover-title-text">' + this.options.title + "</div>"));
            }
            if (this.hasSeparatedSearchInput() && !this.options.searchInFooter) {
                e.append(this.options.templates.search);
            } else if (!this.options.title) {
                e.remove();
            }
            if (this.options.showFooter && !f.isEmpty(this.options.templates.footer)) {
                var a = c(this.options.templates.footer);
                if (this.hasSeparatedSearchInput() && this.options.searchInFooter) {
                    a.append(c(this.options.templates.search));
                }
                if (!f.isEmpty(this.options.templates.buttons)) {
                    a.append(c(this.options.templates.buttons));
                }
                this.popover.append(a);
            }
            if (this.options.animation === true) {
                this.popover.addClass("fade");
            }
            return this.popover;
        },
        _createIconpicker: function() {
            var t = this;
            this.iconpicker = c(this.options.templates.iconpicker);
            var e = function(e) {
                var a = c(this);
                if (a.is("i")) {
                    a = a.parent();
                }
                t._trigger("iconpickerSelect", {
                    iconpickerItem: a,
                    iconpickerValue: t.iconpickerValue
                });
                if (t.options.mustAccept === false) {
                    t.update(a.data("iconpickerValue"));
                    t._trigger("iconpickerSelected", {
                        iconpickerItem: this,
                        iconpickerValue: t.iconpickerValue
                    });
                } else {
                    t.update(a.data("iconpickerValue"), true);
                }
                if (t.options.hideOnSelect && t.options.mustAccept === false) {
                    t.hide();
                }
            };
            var a = c(this.options.templates.iconpickerItem);
            var s = [];
            for (var r in this.options.icons) {
                if (typeof this.options.icons[r].title === "string") {
                    var i = a.clone();
                    i.find("i").addClass(this.options.fullClassFormatter(this.options.icons[r].title));
                    i.data("iconpickerValue", this.options.icons[r].title).on("click.iconpicker", e);
                    i.attr("title", "." + this.options.icons[r].title);
                    if (this.options.icons[r].searchTerms.length > 0) {
                        var f = "";
                        for (var l = 0; l < this.options.icons[r].searchTerms.length; l++) {
                            f = f + this.options.icons[r].searchTerms[l] + " ";
                        }
                        i.attr("data-search-terms", f);
                    }
                    s.push(i);
                }
            }
            this.iconpicker.find(".iconpicker-items").append(s);
            this.popover.find(".popover-content").append(this.iconpicker);
            return this.iconpicker;
        },
        _isEventInsideIconpicker: function(e) {
            var a = c(e.target);
            if ((!a.hasClass("iconpicker-element") || a.hasClass("iconpicker-element") && !a.is(this.element)) && a.parents(".iconpicker-popover").length === 0) {
                return false;
            }
            return true;
        },
        _bindElementEvents: function() {
            var a = this;
            this.getSearchInput().on("keyup.iconpicker", function() {
                a.filter(c(this).val().toLowerCase());
            });
            this.getAcceptButton().on("click.iconpicker", function() {
                var e = a.iconpicker.find(".iconpicker-selected").get(0);
                a.update(a.iconpickerValue);
                a._trigger("iconpickerSelected", {
                    iconpickerItem: e,
                    iconpickerValue: a.iconpickerValue
                });
                if (!a.isInline()) {
                    a.hide();
                }
            });
            this.getCancelButton().on("click.iconpicker", function() {
                if (!a.isInline()) {
                    a.hide();
                }
            });
            this.element.on("focus.iconpicker", function(e) {
                a.show();
                e.stopPropagation();
            });
            if (this.hasComponent()) {
                this.component.on("click.iconpicker", function() {
                    a.toggle();
                });
            }
            if (this.hasInput()) {
                this.input.on("keyup.iconpicker", function(e) {
                    if (!f.inArray(e.keyCode, [ 38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86 ])) {
                        a.update();
                    } else {
                        a._updateFormGroupStatus(a.getValid(this.value) !== false);
                    }
                    if (a.options.inputSearch === true) {
                        a.filter(c(this).val().toLowerCase());
                    }
                });
            }
        },
        _bindWindowEvents: function() {
            var e = c(window.document);
            var a = this;
            var t = ".iconpicker.inst" + this._id;
            c(window).on("resize.iconpicker" + t + " orientationchange.iconpicker" + t, function(e) {
                if (a.popover.hasClass("in")) {
                    a.updatePlacement();
                }
            });
            if (!a.isInline()) {
                e.on("mouseup" + t, function(e) {
                    if (!a._isEventInsideIconpicker(e) && !a.isInline()) {
                        a.hide();
                    }
                });
            }
        },
        _unbindElementEvents: function() {
            this.popover.off(".iconpicker");
            this.element.off(".iconpicker");
            if (this.hasInput()) {
                this.input.off(".iconpicker");
            }
            if (this.hasComponent()) {
                this.component.off(".iconpicker");
            }
            if (this.hasContainer()) {
                this.container.off(".iconpicker");
            }
        },
        _unbindWindowEvents: function() {
            c(window).off(".iconpicker.inst" + this._id);
            c(window.document).off(".iconpicker.inst" + this._id);
        },
        updatePlacement: function(e, a) {
            e = e || this.options.placement;
            this.options.placement = e;
            a = a || this.options.collision;
            a = a === true ? "flip" : a;
            var t = {
                at: "right bottom",
                my: "right top",
                of: this.hasInput() && !this.isInputGroup() ? this.input : this.container,
                collision: a === true ? "flip" : a,
                within: window
            };
            this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner " + "rightTop right rightBottom bottomRight bottomRightCorner " + "bottom bottomLeft bottomLeftCorner leftBottom left leftTop");
            if (typeof e === "object") {
                return this.popover.pos(c.extend({}, t, e));
            }
            switch (e) {
              case "inline":
                {
                    t = false;
                }
                break;

              case "topLeftCorner":
                {
                    t.my = "right bottom";
                    t.at = "left top";
                }
                break;

              case "topLeft":
                {
                    t.my = "left bottom";
                    t.at = "left top";
                }
                break;

              case "top":
                {
                    t.my = "center bottom";
                    t.at = "center top";
                }
                break;

              case "topRight":
                {
                    t.my = "right bottom";
                    t.at = "right top";
                }
                break;

              case "topRightCorner":
                {
                    t.my = "left bottom";
                    t.at = "right top";
                }
                break;

              case "rightTop":
                {
                    t.my = "left bottom";
                    t.at = "right center";
                }
                break;

              case "right":
                {
                    t.my = "left center";
                    t.at = "right center";
                }
                break;

              case "rightBottom":
                {
                    t.my = "left top";
                    t.at = "right center";
                }
                break;

              case "bottomRightCorner":
                {
                    t.my = "left top";
                    t.at = "right bottom";
                }
                break;

              case "bottomRight":
                {
                    t.my = "right top";
                    t.at = "right bottom";
                }
                break;

              case "bottom":
                {
                    t.my = "center top";
                    t.at = "center bottom";
                }
                break;

              case "bottomLeft":
                {
                    t.my = "left top";
                    t.at = "left bottom";
                }
                break;

              case "bottomLeftCorner":
                {
                    t.my = "right top";
                    t.at = "left bottom";
                }
                break;

              case "leftBottom":
                {
                    t.my = "right top";
                    t.at = "left center";
                }
                break;

              case "left":
                {
                    t.my = "right center";
                    t.at = "left center";
                }
                break;

              case "leftTop":
                {
                    t.my = "right bottom";
                    t.at = "left center";
                }
                break;

              default:
                {
                    return false;
                }
                break;
            }
            this.popover.css({
                display: this.options.placement === "inline" ? "" : "block"
            });
            if (t !== false) {
                this.popover.pos(t).css("maxWidth", c(window).width() - this.container.offset().left - 5);
            } else {
                this.popover.css({
                    top: "auto",
                    right: "auto",
                    bottom: "auto",
                    left: "auto",
                    maxWidth: "none"
                });
            }
            this.popover.addClass(this.options.placement);
            return true;
        },
        _updateComponents: function() {
            this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass);
            if (this.iconpickerValue) {
                this.iconpicker.find("." + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, ".")).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass);
            }
            if (this.hasComponent()) {
                var e = this.component.find("i");
                if (e.length > 0) {
                    e.attr("class", this.options.fullClassFormatter(this.iconpickerValue));
                } else {
                    this.component.html(this.getHtml());
                }
            }
        },
        _updateFormGroupStatus: function(e) {
            if (this.hasInput()) {
                if (e !== false) {
                    this.input.parents(".form-group:first").removeClass("has-error");
                } else {
                    this.input.parents(".form-group:first").addClass("has-error");
                }
                return true;
            }
            return false;
        },
        getValid: function(e) {
            if (!f.isString(e)) {
                e = "";
            }
            var a = e === "";
            e = c.trim(e);
            var t = false;
            for (var s = 0; s < this.options.icons.length; s++) {
                if (this.options.icons[s].title === e) {
                    t = true;
                    break;
                }
            }
            if (t || a) {
                return e;
            }
            return false;
        },
        setValue: function(e) {
            var a = this.getValid(e);
            if (a !== false) {
                this.iconpickerValue = a;
                this._trigger("iconpickerSetValue", {
                    iconpickerValue: a
                });
                return this.iconpickerValue;
            } else {
                this._trigger("iconpickerInvalid", {
                    iconpickerValue: e
                });
                return false;
            }
        },
        getHtml: function() {
            return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>';
        },
        setSourceValue: function(e) {
            e = this.setValue(e);
            if (e !== false && e !== "") {
                if (this.hasInput()) {
                    this.input.val(this.iconpickerValue);
                } else {
                    this.element.data("iconpickerValue", this.iconpickerValue);
                }
                this._trigger("iconpickerSetSourceValue", {
                    iconpickerValue: e
                });
            }
            return e;
        },
        getSourceValue: function(e) {
            e = e || this.options.defaultValue;
            var a = e;
            if (this.hasInput()) {
                a = this.input.val();
            } else {
                a = this.element.data("iconpickerValue");
            }
            if (a === undefined || a === "" || a === null || a === false) {
                a = e;
            }
            return a;
        },
        hasInput: function() {
            return this.input !== false;
        },
        isInputSearch: function() {
            return this.hasInput() && this.options.inputSearch === true;
        },
        isInputGroup: function() {
            return this.container.is(".input-group");
        },
        isDropdownMenu: function() {
            return this.container.is(".dropdown-menu");
        },
        hasSeparatedSearchInput: function() {
            return this.options.templates.search !== false && !this.isInputSearch();
        },
        hasComponent: function() {
            return this.component !== false;
        },
        hasContainer: function() {
            return this.container !== false;
        },
        getAcceptButton: function() {
            return this.popover.find(".iconpicker-btn-accept");
        },
        getCancelButton: function() {
            return this.popover.find(".iconpicker-btn-cancel");
        },
        getSearchInput: function() {
            return this.popover.find(".iconpicker-search");
        },
        filter: function(r) {
            if (f.isEmpty(r)) {
                this.iconpicker.find(".iconpicker-item").show();
                return c(false);
            } else {
                var i = [];
                this.iconpicker.find(".iconpicker-item").each(function() {
                    var e = c(this);
                    var a = e.attr("title");
                    var t = e.attr("data-search-terms") ? e.attr("data-search-terms") : "";
                    a = a + " " + t;
                    var s = false;
                    try {
                        s = new RegExp("(^|\\W)" + r, "g");
                    } catch (e) {
                        s = false;
                    }
                    if (s !== false && a.match(s)) {
                        i.push(e);
                        e.show();
                    } else {
                        e.hide();
                    }
                });
                return i;
            }
        },
        show: function() {
            if (this.popover.hasClass("in")) {
                return false;
            }
            c.iconpicker.batch(c(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide");
            this._trigger("iconpickerShow", {
                iconpickerValue: this.iconpickerValue
            });
            this.updatePlacement();
            this.popover.addClass("in");
            setTimeout(c.proxy(function() {
                this.popover.css("display", this.isInline() ? "" : "block");
                this._trigger("iconpickerShown", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        hide: function() {
            if (!this.popover.hasClass("in")) {
                return false;
            }
            this._trigger("iconpickerHide", {
                iconpickerValue: this.iconpickerValue
            });
            this.popover.removeClass("in");
            setTimeout(c.proxy(function() {
                this.popover.css("display", "none");
                this.getSearchInput().val("");
                this.filter("");
                this._trigger("iconpickerHidden", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        toggle: function() {
            if (this.popover.is(":visible")) {
                this.hide();
            } else {
                this.show(true);
            }
        },
        update: function(e, a) {
            e = e ? e : this.getSourceValue(this.iconpickerValue);
            this._trigger("iconpickerUpdate", {
                iconpickerValue: this.iconpickerValue
            });
            if (a === true) {
                e = this.setValue(e);
            } else {
                e = this.setSourceValue(e);
                this._updateFormGroupStatus(e !== false);
            }
            if (e !== false) {
                this._updateComponents();
            }
            this._trigger("iconpickerUpdated", {
                iconpickerValue: this.iconpickerValue
            });
            return e;
        },
        destroy: function() {
            this._trigger("iconpickerDestroy", {
                iconpickerValue: this.iconpickerValue
            });
            this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element");
            this._unbindElementEvents();
            this._unbindWindowEvents();
            c(this.popover).remove();
            this._trigger("iconpickerDestroyed", {
                iconpickerValue: this.iconpickerValue
            });
        },
        disable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", true);
                return true;
            }
            return false;
        },
        enable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", false);
                return true;
            }
            return false;
        },
        isDisabled: function() {
            if (this.hasInput()) {
                return this.input.prop("disabled") === true;
            }
            return false;
        },
        isInline: function() {
            return this.options.placement === "inline" || this.popover.hasClass("inline");
        }
    };
    c.iconpicker = t;
    c.fn.iconpicker = function(a) {
        return this.each(function() {
            var e = c(this);
            if (!e.data("iconpicker")) {
                e.data("iconpicker", new t(this, typeof a === "object" ? a : {}));
            }
        });
    };

    t.defaultOptions = c.extend(t.defaultOptions, {
        icons: [ 
        {
            title: "fab fa-500px",
            searchTerms: []
        }, {
            title: "fab fa-accessible-icon",
            searchTerms: [ "accessibility", "handicap", "person", "wheelchair", "wheelchair-alt" ]
        }]
    });

    t.defaultOptions = c.extend(t.defaultOptions, {
        icons: [ 
            {
				title: "ti ti-a-b",
				searchTerms: ["a-b"]
			}, {
				title: "ti ti-a-b-off",
				searchTerms: ["a-b-off"]
			}, {
				title: "ti ti-abacus",
				searchTerms: ["abacus"]
			}, {
				title: "ti ti-abacus-off",
				searchTerms: ["abacus-off"]
			}, {
				title: "ti ti-abc",
				searchTerms: ["abc"]
			}, {
				title: "ti ti-access-point",
				searchTerms: ["access-point"]
			}, {
				title: "ti ti-access-point-off",
				searchTerms: ["access-point-off"]
			}, {
				title: "ti ti-accessible",
				searchTerms: ["accessible"]
			}, {
				title: "ti ti-accessible-filled",
				searchTerms: ["accessible-filled"]
			}, {
				title: "ti ti-accessible-off",
				searchTerms: ["accessible-off"]
			}, {
				title: "ti ti-activity",
				searchTerms: ["activity"]
			}, {
				title: "ti ti-activity-heartbeat",
				searchTerms: ["activity-heartbeat"]
			}, {
				title: "ti ti-ad",
				searchTerms: ["ad"]
			}, {
				title: "ti ti-ad-2",
				searchTerms: ["ad-2"]
			}, {
				title: "ti ti-ad-circle",
				searchTerms: ["ad-circle"]
			}, {
				title: "ti ti-ad-circle-filled",
				searchTerms: ["ad-circle-filled"]
			}, {
				title: "ti ti-ad-circle-off",
				searchTerms: ["ad-circle-off"]
			}, {
				title: "ti ti-ad-filled",
				searchTerms: ["ad-filled"]
			}, {
				title: "ti ti-ad-off",
				searchTerms: ["ad-off"]
			}, {
				title: "ti ti-address-book",
				searchTerms: ["address-book"]
			}, {
				title: "ti ti-address-book-off",
				searchTerms: ["address-book-off"]
			}, {
				title: "ti ti-adjustments",
				searchTerms: ["adjustments"]
			}, {
				title: "ti ti-adjustments-alt",
				searchTerms: ["adjustments-alt"]
			}, {
				title: "ti ti-adjustments-bolt",
				searchTerms: ["adjustments-bolt"]
			}, {
				title: "ti ti-adjustments-cancel",
				searchTerms: ["adjustments-cancel"]
			}, {
				title: "ti ti-adjustments-check",
				searchTerms: ["adjustments-check"]
			}, {
				title: "ti ti-adjustments-code",
				searchTerms: ["adjustments-code"]
			}, {
				title: "ti ti-adjustments-cog",
				searchTerms: ["adjustments-cog"]
			}, {
				title: "ti ti-adjustments-dollar",
				searchTerms: ["adjustments-dollar"]
			}, {
				title: "ti ti-adjustments-down",
				searchTerms: ["adjustments-down"]
			}, {
				title: "ti ti-adjustments-exclamation",
				searchTerms: ["adjustments-exclamation"]
			}, {
				title: "ti ti-adjustments-filled",
				searchTerms: ["adjustments-filled"]
			}, {
				title: "ti ti-adjustments-heart",
				searchTerms: ["adjustments-heart"]
			}, {
				title: "ti ti-adjustments-horizontal",
				searchTerms: ["adjustments-horizontal"]
			}, {
				title: "ti ti-adjustments-minus",
				searchTerms: ["adjustments-minus"]
			}, {
				title: "ti ti-adjustments-off",
				searchTerms: ["adjustments-off"]
			}, {
				title: "ti ti-adjustments-pause",
				searchTerms: ["adjustments-pause"]
			}, {
				title: "ti ti-adjustments-pin",
				searchTerms: ["adjustments-pin"]
			}, {
				title: "ti ti-adjustments-plus",
				searchTerms: ["adjustments-plus"]
			}, {
				title: "ti ti-adjustments-question",
				searchTerms: ["adjustments-question"]
			}, {
				title: "ti ti-adjustments-search",
				searchTerms: ["adjustments-search"]
			}, {
				title: "ti ti-adjustments-share",
				searchTerms: ["adjustments-share"]
			}, {
				title: "ti ti-adjustments-star",
				searchTerms: ["adjustments-star"]
			}, {
				title: "ti ti-adjustments-up",
				searchTerms: ["adjustments-up"]
			}, {
				title: "ti ti-adjustments-x",
				searchTerms: ["adjustments-x"]
			}, {
				title: "ti ti-aerial-lift",
				searchTerms: ["aerial-lift"]
			}, {
				title: "ti ti-affiliate",
				searchTerms: ["affiliate"]
			}, {
				title: "ti ti-affiliate-filled",
				searchTerms: ["affiliate-filled"]
			}, {
				title: "ti ti-ai",
				searchTerms: ["ai"]
			}, {
				title: "ti ti-air-balloon",
				searchTerms: ["air-balloon"]
			}, {
				title: "ti ti-air-conditioning",
				searchTerms: ["air-conditioning"]
			}, {
				title: "ti ti-air-conditioning-disabled",
				searchTerms: ["air-conditioning-disabled"]
			}, {
				title: "ti ti-air-traffic-control",
				searchTerms: ["air-traffic-control"]
			}, {
				title: "ti ti-alarm",
				searchTerms: ["alarm"]
			}, {
				title: "ti ti-alarm-average",
				searchTerms: ["alarm-average"]
			}, {
				title: "ti ti-alarm-filled",
				searchTerms: ["alarm-filled"]
			}, {
				title: "ti ti-alarm-minus",
				searchTerms: ["alarm-minus"]
			}, {
				title: "ti ti-alarm-minus-filled",
				searchTerms: ["alarm-minus-filled"]
			}, {
				title: "ti ti-alarm-off",
				searchTerms: ["alarm-off"]
			}, {
				title: "ti ti-alarm-plus",
				searchTerms: ["alarm-plus"]
			}, {
				title: "ti ti-alarm-plus-filled",
				searchTerms: ["alarm-plus-filled"]
			}, {
				title: "ti ti-alarm-snooze",
				searchTerms: ["alarm-snooze"]
			}, {
				title: "ti ti-alarm-snooze-filled",
				searchTerms: ["alarm-snooze-filled"]
			}, {
				title: "ti ti-album",
				searchTerms: ["album"]
			}, {
				title: "ti ti-album-off",
				searchTerms: ["album-off"]
			}, {
				title: "ti ti-alert-circle",
				searchTerms: ["alert-circle"]
			}, {
				title: "ti ti-alert-circle-filled",
				searchTerms: ["alert-circle-filled"]
			}, {
				title: "ti ti-alert-circle-off",
				searchTerms: ["alert-circle-off"]
			}, {
				title: "ti ti-alert-hexagon",
				searchTerms: ["alert-hexagon"]
			}, {
				title: "ti ti-alert-hexagon-filled",
				searchTerms: ["alert-hexagon-filled"]
			}, {
				title: "ti ti-alert-hexagon-off",
				searchTerms: ["alert-hexagon-off"]
			}, {
				title: "ti ti-alert-octagon",
				searchTerms: ["alert-octagon"]
			}, {
				title: "ti ti-alert-octagon-filled",
				searchTerms: ["alert-octagon-filled"]
			}, {
				title: "ti ti-alert-small",
				searchTerms: ["alert-small"]
			}, {
				title: "ti ti-alert-small-off",
				searchTerms: ["alert-small-off"]
			}, {
				title: "ti ti-alert-square",
				searchTerms: ["alert-square"]
			}, {
				title: "ti ti-alert-square-filled",
				searchTerms: ["alert-square-filled"]
			}, {
				title: "ti ti-alert-square-rounded",
				searchTerms: ["alert-square-rounded"]
			}, {
				title: "ti ti-alert-square-rounded-filled",
				searchTerms: ["alert-square-rounded-filled"]
			}, {
				title: "ti ti-alert-square-rounded-off",
				searchTerms: ["alert-square-rounded-off"]
			}, {
				title: "ti ti-alert-triangle",
				searchTerms: ["alert-triangle"]
			}, {
				title: "ti ti-alert-triangle-filled",
				searchTerms: ["alert-triangle-filled"]
			}, {
				title: "ti ti-alert-triangle-off",
				searchTerms: ["alert-triangle-off"]
			}, {
				title: "ti ti-alien",
				searchTerms: ["alien"]
			}, {
				title: "ti ti-alien-filled",
				searchTerms: ["alien-filled"]
			}, {
				title: "ti ti-align-box-bottom-center",
				searchTerms: ["align-box-bottom-center"]
			}, {
				title: "ti ti-align-box-bottom-center-filled",
				searchTerms: ["align-box-bottom-center-filled"]
			}, {
				title: "ti ti-align-box-bottom-left",
				searchTerms: ["align-box-bottom-left"]
			}, {
				title: "ti ti-align-box-bottom-left-filled",
				searchTerms: ["align-box-bottom-left-filled"]
			}, {
				title: "ti ti-align-box-bottom-right",
				searchTerms: ["align-box-bottom-right"]
			}, {
				title: "ti ti-align-box-bottom-right-filled",
				searchTerms: ["align-box-bottom-right-filled"]
			}, {
				title: "ti ti-align-box-center-bottom",
				searchTerms: ["align-box-center-bottom"]
			}, {
				title: "ti ti-align-box-center-middle",
				searchTerms: ["align-box-center-middle"]
			}, {
				title: "ti ti-align-box-center-middle-filled",
				searchTerms: ["align-box-center-middle-filled"]
			}, {
				title: "ti ti-align-box-center-stretch",
				searchTerms: ["align-box-center-stretch"]
			}, {
				title: "ti ti-align-box-center-top",
				searchTerms: ["align-box-center-top"]
			}, {
				title: "ti ti-align-box-left-bottom",
				searchTerms: ["align-box-left-bottom"]
			}, {
				title: "ti ti-align-box-left-bottom-filled",
				searchTerms: ["align-box-left-bottom-filled"]
			}, {
				title: "ti ti-align-box-left-middle",
				searchTerms: ["align-box-left-middle"]
			}, {
				title: "ti ti-align-box-left-middle-filled",
				searchTerms: ["align-box-left-middle-filled"]
			}, {
				title: "ti ti-align-box-left-stretch",
				searchTerms: ["align-box-left-stretch"]
			}, {
				title: "ti ti-align-box-left-top",
				searchTerms: ["align-box-left-top"]
			}, {
				title: "ti ti-align-box-left-top-filled",
				searchTerms: ["align-box-left-top-filled"]
			}, {
				title: "ti ti-align-box-right-bottom",
				searchTerms: ["align-box-right-bottom"]
			}, {
				title: "ti ti-align-box-right-bottom-filled",
				searchTerms: ["align-box-right-bottom-filled"]
			}, {
				title: "ti ti-align-box-right-middle",
				searchTerms: ["align-box-right-middle"]
			}, {
				title: "ti ti-align-box-right-middle-filled",
				searchTerms: ["align-box-right-middle-filled"]
			}, {
				title: "ti ti-align-box-right-stretch",
				searchTerms: ["align-box-right-stretch"]
			}, {
				title: "ti ti-align-box-right-top",
				searchTerms: ["align-box-right-top"]
			}, {
				title: "ti ti-align-box-right-top-filled",
				searchTerms: ["align-box-right-top-filled"]
			}, {
				title: "ti ti-align-box-top-center",
				searchTerms: ["align-box-top-center"]
			}, {
				title: "ti ti-align-box-top-center-filled",
				searchTerms: ["align-box-top-center-filled"]
			}, {
				title: "ti ti-align-box-top-left",
				searchTerms: ["align-box-top-left"]
			}, {
				title: "ti ti-align-box-top-left-filled",
				searchTerms: ["align-box-top-left-filled"]
			}, {
				title: "ti ti-align-box-top-right",
				searchTerms: ["align-box-top-right"]
			}, {
				title: "ti ti-align-box-top-right-filled",
				searchTerms: ["align-box-top-right-filled"]
			}, {
				title: "ti ti-align-center",
				searchTerms: ["align-center"]
			}, {
				title: "ti ti-align-justified",
				searchTerms: ["align-justified"]
			}, {
				title: "ti ti-align-left",
				searchTerms: ["align-left"]
			}, {
				title: "ti ti-align-right",
				searchTerms: ["align-right"]
			}, {
				title: "ti ti-alpha",
				searchTerms: ["alpha"]
			}, {
				title: "ti ti-alphabet-cyrillic",
				searchTerms: ["alphabet-cyrillic"]
			}, {
				title: "ti ti-alphabet-greek",
				searchTerms: ["alphabet-greek"]
			}, {
				title: "ti ti-alphabet-latin",
				searchTerms: ["alphabet-latin"]
			}, {
				title: "ti ti-alt",
				searchTerms: ["alt"]
			}, {
				title: "ti ti-ambulance",
				searchTerms: ["ambulance"]
			}, {
				title: "ti ti-ampersand",
				searchTerms: ["ampersand"]
			}, {
				title: "ti ti-analyze",
				searchTerms: ["analyze"]
			}, {
				title: "ti ti-analyze-filled",
				searchTerms: ["analyze-filled"]
			}, {
				title: "ti ti-analyze-off",
				searchTerms: ["analyze-off"]
			}, {
				title: "ti ti-anchor",
				searchTerms: ["anchor"]
			}, {
				title: "ti ti-anchor-off",
				searchTerms: ["anchor-off"]
			}, {
				title: "ti ti-angle",
				searchTerms: ["angle"]
			}, {
				title: "ti ti-ankh",
				searchTerms: ["ankh"]
			}, {
				title: "ti ti-antenna",
				searchTerms: ["antenna"]
			}, {
				title: "ti ti-antenna-bars-1",
				searchTerms: ["antenna-bars-1"]
			}, {
				title: "ti ti-antenna-bars-2",
				searchTerms: ["antenna-bars-2"]
			}, {
				title: "ti ti-antenna-bars-3",
				searchTerms: ["antenna-bars-3"]
			}, {
				title: "ti ti-antenna-bars-4",
				searchTerms: ["antenna-bars-4"]
			}, {
				title: "ti ti-antenna-bars-5",
				searchTerms: ["antenna-bars-5"]
			}, {
				title: "ti ti-antenna-bars-off",
				searchTerms: ["antenna-bars-off"]
			}, {
				title: "ti ti-antenna-off",
				searchTerms: ["antenna-off"]
			}, {
				title: "ti ti-aperture",
				searchTerms: ["aperture"]
			}, {
				title: "ti ti-aperture-off",
				searchTerms: ["aperture-off"]
			}, {
				title: "ti ti-api",
				searchTerms: ["api"]
			}, {
				title: "ti ti-api-app",
				searchTerms: ["api-app"]
			}, {
				title: "ti ti-api-app-off",
				searchTerms: ["api-app-off"]
			}, {
				title: "ti ti-api-off",
				searchTerms: ["api-off"]
			}, {
				title: "ti ti-app-window",
				searchTerms: ["app-window"]
			}, {
				title: "ti ti-app-window-filled",
				searchTerms: ["app-window-filled"]
			}, {
				title: "ti ti-apple",
				searchTerms: ["apple"]
			}, {
				title: "ti ti-apps",
				searchTerms: ["apps"]
			}, {
				title: "ti ti-apps-filled",
				searchTerms: ["apps-filled"]
			}, {
				title: "ti ti-apps-off",
				searchTerms: ["apps-off"]
			}, {
				title: "ti ti-archery-arrow",
				searchTerms: ["archery-arrow"]
			}, {
				title: "ti ti-archive",
				searchTerms: ["archive"]
			}, {
				title: "ti ti-archive-filled",
				searchTerms: ["archive-filled"]
			}, {
				title: "ti ti-archive-off",
				searchTerms: ["archive-off"]
			}, {
				title: "ti ti-armchair",
				searchTerms: ["armchair"]
			}, {
				title: "ti ti-armchair-2",
				searchTerms: ["armchair-2"]
			}, {
				title: "ti ti-armchair-2-off",
				searchTerms: ["armchair-2-off"]
			}, {
				title: "ti ti-armchair-off",
				searchTerms: ["armchair-off"]
			}, {
				title: "ti ti-arrow-autofit-content",
				searchTerms: ["arrow-autofit-content"]
			}, {
				title: "ti ti-arrow-autofit-content-filled",
				searchTerms: ["arrow-autofit-content-filled"]
			}, {
				title: "ti ti-arrow-autofit-down",
				searchTerms: ["arrow-autofit-down"]
			}, {
				title: "ti ti-arrow-autofit-height",
				searchTerms: ["arrow-autofit-height"]
			}, {
				title: "ti ti-arrow-autofit-left",
				searchTerms: ["arrow-autofit-left"]
			}, {
				title: "ti ti-arrow-autofit-right",
				searchTerms: ["arrow-autofit-right"]
			}, {
				title: "ti ti-arrow-autofit-up",
				searchTerms: ["arrow-autofit-up"]
			}, {
				title: "ti ti-arrow-autofit-width",
				searchTerms: ["arrow-autofit-width"]
			}, {
				title: "ti ti-arrow-back",
				searchTerms: ["arrow-back"]
			}, {
				title: "ti ti-arrow-back-up",
				searchTerms: ["arrow-back-up"]
			}, {
				title: "ti ti-arrow-back-up-double",
				searchTerms: ["arrow-back-up-double"]
			}, {
				title: "ti ti-arrow-badge-down",
				searchTerms: ["arrow-badge-down"]
			}, {
				title: "ti ti-arrow-badge-down-filled",
				searchTerms: ["arrow-badge-down-filled"]
			}, {
				title: "ti ti-arrow-badge-left",
				searchTerms: ["arrow-badge-left"]
			}, {
				title: "ti ti-arrow-badge-left-filled",
				searchTerms: ["arrow-badge-left-filled"]
			}, {
				title: "ti ti-arrow-badge-right",
				searchTerms: ["arrow-badge-right"]
			}, {
				title: "ti ti-arrow-badge-right-filled",
				searchTerms: ["arrow-badge-right-filled"]
			}, {
				title: "ti ti-arrow-badge-up",
				searchTerms: ["arrow-badge-up"]
			}, {
				title: "ti ti-arrow-badge-up-filled",
				searchTerms: ["arrow-badge-up-filled"]
			}, {
				title: "ti ti-arrow-bar-both",
				searchTerms: ["arrow-bar-both"]
			}, {
				title: "ti ti-arrow-bar-down",
				searchTerms: ["arrow-bar-down"]
			}, {
				title: "ti ti-arrow-bar-left",
				searchTerms: ["arrow-bar-left"]
			}, {
				title: "ti ti-arrow-bar-right",
				searchTerms: ["arrow-bar-right"]
			}, {
				title: "ti ti-arrow-bar-to-down",
				searchTerms: ["arrow-bar-to-down"]
			}, {
				title: "ti ti-arrow-bar-to-left",
				searchTerms: ["arrow-bar-to-left"]
			}, {
				title: "ti ti-arrow-bar-to-right",
				searchTerms: ["arrow-bar-to-right"]
			}, {
				title: "ti ti-arrow-bar-to-up",
				searchTerms: ["arrow-bar-to-up"]
			}, {
				title: "ti ti-arrow-bar-up",
				searchTerms: ["arrow-bar-up"]
			}, {
				title: "ti ti-arrow-bear-left",
				searchTerms: ["arrow-bear-left"]
			}, {
				title: "ti ti-arrow-bear-left-2",
				searchTerms: ["arrow-bear-left-2"]
			}, {
				title: "ti ti-arrow-bear-right",
				searchTerms: ["arrow-bear-right"]
			}, {
				title: "ti ti-arrow-bear-right-2",
				searchTerms: ["arrow-bear-right-2"]
			}, {
				title: "ti ti-arrow-big-down",
				searchTerms: ["arrow-big-down"]
			}, {
				title: "ti ti-arrow-big-down-filled",
				searchTerms: ["arrow-big-down-filled"]
			}, {
				title: "ti ti-arrow-big-down-line",
				searchTerms: ["arrow-big-down-line"]
			}, {
				title: "ti ti-arrow-big-down-line-filled",
				searchTerms: ["arrow-big-down-line-filled"]
			}, {
				title: "ti ti-arrow-big-down-lines",
				searchTerms: ["arrow-big-down-lines"]
			}, {
				title: "ti ti-arrow-big-down-lines-filled",
				searchTerms: ["arrow-big-down-lines-filled"]
			}, {
				title: "ti ti-arrow-big-left",
				searchTerms: ["arrow-big-left"]
			}, {
				title: "ti ti-arrow-big-left-filled",
				searchTerms: ["arrow-big-left-filled"]
			}, {
				title: "ti ti-arrow-big-left-line",
				searchTerms: ["arrow-big-left-line"]
			}, {
				title: "ti ti-arrow-big-left-line-filled",
				searchTerms: ["arrow-big-left-line-filled"]
			}, {
				title: "ti ti-arrow-big-left-lines",
				searchTerms: ["arrow-big-left-lines"]
			}, {
				title: "ti ti-arrow-big-left-lines-filled",
				searchTerms: ["arrow-big-left-lines-filled"]
			}, {
				title: "ti ti-arrow-big-right",
				searchTerms: ["arrow-big-right"]
			}, {
				title: "ti ti-arrow-big-right-filled",
				searchTerms: ["arrow-big-right-filled"]
			}, {
				title: "ti ti-arrow-big-right-line",
				searchTerms: ["arrow-big-right-line"]
			}, {
				title: "ti ti-arrow-big-right-line-filled",
				searchTerms: ["arrow-big-right-line-filled"]
			}, {
				title: "ti ti-arrow-big-right-lines",
				searchTerms: ["arrow-big-right-lines"]
			}, {
				title: "ti ti-arrow-big-right-lines-filled",
				searchTerms: ["arrow-big-right-lines-filled"]
			}, {
				title: "ti ti-arrow-big-up",
				searchTerms: ["arrow-big-up"]
			}, {
				title: "ti ti-arrow-big-up-filled",
				searchTerms: ["arrow-big-up-filled"]
			}, {
				title: "ti ti-arrow-big-up-line",
				searchTerms: ["arrow-big-up-line"]
			}, {
				title: "ti ti-arrow-big-up-line-filled",
				searchTerms: ["arrow-big-up-line-filled"]
			}, {
				title: "ti ti-arrow-big-up-lines",
				searchTerms: ["arrow-big-up-lines"]
			}, {
				title: "ti ti-arrow-big-up-lines-filled",
				searchTerms: ["arrow-big-up-lines-filled"]
			}, {
				title: "ti ti-arrow-bounce",
				searchTerms: ["arrow-bounce"]
			}, {
				title: "ti ti-arrow-capsule",
				searchTerms: ["arrow-capsule"]
			}, {
				title: "ti ti-arrow-curve-left",
				searchTerms: ["arrow-curve-left"]
			}, {
				title: "ti ti-arrow-curve-right",
				searchTerms: ["arrow-curve-right"]
			}, {
				title: "ti ti-arrow-down",
				searchTerms: ["arrow-down"]
			}, {
				title: "ti ti-arrow-down-bar",
				searchTerms: ["arrow-down-bar"]
			}, {
				title: "ti ti-arrow-down-circle",
				searchTerms: ["arrow-down-circle"]
			}, {
				title: "ti ti-arrow-down-from-arc",
				searchTerms: ["arrow-down-from-arc"]
			}, {
				title: "ti ti-arrow-down-left",
				searchTerms: ["arrow-down-left"]
			}, {
				title: "ti ti-arrow-down-left-circle",
				searchTerms: ["arrow-down-left-circle"]
			}, {
				title: "ti ti-arrow-down-rhombus",
				searchTerms: ["arrow-down-rhombus"]
			}, {
				title: "ti ti-arrow-down-right",
				searchTerms: ["arrow-down-right"]
			}, {
				title: "ti ti-arrow-down-right-circle",
				searchTerms: ["arrow-down-right-circle"]
			}, {
				title: "ti ti-arrow-down-square",
				searchTerms: ["arrow-down-square"]
			}, {
				title: "ti ti-arrow-down-tail",
				searchTerms: ["arrow-down-tail"]
			}, {
				title: "ti ti-arrow-down-to-arc",
				searchTerms: ["arrow-down-to-arc"]
			}, {
				title: "ti ti-arrow-elbow-left",
				searchTerms: ["arrow-elbow-left"]
			}, {
				title: "ti ti-arrow-elbow-right",
				searchTerms: ["arrow-elbow-right"]
			}, {
				title: "ti ti-arrow-fork",
				searchTerms: ["arrow-fork"]
			}, {
				title: "ti ti-arrow-forward",
				searchTerms: ["arrow-forward"]
			}, {
				title: "ti ti-arrow-forward-up",
				searchTerms: ["arrow-forward-up"]
			}, {
				title: "ti ti-arrow-forward-up-double",
				searchTerms: ["arrow-forward-up-double"]
			}, {
				title: "ti ti-arrow-guide",
				searchTerms: ["arrow-guide"]
			}, {
				title: "ti ti-arrow-iteration",
				searchTerms: ["arrow-iteration"]
			}, {
				title: "ti ti-arrow-left",
				searchTerms: ["arrow-left"]
			}, {
				title: "ti ti-arrow-left-bar",
				searchTerms: ["arrow-left-bar"]
			}, {
				title: "ti ti-arrow-left-circle",
				searchTerms: ["arrow-left-circle"]
			}, {
				title: "ti ti-arrow-left-from-arc",
				searchTerms: ["arrow-left-from-arc"]
			}, {
				title: "ti ti-arrow-left-rhombus",
				searchTerms: ["arrow-left-rhombus"]
			}, {
				title: "ti ti-arrow-left-right",
				searchTerms: ["arrow-left-right"]
			}, {
				title: "ti ti-arrow-left-square",
				searchTerms: ["arrow-left-square"]
			}, {
				title: "ti ti-arrow-left-tail",
				searchTerms: ["arrow-left-tail"]
			}, {
				title: "ti ti-arrow-left-to-arc",
				searchTerms: ["arrow-left-to-arc"]
			}, {
				title: "ti ti-arrow-loop-left",
				searchTerms: ["arrow-loop-left"]
			}, {
				title: "ti ti-arrow-loop-left-2",
				searchTerms: ["arrow-loop-left-2"]
			}, {
				title: "ti ti-arrow-loop-right",
				searchTerms: ["arrow-loop-right"]
			}, {
				title: "ti ti-arrow-loop-right-2",
				searchTerms: ["arrow-loop-right-2"]
			}, {
				title: "ti ti-arrow-merge",
				searchTerms: ["arrow-merge"]
			}, {
				title: "ti ti-arrow-merge-alt-left",
				searchTerms: ["arrow-merge-alt-left"]
			}, {
				title: "ti ti-arrow-merge-alt-right",
				searchTerms: ["arrow-merge-alt-right"]
			}, {
				title: "ti ti-arrow-merge-both",
				searchTerms: ["arrow-merge-both"]
			}, {
				title: "ti ti-arrow-merge-left",
				searchTerms: ["arrow-merge-left"]
			}, {
				title: "ti ti-arrow-merge-right",
				searchTerms: ["arrow-merge-right"]
			}, {
				title: "ti ti-arrow-move-down",
				searchTerms: ["arrow-move-down"]
			}, {
				title: "ti ti-arrow-move-left",
				searchTerms: ["arrow-move-left"]
			}, {
				title: "ti ti-arrow-move-right",
				searchTerms: ["arrow-move-right"]
			}, {
				title: "ti ti-arrow-move-up",
				searchTerms: ["arrow-move-up"]
			}, {
				title: "ti ti-arrow-narrow-down",
				searchTerms: ["arrow-narrow-down"]
			}, {
				title: "ti ti-arrow-narrow-left",
				searchTerms: ["arrow-narrow-left"]
			}, {
				title: "ti ti-arrow-narrow-right",
				searchTerms: ["arrow-narrow-right"]
			}, {
				title: "ti ti-arrow-narrow-up",
				searchTerms: ["arrow-narrow-up"]
			}, {
				title: "ti ti-arrow-ramp-left",
				searchTerms: ["arrow-ramp-left"]
			}, {
				title: "ti ti-arrow-ramp-left-2",
				searchTerms: ["arrow-ramp-left-2"]
			}, {
				title: "ti ti-arrow-ramp-left-3",
				searchTerms: ["arrow-ramp-left-3"]
			}, {
				title: "ti ti-arrow-ramp-right",
				searchTerms: ["arrow-ramp-right"]
			}, {
				title: "ti ti-arrow-ramp-right-2",
				searchTerms: ["arrow-ramp-right-2"]
			}, {
				title: "ti ti-arrow-ramp-right-3",
				searchTerms: ["arrow-ramp-right-3"]
			}, {
				title: "ti ti-arrow-right",
				searchTerms: ["arrow-right"]
			}, {
				title: "ti ti-arrow-right-bar",
				searchTerms: ["arrow-right-bar"]
			}, {
				title: "ti ti-arrow-right-circle",
				searchTerms: ["arrow-right-circle"]
			}, {
				title: "ti ti-arrow-right-from-arc",
				searchTerms: ["arrow-right-from-arc"]
			}, {
				title: "ti ti-arrow-right-rhombus",
				searchTerms: ["arrow-right-rhombus"]
			}, {
				title: "ti ti-arrow-right-square",
				searchTerms: ["arrow-right-square"]
			}, {
				title: "ti ti-arrow-right-tail",
				searchTerms: ["arrow-right-tail"]
			}, {
				title: "ti ti-arrow-right-to-arc",
				searchTerms: ["arrow-right-to-arc"]
			}, {
				title: "ti ti-arrow-rotary-first-left",
				searchTerms: ["arrow-rotary-first-left"]
			}, {
				title: "ti ti-arrow-rotary-first-right",
				searchTerms: ["arrow-rotary-first-right"]
			}, {
				title: "ti ti-arrow-rotary-last-left",
				searchTerms: ["arrow-rotary-last-left"]
			}, {
				title: "ti ti-arrow-rotary-last-right",
				searchTerms: ["arrow-rotary-last-right"]
			}, {
				title: "ti ti-arrow-rotary-left",
				searchTerms: ["arrow-rotary-left"]
			}, {
				title: "ti ti-arrow-rotary-right",
				searchTerms: ["arrow-rotary-right"]
			}, {
				title: "ti ti-arrow-rotary-straight",
				searchTerms: ["arrow-rotary-straight"]
			}, {
				title: "ti ti-arrow-roundabout-left",
				searchTerms: ["arrow-roundabout-left"]
			}, {
				title: "ti ti-arrow-roundabout-right",
				searchTerms: ["arrow-roundabout-right"]
			}, {
				title: "ti ti-arrow-sharp-turn-left",
				searchTerms: ["arrow-sharp-turn-left"]
			}, {
				title: "ti ti-arrow-sharp-turn-right",
				searchTerms: ["arrow-sharp-turn-right"]
			}, {
				title: "ti ti-arrow-up",
				searchTerms: ["arrow-up"]
			}, {
				title: "ti ti-arrow-up-bar",
				searchTerms: ["arrow-up-bar"]
			}, {
				title: "ti ti-arrow-up-circle",
				searchTerms: ["arrow-up-circle"]
			}, {
				title: "ti ti-arrow-up-from-arc",
				searchTerms: ["arrow-up-from-arc"]
			}, {
				title: "ti ti-arrow-up-left",
				searchTerms: ["arrow-up-left"]
			}, {
				title: "ti ti-arrow-up-left-circle",
				searchTerms: ["arrow-up-left-circle"]
			}, {
				title: "ti ti-arrow-up-rhombus",
				searchTerms: ["arrow-up-rhombus"]
			}, {
				title: "ti ti-arrow-up-right",
				searchTerms: ["arrow-up-right"]
			}, {
				title: "ti ti-arrow-up-right-circle",
				searchTerms: ["arrow-up-right-circle"]
			}, {
				title: "ti ti-arrow-up-square",
				searchTerms: ["arrow-up-square"]
			}, {
				title: "ti ti-arrow-up-tail",
				searchTerms: ["arrow-up-tail"]
			}, {
				title: "ti ti-arrow-up-to-arc",
				searchTerms: ["arrow-up-to-arc"]
			}, {
				title: "ti ti-arrow-wave-left-down",
				searchTerms: ["arrow-wave-left-down"]
			}, {
				title: "ti ti-arrow-wave-left-up",
				searchTerms: ["arrow-wave-left-up"]
			}, {
				title: "ti ti-arrow-wave-right-down",
				searchTerms: ["arrow-wave-right-down"]
			}, {
				title: "ti ti-arrow-wave-right-up",
				searchTerms: ["arrow-wave-right-up"]
			}, {
				title: "ti ti-arrow-zig-zag",
				searchTerms: ["arrow-zig-zag"]
			}, {
				title: "ti ti-arrows-cross",
				searchTerms: ["arrows-cross"]
			}, {
				title: "ti ti-arrows-diagonal",
				searchTerms: ["arrows-diagonal"]
			}, {
				title: "ti ti-arrows-diagonal-2",
				searchTerms: ["arrows-diagonal-2"]
			}, {
				title: "ti ti-arrows-diagonal-minimize",
				searchTerms: ["arrows-diagonal-minimize"]
			}, {
				title: "ti ti-arrows-diagonal-minimize-2",
				searchTerms: ["arrows-diagonal-minimize-2"]
			}, {
				title: "ti ti-arrows-diff",
				searchTerms: ["arrows-diff"]
			}, {
				title: "ti ti-arrows-double-ne-sw",
				searchTerms: ["arrows-double-ne-sw"]
			}, {
				title: "ti ti-arrows-double-nw-se",
				searchTerms: ["arrows-double-nw-se"]
			}, {
				title: "ti ti-arrows-double-se-nw",
				searchTerms: ["arrows-double-se-nw"]
			}, {
				title: "ti ti-arrows-double-sw-ne",
				searchTerms: ["arrows-double-sw-ne"]
			}, {
				title: "ti ti-arrows-down",
				searchTerms: ["arrows-down"]
			}, {
				title: "ti ti-arrows-down-up",
				searchTerms: ["arrows-down-up"]
			}, {
				title: "ti ti-arrows-exchange",
				searchTerms: ["arrows-exchange"]
			}, {
				title: "ti ti-arrows-exchange-2",
				searchTerms: ["arrows-exchange-2"]
			}, {
				title: "ti ti-arrows-horizontal",
				searchTerms: ["arrows-horizontal"]
			}, {
				title: "ti ti-arrows-join",
				searchTerms: ["arrows-join"]
			}, {
				title: "ti ti-arrows-join-2",
				searchTerms: ["arrows-join-2"]
			}, {
				title: "ti ti-arrows-left",
				searchTerms: ["arrows-left"]
			}, {
				title: "ti ti-arrows-left-down",
				searchTerms: ["arrows-left-down"]
			}, {
				title: "ti ti-arrows-left-right",
				searchTerms: ["arrows-left-right"]
			}, {
				title: "ti ti-arrows-maximize",
				searchTerms: ["arrows-maximize"]
			}, {
				title: "ti ti-arrows-minimize",
				searchTerms: ["arrows-minimize"]
			}, {
				title: "ti ti-arrows-move",
				searchTerms: ["arrows-move"]
			}, {
				title: "ti ti-arrows-move-horizontal",
				searchTerms: ["arrows-move-horizontal"]
			}, {
				title: "ti ti-arrows-move-vertical",
				searchTerms: ["arrows-move-vertical"]
			}, {
				title: "ti ti-arrows-random",
				searchTerms: ["arrows-random"]
			}, {
				title: "ti ti-arrows-right",
				searchTerms: ["arrows-right"]
			}, {
				title: "ti ti-arrows-right-down",
				searchTerms: ["arrows-right-down"]
			}, {
				title: "ti ti-arrows-right-left",
				searchTerms: ["arrows-right-left"]
			}, {
				title: "ti ti-arrows-shuffle",
				searchTerms: ["arrows-shuffle"]
			}, {
				title: "ti ti-arrows-shuffle-2",
				searchTerms: ["arrows-shuffle-2"]
			}, {
				title: "ti ti-arrows-sort",
				searchTerms: ["arrows-sort"]
			}, {
				title: "ti ti-arrows-split",
				searchTerms: ["arrows-split"]
			}, {
				title: "ti ti-arrows-split-2",
				searchTerms: ["arrows-split-2"]
			}, {
				title: "ti ti-arrows-transfer-down",
				searchTerms: ["arrows-transfer-down"]
			}, {
				title: "ti ti-arrows-transfer-up",
				searchTerms: ["arrows-transfer-up"]
			}, {
				title: "ti ti-arrows-up",
				searchTerms: ["arrows-up"]
			}, {
				title: "ti ti-arrows-up-down",
				searchTerms: ["arrows-up-down"]
			}, {
				title: "ti ti-arrows-up-left",
				searchTerms: ["arrows-up-left"]
			}, {
				title: "ti ti-arrows-up-right",
				searchTerms: ["arrows-up-right"]
			}, {
				title: "ti ti-arrows-vertical",
				searchTerms: ["arrows-vertical"]
			}, {
				title: "ti ti-artboard",
				searchTerms: ["artboard"]
			}, {
				title: "ti ti-artboard-filled",
				searchTerms: ["artboard-filled"]
			}, {
				title: "ti ti-artboard-off",
				searchTerms: ["artboard-off"]
			}, {
				title: "ti ti-article",
				searchTerms: ["article"]
			}, {
				title: "ti ti-article-filled",
				searchTerms: ["article-filled"]
			}, {
				title: "ti ti-article-off",
				searchTerms: ["article-off"]
			}, {
				title: "ti ti-aspect-ratio",
				searchTerms: ["aspect-ratio"]
			}, {
				title: "ti ti-aspect-ratio-filled",
				searchTerms: ["aspect-ratio-filled"]
			}, {
				title: "ti ti-aspect-ratio-off",
				searchTerms: ["aspect-ratio-off"]
			}, {
				title: "ti ti-assembly",
				searchTerms: ["assembly"]
			}, {
				title: "ti ti-assembly-filled",
				searchTerms: ["assembly-filled"]
			}, {
				title: "ti ti-assembly-off",
				searchTerms: ["assembly-off"]
			}, {
				title: "ti ti-asset",
				searchTerms: ["asset"]
			}, {
				title: "ti ti-asset-filled",
				searchTerms: ["asset-filled"]
			}, {
				title: "ti ti-asterisk",
				searchTerms: ["asterisk"]
			}, {
				title: "ti ti-asterisk-simple",
				searchTerms: ["asterisk-simple"]
			}, {
				title: "ti ti-at",
				searchTerms: ["at"]
			}, {
				title: "ti ti-at-off",
				searchTerms: ["at-off"]
			}, {
				title: "ti ti-atom",
				searchTerms: ["atom"]
			}, {
				title: "ti ti-atom-2",
				searchTerms: ["atom-2"]
			}, {
				title: "ti ti-atom-2-filled",
				searchTerms: ["atom-2-filled"]
			}, {
				title: "ti ti-atom-off",
				searchTerms: ["atom-off"]
			}, {
				title: "ti ti-augmented-reality",
				searchTerms: ["augmented-reality"]
			}, {
				title: "ti ti-augmented-reality-2",
				searchTerms: ["augmented-reality-2"]
			}, {
				title: "ti ti-augmented-reality-off",
				searchTerms: ["augmented-reality-off"]
			}, {
				title: "ti ti-auth-2fa",
				searchTerms: ["auth-2fa"]
			}, {
				title: "ti ti-automatic-gearbox",
				searchTerms: ["automatic-gearbox"]
			}, {
				title: "ti ti-avocado",
				searchTerms: ["avocado"]
			}, {
				title: "ti ti-award",
				searchTerms: ["award"]
			}, {
				title: "ti ti-award-filled",
				searchTerms: ["award-filled"]
			}, {
				title: "ti ti-award-off",
				searchTerms: ["award-off"]
			}, {
				title: "ti ti-axe",
				searchTerms: ["axe"]
			}, {
				title: "ti ti-axis-x",
				searchTerms: ["axis-x"]
			}, {
				title: "ti ti-axis-y",
				searchTerms: ["axis-y"]
			}, {
				title: "ti ti-baby-bottle",
				searchTerms: ["baby-bottle"]
			}, {
				title: "ti ti-baby-carriage",
				searchTerms: ["baby-carriage"]
			}, {
				title: "ti ti-baby-carriage-filled",
				searchTerms: ["baby-carriage-filled"]
			}, {
				title: "ti ti-background",
				searchTerms: ["background"]
			}, {
				title: "ti ti-backhoe",
				searchTerms: ["backhoe"]
			}, {
				title: "ti ti-backpack",
				searchTerms: ["backpack"]
			}, {
				title: "ti ti-backpack-off",
				searchTerms: ["backpack-off"]
			}, {
				title: "ti ti-backslash",
				searchTerms: ["backslash"]
			}, {
				title: "ti ti-backspace",
				searchTerms: ["backspace"]
			}, {
				title: "ti ti-backspace-filled",
				searchTerms: ["backspace-filled"]
			}, {
				title: "ti ti-badge",
				searchTerms: ["badge"]
			}, {
				title: "ti ti-badge-3d",
				searchTerms: ["badge-3d"]
			}, {
				title: "ti ti-badge-3d-filled",
				searchTerms: ["badge-3d-filled"]
			}, {
				title: "ti ti-badge-4k",
				searchTerms: ["badge-4k"]
			}, {
				title: "ti ti-badge-4k-filled",
				searchTerms: ["badge-4k-filled"]
			}, {
				title: "ti ti-badge-8k",
				searchTerms: ["badge-8k"]
			}, {
				title: "ti ti-badge-8k-filled",
				searchTerms: ["badge-8k-filled"]
			}, {
				title: "ti ti-badge-ad",
				searchTerms: ["badge-ad"]
			}, {
				title: "ti ti-badge-ad-filled",
				searchTerms: ["badge-ad-filled"]
			}, {
				title: "ti ti-badge-ad-off",
				searchTerms: ["badge-ad-off"]
			}, {
				title: "ti ti-badge-ar",
				searchTerms: ["badge-ar"]
			}, {
				title: "ti ti-badge-ar-filled",
				searchTerms: ["badge-ar-filled"]
			}, {
				title: "ti ti-badge-cc",
				searchTerms: ["badge-cc"]
			}, {
				title: "ti ti-badge-cc-filled",
				searchTerms: ["badge-cc-filled"]
			}, {
				title: "ti ti-badge-filled",
				searchTerms: ["badge-filled"]
			}, {
				title: "ti ti-badge-hd",
				searchTerms: ["badge-hd"]
			}, {
				title: "ti ti-badge-hd-filled",
				searchTerms: ["badge-hd-filled"]
			}, {
				title: "ti ti-badge-off",
				searchTerms: ["badge-off"]
			}, {
				title: "ti ti-badge-sd",
				searchTerms: ["badge-sd"]
			}, {
				title: "ti ti-badge-sd-filled",
				searchTerms: ["badge-sd-filled"]
			}, {
				title: "ti ti-badge-tm",
				searchTerms: ["badge-tm"]
			}, {
				title: "ti ti-badge-tm-filled",
				searchTerms: ["badge-tm-filled"]
			}, {
				title: "ti ti-badge-vo",
				searchTerms: ["badge-vo"]
			}, {
				title: "ti ti-badge-vo-filled",
				searchTerms: ["badge-vo-filled"]
			}, {
				title: "ti ti-badge-vr",
				searchTerms: ["badge-vr"]
			}, {
				title: "ti ti-badge-vr-filled",
				searchTerms: ["badge-vr-filled"]
			}, {
				title: "ti ti-badge-wc",
				searchTerms: ["badge-wc"]
			}, {
				title: "ti ti-badge-wc-filled",
				searchTerms: ["badge-wc-filled"]
			}, {
				title: "ti ti-badges",
				searchTerms: ["badges"]
			}, {
				title: "ti ti-badges-filled",
				searchTerms: ["badges-filled"]
			}, {
				title: "ti ti-badges-off",
				searchTerms: ["badges-off"]
			}, {
				title: "ti ti-baguette",
				searchTerms: ["baguette"]
			}, {
				title: "ti ti-ball-american-football",
				searchTerms: ["ball-american-football"]
			}, {
				title: "ti ti-ball-american-football-off",
				searchTerms: ["ball-american-football-off"]
			}, {
				title: "ti ti-ball-baseball",
				searchTerms: ["ball-baseball"]
			}, {
				title: "ti ti-ball-basketball",
				searchTerms: ["ball-basketball"]
			}, {
				title: "ti ti-ball-bowling",
				searchTerms: ["ball-bowling"]
			}, {
				title: "ti ti-ball-football",
				searchTerms: ["ball-football"]
			}, {
				title: "ti ti-ball-football-off",
				searchTerms: ["ball-football-off"]
			}, {
				title: "ti ti-ball-tennis",
				searchTerms: ["ball-tennis"]
			}, {
				title: "ti ti-ball-volleyball",
				searchTerms: ["ball-volleyball"]
			}, {
				title: "ti ti-balloon",
				searchTerms: ["balloon"]
			}, {
				title: "ti ti-balloon-filled",
				searchTerms: ["balloon-filled"]
			}, {
				title: "ti ti-balloon-off",
				searchTerms: ["balloon-off"]
			}, {
				title: "ti ti-ballpen",
				searchTerms: ["ballpen"]
			}, {
				title: "ti ti-ballpen-filled",
				searchTerms: ["ballpen-filled"]
			}, {
				title: "ti ti-ballpen-off",
				searchTerms: ["ballpen-off"]
			}, {
				title: "ti ti-ban",
				searchTerms: ["ban"]
			}, {
				title: "ti ti-bandage",
				searchTerms: ["bandage"]
			}, {
				title: "ti ti-bandage-filled",
				searchTerms: ["bandage-filled"]
			}, {
				title: "ti ti-bandage-off",
				searchTerms: ["bandage-off"]
			}, {
				title: "ti ti-barbell",
				searchTerms: ["barbell"]
			}, {
				title: "ti ti-barbell-filled",
				searchTerms: ["barbell-filled"]
			}, {
				title: "ti ti-barbell-off",
				searchTerms: ["barbell-off"]
			}, {
				title: "ti ti-barcode",
				searchTerms: ["barcode"]
			}, {
				title: "ti ti-barcode-off",
				searchTerms: ["barcode-off"]
			}, {
				title: "ti ti-barrel",
				searchTerms: ["barrel"]
			}, {
				title: "ti ti-barrel-off",
				searchTerms: ["barrel-off"]
			}, {
				title: "ti ti-barrier-block",
				searchTerms: ["barrier-block"]
			}, {
				title: "ti ti-barrier-block-filled",
				searchTerms: ["barrier-block-filled"]
			}, {
				title: "ti ti-barrier-block-off",
				searchTerms: ["barrier-block-off"]
			}, {
				title: "ti ti-baseline",
				searchTerms: ["baseline"]
			}, {
				title: "ti ti-baseline-density-large",
				searchTerms: ["baseline-density-large"]
			}, {
				title: "ti ti-baseline-density-medium",
				searchTerms: ["baseline-density-medium"]
			}, {
				title: "ti ti-baseline-density-small",
				searchTerms: ["baseline-density-small"]
			}, {
				title: "ti ti-basket",
				searchTerms: ["basket"]
			}, {
				title: "ti ti-basket-bolt",
				searchTerms: ["basket-bolt"]
			}, {
				title: "ti ti-basket-cancel",
				searchTerms: ["basket-cancel"]
			}, {
				title: "ti ti-basket-check",
				searchTerms: ["basket-check"]
			}, {
				title: "ti ti-basket-code",
				searchTerms: ["basket-code"]
			}, {
				title: "ti ti-basket-cog",
				searchTerms: ["basket-cog"]
			}, {
				title: "ti ti-basket-discount",
				searchTerms: ["basket-discount"]
			}, {
				title: "ti ti-basket-dollar",
				searchTerms: ["basket-dollar"]
			}, {
				title: "ti ti-basket-down",
				searchTerms: ["basket-down"]
			}, {
				title: "ti ti-basket-exclamation",
				searchTerms: ["basket-exclamation"]
			}, {
				title: "ti ti-basket-filled",
				searchTerms: ["basket-filled"]
			}, {
				title: "ti ti-basket-heart",
				searchTerms: ["basket-heart"]
			}, {
				title: "ti ti-basket-minus",
				searchTerms: ["basket-minus"]
			}, {
				title: "ti ti-basket-off",
				searchTerms: ["basket-off"]
			}, {
				title: "ti ti-basket-pause",
				searchTerms: ["basket-pause"]
			}, {
				title: "ti ti-basket-pin",
				searchTerms: ["basket-pin"]
			}, {
				title: "ti ti-basket-plus",
				searchTerms: ["basket-plus"]
			}, {
				title: "ti ti-basket-question",
				searchTerms: ["basket-question"]
			}, {
				title: "ti ti-basket-search",
				searchTerms: ["basket-search"]
			}, {
				title: "ti ti-basket-share",
				searchTerms: ["basket-share"]
			}, {
				title: "ti ti-basket-star",
				searchTerms: ["basket-star"]
			}, {
				title: "ti ti-basket-up",
				searchTerms: ["basket-up"]
			}, {
				title: "ti ti-basket-x",
				searchTerms: ["basket-x"]
			}, {
				title: "ti ti-bat",
				searchTerms: ["bat"]
			}, {
				title: "ti ti-bath",
				searchTerms: ["bath"]
			}, {
				title: "ti ti-bath-filled",
				searchTerms: ["bath-filled"]
			}, {
				title: "ti ti-bath-off",
				searchTerms: ["bath-off"]
			}, {
				title: "ti ti-battery",
				searchTerms: ["battery"]
			}, {
				title: "ti ti-battery-1",
				searchTerms: ["battery-1"]
			}, {
				title: "ti ti-battery-1-filled",
				searchTerms: ["battery-1-filled"]
			}, {
				title: "ti ti-battery-2",
				searchTerms: ["battery-2"]
			}, {
				title: "ti ti-battery-2-filled",
				searchTerms: ["battery-2-filled"]
			}, {
				title: "ti ti-battery-3",
				searchTerms: ["battery-3"]
			}, {
				title: "ti ti-battery-3-filled",
				searchTerms: ["battery-3-filled"]
			}, {
				title: "ti ti-battery-4",
				searchTerms: ["battery-4"]
			}, {
				title: "ti ti-battery-4-filled",
				searchTerms: ["battery-4-filled"]
			}, {
				title: "ti ti-battery-automotive",
				searchTerms: ["battery-automotive"]
			}, {
				title: "ti ti-battery-charging",
				searchTerms: ["battery-charging"]
			}, {
				title: "ti ti-battery-charging-2",
				searchTerms: ["battery-charging-2"]
			}, {
				title: "ti ti-battery-eco",
				searchTerms: ["battery-eco"]
			}, {
				title: "ti ti-battery-filled",
				searchTerms: ["battery-filled"]
			}, {
				title: "ti ti-battery-off",
				searchTerms: ["battery-off"]
			}, {
				title: "ti ti-beach",
				searchTerms: ["beach"]
			}, {
				title: "ti ti-beach-off",
				searchTerms: ["beach-off"]
			}, {
				title: "ti ti-bed",
				searchTerms: ["bed"]
			}, {
				title: "ti ti-bed-filled",
				searchTerms: ["bed-filled"]
			}, {
				title: "ti ti-bed-flat",
				searchTerms: ["bed-flat"]
			}, {
				title: "ti ti-bed-flat-filled",
				searchTerms: ["bed-flat-filled"]
			}, {
				title: "ti ti-bed-off",
				searchTerms: ["bed-off"]
			}, {
				title: "ti ti-beer",
				searchTerms: ["beer"]
			}, {
				title: "ti ti-beer-filled",
				searchTerms: ["beer-filled"]
			}, {
				title: "ti ti-beer-off",
				searchTerms: ["beer-off"]
			}, {
				title: "ti ti-bell",
				searchTerms: ["bell"]
			}, {
				title: "ti ti-bell-bolt",
				searchTerms: ["bell-bolt"]
			}, {
				title: "ti ti-bell-cancel",
				searchTerms: ["bell-cancel"]
			}, {
				title: "ti ti-bell-check",
				searchTerms: ["bell-check"]
			}, {
				title: "ti ti-bell-code",
				searchTerms: ["bell-code"]
			}, {
				title: "ti ti-bell-cog",
				searchTerms: ["bell-cog"]
			}, {
				title: "ti ti-bell-dollar",
				searchTerms: ["bell-dollar"]
			}, {
				title: "ti ti-bell-down",
				searchTerms: ["bell-down"]
			}, {
				title: "ti ti-bell-exclamation",
				searchTerms: ["bell-exclamation"]
			}, {
				title: "ti ti-bell-filled",
				searchTerms: ["bell-filled"]
			}, {
				title: "ti ti-bell-heart",
				searchTerms: ["bell-heart"]
			}, {
				title: "ti ti-bell-minus",
				searchTerms: ["bell-minus"]
			}, {
				title: "ti ti-bell-minus-filled",
				searchTerms: ["bell-minus-filled"]
			}, {
				title: "ti ti-bell-off",
				searchTerms: ["bell-off"]
			}, {
				title: "ti ti-bell-pause",
				searchTerms: ["bell-pause"]
			}, {
				title: "ti ti-bell-pin",
				searchTerms: ["bell-pin"]
			}, {
				title: "ti ti-bell-plus",
				searchTerms: ["bell-plus"]
			}, {
				title: "ti ti-bell-plus-filled",
				searchTerms: ["bell-plus-filled"]
			}, {
				title: "ti ti-bell-question",
				searchTerms: ["bell-question"]
			}, {
				title: "ti ti-bell-ringing",
				searchTerms: ["bell-ringing"]
			}, {
				title: "ti ti-bell-ringing-2",
				searchTerms: ["bell-ringing-2"]
			}, {
				title: "ti ti-bell-ringing-2-filled",
				searchTerms: ["bell-ringing-2-filled"]
			}, {
				title: "ti ti-bell-ringing-filled",
				searchTerms: ["bell-ringing-filled"]
			}, {
				title: "ti ti-bell-school",
				searchTerms: ["bell-school"]
			}, {
				title: "ti ti-bell-search",
				searchTerms: ["bell-search"]
			}, {
				title: "ti ti-bell-share",
				searchTerms: ["bell-share"]
			}, {
				title: "ti ti-bell-star",
				searchTerms: ["bell-star"]
			}, {
				title: "ti ti-bell-up",
				searchTerms: ["bell-up"]
			}, {
				title: "ti ti-bell-x",
				searchTerms: ["bell-x"]
			}, {
				title: "ti ti-bell-x-filled",
				searchTerms: ["bell-x-filled"]
			}, {
				title: "ti ti-bell-z",
				searchTerms: ["bell-z"]
			}, {
				title: "ti ti-bell-z-filled",
				searchTerms: ["bell-z-filled"]
			}, {
				title: "ti ti-beta",
				searchTerms: ["beta"]
			}, {
				title: "ti ti-bible",
				searchTerms: ["bible"]
			}, {
				title: "ti ti-bike",
				searchTerms: ["bike"]
			}, {
				title: "ti ti-bike-off",
				searchTerms: ["bike-off"]
			}, {
				title: "ti ti-binary",
				searchTerms: ["binary"]
			}, {
				title: "ti ti-binary-off",
				searchTerms: ["binary-off"]
			}, {
				title: "ti ti-binary-tree",
				searchTerms: ["binary-tree"]
			}, {
				title: "ti ti-binary-tree-2",
				searchTerms: ["binary-tree-2"]
			}, {
				title: "ti ti-biohazard",
				searchTerms: ["biohazard"]
			}, {
				title: "ti ti-biohazard-filled",
				searchTerms: ["biohazard-filled"]
			}, {
				title: "ti ti-biohazard-off",
				searchTerms: ["biohazard-off"]
			}, {
				title: "ti ti-blade",
				searchTerms: ["blade"]
			}, {
				title: "ti ti-blade-filled",
				searchTerms: ["blade-filled"]
			}, {
				title: "ti ti-bleach",
				searchTerms: ["bleach"]
			}, {
				title: "ti ti-bleach-chlorine",
				searchTerms: ["bleach-chlorine"]
			}, {
				title: "ti ti-bleach-no-chlorine",
				searchTerms: ["bleach-no-chlorine"]
			}, {
				title: "ti ti-bleach-off",
				searchTerms: ["bleach-off"]
			}, {
				title: "ti ti-blend-mode",
				searchTerms: ["blend-mode"]
			}, {
				title: "ti ti-blender",
				searchTerms: ["blender"]
			}, {
				title: "ti ti-blob",
				searchTerms: ["blob"]
			}, {
				title: "ti ti-blob-filled",
				searchTerms: ["blob-filled"]
			}, {
				title: "ti ti-blockquote",
				searchTerms: ["blockquote"]
			}, {
				title: "ti ti-bluetooth",
				searchTerms: ["bluetooth"]
			}, {
				title: "ti ti-bluetooth-connected",
				searchTerms: ["bluetooth-connected"]
			}, {
				title: "ti ti-bluetooth-off",
				searchTerms: ["bluetooth-off"]
			}, {
				title: "ti ti-bluetooth-x",
				searchTerms: ["bluetooth-x"]
			}, {
				title: "ti ti-blur",
				searchTerms: ["blur"]
			}, {
				title: "ti ti-blur-off",
				searchTerms: ["blur-off"]
			}, {
				title: "ti ti-bmp",
				searchTerms: ["bmp"]
			}, {
				title: "ti ti-body-scan",
				searchTerms: ["body-scan"]
			}, {
				title: "ti ti-bold",
				searchTerms: ["bold"]
			}, {
				title: "ti ti-bold-off",
				searchTerms: ["bold-off"]
			}, {
				title: "ti ti-bolt",
				searchTerms: ["bolt"]
			}, {
				title: "ti ti-bolt-off",
				searchTerms: ["bolt-off"]
			}, {
				title: "ti ti-bomb",
				searchTerms: ["bomb"]
			}, {
				title: "ti ti-bomb-filled",
				searchTerms: ["bomb-filled"]
			}, {
				title: "ti ti-bone",
				searchTerms: ["bone"]
			}, {
				title: "ti ti-bone-filled",
				searchTerms: ["bone-filled"]
			}, {
				title: "ti ti-bone-off",
				searchTerms: ["bone-off"]
			}, {
				title: "ti ti-bong",
				searchTerms: ["bong"]
			}, {
				title: "ti ti-bong-off",
				searchTerms: ["bong-off"]
			}, {
				title: "ti ti-book",
				searchTerms: ["book"]
			}, {
				title: "ti ti-book-2",
				searchTerms: ["book-2"]
			}, {
				title: "ti ti-book-download",
				searchTerms: ["book-download"]
			}, {
				title: "ti ti-book-filled",
				searchTerms: ["book-filled"]
			}, {
				title: "ti ti-book-off",
				searchTerms: ["book-off"]
			}, {
				title: "ti ti-book-upload",
				searchTerms: ["book-upload"]
			}, {
				title: "ti ti-bookmark",
				searchTerms: ["bookmark"]
			}, {
				title: "ti ti-bookmark-ai",
				searchTerms: ["bookmark-ai"]
			}, {
				title: "ti ti-bookmark-edit",
				searchTerms: ["bookmark-edit"]
			}, {
				title: "ti ti-bookmark-filled",
				searchTerms: ["bookmark-filled"]
			}, {
				title: "ti ti-bookmark-minus",
				searchTerms: ["bookmark-minus"]
			}, {
				title: "ti ti-bookmark-off",
				searchTerms: ["bookmark-off"]
			}, {
				title: "ti ti-bookmark-plus",
				searchTerms: ["bookmark-plus"]
			}, {
				title: "ti ti-bookmark-question",
				searchTerms: ["bookmark-question"]
			}, {
				title: "ti ti-bookmarks",
				searchTerms: ["bookmarks"]
			}, {
				title: "ti ti-bookmarks-filled",
				searchTerms: ["bookmarks-filled"]
			}, {
				title: "ti ti-bookmarks-off",
				searchTerms: ["bookmarks-off"]
			}, {
				title: "ti ti-books",
				searchTerms: ["books"]
			}, {
				title: "ti ti-books-off",
				searchTerms: ["books-off"]
			}, {
				title: "ti ti-boom",
				searchTerms: ["boom"]
			}, {
				title: "ti ti-boom-filled",
				searchTerms: ["boom-filled"]
			}, {
				title: "ti ti-border-all",
				searchTerms: ["border-all"]
			}, {
				title: "ti ti-border-bottom",
				searchTerms: ["border-bottom"]
			}, {
				title: "ti ti-border-bottom-plus",
				searchTerms: ["border-bottom-plus"]
			}, {
				title: "ti ti-border-corner-ios",
				searchTerms: ["border-corner-ios"]
			}, {
				title: "ti ti-border-corner-pill",
				searchTerms: ["border-corner-pill"]
			}, {
				title: "ti ti-border-corner-rounded",
				searchTerms: ["border-corner-rounded"]
			}, {
				title: "ti ti-border-corner-square",
				searchTerms: ["border-corner-square"]
			}, {
				title: "ti ti-border-corners",
				searchTerms: ["border-corners"]
			}, {
				title: "ti ti-border-horizontal",
				searchTerms: ["border-horizontal"]
			}, {
				title: "ti ti-border-inner",
				searchTerms: ["border-inner"]
			}, {
				title: "ti ti-border-left",
				searchTerms: ["border-left"]
			}, {
				title: "ti ti-border-left-plus",
				searchTerms: ["border-left-plus"]
			}, {
				title: "ti ti-border-none",
				searchTerms: ["border-none"]
			}, {
				title: "ti ti-border-outer",
				searchTerms: ["border-outer"]
			}, {
				title: "ti ti-border-radius",
				searchTerms: ["border-radius"]
			}, {
				title: "ti ti-border-right",
				searchTerms: ["border-right"]
			}, {
				title: "ti ti-border-right-plus",
				searchTerms: ["border-right-plus"]
			}, {
				title: "ti ti-border-sides",
				searchTerms: ["border-sides"]
			}, {
				title: "ti ti-border-style",
				searchTerms: ["border-style"]
			}, {
				title: "ti ti-border-style-2",
				searchTerms: ["border-style-2"]
			}, {
				title: "ti ti-border-top",
				searchTerms: ["border-top"]
			}, {
				title: "ti ti-border-top-plus",
				searchTerms: ["border-top-plus"]
			}, {
				title: "ti ti-border-vertical",
				searchTerms: ["border-vertical"]
			}, {
				title: "ti ti-bottle",
				searchTerms: ["bottle"]
			}, {
				title: "ti ti-bottle-filled",
				searchTerms: ["bottle-filled"]
			}, {
				title: "ti ti-bottle-off",
				searchTerms: ["bottle-off"]
			}, {
				title: "ti ti-bounce-left",
				searchTerms: ["bounce-left"]
			}, {
				title: "ti ti-bounce-left-filled",
				searchTerms: ["bounce-left-filled"]
			}, {
				title: "ti ti-bounce-right",
				searchTerms: ["bounce-right"]
			}, {
				title: "ti ti-bounce-right-filled",
				searchTerms: ["bounce-right-filled"]
			}, {
				title: "ti ti-bow",
				searchTerms: ["bow"]
			}, {
				title: "ti ti-bow-filled",
				searchTerms: ["bow-filled"]
			}, {
				title: "ti ti-bowl",
				searchTerms: ["bowl"]
			}, {
				title: "ti ti-bowl-chopsticks",
				searchTerms: ["bowl-chopsticks"]
			}, {
				title: "ti ti-bowl-chopsticks-filled",
				searchTerms: ["bowl-chopsticks-filled"]
			}, {
				title: "ti ti-bowl-filled",
				searchTerms: ["bowl-filled"]
			}, {
				title: "ti ti-bowl-spoon",
				searchTerms: ["bowl-spoon"]
			}, {
				title: "ti ti-bowl-spoon-filled",
				searchTerms: ["bowl-spoon-filled"]
			}, {
				title: "ti ti-box",
				searchTerms: ["box"]
			}, {
				title: "ti ti-box-align-bottom",
				searchTerms: ["box-align-bottom"]
			}, {
				title: "ti ti-box-align-bottom-filled",
				searchTerms: ["box-align-bottom-filled"]
			}, {
				title: "ti ti-box-align-bottom-left",
				searchTerms: ["box-align-bottom-left"]
			}, {
				title: "ti ti-box-align-bottom-left-filled",
				searchTerms: ["box-align-bottom-left-filled"]
			}, {
				title: "ti ti-box-align-bottom-right",
				searchTerms: ["box-align-bottom-right"]
			}, {
				title: "ti ti-box-align-bottom-right-filled",
				searchTerms: ["box-align-bottom-right-filled"]
			}, {
				title: "ti ti-box-align-left",
				searchTerms: ["box-align-left"]
			}, {
				title: "ti ti-box-align-left-filled",
				searchTerms: ["box-align-left-filled"]
			}, {
				title: "ti ti-box-align-right",
				searchTerms: ["box-align-right"]
			}, {
				title: "ti ti-box-align-right-filled",
				searchTerms: ["box-align-right-filled"]
			}, {
				title: "ti ti-box-align-top",
				searchTerms: ["box-align-top"]
			}, {
				title: "ti ti-box-align-top-filled",
				searchTerms: ["box-align-top-filled"]
			}, {
				title: "ti ti-box-align-top-left",
				searchTerms: ["box-align-top-left"]
			}, {
				title: "ti ti-box-align-top-left-filled",
				searchTerms: ["box-align-top-left-filled"]
			}, {
				title: "ti ti-box-align-top-right",
				searchTerms: ["box-align-top-right"]
			}, {
				title: "ti ti-box-align-top-right-filled",
				searchTerms: ["box-align-top-right-filled"]
			}, {
				title: "ti ti-box-margin",
				searchTerms: ["box-margin"]
			}, {
				title: "ti ti-box-model",
				searchTerms: ["box-model"]
			}, {
				title: "ti ti-box-model-2",
				searchTerms: ["box-model-2"]
			}, {
				title: "ti ti-box-model-2-off",
				searchTerms: ["box-model-2-off"]
			}, {
				title: "ti ti-box-model-off",
				searchTerms: ["box-model-off"]
			}, {
				title: "ti ti-box-multiple",
				searchTerms: ["box-multiple"]
			}, {
				title: "ti ti-box-multiple-0",
				searchTerms: ["box-multiple-0"]
			}, {
				title: "ti ti-box-multiple-1",
				searchTerms: ["box-multiple-1"]
			}, {
				title: "ti ti-box-multiple-2",
				searchTerms: ["box-multiple-2"]
			}, {
				title: "ti ti-box-multiple-3",
				searchTerms: ["box-multiple-3"]
			}, {
				title: "ti ti-box-multiple-4",
				searchTerms: ["box-multiple-4"]
			}, {
				title: "ti ti-box-multiple-5",
				searchTerms: ["box-multiple-5"]
			}, {
				title: "ti ti-box-multiple-6",
				searchTerms: ["box-multiple-6"]
			}, {
				title: "ti ti-box-multiple-7",
				searchTerms: ["box-multiple-7"]
			}, {
				title: "ti ti-box-multiple-8",
				searchTerms: ["box-multiple-8"]
			}, {
				title: "ti ti-box-multiple-9",
				searchTerms: ["box-multiple-9"]
			}, {
				title: "ti ti-box-off",
				searchTerms: ["box-off"]
			}, {
				title: "ti ti-box-padding",
				searchTerms: ["box-padding"]
			}, {
				title: "ti ti-braces",
				searchTerms: ["braces"]
			}, {
				title: "ti ti-braces-off",
				searchTerms: ["braces-off"]
			}, {
				title: "ti ti-brackets",
				searchTerms: ["brackets"]
			}, {
				title: "ti ti-brackets-angle",
				searchTerms: ["brackets-angle"]
			}, {
				title: "ti ti-brackets-angle-off",
				searchTerms: ["brackets-angle-off"]
			}, {
				title: "ti ti-brackets-contain",
				searchTerms: ["brackets-contain"]
			}, {
				title: "ti ti-brackets-contain-end",
				searchTerms: ["brackets-contain-end"]
			}, {
				title: "ti ti-brackets-contain-start",
				searchTerms: ["brackets-contain-start"]
			}, {
				title: "ti ti-brackets-off",
				searchTerms: ["brackets-off"]
			}, {
				title: "ti ti-braille",
				searchTerms: ["braille"]
			}, {
				title: "ti ti-brain",
				searchTerms: ["brain"]
			}, {
				title: "ti ti-brand-4chan",
				searchTerms: ["brand-4chan"]
			}, {
				title: "ti ti-brand-abstract",
				searchTerms: ["brand-abstract"]
			}, {
				title: "ti ti-brand-adobe",
				searchTerms: ["brand-adobe"]
			}, {
				title: "ti ti-brand-adonis-js",
				searchTerms: ["brand-adonis-js"]
			}, {
				title: "ti ti-brand-airbnb",
				searchTerms: ["brand-airbnb"]
			}, {
				title: "ti ti-brand-airtable",
				searchTerms: ["brand-airtable"]
			}, {
				title: "ti ti-brand-algolia",
				searchTerms: ["brand-algolia"]
			}, {
				title: "ti ti-brand-alipay",
				searchTerms: ["brand-alipay"]
			}, {
				title: "ti ti-brand-alpine-js",
				searchTerms: ["brand-alpine-js"]
			}, {
				title: "ti ti-brand-amazon",
				searchTerms: ["brand-amazon"]
			}, {
				title: "ti ti-brand-amd",
				searchTerms: ["brand-amd"]
			}, {
				title: "ti ti-brand-amigo",
				searchTerms: ["brand-amigo"]
			}, {
				title: "ti ti-brand-among-us",
				searchTerms: ["brand-among-us"]
			}, {
				title: "ti ti-brand-android",
				searchTerms: ["brand-android"]
			}, {
				title: "ti ti-brand-angular",
				searchTerms: ["brand-angular"]
			}, {
				title: "ti ti-brand-ansible",
				searchTerms: ["brand-ansible"]
			}, {
				title: "ti ti-brand-ao3",
				searchTerms: ["brand-ao3"]
			}, {
				title: "ti ti-brand-appgallery",
				searchTerms: ["brand-appgallery"]
			}, {
				title: "ti ti-brand-apple",
				searchTerms: ["brand-apple"]
			}, {
				title: "ti ti-brand-apple-arcade",
				searchTerms: ["brand-apple-arcade"]
			}, {
				title: "ti ti-brand-apple-filled",
				searchTerms: ["brand-apple-filled"]
			}, {
				title: "ti ti-brand-apple-podcast",
				searchTerms: ["brand-apple-podcast"]
			}, {
				title: "ti ti-brand-appstore",
				searchTerms: ["brand-appstore"]
			}, {
				title: "ti ti-brand-arc",
				searchTerms: ["brand-arc"]
			}, {
				title: "ti ti-brand-asana",
				searchTerms: ["brand-asana"]
			}, {
				title: "ti ti-brand-astro",
				searchTerms: ["brand-astro"]
			}, {
				title: "ti ti-brand-auth0",
				searchTerms: ["brand-auth0"]
			}, {
				title: "ti ti-brand-aws",
				searchTerms: ["brand-aws"]
			}, {
				title: "ti ti-brand-azure",
				searchTerms: ["brand-azure"]
			}, {
				title: "ti ti-brand-backbone",
				searchTerms: ["brand-backbone"]
			}, {
				title: "ti ti-brand-badoo",
				searchTerms: ["brand-badoo"]
			}, {
				title: "ti ti-brand-baidu",
				searchTerms: ["brand-baidu"]
			}, {
				title: "ti ti-brand-bandcamp",
				searchTerms: ["brand-bandcamp"]
			}, {
				title: "ti ti-brand-bandlab",
				searchTerms: ["brand-bandlab"]
			}, {
				title: "ti ti-brand-beats",
				searchTerms: ["brand-beats"]
			}, {
				title: "ti ti-brand-behance",
				searchTerms: ["brand-behance"]
			}, {
				title: "ti ti-brand-bilibili",
				searchTerms: ["brand-bilibili"]
			}, {
				title: "ti ti-brand-binance",
				searchTerms: ["brand-binance"]
			}, {
				title: "ti ti-brand-bing",
				searchTerms: ["brand-bing"]
			}, {
				title: "ti ti-brand-bitbucket",
				searchTerms: ["brand-bitbucket"]
			}, {
				title: "ti ti-brand-blackberry",
				searchTerms: ["brand-blackberry"]
			}, {
				title: "ti ti-brand-blender",
				searchTerms: ["brand-blender"]
			}, {
				title: "ti ti-brand-blogger",
				searchTerms: ["brand-blogger"]
			}, {
				title: "ti ti-brand-bluesky",
				searchTerms: ["brand-bluesky"]
			}, {
				title: "ti ti-brand-booking",
				searchTerms: ["brand-booking"]
			}, {
				title: "ti ti-brand-bootstrap",
				searchTerms: ["brand-bootstrap"]
			}, {
				title: "ti ti-brand-bulma",
				searchTerms: ["brand-bulma"]
			}, {
				title: "ti ti-brand-bumble",
				searchTerms: ["brand-bumble"]
			}, {
				title: "ti ti-brand-bunpo",
				searchTerms: ["brand-bunpo"]
			}, {
				title: "ti ti-brand-c-sharp",
				searchTerms: ["brand-c-sharp"]
			}, {
				title: "ti ti-brand-cake",
				searchTerms: ["brand-cake"]
			}, {
				title: "ti ti-brand-cakephp",
				searchTerms: ["brand-cakephp"]
			}, {
				title: "ti ti-brand-campaignmonitor",
				searchTerms: ["brand-campaignmonitor"]
			}, {
				title: "ti ti-brand-carbon",
				searchTerms: ["brand-carbon"]
			}, {
				title: "ti ti-brand-cashapp",
				searchTerms: ["brand-cashapp"]
			}, {
				title: "ti ti-brand-chrome",
				searchTerms: ["brand-chrome"]
			}, {
				title: "ti ti-brand-cinema-4d",
				searchTerms: ["brand-cinema-4d"]
			}, {
				title: "ti ti-brand-citymapper",
				searchTerms: ["brand-citymapper"]
			}, {
				title: "ti ti-brand-cloudflare",
				searchTerms: ["brand-cloudflare"]
			}, {
				title: "ti ti-brand-codecov",
				searchTerms: ["brand-codecov"]
			}, {
				title: "ti ti-brand-codepen",
				searchTerms: ["brand-codepen"]
			}, {
				title: "ti ti-brand-codesandbox",
				searchTerms: ["brand-codesandbox"]
			}, {
				title: "ti ti-brand-cohost",
				searchTerms: ["brand-cohost"]
			}, {
				title: "ti ti-brand-coinbase",
				searchTerms: ["brand-coinbase"]
			}, {
				title: "ti ti-brand-comedy-central",
				searchTerms: ["brand-comedy-central"]
			}, {
				title: "ti ti-brand-coreos",
				searchTerms: ["brand-coreos"]
			}, {
				title: "ti ti-brand-couchdb",
				searchTerms: ["brand-couchdb"]
			}, {
				title: "ti ti-brand-couchsurfing",
				searchTerms: ["brand-couchsurfing"]
			}, {
				title: "ti ti-brand-cpp",
				searchTerms: ["brand-cpp"]
			}, {
				title: "ti ti-brand-craft",
				searchTerms: ["brand-craft"]
			}, {
				title: "ti ti-brand-crunchbase",
				searchTerms: ["brand-crunchbase"]
			}, {
				title: "ti ti-brand-css3",
				searchTerms: ["brand-css3"]
			}, {
				title: "ti ti-brand-ctemplar",
				searchTerms: ["brand-ctemplar"]
			}, {
				title: "ti ti-brand-cucumber",
				searchTerms: ["brand-cucumber"]
			}, {
				title: "ti ti-brand-cupra",
				searchTerms: ["brand-cupra"]
			}, {
				title: "ti ti-brand-cypress",
				searchTerms: ["brand-cypress"]
			}, {
				title: "ti ti-brand-d3",
				searchTerms: ["brand-d3"]
			}, {
				title: "ti ti-brand-databricks",
				searchTerms: ["brand-databricks"]
			}, {
				title: "ti ti-brand-days-counter",
				searchTerms: ["brand-days-counter"]
			}, {
				title: "ti ti-brand-dcos",
				searchTerms: ["brand-dcos"]
			}, {
				title: "ti ti-brand-debian",
				searchTerms: ["brand-debian"]
			}, {
				title: "ti ti-brand-deezer",
				searchTerms: ["brand-deezer"]
			}, {
				title: "ti ti-brand-deliveroo",
				searchTerms: ["brand-deliveroo"]
			}, {
				title: "ti ti-brand-deno",
				searchTerms: ["brand-deno"]
			}, {
				title: "ti ti-brand-denodo",
				searchTerms: ["brand-denodo"]
			}, {
				title: "ti ti-brand-deviantart",
				searchTerms: ["brand-deviantart"]
			}, {
				title: "ti ti-brand-digg",
				searchTerms: ["brand-digg"]
			}, {
				title: "ti ti-brand-dingtalk",
				searchTerms: ["brand-dingtalk"]
			}, {
				title: "ti ti-brand-discord",
				searchTerms: ["brand-discord"]
			}, {
				title: "ti ti-brand-discord-filled",
				searchTerms: ["brand-discord-filled"]
			}, {
				title: "ti ti-brand-disney",
				searchTerms: ["brand-disney"]
			}, {
				title: "ti ti-brand-disqus",
				searchTerms: ["brand-disqus"]
			}, {
				title: "ti ti-brand-django",
				searchTerms: ["brand-django"]
			}, {
				title: "ti ti-brand-docker",
				searchTerms: ["brand-docker"]
			}, {
				title: "ti ti-brand-doctrine",
				searchTerms: ["brand-doctrine"]
			}, {
				title: "ti ti-brand-dolby-digital",
				searchTerms: ["brand-dolby-digital"]
			}, {
				title: "ti ti-brand-douban",
				searchTerms: ["brand-douban"]
			}, {
				title: "ti ti-brand-dribbble",
				searchTerms: ["brand-dribbble"]
			}, {
				title: "ti ti-brand-dribbble-filled",
				searchTerms: ["brand-dribbble-filled"]
			}, {
				title: "ti ti-brand-drops",
				searchTerms: ["brand-drops"]
			}, {
				title: "ti ti-brand-drupal",
				searchTerms: ["brand-drupal"]
			}, {
				title: "ti ti-brand-edge",
				searchTerms: ["brand-edge"]
			}, {
				title: "ti ti-brand-elastic",
				searchTerms: ["brand-elastic"]
			}, {
				title: "ti ti-brand-electronic-arts",
				searchTerms: ["brand-electronic-arts"]
			}, {
				title: "ti ti-brand-ember",
				searchTerms: ["brand-ember"]
			}, {
				title: "ti ti-brand-envato",
				searchTerms: ["brand-envato"]
			}, {
				title: "ti ti-brand-etsy",
				searchTerms: ["brand-etsy"]
			}, {
				title: "ti ti-brand-evernote",
				searchTerms: ["brand-evernote"]
			}, {
				title: "ti ti-brand-facebook",
				searchTerms: ["brand-facebook"]
			}, {
				title: "ti ti-brand-facebook-filled",
				searchTerms: ["brand-facebook-filled"]
			}, {
				title: "ti ti-brand-feedly",
				searchTerms: ["brand-feedly"]
			}, {
				title: "ti ti-brand-figma",
				searchTerms: ["brand-figma"]
			}, {
				title: "ti ti-brand-filezilla",
				searchTerms: ["brand-filezilla"]
			}, {
				title: "ti ti-brand-finder",
				searchTerms: ["brand-finder"]
			}, {
				title: "ti ti-brand-firebase",
				searchTerms: ["brand-firebase"]
			}, {
				title: "ti ti-brand-firefox",
				searchTerms: ["brand-firefox"]
			}, {
				title: "ti ti-brand-fiverr",
				searchTerms: ["brand-fiverr"]
			}, {
				title: "ti ti-brand-flickr",
				searchTerms: ["brand-flickr"]
			}, {
				title: "ti ti-brand-flightradar24",
				searchTerms: ["brand-flightradar24"]
			}, {
				title: "ti ti-brand-flipboard",
				searchTerms: ["brand-flipboard"]
			}, {
				title: "ti ti-brand-flutter",
				searchTerms: ["brand-flutter"]
			}, {
				title: "ti ti-brand-fortnite",
				searchTerms: ["brand-fortnite"]
			}, {
				title: "ti ti-brand-foursquare",
				searchTerms: ["brand-foursquare"]
			}, {
				title: "ti ti-brand-framer",
				searchTerms: ["brand-framer"]
			}, {
				title: "ti ti-brand-framer-motion",
				searchTerms: ["brand-framer-motion"]
			}, {
				title: "ti ti-brand-funimation",
				searchTerms: ["brand-funimation"]
			}, {
				title: "ti ti-brand-gatsby",
				searchTerms: ["brand-gatsby"]
			}, {
				title: "ti ti-brand-git",
				searchTerms: ["brand-git"]
			}, {
				title: "ti ti-brand-github",
				searchTerms: ["brand-github"]
			}, {
				title: "ti ti-brand-github-copilot",
				searchTerms: ["brand-github-copilot"]
			}, {
				title: "ti ti-brand-github-filled",
				searchTerms: ["brand-github-filled"]
			}, {
				title: "ti ti-brand-gitlab",
				searchTerms: ["brand-gitlab"]
			}, {
				title: "ti ti-brand-gmail",
				searchTerms: ["brand-gmail"]
			}, {
				title: "ti ti-brand-golang",
				searchTerms: ["brand-golang"]
			}, {
				title: "ti ti-brand-google",
				searchTerms: ["brand-google"]
			}, {
				title: "ti ti-brand-google-analytics",
				searchTerms: ["brand-google-analytics"]
			}, {
				title: "ti ti-brand-google-big-query",
				searchTerms: ["brand-google-big-query"]
			}, {
				title: "ti ti-brand-google-drive",
				searchTerms: ["brand-google-drive"]
			}, {
				title: "ti ti-brand-google-filled",
				searchTerms: ["brand-google-filled"]
			}, {
				title: "ti ti-brand-google-fit",
				searchTerms: ["brand-google-fit"]
			}, {
				title: "ti ti-brand-google-home",
				searchTerms: ["brand-google-home"]
			}, {
				title: "ti ti-brand-google-maps",
				searchTerms: ["brand-google-maps"]
			}, {
				title: "ti ti-brand-google-one",
				searchTerms: ["brand-google-one"]
			}, {
				title: "ti ti-brand-google-photos",
				searchTerms: ["brand-google-photos"]
			}, {
				title: "ti ti-brand-google-play",
				searchTerms: ["brand-google-play"]
			}, {
				title: "ti ti-brand-google-podcasts",
				searchTerms: ["brand-google-podcasts"]
			}, {
				title: "ti ti-brand-grammarly",
				searchTerms: ["brand-grammarly"]
			}, {
				title: "ti ti-brand-graphql",
				searchTerms: ["brand-graphql"]
			}, {
				title: "ti ti-brand-gravatar",
				searchTerms: ["brand-gravatar"]
			}, {
				title: "ti ti-brand-grindr",
				searchTerms: ["brand-grindr"]
			}, {
				title: "ti ti-brand-guardian",
				searchTerms: ["brand-guardian"]
			}, {
				title: "ti ti-brand-gumroad",
				searchTerms: ["brand-gumroad"]
			}, {
				title: "ti ti-brand-hbo",
				searchTerms: ["brand-hbo"]
			}, {
				title: "ti ti-brand-headlessui",
				searchTerms: ["brand-headlessui"]
			}, {
				title: "ti ti-brand-hexo",
				searchTerms: ["brand-hexo"]
			}, {
				title: "ti ti-brand-hipchat",
				searchTerms: ["brand-hipchat"]
			}, {
				title: "ti ti-brand-html5",
				searchTerms: ["brand-html5"]
			}, {
				title: "ti ti-brand-inertia",
				searchTerms: ["brand-inertia"]
			}, {
				title: "ti ti-brand-instagram",
				searchTerms: ["brand-instagram"]
			}, {
				title: "ti ti-brand-intercom",
				searchTerms: ["brand-intercom"]
			}, {
				title: "ti ti-brand-itch",
				searchTerms: ["brand-itch"]
			}, {
				title: "ti ti-brand-javascript",
				searchTerms: ["brand-javascript"]
			}, {
				title: "ti ti-brand-juejin",
				searchTerms: ["brand-juejin"]
			}, {
				title: "ti ti-brand-kako-talk",
				searchTerms: ["brand-kako-talk"]
			}, {
				title: "ti ti-brand-kbin",
				searchTerms: ["brand-kbin"]
			}, {
				title: "ti ti-brand-kick",
				searchTerms: ["brand-kick"]
			}, {
				title: "ti ti-brand-kickstarter",
				searchTerms: ["brand-kickstarter"]
			}, {
				title: "ti ti-brand-kotlin",
				searchTerms: ["brand-kotlin"]
			}, {
				title: "ti ti-brand-laravel",
				searchTerms: ["brand-laravel"]
			}, {
				title: "ti ti-brand-lastfm",
				searchTerms: ["brand-lastfm"]
			}, {
				title: "ti ti-brand-leetcode",
				searchTerms: ["brand-leetcode"]
			}, {
				title: "ti ti-brand-letterboxd",
				searchTerms: ["brand-letterboxd"]
			}, {
				title: "ti ti-brand-line",
				searchTerms: ["brand-line"]
			}, {
				title: "ti ti-brand-linkedin",
				searchTerms: ["brand-linkedin"]
			}, {
				title: "ti ti-brand-linktree",
				searchTerms: ["brand-linktree"]
			}, {
				title: "ti ti-brand-linqpad",
				searchTerms: ["brand-linqpad"]
			}, {
				title: "ti ti-brand-livewire",
				searchTerms: ["brand-livewire"]
			}, {
				title: "ti ti-brand-loom",
				searchTerms: ["brand-loom"]
			}, {
				title: "ti ti-brand-mailgun",
				searchTerms: ["brand-mailgun"]
			}, {
				title: "ti ti-brand-mantine",
				searchTerms: ["brand-mantine"]
			}, {
				title: "ti ti-brand-mastercard",
				searchTerms: ["brand-mastercard"]
			}, {
				title: "ti ti-brand-mastodon",
				searchTerms: ["brand-mastodon"]
			}, {
				title: "ti ti-brand-matrix",
				searchTerms: ["brand-matrix"]
			}, {
				title: "ti ti-brand-mcdonalds",
				searchTerms: ["brand-mcdonalds"]
			}, {
				title: "ti ti-brand-medium",
				searchTerms: ["brand-medium"]
			}, {
				title: "ti ti-brand-meetup",
				searchTerms: ["brand-meetup"]
			}, {
				title: "ti ti-brand-mercedes",
				searchTerms: ["brand-mercedes"]
			}, {
				title: "ti ti-brand-messenger",
				searchTerms: ["brand-messenger"]
			}, {
				title: "ti ti-brand-meta",
				searchTerms: ["brand-meta"]
			}, {
				title: "ti ti-brand-minecraft",
				searchTerms: ["brand-minecraft"]
			}, {
				title: "ti ti-brand-miniprogram",
				searchTerms: ["brand-miniprogram"]
			}, {
				title: "ti ti-brand-mixpanel",
				searchTerms: ["brand-mixpanel"]
			}, {
				title: "ti ti-brand-monday",
				searchTerms: ["brand-monday"]
			}, {
				title: "ti ti-brand-mongodb",
				searchTerms: ["brand-mongodb"]
			}, {
				title: "ti ti-brand-my-oppo",
				searchTerms: ["brand-my-oppo"]
			}, {
				title: "ti ti-brand-mysql",
				searchTerms: ["brand-mysql"]
			}, {
				title: "ti ti-brand-national-geographic",
				searchTerms: ["brand-national-geographic"]
			}, {
				title: "ti ti-brand-nem",
				searchTerms: ["brand-nem"]
			}, {
				title: "ti ti-brand-netbeans",
				searchTerms: ["brand-netbeans"]
			}, {
				title: "ti ti-brand-netease-music",
				searchTerms: ["brand-netease-music"]
			}, {
				title: "ti ti-brand-netflix",
				searchTerms: ["brand-netflix"]
			}, {
				title: "ti ti-brand-nexo",
				searchTerms: ["brand-nexo"]
			}, {
				title: "ti ti-brand-nextcloud",
				searchTerms: ["brand-nextcloud"]
			}, {
				title: "ti ti-brand-nextjs",
				searchTerms: ["brand-nextjs"]
			}, {
				title: "ti ti-brand-nodejs",
				searchTerms: ["brand-nodejs"]
			}, {
				title: "ti ti-brand-nord-vpn",
				searchTerms: ["brand-nord-vpn"]
			}, {
				title: "ti ti-brand-notion",
				searchTerms: ["brand-notion"]
			}, {
				title: "ti ti-brand-npm",
				searchTerms: ["brand-npm"]
			}, {
				title: "ti ti-brand-nuxt",
				searchTerms: ["brand-nuxt"]
			}, {
				title: "ti ti-brand-nytimes",
				searchTerms: ["brand-nytimes"]
			}, {
				title: "ti ti-brand-oauth",
				searchTerms: ["brand-oauth"]
			}, {
				title: "ti ti-brand-office",
				searchTerms: ["brand-office"]
			}, {
				title: "ti ti-brand-ok-ru",
				searchTerms: ["brand-ok-ru"]
			}, {
				title: "ti ti-brand-onedrive",
				searchTerms: ["brand-onedrive"]
			}, {
				title: "ti ti-brand-onlyfans",
				searchTerms: ["brand-onlyfans"]
			}, {
				title: "ti ti-brand-open-source",
				searchTerms: ["brand-open-source"]
			}, {
				title: "ti ti-brand-openai",
				searchTerms: ["brand-openai"]
			}, {
				title: "ti ti-brand-openvpn",
				searchTerms: ["brand-openvpn"]
			}, {
				title: "ti ti-brand-opera",
				searchTerms: ["brand-opera"]
			}, {
				title: "ti ti-brand-pagekit",
				searchTerms: ["brand-pagekit"]
			}, {
				title: "ti ti-brand-parsinta",
				searchTerms: ["brand-parsinta"]
			}, {
				title: "ti ti-brand-patreon",
				searchTerms: ["brand-patreon"]
			}, {
				title: "ti ti-brand-patreon-filled",
				searchTerms: ["brand-patreon-filled"]
			}, {
				title: "ti ti-brand-paypal",
				searchTerms: ["brand-paypal"]
			}, {
				title: "ti ti-brand-paypal-filled",
				searchTerms: ["brand-paypal-filled"]
			}, {
				title: "ti ti-brand-paypay",
				searchTerms: ["brand-paypay"]
			}, {
				title: "ti ti-brand-peanut",
				searchTerms: ["brand-peanut"]
			}, {
				title: "ti ti-brand-pepsi",
				searchTerms: ["brand-pepsi"]
			}, {
				title: "ti ti-brand-php",
				searchTerms: ["brand-php"]
			}, {
				title: "ti ti-brand-picsart",
				searchTerms: ["brand-picsart"]
			}, {
				title: "ti ti-brand-pinterest",
				searchTerms: ["brand-pinterest"]
			}, {
				title: "ti ti-brand-planetscale",
				searchTerms: ["brand-planetscale"]
			}, {
				title: "ti ti-brand-pnpm",
				searchTerms: ["brand-pnpm"]
			}, {
				title: "ti ti-brand-pocket",
				searchTerms: ["brand-pocket"]
			}, {
				title: "ti ti-brand-polymer",
				searchTerms: ["brand-polymer"]
			}, {
				title: "ti ti-brand-powershell",
				searchTerms: ["brand-powershell"]
			}, {
				title: "ti ti-brand-printables",
				searchTerms: ["brand-printables"]
			}, {
				title: "ti ti-brand-prisma",
				searchTerms: ["brand-prisma"]
			}, {
				title: "ti ti-brand-producthunt",
				searchTerms: ["brand-producthunt"]
			}, {
				title: "ti ti-brand-pushbullet",
				searchTerms: ["brand-pushbullet"]
			}, {
				title: "ti ti-brand-pushover",
				searchTerms: ["brand-pushover"]
			}, {
				title: "ti ti-brand-python",
				searchTerms: ["brand-python"]
			}, {
				title: "ti ti-brand-qq",
				searchTerms: ["brand-qq"]
			}, {
				title: "ti ti-brand-radix-ui",
				searchTerms: ["brand-radix-ui"]
			}, {
				title: "ti ti-brand-react",
				searchTerms: ["brand-react"]
			}, {
				title: "ti ti-brand-react-native",
				searchTerms: ["brand-react-native"]
			}, {
				title: "ti ti-brand-reason",
				searchTerms: ["brand-reason"]
			}, {
				title: "ti ti-brand-reddit",
				searchTerms: ["brand-reddit"]
			}, {
				title: "ti ti-brand-redhat",
				searchTerms: ["brand-redhat"]
			}, {
				title: "ti ti-brand-redux",
				searchTerms: ["brand-redux"]
			}, {
				title: "ti ti-brand-revolut",
				searchTerms: ["brand-revolut"]
			}, {
				title: "ti ti-brand-rumble",
				searchTerms: ["brand-rumble"]
			}, {
				title: "ti ti-brand-rust",
				searchTerms: ["brand-rust"]
			}, {
				title: "ti ti-brand-safari",
				searchTerms: ["brand-safari"]
			}, {
				title: "ti ti-brand-samsungpass",
				searchTerms: ["brand-samsungpass"]
			}, {
				title: "ti ti-brand-sass",
				searchTerms: ["brand-sass"]
			}, {
				title: "ti ti-brand-sentry",
				searchTerms: ["brand-sentry"]
			}, {
				title: "ti ti-brand-sharik",
				searchTerms: ["brand-sharik"]
			}, {
				title: "ti ti-brand-shazam",
				searchTerms: ["brand-shazam"]
			}, {
				title: "ti ti-brand-shopee",
				searchTerms: ["brand-shopee"]
			}, {
				title: "ti ti-brand-sketch",
				searchTerms: ["brand-sketch"]
			}, {
				title: "ti ti-brand-skype",
				searchTerms: ["brand-skype"]
			}, {
				title: "ti ti-brand-slack",
				searchTerms: ["brand-slack"]
			}, {
				title: "ti ti-brand-snapchat",
				searchTerms: ["brand-snapchat"]
			}, {
				title: "ti ti-brand-snapseed",
				searchTerms: ["brand-snapseed"]
			}, {
				title: "ti ti-brand-snowflake",
				searchTerms: ["brand-snowflake"]
			}, {
				title: "ti ti-brand-socket-io",
				searchTerms: ["brand-socket-io"]
			}, {
				title: "ti ti-brand-solidjs",
				searchTerms: ["brand-solidjs"]
			}, {
				title: "ti ti-brand-soundcloud",
				searchTerms: ["brand-soundcloud"]
			}, {
				title: "ti ti-brand-spacehey",
				searchTerms: ["brand-spacehey"]
			}, {
				title: "ti ti-brand-speedtest",
				searchTerms: ["brand-speedtest"]
			}, {
				title: "ti ti-brand-spotify",
				searchTerms: ["brand-spotify"]
			}, {
				title: "ti ti-brand-spotify-filled",
				searchTerms: ["brand-spotify-filled"]
			}, {
				title: "ti ti-brand-stackoverflow",
				searchTerms: ["brand-stackoverflow"]
			}, {
				title: "ti ti-brand-stackshare",
				searchTerms: ["brand-stackshare"]
			}, {
				title: "ti ti-brand-steam",
				searchTerms: ["brand-steam"]
			}, {
				title: "ti ti-brand-stocktwits",
				searchTerms: ["brand-stocktwits"]
			}, {
				title: "ti ti-brand-storj",
				searchTerms: ["brand-storj"]
			}, {
				title: "ti ti-brand-storybook",
				searchTerms: ["brand-storybook"]
			}, {
				title: "ti ti-brand-storytel",
				searchTerms: ["brand-storytel"]
			}, {
				title: "ti ti-brand-strava",
				searchTerms: ["brand-strava"]
			}, {
				title: "ti ti-brand-stripe",
				searchTerms: ["brand-stripe"]
			}, {
				title: "ti ti-brand-sublime-text",
				searchTerms: ["brand-sublime-text"]
			}, {
				title: "ti ti-brand-sugarizer",
				searchTerms: ["brand-sugarizer"]
			}, {
				title: "ti ti-brand-supabase",
				searchTerms: ["brand-supabase"]
			}, {
				title: "ti ti-brand-superhuman",
				searchTerms: ["brand-superhuman"]
			}, {
				title: "ti ti-brand-supernova",
				searchTerms: ["brand-supernova"]
			}, {
				title: "ti ti-brand-surfshark",
				searchTerms: ["brand-surfshark"]
			}, {
				title: "ti ti-brand-svelte",
				searchTerms: ["brand-svelte"]
			}, {
				title: "ti ti-brand-swift",
				searchTerms: ["brand-swift"]
			}, {
				title: "ti ti-brand-symfony",
				searchTerms: ["brand-symfony"]
			}, {
				title: "ti ti-brand-tabler",
				searchTerms: ["brand-tabler"]
			}, {
				title: "ti ti-brand-tailwind",
				searchTerms: ["brand-tailwind"]
			}, {
				title: "ti ti-brand-taobao",
				searchTerms: ["brand-taobao"]
			}, {
				title: "ti ti-brand-teams",
				searchTerms: ["brand-teams"]
			}, {
				title: "ti ti-brand-ted",
				searchTerms: ["brand-ted"]
			}, {
				title: "ti ti-brand-telegram",
				searchTerms: ["brand-telegram"]
			}, {
				title: "ti ti-brand-terraform",
				searchTerms: ["brand-terraform"]
			}, {
				title: "ti ti-brand-tether",
				searchTerms: ["brand-tether"]
			}, {
				title: "ti ti-brand-thingiverse",
				searchTerms: ["brand-thingiverse"]
			}, {
				title: "ti ti-brand-threads",
				searchTerms: ["brand-threads"]
			}, {
				title: "ti ti-brand-threejs",
				searchTerms: ["brand-threejs"]
			}, {
				title: "ti ti-brand-tidal",
				searchTerms: ["brand-tidal"]
			}, {
				title: "ti ti-brand-tiktok",
				searchTerms: ["brand-tiktok"]
			}, {
				title: "ti ti-brand-tiktok-filled",
				searchTerms: ["brand-tiktok-filled"]
			}, {
				title: "ti ti-brand-tinder",
				searchTerms: ["brand-tinder"]
			}, {
				title: "ti ti-brand-topbuzz",
				searchTerms: ["brand-topbuzz"]
			}, {
				title: "ti ti-brand-torchain",
				searchTerms: ["brand-torchain"]
			}, {
				title: "ti ti-brand-toyota",
				searchTerms: ["brand-toyota"]
			}, {
				title: "ti ti-brand-trello",
				searchTerms: ["brand-trello"]
			}, {
				title: "ti ti-brand-tripadvisor",
				searchTerms: ["brand-tripadvisor"]
			}, {
				title: "ti ti-brand-tumblr",
				searchTerms: ["brand-tumblr"]
			}, {
				title: "ti ti-brand-twilio",
				searchTerms: ["brand-twilio"]
			}, {
				title: "ti ti-brand-twitch",
				searchTerms: ["brand-twitch"]
			}, {
				title: "ti ti-brand-twitter",
				searchTerms: ["brand-twitter"]
			}, {
				title: "ti ti-brand-twitter-filled",
				searchTerms: ["brand-twitter-filled"]
			}, {
				title: "ti ti-brand-typescript",
				searchTerms: ["brand-typescript"]
			}, {
				title: "ti ti-brand-uber",
				searchTerms: ["brand-uber"]
			}, {
				title: "ti ti-brand-ubuntu",
				searchTerms: ["brand-ubuntu"]
			}, {
				title: "ti ti-brand-unity",
				searchTerms: ["brand-unity"]
			}, {
				title: "ti ti-brand-unsplash",
				searchTerms: ["brand-unsplash"]
			}, {
				title: "ti ti-brand-upwork",
				searchTerms: ["brand-upwork"]
			}, {
				title: "ti ti-brand-valorant",
				searchTerms: ["brand-valorant"]
			}, {
				title: "ti ti-brand-vercel",
				searchTerms: ["brand-vercel"]
			}, {
				title: "ti ti-brand-vimeo",
				searchTerms: ["brand-vimeo"]
			}, {
				title: "ti ti-brand-vinted",
				searchTerms: ["brand-vinted"]
			}, {
				title: "ti ti-brand-visa",
				searchTerms: ["brand-visa"]
			}, {
				title: "ti ti-brand-visual-studio",
				searchTerms: ["brand-visual-studio"]
			}, {
				title: "ti ti-brand-vite",
				searchTerms: ["brand-vite"]
			}, {
				title: "ti ti-brand-vivaldi",
				searchTerms: ["brand-vivaldi"]
			}, {
				title: "ti ti-brand-vk",
				searchTerms: ["brand-vk"]
			}, {
				title: "ti ti-brand-vlc",
				searchTerms: ["brand-vlc"]
			}, {
				title: "ti ti-brand-volkswagen",
				searchTerms: ["brand-volkswagen"]
			}, {
				title: "ti ti-brand-vsco",
				searchTerms: ["brand-vsco"]
			}, {
				title: "ti ti-brand-vscode",
				searchTerms: ["brand-vscode"]
			}, {
				title: "ti ti-brand-vue",
				searchTerms: ["brand-vue"]
			}, {
				title: "ti ti-brand-walmart",
				searchTerms: ["brand-walmart"]
			}, {
				title: "ti ti-brand-waze",
				searchTerms: ["brand-waze"]
			}, {
				title: "ti ti-brand-webflow",
				searchTerms: ["brand-webflow"]
			}, {
				title: "ti ti-brand-wechat",
				searchTerms: ["brand-wechat"]
			}, {
				title: "ti ti-brand-weibo",
				searchTerms: ["brand-weibo"]
			}, {
				title: "ti ti-brand-whatsapp",
				searchTerms: ["brand-whatsapp"]
			}, {
				title: "ti ti-brand-wikipedia",
				searchTerms: ["brand-wikipedia"]
			}, {
				title: "ti ti-brand-windows",
				searchTerms: ["brand-windows"]
			}, {
				title: "ti ti-brand-windy",
				searchTerms: ["brand-windy"]
			}, {
				title: "ti ti-brand-wish",
				searchTerms: ["brand-wish"]
			}, {
				title: "ti ti-brand-wix",
				searchTerms: ["brand-wix"]
			}, {
				title: "ti ti-brand-wordpress",
				searchTerms: ["brand-wordpress"]
			}, {
				title: "ti ti-brand-x",
				searchTerms: ["brand-x"]
			}, {
				title: "ti ti-brand-x-filled",
				searchTerms: ["brand-x-filled"]
			}, {
				title: "ti ti-brand-xamarin",
				searchTerms: ["brand-xamarin"]
			}, {
				title: "ti ti-brand-xbox",
				searchTerms: ["brand-xbox"]
			}, {
				title: "ti ti-brand-xdeep",
				searchTerms: ["brand-xdeep"]
			}, {
				title: "ti ti-brand-xing",
				searchTerms: ["brand-xing"]
			}, {
				title: "ti ti-brand-yahoo",
				searchTerms: ["brand-yahoo"]
			}, {
				title: "ti ti-brand-yandex",
				searchTerms: ["brand-yandex"]
			}, {
				title: "ti ti-brand-yarn",
				searchTerms: ["brand-yarn"]
			}, {
				title: "ti ti-brand-yatse",
				searchTerms: ["brand-yatse"]
			}, {
				title: "ti ti-brand-ycombinator",
				searchTerms: ["brand-ycombinator"]
			}, {
				title: "ti ti-brand-youtube",
				searchTerms: ["brand-youtube"]
			}, {
				title: "ti ti-brand-youtube-filled",
				searchTerms: ["brand-youtube-filled"]
			}, {
				title: "ti ti-brand-youtube-kids",
				searchTerms: ["brand-youtube-kids"]
			}, {
				title: "ti ti-brand-zalando",
				searchTerms: ["brand-zalando"]
			}, {
				title: "ti ti-brand-zapier",
				searchTerms: ["brand-zapier"]
			}, {
				title: "ti ti-brand-zeit",
				searchTerms: ["brand-zeit"]
			}, {
				title: "ti ti-brand-zhihu",
				searchTerms: ["brand-zhihu"]
			}, {
				title: "ti ti-brand-zoom",
				searchTerms: ["brand-zoom"]
			}, {
				title: "ti ti-brand-zulip",
				searchTerms: ["brand-zulip"]
			}, {
				title: "ti ti-brand-zwift",
				searchTerms: ["brand-zwift"]
			}, {
				title: "ti ti-bread",
				searchTerms: ["bread"]
			}, {
				title: "ti ti-bread-filled",
				searchTerms: ["bread-filled"]
			}, {
				title: "ti ti-bread-off",
				searchTerms: ["bread-off"]
			}, {
				title: "ti ti-briefcase",
				searchTerms: ["briefcase"]
			}, {
				title: "ti ti-briefcase-2",
				searchTerms: ["briefcase-2"]
			}, {
				title: "ti ti-briefcase-2-filled",
				searchTerms: ["briefcase-2-filled"]
			}, {
				title: "ti ti-briefcase-filled",
				searchTerms: ["briefcase-filled"]
			}, {
				title: "ti ti-briefcase-off",
				searchTerms: ["briefcase-off"]
			}, {
				title: "ti ti-brightness",
				searchTerms: ["brightness"]
			}, {
				title: "ti ti-brightness-2",
				searchTerms: ["brightness-2"]
			}, {
				title: "ti ti-brightness-auto",
				searchTerms: ["brightness-auto"]
			}, {
				title: "ti ti-brightness-auto-filled",
				searchTerms: ["brightness-auto-filled"]
			}, {
				title: "ti ti-brightness-down",
				searchTerms: ["brightness-down"]
			}, {
				title: "ti ti-brightness-down-filled",
				searchTerms: ["brightness-down-filled"]
			}, {
				title: "ti ti-brightness-filled",
				searchTerms: ["brightness-filled"]
			}, {
				title: "ti ti-brightness-half",
				searchTerms: ["brightness-half"]
			}, {
				title: "ti ti-brightness-off",
				searchTerms: ["brightness-off"]
			}, {
				title: "ti ti-brightness-up",
				searchTerms: ["brightness-up"]
			}, {
				title: "ti ti-brightness-up-filled",
				searchTerms: ["brightness-up-filled"]
			}, {
				title: "ti ti-broadcast",
				searchTerms: ["broadcast"]
			}, {
				title: "ti ti-broadcast-off",
				searchTerms: ["broadcast-off"]
			}, {
				title: "ti ti-browser",
				searchTerms: ["browser"]
			}, {
				title: "ti ti-browser-check",
				searchTerms: ["browser-check"]
			}, {
				title: "ti ti-browser-off",
				searchTerms: ["browser-off"]
			}, {
				title: "ti ti-browser-plus",
				searchTerms: ["browser-plus"]
			}, {
				title: "ti ti-browser-x",
				searchTerms: ["browser-x"]
			}, {
				title: "ti ti-brush",
				searchTerms: ["brush"]
			}, {
				title: "ti ti-brush-off",
				searchTerms: ["brush-off"]
			}, {
				title: "ti ti-bubble",
				searchTerms: ["bubble"]
			}, {
				title: "ti ti-bubble-filled",
				searchTerms: ["bubble-filled"]
			}, {
				title: "ti ti-bubble-minus",
				searchTerms: ["bubble-minus"]
			}, {
				title: "ti ti-bubble-plus",
				searchTerms: ["bubble-plus"]
			}, {
				title: "ti ti-bubble-text",
				searchTerms: ["bubble-text"]
			}, {
				title: "ti ti-bubble-x",
				searchTerms: ["bubble-x"]
			}, {
				title: "ti ti-bucket",
				searchTerms: ["bucket"]
			}, {
				title: "ti ti-bucket-droplet",
				searchTerms: ["bucket-droplet"]
			}, {
				title: "ti ti-bucket-off",
				searchTerms: ["bucket-off"]
			}, {
				title: "ti ti-bug",
				searchTerms: ["bug"]
			}, {
				title: "ti ti-bug-filled",
				searchTerms: ["bug-filled"]
			}, {
				title: "ti ti-bug-off",
				searchTerms: ["bug-off"]
			}, {
				title: "ti ti-building",
				searchTerms: ["building"]
			}, {
				title: "ti ti-building-arch",
				searchTerms: ["building-arch"]
			}, {
				title: "ti ti-building-bank",
				searchTerms: ["building-bank"]
			}, {
				title: "ti ti-building-bridge",
				searchTerms: ["building-bridge"]
			}, {
				title: "ti ti-building-bridge-2",
				searchTerms: ["building-bridge-2"]
			}, {
				title: "ti ti-building-broadcast-tower",
				searchTerms: ["building-broadcast-tower"]
			}, {
				title: "ti ti-building-broadcast-tower-filled",
				searchTerms: ["building-broadcast-tower-filled"]
			}, {
				title: "ti ti-building-carousel",
				searchTerms: ["building-carousel"]
			}, {
				title: "ti ti-building-castle",
				searchTerms: ["building-castle"]
			}, {
				title: "ti ti-building-church",
				searchTerms: ["building-church"]
			}, {
				title: "ti ti-building-circus",
				searchTerms: ["building-circus"]
			}, {
				title: "ti ti-building-community",
				searchTerms: ["building-community"]
			}, {
				title: "ti ti-building-cottage",
				searchTerms: ["building-cottage"]
			}, {
				title: "ti ti-building-estate",
				searchTerms: ["building-estate"]
			}, {
				title: "ti ti-building-factory",
				searchTerms: ["building-factory"]
			}, {
				title: "ti ti-building-factory-2",
				searchTerms: ["building-factory-2"]
			}, {
				title: "ti ti-building-fortress",
				searchTerms: ["building-fortress"]
			}, {
				title: "ti ti-building-hospital",
				searchTerms: ["building-hospital"]
			}, {
				title: "ti ti-building-lighthouse",
				searchTerms: ["building-lighthouse"]
			}, {
				title: "ti ti-building-monument",
				searchTerms: ["building-monument"]
			}, {
				title: "ti ti-building-mosque",
				searchTerms: ["building-mosque"]
			}, {
				title: "ti ti-building-pavilion",
				searchTerms: ["building-pavilion"]
			}, {
				title: "ti ti-building-skyscraper",
				searchTerms: ["building-skyscraper"]
			}, {
				title: "ti ti-building-stadium",
				searchTerms: ["building-stadium"]
			}, {
				title: "ti ti-building-store",
				searchTerms: ["building-store"]
			}, {
				title: "ti ti-building-tunnel",
				searchTerms: ["building-tunnel"]
			}, {
				title: "ti ti-building-warehouse",
				searchTerms: ["building-warehouse"]
			}, {
				title: "ti ti-building-wind-turbine",
				searchTerms: ["building-wind-turbine"]
			}, {
				title: "ti ti-bulb",
				searchTerms: ["bulb"]
			}, {
				title: "ti ti-bulb-filled",
				searchTerms: ["bulb-filled"]
			}, {
				title: "ti ti-bulb-off",
				searchTerms: ["bulb-off"]
			}, {
				title: "ti ti-bulldozer",
				searchTerms: ["bulldozer"]
			}, {
				title: "ti ti-burger",
				searchTerms: ["burger"]
			}, {
				title: "ti ti-bus",
				searchTerms: ["bus"]
			}, {
				title: "ti ti-bus-off",
				searchTerms: ["bus-off"]
			}, {
				title: "ti ti-bus-stop",
				searchTerms: ["bus-stop"]
			}, {
				title: "ti ti-businessplan",
				searchTerms: ["businessplan"]
			}, {
				title: "ti ti-butterfly",
				searchTerms: ["butterfly"]
			}, {
				title: "ti ti-cactus",
				searchTerms: ["cactus"]
			}, {
				title: "ti ti-cactus-filled",
				searchTerms: ["cactus-filled"]
			}, {
				title: "ti ti-cactus-off",
				searchTerms: ["cactus-off"]
			}, {
				title: "ti ti-cake",
				searchTerms: ["cake"]
			}, {
				title: "ti ti-cake-off",
				searchTerms: ["cake-off"]
			}, {
				title: "ti ti-calculator",
				searchTerms: ["calculator"]
			}, {
				title: "ti ti-calculator-filled",
				searchTerms: ["calculator-filled"]
			}, {
				title: "ti ti-calculator-off",
				searchTerms: ["calculator-off"]
			}, {
				title: "ti ti-calendar",
				searchTerms: ["calendar"]
			}, {
				title: "ti ti-calendar-bolt",
				searchTerms: ["calendar-bolt"]
			}, {
				title: "ti ti-calendar-cancel",
				searchTerms: ["calendar-cancel"]
			}, {
				title: "ti ti-calendar-check",
				searchTerms: ["calendar-check"]
			}, {
				title: "ti ti-calendar-clock",
				searchTerms: ["calendar-clock"]
			}, {
				title: "ti ti-calendar-code",
				searchTerms: ["calendar-code"]
			}, {
				title: "ti ti-calendar-cog",
				searchTerms: ["calendar-cog"]
			}, {
				title: "ti ti-calendar-dollar",
				searchTerms: ["calendar-dollar"]
			}, {
				title: "ti ti-calendar-dot",
				searchTerms: ["calendar-dot"]
			}, {
				title: "ti ti-calendar-down",
				searchTerms: ["calendar-down"]
			}, {
				title: "ti ti-calendar-due",
				searchTerms: ["calendar-due"]
			}, {
				title: "ti ti-calendar-event",
				searchTerms: ["calendar-event"]
			}, {
				title: "ti ti-calendar-exclamation",
				searchTerms: ["calendar-exclamation"]
			}, {
				title: "ti ti-calendar-filled",
				searchTerms: ["calendar-filled"]
			}, {
				title: "ti ti-calendar-heart",
				searchTerms: ["calendar-heart"]
			}, {
				title: "ti ti-calendar-minus",
				searchTerms: ["calendar-minus"]
			}, {
				title: "ti ti-calendar-month",
				searchTerms: ["calendar-month"]
			}, {
				title: "ti ti-calendar-off",
				searchTerms: ["calendar-off"]
			}, {
				title: "ti ti-calendar-pause",
				searchTerms: ["calendar-pause"]
			}, {
				title: "ti ti-calendar-pin",
				searchTerms: ["calendar-pin"]
			}, {
				title: "ti ti-calendar-plus",
				searchTerms: ["calendar-plus"]
			}, {
				title: "ti ti-calendar-question",
				searchTerms: ["calendar-question"]
			}, {
				title: "ti ti-calendar-repeat",
				searchTerms: ["calendar-repeat"]
			}, {
				title: "ti ti-calendar-sad",
				searchTerms: ["calendar-sad"]
			}, {
				title: "ti ti-calendar-search",
				searchTerms: ["calendar-search"]
			}, {
				title: "ti ti-calendar-share",
				searchTerms: ["calendar-share"]
			}, {
				title: "ti ti-calendar-smile",
				searchTerms: ["calendar-smile"]
			}, {
				title: "ti ti-calendar-star",
				searchTerms: ["calendar-star"]
			}, {
				title: "ti ti-calendar-stats",
				searchTerms: ["calendar-stats"]
			}, {
				title: "ti ti-calendar-time",
				searchTerms: ["calendar-time"]
			}, {
				title: "ti ti-calendar-up",
				searchTerms: ["calendar-up"]
			}, {
				title: "ti ti-calendar-user",
				searchTerms: ["calendar-user"]
			}, {
				title: "ti ti-calendar-week",
				searchTerms: ["calendar-week"]
			}, {
				title: "ti ti-calendar-x",
				searchTerms: ["calendar-x"]
			}, {
				title: "ti ti-camera",
				searchTerms: ["camera"]
			}, {
				title: "ti ti-camera-bolt",
				searchTerms: ["camera-bolt"]
			}, {
				title: "ti ti-camera-cancel",
				searchTerms: ["camera-cancel"]
			}, {
				title: "ti ti-camera-check",
				searchTerms: ["camera-check"]
			}, {
				title: "ti ti-camera-code",
				searchTerms: ["camera-code"]
			}, {
				title: "ti ti-camera-cog",
				searchTerms: ["camera-cog"]
			}, {
				title: "ti ti-camera-dollar",
				searchTerms: ["camera-dollar"]
			}, {
				title: "ti ti-camera-down",
				searchTerms: ["camera-down"]
			}, {
				title: "ti ti-camera-exclamation",
				searchTerms: ["camera-exclamation"]
			}, {
				title: "ti ti-camera-filled",
				searchTerms: ["camera-filled"]
			}, {
				title: "ti ti-camera-heart",
				searchTerms: ["camera-heart"]
			}, {
				title: "ti ti-camera-minus",
				searchTerms: ["camera-minus"]
			}, {
				title: "ti ti-camera-off",
				searchTerms: ["camera-off"]
			}, {
				title: "ti ti-camera-pause",
				searchTerms: ["camera-pause"]
			}, {
				title: "ti ti-camera-pin",
				searchTerms: ["camera-pin"]
			}, {
				title: "ti ti-camera-plus",
				searchTerms: ["camera-plus"]
			}, {
				title: "ti ti-camera-question",
				searchTerms: ["camera-question"]
			}, {
				title: "ti ti-camera-rotate",
				searchTerms: ["camera-rotate"]
			}, {
				title: "ti ti-camera-search",
				searchTerms: ["camera-search"]
			}, {
				title: "ti ti-camera-selfie",
				searchTerms: ["camera-selfie"]
			}, {
				title: "ti ti-camera-share",
				searchTerms: ["camera-share"]
			}, {
				title: "ti ti-camera-star",
				searchTerms: ["camera-star"]
			}, {
				title: "ti ti-camera-up",
				searchTerms: ["camera-up"]
			}, {
				title: "ti ti-camera-x",
				searchTerms: ["camera-x"]
			}, {
				title: "ti ti-camper",
				searchTerms: ["camper"]
			}, {
				title: "ti ti-campfire",
				searchTerms: ["campfire"]
			}, {
				title: "ti ti-campfire-filled",
				searchTerms: ["campfire-filled"]
			}, {
				title: "ti ti-candle",
				searchTerms: ["candle"]
			}, {
				title: "ti ti-candle-filled",
				searchTerms: ["candle-filled"]
			}, {
				title: "ti ti-candy",
				searchTerms: ["candy"]
			}, {
				title: "ti ti-candy-off",
				searchTerms: ["candy-off"]
			}, {
				title: "ti ti-cane",
				searchTerms: ["cane"]
			}, {
				title: "ti ti-cannabis",
				searchTerms: ["cannabis"]
			}, {
				title: "ti ti-capsule",
				searchTerms: ["capsule"]
			}, {
				title: "ti ti-capsule-filled",
				searchTerms: ["capsule-filled"]
			}, {
				title: "ti ti-capsule-horizontal",
				searchTerms: ["capsule-horizontal"]
			}, {
				title: "ti ti-capsule-horizontal-filled",
				searchTerms: ["capsule-horizontal-filled"]
			}, {
				title: "ti ti-capture",
				searchTerms: ["capture"]
			}, {
				title: "ti ti-capture-filled",
				searchTerms: ["capture-filled"]
			}, {
				title: "ti ti-capture-off",
				searchTerms: ["capture-off"]
			}, {
				title: "ti ti-car",
				searchTerms: ["car"]
			}, {
				title: "ti ti-car-4wd",
				searchTerms: ["car-4wd"]
			}, {
				title: "ti ti-car-crane",
				searchTerms: ["car-crane"]
			}, {
				title: "ti ti-car-crash",
				searchTerms: ["car-crash"]
			}, {
				title: "ti ti-car-fan",
				searchTerms: ["car-fan"]
			}, {
				title: "ti ti-car-fan-1",
				searchTerms: ["car-fan-1"]
			}, {
				title: "ti ti-car-fan-2",
				searchTerms: ["car-fan-2"]
			}, {
				title: "ti ti-car-fan-3",
				searchTerms: ["car-fan-3"]
			}, {
				title: "ti ti-car-fan-auto",
				searchTerms: ["car-fan-auto"]
			}, {
				title: "ti ti-car-garage",
				searchTerms: ["car-garage"]
			}, {
				title: "ti ti-car-off",
				searchTerms: ["car-off"]
			}, {
				title: "ti ti-car-suv",
				searchTerms: ["car-suv"]
			}, {
				title: "ti ti-car-turbine",
				searchTerms: ["car-turbine"]
			}, {
				title: "ti ti-carambola",
				searchTerms: ["carambola"]
			}, {
				title: "ti ti-caravan",
				searchTerms: ["caravan"]
			}, {
				title: "ti ti-cardboards",
				searchTerms: ["cardboards"]
			}, {
				title: "ti ti-cardboards-off",
				searchTerms: ["cardboards-off"]
			}, {
				title: "ti ti-cards",
				searchTerms: ["cards"]
			}, {
				title: "ti ti-cards-filled",
				searchTerms: ["cards-filled"]
			}, {
				title: "ti ti-caret-down",
				searchTerms: ["caret-down"]
			}, {
				title: "ti ti-caret-down-filled",
				searchTerms: ["caret-down-filled"]
			}, {
				title: "ti ti-caret-left",
				searchTerms: ["caret-left"]
			}, {
				title: "ti ti-caret-left-filled",
				searchTerms: ["caret-left-filled"]
			}, {
				title: "ti ti-caret-left-right",
				searchTerms: ["caret-left-right"]
			}, {
				title: "ti ti-caret-left-right-filled",
				searchTerms: ["caret-left-right-filled"]
			}, {
				title: "ti ti-caret-right",
				searchTerms: ["caret-right"]
			}, {
				title: "ti ti-caret-right-filled",
				searchTerms: ["caret-right-filled"]
			}, {
				title: "ti ti-caret-up",
				searchTerms: ["caret-up"]
			}, {
				title: "ti ti-caret-up-down",
				searchTerms: ["caret-up-down"]
			}, {
				title: "ti ti-caret-up-down-filled",
				searchTerms: ["caret-up-down-filled"]
			}, {
				title: "ti ti-caret-up-filled",
				searchTerms: ["caret-up-filled"]
			}, {
				title: "ti ti-carousel-horizontal",
				searchTerms: ["carousel-horizontal"]
			}, {
				title: "ti ti-carousel-horizontal-filled",
				searchTerms: ["carousel-horizontal-filled"]
			}, {
				title: "ti ti-carousel-vertical",
				searchTerms: ["carousel-vertical"]
			}, {
				title: "ti ti-carousel-vertical-filled",
				searchTerms: ["carousel-vertical-filled"]
			}, {
				title: "ti ti-carrot",
				searchTerms: ["carrot"]
			}, {
				title: "ti ti-carrot-off",
				searchTerms: ["carrot-off"]
			}, {
				title: "ti ti-cash",
				searchTerms: ["cash"]
			}, {
				title: "ti ti-cash-banknote",
				searchTerms: ["cash-banknote"]
			}, {
				title: "ti ti-cash-banknote-filled",
				searchTerms: ["cash-banknote-filled"]
			}, {
				title: "ti ti-cash-banknote-off",
				searchTerms: ["cash-banknote-off"]
			}, {
				title: "ti ti-cash-off",
				searchTerms: ["cash-off"]
			}, {
				title: "ti ti-cash-register",
				searchTerms: ["cash-register"]
			}, {
				title: "ti ti-cast",
				searchTerms: ["cast"]
			}, {
				title: "ti ti-cast-off",
				searchTerms: ["cast-off"]
			}, {
				title: "ti ti-cat",
				searchTerms: ["cat"]
			}, {
				title: "ti ti-category",
				searchTerms: ["category"]
			}, {
				title: "ti ti-category-2",
				searchTerms: ["category-2"]
			}, {
				title: "ti ti-category-filled",
				searchTerms: ["category-filled"]
			}, {
				title: "ti ti-category-minus",
				searchTerms: ["category-minus"]
			}, {
				title: "ti ti-category-plus",
				searchTerms: ["category-plus"]
			}, {
				title: "ti ti-ce",
				searchTerms: ["ce"]
			}, {
				title: "ti ti-ce-off",
				searchTerms: ["ce-off"]
			}, {
				title: "ti ti-cell",
				searchTerms: ["cell"]
			}, {
				title: "ti ti-cell-signal-1",
				searchTerms: ["cell-signal-1"]
			}, {
				title: "ti ti-cell-signal-2",
				searchTerms: ["cell-signal-2"]
			}, {
				title: "ti ti-cell-signal-3",
				searchTerms: ["cell-signal-3"]
			}, {
				title: "ti ti-cell-signal-4",
				searchTerms: ["cell-signal-4"]
			}, {
				title: "ti ti-cell-signal-5",
				searchTerms: ["cell-signal-5"]
			}, {
				title: "ti ti-cell-signal-off",
				searchTerms: ["cell-signal-off"]
			}, {
				title: "ti ti-certificate",
				searchTerms: ["certificate"]
			}, {
				title: "ti ti-certificate-2",
				searchTerms: ["certificate-2"]
			}, {
				title: "ti ti-certificate-2-off",
				searchTerms: ["certificate-2-off"]
			}, {
				title: "ti ti-certificate-off",
				searchTerms: ["certificate-off"]
			}, {
				title: "ti ti-chair-director",
				searchTerms: ["chair-director"]
			}, {
				title: "ti ti-chalkboard",
				searchTerms: ["chalkboard"]
			}, {
				title: "ti ti-chalkboard-off",
				searchTerms: ["chalkboard-off"]
			}, {
				title: "ti ti-charging-pile",
				searchTerms: ["charging-pile"]
			}, {
				title: "ti ti-chart-arcs",
				searchTerms: ["chart-arcs"]
			}, {
				title: "ti ti-chart-arcs-3",
				searchTerms: ["chart-arcs-3"]
			}, {
				title: "ti ti-chart-area",
				searchTerms: ["chart-area"]
			}, {
				title: "ti ti-chart-area-filled",
				searchTerms: ["chart-area-filled"]
			}, {
				title: "ti ti-chart-area-line",
				searchTerms: ["chart-area-line"]
			}, {
				title: "ti ti-chart-area-line-filled",
				searchTerms: ["chart-area-line-filled"]
			}, {
				title: "ti ti-chart-arrows",
				searchTerms: ["chart-arrows"]
			}, {
				title: "ti ti-chart-arrows-vertical",
				searchTerms: ["chart-arrows-vertical"]
			}, {
				title: "ti ti-chart-bar",
				searchTerms: ["chart-bar"]
			}, {
				title: "ti ti-chart-bar-off",
				searchTerms: ["chart-bar-off"]
			}, {
				title: "ti ti-chart-bubble",
				searchTerms: ["chart-bubble"]
			}, {
				title: "ti ti-chart-bubble-filled",
				searchTerms: ["chart-bubble-filled"]
			}, {
				title: "ti ti-chart-candle",
				searchTerms: ["chart-candle"]
			}, {
				title: "ti ti-chart-candle-filled",
				searchTerms: ["chart-candle-filled"]
			}, {
				title: "ti ti-chart-circles",
				searchTerms: ["chart-circles"]
			}, {
				title: "ti ti-chart-donut",
				searchTerms: ["chart-donut"]
			}, {
				title: "ti ti-chart-donut-2",
				searchTerms: ["chart-donut-2"]
			}, {
				title: "ti ti-chart-donut-3",
				searchTerms: ["chart-donut-3"]
			}, {
				title: "ti ti-chart-donut-4",
				searchTerms: ["chart-donut-4"]
			}, {
				title: "ti ti-chart-donut-filled",
				searchTerms: ["chart-donut-filled"]
			}, {
				title: "ti ti-chart-dots",
				searchTerms: ["chart-dots"]
			}, {
				title: "ti ti-chart-dots-2",
				searchTerms: ["chart-dots-2"]
			}, {
				title: "ti ti-chart-dots-3",
				searchTerms: ["chart-dots-3"]
			}, {
				title: "ti ti-chart-dots-filled",
				searchTerms: ["chart-dots-filled"]
			}, {
				title: "ti ti-chart-grid-dots",
				searchTerms: ["chart-grid-dots"]
			}, {
				title: "ti ti-chart-grid-dots-filled",
				searchTerms: ["chart-grid-dots-filled"]
			}, {
				title: "ti ti-chart-histogram",
				searchTerms: ["chart-histogram"]
			}, {
				title: "ti ti-chart-infographic",
				searchTerms: ["chart-infographic"]
			}, {
				title: "ti ti-chart-line",
				searchTerms: ["chart-line"]
			}, {
				title: "ti ti-chart-pie",
				searchTerms: ["chart-pie"]
			}, {
				title: "ti ti-chart-pie-2",
				searchTerms: ["chart-pie-2"]
			}, {
				title: "ti ti-chart-pie-3",
				searchTerms: ["chart-pie-3"]
			}, {
				title: "ti ti-chart-pie-4",
				searchTerms: ["chart-pie-4"]
			}, {
				title: "ti ti-chart-pie-filled",
				searchTerms: ["chart-pie-filled"]
			}, {
				title: "ti ti-chart-pie-off",
				searchTerms: ["chart-pie-off"]
			}, {
				title: "ti ti-chart-ppf",
				searchTerms: ["chart-ppf"]
			}, {
				title: "ti ti-chart-radar",
				searchTerms: ["chart-radar"]
			}, {
				title: "ti ti-chart-sankey",
				searchTerms: ["chart-sankey"]
			}, {
				title: "ti ti-chart-scatter",
				searchTerms: ["chart-scatter"]
			}, {
				title: "ti ti-chart-scatter-3d",
				searchTerms: ["chart-scatter-3d"]
			}, {
				title: "ti ti-chart-treemap",
				searchTerms: ["chart-treemap"]
			}, {
				title: "ti ti-check",
				searchTerms: ["check"]
			}, {
				title: "ti ti-checkbox",
				searchTerms: ["checkbox"]
			}, {
				title: "ti ti-checklist",
				searchTerms: ["checklist"]
			}, {
				title: "ti ti-checks",
				searchTerms: ["checks"]
			}, {
				title: "ti ti-checkup-list",
				searchTerms: ["checkup-list"]
			}, {
				title: "ti ti-cheese",
				searchTerms: ["cheese"]
			}, {
				title: "ti ti-chef-hat",
				searchTerms: ["chef-hat"]
			}, {
				title: "ti ti-chef-hat-off",
				searchTerms: ["chef-hat-off"]
			}, {
				title: "ti ti-cherry",
				searchTerms: ["cherry"]
			}, {
				title: "ti ti-cherry-filled",
				searchTerms: ["cherry-filled"]
			}, {
				title: "ti ti-chess",
				searchTerms: ["chess"]
			}, {
				title: "ti ti-chess-bishop",
				searchTerms: ["chess-bishop"]
			}, {
				title: "ti ti-chess-bishop-filled",
				searchTerms: ["chess-bishop-filled"]
			}, {
				title: "ti ti-chess-filled",
				searchTerms: ["chess-filled"]
			}, {
				title: "ti ti-chess-king",
				searchTerms: ["chess-king"]
			}, {
				title: "ti ti-chess-king-filled",
				searchTerms: ["chess-king-filled"]
			}, {
				title: "ti ti-chess-knight",
				searchTerms: ["chess-knight"]
			}, {
				title: "ti ti-chess-knight-filled",
				searchTerms: ["chess-knight-filled"]
			}, {
				title: "ti ti-chess-queen",
				searchTerms: ["chess-queen"]
			}, {
				title: "ti ti-chess-queen-filled",
				searchTerms: ["chess-queen-filled"]
			}, {
				title: "ti ti-chess-rook",
				searchTerms: ["chess-rook"]
			}, {
				title: "ti ti-chess-rook-filled",
				searchTerms: ["chess-rook-filled"]
			}, {
				title: "ti ti-chevron-compact-down",
				searchTerms: ["chevron-compact-down"]
			}, {
				title: "ti ti-chevron-compact-left",
				searchTerms: ["chevron-compact-left"]
			}, {
				title: "ti ti-chevron-compact-right",
				searchTerms: ["chevron-compact-right"]
			}, {
				title: "ti ti-chevron-compact-up",
				searchTerms: ["chevron-compact-up"]
			}, {
				title: "ti ti-chevron-down",
				searchTerms: ["chevron-down"]
			}, {
				title: "ti ti-chevron-down-left",
				searchTerms: ["chevron-down-left"]
			}, {
				title: "ti ti-chevron-down-right",
				searchTerms: ["chevron-down-right"]
			}, {
				title: "ti ti-chevron-left",
				searchTerms: ["chevron-left"]
			}, {
				title: "ti ti-chevron-left-pipe",
				searchTerms: ["chevron-left-pipe"]
			}, {
				title: "ti ti-chevron-right",
				searchTerms: ["chevron-right"]
			}, {
				title: "ti ti-chevron-right-pipe",
				searchTerms: ["chevron-right-pipe"]
			}, {
				title: "ti ti-chevron-up",
				searchTerms: ["chevron-up"]
			}, {
				title: "ti ti-chevron-up-left",
				searchTerms: ["chevron-up-left"]
			}, {
				title: "ti ti-chevron-up-right",
				searchTerms: ["chevron-up-right"]
			}, {
				title: "ti ti-chevrons-down",
				searchTerms: ["chevrons-down"]
			}, {
				title: "ti ti-chevrons-down-left",
				searchTerms: ["chevrons-down-left"]
			}, {
				title: "ti ti-chevrons-down-right",
				searchTerms: ["chevrons-down-right"]
			}, {
				title: "ti ti-chevrons-left",
				searchTerms: ["chevrons-left"]
			}, {
				title: "ti ti-chevrons-right",
				searchTerms: ["chevrons-right"]
			}, {
				title: "ti ti-chevrons-up",
				searchTerms: ["chevrons-up"]
			}, {
				title: "ti ti-chevrons-up-left",
				searchTerms: ["chevrons-up-left"]
			}, {
				title: "ti ti-chevrons-up-right",
				searchTerms: ["chevrons-up-right"]
			}, {
				title: "ti ti-chisel",
				searchTerms: ["chisel"]
			}, {
				title: "ti ti-christmas-ball",
				searchTerms: ["christmas-ball"]
			}, {
				title: "ti ti-christmas-tree",
				searchTerms: ["christmas-tree"]
			}, {
				title: "ti ti-christmas-tree-off",
				searchTerms: ["christmas-tree-off"]
			}, {
				title: "ti ti-circle",
				searchTerms: ["circle"]
			}, {
				title: "ti ti-circle-arrow-down",
				searchTerms: ["circle-arrow-down"]
			}, {
				title: "ti ti-circle-arrow-down-filled",
				searchTerms: ["circle-arrow-down-filled"]
			}, {
				title: "ti ti-circle-arrow-down-left",
				searchTerms: ["circle-arrow-down-left"]
			}, {
				title: "ti ti-circle-arrow-down-left-filled",
				searchTerms: ["circle-arrow-down-left-filled"]
			}, {
				title: "ti ti-circle-arrow-down-right",
				searchTerms: ["circle-arrow-down-right"]
			}, {
				title: "ti ti-circle-arrow-down-right-filled",
				searchTerms: ["circle-arrow-down-right-filled"]
			}, {
				title: "ti ti-circle-arrow-left",
				searchTerms: ["circle-arrow-left"]
			}, {
				title: "ti ti-circle-arrow-left-filled",
				searchTerms: ["circle-arrow-left-filled"]
			}, {
				title: "ti ti-circle-arrow-right",
				searchTerms: ["circle-arrow-right"]
			}, {
				title: "ti ti-circle-arrow-right-filled",
				searchTerms: ["circle-arrow-right-filled"]
			}, {
				title: "ti ti-circle-arrow-up",
				searchTerms: ["circle-arrow-up"]
			}, {
				title: "ti ti-circle-arrow-up-filled",
				searchTerms: ["circle-arrow-up-filled"]
			}, {
				title: "ti ti-circle-arrow-up-left",
				searchTerms: ["circle-arrow-up-left"]
			}, {
				title: "ti ti-circle-arrow-up-left-filled",
				searchTerms: ["circle-arrow-up-left-filled"]
			}, {
				title: "ti ti-circle-arrow-up-right",
				searchTerms: ["circle-arrow-up-right"]
			}, {
				title: "ti ti-circle-arrow-up-right-filled",
				searchTerms: ["circle-arrow-up-right-filled"]
			}, {
				title: "ti ti-circle-caret-down",
				searchTerms: ["circle-caret-down"]
			}, {
				title: "ti ti-circle-caret-left",
				searchTerms: ["circle-caret-left"]
			}, {
				title: "ti ti-circle-caret-right",
				searchTerms: ["circle-caret-right"]
			}, {
				title: "ti ti-circle-caret-up",
				searchTerms: ["circle-caret-up"]
			}, {
				title: "ti ti-circle-check",
				searchTerms: ["circle-check"]
			}, {
				title: "ti ti-circle-check-filled",
				searchTerms: ["circle-check-filled"]
			}, {
				title: "ti ti-circle-chevron-down",
				searchTerms: ["circle-chevron-down"]
			}, {
				title: "ti ti-circle-chevron-left",
				searchTerms: ["circle-chevron-left"]
			}, {
				title: "ti ti-circle-chevron-right",
				searchTerms: ["circle-chevron-right"]
			}, {
				title: "ti ti-circle-chevron-up",
				searchTerms: ["circle-chevron-up"]
			}, {
				title: "ti ti-circle-chevrons-down",
				searchTerms: ["circle-chevrons-down"]
			}, {
				title: "ti ti-circle-chevrons-left",
				searchTerms: ["circle-chevrons-left"]
			}, {
				title: "ti ti-circle-chevrons-right",
				searchTerms: ["circle-chevrons-right"]
			}, {
				title: "ti ti-circle-chevrons-up",
				searchTerms: ["circle-chevrons-up"]
			}, {
				title: "ti ti-circle-dashed",
				searchTerms: ["circle-dashed"]
			}, {
				title: "ti ti-circle-dashed-check",
				searchTerms: ["circle-dashed-check"]
			}, {
				title: "ti ti-circle-dashed-minus",
				searchTerms: ["circle-dashed-minus"]
			}, {
				title: "ti ti-circle-dashed-number-0",
				searchTerms: ["circle-dashed-number-0"]
			}, {
				title: "ti ti-circle-dashed-number-1",
				searchTerms: ["circle-dashed-number-1"]
			}, {
				title: "ti ti-circle-dashed-number-2",
				searchTerms: ["circle-dashed-number-2"]
			}, {
				title: "ti ti-circle-dashed-number-3",
				searchTerms: ["circle-dashed-number-3"]
			}, {
				title: "ti ti-circle-dashed-number-4",
				searchTerms: ["circle-dashed-number-4"]
			}, {
				title: "ti ti-circle-dashed-number-5",
				searchTerms: ["circle-dashed-number-5"]
			}, {
				title: "ti ti-circle-dashed-number-6",
				searchTerms: ["circle-dashed-number-6"]
			}, {
				title: "ti ti-circle-dashed-number-7",
				searchTerms: ["circle-dashed-number-7"]
			}, {
				title: "ti ti-circle-dashed-number-8",
				searchTerms: ["circle-dashed-number-8"]
			}, {
				title: "ti ti-circle-dashed-number-9",
				searchTerms: ["circle-dashed-number-9"]
			}, {
				title: "ti ti-circle-dashed-percentage",
				searchTerms: ["circle-dashed-percentage"]
			}, {
				title: "ti ti-circle-dashed-plus",
				searchTerms: ["circle-dashed-plus"]
			}, {
				title: "ti ti-circle-dashed-x",
				searchTerms: ["circle-dashed-x"]
			}, {
				title: "ti ti-circle-dot",
				searchTerms: ["circle-dot"]
			}, {
				title: "ti ti-circle-dot-filled",
				searchTerms: ["circle-dot-filled"]
			}, {
				title: "ti ti-circle-dotted",
				searchTerms: ["circle-dotted"]
			}, {
				title: "ti ti-circle-filled",
				searchTerms: ["circle-filled"]
			}, {
				title: "ti ti-circle-half",
				searchTerms: ["circle-half"]
			}, {
				title: "ti ti-circle-half-2",
				searchTerms: ["circle-half-2"]
			}, {
				title: "ti ti-circle-half-vertical",
				searchTerms: ["circle-half-vertical"]
			}, {
				title: "ti ti-circle-key",
				searchTerms: ["circle-key"]
			}, {
				title: "ti ti-circle-key-filled",
				searchTerms: ["circle-key-filled"]
			}, {
				title: "ti ti-circle-letter-a",
				searchTerms: ["circle-letter-a"]
			}, {
				title: "ti ti-circle-letter-a-filled",
				searchTerms: ["circle-letter-a-filled"]
			}, {
				title: "ti ti-circle-letter-b",
				searchTerms: ["circle-letter-b"]
			}, {
				title: "ti ti-circle-letter-b-filled",
				searchTerms: ["circle-letter-b-filled"]
			}, {
				title: "ti ti-circle-letter-c",
				searchTerms: ["circle-letter-c"]
			}, {
				title: "ti ti-circle-letter-c-filled",
				searchTerms: ["circle-letter-c-filled"]
			}, {
				title: "ti ti-circle-letter-d",
				searchTerms: ["circle-letter-d"]
			}, {
				title: "ti ti-circle-letter-d-filled",
				searchTerms: ["circle-letter-d-filled"]
			}, {
				title: "ti ti-circle-letter-e",
				searchTerms: ["circle-letter-e"]
			}, {
				title: "ti ti-circle-letter-e-filled",
				searchTerms: ["circle-letter-e-filled"]
			}, {
				title: "ti ti-circle-letter-f",
				searchTerms: ["circle-letter-f"]
			}, {
				title: "ti ti-circle-letter-f-filled",
				searchTerms: ["circle-letter-f-filled"]
			}, {
				title: "ti ti-circle-letter-g",
				searchTerms: ["circle-letter-g"]
			}, {
				title: "ti ti-circle-letter-g-filled",
				searchTerms: ["circle-letter-g-filled"]
			}, {
				title: "ti ti-circle-letter-h",
				searchTerms: ["circle-letter-h"]
			}, {
				title: "ti ti-circle-letter-h-filled",
				searchTerms: ["circle-letter-h-filled"]
			}, {
				title: "ti ti-circle-letter-i",
				searchTerms: ["circle-letter-i"]
			}, {
				title: "ti ti-circle-letter-i-filled",
				searchTerms: ["circle-letter-i-filled"]
			}, {
				title: "ti ti-circle-letter-j",
				searchTerms: ["circle-letter-j"]
			}, {
				title: "ti ti-circle-letter-j-filled",
				searchTerms: ["circle-letter-j-filled"]
			}, {
				title: "ti ti-circle-letter-k",
				searchTerms: ["circle-letter-k"]
			}, {
				title: "ti ti-circle-letter-k-filled",
				searchTerms: ["circle-letter-k-filled"]
			}, {
				title: "ti ti-circle-letter-l",
				searchTerms: ["circle-letter-l"]
			}, {
				title: "ti ti-circle-letter-l-filled",
				searchTerms: ["circle-letter-l-filled"]
			}, {
				title: "ti ti-circle-letter-m",
				searchTerms: ["circle-letter-m"]
			}, {
				title: "ti ti-circle-letter-m-filled",
				searchTerms: ["circle-letter-m-filled"]
			}, {
				title: "ti ti-circle-letter-n",
				searchTerms: ["circle-letter-n"]
			}, {
				title: "ti ti-circle-letter-n-filled",
				searchTerms: ["circle-letter-n-filled"]
			}, {
				title: "ti ti-circle-letter-o",
				searchTerms: ["circle-letter-o"]
			}, {
				title: "ti ti-circle-letter-o-filled",
				searchTerms: ["circle-letter-o-filled"]
			}, {
				title: "ti ti-circle-letter-p",
				searchTerms: ["circle-letter-p"]
			}, {
				title: "ti ti-circle-letter-p-filled",
				searchTerms: ["circle-letter-p-filled"]
			}, {
				title: "ti ti-circle-letter-q",
				searchTerms: ["circle-letter-q"]
			}, {
				title: "ti ti-circle-letter-q-filled",
				searchTerms: ["circle-letter-q-filled"]
			}, {
				title: "ti ti-circle-letter-r",
				searchTerms: ["circle-letter-r"]
			}, {
				title: "ti ti-circle-letter-r-filled",
				searchTerms: ["circle-letter-r-filled"]
			}, {
				title: "ti ti-circle-letter-s",
				searchTerms: ["circle-letter-s"]
			}, {
				title: "ti ti-circle-letter-s-filled",
				searchTerms: ["circle-letter-s-filled"]
			}, {
				title: "ti ti-circle-letter-t",
				searchTerms: ["circle-letter-t"]
			}, {
				title: "ti ti-circle-letter-t-filled",
				searchTerms: ["circle-letter-t-filled"]
			}, {
				title: "ti ti-circle-letter-u",
				searchTerms: ["circle-letter-u"]
			}, {
				title: "ti ti-circle-letter-u-filled",
				searchTerms: ["circle-letter-u-filled"]
			}, {
				title: "ti ti-circle-letter-v",
				searchTerms: ["circle-letter-v"]
			}, {
				title: "ti ti-circle-letter-v-filled",
				searchTerms: ["circle-letter-v-filled"]
			}, {
				title: "ti ti-circle-letter-w",
				searchTerms: ["circle-letter-w"]
			}, {
				title: "ti ti-circle-letter-w-filled",
				searchTerms: ["circle-letter-w-filled"]
			}, {
				title: "ti ti-circle-letter-x",
				searchTerms: ["circle-letter-x"]
			}, {
				title: "ti ti-circle-letter-x-filled",
				searchTerms: ["circle-letter-x-filled"]
			}, {
				title: "ti ti-circle-letter-y",
				searchTerms: ["circle-letter-y"]
			}, {
				title: "ti ti-circle-letter-y-filled",
				searchTerms: ["circle-letter-y-filled"]
			}, {
				title: "ti ti-circle-letter-z",
				searchTerms: ["circle-letter-z"]
			}, {
				title: "ti ti-circle-letter-z-filled",
				searchTerms: ["circle-letter-z-filled"]
			}, {
				title: "ti ti-circle-minus",
				searchTerms: ["circle-minus"]
			}, {
				title: "ti ti-circle-minus-2",
				searchTerms: ["circle-minus-2"]
			}, {
				title: "ti ti-circle-number-0",
				searchTerms: ["circle-number-0"]
			}, {
				title: "ti ti-circle-number-0-filled",
				searchTerms: ["circle-number-0-filled"]
			}, {
				title: "ti ti-circle-number-1",
				searchTerms: ["circle-number-1"]
			}, {
				title: "ti ti-circle-number-1-filled",
				searchTerms: ["circle-number-1-filled"]
			}, {
				title: "ti ti-circle-number-2",
				searchTerms: ["circle-number-2"]
			}, {
				title: "ti ti-circle-number-2-filled",
				searchTerms: ["circle-number-2-filled"]
			}, {
				title: "ti ti-circle-number-3",
				searchTerms: ["circle-number-3"]
			}, {
				title: "ti ti-circle-number-3-filled",
				searchTerms: ["circle-number-3-filled"]
			}, {
				title: "ti ti-circle-number-4",
				searchTerms: ["circle-number-4"]
			}, {
				title: "ti ti-circle-number-4-filled",
				searchTerms: ["circle-number-4-filled"]
			}, {
				title: "ti ti-circle-number-5",
				searchTerms: ["circle-number-5"]
			}, {
				title: "ti ti-circle-number-5-filled",
				searchTerms: ["circle-number-5-filled"]
			}, {
				title: "ti ti-circle-number-6",
				searchTerms: ["circle-number-6"]
			}, {
				title: "ti ti-circle-number-6-filled",
				searchTerms: ["circle-number-6-filled"]
			}, {
				title: "ti ti-circle-number-7",
				searchTerms: ["circle-number-7"]
			}, {
				title: "ti ti-circle-number-7-filled",
				searchTerms: ["circle-number-7-filled"]
			}, {
				title: "ti ti-circle-number-8",
				searchTerms: ["circle-number-8"]
			}, {
				title: "ti ti-circle-number-8-filled",
				searchTerms: ["circle-number-8-filled"]
			}, {
				title: "ti ti-circle-number-9",
				searchTerms: ["circle-number-9"]
			}, {
				title: "ti ti-circle-number-9-filled",
				searchTerms: ["circle-number-9-filled"]
			}, {
				title: "ti ti-circle-off",
				searchTerms: ["circle-off"]
			}, {
				title: "ti ti-circle-percentage",
				searchTerms: ["circle-percentage"]
			}, {
				title: "ti ti-circle-percentage-filled",
				searchTerms: ["circle-percentage-filled"]
			}, {
				title: "ti ti-circle-plus",
				searchTerms: ["circle-plus"]
			}, {
				title: "ti ti-circle-plus-2",
				searchTerms: ["circle-plus-2"]
			}, {
				title: "ti ti-circle-rectangle",
				searchTerms: ["circle-rectangle"]
			}, {
				title: "ti ti-circle-rectangle-off",
				searchTerms: ["circle-rectangle-off"]
			}, {
				title: "ti ti-circle-square",
				searchTerms: ["circle-square"]
			}, {
				title: "ti ti-circle-triangle",
				searchTerms: ["circle-triangle"]
			}, {
				title: "ti ti-circle-x",
				searchTerms: ["circle-x"]
			}, {
				title: "ti ti-circle-x-filled",
				searchTerms: ["circle-x-filled"]
			}, {
				title: "ti ti-circles",
				searchTerms: ["circles"]
			}, {
				title: "ti ti-circles-filled",
				searchTerms: ["circles-filled"]
			}, {
				title: "ti ti-circles-relation",
				searchTerms: ["circles-relation"]
			}, {
				title: "ti ti-circuit-ammeter",
				searchTerms: ["circuit-ammeter"]
			}, {
				title: "ti ti-circuit-battery",
				searchTerms: ["circuit-battery"]
			}, {
				title: "ti ti-circuit-bulb",
				searchTerms: ["circuit-bulb"]
			}, {
				title: "ti ti-circuit-capacitor",
				searchTerms: ["circuit-capacitor"]
			}, {
				title: "ti ti-circuit-capacitor-polarized",
				searchTerms: ["circuit-capacitor-polarized"]
			}, {
				title: "ti ti-circuit-cell",
				searchTerms: ["circuit-cell"]
			}, {
				title: "ti ti-circuit-cell-plus",
				searchTerms: ["circuit-cell-plus"]
			}, {
				title: "ti ti-circuit-changeover",
				searchTerms: ["circuit-changeover"]
			}, {
				title: "ti ti-circuit-diode",
				searchTerms: ["circuit-diode"]
			}, {
				title: "ti ti-circuit-diode-zener",
				searchTerms: ["circuit-diode-zener"]
			}, {
				title: "ti ti-circuit-ground",
				searchTerms: ["circuit-ground"]
			}, {
				title: "ti ti-circuit-ground-digital",
				searchTerms: ["circuit-ground-digital"]
			}, {
				title: "ti ti-circuit-inductor",
				searchTerms: ["circuit-inductor"]
			}, {
				title: "ti ti-circuit-motor",
				searchTerms: ["circuit-motor"]
			}, {
				title: "ti ti-circuit-pushbutton",
				searchTerms: ["circuit-pushbutton"]
			}, {
				title: "ti ti-circuit-resistor",
				searchTerms: ["circuit-resistor"]
			}, {
				title: "ti ti-circuit-switch-closed",
				searchTerms: ["circuit-switch-closed"]
			}, {
				title: "ti ti-circuit-switch-open",
				searchTerms: ["circuit-switch-open"]
			}, {
				title: "ti ti-circuit-voltmeter",
				searchTerms: ["circuit-voltmeter"]
			}, {
				title: "ti ti-clear-all",
				searchTerms: ["clear-all"]
			}, {
				title: "ti ti-clear-formatting",
				searchTerms: ["clear-formatting"]
			}, {
				title: "ti ti-click",
				searchTerms: ["click"]
			}, {
				title: "ti ti-clipboard",
				searchTerms: ["clipboard"]
			}, {
				title: "ti ti-clipboard-check",
				searchTerms: ["clipboard-check"]
			}, {
				title: "ti ti-clipboard-copy",
				searchTerms: ["clipboard-copy"]
			}, {
				title: "ti ti-clipboard-data",
				searchTerms: ["clipboard-data"]
			}, {
				title: "ti ti-clipboard-heart",
				searchTerms: ["clipboard-heart"]
			}, {
				title: "ti ti-clipboard-list",
				searchTerms: ["clipboard-list"]
			}, {
				title: "ti ti-clipboard-off",
				searchTerms: ["clipboard-off"]
			}, {
				title: "ti ti-clipboard-plus",
				searchTerms: ["clipboard-plus"]
			}, {
				title: "ti ti-clipboard-smile",
				searchTerms: ["clipboard-smile"]
			}, {
				title: "ti ti-clipboard-text",
				searchTerms: ["clipboard-text"]
			}, {
				title: "ti ti-clipboard-typography",
				searchTerms: ["clipboard-typography"]
			}, {
				title: "ti ti-clipboard-x",
				searchTerms: ["clipboard-x"]
			}, {
				title: "ti ti-clock",
				searchTerms: ["clock"]
			}, {
				title: "ti ti-clock-12",
				searchTerms: ["clock-12"]
			}, {
				title: "ti ti-clock-2",
				searchTerms: ["clock-2"]
			}, {
				title: "ti ti-clock-24",
				searchTerms: ["clock-24"]
			}, {
				title: "ti ti-clock-bolt",
				searchTerms: ["clock-bolt"]
			}, {
				title: "ti ti-clock-cancel",
				searchTerms: ["clock-cancel"]
			}, {
				title: "ti ti-clock-check",
				searchTerms: ["clock-check"]
			}, {
				title: "ti ti-clock-code",
				searchTerms: ["clock-code"]
			}, {
				title: "ti ti-clock-cog",
				searchTerms: ["clock-cog"]
			}, {
				title: "ti ti-clock-dollar",
				searchTerms: ["clock-dollar"]
			}, {
				title: "ti ti-clock-down",
				searchTerms: ["clock-down"]
			}, {
				title: "ti ti-clock-edit",
				searchTerms: ["clock-edit"]
			}, {
				title: "ti ti-clock-exclamation",
				searchTerms: ["clock-exclamation"]
			}, {
				title: "ti ti-clock-filled",
				searchTerms: ["clock-filled"]
			}, {
				title: "ti ti-clock-heart",
				searchTerms: ["clock-heart"]
			}, {
				title: "ti ti-clock-hour-1",
				searchTerms: ["clock-hour-1"]
			}, {
				title: "ti ti-clock-hour-1-filled",
				searchTerms: ["clock-hour-1-filled"]
			}, {
				title: "ti ti-clock-hour-10",
				searchTerms: ["clock-hour-10"]
			}, {
				title: "ti ti-clock-hour-10-filled",
				searchTerms: ["clock-hour-10-filled"]
			}, {
				title: "ti ti-clock-hour-11",
				searchTerms: ["clock-hour-11"]
			}, {
				title: "ti ti-clock-hour-11-filled",
				searchTerms: ["clock-hour-11-filled"]
			}, {
				title: "ti ti-clock-hour-12",
				searchTerms: ["clock-hour-12"]
			}, {
				title: "ti ti-clock-hour-12-filled",
				searchTerms: ["clock-hour-12-filled"]
			}, {
				title: "ti ti-clock-hour-2",
				searchTerms: ["clock-hour-2"]
			}, {
				title: "ti ti-clock-hour-2-filled",
				searchTerms: ["clock-hour-2-filled"]
			}, {
				title: "ti ti-clock-hour-3",
				searchTerms: ["clock-hour-3"]
			}, {
				title: "ti ti-clock-hour-3-filled",
				searchTerms: ["clock-hour-3-filled"]
			}, {
				title: "ti ti-clock-hour-4",
				searchTerms: ["clock-hour-4"]
			}, {
				title: "ti ti-clock-hour-4-filled",
				searchTerms: ["clock-hour-4-filled"]
			}, {
				title: "ti ti-clock-hour-5",
				searchTerms: ["clock-hour-5"]
			}, {
				title: "ti ti-clock-hour-5-filled",
				searchTerms: ["clock-hour-5-filled"]
			}, {
				title: "ti ti-clock-hour-6",
				searchTerms: ["clock-hour-6"]
			}, {
				title: "ti ti-clock-hour-6-filled",
				searchTerms: ["clock-hour-6-filled"]
			}, {
				title: "ti ti-clock-hour-7",
				searchTerms: ["clock-hour-7"]
			}, {
				title: "ti ti-clock-hour-7-filled",
				searchTerms: ["clock-hour-7-filled"]
			}, {
				title: "ti ti-clock-hour-8",
				searchTerms: ["clock-hour-8"]
			}, {
				title: "ti ti-clock-hour-8-filled",
				searchTerms: ["clock-hour-8-filled"]
			}, {
				title: "ti ti-clock-hour-9",
				searchTerms: ["clock-hour-9"]
			}, {
				title: "ti ti-clock-hour-9-filled",
				searchTerms: ["clock-hour-9-filled"]
			}, {
				title: "ti ti-clock-minus",
				searchTerms: ["clock-minus"]
			}, {
				title: "ti ti-clock-off",
				searchTerms: ["clock-off"]
			}, {
				title: "ti ti-clock-pause",
				searchTerms: ["clock-pause"]
			}, {
				title: "ti ti-clock-pin",
				searchTerms: ["clock-pin"]
			}, {
				title: "ti ti-clock-play",
				searchTerms: ["clock-play"]
			}, {
				title: "ti ti-clock-plus",
				searchTerms: ["clock-plus"]
			}, {
				title: "ti ti-clock-question",
				searchTerms: ["clock-question"]
			}, {
				title: "ti ti-clock-record",
				searchTerms: ["clock-record"]
			}, {
				title: "ti ti-clock-search",
				searchTerms: ["clock-search"]
			}, {
				title: "ti ti-clock-share",
				searchTerms: ["clock-share"]
			}, {
				title: "ti ti-clock-shield",
				searchTerms: ["clock-shield"]
			}, {
				title: "ti ti-clock-star",
				searchTerms: ["clock-star"]
			}, {
				title: "ti ti-clock-stop",
				searchTerms: ["clock-stop"]
			}, {
				title: "ti ti-clock-up",
				searchTerms: ["clock-up"]
			}, {
				title: "ti ti-clock-x",
				searchTerms: ["clock-x"]
			}, {
				title: "ti ti-clothes-rack",
				searchTerms: ["clothes-rack"]
			}, {
				title: "ti ti-clothes-rack-off",
				searchTerms: ["clothes-rack-off"]
			}, {
				title: "ti ti-cloud",
				searchTerms: ["cloud"]
			}, {
				title: "ti ti-cloud-bolt",
				searchTerms: ["cloud-bolt"]
			}, {
				title: "ti ti-cloud-cancel",
				searchTerms: ["cloud-cancel"]
			}, {
				title: "ti ti-cloud-check",
				searchTerms: ["cloud-check"]
			}, {
				title: "ti ti-cloud-code",
				searchTerms: ["cloud-code"]
			}, {
				title: "ti ti-cloud-cog",
				searchTerms: ["cloud-cog"]
			}, {
				title: "ti ti-cloud-computing",
				searchTerms: ["cloud-computing"]
			}, {
				title: "ti ti-cloud-data-connection",
				searchTerms: ["cloud-data-connection"]
			}, {
				title: "ti ti-cloud-dollar",
				searchTerms: ["cloud-dollar"]
			}, {
				title: "ti ti-cloud-down",
				searchTerms: ["cloud-down"]
			}, {
				title: "ti ti-cloud-download",
				searchTerms: ["cloud-download"]
			}, {
				title: "ti ti-cloud-exclamation",
				searchTerms: ["cloud-exclamation"]
			}, {
				title: "ti ti-cloud-filled",
				searchTerms: ["cloud-filled"]
			}, {
				title: "ti ti-cloud-fog",
				searchTerms: ["cloud-fog"]
			}, {
				title: "ti ti-cloud-heart",
				searchTerms: ["cloud-heart"]
			}, {
				title: "ti ti-cloud-lock",
				searchTerms: ["cloud-lock"]
			}, {
				title: "ti ti-cloud-lock-open",
				searchTerms: ["cloud-lock-open"]
			}, {
				title: "ti ti-cloud-minus",
				searchTerms: ["cloud-minus"]
			}, {
				title: "ti ti-cloud-network",
				searchTerms: ["cloud-network"]
			}, {
				title: "ti ti-cloud-off",
				searchTerms: ["cloud-off"]
			}, {
				title: "ti ti-cloud-pause",
				searchTerms: ["cloud-pause"]
			}, {
				title: "ti ti-cloud-pin",
				searchTerms: ["cloud-pin"]
			}, {
				title: "ti ti-cloud-plus",
				searchTerms: ["cloud-plus"]
			}, {
				title: "ti ti-cloud-question",
				searchTerms: ["cloud-question"]
			}, {
				title: "ti ti-cloud-rain",
				searchTerms: ["cloud-rain"]
			}, {
				title: "ti ti-cloud-search",
				searchTerms: ["cloud-search"]
			}, {
				title: "ti ti-cloud-share",
				searchTerms: ["cloud-share"]
			}, {
				title: "ti ti-cloud-snow",
				searchTerms: ["cloud-snow"]
			}, {
				title: "ti ti-cloud-star",
				searchTerms: ["cloud-star"]
			}, {
				title: "ti ti-cloud-storm",
				searchTerms: ["cloud-storm"]
			}, {
				title: "ti ti-cloud-up",
				searchTerms: ["cloud-up"]
			}, {
				title: "ti ti-cloud-upload",
				searchTerms: ["cloud-upload"]
			}, {
				title: "ti ti-cloud-x",
				searchTerms: ["cloud-x"]
			}, {
				title: "ti ti-clover",
				searchTerms: ["clover"]
			}, {
				title: "ti ti-clover-2",
				searchTerms: ["clover-2"]
			}, {
				title: "ti ti-clubs",
				searchTerms: ["clubs"]
			}, {
				title: "ti ti-clubs-filled",
				searchTerms: ["clubs-filled"]
			}, {
				title: "ti ti-code",
				searchTerms: ["code"]
			}, {
				title: "ti ti-code-asterisk",
				searchTerms: ["code-asterisk"]
			}, {
				title: "ti ti-code-circle",
				searchTerms: ["code-circle"]
			}, {
				title: "ti ti-code-circle-2",
				searchTerms: ["code-circle-2"]
			}, {
				title: "ti ti-code-circle-2-filled",
				searchTerms: ["code-circle-2-filled"]
			}, {
				title: "ti ti-code-circle-filled",
				searchTerms: ["code-circle-filled"]
			}, {
				title: "ti ti-code-dots",
				searchTerms: ["code-dots"]
			}, {
				title: "ti ti-code-minus",
				searchTerms: ["code-minus"]
			}, {
				title: "ti ti-code-off",
				searchTerms: ["code-off"]
			}, {
				title: "ti ti-code-plus",
				searchTerms: ["code-plus"]
			}, {
				title: "ti ti-coffee",
				searchTerms: ["coffee"]
			}, {
				title: "ti ti-coffee-off",
				searchTerms: ["coffee-off"]
			}, {
				title: "ti ti-coffin",
				searchTerms: ["coffin"]
			}, {
				title: "ti ti-coin",
				searchTerms: ["coin"]
			}, {
				title: "ti ti-coin-bitcoin",
				searchTerms: ["coin-bitcoin"]
			}, {
				title: "ti ti-coin-bitcoin-filled",
				searchTerms: ["coin-bitcoin-filled"]
			}, {
				title: "ti ti-coin-euro",
				searchTerms: ["coin-euro"]
			}, {
				title: "ti ti-coin-euro-filled",
				searchTerms: ["coin-euro-filled"]
			}, {
				title: "ti ti-coin-filled",
				searchTerms: ["coin-filled"]
			}, {
				title: "ti ti-coin-monero",
				searchTerms: ["coin-monero"]
			}, {
				title: "ti ti-coin-monero-filled",
				searchTerms: ["coin-monero-filled"]
			}, {
				title: "ti ti-coin-off",
				searchTerms: ["coin-off"]
			}, {
				title: "ti ti-coin-pound",
				searchTerms: ["coin-pound"]
			}, {
				title: "ti ti-coin-pound-filled",
				searchTerms: ["coin-pound-filled"]
			}, {
				title: "ti ti-coin-rupee",
				searchTerms: ["coin-rupee"]
			}, {
				title: "ti ti-coin-rupee-filled",
				searchTerms: ["coin-rupee-filled"]
			}, {
				title: "ti ti-coin-taka",
				searchTerms: ["coin-taka"]
			}, {
				title: "ti ti-coin-taka-filled",
				searchTerms: ["coin-taka-filled"]
			}, {
				title: "ti ti-coin-yen",
				searchTerms: ["coin-yen"]
			}, {
				title: "ti ti-coin-yen-filled",
				searchTerms: ["coin-yen-filled"]
			}, {
				title: "ti ti-coin-yuan",
				searchTerms: ["coin-yuan"]
			}, {
				title: "ti ti-coin-yuan-filled",
				searchTerms: ["coin-yuan-filled"]
			}, {
				title: "ti ti-coins",
				searchTerms: ["coins"]
			}, {
				title: "ti ti-color-filter",
				searchTerms: ["color-filter"]
			}, {
				title: "ti ti-color-picker",
				searchTerms: ["color-picker"]
			}, {
				title: "ti ti-color-picker-off",
				searchTerms: ["color-picker-off"]
			}, {
				title: "ti ti-color-swatch",
				searchTerms: ["color-swatch"]
			}, {
				title: "ti ti-color-swatch-off",
				searchTerms: ["color-swatch-off"]
			}, {
				title: "ti ti-column-insert-left",
				searchTerms: ["column-insert-left"]
			}, {
				title: "ti ti-column-insert-right",
				searchTerms: ["column-insert-right"]
			}, {
				title: "ti ti-column-remove",
				searchTerms: ["column-remove"]
			}, {
				title: "ti ti-columns",
				searchTerms: ["columns"]
			}, {
				title: "ti ti-columns-1",
				searchTerms: ["columns-1"]
			}, {
				title: "ti ti-columns-2",
				searchTerms: ["columns-2"]
			}, {
				title: "ti ti-columns-3",
				searchTerms: ["columns-3"]
			}, {
				title: "ti ti-columns-off",
				searchTerms: ["columns-off"]
			}, {
				title: "ti ti-comet",
				searchTerms: ["comet"]
			}, {
				title: "ti ti-command",
				searchTerms: ["command"]
			}, {
				title: "ti ti-command-off",
				searchTerms: ["command-off"]
			}, {
				title: "ti ti-compass",
				searchTerms: ["compass"]
			}, {
				title: "ti ti-compass-filled",
				searchTerms: ["compass-filled"]
			}, {
				title: "ti ti-compass-off",
				searchTerms: ["compass-off"]
			}, {
				title: "ti ti-components",
				searchTerms: ["components"]
			}, {
				title: "ti ti-components-off",
				searchTerms: ["components-off"]
			}, {
				title: "ti ti-cone",
				searchTerms: ["cone"]
			}, {
				title: "ti ti-cone-2",
				searchTerms: ["cone-2"]
			}, {
				title: "ti ti-cone-2-filled",
				searchTerms: ["cone-2-filled"]
			}, {
				title: "ti ti-cone-filled",
				searchTerms: ["cone-filled"]
			}, {
				title: "ti ti-cone-off",
				searchTerms: ["cone-off"]
			}, {
				title: "ti ti-cone-plus",
				searchTerms: ["cone-plus"]
			}, {
				title: "ti ti-confetti",
				searchTerms: ["confetti"]
			}, {
				title: "ti ti-confetti-off",
				searchTerms: ["confetti-off"]
			}, {
				title: "ti ti-confucius",
				searchTerms: ["confucius"]
			}, {
				title: "ti ti-container",
				searchTerms: ["container"]
			}, {
				title: "ti ti-container-off",
				searchTerms: ["container-off"]
			}, {
				title: "ti ti-contrast",
				searchTerms: ["contrast"]
			}, {
				title: "ti ti-contrast-2",
				searchTerms: ["contrast-2"]
			}, {
				title: "ti ti-contrast-2-filled",
				searchTerms: ["contrast-2-filled"]
			}, {
				title: "ti ti-contrast-2-off",
				searchTerms: ["contrast-2-off"]
			}, {
				title: "ti ti-contrast-filled",
				searchTerms: ["contrast-filled"]
			}, {
				title: "ti ti-contrast-off",
				searchTerms: ["contrast-off"]
			}, {
				title: "ti ti-cooker",
				searchTerms: ["cooker"]
			}, {
				title: "ti ti-cookie",
				searchTerms: ["cookie"]
			}, {
				title: "ti ti-cookie-filled",
				searchTerms: ["cookie-filled"]
			}, {
				title: "ti ti-cookie-man",
				searchTerms: ["cookie-man"]
			}, {
				title: "ti ti-cookie-man-filled",
				searchTerms: ["cookie-man-filled"]
			}, {
				title: "ti ti-cookie-off",
				searchTerms: ["cookie-off"]
			}, {
				title: "ti ti-copy",
				searchTerms: ["copy"]
			}, {
				title: "ti ti-copy-check",
				searchTerms: ["copy-check"]
			}, {
				title: "ti ti-copy-check-filled",
				searchTerms: ["copy-check-filled"]
			}, {
				title: "ti ti-copy-minus",
				searchTerms: ["copy-minus"]
			}, {
				title: "ti ti-copy-minus-filled",
				searchTerms: ["copy-minus-filled"]
			}, {
				title: "ti ti-copy-off",
				searchTerms: ["copy-off"]
			}, {
				title: "ti ti-copy-plus",
				searchTerms: ["copy-plus"]
			}, {
				title: "ti ti-copy-plus-filled",
				searchTerms: ["copy-plus-filled"]
			}, {
				title: "ti ti-copy-x",
				searchTerms: ["copy-x"]
			}, {
				title: "ti ti-copy-x-filled",
				searchTerms: ["copy-x-filled"]
			}, {
				title: "ti ti-copyleft",
				searchTerms: ["copyleft"]
			}, {
				title: "ti ti-copyleft-filled",
				searchTerms: ["copyleft-filled"]
			}, {
				title: "ti ti-copyleft-off",
				searchTerms: ["copyleft-off"]
			}, {
				title: "ti ti-copyright",
				searchTerms: ["copyright"]
			}, {
				title: "ti ti-copyright-filled",
				searchTerms: ["copyright-filled"]
			}, {
				title: "ti ti-copyright-off",
				searchTerms: ["copyright-off"]
			}, {
				title: "ti ti-corner-down-left",
				searchTerms: ["corner-down-left"]
			}, {
				title: "ti ti-corner-down-left-double",
				searchTerms: ["corner-down-left-double"]
			}, {
				title: "ti ti-corner-down-right",
				searchTerms: ["corner-down-right"]
			}, {
				title: "ti ti-corner-down-right-double",
				searchTerms: ["corner-down-right-double"]
			}, {
				title: "ti ti-corner-left-down",
				searchTerms: ["corner-left-down"]
			}, {
				title: "ti ti-corner-left-down-double",
				searchTerms: ["corner-left-down-double"]
			}, {
				title: "ti ti-corner-left-up",
				searchTerms: ["corner-left-up"]
			}, {
				title: "ti ti-corner-left-up-double",
				searchTerms: ["corner-left-up-double"]
			}, {
				title: "ti ti-corner-right-down",
				searchTerms: ["corner-right-down"]
			}, {
				title: "ti ti-corner-right-down-double",
				searchTerms: ["corner-right-down-double"]
			}, {
				title: "ti ti-corner-right-up",
				searchTerms: ["corner-right-up"]
			}, {
				title: "ti ti-corner-right-up-double",
				searchTerms: ["corner-right-up-double"]
			}, {
				title: "ti ti-corner-up-left",
				searchTerms: ["corner-up-left"]
			}, {
				title: "ti ti-corner-up-left-double",
				searchTerms: ["corner-up-left-double"]
			}, {
				title: "ti ti-corner-up-right",
				searchTerms: ["corner-up-right"]
			}, {
				title: "ti ti-corner-up-right-double",
				searchTerms: ["corner-up-right-double"]
			}, {
				title: "ti ti-cpu",
				searchTerms: ["cpu"]
			}, {
				title: "ti ti-cpu-2",
				searchTerms: ["cpu-2"]
			}, {
				title: "ti ti-cpu-off",
				searchTerms: ["cpu-off"]
			}, {
				title: "ti ti-crane",
				searchTerms: ["crane"]
			}, {
				title: "ti ti-crane-off",
				searchTerms: ["crane-off"]
			}, {
				title: "ti ti-creative-commons",
				searchTerms: ["creative-commons"]
			}, {
				title: "ti ti-creative-commons-by",
				searchTerms: ["creative-commons-by"]
			}, {
				title: "ti ti-creative-commons-nc",
				searchTerms: ["creative-commons-nc"]
			}, {
				title: "ti ti-creative-commons-nd",
				searchTerms: ["creative-commons-nd"]
			}, {
				title: "ti ti-creative-commons-off",
				searchTerms: ["creative-commons-off"]
			}, {
				title: "ti ti-creative-commons-sa",
				searchTerms: ["creative-commons-sa"]
			}, {
				title: "ti ti-creative-commons-zero",
				searchTerms: ["creative-commons-zero"]
			}, {
				title: "ti ti-credit-card",
				searchTerms: ["credit-card"]
			}, {
				title: "ti ti-credit-card-filled",
				searchTerms: ["credit-card-filled"]
			}, {
				title: "ti ti-credit-card-off",
				searchTerms: ["credit-card-off"]
			}, {
				title: "ti ti-credit-card-pay",
				searchTerms: ["credit-card-pay"]
			}, {
				title: "ti ti-credit-card-refund",
				searchTerms: ["credit-card-refund"]
			}, {
				title: "ti ti-cricket",
				searchTerms: ["cricket"]
			}, {
				title: "ti ti-crop",
				searchTerms: ["crop"]
			}, {
				title: "ti ti-crop-1-1",
				searchTerms: ["crop-1-1"]
			}, {
				title: "ti ti-crop-1-1-filled",
				searchTerms: ["crop-1-1-filled"]
			}, {
				title: "ti ti-crop-16-9",
				searchTerms: ["crop-16-9"]
			}, {
				title: "ti ti-crop-16-9-filled",
				searchTerms: ["crop-16-9-filled"]
			}, {
				title: "ti ti-crop-3-2",
				searchTerms: ["crop-3-2"]
			}, {
				title: "ti ti-crop-3-2-filled",
				searchTerms: ["crop-3-2-filled"]
			}, {
				title: "ti ti-crop-5-4",
				searchTerms: ["crop-5-4"]
			}, {
				title: "ti ti-crop-5-4-filled",
				searchTerms: ["crop-5-4-filled"]
			}, {
				title: "ti ti-crop-7-5",
				searchTerms: ["crop-7-5"]
			}, {
				title: "ti ti-crop-7-5-filled",
				searchTerms: ["crop-7-5-filled"]
			}, {
				title: "ti ti-crop-landscape",
				searchTerms: ["crop-landscape"]
			}, {
				title: "ti ti-crop-landscape-filled",
				searchTerms: ["crop-landscape-filled"]
			}, {
				title: "ti ti-crop-portrait",
				searchTerms: ["crop-portrait"]
			}, {
				title: "ti ti-crop-portrait-filled",
				searchTerms: ["crop-portrait-filled"]
			}, {
				title: "ti ti-cross",
				searchTerms: ["cross"]
			}, {
				title: "ti ti-cross-filled",
				searchTerms: ["cross-filled"]
			}, {
				title: "ti ti-cross-off",
				searchTerms: ["cross-off"]
			}, {
				title: "ti ti-crosshair",
				searchTerms: ["crosshair"]
			}, {
				title: "ti ti-crown",
				searchTerms: ["crown"]
			}, {
				title: "ti ti-crown-off",
				searchTerms: ["crown-off"]
			}, {
				title: "ti ti-crutches",
				searchTerms: ["crutches"]
			}, {
				title: "ti ti-crutches-off",
				searchTerms: ["crutches-off"]
			}, {
				title: "ti ti-crystal-ball",
				searchTerms: ["crystal-ball"]
			}, {
				title: "ti ti-csv",
				searchTerms: ["csv"]
			}, {
				title: "ti ti-cube",
				searchTerms: ["cube"]
			}, {
				title: "ti ti-cube-3d-sphere",
				searchTerms: ["cube-3d-sphere"]
			}, {
				title: "ti ti-cube-3d-sphere-off",
				searchTerms: ["cube-3d-sphere-off"]
			}, {
				title: "ti ti-cube-off",
				searchTerms: ["cube-off"]
			}, {
				title: "ti ti-cube-plus",
				searchTerms: ["cube-plus"]
			}, {
				title: "ti ti-cube-send",
				searchTerms: ["cube-send"]
			}, {
				title: "ti ti-cube-unfolded",
				searchTerms: ["cube-unfolded"]
			}, {
				title: "ti ti-cup",
				searchTerms: ["cup"]
			}, {
				title: "ti ti-cup-off",
				searchTerms: ["cup-off"]
			}, {
				title: "ti ti-curling",
				searchTerms: ["curling"]
			}, {
				title: "ti ti-curly-loop",
				searchTerms: ["curly-loop"]
			}, {
				title: "ti ti-currency",
				searchTerms: ["currency"]
			}, {
				title: "ti ti-currency-afghani",
				searchTerms: ["currency-afghani"]
			}, {
				title: "ti ti-currency-bahraini",
				searchTerms: ["currency-bahraini"]
			}, {
				title: "ti ti-currency-baht",
				searchTerms: ["currency-baht"]
			}, {
				title: "ti ti-currency-bitcoin",
				searchTerms: ["currency-bitcoin"]
			}, {
				title: "ti ti-currency-cent",
				searchTerms: ["currency-cent"]
			}, {
				title: "ti ti-currency-dinar",
				searchTerms: ["currency-dinar"]
			}, {
				title: "ti ti-currency-dirham",
				searchTerms: ["currency-dirham"]
			}, {
				title: "ti ti-currency-dogecoin",
				searchTerms: ["currency-dogecoin"]
			}, {
				title: "ti ti-currency-dollar",
				searchTerms: ["currency-dollar"]
			}, {
				title: "ti ti-currency-dollar-australian",
				searchTerms: ["currency-dollar-australian"]
			}, {
				title: "ti ti-currency-dollar-brunei",
				searchTerms: ["currency-dollar-brunei"]
			}, {
				title: "ti ti-currency-dollar-canadian",
				searchTerms: ["currency-dollar-canadian"]
			}, {
				title: "ti ti-currency-dollar-guyanese",
				searchTerms: ["currency-dollar-guyanese"]
			}, {
				title: "ti ti-currency-dollar-off",
				searchTerms: ["currency-dollar-off"]
			}, {
				title: "ti ti-currency-dollar-singapore",
				searchTerms: ["currency-dollar-singapore"]
			}, {
				title: "ti ti-currency-dollar-zimbabwean",
				searchTerms: ["currency-dollar-zimbabwean"]
			}, {
				title: "ti ti-currency-dong",
				searchTerms: ["currency-dong"]
			}, {
				title: "ti ti-currency-dram",
				searchTerms: ["currency-dram"]
			}, {
				title: "ti ti-currency-ethereum",
				searchTerms: ["currency-ethereum"]
			}, {
				title: "ti ti-currency-euro",
				searchTerms: ["currency-euro"]
			}, {
				title: "ti ti-currency-euro-off",
				searchTerms: ["currency-euro-off"]
			}, {
				title: "ti ti-currency-florin",
				searchTerms: ["currency-florin"]
			}, {
				title: "ti ti-currency-forint",
				searchTerms: ["currency-forint"]
			}, {
				title: "ti ti-currency-frank",
				searchTerms: ["currency-frank"]
			}, {
				title: "ti ti-currency-guarani",
				searchTerms: ["currency-guarani"]
			}, {
				title: "ti ti-currency-hryvnia",
				searchTerms: ["currency-hryvnia"]
			}, {
				title: "ti ti-currency-iranian-rial",
				searchTerms: ["currency-iranian-rial"]
			}, {
				title: "ti ti-currency-kip",
				searchTerms: ["currency-kip"]
			}, {
				title: "ti ti-currency-krone-czech",
				searchTerms: ["currency-krone-czech"]
			}, {
				title: "ti ti-currency-krone-danish",
				searchTerms: ["currency-krone-danish"]
			}, {
				title: "ti ti-currency-krone-swedish",
				searchTerms: ["currency-krone-swedish"]
			}, {
				title: "ti ti-currency-lari",
				searchTerms: ["currency-lari"]
			}, {
				title: "ti ti-currency-leu",
				searchTerms: ["currency-leu"]
			}, {
				title: "ti ti-currency-lira",
				searchTerms: ["currency-lira"]
			}, {
				title: "ti ti-currency-litecoin",
				searchTerms: ["currency-litecoin"]
			}, {
				title: "ti ti-currency-lyd",
				searchTerms: ["currency-lyd"]
			}, {
				title: "ti ti-currency-manat",
				searchTerms: ["currency-manat"]
			}, {
				title: "ti ti-currency-monero",
				searchTerms: ["currency-monero"]
			}, {
				title: "ti ti-currency-naira",
				searchTerms: ["currency-naira"]
			}, {
				title: "ti ti-currency-nano",
				searchTerms: ["currency-nano"]
			}, {
				title: "ti ti-currency-off",
				searchTerms: ["currency-off"]
			}, {
				title: "ti ti-currency-paanga",
				searchTerms: ["currency-paanga"]
			}, {
				title: "ti ti-currency-peso",
				searchTerms: ["currency-peso"]
			}, {
				title: "ti ti-currency-pound",
				searchTerms: ["currency-pound"]
			}, {
				title: "ti ti-currency-pound-off",
				searchTerms: ["currency-pound-off"]
			}, {
				title: "ti ti-currency-quetzal",
				searchTerms: ["currency-quetzal"]
			}, {
				title: "ti ti-currency-real",
				searchTerms: ["currency-real"]
			}, {
				title: "ti ti-currency-renminbi",
				searchTerms: ["currency-renminbi"]
			}, {
				title: "ti ti-currency-ripple",
				searchTerms: ["currency-ripple"]
			}, {
				title: "ti ti-currency-riyal",
				searchTerms: ["currency-riyal"]
			}, {
				title: "ti ti-currency-rubel",
				searchTerms: ["currency-rubel"]
			}, {
				title: "ti ti-currency-rufiyaa",
				searchTerms: ["currency-rufiyaa"]
			}, {
				title: "ti ti-currency-rupee",
				searchTerms: ["currency-rupee"]
			}, {
				title: "ti ti-currency-rupee-nepalese",
				searchTerms: ["currency-rupee-nepalese"]
			}, {
				title: "ti ti-currency-shekel",
				searchTerms: ["currency-shekel"]
			}, {
				title: "ti ti-currency-solana",
				searchTerms: ["currency-solana"]
			}, {
				title: "ti ti-currency-som",
				searchTerms: ["currency-som"]
			}, {
				title: "ti ti-currency-taka",
				searchTerms: ["currency-taka"]
			}, {
				title: "ti ti-currency-tenge",
				searchTerms: ["currency-tenge"]
			}, {
				title: "ti ti-currency-tugrik",
				searchTerms: ["currency-tugrik"]
			}, {
				title: "ti ti-currency-won",
				searchTerms: ["currency-won"]
			}, {
				title: "ti ti-currency-xrp",
				searchTerms: ["currency-xrp"]
			}, {
				title: "ti ti-currency-yen",
				searchTerms: ["currency-yen"]
			}, {
				title: "ti ti-currency-yen-off",
				searchTerms: ["currency-yen-off"]
			}, {
				title: "ti ti-currency-yuan",
				searchTerms: ["currency-yuan"]
			}, {
				title: "ti ti-currency-zloty",
				searchTerms: ["currency-zloty"]
			}, {
				title: "ti ti-current-location",
				searchTerms: ["current-location"]
			}, {
				title: "ti ti-current-location-off",
				searchTerms: ["current-location-off"]
			}, {
				title: "ti ti-cursor-off",
				searchTerms: ["cursor-off"]
			}, {
				title: "ti ti-cursor-text",
				searchTerms: ["cursor-text"]
			}, {
				title: "ti ti-cut",
				searchTerms: ["cut"]
			}, {
				title: "ti ti-cylinder",
				searchTerms: ["cylinder"]
			}, {
				title: "ti ti-cylinder-off",
				searchTerms: ["cylinder-off"]
			}, {
				title: "ti ti-cylinder-plus",
				searchTerms: ["cylinder-plus"]
			}, {
				title: "ti ti-dashboard",
				searchTerms: ["dashboard"]
			}, {
				title: "ti ti-dashboard-off",
				searchTerms: ["dashboard-off"]
			}, {
				title: "ti ti-database",
				searchTerms: ["database"]
			}, {
				title: "ti ti-database-cog",
				searchTerms: ["database-cog"]
			}, {
				title: "ti ti-database-dollar",
				searchTerms: ["database-dollar"]
			}, {
				title: "ti ti-database-edit",
				searchTerms: ["database-edit"]
			}, {
				title: "ti ti-database-exclamation",
				searchTerms: ["database-exclamation"]
			}, {
				title: "ti ti-database-export",
				searchTerms: ["database-export"]
			}, {
				title: "ti ti-database-heart",
				searchTerms: ["database-heart"]
			}, {
				title: "ti ti-database-import",
				searchTerms: ["database-import"]
			}, {
				title: "ti ti-database-leak",
				searchTerms: ["database-leak"]
			}, {
				title: "ti ti-database-minus",
				searchTerms: ["database-minus"]
			}, {
				title: "ti ti-database-off",
				searchTerms: ["database-off"]
			}, {
				title: "ti ti-database-plus",
				searchTerms: ["database-plus"]
			}, {
				title: "ti ti-database-search",
				searchTerms: ["database-search"]
			}, {
				title: "ti ti-database-share",
				searchTerms: ["database-share"]
			}, {
				title: "ti ti-database-smile",
				searchTerms: ["database-smile"]
			}, {
				title: "ti ti-database-star",
				searchTerms: ["database-star"]
			}, {
				title: "ti ti-database-x",
				searchTerms: ["database-x"]
			}, {
				title: "ti ti-decimal",
				searchTerms: ["decimal"]
			}, {
				title: "ti ti-deer",
				searchTerms: ["deer"]
			}, {
				title: "ti ti-delta",
				searchTerms: ["delta"]
			}, {
				title: "ti ti-dental",
				searchTerms: ["dental"]
			}, {
				title: "ti ti-dental-broken",
				searchTerms: ["dental-broken"]
			}, {
				title: "ti ti-dental-off",
				searchTerms: ["dental-off"]
			}, {
				title: "ti ti-deselect",
				searchTerms: ["deselect"]
			}, {
				title: "ti ti-desk",
				searchTerms: ["desk"]
			}, {
				title: "ti ti-details",
				searchTerms: ["details"]
			}, {
				title: "ti ti-details-off",
				searchTerms: ["details-off"]
			}, {
				title: "ti ti-device-airpods",
				searchTerms: ["device-airpods"]
			}, {
				title: "ti ti-device-airpods-case",
				searchTerms: ["device-airpods-case"]
			}, {
				title: "ti ti-device-airtag",
				searchTerms: ["device-airtag"]
			}, {
				title: "ti ti-device-analytics",
				searchTerms: ["device-analytics"]
			}, {
				title: "ti ti-device-audio-tape",
				searchTerms: ["device-audio-tape"]
			}, {
				title: "ti ti-device-camera-phone",
				searchTerms: ["device-camera-phone"]
			}, {
				title: "ti ti-device-cctv",
				searchTerms: ["device-cctv"]
			}, {
				title: "ti ti-device-cctv-off",
				searchTerms: ["device-cctv-off"]
			}, {
				title: "ti ti-device-computer-camera",
				searchTerms: ["device-computer-camera"]
			}, {
				title: "ti ti-device-computer-camera-off",
				searchTerms: ["device-computer-camera-off"]
			}, {
				title: "ti ti-device-desktop",
				searchTerms: ["device-desktop"]
			}, {
				title: "ti ti-device-desktop-analytics",
				searchTerms: ["device-desktop-analytics"]
			}, {
				title: "ti ti-device-desktop-bolt",
				searchTerms: ["device-desktop-bolt"]
			}, {
				title: "ti ti-device-desktop-cancel",
				searchTerms: ["device-desktop-cancel"]
			}, {
				title: "ti ti-device-desktop-check",
				searchTerms: ["device-desktop-check"]
			}, {
				title: "ti ti-device-desktop-code",
				searchTerms: ["device-desktop-code"]
			}, {
				title: "ti ti-device-desktop-cog",
				searchTerms: ["device-desktop-cog"]
			}, {
				title: "ti ti-device-desktop-dollar",
				searchTerms: ["device-desktop-dollar"]
			}, {
				title: "ti ti-device-desktop-down",
				searchTerms: ["device-desktop-down"]
			}, {
				title: "ti ti-device-desktop-exclamation",
				searchTerms: ["device-desktop-exclamation"]
			}, {
				title: "ti ti-device-desktop-heart",
				searchTerms: ["device-desktop-heart"]
			}, {
				title: "ti ti-device-desktop-minus",
				searchTerms: ["device-desktop-minus"]
			}, {
				title: "ti ti-device-desktop-off",
				searchTerms: ["device-desktop-off"]
			}, {
				title: "ti ti-device-desktop-pause",
				searchTerms: ["device-desktop-pause"]
			}, {
				title: "ti ti-device-desktop-pin",
				searchTerms: ["device-desktop-pin"]
			}, {
				title: "ti ti-device-desktop-plus",
				searchTerms: ["device-desktop-plus"]
			}, {
				title: "ti ti-device-desktop-question",
				searchTerms: ["device-desktop-question"]
			}, {
				title: "ti ti-device-desktop-search",
				searchTerms: ["device-desktop-search"]
			}, {
				title: "ti ti-device-desktop-share",
				searchTerms: ["device-desktop-share"]
			}, {
				title: "ti ti-device-desktop-star",
				searchTerms: ["device-desktop-star"]
			}, {
				title: "ti ti-device-desktop-up",
				searchTerms: ["device-desktop-up"]
			}, {
				title: "ti ti-device-desktop-x",
				searchTerms: ["device-desktop-x"]
			}, {
				title: "ti ti-device-floppy",
				searchTerms: ["device-floppy"]
			}, {
				title: "ti ti-device-gamepad",
				searchTerms: ["device-gamepad"]
			}, {
				title: "ti ti-device-gamepad-2",
				searchTerms: ["device-gamepad-2"]
			}, {
				title: "ti ti-device-gamepad-3",
				searchTerms: ["device-gamepad-3"]
			}, {
				title: "ti ti-device-heart-monitor",
				searchTerms: ["device-heart-monitor"]
			}, {
				title: "ti ti-device-heart-monitor-filled",
				searchTerms: ["device-heart-monitor-filled"]
			}, {
				title: "ti ti-device-imac",
				searchTerms: ["device-imac"]
			}, {
				title: "ti ti-device-imac-bolt",
				searchTerms: ["device-imac-bolt"]
			}, {
				title: "ti ti-device-imac-cancel",
				searchTerms: ["device-imac-cancel"]
			}, {
				title: "ti ti-device-imac-check",
				searchTerms: ["device-imac-check"]
			}, {
				title: "ti ti-device-imac-code",
				searchTerms: ["device-imac-code"]
			}, {
				title: "ti ti-device-imac-cog",
				searchTerms: ["device-imac-cog"]
			}, {
				title: "ti ti-device-imac-dollar",
				searchTerms: ["device-imac-dollar"]
			}, {
				title: "ti ti-device-imac-down",
				searchTerms: ["device-imac-down"]
			}, {
				title: "ti ti-device-imac-exclamation",
				searchTerms: ["device-imac-exclamation"]
			}, {
				title: "ti ti-device-imac-heart",
				searchTerms: ["device-imac-heart"]
			}, {
				title: "ti ti-device-imac-minus",
				searchTerms: ["device-imac-minus"]
			}, {
				title: "ti ti-device-imac-off",
				searchTerms: ["device-imac-off"]
			}, {
				title: "ti ti-device-imac-pause",
				searchTerms: ["device-imac-pause"]
			}, {
				title: "ti ti-device-imac-pin",
				searchTerms: ["device-imac-pin"]
			}, {
				title: "ti ti-device-imac-plus",
				searchTerms: ["device-imac-plus"]
			}, {
				title: "ti ti-device-imac-question",
				searchTerms: ["device-imac-question"]
			}, {
				title: "ti ti-device-imac-search",
				searchTerms: ["device-imac-search"]
			}, {
				title: "ti ti-device-imac-share",
				searchTerms: ["device-imac-share"]
			}, {
				title: "ti ti-device-imac-star",
				searchTerms: ["device-imac-star"]
			}, {
				title: "ti ti-device-imac-up",
				searchTerms: ["device-imac-up"]
			}, {
				title: "ti ti-device-imac-x",
				searchTerms: ["device-imac-x"]
			}, {
				title: "ti ti-device-ipad",
				searchTerms: ["device-ipad"]
			}, {
				title: "ti ti-device-ipad-bolt",
				searchTerms: ["device-ipad-bolt"]
			}, {
				title: "ti ti-device-ipad-cancel",
				searchTerms: ["device-ipad-cancel"]
			}, {
				title: "ti ti-device-ipad-check",
				searchTerms: ["device-ipad-check"]
			}, {
				title: "ti ti-device-ipad-code",
				searchTerms: ["device-ipad-code"]
			}, {
				title: "ti ti-device-ipad-cog",
				searchTerms: ["device-ipad-cog"]
			}, {
				title: "ti ti-device-ipad-dollar",
				searchTerms: ["device-ipad-dollar"]
			}, {
				title: "ti ti-device-ipad-down",
				searchTerms: ["device-ipad-down"]
			}, {
				title: "ti ti-device-ipad-exclamation",
				searchTerms: ["device-ipad-exclamation"]
			}, {
				title: "ti ti-device-ipad-heart",
				searchTerms: ["device-ipad-heart"]
			}, {
				title: "ti ti-device-ipad-horizontal",
				searchTerms: ["device-ipad-horizontal"]
			}, {
				title: "ti ti-device-ipad-horizontal-bolt",
				searchTerms: ["device-ipad-horizontal-bolt"]
			}, {
				title: "ti ti-device-ipad-horizontal-cancel",
				searchTerms: ["device-ipad-horizontal-cancel"]
			}, {
				title: "ti ti-device-ipad-horizontal-check",
				searchTerms: ["device-ipad-horizontal-check"]
			}, {
				title: "ti ti-device-ipad-horizontal-code",
				searchTerms: ["device-ipad-horizontal-code"]
			}, {
				title: "ti ti-device-ipad-horizontal-cog",
				searchTerms: ["device-ipad-horizontal-cog"]
			}, {
				title: "ti ti-device-ipad-horizontal-dollar",
				searchTerms: ["device-ipad-horizontal-dollar"]
			}, {
				title: "ti ti-device-ipad-horizontal-down",
				searchTerms: ["device-ipad-horizontal-down"]
			}, {
				title: "ti ti-device-ipad-horizontal-exclamation",
				searchTerms: ["device-ipad-horizontal-exclamation"]
			}, {
				title: "ti ti-device-ipad-horizontal-heart",
				searchTerms: ["device-ipad-horizontal-heart"]
			}, {
				title: "ti ti-device-ipad-horizontal-minus",
				searchTerms: ["device-ipad-horizontal-minus"]
			}, {
				title: "ti ti-device-ipad-horizontal-off",
				searchTerms: ["device-ipad-horizontal-off"]
			}, {
				title: "ti ti-device-ipad-horizontal-pause",
				searchTerms: ["device-ipad-horizontal-pause"]
			}, {
				title: "ti ti-device-ipad-horizontal-pin",
				searchTerms: ["device-ipad-horizontal-pin"]
			}, {
				title: "ti ti-device-ipad-horizontal-plus",
				searchTerms: ["device-ipad-horizontal-plus"]
			}, {
				title: "ti ti-device-ipad-horizontal-question",
				searchTerms: ["device-ipad-horizontal-question"]
			}, {
				title: "ti ti-device-ipad-horizontal-search",
				searchTerms: ["device-ipad-horizontal-search"]
			}, {
				title: "ti ti-device-ipad-horizontal-share",
				searchTerms: ["device-ipad-horizontal-share"]
			}, {
				title: "ti ti-device-ipad-horizontal-star",
				searchTerms: ["device-ipad-horizontal-star"]
			}, {
				title: "ti ti-device-ipad-horizontal-up",
				searchTerms: ["device-ipad-horizontal-up"]
			}, {
				title: "ti ti-device-ipad-horizontal-x",
				searchTerms: ["device-ipad-horizontal-x"]
			}, {
				title: "ti ti-device-ipad-minus",
				searchTerms: ["device-ipad-minus"]
			}, {
				title: "ti ti-device-ipad-off",
				searchTerms: ["device-ipad-off"]
			}, {
				title: "ti ti-device-ipad-pause",
				searchTerms: ["device-ipad-pause"]
			}, {
				title: "ti ti-device-ipad-pin",
				searchTerms: ["device-ipad-pin"]
			}, {
				title: "ti ti-device-ipad-plus",
				searchTerms: ["device-ipad-plus"]
			}, {
				title: "ti ti-device-ipad-question",
				searchTerms: ["device-ipad-question"]
			}, {
				title: "ti ti-device-ipad-search",
				searchTerms: ["device-ipad-search"]
			}, {
				title: "ti ti-device-ipad-share",
				searchTerms: ["device-ipad-share"]
			}, {
				title: "ti ti-device-ipad-star",
				searchTerms: ["device-ipad-star"]
			}, {
				title: "ti ti-device-ipad-up",
				searchTerms: ["device-ipad-up"]
			}, {
				title: "ti ti-device-ipad-x",
				searchTerms: ["device-ipad-x"]
			}, {
				title: "ti ti-device-landline-phone",
				searchTerms: ["device-landline-phone"]
			}, {
				title: "ti ti-device-laptop",
				searchTerms: ["device-laptop"]
			}, {
				title: "ti ti-device-laptop-off",
				searchTerms: ["device-laptop-off"]
			}, {
				title: "ti ti-device-mobile",
				searchTerms: ["device-mobile"]
			}, {
				title: "ti ti-device-mobile-bolt",
				searchTerms: ["device-mobile-bolt"]
			}, {
				title: "ti ti-device-mobile-cancel",
				searchTerms: ["device-mobile-cancel"]
			}, {
				title: "ti ti-device-mobile-charging",
				searchTerms: ["device-mobile-charging"]
			}, {
				title: "ti ti-device-mobile-check",
				searchTerms: ["device-mobile-check"]
			}, {
				title: "ti ti-device-mobile-code",
				searchTerms: ["device-mobile-code"]
			}, {
				title: "ti ti-device-mobile-cog",
				searchTerms: ["device-mobile-cog"]
			}, {
				title: "ti ti-device-mobile-dollar",
				searchTerms: ["device-mobile-dollar"]
			}, {
				title: "ti ti-device-mobile-down",
				searchTerms: ["device-mobile-down"]
			}, {
				title: "ti ti-device-mobile-exclamation",
				searchTerms: ["device-mobile-exclamation"]
			}, {
				title: "ti ti-device-mobile-filled",
				searchTerms: ["device-mobile-filled"]
			}, {
				title: "ti ti-device-mobile-heart",
				searchTerms: ["device-mobile-heart"]
			}, {
				title: "ti ti-device-mobile-message",
				searchTerms: ["device-mobile-message"]
			}, {
				title: "ti ti-device-mobile-minus",
				searchTerms: ["device-mobile-minus"]
			}, {
				title: "ti ti-device-mobile-off",
				searchTerms: ["device-mobile-off"]
			}, {
				title: "ti ti-device-mobile-pause",
				searchTerms: ["device-mobile-pause"]
			}, {
				title: "ti ti-device-mobile-pin",
				searchTerms: ["device-mobile-pin"]
			}, {
				title: "ti ti-device-mobile-plus",
				searchTerms: ["device-mobile-plus"]
			}, {
				title: "ti ti-device-mobile-question",
				searchTerms: ["device-mobile-question"]
			}, {
				title: "ti ti-device-mobile-rotated",
				searchTerms: ["device-mobile-rotated"]
			}, {
				title: "ti ti-device-mobile-search",
				searchTerms: ["device-mobile-search"]
			}, {
				title: "ti ti-device-mobile-share",
				searchTerms: ["device-mobile-share"]
			}, {
				title: "ti ti-device-mobile-star",
				searchTerms: ["device-mobile-star"]
			}, {
				title: "ti ti-device-mobile-up",
				searchTerms: ["device-mobile-up"]
			}, {
				title: "ti ti-device-mobile-vibration",
				searchTerms: ["device-mobile-vibration"]
			}, {
				title: "ti ti-device-mobile-x",
				searchTerms: ["device-mobile-x"]
			}, {
				title: "ti ti-device-nintendo",
				searchTerms: ["device-nintendo"]
			}, {
				title: "ti ti-device-nintendo-off",
				searchTerms: ["device-nintendo-off"]
			}, {
				title: "ti ti-device-projector",
				searchTerms: ["device-projector"]
			}, {
				title: "ti ti-device-remote",
				searchTerms: ["device-remote"]
			}, {
				title: "ti ti-device-sd-card",
				searchTerms: ["device-sd-card"]
			}, {
				title: "ti ti-device-sim",
				searchTerms: ["device-sim"]
			}, {
				title: "ti ti-device-sim-1",
				searchTerms: ["device-sim-1"]
			}, {
				title: "ti ti-device-sim-2",
				searchTerms: ["device-sim-2"]
			}, {
				title: "ti ti-device-sim-3",
				searchTerms: ["device-sim-3"]
			}, {
				title: "ti ti-device-speaker",
				searchTerms: ["device-speaker"]
			}, {
				title: "ti ti-device-speaker-off",
				searchTerms: ["device-speaker-off"]
			}, {
				title: "ti ti-device-tablet",
				searchTerms: ["device-tablet"]
			}, {
				title: "ti ti-device-tablet-bolt",
				searchTerms: ["device-tablet-bolt"]
			}, {
				title: "ti ti-device-tablet-cancel",
				searchTerms: ["device-tablet-cancel"]
			}, {
				title: "ti ti-device-tablet-check",
				searchTerms: ["device-tablet-check"]
			}, {
				title: "ti ti-device-tablet-code",
				searchTerms: ["device-tablet-code"]
			}, {
				title: "ti ti-device-tablet-cog",
				searchTerms: ["device-tablet-cog"]
			}, {
				title: "ti ti-device-tablet-dollar",
				searchTerms: ["device-tablet-dollar"]
			}, {
				title: "ti ti-device-tablet-down",
				searchTerms: ["device-tablet-down"]
			}, {
				title: "ti ti-device-tablet-exclamation",
				searchTerms: ["device-tablet-exclamation"]
			}, {
				title: "ti ti-device-tablet-filled",
				searchTerms: ["device-tablet-filled"]
			}, {
				title: "ti ti-device-tablet-heart",
				searchTerms: ["device-tablet-heart"]
			}, {
				title: "ti ti-device-tablet-minus",
				searchTerms: ["device-tablet-minus"]
			}, {
				title: "ti ti-device-tablet-off",
				searchTerms: ["device-tablet-off"]
			}, {
				title: "ti ti-device-tablet-pause",
				searchTerms: ["device-tablet-pause"]
			}, {
				title: "ti ti-device-tablet-pin",
				searchTerms: ["device-tablet-pin"]
			}, {
				title: "ti ti-device-tablet-plus",
				searchTerms: ["device-tablet-plus"]
			}, {
				title: "ti ti-device-tablet-question",
				searchTerms: ["device-tablet-question"]
			}, {
				title: "ti ti-device-tablet-search",
				searchTerms: ["device-tablet-search"]
			}, {
				title: "ti ti-device-tablet-share",
				searchTerms: ["device-tablet-share"]
			}, {
				title: "ti ti-device-tablet-star",
				searchTerms: ["device-tablet-star"]
			}, {
				title: "ti ti-device-tablet-up",
				searchTerms: ["device-tablet-up"]
			}, {
				title: "ti ti-device-tablet-x",
				searchTerms: ["device-tablet-x"]
			}, {
				title: "ti ti-device-tv",
				searchTerms: ["device-tv"]
			}, {
				title: "ti ti-device-tv-off",
				searchTerms: ["device-tv-off"]
			}, {
				title: "ti ti-device-tv-old",
				searchTerms: ["device-tv-old"]
			}, {
				title: "ti ti-device-usb",
				searchTerms: ["device-usb"]
			}, {
				title: "ti ti-device-vision-pro",
				searchTerms: ["device-vision-pro"]
			}, {
				title: "ti ti-device-watch",
				searchTerms: ["device-watch"]
			}, {
				title: "ti ti-device-watch-bolt",
				searchTerms: ["device-watch-bolt"]
			}, {
				title: "ti ti-device-watch-cancel",
				searchTerms: ["device-watch-cancel"]
			}, {
				title: "ti ti-device-watch-check",
				searchTerms: ["device-watch-check"]
			}, {
				title: "ti ti-device-watch-code",
				searchTerms: ["device-watch-code"]
			}, {
				title: "ti ti-device-watch-cog",
				searchTerms: ["device-watch-cog"]
			}, {
				title: "ti ti-device-watch-dollar",
				searchTerms: ["device-watch-dollar"]
			}, {
				title: "ti ti-device-watch-down",
				searchTerms: ["device-watch-down"]
			}, {
				title: "ti ti-device-watch-exclamation",
				searchTerms: ["device-watch-exclamation"]
			}, {
				title: "ti ti-device-watch-heart",
				searchTerms: ["device-watch-heart"]
			}, {
				title: "ti ti-device-watch-minus",
				searchTerms: ["device-watch-minus"]
			}, {
				title: "ti ti-device-watch-off",
				searchTerms: ["device-watch-off"]
			}, {
				title: "ti ti-device-watch-pause",
				searchTerms: ["device-watch-pause"]
			}, {
				title: "ti ti-device-watch-pin",
				searchTerms: ["device-watch-pin"]
			}, {
				title: "ti ti-device-watch-plus",
				searchTerms: ["device-watch-plus"]
			}, {
				title: "ti ti-device-watch-question",
				searchTerms: ["device-watch-question"]
			}, {
				title: "ti ti-device-watch-search",
				searchTerms: ["device-watch-search"]
			}, {
				title: "ti ti-device-watch-share",
				searchTerms: ["device-watch-share"]
			}, {
				title: "ti ti-device-watch-star",
				searchTerms: ["device-watch-star"]
			}, {
				title: "ti ti-device-watch-stats",
				searchTerms: ["device-watch-stats"]
			}, {
				title: "ti ti-device-watch-stats-2",
				searchTerms: ["device-watch-stats-2"]
			}, {
				title: "ti ti-device-watch-up",
				searchTerms: ["device-watch-up"]
			}, {
				title: "ti ti-device-watch-x",
				searchTerms: ["device-watch-x"]
			}, {
				title: "ti ti-devices",
				searchTerms: ["devices"]
			}, {
				title: "ti ti-devices-2",
				searchTerms: ["devices-2"]
			}, {
				title: "ti ti-devices-bolt",
				searchTerms: ["devices-bolt"]
			}, {
				title: "ti ti-devices-cancel",
				searchTerms: ["devices-cancel"]
			}, {
				title: "ti ti-devices-check",
				searchTerms: ["devices-check"]
			}, {
				title: "ti ti-devices-code",
				searchTerms: ["devices-code"]
			}, {
				title: "ti ti-devices-cog",
				searchTerms: ["devices-cog"]
			}, {
				title: "ti ti-devices-dollar",
				searchTerms: ["devices-dollar"]
			}, {
				title: "ti ti-devices-down",
				searchTerms: ["devices-down"]
			}, {
				title: "ti ti-devices-exclamation",
				searchTerms: ["devices-exclamation"]
			}, {
				title: "ti ti-devices-heart",
				searchTerms: ["devices-heart"]
			}, {
				title: "ti ti-devices-minus",
				searchTerms: ["devices-minus"]
			}, {
				title: "ti ti-devices-off",
				searchTerms: ["devices-off"]
			}, {
				title: "ti ti-devices-pause",
				searchTerms: ["devices-pause"]
			}, {
				title: "ti ti-devices-pc",
				searchTerms: ["devices-pc"]
			}, {
				title: "ti ti-devices-pc-off",
				searchTerms: ["devices-pc-off"]
			}, {
				title: "ti ti-devices-pin",
				searchTerms: ["devices-pin"]
			}, {
				title: "ti ti-devices-plus",
				searchTerms: ["devices-plus"]
			}, {
				title: "ti ti-devices-question",
				searchTerms: ["devices-question"]
			}, {
				title: "ti ti-devices-search",
				searchTerms: ["devices-search"]
			}, {
				title: "ti ti-devices-share",
				searchTerms: ["devices-share"]
			}, {
				title: "ti ti-devices-star",
				searchTerms: ["devices-star"]
			}, {
				title: "ti ti-devices-up",
				searchTerms: ["devices-up"]
			}, {
				title: "ti ti-devices-x",
				searchTerms: ["devices-x"]
			}, {
				title: "ti ti-diabolo",
				searchTerms: ["diabolo"]
			}, {
				title: "ti ti-diabolo-off",
				searchTerms: ["diabolo-off"]
			}, {
				title: "ti ti-diabolo-plus",
				searchTerms: ["diabolo-plus"]
			}, {
				title: "ti ti-dialpad",
				searchTerms: ["dialpad"]
			}, {
				title: "ti ti-dialpad-filled",
				searchTerms: ["dialpad-filled"]
			}, {
				title: "ti ti-dialpad-off",
				searchTerms: ["dialpad-off"]
			}, {
				title: "ti ti-diamond",
				searchTerms: ["diamond"]
			}, {
				title: "ti ti-diamond-filled",
				searchTerms: ["diamond-filled"]
			}, {
				title: "ti ti-diamond-off",
				searchTerms: ["diamond-off"]
			}, {
				title: "ti ti-diamonds",
				searchTerms: ["diamonds"]
			}, {
				title: "ti ti-diamonds-filled",
				searchTerms: ["diamonds-filled"]
			}, {
				title: "ti ti-dice",
				searchTerms: ["dice"]
			}, {
				title: "ti ti-dice-1",
				searchTerms: ["dice-1"]
			}, {
				title: "ti ti-dice-1-filled",
				searchTerms: ["dice-1-filled"]
			}, {
				title: "ti ti-dice-2",
				searchTerms: ["dice-2"]
			}, {
				title: "ti ti-dice-2-filled",
				searchTerms: ["dice-2-filled"]
			}, {
				title: "ti ti-dice-3",
				searchTerms: ["dice-3"]
			}, {
				title: "ti ti-dice-3-filled",
				searchTerms: ["dice-3-filled"]
			}, {
				title: "ti ti-dice-4",
				searchTerms: ["dice-4"]
			}, {
				title: "ti ti-dice-4-filled",
				searchTerms: ["dice-4-filled"]
			}, {
				title: "ti ti-dice-5",
				searchTerms: ["dice-5"]
			}, {
				title: "ti ti-dice-5-filled",
				searchTerms: ["dice-5-filled"]
			}, {
				title: "ti ti-dice-6",
				searchTerms: ["dice-6"]
			}, {
				title: "ti ti-dice-6-filled",
				searchTerms: ["dice-6-filled"]
			}, {
				title: "ti ti-dice-filled",
				searchTerms: ["dice-filled"]
			}, {
				title: "ti ti-dimensions",
				searchTerms: ["dimensions"]
			}, {
				title: "ti ti-direction",
				searchTerms: ["direction"]
			}, {
				title: "ti ti-direction-arrows",
				searchTerms: ["direction-arrows"]
			}, {
				title: "ti ti-direction-horizontal",
				searchTerms: ["direction-horizontal"]
			}, {
				title: "ti ti-direction-sign",
				searchTerms: ["direction-sign"]
			}, {
				title: "ti ti-direction-sign-filled",
				searchTerms: ["direction-sign-filled"]
			}, {
				title: "ti ti-direction-sign-off",
				searchTerms: ["direction-sign-off"]
			}, {
				title: "ti ti-directions",
				searchTerms: ["directions"]
			}, {
				title: "ti ti-directions-off",
				searchTerms: ["directions-off"]
			}, {
				title: "ti ti-disabled",
				searchTerms: ["disabled"]
			}, {
				title: "ti ti-disabled-2",
				searchTerms: ["disabled-2"]
			}, {
				title: "ti ti-disabled-off",
				searchTerms: ["disabled-off"]
			}, {
				title: "ti ti-disc",
				searchTerms: ["disc"]
			}, {
				title: "ti ti-disc-golf",
				searchTerms: ["disc-golf"]
			}, {
				title: "ti ti-disc-off",
				searchTerms: ["disc-off"]
			}, {
				title: "ti ti-discount",
				searchTerms: ["discount"]
			}, {
				title: "ti ti-discount-off",
				searchTerms: ["discount-off"]
			}, {
				title: "ti ti-divide",
				searchTerms: ["divide"]
			}, {
				title: "ti ti-dna",
				searchTerms: ["dna"]
			}, {
				title: "ti ti-dna-2",
				searchTerms: ["dna-2"]
			}, {
				title: "ti ti-dna-2-off",
				searchTerms: ["dna-2-off"]
			}, {
				title: "ti ti-dna-off",
				searchTerms: ["dna-off"]
			}, {
				title: "ti ti-dog",
				searchTerms: ["dog"]
			}, {
				title: "ti ti-dog-bowl",
				searchTerms: ["dog-bowl"]
			}, {
				title: "ti ti-door",
				searchTerms: ["door"]
			}, {
				title: "ti ti-door-enter",
				searchTerms: ["door-enter"]
			}, {
				title: "ti ti-door-exit",
				searchTerms: ["door-exit"]
			}, {
				title: "ti ti-door-off",
				searchTerms: ["door-off"]
			}, {
				title: "ti ti-dots",
				searchTerms: ["dots"]
			}, {
				title: "ti ti-dots-circle-horizontal",
				searchTerms: ["dots-circle-horizontal"]
			}, {
				title: "ti ti-dots-diagonal",
				searchTerms: ["dots-diagonal"]
			}, {
				title: "ti ti-dots-diagonal-2",
				searchTerms: ["dots-diagonal-2"]
			}, {
				title: "ti ti-dots-vertical",
				searchTerms: ["dots-vertical"]
			}, {
				title: "ti ti-download",
				searchTerms: ["download"]
			}, {
				title: "ti ti-download-off",
				searchTerms: ["download-off"]
			}, {
				title: "ti ti-drag-drop",
				searchTerms: ["drag-drop"]
			}, {
				title: "ti ti-drag-drop-2",
				searchTerms: ["drag-drop-2"]
			}, {
				title: "ti ti-drone",
				searchTerms: ["drone"]
			}, {
				title: "ti ti-drone-off",
				searchTerms: ["drone-off"]
			}, {
				title: "ti ti-drop-circle",
				searchTerms: ["drop-circle"]
			}, {
				title: "ti ti-droplet",
				searchTerms: ["droplet"]
			}, {
				title: "ti ti-droplet-bolt",
				searchTerms: ["droplet-bolt"]
			}, {
				title: "ti ti-droplet-cancel",
				searchTerms: ["droplet-cancel"]
			}, {
				title: "ti ti-droplet-check",
				searchTerms: ["droplet-check"]
			}, {
				title: "ti ti-droplet-code",
				searchTerms: ["droplet-code"]
			}, {
				title: "ti ti-droplet-cog",
				searchTerms: ["droplet-cog"]
			}, {
				title: "ti ti-droplet-dollar",
				searchTerms: ["droplet-dollar"]
			}, {
				title: "ti ti-droplet-down",
				searchTerms: ["droplet-down"]
			}, {
				title: "ti ti-droplet-exclamation",
				searchTerms: ["droplet-exclamation"]
			}, {
				title: "ti ti-droplet-filled",
				searchTerms: ["droplet-filled"]
			}, {
				title: "ti ti-droplet-half",
				searchTerms: ["droplet-half"]
			}, {
				title: "ti ti-droplet-half-2",
				searchTerms: ["droplet-half-2"]
			}, {
				title: "ti ti-droplet-half-2-filled",
				searchTerms: ["droplet-half-2-filled"]
			}, {
				title: "ti ti-droplet-half-filled",
				searchTerms: ["droplet-half-filled"]
			}, {
				title: "ti ti-droplet-heart",
				searchTerms: ["droplet-heart"]
			}, {
				title: "ti ti-droplet-minus",
				searchTerms: ["droplet-minus"]
			}, {
				title: "ti ti-droplet-off",
				searchTerms: ["droplet-off"]
			}, {
				title: "ti ti-droplet-pause",
				searchTerms: ["droplet-pause"]
			}, {
				title: "ti ti-droplet-pin",
				searchTerms: ["droplet-pin"]
			}, {
				title: "ti ti-droplet-plus",
				searchTerms: ["droplet-plus"]
			}, {
				title: "ti ti-droplet-question",
				searchTerms: ["droplet-question"]
			}, {
				title: "ti ti-droplet-search",
				searchTerms: ["droplet-search"]
			}, {
				title: "ti ti-droplet-share",
				searchTerms: ["droplet-share"]
			}, {
				title: "ti ti-droplet-star",
				searchTerms: ["droplet-star"]
			}, {
				title: "ti ti-droplet-up",
				searchTerms: ["droplet-up"]
			}, {
				title: "ti ti-droplet-x",
				searchTerms: ["droplet-x"]
			}, {
				title: "ti ti-droplets",
				searchTerms: ["droplets"]
			}, {
				title: "ti ti-dual-screen",
				searchTerms: ["dual-screen"]
			}, {
				title: "ti ti-dumpling",
				searchTerms: ["dumpling"]
			}, {
				title: "ti ti-e-passport",
				searchTerms: ["e-passport"]
			}, {
				title: "ti ti-ear",
				searchTerms: ["ear"]
			}, {
				title: "ti ti-ear-off",
				searchTerms: ["ear-off"]
			}, {
				title: "ti ti-ear-scan",
				searchTerms: ["ear-scan"]
			}, {
				title: "ti ti-ease-in",
				searchTerms: ["ease-in"]
			}, {
				title: "ti ti-ease-in-control-point",
				searchTerms: ["ease-in-control-point"]
			}, {
				title: "ti ti-ease-in-out",
				searchTerms: ["ease-in-out"]
			}, {
				title: "ti ti-ease-in-out-control-points",
				searchTerms: ["ease-in-out-control-points"]
			}, {
				title: "ti ti-ease-out",
				searchTerms: ["ease-out"]
			}, {
				title: "ti ti-ease-out-control-point",
				searchTerms: ["ease-out-control-point"]
			}, {
				title: "ti ti-edit",
				searchTerms: ["edit"]
			}, {
				title: "ti ti-edit-circle",
				searchTerms: ["edit-circle"]
			}, {
				title: "ti ti-edit-circle-off",
				searchTerms: ["edit-circle-off"]
			}, {
				title: "ti ti-edit-off",
				searchTerms: ["edit-off"]
			}, {
				title: "ti ti-egg",
				searchTerms: ["egg"]
			}, {
				title: "ti ti-egg-cracked",
				searchTerms: ["egg-cracked"]
			}, {
				title: "ti ti-egg-filled",
				searchTerms: ["egg-filled"]
			}, {
				title: "ti ti-egg-fried",
				searchTerms: ["egg-fried"]
			}, {
				title: "ti ti-egg-off",
				searchTerms: ["egg-off"]
			}, {
				title: "ti ti-eggs",
				searchTerms: ["eggs"]
			}, {
				title: "ti ti-elevator",
				searchTerms: ["elevator"]
			}, {
				title: "ti ti-elevator-off",
				searchTerms: ["elevator-off"]
			}, {
				title: "ti ti-emergency-bed",
				searchTerms: ["emergency-bed"]
			}, {
				title: "ti ti-empathize",
				searchTerms: ["empathize"]
			}, {
				title: "ti ti-empathize-off",
				searchTerms: ["empathize-off"]
			}, {
				title: "ti ti-emphasis",
				searchTerms: ["emphasis"]
			}, {
				title: "ti ti-engine",
				searchTerms: ["engine"]
			}, {
				title: "ti ti-engine-off",
				searchTerms: ["engine-off"]
			}, {
				title: "ti ti-equal",
				searchTerms: ["equal"]
			}, {
				title: "ti ti-equal-double",
				searchTerms: ["equal-double"]
			}, {
				title: "ti ti-equal-not",
				searchTerms: ["equal-not"]
			}, {
				title: "ti ti-eraser",
				searchTerms: ["eraser"]
			}, {
				title: "ti ti-eraser-off",
				searchTerms: ["eraser-off"]
			}, {
				title: "ti ti-error-404",
				searchTerms: ["error-404"]
			}, {
				title: "ti ti-error-404-off",
				searchTerms: ["error-404-off"]
			}, {
				title: "ti ti-escalator",
				searchTerms: ["escalator"]
			}, {
				title: "ti ti-escalator-down",
				searchTerms: ["escalator-down"]
			}, {
				title: "ti ti-escalator-up",
				searchTerms: ["escalator-up"]
			}, {
				title: "ti ti-exchange",
				searchTerms: ["exchange"]
			}, {
				title: "ti ti-exchange-off",
				searchTerms: ["exchange-off"]
			}, {
				title: "ti ti-exclamation-circle",
				searchTerms: ["exclamation-circle"]
			}, {
				title: "ti ti-exclamation-mark",
				searchTerms: ["exclamation-mark"]
			}, {
				title: "ti ti-exclamation-mark-off",
				searchTerms: ["exclamation-mark-off"]
			}, {
				title: "ti ti-explicit",
				searchTerms: ["explicit"]
			}, {
				title: "ti ti-explicit-off",
				searchTerms: ["explicit-off"]
			}, {
				title: "ti ti-exposure",
				searchTerms: ["exposure"]
			}, {
				title: "ti ti-exposure-0",
				searchTerms: ["exposure-0"]
			}, {
				title: "ti ti-exposure-minus-1",
				searchTerms: ["exposure-minus-1"]
			}, {
				title: "ti ti-exposure-minus-2",
				searchTerms: ["exposure-minus-2"]
			}, {
				title: "ti ti-exposure-off",
				searchTerms: ["exposure-off"]
			}, {
				title: "ti ti-exposure-plus-1",
				searchTerms: ["exposure-plus-1"]
			}, {
				title: "ti ti-exposure-plus-2",
				searchTerms: ["exposure-plus-2"]
			}, {
				title: "ti ti-external-link",
				searchTerms: ["external-link"]
			}, {
				title: "ti ti-external-link-off",
				searchTerms: ["external-link-off"]
			}, {
				title: "ti ti-eye",
				searchTerms: ["eye"]
			}, {
				title: "ti ti-eye-bolt",
				searchTerms: ["eye-bolt"]
			}, {
				title: "ti ti-eye-cancel",
				searchTerms: ["eye-cancel"]
			}, {
				title: "ti ti-eye-check",
				searchTerms: ["eye-check"]
			}, {
				title: "ti ti-eye-closed",
				searchTerms: ["eye-closed"]
			}, {
				title: "ti ti-eye-code",
				searchTerms: ["eye-code"]
			}, {
				title: "ti ti-eye-cog",
				searchTerms: ["eye-cog"]
			}, {
				title: "ti ti-eye-discount",
				searchTerms: ["eye-discount"]
			}, {
				title: "ti ti-eye-dollar",
				searchTerms: ["eye-dollar"]
			}, {
				title: "ti ti-eye-dotted",
				searchTerms: ["eye-dotted"]
			}, {
				title: "ti ti-eye-down",
				searchTerms: ["eye-down"]
			}, {
				title: "ti ti-eye-edit",
				searchTerms: ["eye-edit"]
			}, {
				title: "ti ti-eye-exclamation",
				searchTerms: ["eye-exclamation"]
			}, {
				title: "ti ti-eye-filled",
				searchTerms: ["eye-filled"]
			}, {
				title: "ti ti-eye-heart",
				searchTerms: ["eye-heart"]
			}, {
				title: "ti ti-eye-minus",
				searchTerms: ["eye-minus"]
			}, {
				title: "ti ti-eye-off",
				searchTerms: ["eye-off"]
			}, {
				title: "ti ti-eye-pause",
				searchTerms: ["eye-pause"]
			}, {
				title: "ti ti-eye-pin",
				searchTerms: ["eye-pin"]
			}, {
				title: "ti ti-eye-plus",
				searchTerms: ["eye-plus"]
			}, {
				title: "ti ti-eye-question",
				searchTerms: ["eye-question"]
			}, {
				title: "ti ti-eye-search",
				searchTerms: ["eye-search"]
			}, {
				title: "ti ti-eye-share",
				searchTerms: ["eye-share"]
			}, {
				title: "ti ti-eye-star",
				searchTerms: ["eye-star"]
			}, {
				title: "ti ti-eye-table",
				searchTerms: ["eye-table"]
			}, {
				title: "ti ti-eye-up",
				searchTerms: ["eye-up"]
			}, {
				title: "ti ti-eye-x",
				searchTerms: ["eye-x"]
			}, {
				title: "ti ti-eyeglass",
				searchTerms: ["eyeglass"]
			}, {
				title: "ti ti-eyeglass-2",
				searchTerms: ["eyeglass-2"]
			}, {
				title: "ti ti-eyeglass-off",
				searchTerms: ["eyeglass-off"]
			}, {
				title: "ti ti-face-id",
				searchTerms: ["face-id"]
			}, {
				title: "ti ti-face-id-error",
				searchTerms: ["face-id-error"]
			}, {
				title: "ti ti-face-mask",
				searchTerms: ["face-mask"]
			}, {
				title: "ti ti-face-mask-off",
				searchTerms: ["face-mask-off"]
			}, {
				title: "ti ti-fall",
				searchTerms: ["fall"]
			}, {
				title: "ti ti-favicon",
				searchTerms: ["favicon"]
			}, {
				title: "ti ti-feather",
				searchTerms: ["feather"]
			}, {
				title: "ti ti-feather-off",
				searchTerms: ["feather-off"]
			}, {
				title: "ti ti-fence",
				searchTerms: ["fence"]
			}, {
				title: "ti ti-fence-off",
				searchTerms: ["fence-off"]
			}, {
				title: "ti ti-fidget-spinner",
				searchTerms: ["fidget-spinner"]
			}, {
				title: "ti ti-file",
				searchTerms: ["file"]
			}, {
				title: "ti ti-file-3d",
				searchTerms: ["file-3d"]
			}, {
				title: "ti ti-file-alert",
				searchTerms: ["file-alert"]
			}, {
				title: "ti ti-file-analytics",
				searchTerms: ["file-analytics"]
			}, {
				title: "ti ti-file-arrow-left",
				searchTerms: ["file-arrow-left"]
			}, {
				title: "ti ti-file-arrow-right",
				searchTerms: ["file-arrow-right"]
			}, {
				title: "ti ti-file-barcode",
				searchTerms: ["file-barcode"]
			}, {
				title: "ti ti-file-broken",
				searchTerms: ["file-broken"]
			}, {
				title: "ti ti-file-certificate",
				searchTerms: ["file-certificate"]
			}, {
				title: "ti ti-file-chart",
				searchTerms: ["file-chart"]
			}, {
				title: "ti ti-file-check",
				searchTerms: ["file-check"]
			}, {
				title: "ti ti-file-code",
				searchTerms: ["file-code"]
			}, {
				title: "ti ti-file-code-2",
				searchTerms: ["file-code-2"]
			}, {
				title: "ti ti-file-cv",
				searchTerms: ["file-cv"]
			}, {
				title: "ti ti-file-database",
				searchTerms: ["file-database"]
			}, {
				title: "ti ti-file-delta",
				searchTerms: ["file-delta"]
			}, {
				title: "ti ti-file-description",
				searchTerms: ["file-description"]
			}, {
				title: "ti ti-file-diff",
				searchTerms: ["file-diff"]
			}, {
				title: "ti ti-file-digit",
				searchTerms: ["file-digit"]
			}, {
				title: "ti ti-file-dislike",
				searchTerms: ["file-dislike"]
			}, {
				title: "ti ti-file-dollar",
				searchTerms: ["file-dollar"]
			}, {
				title: "ti ti-file-dots",
				searchTerms: ["file-dots"]
			}, {
				title: "ti ti-file-download",
				searchTerms: ["file-download"]
			}, {
				title: "ti ti-file-euro",
				searchTerms: ["file-euro"]
			}, {
				title: "ti ti-file-export",
				searchTerms: ["file-export"]
			}, {
				title: "ti ti-file-filled",
				searchTerms: ["file-filled"]
			}, {
				title: "ti ti-file-function",
				searchTerms: ["file-function"]
			}, {
				title: "ti ti-file-horizontal",
				searchTerms: ["file-horizontal"]
			}, {
				title: "ti ti-file-import",
				searchTerms: ["file-import"]
			}, {
				title: "ti ti-file-infinity",
				searchTerms: ["file-infinity"]
			}, {
				title: "ti ti-file-info",
				searchTerms: ["file-info"]
			}, {
				title: "ti ti-file-invoice",
				searchTerms: ["file-invoice"]
			}, {
				title: "ti ti-file-isr",
				searchTerms: ["file-isr"]
			}, {
				title: "ti ti-file-lambda",
				searchTerms: ["file-lambda"]
			}, {
				title: "ti ti-file-like",
				searchTerms: ["file-like"]
			}, {
				title: "ti ti-file-minus",
				searchTerms: ["file-minus"]
			}, {
				title: "ti ti-file-music",
				searchTerms: ["file-music"]
			}, {
				title: "ti ti-file-neutral",
				searchTerms: ["file-neutral"]
			}, {
				title: "ti ti-file-off",
				searchTerms: ["file-off"]
			}, {
				title: "ti ti-file-orientation",
				searchTerms: ["file-orientation"]
			}, {
				title: "ti ti-file-pencil",
				searchTerms: ["file-pencil"]
			}, {
				title: "ti ti-file-percent",
				searchTerms: ["file-percent"]
			}, {
				title: "ti ti-file-phone",
				searchTerms: ["file-phone"]
			}, {
				title: "ti ti-file-plus",
				searchTerms: ["file-plus"]
			}, {
				title: "ti ti-file-power",
				searchTerms: ["file-power"]
			}, {
				title: "ti ti-file-report",
				searchTerms: ["file-report"]
			}, {
				title: "ti ti-file-rss",
				searchTerms: ["file-rss"]
			}, {
				title: "ti ti-file-sad",
				searchTerms: ["file-sad"]
			}, {
				title: "ti ti-file-scissors",
				searchTerms: ["file-scissors"]
			}, {
				title: "ti ti-file-search",
				searchTerms: ["file-search"]
			}, {
				title: "ti ti-file-settings",
				searchTerms: ["file-settings"]
			}, {
				title: "ti ti-file-shredder",
				searchTerms: ["file-shredder"]
			}, {
				title: "ti ti-file-signal",
				searchTerms: ["file-signal"]
			}, {
				title: "ti ti-file-smile",
				searchTerms: ["file-smile"]
			}, {
				title: "ti ti-file-spreadsheet",
				searchTerms: ["file-spreadsheet"]
			}, {
				title: "ti ti-file-stack",
				searchTerms: ["file-stack"]
			}, {
				title: "ti ti-file-star",
				searchTerms: ["file-star"]
			}, {
				title: "ti ti-file-symlink",
				searchTerms: ["file-symlink"]
			}, {
				title: "ti ti-file-text",
				searchTerms: ["file-text"]
			}, {
				title: "ti ti-file-text-ai",
				searchTerms: ["file-text-ai"]
			}, {
				title: "ti ti-file-time",
				searchTerms: ["file-time"]
			}, {
				title: "ti ti-file-type-bmp",
				searchTerms: ["file-type-bmp"]
			}, {
				title: "ti ti-file-type-css",
				searchTerms: ["file-type-css"]
			}, {
				title: "ti ti-file-type-csv",
				searchTerms: ["file-type-csv"]
			}, {
				title: "ti ti-file-type-doc",
				searchTerms: ["file-type-doc"]
			}, {
				title: "ti ti-file-type-docx",
				searchTerms: ["file-type-docx"]
			}, {
				title: "ti ti-file-type-html",
				searchTerms: ["file-type-html"]
			}, {
				title: "ti ti-file-type-jpg",
				searchTerms: ["file-type-jpg"]
			}, {
				title: "ti ti-file-type-js",
				searchTerms: ["file-type-js"]
			}, {
				title: "ti ti-file-type-jsx",
				searchTerms: ["file-type-jsx"]
			}, {
				title: "ti ti-file-type-pdf",
				searchTerms: ["file-type-pdf"]
			}, {
				title: "ti ti-file-type-php",
				searchTerms: ["file-type-php"]
			}, {
				title: "ti ti-file-type-png",
				searchTerms: ["file-type-png"]
			}, {
				title: "ti ti-file-type-ppt",
				searchTerms: ["file-type-ppt"]
			}, {
				title: "ti ti-file-type-rs",
				searchTerms: ["file-type-rs"]
			}, {
				title: "ti ti-file-type-sql",
				searchTerms: ["file-type-sql"]
			}, {
				title: "ti ti-file-type-svg",
				searchTerms: ["file-type-svg"]
			}, {
				title: "ti ti-file-type-ts",
				searchTerms: ["file-type-ts"]
			}, {
				title: "ti ti-file-type-tsx",
				searchTerms: ["file-type-tsx"]
			}, {
				title: "ti ti-file-type-txt",
				searchTerms: ["file-type-txt"]
			}, {
				title: "ti ti-file-type-vue",
				searchTerms: ["file-type-vue"]
			}, {
				title: "ti ti-file-type-xls",
				searchTerms: ["file-type-xls"]
			}, {
				title: "ti ti-file-type-xml",
				searchTerms: ["file-type-xml"]
			}, {
				title: "ti ti-file-type-zip",
				searchTerms: ["file-type-zip"]
			}, {
				title: "ti ti-file-typography",
				searchTerms: ["file-typography"]
			}, {
				title: "ti ti-file-unknown",
				searchTerms: ["file-unknown"]
			}, {
				title: "ti ti-file-upload",
				searchTerms: ["file-upload"]
			}, {
				title: "ti ti-file-vector",
				searchTerms: ["file-vector"]
			}, {
				title: "ti ti-file-x",
				searchTerms: ["file-x"]
			}, {
				title: "ti ti-file-x-filled",
				searchTerms: ["file-x-filled"]
			}, {
				title: "ti ti-file-zip",
				searchTerms: ["file-zip"]
			}, {
				title: "ti ti-files",
				searchTerms: ["files"]
			}, {
				title: "ti ti-files-off",
				searchTerms: ["files-off"]
			}, {
				title: "ti ti-filter",
				searchTerms: ["filter"]
			}, {
				title: "ti ti-filter-bolt",
				searchTerms: ["filter-bolt"]
			}, {
				title: "ti ti-filter-cancel",
				searchTerms: ["filter-cancel"]
			}, {
				title: "ti ti-filter-check",
				searchTerms: ["filter-check"]
			}, {
				title: "ti ti-filter-code",
				searchTerms: ["filter-code"]
			}, {
				title: "ti ti-filter-cog",
				searchTerms: ["filter-cog"]
			}, {
				title: "ti ti-filter-discount",
				searchTerms: ["filter-discount"]
			}, {
				title: "ti ti-filter-dollar",
				searchTerms: ["filter-dollar"]
			}, {
				title: "ti ti-filter-down",
				searchTerms: ["filter-down"]
			}, {
				title: "ti ti-filter-edit",
				searchTerms: ["filter-edit"]
			}, {
				title: "ti ti-filter-exclamation",
				searchTerms: ["filter-exclamation"]
			}, {
				title: "ti ti-filter-filled",
				searchTerms: ["filter-filled"]
			}, {
				title: "ti ti-filter-heart",
				searchTerms: ["filter-heart"]
			}, {
				title: "ti ti-filter-minus",
				searchTerms: ["filter-minus"]
			}, {
				title: "ti ti-filter-off",
				searchTerms: ["filter-off"]
			}, {
				title: "ti ti-filter-pause",
				searchTerms: ["filter-pause"]
			}, {
				title: "ti ti-filter-pin",
				searchTerms: ["filter-pin"]
			}, {
				title: "ti ti-filter-plus",
				searchTerms: ["filter-plus"]
			}, {
				title: "ti ti-filter-question",
				searchTerms: ["filter-question"]
			}, {
				title: "ti ti-filter-search",
				searchTerms: ["filter-search"]
			}, {
				title: "ti ti-filter-share",
				searchTerms: ["filter-share"]
			}, {
				title: "ti ti-filter-star",
				searchTerms: ["filter-star"]
			}, {
				title: "ti ti-filter-up",
				searchTerms: ["filter-up"]
			}, {
				title: "ti ti-filter-x",
				searchTerms: ["filter-x"]
			}, {
				title: "ti ti-filters",
				searchTerms: ["filters"]
			}, {
				title: "ti ti-fingerprint",
				searchTerms: ["fingerprint"]
			}, {
				title: "ti ti-fingerprint-off",
				searchTerms: ["fingerprint-off"]
			}, {
				title: "ti ti-fingerprint-scan",
				searchTerms: ["fingerprint-scan"]
			}, {
				title: "ti ti-fire-extinguisher",
				searchTerms: ["fire-extinguisher"]
			}, {
				title: "ti ti-fire-hydrant",
				searchTerms: ["fire-hydrant"]
			}, {
				title: "ti ti-fire-hydrant-off",
				searchTerms: ["fire-hydrant-off"]
			}, {
				title: "ti ti-firetruck",
				searchTerms: ["firetruck"]
			}, {
				title: "ti ti-first-aid-kit",
				searchTerms: ["first-aid-kit"]
			}, {
				title: "ti ti-first-aid-kit-off",
				searchTerms: ["first-aid-kit-off"]
			}, {
				title: "ti ti-fish",
				searchTerms: ["fish"]
			}, {
				title: "ti ti-fish-bone",
				searchTerms: ["fish-bone"]
			}, {
				title: "ti ti-fish-christianity",
				searchTerms: ["fish-christianity"]
			}, {
				title: "ti ti-fish-hook",
				searchTerms: ["fish-hook"]
			}, {
				title: "ti ti-fish-hook-off",
				searchTerms: ["fish-hook-off"]
			}, {
				title: "ti ti-fish-off",
				searchTerms: ["fish-off"]
			}, {
				title: "ti ti-flag",
				searchTerms: ["flag"]
			}, {
				title: "ti ti-flag-2",
				searchTerms: ["flag-2"]
			}, {
				title: "ti ti-flag-2-filled",
				searchTerms: ["flag-2-filled"]
			}, {
				title: "ti ti-flag-2-off",
				searchTerms: ["flag-2-off"]
			}, {
				title: "ti ti-flag-3",
				searchTerms: ["flag-3"]
			}, {
				title: "ti ti-flag-3-filled",
				searchTerms: ["flag-3-filled"]
			}, {
				title: "ti ti-flag-bolt",
				searchTerms: ["flag-bolt"]
			}, {
				title: "ti ti-flag-cancel",
				searchTerms: ["flag-cancel"]
			}, {
				title: "ti ti-flag-check",
				searchTerms: ["flag-check"]
			}, {
				title: "ti ti-flag-code",
				searchTerms: ["flag-code"]
			}, {
				title: "ti ti-flag-cog",
				searchTerms: ["flag-cog"]
			}, {
				title: "ti ti-flag-discount",
				searchTerms: ["flag-discount"]
			}, {
				title: "ti ti-flag-dollar",
				searchTerms: ["flag-dollar"]
			}, {
				title: "ti ti-flag-down",
				searchTerms: ["flag-down"]
			}, {
				title: "ti ti-flag-exclamation",
				searchTerms: ["flag-exclamation"]
			}, {
				title: "ti ti-flag-filled",
				searchTerms: ["flag-filled"]
			}, {
				title: "ti ti-flag-heart",
				searchTerms: ["flag-heart"]
			}, {
				title: "ti ti-flag-minus",
				searchTerms: ["flag-minus"]
			}, {
				title: "ti ti-flag-off",
				searchTerms: ["flag-off"]
			}, {
				title: "ti ti-flag-pause",
				searchTerms: ["flag-pause"]
			}, {
				title: "ti ti-flag-pin",
				searchTerms: ["flag-pin"]
			}, {
				title: "ti ti-flag-plus",
				searchTerms: ["flag-plus"]
			}, {
				title: "ti ti-flag-question",
				searchTerms: ["flag-question"]
			}, {
				title: "ti ti-flag-search",
				searchTerms: ["flag-search"]
			}, {
				title: "ti ti-flag-share",
				searchTerms: ["flag-share"]
			}, {
				title: "ti ti-flag-star",
				searchTerms: ["flag-star"]
			}, {
				title: "ti ti-flag-up",
				searchTerms: ["flag-up"]
			}, {
				title: "ti ti-flag-x",
				searchTerms: ["flag-x"]
			}, {
				title: "ti ti-flame",
				searchTerms: ["flame"]
			}, {
				title: "ti ti-flame-off",
				searchTerms: ["flame-off"]
			}, {
				title: "ti ti-flare",
				searchTerms: ["flare"]
			}, {
				title: "ti ti-flask",
				searchTerms: ["flask"]
			}, {
				title: "ti ti-flask-2",
				searchTerms: ["flask-2"]
			}, {
				title: "ti ti-flask-2-filled",
				searchTerms: ["flask-2-filled"]
			}, {
				title: "ti ti-flask-2-off",
				searchTerms: ["flask-2-off"]
			}, {
				title: "ti ti-flask-filled",
				searchTerms: ["flask-filled"]
			}, {
				title: "ti ti-flask-off",
				searchTerms: ["flask-off"]
			}, {
				title: "ti ti-flip-flops",
				searchTerms: ["flip-flops"]
			}, {
				title: "ti ti-flip-horizontal",
				searchTerms: ["flip-horizontal"]
			}, {
				title: "ti ti-flip-vertical",
				searchTerms: ["flip-vertical"]
			}, {
				title: "ti ti-float-center",
				searchTerms: ["float-center"]
			}, {
				title: "ti ti-float-left",
				searchTerms: ["float-left"]
			}, {
				title: "ti ti-float-none",
				searchTerms: ["float-none"]
			}, {
				title: "ti ti-float-right",
				searchTerms: ["float-right"]
			}, {
				title: "ti ti-flower",
				searchTerms: ["flower"]
			}, {
				title: "ti ti-flower-off",
				searchTerms: ["flower-off"]
			}, {
				title: "ti ti-focus",
				searchTerms: ["focus"]
			}, {
				title: "ti ti-focus-2",
				searchTerms: ["focus-2"]
			}, {
				title: "ti ti-focus-auto",
				searchTerms: ["focus-auto"]
			}, {
				title: "ti ti-focus-centered",
				searchTerms: ["focus-centered"]
			}, {
				title: "ti ti-fold",
				searchTerms: ["fold"]
			}, {
				title: "ti ti-fold-down",
				searchTerms: ["fold-down"]
			}, {
				title: "ti ti-fold-up",
				searchTerms: ["fold-up"]
			}, {
				title: "ti ti-folder",
				searchTerms: ["folder"]
			}, {
				title: "ti ti-folder-bolt",
				searchTerms: ["folder-bolt"]
			}, {
				title: "ti ti-folder-cancel",
				searchTerms: ["folder-cancel"]
			}, {
				title: "ti ti-folder-check",
				searchTerms: ["folder-check"]
			}, {
				title: "ti ti-folder-code",
				searchTerms: ["folder-code"]
			}, {
				title: "ti ti-folder-cog",
				searchTerms: ["folder-cog"]
			}, {
				title: "ti ti-folder-dollar",
				searchTerms: ["folder-dollar"]
			}, {
				title: "ti ti-folder-down",
				searchTerms: ["folder-down"]
			}, {
				title: "ti ti-folder-exclamation",
				searchTerms: ["folder-exclamation"]
			}, {
				title: "ti ti-folder-filled",
				searchTerms: ["folder-filled"]
			}, {
				title: "ti ti-folder-heart",
				searchTerms: ["folder-heart"]
			}, {
				title: "ti ti-folder-minus",
				searchTerms: ["folder-minus"]
			}, {
				title: "ti ti-folder-off",
				searchTerms: ["folder-off"]
			}, {
				title: "ti ti-folder-open",
				searchTerms: ["folder-open"]
			}, {
				title: "ti ti-folder-pause",
				searchTerms: ["folder-pause"]
			}, {
				title: "ti ti-folder-pin",
				searchTerms: ["folder-pin"]
			}, {
				title: "ti ti-folder-plus",
				searchTerms: ["folder-plus"]
			}, {
				title: "ti ti-folder-question",
				searchTerms: ["folder-question"]
			}, {
				title: "ti ti-folder-root",
				searchTerms: ["folder-root"]
			}, {
				title: "ti ti-folder-search",
				searchTerms: ["folder-search"]
			}, {
				title: "ti ti-folder-share",
				searchTerms: ["folder-share"]
			}, {
				title: "ti ti-folder-star",
				searchTerms: ["folder-star"]
			}, {
				title: "ti ti-folder-symlink",
				searchTerms: ["folder-symlink"]
			}, {
				title: "ti ti-folder-up",
				searchTerms: ["folder-up"]
			}, {
				title: "ti ti-folder-x",
				searchTerms: ["folder-x"]
			}, {
				title: "ti ti-folders",
				searchTerms: ["folders"]
			}, {
				title: "ti ti-folders-off",
				searchTerms: ["folders-off"]
			}, {
				title: "ti ti-forbid",
				searchTerms: ["forbid"]
			}, {
				title: "ti ti-forbid-2",
				searchTerms: ["forbid-2"]
			}, {
				title: "ti ti-forbid-2-filled",
				searchTerms: ["forbid-2-filled"]
			}, {
				title: "ti ti-forbid-filled",
				searchTerms: ["forbid-filled"]
			}, {
				title: "ti ti-forklift",
				searchTerms: ["forklift"]
			}, {
				title: "ti ti-forms",
				searchTerms: ["forms"]
			}, {
				title: "ti ti-fountain",
				searchTerms: ["fountain"]
			}, {
				title: "ti ti-fountain-filled",
				searchTerms: ["fountain-filled"]
			}, {
				title: "ti ti-fountain-off",
				searchTerms: ["fountain-off"]
			}, {
				title: "ti ti-frame",
				searchTerms: ["frame"]
			}, {
				title: "ti ti-frame-off",
				searchTerms: ["frame-off"]
			}, {
				title: "ti ti-free-rights",
				searchTerms: ["free-rights"]
			}, {
				title: "ti ti-freeze-column",
				searchTerms: ["freeze-column"]
			}, {
				title: "ti ti-freeze-row",
				searchTerms: ["freeze-row"]
			}, {
				title: "ti ti-freeze-row-column",
				searchTerms: ["freeze-row-column"]
			}, {
				title: "ti ti-fridge",
				searchTerms: ["fridge"]
			}, {
				title: "ti ti-fridge-off",
				searchTerms: ["fridge-off"]
			}, {
				title: "ti ti-friends",
				searchTerms: ["friends"]
			}, {
				title: "ti ti-friends-off",
				searchTerms: ["friends-off"]
			}, {
				title: "ti ti-frustum",
				searchTerms: ["frustum"]
			}, {
				title: "ti ti-frustum-off",
				searchTerms: ["frustum-off"]
			}, {
				title: "ti ti-frustum-plus",
				searchTerms: ["frustum-plus"]
			}, {
				title: "ti ti-function",
				searchTerms: ["function"]
			}, {
				title: "ti ti-function-filled",
				searchTerms: ["function-filled"]
			}, {
				title: "ti ti-function-off",
				searchTerms: ["function-off"]
			}, {
				title: "ti ti-galaxy",
				searchTerms: ["galaxy"]
			}, {
				title: "ti ti-garden-cart",
				searchTerms: ["garden-cart"]
			}, {
				title: "ti ti-garden-cart-off",
				searchTerms: ["garden-cart-off"]
			}, {
				title: "ti ti-gas-station",
				searchTerms: ["gas-station"]
			}, {
				title: "ti ti-gas-station-off",
				searchTerms: ["gas-station-off"]
			}, {
				title: "ti ti-gauge",
				searchTerms: ["gauge"]
			}, {
				title: "ti ti-gauge-filled",
				searchTerms: ["gauge-filled"]
			}, {
				title: "ti ti-gauge-off",
				searchTerms: ["gauge-off"]
			}, {
				title: "ti ti-gavel",
				searchTerms: ["gavel"]
			}, {
				title: "ti ti-gender-agender",
				searchTerms: ["gender-agender"]
			}, {
				title: "ti ti-gender-androgyne",
				searchTerms: ["gender-androgyne"]
			}, {
				title: "ti ti-gender-bigender",
				searchTerms: ["gender-bigender"]
			}, {
				title: "ti ti-gender-demiboy",
				searchTerms: ["gender-demiboy"]
			}, {
				title: "ti ti-gender-demigirl",
				searchTerms: ["gender-demigirl"]
			}, {
				title: "ti ti-gender-epicene",
				searchTerms: ["gender-epicene"]
			}, {
				title: "ti ti-gender-female",
				searchTerms: ["gender-female"]
			}, {
				title: "ti ti-gender-femme",
				searchTerms: ["gender-femme"]
			}, {
				title: "ti ti-gender-genderfluid",
				searchTerms: ["gender-genderfluid"]
			}, {
				title: "ti ti-gender-genderless",
				searchTerms: ["gender-genderless"]
			}, {
				title: "ti ti-gender-genderqueer",
				searchTerms: ["gender-genderqueer"]
			}, {
				title: "ti ti-gender-hermaphrodite",
				searchTerms: ["gender-hermaphrodite"]
			}, {
				title: "ti ti-gender-intergender",
				searchTerms: ["gender-intergender"]
			}, {
				title: "ti ti-gender-male",
				searchTerms: ["gender-male"]
			}, {
				title: "ti ti-gender-neutrois",
				searchTerms: ["gender-neutrois"]
			}, {
				title: "ti ti-gender-third",
				searchTerms: ["gender-third"]
			}, {
				title: "ti ti-gender-transgender",
				searchTerms: ["gender-transgender"]
			}, {
				title: "ti ti-gender-trasvesti",
				searchTerms: ["gender-trasvesti"]
			}, {
				title: "ti ti-geometry",
				searchTerms: ["geometry"]
			}, {
				title: "ti ti-ghost",
				searchTerms: ["ghost"]
			}, {
				title: "ti ti-ghost-2",
				searchTerms: ["ghost-2"]
			}, {
				title: "ti ti-ghost-2-filled",
				searchTerms: ["ghost-2-filled"]
			}, {
				title: "ti ti-ghost-3",
				searchTerms: ["ghost-3"]
			}, {
				title: "ti ti-ghost-filled",
				searchTerms: ["ghost-filled"]
			}, {
				title: "ti ti-ghost-off",
				searchTerms: ["ghost-off"]
			}, {
				title: "ti ti-gif",
				searchTerms: ["gif"]
			}, {
				title: "ti ti-gift",
				searchTerms: ["gift"]
			}, {
				title: "ti ti-gift-card",
				searchTerms: ["gift-card"]
			}, {
				title: "ti ti-gift-card-filled",
				searchTerms: ["gift-card-filled"]
			}, {
				title: "ti ti-gift-filled",
				searchTerms: ["gift-filled"]
			}, {
				title: "ti ti-gift-off",
				searchTerms: ["gift-off"]
			}, {
				title: "ti ti-git-branch",
				searchTerms: ["git-branch"]
			}, {
				title: "ti ti-git-branch-deleted",
				searchTerms: ["git-branch-deleted"]
			}, {
				title: "ti ti-git-cherry-pick",
				searchTerms: ["git-cherry-pick"]
			}, {
				title: "ti ti-git-commit",
				searchTerms: ["git-commit"]
			}, {
				title: "ti ti-git-compare",
				searchTerms: ["git-compare"]
			}, {
				title: "ti ti-git-fork",
				searchTerms: ["git-fork"]
			}, {
				title: "ti ti-git-merge",
				searchTerms: ["git-merge"]
			}, {
				title: "ti ti-git-pull-request",
				searchTerms: ["git-pull-request"]
			}, {
				title: "ti ti-git-pull-request-closed",
				searchTerms: ["git-pull-request-closed"]
			}, {
				title: "ti ti-git-pull-request-draft",
				searchTerms: ["git-pull-request-draft"]
			}, {
				title: "ti ti-gizmo",
				searchTerms: ["gizmo"]
			}, {
				title: "ti ti-glass",
				searchTerms: ["glass"]
			}, {
				title: "ti ti-glass-champagne",
				searchTerms: ["glass-champagne"]
			}, {
				title: "ti ti-glass-cocktail",
				searchTerms: ["glass-cocktail"]
			}, {
				title: "ti ti-glass-full",
				searchTerms: ["glass-full"]
			}, {
				title: "ti ti-glass-full-filled",
				searchTerms: ["glass-full-filled"]
			}, {
				title: "ti ti-glass-gin",
				searchTerms: ["glass-gin"]
			}, {
				title: "ti ti-glass-off",
				searchTerms: ["glass-off"]
			}, {
				title: "ti ti-globe",
				searchTerms: ["globe"]
			}, {
				title: "ti ti-globe-filled",
				searchTerms: ["globe-filled"]
			}, {
				title: "ti ti-globe-off",
				searchTerms: ["globe-off"]
			}, {
				title: "ti ti-go-game",
				searchTerms: ["go-game"]
			}, {
				title: "ti ti-golf",
				searchTerms: ["golf"]
			}, {
				title: "ti ti-golf-off",
				searchTerms: ["golf-off"]
			}, {
				title: "ti ti-gps",
				searchTerms: ["gps"]
			}, {
				title: "ti ti-gps-filled",
				searchTerms: ["gps-filled"]
			}, {
				title: "ti ti-gradienter",
				searchTerms: ["gradienter"]
			}, {
				title: "ti ti-grain",
				searchTerms: ["grain"]
			}, {
				title: "ti ti-graph",
				searchTerms: ["graph"]
			}, {
				title: "ti ti-graph-filled",
				searchTerms: ["graph-filled"]
			}, {
				title: "ti ti-graph-off",
				searchTerms: ["graph-off"]
			}, {
				title: "ti ti-grave",
				searchTerms: ["grave"]
			}, {
				title: "ti ti-grave-2",
				searchTerms: ["grave-2"]
			}, {
				title: "ti ti-grid-3x3",
				searchTerms: ["grid-3x3"]
			}, {
				title: "ti ti-grid-4x4",
				searchTerms: ["grid-4x4"]
			}, {
				title: "ti ti-grid-dots",
				searchTerms: ["grid-dots"]
			}, {
				title: "ti ti-grid-goldenratio",
				searchTerms: ["grid-goldenratio"]
			}, {
				title: "ti ti-grid-pattern",
				searchTerms: ["grid-pattern"]
			}, {
				title: "ti ti-grid-scan",
				searchTerms: ["grid-scan"]
			}, {
				title: "ti ti-grill",
				searchTerms: ["grill"]
			}, {
				title: "ti ti-grill-fork",
				searchTerms: ["grill-fork"]
			}, {
				title: "ti ti-grill-off",
				searchTerms: ["grill-off"]
			}, {
				title: "ti ti-grill-spatula",
				searchTerms: ["grill-spatula"]
			}, {
				title: "ti ti-grip-horizontal",
				searchTerms: ["grip-horizontal"]
			}, {
				title: "ti ti-grip-vertical",
				searchTerms: ["grip-vertical"]
			}, {
				title: "ti ti-growth",
				searchTerms: ["growth"]
			}, {
				title: "ti ti-guitar-pick",
				searchTerms: ["guitar-pick"]
			}, {
				title: "ti ti-guitar-pick-filled",
				searchTerms: ["guitar-pick-filled"]
			}, {
				title: "ti ti-gymnastics",
				searchTerms: ["gymnastics"]
			}, {
				title: "ti ti-h-1",
				searchTerms: ["h-1"]
			}, {
				title: "ti ti-h-2",
				searchTerms: ["h-2"]
			}, {
				title: "ti ti-h-3",
				searchTerms: ["h-3"]
			}, {
				title: "ti ti-h-4",
				searchTerms: ["h-4"]
			}, {
				title: "ti ti-h-5",
				searchTerms: ["h-5"]
			}, {
				title: "ti ti-h-6",
				searchTerms: ["h-6"]
			}, {
				title: "ti ti-hammer",
				searchTerms: ["hammer"]
			}, {
				title: "ti ti-hammer-off",
				searchTerms: ["hammer-off"]
			}, {
				title: "ti ti-hand-click",
				searchTerms: ["hand-click"]
			}, {
				title: "ti ti-hand-finger",
				searchTerms: ["hand-finger"]
			}, {
				title: "ti ti-hand-finger-off",
				searchTerms: ["hand-finger-off"]
			}, {
				title: "ti ti-hand-grab",
				searchTerms: ["hand-grab"]
			}, {
				title: "ti ti-hand-little-finger",
				searchTerms: ["hand-little-finger"]
			}, {
				title: "ti ti-hand-love-you",
				searchTerms: ["hand-love-you"]
			}, {
				title: "ti ti-hand-middle-finger",
				searchTerms: ["hand-middle-finger"]
			}, {
				title: "ti ti-hand-move",
				searchTerms: ["hand-move"]
			}, {
				title: "ti ti-hand-off",
				searchTerms: ["hand-off"]
			}, {
				title: "ti ti-hand-ring-finger",
				searchTerms: ["hand-ring-finger"]
			}, {
				title: "ti ti-hand-sanitizer",
				searchTerms: ["hand-sanitizer"]
			}, {
				title: "ti ti-hand-stop",
				searchTerms: ["hand-stop"]
			}, {
				title: "ti ti-hand-three-fingers",
				searchTerms: ["hand-three-fingers"]
			}, {
				title: "ti ti-hand-two-fingers",
				searchTerms: ["hand-two-fingers"]
			}, {
				title: "ti ti-hanger",
				searchTerms: ["hanger"]
			}, {
				title: "ti ti-hanger-2",
				searchTerms: ["hanger-2"]
			}, {
				title: "ti ti-hanger-off",
				searchTerms: ["hanger-off"]
			}, {
				title: "ti ti-hash",
				searchTerms: ["hash"]
			}, {
				title: "ti ti-haze",
				searchTerms: ["haze"]
			}, {
				title: "ti ti-haze-moon",
				searchTerms: ["haze-moon"]
			}, {
				title: "ti ti-hdr",
				searchTerms: ["hdr"]
			}, {
				title: "ti ti-heading",
				searchTerms: ["heading"]
			}, {
				title: "ti ti-heading-off",
				searchTerms: ["heading-off"]
			}, {
				title: "ti ti-headphones",
				searchTerms: ["headphones"]
			}, {
				title: "ti ti-headphones-filled",
				searchTerms: ["headphones-filled"]
			}, {
				title: "ti ti-headphones-off",
				searchTerms: ["headphones-off"]
			}, {
				title: "ti ti-headset",
				searchTerms: ["headset"]
			}, {
				title: "ti ti-headset-off",
				searchTerms: ["headset-off"]
			}, {
				title: "ti ti-health-recognition",
				searchTerms: ["health-recognition"]
			}, {
				title: "ti ti-heart",
				searchTerms: ["heart"]
			}, {
				title: "ti ti-heart-bolt",
				searchTerms: ["heart-bolt"]
			}, {
				title: "ti ti-heart-broken",
				searchTerms: ["heart-broken"]
			}, {
				title: "ti ti-heart-cancel",
				searchTerms: ["heart-cancel"]
			}, {
				title: "ti ti-heart-check",
				searchTerms: ["heart-check"]
			}, {
				title: "ti ti-heart-code",
				searchTerms: ["heart-code"]
			}, {
				title: "ti ti-heart-cog",
				searchTerms: ["heart-cog"]
			}, {
				title: "ti ti-heart-discount",
				searchTerms: ["heart-discount"]
			}, {
				title: "ti ti-heart-dollar",
				searchTerms: ["heart-dollar"]
			}, {
				title: "ti ti-heart-down",
				searchTerms: ["heart-down"]
			}, {
				title: "ti ti-heart-exclamation",
				searchTerms: ["heart-exclamation"]
			}, {
				title: "ti ti-heart-filled",
				searchTerms: ["heart-filled"]
			}, {
				title: "ti ti-heart-handshake",
				searchTerms: ["heart-handshake"]
			}, {
				title: "ti ti-heart-minus",
				searchTerms: ["heart-minus"]
			}, {
				title: "ti ti-heart-off",
				searchTerms: ["heart-off"]
			}, {
				title: "ti ti-heart-pause",
				searchTerms: ["heart-pause"]
			}, {
				title: "ti ti-heart-pin",
				searchTerms: ["heart-pin"]
			}, {
				title: "ti ti-heart-plus",
				searchTerms: ["heart-plus"]
			}, {
				title: "ti ti-heart-question",
				searchTerms: ["heart-question"]
			}, {
				title: "ti ti-heart-rate-monitor",
				searchTerms: ["heart-rate-monitor"]
			}, {
				title: "ti ti-heart-search",
				searchTerms: ["heart-search"]
			}, {
				title: "ti ti-heart-share",
				searchTerms: ["heart-share"]
			}, {
				title: "ti ti-heart-star",
				searchTerms: ["heart-star"]
			}, {
				title: "ti ti-heart-up",
				searchTerms: ["heart-up"]
			}, {
				title: "ti ti-heart-x",
				searchTerms: ["heart-x"]
			}, {
				title: "ti ti-heartbeat",
				searchTerms: ["heartbeat"]
			}, {
				title: "ti ti-hearts",
				searchTerms: ["hearts"]
			}, {
				title: "ti ti-hearts-off",
				searchTerms: ["hearts-off"]
			}, {
				title: "ti ti-helicopter",
				searchTerms: ["helicopter"]
			}, {
				title: "ti ti-helicopter-landing",
				searchTerms: ["helicopter-landing"]
			}, {
				title: "ti ti-helmet",
				searchTerms: ["helmet"]
			}, {
				title: "ti ti-helmet-off",
				searchTerms: ["helmet-off"]
			}, {
				title: "ti ti-help",
				searchTerms: ["help"]
			}, {
				title: "ti ti-help-circle",
				searchTerms: ["help-circle"]
			}, {
				title: "ti ti-help-circle-filled",
				searchTerms: ["help-circle-filled"]
			}, {
				title: "ti ti-help-hexagon",
				searchTerms: ["help-hexagon"]
			}, {
				title: "ti ti-help-hexagon-filled",
				searchTerms: ["help-hexagon-filled"]
			}, {
				title: "ti ti-help-octagon",
				searchTerms: ["help-octagon"]
			}, {
				title: "ti ti-help-octagon-filled",
				searchTerms: ["help-octagon-filled"]
			}, {
				title: "ti ti-help-off",
				searchTerms: ["help-off"]
			}, {
				title: "ti ti-help-small",
				searchTerms: ["help-small"]
			}, {
				title: "ti ti-help-square",
				searchTerms: ["help-square"]
			}, {
				title: "ti ti-help-square-filled",
				searchTerms: ["help-square-filled"]
			}, {
				title: "ti ti-help-square-rounded",
				searchTerms: ["help-square-rounded"]
			}, {
				title: "ti ti-help-square-rounded-filled",
				searchTerms: ["help-square-rounded-filled"]
			}, {
				title: "ti ti-help-triangle",
				searchTerms: ["help-triangle"]
			}, {
				title: "ti ti-help-triangle-filled",
				searchTerms: ["help-triangle-filled"]
			}, {
				title: "ti ti-hemisphere",
				searchTerms: ["hemisphere"]
			}, {
				title: "ti ti-hemisphere-off",
				searchTerms: ["hemisphere-off"]
			}, {
				title: "ti ti-hemisphere-plus",
				searchTerms: ["hemisphere-plus"]
			}, {
				title: "ti ti-hexagon",
				searchTerms: ["hexagon"]
			}, {
				title: "ti ti-hexagon-3d",
				searchTerms: ["hexagon-3d"]
			}, {
				title: "ti ti-hexagon-filled",
				searchTerms: ["hexagon-filled"]
			}, {
				title: "ti ti-hexagon-letter-a",
				searchTerms: ["hexagon-letter-a"]
			}, {
				title: "ti ti-hexagon-letter-a-filled",
				searchTerms: ["hexagon-letter-a-filled"]
			}, {
				title: "ti ti-hexagon-letter-b",
				searchTerms: ["hexagon-letter-b"]
			}, {
				title: "ti ti-hexagon-letter-b-filled",
				searchTerms: ["hexagon-letter-b-filled"]
			}, {
				title: "ti ti-hexagon-letter-c",
				searchTerms: ["hexagon-letter-c"]
			}, {
				title: "ti ti-hexagon-letter-c-filled",
				searchTerms: ["hexagon-letter-c-filled"]
			}, {
				title: "ti ti-hexagon-letter-d",
				searchTerms: ["hexagon-letter-d"]
			}, {
				title: "ti ti-hexagon-letter-d-filled",
				searchTerms: ["hexagon-letter-d-filled"]
			}, {
				title: "ti ti-hexagon-letter-e",
				searchTerms: ["hexagon-letter-e"]
			}, {
				title: "ti ti-hexagon-letter-e-filled",
				searchTerms: ["hexagon-letter-e-filled"]
			}, {
				title: "ti ti-hexagon-letter-f",
				searchTerms: ["hexagon-letter-f"]
			}, {
				title: "ti ti-hexagon-letter-f-filled",
				searchTerms: ["hexagon-letter-f-filled"]
			}, {
				title: "ti ti-hexagon-letter-g",
				searchTerms: ["hexagon-letter-g"]
			}, {
				title: "ti ti-hexagon-letter-g-filled",
				searchTerms: ["hexagon-letter-g-filled"]
			}, {
				title: "ti ti-hexagon-letter-h",
				searchTerms: ["hexagon-letter-h"]
			}, {
				title: "ti ti-hexagon-letter-h-filled",
				searchTerms: ["hexagon-letter-h-filled"]
			}, {
				title: "ti ti-hexagon-letter-i",
				searchTerms: ["hexagon-letter-i"]
			}, {
				title: "ti ti-hexagon-letter-i-filled",
				searchTerms: ["hexagon-letter-i-filled"]
			}, {
				title: "ti ti-hexagon-letter-j",
				searchTerms: ["hexagon-letter-j"]
			}, {
				title: "ti ti-hexagon-letter-j-filled",
				searchTerms: ["hexagon-letter-j-filled"]
			}, {
				title: "ti ti-hexagon-letter-k",
				searchTerms: ["hexagon-letter-k"]
			}, {
				title: "ti ti-hexagon-letter-k-filled",
				searchTerms: ["hexagon-letter-k-filled"]
			}, {
				title: "ti ti-hexagon-letter-l",
				searchTerms: ["hexagon-letter-l"]
			}, {
				title: "ti ti-hexagon-letter-l-filled",
				searchTerms: ["hexagon-letter-l-filled"]
			}, {
				title: "ti ti-hexagon-letter-m",
				searchTerms: ["hexagon-letter-m"]
			}, {
				title: "ti ti-hexagon-letter-m-filled",
				searchTerms: ["hexagon-letter-m-filled"]
			}, {
				title: "ti ti-hexagon-letter-n",
				searchTerms: ["hexagon-letter-n"]
			}, {
				title: "ti ti-hexagon-letter-n-filled",
				searchTerms: ["hexagon-letter-n-filled"]
			}, {
				title: "ti ti-hexagon-letter-o",
				searchTerms: ["hexagon-letter-o"]
			}, {
				title: "ti ti-hexagon-letter-o-filled",
				searchTerms: ["hexagon-letter-o-filled"]
			}, {
				title: "ti ti-hexagon-letter-p",
				searchTerms: ["hexagon-letter-p"]
			}, {
				title: "ti ti-hexagon-letter-p-filled",
				searchTerms: ["hexagon-letter-p-filled"]
			}, {
				title: "ti ti-hexagon-letter-q",
				searchTerms: ["hexagon-letter-q"]
			}, {
				title: "ti ti-hexagon-letter-q-filled",
				searchTerms: ["hexagon-letter-q-filled"]
			}, {
				title: "ti ti-hexagon-letter-r",
				searchTerms: ["hexagon-letter-r"]
			}, {
				title: "ti ti-hexagon-letter-r-filled",
				searchTerms: ["hexagon-letter-r-filled"]
			}, {
				title: "ti ti-hexagon-letter-s",
				searchTerms: ["hexagon-letter-s"]
			}, {
				title: "ti ti-hexagon-letter-s-filled",
				searchTerms: ["hexagon-letter-s-filled"]
			}, {
				title: "ti ti-hexagon-letter-t",
				searchTerms: ["hexagon-letter-t"]
			}, {
				title: "ti ti-hexagon-letter-t-filled",
				searchTerms: ["hexagon-letter-t-filled"]
			}, {
				title: "ti ti-hexagon-letter-u",
				searchTerms: ["hexagon-letter-u"]
			}, {
				title: "ti ti-hexagon-letter-u-filled",
				searchTerms: ["hexagon-letter-u-filled"]
			}, {
				title: "ti ti-hexagon-letter-v",
				searchTerms: ["hexagon-letter-v"]
			}, {
				title: "ti ti-hexagon-letter-v-filled",
				searchTerms: ["hexagon-letter-v-filled"]
			}, {
				title: "ti ti-hexagon-letter-w",
				searchTerms: ["hexagon-letter-w"]
			}, {
				title: "ti ti-hexagon-letter-w-filled",
				searchTerms: ["hexagon-letter-w-filled"]
			}, {
				title: "ti ti-hexagon-letter-x",
				searchTerms: ["hexagon-letter-x"]
			}, {
				title: "ti ti-hexagon-letter-x-filled",
				searchTerms: ["hexagon-letter-x-filled"]
			}, {
				title: "ti ti-hexagon-letter-y",
				searchTerms: ["hexagon-letter-y"]
			}, {
				title: "ti ti-hexagon-letter-y-filled",
				searchTerms: ["hexagon-letter-y-filled"]
			}, {
				title: "ti ti-hexagon-letter-z",
				searchTerms: ["hexagon-letter-z"]
			}, {
				title: "ti ti-hexagon-letter-z-filled",
				searchTerms: ["hexagon-letter-z-filled"]
			}, {
				title: "ti ti-hexagon-minus",
				searchTerms: ["hexagon-minus"]
			}, {
				title: "ti ti-hexagon-minus-2",
				searchTerms: ["hexagon-minus-2"]
			}, {
				title: "ti ti-hexagon-minus-filled",
				searchTerms: ["hexagon-minus-filled"]
			}, {
				title: "ti ti-hexagon-number-0",
				searchTerms: ["hexagon-number-0"]
			}, {
				title: "ti ti-hexagon-number-0-filled",
				searchTerms: ["hexagon-number-0-filled"]
			}, {
				title: "ti ti-hexagon-number-1",
				searchTerms: ["hexagon-number-1"]
			}, {
				title: "ti ti-hexagon-number-1-filled",
				searchTerms: ["hexagon-number-1-filled"]
			}, {
				title: "ti ti-hexagon-number-2",
				searchTerms: ["hexagon-number-2"]
			}, {
				title: "ti ti-hexagon-number-2-filled",
				searchTerms: ["hexagon-number-2-filled"]
			}, {
				title: "ti ti-hexagon-number-3",
				searchTerms: ["hexagon-number-3"]
			}, {
				title: "ti ti-hexagon-number-3-filled",
				searchTerms: ["hexagon-number-3-filled"]
			}, {
				title: "ti ti-hexagon-number-4",
				searchTerms: ["hexagon-number-4"]
			}, {
				title: "ti ti-hexagon-number-4-filled",
				searchTerms: ["hexagon-number-4-filled"]
			}, {
				title: "ti ti-hexagon-number-5",
				searchTerms: ["hexagon-number-5"]
			}, {
				title: "ti ti-hexagon-number-5-filled",
				searchTerms: ["hexagon-number-5-filled"]
			}, {
				title: "ti ti-hexagon-number-6",
				searchTerms: ["hexagon-number-6"]
			}, {
				title: "ti ti-hexagon-number-6-filled",
				searchTerms: ["hexagon-number-6-filled"]
			}, {
				title: "ti ti-hexagon-number-7",
				searchTerms: ["hexagon-number-7"]
			}, {
				title: "ti ti-hexagon-number-7-filled",
				searchTerms: ["hexagon-number-7-filled"]
			}, {
				title: "ti ti-hexagon-number-8",
				searchTerms: ["hexagon-number-8"]
			}, {
				title: "ti ti-hexagon-number-8-filled",
				searchTerms: ["hexagon-number-8-filled"]
			}, {
				title: "ti ti-hexagon-number-9",
				searchTerms: ["hexagon-number-9"]
			}, {
				title: "ti ti-hexagon-number-9-filled",
				searchTerms: ["hexagon-number-9-filled"]
			}, {
				title: "ti ti-hexagon-off",
				searchTerms: ["hexagon-off"]
			}, {
				title: "ti ti-hexagon-plus",
				searchTerms: ["hexagon-plus"]
			}, {
				title: "ti ti-hexagon-plus-2",
				searchTerms: ["hexagon-plus-2"]
			}, {
				title: "ti ti-hexagon-plus-filled",
				searchTerms: ["hexagon-plus-filled"]
			}, {
				title: "ti ti-hexagonal-prism",
				searchTerms: ["hexagonal-prism"]
			}, {
				title: "ti ti-hexagonal-prism-off",
				searchTerms: ["hexagonal-prism-off"]
			}, {
				title: "ti ti-hexagonal-prism-plus",
				searchTerms: ["hexagonal-prism-plus"]
			}, {
				title: "ti ti-hexagonal-pyramid",
				searchTerms: ["hexagonal-pyramid"]
			}, {
				title: "ti ti-hexagonal-pyramid-off",
				searchTerms: ["hexagonal-pyramid-off"]
			}, {
				title: "ti ti-hexagonal-pyramid-plus",
				searchTerms: ["hexagonal-pyramid-plus"]
			}, {
				title: "ti ti-hexagons",
				searchTerms: ["hexagons"]
			}, {
				title: "ti ti-hexagons-off",
				searchTerms: ["hexagons-off"]
			}, {
				title: "ti ti-hierarchy",
				searchTerms: ["hierarchy"]
			}, {
				title: "ti ti-hierarchy-2",
				searchTerms: ["hierarchy-2"]
			}, {
				title: "ti ti-hierarchy-3",
				searchTerms: ["hierarchy-3"]
			}, {
				title: "ti ti-hierarchy-off",
				searchTerms: ["hierarchy-off"]
			}, {
				title: "ti ti-highlight",
				searchTerms: ["highlight"]
			}, {
				title: "ti ti-highlight-off",
				searchTerms: ["highlight-off"]
			}, {
				title: "ti ti-history",
				searchTerms: ["history"]
			}, {
				title: "ti ti-history-off",
				searchTerms: ["history-off"]
			}, {
				title: "ti ti-history-toggle",
				searchTerms: ["history-toggle"]
			}, {
				title: "ti ti-home",
				searchTerms: ["home"]
			}, {
				title: "ti ti-home-2",
				searchTerms: ["home-2"]
			}, {
				title: "ti ti-home-bolt",
				searchTerms: ["home-bolt"]
			}, {
				title: "ti ti-home-cancel",
				searchTerms: ["home-cancel"]
			}, {
				title: "ti ti-home-check",
				searchTerms: ["home-check"]
			}, {
				title: "ti ti-home-cog",
				searchTerms: ["home-cog"]
			}, {
				title: "ti ti-home-dollar",
				searchTerms: ["home-dollar"]
			}, {
				title: "ti ti-home-dot",
				searchTerms: ["home-dot"]
			}, {
				title: "ti ti-home-down",
				searchTerms: ["home-down"]
			}, {
				title: "ti ti-home-eco",
				searchTerms: ["home-eco"]
			}, {
				title: "ti ti-home-edit",
				searchTerms: ["home-edit"]
			}, {
				title: "ti ti-home-exclamation",
				searchTerms: ["home-exclamation"]
			}, {
				title: "ti ti-home-filled",
				searchTerms: ["home-filled"]
			}, {
				title: "ti ti-home-hand",
				searchTerms: ["home-hand"]
			}, {
				title: "ti ti-home-heart",
				searchTerms: ["home-heart"]
			}, {
				title: "ti ti-home-infinity",
				searchTerms: ["home-infinity"]
			}, {
				title: "ti ti-home-link",
				searchTerms: ["home-link"]
			}, {
				title: "ti ti-home-minus",
				searchTerms: ["home-minus"]
			}, {
				title: "ti ti-home-move",
				searchTerms: ["home-move"]
			}, {
				title: "ti ti-home-off",
				searchTerms: ["home-off"]
			}, {
				title: "ti ti-home-plus",
				searchTerms: ["home-plus"]
			}, {
				title: "ti ti-home-question",
				searchTerms: ["home-question"]
			}, {
				title: "ti ti-home-ribbon",
				searchTerms: ["home-ribbon"]
			}, {
				title: "ti ti-home-search",
				searchTerms: ["home-search"]
			}, {
				title: "ti ti-home-share",
				searchTerms: ["home-share"]
			}, {
				title: "ti ti-home-shield",
				searchTerms: ["home-shield"]
			}, {
				title: "ti ti-home-signal",
				searchTerms: ["home-signal"]
			}, {
				title: "ti ti-home-star",
				searchTerms: ["home-star"]
			}, {
				title: "ti ti-home-stats",
				searchTerms: ["home-stats"]
			}, {
				title: "ti ti-home-up",
				searchTerms: ["home-up"]
			}, {
				title: "ti ti-home-x",
				searchTerms: ["home-x"]
			}, {
				title: "ti ti-horse",
				searchTerms: ["horse"]
			}, {
				title: "ti ti-horse-toy",
				searchTerms: ["horse-toy"]
			}, {
				title: "ti ti-horseshoe",
				searchTerms: ["horseshoe"]
			}, {
				title: "ti ti-hospital",
				searchTerms: ["hospital"]
			}, {
				title: "ti ti-hospital-circle",
				searchTerms: ["hospital-circle"]
			}, {
				title: "ti ti-hospital-circle-filled",
				searchTerms: ["hospital-circle-filled"]
			}, {
				title: "ti ti-hotel-service",
				searchTerms: ["hotel-service"]
			}, {
				title: "ti ti-hourglass",
				searchTerms: ["hourglass"]
			}, {
				title: "ti ti-hourglass-empty",
				searchTerms: ["hourglass-empty"]
			}, {
				title: "ti ti-hourglass-filled",
				searchTerms: ["hourglass-filled"]
			}, {
				title: "ti ti-hourglass-high",
				searchTerms: ["hourglass-high"]
			}, {
				title: "ti ti-hourglass-low",
				searchTerms: ["hourglass-low"]
			}, {
				title: "ti ti-hourglass-off",
				searchTerms: ["hourglass-off"]
			}, {
				title: "ti ti-hours-12",
				searchTerms: ["hours-12"]
			}, {
				title: "ti ti-hours-24",
				searchTerms: ["hours-24"]
			}, {
				title: "ti ti-html",
				searchTerms: ["html"]
			}, {
				title: "ti ti-http-connect",
				searchTerms: ["http-connect"]
			}, {
				title: "ti ti-http-delete",
				searchTerms: ["http-delete"]
			}, {
				title: "ti ti-http-get",
				searchTerms: ["http-get"]
			}, {
				title: "ti ti-http-head",
				searchTerms: ["http-head"]
			}, {
				title: "ti ti-http-options",
				searchTerms: ["http-options"]
			}, {
				title: "ti ti-http-patch",
				searchTerms: ["http-patch"]
			}, {
				title: "ti ti-http-post",
				searchTerms: ["http-post"]
			}, {
				title: "ti ti-http-put",
				searchTerms: ["http-put"]
			}, {
				title: "ti ti-http-que",
				searchTerms: ["http-que"]
			}, {
				title: "ti ti-http-trace",
				searchTerms: ["http-trace"]
			}, {
				title: "ti ti-ice-cream",
				searchTerms: ["ice-cream"]
			}, {
				title: "ti ti-ice-cream-2",
				searchTerms: ["ice-cream-2"]
			}, {
				title: "ti ti-ice-cream-off",
				searchTerms: ["ice-cream-off"]
			}, {
				title: "ti ti-ice-skating",
				searchTerms: ["ice-skating"]
			}, {
				title: "ti ti-icons",
				searchTerms: ["icons"]
			}, {
				title: "ti ti-icons-off",
				searchTerms: ["icons-off"]
			}, {
				title: "ti ti-id",
				searchTerms: ["id"]
			}, {
				title: "ti ti-id-badge",
				searchTerms: ["id-badge"]
			}, {
				title: "ti ti-id-badge-2",
				searchTerms: ["id-badge-2"]
			}, {
				title: "ti ti-id-badge-off",
				searchTerms: ["id-badge-off"]
			}, {
				title: "ti ti-id-off",
				searchTerms: ["id-off"]
			}, {
				title: "ti ti-ikosaedr",
				searchTerms: ["ikosaedr"]
			}, {
				title: "ti ti-image-in-picture",
				searchTerms: ["image-in-picture"]
			}, {
				title: "ti ti-inbox",
				searchTerms: ["inbox"]
			}, {
				title: "ti ti-inbox-off",
				searchTerms: ["inbox-off"]
			}, {
				title: "ti ti-indent-decrease",
				searchTerms: ["indent-decrease"]
			}, {
				title: "ti ti-indent-increase",
				searchTerms: ["indent-increase"]
			}, {
				title: "ti ti-infinity",
				searchTerms: ["infinity"]
			}, {
				title: "ti ti-infinity-off",
				searchTerms: ["infinity-off"]
			}, {
				title: "ti ti-info-circle",
				searchTerms: ["info-circle"]
			}, {
				title: "ti ti-info-circle-filled",
				searchTerms: ["info-circle-filled"]
			}, {
				title: "ti ti-info-hexagon",
				searchTerms: ["info-hexagon"]
			}, {
				title: "ti ti-info-hexagon-filled",
				searchTerms: ["info-hexagon-filled"]
			}, {
				title: "ti ti-info-octagon",
				searchTerms: ["info-octagon"]
			}, {
				title: "ti ti-info-octagon-filled",
				searchTerms: ["info-octagon-filled"]
			}, {
				title: "ti ti-info-small",
				searchTerms: ["info-small"]
			}, {
				title: "ti ti-info-square",
				searchTerms: ["info-square"]
			}, {
				title: "ti ti-info-square-filled",
				searchTerms: ["info-square-filled"]
			}, {
				title: "ti ti-info-square-rounded",
				searchTerms: ["info-square-rounded"]
			}, {
				title: "ti ti-info-square-rounded-filled",
				searchTerms: ["info-square-rounded-filled"]
			}, {
				title: "ti ti-info-triangle",
				searchTerms: ["info-triangle"]
			}, {
				title: "ti ti-info-triangle-filled",
				searchTerms: ["info-triangle-filled"]
			}, {
				title: "ti ti-inner-shadow-bottom",
				searchTerms: ["inner-shadow-bottom"]
			}, {
				title: "ti ti-inner-shadow-bottom-filled",
				searchTerms: ["inner-shadow-bottom-filled"]
			}, {
				title: "ti ti-inner-shadow-bottom-left",
				searchTerms: ["inner-shadow-bottom-left"]
			}, {
				title: "ti ti-inner-shadow-bottom-left-filled",
				searchTerms: ["inner-shadow-bottom-left-filled"]
			}, {
				title: "ti ti-inner-shadow-bottom-right",
				searchTerms: ["inner-shadow-bottom-right"]
			}, {
				title: "ti ti-inner-shadow-bottom-right-filled",
				searchTerms: ["inner-shadow-bottom-right-filled"]
			}, {
				title: "ti ti-inner-shadow-left",
				searchTerms: ["inner-shadow-left"]
			}, {
				title: "ti ti-inner-shadow-left-filled",
				searchTerms: ["inner-shadow-left-filled"]
			}, {
				title: "ti ti-inner-shadow-right",
				searchTerms: ["inner-shadow-right"]
			}, {
				title: "ti ti-inner-shadow-right-filled",
				searchTerms: ["inner-shadow-right-filled"]
			}, {
				title: "ti ti-inner-shadow-top",
				searchTerms: ["inner-shadow-top"]
			}, {
				title: "ti ti-inner-shadow-top-filled",
				searchTerms: ["inner-shadow-top-filled"]
			}, {
				title: "ti ti-inner-shadow-top-left",
				searchTerms: ["inner-shadow-top-left"]
			}, {
				title: "ti ti-inner-shadow-top-left-filled",
				searchTerms: ["inner-shadow-top-left-filled"]
			}, {
				title: "ti ti-inner-shadow-top-right",
				searchTerms: ["inner-shadow-top-right"]
			}, {
				title: "ti ti-inner-shadow-top-right-filled",
				searchTerms: ["inner-shadow-top-right-filled"]
			}, {
				title: "ti ti-input-ai",
				searchTerms: ["input-ai"]
			}, {
				title: "ti ti-input-check",
				searchTerms: ["input-check"]
			}, {
				title: "ti ti-input-search",
				searchTerms: ["input-search"]
			}, {
				title: "ti ti-input-x",
				searchTerms: ["input-x"]
			}, {
				title: "ti ti-invoice",
				searchTerms: ["invoice"]
			}, {
				title: "ti ti-ironing",
				searchTerms: ["ironing"]
			}, {
				title: "ti ti-ironing-1",
				searchTerms: ["ironing-1"]
			}, {
				title: "ti ti-ironing-2",
				searchTerms: ["ironing-2"]
			}, {
				title: "ti ti-ironing-3",
				searchTerms: ["ironing-3"]
			}, {
				title: "ti ti-ironing-filled",
				searchTerms: ["ironing-filled"]
			}, {
				title: "ti ti-ironing-off",
				searchTerms: ["ironing-off"]
			}, {
				title: "ti ti-ironing-steam",
				searchTerms: ["ironing-steam"]
			}, {
				title: "ti ti-ironing-steam-off",
				searchTerms: ["ironing-steam-off"]
			}, {
				title: "ti ti-irregular-polyhedron",
				searchTerms: ["irregular-polyhedron"]
			}, {
				title: "ti ti-irregular-polyhedron-off",
				searchTerms: ["irregular-polyhedron-off"]
			}, {
				title: "ti ti-irregular-polyhedron-plus",
				searchTerms: ["irregular-polyhedron-plus"]
			}, {
				title: "ti ti-italic",
				searchTerms: ["italic"]
			}, {
				title: "ti ti-jacket",
				searchTerms: ["jacket"]
			}, {
				title: "ti ti-jetpack",
				searchTerms: ["jetpack"]
			}, {
				title: "ti ti-jetpack-filled",
				searchTerms: ["jetpack-filled"]
			}, {
				title: "ti ti-jewish-star",
				searchTerms: ["jewish-star"]
			}, {
				title: "ti ti-jewish-star-filled",
				searchTerms: ["jewish-star-filled"]
			}, {
				title: "ti ti-jpg",
				searchTerms: ["jpg"]
			}, {
				title: "ti ti-json",
				searchTerms: ["json"]
			}, {
				title: "ti ti-jump-rope",
				searchTerms: ["jump-rope"]
			}, {
				title: "ti ti-karate",
				searchTerms: ["karate"]
			}, {
				title: "ti ti-kayak",
				searchTerms: ["kayak"]
			}, {
				title: "ti ti-kerning",
				searchTerms: ["kerning"]
			}, {
				title: "ti ti-key",
				searchTerms: ["key"]
			}, {
				title: "ti ti-key-filled",
				searchTerms: ["key-filled"]
			}, {
				title: "ti ti-key-off",
				searchTerms: ["key-off"]
			}, {
				title: "ti ti-keyboard",
				searchTerms: ["keyboard"]
			}, {
				title: "ti ti-keyboard-hide",
				searchTerms: ["keyboard-hide"]
			}, {
				title: "ti ti-keyboard-off",
				searchTerms: ["keyboard-off"]
			}, {
				title: "ti ti-keyboard-show",
				searchTerms: ["keyboard-show"]
			}, {
				title: "ti ti-keyframe",
				searchTerms: ["keyframe"]
			}, {
				title: "ti ti-keyframe-align-center",
				searchTerms: ["keyframe-align-center"]
			}, {
				title: "ti ti-keyframe-align-center-filled",
				searchTerms: ["keyframe-align-center-filled"]
			}, {
				title: "ti ti-keyframe-align-horizontal",
				searchTerms: ["keyframe-align-horizontal"]
			}, {
				title: "ti ti-keyframe-align-horizontal-filled",
				searchTerms: ["keyframe-align-horizontal-filled"]
			}, {
				title: "ti ti-keyframe-align-vertical",
				searchTerms: ["keyframe-align-vertical"]
			}, {
				title: "ti ti-keyframe-align-vertical-filled",
				searchTerms: ["keyframe-align-vertical-filled"]
			}, {
				title: "ti ti-keyframe-filled",
				searchTerms: ["keyframe-filled"]
			}, {
				title: "ti ti-keyframes",
				searchTerms: ["keyframes"]
			}, {
				title: "ti ti-keyframes-filled",
				searchTerms: ["keyframes-filled"]
			}, {
				title: "ti ti-ladder",
				searchTerms: ["ladder"]
			}, {
				title: "ti ti-ladder-off",
				searchTerms: ["ladder-off"]
			}, {
				title: "ti ti-ladle",
				searchTerms: ["ladle"]
			}, {
				title: "ti ti-lambda",
				searchTerms: ["lambda"]
			}, {
				title: "ti ti-lamp",
				searchTerms: ["lamp"]
			}, {
				title: "ti ti-lamp-2",
				searchTerms: ["lamp-2"]
			}, {
				title: "ti ti-lamp-off",
				searchTerms: ["lamp-off"]
			}, {
				title: "ti ti-lane",
				searchTerms: ["lane"]
			}, {
				title: "ti ti-language",
				searchTerms: ["language"]
			}, {
				title: "ti ti-language-hiragana",
				searchTerms: ["language-hiragana"]
			}, {
				title: "ti ti-language-katakana",
				searchTerms: ["language-katakana"]
			}, {
				title: "ti ti-language-off",
				searchTerms: ["language-off"]
			}, {
				title: "ti ti-lasso",
				searchTerms: ["lasso"]
			}, {
				title: "ti ti-lasso-off",
				searchTerms: ["lasso-off"]
			}, {
				title: "ti ti-lasso-polygon",
				searchTerms: ["lasso-polygon"]
			}, {
				title: "ti ti-layers-difference",
				searchTerms: ["layers-difference"]
			}, {
				title: "ti ti-layers-intersect",
				searchTerms: ["layers-intersect"]
			}, {
				title: "ti ti-layers-intersect-2",
				searchTerms: ["layers-intersect-2"]
			}, {
				title: "ti ti-layers-linked",
				searchTerms: ["layers-linked"]
			}, {
				title: "ti ti-layers-off",
				searchTerms: ["layers-off"]
			}, {
				title: "ti ti-layers-selected",
				searchTerms: ["layers-selected"]
			}, {
				title: "ti ti-layers-selected-bottom",
				searchTerms: ["layers-selected-bottom"]
			}, {
				title: "ti ti-layers-subtract",
				searchTerms: ["layers-subtract"]
			}, {
				title: "ti ti-layers-union",
				searchTerms: ["layers-union"]
			}, {
				title: "ti ti-layout",
				searchTerms: ["layout"]
			}, {
				title: "ti ti-layout-2",
				searchTerms: ["layout-2"]
			}, {
				title: "ti ti-layout-2-filled",
				searchTerms: ["layout-2-filled"]
			}, {
				title: "ti ti-layout-align-bottom",
				searchTerms: ["layout-align-bottom"]
			}, {
				title: "ti ti-layout-align-bottom-filled",
				searchTerms: ["layout-align-bottom-filled"]
			}, {
				title: "ti ti-layout-align-center",
				searchTerms: ["layout-align-center"]
			}, {
				title: "ti ti-layout-align-center-filled",
				searchTerms: ["layout-align-center-filled"]
			}, {
				title: "ti ti-layout-align-left",
				searchTerms: ["layout-align-left"]
			}, {
				title: "ti ti-layout-align-left-filled",
				searchTerms: ["layout-align-left-filled"]
			}, {
				title: "ti ti-layout-align-middle",
				searchTerms: ["layout-align-middle"]
			}, {
				title: "ti ti-layout-align-middle-filled",
				searchTerms: ["layout-align-middle-filled"]
			}, {
				title: "ti ti-layout-align-right",
				searchTerms: ["layout-align-right"]
			}, {
				title: "ti ti-layout-align-right-filled",
				searchTerms: ["layout-align-right-filled"]
			}, {
				title: "ti ti-layout-align-top",
				searchTerms: ["layout-align-top"]
			}, {
				title: "ti ti-layout-align-top-filled",
				searchTerms: ["layout-align-top-filled"]
			}, {
				title: "ti ti-layout-board",
				searchTerms: ["layout-board"]
			}, {
				title: "ti ti-layout-board-split",
				searchTerms: ["layout-board-split"]
			}, {
				title: "ti ti-layout-bottombar",
				searchTerms: ["layout-bottombar"]
			}, {
				title: "ti ti-layout-bottombar-collapse",
				searchTerms: ["layout-bottombar-collapse"]
			}, {
				title: "ti ti-layout-bottombar-collapse-filled",
				searchTerms: ["layout-bottombar-collapse-filled"]
			}, {
				title: "ti ti-layout-bottombar-expand",
				searchTerms: ["layout-bottombar-expand"]
			}, {
				title: "ti ti-layout-bottombar-expand-filled",
				searchTerms: ["layout-bottombar-expand-filled"]
			}, {
				title: "ti ti-layout-bottombar-filled",
				searchTerms: ["layout-bottombar-filled"]
			}, {
				title: "ti ti-layout-bottombar-inactive",
				searchTerms: ["layout-bottombar-inactive"]
			}, {
				title: "ti ti-layout-cards",
				searchTerms: ["layout-cards"]
			}, {
				title: "ti ti-layout-cards-filled",
				searchTerms: ["layout-cards-filled"]
			}, {
				title: "ti ti-layout-collage",
				searchTerms: ["layout-collage"]
			}, {
				title: "ti ti-layout-columns",
				searchTerms: ["layout-columns"]
			}, {
				title: "ti ti-layout-dashboard",
				searchTerms: ["layout-dashboard"]
			}, {
				title: "ti ti-layout-dashboard-filled",
				searchTerms: ["layout-dashboard-filled"]
			}, {
				title: "ti ti-layout-distribute-horizontal",
				searchTerms: ["layout-distribute-horizontal"]
			}, {
				title: "ti ti-layout-distribute-horizontal-filled",
				searchTerms: ["layout-distribute-horizontal-filled"]
			}, {
				title: "ti ti-layout-distribute-vertical",
				searchTerms: ["layout-distribute-vertical"]
			}, {
				title: "ti ti-layout-distribute-vertical-filled",
				searchTerms: ["layout-distribute-vertical-filled"]
			}, {
				title: "ti ti-layout-filled",
				searchTerms: ["layout-filled"]
			}, {
				title: "ti ti-layout-grid",
				searchTerms: ["layout-grid"]
			}, {
				title: "ti ti-layout-grid-add",
				searchTerms: ["layout-grid-add"]
			}, {
				title: "ti ti-layout-grid-filled",
				searchTerms: ["layout-grid-filled"]
			}, {
				title: "ti ti-layout-grid-remove",
				searchTerms: ["layout-grid-remove"]
			}, {
				title: "ti ti-layout-kanban",
				searchTerms: ["layout-kanban"]
			}, {
				title: "ti ti-layout-kanban-filled",
				searchTerms: ["layout-kanban-filled"]
			}, {
				title: "ti ti-layout-list",
				searchTerms: ["layout-list"]
			}, {
				title: "ti ti-layout-list-filled",
				searchTerms: ["layout-list-filled"]
			}, {
				title: "ti ti-layout-navbar",
				searchTerms: ["layout-navbar"]
			}, {
				title: "ti ti-layout-navbar-collapse",
				searchTerms: ["layout-navbar-collapse"]
			}, {
				title: "ti ti-layout-navbar-collapse-filled",
				searchTerms: ["layout-navbar-collapse-filled"]
			}, {
				title: "ti ti-layout-navbar-expand",
				searchTerms: ["layout-navbar-expand"]
			}, {
				title: "ti ti-layout-navbar-expand-filled",
				searchTerms: ["layout-navbar-expand-filled"]
			}, {
				title: "ti ti-layout-navbar-filled",
				searchTerms: ["layout-navbar-filled"]
			}, {
				title: "ti ti-layout-navbar-inactive",
				searchTerms: ["layout-navbar-inactive"]
			}, {
				title: "ti ti-layout-off",
				searchTerms: ["layout-off"]
			}, {
				title: "ti ti-layout-rows",
				searchTerms: ["layout-rows"]
			}, {
				title: "ti ti-layout-sidebar",
				searchTerms: ["layout-sidebar"]
			}, {
				title: "ti ti-layout-sidebar-filled",
				searchTerms: ["layout-sidebar-filled"]
			}, {
				title: "ti ti-layout-sidebar-inactive",
				searchTerms: ["layout-sidebar-inactive"]
			}, {
				title: "ti ti-layout-sidebar-left-collapse",
				searchTerms: ["layout-sidebar-left-collapse"]
			}, {
				title: "ti ti-layout-sidebar-left-collapse-filled",
				searchTerms: ["layout-sidebar-left-collapse-filled"]
			}, {
				title: "ti ti-layout-sidebar-left-expand",
				searchTerms: ["layout-sidebar-left-expand"]
			}, {
				title: "ti ti-layout-sidebar-left-expand-filled",
				searchTerms: ["layout-sidebar-left-expand-filled"]
			}, {
				title: "ti ti-layout-sidebar-right",
				searchTerms: ["layout-sidebar-right"]
			}, {
				title: "ti ti-layout-sidebar-right-collapse",
				searchTerms: ["layout-sidebar-right-collapse"]
			}, {
				title: "ti ti-layout-sidebar-right-collapse-filled",
				searchTerms: ["layout-sidebar-right-collapse-filled"]
			}, {
				title: "ti ti-layout-sidebar-right-expand",
				searchTerms: ["layout-sidebar-right-expand"]
			}, {
				title: "ti ti-layout-sidebar-right-expand-filled",
				searchTerms: ["layout-sidebar-right-expand-filled"]
			}, {
				title: "ti ti-layout-sidebar-right-filled",
				searchTerms: ["layout-sidebar-right-filled"]
			}, {
				title: "ti ti-layout-sidebar-right-inactive",
				searchTerms: ["layout-sidebar-right-inactive"]
			}, {
				title: "ti ti-leaf",
				searchTerms: ["leaf"]
			}, {
				title: "ti ti-leaf-off",
				searchTerms: ["leaf-off"]
			}, {
				title: "ti ti-lego",
				searchTerms: ["lego"]
			}, {
				title: "ti ti-lego-filled",
				searchTerms: ["lego-filled"]
			}, {
				title: "ti ti-lego-off",
				searchTerms: ["lego-off"]
			}, {
				title: "ti ti-lemon",
				searchTerms: ["lemon"]
			}, {
				title: "ti ti-lemon-2",
				searchTerms: ["lemon-2"]
			}, {
				title: "ti ti-letter-a",
				searchTerms: ["letter-a"]
			}, {
				title: "ti ti-letter-a-small",
				searchTerms: ["letter-a-small"]
			}, {
				title: "ti ti-letter-b",
				searchTerms: ["letter-b"]
			}, {
				title: "ti ti-letter-b-small",
				searchTerms: ["letter-b-small"]
			}, {
				title: "ti ti-letter-c",
				searchTerms: ["letter-c"]
			}, {
				title: "ti ti-letter-c-small",
				searchTerms: ["letter-c-small"]
			}, {
				title: "ti ti-letter-case",
				searchTerms: ["letter-case"]
			}, {
				title: "ti ti-letter-case-lower",
				searchTerms: ["letter-case-lower"]
			}, {
				title: "ti ti-letter-case-toggle",
				searchTerms: ["letter-case-toggle"]
			}, {
				title: "ti ti-letter-case-upper",
				searchTerms: ["letter-case-upper"]
			}, {
				title: "ti ti-letter-d",
				searchTerms: ["letter-d"]
			}, {
				title: "ti ti-letter-d-small",
				searchTerms: ["letter-d-small"]
			}, {
				title: "ti ti-letter-e",
				searchTerms: ["letter-e"]
			}, {
				title: "ti ti-letter-e-small",
				searchTerms: ["letter-e-small"]
			}, {
				title: "ti ti-letter-f",
				searchTerms: ["letter-f"]
			}, {
				title: "ti ti-letter-f-small",
				searchTerms: ["letter-f-small"]
			}, {
				title: "ti ti-letter-g",
				searchTerms: ["letter-g"]
			}, {
				title: "ti ti-letter-g-small",
				searchTerms: ["letter-g-small"]
			}, {
				title: "ti ti-letter-h",
				searchTerms: ["letter-h"]
			}, {
				title: "ti ti-letter-h-small",
				searchTerms: ["letter-h-small"]
			}, {
				title: "ti ti-letter-i",
				searchTerms: ["letter-i"]
			}, {
				title: "ti ti-letter-i-small",
				searchTerms: ["letter-i-small"]
			}, {
				title: "ti ti-letter-j",
				searchTerms: ["letter-j"]
			}, {
				title: "ti ti-letter-j-small",
				searchTerms: ["letter-j-small"]
			}, {
				title: "ti ti-letter-k",
				searchTerms: ["letter-k"]
			}, {
				title: "ti ti-letter-k-small",
				searchTerms: ["letter-k-small"]
			}, {
				title: "ti ti-letter-l",
				searchTerms: ["letter-l"]
			}, {
				title: "ti ti-letter-l-small",
				searchTerms: ["letter-l-small"]
			}, {
				title: "ti ti-letter-m",
				searchTerms: ["letter-m"]
			}, {
				title: "ti ti-letter-m-small",
				searchTerms: ["letter-m-small"]
			}, {
				title: "ti ti-letter-n",
				searchTerms: ["letter-n"]
			}, {
				title: "ti ti-letter-n-small",
				searchTerms: ["letter-n-small"]
			}, {
				title: "ti ti-letter-o",
				searchTerms: ["letter-o"]
			}, {
				title: "ti ti-letter-o-small",
				searchTerms: ["letter-o-small"]
			}, {
				title: "ti ti-letter-p",
				searchTerms: ["letter-p"]
			}, {
				title: "ti ti-letter-p-small",
				searchTerms: ["letter-p-small"]
			}, {
				title: "ti ti-letter-q",
				searchTerms: ["letter-q"]
			}, {
				title: "ti ti-letter-q-small",
				searchTerms: ["letter-q-small"]
			}, {
				title: "ti ti-letter-r",
				searchTerms: ["letter-r"]
			}, {
				title: "ti ti-letter-r-small",
				searchTerms: ["letter-r-small"]
			}, {
				title: "ti ti-letter-s",
				searchTerms: ["letter-s"]
			}, {
				title: "ti ti-letter-s-small",
				searchTerms: ["letter-s-small"]
			}, {
				title: "ti ti-letter-spacing",
				searchTerms: ["letter-spacing"]
			}, {
				title: "ti ti-letter-t",
				searchTerms: ["letter-t"]
			}, {
				title: "ti ti-letter-t-small",
				searchTerms: ["letter-t-small"]
			}, {
				title: "ti ti-letter-u",
				searchTerms: ["letter-u"]
			}, {
				title: "ti ti-letter-u-small",
				searchTerms: ["letter-u-small"]
			}, {
				title: "ti ti-letter-v",
				searchTerms: ["letter-v"]
			}, {
				title: "ti ti-letter-v-small",
				searchTerms: ["letter-v-small"]
			}, {
				title: "ti ti-letter-w",
				searchTerms: ["letter-w"]
			}, {
				title: "ti ti-letter-w-small",
				searchTerms: ["letter-w-small"]
			}, {
				title: "ti ti-letter-x",
				searchTerms: ["letter-x"]
			}, {
				title: "ti ti-letter-x-small",
				searchTerms: ["letter-x-small"]
			}, {
				title: "ti ti-letter-y",
				searchTerms: ["letter-y"]
			}, {
				title: "ti ti-letter-y-small",
				searchTerms: ["letter-y-small"]
			}, {
				title: "ti ti-letter-z",
				searchTerms: ["letter-z"]
			}, {
				title: "ti ti-letter-z-small",
				searchTerms: ["letter-z-small"]
			}, {
				title: "ti ti-library",
				searchTerms: ["library"]
			}, {
				title: "ti ti-library-minus",
				searchTerms: ["library-minus"]
			}, {
				title: "ti ti-library-photo",
				searchTerms: ["library-photo"]
			}, {
				title: "ti ti-library-plus",
				searchTerms: ["library-plus"]
			}, {
				title: "ti ti-license",
				searchTerms: ["license"]
			}, {
				title: "ti ti-license-off",
				searchTerms: ["license-off"]
			}, {
				title: "ti ti-lifebuoy",
				searchTerms: ["lifebuoy"]
			}, {
				title: "ti ti-lifebuoy-off",
				searchTerms: ["lifebuoy-off"]
			}, {
				title: "ti ti-lighter",
				searchTerms: ["lighter"]
			}, {
				title: "ti ti-line",
				searchTerms: ["line"]
			}, {
				title: "ti ti-line-dashed",
				searchTerms: ["line-dashed"]
			}, {
				title: "ti ti-line-dotted",
				searchTerms: ["line-dotted"]
			}, {
				title: "ti ti-line-height",
				searchTerms: ["line-height"]
			}, {
				title: "ti ti-line-scan",
				searchTerms: ["line-scan"]
			}, {
				title: "ti ti-link",
				searchTerms: ["link"]
			}, {
				title: "ti ti-link-minus",
				searchTerms: ["link-minus"]
			}, {
				title: "ti ti-link-off",
				searchTerms: ["link-off"]
			}, {
				title: "ti ti-link-plus",
				searchTerms: ["link-plus"]
			}, {
				title: "ti ti-list",
				searchTerms: ["list"]
			}, {
				title: "ti ti-list-check",
				searchTerms: ["list-check"]
			}, {
				title: "ti ti-list-details",
				searchTerms: ["list-details"]
			}, {
				title: "ti ti-list-letters",
				searchTerms: ["list-letters"]
			}, {
				title: "ti ti-list-numbers",
				searchTerms: ["list-numbers"]
			}, {
				title: "ti ti-list-search",
				searchTerms: ["list-search"]
			}, {
				title: "ti ti-list-tree",
				searchTerms: ["list-tree"]
			}, {
				title: "ti ti-live-photo",
				searchTerms: ["live-photo"]
			}, {
				title: "ti ti-live-photo-filled",
				searchTerms: ["live-photo-filled"]
			}, {
				title: "ti ti-live-photo-off",
				searchTerms: ["live-photo-off"]
			}, {
				title: "ti ti-live-view",
				searchTerms: ["live-view"]
			}, {
				title: "ti ti-load-balancer",
				searchTerms: ["load-balancer"]
			}, {
				title: "ti ti-loader",
				searchTerms: ["loader"]
			}, {
				title: "ti ti-loader-2",
				searchTerms: ["loader-2"]
			}, {
				title: "ti ti-loader-3",
				searchTerms: ["loader-3"]
			}, {
				title: "ti ti-loader-quarter",
				searchTerms: ["loader-quarter"]
			}, {
				title: "ti ti-location",
				searchTerms: ["location"]
			}, {
				title: "ti ti-location-bolt",
				searchTerms: ["location-bolt"]
			}, {
				title: "ti ti-location-broken",
				searchTerms: ["location-broken"]
			}, {
				title: "ti ti-location-cancel",
				searchTerms: ["location-cancel"]
			}, {
				title: "ti ti-location-check",
				searchTerms: ["location-check"]
			}, {
				title: "ti ti-location-code",
				searchTerms: ["location-code"]
			}, {
				title: "ti ti-location-cog",
				searchTerms: ["location-cog"]
			}, {
				title: "ti ti-location-discount",
				searchTerms: ["location-discount"]
			}, {
				title: "ti ti-location-dollar",
				searchTerms: ["location-dollar"]
			}, {
				title: "ti ti-location-down",
				searchTerms: ["location-down"]
			}, {
				title: "ti ti-location-exclamation",
				searchTerms: ["location-exclamation"]
			}, {
				title: "ti ti-location-filled",
				searchTerms: ["location-filled"]
			}, {
				title: "ti ti-location-heart",
				searchTerms: ["location-heart"]
			}, {
				title: "ti ti-location-minus",
				searchTerms: ["location-minus"]
			}, {
				title: "ti ti-location-off",
				searchTerms: ["location-off"]
			}, {
				title: "ti ti-location-pause",
				searchTerms: ["location-pause"]
			}, {
				title: "ti ti-location-pin",
				searchTerms: ["location-pin"]
			}, {
				title: "ti ti-location-plus",
				searchTerms: ["location-plus"]
			}, {
				title: "ti ti-location-question",
				searchTerms: ["location-question"]
			}, {
				title: "ti ti-location-search",
				searchTerms: ["location-search"]
			}, {
				title: "ti ti-location-share",
				searchTerms: ["location-share"]
			}, {
				title: "ti ti-location-star",
				searchTerms: ["location-star"]
			}, {
				title: "ti ti-location-up",
				searchTerms: ["location-up"]
			}, {
				title: "ti ti-location-x",
				searchTerms: ["location-x"]
			}, {
				title: "ti ti-lock",
				searchTerms: ["lock"]
			}, {
				title: "ti ti-lock-access",
				searchTerms: ["lock-access"]
			}, {
				title: "ti ti-lock-access-off",
				searchTerms: ["lock-access-off"]
			}, {
				title: "ti ti-lock-bolt",
				searchTerms: ["lock-bolt"]
			}, {
				title: "ti ti-lock-cancel",
				searchTerms: ["lock-cancel"]
			}, {
				title: "ti ti-lock-check",
				searchTerms: ["lock-check"]
			}, {
				title: "ti ti-lock-code",
				searchTerms: ["lock-code"]
			}, {
				title: "ti ti-lock-cog",
				searchTerms: ["lock-cog"]
			}, {
				title: "ti ti-lock-dollar",
				searchTerms: ["lock-dollar"]
			}, {
				title: "ti ti-lock-down",
				searchTerms: ["lock-down"]
			}, {
				title: "ti ti-lock-exclamation",
				searchTerms: ["lock-exclamation"]
			}, {
				title: "ti ti-lock-filled",
				searchTerms: ["lock-filled"]
			}, {
				title: "ti ti-lock-heart",
				searchTerms: ["lock-heart"]
			}, {
				title: "ti ti-lock-minus",
				searchTerms: ["lock-minus"]
			}, {
				title: "ti ti-lock-off",
				searchTerms: ["lock-off"]
			}, {
				title: "ti ti-lock-open",
				searchTerms: ["lock-open"]
			}, {
				title: "ti ti-lock-open-2",
				searchTerms: ["lock-open-2"]
			}, {
				title: "ti ti-lock-open-off",
				searchTerms: ["lock-open-off"]
			}, {
				title: "ti ti-lock-pause",
				searchTerms: ["lock-pause"]
			}, {
				title: "ti ti-lock-pin",
				searchTerms: ["lock-pin"]
			}, {
				title: "ti ti-lock-plus",
				searchTerms: ["lock-plus"]
			}, {
				title: "ti ti-lock-question",
				searchTerms: ["lock-question"]
			}, {
				title: "ti ti-lock-search",
				searchTerms: ["lock-search"]
			}, {
				title: "ti ti-lock-share",
				searchTerms: ["lock-share"]
			}, {
				title: "ti ti-lock-square",
				searchTerms: ["lock-square"]
			}, {
				title: "ti ti-lock-square-rounded",
				searchTerms: ["lock-square-rounded"]
			}, {
				title: "ti ti-lock-square-rounded-filled",
				searchTerms: ["lock-square-rounded-filled"]
			}, {
				title: "ti ti-lock-star",
				searchTerms: ["lock-star"]
			}, {
				title: "ti ti-lock-up",
				searchTerms: ["lock-up"]
			}, {
				title: "ti ti-lock-x",
				searchTerms: ["lock-x"]
			}, {
				title: "ti ti-logic-and",
				searchTerms: ["logic-and"]
			}, {
				title: "ti ti-logic-buffer",
				searchTerms: ["logic-buffer"]
			}, {
				title: "ti ti-logic-nand",
				searchTerms: ["logic-nand"]
			}, {
				title: "ti ti-logic-nor",
				searchTerms: ["logic-nor"]
			}, {
				title: "ti ti-logic-not",
				searchTerms: ["logic-not"]
			}, {
				title: "ti ti-logic-or",
				searchTerms: ["logic-or"]
			}, {
				title: "ti ti-logic-xnor",
				searchTerms: ["logic-xnor"]
			}, {
				title: "ti ti-logic-xor",
				searchTerms: ["logic-xor"]
			}, {
				title: "ti ti-login",
				searchTerms: ["login"]
			}, {
				title: "ti ti-login-2",
				searchTerms: ["login-2"]
			}, {
				title: "ti ti-logout",
				searchTerms: ["logout"]
			}, {
				title: "ti ti-logout-2",
				searchTerms: ["logout-2"]
			}, {
				title: "ti ti-logs",
				searchTerms: ["logs"]
			}, {
				title: "ti ti-lollipop",
				searchTerms: ["lollipop"]
			}, {
				title: "ti ti-lollipop-off",
				searchTerms: ["lollipop-off"]
			}, {
				title: "ti ti-luggage",
				searchTerms: ["luggage"]
			}, {
				title: "ti ti-luggage-off",
				searchTerms: ["luggage-off"]
			}, {
				title: "ti ti-lungs",
				searchTerms: ["lungs"]
			}, {
				title: "ti ti-lungs-filled",
				searchTerms: ["lungs-filled"]
			}, {
				title: "ti ti-lungs-off",
				searchTerms: ["lungs-off"]
			}, {
				title: "ti ti-macro",
				searchTerms: ["macro"]
			}, {
				title: "ti ti-macro-filled",
				searchTerms: ["macro-filled"]
			}, {
				title: "ti ti-macro-off",
				searchTerms: ["macro-off"]
			}, {
				title: "ti ti-magnet",
				searchTerms: ["magnet"]
			}, {
				title: "ti ti-magnet-filled",
				searchTerms: ["magnet-filled"]
			}, {
				title: "ti ti-magnet-off",
				searchTerms: ["magnet-off"]
			}, {
				title: "ti ti-magnetic",
				searchTerms: ["magnetic"]
			}, {
				title: "ti ti-mail",
				searchTerms: ["mail"]
			}, {
				title: "ti ti-mail-ai",
				searchTerms: ["mail-ai"]
			}, {
				title: "ti ti-mail-bolt",
				searchTerms: ["mail-bolt"]
			}, {
				title: "ti ti-mail-cancel",
				searchTerms: ["mail-cancel"]
			}, {
				title: "ti ti-mail-check",
				searchTerms: ["mail-check"]
			}, {
				title: "ti ti-mail-code",
				searchTerms: ["mail-code"]
			}, {
				title: "ti ti-mail-cog",
				searchTerms: ["mail-cog"]
			}, {
				title: "ti ti-mail-dollar",
				searchTerms: ["mail-dollar"]
			}, {
				title: "ti ti-mail-down",
				searchTerms: ["mail-down"]
			}, {
				title: "ti ti-mail-exclamation",
				searchTerms: ["mail-exclamation"]
			}, {
				title: "ti ti-mail-fast",
				searchTerms: ["mail-fast"]
			}, {
				title: "ti ti-mail-filled",
				searchTerms: ["mail-filled"]
			}, {
				title: "ti ti-mail-forward",
				searchTerms: ["mail-forward"]
			}, {
				title: "ti ti-mail-heart",
				searchTerms: ["mail-heart"]
			}, {
				title: "ti ti-mail-minus",
				searchTerms: ["mail-minus"]
			}, {
				title: "ti ti-mail-off",
				searchTerms: ["mail-off"]
			}, {
				title: "ti ti-mail-opened",
				searchTerms: ["mail-opened"]
			}, {
				title: "ti ti-mail-opened-filled",
				searchTerms: ["mail-opened-filled"]
			}, {
				title: "ti ti-mail-pause",
				searchTerms: ["mail-pause"]
			}, {
				title: "ti ti-mail-pin",
				searchTerms: ["mail-pin"]
			}, {
				title: "ti ti-mail-plus",
				searchTerms: ["mail-plus"]
			}, {
				title: "ti ti-mail-question",
				searchTerms: ["mail-question"]
			}, {
				title: "ti ti-mail-search",
				searchTerms: ["mail-search"]
			}, {
				title: "ti ti-mail-share",
				searchTerms: ["mail-share"]
			}, {
				title: "ti ti-mail-star",
				searchTerms: ["mail-star"]
			}, {
				title: "ti ti-mail-up",
				searchTerms: ["mail-up"]
			}, {
				title: "ti ti-mail-x",
				searchTerms: ["mail-x"]
			}, {
				title: "ti ti-mailbox",
				searchTerms: ["mailbox"]
			}, {
				title: "ti ti-mailbox-off",
				searchTerms: ["mailbox-off"]
			}, {
				title: "ti ti-man",
				searchTerms: ["man"]
			}, {
				title: "ti ti-man-filled",
				searchTerms: ["man-filled"]
			}, {
				title: "ti ti-manual-gearbox",
				searchTerms: ["manual-gearbox"]
			}, {
				title: "ti ti-manual-gearbox-filled",
				searchTerms: ["manual-gearbox-filled"]
			}, {
				title: "ti ti-map",
				searchTerms: ["map"]
			}, {
				title: "ti ti-map-2",
				searchTerms: ["map-2"]
			}, {
				title: "ti ti-map-bolt",
				searchTerms: ["map-bolt"]
			}, {
				title: "ti ti-map-cancel",
				searchTerms: ["map-cancel"]
			}, {
				title: "ti ti-map-check",
				searchTerms: ["map-check"]
			}, {
				title: "ti ti-map-code",
				searchTerms: ["map-code"]
			}, {
				title: "ti ti-map-cog",
				searchTerms: ["map-cog"]
			}, {
				title: "ti ti-map-discount",
				searchTerms: ["map-discount"]
			}, {
				title: "ti ti-map-dollar",
				searchTerms: ["map-dollar"]
			}, {
				title: "ti ti-map-down",
				searchTerms: ["map-down"]
			}, {
				title: "ti ti-map-east",
				searchTerms: ["map-east"]
			}, {
				title: "ti ti-map-exclamation",
				searchTerms: ["map-exclamation"]
			}, {
				title: "ti ti-map-heart",
				searchTerms: ["map-heart"]
			}, {
				title: "ti ti-map-minus",
				searchTerms: ["map-minus"]
			}, {
				title: "ti ti-map-north",
				searchTerms: ["map-north"]
			}, {
				title: "ti ti-map-off",
				searchTerms: ["map-off"]
			}, {
				title: "ti ti-map-pause",
				searchTerms: ["map-pause"]
			}, {
				title: "ti ti-map-pin",
				searchTerms: ["map-pin"]
			}, {
				title: "ti ti-map-pin-2",
				searchTerms: ["map-pin-2"]
			}, {
				title: "ti ti-map-pin-bolt",
				searchTerms: ["map-pin-bolt"]
			}, {
				title: "ti ti-map-pin-cancel",
				searchTerms: ["map-pin-cancel"]
			}, {
				title: "ti ti-map-pin-check",
				searchTerms: ["map-pin-check"]
			}, {
				title: "ti ti-map-pin-code",
				searchTerms: ["map-pin-code"]
			}, {
				title: "ti ti-map-pin-cog",
				searchTerms: ["map-pin-cog"]
			}, {
				title: "ti ti-map-pin-dollar",
				searchTerms: ["map-pin-dollar"]
			}, {
				title: "ti ti-map-pin-down",
				searchTerms: ["map-pin-down"]
			}, {
				title: "ti ti-map-pin-exclamation",
				searchTerms: ["map-pin-exclamation"]
			}, {
				title: "ti ti-map-pin-filled",
				searchTerms: ["map-pin-filled"]
			}, {
				title: "ti ti-map-pin-heart",
				searchTerms: ["map-pin-heart"]
			}, {
				title: "ti ti-map-pin-minus",
				searchTerms: ["map-pin-minus"]
			}, {
				title: "ti ti-map-pin-off",
				searchTerms: ["map-pin-off"]
			}, {
				title: "ti ti-map-pin-pause",
				searchTerms: ["map-pin-pause"]
			}, {
				title: "ti ti-map-pin-pin",
				searchTerms: ["map-pin-pin"]
			}, {
				title: "ti ti-map-pin-plus",
				searchTerms: ["map-pin-plus"]
			}, {
				title: "ti ti-map-pin-question",
				searchTerms: ["map-pin-question"]
			}, {
				title: "ti ti-map-pin-search",
				searchTerms: ["map-pin-search"]
			}, {
				title: "ti ti-map-pin-share",
				searchTerms: ["map-pin-share"]
			}, {
				title: "ti ti-map-pin-star",
				searchTerms: ["map-pin-star"]
			}, {
				title: "ti ti-map-pin-up",
				searchTerms: ["map-pin-up"]
			}, {
				title: "ti ti-map-pin-x",
				searchTerms: ["map-pin-x"]
			}, {
				title: "ti ti-map-pins",
				searchTerms: ["map-pins"]
			}, {
				title: "ti ti-map-plus",
				searchTerms: ["map-plus"]
			}, {
				title: "ti ti-map-question",
				searchTerms: ["map-question"]
			}, {
				title: "ti ti-map-route",
				searchTerms: ["map-route"]
			}, {
				title: "ti ti-map-search",
				searchTerms: ["map-search"]
			}, {
				title: "ti ti-map-share",
				searchTerms: ["map-share"]
			}, {
				title: "ti ti-map-south",
				searchTerms: ["map-south"]
			}, {
				title: "ti ti-map-star",
				searchTerms: ["map-star"]
			}, {
				title: "ti ti-map-up",
				searchTerms: ["map-up"]
			}, {
				title: "ti ti-map-west",
				searchTerms: ["map-west"]
			}, {
				title: "ti ti-map-x",
				searchTerms: ["map-x"]
			}, {
				title: "ti ti-markdown",
				searchTerms: ["markdown"]
			}, {
				title: "ti ti-markdown-off",
				searchTerms: ["markdown-off"]
			}, {
				title: "ti ti-marquee",
				searchTerms: ["marquee"]
			}, {
				title: "ti ti-marquee-2",
				searchTerms: ["marquee-2"]
			}, {
				title: "ti ti-marquee-off",
				searchTerms: ["marquee-off"]
			}, {
				title: "ti ti-mars",
				searchTerms: ["mars"]
			}, {
				title: "ti ti-mask",
				searchTerms: ["mask"]
			}, {
				title: "ti ti-mask-off",
				searchTerms: ["mask-off"]
			}, {
				title: "ti ti-masks-theater",
				searchTerms: ["masks-theater"]
			}, {
				title: "ti ti-masks-theater-off",
				searchTerms: ["masks-theater-off"]
			}, {
				title: "ti ti-massage",
				searchTerms: ["massage"]
			}, {
				title: "ti ti-matchstick",
				searchTerms: ["matchstick"]
			}, {
				title: "ti ti-math",
				searchTerms: ["math"]
			}, {
				title: "ti ti-math-1-divide-2",
				searchTerms: ["math-1-divide-2"]
			}, {
				title: "ti ti-math-1-divide-3",
				searchTerms: ["math-1-divide-3"]
			}, {
				title: "ti ti-math-avg",
				searchTerms: ["math-avg"]
			}, {
				title: "ti ti-math-equal-greater",
				searchTerms: ["math-equal-greater"]
			}, {
				title: "ti ti-math-equal-lower",
				searchTerms: ["math-equal-lower"]
			}, {
				title: "ti ti-math-function",
				searchTerms: ["math-function"]
			}, {
				title: "ti ti-math-function-off",
				searchTerms: ["math-function-off"]
			}, {
				title: "ti ti-math-function-y",
				searchTerms: ["math-function-y"]
			}, {
				title: "ti ti-math-greater",
				searchTerms: ["math-greater"]
			}, {
				title: "ti ti-math-integral",
				searchTerms: ["math-integral"]
			}, {
				title: "ti ti-math-integral-x",
				searchTerms: ["math-integral-x"]
			}, {
				title: "ti ti-math-integrals",
				searchTerms: ["math-integrals"]
			}, {
				title: "ti ti-math-lower",
				searchTerms: ["math-lower"]
			}, {
				title: "ti ti-math-max",
				searchTerms: ["math-max"]
			}, {
				title: "ti ti-math-max-min",
				searchTerms: ["math-max-min"]
			}, {
				title: "ti ti-math-min",
				searchTerms: ["math-min"]
			}, {
				title: "ti ti-math-not",
				searchTerms: ["math-not"]
			}, {
				title: "ti ti-math-off",
				searchTerms: ["math-off"]
			}, {
				title: "ti ti-math-pi",
				searchTerms: ["math-pi"]
			}, {
				title: "ti ti-math-pi-divide-2",
				searchTerms: ["math-pi-divide-2"]
			}, {
				title: "ti ti-math-symbols",
				searchTerms: ["math-symbols"]
			}, {
				title: "ti ti-math-x-divide-2",
				searchTerms: ["math-x-divide-2"]
			}, {
				title: "ti ti-math-x-divide-y",
				searchTerms: ["math-x-divide-y"]
			}, {
				title: "ti ti-math-x-divide-y-2",
				searchTerms: ["math-x-divide-y-2"]
			}, {
				title: "ti ti-math-x-minus-x",
				searchTerms: ["math-x-minus-x"]
			}, {
				title: "ti ti-math-x-minus-y",
				searchTerms: ["math-x-minus-y"]
			}, {
				title: "ti ti-math-x-plus-x",
				searchTerms: ["math-x-plus-x"]
			}, {
				title: "ti ti-math-x-plus-y",
				searchTerms: ["math-x-plus-y"]
			}, {
				title: "ti ti-math-xy",
				searchTerms: ["math-xy"]
			}, {
				title: "ti ti-math-y-minus-y",
				searchTerms: ["math-y-minus-y"]
			}, {
				title: "ti ti-math-y-plus-y",
				searchTerms: ["math-y-plus-y"]
			}, {
				title: "ti ti-maximize",
				searchTerms: ["maximize"]
			}, {
				title: "ti ti-maximize-off",
				searchTerms: ["maximize-off"]
			}, {
				title: "ti ti-meat",
				searchTerms: ["meat"]
			}, {
				title: "ti ti-meat-off",
				searchTerms: ["meat-off"]
			}, {
				title: "ti ti-medal",
				searchTerms: ["medal"]
			}, {
				title: "ti ti-medal-2",
				searchTerms: ["medal-2"]
			}, {
				title: "ti ti-medical-cross",
				searchTerms: ["medical-cross"]
			}, {
				title: "ti ti-medical-cross-circle",
				searchTerms: ["medical-cross-circle"]
			}, {
				title: "ti ti-medical-cross-filled",
				searchTerms: ["medical-cross-filled"]
			}, {
				title: "ti ti-medical-cross-off",
				searchTerms: ["medical-cross-off"]
			}, {
				title: "ti ti-medicine-syrup",
				searchTerms: ["medicine-syrup"]
			}, {
				title: "ti ti-meeple",
				searchTerms: ["meeple"]
			}, {
				title: "ti ti-melon",
				searchTerms: ["melon"]
			}, {
				title: "ti ti-menorah",
				searchTerms: ["menorah"]
			}, {
				title: "ti ti-menu",
				searchTerms: ["menu"]
			}, {
				title: "ti ti-menu-2",
				searchTerms: ["menu-2"]
			}, {
				title: "ti ti-menu-deep",
				searchTerms: ["menu-deep"]
			}, {
				title: "ti ti-menu-order",
				searchTerms: ["menu-order"]
			}, {
				title: "ti ti-message",
				searchTerms: ["message"]
			}, {
				title: "ti ti-message-2",
				searchTerms: ["message-2"]
			}, {
				title: "ti ti-message-2-bolt",
				searchTerms: ["message-2-bolt"]
			}, {
				title: "ti ti-message-2-cancel",
				searchTerms: ["message-2-cancel"]
			}, {
				title: "ti ti-message-2-check",
				searchTerms: ["message-2-check"]
			}, {
				title: "ti ti-message-2-code",
				searchTerms: ["message-2-code"]
			}, {
				title: "ti ti-message-2-cog",
				searchTerms: ["message-2-cog"]
			}, {
				title: "ti ti-message-2-dollar",
				searchTerms: ["message-2-dollar"]
			}, {
				title: "ti ti-message-2-down",
				searchTerms: ["message-2-down"]
			}, {
				title: "ti ti-message-2-exclamation",
				searchTerms: ["message-2-exclamation"]
			}, {
				title: "ti ti-message-2-heart",
				searchTerms: ["message-2-heart"]
			}, {
				title: "ti ti-message-2-minus",
				searchTerms: ["message-2-minus"]
			}, {
				title: "ti ti-message-2-off",
				searchTerms: ["message-2-off"]
			}, {
				title: "ti ti-message-2-pause",
				searchTerms: ["message-2-pause"]
			}, {
				title: "ti ti-message-2-pin",
				searchTerms: ["message-2-pin"]
			}, {
				title: "ti ti-message-2-plus",
				searchTerms: ["message-2-plus"]
			}, {
				title: "ti ti-message-2-question",
				searchTerms: ["message-2-question"]
			}, {
				title: "ti ti-message-2-search",
				searchTerms: ["message-2-search"]
			}, {
				title: "ti ti-message-2-share",
				searchTerms: ["message-2-share"]
			}, {
				title: "ti ti-message-2-star",
				searchTerms: ["message-2-star"]
			}, {
				title: "ti ti-message-2-up",
				searchTerms: ["message-2-up"]
			}, {
				title: "ti ti-message-2-x",
				searchTerms: ["message-2-x"]
			}, {
				title: "ti ti-message-bolt",
				searchTerms: ["message-bolt"]
			}, {
				title: "ti ti-message-cancel",
				searchTerms: ["message-cancel"]
			}, {
				title: "ti ti-message-chatbot",
				searchTerms: ["message-chatbot"]
			}, {
				title: "ti ti-message-chatbot-filled",
				searchTerms: ["message-chatbot-filled"]
			}, {
				title: "ti ti-message-check",
				searchTerms: ["message-check"]
			}, {
				title: "ti ti-message-circle",
				searchTerms: ["message-circle"]
			}, {
				title: "ti ti-message-circle-2",
				searchTerms: ["message-circle-2"]
			}, {
				title: "ti ti-message-circle-2-filled",
				searchTerms: ["message-circle-2-filled"]
			}, {
				title: "ti ti-message-circle-bolt",
				searchTerms: ["message-circle-bolt"]
			}, {
				title: "ti ti-message-circle-cancel",
				searchTerms: ["message-circle-cancel"]
			}, {
				title: "ti ti-message-circle-check",
				searchTerms: ["message-circle-check"]
			}, {
				title: "ti ti-message-circle-code",
				searchTerms: ["message-circle-code"]
			}, {
				title: "ti ti-message-circle-cog",
				searchTerms: ["message-circle-cog"]
			}, {
				title: "ti ti-message-circle-dollar",
				searchTerms: ["message-circle-dollar"]
			}, {
				title: "ti ti-message-circle-down",
				searchTerms: ["message-circle-down"]
			}, {
				title: "ti ti-message-circle-exclamation",
				searchTerms: ["message-circle-exclamation"]
			}, {
				title: "ti ti-message-circle-filled",
				searchTerms: ["message-circle-filled"]
			}, {
				title: "ti ti-message-circle-heart",
				searchTerms: ["message-circle-heart"]
			}, {
				title: "ti ti-message-circle-minus",
				searchTerms: ["message-circle-minus"]
			}, {
				title: "ti ti-message-circle-off",
				searchTerms: ["message-circle-off"]
			}, {
				title: "ti ti-message-circle-pause",
				searchTerms: ["message-circle-pause"]
			}, {
				title: "ti ti-message-circle-pin",
				searchTerms: ["message-circle-pin"]
			}, {
				title: "ti ti-message-circle-plus",
				searchTerms: ["message-circle-plus"]
			}, {
				title: "ti ti-message-circle-question",
				searchTerms: ["message-circle-question"]
			}, {
				title: "ti ti-message-circle-search",
				searchTerms: ["message-circle-search"]
			}, {
				title: "ti ti-message-circle-share",
				searchTerms: ["message-circle-share"]
			}, {
				title: "ti ti-message-circle-star",
				searchTerms: ["message-circle-star"]
			}, {
				title: "ti ti-message-circle-up",
				searchTerms: ["message-circle-up"]
			}, {
				title: "ti ti-message-circle-user",
				searchTerms: ["message-circle-user"]
			}, {
				title: "ti ti-message-circle-x",
				searchTerms: ["message-circle-x"]
			}, {
				title: "ti ti-message-code",
				searchTerms: ["message-code"]
			}, {
				title: "ti ti-message-cog",
				searchTerms: ["message-cog"]
			}, {
				title: "ti ti-message-dollar",
				searchTerms: ["message-dollar"]
			}, {
				title: "ti ti-message-dots",
				searchTerms: ["message-dots"]
			}, {
				title: "ti ti-message-down",
				searchTerms: ["message-down"]
			}, {
				title: "ti ti-message-exclamation",
				searchTerms: ["message-exclamation"]
			}, {
				title: "ti ti-message-filled",
				searchTerms: ["message-filled"]
			}, {
				title: "ti ti-message-forward",
				searchTerms: ["message-forward"]
			}, {
				title: "ti ti-message-heart",
				searchTerms: ["message-heart"]
			}, {
				title: "ti ti-message-language",
				searchTerms: ["message-language"]
			}, {
				title: "ti ti-message-minus",
				searchTerms: ["message-minus"]
			}, {
				title: "ti ti-message-off",
				searchTerms: ["message-off"]
			}, {
				title: "ti ti-message-pause",
				searchTerms: ["message-pause"]
			}, {
				title: "ti ti-message-pin",
				searchTerms: ["message-pin"]
			}, {
				title: "ti ti-message-plus",
				searchTerms: ["message-plus"]
			}, {
				title: "ti ti-message-question",
				searchTerms: ["message-question"]
			}, {
				title: "ti ti-message-reply",
				searchTerms: ["message-reply"]
			}, {
				title: "ti ti-message-report",
				searchTerms: ["message-report"]
			}, {
				title: "ti ti-message-report-filled",
				searchTerms: ["message-report-filled"]
			}, {
				title: "ti ti-message-search",
				searchTerms: ["message-search"]
			}, {
				title: "ti ti-message-share",
				searchTerms: ["message-share"]
			}, {
				title: "ti ti-message-star",
				searchTerms: ["message-star"]
			}, {
				title: "ti ti-message-up",
				searchTerms: ["message-up"]
			}, {
				title: "ti ti-message-user",
				searchTerms: ["message-user"]
			}, {
				title: "ti ti-message-x",
				searchTerms: ["message-x"]
			}, {
				title: "ti ti-messages",
				searchTerms: ["messages"]
			}, {
				title: "ti ti-messages-off",
				searchTerms: ["messages-off"]
			}, {
				title: "ti ti-meteor",
				searchTerms: ["meteor"]
			}, {
				title: "ti ti-meteor-off",
				searchTerms: ["meteor-off"]
			}, {
				title: "ti ti-meter-cube",
				searchTerms: ["meter-cube"]
			}, {
				title: "ti ti-meter-square",
				searchTerms: ["meter-square"]
			}, {
				title: "ti ti-metronome",
				searchTerms: ["metronome"]
			}, {
				title: "ti ti-michelin-bib-gourmand",
				searchTerms: ["michelin-bib-gourmand"]
			}, {
				title: "ti ti-michelin-star",
				searchTerms: ["michelin-star"]
			}, {
				title: "ti ti-michelin-star-green",
				searchTerms: ["michelin-star-green"]
			}, {
				title: "ti ti-mickey",
				searchTerms: ["mickey"]
			}, {
				title: "ti ti-mickey-filled",
				searchTerms: ["mickey-filled"]
			}, {
				title: "ti ti-microphone",
				searchTerms: ["microphone"]
			}, {
				title: "ti ti-microphone-2",
				searchTerms: ["microphone-2"]
			}, {
				title: "ti ti-microphone-2-off",
				searchTerms: ["microphone-2-off"]
			}, {
				title: "ti ti-microphone-filled",
				searchTerms: ["microphone-filled"]
			}, {
				title: "ti ti-microphone-off",
				searchTerms: ["microphone-off"]
			}, {
				title: "ti ti-microscope",
				searchTerms: ["microscope"]
			}, {
				title: "ti ti-microscope-off",
				searchTerms: ["microscope-off"]
			}, {
				title: "ti ti-microwave",
				searchTerms: ["microwave"]
			}, {
				title: "ti ti-microwave-filled",
				searchTerms: ["microwave-filled"]
			}, {
				title: "ti ti-microwave-off",
				searchTerms: ["microwave-off"]
			}, {
				title: "ti ti-military-award",
				searchTerms: ["military-award"]
			}, {
				title: "ti ti-military-rank",
				searchTerms: ["military-rank"]
			}, {
				title: "ti ti-milk",
				searchTerms: ["milk"]
			}, {
				title: "ti ti-milk-off",
				searchTerms: ["milk-off"]
			}, {
				title: "ti ti-milkshake",
				searchTerms: ["milkshake"]
			}, {
				title: "ti ti-minimize",
				searchTerms: ["minimize"]
			}, {
				title: "ti ti-minus",
				searchTerms: ["minus"]
			}, {
				title: "ti ti-minus-vertical",
				searchTerms: ["minus-vertical"]
			}, {
				title: "ti ti-mist",
				searchTerms: ["mist"]
			}, {
				title: "ti ti-mist-off",
				searchTerms: ["mist-off"]
			}, {
				title: "ti ti-mobiledata",
				searchTerms: ["mobiledata"]
			}, {
				title: "ti ti-mobiledata-off",
				searchTerms: ["mobiledata-off"]
			}, {
				title: "ti ti-moneybag",
				searchTerms: ["moneybag"]
			}, {
				title: "ti ti-monkeybar",
				searchTerms: ["monkeybar"]
			}, {
				title: "ti ti-mood-angry",
				searchTerms: ["mood-angry"]
			}, {
				title: "ti ti-mood-annoyed",
				searchTerms: ["mood-annoyed"]
			}, {
				title: "ti ti-mood-annoyed-2",
				searchTerms: ["mood-annoyed-2"]
			}, {
				title: "ti ti-mood-boy",
				searchTerms: ["mood-boy"]
			}, {
				title: "ti ti-mood-check",
				searchTerms: ["mood-check"]
			}, {
				title: "ti ti-mood-cog",
				searchTerms: ["mood-cog"]
			}, {
				title: "ti ti-mood-confuzed",
				searchTerms: ["mood-confuzed"]
			}, {
				title: "ti ti-mood-confuzed-filled",
				searchTerms: ["mood-confuzed-filled"]
			}, {
				title: "ti ti-mood-crazy-happy",
				searchTerms: ["mood-crazy-happy"]
			}, {
				title: "ti ti-mood-cry",
				searchTerms: ["mood-cry"]
			}, {
				title: "ti ti-mood-dollar",
				searchTerms: ["mood-dollar"]
			}, {
				title: "ti ti-mood-edit",
				searchTerms: ["mood-edit"]
			}, {
				title: "ti ti-mood-empty",
				searchTerms: ["mood-empty"]
			}, {
				title: "ti ti-mood-empty-filled",
				searchTerms: ["mood-empty-filled"]
			}, {
				title: "ti ti-mood-happy",
				searchTerms: ["mood-happy"]
			}, {
				title: "ti ti-mood-happy-filled",
				searchTerms: ["mood-happy-filled"]
			}, {
				title: "ti ti-mood-heart",
				searchTerms: ["mood-heart"]
			}, {
				title: "ti ti-mood-kid",
				searchTerms: ["mood-kid"]
			}, {
				title: "ti ti-mood-kid-filled",
				searchTerms: ["mood-kid-filled"]
			}, {
				title: "ti ti-mood-look-down",
				searchTerms: ["mood-look-down"]
			}, {
				title: "ti ti-mood-look-left",
				searchTerms: ["mood-look-left"]
			}, {
				title: "ti ti-mood-look-right",
				searchTerms: ["mood-look-right"]
			}, {
				title: "ti ti-mood-look-up",
				searchTerms: ["mood-look-up"]
			}, {
				title: "ti ti-mood-minus",
				searchTerms: ["mood-minus"]
			}, {
				title: "ti ti-mood-nerd",
				searchTerms: ["mood-nerd"]
			}, {
				title: "ti ti-mood-nervous",
				searchTerms: ["mood-nervous"]
			}, {
				title: "ti ti-mood-neutral",
				searchTerms: ["mood-neutral"]
			}, {
				title: "ti ti-mood-neutral-filled",
				searchTerms: ["mood-neutral-filled"]
			}, {
				title: "ti ti-mood-off",
				searchTerms: ["mood-off"]
			}, {
				title: "ti ti-mood-pin",
				searchTerms: ["mood-pin"]
			}, {
				title: "ti ti-mood-plus",
				searchTerms: ["mood-plus"]
			}, {
				title: "ti ti-mood-puzzled",
				searchTerms: ["mood-puzzled"]
			}, {
				title: "ti ti-mood-sad",
				searchTerms: ["mood-sad"]
			}, {
				title: "ti ti-mood-sad-2",
				searchTerms: ["mood-sad-2"]
			}, {
				title: "ti ti-mood-sad-dizzy",
				searchTerms: ["mood-sad-dizzy"]
			}, {
				title: "ti ti-mood-sad-filled",
				searchTerms: ["mood-sad-filled"]
			}, {
				title: "ti ti-mood-sad-squint",
				searchTerms: ["mood-sad-squint"]
			}, {
				title: "ti ti-mood-search",
				searchTerms: ["mood-search"]
			}, {
				title: "ti ti-mood-share",
				searchTerms: ["mood-share"]
			}, {
				title: "ti ti-mood-sick",
				searchTerms: ["mood-sick"]
			}, {
				title: "ti ti-mood-silence",
				searchTerms: ["mood-silence"]
			}, {
				title: "ti ti-mood-sing",
				searchTerms: ["mood-sing"]
			}, {
				title: "ti ti-mood-smile",
				searchTerms: ["mood-smile"]
			}, {
				title: "ti ti-mood-smile-beam",
				searchTerms: ["mood-smile-beam"]
			}, {
				title: "ti ti-mood-smile-dizzy",
				searchTerms: ["mood-smile-dizzy"]
			}, {
				title: "ti ti-mood-smile-filled",
				searchTerms: ["mood-smile-filled"]
			}, {
				title: "ti ti-mood-suprised",
				searchTerms: ["mood-suprised"]
			}, {
				title: "ti ti-mood-tongue",
				searchTerms: ["mood-tongue"]
			}, {
				title: "ti ti-mood-tongue-wink",
				searchTerms: ["mood-tongue-wink"]
			}, {
				title: "ti ti-mood-tongue-wink-2",
				searchTerms: ["mood-tongue-wink-2"]
			}, {
				title: "ti ti-mood-unamused",
				searchTerms: ["mood-unamused"]
			}, {
				title: "ti ti-mood-up",
				searchTerms: ["mood-up"]
			}, {
				title: "ti ti-mood-wink",
				searchTerms: ["mood-wink"]
			}, {
				title: "ti ti-mood-wink-2",
				searchTerms: ["mood-wink-2"]
			}, {
				title: "ti ti-mood-wrrr",
				searchTerms: ["mood-wrrr"]
			}, {
				title: "ti ti-mood-x",
				searchTerms: ["mood-x"]
			}, {
				title: "ti ti-mood-xd",
				searchTerms: ["mood-xd"]
			}, {
				title: "ti ti-moon",
				searchTerms: ["moon"]
			}, {
				title: "ti ti-moon-2",
				searchTerms: ["moon-2"]
			}, {
				title: "ti ti-moon-filled",
				searchTerms: ["moon-filled"]
			}, {
				title: "ti ti-moon-off",
				searchTerms: ["moon-off"]
			}, {
				title: "ti ti-moon-stars",
				searchTerms: ["moon-stars"]
			}, {
				title: "ti ti-moped",
				searchTerms: ["moped"]
			}, {
				title: "ti ti-motorbike",
				searchTerms: ["motorbike"]
			}, {
				title: "ti ti-mountain",
				searchTerms: ["mountain"]
			}, {
				title: "ti ti-mountain-off",
				searchTerms: ["mountain-off"]
			}, {
				title: "ti ti-mouse",
				searchTerms: ["mouse"]
			}, {
				title: "ti ti-mouse-2",
				searchTerms: ["mouse-2"]
			}, {
				title: "ti ti-mouse-filled",
				searchTerms: ["mouse-filled"]
			}, {
				title: "ti ti-mouse-off",
				searchTerms: ["mouse-off"]
			}, {
				title: "ti ti-moustache",
				searchTerms: ["moustache"]
			}, {
				title: "ti ti-movie",
				searchTerms: ["movie"]
			}, {
				title: "ti ti-movie-off",
				searchTerms: ["movie-off"]
			}, {
				title: "ti ti-mug",
				searchTerms: ["mug"]
			}, {
				title: "ti ti-mug-off",
				searchTerms: ["mug-off"]
			}, {
				title: "ti ti-multiplier-0-5x",
				searchTerms: ["multiplier-0-5x"]
			}, {
				title: "ti ti-multiplier-1-5x",
				searchTerms: ["multiplier-1-5x"]
			}, {
				title: "ti ti-multiplier-1x",
				searchTerms: ["multiplier-1x"]
			}, {
				title: "ti ti-multiplier-2x",
				searchTerms: ["multiplier-2x"]
			}, {
				title: "ti ti-mushroom",
				searchTerms: ["mushroom"]
			}, {
				title: "ti ti-mushroom-filled",
				searchTerms: ["mushroom-filled"]
			}, {
				title: "ti ti-mushroom-off",
				searchTerms: ["mushroom-off"]
			}, {
				title: "ti ti-music",
				searchTerms: ["music"]
			}, {
				title: "ti ti-music-bolt",
				searchTerms: ["music-bolt"]
			}, {
				title: "ti ti-music-cancel",
				searchTerms: ["music-cancel"]
			}, {
				title: "ti ti-music-check",
				searchTerms: ["music-check"]
			}, {
				title: "ti ti-music-code",
				searchTerms: ["music-code"]
			}, {
				title: "ti ti-music-cog",
				searchTerms: ["music-cog"]
			}, {
				title: "ti ti-music-discount",
				searchTerms: ["music-discount"]
			}, {
				title: "ti ti-music-dollar",
				searchTerms: ["music-dollar"]
			}, {
				title: "ti ti-music-down",
				searchTerms: ["music-down"]
			}, {
				title: "ti ti-music-exclamation",
				searchTerms: ["music-exclamation"]
			}, {
				title: "ti ti-music-heart",
				searchTerms: ["music-heart"]
			}, {
				title: "ti ti-music-minus",
				searchTerms: ["music-minus"]
			}, {
				title: "ti ti-music-off",
				searchTerms: ["music-off"]
			}, {
				title: "ti ti-music-pause",
				searchTerms: ["music-pause"]
			}, {
				title: "ti ti-music-pin",
				searchTerms: ["music-pin"]
			}, {
				title: "ti ti-music-plus",
				searchTerms: ["music-plus"]
			}, {
				title: "ti ti-music-question",
				searchTerms: ["music-question"]
			}, {
				title: "ti ti-music-search",
				searchTerms: ["music-search"]
			}, {
				title: "ti ti-music-share",
				searchTerms: ["music-share"]
			}, {
				title: "ti ti-music-star",
				searchTerms: ["music-star"]
			}, {
				title: "ti ti-music-up",
				searchTerms: ["music-up"]
			}, {
				title: "ti ti-music-x",
				searchTerms: ["music-x"]
			}, {
				title: "ti ti-navigation",
				searchTerms: ["navigation"]
			}, {
				title: "ti ti-navigation-bolt",
				searchTerms: ["navigation-bolt"]
			}, {
				title: "ti ti-navigation-cancel",
				searchTerms: ["navigation-cancel"]
			}, {
				title: "ti ti-navigation-check",
				searchTerms: ["navigation-check"]
			}, {
				title: "ti ti-navigation-code",
				searchTerms: ["navigation-code"]
			}, {
				title: "ti ti-navigation-cog",
				searchTerms: ["navigation-cog"]
			}, {
				title: "ti ti-navigation-discount",
				searchTerms: ["navigation-discount"]
			}, {
				title: "ti ti-navigation-dollar",
				searchTerms: ["navigation-dollar"]
			}, {
				title: "ti ti-navigation-down",
				searchTerms: ["navigation-down"]
			}, {
				title: "ti ti-navigation-east",
				searchTerms: ["navigation-east"]
			}, {
				title: "ti ti-navigation-exclamation",
				searchTerms: ["navigation-exclamation"]
			}, {
				title: "ti ti-navigation-filled",
				searchTerms: ["navigation-filled"]
			}, {
				title: "ti ti-navigation-heart",
				searchTerms: ["navigation-heart"]
			}, {
				title: "ti ti-navigation-minus",
				searchTerms: ["navigation-minus"]
			}, {
				title: "ti ti-navigation-north",
				searchTerms: ["navigation-north"]
			}, {
				title: "ti ti-navigation-off",
				searchTerms: ["navigation-off"]
			}, {
				title: "ti ti-navigation-pause",
				searchTerms: ["navigation-pause"]
			}, {
				title: "ti ti-navigation-pin",
				searchTerms: ["navigation-pin"]
			}, {
				title: "ti ti-navigation-plus",
				searchTerms: ["navigation-plus"]
			}, {
				title: "ti ti-navigation-question",
				searchTerms: ["navigation-question"]
			}, {
				title: "ti ti-navigation-search",
				searchTerms: ["navigation-search"]
			}, {
				title: "ti ti-navigation-share",
				searchTerms: ["navigation-share"]
			}, {
				title: "ti ti-navigation-south",
				searchTerms: ["navigation-south"]
			}, {
				title: "ti ti-navigation-star",
				searchTerms: ["navigation-star"]
			}, {
				title: "ti ti-navigation-top",
				searchTerms: ["navigation-top"]
			}, {
				title: "ti ti-navigation-up",
				searchTerms: ["navigation-up"]
			}, {
				title: "ti ti-navigation-west",
				searchTerms: ["navigation-west"]
			}, {
				title: "ti ti-navigation-x",
				searchTerms: ["navigation-x"]
			}, {
				title: "ti ti-needle",
				searchTerms: ["needle"]
			}, {
				title: "ti ti-needle-thread",
				searchTerms: ["needle-thread"]
			}, {
				title: "ti ti-network",
				searchTerms: ["network"]
			}, {
				title: "ti ti-network-off",
				searchTerms: ["network-off"]
			}, {
				title: "ti ti-new-section",
				searchTerms: ["new-section"]
			}, {
				title: "ti ti-news",
				searchTerms: ["news"]
			}, {
				title: "ti ti-news-off",
				searchTerms: ["news-off"]
			}, {
				title: "ti ti-nfc",
				searchTerms: ["nfc"]
			}, {
				title: "ti ti-nfc-off",
				searchTerms: ["nfc-off"]
			}, {
				title: "ti ti-no-copyright",
				searchTerms: ["no-copyright"]
			}, {
				title: "ti ti-no-creative-commons",
				searchTerms: ["no-creative-commons"]
			}, {
				title: "ti ti-no-derivatives",
				searchTerms: ["no-derivatives"]
			}, {
				title: "ti ti-north-star",
				searchTerms: ["north-star"]
			}, {
				title: "ti ti-note",
				searchTerms: ["note"]
			}, {
				title: "ti ti-note-off",
				searchTerms: ["note-off"]
			}, {
				title: "ti ti-notebook",
				searchTerms: ["notebook"]
			}, {
				title: "ti ti-notebook-off",
				searchTerms: ["notebook-off"]
			}, {
				title: "ti ti-notes",
				searchTerms: ["notes"]
			}, {
				title: "ti ti-notes-off",
				searchTerms: ["notes-off"]
			}, {
				title: "ti ti-notification",
				searchTerms: ["notification"]
			}, {
				title: "ti ti-notification-off",
				searchTerms: ["notification-off"]
			}, {
				title: "ti ti-number",
				searchTerms: ["number"]
			}, {
				title: "ti ti-number-0",
				searchTerms: ["number-0"]
			}, {
				title: "ti ti-number-0-small",
				searchTerms: ["number-0-small"]
			}, {
				title: "ti ti-number-1",
				searchTerms: ["number-1"]
			}, {
				title: "ti ti-number-1-small",
				searchTerms: ["number-1-small"]
			}, {
				title: "ti ti-number-10-small",
				searchTerms: ["number-10-small"]
			}, {
				title: "ti ti-number-11-small",
				searchTerms: ["number-11-small"]
			}, {
				title: "ti ti-number-12-small",
				searchTerms: ["number-12-small"]
			}, {
				title: "ti ti-number-123",
				searchTerms: ["number-123"]
			}, {
				title: "ti ti-number-13-small",
				searchTerms: ["number-13-small"]
			}, {
				title: "ti ti-number-14-small",
				searchTerms: ["number-14-small"]
			}, {
				title: "ti ti-number-15-small",
				searchTerms: ["number-15-small"]
			}, {
				title: "ti ti-number-16-small",
				searchTerms: ["number-16-small"]
			}, {
				title: "ti ti-number-17-small",
				searchTerms: ["number-17-small"]
			}, {
				title: "ti ti-number-18-small",
				searchTerms: ["number-18-small"]
			}, {
				title: "ti ti-number-19-small",
				searchTerms: ["number-19-small"]
			}, {
				title: "ti ti-number-2",
				searchTerms: ["number-2"]
			}, {
				title: "ti ti-number-2-small",
				searchTerms: ["number-2-small"]
			}, {
				title: "ti ti-number-20-small",
				searchTerms: ["number-20-small"]
			}, {
				title: "ti ti-number-21-small",
				searchTerms: ["number-21-small"]
			}, {
				title: "ti ti-number-22-small",
				searchTerms: ["number-22-small"]
			}, {
				title: "ti ti-number-23-small",
				searchTerms: ["number-23-small"]
			}, {
				title: "ti ti-number-24-small",
				searchTerms: ["number-24-small"]
			}, {
				title: "ti ti-number-25-small",
				searchTerms: ["number-25-small"]
			}, {
				title: "ti ti-number-26-small",
				searchTerms: ["number-26-small"]
			}, {
				title: "ti ti-number-27-small",
				searchTerms: ["number-27-small"]
			}, {
				title: "ti ti-number-28-small",
				searchTerms: ["number-28-small"]
			}, {
				title: "ti ti-number-29-small",
				searchTerms: ["number-29-small"]
			}, {
				title: "ti ti-number-3",
				searchTerms: ["number-3"]
			}, {
				title: "ti ti-number-3-small",
				searchTerms: ["number-3-small"]
			}, {
				title: "ti ti-number-4",
				searchTerms: ["number-4"]
			}, {
				title: "ti ti-number-4-small",
				searchTerms: ["number-4-small"]
			}, {
				title: "ti ti-number-5",
				searchTerms: ["number-5"]
			}, {
				title: "ti ti-number-5-small",
				searchTerms: ["number-5-small"]
			}, {
				title: "ti ti-number-6",
				searchTerms: ["number-6"]
			}, {
				title: "ti ti-number-6-small",
				searchTerms: ["number-6-small"]
			}, {
				title: "ti ti-number-7",
				searchTerms: ["number-7"]
			}, {
				title: "ti ti-number-7-small",
				searchTerms: ["number-7-small"]
			}, {
				title: "ti ti-number-8",
				searchTerms: ["number-8"]
			}, {
				title: "ti ti-number-8-small",
				searchTerms: ["number-8-small"]
			}, {
				title: "ti ti-number-9",
				searchTerms: ["number-9"]
			}, {
				title: "ti ti-number-9-small",
				searchTerms: ["number-9-small"]
			}, {
				title: "ti ti-numbers",
				searchTerms: ["numbers"]
			}, {
				title: "ti ti-nurse",
				searchTerms: ["nurse"]
			}, {
				title: "ti ti-nut",
				searchTerms: ["nut"]
			}, {
				title: "ti ti-octagon",
				searchTerms: ["octagon"]
			}, {
				title: "ti ti-octagon-filled",
				searchTerms: ["octagon-filled"]
			}, {
				title: "ti ti-octagon-minus",
				searchTerms: ["octagon-minus"]
			}, {
				title: "ti ti-octagon-minus-2",
				searchTerms: ["octagon-minus-2"]
			}, {
				title: "ti ti-octagon-off",
				searchTerms: ["octagon-off"]
			}, {
				title: "ti ti-octagon-plus",
				searchTerms: ["octagon-plus"]
			}, {
				title: "ti ti-octagon-plus-2",
				searchTerms: ["octagon-plus-2"]
			}, {
				title: "ti ti-octahedron",
				searchTerms: ["octahedron"]
			}, {
				title: "ti ti-octahedron-off",
				searchTerms: ["octahedron-off"]
			}, {
				title: "ti ti-octahedron-plus",
				searchTerms: ["octahedron-plus"]
			}, {
				title: "ti ti-old",
				searchTerms: ["old"]
			}, {
				title: "ti ti-olympics",
				searchTerms: ["olympics"]
			}, {
				title: "ti ti-olympics-off",
				searchTerms: ["olympics-off"]
			}, {
				title: "ti ti-om",
				searchTerms: ["om"]
			}, {
				title: "ti ti-omega",
				searchTerms: ["omega"]
			}, {
				title: "ti ti-outbound",
				searchTerms: ["outbound"]
			}, {
				title: "ti ti-outlet",
				searchTerms: ["outlet"]
			}, {
				title: "ti ti-oval",
				searchTerms: ["oval"]
			}, {
				title: "ti ti-oval-filled",
				searchTerms: ["oval-filled"]
			}, {
				title: "ti ti-oval-vertical",
				searchTerms: ["oval-vertical"]
			}, {
				title: "ti ti-oval-vertical-filled",
				searchTerms: ["oval-vertical-filled"]
			}, {
				title: "ti ti-overline",
				searchTerms: ["overline"]
			}, {
				title: "ti ti-package",
				searchTerms: ["package"]
			}, {
				title: "ti ti-package-export",
				searchTerms: ["package-export"]
			}, {
				title: "ti ti-package-import",
				searchTerms: ["package-import"]
			}, {
				title: "ti ti-package-off",
				searchTerms: ["package-off"]
			}, {
				title: "ti ti-packages",
				searchTerms: ["packages"]
			}, {
				title: "ti ti-pacman",
				searchTerms: ["pacman"]
			}, {
				title: "ti ti-page-break",
				searchTerms: ["page-break"]
			}, {
				title: "ti ti-paint",
				searchTerms: ["paint"]
			}, {
				title: "ti ti-paint-filled",
				searchTerms: ["paint-filled"]
			}, {
				title: "ti ti-paint-off",
				searchTerms: ["paint-off"]
			}, {
				title: "ti ti-palette",
				searchTerms: ["palette"]
			}, {
				title: "ti ti-palette-off",
				searchTerms: ["palette-off"]
			}, {
				title: "ti ti-panorama-horizontal",
				searchTerms: ["panorama-horizontal"]
			}, {
				title: "ti ti-panorama-horizontal-filled",
				searchTerms: ["panorama-horizontal-filled"]
			}, {
				title: "ti ti-panorama-horizontal-off",
				searchTerms: ["panorama-horizontal-off"]
			}, {
				title: "ti ti-panorama-vertical",
				searchTerms: ["panorama-vertical"]
			}, {
				title: "ti ti-panorama-vertical-filled",
				searchTerms: ["panorama-vertical-filled"]
			}, {
				title: "ti ti-panorama-vertical-off",
				searchTerms: ["panorama-vertical-off"]
			}, {
				title: "ti ti-paper-bag",
				searchTerms: ["paper-bag"]
			}, {
				title: "ti ti-paper-bag-off",
				searchTerms: ["paper-bag-off"]
			}, {
				title: "ti ti-paperclip",
				searchTerms: ["paperclip"]
			}, {
				title: "ti ti-parachute",
				searchTerms: ["parachute"]
			}, {
				title: "ti ti-parachute-off",
				searchTerms: ["parachute-off"]
			}, {
				title: "ti ti-parentheses",
				searchTerms: ["parentheses"]
			}, {
				title: "ti ti-parentheses-off",
				searchTerms: ["parentheses-off"]
			}, {
				title: "ti ti-parking",
				searchTerms: ["parking"]
			}, {
				title: "ti ti-parking-circle",
				searchTerms: ["parking-circle"]
			}, {
				title: "ti ti-parking-circle-filled",
				searchTerms: ["parking-circle-filled"]
			}, {
				title: "ti ti-parking-off",
				searchTerms: ["parking-off"]
			}, {
				title: "ti ti-password",
				searchTerms: ["password"]
			}, {
				title: "ti ti-password-fingerprint",
				searchTerms: ["password-fingerprint"]
			}, {
				title: "ti ti-password-mobile-phone",
				searchTerms: ["password-mobile-phone"]
			}, {
				title: "ti ti-password-user",
				searchTerms: ["password-user"]
			}, {
				title: "ti ti-paw",
				searchTerms: ["paw"]
			}, {
				title: "ti ti-paw-filled",
				searchTerms: ["paw-filled"]
			}, {
				title: "ti ti-paw-off",
				searchTerms: ["paw-off"]
			}, {
				title: "ti ti-paywall",
				searchTerms: ["paywall"]
			}, {
				title: "ti ti-pdf",
				searchTerms: ["pdf"]
			}, {
				title: "ti ti-peace",
				searchTerms: ["peace"]
			}, {
				title: "ti ti-pencil",
				searchTerms: ["pencil"]
			}, {
				title: "ti ti-pencil-bolt",
				searchTerms: ["pencil-bolt"]
			}, {
				title: "ti ti-pencil-cancel",
				searchTerms: ["pencil-cancel"]
			}, {
				title: "ti ti-pencil-check",
				searchTerms: ["pencil-check"]
			}, {
				title: "ti ti-pencil-code",
				searchTerms: ["pencil-code"]
			}, {
				title: "ti ti-pencil-cog",
				searchTerms: ["pencil-cog"]
			}, {
				title: "ti ti-pencil-discount",
				searchTerms: ["pencil-discount"]
			}, {
				title: "ti ti-pencil-dollar",
				searchTerms: ["pencil-dollar"]
			}, {
				title: "ti ti-pencil-down",
				searchTerms: ["pencil-down"]
			}, {
				title: "ti ti-pencil-exclamation",
				searchTerms: ["pencil-exclamation"]
			}, {
				title: "ti ti-pencil-heart",
				searchTerms: ["pencil-heart"]
			}, {
				title: "ti ti-pencil-minus",
				searchTerms: ["pencil-minus"]
			}, {
				title: "ti ti-pencil-off",
				searchTerms: ["pencil-off"]
			}, {
				title: "ti ti-pencil-pause",
				searchTerms: ["pencil-pause"]
			}, {
				title: "ti ti-pencil-pin",
				searchTerms: ["pencil-pin"]
			}, {
				title: "ti ti-pencil-plus",
				searchTerms: ["pencil-plus"]
			}, {
				title: "ti ti-pencil-question",
				searchTerms: ["pencil-question"]
			}, {
				title: "ti ti-pencil-search",
				searchTerms: ["pencil-search"]
			}, {
				title: "ti ti-pencil-share",
				searchTerms: ["pencil-share"]
			}, {
				title: "ti ti-pencil-star",
				searchTerms: ["pencil-star"]
			}, {
				title: "ti ti-pencil-up",
				searchTerms: ["pencil-up"]
			}, {
				title: "ti ti-pencil-x",
				searchTerms: ["pencil-x"]
			}, {
				title: "ti ti-pennant",
				searchTerms: ["pennant"]
			}, {
				title: "ti ti-pennant-2",
				searchTerms: ["pennant-2"]
			}, {
				title: "ti ti-pennant-2-filled",
				searchTerms: ["pennant-2-filled"]
			}, {
				title: "ti ti-pennant-filled",
				searchTerms: ["pennant-filled"]
			}, {
				title: "ti ti-pennant-off",
				searchTerms: ["pennant-off"]
			}, {
				title: "ti ti-pentagon",
				searchTerms: ["pentagon"]
			}, {
				title: "ti ti-pentagon-filled",
				searchTerms: ["pentagon-filled"]
			}, {
				title: "ti ti-pentagon-minus",
				searchTerms: ["pentagon-minus"]
			}, {
				title: "ti ti-pentagon-number-0",
				searchTerms: ["pentagon-number-0"]
			}, {
				title: "ti ti-pentagon-number-1",
				searchTerms: ["pentagon-number-1"]
			}, {
				title: "ti ti-pentagon-number-2",
				searchTerms: ["pentagon-number-2"]
			}, {
				title: "ti ti-pentagon-number-3",
				searchTerms: ["pentagon-number-3"]
			}, {
				title: "ti ti-pentagon-number-4",
				searchTerms: ["pentagon-number-4"]
			}, {
				title: "ti ti-pentagon-number-5",
				searchTerms: ["pentagon-number-5"]
			}, {
				title: "ti ti-pentagon-number-6",
				searchTerms: ["pentagon-number-6"]
			}, {
				title: "ti ti-pentagon-number-7",
				searchTerms: ["pentagon-number-7"]
			}, {
				title: "ti ti-pentagon-number-8",
				searchTerms: ["pentagon-number-8"]
			}, {
				title: "ti ti-pentagon-number-9",
				searchTerms: ["pentagon-number-9"]
			}, {
				title: "ti ti-pentagon-off",
				searchTerms: ["pentagon-off"]
			}, {
				title: "ti ti-pentagon-plus",
				searchTerms: ["pentagon-plus"]
			}, {
				title: "ti ti-pentagon-x",
				searchTerms: ["pentagon-x"]
			}, {
				title: "ti ti-pentagram",
				searchTerms: ["pentagram"]
			}, {
				title: "ti ti-pepper",
				searchTerms: ["pepper"]
			}, {
				title: "ti ti-pepper-off",
				searchTerms: ["pepper-off"]
			}, {
				title: "ti ti-percentage",
				searchTerms: ["percentage"]
			}, {
				title: "ti ti-percentage-0",
				searchTerms: ["percentage-0"]
			}, {
				title: "ti ti-percentage-10",
				searchTerms: ["percentage-10"]
			}, {
				title: "ti ti-percentage-100",
				searchTerms: ["percentage-100"]
			}, {
				title: "ti ti-percentage-20",
				searchTerms: ["percentage-20"]
			}, {
				title: "ti ti-percentage-25",
				searchTerms: ["percentage-25"]
			}, {
				title: "ti ti-percentage-30",
				searchTerms: ["percentage-30"]
			}, {
				title: "ti ti-percentage-33",
				searchTerms: ["percentage-33"]
			}, {
				title: "ti ti-percentage-40",
				searchTerms: ["percentage-40"]
			}, {
				title: "ti ti-percentage-50",
				searchTerms: ["percentage-50"]
			}, {
				title: "ti ti-percentage-60",
				searchTerms: ["percentage-60"]
			}, {
				title: "ti ti-percentage-66",
				searchTerms: ["percentage-66"]
			}, {
				title: "ti ti-percentage-70",
				searchTerms: ["percentage-70"]
			}, {
				title: "ti ti-percentage-75",
				searchTerms: ["percentage-75"]
			}, {
				title: "ti ti-percentage-80",
				searchTerms: ["percentage-80"]
			}, {
				title: "ti ti-percentage-90",
				searchTerms: ["percentage-90"]
			}, {
				title: "ti ti-perfume",
				searchTerms: ["perfume"]
			}, {
				title: "ti ti-perspective",
				searchTerms: ["perspective"]
			}, {
				title: "ti ti-perspective-off",
				searchTerms: ["perspective-off"]
			}, {
				title: "ti ti-phone",
				searchTerms: ["phone"]
			}, {
				title: "ti ti-phone-call",
				searchTerms: ["phone-call"]
			}, {
				title: "ti ti-phone-calling",
				searchTerms: ["phone-calling"]
			}, {
				title: "ti ti-phone-check",
				searchTerms: ["phone-check"]
			}, {
				title: "ti ti-phone-filled",
				searchTerms: ["phone-filled"]
			}, {
				title: "ti ti-phone-incoming",
				searchTerms: ["phone-incoming"]
			}, {
				title: "ti ti-phone-off",
				searchTerms: ["phone-off"]
			}, {
				title: "ti ti-phone-outgoing",
				searchTerms: ["phone-outgoing"]
			}, {
				title: "ti ti-phone-pause",
				searchTerms: ["phone-pause"]
			}, {
				title: "ti ti-phone-plus",
				searchTerms: ["phone-plus"]
			}, {
				title: "ti ti-phone-x",
				searchTerms: ["phone-x"]
			}, {
				title: "ti ti-photo",
				searchTerms: ["photo"]
			}, {
				title: "ti ti-photo-ai",
				searchTerms: ["photo-ai"]
			}, {
				title: "ti ti-photo-bolt",
				searchTerms: ["photo-bolt"]
			}, {
				title: "ti ti-photo-cancel",
				searchTerms: ["photo-cancel"]
			}, {
				title: "ti ti-photo-check",
				searchTerms: ["photo-check"]
			}, {
				title: "ti ti-photo-circle",
				searchTerms: ["photo-circle"]
			}, {
				title: "ti ti-photo-circle-minus",
				searchTerms: ["photo-circle-minus"]
			}, {
				title: "ti ti-photo-circle-plus",
				searchTerms: ["photo-circle-plus"]
			}, {
				title: "ti ti-photo-code",
				searchTerms: ["photo-code"]
			}, {
				title: "ti ti-photo-cog",
				searchTerms: ["photo-cog"]
			}, {
				title: "ti ti-photo-dollar",
				searchTerms: ["photo-dollar"]
			}, {
				title: "ti ti-photo-down",
				searchTerms: ["photo-down"]
			}, {
				title: "ti ti-photo-edit",
				searchTerms: ["photo-edit"]
			}, {
				title: "ti ti-photo-exclamation",
				searchTerms: ["photo-exclamation"]
			}, {
				title: "ti ti-photo-filled",
				searchTerms: ["photo-filled"]
			}, {
				title: "ti ti-photo-heart",
				searchTerms: ["photo-heart"]
			}, {
				title: "ti ti-photo-hexagon",
				searchTerms: ["photo-hexagon"]
			}, {
				title: "ti ti-photo-minus",
				searchTerms: ["photo-minus"]
			}, {
				title: "ti ti-photo-off",
				searchTerms: ["photo-off"]
			}, {
				title: "ti ti-photo-pause",
				searchTerms: ["photo-pause"]
			}, {
				title: "ti ti-photo-pentagon",
				searchTerms: ["photo-pentagon"]
			}, {
				title: "ti ti-photo-pin",
				searchTerms: ["photo-pin"]
			}, {
				title: "ti ti-photo-plus",
				searchTerms: ["photo-plus"]
			}, {
				title: "ti ti-photo-question",
				searchTerms: ["photo-question"]
			}, {
				title: "ti ti-photo-scan",
				searchTerms: ["photo-scan"]
			}, {
				title: "ti ti-photo-search",
				searchTerms: ["photo-search"]
			}, {
				title: "ti ti-photo-sensor",
				searchTerms: ["photo-sensor"]
			}, {
				title: "ti ti-photo-sensor-2",
				searchTerms: ["photo-sensor-2"]
			}, {
				title: "ti ti-photo-sensor-3",
				searchTerms: ["photo-sensor-3"]
			}, {
				title: "ti ti-photo-share",
				searchTerms: ["photo-share"]
			}, {
				title: "ti ti-photo-shield",
				searchTerms: ["photo-shield"]
			}, {
				title: "ti ti-photo-square-rounded",
				searchTerms: ["photo-square-rounded"]
			}, {
				title: "ti ti-photo-star",
				searchTerms: ["photo-star"]
			}, {
				title: "ti ti-photo-up",
				searchTerms: ["photo-up"]
			}, {
				title: "ti ti-photo-video",
				searchTerms: ["photo-video"]
			}, {
				title: "ti ti-photo-x",
				searchTerms: ["photo-x"]
			}, {
				title: "ti ti-physotherapist",
				searchTerms: ["physotherapist"]
			}, {
				title: "ti ti-piano",
				searchTerms: ["piano"]
			}, {
				title: "ti ti-pick",
				searchTerms: ["pick"]
			}, {
				title: "ti ti-picnic-table",
				searchTerms: ["picnic-table"]
			}, {
				title: "ti ti-picture-in-picture",
				searchTerms: ["picture-in-picture"]
			}, {
				title: "ti ti-picture-in-picture-filled",
				searchTerms: ["picture-in-picture-filled"]
			}, {
				title: "ti ti-picture-in-picture-off",
				searchTerms: ["picture-in-picture-off"]
			}, {
				title: "ti ti-picture-in-picture-on",
				searchTerms: ["picture-in-picture-on"]
			}, {
				title: "ti ti-picture-in-picture-top",
				searchTerms: ["picture-in-picture-top"]
			}, {
				title: "ti ti-picture-in-picture-top-filled",
				searchTerms: ["picture-in-picture-top-filled"]
			}, {
				title: "ti ti-pig",
				searchTerms: ["pig"]
			}, {
				title: "ti ti-pig-money",
				searchTerms: ["pig-money"]
			}, {
				title: "ti ti-pig-off",
				searchTerms: ["pig-off"]
			}, {
				title: "ti ti-pilcrow",
				searchTerms: ["pilcrow"]
			}, {
				title: "ti ti-pilcrow-left",
				searchTerms: ["pilcrow-left"]
			}, {
				title: "ti ti-pilcrow-right",
				searchTerms: ["pilcrow-right"]
			}, {
				title: "ti ti-pill",
				searchTerms: ["pill"]
			}, {
				title: "ti ti-pill-off",
				searchTerms: ["pill-off"]
			}, {
				title: "ti ti-pills",
				searchTerms: ["pills"]
			}, {
				title: "ti ti-pin",
				searchTerms: ["pin"]
			}, {
				title: "ti ti-pin-end",
				searchTerms: ["pin-end"]
			}, {
				title: "ti ti-pin-filled",
				searchTerms: ["pin-filled"]
			}, {
				title: "ti ti-pin-invoke",
				searchTerms: ["pin-invoke"]
			}, {
				title: "ti ti-ping-pong",
				searchTerms: ["ping-pong"]
			}, {
				title: "ti ti-pinned",
				searchTerms: ["pinned"]
			}, {
				title: "ti ti-pinned-filled",
				searchTerms: ["pinned-filled"]
			}, {
				title: "ti ti-pinned-off",
				searchTerms: ["pinned-off"]
			}, {
				title: "ti ti-pizza",
				searchTerms: ["pizza"]
			}, {
				title: "ti ti-pizza-off",
				searchTerms: ["pizza-off"]
			}, {
				title: "ti ti-placeholder",
				searchTerms: ["placeholder"]
			}, {
				title: "ti ti-plane",
				searchTerms: ["plane"]
			}, {
				title: "ti ti-plane-arrival",
				searchTerms: ["plane-arrival"]
			}, {
				title: "ti ti-plane-departure",
				searchTerms: ["plane-departure"]
			}, {
				title: "ti ti-plane-inflight",
				searchTerms: ["plane-inflight"]
			}, {
				title: "ti ti-plane-off",
				searchTerms: ["plane-off"]
			}, {
				title: "ti ti-plane-tilt",
				searchTerms: ["plane-tilt"]
			}, {
				title: "ti ti-planet",
				searchTerms: ["planet"]
			}, {
				title: "ti ti-planet-off",
				searchTerms: ["planet-off"]
			}, {
				title: "ti ti-plant",
				searchTerms: ["plant"]
			}, {
				title: "ti ti-plant-2",
				searchTerms: ["plant-2"]
			}, {
				title: "ti ti-plant-2-off",
				searchTerms: ["plant-2-off"]
			}, {
				title: "ti ti-plant-off",
				searchTerms: ["plant-off"]
			}, {
				title: "ti ti-play-basketball",
				searchTerms: ["play-basketball"]
			}, {
				title: "ti ti-play-card",
				searchTerms: ["play-card"]
			}, {
				title: "ti ti-play-card-off",
				searchTerms: ["play-card-off"]
			}, {
				title: "ti ti-play-football",
				searchTerms: ["play-football"]
			}, {
				title: "ti ti-play-handball",
				searchTerms: ["play-handball"]
			}, {
				title: "ti ti-play-volleyball",
				searchTerms: ["play-volleyball"]
			}, {
				title: "ti ti-player-eject",
				searchTerms: ["player-eject"]
			}, {
				title: "ti ti-player-eject-filled",
				searchTerms: ["player-eject-filled"]
			}, {
				title: "ti ti-player-pause",
				searchTerms: ["player-pause"]
			}, {
				title: "ti ti-player-pause-filled",
				searchTerms: ["player-pause-filled"]
			}, {
				title: "ti ti-player-play",
				searchTerms: ["player-play"]
			}, {
				title: "ti ti-player-play-filled",
				searchTerms: ["player-play-filled"]
			}, {
				title: "ti ti-player-record",
				searchTerms: ["player-record"]
			}, {
				title: "ti ti-player-record-filled",
				searchTerms: ["player-record-filled"]
			}, {
				title: "ti ti-player-skip-back",
				searchTerms: ["player-skip-back"]
			}, {
				title: "ti ti-player-skip-back-filled",
				searchTerms: ["player-skip-back-filled"]
			}, {
				title: "ti ti-player-skip-forward",
				searchTerms: ["player-skip-forward"]
			}, {
				title: "ti ti-player-skip-forward-filled",
				searchTerms: ["player-skip-forward-filled"]
			}, {
				title: "ti ti-player-stop",
				searchTerms: ["player-stop"]
			}, {
				title: "ti ti-player-stop-filled",
				searchTerms: ["player-stop-filled"]
			}, {
				title: "ti ti-player-track-next",
				searchTerms: ["player-track-next"]
			}, {
				title: "ti ti-player-track-next-filled",
				searchTerms: ["player-track-next-filled"]
			}, {
				title: "ti ti-player-track-prev",
				searchTerms: ["player-track-prev"]
			}, {
				title: "ti ti-player-track-prev-filled",
				searchTerms: ["player-track-prev-filled"]
			}, {
				title: "ti ti-playlist",
				searchTerms: ["playlist"]
			}, {
				title: "ti ti-playlist-add",
				searchTerms: ["playlist-add"]
			}, {
				title: "ti ti-playlist-off",
				searchTerms: ["playlist-off"]
			}, {
				title: "ti ti-playlist-x",
				searchTerms: ["playlist-x"]
			}, {
				title: "ti ti-playstation-circle",
				searchTerms: ["playstation-circle"]
			}, {
				title: "ti ti-playstation-square",
				searchTerms: ["playstation-square"]
			}, {
				title: "ti ti-playstation-triangle",
				searchTerms: ["playstation-triangle"]
			}, {
				title: "ti ti-playstation-x",
				searchTerms: ["playstation-x"]
			}, {
				title: "ti ti-plug",
				searchTerms: ["plug"]
			}, {
				title: "ti ti-plug-connected",
				searchTerms: ["plug-connected"]
			}, {
				title: "ti ti-plug-connected-x",
				searchTerms: ["plug-connected-x"]
			}, {
				title: "ti ti-plug-off",
				searchTerms: ["plug-off"]
			}, {
				title: "ti ti-plug-x",
				searchTerms: ["plug-x"]
			}, {
				title: "ti ti-plus",
				searchTerms: ["plus"]
			}, {
				title: "ti ti-plus-equal",
				searchTerms: ["plus-equal"]
			}, {
				title: "ti ti-plus-minus",
				searchTerms: ["plus-minus"]
			}, {
				title: "ti ti-png",
				searchTerms: ["png"]
			}, {
				title: "ti ti-podium",
				searchTerms: ["podium"]
			}, {
				title: "ti ti-podium-off",
				searchTerms: ["podium-off"]
			}, {
				title: "ti ti-point",
				searchTerms: ["point"]
			}, {
				title: "ti ti-point-filled",
				searchTerms: ["point-filled"]
			}, {
				title: "ti ti-point-off",
				searchTerms: ["point-off"]
			}, {
				title: "ti ti-pointer",
				searchTerms: ["pointer"]
			}, {
				title: "ti ti-pointer-bolt",
				searchTerms: ["pointer-bolt"]
			}, {
				title: "ti ti-pointer-cancel",
				searchTerms: ["pointer-cancel"]
			}, {
				title: "ti ti-pointer-check",
				searchTerms: ["pointer-check"]
			}, {
				title: "ti ti-pointer-code",
				searchTerms: ["pointer-code"]
			}, {
				title: "ti ti-pointer-cog",
				searchTerms: ["pointer-cog"]
			}, {
				title: "ti ti-pointer-dollar",
				searchTerms: ["pointer-dollar"]
			}, {
				title: "ti ti-pointer-down",
				searchTerms: ["pointer-down"]
			}, {
				title: "ti ti-pointer-exclamation",
				searchTerms: ["pointer-exclamation"]
			}, {
				title: "ti ti-pointer-filled",
				searchTerms: ["pointer-filled"]
			}, {
				title: "ti ti-pointer-heart",
				searchTerms: ["pointer-heart"]
			}, {
				title: "ti ti-pointer-minus",
				searchTerms: ["pointer-minus"]
			}, {
				title: "ti ti-pointer-off",
				searchTerms: ["pointer-off"]
			}, {
				title: "ti ti-pointer-pause",
				searchTerms: ["pointer-pause"]
			}, {
				title: "ti ti-pointer-pin",
				searchTerms: ["pointer-pin"]
			}, {
				title: "ti ti-pointer-plus",
				searchTerms: ["pointer-plus"]
			}, {
				title: "ti ti-pointer-question",
				searchTerms: ["pointer-question"]
			}, {
				title: "ti ti-pointer-search",
				searchTerms: ["pointer-search"]
			}, {
				title: "ti ti-pointer-share",
				searchTerms: ["pointer-share"]
			}, {
				title: "ti ti-pointer-star",
				searchTerms: ["pointer-star"]
			}, {
				title: "ti ti-pointer-up",
				searchTerms: ["pointer-up"]
			}, {
				title: "ti ti-pointer-x",
				searchTerms: ["pointer-x"]
			}, {
				title: "ti ti-pokeball",
				searchTerms: ["pokeball"]
			}, {
				title: "ti ti-pokeball-off",
				searchTerms: ["pokeball-off"]
			}, {
				title: "ti ti-poker-chip",
				searchTerms: ["poker-chip"]
			}, {
				title: "ti ti-polaroid",
				searchTerms: ["polaroid"]
			}, {
				title: "ti ti-polaroid-filled",
				searchTerms: ["polaroid-filled"]
			}, {
				title: "ti ti-polygon",
				searchTerms: ["polygon"]
			}, {
				title: "ti ti-polygon-off",
				searchTerms: ["polygon-off"]
			}, {
				title: "ti ti-poo",
				searchTerms: ["poo"]
			}, {
				title: "ti ti-poo-filled",
				searchTerms: ["poo-filled"]
			}, {
				title: "ti ti-pool",
				searchTerms: ["pool"]
			}, {
				title: "ti ti-pool-off",
				searchTerms: ["pool-off"]
			}, {
				title: "ti ti-power",
				searchTerms: ["power"]
			}, {
				title: "ti ti-pray",
				searchTerms: ["pray"]
			}, {
				title: "ti ti-premium-rights",
				searchTerms: ["premium-rights"]
			}, {
				title: "ti ti-prescription",
				searchTerms: ["prescription"]
			}, {
				title: "ti ti-presentation",
				searchTerms: ["presentation"]
			}, {
				title: "ti ti-presentation-analytics",
				searchTerms: ["presentation-analytics"]
			}, {
				title: "ti ti-presentation-off",
				searchTerms: ["presentation-off"]
			}, {
				title: "ti ti-printer",
				searchTerms: ["printer"]
			}, {
				title: "ti ti-printer-off",
				searchTerms: ["printer-off"]
			}, {
				title: "ti ti-prism",
				searchTerms: ["prism"]
			}, {
				title: "ti ti-prism-light",
				searchTerms: ["prism-light"]
			}, {
				title: "ti ti-prism-off",
				searchTerms: ["prism-off"]
			}, {
				title: "ti ti-prism-plus",
				searchTerms: ["prism-plus"]
			}, {
				title: "ti ti-prison",
				searchTerms: ["prison"]
			}, {
				title: "ti ti-progress",
				searchTerms: ["progress"]
			}, {
				title: "ti ti-progress-alert",
				searchTerms: ["progress-alert"]
			}, {
				title: "ti ti-progress-bolt",
				searchTerms: ["progress-bolt"]
			}, {
				title: "ti ti-progress-check",
				searchTerms: ["progress-check"]
			}, {
				title: "ti ti-progress-down",
				searchTerms: ["progress-down"]
			}, {
				title: "ti ti-progress-help",
				searchTerms: ["progress-help"]
			}, {
				title: "ti ti-progress-x",
				searchTerms: ["progress-x"]
			}, {
				title: "ti ti-prompt",
				searchTerms: ["prompt"]
			}, {
				title: "ti ti-prong",
				searchTerms: ["prong"]
			}, {
				title: "ti ti-propeller",
				searchTerms: ["propeller"]
			}, {
				title: "ti ti-propeller-off",
				searchTerms: ["propeller-off"]
			}, {
				title: "ti ti-protocol",
				searchTerms: ["protocol"]
			}, {
				title: "ti ti-pumpkin-scary",
				searchTerms: ["pumpkin-scary"]
			}, {
				title: "ti ti-puzzle",
				searchTerms: ["puzzle"]
			}, {
				title: "ti ti-puzzle-2",
				searchTerms: ["puzzle-2"]
			}, {
				title: "ti ti-puzzle-filled",
				searchTerms: ["puzzle-filled"]
			}, {
				title: "ti ti-puzzle-off",
				searchTerms: ["puzzle-off"]
			}, {
				title: "ti ti-pyramid",
				searchTerms: ["pyramid"]
			}, {
				title: "ti ti-pyramid-off",
				searchTerms: ["pyramid-off"]
			}, {
				title: "ti ti-pyramid-plus",
				searchTerms: ["pyramid-plus"]
			}, {
				title: "ti ti-qrcode",
				searchTerms: ["qrcode"]
			}, {
				title: "ti ti-qrcode-off",
				searchTerms: ["qrcode-off"]
			}, {
				title: "ti ti-question-mark",
				searchTerms: ["question-mark"]
			}, {
				title: "ti ti-quote",
				searchTerms: ["quote"]
			}, {
				title: "ti ti-quote-off",
				searchTerms: ["quote-off"]
			}, {
				title: "ti ti-quotes",
				searchTerms: ["quotes"]
			}, {
				title: "ti ti-radar",
				searchTerms: ["radar"]
			}, {
				title: "ti ti-radar-2",
				searchTerms: ["radar-2"]
			}, {
				title: "ti ti-radar-filled",
				searchTerms: ["radar-filled"]
			}, {
				title: "ti ti-radar-off",
				searchTerms: ["radar-off"]
			}, {
				title: "ti ti-radio",
				searchTerms: ["radio"]
			}, {
				title: "ti ti-radio-off",
				searchTerms: ["radio-off"]
			}, {
				title: "ti ti-radioactive",
				searchTerms: ["radioactive"]
			}, {
				title: "ti ti-radioactive-filled",
				searchTerms: ["radioactive-filled"]
			}, {
				title: "ti ti-radioactive-off",
				searchTerms: ["radioactive-off"]
			}, {
				title: "ti ti-radius-bottom-left",
				searchTerms: ["radius-bottom-left"]
			}, {
				title: "ti ti-radius-bottom-right",
				searchTerms: ["radius-bottom-right"]
			}, {
				title: "ti ti-radius-top-left",
				searchTerms: ["radius-top-left"]
			}, {
				title: "ti ti-radius-top-right",
				searchTerms: ["radius-top-right"]
			}, {
				title: "ti ti-rainbow",
				searchTerms: ["rainbow"]
			}, {
				title: "ti ti-rainbow-off",
				searchTerms: ["rainbow-off"]
			}, {
				title: "ti ti-rating-12-plus",
				searchTerms: ["rating-12-plus"]
			}, {
				title: "ti ti-rating-14-plus",
				searchTerms: ["rating-14-plus"]
			}, {
				title: "ti ti-rating-16-plus",
				searchTerms: ["rating-16-plus"]
			}, {
				title: "ti ti-rating-18-plus",
				searchTerms: ["rating-18-plus"]
			}, {
				title: "ti ti-rating-21-plus",
				searchTerms: ["rating-21-plus"]
			}, {
				title: "ti ti-razor",
				searchTerms: ["razor"]
			}, {
				title: "ti ti-razor-electric",
				searchTerms: ["razor-electric"]
			}, {
				title: "ti ti-receipt",
				searchTerms: ["receipt"]
			}, {
				title: "ti ti-receipt-2",
				searchTerms: ["receipt-2"]
			}, {
				title: "ti ti-receipt-bitcoin",
				searchTerms: ["receipt-bitcoin"]
			}, {
				title: "ti ti-receipt-dollar",
				searchTerms: ["receipt-dollar"]
			}, {
				title: "ti ti-receipt-euro",
				searchTerms: ["receipt-euro"]
			}, {
				title: "ti ti-receipt-off",
				searchTerms: ["receipt-off"]
			}, {
				title: "ti ti-receipt-pound",
				searchTerms: ["receipt-pound"]
			}, {
				title: "ti ti-receipt-refund",
				searchTerms: ["receipt-refund"]
			}, {
				title: "ti ti-receipt-rupee",
				searchTerms: ["receipt-rupee"]
			}, {
				title: "ti ti-receipt-tax",
				searchTerms: ["receipt-tax"]
			}, {
				title: "ti ti-receipt-yen",
				searchTerms: ["receipt-yen"]
			}, {
				title: "ti ti-receipt-yuan",
				searchTerms: ["receipt-yuan"]
			}, {
				title: "ti ti-recharging",
				searchTerms: ["recharging"]
			}, {
				title: "ti ti-record-mail",
				searchTerms: ["record-mail"]
			}, {
				title: "ti ti-record-mail-off",
				searchTerms: ["record-mail-off"]
			}, {
				title: "ti ti-rectangle",
				searchTerms: ["rectangle"]
			}, {
				title: "ti ti-rectangle-filled",
				searchTerms: ["rectangle-filled"]
			}, {
				title: "ti ti-rectangle-rounded-bottom",
				searchTerms: ["rectangle-rounded-bottom"]
			}, {
				title: "ti ti-rectangle-rounded-top",
				searchTerms: ["rectangle-rounded-top"]
			}, {
				title: "ti ti-rectangle-vertical",
				searchTerms: ["rectangle-vertical"]
			}, {
				title: "ti ti-rectangle-vertical-filled",
				searchTerms: ["rectangle-vertical-filled"]
			}, {
				title: "ti ti-rectangular-prism",
				searchTerms: ["rectangular-prism"]
			}, {
				title: "ti ti-rectangular-prism-off",
				searchTerms: ["rectangular-prism-off"]
			}, {
				title: "ti ti-rectangular-prism-plus",
				searchTerms: ["rectangular-prism-plus"]
			}, {
				title: "ti ti-recycle",
				searchTerms: ["recycle"]
			}, {
				title: "ti ti-recycle-off",
				searchTerms: ["recycle-off"]
			}, {
				title: "ti ti-refresh",
				searchTerms: ["refresh"]
			}, {
				title: "ti ti-refresh-alert",
				searchTerms: ["refresh-alert"]
			}, {
				title: "ti ti-refresh-dot",
				searchTerms: ["refresh-dot"]
			}, {
				title: "ti ti-refresh-off",
				searchTerms: ["refresh-off"]
			}, {
				title: "ti ti-regex",
				searchTerms: ["regex"]
			}, {
				title: "ti ti-regex-off",
				searchTerms: ["regex-off"]
			}, {
				title: "ti ti-registered",
				searchTerms: ["registered"]
			}, {
				title: "ti ti-relation-many-to-many",
				searchTerms: ["relation-many-to-many"]
			}, {
				title: "ti ti-relation-many-to-many-filled",
				searchTerms: ["relation-many-to-many-filled"]
			}, {
				title: "ti ti-relation-one-to-many",
				searchTerms: ["relation-one-to-many"]
			}, {
				title: "ti ti-relation-one-to-many-filled",
				searchTerms: ["relation-one-to-many-filled"]
			}, {
				title: "ti ti-relation-one-to-one",
				searchTerms: ["relation-one-to-one"]
			}, {
				title: "ti ti-relation-one-to-one-filled",
				searchTerms: ["relation-one-to-one-filled"]
			}, {
				title: "ti ti-reload",
				searchTerms: ["reload"]
			}, {
				title: "ti ti-reorder",
				searchTerms: ["reorder"]
			}, {
				title: "ti ti-repeat",
				searchTerms: ["repeat"]
			}, {
				title: "ti ti-repeat-off",
				searchTerms: ["repeat-off"]
			}, {
				title: "ti ti-repeat-once",
				searchTerms: ["repeat-once"]
			}, {
				title: "ti ti-replace",
				searchTerms: ["replace"]
			}, {
				title: "ti ti-replace-filled",
				searchTerms: ["replace-filled"]
			}, {
				title: "ti ti-replace-off",
				searchTerms: ["replace-off"]
			}, {
				title: "ti ti-report",
				searchTerms: ["report"]
			}, {
				title: "ti ti-report-analytics",
				searchTerms: ["report-analytics"]
			}, {
				title: "ti ti-report-medical",
				searchTerms: ["report-medical"]
			}, {
				title: "ti ti-report-money",
				searchTerms: ["report-money"]
			}, {
				title: "ti ti-report-off",
				searchTerms: ["report-off"]
			}, {
				title: "ti ti-report-search",
				searchTerms: ["report-search"]
			}, {
				title: "ti ti-reserved-line",
				searchTerms: ["reserved-line"]
			}, {
				title: "ti ti-resize",
				searchTerms: ["resize"]
			}, {
				title: "ti ti-restore",
				searchTerms: ["restore"]
			}, {
				title: "ti ti-rewind-backward-10",
				searchTerms: ["rewind-backward-10"]
			}, {
				title: "ti ti-rewind-backward-15",
				searchTerms: ["rewind-backward-15"]
			}, {
				title: "ti ti-rewind-backward-20",
				searchTerms: ["rewind-backward-20"]
			}, {
				title: "ti ti-rewind-backward-30",
				searchTerms: ["rewind-backward-30"]
			}, {
				title: "ti ti-rewind-backward-40",
				searchTerms: ["rewind-backward-40"]
			}, {
				title: "ti ti-rewind-backward-5",
				searchTerms: ["rewind-backward-5"]
			}, {
				title: "ti ti-rewind-backward-50",
				searchTerms: ["rewind-backward-50"]
			}, {
				title: "ti ti-rewind-backward-60",
				searchTerms: ["rewind-backward-60"]
			}, {
				title: "ti ti-rewind-forward-10",
				searchTerms: ["rewind-forward-10"]
			}, {
				title: "ti ti-rewind-forward-15",
				searchTerms: ["rewind-forward-15"]
			}, {
				title: "ti ti-rewind-forward-20",
				searchTerms: ["rewind-forward-20"]
			}, {
				title: "ti ti-rewind-forward-30",
				searchTerms: ["rewind-forward-30"]
			}, {
				title: "ti ti-rewind-forward-40",
				searchTerms: ["rewind-forward-40"]
			}, {
				title: "ti ti-rewind-forward-5",
				searchTerms: ["rewind-forward-5"]
			}, {
				title: "ti ti-rewind-forward-50",
				searchTerms: ["rewind-forward-50"]
			}, {
				title: "ti ti-rewind-forward-60",
				searchTerms: ["rewind-forward-60"]
			}, {
				title: "ti ti-ribbon-health",
				searchTerms: ["ribbon-health"]
			}, {
				title: "ti ti-rings",
				searchTerms: ["rings"]
			}, {
				title: "ti ti-ripple",
				searchTerms: ["ripple"]
			}, {
				title: "ti ti-ripple-off",
				searchTerms: ["ripple-off"]
			}, {
				title: "ti ti-road",
				searchTerms: ["road"]
			}, {
				title: "ti ti-road-off",
				searchTerms: ["road-off"]
			}, {
				title: "ti ti-road-sign",
				searchTerms: ["road-sign"]
			}, {
				title: "ti ti-robot",
				searchTerms: ["robot"]
			}, {
				title: "ti ti-robot-face",
				searchTerms: ["robot-face"]
			}, {
				title: "ti ti-robot-off",
				searchTerms: ["robot-off"]
			}, {
				title: "ti ti-rocket",
				searchTerms: ["rocket"]
			}, {
				title: "ti ti-rocket-off",
				searchTerms: ["rocket-off"]
			}, {
				title: "ti ti-roller-skating",
				searchTerms: ["roller-skating"]
			}, {
				title: "ti ti-rollercoaster",
				searchTerms: ["rollercoaster"]
			}, {
				title: "ti ti-rollercoaster-off",
				searchTerms: ["rollercoaster-off"]
			}, {
				title: "ti ti-rosette",
				searchTerms: ["rosette"]
			}, {
				title: "ti ti-rosette-discount",
				searchTerms: ["rosette-discount"]
			}, {
				title: "ti ti-rosette-discount-check",
				searchTerms: ["rosette-discount-check"]
			}, {
				title: "ti ti-rosette-discount-check-filled",
				searchTerms: ["rosette-discount-check-filled"]
			}, {
				title: "ti ti-rosette-discount-off",
				searchTerms: ["rosette-discount-off"]
			}, {
				title: "ti ti-rosette-filled",
				searchTerms: ["rosette-filled"]
			}, {
				title: "ti ti-rosette-number-0",
				searchTerms: ["rosette-number-0"]
			}, {
				title: "ti ti-rosette-number-1",
				searchTerms: ["rosette-number-1"]
			}, {
				title: "ti ti-rosette-number-2",
				searchTerms: ["rosette-number-2"]
			}, {
				title: "ti ti-rosette-number-3",
				searchTerms: ["rosette-number-3"]
			}, {
				title: "ti ti-rosette-number-4",
				searchTerms: ["rosette-number-4"]
			}, {
				title: "ti ti-rosette-number-5",
				searchTerms: ["rosette-number-5"]
			}, {
				title: "ti ti-rosette-number-6",
				searchTerms: ["rosette-number-6"]
			}, {
				title: "ti ti-rosette-number-7",
				searchTerms: ["rosette-number-7"]
			}, {
				title: "ti ti-rosette-number-8",
				searchTerms: ["rosette-number-8"]
			}, {
				title: "ti ti-rosette-number-9",
				searchTerms: ["rosette-number-9"]
			}, {
				title: "ti ti-rotate",
				searchTerms: ["rotate"]
			}, {
				title: "ti ti-rotate-2",
				searchTerms: ["rotate-2"]
			}, {
				title: "ti ti-rotate-360",
				searchTerms: ["rotate-360"]
			}, {
				title: "ti ti-rotate-3d",
				searchTerms: ["rotate-3d"]
			}, {
				title: "ti ti-rotate-clockwise",
				searchTerms: ["rotate-clockwise"]
			}, {
				title: "ti ti-rotate-clockwise-2",
				searchTerms: ["rotate-clockwise-2"]
			}, {
				title: "ti ti-rotate-dot",
				searchTerms: ["rotate-dot"]
			}, {
				title: "ti ti-rotate-rectangle",
				searchTerms: ["rotate-rectangle"]
			}, {
				title: "ti ti-route",
				searchTerms: ["route"]
			}, {
				title: "ti ti-route-2",
				searchTerms: ["route-2"]
			}, {
				title: "ti ti-route-alt-left",
				searchTerms: ["route-alt-left"]
			}, {
				title: "ti ti-route-alt-right",
				searchTerms: ["route-alt-right"]
			}, {
				title: "ti ti-route-off",
				searchTerms: ["route-off"]
			}, {
				title: "ti ti-route-scan",
				searchTerms: ["route-scan"]
			}, {
				title: "ti ti-route-square",
				searchTerms: ["route-square"]
			}, {
				title: "ti ti-route-square-2",
				searchTerms: ["route-square-2"]
			}, {
				title: "ti ti-route-x",
				searchTerms: ["route-x"]
			}, {
				title: "ti ti-route-x-2",
				searchTerms: ["route-x-2"]
			}, {
				title: "ti ti-router",
				searchTerms: ["router"]
			}, {
				title: "ti ti-router-off",
				searchTerms: ["router-off"]
			}, {
				title: "ti ti-row-insert-bottom",
				searchTerms: ["row-insert-bottom"]
			}, {
				title: "ti ti-row-insert-top",
				searchTerms: ["row-insert-top"]
			}, {
				title: "ti ti-row-remove",
				searchTerms: ["row-remove"]
			}, {
				title: "ti ti-rss",
				searchTerms: ["rss"]
			}, {
				title: "ti ti-rubber-stamp",
				searchTerms: ["rubber-stamp"]
			}, {
				title: "ti ti-rubber-stamp-off",
				searchTerms: ["rubber-stamp-off"]
			}, {
				title: "ti ti-ruler",
				searchTerms: ["ruler"]
			}, {
				title: "ti ti-ruler-2",
				searchTerms: ["ruler-2"]
			}, {
				title: "ti ti-ruler-2-off",
				searchTerms: ["ruler-2-off"]
			}, {
				title: "ti ti-ruler-3",
				searchTerms: ["ruler-3"]
			}, {
				title: "ti ti-ruler-measure",
				searchTerms: ["ruler-measure"]
			}, {
				title: "ti ti-ruler-off",
				searchTerms: ["ruler-off"]
			}, {
				title: "ti ti-run",
				searchTerms: ["run"]
			}, {
				title: "ti ti-rv-truck",
				searchTerms: ["rv-truck"]
			}, {
				title: "ti ti-s-turn-down",
				searchTerms: ["s-turn-down"]
			}, {
				title: "ti ti-s-turn-left",
				searchTerms: ["s-turn-left"]
			}, {
				title: "ti ti-s-turn-right",
				searchTerms: ["s-turn-right"]
			}, {
				title: "ti ti-s-turn-up",
				searchTerms: ["s-turn-up"]
			}, {
				title: "ti ti-sailboat",
				searchTerms: ["sailboat"]
			}, {
				title: "ti ti-sailboat-2",
				searchTerms: ["sailboat-2"]
			}, {
				title: "ti ti-sailboat-off",
				searchTerms: ["sailboat-off"]
			}, {
				title: "ti ti-salad",
				searchTerms: ["salad"]
			}, {
				title: "ti ti-salt",
				searchTerms: ["salt"]
			}, {
				title: "ti ti-sandbox",
				searchTerms: ["sandbox"]
			}, {
				title: "ti ti-satellite",
				searchTerms: ["satellite"]
			}, {
				title: "ti ti-satellite-off",
				searchTerms: ["satellite-off"]
			}, {
				title: "ti ti-sausage",
				searchTerms: ["sausage"]
			}, {
				title: "ti ti-scale",
				searchTerms: ["scale"]
			}, {
				title: "ti ti-scale-off",
				searchTerms: ["scale-off"]
			}, {
				title: "ti ti-scale-outline",
				searchTerms: ["scale-outline"]
			}, {
				title: "ti ti-scale-outline-off",
				searchTerms: ["scale-outline-off"]
			}, {
				title: "ti ti-scan",
				searchTerms: ["scan"]
			}, {
				title: "ti ti-scan-eye",
				searchTerms: ["scan-eye"]
			}, {
				title: "ti ti-scan-position",
				searchTerms: ["scan-position"]
			}, {
				title: "ti ti-schema",
				searchTerms: ["schema"]
			}, {
				title: "ti ti-schema-off",
				searchTerms: ["schema-off"]
			}, {
				title: "ti ti-school",
				searchTerms: ["school"]
			}, {
				title: "ti ti-school-bell",
				searchTerms: ["school-bell"]
			}, {
				title: "ti ti-school-off",
				searchTerms: ["school-off"]
			}, {
				title: "ti ti-scissors",
				searchTerms: ["scissors"]
			}, {
				title: "ti ti-scissors-off",
				searchTerms: ["scissors-off"]
			}, {
				title: "ti ti-scooter",
				searchTerms: ["scooter"]
			}, {
				title: "ti ti-scooter-electric",
				searchTerms: ["scooter-electric"]
			}, {
				title: "ti ti-scoreboard",
				searchTerms: ["scoreboard"]
			}, {
				title: "ti ti-screen-share",
				searchTerms: ["screen-share"]
			}, {
				title: "ti ti-screen-share-off",
				searchTerms: ["screen-share-off"]
			}, {
				title: "ti ti-screenshot",
				searchTerms: ["screenshot"]
			}, {
				title: "ti ti-scribble",
				searchTerms: ["scribble"]
			}, {
				title: "ti ti-scribble-off",
				searchTerms: ["scribble-off"]
			}, {
				title: "ti ti-script",
				searchTerms: ["script"]
			}, {
				title: "ti ti-script-minus",
				searchTerms: ["script-minus"]
			}, {
				title: "ti ti-script-plus",
				searchTerms: ["script-plus"]
			}, {
				title: "ti ti-script-x",
				searchTerms: ["script-x"]
			}, {
				title: "ti ti-scuba-diving",
				searchTerms: ["scuba-diving"]
			}, {
				title: "ti ti-scuba-mask",
				searchTerms: ["scuba-mask"]
			}, {
				title: "ti ti-scuba-mask-off",
				searchTerms: ["scuba-mask-off"]
			}, {
				title: "ti ti-sdk",
				searchTerms: ["sdk"]
			}, {
				title: "ti ti-search",
				searchTerms: ["search"]
			}, {
				title: "ti ti-search-off",
				searchTerms: ["search-off"]
			}, {
				title: "ti ti-section",
				searchTerms: ["section"]
			}, {
				title: "ti ti-section-filled",
				searchTerms: ["section-filled"]
			}, {
				title: "ti ti-section-sign",
				searchTerms: ["section-sign"]
			}, {
				title: "ti ti-seeding",
				searchTerms: ["seeding"]
			}, {
				title: "ti ti-seeding-off",
				searchTerms: ["seeding-off"]
			}, {
				title: "ti ti-select",
				searchTerms: ["select"]
			}, {
				title: "ti ti-select-all",
				searchTerms: ["select-all"]
			}, {
				title: "ti ti-selector",
				searchTerms: ["selector"]
			}, {
				title: "ti ti-send",
				searchTerms: ["send"]
			}, {
				title: "ti ti-send-2",
				searchTerms: ["send-2"]
			}, {
				title: "ti ti-send-off",
				searchTerms: ["send-off"]
			}, {
				title: "ti ti-seo",
				searchTerms: ["seo"]
			}, {
				title: "ti ti-separator",
				searchTerms: ["separator"]
			}, {
				title: "ti ti-separator-horizontal",
				searchTerms: ["separator-horizontal"]
			}, {
				title: "ti ti-separator-vertical",
				searchTerms: ["separator-vertical"]
			}, {
				title: "ti ti-server",
				searchTerms: ["server"]
			}, {
				title: "ti ti-server-2",
				searchTerms: ["server-2"]
			}, {
				title: "ti ti-server-bolt",
				searchTerms: ["server-bolt"]
			}, {
				title: "ti ti-server-cog",
				searchTerms: ["server-cog"]
			}, {
				title: "ti ti-server-off",
				searchTerms: ["server-off"]
			}, {
				title: "ti ti-servicemark",
				searchTerms: ["servicemark"]
			}, {
				title: "ti ti-settings",
				searchTerms: ["settings"]
			}, {
				title: "ti ti-settings-2",
				searchTerms: ["settings-2"]
			}, {
				title: "ti ti-settings-automation",
				searchTerms: ["settings-automation"]
			}, {
				title: "ti ti-settings-bolt",
				searchTerms: ["settings-bolt"]
			}, {
				title: "ti ti-settings-cancel",
				searchTerms: ["settings-cancel"]
			}, {
				title: "ti ti-settings-check",
				searchTerms: ["settings-check"]
			}, {
				title: "ti ti-settings-code",
				searchTerms: ["settings-code"]
			}, {
				title: "ti ti-settings-cog",
				searchTerms: ["settings-cog"]
			}, {
				title: "ti ti-settings-dollar",
				searchTerms: ["settings-dollar"]
			}, {
				title: "ti ti-settings-down",
				searchTerms: ["settings-down"]
			}, {
				title: "ti ti-settings-exclamation",
				searchTerms: ["settings-exclamation"]
			}, {
				title: "ti ti-settings-filled",
				searchTerms: ["settings-filled"]
			}, {
				title: "ti ti-settings-heart",
				searchTerms: ["settings-heart"]
			}, {
				title: "ti ti-settings-minus",
				searchTerms: ["settings-minus"]
			}, {
				title: "ti ti-settings-off",
				searchTerms: ["settings-off"]
			}, {
				title: "ti ti-settings-pause",
				searchTerms: ["settings-pause"]
			}, {
				title: "ti ti-settings-pin",
				searchTerms: ["settings-pin"]
			}, {
				title: "ti ti-settings-plus",
				searchTerms: ["settings-plus"]
			}, {
				title: "ti ti-settings-question",
				searchTerms: ["settings-question"]
			}, {
				title: "ti ti-settings-search",
				searchTerms: ["settings-search"]
			}, {
				title: "ti ti-settings-share",
				searchTerms: ["settings-share"]
			}, {
				title: "ti ti-settings-star",
				searchTerms: ["settings-star"]
			}, {
				title: "ti ti-settings-up",
				searchTerms: ["settings-up"]
			}, {
				title: "ti ti-settings-x",
				searchTerms: ["settings-x"]
			}, {
				title: "ti ti-shadow",
				searchTerms: ["shadow"]
			}, {
				title: "ti ti-shadow-off",
				searchTerms: ["shadow-off"]
			}, {
				title: "ti ti-shape",
				searchTerms: ["shape"]
			}, {
				title: "ti ti-shape-2",
				searchTerms: ["shape-2"]
			}, {
				title: "ti ti-shape-3",
				searchTerms: ["shape-3"]
			}, {
				title: "ti ti-shape-off",
				searchTerms: ["shape-off"]
			}, {
				title: "ti ti-share",
				searchTerms: ["share"]
			}, {
				title: "ti ti-share-2",
				searchTerms: ["share-2"]
			}, {
				title: "ti ti-share-3",
				searchTerms: ["share-3"]
			}, {
				title: "ti ti-share-off",
				searchTerms: ["share-off"]
			}, {
				title: "ti ti-shareplay",
				searchTerms: ["shareplay"]
			}, {
				title: "ti ti-shield",
				searchTerms: ["shield"]
			}, {
				title: "ti ti-shield-bolt",
				searchTerms: ["shield-bolt"]
			}, {
				title: "ti ti-shield-cancel",
				searchTerms: ["shield-cancel"]
			}, {
				title: "ti ti-shield-check",
				searchTerms: ["shield-check"]
			}, {
				title: "ti ti-shield-check-filled",
				searchTerms: ["shield-check-filled"]
			}, {
				title: "ti ti-shield-checkered",
				searchTerms: ["shield-checkered"]
			}, {
				title: "ti ti-shield-checkered-filled",
				searchTerms: ["shield-checkered-filled"]
			}, {
				title: "ti ti-shield-chevron",
				searchTerms: ["shield-chevron"]
			}, {
				title: "ti ti-shield-code",
				searchTerms: ["shield-code"]
			}, {
				title: "ti ti-shield-cog",
				searchTerms: ["shield-cog"]
			}, {
				title: "ti ti-shield-dollar",
				searchTerms: ["shield-dollar"]
			}, {
				title: "ti ti-shield-down",
				searchTerms: ["shield-down"]
			}, {
				title: "ti ti-shield-exclamation",
				searchTerms: ["shield-exclamation"]
			}, {
				title: "ti ti-shield-filled",
				searchTerms: ["shield-filled"]
			}, {
				title: "ti ti-shield-half",
				searchTerms: ["shield-half"]
			}, {
				title: "ti ti-shield-half-filled",
				searchTerms: ["shield-half-filled"]
			}, {
				title: "ti ti-shield-heart",
				searchTerms: ["shield-heart"]
			}, {
				title: "ti ti-shield-lock",
				searchTerms: ["shield-lock"]
			}, {
				title: "ti ti-shield-lock-filled",
				searchTerms: ["shield-lock-filled"]
			}, {
				title: "ti ti-shield-minus",
				searchTerms: ["shield-minus"]
			}, {
				title: "ti ti-shield-off",
				searchTerms: ["shield-off"]
			}, {
				title: "ti ti-shield-pause",
				searchTerms: ["shield-pause"]
			}, {
				title: "ti ti-shield-pin",
				searchTerms: ["shield-pin"]
			}, {
				title: "ti ti-shield-plus",
				searchTerms: ["shield-plus"]
			}, {
				title: "ti ti-shield-question",
				searchTerms: ["shield-question"]
			}, {
				title: "ti ti-shield-search",
				searchTerms: ["shield-search"]
			}, {
				title: "ti ti-shield-share",
				searchTerms: ["shield-share"]
			}, {
				title: "ti ti-shield-star",
				searchTerms: ["shield-star"]
			}, {
				title: "ti ti-shield-up",
				searchTerms: ["shield-up"]
			}, {
				title: "ti ti-shield-x",
				searchTerms: ["shield-x"]
			}, {
				title: "ti ti-ship",
				searchTerms: ["ship"]
			}, {
				title: "ti ti-ship-off",
				searchTerms: ["ship-off"]
			}, {
				title: "ti ti-shirt",
				searchTerms: ["shirt"]
			}, {
				title: "ti ti-shirt-filled",
				searchTerms: ["shirt-filled"]
			}, {
				title: "ti ti-shirt-off",
				searchTerms: ["shirt-off"]
			}, {
				title: "ti ti-shirt-sport",
				searchTerms: ["shirt-sport"]
			}, {
				title: "ti ti-shoe",
				searchTerms: ["shoe"]
			}, {
				title: "ti ti-shoe-off",
				searchTerms: ["shoe-off"]
			}, {
				title: "ti ti-shopping-bag",
				searchTerms: ["shopping-bag"]
			}, {
				title: "ti ti-shopping-bag-check",
				searchTerms: ["shopping-bag-check"]
			}, {
				title: "ti ti-shopping-bag-discount",
				searchTerms: ["shopping-bag-discount"]
			}, {
				title: "ti ti-shopping-bag-edit",
				searchTerms: ["shopping-bag-edit"]
			}, {
				title: "ti ti-shopping-bag-exclamation",
				searchTerms: ["shopping-bag-exclamation"]
			}, {
				title: "ti ti-shopping-bag-heart",
				searchTerms: ["shopping-bag-heart"]
			}, {
				title: "ti ti-shopping-bag-minus",
				searchTerms: ["shopping-bag-minus"]
			}, {
				title: "ti ti-shopping-bag-plus",
				searchTerms: ["shopping-bag-plus"]
			}, {
				title: "ti ti-shopping-bag-search",
				searchTerms: ["shopping-bag-search"]
			}, {
				title: "ti ti-shopping-bag-x",
				searchTerms: ["shopping-bag-x"]
			}, {
				title: "ti ti-shopping-cart",
				searchTerms: ["shopping-cart"]
			}, {
				title: "ti ti-shopping-cart-bolt",
				searchTerms: ["shopping-cart-bolt"]
			}, {
				title: "ti ti-shopping-cart-cancel",
				searchTerms: ["shopping-cart-cancel"]
			}, {
				title: "ti ti-shopping-cart-check",
				searchTerms: ["shopping-cart-check"]
			}, {
				title: "ti ti-shopping-cart-code",
				searchTerms: ["shopping-cart-code"]
			}, {
				title: "ti ti-shopping-cart-cog",
				searchTerms: ["shopping-cart-cog"]
			}, {
				title: "ti ti-shopping-cart-copy",
				searchTerms: ["shopping-cart-copy"]
			}, {
				title: "ti ti-shopping-cart-discount",
				searchTerms: ["shopping-cart-discount"]
			}, {
				title: "ti ti-shopping-cart-dollar",
				searchTerms: ["shopping-cart-dollar"]
			}, {
				title: "ti ti-shopping-cart-down",
				searchTerms: ["shopping-cart-down"]
			}, {
				title: "ti ti-shopping-cart-exclamation",
				searchTerms: ["shopping-cart-exclamation"]
			}, {
				title: "ti ti-shopping-cart-filled",
				searchTerms: ["shopping-cart-filled"]
			}, {
				title: "ti ti-shopping-cart-heart",
				searchTerms: ["shopping-cart-heart"]
			}, {
				title: "ti ti-shopping-cart-minus",
				searchTerms: ["shopping-cart-minus"]
			}, {
				title: "ti ti-shopping-cart-off",
				searchTerms: ["shopping-cart-off"]
			}, {
				title: "ti ti-shopping-cart-pause",
				searchTerms: ["shopping-cart-pause"]
			}, {
				title: "ti ti-shopping-cart-pin",
				searchTerms: ["shopping-cart-pin"]
			}, {
				title: "ti ti-shopping-cart-plus",
				searchTerms: ["shopping-cart-plus"]
			}, {
				title: "ti ti-shopping-cart-question",
				searchTerms: ["shopping-cart-question"]
			}, {
				title: "ti ti-shopping-cart-search",
				searchTerms: ["shopping-cart-search"]
			}, {
				title: "ti ti-shopping-cart-share",
				searchTerms: ["shopping-cart-share"]
			}, {
				title: "ti ti-shopping-cart-star",
				searchTerms: ["shopping-cart-star"]
			}, {
				title: "ti ti-shopping-cart-up",
				searchTerms: ["shopping-cart-up"]
			}, {
				title: "ti ti-shopping-cart-x",
				searchTerms: ["shopping-cart-x"]
			}, {
				title: "ti ti-shovel",
				searchTerms: ["shovel"]
			}, {
				title: "ti ti-shovel-pitchforks",
				searchTerms: ["shovel-pitchforks"]
			}, {
				title: "ti ti-shredder",
				searchTerms: ["shredder"]
			}, {
				title: "ti ti-sign-left",
				searchTerms: ["sign-left"]
			}, {
				title: "ti ti-sign-left-filled",
				searchTerms: ["sign-left-filled"]
			}, {
				title: "ti ti-sign-right",
				searchTerms: ["sign-right"]
			}, {
				title: "ti ti-sign-right-filled",
				searchTerms: ["sign-right-filled"]
			}, {
				title: "ti ti-signal-2g",
				searchTerms: ["signal-2g"]
			}, {
				title: "ti ti-signal-3g",
				searchTerms: ["signal-3g"]
			}, {
				title: "ti ti-signal-4g",
				searchTerms: ["signal-4g"]
			}, {
				title: "ti ti-signal-4g-plus",
				searchTerms: ["signal-4g-plus"]
			}, {
				title: "ti ti-signal-5g",
				searchTerms: ["signal-5g"]
			}, {
				title: "ti ti-signal-6g",
				searchTerms: ["signal-6g"]
			}, {
				title: "ti ti-signal-e",
				searchTerms: ["signal-e"]
			}, {
				title: "ti ti-signal-g",
				searchTerms: ["signal-g"]
			}, {
				title: "ti ti-signal-h",
				searchTerms: ["signal-h"]
			}, {
				title: "ti ti-signal-h-plus",
				searchTerms: ["signal-h-plus"]
			}, {
				title: "ti ti-signal-lte",
				searchTerms: ["signal-lte"]
			}, {
				title: "ti ti-signature",
				searchTerms: ["signature"]
			}, {
				title: "ti ti-signature-off",
				searchTerms: ["signature-off"]
			}, {
				title: "ti ti-sitemap",
				searchTerms: ["sitemap"]
			}, {
				title: "ti ti-sitemap-off",
				searchTerms: ["sitemap-off"]
			}, {
				title: "ti ti-skateboard",
				searchTerms: ["skateboard"]
			}, {
				title: "ti ti-skateboard-off",
				searchTerms: ["skateboard-off"]
			}, {
				title: "ti ti-skateboarding",
				searchTerms: ["skateboarding"]
			}, {
				title: "ti ti-skew-x",
				searchTerms: ["skew-x"]
			}, {
				title: "ti ti-skew-y",
				searchTerms: ["skew-y"]
			}, {
				title: "ti ti-ski-jumping",
				searchTerms: ["ski-jumping"]
			}, {
				title: "ti ti-skull",
				searchTerms: ["skull"]
			}, {
				title: "ti ti-slash",
				searchTerms: ["slash"]
			}, {
				title: "ti ti-slashes",
				searchTerms: ["slashes"]
			}, {
				title: "ti ti-sleigh",
				searchTerms: ["sleigh"]
			}, {
				title: "ti ti-slice",
				searchTerms: ["slice"]
			}, {
				title: "ti ti-slideshow",
				searchTerms: ["slideshow"]
			}, {
				title: "ti ti-smart-home",
				searchTerms: ["smart-home"]
			}, {
				title: "ti ti-smart-home-off",
				searchTerms: ["smart-home-off"]
			}, {
				title: "ti ti-smoking",
				searchTerms: ["smoking"]
			}, {
				title: "ti ti-smoking-no",
				searchTerms: ["smoking-no"]
			}, {
				title: "ti ti-snowboarding",
				searchTerms: ["snowboarding"]
			}, {
				title: "ti ti-snowflake",
				searchTerms: ["snowflake"]
			}, {
				title: "ti ti-snowflake-off",
				searchTerms: ["snowflake-off"]
			}, {
				title: "ti ti-snowman",
				searchTerms: ["snowman"]
			}, {
				title: "ti ti-soccer-field",
				searchTerms: ["soccer-field"]
			}, {
				title: "ti ti-social",
				searchTerms: ["social"]
			}, {
				title: "ti ti-social-off",
				searchTerms: ["social-off"]
			}, {
				title: "ti ti-sock",
				searchTerms: ["sock"]
			}, {
				title: "ti ti-sofa",
				searchTerms: ["sofa"]
			}, {
				title: "ti ti-sofa-off",
				searchTerms: ["sofa-off"]
			}, {
				title: "ti ti-solar-electricity",
				searchTerms: ["solar-electricity"]
			}, {
				title: "ti ti-solar-panel",
				searchTerms: ["solar-panel"]
			}, {
				title: "ti ti-solar-panel-2",
				searchTerms: ["solar-panel-2"]
			}, {
				title: "ti ti-sort-0-9",
				searchTerms: ["sort-0-9"]
			}, {
				title: "ti ti-sort-9-0",
				searchTerms: ["sort-9-0"]
			}, {
				title: "ti ti-sort-a-z",
				searchTerms: ["sort-a-z"]
			}, {
				title: "ti ti-sort-ascending",
				searchTerms: ["sort-ascending"]
			}, {
				title: "ti ti-sort-ascending-2",
				searchTerms: ["sort-ascending-2"]
			}, {
				title: "ti ti-sort-ascending-letters",
				searchTerms: ["sort-ascending-letters"]
			}, {
				title: "ti ti-sort-ascending-numbers",
				searchTerms: ["sort-ascending-numbers"]
			}, {
				title: "ti ti-sort-ascending-shapes",
				searchTerms: ["sort-ascending-shapes"]
			}, {
				title: "ti ti-sort-ascending-small-big",
				searchTerms: ["sort-ascending-small-big"]
			}, {
				title: "ti ti-sort-descending",
				searchTerms: ["sort-descending"]
			}, {
				title: "ti ti-sort-descending-2",
				searchTerms: ["sort-descending-2"]
			}, {
				title: "ti ti-sort-descending-letters",
				searchTerms: ["sort-descending-letters"]
			}, {
				title: "ti ti-sort-descending-numbers",
				searchTerms: ["sort-descending-numbers"]
			}, {
				title: "ti ti-sort-descending-shapes",
				searchTerms: ["sort-descending-shapes"]
			}, {
				title: "ti ti-sort-descending-small-big",
				searchTerms: ["sort-descending-small-big"]
			}, {
				title: "ti ti-sort-z-a",
				searchTerms: ["sort-z-a"]
			}, {
				title: "ti ti-sos",
				searchTerms: ["sos"]
			}, {
				title: "ti ti-soup",
				searchTerms: ["soup"]
			}, {
				title: "ti ti-soup-filled",
				searchTerms: ["soup-filled"]
			}, {
				title: "ti ti-soup-off",
				searchTerms: ["soup-off"]
			}, {
				title: "ti ti-source-code",
				searchTerms: ["source-code"]
			}, {
				title: "ti ti-space",
				searchTerms: ["space"]
			}, {
				title: "ti ti-space-off",
				searchTerms: ["space-off"]
			}, {
				title: "ti ti-spaces",
				searchTerms: ["spaces"]
			}, {
				title: "ti ti-spacing-horizontal",
				searchTerms: ["spacing-horizontal"]
			}, {
				title: "ti ti-spacing-vertical",
				searchTerms: ["spacing-vertical"]
			}, {
				title: "ti ti-spade",
				searchTerms: ["spade"]
			}, {
				title: "ti ti-spade-filled",
				searchTerms: ["spade-filled"]
			}, {
				title: "ti ti-sparkles",
				searchTerms: ["sparkles"]
			}, {
				title: "ti ti-speakerphone",
				searchTerms: ["speakerphone"]
			}, {
				title: "ti ti-speedboat",
				searchTerms: ["speedboat"]
			}, {
				title: "ti ti-sphere",
				searchTerms: ["sphere"]
			}, {
				title: "ti ti-sphere-off",
				searchTerms: ["sphere-off"]
			}, {
				title: "ti ti-sphere-plus",
				searchTerms: ["sphere-plus"]
			}, {
				title: "ti ti-spider",
				searchTerms: ["spider"]
			}, {
				title: "ti ti-spiral",
				searchTerms: ["spiral"]
			}, {
				title: "ti ti-spiral-off",
				searchTerms: ["spiral-off"]
			}, {
				title: "ti ti-sport-billard",
				searchTerms: ["sport-billard"]
			}, {
				title: "ti ti-spray",
				searchTerms: ["spray"]
			}, {
				title: "ti ti-spy",
				searchTerms: ["spy"]
			}, {
				title: "ti ti-spy-off",
				searchTerms: ["spy-off"]
			}, {
				title: "ti ti-sql",
				searchTerms: ["sql"]
			}, {
				title: "ti ti-square",
				searchTerms: ["square"]
			}, {
				title: "ti ti-square-arrow-down",
				searchTerms: ["square-arrow-down"]
			}, {
				title: "ti ti-square-arrow-down-filled",
				searchTerms: ["square-arrow-down-filled"]
			}, {
				title: "ti ti-square-arrow-left",
				searchTerms: ["square-arrow-left"]
			}, {
				title: "ti ti-square-arrow-left-filled",
				searchTerms: ["square-arrow-left-filled"]
			}, {
				title: "ti ti-square-arrow-right",
				searchTerms: ["square-arrow-right"]
			}, {
				title: "ti ti-square-arrow-right-filled",
				searchTerms: ["square-arrow-right-filled"]
			}, {
				title: "ti ti-square-arrow-up",
				searchTerms: ["square-arrow-up"]
			}, {
				title: "ti ti-square-arrow-up-filled",
				searchTerms: ["square-arrow-up-filled"]
			}, {
				title: "ti ti-square-asterisk",
				searchTerms: ["square-asterisk"]
			}, {
				title: "ti ti-square-asterisk-filled",
				searchTerms: ["square-asterisk-filled"]
			}, {
				title: "ti ti-square-check",
				searchTerms: ["square-check"]
			}, {
				title: "ti ti-square-check-filled",
				searchTerms: ["square-check-filled"]
			}, {
				title: "ti ti-square-chevron-down",
				searchTerms: ["square-chevron-down"]
			}, {
				title: "ti ti-square-chevron-down-filled",
				searchTerms: ["square-chevron-down-filled"]
			}, {
				title: "ti ti-square-chevron-left",
				searchTerms: ["square-chevron-left"]
			}, {
				title: "ti ti-square-chevron-left-filled",
				searchTerms: ["square-chevron-left-filled"]
			}, {
				title: "ti ti-square-chevron-right",
				searchTerms: ["square-chevron-right"]
			}, {
				title: "ti ti-square-chevron-right-filled",
				searchTerms: ["square-chevron-right-filled"]
			}, {
				title: "ti ti-square-chevron-up",
				searchTerms: ["square-chevron-up"]
			}, {
				title: "ti ti-square-chevron-up-filled",
				searchTerms: ["square-chevron-up-filled"]
			}, {
				title: "ti ti-square-chevrons-down",
				searchTerms: ["square-chevrons-down"]
			}, {
				title: "ti ti-square-chevrons-down-filled",
				searchTerms: ["square-chevrons-down-filled"]
			}, {
				title: "ti ti-square-chevrons-left",
				searchTerms: ["square-chevrons-left"]
			}, {
				title: "ti ti-square-chevrons-left-filled",
				searchTerms: ["square-chevrons-left-filled"]
			}, {
				title: "ti ti-square-chevrons-right",
				searchTerms: ["square-chevrons-right"]
			}, {
				title: "ti ti-square-chevrons-right-filled",
				searchTerms: ["square-chevrons-right-filled"]
			}, {
				title: "ti ti-square-chevrons-up",
				searchTerms: ["square-chevrons-up"]
			}, {
				title: "ti ti-square-chevrons-up-filled",
				searchTerms: ["square-chevrons-up-filled"]
			}, {
				title: "ti ti-square-dot",
				searchTerms: ["square-dot"]
			}, {
				title: "ti ti-square-dot-filled",
				searchTerms: ["square-dot-filled"]
			}, {
				title: "ti ti-square-f0",
				searchTerms: ["square-f0"]
			}, {
				title: "ti ti-square-f0-filled",
				searchTerms: ["square-f0-filled"]
			}, {
				title: "ti ti-square-f1",
				searchTerms: ["square-f1"]
			}, {
				title: "ti ti-square-f1-filled",
				searchTerms: ["square-f1-filled"]
			}, {
				title: "ti ti-square-f2",
				searchTerms: ["square-f2"]
			}, {
				title: "ti ti-square-f2-filled",
				searchTerms: ["square-f2-filled"]
			}, {
				title: "ti ti-square-f3",
				searchTerms: ["square-f3"]
			}, {
				title: "ti ti-square-f3-filled",
				searchTerms: ["square-f3-filled"]
			}, {
				title: "ti ti-square-f4",
				searchTerms: ["square-f4"]
			}, {
				title: "ti ti-square-f4-filled",
				searchTerms: ["square-f4-filled"]
			}, {
				title: "ti ti-square-f5",
				searchTerms: ["square-f5"]
			}, {
				title: "ti ti-square-f5-filled",
				searchTerms: ["square-f5-filled"]
			}, {
				title: "ti ti-square-f6",
				searchTerms: ["square-f6"]
			}, {
				title: "ti ti-square-f6-filled",
				searchTerms: ["square-f6-filled"]
			}, {
				title: "ti ti-square-f7",
				searchTerms: ["square-f7"]
			}, {
				title: "ti ti-square-f7-filled",
				searchTerms: ["square-f7-filled"]
			}, {
				title: "ti ti-square-f8",
				searchTerms: ["square-f8"]
			}, {
				title: "ti ti-square-f8-filled",
				searchTerms: ["square-f8-filled"]
			}, {
				title: "ti ti-square-f9",
				searchTerms: ["square-f9"]
			}, {
				title: "ti ti-square-f9-filled",
				searchTerms: ["square-f9-filled"]
			}, {
				title: "ti ti-square-filled",
				searchTerms: ["square-filled"]
			}, {
				title: "ti ti-square-forbid",
				searchTerms: ["square-forbid"]
			}, {
				title: "ti ti-square-forbid-2",
				searchTerms: ["square-forbid-2"]
			}, {
				title: "ti ti-square-half",
				searchTerms: ["square-half"]
			}, {
				title: "ti ti-square-key",
				searchTerms: ["square-key"]
			}, {
				title: "ti ti-square-letter-a",
				searchTerms: ["square-letter-a"]
			}, {
				title: "ti ti-square-letter-a-filled",
				searchTerms: ["square-letter-a-filled"]
			}, {
				title: "ti ti-square-letter-b",
				searchTerms: ["square-letter-b"]
			}, {
				title: "ti ti-square-letter-b-filled",
				searchTerms: ["square-letter-b-filled"]
			}, {
				title: "ti ti-square-letter-c",
				searchTerms: ["square-letter-c"]
			}, {
				title: "ti ti-square-letter-c-filled",
				searchTerms: ["square-letter-c-filled"]
			}, {
				title: "ti ti-square-letter-d",
				searchTerms: ["square-letter-d"]
			}, {
				title: "ti ti-square-letter-d-filled",
				searchTerms: ["square-letter-d-filled"]
			}, {
				title: "ti ti-square-letter-e",
				searchTerms: ["square-letter-e"]
			}, {
				title: "ti ti-square-letter-e-filled",
				searchTerms: ["square-letter-e-filled"]
			}, {
				title: "ti ti-square-letter-f",
				searchTerms: ["square-letter-f"]
			}, {
				title: "ti ti-square-letter-f-filled",
				searchTerms: ["square-letter-f-filled"]
			}, {
				title: "ti ti-square-letter-g",
				searchTerms: ["square-letter-g"]
			}, {
				title: "ti ti-square-letter-g-filled",
				searchTerms: ["square-letter-g-filled"]
			}, {
				title: "ti ti-square-letter-h",
				searchTerms: ["square-letter-h"]
			}, {
				title: "ti ti-square-letter-h-filled",
				searchTerms: ["square-letter-h-filled"]
			}, {
				title: "ti ti-square-letter-i",
				searchTerms: ["square-letter-i"]
			}, {
				title: "ti ti-square-letter-i-filled",
				searchTerms: ["square-letter-i-filled"]
			}, {
				title: "ti ti-square-letter-j",
				searchTerms: ["square-letter-j"]
			}, {
				title: "ti ti-square-letter-j-filled",
				searchTerms: ["square-letter-j-filled"]
			}, {
				title: "ti ti-square-letter-k",
				searchTerms: ["square-letter-k"]
			}, {
				title: "ti ti-square-letter-k-filled",
				searchTerms: ["square-letter-k-filled"]
			}, {
				title: "ti ti-square-letter-l",
				searchTerms: ["square-letter-l"]
			}, {
				title: "ti ti-square-letter-l-filled",
				searchTerms: ["square-letter-l-filled"]
			}, {
				title: "ti ti-square-letter-m",
				searchTerms: ["square-letter-m"]
			}, {
				title: "ti ti-square-letter-m-filled",
				searchTerms: ["square-letter-m-filled"]
			}, {
				title: "ti ti-square-letter-n",
				searchTerms: ["square-letter-n"]
			}, {
				title: "ti ti-square-letter-n-filled",
				searchTerms: ["square-letter-n-filled"]
			}, {
				title: "ti ti-square-letter-o",
				searchTerms: ["square-letter-o"]
			}, {
				title: "ti ti-square-letter-o-filled",
				searchTerms: ["square-letter-o-filled"]
			}, {
				title: "ti ti-square-letter-p",
				searchTerms: ["square-letter-p"]
			}, {
				title: "ti ti-square-letter-p-filled",
				searchTerms: ["square-letter-p-filled"]
			}, {
				title: "ti ti-square-letter-q",
				searchTerms: ["square-letter-q"]
			}, {
				title: "ti ti-square-letter-q-filled",
				searchTerms: ["square-letter-q-filled"]
			}, {
				title: "ti ti-square-letter-r",
				searchTerms: ["square-letter-r"]
			}, {
				title: "ti ti-square-letter-r-filled",
				searchTerms: ["square-letter-r-filled"]
			}, {
				title: "ti ti-square-letter-s",
				searchTerms: ["square-letter-s"]
			}, {
				title: "ti ti-square-letter-s-filled",
				searchTerms: ["square-letter-s-filled"]
			}, {
				title: "ti ti-square-letter-t",
				searchTerms: ["square-letter-t"]
			}, {
				title: "ti ti-square-letter-t-filled",
				searchTerms: ["square-letter-t-filled"]
			}, {
				title: "ti ti-square-letter-u",
				searchTerms: ["square-letter-u"]
			}, {
				title: "ti ti-square-letter-u-filled",
				searchTerms: ["square-letter-u-filled"]
			}, {
				title: "ti ti-square-letter-v",
				searchTerms: ["square-letter-v"]
			}, {
				title: "ti ti-square-letter-v-filled",
				searchTerms: ["square-letter-v-filled"]
			}, {
				title: "ti ti-square-letter-w",
				searchTerms: ["square-letter-w"]
			}, {
				title: "ti ti-square-letter-w-filled",
				searchTerms: ["square-letter-w-filled"]
			}, {
				title: "ti ti-square-letter-x",
				searchTerms: ["square-letter-x"]
			}, {
				title: "ti ti-square-letter-x-filled",
				searchTerms: ["square-letter-x-filled"]
			}, {
				title: "ti ti-square-letter-y",
				searchTerms: ["square-letter-y"]
			}, {
				title: "ti ti-square-letter-y-filled",
				searchTerms: ["square-letter-y-filled"]
			}, {
				title: "ti ti-square-letter-z",
				searchTerms: ["square-letter-z"]
			}, {
				title: "ti ti-square-letter-z-filled",
				searchTerms: ["square-letter-z-filled"]
			}, {
				title: "ti ti-square-minus",
				searchTerms: ["square-minus"]
			}, {
				title: "ti ti-square-minus-filled",
				searchTerms: ["square-minus-filled"]
			}, {
				title: "ti ti-square-number-0",
				searchTerms: ["square-number-0"]
			}, {
				title: "ti ti-square-number-0-filled",
				searchTerms: ["square-number-0-filled"]
			}, {
				title: "ti ti-square-number-1",
				searchTerms: ["square-number-1"]
			}, {
				title: "ti ti-square-number-1-filled",
				searchTerms: ["square-number-1-filled"]
			}, {
				title: "ti ti-square-number-2",
				searchTerms: ["square-number-2"]
			}, {
				title: "ti ti-square-number-2-filled",
				searchTerms: ["square-number-2-filled"]
			}, {
				title: "ti ti-square-number-3",
				searchTerms: ["square-number-3"]
			}, {
				title: "ti ti-square-number-3-filled",
				searchTerms: ["square-number-3-filled"]
			}, {
				title: "ti ti-square-number-4",
				searchTerms: ["square-number-4"]
			}, {
				title: "ti ti-square-number-4-filled",
				searchTerms: ["square-number-4-filled"]
			}, {
				title: "ti ti-square-number-5",
				searchTerms: ["square-number-5"]
			}, {
				title: "ti ti-square-number-5-filled",
				searchTerms: ["square-number-5-filled"]
			}, {
				title: "ti ti-square-number-6",
				searchTerms: ["square-number-6"]
			}, {
				title: "ti ti-square-number-6-filled",
				searchTerms: ["square-number-6-filled"]
			}, {
				title: "ti ti-square-number-7",
				searchTerms: ["square-number-7"]
			}, {
				title: "ti ti-square-number-7-filled",
				searchTerms: ["square-number-7-filled"]
			}, {
				title: "ti ti-square-number-8",
				searchTerms: ["square-number-8"]
			}, {
				title: "ti ti-square-number-8-filled",
				searchTerms: ["square-number-8-filled"]
			}, {
				title: "ti ti-square-number-9",
				searchTerms: ["square-number-9"]
			}, {
				title: "ti ti-square-number-9-filled",
				searchTerms: ["square-number-9-filled"]
			}, {
				title: "ti ti-square-off",
				searchTerms: ["square-off"]
			}, {
				title: "ti ti-square-percentage",
				searchTerms: ["square-percentage"]
			}, {
				title: "ti ti-square-plus",
				searchTerms: ["square-plus"]
			}, {
				title: "ti ti-square-plus-2",
				searchTerms: ["square-plus-2"]
			}, {
				title: "ti ti-square-root",
				searchTerms: ["square-root"]
			}, {
				title: "ti ti-square-root-2",
				searchTerms: ["square-root-2"]
			}, {
				title: "ti ti-square-rotated",
				searchTerms: ["square-rotated"]
			}, {
				title: "ti ti-square-rotated-filled",
				searchTerms: ["square-rotated-filled"]
			}, {
				title: "ti ti-square-rotated-forbid",
				searchTerms: ["square-rotated-forbid"]
			}, {
				title: "ti ti-square-rotated-forbid-2",
				searchTerms: ["square-rotated-forbid-2"]
			}, {
				title: "ti ti-square-rotated-off",
				searchTerms: ["square-rotated-off"]
			}, {
				title: "ti ti-square-rounded",
				searchTerms: ["square-rounded"]
			}, {
				title: "ti ti-square-rounded-arrow-down",
				searchTerms: ["square-rounded-arrow-down"]
			}, {
				title: "ti ti-square-rounded-arrow-down-filled",
				searchTerms: ["square-rounded-arrow-down-filled"]
			}, {
				title: "ti ti-square-rounded-arrow-left",
				searchTerms: ["square-rounded-arrow-left"]
			}, {
				title: "ti ti-square-rounded-arrow-left-filled",
				searchTerms: ["square-rounded-arrow-left-filled"]
			}, {
				title: "ti ti-square-rounded-arrow-right",
				searchTerms: ["square-rounded-arrow-right"]
			}, {
				title: "ti ti-square-rounded-arrow-right-filled",
				searchTerms: ["square-rounded-arrow-right-filled"]
			}, {
				title: "ti ti-square-rounded-arrow-up",
				searchTerms: ["square-rounded-arrow-up"]
			}, {
				title: "ti ti-square-rounded-arrow-up-filled",
				searchTerms: ["square-rounded-arrow-up-filled"]
			}, {
				title: "ti ti-square-rounded-check",
				searchTerms: ["square-rounded-check"]
			}, {
				title: "ti ti-square-rounded-check-filled",
				searchTerms: ["square-rounded-check-filled"]
			}, {
				title: "ti ti-square-rounded-chevron-down",
				searchTerms: ["square-rounded-chevron-down"]
			}, {
				title: "ti ti-square-rounded-chevron-down-filled",
				searchTerms: ["square-rounded-chevron-down-filled"]
			}, {
				title: "ti ti-square-rounded-chevron-left",
				searchTerms: ["square-rounded-chevron-left"]
			}, {
				title: "ti ti-square-rounded-chevron-left-filled",
				searchTerms: ["square-rounded-chevron-left-filled"]
			}, {
				title: "ti ti-square-rounded-chevron-right",
				searchTerms: ["square-rounded-chevron-right"]
			}, {
				title: "ti ti-square-rounded-chevron-right-filled",
				searchTerms: ["square-rounded-chevron-right-filled"]
			}, {
				title: "ti ti-square-rounded-chevron-up",
				searchTerms: ["square-rounded-chevron-up"]
			}, {
				title: "ti ti-square-rounded-chevron-up-filled",
				searchTerms: ["square-rounded-chevron-up-filled"]
			}, {
				title: "ti ti-square-rounded-chevrons-down",
				searchTerms: ["square-rounded-chevrons-down"]
			}, {
				title: "ti ti-square-rounded-chevrons-down-filled",
				searchTerms: ["square-rounded-chevrons-down-filled"]
			}, {
				title: "ti ti-square-rounded-chevrons-left",
				searchTerms: ["square-rounded-chevrons-left"]
			}, {
				title: "ti ti-square-rounded-chevrons-left-filled",
				searchTerms: ["square-rounded-chevrons-left-filled"]
			}, {
				title: "ti ti-square-rounded-chevrons-right",
				searchTerms: ["square-rounded-chevrons-right"]
			}, {
				title: "ti ti-square-rounded-chevrons-right-filled",
				searchTerms: ["square-rounded-chevrons-right-filled"]
			}, {
				title: "ti ti-square-rounded-chevrons-up",
				searchTerms: ["square-rounded-chevrons-up"]
			}, {
				title: "ti ti-square-rounded-chevrons-up-filled",
				searchTerms: ["square-rounded-chevrons-up-filled"]
			}, {
				title: "ti ti-square-rounded-filled",
				searchTerms: ["square-rounded-filled"]
			}, {
				title: "ti ti-square-rounded-letter-a",
				searchTerms: ["square-rounded-letter-a"]
			}, {
				title: "ti ti-square-rounded-letter-a-filled",
				searchTerms: ["square-rounded-letter-a-filled"]
			}, {
				title: "ti ti-square-rounded-letter-b",
				searchTerms: ["square-rounded-letter-b"]
			}, {
				title: "ti ti-square-rounded-letter-b-filled",
				searchTerms: ["square-rounded-letter-b-filled"]
			}, {
				title: "ti ti-square-rounded-letter-c",
				searchTerms: ["square-rounded-letter-c"]
			}, {
				title: "ti ti-square-rounded-letter-c-filled",
				searchTerms: ["square-rounded-letter-c-filled"]
			}, {
				title: "ti ti-square-rounded-letter-d",
				searchTerms: ["square-rounded-letter-d"]
			}, {
				title: "ti ti-square-rounded-letter-d-filled",
				searchTerms: ["square-rounded-letter-d-filled"]
			}, {
				title: "ti ti-square-rounded-letter-e",
				searchTerms: ["square-rounded-letter-e"]
			}, {
				title: "ti ti-square-rounded-letter-e-filled",
				searchTerms: ["square-rounded-letter-e-filled"]
			}, {
				title: "ti ti-square-rounded-letter-f",
				searchTerms: ["square-rounded-letter-f"]
			}, {
				title: "ti ti-square-rounded-letter-f-filled",
				searchTerms: ["square-rounded-letter-f-filled"]
			}, {
				title: "ti ti-square-rounded-letter-g",
				searchTerms: ["square-rounded-letter-g"]
			}, {
				title: "ti ti-square-rounded-letter-g-filled",
				searchTerms: ["square-rounded-letter-g-filled"]
			}, {
				title: "ti ti-square-rounded-letter-h",
				searchTerms: ["square-rounded-letter-h"]
			}, {
				title: "ti ti-square-rounded-letter-h-filled",
				searchTerms: ["square-rounded-letter-h-filled"]
			}, {
				title: "ti ti-square-rounded-letter-i",
				searchTerms: ["square-rounded-letter-i"]
			}, {
				title: "ti ti-square-rounded-letter-i-filled",
				searchTerms: ["square-rounded-letter-i-filled"]
			}, {
				title: "ti ti-square-rounded-letter-j",
				searchTerms: ["square-rounded-letter-j"]
			}, {
				title: "ti ti-square-rounded-letter-j-filled",
				searchTerms: ["square-rounded-letter-j-filled"]
			}, {
				title: "ti ti-square-rounded-letter-k",
				searchTerms: ["square-rounded-letter-k"]
			}, {
				title: "ti ti-square-rounded-letter-k-filled",
				searchTerms: ["square-rounded-letter-k-filled"]
			}, {
				title: "ti ti-square-rounded-letter-l",
				searchTerms: ["square-rounded-letter-l"]
			}, {
				title: "ti ti-square-rounded-letter-l-filled",
				searchTerms: ["square-rounded-letter-l-filled"]
			}, {
				title: "ti ti-square-rounded-letter-m",
				searchTerms: ["square-rounded-letter-m"]
			}, {
				title: "ti ti-square-rounded-letter-m-filled",
				searchTerms: ["square-rounded-letter-m-filled"]
			}, {
				title: "ti ti-square-rounded-letter-n",
				searchTerms: ["square-rounded-letter-n"]
			}, {
				title: "ti ti-square-rounded-letter-n-filled",
				searchTerms: ["square-rounded-letter-n-filled"]
			}, {
				title: "ti ti-square-rounded-letter-o",
				searchTerms: ["square-rounded-letter-o"]
			}, {
				title: "ti ti-square-rounded-letter-o-filled",
				searchTerms: ["square-rounded-letter-o-filled"]
			}, {
				title: "ti ti-square-rounded-letter-p",
				searchTerms: ["square-rounded-letter-p"]
			}, {
				title: "ti ti-square-rounded-letter-p-filled",
				searchTerms: ["square-rounded-letter-p-filled"]
			}, {
				title: "ti ti-square-rounded-letter-q",
				searchTerms: ["square-rounded-letter-q"]
			}, {
				title: "ti ti-square-rounded-letter-q-filled",
				searchTerms: ["square-rounded-letter-q-filled"]
			}, {
				title: "ti ti-square-rounded-letter-r",
				searchTerms: ["square-rounded-letter-r"]
			}, {
				title: "ti ti-square-rounded-letter-r-filled",
				searchTerms: ["square-rounded-letter-r-filled"]
			}, {
				title: "ti ti-square-rounded-letter-s",
				searchTerms: ["square-rounded-letter-s"]
			}, {
				title: "ti ti-square-rounded-letter-s-filled",
				searchTerms: ["square-rounded-letter-s-filled"]
			}, {
				title: "ti ti-square-rounded-letter-t",
				searchTerms: ["square-rounded-letter-t"]
			}, {
				title: "ti ti-square-rounded-letter-t-filled",
				searchTerms: ["square-rounded-letter-t-filled"]
			}, {
				title: "ti ti-square-rounded-letter-u",
				searchTerms: ["square-rounded-letter-u"]
			}, {
				title: "ti ti-square-rounded-letter-u-filled",
				searchTerms: ["square-rounded-letter-u-filled"]
			}, {
				title: "ti ti-square-rounded-letter-v",
				searchTerms: ["square-rounded-letter-v"]
			}, {
				title: "ti ti-square-rounded-letter-v-filled",
				searchTerms: ["square-rounded-letter-v-filled"]
			}, {
				title: "ti ti-square-rounded-letter-w",
				searchTerms: ["square-rounded-letter-w"]
			}, {
				title: "ti ti-square-rounded-letter-w-filled",
				searchTerms: ["square-rounded-letter-w-filled"]
			}, {
				title: "ti ti-square-rounded-letter-x",
				searchTerms: ["square-rounded-letter-x"]
			}, {
				title: "ti ti-square-rounded-letter-x-filled",
				searchTerms: ["square-rounded-letter-x-filled"]
			}, {
				title: "ti ti-square-rounded-letter-y",
				searchTerms: ["square-rounded-letter-y"]
			}, {
				title: "ti ti-square-rounded-letter-y-filled",
				searchTerms: ["square-rounded-letter-y-filled"]
			}, {
				title: "ti ti-square-rounded-letter-z",
				searchTerms: ["square-rounded-letter-z"]
			}, {
				title: "ti ti-square-rounded-letter-z-filled",
				searchTerms: ["square-rounded-letter-z-filled"]
			}, {
				title: "ti ti-square-rounded-minus",
				searchTerms: ["square-rounded-minus"]
			}, {
				title: "ti ti-square-rounded-minus-2",
				searchTerms: ["square-rounded-minus-2"]
			}, {
				title: "ti ti-square-rounded-minus-filled",
				searchTerms: ["square-rounded-minus-filled"]
			}, {
				title: "ti ti-square-rounded-number-0",
				searchTerms: ["square-rounded-number-0"]
			}, {
				title: "ti ti-square-rounded-number-0-filled",
				searchTerms: ["square-rounded-number-0-filled"]
			}, {
				title: "ti ti-square-rounded-number-1",
				searchTerms: ["square-rounded-number-1"]
			}, {
				title: "ti ti-square-rounded-number-1-filled",
				searchTerms: ["square-rounded-number-1-filled"]
			}, {
				title: "ti ti-square-rounded-number-2",
				searchTerms: ["square-rounded-number-2"]
			}, {
				title: "ti ti-square-rounded-number-2-filled",
				searchTerms: ["square-rounded-number-2-filled"]
			}, {
				title: "ti ti-square-rounded-number-3",
				searchTerms: ["square-rounded-number-3"]
			}, {
				title: "ti ti-square-rounded-number-3-filled",
				searchTerms: ["square-rounded-number-3-filled"]
			}, {
				title: "ti ti-square-rounded-number-4",
				searchTerms: ["square-rounded-number-4"]
			}, {
				title: "ti ti-square-rounded-number-4-filled",
				searchTerms: ["square-rounded-number-4-filled"]
			}, {
				title: "ti ti-square-rounded-number-5",
				searchTerms: ["square-rounded-number-5"]
			}, {
				title: "ti ti-square-rounded-number-5-filled",
				searchTerms: ["square-rounded-number-5-filled"]
			}, {
				title: "ti ti-square-rounded-number-6",
				searchTerms: ["square-rounded-number-6"]
			}, {
				title: "ti ti-square-rounded-number-6-filled",
				searchTerms: ["square-rounded-number-6-filled"]
			}, {
				title: "ti ti-square-rounded-number-7",
				searchTerms: ["square-rounded-number-7"]
			}, {
				title: "ti ti-square-rounded-number-7-filled",
				searchTerms: ["square-rounded-number-7-filled"]
			}, {
				title: "ti ti-square-rounded-number-8",
				searchTerms: ["square-rounded-number-8"]
			}, {
				title: "ti ti-square-rounded-number-8-filled",
				searchTerms: ["square-rounded-number-8-filled"]
			}, {
				title: "ti ti-square-rounded-number-9",
				searchTerms: ["square-rounded-number-9"]
			}, {
				title: "ti ti-square-rounded-number-9-filled",
				searchTerms: ["square-rounded-number-9-filled"]
			}, {
				title: "ti ti-square-rounded-percentage",
				searchTerms: ["square-rounded-percentage"]
			}, {
				title: "ti ti-square-rounded-plus",
				searchTerms: ["square-rounded-plus"]
			}, {
				title: "ti ti-square-rounded-plus-2",
				searchTerms: ["square-rounded-plus-2"]
			}, {
				title: "ti ti-square-rounded-plus-filled",
				searchTerms: ["square-rounded-plus-filled"]
			}, {
				title: "ti ti-square-rounded-x",
				searchTerms: ["square-rounded-x"]
			}, {
				title: "ti ti-square-rounded-x-filled",
				searchTerms: ["square-rounded-x-filled"]
			}, {
				title: "ti ti-square-toggle",
				searchTerms: ["square-toggle"]
			}, {
				title: "ti ti-square-toggle-horizontal",
				searchTerms: ["square-toggle-horizontal"]
			}, {
				title: "ti ti-square-x",
				searchTerms: ["square-x"]
			}, {
				title: "ti ti-square-x-filled",
				searchTerms: ["square-x-filled"]
			}, {
				title: "ti ti-squares",
				searchTerms: ["squares"]
			}, {
				title: "ti ti-squares-diagonal",
				searchTerms: ["squares-diagonal"]
			}, {
				title: "ti ti-squares-filled",
				searchTerms: ["squares-filled"]
			}, {
				title: "ti ti-squares-selected",
				searchTerms: ["squares-selected"]
			}, {
				title: "ti ti-stack",
				searchTerms: ["stack"]
			}, {
				title: "ti ti-stack-2",
				searchTerms: ["stack-2"]
			}, {
				title: "ti ti-stack-2-filled",
				searchTerms: ["stack-2-filled"]
			}, {
				title: "ti ti-stack-3",
				searchTerms: ["stack-3"]
			}, {
				title: "ti ti-stack-3-filled",
				searchTerms: ["stack-3-filled"]
			}, {
				title: "ti ti-stack-back",
				searchTerms: ["stack-back"]
			}, {
				title: "ti ti-stack-backward",
				searchTerms: ["stack-backward"]
			}, {
				title: "ti ti-stack-filled",
				searchTerms: ["stack-filled"]
			}, {
				title: "ti ti-stack-forward",
				searchTerms: ["stack-forward"]
			}, {
				title: "ti ti-stack-front",
				searchTerms: ["stack-front"]
			}, {
				title: "ti ti-stack-middle",
				searchTerms: ["stack-middle"]
			}, {
				title: "ti ti-stack-pop",
				searchTerms: ["stack-pop"]
			}, {
				title: "ti ti-stack-push",
				searchTerms: ["stack-push"]
			}, {
				title: "ti ti-stairs",
				searchTerms: ["stairs"]
			}, {
				title: "ti ti-stairs-down",
				searchTerms: ["stairs-down"]
			}, {
				title: "ti ti-stairs-up",
				searchTerms: ["stairs-up"]
			}, {
				title: "ti ti-star",
				searchTerms: ["star"]
			}, {
				title: "ti ti-star-filled",
				searchTerms: ["star-filled"]
			}, {
				title: "ti ti-star-half",
				searchTerms: ["star-half"]
			}, {
				title: "ti ti-star-half-filled",
				searchTerms: ["star-half-filled"]
			}, {
				title: "ti ti-star-off",
				searchTerms: ["star-off"]
			}, {
				title: "ti ti-stars",
				searchTerms: ["stars"]
			}, {
				title: "ti ti-stars-filled",
				searchTerms: ["stars-filled"]
			}, {
				title: "ti ti-stars-off",
				searchTerms: ["stars-off"]
			}, {
				title: "ti ti-status-change",
				searchTerms: ["status-change"]
			}, {
				title: "ti ti-steam",
				searchTerms: ["steam"]
			}, {
				title: "ti ti-steering-wheel",
				searchTerms: ["steering-wheel"]
			}, {
				title: "ti ti-steering-wheel-off",
				searchTerms: ["steering-wheel-off"]
			}, {
				title: "ti ti-step-into",
				searchTerms: ["step-into"]
			}, {
				title: "ti ti-step-out",
				searchTerms: ["step-out"]
			}, {
				title: "ti ti-stereo-glasses",
				searchTerms: ["stereo-glasses"]
			}, {
				title: "ti ti-stethoscope",
				searchTerms: ["stethoscope"]
			}, {
				title: "ti ti-stethoscope-off",
				searchTerms: ["stethoscope-off"]
			}, {
				title: "ti ti-sticker",
				searchTerms: ["sticker"]
			}, {
				title: "ti ti-sticker-2",
				searchTerms: ["sticker-2"]
			}, {
				title: "ti ti-storm",
				searchTerms: ["storm"]
			}, {
				title: "ti ti-storm-off",
				searchTerms: ["storm-off"]
			}, {
				title: "ti ti-stretching",
				searchTerms: ["stretching"]
			}, {
				title: "ti ti-stretching-2",
				searchTerms: ["stretching-2"]
			}, {
				title: "ti ti-strikethrough",
				searchTerms: ["strikethrough"]
			}, {
				title: "ti ti-submarine",
				searchTerms: ["submarine"]
			}, {
				title: "ti ti-subscript",
				searchTerms: ["subscript"]
			}, {
				title: "ti ti-subtask",
				searchTerms: ["subtask"]
			}, {
				title: "ti ti-sum",
				searchTerms: ["sum"]
			}, {
				title: "ti ti-sum-off",
				searchTerms: ["sum-off"]
			}, {
				title: "ti ti-sun",
				searchTerms: ["sun"]
			}, {
				title: "ti ti-sun-electricity",
				searchTerms: ["sun-electricity"]
			}, {
				title: "ti ti-sun-filled",
				searchTerms: ["sun-filled"]
			}, {
				title: "ti ti-sun-high",
				searchTerms: ["sun-high"]
			}, {
				title: "ti ti-sun-low",
				searchTerms: ["sun-low"]
			}, {
				title: "ti ti-sun-moon",
				searchTerms: ["sun-moon"]
			}, {
				title: "ti ti-sun-off",
				searchTerms: ["sun-off"]
			}, {
				title: "ti ti-sun-wind",
				searchTerms: ["sun-wind"]
			}, {
				title: "ti ti-sunglasses",
				searchTerms: ["sunglasses"]
			}, {
				title: "ti ti-sunglasses-filled",
				searchTerms: ["sunglasses-filled"]
			}, {
				title: "ti ti-sunrise",
				searchTerms: ["sunrise"]
			}, {
				title: "ti ti-sunset",
				searchTerms: ["sunset"]
			}, {
				title: "ti ti-sunset-2",
				searchTerms: ["sunset-2"]
			}, {
				title: "ti ti-superscript",
				searchTerms: ["superscript"]
			}, {
				title: "ti ti-svg",
				searchTerms: ["svg"]
			}, {
				title: "ti ti-swimming",
				searchTerms: ["swimming"]
			}, {
				title: "ti ti-swipe",
				searchTerms: ["swipe"]
			}, {
				title: "ti ti-swipe-down",
				searchTerms: ["swipe-down"]
			}, {
				title: "ti ti-swipe-left",
				searchTerms: ["swipe-left"]
			}, {
				title: "ti ti-swipe-right",
				searchTerms: ["swipe-right"]
			}, {
				title: "ti ti-swipe-up",
				searchTerms: ["swipe-up"]
			}, {
				title: "ti ti-switch",
				searchTerms: ["switch"]
			}, {
				title: "ti ti-switch-2",
				searchTerms: ["switch-2"]
			}, {
				title: "ti ti-switch-3",
				searchTerms: ["switch-3"]
			}, {
				title: "ti ti-switch-horizontal",
				searchTerms: ["switch-horizontal"]
			}, {
				title: "ti ti-switch-vertical",
				searchTerms: ["switch-vertical"]
			}, {
				title: "ti ti-sword",
				searchTerms: ["sword"]
			}, {
				title: "ti ti-sword-off",
				searchTerms: ["sword-off"]
			}, {
				title: "ti ti-swords",
				searchTerms: ["swords"]
			}, {
				title: "ti ti-table",
				searchTerms: ["table"]
			}, {
				title: "ti ti-table-alias",
				searchTerms: ["table-alias"]
			}, {
				title: "ti ti-table-column",
				searchTerms: ["table-column"]
			}, {
				title: "ti ti-table-down",
				searchTerms: ["table-down"]
			}, {
				title: "ti ti-table-export",
				searchTerms: ["table-export"]
			}, {
				title: "ti ti-table-filled",
				searchTerms: ["table-filled"]
			}, {
				title: "ti ti-table-heart",
				searchTerms: ["table-heart"]
			}, {
				title: "ti ti-table-import",
				searchTerms: ["table-import"]
			}, {
				title: "ti ti-table-minus",
				searchTerms: ["table-minus"]
			}, {
				title: "ti ti-table-off",
				searchTerms: ["table-off"]
			}, {
				title: "ti ti-table-options",
				searchTerms: ["table-options"]
			}, {
				title: "ti ti-table-plus",
				searchTerms: ["table-plus"]
			}, {
				title: "ti ti-table-row",
				searchTerms: ["table-row"]
			}, {
				title: "ti ti-table-share",
				searchTerms: ["table-share"]
			}, {
				title: "ti ti-table-shortcut",
				searchTerms: ["table-shortcut"]
			}, {
				title: "ti ti-tag",
				searchTerms: ["tag"]
			}, {
				title: "ti ti-tag-off",
				searchTerms: ["tag-off"]
			}, {
				title: "ti ti-tag-starred",
				searchTerms: ["tag-starred"]
			}, {
				title: "ti ti-tags",
				searchTerms: ["tags"]
			}, {
				title: "ti ti-tags-off",
				searchTerms: ["tags-off"]
			}, {
				title: "ti ti-tallymark-1",
				searchTerms: ["tallymark-1"]
			}, {
				title: "ti ti-tallymark-2",
				searchTerms: ["tallymark-2"]
			}, {
				title: "ti ti-tallymark-3",
				searchTerms: ["tallymark-3"]
			}, {
				title: "ti ti-tallymark-4",
				searchTerms: ["tallymark-4"]
			}, {
				title: "ti ti-tallymarks",
				searchTerms: ["tallymarks"]
			}, {
				title: "ti ti-tank",
				searchTerms: ["tank"]
			}, {
				title: "ti ti-target",
				searchTerms: ["target"]
			}, {
				title: "ti ti-target-arrow",
				searchTerms: ["target-arrow"]
			}, {
				title: "ti ti-target-off",
				searchTerms: ["target-off"]
			}, {
				title: "ti ti-teapot",
				searchTerms: ["teapot"]
			}, {
				title: "ti ti-telescope",
				searchTerms: ["telescope"]
			}, {
				title: "ti ti-telescope-off",
				searchTerms: ["telescope-off"]
			}, {
				title: "ti ti-temperature",
				searchTerms: ["temperature"]
			}, {
				title: "ti ti-temperature-celsius",
				searchTerms: ["temperature-celsius"]
			}, {
				title: "ti ti-temperature-fahrenheit",
				searchTerms: ["temperature-fahrenheit"]
			}, {
				title: "ti ti-temperature-minus",
				searchTerms: ["temperature-minus"]
			}, {
				title: "ti ti-temperature-off",
				searchTerms: ["temperature-off"]
			}, {
				title: "ti ti-temperature-plus",
				searchTerms: ["temperature-plus"]
			}, {
				title: "ti ti-temperature-snow",
				searchTerms: ["temperature-snow"]
			}, {
				title: "ti ti-temperature-sun",
				searchTerms: ["temperature-sun"]
			}, {
				title: "ti ti-template",
				searchTerms: ["template"]
			}, {
				title: "ti ti-template-off",
				searchTerms: ["template-off"]
			}, {
				title: "ti ti-tent",
				searchTerms: ["tent"]
			}, {
				title: "ti ti-tent-off",
				searchTerms: ["tent-off"]
			}, {
				title: "ti ti-terminal",
				searchTerms: ["terminal"]
			}, {
				title: "ti ti-terminal-2",
				searchTerms: ["terminal-2"]
			}, {
				title: "ti ti-test-pipe",
				searchTerms: ["test-pipe"]
			}, {
				title: "ti ti-test-pipe-2",
				searchTerms: ["test-pipe-2"]
			}, {
				title: "ti ti-test-pipe-off",
				searchTerms: ["test-pipe-off"]
			}, {
				title: "ti ti-tex",
				searchTerms: ["tex"]
			}, {
				title: "ti ti-text-caption",
				searchTerms: ["text-caption"]
			}, {
				title: "ti ti-text-color",
				searchTerms: ["text-color"]
			}, {
				title: "ti ti-text-decrease",
				searchTerms: ["text-decrease"]
			}, {
				title: "ti ti-text-direction-ltr",
				searchTerms: ["text-direction-ltr"]
			}, {
				title: "ti ti-text-direction-rtl",
				searchTerms: ["text-direction-rtl"]
			}, {
				title: "ti ti-text-grammar",
				searchTerms: ["text-grammar"]
			}, {
				title: "ti ti-text-increase",
				searchTerms: ["text-increase"]
			}, {
				title: "ti ti-text-orientation",
				searchTerms: ["text-orientation"]
			}, {
				title: "ti ti-text-plus",
				searchTerms: ["text-plus"]
			}, {
				title: "ti ti-text-recognition",
				searchTerms: ["text-recognition"]
			}, {
				title: "ti ti-text-resize",
				searchTerms: ["text-resize"]
			}, {
				title: "ti ti-text-scan-2",
				searchTerms: ["text-scan-2"]
			}, {
				title: "ti ti-text-size",
				searchTerms: ["text-size"]
			}, {
				title: "ti ti-text-spellcheck",
				searchTerms: ["text-spellcheck"]
			}, {
				title: "ti ti-text-wrap",
				searchTerms: ["text-wrap"]
			}, {
				title: "ti ti-text-wrap-column",
				searchTerms: ["text-wrap-column"]
			}, {
				title: "ti ti-text-wrap-disabled",
				searchTerms: ["text-wrap-disabled"]
			}, {
				title: "ti ti-texture",
				searchTerms: ["texture"]
			}, {
				title: "ti ti-theater",
				searchTerms: ["theater"]
			}, {
				title: "ti ti-thermometer",
				searchTerms: ["thermometer"]
			}, {
				title: "ti ti-thumb-down",
				searchTerms: ["thumb-down"]
			}, {
				title: "ti ti-thumb-down-filled",
				searchTerms: ["thumb-down-filled"]
			}, {
				title: "ti ti-thumb-down-off",
				searchTerms: ["thumb-down-off"]
			}, {
				title: "ti ti-thumb-up",
				searchTerms: ["thumb-up"]
			}, {
				title: "ti ti-thumb-up-filled",
				searchTerms: ["thumb-up-filled"]
			}, {
				title: "ti ti-thumb-up-off",
				searchTerms: ["thumb-up-off"]
			}, {
				title: "ti ti-tic-tac",
				searchTerms: ["tic-tac"]
			}, {
				title: "ti ti-ticket",
				searchTerms: ["ticket"]
			}, {
				title: "ti ti-ticket-off",
				searchTerms: ["ticket-off"]
			}, {
				title: "ti ti-tie",
				searchTerms: ["tie"]
			}, {
				title: "ti ti-tilde",
				searchTerms: ["tilde"]
			}, {
				title: "ti ti-tilt-shift",
				searchTerms: ["tilt-shift"]
			}, {
				title: "ti ti-tilt-shift-filled",
				searchTerms: ["tilt-shift-filled"]
			}, {
				title: "ti ti-tilt-shift-off",
				searchTerms: ["tilt-shift-off"]
			}, {
				title: "ti ti-time-duration-0",
				searchTerms: ["time-duration-0"]
			}, {
				title: "ti ti-time-duration-10",
				searchTerms: ["time-duration-10"]
			}, {
				title: "ti ti-time-duration-15",
				searchTerms: ["time-duration-15"]
			}, {
				title: "ti ti-time-duration-30",
				searchTerms: ["time-duration-30"]
			}, {
				title: "ti ti-time-duration-45",
				searchTerms: ["time-duration-45"]
			}, {
				title: "ti ti-time-duration-5",
				searchTerms: ["time-duration-5"]
			}, {
				title: "ti ti-time-duration-60",
				searchTerms: ["time-duration-60"]
			}, {
				title: "ti ti-time-duration-90",
				searchTerms: ["time-duration-90"]
			}, {
				title: "ti ti-time-duration-off",
				searchTerms: ["time-duration-off"]
			}, {
				title: "ti ti-timeline",
				searchTerms: ["timeline"]
			}, {
				title: "ti ti-timeline-event",
				searchTerms: ["timeline-event"]
			}, {
				title: "ti ti-timeline-event-exclamation",
				searchTerms: ["timeline-event-exclamation"]
			}, {
				title: "ti ti-timeline-event-filled",
				searchTerms: ["timeline-event-filled"]
			}, {
				title: "ti ti-timeline-event-minus",
				searchTerms: ["timeline-event-minus"]
			}, {
				title: "ti ti-timeline-event-plus",
				searchTerms: ["timeline-event-plus"]
			}, {
				title: "ti ti-timeline-event-text",
				searchTerms: ["timeline-event-text"]
			}, {
				title: "ti ti-timeline-event-x",
				searchTerms: ["timeline-event-x"]
			}, {
				title: "ti ti-tir",
				searchTerms: ["tir"]
			}, {
				title: "ti ti-toggle-left",
				searchTerms: ["toggle-left"]
			}, {
				title: "ti ti-toggle-left-filled",
				searchTerms: ["toggle-left-filled"]
			}, {
				title: "ti ti-toggle-right",
				searchTerms: ["toggle-right"]
			}, {
				title: "ti ti-toggle-right-filled",
				searchTerms: ["toggle-right-filled"]
			}, {
				title: "ti ti-toilet-paper",
				searchTerms: ["toilet-paper"]
			}, {
				title: "ti ti-toilet-paper-off",
				searchTerms: ["toilet-paper-off"]
			}, {
				title: "ti ti-toml",
				searchTerms: ["toml"]
			}, {
				title: "ti ti-tool",
				searchTerms: ["tool"]
			}, {
				title: "ti ti-tools",
				searchTerms: ["tools"]
			}, {
				title: "ti ti-tools-kitchen",
				searchTerms: ["tools-kitchen"]
			}, {
				title: "ti ti-tools-kitchen-2",
				searchTerms: ["tools-kitchen-2"]
			}, {
				title: "ti ti-tools-kitchen-2-off",
				searchTerms: ["tools-kitchen-2-off"]
			}, {
				title: "ti ti-tools-kitchen-3",
				searchTerms: ["tools-kitchen-3"]
			}, {
				title: "ti ti-tools-kitchen-off",
				searchTerms: ["tools-kitchen-off"]
			}, {
				title: "ti ti-tools-off",
				searchTerms: ["tools-off"]
			}, {
				title: "ti ti-tooltip",
				searchTerms: ["tooltip"]
			}, {
				title: "ti ti-topology-bus",
				searchTerms: ["topology-bus"]
			}, {
				title: "ti ti-topology-complex",
				searchTerms: ["topology-complex"]
			}, {
				title: "ti ti-topology-full",
				searchTerms: ["topology-full"]
			}, {
				title: "ti ti-topology-full-hierarchy",
				searchTerms: ["topology-full-hierarchy"]
			}, {
				title: "ti ti-topology-ring",
				searchTerms: ["topology-ring"]
			}, {
				title: "ti ti-topology-ring-2",
				searchTerms: ["topology-ring-2"]
			}, {
				title: "ti ti-topology-ring-3",
				searchTerms: ["topology-ring-3"]
			}, {
				title: "ti ti-topology-star",
				searchTerms: ["topology-star"]
			}, {
				title: "ti ti-topology-star-2",
				searchTerms: ["topology-star-2"]
			}, {
				title: "ti ti-topology-star-3",
				searchTerms: ["topology-star-3"]
			}, {
				title: "ti ti-topology-star-ring",
				searchTerms: ["topology-star-ring"]
			}, {
				title: "ti ti-topology-star-ring-2",
				searchTerms: ["topology-star-ring-2"]
			}, {
				title: "ti ti-topology-star-ring-3",
				searchTerms: ["topology-star-ring-3"]
			}, {
				title: "ti ti-torii",
				searchTerms: ["torii"]
			}, {
				title: "ti ti-tornado",
				searchTerms: ["tornado"]
			}, {
				title: "ti ti-tournament",
				searchTerms: ["tournament"]
			}, {
				title: "ti ti-tower",
				searchTerms: ["tower"]
			}, {
				title: "ti ti-tower-off",
				searchTerms: ["tower-off"]
			}, {
				title: "ti ti-track",
				searchTerms: ["track"]
			}, {
				title: "ti ti-tractor",
				searchTerms: ["tractor"]
			}, {
				title: "ti ti-trademark",
				searchTerms: ["trademark"]
			}, {
				title: "ti ti-traffic-cone",
				searchTerms: ["traffic-cone"]
			}, {
				title: "ti ti-traffic-cone-off",
				searchTerms: ["traffic-cone-off"]
			}, {
				title: "ti ti-traffic-lights",
				searchTerms: ["traffic-lights"]
			}, {
				title: "ti ti-traffic-lights-off",
				searchTerms: ["traffic-lights-off"]
			}, {
				title: "ti ti-train",
				searchTerms: ["train"]
			}, {
				title: "ti ti-transaction-bitcoin",
				searchTerms: ["transaction-bitcoin"]
			}, {
				title: "ti ti-transaction-dollar",
				searchTerms: ["transaction-dollar"]
			}, {
				title: "ti ti-transaction-euro",
				searchTerms: ["transaction-euro"]
			}, {
				title: "ti ti-transaction-pound",
				searchTerms: ["transaction-pound"]
			}, {
				title: "ti ti-transaction-rupee",
				searchTerms: ["transaction-rupee"]
			}, {
				title: "ti ti-transaction-yen",
				searchTerms: ["transaction-yen"]
			}, {
				title: "ti ti-transaction-yuan",
				searchTerms: ["transaction-yuan"]
			}, {
				title: "ti ti-transfer",
				searchTerms: ["transfer"]
			}, {
				title: "ti ti-transfer-in",
				searchTerms: ["transfer-in"]
			}, {
				title: "ti ti-transfer-out",
				searchTerms: ["transfer-out"]
			}, {
				title: "ti ti-transfer-vertical",
				searchTerms: ["transfer-vertical"]
			}, {
				title: "ti ti-transform",
				searchTerms: ["transform"]
			}, {
				title: "ti ti-transform-filled",
				searchTerms: ["transform-filled"]
			}, {
				title: "ti ti-transform-point",
				searchTerms: ["transform-point"]
			}, {
				title: "ti ti-transform-point-bottom-left",
				searchTerms: ["transform-point-bottom-left"]
			}, {
				title: "ti ti-transform-point-bottom-right",
				searchTerms: ["transform-point-bottom-right"]
			}, {
				title: "ti ti-transform-point-top-left",
				searchTerms: ["transform-point-top-left"]
			}, {
				title: "ti ti-transform-point-top-right",
				searchTerms: ["transform-point-top-right"]
			}, {
				title: "ti ti-transition-bottom",
				searchTerms: ["transition-bottom"]
			}, {
				title: "ti ti-transition-bottom-filled",
				searchTerms: ["transition-bottom-filled"]
			}, {
				title: "ti ti-transition-left",
				searchTerms: ["transition-left"]
			}, {
				title: "ti ti-transition-left-filled",
				searchTerms: ["transition-left-filled"]
			}, {
				title: "ti ti-transition-right",
				searchTerms: ["transition-right"]
			}, {
				title: "ti ti-transition-right-filled",
				searchTerms: ["transition-right-filled"]
			}, {
				title: "ti ti-transition-top",
				searchTerms: ["transition-top"]
			}, {
				title: "ti ti-transition-top-filled",
				searchTerms: ["transition-top-filled"]
			}, {
				title: "ti ti-trash",
				searchTerms: ["trash"]
			}, {
				title: "ti ti-trash-filled",
				searchTerms: ["trash-filled"]
			}, {
				title: "ti ti-trash-off",
				searchTerms: ["trash-off"]
			}, {
				title: "ti ti-trash-x",
				searchTerms: ["trash-x"]
			}, {
				title: "ti ti-trash-x-filled",
				searchTerms: ["trash-x-filled"]
			}, {
				title: "ti ti-treadmill",
				searchTerms: ["treadmill"]
			}, {
				title: "ti ti-tree",
				searchTerms: ["tree"]
			}, {
				title: "ti ti-trees",
				searchTerms: ["trees"]
			}, {
				title: "ti ti-trekking",
				searchTerms: ["trekking"]
			}, {
				title: "ti ti-trending-down",
				searchTerms: ["trending-down"]
			}, {
				title: "ti ti-trending-down-2",
				searchTerms: ["trending-down-2"]
			}, {
				title: "ti ti-trending-down-3",
				searchTerms: ["trending-down-3"]
			}, {
				title: "ti ti-trending-up",
				searchTerms: ["trending-up"]
			}, {
				title: "ti ti-trending-up-2",
				searchTerms: ["trending-up-2"]
			}, {
				title: "ti ti-trending-up-3",
				searchTerms: ["trending-up-3"]
			}, {
				title: "ti ti-triangle",
				searchTerms: ["triangle"]
			}, {
				title: "ti ti-triangle-filled",
				searchTerms: ["triangle-filled"]
			}, {
				title: "ti ti-triangle-inverted",
				searchTerms: ["triangle-inverted"]
			}, {
				title: "ti ti-triangle-inverted-filled",
				searchTerms: ["triangle-inverted-filled"]
			}, {
				title: "ti ti-triangle-minus",
				searchTerms: ["triangle-minus"]
			}, {
				title: "ti ti-triangle-minus-2",
				searchTerms: ["triangle-minus-2"]
			}, {
				title: "ti ti-triangle-off",
				searchTerms: ["triangle-off"]
			}, {
				title: "ti ti-triangle-plus",
				searchTerms: ["triangle-plus"]
			}, {
				title: "ti ti-triangle-plus-2",
				searchTerms: ["triangle-plus-2"]
			}, {
				title: "ti ti-triangle-square-circle",
				searchTerms: ["triangle-square-circle"]
			}, {
				title: "ti ti-triangle-square-circle-filled",
				searchTerms: ["triangle-square-circle-filled"]
			}, {
				title: "ti ti-triangles",
				searchTerms: ["triangles"]
			}, {
				title: "ti ti-trident",
				searchTerms: ["trident"]
			}, {
				title: "ti ti-trolley",
				searchTerms: ["trolley"]
			}, {
				title: "ti ti-trophy",
				searchTerms: ["trophy"]
			}, {
				title: "ti ti-trophy-filled",
				searchTerms: ["trophy-filled"]
			}, {
				title: "ti ti-trophy-off",
				searchTerms: ["trophy-off"]
			}, {
				title: "ti ti-trowel",
				searchTerms: ["trowel"]
			}, {
				title: "ti ti-truck",
				searchTerms: ["truck"]
			}, {
				title: "ti ti-truck-delivery",
				searchTerms: ["truck-delivery"]
			}, {
				title: "ti ti-truck-loading",
				searchTerms: ["truck-loading"]
			}, {
				title: "ti ti-truck-off",
				searchTerms: ["truck-off"]
			}, {
				title: "ti ti-truck-return",
				searchTerms: ["truck-return"]
			}, {
				title: "ti ti-txt",
				searchTerms: ["txt"]
			}, {
				title: "ti ti-typeface",
				searchTerms: ["typeface"]
			}, {
				title: "ti ti-typography",
				searchTerms: ["typography"]
			}, {
				title: "ti ti-typography-off",
				searchTerms: ["typography-off"]
			}, {
				title: "ti ti-u-turn-left",
				searchTerms: ["u-turn-left"]
			}, {
				title: "ti ti-u-turn-right",
				searchTerms: ["u-turn-right"]
			}, {
				title: "ti ti-ufo",
				searchTerms: ["ufo"]
			}, {
				title: "ti ti-ufo-off",
				searchTerms: ["ufo-off"]
			}, {
				title: "ti ti-umbrella",
				searchTerms: ["umbrella"]
			}, {
				title: "ti ti-umbrella-filled",
				searchTerms: ["umbrella-filled"]
			}, {
				title: "ti ti-umbrella-off",
				searchTerms: ["umbrella-off"]
			}, {
				title: "ti ti-underline",
				searchTerms: ["underline"]
			}, {
				title: "ti ti-universe",
				searchTerms: ["universe"]
			}, {
				title: "ti ti-unlink",
				searchTerms: ["unlink"]
			}, {
				title: "ti ti-upload",
				searchTerms: ["upload"]
			}, {
				title: "ti ti-urgent",
				searchTerms: ["urgent"]
			}, {
				title: "ti ti-usb",
				searchTerms: ["usb"]
			}, {
				title: "ti ti-user",
				searchTerms: ["user"]
			}, {
				title: "ti ti-user-bolt",
				searchTerms: ["user-bolt"]
			}, {
				title: "ti ti-user-cancel",
				searchTerms: ["user-cancel"]
			}, {
				title: "ti ti-user-check",
				searchTerms: ["user-check"]
			}, {
				title: "ti ti-user-circle",
				searchTerms: ["user-circle"]
			}, {
				title: "ti ti-user-code",
				searchTerms: ["user-code"]
			}, {
				title: "ti ti-user-cog",
				searchTerms: ["user-cog"]
			}, {
				title: "ti ti-user-dollar",
				searchTerms: ["user-dollar"]
			}, {
				title: "ti ti-user-down",
				searchTerms: ["user-down"]
			}, {
				title: "ti ti-user-edit",
				searchTerms: ["user-edit"]
			}, {
				title: "ti ti-user-exclamation",
				searchTerms: ["user-exclamation"]
			}, {
				title: "ti ti-user-filled",
				searchTerms: ["user-filled"]
			}, {
				title: "ti ti-user-heart",
				searchTerms: ["user-heart"]
			}, {
				title: "ti ti-user-hexagon",
				searchTerms: ["user-hexagon"]
			}, {
				title: "ti ti-user-minus",
				searchTerms: ["user-minus"]
			}, {
				title: "ti ti-user-off",
				searchTerms: ["user-off"]
			}, {
				title: "ti ti-user-pause",
				searchTerms: ["user-pause"]
			}, {
				title: "ti ti-user-pentagon",
				searchTerms: ["user-pentagon"]
			}, {
				title: "ti ti-user-pin",
				searchTerms: ["user-pin"]
			}, {
				title: "ti ti-user-plus",
				searchTerms: ["user-plus"]
			}, {
				title: "ti ti-user-question",
				searchTerms: ["user-question"]
			}, {
				title: "ti ti-user-scan",
				searchTerms: ["user-scan"]
			}, {
				title: "ti ti-user-screen",
				searchTerms: ["user-screen"]
			}, {
				title: "ti ti-user-search",
				searchTerms: ["user-search"]
			}, {
				title: "ti ti-user-share",
				searchTerms: ["user-share"]
			}, {
				title: "ti ti-user-shield",
				searchTerms: ["user-shield"]
			}, {
				title: "ti ti-user-square",
				searchTerms: ["user-square"]
			}, {
				title: "ti ti-user-square-rounded",
				searchTerms: ["user-square-rounded"]
			}, {
				title: "ti ti-user-star",
				searchTerms: ["user-star"]
			}, {
				title: "ti ti-user-up",
				searchTerms: ["user-up"]
			}, {
				title: "ti ti-user-x",
				searchTerms: ["user-x"]
			}, {
				title: "ti ti-users",
				searchTerms: ["users"]
			}, {
				title: "ti ti-users-group",
				searchTerms: ["users-group"]
			}, {
				title: "ti ti-users-minus",
				searchTerms: ["users-minus"]
			}, {
				title: "ti ti-users-plus",
				searchTerms: ["users-plus"]
			}, {
				title: "ti ti-uv-index",
				searchTerms: ["uv-index"]
			}, {
				title: "ti ti-ux-circle",
				searchTerms: ["ux-circle"]
			}, {
				title: "ti ti-vaccine",
				searchTerms: ["vaccine"]
			}, {
				title: "ti ti-vaccine-bottle",
				searchTerms: ["vaccine-bottle"]
			}, {
				title: "ti ti-vaccine-bottle-off",
				searchTerms: ["vaccine-bottle-off"]
			}, {
				title: "ti ti-vaccine-off",
				searchTerms: ["vaccine-off"]
			}, {
				title: "ti ti-vacuum-cleaner",
				searchTerms: ["vacuum-cleaner"]
			}, {
				title: "ti ti-variable",
				searchTerms: ["variable"]
			}, {
				title: "ti ti-variable-minus",
				searchTerms: ["variable-minus"]
			}, {
				title: "ti ti-variable-off",
				searchTerms: ["variable-off"]
			}, {
				title: "ti ti-variable-plus",
				searchTerms: ["variable-plus"]
			}, {
				title: "ti ti-vector",
				searchTerms: ["vector"]
			}, {
				title: "ti ti-vector-bezier",
				searchTerms: ["vector-bezier"]
			}, {
				title: "ti ti-vector-bezier-2",
				searchTerms: ["vector-bezier-2"]
			}, {
				title: "ti ti-vector-bezier-arc",
				searchTerms: ["vector-bezier-arc"]
			}, {
				title: "ti ti-vector-bezier-circle",
				searchTerms: ["vector-bezier-circle"]
			}, {
				title: "ti ti-vector-off",
				searchTerms: ["vector-off"]
			}, {
				title: "ti ti-vector-spline",
				searchTerms: ["vector-spline"]
			}, {
				title: "ti ti-vector-triangle",
				searchTerms: ["vector-triangle"]
			}, {
				title: "ti ti-vector-triangle-off",
				searchTerms: ["vector-triangle-off"]
			}, {
				title: "ti ti-venus",
				searchTerms: ["venus"]
			}, {
				title: "ti ti-versions",
				searchTerms: ["versions"]
			}, {
				title: "ti ti-versions-filled",
				searchTerms: ["versions-filled"]
			}, {
				title: "ti ti-versions-off",
				searchTerms: ["versions-off"]
			}, {
				title: "ti ti-video",
				searchTerms: ["video"]
			}, {
				title: "ti ti-video-minus",
				searchTerms: ["video-minus"]
			}, {
				title: "ti ti-video-off",
				searchTerms: ["video-off"]
			}, {
				title: "ti ti-video-plus",
				searchTerms: ["video-plus"]
			}, {
				title: "ti ti-view-360",
				searchTerms: ["view-360"]
			}, {
				title: "ti ti-view-360-arrow",
				searchTerms: ["view-360-arrow"]
			}, {
				title: "ti ti-view-360-number",
				searchTerms: ["view-360-number"]
			}, {
				title: "ti ti-view-360-off",
				searchTerms: ["view-360-off"]
			}, {
				title: "ti ti-viewfinder",
				searchTerms: ["viewfinder"]
			}, {
				title: "ti ti-viewfinder-off",
				searchTerms: ["viewfinder-off"]
			}, {
				title: "ti ti-viewport-narrow",
				searchTerms: ["viewport-narrow"]
			}, {
				title: "ti ti-viewport-wide",
				searchTerms: ["viewport-wide"]
			}, {
				title: "ti ti-vinyl",
				searchTerms: ["vinyl"]
			}, {
				title: "ti ti-vip",
				searchTerms: ["vip"]
			}, {
				title: "ti ti-vip-off",
				searchTerms: ["vip-off"]
			}, {
				title: "ti ti-virus",
				searchTerms: ["virus"]
			}, {
				title: "ti ti-virus-off",
				searchTerms: ["virus-off"]
			}, {
				title: "ti ti-virus-search",
				searchTerms: ["virus-search"]
			}, {
				title: "ti ti-vocabulary",
				searchTerms: ["vocabulary"]
			}, {
				title: "ti ti-vocabulary-off",
				searchTerms: ["vocabulary-off"]
			}, {
				title: "ti ti-volcano",
				searchTerms: ["volcano"]
			}, {
				title: "ti ti-volume",
				searchTerms: ["volume"]
			}, {
				title: "ti ti-volume-2",
				searchTerms: ["volume-2"]
			}, {
				title: "ti ti-volume-3",
				searchTerms: ["volume-3"]
			}, {
				title: "ti ti-volume-off",
				searchTerms: ["volume-off"]
			}, {
				title: "ti ti-vs",
				searchTerms: ["vs"]
			}, {
				title: "ti ti-walk",
				searchTerms: ["walk"]
			}, {
				title: "ti ti-wall",
				searchTerms: ["wall"]
			}, {
				title: "ti ti-wall-off",
				searchTerms: ["wall-off"]
			}, {
				title: "ti ti-wallet",
				searchTerms: ["wallet"]
			}, {
				title: "ti ti-wallet-off",
				searchTerms: ["wallet-off"]
			}, {
				title: "ti ti-wallpaper",
				searchTerms: ["wallpaper"]
			}, {
				title: "ti ti-wallpaper-off",
				searchTerms: ["wallpaper-off"]
			}, {
				title: "ti ti-wand",
				searchTerms: ["wand"]
			}, {
				title: "ti ti-wand-off",
				searchTerms: ["wand-off"]
			}, {
				title: "ti ti-wash",
				searchTerms: ["wash"]
			}, {
				title: "ti ti-wash-dry",
				searchTerms: ["wash-dry"]
			}, {
				title: "ti ti-wash-dry-1",
				searchTerms: ["wash-dry-1"]
			}, {
				title: "ti ti-wash-dry-2",
				searchTerms: ["wash-dry-2"]
			}, {
				title: "ti ti-wash-dry-3",
				searchTerms: ["wash-dry-3"]
			}, {
				title: "ti ti-wash-dry-a",
				searchTerms: ["wash-dry-a"]
			}, {
				title: "ti ti-wash-dry-dip",
				searchTerms: ["wash-dry-dip"]
			}, {
				title: "ti ti-wash-dry-f",
				searchTerms: ["wash-dry-f"]
			}, {
				title: "ti ti-wash-dry-flat",
				searchTerms: ["wash-dry-flat"]
			}, {
				title: "ti ti-wash-dry-hang",
				searchTerms: ["wash-dry-hang"]
			}, {
				title: "ti ti-wash-dry-off",
				searchTerms: ["wash-dry-off"]
			}, {
				title: "ti ti-wash-dry-p",
				searchTerms: ["wash-dry-p"]
			}, {
				title: "ti ti-wash-dry-shade",
				searchTerms: ["wash-dry-shade"]
			}, {
				title: "ti ti-wash-dry-w",
				searchTerms: ["wash-dry-w"]
			}, {
				title: "ti ti-wash-dryclean",
				searchTerms: ["wash-dryclean"]
			}, {
				title: "ti ti-wash-dryclean-off",
				searchTerms: ["wash-dryclean-off"]
			}, {
				title: "ti ti-wash-eco",
				searchTerms: ["wash-eco"]
			}, {
				title: "ti ti-wash-gentle",
				searchTerms: ["wash-gentle"]
			}, {
				title: "ti ti-wash-hand",
				searchTerms: ["wash-hand"]
			}, {
				title: "ti ti-wash-machine",
				searchTerms: ["wash-machine"]
			}, {
				title: "ti ti-wash-off",
				searchTerms: ["wash-off"]
			}, {
				title: "ti ti-wash-press",
				searchTerms: ["wash-press"]
			}, {
				title: "ti ti-wash-temperature-1",
				searchTerms: ["wash-temperature-1"]
			}, {
				title: "ti ti-wash-temperature-2",
				searchTerms: ["wash-temperature-2"]
			}, {
				title: "ti ti-wash-temperature-3",
				searchTerms: ["wash-temperature-3"]
			}, {
				title: "ti ti-wash-temperature-4",
				searchTerms: ["wash-temperature-4"]
			}, {
				title: "ti ti-wash-temperature-5",
				searchTerms: ["wash-temperature-5"]
			}, {
				title: "ti ti-wash-temperature-6",
				searchTerms: ["wash-temperature-6"]
			}, {
				title: "ti ti-wash-tumble-dry",
				searchTerms: ["wash-tumble-dry"]
			}, {
				title: "ti ti-wash-tumble-off",
				searchTerms: ["wash-tumble-off"]
			}, {
				title: "ti ti-waterpolo",
				searchTerms: ["waterpolo"]
			}, {
				title: "ti ti-wave-saw-tool",
				searchTerms: ["wave-saw-tool"]
			}, {
				title: "ti ti-wave-sine",
				searchTerms: ["wave-sine"]
			}, {
				title: "ti ti-wave-square",
				searchTerms: ["wave-square"]
			}, {
				title: "ti ti-waves-electricity",
				searchTerms: ["waves-electricity"]
			}, {
				title: "ti ti-webhook",
				searchTerms: ["webhook"]
			}, {
				title: "ti ti-webhook-off",
				searchTerms: ["webhook-off"]
			}, {
				title: "ti ti-weight",
				searchTerms: ["weight"]
			}, {
				title: "ti ti-wheel",
				searchTerms: ["wheel"]
			}, {
				title: "ti ti-wheelchair",
				searchTerms: ["wheelchair"]
			}, {
				title: "ti ti-wheelchair-off",
				searchTerms: ["wheelchair-off"]
			}, {
				title: "ti ti-whirl",
				searchTerms: ["whirl"]
			}, {
				title: "ti ti-wifi",
				searchTerms: ["wifi"]
			}, {
				title: "ti ti-wifi-0",
				searchTerms: ["wifi-0"]
			}, {
				title: "ti ti-wifi-1",
				searchTerms: ["wifi-1"]
			}, {
				title: "ti ti-wifi-2",
				searchTerms: ["wifi-2"]
			}, {
				title: "ti ti-wifi-off",
				searchTerms: ["wifi-off"]
			}, {
				title: "ti ti-wind",
				searchTerms: ["wind"]
			}, {
				title: "ti ti-wind-electricity",
				searchTerms: ["wind-electricity"]
			}, {
				title: "ti ti-wind-off",
				searchTerms: ["wind-off"]
			}, {
				title: "ti ti-windmill",
				searchTerms: ["windmill"]
			}, {
				title: "ti ti-windmill-filled",
				searchTerms: ["windmill-filled"]
			}, {
				title: "ti ti-windmill-off",
				searchTerms: ["windmill-off"]
			}, {
				title: "ti ti-window",
				searchTerms: ["window"]
			}, {
				title: "ti ti-window-maximize",
				searchTerms: ["window-maximize"]
			}, {
				title: "ti ti-window-minimize",
				searchTerms: ["window-minimize"]
			}, {
				title: "ti ti-window-off",
				searchTerms: ["window-off"]
			}, {
				title: "ti ti-windsock",
				searchTerms: ["windsock"]
			}, {
				title: "ti ti-wiper",
				searchTerms: ["wiper"]
			}, {
				title: "ti ti-wiper-wash",
				searchTerms: ["wiper-wash"]
			}, {
				title: "ti ti-woman",
				searchTerms: ["woman"]
			}, {
				title: "ti ti-woman-filled",
				searchTerms: ["woman-filled"]
			}, {
				title: "ti ti-wood",
				searchTerms: ["wood"]
			}, {
				title: "ti ti-world",
				searchTerms: ["world"]
			}, {
				title: "ti ti-world-bolt",
				searchTerms: ["world-bolt"]
			}, {
				title: "ti ti-world-cancel",
				searchTerms: ["world-cancel"]
			}, {
				title: "ti ti-world-check",
				searchTerms: ["world-check"]
			}, {
				title: "ti ti-world-code",
				searchTerms: ["world-code"]
			}, {
				title: "ti ti-world-cog",
				searchTerms: ["world-cog"]
			}, {
				title: "ti ti-world-dollar",
				searchTerms: ["world-dollar"]
			}, {
				title: "ti ti-world-down",
				searchTerms: ["world-down"]
			}, {
				title: "ti ti-world-download",
				searchTerms: ["world-download"]
			}, {
				title: "ti ti-world-exclamation",
				searchTerms: ["world-exclamation"]
			}, {
				title: "ti ti-world-heart",
				searchTerms: ["world-heart"]
			}, {
				title: "ti ti-world-latitude",
				searchTerms: ["world-latitude"]
			}, {
				title: "ti ti-world-longitude",
				searchTerms: ["world-longitude"]
			}, {
				title: "ti ti-world-minus",
				searchTerms: ["world-minus"]
			}, {
				title: "ti ti-world-off",
				searchTerms: ["world-off"]
			}, {
				title: "ti ti-world-pause",
				searchTerms: ["world-pause"]
			}, {
				title: "ti ti-world-pin",
				searchTerms: ["world-pin"]
			}, {
				title: "ti ti-world-plus",
				searchTerms: ["world-plus"]
			}, {
				title: "ti ti-world-question",
				searchTerms: ["world-question"]
			}, {
				title: "ti ti-world-search",
				searchTerms: ["world-search"]
			}, {
				title: "ti ti-world-share",
				searchTerms: ["world-share"]
			}, {
				title: "ti ti-world-star",
				searchTerms: ["world-star"]
			}, {
				title: "ti ti-world-up",
				searchTerms: ["world-up"]
			}, {
				title: "ti ti-world-upload",
				searchTerms: ["world-upload"]
			}, {
				title: "ti ti-world-www",
				searchTerms: ["world-www"]
			}, {
				title: "ti ti-world-x",
				searchTerms: ["world-x"]
			}, {
				title: "ti ti-wrecking-ball",
				searchTerms: ["wrecking-ball"]
			}, {
				title: "ti ti-writing",
				searchTerms: ["writing"]
			}, {
				title: "ti ti-writing-off",
				searchTerms: ["writing-off"]
			}, {
				title: "ti ti-writing-sign",
				searchTerms: ["writing-sign"]
			}, {
				title: "ti ti-writing-sign-off",
				searchTerms: ["writing-sign-off"]
			}, {
				title: "ti ti-x",
				searchTerms: ["x"]
			}, {
				title: "ti ti-xbox-a",
				searchTerms: ["xbox-a"]
			}, {
				title: "ti ti-xbox-a-filled",
				searchTerms: ["xbox-a-filled"]
			}, {
				title: "ti ti-xbox-b",
				searchTerms: ["xbox-b"]
			}, {
				title: "ti ti-xbox-b-filled",
				searchTerms: ["xbox-b-filled"]
			}, {
				title: "ti ti-xbox-x",
				searchTerms: ["xbox-x"]
			}, {
				title: "ti ti-xbox-x-filled",
				searchTerms: ["xbox-x-filled"]
			}, {
				title: "ti ti-xbox-y",
				searchTerms: ["xbox-y"]
			}, {
				title: "ti ti-xbox-y-filled",
				searchTerms: ["xbox-y-filled"]
			}, {
				title: "ti ti-xd",
				searchTerms: ["xd"]
			}, {
				title: "ti ti-xxx",
				searchTerms: ["xxx"]
			}, {
				title: "ti ti-yin-yang",
				searchTerms: ["yin-yang"]
			}, {
				title: "ti ti-yin-yang-filled",
				searchTerms: ["yin-yang-filled"]
			}, {
				title: "ti ti-yoga",
				searchTerms: ["yoga"]
			}, {
				title: "ti ti-zeppelin",
				searchTerms: ["zeppelin"]
			}, {
				title: "ti ti-zeppelin-filled",
				searchTerms: ["zeppelin-filled"]
			}, {
				title: "ti ti-zeppelin-off",
				searchTerms: ["zeppelin-off"]
			}, {
				title: "ti ti-zip",
				searchTerms: ["zip"]
			}, {
				title: "ti ti-zodiac-aquarius",
				searchTerms: ["zodiac-aquarius"]
			}, {
				title: "ti ti-zodiac-aries",
				searchTerms: ["zodiac-aries"]
			}, {
				title: "ti ti-zodiac-cancer",
				searchTerms: ["zodiac-cancer"]
			}, {
				title: "ti ti-zodiac-capricorn",
				searchTerms: ["zodiac-capricorn"]
			}, {
				title: "ti ti-zodiac-gemini",
				searchTerms: ["zodiac-gemini"]
			}, {
				title: "ti ti-zodiac-leo",
				searchTerms: ["zodiac-leo"]
			}, {
				title: "ti ti-zodiac-libra",
				searchTerms: ["zodiac-libra"]
			}, {
				title: "ti ti-zodiac-pisces",
				searchTerms: ["zodiac-pisces"]
			}, {
				title: "ti ti-zodiac-sagittarius",
				searchTerms: ["zodiac-sagittarius"]
			}, {
				title: "ti ti-zodiac-scorpio",
				searchTerms: ["zodiac-scorpio"]
			}, {
				title: "ti ti-zodiac-taurus",
				searchTerms: ["zodiac-taurus"]
			}, {
				title: "ti ti-zodiac-virgo",
				searchTerms: ["zodiac-virgo"]
			}, {
				title: "ti ti-zoom",
				searchTerms: ["zoom"]
			}, {
				title: "ti ti-zoom-cancel",
				searchTerms: ["zoom-cancel"]
			}, {
				title: "ti ti-zoom-cancel-filled",
				searchTerms: ["zoom-cancel-filled"]
			}, {
				title: "ti ti-zoom-check",
				searchTerms: ["zoom-check"]
			}, {
				title: "ti ti-zoom-check-filled",
				searchTerms: ["zoom-check-filled"]
			}, {
				title: "ti ti-zoom-code",
				searchTerms: ["zoom-code"]
			}, {
				title: "ti ti-zoom-code-filled",
				searchTerms: ["zoom-code-filled"]
			}, {
				title: "ti ti-zoom-exclamation",
				searchTerms: ["zoom-exclamation"]
			}, {
				title: "ti ti-zoom-exclamation-filled",
				searchTerms: ["zoom-exclamation-filled"]
			}, {
				title: "ti ti-zoom-filled",
				searchTerms: ["zoom-filled"]
			}, {
				title: "ti ti-zoom-in",
				searchTerms: ["zoom-in"]
			}, {
				title: "ti ti-zoom-in-area",
				searchTerms: ["zoom-in-area"]
			}, {
				title: "ti ti-zoom-in-area-filled",
				searchTerms: ["zoom-in-area-filled"]
			}, {
				title: "ti ti-zoom-in-filled",
				searchTerms: ["zoom-in-filled"]
			}, {
				title: "ti ti-zoom-money",
				searchTerms: ["zoom-money"]
			}, {
				title: "ti ti-zoom-money-filled",
				searchTerms: ["zoom-money-filled"]
			}, {
				title: "ti ti-zoom-out",
				searchTerms: ["zoom-out"]
			}, {
				title: "ti ti-zoom-out-area",
				searchTerms: ["zoom-out-area"]
			}, {
				title: "ti ti-zoom-out-area-filled",
				searchTerms: ["zoom-out-area-filled"]
			}, {
				title: "ti ti-zoom-out-filled",
				searchTerms: ["zoom-out-filled"]
			}, {
				title: "ti ti-zoom-pan",
				searchTerms: ["zoom-pan"]
			}, {
				title: "ti ti-zoom-pan-filled",
				searchTerms: ["zoom-pan-filled"]
			}, {
				title: "ti ti-zoom-question",
				searchTerms: ["zoom-question"]
			}, {
				title: "ti ti-zoom-question-filled",
				searchTerms: ["zoom-question-filled"]
			}, {
				title: "ti ti-zoom-replace",
				searchTerms: ["zoom-replace"]
			}, {
				title: "ti ti-zoom-reset",
				searchTerms: ["zoom-reset"]
			}, {
				title: "ti ti-zoom-scan",
				searchTerms: ["zoom-scan"]
			}, {
				title: "ti ti-zoom-scan-filled",
				searchTerms: ["zoom-scan-filled"]
			}, {
				title: "ti ti-zzz",
				searchTerms: ["zzz"]
			}, {
				title: "ti ti-zzz-off",
				searchTerms: ["zzz-off"]
			} 
    ]
    });
});