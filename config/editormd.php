<?php
return [ // laravelchen/laravel-editormd
    'upload_path' => 'uploads/images/', // 上传路径
    'upload_type' => '', // 上传方式 |qiniu
    'upload_http' => 'https', // 上传协议 |https
    'width' => '100%', // 宽度 100%
    'height' => '700', // 高度
    'theme' => 'default', // 主题 default|dark
    'editorTheme' => 'default', // 编辑主题 default|pastel-on-dark vendor/editormd/lib/theme
    'previewTheme' => 'default', // 显示主题 default|dark

    'emoji' => 'true', // emoji 表情
    'taskList' => 'true', // 任务列表
    'flowChart' => 'true', // 流程图
    'sequenceDiagram' => 'true', // 开启时序/序列图支持
    'tex' => 'true', // 科学公式 TeX

    'searchReplace' => 'true', // 搜索替换
    'saveHTMLToTextarea' => 'true', // 保存 HTML 到 Textarea
    'codeFold' => 'true', // 代码折叠
    'toc' => 'true', // 目录
    'tocm' => 'true', // 目录下拉菜单
    'imageUpload' => 'true', // 图片本地上传支持
];
