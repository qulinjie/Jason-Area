<?php
/**
 * authorizationCode_list.php
 * 	
 */
?>

<div class="modal fade" id="confirm-entity-modal">
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
			<div class="alert alert-danger" id="confirm-entity-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-entity">确定</button>
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
<div class="panel-heading">授权码列表</div>
  <div class="panel-body">
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>授权码</th>
					<th>使用方式</th>
					<th>可用次数</th>
					<th>有效开始时间</th>
					<th>有效结束时间</th>
					<th>已使用次数</th>
					<th>添加时间</th>
					<th>停用时间</th>
					<th>状态</th>
					<th>备注</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td><?php echo $item['code'];?></td>
				<td><?php echo $item['type'];?></td>
				<td><?php echo $item['active_count'];?></td>
				<td><?php echo $item['time_start'];?></td>
				<td><?php echo $item['time_end'];?></td>
				<td><?php echo $item['used_count'];?></td>
				<td><?php echo $item['add_timestamp'];?></td>
				<td><?php echo (0 < strtotime($item['disenabled_timestamp']))? $item['disenabled_timestamp'] : '-';?></td>
				<td><?php if($item['status']==1){ echo "启用"; } else if($item['status']==2) { echo "停用"; } else { echo "失效"; }?></td>
				<td><?php echo $item['comment'];?></td>
				<td>
					<div class="btn-group" role="group">
					  <a id="entity-infoUpd-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">修改</a>
					  <a id="entity-enabledStatus-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">
					       <?php if($item['enabled_status']==1){?>停用<?php } else {?>启用<?php }?>
					  </a>
					  <a id="entity-resetPwd-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">重置密码</a>
					  <a id="entity-infoDisplay-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">更多信息</a>
					  <a id="entity-delete-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">删除</a>
					</div>
				</td>
			</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager">
		    <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
		    <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
		    <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>