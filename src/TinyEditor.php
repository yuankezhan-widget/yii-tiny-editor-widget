<?php
namespace yuankezhan\tinyEditor;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class TinyEditor extends InputWidget
{
    public $ClassId = 'editor';//编辑器创建选择器
    public $IsShowMenu = false;
    public $Toolbar = ['fullscreen code undo redo restoredraft | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify lineheight outdent indent indent2em beforemargin aftermargin | styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat table media charmap emoticons pagebreak insertdatetime print preview bdmap formatpainter mediaLibrary'];
    public $LanguageType = 'zh_CN';
    public $Height = 400;//最小高度
    public $Inline = false;//false：经典模式，true：内联样式
    public $ContentStyle = '';//可直接写编辑器样式
    public $ContentHtml = '';
    public $Name = '';

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
            'classId' => $this->ClassId,
            'isShowMenu' => $this->IsShowMenu,
            'toolbar' => $this->Toolbar,
            'language' => $this->LanguageType,
            'height' => $this->Height,
            'inline' => $this->Inline,
            'content_style' => $this->ContentStyle,
            'name' => $this->Name,
            'content' => empty($this->model) ? $this->ContentHtml : $this->model->toArray()[$this->options['id']]
        ]);
    }
}