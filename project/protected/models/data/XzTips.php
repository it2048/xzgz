<?php

/**
 * This is the model class for table "xz_tips".
 *
 * The followings are the available columns in table 'xz_tips':
 * @property integer $id
 * @property string $title
 * @property string $tag
 * @property integer $stime
 * @property integer $endtime
 * @property string $img
 * @property string $zone_list
 * @property integer $type
 * @property string $content
 * @property string $user
 */
class XzTips extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return XzTips the static model class
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
		return 'xz_tips';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, stime, endtime, zone_list, content, user', 'required'),
			array('stime, endtime, type', 'numerical', 'integerOnly'=>true),
			array('title, img', 'length', 'max'=>128),
			array('tag, user', 'length', 'max'=>64),
			array('zone_list', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, tag, stime, endtime, img, zone_list, type, content, user', 'safe', 'on'=>'search'),
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
			'tag' => 'Tag',
			'stime' => 'Stime',
			'endtime' => 'Endtime',
			'img' => 'Img',
			'zone_list' => 'Zone List',
			'type' => 'Type',
			'content' => 'Content',
			'user' => 'User',
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
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('stime',$this->stime);
		$criteria->compare('endtime',$this->endtime);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('zone_list',$this->zone_list,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('user',$this->user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}