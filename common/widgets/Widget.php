<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\widgets;

use gromver\models\ObjectModel;
use gromver\models\SpecificationInterface;
use gromver\cmf\common\models\WidgetConfig;
use gromver\widgets\ModalIFrame;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Widget
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @package gromver/cms
 * @property $context string
 * @property $realContext string
 */
class Widget extends \yii\base\Widget implements SpecificationInterface
{
    private $_config;
    /**
     * @var \Exception
     */
    private $_exception;
    private $_context;
    private $_loadedContext;
    private $_id;
    private $_debug = false;
    private $_showPanel = true;
    private $_accessRule = 'administrate';

    public function __construct($config = [])
    {
        $this->_config = $config;

        try {
            parent::__construct($config);
        } catch(WidgetMissedIdException $e) {
            throw $e;
        } catch(\Exception $e) {
            $this->processException($e);
        }
    }

    private function processException($e)
    {
        if($this->getDebug())
            throw $e;
        else
            $this->_exception = $e;
    }

    /**
     * @throws WidgetMissedIdException
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        if (!isset($this->id) || empty($this->id)) {
            throw new WidgetMissedIdException('Specify widget ' . __CLASS__ . '::id.');
        }

        if (!isset($this->context)) {
            $this->context = Yii::$app->request->getPathInfo();
        }

        $parts = empty($this->context) ? [''] : explode('/', '/' . $this->context);

        $contexts = []; $context = '';
        foreach ($parts as $part) {
            $context .= strlen($context) ? '/'.$part : $part;
            $contexts[] = $context;
        }

        if ($model = WidgetConfig::find()->orderBy('context desc')->where(['widget_id' => $this->id, 'language' => Yii::$app->language, 'context' => $contexts])->one()) {
            /** @var $model WidgetConfig */
            if($model->widget_class!=$this->className())
                throw new InvalidConfigException("DB's widget configuration is adjusted for a widget ". $model->widget_class . " that doesn't correspond to the current widget " . $this->className());

            $this->_loadedContext = $model->context;

            foreach($model->getParamsArray() as $key=>$value)
                if(!array_key_exists($key, $this->_config) && $this->hasProperty($key))
                    $this->$key = $value;
        }
    }

    public function run()
    {
        echo Html::beginTag('div', ['id' => $this->id, 'class' => 'widget-wrapper' . ($this->canEdit() && Yii::$app->cmf->getIsEditMode() && $this->getShowPanel() ? ' edit-mode' : '')]);
        if ($this->_exception === null) {
            try {
                $this->launch();
            } catch (\Exception $e) {
                $this->processException($e);
            }
        }

        if ($this->canEdit()) {
            if ($this->_exception) {
                $this->renderException();
            }

            if ($this->_showPanel && Yii::$app->cmf->getIsEditMode()) {
                $this->renderEditControl();
            }
        }

        echo Html::endTag('div');

        WidgetAsset::register($this->getView());
    }

    /**
     * @throws \Exception в случае использования вьюхи, проверку на работоспособность виджета надо проводить до [[self::render()]],
     * т.к. если исключение сработает во вьюхе, то верстка нарушится
     */
    protected function launch() {}

    public function processSpecification(&$specification)
    {
        foreach ($this->_config as $attribute => $value) {
            if(array_key_exists($attribute, $specification)) $specification[$attribute]['disabled'] = true;
        }
    }

    public function renderException()
    {
        echo Html::tag('p', Yii::t('gromver.cmf', 'Widget error: {error}', ['error' => $this->_exception->getMessage()]), ['class' => 'text-danger widget-error']);
    }

    public function canEdit()
    {
        return $this->_accessRule ? Yii::$app->user->can($this->_accessRule) : true;
    }

    public function getId($autoGenerate = true)
    {
        return $this->_id;
    }

    public function setId($value)
    {
        $this->_id = $value;
    }

    public function getContext()
    {
        return $this->_context;
    }

    public function setContext($value)
    {
        $this->_context = $value;
    }

    public function getLoadedContext()
    {
        return $this->_loadedContext;
    }

    public function setDebug($value)
    {
        $this->_debug = $value;
    }

    public function getDebug()
    {
        if(!isset($this->_debug))
            $this->_debug = !!YII_DEBUG;

        return $this->_debug;
    }

    public function setShowPanel($value)
    {
        $this->_showPanel = $value;
    }

    public function getShowPanel()
    {
        return $this->_showPanel;
    }

    public function setAccessRule($value)
    {
        $this->_accessRule = $value;
    }

    public function getAccessRule()
    {
        return $this->_accessRule;
    }
    /**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    public function renderEditControl()
    {
        ModalIFrame::begin([
            'modalOptions' => [
                'header' => Yii::t('gromver.cmf', 'Widget "{name}" (ID: {id})', ['name' => $this->className(), 'id' => $this->id]),
                'size' => Modal::SIZE_LARGE
            ],
            'buttonOptions' => [
                'class' => 'widget-button-edit'
            ]
        ]);

        echo Html::beginForm(['/cmf/widget/default/configure', 'modal' => 1]);

        echo Html::hiddenInput('url', Yii::$app->request->getAbsoluteUrl());

        echo Html::hiddenInput('widget_id', $this->id);

        echo Html::hiddenInput('widget_class', $this->className());

        echo Html::hiddenInput('widget_context', $this->context);

        $objectModel = new ObjectModel($this->className());
        $objectModel->setAttributes($this->_config);

        echo Html::hiddenInput('widget_config', Json::encode($objectModel->toArray(array_keys($this->_config))));

        echo Html::submitButton('<i class="glyphicon glyphicon-pencil"></i>', ['class' => 'btn btn-default']);

        echo Html::endForm();

        ModalIFrame::end();

        echo Html::tag('div', Yii::t('gromver.cmf', 'Widget "{name}" (ID: {id})', ['name' => $this->className(), 'id' => $this->id]), ['class' => 'widget-description']);
    }
}