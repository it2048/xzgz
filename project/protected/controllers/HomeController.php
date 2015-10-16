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
                ,"content"=>$this->revc($allList->content));
                $this->renderPartial('home',array("model"=>$data));
            }else
            {
                echo "404 文章不存在啊！";
            }
        }
    }

    /**
     * 生成首页
     *
     */
    public function actionHome() {
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
                ,"content"=>$this->revc($allList->content));
                $this->renderPartial('home',array("model"=>$data));
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
                $data['content'] = $this->revc($content);
                $this->renderPartial('news',array("model"=>$data));
            }
            else
            {
                echo "404 景点不存在啊！";
            }
        }
    }

    private function revc($ptr)
    {
        $str = preg_replace('/width=\"[0-9]*\"/is',"", $ptr);
        $str = preg_replace("/width[:0-9\s]+px;/is","", $str);
        $str = preg_replace("/text-indent[:0-9\s]+em;/is","", $str);

        return $str;
    }

    /**
     * 成就
     *
     */
    public function actionAchieve() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        $type = Yii::app()->getRequest()->getParam("type", "2");
        if ($id == "") {
            echo "404 成就不存在啊！";
        } else {
            if($type==1)
            {
                $list = AppXzAchieve::model()->findByPk($id);
                if(empty($list))
                    $list = AppXzScenic::model()->findByPk($id);
                $img = $this->img_revert($list->icon);
            }else
            {
                $list = AppXzScenic::model()->findByPk($id);
                if(empty($list))
                    $list = AppXzAchieve::model()->findByPk($id);
                $img = $this->img_revert($list->icon);
            }
            $this->renderPartial('achieve',array("list"=>$img));
        }
    }

    protected function img_revert($str)
    {
        if(trim($str)=="")
        {
            return "";
        }else{
            return Yii::app()->params->url.Yii::app()->request->baseUrl.$str;
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
                $this->render('jq',array("model"=>$this->revc($content)));
            }
            else
            {
                echo "404 景点不存在啊！";
            }
        }
    }

    public function actionBm() {
        $id = Yii::app()->getRequest()->getParam("id", "");
        if ($id == "") {
            echo "404 便民点不存在啊！";
        } else {
            $row = AppXzConvenient::model()->findByPk($id);
            if (!empty($row)) {
                $content = $row['content'];
                $this->render('jq',array("model"=>$this->revc($content)));
            }
            else
            {
                echo "404 便民点不存在啊！";
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
                $this->render('jq',array("model"=>$this->revc($content)));
            }
            else
            {
                echo "404 商店不存在啊！";
            }
        }
    }
}
