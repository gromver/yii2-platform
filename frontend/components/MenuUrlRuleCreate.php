<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\components;

/**
 * Class MenuUrlRuleCreate
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuUrlRuleCreate extends MenuUrlRule {
    /**
     * @var string
     */
    public $requestRoute;
    /**
     * @var array
     */
    public $requestParams;

    /**
     * @inheritdoc
     */
    public function process($requestInfo, $menuManager)
    {
        if ($this->requestRoute && $this->requestRoute != $requestInfo->requestRoute) {
            return false;
        }

        if (isset($this->requestParams)) {
            foreach ($this->requestParams as $param) {
                if (!isset($requestInfo->requestParams[$param])) {
                    return false;
                }
            }
        }

        return $menuManager->getRouter($this->router)->{$this->handler}($requestInfo);
    }
} 