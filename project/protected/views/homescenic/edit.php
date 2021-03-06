<style type="text/css">
    .uploadify-queue{ display: none;
    }
</style>
<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homescenic/newsupdate'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <p class="nowrap">
                <label>标题：</label>
                <input  name="scien_title" id="scien_title" type="text" class="textInput required" size="50" value="<?php echo $models->title;?>">
            </p>
            <p class="nowrap"><label>选择地区：</label></p>
            <p class="nowrap">
                <?php foreach(TmpList::$zone_list as $k=>$val){
                        printf('<label><input type="radio" name="zone" %s value="%s" />%s</label>',$k==$models->zone?"checked":"",$k,$val);
                    } ?>
            </p>
            <div class="divider"></div>
            <p class="nowrap">
                <label>简要描述：</label>
                <input  name="scien_desc" id="scien_desc" type="text" class="textInput" size="50" value="<?php echo $models->desc;?>">
            </p>
            <?php if($models->icon!=""){?><p class="nowrap"><label>小图标：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->icon;?>"></p>
            <?php }?>
            <p class="nowrap">
                <label>更换小图标：</label>
                <input name="scien_icon" type="file">
            </p>
            <?php if($models->hicon!=""){?><p class="nowrap"><label>灰色小图标：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->hicon;?>"></p>
            <?php }?>
            <p class="nowrap">
                <label>更换灰色小图标：</label>
                <input name="scien_hicon" type="file">
            </p>
             <p class="nowrap">
                <label>地址描述：</label>
                <input  name="scien_add" id="scien_add" type="text" class="textInput" size="50" value="<?php echo $models->add;?>">
            </p>
            <p class="nowrap">
                <label>x坐标，y坐标：</label>
                <input  name="scien_x" type="text" class="required" size="10" value="<?php echo $models->x;?>">
                <input  name="scien_y" type="text" class="required" size="10" value="<?php echo $models->y;?>">
            </p>
            <p class="nowrap">
                <label>经度，纬度：</label>
                <input  name="scien_lng" type="text" class="required" size="10" value="<?php echo $models->lng;?>">
                <input  name="scien_lat" type="text" class="required" size="10" value="<?php echo $models->lat;?>">
            </p>
            <p class="nowrap">
                <label>景点范围：</label>
                <input  name="scien_around" type="text" class="textInput required" size="20" value="<?php echo $models->around;?>"><span class="info"> 单位:米</span>
            </p>
            <p class="nowrap">
                <label>游玩时间：</label>
                <input  name="scien_ptime" type="text" class="textInput" size="20" value="<?php echo $models->ptime;?>"><span class="info"> 例如:1天,2小时</span>
            </p>
            <p>
                <label>景点状态：</label>
                <select class="combox" name="scien_top">
                    <option value="0" <?php echo $models->top==0?"selected":"";?>>普通</option>
                    <option value="1" <?php echo $models->top==1?"selected":"";?>>推荐</option>
                </select>
            </p>
            <?php if($models->mp3!=""){?><p class="nowrap"><label>原录音地址：</label>
                <input name="mp3tmp" type="text" class="textInput readonly" size="50" readonly="true" value="<?php echo Yii::app()->request->baseUrl.$models->mp3;?>">
            </p>
            <?php }?>
            <p class="nowrap">
                <label>更换音频mp3：</label>
                <input name="scien_mp3" type="file">
                <input name="scien_id" type="hidden" value="<?php echo $models->id;?>">
            </p>
            <div class="divider"></div>
            <p class="nowrap">
                <div style="float: left;">
                <label>图片说明：</label>
                <input  name="img_desc" id="img_desc"  type="text" class="textInput" size="40" value="">
                </div>
                <div style="float: right;">
                <input id="testFileInput" type="file" name="image"/>
                </div>
            </p>
            <div class="divider"></div>
            <div id="test">
                <table class="list" width="560">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tbody id="tbody">
<?php
$imgList = json_decode($models->img,true);
$str = "";
foreach ($imgList as $key => $value) {
    $tr = "";
    if($key%4==0)
    {
        $tr = $key!=0?"</tr><tr>":"<tr>";
    }
    $str .= sprintf('%s<td id="td%s"><img src="%s" width="128" height="100" /><input name="url%s" type="hidden" value="%s">'
            . '<br/><input name="desc%s" type="text" class="textInput" size="16" value="%s"><br/>'
            . '<button type="button" onclick="deletetd(\'%s\',\'%s\')">删除</button></td>',$tr,
            $key,Yii::app()->request->baseUrl.$value['url'],$key,$value['url'],$key,
            $value['desc'],base64_encode($value['url']),$key);
    
}
$str .= "</tr>";
echo $str;
?>                        
                    </tbody>
                </table>
            </div>
            <div class="divider"></div>
            <p class="nowrap"><label>景点内容：</label></p>
            <p>
                <textarea class="editor" upImgUrl="<?php echo Yii::app()->createAbsoluteUrl('homenews/imgupload'); ?>" upImgExt="jpg,jpeg,gif,png" name="scien_content" rows="21" cols="74" ><?php echo $models->content;?></textarea>
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
    $(function() {
        var _id = <?php echo count($imgList);?>;
        $("#testFileInput").uploadify({
            'swf':'<?php echo Yii::app()->request->baseUrl; ?>/admincss/dwzthemes/uploadify/scripts/uploadify.swf',
            'uploader':'<?php echo Yii::app()->createAbsoluteUrl('adminimg/imgsave'); ?>',
            'formData':{'scien_title':"", 'scien_desc':"",'scien_source':""},
            'buttonText':'添加图片',
            'fileTypeDesc':'*.jpg;*.jpeg;*.gif;*.png;',
            'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png;',
            'height':20,
            'onUploadSuccess' : function(file, data, response) {

                var desc = $.trim($("#img_desc").val());
                json = jQuery.parseJSON(data);
                if(json.statusCode==200)
                {
                    if(_id%4==0)
                    {
                        $("#tbody").append('<tr><td id="td'+_id+'"><img src="<?php echo Yii::app()->request->baseUrl;?>'+json.img+'" width="128" height="100" /><input name="url'+_id+'" type="hidden" value="'+json.img+'"><br/><input name="desc'+_id+'" type="text" class="textInput" size="16" value="'+desc+'"><br/><button type="button" onclick="deletetd(\''+json.rel+'\',\''+_id+'\')">删除</button></td></tr>');
                    }else
                    {
                        $("#td"+(_id-1)).after('<td id="td'+_id+'"><img src="<?php echo Yii::app()->request->baseUrl;?>'+json.img+'" width="128" height="100" /><input name="url'+_id+'" type="hidden" value="'+json.img+'"><br/><input name="desc'+_id+'" type="text" class="textInput" size="16" value="'+desc+'"><br/><button type="button" onclick="deletetd(\''+json.rel+'\',\''+_id+'\')">删除</button></td>');
                    }
                    _id++;
                }
            }
        });
    });

    function deletetd(ul,id)
    {
        var url = '<?php echo Yii::app()->createAbsoluteUrl('adminimg/imgdel'); ?>/ph/'+ul;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            success: function(data) {
                if(data.code!=0)
                {
                    alertMsg.error(data.msg); //返回错误
                }else
                {
                    $("#td"+(id)).html("");
                }
            }
        });
    }
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