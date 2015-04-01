<?php
/**
 * Created by PhpStorm.
 * User: xiongfanglei
 * Date: 15-2-11
 * Time: 上午10:06
 */
class Sms {

    public function sendNotice($mobile,$sb="")
    {

        if(!empty($sb))
        {
            $msg = $this->insert($sb);
            if($msg['code']==0)
            {
                return $this->insert($mobile);
            }
            else
            {
                return $msg;
            }
        }else
        {
            return $this->insert($mobile);
        }

    }

    private function insert($sb)
    {
        $msg = array("code"=>1,"msg"=>"");
        $tm = time()-86400;
        $model = AppSmsNotice::model()->findByPk($sb);
        if(!empty($model))
        {
            if($model->ctn>=10)
            {
                if($tm<$model->ftime)
                    $msg['msg'] = "验证码发送太频繁，请稍后再试。";
                else
                {
                    $model->ftime = time();
                    $model->ctn = 1;
                    $model->ltime = time();
                    $model->save();
                }
            }
            else{
                if($tm>$model->ftime)
                {
                    $model->ftime = time();
                    $model->ctn = 1;
                    $model->ltime = time();
                    $model->save();
                }else{
                    $model->ctn += 1;
                    $model->ltime = time();
                    $model->save();
                }
            }
        }
        else{
            $mod = new AppSmsNotice();
            $mod->telorsb = $sb;
            $mod->ftime = time();
            $mod->ctn = 1;
            $mod->ltime = time();
            $mod->save();
        }
        if(empty($msg['msg']))
            $msg['code'] = 0;
        return $msg;
    }

    public function sendSMS($mobile,$content,$type="identify")
    {
        $data = array
        (
            'username'=>Yii::app()->params['sms']['username'],					//用户账号
            'password'=>Yii::app()->params['sms']['password'],				//密码
            'mobile'=>$mobile,					//号码
            'content'=>iconv("UTF-8","GBK//IGNORE",$content),				//内容
            'apikey'=>Yii::app()->params['sms']['apikey'],				    //apikey
        );
        $result= $this->curlSMS(Yii::app()->params['sms']['url'],$data);			//POST方式提交
        $model = new AppSmsSend();
        $model->content = $content;
        $model->tel = $mobile;
        $model->time = time();
        $model->type = $type;
        if(strpos($result,"success")!==false)
        {
            $model->rtn = 0;
            $model->save();
            return true;
        }
        else{
            $model->rtn = 1;
            $model->save();
            return false;
        }
    }
    private function curlSMS($url,$post_fields=array()){
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //60秒
//        curl_setopt($ch, CURLOPT_HEADER,1);
//        curl_setopt($ch, CURLOPT_REFERER,'http://rs.windplay.cn');
//        curl_setopt($ch,CURLOPT_POST,1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
//        $data = curl_exec($ch);
//        curl_close($ch);
//        $res = explode("\r\n\r\n",$data);
//        file_put_contents('d:/t.log',print_r($res,true),8);
//        return $res[2];
        return "success:14279022595464";
    }

}
