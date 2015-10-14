<?php
/**
 * Created by PhpStorm.
 * User: xiongfanglei
 * Date: 15-3-20
 * Time: 下午1:52
 */

class HomenativeController extends AdminSet{
    /**
     * 景点管理
     */
    public function actionIndex()
    {
        //print_r(Yii::app()->user->getState('username'));
        //先获取当前是否有页码信息
        $pages['pageNum'] = Yii::app()->getRequest()->getParam("pageNum", 1); //当前页
        $pages['countPage'] = Yii::app()->getRequest()->getParam("countPage", 0); //总共多少记录
        $pages['numPerPage'] = Yii::app()->getRequest()->getParam("numPerPage", 50); //每页多少条数据
        $criteria = new CDbCriteria;
        $pages['countPage'] = AppXzNative::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppXzNative::model()->findAll($criteria);
        $this->renderPartial('index', array(
            'models' => $allList,
            'pages' => $pages),false,true);
    }

    /**
     * 添加新闻
     */
    public function actionNewsAdd()
    {
        $this->renderPartial('add');
    }

    /**
     * 保存新闻
     */
    public function actionNewsSave()
    {
        $msg = $this->msgcode();
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //标题

        $star = Yii::app()->getRequest()->getParam("shop_star", ""); //星级


        $tel = Yii::app()->getRequest()->getParam("shop_tel", ""); //电话


        $add = Yii::app()->getRequest()->getParam("shop_add", ""); //地址

        $office = Yii::app()->getRequest()->getParam("shop_office", ""); //游玩时间


        $lng = Yii::app()->getRequest()->getParam("shop_lng", ""); //经度
        $lat = Yii::app()->getRequest()->getParam("shop_lat", ""); //纬度


        $idArr = array();
        if($title!=""&&$zone!="")
        {
            for($i=0;$i<30;$i++)
            {
                $url = Yii::app()->getRequest()->getParam("url{$i}", ""); //图片地址
                if($url=="")continue;
                $desc1 = Yii::app()->getRequest()->getParam("desc{$i}", ""); //图片描述
                array_push($idArr,array("url"=>$url,"desc"=>$desc1));
            }

            $model = new AppXzNative();
            $model->name = $title;
            $model->star = $star;

            $model->zone = $zone;
            $model->tel = $tel;
            $model->lng = $lng;
            $model->lat = $lat;
            $model->add = $add;
            $model->office = $office;
            $model->admin = $this->getUserName();


            $model->img = json_encode($idArr);


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
    public function actionNewsEdit()
    {
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        $model = array();
        if($id!="")
            $model = AppXzNative::model()->findByPk($id);
        $this->renderPartial('edit',array("models"=>$model));
    }


    /**
     * 保存新闻
     */
    public function actionNewsUpdate()
    {

        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("shop_id", ""); //编号
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //标题

        $star = Yii::app()->getRequest()->getParam("shop_star", ""); //星级



        $tel = Yii::app()->getRequest()->getParam("shop_tel", ""); //电话


        $add = Yii::app()->getRequest()->getParam("shop_add", ""); //地址

        $office = Yii::app()->getRequest()->getParam("shop_office", ""); //游玩时间


        $lng = Yii::app()->getRequest()->getParam("shop_lng", ""); //经度
        $lat = Yii::app()->getRequest()->getParam("shop_lat", ""); //纬度
        $model = AppXzNative::model()->findByPk($id);

        $idArr = array();
        if($title!=""&&$zone!="")
        {
            for($i=0;$i<30;$i++)
            {
                $url = Yii::app()->getRequest()->getParam("url{$i}", ""); //图片地址
                if($url=="")continue;
                $desc1 = Yii::app()->getRequest()->getParam("desc{$i}", ""); //图片描述
                array_push($idArr,array("url"=>$url,"desc"=>$desc1));
            }
            $model->name = $title;
            $model->star = $star;
            $model->zone = $zone;
            $model->tel = $tel;
            $model->lng = $lng;
            $model->lat = $lat;
            $model->add = $add;
            $model->office = $office;
            $model->img = json_encode($idArr);

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
    public function actionNewsDel()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $model = AppXzNative::model()->findByPk($id);
            $td = json_decode($model->img,true);
            if(count($td)>0)
            {
                foreach ($td as $value) {
                    @unlink(Yii::app()->basePath . '/..'.$value['url']);
                }
            }
            if(AppXzNative::model()->deleteByPk($id))
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