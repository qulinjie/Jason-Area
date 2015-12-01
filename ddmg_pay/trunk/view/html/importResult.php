<?php 
/**
 * 
 */
?>


<!--<h2 class="sub-header">Section title</h2>-->
<div class="table-responsive">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th><input type="checkbox" id="checkAll" /></th>
				<th>ID</th>
				<th>分类</th>
				<th>品名</th>
				<th>材质</th>
				<th>规格</th>
				<th>厂家</th>
				<th>城市</th>
				<th>价格(元)</th>
				<th>计价类型</th>
				<th>件重(吨)</th>
				<th>计重类型</th>
				<th>库存</th>
				<th>交割仓库</th>
				<th>错误信息</th>
			</tr>
		</thead>
		<tbody id="checkList">
			<?php if ( $importData ) { ?>
			<?php foreach ( $importData as $key=>$item ) { ?>
				<tr>
					<td>
					<?php if ( !$item['err'] ) { ?>
						<input type="checkbox" class="item-checkbox"  value="<?= $key ?>"  />
					<?php } ?>
</td>
					<td><?= $key+1+($page-1)*$numPerPage; ?></td>
					<td><?=$item['category_name'] ?><input type="hidden" name id="category-id-<?= $key ?>" value="<?= $item['category_id'] ?>"></td>
					<td><?=$item['product_name'] ?><input type="hidden" name id="product-id-<?= $key ?>" value="<?= $item['product_id'] ?>"></td>
					<td><?=$item['material_name'] ?><input type="hidden" name id="material-id-<?= $key ?>" value="<?= $item['material_id'] ?>"></td>
					<td><?=$item['size_name'] ?><input type="hidden" name id="size-id-<?= $key ?>" value="<?= $item['size_id'] ?>"></td>
					<td><?=$item['factory_name'] ?><input type="hidden" name id="factory-id-<?= $key ?>" value="<?= $item['factory_id'] ?>"></td>
					<td><?=$item['city_name'] ?><input type="hidden" name id="city-id-<?= $key ?>" value="<?= $item['city_id'] ?>"></td>
					<td><?=$item['price'] ?><input type="hidden" name id="price-<?= $key ?>" value="<?= $item['price'] ?>"></td>
					<td><?=$item['price_type_name'] ?><input type="hidden" name id="price-type-<?= $key ?>" value="<?= $item['price_type'] ?>"></td>
					<td><?=$item['ton'] ?><input type="hidden" name id="ton-<?= $key ?>" value="<?= $item['ton'] ?>"></td>
					<td><?=$item['count_type_name'] ?><input type="hidden" name id="count-type-<?= $key ?>" value="<?= $item['count_type'] ?>"></td>
					<td><?=$item['inventory'] ?><input type="hidden" name id="inventory-<?= $key ?>" value="<?= $item['inventory'] ?>"></td>
					<td><?=$item['delivery_point'] ?><input type="hidden" name id="delivery-point-<?= $key ?>" value="<?= $item['delivery_point'] ?>"></td>
					<td>
						<?php if ( $item['err'] ) { ?>
							<?php foreach ( $item['err'] as $val ) {
									echo $val.'<br/>';
							} ?>
						<?php } ?>
					</td>
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
	<div class="col-md-3">
	</div>
	<div class="col-md-3">
		  <a href="javascript:;" id="import-btn" class="btn btn-info text-left">导入</a>
	</div>
	<div class="col-md-3 text-right-disable ">
		  <a href="<?= Router::getBaseUrl()  ?>item" id="" class="btn btn-warning">返回</a>
	</div>
	<div class="col-md-3 text-right">
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

$('#checkAll').on( 'click', function() {
	var togger = $(this).is( ':checked' );
	$( '#checkList :checkbox' ).each(function (){
		$(this).prop( 'checked',  togger );
    });
} );

/*$( '.item-checkbox' ).click( function() {
	var togger = $(this).is( ':checked' );
	if ( !togger ) {
		$(this).prop( 'checked', togger );
	}else{
		$( '#checkList :checkbox' ).each(function (){
			$(this).prop( 'checked',  !togger );
		});
		$(this).prop( 'checked', true );
	}
} );*/

$('#import-btn').click( function() {
	var data = [];
	// 获取勾选的数据
	$( '#checkList :checked ' ).each(function (){
		if (!$(this).is( ':checked' )) {
			return false;
		}
		var item = {};
		var k = $(this).val();
		item.category_id = $( '#category-id-'+k ).val();
		item.product_id = $( '#product-id-'+k ).val();
		item.material_id = $( '#material-id-'+k ).val();
		item.size_id = $( '#size-id-'+k ).val();
		item.factory_id = $( '#factory-id-'+k ).val();
		item.city_id = $( '#city-id-'+k ).val();
		item.price = $( '#price-'+k ).val();
		item.price_type = $( '#price-type-'+k ).val();
		item.ton = $( '#ton-'+k ).val();
		item.count_type = $( '#count-type-'+k ).val();
		item.inventory = $( '#inventory-'+k ).val();
		item.delivery_point = $( '#delivery-point-'+k ).val();
		data.push( item );
	});
	console.log(data);
	
	$('#msg-box').modal('show');

	// POST到添加接口添加
	$.post(BASE_PATH + 'item/doImport', {'items':data},
			function(result){
				if(result.code != 0) {
					$('#msg-box-content').html( '添加失败&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'  );
					setTimeout(function(){  // 1秒后自动关闭弹层
						$('#msg-box').modal('hide');
					},1000);
				}else {
					$('#msg-box-content').html( '添加成功&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'  );
					setTimeout(function(){  // 1秒后自动关闭弹层
						$('#msg-box').modal('hide');
						window.location = BASE_PATH+'item';
					},1000);
				}
			},
			'json'
		);

} );


</script>
