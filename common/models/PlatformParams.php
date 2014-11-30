<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\common\models;


use yii\base\Object;

/**
 * Class CmsParams
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class PlatformParams extends  Object
{
    /**
     * @translation gromver.platform
     */
    public $siteName;
    /**
     * @type multiple
     * @fieldType text
     * @email
     * @translation gromver.platform
     */
    public $adminEmail;
    /**
     * @type multiple
     * @fieldType text
     * @email
     * @translation gromver.platform
     */
    public $supportEmail;
    /**
     * @before <h3 class="col-sm-12">Elasticsearch</h3>
     * @pattern #^\w*$#
     * @translation gromver.platform
     * @label Index
     */
    public $elasticsearchIndex;
}