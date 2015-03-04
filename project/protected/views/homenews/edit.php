<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homenews/newsupdate'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <p>
                <label>新闻类型：</label>
                <select class="combox" name="news_type">
                    <?php foreach(TmpList::$news_list as $k=>$val){
                        printf('<option value="%s" %s>%s</option>',$k,$models->type==$k?"selected":"",$val);
                    } ?>
                </select>
            </p>
            <?php if($models->img!=""){?><p class="nowrap"><label>封面图片：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->img;?>"></img></p>
            <?php }?>
            <p class="nowrap">
                <label>更换封面图片：</label>
                <input name="news_img" type="file">
                <input name="news_id" type="hidden" value="<?php echo $models->id;?>">
            </p>
            <p class="nowrap">
                <label>标题：</label>
                <input  name="news_title" type="text" class="textInput required" size="50" value="<?php echo $models->title;?>">
            </p>
            <p class="nowrap">
                <label>标签：</label>
                <input  name="news_tag" type="text" class="textInput" size="50" value="<?php echo $models->tag;?>">
            </p>
            <p>
                    <label>发布时间：</label>
                    <input type="text" name="stime" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" value="<?php echo date("Y-m-d H:i:s",$models->stime); ?>"/>
                    <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <p>
                    <label>下线时间：</label>
                    <input type="text" name="etime" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" value="<?php echo date("Y-m-d H:i:s",$models->endtime); ?>"/>
                    <a class="inputDateButton" href="javascript:;">选择</a>
            </p>
            <div class="divider"></div>
            <p class="nowrap"><label>选择地区：</label></p>
            <p class="nowrap">
                <?php 
                $arr = explode(",",$models->zone_list);
                foreach(TmpList::$zone_list as $k=>$val){
                    printf('<label><input type="checkbox" name="zone[]" value="%s" %s/>%s</label>',$k,in_array($k,$arr)?"checked":"",$val);
                } ?>
            </p>
            <div class="divider"></div>
            <p class="nowrap"><label>文章内容：</label></p>
            <p>
                <textarea class="editor" upImgUrl="<?php echo Yii::app()->createAbsoluteUrl('homenews/imgupload'); ?>" upImgExt="jpg,jpeg,gif,png" name="news_content" rows="21" cols="70" ><?php echo $models->content;?></textarea>
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