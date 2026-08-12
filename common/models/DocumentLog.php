<?php
namespace common\models;
use yii\db\ActiveRecord;
class DocumentLog extends ActiveRecord { public static function tableName(){return 'document_logs';} public function rules(){return [[['document_type','reference_no'],'required'],[['quantity'],'number'],[['date','property_number','description','status','office','party','details_json'],'safe']];} public function getDetails(){return json_decode((string)$this->details_json,true)?:[];}}
