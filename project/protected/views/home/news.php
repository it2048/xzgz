<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php $home = Yii::app()->request->baseUrl."/public/home/news/";?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.3,user-scalable=no">
    <title>景点介绍</title>
    <link rel="stylesheet" href="<?php echo Yii::app()->params->url.$home; ?>css/cssreset.css">
    <script src="<?php echo Yii::app()->params->url.$home; ?>js/jquery.min.js"></script>
    <style type="text/css">
        .ui-content {
            border-width: 0;
            overflow: visible;
            overflow-x: hidden;
            padding: 0;
            background-color:#ffffff;
        }
        .ui-panel-inner {
            padding: 0;
            background-color:#ffffff;
        }
        body {
            width: 100%;
            min-width: 240px;
        }
        html,body{
            background:#fff;
        }
        #header {
            background: #F86C41 repeat-x;
            height: 45px;
            line-height: 45px;
            text-align: center;
        }
        #header b{
            color: #ffffff;
            font-size: 18px;
            font-weight: normal;
            text-shadow: none;
        }
        #info_content {
            padding: 15px;
        }
        #title {
            text-align: left;
            font-size: 20px;
            color: #3E3A39;
            padding: 0 0 4px 0;
        }
        #time {
            text-align: left;
            font-size: 11px;
            color: #b4b4b5;
            padding: 10px 10px 4px 0;
        }
        #msg_content {
            padding: 10px 0;
            font-size: 15px;
            color: #3e3a39;
        }
        #msg_content img{
            width:100%;
        }
        #float_layer {
            z-index : 999;
            width:100%;
            position:fixed;
            height: 60px;
            line-height: 60px;
            bottom:0;
            right:0;
            background-color:#191919;
            filter:alpha(opacity=90);
            -moz-opacity:0.9;
            opacity:0.9;
        }
        #icon{
            float:left;
            margin:10px 0 0 18px;
        }
        #btn_open{
            float:right;
            margin:14px 18px 0 0;
        }
    </style>
    <script type="text/javascript">

        $().ready(function () {

        });

    </script>
</head>
<body>
<div bgcolor="#ffffff">
    <div id="header"><b>景点介绍<b></div>
    <div id="info_content">
        <div id="title_zone">
            <p id="title"><?php echo empty($model['title'])?"":$model['title']; ?></p>
            <p id="time"><?php echo empty($model['addtime'])?"":$model['addtime']; ?></p>
        </div>
        <div id="msg_content">
            <p><?php echo empty($model['content'])?"":$model['content']; ?></p>
        </div>
    </div>
</div>

</body>
</html>
