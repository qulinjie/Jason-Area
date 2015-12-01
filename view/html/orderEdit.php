<?php 
/**
 * 卖家修改商品页面
 */
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">订单修改</h2>
	</div>
	<div class="col-md-6 text-right page-header">
		  <a href="<?php echo Router::getBaseUrl() ?>order?<?= $queryString ?>" class="btn btn-success text-right">订单列表</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<form class="form-horizontal" action="" method="">
		<input name="id" id="id" value="<?=$order['id'] ?>" type="hidden" />
		<input name="del-order-item-id" id="del-order-item-id" value="" type="hidden" />
		<table class="table table-bordered">
			<tr>
				<td><span>订单编号:</span> <?= $order['order_num'] ?></td>
				<td><span>下单时间:</span> <?= $order['add_timestamp']?></td>
				<td><span>订单状态:</span> <?= $order['status_name']?></td>
			</tr>
			<tr>
				<td><span>订单金额:</span><input class=""   name="amount" id="amount" value="<?= $order['amount']?>"/></td>
				<td><span>买家姓名:</span><?= $order['user']['name']?></td>
				<td><span>买家联系方式:</span><?= $order['user']['tel']?></td>
			</tr>
			<tr>
				<td>
					<span>配送方式:</span>
					<select name="shipping_type" id="shipping_type">
						<?php foreach( $shipping_type_list as $k=>$v ) { ?>
							<option value="<?=$k?>" <?=( $k==$order['shipping_type'] ) ? 'selected="selected"' : ''; ?>><?=$v ?></option>
						<?php } ?>
					</select>
				<td><span>收货人: </span><input name="consignee" id="consignee" value="<?= $order['consignee']?>" /></td>
				<td><span>联系方式: </span><input name="tel" pattern="^13[0-9][0-9]{8}|15[89][0-9]{8}|18[56]\d{8}"  id="tel" value="<?= $order['tel']?>"></td>
			</tr>
			<tr>
				<td><span>配送地址:</span><input name="address" id="address" value="<?= $order['address']?>" /></td>
				<td><span>受权码: </span> <?= $order['pay_code']?></td>
				<td><span>提货码: </span> <?= $order['take_code']?></td>
			</tr>
			<tr>
				<td><span>提货/发货时间:</span> <?= $order['take_out_timestamp']?></td>
				<td><span>审核: </span> <?= $order['audit_timestamp']?></td>
				<td><span> </span> <?= $order['']?></td>
			</tr>
		</table>
		</form>
	</div>
	<div class="col-md-12">
		  <a href="javascript:;" style="display:none;" id="change-order-btn" class="btn btn-info text-left">修改基本信息</a>
	</div>
</div>

<div class="row">
	&nbsp;
</div>

<div class="table-responsive">
	<h2 class="page-header">订单商品</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>序号</th>
				<th>商品</th>
				<th>单价(元/吨)</th>
				<th>件重(吨)</th>
				<th>件数</th>
				<th>小计(元)</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody id="order-item-list">
				<?php $n = 1; ?>
				<tr>
					<td><?= $n ?></td>
					<td class="product_name"><?= $order['product_name'].'/'.$order['material_name'].'/'.$order['size_name'] ?></td>
					<td><input id="price-<?=$order['id']?>"  name="<?=$order['id'] ?>"  class="item-price" value="<?= $order['price'] ?>">元</td>
					<td><span id="ton-<?=$order['id']?>"><?= $order['ton'] ?></span>吨</td>
					<td><input id="quantity-<?=$order['id']?>" name="<?=$order['id'] ?>" class="item-num" value="<?=$order['quantity']?>"></td>
					<td><span class="subtotal" id="subtotal-<?=$order['id'] ?>"><?= $a = $order['price']*$order['quantity']*$order['ton']; ?></span>&nbsp;元</td>
					<td>
						<!--<a href="javascript:;" alt="<?=$order['item_id'] ?>"  class="delete-order-item">删除</a>-->
					</td>
				</tr>
		</tbody>
	</table>
	<h3 class="text-right">商品总金额: <span id="total"><?php echo $a ?></span>元</h3>
</div>

<div class="row">
	<div class="col-md-3">
	</div>
	<div class="col-md-3">
		  <!--<a href="javascript:;" id="add-order-item-btn" class="btn btn-info text-left">添加商品</a>-->
		  <a href="<?php echo Router::getBaseUrl() ?>order?<?= $queryString ?>" id="cancel-change-btn" class="btn btn-info text-left" style="display:none;">取消返回</a>
	</div>
	<div class="col-md-3 text-right-disable ">
		  <a href="javascript:;" id="confirm-change-btn" class="btn btn-warning" style="display:none;">保存修改</a>
	</div>
	<div class="col-md-3 text-right">
			&nbsp;
	</div>
</div>



<!-- 提示信息弹层 -->
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



<!-- 商品选择列表弹层 -->
<div class="modal fade" id="add-order-item-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="add-order-item-title" >选择商品</h4>
			</div>
			<div class="modal-body" id="selecte-item-box" >
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="confirm-add-btn">添加</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>


