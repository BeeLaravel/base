// waterfall - v0.1.73 - 2015-12-01 - http://wlog.cn/waterfall/
;(function($, window, document, undefined) {
    'use strict';

    var $window = $(window),
        pluginName = 'waterfall',
        defaults = {
            itemCls: 'waterfall-item',  // the brick element class
            prefix: 'waterfall', // the waterfall elements prefix
            fitWidth: true, // fit the parent element width
            colWidth: 240,  // column width
            gutterWidth: 10, // the brick element horizontal gutter
            gutterHeight: 10, // the brick element vertical gutter
            align: 'center', // the brick align，'align', 'left', 'right'
            minCol: 1,  // min columns
            maxCol: undefined, // max columns, if undefined,max columns is infinite
            maxPage: undefined, // max page, if undefined,max page is infinite
            bufferPixel: -50, // decrease this number if you want scroll to fire quicker
            containerStyle: {
                position: 'relative'
            },
            resizable: true,
            isFadeIn: false,
            isAnimated: false,
            animationOptions: {
            },
            isAutoPrefill: true,
            checkImagesLoaded: true,
            path: undefined, // ["/popular/page/", "/"] => "/popular/page/1/" function(page) { return '/populr/page/' + page; } => "/popular/page/1/")
            dataType: 'json', // json, jsonp, html
            params: {}, // 参数
            headers: {}, // 头部
            loadingMsg: '<div style="text-align:center;padding:10px 0; color:#999;"><img src="data:image/gif;base64,R0lGODlhEAALAPQAAP///zMzM+Li4tra2u7u7jk5OTMzM1hYWJubm4CAgMjIyE9PT29vb6KiooODg8vLy1JSUjc3N3Jycuvr6+Dg4Pb29mBgYOPj4/X19cXFxbOzs9XV1fHx8TMzMzMzMzMzMyH5BAkLAAAAIf4aQ3JlYXRlZCB3aXRoIGFqYXhsb2FkLmluZm8AIf8LTkVUU0NBUEUyLjADAQAAACwAAAAAEAALAAAFLSAgjmRpnqSgCuLKAq5AEIM4zDVw03ve27ifDgfkEYe04kDIDC5zrtYKRa2WQgAh+QQJCwAAACwAAAAAEAALAAAFJGBhGAVgnqhpHIeRvsDawqns0qeN5+y967tYLyicBYE7EYkYAgAh+QQJCwAAACwAAAAAEAALAAAFNiAgjothLOOIJAkiGgxjpGKiKMkbz7SN6zIawJcDwIK9W/HISxGBzdHTuBNOmcJVCyoUlk7CEAAh+QQJCwAAACwAAAAAEAALAAAFNSAgjqQIRRFUAo3jNGIkSdHqPI8Tz3V55zuaDacDyIQ+YrBH+hWPzJFzOQQaeavWi7oqnVIhACH5BAkLAAAALAAAAAAQAAsAAAUyICCOZGme1rJY5kRRk7hI0mJSVUXJtF3iOl7tltsBZsNfUegjAY3I5sgFY55KqdX1GgIAIfkECQsAAAAsAAAAABAACwAABTcgII5kaZ4kcV2EqLJipmnZhWGXaOOitm2aXQ4g7P2Ct2ER4AMul00kj5g0Al8tADY2y6C+4FIIACH5BAkLAAAALAAAAAAQAAsAAAUvICCOZGme5ERRk6iy7qpyHCVStA3gNa/7txxwlwv2isSacYUc+l4tADQGQ1mvpBAAIfkECQsAAAAsAAAAABAACwAABS8gII5kaZ7kRFGTqLLuqnIcJVK0DeA1r/u3HHCXC/aKxJpxhRz6Xi0ANAZDWa+kEAA7" alt=""><br />Loading...</div>', // 加载中展示内容 loading html
            state: { // 状态
                isDuringAjax: false, // ajax 请求中
                isProcessingData: false, // 处理数据中
                isResizing: false, // 大小改变中
                isPause: false, // 暂停中
                curPage: 1 // 当前页 cur page
            },
            callbacks: { // 回调
                loadingStart: function($loading) { // 加载开始
                    $loading.show();
                },
                loadingFinished: function($loading, isBeyondMaxPage) { // 加载结束
                    if ( !isBeyondMaxPage ) { // 达到最大页数
                        $loading.fadeOut();
                    } else { // 不是最大页
                        $loading.remove();
                    }
                },
                loadingError: function($message, xhr) {
                    $message.html('Data load faild, please try again later.');
                },
                renderData: function (data, dataType) {
                    var tpl,
                        template;

                    if ( dataType === 'json' ||  dataType === 'jsonp'  ) {
                        tpl = $('#waterfall-tpl').html();
                        template = Handlebars.compile(tpl);

                        return template(data);
                    } else { // html
                        return data;
                    }
                }
            },
            debug: false
        };

    function Waterfall(element, options) {
        this.$element = $(element);
        this.options = $.extend(true, {}, defaults, options);
        this.colHeightArray = []; // 列高数组 columns height array
        this.styleQueue = [];

        this._init();
    }

    Waterfall.prototype = {
        constructor: Waterfall,

        _debug: function () {
            if ( true !== this.options.debug ) return;

            if (typeof console !== 'undefined' && typeof console.log === 'function') {
                if ((Array.prototype.slice.call(arguments)).length === 1 && typeof Array.prototype.slice.call(arguments)[0] === 'string') {
                    console.log( (Array.prototype.slice.call(arguments)).toString() );
                } else {
                    console.log( Array.prototype.slice.call(arguments) );
                }
            } else if (!Function.prototype.bind && typeof console !== 'undefined' && typeof console.log === 'object') { // IE8
                Function.prototype.call.call(console.log, console, Array.prototype.slice.call(arguments));
            }
        },
        _init: function( callback ) {
            var options = this.options,
                path = options.path;

            this._setColumns();
            this._initContainer();
            this._resetColumnsHeightArray();
            this.reLayout( callback );

            if ( !path ) {
                this._debug('Invalid path');
                return;
            }

            if ( options.isAutoPrefill ) this._prefill();
            if ( options.resizable ) this._doResize();
            this._doScroll();
        },
        _initContainer: function() {
            var options = this.options,
                prefix = options.prefix;

            // fix fixMarginLeft bug
            $('body').css({
                overflow: 'auto'
            });


            this.$element.css(this.options.containerStyle).addClass(prefix + '-container');
            this.$element.after('<div id="' + prefix + '-loading">' +options.loadingMsg+ '</div><div id="' + prefix + '-message" style="text-align:center;color:#999;"></div>');

            this.$loading = $('#' + prefix + '-loading');
            this.$message = $('#' + prefix + '-message');
        },
        _getColumns : function() {
            var options = this.options,
                $container = options.fitWidth ?  this.$element.parent() : this.$element,
                containerWidth = $container[0].tagName === 'BODY' ? $container.width() - 20 : $container.width(),  // if $container[0].tagName === 'BODY', fix browser scrollbar
                colWidth = options.colWidth, // 列宽
                gutterWidth = options.gutterWidth,
                minCol = options.minCol,
                maxCol = options.maxCol,
                cols = Math.floor(containerWidth / (colWidth + gutterWidth)),
                col = Math.max(cols, minCol );

            return !maxCol ? col : (col > maxCol ? maxCol : col);
        },
        _setColumns: function() { // ok
            this.cols = this._getColumns();
        },
        _getItems: function( $content ) {
            var $items = $content.filter('.' + this.options.itemCls).css({
                'position': 'absolute'
            });

            return $items;
        },
        _resetColumnsHeightArray: function() { // ok
            var cols = this.cols,
                i;

            this.colHeightArray.length = cols;

            for (i = 0; i < cols; i++) {
                this.colHeightArray[i] = 0;
            }
        },
        layout: function($content, callback) {
            var options = this.options,
            $items = this.options.isFadeIn ? this._getItems($content).css({ opacity: 0 }).animate({ opacity: 1 }) : this._getItems($content),
                styleFn = (this.options.isAnimated && this.options.state.isResizing) ? 'animate' : 'css',
                animationOptions = options.animationOptions,
                colWidth = options.colWidth,
                gutterWidth = options.gutterWidth,
                len = this.colHeightArray.length,
                align = options.align,
                fixMarginLeft,
                obj,
                i, j, itemsLen, styleLen;

            // append $items
            this.$element.append($items);

            // fixMarginLeft
            if ( align === 'center' ) {
                fixMarginLeft = (this.$element.width() - colWidth * len  - gutterWidth * (len - 1) ) /2;
                fixMarginLeft = fixMarginLeft > 0 ? fixMarginLeft : 0;
            } else if ( align === 'left' ) {
                fixMarginLeft = 0;
            } else if ( align === 'right' ) {
                fixMarginLeft = this.$element.width() - colWidth * len  - gutterWidth * (len - 1);
            }

            // place items
            for (i = 0, itemsLen = $items.length; i < itemsLen; i++) {
                this._placeItems( $items[i], fixMarginLeft);
            }

            // set style
            for (j= 0, styleLen = this.styleQueue.length; j < styleLen; j++) {
                obj = this.styleQueue[j];
                obj.$el[ styleFn ]( obj.style, animationOptions );
            }

            this.$element.height(Math.max.apply({}, this.colHeightArray)); // 改变父元素高度

            this.styleQueue = [];

            // update status
            this.options.state.isResizing = false;
            this.options.state.isProcessingData = false;

            // callback
            if ( callback ) {
                callback.call( $items );
            }
        },
        reLayout: function( callback ) {
            var $content = this.$element.find('.' + this.options.itemCls);

            this._resetColumnsHeightArray();
            this.layout($content , callback );
        },
        _placeItems: function( item, fixMarginLeft ) {
            var $item = $(item),
                options = this.options,
                colWidth = options.colWidth,
                gutterWidth = options.gutterWidth,
                gutterHeight = options.gutterHeight,
                colHeightArray = this.colHeightArray,
                len = colHeightArray.length,
                minColHeight = Math.min.apply({}, colHeightArray),
                minColIndex = $.inArray(minColHeight, colHeightArray),
                colIndex, //cur column index
                position;

            if ( $item.hasClass(options.prefix + '-item-fixed-left')) {
                colIndex = 0;
            } else if ( $item.hasClass(options.prefix + '-item-fixed-right') ) {
                colIndex = ( len > 1 ) ? ( len - 1) : 0;
            } else {
                colIndex = minColIndex;
            }

            position = {
                left: (colWidth + gutterWidth) * colIndex  + fixMarginLeft,
                top: colHeightArray[colIndex]
            };

            // push to style queue
            this.styleQueue.push({ $el: $item, style: position });

            colHeightArray[colIndex] += $item.outerHeight() + gutterHeight;
        },
        prepend: function($content, callback) {
            this.$element.prepend($content);
            this.reLayout(callback);
        },
        append: function($content, callback) {
            this.$element.append($content);
            this.reLayout(callback);
        },
        removeItems:function($items, callback ) {
            this.$element.find($items).remove();
            this.reLayout(callback);
        },
        option: function( opts, callback ){
            if ( $.isPlainObject( opts ) ){
                this.options = $.extend(true, this.options, opts);

                if ( typeof callback === 'function' ) {
                    callback();
                }

                this._init();
            }
        },
        pause: function(callback) {
            this.options.state.isPause = true;

            if ( typeof callback === 'function' ) {
                callback();
            }
        },
        resume: function(callback) {
            this.options.state.isPause = false;

            if ( typeof callback === 'function' ) {
                callback();
            }
        },
        _requestData: function(callback) { // 加载数据
            var self = this,
                options = this.options, // 选项
                maxPage = options.maxPage, // 最大页
                curPage = options.state.curPage++, // 当前页
                path = options.path, // 请求路径
                dataType = options.dataType, // 数据类型
                params = options.params, // 参数
                headers = options.headers, // 头部
                pageurl; // 当前页路径

            if ( maxPage !== undefined && curPage > maxPage ) { // 有最大页 且 当前页大于最大页
                options.state.isBeyondMaxPage = true; // 已达最大页
                options.callbacks.loadingFinished(this.$loading, options.state.isBeyondMaxPage); // 加载结束
                return;
            }

            pageurl = (typeof path === 'function') ? path(curPage) : path.join(curPage); // 当前页

            this._debug('heading into ajax', pageurl+$.param(params));

            options.callbacks.loadingStart(this.$loading); // 开始加载

            options.state.isDuringAjax = true; // 在加载数据中
            options.state.isProcessingData = true; // 处理数据中

            $.ajax({ // 加载数据
                url: pageurl,
                data: params,
                headers: headers,
                dataType: dataType,
                success: function(data) {
                    self._handleResponse(data, callback);
                    self.options.state.isDuringAjax = false; // ajax 结束
                },
                error: function(jqXHR) {
                    self._responeseError('error');
                }
            });
        },
        _handleResponse: function(data, callback) { // 处理正确的加载数据
            var self = this,
                options = this.options,
                content = $.trim(options.callbacks.renderData(data, options.dataType)), // 渲染数据
                $content = $(content),
                checkImagesLoaded = options.checkImagesLoaded;

            if ( !checkImagesLoaded ) { // 不检查图片是否已加载
               self.append($content, callback);
               self.options.callbacks.loadingFinished(self.$loading, self.options.state.isBeyondMaxPage);
            } else {
                $content.imagesLoaded(function() {
                    self.append($content, callback);
                    self.options.callbacks.loadingFinished(self.$loading, self.options.state.isBeyondMaxPage);
                });
            }
        },
        _responeseError: function(xhr) { // 加载失败
            this.$loading.hide();
            this.options.callbacks.loadingError(this.$message, xhr);

            if ( xhr !== 'end' && xhr !== 'error' ) xhr = 'unknown';

            this._debug('Error', xhr);
        },
        _nearbottom: function() { // 计算是否到底部 ok
            var options = this.options,
                minColHeight = Math.min.apply({}, this.colHeightArray), // 最小列高
                distanceFromWindowBottomToMinColBottom = $window.scrollTop() + $window.height() - this.$element.offset().top - minColHeight;

            this._debug('math:', distanceFromWindowBottomToMinColBottom);

            return ( distanceFromWindowBottomToMinColBottom > options.bufferPixel );
        },
        _prefill: function() { // ok
            if ( this.$element.height() <= $window.height() ) {
                this._scroll();
            }
        },
        _scroll: function() { // ok
            var options = this.options,
                state = options.state,
                self = this;

            if ( state.isProcessingData || state.isDuringAjax || state.isInvalidPage || state.isPause ) { // 处理数据中 ajax 处理中 非法页 暂停
                return;
            }

            if ( !this._nearbottom() ) return; // 不在可加载范围

            this._requestData(function() {
                var timer = setTimeout(function() {
                    self._scroll();
                }, 100);
            });
        },
        _doScroll: function() { // 绑定数据 ok
            var self = this,
                scrollTimer;

            $window.bind('scroll', function() {
                if ( scrollTimer ) clearTimeout(scrollTimer);

                scrollTimer = setTimeout(function() {
                    self._scroll();
                }, 100);
            });
        },
        _resize: function() { // ok
            var cols = this.cols,
                newCols = this._getColumns(); // new columns

            if ( newCols !== cols || this.options.align !== 'left' ) {
                this.options.state.isResizing = true;
                this.cols = newCols; // update columns
                this.reLayout(); // relayout
                this._prefill(); // prefill
            }
        },
        _doResize: function() { // 重设大小 ok
            var self = this,
                resizeTimer;

            $window.bind('resize', function() {
                if ( resizeTimer ) clearTimeout(resizeTimer);

                resizeTimer = setTimeout(function() {
                    self._resize();
                }, 100);
            });
        }
    };

    $.fn[pluginName] = function(options) {
        if ( typeof options === 'string' ) { // plugin method
            var args = Array.prototype.slice.call(arguments, 1);

            this.each(function() {
                var instance = $.data( this, 'plugin_' + pluginName );

                if ( !instance ) {
                    instance._debug('instance is not initialization');
                    return;
                }

                if ( !$.isFunction( instance[options] ) || options.charAt(0) === '_' ) { //
                    instance._debug( 'no such method "' + options + '"' );
                    return;
                }

                instance[options].apply( instance, args );
            });
        } else { // new plugin
            this.each(function() {
                if ( !$.data(this, 'plugin_' + pluginName) ) {
                    $.data(this, 'plugin_' + pluginName, new Waterfall(this, options));
                }
            });
        }

        return this;
    };
}(jQuery, window, document));
// jQuery imagesLoaded plugin v2.1.2 - http://github.com/desandro/imagesloaded
;(function($, undefined) {
    'use strict';

    var BLANK = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

    $.fn.imagesLoaded = function( callback ) {
        var $this = this,
            deferred = $.isFunction($.Deferred) ? $.Deferred() : 0,
            hasNotify = $.isFunction(deferred.notify),
            $images = $this.find('img').add( $this.filter('img') ),
            loaded = [],
            proper = [],
            broken = [];

        if ($.isPlainObject(callback)) {
            $.each(callback, function (key, value) {
                if (key === 'callback') {
                    callback = value;
                } else if (deferred) {
                    deferred[key](value);
                }
            });
        }

        function doneLoading() {
            var $proper = $(proper),
                $broken = $(broken);

            if ( deferred ) {
                if ( broken.length ) {
                    deferred.reject( $images, $proper, $broken );
                } else {
                    deferred.resolve( $images );
                }
            }

            if ( $.isFunction( callback ) ) {
                callback.call( $this, $images, $proper, $broken );
            }
        }
        function imgLoadedHandler( event ) {
            imgLoaded( event.target, event.type === 'error' );
        }
        function imgLoaded( img, isBroken ) {
            // don't proceed if BLANK image, or image is already loaded
            if ( img.src === BLANK || $.inArray( img, loaded ) !== -1 ) {
                return;
            }

            // store element in loaded images array
            loaded.push( img );

            // keep track of broken and properly loaded images
            if ( isBroken ) {
                broken.push( img );
            } else {
                proper.push( img );
            }

            // cache image and its state for future calls
            $.data( img, 'imagesLoaded', { isBroken: isBroken, src: img.src } );

            // trigger deferred progress method if present
            if ( hasNotify ) {
                deferred.notifyWith( $(img), [ isBroken, $images, $(proper), $(broken) ] );
            }

            // call doneLoading and clean listeners if all images are loaded
            if ( $images.length === loaded.length ) {
                setTimeout( doneLoading );
                $images.unbind( '.imagesLoaded', imgLoadedHandler );
            }
        }

        if ( !$images.length ) { // 没有图片
            doneLoading();
        } else {
            $images.bind( 'load.imagesLoaded error.imagesLoaded', imgLoadedHandler )
            .each( function( i, el ) {
                var src = el.src,

                // find out if this image has been already checked for status
                // if it was, and src has not changed, call imgLoaded on it
                cached = $.data( el, 'imagesLoaded' );
                if ( cached && cached.src === src ) {
                    imgLoaded( el, cached.isBroken );
                    return;
                }

                // if complete is true and browser supports natural sizes, try
                // to check for image status manually
                if ( el.complete && el.naturalWidth !== undefined ) {
                    imgLoaded( el, el.naturalWidth === 0 || el.naturalHeight === 0 );
                    return;
                }

                // cached images don't fire load sometimes, so we reset src, but only when
                // dealing with IE, or image is complete (loaded) and failed manual check
                // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
                if ( el.readyState || el.complete ) {
                    el.src = BLANK;
                    el.src = src;
                }
            });
        }

        return deferred ? deferred.promise( $this ) : $this;
    };
})(jQuery);
