<?php 
/**
 * 卖家修改商品页面
 */
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">订单详情</h2>
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
				<td><span>买家姓名:</span> <?= $order['buy_name']?></td>
				<td><span>买家联系方式:</span> <?= $order['buy_tel']?></td>
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
			<tr>
				<th>序号</th>
				<th>商品</th>
				<th>单价(元/吨)</th>
				<th>件重(吨)</th>
				<th>件数</th>
				<th>小计(元)</th>
				<!--<th>操作</th>-->
			</tr>
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

<?php if ( $order['comment'] ) { ?>
<div class="table-responsive">
	<h2 class="page-header">订单评论</h2>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>序号</th>
				<th>评论人</th>
				<th>质量评分</th>
				<th>态度评分</th>
				<th>评论内容</th>
				<th>评论时间</th>
				<!--<th>操作</th>-->
			</tr>
		</thead>
		<tbody>
				<?php foreach( $order['comment'] as $k=>$cm ) { ?>
				<tr>
					<td><?= $k+1 ?></td>
					<td><?= $cm['com_name'] ?></td>
					<td><?= $cm['quality']/10 ?>星</td>
					<td><?= $cm['attitude']/10 ?>星</td>
					<td><?= $cm['info'] ?></td>
					<td><?=$cm['add_timestamp']?></td>
					<!--<td>-->
						<!--<a href="javascript:;" alt="<?=$cm['id'] ?>"  class="delete-order-item">删除</a>-->
					<!--</td>-->
				</tr>
				<?php } ?>
		</tbody>
	</table>
</div>
<?php } ?>
