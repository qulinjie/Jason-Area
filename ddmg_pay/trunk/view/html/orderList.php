<?php
/**
 * 
 */
$session = Controller::instance('session');
$loginUser = $session->get( 'loginUser' );
?>
<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">



<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">订单列表</h2>
	</div>
	<div class="col-md-6 text-right">
			<?php if ( $loginUser['is_partner'] == 1 )  ?>
		  <a href="<?php echo Router::getBaseUrl() ?>order/add?<?= $queryString ?>" class="btn btn-success text-right">帮下单</a>
			<?php  ?>
	</div>
</div>

<div class="row">
		<form class="form-inline" action="<?php echo Router::getBaseUrl() ?>order" method="get">
	<table class="table">
    <tr>
        <td align="right" style="vertical-align:middle">订单编号</td>
        <td align="left"><input name="order_num" id="order_num" pattern="[0-9]{12}" placeholder="12位数字" value="<?=$params['order_num'] ?>" class="form-control" /></td>
        <td align="right" style="vertical-align:middle">收货人</td>
        <td align="left"><input name="consignee" id="consignee" value="<?=$params['consignee'] ?>" class="form-control" /></td>
        <td align="right" style="vertical-align:middle">收货电话</td>
        <td align="left"><input name="tel" id="tel" placeholder="11位数字" pattern="^13[0-9][0-9]{8}|15[89][0-9]{8}|18[56]\d{8}" value="<?=$params['tel'] ?>" class="form-control" /></td>
        <td align="right" style="vertical-align:middle">配送方式</td>
        <td align="left">
            <select name="shipping_type" id="shipping_type" class="form-control step4" style="width:170px">
				<option value="">全部</option>
				<?php foreach( $shipping_type_list as $k=>$v ) { ?>
					<option value="<?=$k?>" <?=( $k==$params['shipping_type'] )?'selected="selected"':false;  ?>><?=$v ?></option>
				<?php } ?>
			</select>
        </td>
    </tr>
    <tr>
        <td align="right" style="vertical-align:middle">订单状态</td>
        <td align="left">
            <select name="status" id="status" class="form-control" style="width:170px">
				<option value="">全部</option>
				<?php foreach( $status_list as $k=>$v ) { ?>
					<option value="<?=$k?>" <?=( $k==$params['status'] )?'selected="selected"':false;  ?>><?=$v ?></option>
				<?php } ?>
			</select>
        </td>
		<td align="right" style="vertical-align:middle">发票状态</td>
		<td align="left">
			<select name="invoicing_status" id="invoicing_status" class="form-control" style="width:170px">
				<option value="">全部</option>
				<?php foreach( $invoicing_status as $k=>$v ) { ?>
					<option value="<?=$k?>" <?=( $k==$params['invoicing_status'] )?'selected="selected"':false;  ?>><?=$v ?></option>
				<?php } ?>
			</select>
		</td>
        <td align="right" style="vertical-align:middle">下单时间</td>
        <td colspan="2" align="left">
            <input name="start_date" id="start_date" readonly class="form-control form_datetime" />
                                    至
            <input name="end_date" id="end_date" readonly class="form-control form_datetime" />
        </td>
        <td colspan="3" align="left">
            <button type="submit" class="btn btn-info">搜索</button>
        </td>
    </tr>
    </table>
		</form>
</div>

<hr/>
<div class="row">
	<div class="col-md-12 col-md-offset-0" id="cascading">
		  <a href="<?php echo Router::getBaseUrl() ?>order" class="btn 
<?php 
	echo  ( !$params['status'] ) ? 'btn-info' : 'btn-default';
 ?>
">全部订单</a>

		  <a href="<?php echo Router::getBaseUrl() ?>order?status=1" class="btn 
<?php 
	echo  ( $params['status']==1 ) ? 'btn-info' : 'btn-default';
 ?>
">待审核订单</a>

		  <a href="<?php echo Router::getBaseUrl() ?>order?status=5" class="btn 
<?php 
	echo  ( $params['status']==5 ) ? 'btn-info' : 'btn-default';
 ?>
">待提货订单</a>
   </div>
</div>
<div class="row">
	&nbsp;
</div>

