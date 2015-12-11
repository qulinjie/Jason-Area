<?php
/**
 * tradeRecord_list.php
 * 	
 */
?>

<div class="modal fade" id="confirm-pay-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">提示</h5>
			</div>
			<div class="modal-body">
			     <h4 id="confirm-modal-pay" class="text-center">确认操作！</h4>
			</div>
			<div class="alert alert-danger" id="confirm-pay-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-pay">确定</button>
			</div>
		</div>
	</div>
</div>

<?php if(empty($item)){?>
<div class="alert alert-info" role="alert"><p class="text-center">无记录</p></div>
<?php }else{?>
<input type="hidden" class="form-control" id="info-pay-id" value="<?php echo $item['id'];?>"></input>
<table style="width: 100%;">
 <tr style="font-weight: bold;">
     <td>&nbsp;&nbsp;&nbsp;&nbsp;卖家：<?php echo $item['seller_name'];?></td>
     <td>联系人：<?php echo $item['seller_conn_name'];?></td>
     <td>手机：<?php echo $item['seller_tel'];?></td>
     <td>公司电话：<?php echo $item['seller_comp_phone'];?></td>
 </tr>
 <tr>
     <td colspan="4" align="center">
         <table style="width: 98%;background-color: #EFEFEF;text-align: left;margin: 3px;">
              <tr style="background-color: #EFEFEF;color: #424242;font-weight: 600;">
                <td>&nbsp;</td>
                <td>订单号</td>
                <td>品名</td>
                <td>规格</td>
                <td>材质</td>
                <td>交货地</td>
                <td>单价（元/吨）</td>
                <td>订购量（吨）</td>
                <td>订单金额（元）</td>
              </tr>
              <tr style="background-color: #FFF;">
                 <td>&nbsp;</td>
                 <td><?php echo $item['order_no'];?></td>
                 <td><?php echo $item['order_goods_name'];?></td>
                 <td><?php echo $item['order_goods_size'];?></td>
                 <td><?php echo $item['order_goods_type'];?></td>
                 <td><?php echo $item['order_delivery_addr'];?></td>
                 <td><?php echo $item['order_goods_price'];?></td>
                 <td><?php echo $item['order_goods_count'];?></td>
                 <td><?php echo $item['order_sum_amount'];?></td>
              </tr>
         </table>
     </td>
 </tr>
 <tr>
     <td colspan="4" style="text-align: right;font-weight: bold;">合计：<span style="color:red;"><?php echo $item['order_sum_amount'];?></span>&nbsp;元 &nbsp;&nbsp;</td>
 </tr>
</table>
<?php }?>