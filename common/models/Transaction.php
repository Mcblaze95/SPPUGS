<?php
namespace common\models;
use yii\db\ActiveRecord;
class Transaction extends ActiveRecord {public static function tableName(){return 'transactions';} public function rules(){return [[['date','type','description'],'required'],[['quantity'],'number'],[['property_number','from_office','to_office','to_person','reference_no','status','remarks','created_by','timestamp','transaction_id'],'safe']];}}
