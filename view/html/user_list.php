<?php
/**
 * callHelp_list
 * 	
 */
?>

<div class="modal fade" id="audit-personal-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">用户信息修改</h5>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				    <input type="hidden" id="id" name="id" value=""/>				  
					<div class="form-group">
						<label for="user-account" class="col-sm-2 control-label">帐号</label>
						<div class="col-sm-4">
							<input type="text" id="user-account" name="user-account" class="form-control" value="">			
						</div>
						<label for="user-name" class="col-sm-2 control-label">用户名称</label>
						<div class="col-sm-4">
							<input type="text" id="user-name" name="user-name" class="form-control" value="">			
						</div>
					</div>
					<div class="form-group">
						<label for="user-legal-name" class="col-sm-2 control-label">法人名称</label>
						<div class="col-sm-4">
							<input type="text" id="user-legal-name" name="user-legal-name" class="form-control" value="">			
						</div>
						<label for="user-company-name" class="col-sm-2 control-label">公司名称</label>
						<div class="col-sm-4">
							<input type="text" id="user-company-name" name="user-company-name" class="form-control" value="">			
						</div>
					</div>					
					<div class="form-group">
					    <label for="user-business-license" class="col-sm-2 control-label">营业执照号</label>
						<div class="col-sm-4">
							<input type="text" id="user-business-license" name="user-business-license" class="form-control" value="">			
						</div>
						
						<label class="col-sm-2 control-label">用户状态</label>
						<div class="col-sm-1">
							<input type="radio" class="form-control" name="user-status" value="1">
						</div>
						<div class="col-sm-1" style="vertical-align: middle; line-height: 2.5;">启用</div>
						<div class="col-sm-1">
							<input type="radio" class="form-control"  name="user-status"	value="2">
						</div>
						<div class="col-sm-1" style="vertical-align: middle; line-height: 2.5;">禁用</div>
					</div>
					<div class="form-group">
					    <a href="javascript:void(0);" id="person-cert" target="_blank"><label class="col-sm-offset-2 col-sm-4 control-label" style="text-align:center;">个人证书详情</label></a>						
						<a href="javascript:void(0);" id="enterprise-cert" target="_blank"><label class="col-sm-offset-2 col-sm-4 control-label" style="text-align:center;">企业证书详情</label></a>					
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">个人认证</label>
						<div class="col-sm-1">
							<input type="radio" class="form-control" name="user-person-cert" value="1">
						</div>
						<div class="col-sm-1" style="vertical-align: middle; line-height: 2.5;">未认证</div>
						<div class="col-sm-1">
							<input type="radio" class="form-control"  name="user-person-cert"	value="2">
						</div>
						<div class="col-sm-2" style="vertical-align: middle; line-height: 2.5;">认证中</div>
					
						<div class="col-sm-1">
							<input type="radio" class="form-control" name="user-person-cert" value="3">
						</div>
						<div class="col-sm-1" style="vertical-align: middle; line-height: 2.5;">同意</div>
						<div class="col-sm-1">
							<input type="radio" class="form-control"  name="user-person-cert"	value="4">
						</div>
						<div class="col-sm-2" style="vertical-align: middle; line-height: 2.5;">驳回</div>
					</div>
				
					<div class="form-group">	
						<label class="col-sm-2 control-label">企业认证</label>
						<div class="col-sm-1">
							<input type="radio" class="form-control" name="user-company-cert" value="1">
						</div>
						<div class="col-sm-1" style="vertical-align: middle; line-height: 2.5;">未认证</div>
						<div class="col-sm-1">
							<input type="radio" class="form-control"  name="user-company-cert"	value="2">
						</div>
						<div class="col-sm-2" style="vertical-align: middle; line-height: 2.5;">认证中</div>
						<div class="col-sm-1">
							<input type="radio" class="form-control" name="user-company-cert" value="3">
						</div>
						<div class="col-sm-1" style="vertical-align: middle; line-height: 2.5;">同意</div>
						<div class="col-sm-1">
							<input type="radio" class="form-control"  name="user-company-cert"	value="4">
						</div>
						<div class="col-sm-2" style="vertical-align: middle; line-height: 2.5;">驳回</div>
					</div>
					<div class="form-group">
						<label for="user-remark" class="col-sm-2 control-label">备注</label>
						<div class="col-sm-10">
							<input type="text" id="user-remark" name="user-remark" class="form-control" value="">
						</div>
					</div>
				</form>
			</div>
			<div class="alert alert-danger" id="user-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-user-update">确定</button>
			</div>
		</div>
	</div>
</div>

<?php if(empty($data_list)){?>
<div class="alert alert-info" role="alert">
	<p class="text-center">无记录</p>
</div>
<?php }else{?>
<div class="panel panel-default">
	<div class="panel-heading">列表</div>
	<div class="panel-body" style="width: 1500px; overflow-x: hidden;">
		<table class="table table-hover"
			style="width: 1500px; max-width: 1500px; overflow-x: scroll;">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th>用户登录帐号</th>
					<th>用户名称</th>					
					<th>法人名称</th>
					<th>公司名称</th>
					<th>营业执照号</th>					
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
					<td><?php echo $item['nicename'] ? $item['nicename'] : '-';?></td>
					<td><?php echo $item['legal_name'] ? $item['legal_name'] : '-';?></td>
					<td><?php echo $item['company_name'] ? $item['company_name'] : '-';?></td>
					<td><?php echo $item['business_license'] ? $item['business_license'] : '-';?></td>					
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
					       <a id="entity-update-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">修改</a> 					       
    			       </div>
					</td>
				</tr>
<?php }?>
		</tbody>
		</table>
		<nav>
			<ul class="pager" id="entity-pager-ul">
				<li><a id="entity-list-prev" href="#"
					<?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
				<li><span id="entity-current-page"><?php echo $current_page;?></span>
					/ <span id="entity-total-page"><?php echo $total_page;?></span></li>
				<li><a id="entity-list-next" href="#"
					<?php if($current_page == $total_page){?> class="disabled"
					<?php }?>>下一页</a></li>
				<li><select class="form-control input-sm " id="entity-custom-page"
					style="width: 60px; height: 26px; display: inline;"></select></li>
			</ul>
		</nav>
	</div>
</div>
<?php }?>