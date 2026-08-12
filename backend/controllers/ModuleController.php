<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\{Asset,Transaction,DocumentLog,Movement,Repair,PhysicalCount,AuditLog};

class ModuleController extends Controller {
 public function behaviors(){return ['access'=>['class'=>AccessControl::class,'rules'=>[['allow'=>true,'roles'=>['@']]]]];}
 private function audit($action,$sheet,$key,$notes=''){Yii::$app->db->createCommand()->insert('audit_log',['timestamp'=>date('Y-m-d H:i:s'),'user'=>Yii::$app->user->identity->name,'action'=>$action,'sheet'=>$sheet,'record_key'=>$key,'field'=>'Record','new_value'=>$action,'notes'=>$notes])->execute();}
 public function actionAssets(){
  $model=null;if($id=Yii::$app->request->get('edit'))$model=Asset::findOne((int)$id);if(!$model&&Yii::$app->request->get('new'))$model=new Asset();
  if($model&&$model->load(Yii::$app->request->post())){$model->last_updated=date('Y-m-d');if($model->save()){ $this->audit($model->isNewRecord?'Create':'Edit','Assets',$model->property_number);Yii::$app->session->setFlash('success','Asset record saved.');return $this->redirect(['assets']);}}
  $query=Asset::find();$q=trim((string)Yii::$app->request->get('q'));if($q)$query->andWhere(['or',['like','property_number',$q],['like','article',$q],['like','brand_model',$q],['like','serial_number',$q]]);if($o=Yii::$app->request->get('office'))$query->andWhere(['office'=>$o]);if($s=Yii::$app->request->get('status'))$query->andWhere(['system_status'=>$s]);$rows=$query->orderBy('property_number')->limit(250)->all();return $this->render('assets',compact('model','rows','q'));
 }
 public function actionTransactions(){ $m=new Transaction();if($m->load(Yii::$app->request->post())){$m->transaction_id='TRX-'.date('Ymd-His');$m->created_by=Yii::$app->user->identity->name;$m->timestamp=date('Y-m-d H:i:s');if($m->save()){ $this->audit('Create','Transactions',$m->transaction_id);return $this->redirect(['transactions']);}}$rows=Transaction::find()->orderBy(['id'=>SORT_DESC])->limit(300)->all();return $this->render('transactions',['model'=>$m,'rows'=>$rows]);}
 public function actionDocuments(){ $type=(string)Yii::$app->request->get('type');$q=DocumentLog::find();if($type)$q->where(['document_type'=>$type]);$rows=$q->orderBy(['date'=>SORT_DESC,'id'=>SORT_DESC])->limit(300)->all();return $this->render('documents',compact('rows','type'));}
 public function actionMovements(){return $this->generic('Transfer & Disposal',Movement::find()->orderBy(['id'=>SORT_DESC])->limit(300)->asArray()->all());}
 public function actionRepairs(){return $this->generic('Repair History',Repair::find()->orderBy(['id'=>SORT_DESC])->limit(300)->asArray()->all());}
 public function actionCounts(){return $this->generic('Physical Count',PhysicalCount::find()->orderBy(['id'=>SORT_DESC])->limit(300)->asArray()->all());}
 public function actionAudit(){ $q=AuditLog::find();if($s=trim((string)Yii::$app->request->get('q')))$q->andWhere(['or',['like','record_key',$s],['like','user',$s],['like','sheet',$s]]);return $this->render('generic',['title'=>'Audit Log','rows'=>$q->orderBy(['id'=>SORT_DESC])->limit(300)->asArray()->all()]);}
 private function generic($title,$rows){return $this->render('generic',compact('title','rows'));}
}
