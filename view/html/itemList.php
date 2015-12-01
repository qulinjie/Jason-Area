<?php 
/**
 * 
 */
$session = Controller::instance('session');
$loginUser = $session->get( 'loginUser' );
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">商品列表</h2>
	</div>
	<div class="col-md-6 text-right">
		<?php if ( $loginUser['seller_type'] && in_array($loginUser['seller_type'], [2]) ) { ?>
		  <a href="<?php echo Router::getBaseUrl() ?>erp" class="btn btn-success text-right">ERP商品管理</a>
		<?php } ?>
		  <a href="<?php echo Router::getBaseUrl() ?>itemsImport.csv" class="btn btn-success text-right">下载导入模版</a>
		  <a href="javascript:;" class="btn btn-success text-right" id="import-item-btn">批量导入商品</a>
		  <a href="<?php echo Router::getBaseUrl() ?>item/add?<?= $queryString ?>" class="btn btn-success text-right">添加单条商品</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0" id="cascading">
		<form class="form-inline" action="<?php echo Router::getBaseUrl() ?>item" method="get">
		<table class="table">
            <tr>
                <td align="right" style="vertical-align:middle">分类</td>
                <td align="left">
                    <select name="category_id" id="category_id" class="form-control step1" style="width:170px">
        				<option value="">全部</option>
        			</select>
                </td>
                <td align="right" style="vertical-align:middle">品名</td>
                <td align="left">
                    <select name="product_id" id="product_id" class="form-control step2" style="width:170px">
    				    <option value="">全部</option>
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
            </tr>
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
				<th>ID</th>
				<th>分类</th>
				<th>品名</th>
				<th>材质</th>
				<th>规格</th>
				<th>厂家</th>
				<th>城市</th>
				<th>价格(元/吨)</th>
				<th>件重(吨/件)</th>
				<th>交割仓库</th>
				<th>添加时间</th>
				<th>销售状态</th>
				<th>操作</th>
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
					<td><?php echo $item['add_timestamp'] ?></td>
					<td><span id="on_sale_status_<?=$item['id']?>"><?php echo $item['is_on_sale']?'上架':'下架'; ?></span></td>
					<td>
						<!--<a href="<?php echo Router::getBaseUrl() ?>item/edit?id=<?=$item['id']?>" class="btn btn-default">编辑</a>-->
						<a href="<?php echo Router::getBaseUrl() ?>item/edit?id=<?=$item['id']?>" class="">修改</a>
						<!--<a href="<?php echo Router::getBaseUrl() ?>item/delete?<?=$queryString?>id=<?=$item['id']?>" class="">删除</a>-->
						<a href="javascript:;" alt="<?=$item['id']?>" class="delete">删除</a>
						<?php if ( $item['is_on_sale'] ) { ?>
							<a href="javascript:;" alt="<?=$item['id']?>" class="toggle">下架</a>
						<?php } else { ?>
							<a href="javascript:;" alt="<?=$item['id']?>" class="toggle">上架</a>
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

// 删除按钮
$( '.delete' ).on( 'click', function(){
	if (!confirm( '确定要删除吗?')) {
			return false;
	}

	var a = $( this );
	var id = a.attr( 'alt' );
	$('#msg-box').modal('show');
	$.post(BASE_PATH + 'item/delete', {'id':id},
			function( data ) {
				if ( data.code!=0 ) 
				{ // 失败
					$('#msg-box-content').html( data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' );
				}
				else
				{ // 成功
					$('#msg-box-content').html( data.msg + '&nbsp;&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' );
					a.parent().parent().remove();

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

} );

</script>
