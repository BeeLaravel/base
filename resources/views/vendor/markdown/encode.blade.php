<link rel="stylesheet" type="text/css" href="{{asset('vendor/markdown/css/editormd.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/gritter/css/jquery.gritter.css')}}">
<style type="text/css">
    .editormd-toolbar {
        z-index: 1070; /* 解决工具栏层级问题 */
    }
</style>

<script type="text/javascript" src="{{asset('vendor/markdown/js/editormd.min.js')}}"></script>
<script type="text/javascript" src="{{asset('template/color_admin/plugins/gritter/js/jquery.gritter.js')}}"></script>
<script type="text/javascript">
    var testEditor;

    $(function(){
        editormd.urls.atLinkBase = "https://laravel56.beesoft.ink/";
        editormd.emoji = {
            path: "//staticfile.qnssl.com/emoji-cheat-sheet/1.0.0/",
            ext: ".png"
        };
        editormd.fn.postSave = function(){
            let paths = location.pathname.split('/');
            let method = paths.pop();

            if ( method == 'edit' || method == 'renew' ) { // 编辑
                let id = paths.pop();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST', // POST|PATCH
                    url: $("#editForm").attr("action"),
                    dataType: 'json',
                    data: {
                        _method: 'PATCH',
                        id: id,
                        content: this.getValue()
                    },
                    success: function() {
                        $.gritter.add({
                            title: "操作消息！",
                            text: "保存成功啦😄"
                        });
                    },
                    error: function() {
                        $.gritter.add({
                            title: "操作消息！",
                            text: "保存失败啦😭"
                        });
                    }
                });
            } else if ( method == 'create' ) { // 创建 保存到 localStorage
                let cacheContentKey = ( location.protocol + location.host + location.pathname + location.search ).replace( /[.:?=\/-]/g, '_' );
                cacheContentKey += "_"+testEditor.id;
                let cacheContent = {};
                cacheContent[cacheContentKey] = this.getValue();
                localStorage.setItem("editormd_cache", JSON.stringify(cacheContent));
            }
        };

        @foreach ( $editors as $editor )
            testEditor = editormd("{{$editor}}", {
                width: '{{config('editormd.width')}}',
                height: '{{config('editormd.height')}}',
                theme: '{{config('editormd.theme')}}',
                editorTheme: '{{config('editormd.editorTheme')}}',
                previewTheme: '{{config('editormd.previewTheme')}}',
                markdown : "",
                path : "{{asset('vendor/markdown/lib')}}/",
                toolbarIcons : function() {
                    return [
                        "undo", "redo",
                        "|", "bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase",
                        "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "list-ul", "list-ol", "hr",
                        "|", "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "emoji", "html-entities", "pagebreak",
                        "||", "goto-line", "watch", "clear", "preview", "fullscreen"
                    ]
                },
                disabledKeyMaps : [
                    // "Ctrl-B", "F11", "F10"
                ],
                // htmlDecode : true,
                // htmlDecode : "style,script,iframe,sub,sup|on*",
                // htmlDecode : "style,script,iframe,sub,sup|*",
                // htmlDecode : "style,script,iframe,sub,sup,embed|onclick,title,onmouseover,onmouseout,style",
                // atLink: false,
                // emailLink: false,
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "{{url(config('markdowneditor.upload_url', 'markdown/editormd/upload'))}}",

                // pageBreak : false, // 分页符

                codeFold: {{config('editormd.codeFold')}},
                saveHTMLToTextarea: '{{config('editormd.saveHTMLToTextarea')}}',
                searchReplace: '{{config('editormd.searchReplace')}}',
                tocm: '{{config('editormd.tocm')}}',

                emoji: '{{config('editormd.emoji')}}', // emoji 表情
                taskList: '{{config('editormd.taskList')}}', // 任务列表
                flowChart: '{{config('editormd.flowChart')}}',
                sequenceDiagram: '{{config('editormd.sequenceDiagram')}}',
                tex: '{{config('editormd.tex')}}',

                onfullscreen: function() { // 解决全屏层级问题
                    $("#{{$editor}}").css({
                        "z-index": 1050
                    });
                },
                onfullscreenExit: function() {
                    $("#{{$editor}}").css({
                        "z-index": 0
                    });
                },

                toolbarIcons: function() {
                    return editormd.toolbarModes.full.concat([
                        "customIcon",
                        "postPreview",
                        "postSaveDraft",
                        "postSave"
                    ]);
                },
                toolbarIconsClass: {
                    customIcon: "fa-paste",
                    postPreview: "fa-chrome",
                    postSaveDraft: "fa-floppy-o",
                    postSave: "fa-paper-plane",
                },
                toolbarIconTexts: {
                    customIcon: "从草稿箱加载"
                },
                lang: {
                    toolbar: {
                        customIcon: "从草稿箱加载",
                        postPreview: "预览更改",
                        postSaveDraft: "保存草稿",
                        postSave: "保存",
                    }
                },
                toolbarHandlers: {
                    customIcon: function(){
                        testEditor.CodeAutoSaveGetCache();
                    },
                    postPreview: function(cm, icon, cursor, selection){
                        console.log(111);
                    },
                    postSaveDraft: function(cm, icon, cursor, selection){
                        console.log(222);
                    },
                    postSave: function(cm, icon, cursor, selection){ // 保存
                        this.postSave();
                    },
                },
                onload: function() {
                    editormd.loadPlugin("{{asset('vendor/markdown')}}/plugins/code-auto-save/code-auto-save", function() {
                        testEditor.CodeAutoSave();
                    });

                    var keyMap = {
                        "Ctrl-S": cm => { // 保存
                            this.postSave();
                        }
                    };

                    this.addKeyMap(keyMap);
                    // this.removeKeyMap(keyMap);

                    let paths = location.pathname.split('/'); // fullscreen
                    let method = paths.pop();
                    if ( method == 'renew' ) this.fullscreen();
                }
            });
            
            $(window).resize(function(){ // 监听窗体改变事件
                if ( testEditor.state.fullscreen ) { // 编辑器全屏
                    testEditor.editor.css({ // 赋予其窗体同等大小
                        width: $(window).width(),
                        height: $(window).height()
                    });
                    testEditor.resize(); // 应用改变大小
                }
            });
        @endforeach
    });
</script>