<form id="pagerForm" onsubmit="return navTabSearch(this);" action="<?php echo Yii::app()->createAbsoluteUrl('homeadvert/index'); ?>" method="post">
    <input type="hidden" name="pageNum" value="<?php echo $pages['pageNum'];?>" /><!--【必须】value=1可以写死-->
    <input type="hidden" name="numPerPage" value="50" /><!--【可选】每页显示多少条-->
</form>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="add" mask="true" height="360" width="700" target="dialog" href="<?php echo Yii::app()->createAbsoluteUrl('homeadvert/newsadd');?>"><span>添加</span></a></li>
        </ul>
    </div>
    <table class="table" width="1040" layoutH="76">
        <thead>
        <tr>
            <th width="20">编号</th>
            <th width="60">唯一标示</th>
            <th width="100">标题</th>
            <th width="180">内容</th>
            <th width="40">图片查看</th>
            <th width="40">链接地址</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $value) {?>
            <tr>
                <td><?php echo $value['id']; ?></td>
                <td><?php echo $value['uid']; ?></td>
                <td title="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></td>
                <td title="<?php echo $value['desc']; ?>"><?php echo $value['desc']; ?></td>
                <td><?php if(trim($value['img'])!=""){ ?><a href="<?php echo Yii::app()->request->baseUrl.$value['img']; ?>" class="btnView" target="_blank">图片查看</a><?php }?></td>
                <td><?php if(trim($value['link'])!=""){ ?><a href="<?php echo $value['link']; ?>" class="btnView" target="_blank">图片查看</a><?php }?></td>

                <td>
                    <a title="确实要删除这条记录吗?" callback="deleteAuCall" target="ajaxTodo" href="<?php echo Yii::app()->createAbsoluteUrl('homeadvert/newsdel',array('id'=>$value['id'])); ?>" class="btnDel">删除</a>
                    <a title="编辑" height="560" mask="true" width="620" target="dialog" href="<?php echo Yii::app()->createAbsoluteUrl('homeadvert/newsedit',array('id'=>$value['id'])); ?>" class="btnEdit">编辑</a>
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