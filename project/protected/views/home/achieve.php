
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php $home = Yii::app()->request->baseUrl."/public/home/achieve/";?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.3,user-scalable=no">
    <title>获得成就</title>
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
        #msg_content {
            padding: 0;
            font-size: 15px;
            color: #3e3a39;
        }
        #msg_content p img{
            width:100%;
        }
        #float_layer {
            z-index : 999;
            position:fixed;
            height: 40px;
            line-height: 40px;
            top:25px;
            right:20px;
        }

        #float_layer img {
            width:100px;
            height:80px;
        }
    </style>
    <script type="text/javascript">

        $().ready(function () {

        });

    </script>
</head>
<body>
<div id="msg_content" bgcolor="#ffffff">
    <p><img src="<?php echo Yii::app()->params->url.$home; ?>image/1.png" /></p>
    <p><img src="<?php echo Yii::app()->params->url.$home; ?>image/2.png" /></p>
    <p><img src="<?php echo Yii::app()->params->url.$home; ?>image/3.png" /></p>
    <div id="float_layer"><img src="<?php echo $list; ?>" /></div>
</div>

</body>
</html>
