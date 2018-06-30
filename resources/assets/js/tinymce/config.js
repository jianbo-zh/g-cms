
window.tinymce.overrideDefaults({
    language: 'zh_CN',
    language_url: '/vendor/tinymce/langs/zh_CN.js',
    images_upload_url: 'test.php',
    plugins: [
        // 注意：当添加 importcss 插件时，会导致 styleselect 不可用
        'advlist anchor autoresize autolink  autosave charmap emoticons code codesample colorpicker contextmenu',
        'directionality fullscreen hr image imagetools insertdatetime',
        'legacyoutput link lists media nonbreaking noneditable pagebreak paste preview print save searchreplace',
        'tabfocus table template textcolor visualblocks wordcount help',
    ],
    menubar: false,
    // link image media anchor charmap hr pagebreak template
    toolbar1: ' code undo redo styleselect formatselect fontselect fontsizeselect forecolor backcolor ' +
    ' table  codesample emoticons insert print help',
    toolbar2: ' cut copy paste pastetext bold italic underline strikethrough ' +
    ' alignleft aligncenter alignright alignjustify outdent indent bullist numlist subscript superscript blockquote removeformat ' +
    ' visualblocks preview fullscreen',
    font_formats: '\u5b8b\u4f53=SimSun;\u9ed1\u4f53=SimHei;\u5fae\u8f6f\u96c5\u9ed1=Microsoft Yahei;\u6977\u4f53=KaiTi;\u65b0\u5b8b\u4f53=NSimSun;\u4eff\u5b8b=FangSong;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;',
    branding: false,
    toolbar_items_size: 'small',
    style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}}],
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}],
});