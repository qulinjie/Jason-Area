<?php
/**
 * callHelp_list
 * 	
 */
?>

<div class="modal fade" id="confirm-callHelp-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">提示</h5>
			</div>
			<div class="modal-body">
			     <h4 id="confirm-modal-body" class="text-center">确认操作！</h4>
			</div>
			<div class="alert alert-danger" id="confirm-callHelp-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-callHelp">确定</button>
			</div>
		</div>
	</div>
</div>

<?php if(empty($data_list)){?>
<div class="alert alert-info" role="alert"><p class="text-center">
	无记录
</p></div>
<?php }else{?>
<div class="panel panel-default">
<div class="panel-heading">列表</div>
  <div class="panel-body">
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>席位号</th>
					<th>虚拟考号</th>
					<th>通话时间</th>
					<th>备注</th>
					<th>处理时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td><?php echo $item['MCH_NO'];?></td>
				<td><?php echo $item['ACCOUNT_NO'];?></td>
				<td><?php echo $item['add_timestamp'];?></td>
				<td><?php echo empty($item['remark'])?'-':$item['remark'];?></td>
				<td><?php echo (0 < strtotime($item['ope_timestamp']))? $item['ope_timestamp'] : '-';?></td>
				<td>
					<div class="btn-group" role="group">
    			       <a id="callHelp-changeInfo-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">修改</a>
					</div>
				</td>
			</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager">
		    <li><a id="callHelp-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li>
		    	<span id="callHelp-current-page"><?php echo $current_page;?></span> / 
		    	<span id="callHelp-total-page"><?php echo $total_page;?></span>
		    </li>
		    <li><a id="callHelp-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
		    <li>
    		    <select class="form-control input-sm " id="callHelp-custom-page" style="width: 60px;height:26px;display:inline;"></select>
		    </li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>