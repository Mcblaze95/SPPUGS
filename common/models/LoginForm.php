<?php
namespace common\models;
use Yii;
use yii\base\Model;
class LoginForm extends Model {
    public $email; public $password; public $rememberMe=true; private $_user;
    public function rules(){return [[['email','password'],'required'],['email','email'],['rememberMe','boolean'],['password','validatePassword']];}
    public function validatePassword($attribute){if(!$this->hasErrors()){ $u=$this->getUser(); if(!$u||!$u->validatePassword($this->password))$this->addError($attribute,'Invalid email or password.');}}
    public function login(){return $this->validate()&&Yii::$app->user->login($this->getUser(),$this->rememberMe?2592000:0);}
    private function getUser(){return $this->_user??=User::findByEmail((string)$this->email);}
}
