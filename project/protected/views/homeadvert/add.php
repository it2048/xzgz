<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homenews/newssave'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <p>
                <label>新闻类型：</label>
                <select class="combox" name="news_type">
                    <?php foreach(TmpList::$news_list as $k=>$val){
                        printf('<option value="%s">%s</option>',$k,$val);
                    } ?>
                </select>
            </p>
            <p class="nowrap">
                <label>封面图片上传：</label>
                <input name="news_img" type="file">
            </p>
            <p class="nowrap">
                <label>标题：</label>
                <input  name="news_title" type="text" class="textInput required" size="50" value="">
            </p>
            <p class="nowrap">
                <label>标签：</label>
                <input  name="news_tag" type="text" class="textInput" size="50" value="">
            </p>
            <p class="nowrap">
                <label>作者：</label>
                <input  name="news_source" type="text" class="textInput" size="50" value="">
            </p>
            <p>
                    <label>发布时间：</label>
                    <input type="text" name="stime" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" value="<?php echo date("Y-m-d H:i:s",time()); ?>"/>
                    <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <p>
                    <label>下线时间：</label>
                    <input type="text" name="etime" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" value="<?php echo date("Y-m-d H:i:s",time()+86400); ?>"/>
                    <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <div class="divider"></div>
            <p class="nowrap"><label>选择地区：</label></p>
            <p class="nowrap">
                <?php foreach(TmpList::$zone_list as $k=>$val){
                        printf('<label><input type="checkbox" name="zone[]" value="%s" />%s</label>',$k,$val);
                    } ?>
            </p>
            <div class="divider"></div>
            <p class="nowrap"><label>文章内容：</label></p>
            <p>
                <textarea class="editor" upImgUrl="<?php echo Yii::app()->createAbsoluteUrl('homenews/imgupload'); ?>" upImgExt="jpg,jpeg,gif,png" name="news_content" rows="21" cols="70" ></textarea>
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