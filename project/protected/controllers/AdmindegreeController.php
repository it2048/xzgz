<?php

class AdmindegreeController extends AdminSet
{
    /**
     * 新闻管理
     */
    public function actiondegreeManager()
    {
        //先获取当前是否有页码信息
        $pages['pageNum'] = Yii::app()->getRequest()->getParam("pageNum", 1); //当前页
        $pages['countPage'] = Yii::app()->getRequest()->getParam("countPage", 0); //总共多少记录
        $pages['numPerPage'] = Yii::app()->getRequest()->getParam("numPerPage", 50); //每页多少条数据
        $criteria = new CDbCriteria;
        $pages['countPage'] = AppJxDegree::model()->count($criteria);
        $criteria->limit = $pages['numPerPage'];
        $criteria->offset = $pages['numPerPage'] * ($pages['pageNum'] - 1);
        $criteria->order = 'news_id DESC';
        $allList = AppJxDegree::model()->findAll($criteria);
        $strNew = "";
        $strUser = "";
        foreach($allList as $val)
        {
            $strNew .= $val->news_id.",";
            $strUser .= $val->user_id.",";
        }
        $newApp = array();
        $userApp = array();
        $strNew = rtrim($strNew,",");
        $strUser = rtrim($strUser,",");
        if($strNew != "")
        {
            $newsList = AppJxNews::model()->findAll("id in ({$strNew})");
            foreach($newsList as $val)
            {
                $newApp[$val->id] = $val->title;
            }
        }
        if($strUser != "")
        {
            $userList = AppJxUser::model()->findAll("id in ({$strUser})");
            foreach($userList as $val)
            {
                $userApp[$val->id] = $val->tel;
            }

        }
        $this->renderPartial('degreemanager', array(
            'models' => $allList,
            'pages' => $pages,
            'newApp' => $newApp,
            'userApp' => $userApp,
        ),false,true);
    }

    /**
     * 删除评论
     */
    public function actiondegreeDel()
    {
        $msg = $this->msgcode();
        $user = Yii::app()->getRequest()->getParam("user_id", 0); //用户名
        $news = Yii::app()->getRequest()->getParam("news_id", 0); //用户名
        if($user!=0&&$news!=0)
        {
            $comm = AppJxDegree::model()->find("news_id={$news} and user_id={$user}");
            $news = AppJxNews::model()->findByPk($comm->news_id);
            if(!empty($news))
            {
                if($comm->type==1)
                {
                    $news->like = $news->like-1;
                }elseif($comm->type==2)
                {
                    $news->han = $news->han-1;
                }elseif($comm->type==3)
                {
                    $news->hate = $news->hate-1;
                }
                $news->save();
            }
            if(AppJxDegree::model()->deleteAll("user_id=:uid and news_id=:nid",array(":uid"=>$user,":nid"=>$news)))
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