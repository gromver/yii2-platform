<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;


use yii\base\Object;

/**
 * Class CmsParams
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CmfParams extends  Object
{
    public $siteName;
    /**
     * @type multiple
     * @fieldType text
     * @email
     */
    public $adminEmail;
    /**
     * @type multiple
     * @fieldType text
     * @email
     */
    public $supportEmail;
    /**
     * @before <h3 class="col-sm-12">Elasticsearch Settings</h3>
     * @pattern #^\w*$#
     */
    public $elasticsearchIndex;
}