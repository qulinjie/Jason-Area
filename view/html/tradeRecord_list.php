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

<?php if(empty($data_list)){?>
<div class="alert alert-info" role="alert"><p class="text-center">
	无记录
</p></div>
<?php }else{?>
<div class="panel panel-default">
<div class="panel-heading" id="search-head-div">付款列表</div>
  <div class="panel-body">
		<table class="table table-hover" id="data-list-table">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>订单号</th>
					<th>时间</th>
					<th>卖家</th>
					<th>金额</th>
					<th>业务员信息</th>
<!-- 					<th>添加时间</th> -->
<!-- 					<th>付款时间</th> -->
					<th>状态</th>
<!-- 					<th>备注</th> -->
					<th id="th-operation-id">操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr onclick="showDetailInfo(this,<?php echo $item['id'];?>);">
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['order_status'];?>"></td>
				<td><?php echo $item['order_no'];?></td>
				<td><?php echo $item['order_timestamp'];?></td>
				<td><?php echo $item['seller_name'];?></td>
				<td><?php echo $item['order_sum_amount'];?></td>
				<td><?php echo $item['seller_conn_name'] . '&nbsp;' . $item['seller_tel']; ?></td>
<!-- 				<td><?php echo $item['add_timestamp'];?></td> -->
<!-- 				<td><?php echo (0 < strtotime($item['pay_timestamp']))? $item['pay_timestamp'] : '-';?></td> -->
				<td><?php if($item['order_status']==TradeRecordModel::$_status_waiting){ echo "待付"; } 
            				else if($item['order_status']==TradeRecordModel::$_status_paid) { echo "已付"; } 
                            else if($item['order_status']==TradeRecordModel::$_status_refuse) { echo "拒付"; } ?>
				</td>
<!-- 				<td><?php echo $item['comment'];?></td> -->
				<td name="td-operation-name">
					<div class="btn-group" role="group">
    					<?php if( $item['order_status'] == TradeRecordModel::$_status_waiting ){?>
    					   <a id="add-pay-new" class="btn btn-primary" href="#" data-toggle="modal"	data-keyboard="false" data-backdrop="static">付款</a>
    					   <a id="entity-changeStatus-btn" class="btn btn-primary" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static" style="margin-left: 5px;">拒付</a>
					    <?php } else { echo '-'; }?>
					</div>
				</td>
			</tr>
			<tr style="background-color: #CCDFEF;display: none;" id="info_tr_<?php echo $item['id'];?>">
			     <td colspan="8">
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
			     </td>
			</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager" id="entity-pager-ul">
		    <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
		    <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
		    <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>