<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;

/**
 * Class UserParams
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class UserParams {
    public $name;
    public $surname;
    public $patronymic;
    public $phone;
    public $work;
    /**
     * @type text
     * @email
     */
    public $email;
    /**
     * @type text
     * @email
     */
    public $work_email;
    public $address;
} 