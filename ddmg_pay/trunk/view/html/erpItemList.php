<?php 
/**
 * 
 */
$session = Controller::instance('session');
$loginUser = $session->get( 'loginUser' );
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">ERP产品列表</h2>
	</div>
	<div class="col-md-6 text-right">
		<?php if ( $loginUser['seller_type'] == 2  ) { ?>
		  <a href="<?php echo Router::getBaseUrl() ?>erp" class="btn btn-success text-right">ERP商品管理</a>
		<?php } ?>
		  <a href="<?php echo Router::getBaseUrl() ?>itemsImport.csv" class="btn btn-success text-right">下载导入模版</a>
		  <a href="javascript:;" class="btn btn-success text-right" id="import-item-btn">批量导入商品</a>
		  <a href="<?php echo Router::getBaseUrl() ?>item/add?<?= $queryString ?>" class="btn btn-success text-right">添加单条商品</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0" id="cascading">
		<form class="form-inline" action="<?php echo Router::getBaseUrl() ?>erp" method="get">
		<table class="table">
            <tr>
                <td align="right" style="vertical-align:middle">仓库</td>
                <td align="left">
                    <select name="warehouse_id" id="warehouse_id" class="form-control" style="width:170px">
        				<option value="">全部</option>
        				<?php if(!$warehouseList){}else foreach( $warehouseList as $warehouse ) { ?>
        					<option value="<?= $warehouse['PK'] ?>" <?= ($params['warehouse_id']==$warehouse['PK']) ? 'selected="selected"' : ''; ?>><?= $warehouse['name'] ?></option>
        				<?php } ?>
        			</select>
                </td>
                <td align="right" style="vertical-align:middle">品名</td>
                <td align="left">
                    <select name="product_id" id="product_id" class="form-control step22" style="width:170px">
    				    <option value="">全部</option>
        				<?php if(!$productList){}else foreach( $productList as $product ) { ?>
        					<option value="<?= $product['PK'] ?>" <?= ($params['product_id']==$product['PK']) ? 'selected="selected"' : ''; ?>><?= $product['name'] ?></option>
        				<?php } ?>
			         </select>
                </td>
                <td colspan="2"><button type="submit" class="btn btn-info">搜索</button></td>
                <td align="right" style="vertical-align:middle">&nbsp;</td>
                <td align="right" style="vertical-align:middle">&nbsp;</td>
                <td align="right" style="vertical-align:middle">&nbsp;</td>
                <td align="right" style="vertical-align:middle">&nbsp;</td>
            </tr>

<!--
            <tr>
                <td align="right" style="vertical-align:middle">品名</td>
                <td align="left">
                    <select name="product_id" id="product_id" class="form-control step22" style="width:170px">
    				    <option value="">全部</option>
        				<?php if(!$productList){}else foreach( $productList as $product ) { ?>
        					<option value="<?= $product['PK'] ?>" <?= ($params['product_id']==$product['PK']) ? 'selected="selected"' : ''; ?>><?= $product['name'] ?></option>
        				<?php } ?>
			         </select>
                </td>
                <td align="right" style="vertical-align:middle">材质</td>
                <td align="left">
                    <select name="material_id" id="material_id" class="form-control step3" style="width:170px">
        				<option value="">全部</option>
        			</select>
                </td>
                <td align="right" style="vertical-align:middle">规格</td>
                <td align="left">
                    <select name="size_id" id="size_id" class="form-control step4" style="width:170px">
        				<option value="">全部</option>
        			</select>
                </td>
                <td colspan="2"><button type="submit" class="btn btn-info">搜索</button></td>
                <td align="right" style="vertical-align:middle">&nbsp;</td>
                <td align="right" style="vertical-align:middle">&nbsp;</td>
            </tr>
-->

<!--
            <tr>
                <td align="right" style="vertical-align:middle">厂家</td>
                <td align="left">
                    <select name="factory_id" id="factory_id" class="form-control" style="width:170px">
        				<option value="">全部</option>
        				<?php if(!$factory_list){}else foreach( $factory_list as $factory ) { ?>
        					<option value="<?php echo $factory['id'] ?>" <?php echo ($params['factory_id']==$factory['id']) ? 'selected="selected"' : ''; ?>><?php echo $factory['name'] ?></option>
        				<?php } ?>
        			</select>
                </td>
                <td align="right" style="vertical-align:middle">城市</td>
                <td align="left">
                    <select name="city_id" id="city_id" class="form-control" style="width:170px">
        				<option value="">全部</option>
        				<?php if(!$city_list){}else foreach( $city_list as $city ) { ?>
        					<option value="<?php echo $city['id'] ?>" <?php echo ($params['city_id']==$city['id']) ? 'selected="selected"' : ''; ?> ><?php echo $city['name'] ?></option>
        				<?php } ?>
        			</select>
                </td>
                <td align="right" style="vertical-align:middle">销售状态</td>
                <td align="left">
                    <select name="is_on_sale" id="is_on_sale" class="form-control" style="width:170px">
    					<option value="">全部</option>
    					<option value="1" <?php echo ($params['is_on_sale']==1) ? 'selected="selected"' : ''; ?> >上架</option>
    					<option value="0"<?php echo (isset( $params['is_on_sale'] )&&$params['is_on_sale']===0) ? 'selected="selected"' : ''; ?>>下架</option>
        			</select>
                </td>
                <td colspan="2"><button type="submit" class="btn btn-info">搜索</button></td>
            </tr>
