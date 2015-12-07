<?php 
/**
 * authorizationCode.php
 */
?>

<div class="modal fade" id="add-partner-modal">
	<div class="modal-dialog" style="width: 1000px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_partner_title">新增</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="partner-form">
				    <input type="hidden" class="form-control" id="info-partner-id" value=""></input>
				    <input type="hidden" class="form-control" id="info-partner-dm" value=""></input>
				  <div class="form-group">
				    <label for="add-partner-tel" class="col-sm-2 control-label">手机号&nbsp;<span style="vertical-align:middle;line-height:2.5;color:red;">*</span></label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-tel" placeholder="手机号"
				            onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"></input>
				    </div>
				    <label for="add-partner-city" class="col-sm-2 control-label">城市</label>
				    <div class="col-sm-4">
				          <select class="form-control" id="add-partner-city"></select>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-partner-name" class="col-sm-2 control-label">性别</label>
				    <div class="col-sm-4">
				      <select class="form-control" id="add-partner-gender">
                	      <option value="0">未定义</option>
                          <option value="1">男</option>
                          <option value="2">女</option>
                      </select>
				    </div>
				    <label for="add-partner-info" class="col-sm-2 control-label">签名</label>
				    <div class="col-sm-4">
				    	<input class="form-control" rows="3" id="add-partner-info"  placeholder="签名"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-partner-name" class="col-sm-2 control-label">姓名&nbsp;<span style="vertical-align:middle;line-height:2.5;color:red;">*</span></label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-name" placeholder="姓名"></input>
				    </div>
				    <label for="add-partner-company" class="col-sm-2 control-label">公司名称</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-company" placeholder="公司名称"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-partner-cardId" class="col-sm-2 control-label">身份证号&nbsp;<span class="span-tips-red">*</span></label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-cardId" placeholder="身份证号"></input>
				    </div>
				    <label for="add-partner-bankName" class="col-sm-2 control-label">银行名称</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-bankName" placeholder="银行名称"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-partner-bankNum" class="col-sm-2 control-label">银行账号</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-bankNum" placeholder="银行账号"
				      onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"></input>
				    </div>
				    <label for="add-partner-bankNumBranch" class="col-sm-2 control-label">开户支行名称</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-bankNumBranch" placeholder="开户支行名称"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-partner-advantageProduct" class="col-sm-2 control-label">擅长品种</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-advantageProduct" placeholder="擅长品种"></input>
				    </div>
				 </div>
				  <div class="form-group">
				    <span id="partner-attachment-file" style="display:none;">
    				    <label for="add-partner-attachment" class="col-sm-2 control-label">协议附件</label>
    				    <div class="col-sm-4">
    				      <input type="file" class="form-control" id="file" name="file" placeholder="协议附件"></input>
    				    </div>
    				    <div class="col-sm-1" style="vertical-align:middle;line-height:2.5;">
    				        <a id="partner-attachment-clear" href="#" title="清除选择">
    				            <span class="glyphicon glyphicon-remove" aria-hidden="true" style="font-size:10px;"></span>
    				        </a>
    				    </div>
    				</span>
				    <input type="hidden" class="form-control" id="add-partner-attachmentName"/>
				    <input type="hidden" class="form-control" id="add-partner-attachmentPath"/>
				    <span id="partner-attachment-download" style="display:none;">
    				    <label for="add-partner-download" id="add-partner-download-txt" class="col-sm-1 control-label">下载</label>
    				    <div class="col-sm-3" style="vertical-align:middle;line-height:2.5;">
    				      <a id="partner-attachment-name" href="#" title="协议附件">[协议附件]</a>
    				    </div>
    				    <div class="col-sm-1" style="vertical-align:middle;line-height:2.5;">
				            <a id="partner-attachment-clearServer" href="#" title="删除协议附件">
				                <span class="glyphicon glyphicon-remove" aria-hidden="true" style="font-size:10px;"></span>
				            </a>
				        </div>
				    </span>
				    <input type="hidden" class="form-control" id="add-partner-attachmentDelete"/>
				  </div>
				  <div class="form-group">
				  <span id="partner-attachment-code" style="display:none;">
				    <label for="add-partner-inviteCode" class="col-sm-2 control-label">邀请码</label>
				    <div class="col-sm-4">
				      <input class="form-control" id="add-partner-inviteCode" placeholder="邀请码"></input>
				    </div>
				  </span>
				 </div>
				 <div class="form-group">
				    <label for="add-partner-fgs" class="col-sm-2 control-label">分公司&nbsp;<span class="span-tips-red">*</span></label>
				    <div class="col-sm-4">
				      <select class="form-control" id="add-partner-fgs"></select>
				    </div>
				    <label for="add-partner-bmdm" class="col-sm-2 control-label">部门&nbsp;<span class="span-tips-red">*</span></label>
				    <div class="col-sm-4">
				      <select class="form-control" id="add-partner-bmdm"></select>
				    </div>
				 </div>
				  <div class="alert alert-danger" id="add-partner-hint"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-add-partner">确定</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<h1 class="page-header">授权码管理</h1>
<div id="add-button-group">
	<a id="add-partner-new" class="btn btn-primary" href="#" data-toggle="modal"	data-keyboard="false" data-backdrop="static">新增</a>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
	  <div class="form-group">
	    <label for="partner-search-tel">手机号</label>
	    <input type="text" class="form-control" id="partner-search-tel" placeholder="手机号"
	           onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
	  </div>
	  <div class="form-group">
	    <label for="partner-search-name">姓名</label>
	    <input type="email" class="form-control" id="partner-search-name" placeholder="名称">
	  </div>
	  <div class="form-group">
	    <label for="partner-search-company">公司名称</label>
	    <input type="email" class="form-control" id="partner-search-company" placeholder="公司名称">
	  </div>
	  <div class="form-group">
	    <label for="partner-search-time">添加时间</label>
	    <input type="email" class="form-control form_datetime" id="partner-search-time1" placeholder="添加时间">
	    -
	    <input type="email" class="form-control form_datetime" id="partner-search-time2" placeholder="添加时间">
	  </div>
	  <div class="form-group">
	    <label for="partner-search-enabledStatus">状态</label>
	    <select class="form-control" id="partner-search-enabledStatus">
	      <option value="-1">全部</option>
          <option value="1">启用</option>
          <option value="2">停用</option>
        </select>
	  </div>
	  <button type="button" class="btn btn-primary" id="partner-search-btn">查询</button>
	  <button type="button" class="btn btn-default search-clear-btn" id="partner-clear-btn">清空查询条件</button>
	  <div class="alert alert-danger  search-list-hint" id="search-partner-hint"></div>
	</div>
  </div>
</div>

<div  id="view-list-html">
<?php echo $view_list_html; ?>
</div>
