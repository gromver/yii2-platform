<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;


use yii\base\Object;

/**
 * Class CmsParams
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CmsParams extends  Object
{
    public $siteName = 'My Application';
    /**
     * @type multiple
     * @fieldType text
     */
    public $adminEmail = 'admin@email.com';
    /**
     * @type multiple
     * @fieldType text
     */
    public $supportEmail = 'support@email.com';
}