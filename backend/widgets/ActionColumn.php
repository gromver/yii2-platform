<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\widgets;

use yii\base\Event;

/**
 * Class ActionColumn позволяет изменять кнопки посредством подписки на событие EVENT_CUSTOM_ACTION
 * @see CustomActionEvent
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class ActionColumn extends \kartik\grid\ActionColumn
{
    const EVENT_CUSTOM_ACTION = 'customAction';

    public $template = '{custom} {view} {update} {delete}';

    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();

        $this->buttons['custom'] = function($url, $model) {
            $event = new CustomActionEvent([
                'module' => \Yii::$app->controller->getUniqueId(),
                'model' => $model
            ]);
            Event::trigger($this, self::EVENT_CUSTOM_ACTION, $event);

            return implode(' ', $event->buttons);
        };
    }
}