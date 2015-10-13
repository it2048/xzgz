<?php

/**
 * This is the model class for table "xz_native".
 *
 * The followings are the available columns in table 'xz_native':
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property integer $star
 * @property string $zone
 * @property string $tel
 * @property string $lat
 * @property string $lng
 * @property string $add
 * @property string $office
 * @property string $admin
 */
class XzNative extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return XzNative the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xz_native';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, admin', 'required'),
			array('star', 'numerical', 'integerOnly'=>true),
			array('name, zone', 'length', 'max'=>64),
			array('tel, lat, lng', 'length', 'max'=>45),
			array('add, office', 'length', 'max'=>128),
			array('admin', 'length', 'max'=>32),
			array('img', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, img, star, zone, tel, lat, lng, add, office, admin', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'img' => 'Img',
			'star' => 'Star',
			'zone' => 'Zone',
			'tel' => 'Tel',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'add' => 'Add',
			'office' => 'Office',
			'admin' => 'Admin',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('star',$this->star);
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('add',$this->add,true);
		$criteria->compare('office',$this->office,true);
		$criteria->compare('admin',$this->admin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}