<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName(){ return 'users'; }
    public static function findIdentity($id){ return static::findOne($id); }
    public static function findIdentityByAccessToken($token,$type=null){ return null; }
    public static function findByEmail(string $email){ return static::find()->where('LOWER(email)=:email',[':email'=>strtolower(trim($email))])->one(); }
    public function getId(){ return $this->id; }
    public function getAuthKey(){ return hash('sha256',$this->email.$this->password); }
    public function validateAuthKey($authKey){ return hash_equals($this->getAuthKey(),$authKey); }
    public function validatePassword(string $password):bool{ return password_verify($password,$this->password); }
}
