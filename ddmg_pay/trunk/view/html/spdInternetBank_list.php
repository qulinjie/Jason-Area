<?php
/**
 * callHelp_list
 * 	
 */
?>

<div class="modal fade" id="confirm-entity-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
<div class="panel-heading">列表</div>
  <div class="panel-body" >
		<table class="table table-hover" >
			<thead>
				<tr>
					<th style="display:none">id</th>
					<?php if(strval($isApplyIndex) == '1' || strval($isApplyIndex) == '2'){?>
					<th>操作</th>
					<?php }?>
<!-- 					<th>序号</th> -->
					<th>行号</th>
					<th>行名称</th>
					<th>更新时间</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
<!-- 				<td>< ?php echo $item['serialNo'];?></td> -->
				<?php if(strval($isApplyIndex) == '1' || strval($isApplyIndex) == '2'){?>
				<td> 	      
	  				<a id="entity-select-<?php echo $item['id'];?>" class="entity-select-bank" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">选择</a>
	        		<input id="entity-select-bankname-<?php echo $item['id'];?>" type="hidden" value="<?php echo $item['bankName'];?>" />
	        	</td>
	        	<?php }?>
				<td><?php echo $item['bankNo'];?></td>
				<td><?php echo $item['bankName'];?></td>
				<!-- <td><?php echo $item['city_name'];?></td> -->
				<td><?php echo $item['add_timestamp'];?></td>
			</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager" id="entity-pager-ul">
		    <li><a id="entity-list-prev-bank" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li><span id="entity-current-page-bank"><?php echo $current_page;?></span> /  <span id="entity-total-page-bank"><?php echo $total_page;?></span></li>
		    <li><a id="entity-list-next-bank" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
		    <li><select class="form-control input-sm " id="entity-custom-page-bank" style="width: 60px;height:26px;display:inline;"></select></li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>