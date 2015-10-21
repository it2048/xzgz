<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homeadvert/newsupdate'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <?php if($models->img!=""){?><p class="nowrap"><label>封面图片：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->img;?>"></p>
            <?php }?>
            <p class="nowrap">
                <label>更换封面图片：</label>
                <input name="news_img" type="file">
                <input name="news_id" type="hidden" value="<?php echo $models->id;?>">
            </p>
            <p class="nowrap">
                <label>广告标题：</label>
                <input  name="news_title" type="text" class="textInput required" size="50" value="<?php echo $models->title;?>">
            </p>
            <p class="nowrap">
                <label>广告描述：</label>
                <input  name="news_desc" type="text" class="textInput" size="50" value="<?php echo $models->desc;?>">
            </p>
            <p class="nowrap">
                <label>链接地址：</label>
                <input  name="news_link" type="text" class="textInput" size="50" value="<?php echo $models->link;?>">
            </p>
        </div>
        <div class="formBar">
            <ul>
                <!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
<script type="text/javascript">
    /**
     * 回调函数
     */
    function viData(json) {
        if(json.code!=0)
        {
            alertMsg.error(json.msg); //返回错误
        }
        else
        {
            alertMsg.correct("保存成功"); //返回错误
            navTab.reload(json.usermaneger);  //刷新主页面
            $.pdialog.closeCurrent();  //
        }
    }

</script>