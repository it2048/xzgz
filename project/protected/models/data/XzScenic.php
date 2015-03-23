<?php

/**
 * This is the model class for table "xz_scenic".
 *
 * The followings are the available columns in table 'xz_scenic':
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property string $content
 * @property integer $top
 * @property string $mp3
 * @property integer $atime
 * @property integer $ptime
 * @property string $img
 * @property string $add
 * @property string $x
 * @property string $y
 * @property string $zone
 * @property string $icon
 * @property string $lat
 * @property string $lng
 * @property string $around
 * @property string $hicon
 */
class XzScenic extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return XzScenic the static model class
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
		return 'xz_scenic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, top, atime, zone, lat, lng, around', 'required'),
			array('top, atime, ptime', 'numerical', 'integerOnly'=>true),
			array('title, mp3, add', 'length', 'max'=>128),
			array('x, y, zone', 'length', 'max'=>32),
			array('icon, hicon', 'length', 'max'=>64),
			array('lat, lng, around', 'length', 'max'=>45),
			array('desc, img', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, desc, content, top, mp3, atime, ptime, img, add, x, y, zone, icon, lat, lng, around, hicon', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'desc' => 'Desc',
			'content' => 'Content',
			'top' => 'Top',
			'mp3' => 'Mp3',
			'atime' => 'Atime',
			'ptime' => 'Ptime',
			'img' => 'Img',
			'add' => 'Add',
			'x' => 'X',
			'y' => 'Y',
			'zone' => 'Zone',
			'icon' => 'Icon',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'around' => 'Around',
			'hicon' => 'Hicon',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('top',$this->top);
		$criteria->compare('mp3',$this->mp3,true);
		$criteria->compare('atime',$this->atime);
		$criteria->compare('ptime',$this->ptime);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('add',$this->add,true);
		$criteria->compare('x',$this->x,true);
		$criteria->compare('y',$this->y,true);
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('around',$this->around,true);
		$criteria->compare('hicon',$this->hicon,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}