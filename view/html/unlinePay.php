<?php 
/**
 * 卖家修改商品页面
 */
?>
<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<div class="alert alert-danger" id="show-err" style="display:none;"></div>
<div class="alert alert-success" id="show-success" style="display:none;"></div>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">线下支付完成信息补填</h2>
	</div>
	<div class="col-md-6 text-right page-header">
		  <a href="<?php echo Router::getBaseUrl() ?>order?<?= $queryString ?>" class="btn btn-success text-right">订单列表</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<form class="form-horizontal" action="" method="">
		<input name="pay_id" id="pay_id" value="<?=$orders[0]['pay_id'] ?>" type="hidden" />
		<table class="table table-bordered">
			<tr>
				<!-- ajax 校验&自动填写 -->
				<td><span>付款单位:</span><input id="pay_company" name="pay_company" value="" placeholder="付款单位名称"></td>
				<td><span>付款帐号:</span><input id="pay_account" name="pay_account" value="" placeholder="付款帐号"></td>
				<td><span>付款时间:</span><input name="pay_time" id="pay_time" size="" readonly class=" form_datetime" /></td>
			</tr>
			<tr>
				<td><span>收款单位:</span><input id="payee_company" name="payee_company" value="" placeholder="收款单位名称"></td>
				<td><span>收款帐号:</span><input id="payee_account" name="payee_account" value="" placeholder="收款帐号"></td>
				<td><span>支付号:</span>&nbsp;&nbsp;&nbsp;<b><?= $orders[0]['pay_id'] ?></b></td>
			</tr>
		</table>
		</form>
	</div>
</div>

<div class="row">
	&nbsp;
</div>

<div class="table-responsive">
	<h2 class="page-header">同批支付的订单</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>序号</th>
				<th>订单编号</th>
				<th>下单时间</th>
				<th>商品</th>
				<th>单价(元/件)</th>
				<th>数量(件)</th>
				<th>订单金额(元)</th>
				<th>收货人</th>
				<th>收货电话</th>
				<th>配送方式</th>
				<th>订单状态</th>
				<th>支付方式</th>
				<th>支付号</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( $orders ) { ?>
			<?php foreach ( $orders as $key=>$order ) { ?>
				<tr>
					<td><?=  $key+1+($page-1)*$numPerPage; ?></td>
					<td><?=  $order['order_num'] ?></td>
					<td><?=  $order['add_timestamp'] ?></td>

					<td><?= $order['product_name'].'/'.$order['material_name'].'/'.$order['size_name'] ?></td>
					<td><?=  $order['price'] ?></td>
					<td><?=  $order['quantity'] ?></td>
					<?php 
						$itemName = $itemPrice = $itemNum = '';
						if ( !$order['orderItems'] ) {
						} else foreach ( $order['orderItems'] as $val ) {
							 $itemName .= $val['product_name'] .'/'.$val['material_name'].'/'.$val['size_name'].'<br/>';
							 $itemPrice .= $val['price'].'<br/>';
							 $itemNum .= $val['quantity'].'<br/>';
						 } 
					?>

					<td><?php echo $order['amount'] ?></td>

					<td><?php echo $order['consignee'] ?></td>
					<td><?php echo $order['tel'] ?></td>
					<td><?php echo $order['shipping_type_name'] ?></td>
					<td id="status-name-<?= $order['id'] ?>"><?php echo $order['status_name'] ?></td>
					<td><?php echo $order['pay_way']==1 ? '线上支付' : '线下支付' ?></td>
					<td><?= $order['pay_id'] ?></td>
				</tr>
			<?php } ?>
			<?php } else { ?>
				<tr>
					<td colspan="12"></td>
				</tr>
			<?php } ?>


		</tbody>
	</table>
</div>

<div class="row">
	&nbsp;
</div>

<div class="row">
	<div class="col-md-3">
	</div>
	<div class="col-md-3">
		  <a href="javascript:;" id="confirm-unline-pay-btn" class="btn btn-info text-left">确认提交</a>
	</div>
	<div class="col-md-3 text-right-disable ">
		  <a href="<?php echo Router::getBaseUrl() ?>order?<?= $queryString ?>" id="" class="btn btn-warning">返回列表</a>
	</div>
	<div class="col-md-3 text-right">
			&nbsp;
	</div>
</div>


<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(".form_datetime").datetimepicker({
	language:  'zh-CN',
	format: 'yyyy-mm-dd hh:ii'
});

function showErrMsg( msg )
{
	$("#show-err").html( msg ).fadeIn();
	setTimeout(function() {
		$("#show-err").html(' no have id ').fadeOut();
	},3000);
}
function showSucMsg( msg )
{
	$("#show-success").html( msg ).fadeIn();
	setTimeout(function() {
		$("#show-success").html(' no have id ').fadeOut();
	},3000);
}

// 点击确认支付完成
$('#confirm-unline-pay-btn').click( function() {
	var id = $('#pay_id').val().trim();
	if ( id == '' ) {
		showErrMsg( ' no have id ' );
		return false;
	}
	var pay_company = $('#pay_company').val().trim();
	if ( pay_company == '' ) {
		$( '#pay_company' ).focus();
		showErrMsg( '请填写付款单位' );
		return false;
	}
	var pay_account = $('#pay_account').val().trim();
	if ( pay_account == '' ) {
		$( '#pay_account' ).focus();
		showErrMsg( '请填写付款帐号' );
		return false;
	}
	var payee_company = $('#payee_company').val().trim();
	if ( payee_company == '' ) {
		$( '#payee_company' ).focus();
		showErrMsg( '请填写收款单位' );
		return false;
	}
	var payee_account = $('#payee_account').val().trim();
	if ( payee_account == '' ) {
		$( '#payee_account' ).focus();
		showErrMsg( '请填写付款帐号' );
		return false;
	}
	var pay_time = $('#pay_time').val().trim();
	if ( pay_time == '' ) {
		$( '#pay_time' ).focus();
		showErrMsg( '请选择支付时间' );
		return false;
	}

	var data = {};
	data.id = id;
	data.pay_company = pay_company;
	data.pay_account = pay_account;
	data.payee_company = payee_company;
	data.payee_account = payee_account;
	data.pay_time = pay_time;
	$.post(BASE_PATH + 'order/unlinePay', data,
			function(data) {
				if(data.code != 0) 
				{ // 失败
					showErrMsg( '操作失败' );
				}
				else
				{ // 成功
					showSucMsg( '操作成功' );

					setTimeout(function(){  // 1秒后自动关闭弹层
						window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);


	
} );


</script>







