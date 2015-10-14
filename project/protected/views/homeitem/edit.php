<div class="pageContent">
    <form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('homeitem/update'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, viData);" enctype="multipart/form-data">
        <div class="pageFormContent" layoutH="56">
            <p class="nowrap">
                <label>商品名称：</label>
                <input  name="shop_title" id="shop_title" type="text" class="textInput required" size="50" value="<?php echo $models->name;?>">
            </p>
            <p>
                <label>商店名：</label>
                <select class="combox" name="shop_name">
                    <?php
                    foreach($shopList as $val)
                    {
                        echo sprintf('<option value="%d" %s>%s</option>',$val['id'],$models->p_id==$val['id']?"selected":"",$val['name']);
                    }
                    ?>
                </select>
            </p>
            <p class="nowrap">
                <label>价格：</label>
                <input  name="shop_price" id="shop_price" type="text" class="textInput required" size="50" value="<?php echo $models->price;?>">
            </p>
            <?php if($models->img!=""){?><p class="nowrap"><label>商品图片：</label><img width="120" height="120" src="<?php echo Yii::app()->request->baseUrl.$models->img;?>"></p>
            <?php }?>
            <p class="nowrap">
                <label>更换商品图片：</label>
                <input name="shop_img" type="file">
                <input name="shop_id" type="hidden" value="<?php echo $models->id;?>">
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