<?php

/**
 * This is the model class for table "xz_shop".
 *
 * The followings are the available columns in table 'xz_shop':
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property integer $star
 * @property string $money
 * @property string $tag
 * @property string $zone
 * @property string $tel
 * @property string $lat
 * @property string $lng
 * @property string $add
 * @property string $office
 * @property string $taste
 * @property string $suround
 * @property string $service
 * @property string $content
 */
class XzShop extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return XzShop the static model class
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
		return 'xz_shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('star', 'numerical', 'integerOnly'=>true),
			array('name, zone', 'length', 'max'=>64),
			array('money, tag, tel, lat, lng', 'length', 'max'=>45),
			array('add, office', 'length', 'max'=>128),
			array('taste, suround, service', 'length', 'max'=>8),
			array('img, content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, img, star, money, tag, zone, tel, lat, lng, add, office, taste, suround, service, content', 'safe', 'on'=>'search'),
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
			'money' => 'Money',
			'tag' => 'Tag',
			'zone' => 'Zone',
			'tel' => 'Tel',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'add' => 'Add',
			'office' => 'Office',
			'taste' => 'Taste',
			'suround' => 'Suround',
			'service' => 'Service',
			'content' => 'Content',
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
		$criteria->compare('money',$this->money,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('add',$this->add,true);
		$criteria->compare('office',$this->office,true);
		$criteria->compare('taste',$this->taste,true);
		$criteria->compare('suround',$this->suround,true);
		$criteria->compare('service',$this->service,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}