<!--<h2 class="sub-header">Section title</h2>-->
<div class="table-responsive">
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
				<th>操作</th>
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
						 //echo '<td>'.$itemName. '</td>';
						 //echo '<td>'.$itemPrice. '</td>';
						 //echo '<td>'.$itemNum. '</td>';
					?>

					<td><?php echo $order['amount'] ?></td>

					<td><?php echo $order['consignee'] ?></td>
					<td><?php echo $order['tel'] ?></td>
					<td><?php echo $order['shipping_type_name'] ?></td>
					<td id="status-name-<?= $order['id'] ?>"><?php echo $order['status_name'] ?></td>
					<td>
						<?php if ( $order['status']==5 ) { ?>
							<a href="javascript:;" id="" class="delivery" alt="<?=$order['id']?>" >实发提货单</a>
							<a href="javascript:;" id="" class="done-order" alt="<?=$order['id']?>" >交易成功</a>
						<?php } ?>

						<?php if ( $order['status']==9 && $order['pay_way']==2 && $order['pay_status']==3 ) { ?>
							<a href="<?php echo Router::getBaseUrl() ?>order/unlinePayFor?<?=$queryString?>id=<?=$order['id']?>" id="" class="" alt="" >线下支付完成</a>
							<a href="javascript:;" id="" class="cancel-order" alt="<?=$order['id']?>" >取消订单</a>
						<?php } ?>

						<?php if ( $order['status']==1 ) { ?>
						<a href="<?php echo Router::getBaseUrl() ?>order/audit?<?=$queryString?>id=<?=$order['id']?>" class="">审核</a>
						<a href="<?php echo Router::getBaseUrl() ?>order/edit?<?=$queryString?>id=<?=$order['id']?>" class="">修改</a>
						<?php } ?>
<?php// 已申请支付，线下支付的?>

						<?php if ( $order['status']==5 && $order['purchase_status']==0 && $loginUser['is_seller']!=1 && $loginUser['is_partner']==1 ) { ?>
							<a class="need-to-purchase-btn" alt="<?= $order['id'] ?>" href="javascript:;" class="">需要采购</a>
						<?php } ?>

						<!--<a id="order-detail-btn" alt="<?= $order['id'] ?>" href="javascript:;" class="">详情</a>-->
						<a id="" alt="" href="<?php echo Router::getBaseUrl() ?>order/detail?<?=$queryString?>id=<?=$order['id']?>" class="">详情</a>

						<?php if ( $order['status']==2 || $order['status']==6) { ?>
							<a href="javascript:;" id="" class="invoicing" alt="<?=$order['id']?>" status="<?php echo $order['invoicing_status'];?>">发票(<?php echo $invoicing_status[$order['invoicing_status']];?>)</a>
						<?php } ?>
					</td>
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
	<div class="col-md-12 col-md-offset-0 text-center" >
		<?= $pager_html ?>
	</div>
<!--
						<nav>
						  <ul class="pagination">
							<li>
							  <a href="#" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
							  </a>
							</li>
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li>
							  <a href="#" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
							  </a>
							</li>
						  </ul>
						</nav>
-->
</div>



<div class="modal fade" id="msg-box">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
<!--
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
-->
      <div id="msg-box-content" class="modal-body text-center">
		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
		<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
      </div>
<!--
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--申请采购单-->
<div class="modal fade" id="purchase-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="tender-bid-title" >申请采购</h4>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" id="f-form-placeholder">
					<input type="hidden" id="order_id" name="order_id" value="">
				  <div class="form-group">
				    <label for="supplier" class="col-sm-2 control-label">供应商</label>
				    <div class="col-sm-9">
						<input id="supplier" name="supplier" value="" placeholder="供应商公司名称">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="supplier_contact" class="col-sm-2 control-label">联系人</label>
				    <div class="col-sm-9">
						<input id="supplier_contact" name="supplier_contact" value="" placeholder="联系人">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="supplier_tel" class="col-sm-2 control-label">联系电话</label>
				    <div class="col-sm-9">
						<input id="supplier_tel" name="supplier_tel" value="" placeholder="联系电话">
				    </div>
				  </div>

				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="confirm-purchase">确定</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			</div>
			<div id="return-info" class="">
			</div>
		</div>
	</div>
</div>

