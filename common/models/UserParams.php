<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\common\models;

/**
 * Class UserParams
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class UserParams {
    /**
     * @translation gromver.platform
     */
    public $name;
    /**
     * @translation gromver.platform
     */
    public $surname;
    /**
     * @translation gromver.platform
     */
    public $patronymic;
    /**
     * @translation gromver.platform
     */
    public $phone;
    /**
     * @translation gromver.platform
     */
    public $work_phone;
    /**
     * @type text
     * @email
     * @translation gromver.platform
     */
    public $email;
    /**
     * @type text
     * @email
     * @translation gromver.platform
     */
    public $work_email;
    /**
     * @translation gromver.platform
     */
    public $address;
} 