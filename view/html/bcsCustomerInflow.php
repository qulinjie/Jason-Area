<h1 class="page-header">出入金明细列表</h1>
<table class="table table-hover">
	<thead style="font: bold 12px 微软雅黑;color: #8b8b8b;line-height: 40px;">
		<tr>
			<th>类型</th>
			<th>状态</th>
			<th>金额</th>
			<th>完成时间</th>
			<th>付款帐号</th>
			<th>付款户名</th>
			<th>收款行名</th>
			<th>收款行号</th>
			<th>收款帐号</th>
			<th>收款户名</th>
			<th>业务日期</th>
			<th>备注</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data['List'] as $val){?>
		<tr>
			<td><?php echo $val['TRANS_TYPE'];?></td>
			<td><?php echo $val['TRANS_STATE'];?></td>
			<td><?php echo $val['TRANS_AMT'];?></td>
			<td><?php echo $val['TRANS_TIME'];?></td>
			<td><?php echo $val['PAYMENT_NO'];?></td>
			<td><?php echo $val['PAYMENT_NAME'];?></td>
			<td><?php echo $val['MBR_BANK_NAME'];?></td>
			<td><?php echo $val['MBR_BANK_NO'];?></td>
			<td><?php echo $val['PROCEEDS_NO'];?></td>
			<td><?php echo $val['PROCEEDS_NAME'];?></td>
			<td><?php echo $val['BUSI_DATE'];?></td>
			<td><?php echo $val['RMRK'];?></td>
		</tr>
		<?php }?>
	</tbody>
	<nav>
		<ul class="pager" id="inflow-pager-ul">
			<li><a id="inflow-list-prev" href="#" <?php if($data['PAGE_NUMBER'] == 1){?> class="disabled" <?php }?>>上一页</a></li>
			<li><span id="inflow-current-page"><?php echo $data['PAGE_NUMBER'];?></span> /  <span id="inflow-total-page"><?php echo $data['PAGE_COUNT'];?></span></li>
			<li><a id="inflow-list-next" href="#" <?php if($data['PAGE_NUMBER'] == $data['PAGE_COUNT']){?> class="disabled" <?php }?>>下一页</a></li>
			<li><select class="form-control input-sm " id="inflow-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		</ul>
	</nav>
</table>

