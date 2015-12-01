<?php 
/**
 * 卖家修改商品页面
 */
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">添加订单</h2>
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
				<!-- ajax 校验&自动填写 -->
				<td><span>买家帐号:</span><input class="" name="buyer_account" id="buyer_account" value=""/><span id="checkBuyerMsg"></span></td>
				<td>
					<span>配送方式:</span>
					<select name="shipping_type" id="shipping_type">
						<?php foreach( $shipping_type_list as $k=>$v ) { ?>
							<option value="<?=$k?>" <?=( $k==$order['shipping_type'] ) ? 'selected="selected"' : ''; ?>><?=$v ?></option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td><span>收货人:</span><input disabled="disabled" class="" name="consignee" id="consignee" value=""/></td>
				<td><span>收货电话:</span><input disabled="disabled" class="" name="tel" id="tel" value=""/></td>
				<td><span>收货地址:</span><input disabled="disabled" class="" name="address" id="address" value=""/></td>
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
				<th>件重(吨/件)</th>
				<th>数量(件)</th>
				<th>实收(元)</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody id="order-item-list">
		</tbody>
	</table>
	<h3 class="text-right">商品总金额: <span id="total"><?php echo $amount ?></span>元</h3>
</div>

<div class="row">
	<div class="col-md-3">
	</div>
	<div class="col-md-3">
		  <a href="javascript:;" id="add-order-item-btn" class="btn btn-info text-left">添加商品</a>
	</div>
	<div class="col-md-3 text-right-disable ">
		  <a href="javascript:;" id="confirm-order-btn" class="btn btn-warning" style="display:none;">确认订单</a>
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
	$('#confirm-order-btn').show(); // 显示修改按钮
	$( '#add-order-item-btn' ).show();
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
	$('#confirm-order-btn').show(); // 显示修改按钮
	/* 17:18
	var root = this.parentNode.parentNode.parentNode;
	root.removeChild(this.parentNode.parentNode);
	reCountTotal(); // 从新计算订单商品总金额
	 */
} );


/**
 * @brief:  删除tr行
 * @return:  
 */
function removeRow(r)
{
	var root = r.parentNode;
	root.removeChild(r);
	Order.changeCountMount();
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

	/*
	if ( n>0 ) { // 一定订单只能选择一个商品
		return false;
	}
	*/

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

		/*
		if ( n > 1 ) {
			return false;
		}
		 */

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
		var ton = $(tds[8]).html();
		var numInput = '<td><input onchange="changeSubTotal()" id="item-'+i.val()+'" name="'+i.val()+'" class="item-num" value="1"/></td>';
		var subtotal = price;
		var newTr = '<tr><input type="hidden" class="newItemId" value="'+i.val()+'">';
			newTr += '<td>'+ n +'</td>';
			newTr += '<td>'+ itemName +'</td>';
			newTr += '<td><input onchange="changeSubTotal()" id="price-'+i.val()+'"  name="'+i.val()+'" class="item-price" value="' + price + '"/>元</td>';
			newTr += '<td>'+ ton +'</td>';
			newTr +=  numInput;
			newTr += '<td><input class="subtotal" id="subtotal-'+i.val()+'" value="' + subtotal + '" />元</td>';
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

	$('#confirm-order-btn').show(); // 显示修改按钮

	//$('#add-order-item-btn').hide(); // 添加成功就不再显示添加按钮


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
		data.price = $('#price-'+data.id).val();
		data.amount = $('#subtotal-'+data.id).val();
		dataArr[n] = data;
		n++;
	} );
	return dataArr;
}


/**
 * @brief:  改动触发按钮显示
 */
$( '#shipping_type' ).on( 'change', function() {
	console.log( $(this).val() );
	if ( $(this).val() == 2 ) {
		$('#address').prop('disabled', false);
	}else{
		$('#address').val('');
		$('#address').prop('disabled', true);
	}
	//$( '#change-order-btn' ).show();
	$('#confirm-order-btn').show(); // 显示修改按钮
} );

/**
 * @brief:  改动商品数量，从新计算价格 - 包成函数是为了使动态加入的节点，能正常调用
 * @param:  
 */
function changeSubTotal()
{
	$('.item-num').on( 'change', function() {
		var n = $(this).prop( 'name' );
		var price = $('#price-'+n).val();
		var num = $(this).val();
		var subtotal = price * num;
		$('#subtotal-'+n).val( subtotal );
		reCountTotal(); // 从新计算订单商品总金额
		$('#confirm-order-btn').show(); // 显示修改按钮
	} );
	$('.item-price').on( 'change', function() {
		var n = $(this).prop( 'name' );
		var num = $('#item-'+n).val();
		var price = $(this).val();
		var subtotal = price * num;
		$('#subtotal-'+n).val( subtotal );
		reCountTotal(); // 从新计算订单商品总金额
		$('#confirm-order-btn').show(); // 显示修改按钮
	} );
}

/**
 * @brief:  改动商品数量，从新计算价格
 */
$('.item-num').on( 'change', function() {
	var n = $(this).prop( 'name' );
	var price = $('#price-'+n).html();
	var num = $(this).val();

	var subtotal = price * num;
	$('#subtotal-'+n).html( subtotal );

	reCountTotal(); // 从新计算订单商品总金额

	$('#confirm-order-btn').show(); // 显示修改按钮
} );

/**
 * @brief:  从新计算订单商品总金额
 * @return:  
 */
function reCountTotal(  )
{
	var num = 0;
	$('.subtotal').each( function() {
		var a = $( this ).val();
		num = num + parseFloat(a);
	} );
	$('#total').html(num);
}

//  确认表单按钮
$( '#confirm-order-btn' ).on( 'click', function() {

	var data = {};

	data.buyer_account = $('#buyer_account').val();
	data.tel = $('#tel').val();
	data.consignee = $('#consignee').val();
	data.shipping_type = $('#shipping_type').val();
	data.address = $('#address').val();


	// 现在表单是什么样的数据
	data.items = getOrderItems();
	//console.log( data );
	//return false;
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'order/doAdd', data,
			function( data ) {
				//console.log( data );
				//return false;
				if(data.code != 0) {
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
					},1000);

				}else {
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					$('#confirm-order-btn').hide(); // 显示修改按钮
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
						window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);
} );

// 点击添加商品按钮
$( '#add-order-item-btn' ).on( 'click', function() {
	
	$.get( BASE_PATH+'order/selectItemList', {}, function(html){
		$('#add-order-item-modal').modal('show');
		$('#selecte-item-box').html( html );
	} );

} );


// AJAX验证，合伙人帮下单，填写的买家帐号，是否正确
$( '#buyer_account' ).on( 'blur', function() {
	var buyer_account = $( this ).val();
	if ( buyer_account=='' ) {
		return false;
	}
	
	var data = {};
	data.buyer_account = buyer_account;
	$.post(BASE_PATH + 'order/getBuyer', data,
			function( data ){
				console.log( data );
				if ( data.code == 0 ) {
					$('#consignee').val( data.data.name );
					$('#tel').val( data.data.tel );

					$('#consignee').prop('disabled', false);
					$('#tel').prop('disabled', false);

					$('#confirm-order-btn').show(); // 显示修改按钮
					
				}else{
				}
				$( '#checkBuyerMsg' ).html( data.msg );
				/*
				setTimeout(function(){ // 1秒后自动关闭弹层 
					$('#msg-box').modal('hide');
					//window.location = BASE_PATH+'order?'+'<?= $queryString ?>';
				},1000);
				 */

			},
			'json'
		);

} );
</script>

