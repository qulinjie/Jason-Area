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
					<th>席位号</th>
					<th>用户登录账号</th>
					<th>用户名称</th>
					<th>用户公司名称</th>
					<th>虚拟卡号</th>
					<th>客户名称</th>
					<th>客户账户名</th>
					<th>证件号码</th>
					<th>证件类型</th>
					<th>客户结算账户</th>
					<th>客户结算账户行别</th>
					<th>客户结算账户行号</th>
					<th>客户结算账户行名</th>
					<th>客户手机号码</th>
					<th>客户电话号码</th>
					<th>客户地址</th>
					<th>客户备注</th>
					<th>是否开通电票</th>
					<th>是否个人</th>
					<th>状态</th>
					<th>注册时间</th>
					<th>签约时间</th>
					<th>备注</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td><?php echo $item['SIT_NO'];?></td>
				<td><?php echo $item['account'];?></td>
				<td><?php echo $item['nicename'];?></td>
				<td><?php echo $item['company_name'];?></td>
				<td><?php echo $item['ACCOUNT_NO'];?></td>
				<td><?php echo $item['CUST_NAME'];?></td>
				<td><?php echo $item['CUST_ACCT_NAME'];?></td>
				<td><?php echo $item['CUST_CERT_NO'];?></td>
				<td><?php echo $item['CUST_CERT_TYPE'];?></td>
				<td><?php echo $item['CUST_SPE_ACCT_NO'];?></td>
				<td><?php echo (0== $item['CUST_SPE_ACCT_BKTYPE'])?'长沙银行':'其它行';?></td>
				<td><?php echo $item['CUST_SPE_ACCT_BKID'];?></td>
				<td><?php echo $item['CUST_SPE_ACCT_BKNAME'];?></td>
				<td><?php echo $item['CUST_PHONE_NUM'];?></td>
				<td><?php echo $item['CUST_TELE_NUM'];?></td>
				<td><?php echo $item['CUST_ADDR'];?></td>
				<td><?php echo $item['RMRK'];?></td>
				<td><?php echo (1== $item['ENABLE_ECDS'])?'是':'否';?></td>
				<td><?php echo (1== $item['IS_PERSON'])?'是':'否';?></td>
				<td><?php echo (1== $item['status'])?'成功':((2== $item['status'])?'失败':'处理中');?></td>
				<td><?php echo $item['add_timestamp'];?></td>
				<td><?php echo (0 < strtotime($item['ACT_TIME']))? $item['ACT_TIME'] : '-';?></td>
				<td><?php echo empty($item['comment'])?'-':$item['comment'];?></td>
				<td>
					<div class="btn-group" role="group">
    			       <a id="entity-changeInfo-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">修改</a>
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