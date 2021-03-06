<form id="pagerForm" onsubmit="return navTabSearch(this);" action="<?php echo Yii::app()->createAbsoluteUrl('homenative/index'); ?>" method="post">
    <input type="hidden" name="pageNum" value="<?php echo $pages['pageNum'];?>" /><!--【必须】value=1可以写死-->
    <input type="hidden" name="numPerPage" value="50" /><!--【可选】每页显示多少条-->
</form>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" mask="true" height="560" width="700" target="dialog" href="<?php echo Yii::app()->createAbsoluteUrl('homenative/newsadd');?>"><span>添加</span></a></li>
        </ul>
    </div>
    <table class="table" width="1040" layoutH="76">
        <thead>
        <tr>
            <th width="60">标题</th>
            <th width="40">电话</th>
            <th width="60">区县</th>
            <th width="60">地址描述</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $value) {?>
            <tr>
                <td title="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></td>
                <td><?php echo $value['tel']; ?></td>
                <td><?php echo TmpList::$zone_list[$value['zone']]; ?></td>
                <td><?php echo $value['add']; ?></td>
                <td>
                    <a title="确实要删除这条记录吗?" callback="deleteAuCall" target="ajaxTodo" href="<?php echo Yii::app()->createAbsoluteUrl('homenative/newsdel',array('id'=>$value['id'])); ?>" class="btnDel">删除</a>
                    <a title="编辑" height="560" mask="true" width="620" target="dialog" href="<?php echo Yii::app()->createAbsoluteUrl('homenative/newsedit',array('id'=>$value['id'])); ?>" class="btnEdit">编辑</a>
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