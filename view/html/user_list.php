<?php
/**
 * callHelp_list
 * 	
 */
?>

<div class="modal fade" id="confirm-callHelp-modal">
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
			<div class="alert alert-danger" id="confirm-callHelp-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-callHelp">确定</button>
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
  <div class="panel-body" style="width: 1500px;overflow-x: hidden;">
		<table class="table table-hover" style="width: 1500px;max-width: 1500px;overflow-x: scroll;">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>用户登录帐号</th>
					<th>用户名称</th>
					<th>用户公司名称</th>
					<th>用户状态</th>
					<th>个人认证状态</th>
					<th>企业认证状态</th>
					<th>注册时间</th>
					<th>备注</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($data_list as $item){?>
			<tr>
				<td style="display:none"><?php echo $item['id'];?><input type="hidden" value="<?php echo $item['status'];?>"></td>
				<td><?php echo $item['account'];?></td>
				<td><?php echo empty($item['nicename'])?'-':$item['nicename'];?></td>
				<td><?php echo empty($item['company_name'])?'-':$item['company_name'];?></td>
				<td><?php echo (1== $item['status'])?'启用':'停用';?></td>
				<td><?php 
    				    if(1== $item['personal_authentication_status']) {echo '未认证'; }
    				    else if(2== $item['personal_authentication_status']) {echo '认证中'; }
    				    else if(3== $item['personal_authentication_status']) {echo '认证通过'; }
    				    else if(4== $item['personal_authentication_status']) {echo '认证驳回'; }
				    ?>
				</td>
				<td><?php 
    				    if(1== $item['company_authentication_status']) {echo '未认证'; }
    				    else if(2== $item['company_authentication_status']) {echo '认证中'; }
    				    else if(3== $item['company_authentication_status']) {echo '认证通过'; }
    				    else if(4== $item['company_authentication_status']) {echo '认证驳回'; }
				    ?>
				</td>
				<td><?php echo $item['add_timestamp'];?></td>
				<td><?php echo empty($item['comment'])?'-':$item['comment'];?></td>
				<td>
					<div class="btn-group" role="group">
    			       <a id="callHelp-changeInfo-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">修改</a>
					</div>
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