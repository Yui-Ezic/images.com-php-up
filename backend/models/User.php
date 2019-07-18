<?php
namespace backend\models;

use Yii;
use common\models\UserParent;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $picture
 */
class User extends UserParent
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';
    
    public $roles;

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveRoles']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['roles', 'safe'],
        ];
    }
    
    /**
     * @return array
     */
    public function getRolesDropdown()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
        ];
    }
    
    /**
     * Revoke old roles and assign new if any
     */
    public function saveRoles()
    {
        Yii::$app->authManager->revokeAll($this->getId()); 

        if (is_array($this->roles)) {
            foreach ($this->roles as $roleName) {
                if ($role = Yii::$app->authManager->getRole($roleName)) {
                    Yii::$app->authManager->assign($role, $this->getId());
                }
            }
        }
    }
    
    /**
     * Populate roles attribute with data from RBAC after record loaded from DB 
     */
    public function afterFind()
    {
        $this->roles = $this->getRoles();
    }
    
    /**
     * Get user roles from RBAC
     * @return array
     */
    public function getRoles()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->getId());
        return ArrayHelper::getColumn($roles, 'name', false);
    }
}
