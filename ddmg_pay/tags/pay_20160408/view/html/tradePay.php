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

<table style="width: 100%;background: #CCDFEF none repeat scroll 0% 0%;border: 1px solid #BFBFBF;line-height: 35px;">
 <tr style="font-weight: bold;">
     <td>&nbsp;&nbsp;&nbsp;&nbsp;供应商：<?php echo $item['seller_name'];?></td>
     <td>联系人：<?php echo $item['partner_name'];?></td>
     <td>手机：<?php echo $item['partner_tel'];?></td>
     <td>公司电话：<?php echo $item['partner_company_tel'];?></td>
 </tr>
 <tr>
     <td colspan="4" align="center">
         <table style="width: 98%;background-color: #EFEFEF;text-align: left;margin: 3px;">
              <tr style="background-color: #EFEFEF;color: #424242;font-weight: 600;">
                <td>&nbsp;</td>
                <td>订单号</td>
                <td>钢厂</td>
                <td>品名</td>
                <td>规格</td>
                <td>材质</td>
                <td>交货地</td>
                <td>数量（件）</td>
                <td>重量（吨）</td>
                <td>单价（元/吨）</td>
                <td>应收小计（元）</td>
              </tr>
              <?php foreach ($item['data_list'] as $item_info){?>
              <tr style="background-color: #FFF;">
                 <td>&nbsp;</td>
                 <td><?php echo $item_info['order_no'];?></td>
                 <td><?php echo $item_info['item_factory'];?></td>
                 <td><?php echo $item_info['item_name'];?></td>
                 <td><?php echo $item_info['item_size'];?></td>
                 <td><?php echo $item_info['item_type'];?></td>
                 <td><?php echo $item_info['item_delivery_addr'];?></td>
                 <td><?php echo $item_info['item_count'];?></td>
                 <td><?php echo number_format($item_info['item_weight'],3);?></td>
                 <td><?php echo number_format($item_info['bid_price'],2);?></td>
                 <td><?php echo number_format($item_info['bid_amount'],2);?></td>
              </tr>
              <?php } ?>
         </table>
     </td>
 </tr>
 <tr>
     <td colspan="4" style="text-align: right;font-weight: bold;">
        <span style="margin-right: 15px;">合计：<span style="color:red;"><?php echo number_format($item['order_bid_amount'],2);?></span>&nbsp;元 </span>
     </td>
 </tr>
</table>

<br/>
<div style="border: 1px dashed #BFBFBF;line-height: 55px;height: 60px;">
<!-- 
    <span style="float:left;margin-left: 15px;">
        <input name="pay_type" value="1" checked="checked" type="radio"/>大大付款 余额：<font color="red">100,000.00</font> 元
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input name="pay_type" value="2" type="radio"/>网银支付
    </span>
    -->
    <span style="float:right;margin-right: 15px;">
                          付款金额：<font color="red"><?php echo number_format($item['order_bid_amount'],2);?></font> 元
    </span>
</div>
<?php }?>