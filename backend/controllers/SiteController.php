<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\{LoginForm,RegisterForm,Asset};

class SiteController extends Controller {
 public function behaviors(){return ['access'=>['class'=>AccessControl::class,'only'=>['dashboard','logout'],'rules'=>[['actions'=>['dashboard','logout'],'allow'=>true,'roles'=>['@']]]]];}
 public function actions(){return ['error'=>['class'=>'yii\web\ErrorAction']];}
 public function actionIndex(){return $this->redirect(['dashboard']);}
 public function actionLogin(){if(!Yii::$app->user->isGuest)return $this->redirect(['dashboard']);$m=new LoginForm();if($m->load(Yii::$app->request->post())&&$m->login())return $this->redirect(['dashboard']);$this->layout='login';return $this->render('login',['model'=>$m]);}
 public function actionRegister(){if(!Yii::$app->user->isGuest)return $this->redirect(['dashboard']);$m=new RegisterForm();if($m->load(Yii::$app->request->post())&&($u=$m->register())){Yii::$app->session->setFlash('success','Account created. You may now sign in.');return $this->redirect(['login']);}$this->layout='login';return $this->render('register',['model'=>$m]);}
 public function actionLogout(){Yii::$app->user->logout();return $this->redirect(['login']);}
 public function actionDashboard(){
  $q=Asset::find();$total=(int)(clone $q)->count();$amount=(float)(clone $q)->sum('acquisition_amount');$book=(float)(clone $q)->sum('book_value');$active=(int)(clone $q)->where(['system_status'=>'Active'])->count();$riskStatuses=['For Repair','Disposed','Missing','Unserviceable'];$risk=(int)(clone $q)->where(['system_status'=>$riskStatuses])->count();
  $offices=Asset::find()->select(['office','c'=>'COUNT(*)'])->groupBy('office')->orderBy(['c'=>SORT_DESC])->asArray()->all();$statuses=Asset::find()->select(['system_status','c'=>'COUNT(*)'])->groupBy('system_status')->orderBy(['c'=>SORT_DESC])->asArray()->all();$rows=[];$title='';
  if(Yii::$app->request->get('attention')){$rows=Asset::find()->where(['system_status'=>$riskStatuses])->orderBy(['system_status'=>SORT_ASC,'property_number'=>SORT_ASC])->limit(500)->all();$title='Attention Needed';}elseif($office=trim((string)Yii::$app->request->get('office'))){$rows=Asset::find()->where(['office'=>$office])->orderBy('property_number')->limit(500)->all();$title='Assets in '.$office;}
  return $this->render('dashboard',compact('total','amount','book','active','risk','offices','statuses','rows','title'));
 }
}
