<?php

class HomenewsController extends AdminSet
{
    /**
     * 新闻管理
     */
    public function actionIndex()
    {
        //print_r(Yii::app()->user->getState('username'));
        //先获取当前是否有页码信息
        $pages['pageNum'] = Yii::app()->getRequest()->getParam("pageNum", 1); //当前页
        $pages['countPage'] = Yii::app()->getRequest()->getParam("countPage", 0); //总共多少记录
        $pages['numPerPage'] = Yii::app()->getRequest()->getParam("numPerPage", 50); //每页多少条数据
        $criteria = new CDbCriteria;
        $pages['countPage'] = AppXzTips::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppXzTips::model()->findAll($criteria);
        $this->renderPartial('index', array(
            'models' => $allList,
            'pages' => $pages),false,true);
    }
    public function actionSave() {
        $msg = $this->msgcode();
        $model = AppJxConfig::model()->findAll();
        foreach ($model as $value) {
            $mod = AppJxConfig::model()->findByPk($value['name']);
            $mod->value = Yii::app()->getRequest()->getParam($value['name'], "");
            $mod->save();
        }
        $this->msgsucc($msg);
        echo json_encode($msg);
    }

    /**
     * 添加新闻
     */
    public function actionNewsAdd()
    {
        $this->renderPartial('add');
    }

    /**
     * 关闭评论
     */
    public function actionGb()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $tm = AppXzTips::model()->findByPk($id);
            $tm->comtype = 1;
            if($tm->save())
            {
                $this->msgsucc($msg);
            }
            else
                $msg['msg'] = "评论关闭失败";
        }else
        {
            $msg['msg'] = "id不能为空";
        }
        echo json_encode($msg);
    }
    /**
     * 关闭评论
     */
    public function actionDk()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $tm = AppXzTips::model()->findByPk($id);
            $tm->comtype = 0;
            if($tm->save())
            {
                $this->msgsucc($msg);
            }
            else
                $msg['msg'] = "评论开启失败";
        }else
        {
            $msg['msg'] = "id不能为空";
        }
        echo json_encode($msg);
    }


    /**
     * 保存新闻
     */
    public function actionNewsSave()
    {
        $msg = $this->msgcode();
        $type = Yii::app()->getRequest()->getParam("news_type", 1); //类型
        $stime = Yii::app()->getRequest()->getParam("stime", ""); //开始时间
        $etime = Yii::app()->getRequest()->getParam("etime", ""); //结束时间
        $title = Yii::app()->getRequest()->getParam("news_title", ""); //标题
        $content = Yii::app()->getRequest()->getParam("news_content", ""); //内容
$source = Yii::app()->getRequest()->getParam("news_source", ""); //内容
        $tag = Yii::app()->getRequest()->getParam("news_tag", ""); //内容
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //关联
        $username = $this->getUserName(); //用户名
        $img_url = "";
        if(!empty($_FILES['news_img']['name']))
        {
            $img = array("png","jpg");
            $_tmp_pathinfo = pathinfo($_FILES['news_img']['name']);
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
                    if (move_uploaded_file($_FILES['news_img']['tmp_name'], $dest_file_path)) {
                        $img_url ='/public/upload'.$flname;
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }
        if($username!=""&&$title!="")
        {
            
            $model = new AppXzTips();
            $model->title = $title;
            $model->type = $type;
            
            $model->tag = $tag;
            $model->source = $source;
            $model->stime = strtotime($stime);
            $model->endtime = strtotime($etime);
            $model->user = $username;
            $model->img = $img_url;
            $model->content = $content;
            $model->zone_list = implode(",", $zone);
            
            
            if($model->save())
            {
                $this->msgsucc($msg);
                $msg['msg'] = "添加成功";
            }else
            {
                print_r($model->getErrors());
                $msg['msg'] = "存入数据库异常";
            }
        }else{
            $msg['msg'] = "必填项不能为空";
        }
        echo json_encode($msg);
    }

    protected function storeImg($fname,$ftmp,$img_url,$t="0")
    {
        $img = array("png","jpg","gif");
        $_tmp_pathinfo = pathinfo($fname);
        $tmg = "";
        if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
            //设置图片路径
            $flname = 'hero/'.time()."{$t}.".$_tmp_pathinfo['extension'];
            $dest_file_path = Yii::app()->basePath . '/../public/'.$flname;
            $filepathh = dirname($dest_file_path);
            if (!file_exists($filepathh))
                $b_mkdir = mkdir($filepathh, 0777, true);
            else
                $b_mkdir = true;
            if ($b_mkdir && is_dir($filepathh)) {
                //转存文件到 $dest_file_path路径
                if (move_uploaded_file($ftmp, $dest_file_path)) {
                    $tmg ='/public/'.$flname;
                    if($img_url!=""&&strpos($img_url,"http://")===false)
                        @unlink(Yii::app()->basePath . '/..'.$img_url);
                }
            }
        }
        return $tmg;
    }

    /**
     * 编辑新闻
     */
    public function actionNewsEdit()
    {
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        $model = array();
        if($id!="")
            $model = AppXzTips::model()->findByPk($id);
        $this->renderPartial('edit',array("models"=>$model));
    }
    
    /**
     * 上传文件到服务器
     */
    public function actionImgUpload() {
        
        $localName = "";
        $inputName = "filedata";
        $upExt='rar,zip,jpg,jpeg,gif,png,swf';//上传扩展名
        $err = "";
        $msg = "";

        $upfile = @$_FILES[$inputName];
        if (!isset($upfile))
            $err = '文件域的name错误';
        elseif (!empty($upfile['error'])) {
            switch ($upfile['error']) {
                case '1':
                    $err = '文件大小超过了php.ini定义的upload_max_filesize值';
                    break;
                case '2':
                    $err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
                    break;
                case '3':
                    $err = '文件上传不完全';
                    break;
                case '4':
                    $err = '无文件上传';
                    break;
                case '6':
                    $err = '缺少临时文件夹';
                    break;
                case '7':
                    $err = '写文件失败';
                    break;
                case '8':
                    $err = '上传被其它扩展中断';
                    break;
                case '999':
                default:
                    $err = '无有效错误代码';
            }
        } elseif (empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none')
            $err = '无文件上传';
        else {
            $username = md5($this->getUserName()); //用户名
            $_tmp_pathinfo = pathinfo($_FILES[$inputName]['name']);
            //设置图片路径
            $flname = 'upload/'.time().".".$username.".".$_tmp_pathinfo['extension'];
            $dest_file_path = Yii::app()->basePath . '/../public/'.$flname;
            $filepathh = dirname($dest_file_path);
            if (!file_exists($filepathh))
                $b_mkdir = mkdir($filepathh, 0777, true);
            else
                $b_mkdir = true;
            if ($b_mkdir && is_dir($filepathh)) {
                //转存文件到 $dest_file_path路径
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $dest_file_path)) {
                    $img_url ='http://120.24.234.19/api/xzgz/project/public/'.$flname;
                    $msg="{'url':'".$img_url."','localname':'".$this->jsonString($localName)."','id':1}";
                }
            } 
        }
        echo "{'err':'".$this->jsonString($err)."','msg':".$msg."}";
       
    }
    private function jsonString($str)
    {
        return preg_replace("/([\\\\\/'])/",'\\\$1',$str);
    }
    
    /**
     * 保存新闻
     */
    public function actionNewsUpdate()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("news_id", ""); //编号
        $type = Yii::app()->getRequest()->getParam("news_type", 1); //类型
        $stime = Yii::app()->getRequest()->getParam("stime", ""); //开始时间
        $etime = Yii::app()->getRequest()->getParam("etime", ""); //结束时间
        $title = Yii::app()->getRequest()->getParam("news_title", ""); //标题
        $content = Yii::app()->getRequest()->getParam("news_content", ""); //内容
