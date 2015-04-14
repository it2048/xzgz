<?php

class V0Controller extends Controller
{
    public $utrl = "http://120.24.234.19";

    public $user_id = "";
    public $token = "";
    /**
     * 生成首页
     *
     */
    public function actionIndex()
    {
        $msg = $this->msgcode();
        $sign =Yii::app()->getRequest()->getParam("sign");
        $data =Yii::app()->getRequest()->getParam("data");
        $salt = "xFlaSd!$&258";
        if($sign==md5($data.$salt))
        {
            $reques = json_decode($data,true);
            $this->user_id = empty($reques['user_id'])?"":$reques['user_id'];
            $this->token = empty($reques['token'])?"":$reques['token'];
            if(!call_user_func(array('V0Controller',$reques['action']),$reques))
            {
                die();
            }
            else
            {
                $msg['msg'] = "请求的action不存在";
            }
        }
        echo json_encode($msg);
    }


    private function setNotice()
    {


    }
    private function getNotice(&$msg)
    {
        if(empty($this->user_id)||empty($this->token)||!$this->chkToken($this->user_id,$this->token))
        {
            $msg['notice'] = array("name"=>"");
        }else{
            $arr = array("name"=>"");
            $mld = AppXzFly::model()->find("status=1");
            if(empty($mld))
            {
                $mpd = AppXzAlias::model()->find("status=1");
                if(!empty($mpd))
                {
                    $mpd->status = 0;
                    $mpd->save();
                    $model = AppXzAchieve::model()->find("id={$mpd->alias_id}");
                    $arr = array("name"=>"恭喜！【{$model->title}】已达成！");
                }
            }else{
                $model = AppXzScenic::model()->find("id={$mld->zone}");
                $arr = array("name"=>"恭喜！【浏览过{$model->title}】已达成！");
                $mld->status = 0;
                $mld->save();
            }
            $msg['notice'] = $arr;
        }

    }
    public function test($arr)
    {
        $msg = $this->msgcode();
        $x = $arr['user'];
        $y = $arr['id'];

        $this->msgsucc($msg);
        $msg['data'] = array("user"=>$x,"id"=>$y);
        echo json_encode($msg);
    }
    
