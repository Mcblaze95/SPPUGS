<?php
namespace common\models;
use yii\db\ActiveRecord;
class Asset extends ActiveRecord { public static function tableName(){return 'assets';} public function rules(){return [[['property_number','article'],'required'],[['quantity','acquisition_amount','book_value'],'number'],[['property_number'],'unique'],[['date_acquired','asset_type','brand_model','serial_number','unit','office','location','accountable_officer','condition','ppe_type','system_status','tracking_notes','last_updated'],'safe']];}}
