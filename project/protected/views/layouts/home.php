<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php $home = Yii::app()->request->baseUrl."/public/home/news/";?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.3,user-scalable=no">
    <title></title>
    <link rel="stylesheet" href="<?php echo Yii::app()->params->url.$home; ?>css/cssreset.css">
    <script src="<?php echo Yii::app()->params->url.$home; ?>js/jquery.min.js"></script>
    <style type="text/css">
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
            padding: 0px 15px;
        }
        #msg_content {
            padding: 1px 0;
            font-size: 15px;
            color: #3e3a39;
        }
        #msg_content img{
            width:100%;
        }
    </style>
<script type="text/javascript">
	
	$().ready(function () {
		
	});  
	 
</script>
</head>
<body>
<div bgcolor="#ffffff">
    <div id="info_content">
        <div id="msg_content">
            <p><?php echo $content; ?></p>
        </div>
    </div>
</div>
</body>
</html>
