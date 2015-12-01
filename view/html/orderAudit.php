<?php 
/**
 * 卖家修改商品页面
 */
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">审核订单</h2>
	</div>
	<div class="col-md-6 text-right page-header">
		  <a href="<?php echo Router::getBaseUrl() ?>order?<?= $queryString ?>" class="btn btn-success text-right">订单列表</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<table class="table table-bordered">
			<tr>
				<td><span>订单编号:</span> <?= $order['order_num'] ?></td>
				<td><span>下单时间:</span> <?= $order['add_timestamp']?></td>
				<td><span>订单状态:</span> <?= $order['status_name']?></td>
			</tr>
			<tr>
				<td><span>订单金额:</span> <?= $order['amount']?></td>
				<td><span>买家姓名:</span> <?= $order['consignee']?></td>
				<td><span>买家联系方式:</span> <?= $order['tel']?></td>
			</tr>
			<tr>
				<td><span>配送方式:</span> <?= $order['shipping_type_name']?></td>
				<td><span>收货人: </span> <?= $order['consignee']?></td>
				<td><span>联系方式: </span> <?= $order['tel']?></td>
			</tr>
			<tr>
				<td><span>配送地址:</span> <?= $order['address']?></td>
				<td><span>受权码: </span> <?= $order['pay_code']?></td>
				<td><span>提货码: </span> <?= $order['take_code']?></td>
			</tr>
			<tr>
				<td><span>提货/发货时间:</span> <?= $order['take_out_timestamp']?></td>
				<td><span>审核: </span> <?= $order['audit_timestamp']?></td>
				<td><span> </span> <?= $order['']?></td>
			</tr>
		</table>
	</div>
</div>

<div class="table-responsive">
	<h2 class="page-header">订单商品</h2>
	<table class="table table-striped table-hover">
		<thead>
				<th>序号</th>
				<th>商品</th>
				<th>单价(元/吨)</th>
				<th>件重(吨)</th>
				<th>件数</th>
				<th>小计(元)</th>
				<!--<th>操作</th>-->
			
		</thead>
		<tbody>
						<?php $n = 1; ?>
						<tr>
							<td><?= $n ?></td>
							<td class="product_name"><?= $order['product_name'].'/'.$order['material_name'].'/'.$order['size_name'] ?></td>
							<td><span id="price-<?=$order['item_id']?>"><?= $order['price'] ?></span>元</td>
							<td><span id="ton-<?=$order['item_id']?>"><?= $order['ton'] ?></span>吨</td>
							<td><?=$order['quantity']?></td>
							<td><span class="subtotal" id="subtotal-<?=$order['item_id'] ?>"><?= $a = $order['price']*$order['quantity']*$order['ton']; ?></span>&nbsp;元</td>
							<td>
								<!--<a href="javascript:;" alt="<?=$order['item_id'] ?>"  class="delete-order-item">删除</a>-->
							</td>
						</tr>
				</tbody>
	</table>
</div>
		<h3 class="text-right">商品总金额: <span id="total"><?php echo $a ?></span>元</h3>
<div class="row">
	<div class="col-md-3">
			&nbsp;
	</div>
	<div class="col-md-3">
		  <a href="javascript:;" id="denial-btn" class="btn btn-danger text-right">拒绝</a>
		  <!--<a href="<?php echo Router::getBaseUrl() ?>order/doAudit?" class="btn btn-danger text-right">拒绝</a>-->
	</div>
	<div class="col-md-3 text-right-disable ">
		  <a href="javascript:;"  id="allow-btn" class="btn btn-success text-right">通过</a>
		 <!-- <a href="<?php echo Router::getBaseUrl() ?>order/doAudit"  id="" class="btn btn-success text-right">通过</a>-->
	</div>
	<div class="col-md-3">
			&nbsp;
	</div>
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



<div class="modal fade" id="tender-bid-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="tender-bid-title" >审核不通过</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="f-form-placeholder">
				  <div class="form-group">
				    <label for="tender-bid-comment" class="col-sm-2 control-label">拒绝理由</label>
				    <div class="col-sm-9">
				      <textarea class="form-control" rows="3" id="tender-bid-comment"  placeholder="订单审核不通过的理由"></textarea>
				    </div>
				  </div>
				  <div class="alert alert-danger" id="tender-bid-hint" style="display:none;"></div>
				  <div class="alert alert-success" id="tender-bid-hint2" style="display:none;"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-confirm-tender">确定</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>

<script>

// 点击通过
$( '#allow-btn' ).on( 'click', function() {

	var id =  <?= $order['id'] ?>;
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'order/doAudit', {'id':id,'audit_result':'allow'},
			function(data){

				if(data.code != 0) {
					$('#msg-box-content').html('审核失败');
				}else {
					$('#msg-box-content').html('审核通过');
				}

				setTimeout(function(){ // 1秒后自动关闭弹层 + 跳转
					$('#msg-box').modal('hide');
					window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
				},1000);

			},
			'json'
		);

		setTimeout(function(){  // 1秒后自动关闭弹层
			$('#msg-box').modal('hide');
		},1000);
} );


// 点击拒绝通过
$(document).on('click', '#denial-btn', function(event){

	var id =  <?php echo $order['id'] ?>
	
	$("#btn-confirm-tender").removeAttr('disabled');
	$('#tender-bid-price').val('');
	$("#tender-bid-hint").html('').hide();
	$('#tender-bid-modal').modal('show');
	
	$('#btn-confirm-tender').unbind("click");
	$('#btn-confirm-tender').on('click', {'id':id},function(event){
		//$("#btn-confirm-tender").attr('disabled', 'disabled');
		
		var why_denial = $('#tender-bid-comment').val();
		
		$.post(BASE_PATH + 'order/doAudit', {'id':id, 'audit_result':'denial', 'why_denial':why_denial},
		        function(result){
		            if(result.code != 0) {
		                $("#tender-bid-hint").show()
						//$("#tender-bid-hint").html(result.msg + '(' + result.code + ')').fadeIn();
		                $("#tender-bid-hint").html(result.msg + '(' + result.code + ')').fadeOut(4000);
		            }else {
		                $("#tender-bid-hint2").show()
		            	$("#tender-bid-hint2").html(result.msg).fadeOut(4000);
		            	setTimeout(function(){
		            		$('#tender-bid-modal').modal('hide');
							window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
		            	},1000);
		            }
		        },
		        'json'
		    );
	});
});

</script>

