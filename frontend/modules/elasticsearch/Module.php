<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\elasticsearch;

use gromver\cmf\backend\modules\elasticsearch\models\ActiveDocument;
use gromver\cmf\common\interfaces\BootstrapInterface;
use gromver\cmf\frontend\interfaces\MenuRouterInterface;
use gromver\cmf\frontend\modules\elasticsearch\components\MenuRouterSearch;
use gromver\modulequery\ModuleQuery;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements BootstrapInterface, MenuRouterInterface
{
    public $controllerNamespace = 'gromver\cmf\frontend\modules\elasticsearch\controllers';
    public $documentClasses = [];
    public $elasticsearchIndex;

    public function init()
    {
        $this->documentClasses = array_merge($this->documentClasses, [
            'gromver\cmf\backend\modules\elasticsearch\models\Page',
            'gromver\cmf\backend\modules\elasticsearch\models\Post',
            'gromver\cmf\backend\modules\elasticsearch\models\Category',
        ]);

        if ($this->elasticsearchIndex) {
            ActiveDocument::$index = $this->elasticsearchIndex;
        }
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($application)
    {
        ActiveDocument::watch(array_merge($this->documentClasses, ModuleQuery::instance()->implement('gromver\cmf\common\interfaces\SearchableInterface')->execute('getDocumentClasses', [], ModuleQuery::AGGREGATE_MERGE)));
    }

    /**
     * @inheritdoc
     */
    public function getMenuRouter()
    {
        return MenuRouterSearch::className();
    }
}
