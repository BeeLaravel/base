(function(){
    var factory = function (exports) {
        var pluginName = "code-auto-save";  
        var cacheKey = 'editormd_cache';
        var cacheContentKey = ( location.protocol + location.host + location.pathname + location.search ).replace( /[.:?=\/-]/g, '_' );
        var cm;

        exports.fn.CodeAutoSave = function() {
            var _this       = this;
            cm              = _this.cm;
            var settings    = _this.settings;
            var classPrefix = _this.classPrefix;
            var id          = _this.id; // 编辑器id

            var _saveFlag = null; // 定时器
            var saveInterval = 1000; // 自动保存间隔时间 单位 ms

            if ( typeof(Storage)=="undefined" ) return; // 没有 localStorage

            cacheContentKey = cacheContentKey + "_" + id; // 设置编辑器为当前域名+编辑器id

            cm.on('change', function(){
                if ( _saveFlag ) window.clearTimeout(_saveFlag); //已经存在定时器关闭 重新开始 防止多次执行

                _saveFlag = window.setTimeout(function(){
                    _this.CodeAutoSaveSetCache(cm.getValue()); // 设置缓存
                }, saveInterval);
            });
        };
        exports.fn.CodeAutoSaveSetCache = function(value) { // 设置缓存
            value = value || cm.getValue(); // 内容
            var cacheContent = {};
            cacheContent[cacheContentKey] = value; // 
            localStorage.setItem(cacheKey, JSON.stringify(cacheContent));
        }
        exports.fn.CodeAutoSaveGetCache = function() { // 读取缓存
            if ( localStorage.hasOwnProperty(cacheKey) ) {
                var cacheData = JSON.parse(localStorage.getItem(cacheKey));
                if ( cacheData[cacheContentKey] ) cm.setValue(cacheData[cacheContentKey]); // 设置内容
            }
        }
        exports.fn.CodeAutoSaveDelCache = function() { // 删除缓存
            localStorage.removeItem(cacheKey);
        }
        exports.fn.CodeAutoSaveEmptyCacheContent = function() { // 清空缓存的文档内容
            _this.CodeAutoSaveSetCache('');
        }
    };

    if ( typeof require === "function" && typeof exports === "object" && typeof module === "object" ) { // CommonJS/Node.js
        module.exports = factory;
    } else if ( typeof define === "function" ) { // AMD/CMD/Sea.js
        if ( define.amd ) { // Require.js
            define(["editormd"], function(editormd) {
                factory(editormd);
            });
        } else { // Sea.js
            define(function(require) {
                var editormd = require("./../../editormd");
                factory(editormd);
            });
        }
    } else {
        factory(window.editormd);
    }
})();