<!--下线支付完成-->
<div class="modal fade" id="unline-pay-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="tender-bid-title" >线下支付信息补充填</h4>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" id="f-form-placeholder">
					<input type="hidden" id="orderId" name="order_id" value="">
				  <div class="form-group">
				    <label for="pay_company" class="col-sm-2 control-label">付款单位</label>
				    <div class="col-sm-9">
						<input id="pay_company" name="pay_company" value="" placeholder="付款单位名称">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="pay_account" class="col-sm-2 control-label">付款帐号</label>
				    <div class="col-sm-9">
						<input id="pay_account" name="pay_account" value="" placeholder="付款帐号">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="payee_company" class="col-sm-2 control-label">收款单位</label>
				    <div class="col-sm-9">
						<input id="payee_company" name="payee_company" value="" placeholder="收款单位名称">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="payee_account" class="col-sm-2 control-label">收款帐号</label>
				    <div class="col-sm-9">
						<input id="payee_account" name="payee_account" value="" placeholder="收款帐号">
				    </div>
				  </div>

				  <div class="alert alert-danger" id="tender-bid-hint" style="display:none;"></div>
				  <div class="alert alert-success" id="tender-bid-hint2" style="display:none;"></div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="confirm-unline-pay-btn">确定</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			</div>
			<div id="return-info" class="">
			</div>
		</div>
	</div>
</div>

<!--提货实收单-->
<div class="modal fade" id="delivery-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="" >提货实收单</h4>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" id="">
				  <input type="hidden" id="delivery-order-id" name="order_id" value="">

				  <div class="form-group">
				    <label for="delivery-quantity" class="col-sm-2 control-label">实发总件数</label>
				    <div class="col-sm-9">
						<input id="delivery-quantity" name="delivery-quantity" value="" placeholder="实发件数">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="delivery-allton" class="col-sm-2 control-label">实发总重量(吨)</label>
				    <div class="col-sm-9">
						<input id="delivery-allton" name="delivery-allton" value="" placeholder="实发重量">
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="delivery-amount" class="col-sm-2 control-label">总金额</label>
				    <div class="col-sm-9">
						<input id="delivery-amount" name="delivery-amount" value="" placeholder="总金额">
				    </div>
				  </div>

				  <div class="alert alert-danger" id="delivery-err" style="display:none;"></div>
				  <div class="alert alert-success" id="delivery-ok" style="display:none;"></div>

				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="confirm-delivery">确定</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			</div>
			<div id="return-info" class="">
			</div>
		</div>
	</div>
</div>

<!--发票状态修改-->
<div class="modal fade" id="invoicing-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="" >发票状态</h4>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" id="invoicing_form">
					<input type="hidden" id="invoicing-order-id" name="invoicing-order-id" value="">
					<div class="form-group">
						<label for="delivery-quantity" class="col-sm-2 control-label">发票状态</label>
						<div class="col-sm-9">
							<select id="setting_invoicing_status" name="setting_invoicing_status" class="form-control">
								<?php foreach($invoicing_status as $key => $value){?>
									<option value="<?php echo $key?>"><?php echo $value?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="alert alert-danger" id="invoicing-err" style="display:none;"></div>
					<div class="alert alert-success" id="invoicing-ok" style="display:none;"></div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="save-invoicing">确定</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			</div>
			<div id="return-info" class="">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">

$(".form_datetime").datetimepicker({
	language:  'zh-CN',
	format: 'yyyy-mm-dd hh:ii'
});


// 点击详情 
$( '#order-detail-btn' ).on( 'click', function(){
	var id =  <?= $order['id'] ? $order['id'] : "''"; ?>;
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'order/detail', {'id':id},
			function(data) {
				if(data.code != 0) 
				{ // 失败
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
				}
				else
				{ // 成功
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
				}
			},
			'json'
		);

		setTimeout(function(){  // 1秒后自动关闭弹层
			$('#msg-box').modal('hide');
		},1000);
} );

