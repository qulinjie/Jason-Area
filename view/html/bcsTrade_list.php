<?php
/**
 * bcsTrade_list.php
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
<div class="panel-heading" id="search-head-div">付款列表</div>
  <div class="panel-body">
		<table class="table table-hover" id="data-list-table">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>交易流水号</th>
					<th>订单号</th>
					<th>交易完成时间</th>
					<th>收款方</th>
					<th>付款金额</th>
					<th>付款时间</th>
					<th>状态</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td><?php echo $item['FMS_TRANS_NO'];?></td>
				<td><?php echo $item['order_no'];?></td>
				<td><?php echo (0 < strtotime($item['TRANS_TIME']))? $item['TRANS_TIME'] : '-';?>
				<td><?php echo $item['seller_name'];?></td>
				<td><?php echo number_format($item['TX_AMT'],2);?></td>
				<td><?php echo (0 < strtotime($item['add_timestamp']))? $item['add_timestamp'] : '-';?></td>
				<td><?php if($item['status']==BcsTradeModel::$_status_success){ echo "成功"; } 
            				else if($item['status']==BcsTradeModel::$_status_failed) { echo "失败"; } 
                            else if($item['status']==BcsTradeModel::$_status_unknown) { echo "未知"; } ?>
				</td>
				<td><?php echo $item['comment'];?></td>
			</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager" id="entity-pager-ul">
		    <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
		    <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
		    <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>