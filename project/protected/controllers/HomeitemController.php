<?php

class HomeitemController extends AdminSet
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
        $pages['countPage'] = AppXzItem::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppXzItem::model()->findAll($criteria);


        $str_id = '';
        $userApp = array();
        foreach($allList as $val)
        {
            $str_id .=$val->p_id.',';
        }
        if($str_id!='')
        {
            $str_id = rtrim($str_id,',');
            $nativeList = AppXzNative::model()->findAll("id in($str_id)");
            foreach($nativeList as $val)
            {
                $userApp[$val->id] = $val->name;
            }
        }

        $this->renderPartial('index', array(
            'models' => $allList,'userApp'=>$userApp,
            'pages' => $pages),false,true);
    }


    /**
     * 添加新闻
     */
    public function actionAdd()
    {
        $username = $this->getUserName(); //用户名
        $shop = AppXzNative::model()->findAll("admin='{$username}'");
        $shopList = array();
        if(empty($shop))
        {
            echo '请先创建商店再添加商品';die();
        }else
        {
            foreach($shop as $val)
            {
                array_push($shopList,array('id'=>$val->id,'name'=>$val->name));
            }
        }
        $this->renderPartial('add',array(
        'shopList' => $shopList));
    }

    /**
     * 保存新闻
     */
    public function actionSave()
    {
        $msg = $this->msgcode();
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $price = Yii::app()->getRequest()->getParam("shop_price", ""); //电话
        $name = Yii::app()->getRequest()->getParam("shop_name", ""); //地址
        $img_url = "";
        $username = $this->getUserName(); //用户名
        if(!empty($_FILES['shop_img']['name']))
        {
            $img = array("png","jpg");
            $_tmp_pathinfo = pathinfo($_FILES['shop_img']['name']);
            if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                //设置图片路径
                $flname = Yii::app()->params['filetmpcache'].'/'.time().".".md5($username).".".$_tmp_pathinfo['extension'];
                $dest_file_path = Yii::app()->basePath . '/../public/upload'.$flname;
                $filepathh = dirname($dest_file_path);
                if (!file_exists($filepathh))
                    $b_mkdir = mkdir($filepathh, 0777, true);
                else
                    $b_mkdir = true;
                if ($b_mkdir && is_dir($filepathh)) {
                    //转存文件到 $dest_file_path路径
                    if (move_uploaded_file($_FILES['shop_img']['tmp_name'], $dest_file_path)) {
                        $img_url ='/public/upload'.$flname;
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }

        if($title!=""&&$img_url!="")
        {
            $model = new AppXzItem();
            $model->name = $title;
            $model->price = $price;
            $model->p_id = $name;
            $model->admin = $this->getUserName();
            $model->img = $img_url;

            if($model->save())
            {
                $this->msgsucc($msg);
                $msg['msg'] = "添加成功";
            }else
            {

                $msg['msg'] = "存入数据库异常";
            }
        }else{
            $msg['msg'] = "图片不能为空";
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

        $username = $this->getUserName(); //用户名
        $shop = AppXzNative::model()->findAll("admin='{$username}'");
        $shopList = array();
        if(empty($shop))
        {
            echo '请先创建商店再添加商品';die();
        }else
        {
            foreach($shop as $val)
            {
                array_push($shopList,array('id'=>$val->id,'name'=>$val->name));
            }
        }
        if($id!="")
            $model = AppXzItem::model()->findByPk($id);
        $this->renderPartial('edit',array("models"=>$model,'shopList'=>$shopList));
    }


    /**
     * 保存新闻
     */
    public function actionUpdate()
    {
        $id = Yii::app()->getRequest()->getParam("shop_id", ""); //编号
        $msg = $this->msgcode();
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $price = Yii::app()->getRequest()->getParam("shop_price", ""); //电话
        $name = Yii::app()->getRequest()->getParam("shop_name", ""); //地址

        $model = AppXzItem::model()->findByPk($id);
        $img_url = $model->img;
        $username = $this->getUserName(); //用户名
        if(!empty($_FILES['shop_img']['name']))
        {
            $img = array("png","jpg");
            $_tmp_pathinfo = pathinfo($_FILES['shop_img']['name']);
            if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                //设置图片路径
                $flname = Yii::app()->params['filetmpcache'].'/'.time().".".md5($username).".".$_tmp_pathinfo['extension'];
                $dest_file_path = Yii::app()->basePath . '/../public/upload'.$flname;
                $filepathh = dirname($dest_file_path);
                if (!file_exists($filepathh))
                    $b_mkdir = mkdir($filepathh, 0777, true);
                else
                    $b_mkdir = true;
                if ($b_mkdir && is_dir($filepathh)) {
                    //转存文件到 $dest_file_path路径
                    if (move_uploaded_file($_FILES['shop_img']['tmp_name'], $dest_file_path)) {
                        @unlink(Yii::app()->basePath . '/..'.$model->img);
                        $img_url ='/public/upload'.$flname;
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }
        if($title!=""&&$img_url!="")
        {
            $model->name = $title;
            $model->price = $price;
            $model->p_id = $name;
            $model->admin = $this->getUserName();
            $model->img = $img_url;

            if($model->save())
            {
                $this->msgsucc($msg);
                $msg['msg'] = "修改成功";
            }else
            {

                $msg['msg'] = "存入数据库异常";
            }
        }else{
            $msg['msg'] = "图片不能为空";
        }
        echo json_encode($msg);

    }

    /**
     * 删除新闻
     */
    public function actionDel()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $model = AppXzItem::model()->findByPk($id);
            @unlink(Yii::app()->basePath . '/..'.$model['img']);
            if(AppXzItem::model()->deleteByPk($id))
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
