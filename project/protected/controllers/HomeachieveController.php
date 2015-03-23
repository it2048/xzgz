<?php
/**
 * Created by PhpStorm.
 * User: xiongfanglei
 * Date: 15-3-23
 * Time: 下午6:49
 */

class HomeachieveController extends AdminSet
{
    /**
     * 成就管理
     */
    public function actionIndex()
    {
        //print_r(Yii::app()->user->getState('username'));
        //先获取当前是否有页码信息
        $pages['pageNum'] = Yii::app()->getRequest()->getParam("pageNum", 1); //当前页
        $pages['countPage'] = Yii::app()->getRequest()->getParam("countPage", 0); //总共多少记录
        $pages['numPerPage'] = Yii::app()->getRequest()->getParam("numPerPage", 50); //每页多少条数据



        $criteria = new CDbCriteria;

        $pages['countPage'] = AppXzAchieve::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppXzAchieve::model()->findAll($criteria);
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
        $title = Yii::app()->getRequest()->getParam("ach_title", ""); //标题
        $desc = Yii::app()->getRequest()->getParam("ach_desc", ""); //标题

        $icon_url = "";
        if(!empty($_FILES['ach_icon']['name']))
        {
            $img = array("png","jpg","gif");
            $_tmp_pathinfo = pathinfo($_FILES['ach_icon']['name']);
            if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                //设置图片路径
                $flname = '/1'.time().".".$_tmp_pathinfo['extension'];
                $dest_file_path = Yii::app()->basePath . '/../public/upload'.$flname;
                $filepathh = dirname($dest_file_path);
                if (!file_exists($filepathh))
                    $b_mkdir = mkdir($filepathh, 0777, true);
                else
                    $b_mkdir = true;
                if ($b_mkdir && is_dir($filepathh)) {
                    //转存文件到 $dest_file_path路径
                    if (move_uploaded_file($_FILES['ach_icon']['tmp_name'], $dest_file_path)) {
                        $icon_url ='/public/upload'.$flname;
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }

        $hicon_url = "";
        if(!empty($_FILES['ach_hicon']['name']))
        {
            $img = array("png","jpg","gif");
            $_tmp_pathinfo = pathinfo($_FILES['ach_hicon']['name']);
            if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                //设置图片路径
                $flname = '/2'.time().".".$_tmp_pathinfo['extension'];
                $dest_file_path = Yii::app()->basePath . '/../public/upload'.$flname;
                $filepathh = dirname($dest_file_path);
                if (!file_exists($filepathh))
                    $b_mkdir = mkdir($filepathh, 0777, true);
                else
                    $b_mkdir = true;
                if ($b_mkdir && is_dir($filepathh)) {
                    //转存文件到 $dest_file_path路径
                    if (move_uploaded_file($_FILES['ach_hicon']['tmp_name'], $dest_file_path)) {
                        $hicon_url ='/public/upload'.$flname;
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }

        if($msg["code"] == 3)
        {
            $msg['msg'] = "上传的文件格式只能为jpg,png";
        }
        else if($title!="")
        {
            $model = new AppXzAchieve();
            $model->title = $title;
            $model->desc = $desc;

            $model->icon = $icon_url;
            $model->hicon = $hicon_url;
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
            $model = AppXzAchieve::model()->findByPk($id);
        $this->renderPartial('edit',array("models"=>$model));
    }

    /**
     * 保存新闻
     */
    public function actionUpdate()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("ach_id", ""); //编号
        $title = Yii::app()->getRequest()->getParam("ach_title", ""); //标题
        $desc = Yii::app()->getRequest()->getParam("ach_desc", ""); //标题

        $model = AppXzAchieve::model()->findByPk($id);

        $icon_url = $model->icon;
        if(!empty($_FILES['ach_icon']['name']))
        {
            $img = array("png","jpg","gif");
            $_tmp_pathinfo = pathinfo($_FILES['ach_icon']['name']);
            if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                //设置图片路径
                $flname = '/1'.time().".".$_tmp_pathinfo['extension'];
                $dest_file_path = Yii::app()->basePath . '/../public/upload'.$flname;
                $filepathh = dirname($dest_file_path);
                if (!file_exists($filepathh))
                    $b_mkdir = mkdir($filepathh, 0777, true);
                else
                    $b_mkdir = true;
                if ($b_mkdir && is_dir($filepathh)) {
                    //转存文件到 $dest_file_path路径
                    if (move_uploaded_file($_FILES['ach_icon']['tmp_name'], $dest_file_path)) {
                        $icon_url ='/public/upload'.$flname;
                        @unlink(Yii::app()->basePath . '/..'.$model->icon);
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }

        $hicon_url = $model->hicon;
        if(!empty($_FILES['ach_hicon']['name']))
        {
            $img = array("png","jpg","gif");
            $_tmp_pathinfo = pathinfo($_FILES['ach_hicon']['name']);
            if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                //设置图片路径
                $flname = '/2'.time().".".$_tmp_pathinfo['extension'];
                $dest_file_path = Yii::app()->basePath . '/../public/upload'.$flname;
                $filepathh = dirname($dest_file_path);
                if (!file_exists($filepathh))
                    $b_mkdir = mkdir($filepathh, 0777, true);
                else
                    $b_mkdir = true;
                if ($b_mkdir && is_dir($filepathh)) {
                    //转存文件到 $dest_file_path路径
                    if (move_uploaded_file($_FILES['ach_hicon']['tmp_name'], $dest_file_path)) {
                        $hicon_url ='/public/upload'.$flname;
                        @unlink(Yii::app()->basePath . '/..'.$model->hicon);
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }

        if($title!="")
        {

            $model->title = $title;
            $model->desc = $desc;

            $model->icon = $icon_url;
            $model->hicon = $hicon_url;
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
    public function actionDel()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $model = AppXzAchieve::model()->findByPk($id);
            @unlink(Yii::app()->basePath . '/..'.$model->icon);
            @unlink(Yii::app()->basePath . '/..'.$model->hicon);

            if(AppXzAchieve::model()->deleteByPk($id))
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