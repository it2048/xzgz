<?php

/**
 * This is the model class for table "xz_comment".
 *
 * The followings are the available columns in table 'xz_comment':
 * @property integer $id
 * @property integer $news_id
 * @property integer $parent_id
 * @property integer $user_id
 * @property string $comment
 * @property string $parent_user
 * @property integer $addtime
 * @property integer $type
 */
class XzComment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return XzComment the static model class
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
		return 'xz_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('news_id, user_id, comment, addtime, type', 'required'),
			array('news_id, parent_id, user_id, addtime, type', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>2048),
			array('parent_user', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, news_id, parent_id, user_id, comment, parent_user, addtime, type', 'safe', 'on'=>'search'),
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
			'news_id' => 'News',
			'parent_id' => 'Parent',
			'user_id' => 'User',
			'comment' => 'Comment',
			'parent_user' => 'Parent User',
			'addtime' => 'Addtime',
			'type' => 'Type',
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
		$criteria->compare('news_id',$this->news_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('parent_user',$this->parent_user,true);
		$criteria->compare('addtime',$this->addtime);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}