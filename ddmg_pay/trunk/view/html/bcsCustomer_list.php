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
					<th>用户登录账号</th>
					<th>用户登录名称</th>
					<th>分公司名称</th>
					<th>专属帐户名</th>
					<th>专属帐号</th>
					<!--  
					<th>客户名称</th>
					<th>证件号码</th>
					<th>证件类型</th>
					<th>客户结算账户</th>
					<th>客户结算账户名</th>
					<th>客户结算账户行名</th>
					<th>客户结算账户行号</th>
					<th>客户手机号码</th>
					<th>客户电话号码</th>
					<th>客户地址</th>
					<th>客户状态</th>
					-->
					<th>余额</th>
					<th>可用余额</th>
					<!--
					<th>注册时间</th>
					<th>开户日期</th>
					<th>签约时间</th>
					<th>解约时间</th>
					-->
					<th>备注</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td><?php echo ('-1' == $item['user_id']) ? '' : $item['user_id'];?></td>
				<td><?php echo $item['user_name'];?></td>
				<td><?php echo $item['user_fgs_dm'];?></td>
				<td><?php echo $item['SIT_NO'];?></td>				
				<td><?php echo $item['ACCOUNT_NO'];?></td>				
				<!--
				<td><?php echo $item['MBR_NAME'];?></td>
				<td><?php echo $item['MBR_CERT_NO'];?></td>
				<td><?php echo $item['MBR_CERT_TYPE'];?></td>
				<td><?php echo $item['MBR_SPE_ACCT_NO'];?></td>
				<td><?php echo $item['MBR_SPE_ACCT_NAME'];?></td>
				<td><?php echo $item['MBR_BANK_NAME'];?></td>
				<td><?php echo $item['MBR_BANK_NO'];?></td>
				<td><?php echo $item['MBR_PHONE'];?></td>
				<td><?php echo $item['MBR_TELENO'];?></td>
				<td><?php echo $item['MBR_ADDR'];?></td>
				<td><?php echo (1== $item['MBR_STS'])?'已注册':((2== $item['MBR_STS'])?'已签约':'已注销');?></td>
				-->
				<td><?php echo number_format($item['ACCT_BAL'],2);?></td>
				<td><?php echo number_format($item['AVL_BAL'],2);?></td>
				<!--
				<td><?php echo $item['add_timestamp'];?></td>
				<td><?php echo (0 < strtotime($item['SIGNED_DATE']))? $item['SIGNED_DATE'] : '-';?></td>
				<td><?php echo (0 < strtotime($item['ACT_TIME']))? $item['ACT_TIME'] : '-';?></td>
				<td><?php echo (0 < strtotime($item['cancel_TIME']))? $item['cancel_TIME'] : '-';?></td>
				-->
				<td><?php echo empty($item['comment'])?'-':$item['comment'];?></td>
				<td>
					<div class="btn-group" role="group">
					   <?php if( 1 == $item[record_bank_type] ){ ?>
    			       <a id="entity-loadInfo-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">刷新</a>
    			       <?php } else if( 2 == $item[record_bank_type] ){ ?>
    			       <a id="entity-loadInfo-btn2" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">刷新</a>
    			       <?php } ?>
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