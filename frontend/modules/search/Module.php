<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\search;

use gromver\cmf\frontend\interfaces\MenuRouterInterface;
use gromver\cmf\frontend\modules\search\components\MenuRouterSearch;
use gromver\modulequery\ModuleQuery;
use gromver\cmf\common\interfaces\SearchableInterface;
use gromver\cmf\common\models\search\ActiveDocument;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements BootstrapInterface, SearchableInterface, MenuRouterInterface
{
    public $controllerNamespace = 'gromver\cmf\frontend\modules\search\controllers';
    public $documentClasses = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($application)
    {
        ActiveDocument::watch(ModuleQuery::instance()->implement('gromver\cmf\common\interfaces\SearchableInterface')->execute('getDocumentClasses', [], ModuleQuery::AGGREGATE_MERGE));
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
    public function getMenuRouter()
    {
        return MenuRouterSearch::className();
    }
}
