<?php

namespace yuankezhan\yiiTinyEditor;
use yii\web\AssetBundle;


class EditorAssets extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = __DIR__ . '/assets';
    public $cssOptions = ['position' => \yii\web\View::POS_BEGIN];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
    /**
     * {@inheritdoc}
     */
    public $css = [
//        'css/iconfont.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $js = [
        'tinymce/js/tinymce/tinymce.min.js',
    ];
}