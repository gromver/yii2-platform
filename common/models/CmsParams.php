<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models;


use yii\base\Object;

/**
 * Class CmsParams
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
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