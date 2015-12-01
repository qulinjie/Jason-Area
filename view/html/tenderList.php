<?php 
/**
 * 招标信息
 */
?>
<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<div class="modal fade" id="tender-bid-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="tender-bid-title" >投标</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="f-form-placeholder">
				  <div class="form-group">
				    <label for="tender-bid-comment" class="col-sm-2 control-label">投标信息</label>
				    <div class="col-sm-9">
				      <textarea class="form-control" rows="3" id="tender-bid-comment"  placeholder="投标信息"></textarea>
				    </div>
				  </div>
				  <div class="alert alert-danger" id="tender-bid-hint"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-tender">确定</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div id="tender-bid-title-div" class="col-md-6">
		<h2 class="page-header">招标列表</h2>
	</div>
	<div class="col-md-6 text-right">
		  <a href="<?php echo Router::getBaseUrl() ?>castTender/" class="btn btn-success text-right">我的投标</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0" id="cascading">
		<form class="form-inline" action="<?php echo Router::getBaseUrl() ?>tender" method="get">
		  <div class="form-group">
			<label for="content">关键字</label>
			<input name="content" id="content" value="<?=$params['content'] ?>" class="form-control" />
		  </div>
		  <button type="submit" class="btn btn-info">搜索</button>
		</form>
	</div>
</div>

<hr/>

<!--<h2 class="sub-header">Section title</h2>-->
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
			    <th style="display:none">ID</th>
				<th>序号</th>
				<th>招标方</th>
				<th>招标信息</th>
				<th>交割地</th>
				<th>结束时间</th>
				<th>发布时间</th>
				<th>说明</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( $tenderList ) { ?>
			<?php foreach ( $tenderList as $key=>$data ) { ?>
				<tr>
				    <td style="display:none"><?php echo $data['id']; ?></td>
					<td><?php echo $key+1+($page-1)*$numPerPage; ?></td>
					<td><?php echo $data['user_name'] ?></td>
					<td><?php echo $data['content'] ?></td>
					<td><?php echo $data['delivery_point'] ?></td>
					<td><?php echo date('Y-m-d',strtotime($data['past_timestamp'])) ?></td>
					<td><?php echo $data['add_timestamp'] ?></td>
					<td><?php echo $data['info'] ?></td>
					<td>
					   <a id="tender-bid-btn" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">投标</a>
					</td>
				</tr>
			<?php } ?>
			     <tr class="text-center"> 
    				<td colspan="3" style="vertical-align:middle;">
    				    <input type="hidden" id="tender-current-page" value="<?= $page ?>"/>
                        <a id="export-trender-page" href="#" class="btn btn-default">导出当前页</a>
                        &nbsp;&nbsp;
                        <a id="export-trender-all" href="#" class="btn btn-default">导出全部</a>
                    </td>
					<td colspan="6" align="left">
						<?= $pager_html ?>
					</td>
				</tr>
			<?php } else { ?>
				<tr>
					<td colspan="9">
					   <div class="alert alert-info" role="alert"><p class="text-center">无记录</p></div>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/tender.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(".form_datetime").datetimepicker({
	language:  'zh-CN',
	format: 'yyyy-mm-dd hh:ii'
});
</script> 
