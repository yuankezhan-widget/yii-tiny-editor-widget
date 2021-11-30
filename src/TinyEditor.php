<?php
namespace yuankezhan\yiiTinyEditor;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class TinyEditor extends InputWidget
{
    public $classId = 'editor';//编辑器创建选择器
    public $isShowMenu = false;
    public $toolbar = ['fullscreen code undo redo restoredraft | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify lineheight outdent indent indent2em beforemargin aftermargin | styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat table media charmap emoticons pagebreak insertdatetime print preview bdmap formatpainter mediaLibrary'];
    public $languageType = 'zh_CN';
    public $height = 400;//最小高度
    public $inline = false;//false：经典模式，true：内联样式
    public $contentStyle = '';//可直接写编辑器样式
    public $contentHtml = '';
    public $name = '';

    /**
     * 'mediaConfig' => [
     *     "imgListUrl" => Url::to(['media/get-img-list']),
     *     "groupListUrl" => Url::to(['media/group-list']),
     *     "addImgUrl" => Url::to(['upload/upload']),
     *     "addGroupUrl" => Url::to(['media/add-group']),
     * ],
     * @var array
     */
    public $mediaConfig = [];

    public function init()
    {
        if (!empty($this->model)) {
            if ($this->name === null && !$this->hasModel()) {
                throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
            }
            if (!isset($this->options['id'])) {
                $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
            }
            parent::init();
        }
    }

    public function run()
    {
        return $this->render('TinyEditor', [
            'classId' => $this->classId,
            'isShowMenu' => $this->isShowMenu,
            'toolbar' => $this->toolbar,
            'language' => $this->languageType,
            'height' => $this->height,
            'inline' => $this->inline,
            'content_style' => $this->contentStyle,
            'name' => $this->name,
            'mediaConfig' => $this->mediaConfig,
            'content' => empty($this->model) ? $this->contentHtml : $this->model->toArray()[$this->options['id']]
        ]);
    }
}