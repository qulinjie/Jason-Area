<?php 
/**
 * 
 */
?>

<div class="row">
	<div class="col-md-12 col-md-offset-0" id="cascading">
		<form class="form-inline" action="" method="">
		  <div class="form-group">
			<label for="category_id">分类</label>
			<select name="category_id" id="category_id" class="form-control step1">
				<option value="">全部</option>
			</select>
		  </div>
		  <div class="form-group">
			<label for="product_id">品名</label>
			<select name="product_id" id="product_id" class="form-control step2">
				<option value="">全部</option>
			</select>
		  </div>
		  <div class="form-group">
			<label for="material_id">材质</label>
			<select name="material_id" id="material_id" class="form-control step3">
				<option value="">全部</option>
			</select>
		  </div>
		  <div class="form-group">
			<label for="size_id">规格</label>
			<select name="size_id" id="size_id" class="form-control step4">
				<option value="">全部</option>
			</select>
		  </div>

		  <div class="form-group">
			<label for="factory_id">厂家</label>
			<select name="factory_id" id="factory_id" class="form-control">
				<option value="">全部</option>
				<?php foreach( $factory_list as $factory ) { ?>
					<option value="<?php echo $factory['id'] ?>" <?php echo ($params['factory_id']==$factory['id']) ? 'selected="selected"' : ''; ?>><?php echo $factory['name'] ?></option>
				<?php } ?>
			</select>
		  </div>
		  <div class="form-group">
			<label for="city_id">城市</label>
			<select name="city_id" id="city_id" class="form-control">
				<option value="">全部</option>
				<?php foreach( $city_list as $city ) { ?>
					<option value="<?php echo $city['id'] ?>" <?php echo ($params['city_id']==$city['id']) ? 'selected="selected"' : ''; ?> ><?php echo $city['name'] ?></option>
				<?php } ?>
			</select>
		  </div>

		  <button type="button" class="btn btn-info" id="search-btn">搜索</button>
		</form>
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
				<th><input type="checkbox" id="checkAll" /></th>
				<th>ID</th>
				<th>分类</th>
				<th>品名</th>
				<th>材质</th>
				<th>规格</th>
				<th>厂家</th>
				<th>城市</th>
				<th>价格<br/>(元/吨)</th>
				<th>件重<br/>(吨/件)</th>
				<th>交货地</th>
			</tr>
		</thead>
		<tbody id="checkList">
			<?php if ( $items ) { ?>
			<?php foreach ( $items as $key=>$item ) { ?>
				<tr>
					<td><input type="checkbox" class="item-checkboxx"  value="<?= $item['id'] ?>"  /></td>
					<td><?php echo $key+1+($page-1)*$numPerPage; ?></td>
					<td><?php echo $item['category_name'] ?></td>
					<td><?php echo $item['product_name'] ?></td>
					<td><?php echo $item['material_name'] ?></td>
					<td><?php echo $item['size_name'] ?></td>
					<td><?php echo $item['factory_name'] ?></td>
					<td><?php echo $item['city_name'] ?></td>
					<td><?php echo $item['price'] ?></td>
					<td><?php echo $item['ton'] ?></td>
					<td><?php echo $item['delivery_point'] ?></td>
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
</div>

<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/jquery.cascadingdropdown.min.js"></script>
<script>

// 全选
$('#checkAll').on( 'click', function() {
	var togger = $(this).is( ':checked' );
	$( '#checkList :checkbox' ).each(function (){
		$(this).prop( 'checked',  togger );
    });
} );

// 单选 --- 暂时废除
$( '.item-checkbox' ).click( function() {
	var togger = $(this).is( ':checked' );
	if ( !togger ) {
		$(this).prop( 'checked', togger );
	}else{
		$( '#checkList :checkbox' ).each(function (){
			$(this).prop( 'checked',  !togger );
		});
		$(this).prop( 'checked', true );
	}
} );



// 点击搜索按钮
$( '#search-btn' ).on( 'click', function() {

	var data = {};
	data.category_id = $('#category_id').val();
	data.product_id = $('#product_id').val();
	data.material_id = $('#material_id').val();
	data.size_id = $('#size_id').val();
	data.factory_id = $('#factory_id').val();
	data.city_id = $('#city_id').val();

	$.get( BASE_PATH+'order/selectItemList', data, function(html){
		$('#add-order-item-modal').modal('show');
		$('#selecte-item-box').html( html );
	} );
} );


// 级联下拉框JS
$( function(){
	$('#cascading').cascadingDropdown({
		selectBoxes: [
			{
				selector: '.step1',
				selected: '<?php echo $params['category_id'] ?>',  // 默认
				source: function(request, response) {
					$.getJSON('<?php echo Router::getBaseUrl() ?>item/category', {}, function(data) {
						if ( data==[] ) return false;
						var selectOnlyOption = data.length <= 1;
						response($.map(data, function(item, index) {
							return {
								label: item.name, 
								value: item.id,
								//selected: selectOnlyOption // Select if only option
								selected: item.id == <?php echo $params['category_id'] ? $params['category_id']: 0; ?>
							};
						}));
					});
				}, 
			},
			{
				selector: '.step2',
				selected: '<?php echo $params['product_id'] ?>',  // 默认
				requires: ['.step1'],
				requireAll: true,
				source: function(request, response) {
					var category_id = $('#category_id').val();
					if ( category_id=='' ) return false;
					$.getJSON('<?php echo Router::getBaseUrl() ?>item/product', {'category_id':category_id}, function(data) {
						if ( data==[] ) return false;
						var selectOnlyOption = data.length <= 1;
						response($.map(data, function(item, index) {
							return {
								label: item.name, 
								value: item.id,
								//selected: selectOnlyOption // Select if only option
								selected: item.id == <?php echo $params['product_id'] ? $params['product_id']: 0;?>
							};
						}));
					});
				}, 
				onChange: function(event, value, requiredValues){
				}
			},
			{
				selector: '.step3',
				selected: '<?php echo $params['material_id'] ?>',  // 默认
				requires: ['.step1', '.step2'],
				requireAll: true,
				source: function(request, response) {
					var product_id = $('#product_id').val();
					if ( product_id=='' ) return false;
					$.getJSON('<?php echo Router::getBaseUrl() ?>item/material', {'product_id':product_id}, function(data) {
						if ( data==[] ) return false;
						response($.map(data, function(item, index) {
							return {
								label: item.name,
								value: item.id,
								selected: item.id == <?php echo $params['material_id'] ? $params['material_id']: 0; ?>
								//selected: index == 0 
							};
						}));
					});
				},
				onChange: function(event, value, requiredValues){
					// do stuff
				}
			}, 
			{
				selector: '.step4',
				selected: '<?php echo $params['size_id'] ?>',  // 默认
				requires: ['.step1', '.step2', '.step3'],
				requireAll: true,
				source: function(request, response) {
					var material_id = $('#material_id').val();
					if ( material_id=='' ) return false;
					$.getJSON('<?php echo Router::getBaseUrl() ?>item/size', {'material_id':material_id}, function(data) {
						response($.map(data, function(item, index) {
						if ( data==[] ) return false;
							return {
								label: item.size,
								value: item.id,
								selected: item.id == <?php echo $params['size_id'] ? $params['size_id']: 0; ?> // Select first available option
							};
						}));
					});
				},
				onChange: function(event, value, requiredValues){
					// do stuff
				}
			}
		]
	});

} );

</script>
