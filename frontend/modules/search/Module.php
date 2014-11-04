<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\search;

use menst\modulequery\ModuleQuery;
use menst\cms\common\interfaces\SearchableInterface;
use menst\cms\common\models\search\ActiveDocument;
use menst\cms\frontend\interfaces\MenuUrlRuleInterface;
use menst\cms\frontend\modules\search\components\SearchMenuUrlBehavior;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements BootstrapInterface, SearchableInterface, MenuUrlRuleInterface
{
    public $controllerNamespace = 'menst\cms\frontend\modules\search\controllers';
    public $documentClasses = [];
    public $index = 'cms';

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/

    /**
     * @inheritdoc
     */
    public function bootstrap($application)
    {
        ActiveDocument::watch(ModuleQuery::instance()->implement('menst\cms\common\interfaces\SearchableInterface')->execute('getDocumentClasses', [], ModuleQuery::AGGREGATE_MERGE));
    }
    /**
     * @inheritdoc
     */
    public function getDocumentClasses()
    {
        return $this->documentClasses;
    }
    /**
     * @inheritdoc
     */
    public function getMenuUrlRuleBehavior()
    {
        return SearchMenuUrlBehavior::className();
    }
}