$( '#confirm-purchase' ).click( function() {
	var order_id = $('#order_id').val().trim();
	if ( order_id == '' ) {
		$('#msg-box-content').html( 'no have order_id' );
		$('#msg-box').modal('show');
		return false;
	}
	var supplier = $('#supplier').val().trim();
	if ( supplier == '' ) {
		$('#msg-box-content').html( 'no have supplier' );
		$('#msg-box').modal('show');
		return false;
	}
	var supplier_contact = $('#supplier_contact').val().trim();
	if ( supplier_contact == '' ) {
		$('#msg-box-content').html( 'no have supplier_contact' );
		$('#msg-box').modal('show');
		return false;
	}
	var supplier_tel = $('#supplier_contact').val().trim();
	if ( supplier_tel == '' ) {
		$('#msg-box-content').html( 'no have supplier_tel');
		$('#msg-box').modal('show');
		return false;
	}

	var data = {};
	data.order_id = order_id;
	data.supplier = supplier;
	data.supplier_contact = supplier_contact;
	data.supplier_tel = supplier_tel;

	$.post(BASE_PATH + 'purchase/doAdd', data,
			function(data) {
				console.log( data );
				if(data.code != 0) 
				{ // 失败
					$('#return-info').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
				}
				else
				{ // 成功
					$('#return-info').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );

					setTimeout(function(){  // 1秒后自动关闭弹层
							$('#purchase-modal').modal('hide');
							window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
					},1000);

					$('#order_id').val('');
					$('#supplier').val('');
					$('#supplier_contact').val('');
					$('#supplier_tel').val('');
					$('#return-info').html('');

				}
			},
			'json'
		);

} );

// 点击需要采购
$( '.need-to-purchase-btn' ).click( function() {
	var id = $(this).attr( 'alt' );
	$('#order_id').val(id);
	$('#purchase-modal').modal('show');
} );

// 点击线下支付完成
$( '.unline-pay-compelet-btn' ).click( function() {
	//console.log( $(this).attr('alt').trim() );
	$( '#orderId' ).val( $(this).attr('alt').trim() );
	$('#unline-pay-modal').modal('show');
} );

function showErrMsg( msg )
{
	$("#tender-bid-hint").html( msg ).fadeIn();
	setTimeout(function() {
		$("#tender-bid-hint").html(' no have id ').fadeOut();
	},3000);
}
function showSucMsg( msg )
{
	$("#tender-bid-hint2").html( msg ).fadeIn();
	setTimeout(function() {
		$("#tender-bid-hint2").html(' no have id ').fadeOut();
	},3000);
}

// 点击确认支付完成
$('#confirm-unline-pay-btn').click( function() {
	var id = $('#orderId').val().trim();
	console.log( id=='' );
	if ( id == '' ) {
		showErrMsg( ' no have id ' );
		return false;
	}
	var pay_company = $('#pay_company').val().trim();
	if ( pay_company == '' ) {
		showErrMsg( '请填写付款单位' );
		return false;
	}
	var pay_account = $('#pay_account').val().trim();
	if ( pay_account == '' ) {
		showErrMsg( '请填写付款帐号' );
		return false;
	}
	var payee_company = $('#payee_company').val().trim();
	if ( payee_company == '' ) {
		showErrMsg( '请填写收款单位' );
		return false;
	}
	var payee_account = $('#payee_account').val().trim();
	if ( payee_account == '' ) {
		showErrMsg( '请填写付款帐号' );
		return false;
	}

	var data = {};
	data.id = id;
	data.pay_company = pay_company;
	data.pay_account = pay_account;
	data.payee_company = payee_company;
	data.payee_account = payee_account;
	$.post(BASE_PATH + 'order/unlinePay', data,
			function(data) {
				if(data.code != 0) 
				{ // 失败
					//$("#tender-bid-hint").html(data.msg + '(' + result.code + ')').fadeOut(4000);
					showErrMsg( '操作失败' );
				}
				else
				{ // 成功
					//$("#tender-bid-hint2").html(data.msg + '(' + result.code + ')').fadeOut(4000);
					showSucMsg( '操作成功' );
					// 改变状态
					$('#status-name-'+id).html('待提货');
					// 隐藏操作
					$('#unline-pay-btn-'+id).hide();

					setTimeout(function(){  // 1秒后自动关闭弹层
						$('#unline-pay-modal').modal('hide');
					},1000);
				}
			},
			'json'
		);
} );


// 点击提货实发
$('.delivery').click( function() {
	var id = $(this).attr( 'alt' );
	if ( id=='' ) {
		alert( 'no have id' );
		return false;
	}
	var data = {};
	data.id = id;
	$.post( BASE_PATH + 'order/deliveryInfo', data, function(d) {
		if ( d.code === 0 ) {
			$('#delivery-quantity').val(d.data.quantity);
			$('#delivery-allton').val(d.data.allton);
			$('#delivery-amount').val(d.data.amount);
		}
	},'json');
	$('#delivery-order-id').val(id);
	$('#delivery-modal').modal( 'show' );
} );

