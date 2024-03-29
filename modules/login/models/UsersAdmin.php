<?php

namespace app\modules\login\models;

use Yii;
use app\models\User;
use app\models\AuthItem;
use yii\helpers\ArrayHelper;

//
// UsersAdmin is the model behind the admin page with the CRUD operations
// extends the class Users
// @param confirmPassword
//
class UsersAdmin extends User
{

    public $confirmPassword;

    /**
     *  Merge new rules with the old ones in the User model
     */
    public function rules()
    {
        $rules = parent::rules();
        $result = ArrayHelper::merge($rules, [
                    [['confirmPassword'], 'required'],
                    [['confirmPassword'], 'string', 'max' => 60],
                    ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords don\'t match'],
        ]);

        return $result;
    }

    //
    //Sign up function
    //
    public function signUp()
    {

        if (!$this->save()) {
            return false;
        }
        $id = $this->id;

        //grant roles
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($this->type);
        $result = $auth->assign($role, $id);
        Yii::info('UsersAdmin/signUp/ result of role assignment' . json_encode($result));
        return true;
    }

    //
    // Finds the UsersAdmin model based on its primary key, also returns
    // the foreign keys values
    // @param integer $id
    // @return UsersAdmin the loaded model
    //
    public static function findModelWithUpdatedAndCreated($id)
    {

        //'cb' and 'ub' are aliases for sql join
        $model = UsersAdmin::find($id)
                ->from('user u')
                ->innerJoinWith('createdBy cb')
                ->innerJoinWith('updatedBy ub')
                ->where(['u.id' => $id])
                ->one();

        return $model;
    }

}
