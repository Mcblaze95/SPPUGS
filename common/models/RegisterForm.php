<?php
namespace common\models;
use yii\base\Model;
class RegisterForm extends Model {
    public $name; public $email; public $password; public $password_confirm;
    public function rules(){return [[['name','email','password','password_confirm'],'required'],['email','email'],['email','unique','targetClass'=>User::class],['password','string','min'=>8],['password_confirm','compare','compareAttribute'=>'password']];}
    public function register(){if(!$this->validate())return false;$u=new User();$u->name=trim($this->name);$u->email=strtolower(trim($this->email));$u->password=password_hash($this->password,PASSWORD_DEFAULT);$u->role='System Administrator';return $u->save(false)?$u:false;}
}
