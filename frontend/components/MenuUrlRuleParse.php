<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\components;

/**
 * Class MenuUrlRuleParse
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuUrlRuleParse extends MenuUrlRule {
    /**
     * @inheritdoc
     */
    public function process($requestInfo, $menuManager)
    {
        if ($this->matchRoute != $requestInfo->menuRoute) {
            return false;
        }

        return $menuManager->getRouter($this->router)->{$this->handler}($requestInfo);
    }
} 