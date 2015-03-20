<?php
/**
 * Created by PhpStorm.
 * User: xiongfanglei
 * Date: 15-3-20
 * Time: 下午1:52
 */

class HomeshopController extends AdminSet{
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
        $pages['countPage'] = AppXzShop::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppXzShop::model()->findAll($criteria);
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

        $taste = Yii::app()->getRequest()->getParam("shop_taste", ""); //味道
        $suround = Yii::app()->getRequest()->getParam("shop_suround", ""); //环境
        $service = Yii::app()->getRequest()->getParam("shop_service", ""); //服务

        $tel = Yii::app()->getRequest()->getParam("shop_tel", ""); //电话
        $money = Yii::app()->getRequest()->getParam("shop_money", ""); //费用


        $add = Yii::app()->getRequest()->getParam("shop_add", ""); //地址
        $tag = Yii::app()->getRequest()->getParam("shop_tag", ""); //标签

        $office = Yii::app()->getRequest()->getParam("shop_office", ""); //游玩时间

        $content = Yii::app()->getRequest()->getParam("shop_content", ""); //游玩时间

        $lng = Yii::app()->getRequest()->getParam("shop_lng", ""); //经度
        $lat = Yii::app()->getRequest()->getParam("shop_lat", ""); //纬度


        $idArr = array();
        if($title!="")
        {
            for($i=0;$i<30;$i++)
            {
                $url = Yii::app()->getRequest()->getParam("url{$i}", ""); //图片地址
                if($url=="")continue;
                $desc1 = Yii::app()->getRequest()->getParam("desc{$i}", ""); //图片描述
                array_push($idArr,array("url"=>$url,"desc"=>$desc1));
            }

            $model = new AppXzShop();
            $model->name = $title;
            $model->star = $star;
            $model->money = $money;
            $model->tag = $tag;
            $model->zone = $zone;
            $model->tel = $tel;
            $model->lng = $lng;
            $model->lat = $lat;
            $model->add = $add;
            $model->office = $office;
            $model->taste = $taste;
            $model->suround = $suround;
            $model->service = $service;
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
            $model = AppXzShop::model()->findByPk($id);
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
            $flname = Yii::app()->params['filetmpcache'].'/'.time().".".$username.".".$_tmp_pathinfo['extension'];
            $dest_file_path = Yii::app()->basePath . '/../public/'.$flname;
            $filepathh = dirname($dest_file_path);
            if (!file_exists($filepathh))
                $b_mkdir = mkdir($filepathh, 0777, true);
            else
                $b_mkdir = true;
            if ($b_mkdir && is_dir($filepathh)) {
                //转存文件到 $dest_file_path路径
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $dest_file_path)) {
                    $img_url ='http://120.24.234.19/api/jixiang/server/project/public/'.$flname;
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
        $id = Yii::app()->getRequest()->getParam("shop_id", ""); //编号
        $title = Yii::app()->getRequest()->getParam("shop_title", ""); //标题
        $zone = Yii::app()->getRequest()->getParam("zone", ""); //标题

        $star = Yii::app()->getRequest()->getParam("shop_star", ""); //星级

        $taste = Yii::app()->getRequest()->getParam("shop_taste", ""); //味道
        $suround = Yii::app()->getRequest()->getParam("shop_suround", ""); //环境
        $service = Yii::app()->getRequest()->getParam("shop_service", ""); //服务

        $tel = Yii::app()->getRequest()->getParam("shop_tel", ""); //电话
        $money = Yii::app()->getRequest()->getParam("shop_money", ""); //费用


        $add = Yii::app()->getRequest()->getParam("shop_add", ""); //地址
        $tag = Yii::app()->getRequest()->getParam("shop_tag", ""); //标签

        $office = Yii::app()->getRequest()->getParam("shop_office", ""); //游玩时间

        $content = Yii::app()->getRequest()->getParam("shop_content", ""); //游玩时间

        $lng = Yii::app()->getRequest()->getParam("shop_lng", ""); //经度
        $lat = Yii::app()->getRequest()->getParam("shop_lat", ""); //纬度
        $model = AppXzShop::model()->findByPk($id);

        $idArr = array();
        if($title!="")
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
            $model->money = $money;
            $model->tag = $tag;
            $model->zone = $zone;
            $model->tel = $tel;
            $model->lng = $lng;
            $model->lat = $lat;
            $model->add = $add;
            $model->office = $office;
            $model->taste = $taste;
            $model->suround = $suround;
            $model->service = $service;
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
    public function actionNewsDel()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            $model = AppXzShop::model()->findByPk($id);
            $td = json_decode($model->img,true);
            if(count($td)>0)
            {
                foreach ($td as $value) {
                    @unlink(Yii::app()->basePath . '/..'.$value['url']);
                }
            }
            if(AppXzShop::model()->deleteByPk($id))
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