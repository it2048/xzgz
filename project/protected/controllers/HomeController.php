<?php

class HomeController extends Controller {

    public $layout = '//layouts/home';

    /**
     * 生成首页
     * 
     */
    public function actionIndex() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        if ($id == "") {
            echo "404 文章不存在啊！";
        } else {
            $allList = AppXzTips::model()->findByPk($id);
            if(!empty($allList))
            {
                $img = $this->img_revert($allList->img);
                $data = array("title"=>$allList->title,"addtime"=>date("Y-m-d",$allList->stime),"source"=>$allList->source,"img_url"=>$img
                ,"type"=>TmpList::$news_list[$allList->type]
                 ,"content"=>$allList->content);
                $this->renderPartial('index',array("model"=>$data));
            }else
            {
                echo "404 文章不存在啊！";
            }
        }
    }

    public function actionGit()
    {
        $secret = Yii::app()->params['gitsec'];
        //获取http 头
        $headers = getallheaders();
        //github发送过来的签名
        $hubSignature = $headers['X-Hub-Signature'];

        list($algo, $hash) = explode('=', $hubSignature, 2);

        // 获取body内容
        $payload = file_get_contents('php://input');

        // Calculate hash based on payload and the secret
        $payloadHash = hash_hmac($algo, $payload, $secret);

        // Check if hashes are equivalent
        if ($hash === $payloadHash) {
            echo exec("/alidata/git.sh xzgz");
        }
    }

    public function actionScenic() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        if ($id == "") {
            echo "404 景点不存在啊！";
        } else {
            $row = AppXzScenic::model()->findByPk($id);
            if (!empty($row)) {
                $content = $row['content'];
                if(!empty($row['img']))
                {
                    $imgList = json_decode($row['img'],true);
                    foreach ($imgList as $key => $value) {
                        $content = '<img src="'.$this->img_revert($value['url']).'" /> <br><br>'.$content;
                    }
                }
                if(date('H:i:s',$row['atime'])=='00:00:00')
                    $time = date('Y-m-d',$row['atime']);
                else
                    $time = date('Y-m-d H:i:s',$row['atime']);

                $data = array("addtime" => $time, "title" => $row['title']);
                $data['content'] = str_replace("<img ", "<img width='100%' ",$content);
                $this->renderPartial('news',array("model"=>$data));
            }
            else
            {
                echo "404 景点不存在啊！";
            }
        }
    }

    /**
     * 成就
     *
     */
    public function actionAchieve() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        if ($id == "") {
            echo "404 成就不存在啊！";
        } else {
            $this->renderPartial('achieve');
        }
    }

    protected function img_revert($str)
    {
        if(trim($str)=="")
        {
            return "";
        }else{
            return "http://120.24.234.19".Yii::app()->request->baseUrl.$str;
        }
    }

    /**
     * 景区
     *
     */
    public function actionJq() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        if ($id == "") {
            echo "404 景区不存在啊！";
        } else {
            $row = AppXzScenic::model()->findByPk($id);
            if (!empty($row)) {
                $content = $row['content'];
                $this->render('jq',array("model"=>$content));
            }
            else
            {
                echo "404 景点不存在啊！";
            }
        }
    }

    /**
     * 商圈
     *
     */
    public function actionSq() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        if ($id == "") {
            echo "404 商店不存在啊！";
        } else {
            $row = AppXzShop::model()->findByPk($id);
            if (!empty($row)) {
                $content = $row['content'];
                $this->render('jq',array("model"=>$content));
            }
            else
            {
                echo "404 商店不存在啊！";
            }
        }
    }
}
