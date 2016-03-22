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
					<th>审批状态</th>
					<th>机构</th>
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
            <td><?php if(1==$item['apply_status']){ echo '待审批' ;}
                    else if(2==$item['apply_status']){ echo '审批通过' ;}
                    else if(3==$item['apply_status']){ echo '审批驳回' ;}
                    else { echo $item['apply_status'] ;}
                ?>
            </td>
            <td><?php echo $item['erp_fgsmc']; ?></td>
            <td> 
            <?php if($is_admin && 1==$item['apply_status']){ ?>
            	<a id="audit-entity-<?php echo $item['id'];?>" value="<?php echo $item['id'];?>" class="audit-entity" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">审批</a>
            <?php }else{ ?> 
            	- 
            <?php }?></td>
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