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
  <div class="panel-body" style="width: 1500px;overflow-x: scroll;">
		<table class="table table-hover" style="width: 3000px;max-width: 3000px;overflow-x: scroll;">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th style="display:none">用户ID</th>
					<th>交易流水号</th><!-- 商户交易流水号 -->
					<?php if(!UserController::getLoginUser()){?>
					<th>用户账号</th>
					<th>用户名称</th>
					<th>用户公司名称</th>
					<?php }?>
					<th>出入金类型</th>
					<th>席位号</th>
					<th>币别</th>
					<th>交易金额</th>
					<th>手续费</th>
					<th>交易状态</th>
					<th>交易完成时间</th>
					<th>交易发送状态</th>
					<th>交易发送时间</th>
					<th>备注</th>
					<?php if(!UserController::getLoginUser()){?>
					<th>资金监管交易流水号</th>
					<?php }?>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td style="display:none"><?php echo $item['user_id'];?></td>
				<td><?php echo $item['MCH_TRANS_NO'];?></td>
				<?php if(!UserController::getLoginUser()){?>
				<td><?php echo $item['account'];?></td>
				<td><?php echo $item['nicename'];?></td>
				<td><?php echo $item['company_name'];?></td>
				<?php }?>
				<td><?php echo (1== $item['transfer_type'])?'出金':'入金';?></td>
				<td><?php echo $item['SIT_NO'];?></td>
				<td><?php echo ('01'== $item['CURR_COD'])?'人民币':'其它';?></td>
				<td><?php echo number_format($item['TRANS_AMT'],2);?></td>
				<td><?php echo number_format($item['TOTALAMT'],2);?></td>
				<td><?php echo (1== $item['TRANS_STS'])?'成功':((2== $item['TRANS_STS'])?'失败':'处理中');?></td>
				<td><?php echo (0 < strtotime($item['TRANS_TIME']))? $item['TRANS_TIME'] : '-';?></td>
				<td><?php if($item['status']==BcsTransferModel::$_status_success){ echo "成功"; } 
            				else if($item['status']==BcsTransferModel::$_status_failed) { echo "失败"; } 
                            else if($item['status']==BcsTransferModel::$_status_unknown) { echo "未知"; } ?>
				</td>
				<td><?php echo $item['add_timestamp'];?></td>
				<td><?php echo empty($item['comment'])?'-':$item['comment'];?></td>
				<?php if(!UserController::getLoginUser()){?>
				<td><?php echo $item['FMS_TRANS_NO'];?></td>
				<?php }?>
				<td>
					<div class="btn-group" role="group">
			             <a id="entity-loadInfo-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">更新</a>
					</div>
				</td>
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