-->

        </table>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<?php if ( $params['is_on_sale']==1 ) { ?>
			<button type="button" id="batch-unsale-btn" class="btn btn-info">批量下架</button>
		<?php } else if ( $params['is_on_sale']===0 ) { ?>
			<button type="button" id="batch-onsale-btn"  class="btn btn-info">批量上架</button>
		<?php } ?>
	</div>
</div>

<!--<h2 class="sub-header">Section title</h2>-->
<div class="table-responsive">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th><input type="checkbox" id="checkAll" /></th>
				<th>序号</th>
				<th>品名</th>
				<th>材质</th>
				<th>规格</th>
				<th>厂家</th>
				<th>城市</th>
				<th>仓库</th>
				<th>价格(元/吨)</th>
				<th>计价类型</th>
				<th>件重(吨/件)</th>
				<th>计重类型</th>
				<th>可用数量(库存件数)</th>
				<th>可用重量</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody id="checkList">
			<?php if ( $items ) { ?>
			<?php foreach ( $items as $key=>$item ) { $key=$key+1+($page-1)*$numPerPage; ?>
				<tr>
					<td><input type="checkbox" class="item-checkboxx"  value="<?= $key ?>"  /></td>
					<td><?= $key ?><input type="hidden" id="productPK-<?= $key ?>" class="" value="<?= $item['productPK'] ?>">
						<input type="hidden" id="warehousePK-<?= $key ?>" class="" value="<?= $item['warehousePK'] ?>">
					</td>
					<td id="product-name-<?= $key ?>"><?= $item['name'] ?></td>
					<td id="material-name-<?= $key ?>"><?= $item['material'] ?></td>
					<td id="size-name-<?= $key ?>"><?= $item['spec'] ?></td>
					<td id="factory-name-<?= $key ?>"><?= $item['brand'] ?></td>
					<td id="city-name-<?= $key ?>"><?= $item['address'] ?></td>
					<td id="delivery-point-<?= $key ?>"><?= $item['warehouse_name'] ?></td>
					<td><input type="" id="price-<?= $key ?>" name="price" value="" placeholder="请输入价格"></td>
					<td>
						<select id="price-type-<?= $key ?>" name="">
							<option value="1" selected="selected">自提价</option>
							<option value="2">包到价</option>
						</select>
					</td>
					<td><input type="" id="ton-<?= $key ?>" name="ton" value="<?= $item['singleWeight'] ?>"></td>
					<!--<td id="delivery-point-<?= $key ?>"><?= $item['weightType'] ?></td>-->
					<td>
						<select id="count-type-<?= $key ?>" name="">
							<?php if ( $item['weightType'] == '理计' ) { ?>
								<option value="1" selected="selected">理计</option>
								<option value="2" >磅计</option>
							<?php } else { ?>
								<option value="1">理计</option>
								<option value="2" selected="selected">磅计</option>
							<?php } ?>
						</select>
					</td>
					<td ><input type="" id="inventory-<?= $key ?>" value="<?= $item['qty'] ?>" /></td>
					<td><?php echo $item['weight'] ?></td>
					<td><button alt="<?= $key ?>" class="btn btn-info on-sale-btn">上架</button></td>
				</tr>
			<?php } ?>
			<?php } else { ?>
				<tr>
					<td colspan="13"></td>
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
	<div class="col-md-1 col-md-offset-11">
		<select name="count">
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="20">20</option>
		</select>
	</div>
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
      </div>
<!--
      <div class="modal-footer">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="import-box">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
<!--
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
-->
      <div id="import-box-content" class="modal-body text-center">
			<form action="<?php echo Router::getBaseUrl() ?>item/import" method="post" enctype="multipart/form-data">
				<label for="importItemsCSV">请选择批量导入模版:</label>
				<input type="file" name="importItemsCSV" value="" />
				<br/>
				<input type="submit" name="" value="导入" />
			</form>
      </div>
<!--
      <div class="modal-footer">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/jquery.cascadingdropdown.min.js"></script>
<script>
$( function(){


$('#import-item-btn').click( function() {
	$( '#import-box' ).modal( 'show' );
} );

// 点击批量上架
$( '#batch-onsale-btn' ).click( function() {
	var idArr = getCheckedItemId();
	//console.log( idArr );
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'item/batchOnsale', {'idArr':idArr},
			function(data) {
				if(data.code != 0) 
				{ // 失败
					//alert( 'fail' );
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
					},1000);
				}
				else
				{ // 成功
					//alert( 'success' );
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
						window.location = BASE_PATH+'item?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);
	//$(this).hide();
	//$('#batch-unsale-btn').hide();
} );

// 点击批量下架
$( '#batch-unsale-btn' ).click( function() {
	var idArr = getCheckedItemId();
	//console.log( idArr );
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'item/batchUnsale', {'idArr':idArr},
			function(data) {
				if(data.code != 0) 
				{ // 失败
					//alert( 'fail' );
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
					},1000);
				}
				else
				{ // 成功
					//alert( 'success' );
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
						window.location = BASE_PATH+'item?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);
	//$(this).hide();
	//$('#batch-onsale-btn').hide();
} );

// 获取当前页面被选中的商品ID
function getCheckedItemId(  )
{
	var idArr = [];
	$( '#checkList :checked' ).each(function () {
		var id = $( this ).val();
		if ( id == '' ) {
			return false;
		}
		idArr.push(id.trim());
    });
	return idArr;
}

// 点击上架|下架
$( '.toggle' ).on( 'click', function(){
	var a = $( this );
	var id = a.attr( 'alt' );
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'item/toggle', {'id':id},
			function(data) {
				if(data.code != 0) 
				{ // 失败
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
				}
				else
				{ // 成功
					$('#msg-box-content').html(  data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					if ( data.data.status==1 ) {// 上架
						a.html('下架');
						$( '#on_sale_status_'+id ).html('上架');
					} else {// 下架
						a.html('上架');
						$( '#on_sale_status_'+id ).html('下架');
					}
				}
			},
			'json'
		);

		setTimeout(function(){  // 1秒后自动关闭弹层
			$('#msg-box').modal('hide');
		},1000);
} );

// 全选
$('#checkAll').on( 'click', function() {
	var togger = $(this).is( ':checked' );
	$( '#checkList :checkbox' ).each(function (){
		$(this).prop( 'checked',  togger );
    });
} );

// ==================新的===================================================================================

// 上架操作
$('.on-sale-btn').click( function() {
	var key = $(this).attr( 'alt' );

	var product_name = $('#product-name-'+key).html().trim();
	if ( product_name=='' ) {
		alert( 'no have product_name' );
		return false;
	}
	var material_name = $('#material-name-'+key).html().trim();
	if ( material_name=='' ) {
		alert( 'no have material_name' );
		return false;
	}
	var size_name = $('#size-name-'+key).html().trim();
	if ( size_name=='' ) {
		alert( 'no have size_name' );
		return false;
	}
	var factory_name = $('#factory-name-'+key).html().trim();
	if ( factory_name=='' ) {
		alert( 'no have factory_name' );
		return false;
	}
	var city_name = $('#city-name-'+key).html().trim();
	/*
	if ( city_name=='' ) {
		alert( 'no have city_name' );
		return false;
	}
	*/
	var delivery_point = $('#delivery-point-'+key).html().trim();
	if ( delivery_point=='' ) {
		alert( 'no have delivery_point' );
		return false;
	}
	var price = $('#price-'+key).val().trim();
	if ( price=='' ) {
		alert( 'no have price' );
		return false;
	}
	var price_type = $('#price-type-'+key).val();
	if ( price_type=='' ) {
		alert( 'no have price_type' );
		return false;
	}
	var ton = $('#ton-'+key).val().trim();
	if ( ton=='' ) {
		alert( 'no have ton' );
		return false;
	}
	var count_type = $('#count-type-'+key).val();
	if ( count_type=='' ) {
		alert( 'no have count_type' );
		return false;
	}
	var inventory = $('#inventory-'+key).val();
	if ( inventory=='' ) {
		alert( 'no have inventory' );
		return false;
	}
	var productPK = $('#productPK-'+key).val();
	if ( productPK=='' ) {
		alert( 'no have productPK' );
		return false;
	}
	var warehousePK = $('#warehousePK-'+key).val();
	if ( warehousePK=='' ) {
		alert( 'no have warehousePK' );
		return false;
	}


	var data = {};
	data.product_name = product_name;
	data.material_name = material_name;
	data.size_name = size_name;
	data.factory_name = factory_name;
	data.city_name = city_name;
	data.delivery_point = delivery_point;
	data.price = price;
	data.price_type = price_type;
	data.ton = ton;
	data.count_type = count_type;
	data.inventory = inventory;
	data.productPK = productPK;
	data.warehousePK = warehousePK;

	$.post(BASE_PATH + 'erp/onsale', data,
			function(data) {
				if(data.code != 0) 
				{ // 失败
					//alert( 'fail' );
					$('#msg-box').modal('show');
					$('#msg-box-content').html('添加失败');

					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
					},1000);
				}
				else
				{ // 成功
					$('#msg-box').modal('show');
					$('#msg-box-content').html('添加成功');
					setTimeout(function(){ // 1秒后自动关闭弹层 
						$('#msg-box').modal('hide');
						window.location = BASE_PATH+'item?'+'<?= $queryString ?>';
					},1000);
				}
			},
			'json'
		);


} );


} );

</script>
