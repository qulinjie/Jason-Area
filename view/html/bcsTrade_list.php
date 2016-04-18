<?php
/**
 * bcsTrade_list.php
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
<?php $isAdmin = AdminController::isLogin(); ?>
<div class="panel panel-default">
<div class="panel-heading" id="search-head-div">列表</div>
  <div class="panel-body" style="width: 100%;overflow-x: scroll;">
		<table class="table table-hover" id="data-list-table" style="width: 100%;max-width: 1900px;overflow-x: scroll;">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<?php if($isAdmin){ echo '<th>专属账号</th>'; } ?>
					<th>交易流水号</th><!-- 商户交易流水号 -->
					<th nowrap>收款/付款</th>
					<th>交易完成时间</th>
					<th nowrap>分公司名称</th>
					<th>对方账户名</th>
					<th>对方帐号</th>
					<th nowrap>交易金额（元）</th>
					<?php if(strval($inout) == ''){ echo '<th>结余</th>'; } ?>
					<th nowrap>状态</th>
					<th>对方行号</th>
					<th>对方行名</th>
					<th>备注</th>
					<?php if($isAdmin && strval($inout) == '1'){ echo '<th>发送erp结果</th><th>操作</th>'; } ?>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<?php if($isAdmin){ echo '<td>' . $item['ACCOUNT_NO'] . '</td>'; } ?>
				<td><?php echo $item['MCH_TRANS_NO'];?></td>
				<td><?php if('1' == strval($item['debitCreditFlag'])) { 
				            echo '收款'; 
				        }else if('0' == strval($item['debitCreditFlag'])) { 
				            echo '付款';
				        } else {
				            echo '-';
				        }
				    ?>
				<td nowrap><?php echo (0 < strtotime($item['TRANS_TIME']))? $item['TRANS_TIME'] : '-';?></td>
				<td><?php echo $item['erp_fgsdm'];?></td>
				<td nowrap="nowrap"><?php echo $item['oppositeAcctName'];?></td>
				<td><?php echo $item['oppositeAcctNo'];?></td>
				
				<td><?php echo number_format($item['TX_AMT'],2);?></td>
				<?php if(strval($inout) == ''){ echo '<td>' . number_format($item['accountBalance'],2) . '</td>'; } ?>
				
				<td><?php if($item['status']==BcsTradeModel::$_status_success){ echo "成功"; } 
            				else if($item['status']==BcsTradeModel::$_status_failed) { echo "失败"; } 
                            else if($item['status']==BcsTradeModel::$_status_unknown) { echo "未知"; } ?>
				</td>
				<td><?php echo $item['payeeBankNo'];?></td>
				<td nowrap><?php echo $item['payeeBankName'];?></td>
				<td nowrap><?php echo $item['comment'];?></td>
				<?php if($isAdmin){?>
				
				<?php if(strval($inout) == '1'){ ?>
				<td>
					<?php if(2 == $item['is_erp_sync']){
	            		  	echo "成功";
	            		  }elseif(4 == $item['is_erp_sync']){
	            		  	echo "不需同步";
	            		  }else{ ?>
	            		  	<a id="erp-sync-entity-<?php echo $item['MCH_TRANS_NO'];?>" class="erp-sync-entity" href="#" >手动发送</a>
	            	<?php } ?>            	
	            </td>
	            <?php } ?>
				
				<td>
					<div class="btn-group" role="group">
			           <?php if( 1 == $item[record_bank_type] ){ ?>
    			       <a id="entity-loadInfo-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">更新</a>
    			       <?php } else if( 2 == $item[record_bank_type] ){ ?>
    			       -
    			       <?php } ?>
					</div>
				</td>
				
				<?php }?>
			</tr>
<?php }?>

		<tr>
			<td style="display:none"></td>
			<?php if($isAdmin){ echo '<td></td>'; } ?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>总计:<?php echo number_format($TX_AMT_total, 2); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<?php if($isAdmin){?>
			<td></td>
			<?php }?>
		</tr>
		
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