// 确认交付信息
$('#confirm-delivery').click( function() {

	var id = $('#delivery-order-id').val();
	if ( id == '' ) {
		alert( ' no id ' );
		return false;
	}

	// 实收商品数量
	var quantity = $('#delivery-quantity').val();
	if ( quantity == '' ) {
		alert( ' no quantity ' );
		return false;
	}

	// 实收商品重量
	var allton = $('#delivery-allton').val();
	if ( allton == '' ) {
		alert( ' no allton ' );
		return false;
	}
	// 总金额
	var amount = $('#delivery-amount').val();
	if ( amount == '' ) {
		alert( ' no amount ' );
		return false;
	}

	var data = {};
	data.id = id;
	data.quantity = quantity;
	data.allton = allton;
	data.amount = amount;

	$.post( BASE_PATH + 'order/delivery', data, function(d) {
		console.log( d );
		if ( d.code != 0 ) {
			$("#delivery-err").html( d.msg ).fadeIn();
			setTimeout(function() {
				$("#delivery-err").fadeOut();
			},3000);

		} else {
			$("#delivery-ok").html( d.msg ).fadeIn();
			setTimeout(function() {
				$("#delivery-ok").fadeOut();
				$('#delivery-modal').modal( 'hide' );
				$('#delivery-order-id').val('');
			},3000);
		}
	}, 'json' );
});

// 取消订单
$('.cancel-order').click( function(){
	if (!confirm('确定要取消吗?')) {
		return false;
	}
	var order_id = $(this).attr('alt');
	if ( order_id == '' ) {
		alert( ' no have order_id ' );
		return false;
	}

	$.post(BASE_PATH + 'order/cancel', {'id':order_id},
			function(d) {
				$('#msg-box').modal('show');
				if(d.code != 0) 
				{ // 失败
					$('#msg-box-content').html(  d.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
				}
				else
				{ // 成功
					$('#msg-box-content').html(  d.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					setTimeout(function(){  // 1秒后自动关闭弹层
							$('#msg-box').modal('hide');
							window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);
} );


// 交易成功
$('.done-order').click( function(){
	if (!confirm('确定要交易成功吗?')) {
		return false;
	}
	var order_id = $(this).attr('alt');
	if ( order_id == '' ) {
		alert( ' no have order_id ' );
		return false;
	}

	$.post(BASE_PATH + 'order/done', {'id':order_id},
			function(d) {
				$('#msg-box').modal('show');
				if(d.code != 0) 
				{ // 失败
					$('#msg-box-content').html(  d.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
				}
				else
				{ // 成功
					$('#msg-box-content').html(  d.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					setTimeout(function(){  // 1秒后自动关闭弹层
							$('#msg-box').modal('hide');
							window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);
} );

//弹出设置发票
$('.invoicing').click(function(){
	var id = $(this).attr('alt');
    var status = 	$(this).attr('status');
	if(id == '' || status== '') return false;
	$('#invoicing-order-id').val(id);
	$('#invoicing-modal').modal('show');
	$('#setting_invoicing_status option[value="'+status+'"]').attr('selected','selected');
	$('#invoicing-err').css('display','none');
	$('#invoicing-ok').css('display','none');
});
$('#save-invoicing').click( function(){
	var order_id = $('#invoicing-order-id').val();
	var invoicing_status   = $('#setting_invoicing_status').val();
	if ( order_id == '' || invoicing_status=='') {
		alert( ' no data ' );
		return false;
	}

	$.post(BASE_PATH + 'order/invoicing', {'id':order_id,'invoicing_status':invoicing_status},
			function(d) {
				if(d.code != 0)
				{ // 失败
					$('#invoicing-err').text(  d.msg).css('display','block');
				}
				else
				{ // 成功
					$('#invoicing-ok').text(  d.msg).css('display','block');
					setTimeout(function(){  // 1秒后自动关闭弹层
						$('#invoicing-modal').modal('hide');
						window.location.reload();
					},1000);
				}
			},
			'json'
	);
} );

/*  Example
    $('.form_datetime').datetimepicker({
		language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
 */
</script> 
