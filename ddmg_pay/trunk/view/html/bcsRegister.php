<?php 
/**
 */
?>
<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog" style="width: 1000px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_entity_title">开户</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				  <input type="hidden" class="form-control" id="info-entity-id" value=""></input>
				  <div class="form-group">
				    <label for="add-entity-user_id" class="col-sm-2 control-label">用户登录帐号</label>
				    <div class="col-sm-4">
    				    <select class="form-control" id="add-entity-user_id"></select>
				    </div>
				    <label for="add-entity-SIT_NO" class="col-sm-2 control-label">席位号</label>
				    <div class="col-sm-4">
				        <input class="form-control" id="add-entity-SIT_NO" placeholder="席位号"></input>
				    </div>				  
				  </div>
				  <div class="form-group">
				    <label for="add-entity-CUST_CERT_TYPE" class="col-sm-2 control-label">客户证件类型</label>
				    <div class="col-sm-4">
    				    <select class="form-control" id="add-entity-CUST_CERT_TYPE">
    				            <option value="-1">请选择</option>
                    	        <option value="01">身份证18位</option>
                                <option value="02">身份证15位</option>
                                <option value="03">军官证</option>
                                <option value="04">台胞证</option>
                                <option value="05">外国护照</option>
                                <option value="06">户口簿</option>
                                <option value="07">香港通行证</option>
                                <option value="08">边民出入境通行证</option>
                                <option value="09">士兵证</option>
                                <option value="10">护照</option>
                                <option value="11">军事学员证</option>
                                <option value="12">离休干部荣誉证</option>
                                <option value="13">军官退休证</option>
                                <option value="14">文职干部证</option>
                                <option value="15">学生证</option>
                                <option value="16">澳门通行证</option>
                                <option value="17">外国人永久居留证</option>
                                <option value="18">村民委员证明</option>
                                <option value="19">武警证</option>
                                <option value="20">营业执照</option>
                                <option value="21">组织结构代码</option>
                                <option value="22">其他</option>
                          </select>
				    </div>
				    <label for="add-entity-CUST_CERT_NO" class="col-sm-2 control-label">客户证件号码</label>
				    <div class="col-sm-4">
				        <input class="form-control" id="add-entity-CUST_CERT_NO" placeholder="客户证件号码"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-CUST_NAME" class="col-sm-2 control-label">客户名称</label>
				    <div class="col-sm-4">
    				    <input class="form-control" id="add-entity-CUST_NAME" placeholder="客户名称"></input>
				    </div>
				    <label for="add-entity-CUST_ACCT_NAME" class="col-sm-2 control-label">客户账户名</label>
				    <div class="col-sm-4">
				        <input class="form-control" id="add-entity-CUST_ACCT_NAME" placeholder="客户账户名"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-CUST_SPE_ACCT_NO" class="col-sm-2 control-label">客户结算账户</label>
				    <div class="col-sm-4">
    				    <input class="form-control" id="add-entity-CUST_SPE_ACCT_NO" placeholder="客户结算账户"></input>
				    </div>
				    <label for="add-entity-CUST_SPE_ACCT_BKTYPE" class="col-sm-2 control-label">客户结算账户行别</label>
				    <div class="col-sm-4">
				        <select class="form-control" id="add-entity-CUST_SPE_ACCT_BKTYPE">
				          <option value="-1">请选择</option>
                	      <option value="0">长沙银行</option>
                	      <option value="1">非长沙银行</option>
                      </select>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-CUST_SPE_ACCT_BKNAME" class="col-sm-2 control-label">客户结算账户行名</label>
				    <div class="col-sm-4">
    				    <input class="form-control" id="add-entity-CUST_SPE_ACCT_BKNAME" placeholder="客户结算账户行名"></input>
				    </div>
				    <label for="add-entity-CUST_SPE_ACCT_BKID" class="col-sm-2 control-label">客户结算账户行号</label>
				    <div class="col-sm-4">
				        <input class="form-control" id="add-entity-CUST_SPE_ACCT_BKID" placeholder="客户结算账户行号"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-CUST_PHONE_NUM" class="col-sm-2 control-label">客户手机号码</label>
				    <div class="col-sm-4">
    				    <input class="form-control" id="add-entity-CUST_PHONE_NUM" placeholder="客户手机号码"></input>
				    </div>
				    <label for="add-entity-CUST_TELE_NUM" class="col-sm-2 control-label">客户电话号码</label>
				    <div class="col-sm-4">
				        <input class="form-control" id="add-entity-CUST_TELE_NUM" placeholder="客户电话号码"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-CUST_ADDR" class="col-sm-2 control-label">客户地址</label>
				    <div class="col-sm-4">
    				    <input class="form-control" id="add-entity-CUST_ADDR" placeholder="客户地址"></input>
				    </div>
				    <label for="add-entity-RMRK" class="col-sm-2 control-label">客户备注</label>
				    <div class="col-sm-4">
				        <input class="form-control" id="add-entity-RMRK" placeholder="客户备注"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-ENABLE_ECDS" class="col-sm-2 control-label">是否开通电票</label>
				    <div class="col-sm-4">
				      <select class="form-control" id="add-entity-ENABLE_ECDS">
				          <option value="-1">请选择</option>
                	      <option value="0">否</option>
                	      <option value="1">是</option>
                      </select>
				    </div>
				    <label for="add-entity-IS_PERSON" class="col-sm-2 control-label">是否个人</label>
				    <div class="col-sm-4">
				      <select class="form-control" id="add-entity-IS_PERSON">
				          <option value="-1">请选择</option>
                	      <option value="0">否</option>
                	      <option value="1">是</option>
                      </select>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-comment" class="col-sm-2 control-label">备注</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-entity-comment" placeholder="备注"></input>
				    </div>
				  </div>
				  <div class="alert alert-danger" id="add-entity-hint"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-add-entity">确定</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<h1 class="page-header">开户管理</h1>

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-SIT_NO">席位号</label>
	    <input type="email" class="form-control" id="entity-search-SIT_NO" placeholder="席位号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-ACCOUNT_NO">虚拟卡号</label>
	    <input type="email" class="form-control" id="entity-search-ACCOUNT_NO" placeholder="虚拟卡号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-time1">注册时间</label>
	    <input type="email" class="form-control form_datetime" id="entity-search-time1" placeholder="注册时间">
	    -
	    <input type="email" class="form-control form_datetime" id="entity-search-time2" placeholder="注册时间">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-status">注册状态</label>
	    <select class="form-control" id="entity-search-status">
	      <option value="-1">全部</option>
	      <option value="1">成功</option>
	      <option value="2">失败</option>
	      <option value="3">处理中</option>
        </select>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
    	  <button type="button" class="btn btn-default" id="entity-clear-btn">清空查询条件</button>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="search-entity-hint"></div>
	</div>
  </div>
</div>

<div id="add-button-group" style="padding-bottom: 2px;">
	<a id="add-entity-new" class="btn btn-primary" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">新增</a>
</div>
<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>
