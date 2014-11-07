<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\search;

use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
use gromver\modulequery\ModuleQuery;
use gromver\cmf\common\interfaces\SearchableInterface;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Module
 * В этом модуле можно кастомизировать дополнительные кдассы ActiveDocument поддерживаемые цмской
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property string[] $documents
 * @property string[] $models
 */
class Module extends \yii\base\Module implements BootstrapInterface, DesktopInterface, MenuRouterInterface, SearchableInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\search\controllers';
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
        \gromver\cmf\common\models\search\ActiveDocument::watch(ModuleQuery::instance()->implement('gromver\cmf\common\interfaces\SearchableInterface')->execute('getDocumentClasses', [], ModuleQuery::AGGREGATE_MERGE));
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('menst.cms', 'Search'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Searching'), 'url' => ['/cmf/search/default/index']],
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
                ['label' => Yii::t('menst.cms', 'Searching'), 'route' => 'cmf/search/default/index'],
            ]
        ];
    }

    public function getDocumentClasses()
    {
        return $this->documentClasses;
    }
}