<script>

function deleteTr(a)
{
	var root = a.parentNode.parentNode.parentNode;
	root.removeChild(a.parentNode.parentNode);
	reCountTotal(); // 从新计算订单商品总金额
	$('#confirm-change-btn').show(); // 显示修改按钮
	$('#cancel-change-btn').show(); // 显示修改按钮
}

$('.delete-order-item').on( 'click', function() {

	var arr = {};
	var alt = $(this).attr( 'alt' );
	var old = $('#del-order-item-id').val();
	//console.log( old=='' );
	if ( old=='' ) {
		 $('#del-order-item-id').val(alt);
	}else{
		 $('#del-order-item-id').val(old+','+alt);
	}

	deleteTr( this );
	$('#confirm-change-btn').show(); // 显示修改按钮
	$('#cancel-change-btn').show(); // 显示修改按钮
	/*
	var root = this.parentNode.parentNode.parentNode;
	root.removeChild(this.parentNode.parentNode);
	reCountTotal(); // 从新计算订单商品总金额
	 */
} );

function removeRow(r, exOutProductId )
{
		if ( typeof( exOutProductId ) != 'undefined' && exOutProductId != '' )
		{
			/*
			Common.confirm('您确定要删除吗？', function() {
				var url = Common.getUrl("warehouseExoutwarehouse", "delExOutProduct");
				Common.postAjax(url, {"id" : exOutProductId}, function(){
					var root = r.parentNode;
					root.removeChild(r);
					Order.changeCountMount();
				}, true)
			});
			*/
		}
		else
		{
			// 删除节点
			var root = r.parentNode;
			root.removeChild(r);
			Order.changeCountMount();
		}
}


/**
 * @brief:  点击添加按钮
* @param:  
* @param:  
 */
$('#confirm-add-btn').on( 'click', function(){

	// 原来的菜单已经有几条了
	var list = $('#order-item-list tr');
	var n = list.size();

	var noTr = false;
	if ( n==0 ) {
		noTr = true;
	}

	$( '#checkList :checked' ).each(function () {
		var i = $(this);
		var flag = false;
		$('.newItemId').each( function() {
			if ( $(this).val()==i.val() ) {		// 判断重复  
				alert( '请勿重复选择['+i.val()+']' );
				flag = true;
			}
		} );
		n = n + 1;

		var tds = i.parent().nextAll();
		var itemName = $(tds[2]).html() + '/' + $(tds[3]).html() + '/' + $(tds[4]).html();
		$( 'td.product_name' ).each( function() {
			//console.log( $(this).html() );
			if ( $(this).html() == itemName ) {
				alert( '请勿重复选择['+itemName+']' );
				flag = true;
			}
		} );

		if ( flag ) {	// 跳过已选中
			return true;
		}

		var price = $(tds[7]).html();
		var numInput = '<td><input onchange="changeSubTotal()" id="item-'+n+'" name="'+i.val()+'" class="item-num" value="1"/></td>';
		var subtotal = price;
		var newTr = '<tr><input type="hidden" class="newItemId" value="'+i.val()+'">';
			newTr += '<td>'+ n +'</td>';
			newTr += '<td>'+ itemName +'</td>';
			newTr += '<td><span id="price-'+n+'">' + price + '</span>&nbsp;元</td>';
			newTr +=  numInput;
			newTr += '<td><span class="subtotal" id="subtotal-'+n+'">' + subtotal + '</span>元</td>';
			newTr += '<td><a href="javascript:;" onclick="deleteTr(this)" alt="'+i.val()+'" class="delete-order-item">删除</a></td>';
			newTr += '</tr>';

		if ( noTr ) {
			$('#order-item-list').append( newTr );
		}else{
			list.parent().append( newTr );
		}
    });

	changeSubTotal(); // 
	reCountTotal(); // 从新计算订单商品总金额

	// 开关弹层
	$('#add-order-item-modal').modal('toggle');

	$('#confirm-change-btn').show(); // 显示修改按钮
	$('#cancel-change-btn').show(); // 显示修改按钮

} );

/**
 * @brief:  获取当前页面订单商品表格项目
 * @return:  
 */
function getOrderItems()
{
	var dataArr = [];
	var n = 0;
	$('.item-num').each( function() {
		var data = {};
		a = $(this);
		data.id = parseInt(a.attr( 'name' ));
		data.num = parseInt(a.val());
		dataArr[n] = data;
		n++;
	} );
	return dataArr;
}


/**
 * @brief:  改动触发按钮显示
 */
$( '#amount, #consignee, #tel, #shipping_type, #address' ).on( 'change', function() {
	//$( '#change-order-btn' ).show();
	$('#confirm-change-btn').show(); // 显示修改按钮
	$('#cancel-change-btn').show(); // 显示修改按钮
} );

/**
 * @brief:  改动商品数量，从新计算价格 - 包成函数是为了使动态加入的节点，能正常调用
 * @param:  
 */
