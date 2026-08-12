<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\DocumentLog;
class FormsController extends Controller {
 public function behaviors(){return ['access'=>['class'=>AccessControl::class,'rules'=>[['allow'=>true,'roles'=>['@']]]]];}
 public function actionIndex(){
  $types=['RIS','IAR','ICS','PAR','Stock Card','Supply Ledger Card','RSMI','Waste Materials Report','RPCI'];$type=in_array(Yii::$app->request->get('type'),$types,true)?Yii::$app->request->get('type'):'RIS';
  if(Yii::$app->request->isPost){$p=Yii::$app->request->post();$ref=trim((string)($p['reference_no']??''));$old=trim((string)($p['old_reference']??''));$tx=Yii::$app->db->beginTransaction();try{if($old)DocumentLog::deleteAll(['document_type'=>$type,'reference_no'=>$old]);foreach(($p['property_number']??[]) as $i=>$number){$desc=trim((string)($p['description'][$i]??''));if(trim((string)$number)===''&&$desc==='')continue;$d=['Fund Cluster'=>trim((string)($p['fund_cluster']??'')),'Division'=>trim((string)($p['office']??'')),'Unit'=>trim((string)($p['unit'][$i]??'')),'Purpose'=>trim((string)($p['purpose']??'')),'Requested By'=>trim((string)($p['requested_by']??'')),'Approved By'=>trim((string)($p['approved_by']??'')),'Issued By'=>trim((string)($p['issued_by']??'')),'Received By'=>trim((string)($p['received_by']??''))];$m=new DocumentLog(['document_type'=>$type,'reference_no'=>$ref,'date'=>$p['form_date']??'','property_number'=>trim((string)$number),'description'=>$desc,'quantity'=>(float)($p['quantity'][$i]??0),'status'=>trim((string)($p['item_status'][$i]??'')),'office'=>trim((string)($p['office']??'')),'party'=>trim((string)($p['party']??'')),'details_json'=>json_encode($d,JSON_UNESCAPED_UNICODE)]);$m->save(false);} $tx->commit();return $this->redirect(['index','type'=>$type,'ref'=>$ref]);}catch(\Throwable $e){$tx->rollBack();throw $e;}}
  $refs=DocumentLog::find()->select(['reference_no','date'=>'MAX(date)','latest_id'=>'MAX(id)','items'=>'COUNT(*)'])->where(['document_type'=>$type])->andWhere(['<>','reference_no',''])->groupBy('reference_no')->orderBy(['date'=>SORT_DESC,'latest_id'=>SORT_DESC,'reference_no'=>SORT_DESC])->limit(500)->asArray()->all();$ref=(string)Yii::$app->request->get('ref',($refs[0]['reference_no']??''));$rows=DocumentLog::find()->where(['document_type'=>$type,'reference_no'=>$ref])->orderBy('id')->all();return $this->render('index',compact('types','type','refs','ref','rows'));
 }
}
