<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\search;

use menst\cms\backend\interfaces\DesktopInterface;
use menst\cms\backend\interfaces\MenuRouterInterface;
use menst\cms\common\helpers\ModuleQuery;
use menst\cms\common\interfaces\SearchableInterface;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Module
 * В этом модуле можно кастомизировать дополнительные кдассы ActiveDocument поддерживаемые цмской
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property string[] $documents
 * @property string[] $models
 */
class Module extends \yii\base\Module implements BootstrapInterface, DesktopInterface, MenuRouterInterface, SearchableInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\search\controllers';
    public $documentClasses = [];
    public $desktopOrder = 6;
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
        \menst\cms\common\models\search\ActiveDocument::watch(ModuleQuery::instance()->implement('menst\cms\common\interfaces\SearchableInterface')->execute('getDocumentClasses', [], ModuleQuery::AGGREGATE_MERGE));
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('menst.cms', 'Search'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Searching'), 'url' => ['/cms/search/default/index']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'Search'),
            'routers' => [
                ['label' => Yii::t('menst.cms', 'Searching'), 'route' => 'cms/search/default/index'],
            ]
        ];
    }

    public function getDocumentClasses()
    {
        return $this->documentClasses;
    }
}
