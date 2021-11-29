<!--<!DOCTYPE html>-->
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<?php
use backend\assets\AppAsset;
use yii\helpers\Url;
use yuankezhan\mediaLibrary\MediaLibrary;

//AppAsset::register($this);
//只在该视图中使用非全局的jui
AppAsset::addScript($this, '/backend/widgets/tinyEditor/views/tinymce/js/tinymce/tinymce.min.js');
?>
<?= MediaLibrary::widget([
    'boxClassName' => ['editor-media-box']
])?>
<textarea placeholder="请输入文章内容" name="<?= $name?>" id="<?= $classId?>"><?= $content?></textarea>
<script>

    var editorMediaUpload_<?= $classId?> = new mediaLibrary().init({
        imgListUrl : "<?=Url::to(['media/get-img-list'])?>",
        groupListUrl : "<?=Url::to(['media/group-list'])?>",
        addImgUrl : "<?=Url::to(['upload/upload'])?>",
        addGroupUrl : "<?=Url::to(['media/add-group'])?>",
        sureCallback : "addImg_<?= $classId?>",
        boxClassName : "editor-media-box"
    });

    tinymce.init({
        selector: '#<?= $classId?>',
        language:'<?= $language?>',
        menubar: '<?= $isShowMenu?>',
        plugins: "print preview searchreplace autolink directionality visualblocks visualchars fullscreen link media template code codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave bdmap  autoresize axupimgs imagetools insertcard indent2em beforemargin aftermargin mediaLibrary",
        toolbar: <?= json_encode($toolbar)?>,
        min_height: <?= $height?>,
        inline: '<?= $inline?>',
        branding: false,
        contextmenu_never_use_native: true,
        contextmenu: false,
        content_style: '<?= $content_style?>',
        elementpath: false,
        font_formats: '微软雅黑=Microsoft YaHei,Helvetica Neue,PingFang SC,sans-serif;苹果苹方=PingFang SC,Microsoft YaHei,sans-serif;宋体=simsun,serif;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        fontsize_formats: '11px 12px 14px 16px 18px 24px 36px 48px',
    });

    function addImg_<?= $classId?>(urlList)
    {
        let imgStr = '';
        if (urlList.length < 1)
        {
            return;
        }
        for (let item of urlList)
        {
            imgStr += `<img src="${item['url']}"/>`
        }
        tinymce.activeEditor.execCommand('mceInsertContent', false, imgStr);
    }

    function getTinyContent(classId) {
        return tinyMCE.editors[classId].getContent();
    }
    //设置
    function setTinyContent(classId, text) {
        tinyMCE.editors[classId].setContent(text);
    }
    //插入
    function insertTinyContent(classId, text) {
        tinyMCE.editors[classId].insertContent(text);
    }
    tinymce.PluginManager.add('mediaLibrary', function(editor, url) {
        // 注册一个工具栏按钮名称
        editor.ui.registry.addButton('mediaLibrary', {
            text: '<svg width="24" height="24"><path d="M5 15.7l3.3-3.2c.3-.3.7-.3 1 0L12 15l4.1-4c.3-.4.8-.4 1 0l2 1.9V5H5v10.7zM5 18V19h3l2.8-2.9-2-2L5 17.9zm14-3l-2.5-2.4-6.4 6.5H19v-4zM4 3h16c.6 0 1 .4 1 1v16c0 .6-.4 1-1 1H4a1 1 0 01-1-1V4c0-.6.4-1 1-1zm6 8a2 2 0 100-4 2 2 0 000 4z" fill-rule="nonzero"></path></svg>',
            onAction: function () {
                editor.focus();
                editorMediaUpload_<?= $classId?>.showBox();
            }
        });
    });
</script>