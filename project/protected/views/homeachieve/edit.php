<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homeachieve/update'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <p class="nowrap">
                <label>名称：</label>
                <input  name="ach_title" type="text" class="textInput required" size="50" value="<?php echo $models->title;?>">
                <input name="ach_id" type="hidden" value="<?php echo $models->id;?>">
            </p>
            <?php if($models->icon!=""){?><p class="nowrap"><label>小图标：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->icon;?>"></p>
            <?php }?>
            <p class="nowrap">
                <label>更换小图标：</label>
                <input name="ach_icon" type="file">
            </p>
            <?php if($models->hicon!=""){?><p class="nowrap"><label>灰色小图标：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->hicon;?>"></p>
            <?php }?>
            <p class="nowrap">
                <label>更换灰色小图标：</label>
                <input name="ach_hicon" type="file">
            </p>
            <p class="nowrap">
                <label>规则描述：</label>
                <textarea name="ach_desc" cols="80" rows="6" class="textInput"><?php echo $models->desc;?></textarea>
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
            navTab.reload(json.homeachieve);  //刷新主页面
            $.pdialog.closeCurrent();  //
        }
    }

</script>