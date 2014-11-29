<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\common\modules\elasticsearch;

use gromver\platform\common\models\elasticsearch\ActiveDocument;
use gromver\platform\common\interfaces\BootstrapInterface;
use gromver\modulequery\ModuleQuery;
use Yii;

/**
 * Class Module
 * В этом модуле можно кастомизировать дополнительные кдассы ActiveDocument поддерживаемые цмской
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $elasticsearchIndex;
    public $documentClasses = [
        'gromver\platform\common\models\elasticsearch\Page',
        'gromver\platform\common\models\elasticsearch\Post',
        'gromver\platform\common\models\elasticsearch\Category',
    ];

    public function init()
    {
        if ($this->elasticsearchIndex) {
            ActiveDocument::$index = $this->elasticsearchIndex;
        } elseif(@Yii::$app->grom->params['elasticsearchIndex']) {
            ActiveDocument::$index = Yii::$app->grom->params['elasticsearchIndex'];
        }
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($application)
    {
        $this->documentClasses = array_merge($this->documentClasses, ModuleQuery::instance()->implement('gromver\platform\common\interfaces\SearchableInterface')->fetch('getDocumentClasses', [], ModuleQuery::AGGREGATE_MERGE));
        ActiveDocument::watch($this->documentClasses);
    }
}
