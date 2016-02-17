<?php
/**
 * bcsInflow_list
 * 	
 */
?>

<?php if(empty($data['List'])){?>
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
					<th>席位号</th>
					<th>监管流水号</th>
					<th>交易流水号</th>
					<th>出入金类型</th>
					<th>交易状态</th>
					<th>交易金额</th>
					<th>交易完成时间</th>
					<th>付款账号</th>
					<th>付款户名</th>
					<th>收款账号</th>
					<th>收款户名</th>
					<th>业务日期</th>
					<th>备注</th>					
				</tr>
			</thead>
			<tbody>
<?php foreach ($data['List'] as $item){?>
			<tr>
				<td><?php echo $item['SIT_NO'];?></td>
				<td><?php echo $item['FMS_TRANS_NO'];?></td>
				<td><?php echo $item['MCH_TRANS_NO'];?></td>
				<td><?php echo $item['TransType'];?></td>
				<td><?php echo $item['TRANS_STATE'];?></td>
				<td><?php echo $item['TRANS_AMT'];?></td>
				<td><?php echo $item['TRANS_TIME'];?></td>				
				<td><?php echo $item['PAYMENT_NO'];?></td>
				<td><?php echo $item['PAYMENT_NAME'];?></td>
				<td><?php echo $item['PROCEEDS_NO'];?></td>
				<td><?php echo $item['PROCEEDS_NAME'];?></td>				
				<td><?php echo $item['BUSI_DATE'];?></td>
				<td><?php echo $item['RMRK'];?></td>				
			</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager" id="entity-pager-ul">
		    <li><a id="entity-list-prev" href="#" <?php if($data['PAGE_NUMBER'] == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li><span id="entity-current-page"><?php echo $data['PAGE_NUMBER'];?></span> /  <span id="entity-total-page"><?php echo $data['PAGE_COUNT'];?></span></li>
		    <li><a id="entity-list-next" href="#" <?php if($data['PAGE_NUMBER'] == $data['PAGE_COUNT']){?> class="disabled" <?php }?>>下一页</a></li>
		    <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>