$source = Yii::app()->getRequest()->getParam("news_source", ""); //内容
        $tag = Yii::app()->getRequest()->getParam("news_tag", ""); //内容
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //关联
        $username = $this->getUserName(); //用户名
        $model = AppXzTips::model()->findByPk($id);
        $img_url = $model->img;
        if(!empty($_FILES['news_img']['name']))
        {
            $img = array("png","jpg");
            $_tmp_pathinfo = pathinfo($_FILES['news_img']['name']);
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
                    if (move_uploaded_file($_FILES['news_img']['tmp_name'], $dest_file_path)) {
                        @unlink(Yii::app()->basePath . '/..'.$model->img);
                        $img_url ='/public/upload'.$flname;
                    }
                }
            } else {
                $msg["msg"] = '上传的文件格式只能为jpg,png';
                $msg["code"] = 3;
            }
        }
        if($username!=""&&$title!="")
        {
            $model->title = $title;
            $model->type = $type;
            
            $model->tag = $tag;

            $model->stime = strtotime($stime);
            $model->endtime = strtotime($etime);
            $model->user = $username;
            $model->img = $img_url;
            $model->content = $content;
            $model->zone_list = implode(",", $zone);
            $model->source = $source;
            
            if($model->save())
            {
                $this->msgsucc($msg);
                $msg['msg'] = "更新成功";
            }else
            {
                //print_r($model->getErrors());
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
            if(AppXzTips::model()->deleteByPk($id))
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