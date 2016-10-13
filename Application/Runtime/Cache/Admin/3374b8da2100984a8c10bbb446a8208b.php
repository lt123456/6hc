<?php if (!defined('THINK_PATH')) exit();?><div class="modal-header no-padding">
    <div class="table-header">
        <i class="icon-lock"></i>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        添加友情连接
    </div>
</div>

<div class="modal-body overflow-visible">
    <div class="control-group">
        <label class="control-label" for="name">名称</label>
        <div class="controls">
            <input type="text" required="required" id="name" name="name" placeholder="名称"/>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="url">URL</label>
        <div class="controls">
            <input type="text" required="required" id="url" name="url" placeholder="注意添加http://"/>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-small">
        <i class="icon-remove"></i>
        取消
    </button>
    <button type="button" class="btn btn-small btn-primary" onclick="$.add('<?php echo U('Admin/Link/doAdd');?>', this);">
        <i class="icon-ok"></i>确认
    </button>
</div>