<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\console\modules\search;

use gromver\cmf\common\models\search\ActiveDocument;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $documentClasses = [];
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\web\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        ActiveDocument::watch($this->getDocumentClasses());
    }

    private function getDocumentClasses()
    {
        return array_merge([
            'gromver\cmf\common\models\search\Page',
            'gromver\cmf\common\models\search\Post',
            'gromver\cmf\common\models\search\Category',
        ], $this->documentClasses);
    }
}
