<?php
/**
 * tradeRecord_list.php
 * 	
 */
?>

<div class="modal fade" id="confirm-entity-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">提示</h5>
			</div>
			<div class="modal-body">
			     <h4 id="confirm-modal-body" class="text-center">确认操作！</h4>
			</div>
			<div class="alert alert-danger" id="confirm-entity-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-entity">确定</button>
			</div>
		</div>
	</div>
</div>


<?php if(empty($data_list)){?>
<div class="alert alert-info" role="alert"><p class="text-center">
	无记录
</p></div>
<?php }else{?>
<div class="panel panel-default">
<div class="panel-heading">列表</div>
  <div class="panel-body" style="width: 1500px;overflow-x: scroll;">
		<table class="table table-hover" style="width: 3000px;max-width: 3000px;overflow-x: scroll;">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>单号</th>
					<th>下单时间</th>
					<th>采购商</th>
					<th>实收金额（元）</th>
					<th>业务员</th>
					<th>业务员电话</th>
					<th>收款时间</th>
					<th>收款状态</th>
					<th><span id="order-status-show">操作</span></th>
				</tr>
			</thead>
			<tbody>
    <span id="span-trade-order-list">
    <?php foreach ($data_list as $item){?>       
       <tr>
            <td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['order_status'];?>"></td>
            <td><?php echo $item['order_no'];?></td>
            <td><?php echo $item['order_timestamp'];?></td>
            <td><?php echo $item['seller_name'];?></td>
            <td><?php echo number_format($item['order_bid_amount'],2);?></td>
            <td><?php echo $item['partner_name']; ?></td>
            <td><?php echo $item['partner_tel']; ?></td>
            <td><?php echo $item['pay_timestamp'];?></td>
            <td><?php echo (TradeRecordModel::$_send_status_n == $item['send_status'])?'冻结':'正常';?></td>
            <td>
                <?php if( TradeRecordModel::$_send_status_n == $item['send_status'] ){ ?> <!-- 未确认发货  -->
                <span><a id="add-check-new" href="#" data-toggle="modal"	data-keyboard="false" data-backdrop="static">登记实发</a></span>
                <?php } else { echo '已登记实发'; } ?>
            </td>
            <td>
                <?php if(TradeRecordModel::$_check_status_y == $item['check_status']){?>
                    <?php if(TradeRecordModel::$_send_status_n == $item['send_status']){ ?> <!-- 先登记实发  未确认发货  -->
                        <span><a id="entity-changeStatus-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static" style="margin-left: 5px;">确认发货</a></span>
                    <?php } else { echo '已确认发货'; } ?>
                <?php }else{ echo '-';}?>    
            </td>
       </tr>
       <tr>
       <td colspan="10">
           <table style="width: 99%;background-color: #e8e8e8;">
            <tr>
                <td>订单号</td>
                <td>品名</td>
                <td>规格</td>
                <td>材质</td>
                <td>交货地</td>
                <td>单价（元/ 吨）</td>
                <td>订购量（吨）</td>
                <td>订单金额（元）</td>
                <td>实提数量（件）</td>
                <td>实提重量（吨）</td>
                <td>应收金额（元）</td>
            </tr>
            <?php foreach ($item['list'] as $v){?>
            <tr>
                <td><?php echo $v['order_no'];?></td>
                <td><?php echo $v['item_name'];?></td>
                <td><?php echo $v['item_size'];?></td>
                <td><?php echo $v['item_type'];?></td>                
                <td><?php echo $v['item_delivery_addr'];?></td>
                <td><?php echo number_format($v['item_price'],2);?></td>
                <td><?php echo $v['item_weight'];?></td>
                <td><?php echo number_format($v['item_amount'],2);?></td>
                <td><?php echo $v['item_count_send'];?></td>
                <td><?php echo number_format($v['item_weight_send'],3);?></td>
                <td><?php echo number_format($v['item_amount_send'],2);?></td>
            </tr>
			<?php }?>
		  </table>
       </td>
       </tr>
    <?php }?>
    </span>
</tbody>
</table>

<nav>
  <ul class="pager" id="entity-pager-ul">
    <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
    <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
    <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
    <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;border-radius: 5px;"></select></li>
  </ul>
</nav>

<?php }?>