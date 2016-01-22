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
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
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
            <span class="sj">时间</span>
            <span class="mj">卖家</span>
            <span class="je">金额（元）</span>
            <span class="yw">业务员信息</span>
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
            <span class="je"><?php echo number_format($item['order_bid_amount'],3);?></span>
            <span class="xm"><?php echo $item['partner_name']; ?></span>
            <span class="phone"><?php echo $item['partner_tel']; ?></span>
            <?php if($item['order_status']==TradeRecordModel::$_status_waiting){ ?>
                <span class="fk"><span><a id="add-pay-new" href="#" data-toggle="modal"	data-keyboard="false" data-backdrop="static">付款</a></span></span>
                <span class="jf"><span><a id="entity-changeStatus-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static" style="margin-left: 5px;">拒付</a></span></span>
            <?php }else if($item['order_status']==TradeRecordModel::$_status_paid) { ?>
                <span class="fk">已付</span>
            <?php } else if($item['order_status']==TradeRecordModel::$_status_refuse) { ?>
                <span class="fk">拒付</span>
            <?php } ?>
            
       </div>
       <div class="information" style="width:1198px;">
            <div class="one">
                <span class="mj">商家：<?php echo $item['seller_name'];?></span>
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
            </div>
            <div class="three">
                <span class="dh"><?php echo $item['order_no'];?></span>
                <span class="pm"><?php echo $item['order_goods_name'];?></span>
                <span class="gg"><?php echo $item['order_goods_size'];?></span>
                <span class="cz"><?php echo $item['order_goods_type'];?></span>
                <span class="jh"><?php echo $item['order_delivery_addr'];?></span>
                <span class="dj"><?php echo number_format($item['order_goods_price'],2);?></span>
                <span class="dg"><?php echo $item['order_goods_count'];?></span>
                <span class="je"><?php echo number_format($item['order_sum_amount'],2);?></span>
            </div>
            <div class="four">合计：<em><?php echo number_format($item['order_sum_amount'],2);?></em>元</div>
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