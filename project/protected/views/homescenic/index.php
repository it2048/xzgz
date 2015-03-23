<div class="pageHeader">
    <form id="pagerForm" onsubmit="return navTabSearch(this);" action="<?php echo Yii::app()->createAbsoluteUrl('homescenic/index'); ?>" method="post">
        <input type="hidden" name="pageNum" value="<?php echo $pages['pageNum'];?>" /><!--【必须】value=1可以写死-->
        <input type="hidden" name="numPerPage" value="50" /><!--【可选】每页显示多少条-->
        <div class="searchBar">
            <table class="searchContent">
                <tbody><tr>
                    <td>
                        <select class="combox" name="zone_type">
                            <option value="all_zone">所有地区</option>
                            <?php foreach(TmpList::$zone_list as $k=>$val){
                                $ps = $pages['zone_type']==$k?'selected="selected"':"";
                                printf('<option value="%s" %s>%s</option>',$k,$ps,$val);
                            } ?>
                        </select>
                    </td>
                    <td>
                        景区名称：<input type="text" name="sec_name" class="textInput" value="<?php echo $pages['sec_name'];?>">
                    </td>
                    <td><div class="buttonActive"><div class="buttonContent"><button type="submit">搜索</button></div></div></td>
                </tr>
                </tbody></table>
        </div>
    </form>
</div>

<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" mask="true" height="560" width="700" target="dialog" href="<?php echo Yii::app()->createAbsoluteUrl('homescenic/newsadd');?>"><span>添加</span></a></li>
        </ul>
    </div>
    <table class="table" width="1040" layoutH="110">
        <thead>
        <tr>
            <th width="60">标题</th>
            <th width="60">概要</th>
            <th width="180">内容</th>
            <th width="40">是否推荐</th>
            <th width="40">录音查看</th>
            <th width="60">发布时间</th>
            <th width="60">游玩时间(分)</th>
            <th width="60">地址</th>
            <th width="40">x坐标</th>
            <th width="40">y坐标</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $value) {?>
            <tr>
                <td title="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></td>
                <td title="<?php echo $value['desc']; ?>"><?php echo $value['desc']; ?></td>
                <td title="<?php echo strip_tags($value['content']); ?>"><?php echo mb_substr(strip_tags($value['content']),0,20,"utf-8");?></td>
                <td><?php echo $value['top']==1?"是":"否"; ?></td>
                <td><?php if(trim($value['mp3'])!=""){ ?><a href="<?php echo Yii::app()->request->baseUrl.$value['mp3']; ?>" class="btnView" target="_blank">查看</a><?php }?></td>
                <td><?php echo date("Y-m-d H:i:s", $value['atime']); ?></td>
                <td><?php echo $value['ptime']; ?></td>
                <td><?php echo $value['add']; ?></td>
                <td><?php echo $value['x']; ?></td>
                <td><?php echo $value['y']; ?></td>
                <td>
                    <a title="确实要删除这条记录吗?" callback="deleteAuCall" target="ajaxTodo" href="<?php echo Yii::app()->createAbsoluteUrl('homescenic/newsdel',array('id'=>$value['id'])); ?>" class="btnDel">删除</a>
                    <a title="编辑" height="560" mask="true" width="620" target="dialog" href="<?php echo Yii::app()->createAbsoluteUrl('homescenic/newsedit',array('id'=>$value['id'])); ?>" class="btnEdit">编辑</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>共<?php echo $pages['countPage'];?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $pages['countPage'];?>" numPerPage="<?php echo $pages['numPerPage'];?>" pageNumShown="10" currentPage="<?php echo $pages['pageNum'];?>"></div>
    </div>
</div>
<script type="text/javascript">
    function deleteAuCall(res)
    {
        if(res.code!=0)
            alertMsg.error(res.msg);
        else
        {
            navTab.reload(res.newsmanager);  //刷新主页面
        }
    }
</script>