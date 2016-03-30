<?php
/**
 * tradeRecord_list.php
 * 	
 */
?>

<div class="modal fade" id="audit-entity-modal">
	<div class="modal-dialog" style="width: 1200px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="audit_entity_title">付款审批</h4>
			</div>
			
			<div class="modal-body">
				<form class="form-horizontal" id="audit-entity-form">
				  <div id="audit-entity-list"></div>
				</form>
			</div>
			<div class="modal-footer" style="display: none;">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-audit-entity">确定</button>
			</div>
		</div>
	</div>
</div>

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
<?php }else{ ?>
<div class="panel panel-default">
<div class="panel-heading">列表</div>
  <div class="panel-body">
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>单据日期</th>
					<th>申请单号</th>
					<th>单位名称</th>
					<th>申请金额（元）</th>
					<th>款项类别</th>
					<th>业务单号</th>
					<th>机构</th>
					<th>审批状态</th>
					<th>支付状态</th>					
					<th><span id="order-status-show">操作</span></th>
				</tr>
			</thead>
			<tbody>
    <?php foreach ($data_list as $item){?>       
       <tr>
            <td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['apply_status'];?>"></td>
            <td><?php echo date("Y-m-d",strtotime($item['order_timestamp']));?></td>
            <td><?php echo $item['apply_no'];?></td>
            <td><?php echo $item['seller_name'];?></td>
            <td><?php echo number_format($item['order_bid_amount'],2);?></td>
            <td><?php echo $item['amount_type']; ?></td>
            <td><?php echo $item['order_no'];?></td>
            <td><?php echo $item['erp_fgsmc']; ?></td>
            <td><?php
                    echo TradeRecordController::getApplyStatusByKey($item['apply_status'], $audit_level). $item['apply_status'];
                 ?>
            </td>
            <td><?php 
            		if(5!=$item['apply_status']){ echo '-';}
            		elseif(1==$item['order_status'] && -1==$item['backhost_status']){ echo '待支付' ;}
                    /* else if(0===$item['backhostStatus']){ echo '待补录' ;}
                    else if(1==$item['backhostStatus']){ echo '待记帐' ;}
                    else if(2==$item['backhostStatus']){ echo '待复核' ;}
                    else if(3==$item['backhostStatus']){ echo '待授权' ;}
                    else if(4==$item['backhostStatus']){ echo '完成' ;}
                    else if(8==$item['backhostStatus']){ echo '拒绝' ;}
                    else if(9==$item['backhostStatus']){ echo '撤销' ;} */
                    else { echo TradeRecordController::getBackhostStatusByKey($item['backhost_status']);}
                ?>
            </td>            
            <td> 
            
            	<a id="audit-entity-<?php echo $item['id'];?>-<?php echo $audit_level?>" value="<?php echo $item['id'];?>" class="audit-entity" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">
            	 <?php             
	            	//apply_status 申请状态 1一级待审核 2一级审核通过 3一级审核驳回 4二级待审核 5二级审核通过 6二级审核驳回            	
	            	//order_status 订单交易状态 1-待付款 2-已付款
	            	if(!$is_admin && $current_user_id == $item['audit_user_id_first']){
	            		//一级审批
	            		if(1==$item['apply_status']){
	            			echo "审批";
	            		}else{
	            			echo "查看";
	            		}
	            	}elseif($is_admin && $current_user_id == $item['audit_user_id_second']){
	            		//二级审批
						if(2==$item['apply_status']){
							echo "审批";
						}elseif(5==$item['apply_status'] && 1==$item['order_status']){
							echo "付款";
						}else{
							echo "查看";
						}
	            	}else{
	            		echo "查看2";
	            	}
            	?>
            	</a>
            	
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
            <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;border-radius: 5px;"></select></li>
          </ul>
        </nav>
  </div>
</div>

<?php }?>