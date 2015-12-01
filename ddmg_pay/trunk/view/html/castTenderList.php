<?php 
/**
 * 投标信息
 */
?>
<link href="<?php echo Router::getBaseUrl();?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">


<div class="row">
	<div id="tender-bid-title-div" class="col-md-6">
		<h2 class="page-header">投标列表</h2>
	</div>
	<div class="col-md-6 text-right">
		  <a href="<?php echo Router::getBaseUrl() ?>tender/" class="btn btn-success text-right">新招标列表</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0" id="cascading">
		<form class="form-inline" action="<?php echo Router::getBaseUrl() ?>castTender" method="get">
		  <div class="form-group">
			<label for="content">关键字</label>
			<input name="content" id="content" value="<?=$params['content'] ?>" class="form-control" />
		  </div>
		  &nbsp;&nbsp;
		  <div class="form-group">
    	    <label for="search-Status">投标状态</label>
    	    <select class="form-control" id="castTender-search-status" name="status">
    	       <option value="-1">全部</option>
               <option value="1" <?php if(CastTenderModel::$_cast_tender_status_hasbid == $params['status']) { ?> selected <?php } ?>>已投标</option>
               <option value="2" <?php if(CastTenderModel::$_cast_tender_status_winbid == $params['status']) { ?> selected <?php } ?>>中标</option>
               <option value="3" <?php if(CastTenderModel::$_cast_tender_status_expired == $params['status']) { ?> selected <?php } ?>>已过期</option>
            </select>
    	  </div>
    	  &nbsp;&nbsp;
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
				<th>招标方</th>
				<th>招标信息</th>
				<th>交割地</th>
				<th>结束时间</th>
				<th>发布时间</th>
				<th>说明</th>
				<th>投标状态</th>
				<th>投标信息</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( $castTenderList ) { ?>
			<?php foreach ( $castTenderList as $key=>$data ) { ?>
				<tr>
				    <td style="display:none"><?php echo $data['id']; ?></td>
					<td><?php echo $key+1+($page-1)*$numPerPage; ?></td>
					<td><?php echo $data['tender_user_name'] ?></td>
					<td><?php echo $data['tender_tel'] ?></td>
					<td><?php echo $data['content'] ?></td>
					<td><?php echo $data['delivery_point'] ?></td>
					<td><?php echo date('Y-m-d',strtotime($data['past_timestamp'])) ?></td>
					<td><?php echo $data['add_timestamp'] ?></td>
					<td><?php echo $data['info'] ?></td>
					<td>
					   <?php if(CastTenderModel::$_cast_tender_status_hasbid == $data['status']) { 
					           echo '已投标';
					       } else if(CastTenderModel::$_cast_tender_status_winbid == $data['status']) { 
					           echo '中标';
					       } else if(CastTenderModel::$_cast_tender_status_expired == $data['status']) { 
					           echo '已过期';
					       } 
					   ?>
					</td>
					<td><?php echo $data['comment'] ?></td>
				</tr>
			<?php } ?>
			     <tr class="text-center"> 
    				<td colspan="4" style="vertical-align:middle;">
    				    <input type="hidden" id="castTender-current-page" value="<?= $page ?>"/>
                        <a id="export-castTender-page" href="#" class="btn btn-default">导出当前页</a>
                        &nbsp;&nbsp;
                        <a id="export-castTender-all" href="#" class="btn btn-default">导出全部</a>
                    </td>
					<td colspan="8" align="left">
						<?= $pager_html ?>
					</td>
				</tr>
			<?php } else { ?>
				<tr>
					<td colspan="11">
					   <div class="alert alert-info" role="alert"><p class="text-center">无记录</p></div>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/castTender.js"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(".form_datetime").datetimepicker({
	language:  'zh-CN',
	format: 'yyyy-mm-dd hh:ii'
});
</script> 
