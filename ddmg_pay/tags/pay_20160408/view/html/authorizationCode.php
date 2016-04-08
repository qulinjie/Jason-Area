<?php 
/**
 * authorizationCode.php
 */
?>

<div class="modal fade" id="info-entity-modal">
	<div class="modal-dialog" style="width: 1250px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">查看</h4>
			</div>
			<div class="entirety">
			<div class="tab">
            <ul class="tab_conbox" id="tab_conbox">
			     <div class="modal-body" id="info-entity-list" style="padding: 5px;"></div>
			</ul>
			</div>
			</div>
			<div class="alert alert-danger" id="info-entity-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog" style="width: 700px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_entity_title">新增</h4>
			</div>
			
			<div class="modal-body">
				<form class="form-horizontal" id="entity-form">
				    <input type="hidden" class="form-control" id="info-entity-id" value=""></input>
				  <div class="form-group">
				    <label for="add-entity-code" class="col-sm-2 control-label">授权码</label>
				    <div class="col-sm-6">
				        <span id="info-entity-code" style="vertical-align:middle;line-height:2.5;">DDMG0099</span>
				        <input type="hidden" class="form-control" id="add-entity-code" value=""></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-type" class="col-sm-2 control-label">使用方式</label>
				    <div class="col-sm-1" style="padding-right: 0px;padding-left: 0px;"><input type="radio" class="form-control" id="add-entity-type-count" name="add-entity-type-rdo" value="1" checked="checked"/></div>
				    <div class="col-sm-2" style="vertical-align:middle;line-height:2.8;padding-left: 0px;">按次数</div>
				    <div class="col-sm-1" style="padding-right: 0px;padding-left: 0px;"><input type="radio" class="form-control" id="add-entity-type-time" name="add-entity-type-rdo" value="2"/></div>
				    <div class="col-sm-2" style="vertical-align:middle;line-height:2.8;padding-left: 0px;">按时间</div>
				  </div>
				  <div class="form-group" id="div-active-count">
				    <label for="add-entity-active_count" class="col-sm-2 control-label">可用次数</label>
				    <div class="col-sm-6">
				      <input class="form-control" id="add-entity-active_count" placeholder="可用次数"
				            onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"></input>
				    </div>
				  </div>
				  <div class="form-group" id="div-time-start" style="display: none;">
				    <label for="add-entity-time_start" class="col-sm-2 control-label">有效开始时间</label>
				    <div class="col-sm-6">
				      <input class="form-control form_datetime" id="add-entity-time_start" placeholder="有效开始时间"></input>
				    </div>
				  </div>
				  <div class="form-group" id="div-time-end" style="display: none;">
				    <label for="add-entity-time_end" class="col-sm-2 control-label">有效结束时间</label>
				    <div class="col-sm-6">
				      <input class="form-control form_datetime" id="add-entity-time_end" placeholder="有效结束时间"></input>
				    </div>
				 </div>
				  <div class="form-group">
				    <label for="add-entity-comment" class="col-sm-2 control-label">备注</label>
				    <div class="col-sm-6">
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

<div id="add-button-group" class="add" style="padding-bottom: 0px;">
	<a id="add-entity-new" href="#" data-toggle="modal"	data-keyboard="false" data-backdrop="static">+授权码</a>
	<span>
    	授权码
    	<input type="text" id="entity-search-code" style="background-color:white;border: #C8C8C8 1px solid;color: black;border-radius: 2px;"/>
    	使用方式
    	<select id="entity-search-type">
          <option value="-1">全部</option>
          <option value="1">按次数</option>
          <option value="2">按时间</option>
        </select>
        <input type="button" id="entity-search-btn" value="查询" style="border-radius: 5px;"/>
    </span>
</div>
<div class="alert alert-danger  search-list-hint" id="search-list-hint"></div>
<div class="form-group" style="display: none;">
    <label for="entity-search-status">状态</label>
    <select class="form-control" id="entity-search-status">
      <option value="-1">全部</option>
      <option value="1">启用</option>
      <option value="2">停用</option>
      <option value="3">失效</option>
    </select>
</div>
	  

<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>
