<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homenative/newsupdate'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <p class="nowrap">
                <label>标题：</label>
                <input  name="shop_title" id="shop_title" type="text" class="textInput required" size="50" value="<?php echo $models->name;?>">
                <input name="shop_id" type="hidden" value="<?php echo $models->id;?>">
            </p>
            <p class="nowrap"><label>选择地区：</label></p>
            <p class="nowrap">
                <?php foreach(TmpList::$zone_list as $k=>$val){
                        printf('<label><input type="radio" name="zone" %s value="%s" />%s</label>',$k==$models->zone?"checked":"",$k,$val);
                    } ?>
            </p>
            <div class="divider"></div>
            <p>
                <label>星级：</label>
                <select class="combox" name="shop_star">
                    <option value="1" <?php echo $models->star==1?"selected":"";?>>1星</option>
                    <option value="2" <?php echo $models->star==2?"selected":"";?>>2星</option>
                    <option value="3" <?php echo $models->star==3?"selected":"";?>>3星</option>
                    <option value="4" <?php echo $models->star==4?"selected":"";?>>4星</option>
                    <option value="5" <?php echo $models->star==5?"selected":"";?>>5星</option>
                </select>
            </p>
            <p class="nowrap">
                <label>电话：</label>
                <input  name="shop_tel" id="shop_desc" type="text" class="textInput" size="50" value="<?php echo $models->tel;?>">
            </p>

            <p class="nowrap">
                <label>地址描述：</label>
                <input  name="shop_add" id="shop_add" type="text" class="textInput" size="50" value="<?php echo $models->add;?>">
            </p>

            <div class="divider"></div>
            <p class="nowrap">
                <label>营业时间：</label>
                <input  name="shop_office" id="shop_desc" type="text" class="textInput" size="50" value="<?php echo $models->office;?>">
            </p>
            <p class="nowrap">
                <label>经度，纬度：</label>
                <input  name="shop_lng" type="text" class="required" size="10" value="<?php echo $models->lng;?>">
                <input  name="shop_lat" type="text" class="required" size="10" value="<?php echo $models->lat;?>">
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
            'formData':{'shop_title':"", 'shop_desc':"",'shop_source':""},
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