    public function setplay($arr)
    {
        $msg = $this->msgcode();
        $id = $arr['scenic_id'];
        $x = $arr['y'];
        $y = $arr['x'];
        $user_id = $arr['user_id'];
        $token = $arr['token'];

        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{

            $model = AppXzScenic::model()->findByPk($id);
            if(empty($model))
            {
                $msg['msg'] = '景点未找到';
            }
            else
            {
                $ar = $this->getDistance($y,$x,$model->lng,$model->lat);
                if($ar<$model->around)
                {
                    
                    $pk = AppXzFly::model()->findAll("zone='{$id}' and user_id={$user_id}");
                    if(empty($pk))
                    {
                        $ml = new AppXzFly();
                        $ml->zone = $id;
                        $ml->user_id = $user_id;
                        $ml->time = time();
                        $ml->save();
                        $this->msgsucc($msg);
                    }
                    else
                    {
                        $msg['msg'] = '你已经到过这里了';
                    }
                }else
                {
                    $msg['msg'] = '当前不在景点范围内';
                }

            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    public function getzone($arr)
    {
        $msg = $this->msgcode();
        $x = $arr['y'];
        $y = $arr['x'];
        $api = sprintf("http://api.map.baidu.com/geocoder/v2/?ak=0QDaLukGIKr22SwQKTWNxGSz&location=%s,%s&output=json&pois=0",$x,$y);
        $data = json_decode(RemoteCurl::getInstance()->get($api),true);

        if($data['status']==0)
        {
            $zone = $data['result']['addressComponent']['district'];
            $city = $data['result']['addressComponent']['city'];
            if($city!="甘孜藏族自治州")
            {
                $msg['code'] = 3;
                $msg['msg'] = "您当前不在甘孜";
            }
            $arr  = array_keys(TmpList::$zone_list,$zone);
            if(!empty($arr[0]))
            {
                $this->msgsucc($msg);
                $msg['data'] = $arr[0];
            }else
            {
                $msg['code'] = 3;
                $msg['msg'] = "您当前不在甘孜";
            }
        }else{
            $msg['code'] = 4;
            $msg['msg'] = "无法定位，请开启GPS";
        }
        $this->getNotice($msg);
        echo json_encode($msg);

    }

    /**
     * 首页新闻接口
     * @param $arr
     */
    public function homenews($arr)
    {
        $msg = $this->msgcode();
        $zone = $arr['zonecode'];
        $slideArr = array();
        $tipArr = array();
        $helpArr = array();

        $slide = AppXzTips::model()->findAll("type=:tp and img!='' and FIND_IN_SET('{$zone}',zone_list) and stime<:tm and endtime>:tm order by stime desc limit 0,4",array(":tp"=>3,
            ":tm"=>time()
        ));
        foreach ($slide as $v ){
            $pass = empty($v['img'])?"":$this->utrl.Yii::app()->request->baseUrl.$v['img'];
            array_push($slideArr,array("news_id"=>$v['id'],"img"=>$pass,"title"=>$v['title']));
        }

        $more = 0;
        $tip = AppXzTips::model()->findAll("type=:tp and FIND_IN_SET('{$zone}',zone_list) and stime<:tm and endtime>:tm order by stime desc limit 0,3",array(":tp"=>1,
            ":tm"=>time()
        ));
        foreach ($tip as $k=>$v ){
            if($k>1){
                $more = 1;
                break;
            }
            array_push($tipArr,array("news_id"=>$v['id'],"title"=>$v['title'],"tag"=>$v['tag']));
        }
        $help = AppXzTips::model()->findAll("type=:tp and FIND_IN_SET('{$zone}',zone_list) order by stime desc limit 0,6",array(":tp"=>2));
        foreach ($help as $v ){
            array_push($helpArr,array("news_id"=>$v['id'],"title"=>$v['title']));
        }

        $zname = TmpList::$zone_list[$zone];
        $url = "http://api.map.baidu.com/telematics/v3/weather?location={$zname}&output=json&ak=0QDaLukGIKr22SwQKTWNxGSz";

        $data = json_decode(RemoteCurl::getInstance()->get($url),true);
        $allList = array();
        if($data['status']=="success")
        {
            if(!empty($data['results'][0]['weather_data'])&&is_array($data['results'][0]['weather_data']))
            {
                $this->msgsucc($msg);
                $model = $data['results'][0]['weather_data'];
                $start = strpos($model[0]['date'],"：");
                if(strpos($model[0]['date'],"℃")===false)
                {
                    $crent = $model[0]['temperature'];
                    $crent = str_replace("℃","",$crent);
                }else
                {
                    $crent = mb_substr($model[0]['date'],$start+3,-1);
                }
                $day = "星期".mb_substr($model[0]['date'],3,3);
                $allList = array("temperature"=>$crent,"date"=>date("Y.m.d",time()),
                    "imgcode"=>$this->getW($model[0]['weather']),"weather"=>$model[0]['weather'],"day"=>$day
                );
            }
        }
        $this->msgsucc($msg);
        $msg['data'] = array("slide"=>$slideArr,"tip"=>array("more"=>$more,"list"=>$tipArr),"help"=>$helpArr,"weather"=>$allList);
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     * 单位米
     */
    private function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        /*
          Convert these degrees to radians
          to work with the formula
        */

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }

    public function rockarock($arr)
    {
        $msg = $this->msgcode();
        $x = $arr['y'];
        $y = $arr['x'];
        $api = sprintf("http://api.map.baidu.com/geocoder/v2/?ak=0QDaLukGIKr22SwQKTWNxGSz&location=%s,%s&output=json&pois=0",$x,$y);
        $data = json_decode(RemoteCurl::getInstance()->get($api),true);
        if($data['status']==0)
        {
            $block = $data['result']['formatted_address'];  //具体到每个街道
            $model = AppXzScenic::model()->findAll("1=1 order by rand() limit 1");

            $allList = $model[0];
            $kilm = $this->getDistance($x,$y,$allList['lng'],$allList['lat']);
            $kilm = $kilm>1000?(floor($kilm/1000))."千米":$kilm."米";

            $img = "";
            if(!empty($allList['img']))
            {
                $imgArr = json_decode($allList['img'],true);
                $img = empty($imgArr[0])?"":$this->utrl.Yii::app()->request->baseUrl.$imgArr[0]['url'];
            }
            $this->msgsucc($msg);
            $msg['data'] = array(
                "scenic_id"=>$allList['id'],
                "name"=>$allList['title'],
                "img"=>$img,
                "address"=>$allList['add'],
                "desc"=>$allList['desc'],
                "top"=>$allList['top'],
                "block"=>$block,
                "distance"=>$kilm,
                "ptime"=>$allList['ptime'],
            );
        }else{
            $msg['code'] = 2;
            $msg['msg'] = "无法定位，请开启GPS";
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 获取新闻列表和分页接口
     * @param $arr
     *
     */
    public function getnewslist($arr)
    {
        $msg = $this->msgcode();
        $type = $arr['news_type'];
        $zone = $arr['zonecode'];
        $page = empty($arr['page'])?1:$arr['page'];
        if($page<1)$page=1;

        $criteria = new CDbCriteria;
        $criteria->addCondition("type={$type} and FIND_IN_SET('{$zone}',zone_list)");
        $tm = time();
        if($type==1||$type==3)
             $criteria->addCondition("stime<{$tm} and endtime>{$tm}");
        $criteria->limit = 20;
        $criteria->offset = 20 * ($page - 1);
        $criteria->order = 'stime DESC';
        $allList = AppXzTips::model()->findAll($criteria);
        $data = array();
        foreach ($allList as $value) {
            $img = empty($value['img'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['img'];
            array_push($data,array(
                "news_id"=>$value['id'], //新闻编号
                "time"=>date("Y-m-d",$value['stime']),
                "title"=>$value['title'],
                "tag"=>$value['tag'],
                "img"=>$img
            ));
        }
        $this->msgsucc($msg);
        $msg['data'] = $data;
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 获取新闻列表和分页接口
     * @param $arr
     *
     */
    public function getshoplist($arr)
    {
        $msg = $this->msgcode();
        $zone = $arr['zonecode'];
        $page = empty($arr['page'])?1:$arr['page'];
        if($page<1)$page=1;
        $criteria = new CDbCriteria;
        $criteria->addCondition("zone='{$zone}'");
        $criteria->limit = 20;
        $criteria->offset = 20 * ($page - 1);
        $allList = AppXzShop::model()->findAll($criteria);
        $data = array();
        foreach ($allList as $value) {
            $img = "";
            if(!empty($value['img']))
            {
                $imgArr = json_decode($value['img'],true);
                $img = empty($imgArr[0])?"":$this->utrl.Yii::app()->request->baseUrl.$imgArr[0]['url'];
            }
            array_push($data,array(
                "shop_id"=>$value['id'], //商店编号
                "name"=>$value['name'],
                "star"=>$value['star'],
                "tag"=>$value['tag'],
                "money"=>$value['money'],
                "tag"=>$value['tag'],
                "img"=>$img,
                "lng"=>$value['lng'],
                "lat"=>$value['lat']
            ));
        }
        $this->msgsucc($msg);
        $msg['data'] = $data;
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    public function shopdetails($arr)
    {
        $msg = $this->msgcode();
        if(empty($arr['shopid']))
        {
            $msg['msg'] = "商店编号参数未传入";
        }else
        {
            $id = $arr['shopid'];
            $allList = AppXzShop::model()->findByPk($id);
            if(!empty($allList))
            {
                $this->msgsucc($msg);
                $mgArr = array();
                if(!empty($allList->img))
                {
                    $imgArr = json_decode($allList->img,true);
                    if(is_array($imgArr))
                    {
                        foreach ($imgArr as $val) {
                            array_push($mgArr,array("url"=>$this->getUrl($val['url']),"desc"=>$val['desc']));
                        }
                    }
                }
                $msg['data'] = array(
                    "shop_id"=>$allList->id,
                    "name"=>$allList->name,
                    "imgList"=>$mgArr,
                    "star"=>$allList->star,
                    "money"=>$allList->money,
                    "tag"=>$allList->tag,
                    "zone"=>$allList->zone,
                    "tel"=>$allList->tel,
                    "lat"=>$allList->lat,
                    "lng"=>$allList->lng,
                    "add"=>$allList->add,
                    "taste"=>$allList->taste,
                    "suround"=>$allList->suround,
                    "service"=>$allList->service,
                    "office"=>$allList->office,
                    "content"=>$allList->content
                );
            }else
            {
                $msg['msg'] = "商店不存在";
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }


    protected function zm($str)
    {
        $strmp = '<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=320,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.3,user-scalable=no">
<style type="text/css">
img {width:99%s; height:auto;}
</style>
</head>
<body>
%s
</body>
</html>
';
        $str = preg_replace("/width[:0-9\s]+px;/is","", $str);
        preg_match_all("/<img(.*)(src=\"[^\"]+\")[^>]+>/isU", $str, $arr);
        for($i=0,$j=count($arr[0]);$i<$j;$i++){
            $str = str_replace($arr[0][$i],"<img ".$arr[2][$i]." />",$str);
        }
        return sprintf($strmp,"%",$str);
    }
    
    /**
     * 获取新闻详情
     * @param type $arr
     */
    public function newsdetails($arr)
    {
        $msg = $this->msgcode();
        if(empty($arr['news_id']))
        {
            $msg['msg'] = "新闻编号参数未传入";
        }else
        {
             $id = $arr['news_id'];
             $allList = AppXzTips::model()->findByPk($id);
            if(!empty($allList))
            {
                $this->msgsucc($msg);
                $img = empty($allList->img)?"":$this->utrl.Yii::app()->request->baseUrl.$allList->img;
                $msg['data'] = array("title"=>$allList->title,"date"=>date("Y-m-d",$allList->stime),"source"=>$allList->source,"img"=>$img,
                    "content"=>$this->zm($allList->content));
            }else
            {
                $msg['msg'] = "新闻不存在";
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }
    
    /**
     * 获取新闻列表和分页接口
     * @param $arr
     *
     */
    public function getsceniclist($arr)
    {
        $msg = $this->msgcode();
        $zone = $arr['zonecode'];
        
        $criteria = new CDbCriteria;
        $criteria->addCondition("zone='{$zone}'");
        $allList = AppXzScenic::model()->findAll($criteria);
        $data = array();
        foreach ($allList as $value) {
            $img = "";
            if(!empty($value['img']))
            {
                $imgArr = json_decode($value['img'],true);
                $img = empty($imgArr[0])?"":$this->utrl.Yii::app()->request->baseUrl.$imgArr[0]['url'];
            }
            $icon = empty($value['icon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['icon'];
            $mp3 = empty($value['mp3'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['mp3'];
            array_push($data,array(
                "scenic_id"=>$value['id'], //新闻编号
                "ptime"=>$value['ptime'],
                "name"=>$value['title'],
                "desc"=>$value['desc'],
                "img"=>$img,
                "icon"=>$icon,
                "top"=>$value['top'],
                "mp3"=>$mp3,
                "x"=>$value['x'],
                "y"=>$value['y']
            ));
        }
        $this->msgsucc($msg);
        $msg['data'] = $data;
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    public function scenicdetails($arr)
    {
        $msg = $this->msgcode();
        if(empty($arr['scenic_id']))
        {
            $msg['msg'] = "景点编号参数未传入";
        }else
        {
             $id = $arr['scenic_id'];
             $allList = AppXzScenic::model()->findByPk($id);
            if(!empty($allList))
            {
                $this->msgsucc($msg);
                $mgArr = array();
                if(!empty($allList->img))
                {
                    $imgArr = json_decode($allList->img,true);
                    if(is_array($imgArr))
                    {
                        foreach ($imgArr as $val) {
                            array_push($mgArr,array("url"=>$this->getUrl($val['url']),"desc"=>$val['desc']));
                        }
                    }
                }
                $msg['data'] = array(
                    "scenic_id"=>$allList->id,
                    "name"=>$allList->title,
                    "imgList"=>$mgArr,
                    "icon"=>$this->getUrl($allList->icon),
                    "address"=>$allList->add,
                    "content"=>$allList->content,
                    "mp3"=>$this->getUrl($allList->mp3)
                );
            }else
            {
                $msg['msg'] = "景点不存在";
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 获取资源完整地址
     * @param $url
     * @return string
     */
    protected function getUrl($url)
    {
        $utl = "";
        if(!empty($url))
        {
            $utl = $this->utrl.Yii::app()->request->baseUrl.$url;
        }
        return $utl;
    }

    /**
     * 获取token
     * @param type $id
     * @return type
     */
    private function getToken($um)
    {
        $salt = "xFl@&^852";
        $um->login_time = time();
        $um->key = substr(md5($um->id.$salt.$um->type.$um->login_time),3,16);
        $um->save();
        return $um->key;
    }
    /**
     * 验证用户是否已经登录
     * @param type $id
     * @param type $token
     * @return type
     */
    private function chkToken($id,$token)
    {
        $salt = "xFl@&^852";
        $um = AppJxUser::model()->findByPk($id);
        if(empty($um))
        {
            return false;
        }
        else
        {
            if($um->type!=1&&$um->login_time+302400>time()&&substr(md5($um->id.$salt.$um->type.$um->login_time),3,16)==$token)
            {
                return true;
            }else
            {
                return false;
            }
        }
    }

    /**
     * 帐号登录
     */
    public function login($arr)
    {
        $msg = $this->msgcode();
        $salt = "xFl@&^852";
        $tel = $arr['tel'];
        $password = $arr['password'];
        if($tel==""||$password=="")
        {
            $msg['msg'] = "存在必填项为空，请确定参数满足条件";
            echo json_encode($msg);die();
        }
        $mod = AppJxUser::model()->find("tel=:tl and type=0",array("tl"=>$tel));
        $tmp = $mod;
        if(!empty($mod)&&md5($password.$salt)==$mod->password)
        {
            $this->msgsucc($msg);
            $this->alias($mod->id,2);
            $msg['data'] = array("id"=>$mod->id,
                "token"=>$this->getToken($tmp),
                "tel"=>$mod->tel,
                "uname"=>$mod->uname,
                "img_url"=>$this->getUrl($mod->img_url)
            );
        }
        else
            $msg['msg'] = "帐号或者密码错误";
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 帐号登出
     */
    public function logout($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $mod = AppJxUser::model()->findByPk($user_id);
            if(empty($mod))
            {
                $msg['msg'] = "用户不存在";
            }else{
                $mod->login_time = time();
                if($mod->save())
                    $this->msgsucc($msg);
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 获取用户信息
     */
    public function getuserinfo($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $mod = AppJxUser::model()->findByPk($user_id);
            if(empty($mod))
            {
                $msg['msg'] = "用户不存在";
            }else{
                $this->msgsucc($msg);
                $msg['data'] = array(
                    "id"=>$mod->id,
                    "tel"=>$mod->tel,
                    "uname"=>$mod->uname,
                    "img_url"=>$this->getUrl($mod->img_url)
                );
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    public function commentlist($arr)
    {
        $msg = $this->msgcode();
        $news_id = $arr['news_id'];
        $newModel = AppJxNews::model()->findByPk($news_id);
        if(empty($newModel)||$newModel->comtype==1)
        {
            $msg['code'] = 3;
            $msg['msg'] = "禁止评论";
        }else{
            $userList = AppJxUser::model()->findAll();
            $userApp = array();
            $userNc = array();
            $userImg = array();
            foreach($userList as $val)
            {
                $userApp[$val->id] = $val->tel;
                $userNc[$val->id] = $val->uname;
                $userImg[$val->id] = $val->img_url;
            }
            $page = $arr['page'];
            if($page<1)$page=1;
            $star = 20*($page-1);
            $comm = AppJxComment::model()->findAll("news_id={$news_id} order by id desc limit {$star},20");
            $this->msgsucc($msg);
            $allList = array();
            foreach($comm as $val)
            {
                array_push($allList,array(
                    "id"=>$val->id,
                    "parent_id"=>$val->parent_id,
                    "parent_user"=>$val->parent_user,
                    "user_id"=>$val->user_id,
                    "comment"=>$val->comment,
                    "user_account"=>$userApp[$val->user_id],
                    "user_nic"=>$userNc[$val->user_id],
                    "addtime"=>$val->addtime,
                    "user_img"=>$this->utrl.Yii::app()->request->baseUrl.$userImg[$val->user_id]
                ));
            }
            $msg['data'] = $allList;
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }


    /**
     * 评论
     */
    public function comment($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $news_id = $arr['news_id'];
        $content = $arr['content'];
        $parent_id = $arr['parent_id'];
        $parent_user = $arr['parent_user'];

        $black = AppJxConfig::model()->findByPk("comment");
        $lackList= explode(",",$black->value);
        $bl = true;
        foreach($lackList as $as)
        {
            if(strpos($content,$as)!==false)
            {
                $bl = false;
                break;
            }
        }
        $newmodel = AppJxNews::model()->find("id={$news_id} and (comtype is null or comtype=0)");
        if(!$bl)
        {
            $msg['msg'] = "评论中包含非法词汇";
        }
        elseif(empty($newmodel))
        {
            $msg['msg'] = "该文章静止评论";
        }
        elseif($user_id==""||$token==""||$news_id==""||$content=="")
        {
            $msg['msg'] = "存在必填项为空，请确定参数满足条件";
        }elseif(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $comm = new AppJxComment();
            $comm->news_id = $news_id;
            $comm->parent_id = $parent_id;
            $comm->parent_user = $parent_user;
            $comm->user_id = $user_id;
            $comm->comment = $content;
            $comm->addtime = time();
            if($comm->save())
            {
                $mdl = AppJxNews::model()->findByPk($news_id);
                $mdl->comment = $mdl->comment+1;
                $mdl->save();
                $this->msgsucc($msg);
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 帐号注册
     * @param type $arr
     */
    public function register($arr)
    {
        $msg = $this->msgcode();
        $salt = "xFl@&^852";
        $tel = $arr['tel'];
        $password = $arr['password'];
        $vcode = trim($arr['verifycode']);
        if($tel==""||$password=="")
        {
            $msg['msg'] = "存在必填项为空，请确定参数满足条件";
            echo json_encode($msg);die();
        }
        $model = AppJxUser::model()->find("tel=:tl and type=1 and password='123456'",array("tl"=>$tel));
        if(!empty($model))
        {
            if(empty($model->check))
            {
                $msg['msg'] = "验证码失效，请重新获取";
            }
            elseif(!Sms::check($tel))
            {
                $model->check = "";
                $model->save();
                $msg['msg'] = "验证时间超时，请重新获取";
            }
            elseif($model->check != $vcode)
            {
                $model->check = "";
                $model->save();
                $msg['msg'] = "验证码错误，请重新获取";
            }else
            {
                $model->tel = $tel;
                $model->password = md5($password.$salt);
                $model->fhtime = time();
                $model->ctime = time();
                $model->check = "";
                $model->type = 0;
                if($model->save())
                {
                    $this->msgsucc($msg);
                    $id = $model->attributes['id'];
                    $this->alias($id,1);
                    $msg['data'] = array("id"=>$id,
                        "token"=>$this->getToken($model));
                }else
                {
                    $msg['msg'] = "注册失败";
                }
            }
        }else
        {
            $msg['msg'] = "号码已被注册";
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    private function alias($uid,$aid)
    {
        $mkl = AppXzAlias::model()->find("user_id={$uid} and alias_id={$aid}");
        if(empty($mkl))
        {
            $ml = new AppXzAlias();
            $ml->user_id = $uid;
            $ml->time = time();
            $ml->alias_id = $aid;
            $ml->status = 1;
            $ml->save();
        }
    }

    /**
     * 点赞的状态
     * @param $arr
     */
    public function zanstatus($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $news_id = $arr['news_id'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $id = AppJxDegree::model()->find("news_id={$news_id} and user_id={$user_id}");
            $this->msgsucc($msg);
            if(empty($id))
            {
                $msg['data'] = 0;
            }else
            {
                $msg['data'] = $id->type;
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 点赞
     * @param $arr
     */
    public function setzan($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $news_id = $arr['news_id'];
        $type = $arr['type'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $id = AppJxDegree::model()->find("news_id={$news_id} and user_id={$user_id}");
            $news = AppJxNews::model()->findByPk($news_id);
            $this->msgsucc($msg);
            if(empty($id))
            {
                $modl = new AppJxDegree();
                $modl->news_id = $news_id;
                $modl->user_id = $user_id;
                $modl->type = $type;
                $modl->save();
                if(!empty($news))
                {
                    if($type==1)
                    {
                        $news->like = $news->like+1;
                    }elseif($type==2)
                    {
                        $news->han = $news->han+1;
                    }elseif($type==3)
                    {
                        $news->hate = $news->hate+1;
                    }
                    $news->save();
                }

            }else
            {
                if(!empty($news))
                {
                    if($type==1)
                    {
                        $news->like = $news->like+1;
                    }elseif($type==2)
                    {
                        $news->han = $news->han+1;
                    }elseif($type==3)
                    {
                        $news->hate = $news->hate+1;
                    }
                    if($id->type==1)
                    {
                        $news->like = $news->like-1;
                    }elseif($id->type==2)
                    {
                        $news->han = $news->han-1;
                    }elseif($id->type==3)
                    {
                        $news->hate = $news->hate-1;
                    }
                    $news->save();
                }
                $id->type = $type;
                $id->save();
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 更新用户头像和昵称
     * @param $arr
     */
    public function updateuserinfo($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $uname = empty($arr['uname'])?"":$arr['uname'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $model = AppJxUser::model()->findByPk($user_id);
            $uimg = empty($_FILES['file'])?"":$_FILES['file'];
            if(!empty($uimg['name']))
            {
                $img = array("png","jpg","gif");
                $_tmp_pathinfo = pathinfo($uimg['name']);
                if (in_array(strtolower($_tmp_pathinfo['extension']),$img)) {
                    //设置图片路径
                    $flname = 'photo/'.time().$user_id.".".$_tmp_pathinfo['extension'];
                    $dest_file_path = Yii::app()->basePath . '/../public/'.$flname;
                    $filepathh = dirname($dest_file_path);
                    if (!file_exists($filepathh))
                        $b_mkdir = mkdir($filepathh, 0777, true);
                    else
                        $b_mkdir = true;
                    if ($b_mkdir && is_dir($filepathh)) {
                        //转存文件到 $dest_file_path路径
                        if (move_uploaded_file($uimg['tmp_name'], $dest_file_path)) {
                            $img_url ='/public/'.$flname;
                            if(!empty($model->img_url))
                                @unlink(Yii::app()->basePath . '/..'.$model->img_url);
                            $model->img_url = $img_url;
                        }else
                        {
                            $msg["msg"] = '头像存储失败';
                            $msg["code"] = 4;
                        }
                    }
                } else {
                    $msg["msg"] = '上传的文件格式只能为jpg,png,gif';
                    $msg["code"] = 3;
                }
            }
            if($msg["code"]==1)
            {
                if($uname!="")
                    $model->uname = $uname;
                if($model->save())
                {
                    $this->msgsucc($msg);
                    $msg['data'] = array(
                        "id"=>$user_id,
                        "tel"=>$model->tel,
                        "uname"=>$model->uname,
                        "img_url"=>$this->getUrl($model->img_url)
                    );
                }else
                {
                    $msg['msg'] = "保存失败";
                }
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }



    /**
     * 收藏的状态
     * @param $arr
     */
    public function collectstatus($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $news_id = $arr['scenic_id'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $id = AppXzCollect::model()->find("scenic_id={$news_id} and user_id={$user_id}");
            $this->msgsucc($msg);
            if(empty($id))
            {
                $msg['data'] = 0;
            }else
            {
                $msg['data'] = 1;
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 收藏与取消收藏
     * @param $arr
     */
    public function setcollect($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $news_id = $arr['scenic_id'];
        $type = $arr['type'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $id = AppXzCollect::model()->find("scenic_id={$news_id} and user_id={$user_id}");
            //取消收藏
            if($type==0)
            {
                if(!empty($id))
                {
                    if($id->delete())
                    {
                        $this->msgsucc($msg);
                    }
                }else{
                    $msg['msg'] = "该文章您并未收藏";
                }
                //收藏
            }elseif($type==1)
            {
                if(empty($id))
                {
                    $modl = new AppXzCollect();
                    $modl->scenic_id = $news_id;
                    $modl->user_id = $user_id;
                    $modl->time = time();
                    if($modl->save())
                    {
                        $cnt = AppXzCollect::model()->count("user_id={$user_id}");
                        if($cnt==3)
                            $this->alias($user_id,3);
                        $this->msgsucc($msg);
                    }
                }else
                {
                    $msg['msg'] = "请勿重复收藏";
                }
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 获取收藏列表
     * @param $arr
     */
    public function getcollectlist($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $page = empty($arr['page'])?1:$arr['page'];
        if($page<1)$page=1;
        $cnt = ($page-1)*20;
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{

            $connection = Yii::app()->db;
            $sql = "SELECT * FROM xz_collect left join xz_scenic on xz_collect.scenic_id = xz_scenic.id where xz_collect.user_id={$user_id} and xz_scenic.title is not null order by time desc limit {$cnt},20"; //构造SQL
            $sqlCom = $connection->createCommand($sql);
            $lst = $sqlCom->queryAll();
            $data = array();
            $this->msgsucc($msg);
            foreach ($lst as $value) {
                $img = "";
                if(!empty($value['img']))
                {
                    $imgArr = json_decode($value['img'],true);
                    $img = empty($imgArr[0])?"":$this->utrl.Yii::app()->request->baseUrl.$imgArr[0]['url'];
                }
                //$icon = empty($value['icon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['icon'];
                //$mp3 = empty($value['mp3'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['mp3'];
                array_push($data,array(
                    "scenic_id"=>$value['id'], //新闻编号
                    "ptime"=>$value['ptime'],
                    "name"=>$value['title'],
                    "desc"=>$value['desc'],
                    "img"=>$img,
                    //"icon"=>$icon,
                    "top"=>$value['top']
//                    "mp3"=>$mp3,
//                    "x"=>$value['x'],
//                    "y"=>$value['y']
                ));

            }
            $msg['data'] = $data;
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }
    
    public function usercenter($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            $lst = AppXzScenic::model()->findAll();
            $fly = AppXzFly::model()->findAll("user_id={$user_id}");
            $ch = AppXzAlias::model()->findAll("user_id={$user_id}");
            $ck = AppXzAchieve::model()->findAll();
            $cct = AppXzCollect::model()->count("user_id={$user_id}");

            $paly = array();
            foreach ($fly as $value) {
                array_push($paly, $value['zone']);
            }
            
            $charr = array();
            foreach ($ch as $value) {
                array_push($charr, $value['alias_id']);
            }

            $sec = array();
            $sec['user'] = array("name"=>"我的","total"=>0,"play"=>0,"pre"=>0,"list"=>array());
            foreach ($ck as $value){
                $sec['user']['total']++;
                if(in_array($value['id'], $charr))
                {
                    $icon = empty($value['icon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['icon'];
                    $sec['user']['play']++;
                }else
                {
                    $icon = empty($value['hicon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['hicon'];
                }
                array_push($sec['user']['list'],array(
                    "scenic_id"=>$value['id'],
                    "name"=>$value['title'],
                    "icon"=>$icon,
                    "desc"=>$value['desc']
                ));
            }
            foreach (TmpList::$zone_list as $key => $value) {
                $sec[$key] = array("name"=>str_replace("县","",$value),"total"=>0,"play"=>0,"pre"=>0,"list"=>array());
            }
            foreach ($lst as $value){
                $sec[$value['zone']]['total']++;
                if(in_array($value['id'], $paly))
                {
                    $icon = empty($value['icon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['icon'];
                    $sec[$value['zone']]['play']++;
                }else
                {
                    $icon = empty($value['hicon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['hicon'];
                }
                array_push($sec[$value['zone']]['list'],array(
                    "scenic_id"=>$value['id'],
                    "name"=>$value['title'],
                    "icon"=>$icon,
                    "desc"=>"浏览过{$value['title']}"
                ));
            }
            foreach ($sec as $k=>$val)
            {
                $sec[$k]['pre'] = round(($val['play']/$val['total'])*100);
            }
            $this->msgsucc($msg);
            $msg['data'] = array("collect"=>$cct,"play"=>count($fly),"list"=>$sec);
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    public function getplaylist($arr)
    {
        $msg = $this->msgcode();
        $user_id = $arr['user_id'];
        $token = $arr['token'];
        $page = empty($arr['page'])?1:$arr['page'];
        if($page<1)$page=1;
        $cnt = ($page-1)*21;
        if(!$this->chkToken($user_id,$token))
        {
            $msg['code'] = 2;
            $msg['msg'] = "无权限，请登录";
        }else{
            
            $connection = Yii::app()->db;
            $sql = "SELECT * FROM xz_fly left join xz_scenic on xz_fly.zone = xz_scenic.id where xz_fly.user_id={$user_id} and xz_scenic.title is not null order by time desc limit {$cnt},21"; //构造SQL
            $sqlCom = $connection->createCommand($sql);
            $lst = $sqlCom->queryAll();
            $data = array();
            $this->msgsucc($msg);
            $total = AppXzScenic::model()->count(); //总数
            $see = count($lst); //浏览数
            $pre = round(($see/$total)*100); //百分比
            foreach ($lst as $value) {
                $icon = empty($value['icon'])?"":$this->utrl.Yii::app()->request->baseUrl.$value['icon'];
                array_push($data,array(
                    "scenic_id"=>$value['id'], //新闻编号
                    "name"=>$value['title'],
                    "icon"=>$icon
                ));
            }
            $msg['data'] = array("list"=>$data,"total"=>$total,"see"=>$see,"pre"=>$pre);
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }
    /**
     * 发送验证码
     * @param type $arr
     */
    public function sendverifycode($arr)
    {
        $msg = $this->msgcode();
        $tel = $arr['tel'];
        $type = $arr['type']==1?1:0;
        $sb = empty($arr['uuid'])?"":$arr['uuid'];
        $umode = AppJxUser::model()->find("tel=:tl",array(":tl"=>$tel));
        //改密码
        if($type==1)
        {
            if(empty($umode))
            {
                $msg['msg'] = "用户不存在";
            }else{
                list($msec, $sec) = explode(' ', microtime());
                $code = substr($msec,4,4);
                $umode->check = $code;
                if($umode->save())
                {
                    $con = new Sms();
                    $mll = $con->sendNotice($tel,$sb);
                    if($mll['code']==0)
                    {
                        $content = sprintf("验证码：%s ，您目前正在使用行走甘孜账密保护功能，请勿告知他人。",$code);
                        if($con->sendSMS($tel,$content))
                            $this->msgsucc($msg);
                        else
                            $msg['msg'] = '发送短信出错';
                    }
                    else{
                        $msg['msg'] = $mll['msg'];
                    }
                }
            }
        }else
        {
            if(empty($umode))
            {
                $msg['msg'] = "号码有误";
                $model = new AppJxUser();
                $model->tel = $tel;
                $model->password = "123456";
                $model->fhtime = time();
                $model->ctime = time();
                //注册用户默认是被封号的
                $model->type = 1;
                list($msec, $sec) = explode(' ', microtime());
                $code = substr($msec,4,4);
                $model->check = $code;

                if($model->save())
                {
                    $con = new Sms();
                    $mll = $con->sendNotice($tel,$sb);
                    if($mll['code']==0)
                    {
                        $content = sprintf("验证码：%s ，您目前正在使用行走甘孜账密保护功能，请勿告知他人。",$code);
                        if($con->sendSMS($tel,$content))
                            $this->msgsucc($msg);
                        else
                            $msg['msg'] = '发送短信出错';
                    }
                    else{
                        $msg['msg'] = $mll['msg'];
                    }
                }

            }else
            {
                $msg['msg'] = "用户已经存在";
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    /**
     * 修改密码
     * @param $arr
     */
    public function updatepassword($arr)
    {
        $msg = $this->msgcode();
        $tel = $arr['tel'];
        $newpass = $arr['newpassword'];
        $vcode = trim($arr['verifycode']);
        $umode = AppJxUser::model()->find("tel=:tl",array(":tl"=>$tel));
        if(!empty($umode))
        {
            if(empty($umode->check))
            {
                $msg['msg'] = "验证码失效，请重新获取";
            }
            elseif(!Sms::check($tel))
            {
                $umode->check = "";
                $umode->save();
                $msg['msg'] = "验证时间超时或次数过多，请重新获取";
            }
            elseif($umode->check != $vcode)
            {
                $umode->check = "";
                $umode->save();
                $msg['msg'] = "验证码错误，请重新获取";
            }else
            {
                $salt = "xFl@&^852";
                $umode->password = md5($newpass.$salt);
                $umode->login_time = time();
                $umode->check = "";
                if($umode->save())
                {
                    $this->msgsucc($msg);
                    $msg['msg'] = "修改密码成功，请重新登录";
                }
            }
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }
    /**
     * 天气显示接口
     *
     */
    public function getweather($arr)
    {
        $msg = $this->msgcode();
        $zone = $arr['zone'];
        $url = "http://api.map.baidu.com/telematics/v3/weather?location={$zone}&output=json&ak=0QDaLukGIKr22SwQKTWNxGSz";

        $data = json_decode(RemoteCurl::getInstance()->get($url),true);
        $allList = array();
        if($data['status']=="success")
        {
            if(!empty($data['results'][0]['weather_data'])&&is_array($data['results'][0]['weather_data']))
            {
                $this->msgsucc($msg);
                $model = $data['results'][0]['weather_data'];
                $start = strpos($model[0]['date'],"：");
                $crent = mb_substr($model[0]['date'],$start+3,strlen($model[0]['date'])-1);
                foreach($model as $k=>$val)
                {
                    $crent = $k==0?$crent:"";
                    $day = mb_substr($val['date'],0,6);
                    array_push($allList,array("current_temperature"=>$crent,"date"=>$day,
                        "weather"=>$this->getW($val['weather']),"temperature"=>$val['temperature'],
                        "wind"=>$val['wind']
                    ));
                }

            }
            else
            {
                $msg['msg'] = "天气获取失败";
            }
            $msg['data'] = $allList;
        }
        $this->getNotice($msg);
        echo json_encode($msg);
    }

    private function getW($str)
    {
        $arr = array("ICE"=>"雹","SNOW"=>"雪","RAIN"=>"雨","SUN"=>"晴","CLOUD"=>"云","WIND"=>"风");
        $rtn = "";
        foreach($arr as $k=>$val)
        {
            if(strpos($str,$val)!==false)
            {
                $rtn = $k;
                break;
            }
        }
        if($rtn=="")
            $rtn="SUN";
        return $rtn;
    }

    public function search($arr)
    {
        $page = empty($arr['page'])?1:$arr['page'];
        $words = $arr['words'];
        if($page<1)$page=1;
        $listArr = array();
        $cnt = ($page-1)*20;
        $list = AppJxNews::model()->findAll("title like '%{$words}%' or content like '%{$words}%' order by id desc limit {$cnt},20");
        foreach($list as $val)
        {
            $content = html_entity_decode(trim(strip_tags($val['content'])));
            $type = $val['type']==2?1:0;
            if(mb_strpos($val['title'],$words,1,"utf-8")!=false)
                $summary = mb_substr($content,0,30,"utf-8");
            else
            {
                $k = mb_strpos($content,$words,1,"utf-8");
                $lmt = 30;
                if($k<10)
                {
                    $star = $k;
                    $lmt = $lmt+10-$star;
                }
                else
                {
                    $star = $k-10;
                }
                $summary = mb_substr($content,$star,$lmt,"utf-8");
            }
            array_push($listArr,array("id"=>$val['id'],"addtime"=>$val['addtime'],"title"=>$val['title'],"summary"=>$summary,
            "type"=>$type));
        }
        $msg['code'] = 0;
        $msg['msg'] = "成功";
        $msg['data'] = $listArr;
        $this->getNotice($msg);
        echo json_encode($msg);
    }


    public function actionSms()
    {
        $msg = $this->msgcode();
        $tel = Yii::app()->request->getParam("tel","18228041350");
        $content = Yii::app()->request->getParam("content","1234");
        if($tel==""||$content=="")
        {
            $msg['msg'] = "不能为空";
        }else{
            $con = new Sms();
            $mll = $con->sendNotice($tel);
            if($mll['code']==0)
            {
                if($con->sendSMS($tel,$content))
                    $this->msgsucc($msg);
                else
                    $msg['msg'] = '发送短信出错';
            }
            else{
                $msg['msg'] = $mll['msg'];
            }
        }
        echo json_encode($msg);

    }

    public function actionDemo()
    {
//       $params = array(
//            'action' => 'commentlist',
//            'user_id' => '23',
//            'token'=>'7cb5f1867099ffab',
//            'news_id'=>'973',
//            'page' => '1',
//            'parent_id'=>'23',
//            'parent_user'=>"测试",
//
//        );

        $params = array(
            'action' => 'newsdetails',
            'tel'=>'18228041350',
            'type'=>1,
            "newpassword"=>md5("123456"."xFl@&^852"),
            "x"=>'101.88',
            'y' => "31.88",
            "news_id"=>6,
            "password"=>md5("123456"."xFl@&^852"),
            "verifycode"=>9999,
            "user_id"=>4,
            "token"=>"361af827aee9c040"
        );

//        $params = array(
//            'action' => 'comment',
//            'user_id' => '23',
//            'news_id' => '286',
//            'content'=>1,
//            'token'=>'c88d5135ac6b59c4 '
//        );
     //   xFl@&^852

        //[id] => 1 [token] => c88d5135ac6b59c4
        $salt = "xFlaSd!$&258";
        $data = json_encode($params);
        $sign = md5($data.$salt);
        $rtnList = array(
            "data"=>$data,
            "sign"=>$sign
        );
        $url = true?"http://127.0.0.1/xzgz/project/index.php":"http://120.24.234.19/api/xzgz/project/index.php";
//echo RemoteCurl::getInstance()->post($url,$rtnList);die();
        print_r(json_decode(RemoteCurl::getInstance()->post($url,$rtnList)));
    }

}