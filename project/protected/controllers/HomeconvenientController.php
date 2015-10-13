<?php

class HomeconvenientController extends Controller
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $pages['pageNum'] = Yii::app()->getRequest()->getParam("pageNum", 1); //当前页
        $pages['countPage'] = Yii::app()->getRequest()->getParam("countPage", 0); //总共多少记录
        $pages['numPerPage'] = Yii::app()->getRequest()->getParam("numPerPage", 50); //每页多少条数据
        $criteria = new CDbCriteria;
        $pages['countPage'] = AppXzConvenient::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppXzConvenient::model()->findAll($criteria);
        $this->renderPartial('index', array(
            'models' => $allList,
            'pages' => $pages),false,true);
	}


    /**
     * 添加新闻
     */
    public function actionAdd()
    {
        $this->renderPartial('add');
    }

    /**
     * 保存新闻
     */
    public function actionSave()
    {
        $msg = $this->msgcode();
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //标题


        $tel = Yii::app()->getRequest()->getParam("shop_tel", ""); //电话


        $add = Yii::app()->getRequest()->getParam("shop_add", ""); //地址
        $tag = Yii::app()->getRequest()->getParam("shop_tag", ""); //标签

        $office = Yii::app()->getRequest()->getParam("shop_office", ""); //游玩时间

        $content = Yii::app()->getRequest()->getParam("shop_content", ""); //游玩时间

        $lng = Yii::app()->getRequest()->getParam("shop_lng", ""); //经度
        $lat = Yii::app()->getRequest()->getParam("shop_lat", ""); //纬度


        $idArr = array();
        if($title!=""||$zone=="")
        {
            for($i=0;$i<30;$i++)
            {
                $url = Yii::app()->getRequest()->getParam("url{$i}", ""); //图片地址
                if($url=="")continue;
                $desc1 = Yii::app()->getRequest()->getParam("desc{$i}", ""); //图片描述
                array_push($idArr,array("url"=>$url,"desc"=>$desc1));
            }

            $model = new AppXzConvenient();
            $model->name = $title;
            $model->zone = $zone;
            $model->tel = $tel;
            $model->lng = $lng;
            $model->lat = $lat;
            $model->add = $add;
            $model->office = $office;
            $model->img = json_encode($idArr);

            $model->content = $content;

            if($model->save())
            {
                $this->msgsucc($msg);
                $msg['msg'] = "添加成功";
            }else
            {

                $msg['msg'] = "存入数据库异常";
            }
        }else{
            $msg['msg'] = "必填项不能为空";
        }
        echo json_encode($msg);
    }



    /**
     * 编辑新闻
     */
    public function actionEdit()
    {
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        $model = array();
        if($id!="")
            $model = AppXzConvenient::model()->findByPk($id);
        $this->renderPartial('edit',array("models"=>$model));
    }


    /**
     * 保存新闻
     */
    public function actionUpdate()
    {

        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("shop_id", ""); //编号
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //标题

        $tel = Yii::app()->getRequest()->getParam("shop_tel", ""); //电话


        $add = Yii::app()->getRequest()->getParam("shop_add", ""); //地址

        $office = Yii::app()->getRequest()->getParam("shop_office", ""); //游玩时间

        $content = Yii::app()->getRequest()->getParam("shop_content", ""); //游玩时间

        $lng = Yii::app()->getRequest()->getParam("shop_lng", ""); //经度
        $lat = Yii::app()->getRequest()->getParam("shop_lat", ""); //纬度
        $model = AppXzConvenient::model()->findByPk($id);

        $idArr = array();
        if($title!=""||$zone=="")
        {
            for($i=0;$i<30;$i++)
            {
                $url = Yii::app()->getRequest()->getParam("url{$i}", ""); //图片地址
                if($url=="")continue;
                $desc1 = Yii::app()->getRequest()->getParam("desc{$i}", ""); //图片描述
                array_push($idArr,array("url"=>$url,"desc"=>$desc1));
            }
            $model->name = $title;
            $model->zone = $zone;
            $model->tel = $tel;
            $model->lng = $lng;
            $model->lat = $lat;
            $model->add = $add;
            $model->office = $office;
            $model->img = json_encode($idArr);

            $model->content = $content;
            if($model->save())
            {
                $this->msgsucc($msg);
                $msg['msg'] = "更新成功";
            }else
            {
                $msg['msg'] = "存入数据库异常";
            }
        }else{
            $msg['msg'] = "必填项不能为空";
        }
        echo json_encode($msg);

    }

    /**
     * 删除新闻
     */
    public function actionDelete()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $model = AppXzConvenient::model()->findByPk($id);
            $td = json_decode($model->img,true);
            if(count($td)>0)
            {
                foreach ($td as $value) {
                    @unlink(Yii::app()->basePath . '/..'.$value['url']);
                }
            }
            if(AppXzConvenient::model()->deleteByPk($id))
            {
                $this->msgsucc($msg);
            }
            else
                $msg['msg'] = "数据删除失败";
        }else
        {
            $msg['msg'] = "id不能为空";
        }
        echo json_encode($msg);
    }

}