function changeSubTotal()
{
	$('.item-num').on( 'change', function() {
		var n = $(this).prop( 'name' );
		var price = $('#price-'+n).html();
		var num = $(this).val();

		var subtotal = price * num;
		$('#subtotal-'+n).html( subtotal );

		reCountTotal(); // 从新计算订单商品总金额

		$('#confirm-change-btn').show(); // 显示修改按钮
		$('#cancel-change-btn').show(); // 显示修改按钮

	} );
}

/**
 * @brief:  改动商品数量，从新计算价格
 */
$('.item-num').on( 'change', function() {
	var n = $(this).prop( 'name' );
	var price = $('#price-'+n).val();
	var num = $(this).val();
	var ton = $('#ton-'+n).html().trim();

	var subtotal = price * num * ton;
	$('#subtotal-'+n).html( subtotal );

	reCountTotal(); // 从新计算订单商品总金额
	$('#confirm-change-btn').show(); // 显示修改按钮
	$('#cancel-change-btn').show(); // 显示修改按钮
} );

/**
 * @brief: 改动商品价格，从新计算价格，和订单金额
 */
$('.item-price').on( 'change', function() {
	var n = $(this).prop( 'name' );
	var num = $('#quantity-'+n).val();
	var price = $(this).val();
	var ton = $('#ton-'+n).html().trim();

	var subtotal = price * num * ton;
	$('#subtotal-'+n).html( subtotal );

	reCountTotal(); // 从新计算订单商品总金额
	$('#confirm-change-btn').show(); // 显示修改按钮
	$('#cancel-change-btn').show(); // 显示修改按钮
} );

/**
 * @brief:  从新计算订单商品总金额
 * @return:  
 */
function reCountTotal(  )
{
	var num = 0;
	$('.subtotal').each( function() {
		var a = $( this ).html();
		num = num + parseFloat(a);
	} );
	$('#total').html(num);
	$('#amount').val( num );
}

//  点击修改基本信息按钮
$( '#confirm-change-btn' ).on( 'click', function() {
	var data = {};
	var flag = false;
	data.id = $('#id').val();
	data.amount = $('#amount').val().trim();
	var amountRe= /^(\d+)$|^(\d+)\.\d+$/;
	if ( ! amountRe.test( data.amount ) ) {
		$('#msg-box-content').html('请填写正确的[订单金额]格式: 100 | 100.00');
		$('#msg-box').modal('show');
				setTimeout(function(){ // 1秒后自动关闭弹层 
					$('#msg-box').modal('hide');
					$('#amount').val('').focus();
				},1000);
		return false;
	}
	//data.tel = parseInt($('#tel').val().trim());
	data.tel = $('#tel').val().trim();
	//var telRe= /^(13\d{9})$|^(15[89]\d{8})$|^18[56]\d{8}$/;
	var telRe= /^1\d{10}$/;
	if ( ! telRe.test( data.tel ) ) {
		$('#msg-box-content').html('请填写正确的[联系方式]手机号码');
		$('#msg-box').modal('show');
				setTimeout(function(){ // 1秒后自动关闭弹层 
					$('#msg-box').modal('hide');
					$('#tel').val('').focus();
				},1000);
		return false;
	}
	data.tel = parseInt( data.tel );
	data.consignee = $('#consignee').val().trim();
	data.shipping_type = $('#shipping_type').val();
	data.address = $('#address').val().trim();

	// 订单商品数量
	data.quantity = $('#quantity-'+data.id).val();
	var quantityRe= /^(\d+)$/;
	if ( ! quantityRe.test( data.quantity ) ) {
		$('#msg-box-content').html('请正确的[商品数量]：1 | 10');
		$('#msg-box').modal('show');
				setTimeout(function(){ // 1秒后自动关闭弹层 
					$('#msg-box').modal('hide');
					$('#quantity-'+data.id).val('').focus();
				},1000);
		return false;
	}
	data.price = $('#price-'+data.id).val();
	var priceRe= /^(\d+)$|^(\d+)\.\d+$/;
	if ( ! priceRe.test( data.price ) ) {
		$('#msg-box-content').html('请填写正确的[商品单价]格式: 100 | 100.00');
		$('#msg-box').modal('show');
				setTimeout(function(){ // 1秒后自动关闭弹层 
					$('#msg-box').modal('hide');
					$('#price-'+data.id).val('').focus();
				},1000);
		return false;
	}

	// 现在表单是什么样的数据
	// data.items = getOrderItems();  -- 一件商品一个订单，不需要了，^_^

	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'order/doEdit', data,
			function(data) {
				if(data.code != 0) {
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
				}else {
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					$('#confirm-change-btn').hide(); // 显示修改按钮
					$('#cancel-change-btn').hide(); // 显示修改按钮
				}

				setTimeout(function(){ // 1秒后自动关闭弹层 
					$('#msg-box').modal('hide');
					//window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
				},1000);

			},
			'json'
		);

} );

// 点击添加商品按钮
$( '#add-order-item-btn' ).on( 'click', function(){
	return false;
	
	$.get( BASE_PATH+'order/selectItemList', {}, function(html){
		$('#add-order-item-modal').modal('show');
		$('#selecte-item-box').html( html );
	} );

} );

</script>
