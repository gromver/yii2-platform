<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\components;

use yii\web\User as BaseUser;


/**
 * Class User
 * @property \menst\cms\common\models\User $identity The identity object associated with the currently logged user. Null
 * is returned if the user is not logged in (not authenticated).
 *
 * @package yii2-cms
 * @author Ricardo Obregón <robregonm@gmail.com>
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class User extends BaseUser
{
    /**
	 * @inheritdoc
	 */
	public $identityClass = 'menst\cms\common\models\User';

	/**
	 * @inheritdoc
	 */
	public $enableAutoLogin = true;

	/**
	 * @inheritdoc
	 */
	public $loginUrl = ['/cms/auth/default/login'];

    public $superAdmins = ['admin'];

	/**
	 * @inheritdoc
	 */
	protected function afterLogin($identity, $cookieBased, $duration)
	{
		parent::afterLogin($identity, $cookieBased, $duration);
		$this->identity->setScenario(self::EVENT_AFTER_LOGIN);
		$this->identity->setAttribute('last_visit_at', time());
		//todo сэйвить али не сэйвить ип юзера?
		// $this->identity->setAttribute('login_ip', ip2long(\Yii::$app->getRequest()->getUserIP()));
		$this->identity->save(false);
	}

	public function getIsSuperAdmin()
	{
		if ($this->isGuest) {
			return false;
		}
		return $this->identity->getIsSuperAdmin();
	}

    /**
     * @inheritdoc
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        // Always return true when SuperAdmin user
        if ($this->getIsSuperAdmin()) {
            return true;
        }
        return parent::can($permissionName, $params, $allowCaching);
    }
}