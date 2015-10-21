<?php

class AdminuserController extends AdminSet
{
    /**
     * 新闻管理
     */
    public function actionUserManager()
    {
        //先获取当前是否有页码信息
        $pages['pageNum'] = Yii::app()->getRequest()->getParam("pageNum", 1); //当前页
        $pages['countPage'] = Yii::app()->getRequest()->getParam("countPage", 0); //总共多少记录
        $pages['numPerPage'] = Yii::app()->getRequest()->getParam("numPerPage", 50); //每页多少条数据
        $pages['user_tel'] = Yii::app()->getRequest()->getParam("user_tel", ""); //用户电话
        $criteria = new CDbCriteria;
        !empty($pages['user_tel'])&&$criteria->addSearchCondition('tel',$pages['user_tel']);
        $pages['countPage'] = AppJxUser::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'id DESC';
        $allList = AppJxUser::model()->findAll($criteria);
        $this->renderPartial('usermanager', array(
            'models' => $allList,
            'pages' => $pages),false,true);
    }
    
    /**
     * 重置密码
     */
    public function actionUsermm()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        $model = array();
        if($id!="")
        {
            $model = AppJxUser::model()->findByPk($id);
            $model->password = md5("123456");
            if($model->save())
                $this->msgsucc($msg);
        }else
        {
            $msg['msg'] = "帐号不能为空";
        }
        echo json_encode($msg);
    }
    /**
     * 封号
     */
    public function actionUserfh()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        $model = array();
        if($id!="")
        {
            $model = AppJxUser::model()->findByPk($id);
            $model->type = 1;
            $model->fhtime = time();
            if($model->save())
                $this->msgsucc($msg);
        }else
        {
            $msg['msg'] = "帐号不能为空";
        }
        echo json_encode($msg);
    }
    /**
     * 解封
     */
    public function actionUserjf()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        $model = array();
        if($id!="")
        {
            $model = AppJxUser::model()->findByPk($id);
            $model->type = 0;
            $model->fhtime = time();
            if($model->save())
                $this->msgsucc($msg);
        }else
        {
            $msg['msg'] = "帐号不能为空";
        }
        echo json_encode($msg);
    }

    /**
     * 删除新闻
     */
    public function actionUserDel()
    {
        $msg = $this->msgcode();
        $id = Yii::app()->getRequest()->getParam("id", 0); //用户名
        if($id!=0)
        {
            if(AppJxUser::model()->deleteByPk($id))
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


    public function actionExptp()
    {
        $allList = AppJxUser::model()->findAll();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="行走甘孜用户列表.csv"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        // 输出Excel列名信息
        $head = array('编号','电话','昵称','创建时间','账号状态');
        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head[$i] = iconv('utf-8', 'gbk', $v);
        }
        // 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);
        // 计数器
        $cnt = 0;
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;

        foreach($allList as $value)
        {
            $cnt ++;
            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                $cnt = 0;
            }
            $row = array($value->id,$value->tel,$value->uname,date("Y年m月d日 H:i:s",$value->ctime),$value->type==1?"冻结":"");
            foreach ($row as $i => $v) {
                // CSV的Excel支持GBK编码，一定要转换，否则乱码
                $row[$i] = iconv('utf-8', 'gbk', $v);
            }
            //file_put_contents('/Applications/XAMPP/xamppfiles/htdocs/t.log',print_r($row,true),8);
            fputcsv($fp, $row);
        }
    }
}