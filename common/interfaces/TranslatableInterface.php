<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\common\interfaces;

/**
 * Interface TranslatableInterface
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
interface TranslatableInterface {
    /**
     * @return static[]
     */
    public function getTranslations();

    /**
     * @return string
     */
    public function getLanguage();
}