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

<div class="panel panel-default">
  <div class="panel-body" style="padding: 1px;">
  
		<div class="classify" style="width: 1197px;">
            <span class="dh">单号</span>
            <span class="sj">下单时间</span>
            <span class="mj">采购商</span>
            <span class="je">实收金额（元）</span>
            <span class="yw" style="left: 760px;">收款时间</span>
            <span class="yw" style="left: 910px;">收款状态</span>
            <span class="cz"><span id="order-status-show">操作</span></span>
       </div>
    <?php if(empty($data_list)){?>
    <div class="alert alert-info" role="alert"><p class="text-center">
    	无记录
    </p></div>
    <?php }else{?>
    <span id="span-trade-order-list">
    <?php foreach ($data_list as $item){?>       
       
       <div class="content">
            <span style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['order_status'];?>"></span>
            <span class="odd"><?php echo $item['order_no'];?></span>
            <span class="sj"><?php echo $item['order_timestamp'];?></span>
            <span class="mj"><?php echo $item['seller_name'];?></span>
            <span class="je"><?php echo number_format($item['order_bid_amount'],2);?></span>
            <span class="xm" style="left: 760px;top:19px;"><?php echo $item['pay_timestamp'];?></span>
            <span class="xm" style="left: 910px;top:19px;"><?php echo (TradeRecordModel::$_send_status_n == $item['send_status'])?'冻结':'正常';?></span>
            <span class="fk" style="left: 1017px;top:19px;">
                <?php if( TradeRecordModel::$_send_status_n == $item['send_status'] ){ ?> <!-- 未确认发货  -->
                <span><a id="add-check-new" href="#" data-toggle="modal"	data-keyboard="false" data-backdrop="static">登记实发</a></span>
                <?php } else { echo '已登记实发'; } ?>
            </span>
            <span class="jf" style="left: 1097px;top:19px;">
                <?php if(TradeRecordModel::$_check_status_y == $item['check_status']){?>
                    <?php if(TradeRecordModel::$_send_status_n == $item['send_status']){ ?> <!-- 先登记实发  未确认发货  -->
                        <span><a id="entity-changeStatus-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static" style="margin-left: 5px;">确认发货</a></span>
                    <?php } else { echo '已确认发货'; } ?>
                <?php }else{ echo '-';}?>    
            </span>
       </div>
       <div class="information" style="width:1198px;">
            <div class="one">
                <span class="lx">联系人：<?php echo $item['partner_name']; ?></span>
                <span class="phone">手机：<?php echo $item['partner_tel']; ?></span>
                <span class="dh">公司电话：<?php echo $item['partner_company_tel'];?></span>
            </div>
            <div class="two">
                <span class="dh">订单号</span>
                <span class="pm">品名</span>
                <span class="gg">规格</span>
                <span class="cz">材质</span>
                <span class="jh">交货地</span>
                <span class="dj">单价（元/ 吨）</span>
                <span class="dg">订购量（吨）</span>
                <span class="je">订单金额（元）</span>
                <span class="sfn">实提数量（件）</span>
                <span class="sfw">实提重量（吨）</span>
                <span class="sfje">应收金额（元）</span>
            </div>
            <?php foreach ($item['list'] as $v){?>
            <div class="three">
                <span class="dh"><?php echo $v['order_no'];?></span>
                <span class="pm"><?php echo $v['item_name'];?></span>
                <span class="gg"><?php echo $v['item_size'];?></span>
                <span class="cz"><?php echo $v['item_type'];?></span>                
                <span class="jh"><?php echo $v['item_delivery_addr'];?></span>
                <span class="dj"><?php echo number_format($v['item_price'],2);?></span>
                <span class="dg"><?php echo $v['item_weight'];?></span>
                <span class="je"><?php echo number_format($v['item_amount'],2);?></span>
                <span class="sfn"><?php echo $v['item_count_send'];?></span>
                <span class="sfw"><?php echo number_format($v['item_weight_send'],3);?></span>
                <span class="sfje"><?php echo number_format($v['item_amount_send'],2);?></span>
            </div>
            <?php }?>
            <div class="four">合计：<em style="margin-right:272px;"><?php echo number_format($item['order_bid_amount'],2);?><span style="color: #000;">元</span></em> &nbsp;<em style="margin-right:26px;"><?php echo number_format(array_sum(array_column($item['list'], 'item_amount_send')),2);?><span style="color: #000;">元</span></em></div>
      </div>
    <?php }?>
    </span>
  </div>
</div>

<nav>
  <ul class="pager" id="entity-pager-ul">
    <li style="float: left;">
	    <a class="dq" href="#" id="entity-export-page-btn" style="color: #424242; background: #F2F2F2 none repeat scroll 0% 0%; border: 1px solid #DADADA; border-radius: 0px;">导出当前页</a>
        <a class="qb" href="#" id="entity-export-all-btn" style="color: #424242; background: #F2F2F2 none repeat scroll 0% 0%; border: 1px solid #DADADA; border-radius: 0px;">导出全部</a>
    </li>
    <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
    <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
    <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
    <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;border-radius: 5px;"></select></li>
  </ul>
</nav>

<?php }?>