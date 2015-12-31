<?php
/**
 * authorizationCode_list.php
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

<div class="right">
    <div class="classify">
    	<span class="sq">授权码</span>
    	<span class="fs">使用方式</span>
    	<span class="ky">可用次数</span>
    	<span class="ks">有效开始时间</span>
    	<span class="js">有效结束时间</span>
    	<span class="yy">已使用次数</span>
    	<span class="remark">备注</span>
    	<span class="operation">操作</span>
    </div>
            
<?php if(empty($data_list)){?>
<div class="alert alert-info" role="alert"><p class="text-center">
	无记录
</p></div>
<?php }else{?>

<?php foreach ($data_list as $item){?>
    <div class="detail">
        <span style="display: none;"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></span>
    	<span class="sq"><?php echo $item['code'];?></span>
    	<span class="fs"><?php if($item['type']==AuthorizationCodeModel::$_type_count){ echo "按次数"; } else { echo "按时间"; } ?></span>
    	<span class="ky"><?php if($item['type']==AuthorizationCodeModel::$_type_count){ echo $item['active_count']; } else { echo "-"; } ?></span>
    	<span class="ks"><?php echo (0 < strtotime($item['time_start']))? $item['time_start'] : '-';?></span>
    	<span class="js"><?php echo (0 < strtotime($item['time_end']))? $item['time_end'] : '-';?></span>
    	<span class="yy"><?php echo $item['used_count'];?></span>
    	<span class="remark"><?php echo $item['comment'];?></span>
    	<span class="operation">
    	   <span>
    	   <?php if( 0 < $item['used_count'] ){?>
		      <a id="entity-infoDisplay-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">查看</a>
		   <?php }?>
		   </span>
	    </span>
    </div>
<?php }?>
<div class="below">
    <nav>
      <ul class="pager">
        <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
        <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
        <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
        <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;border-radius: 5px;"></select></li>
      </ul>
    </nav>
</div>
<?php }?>
</div>