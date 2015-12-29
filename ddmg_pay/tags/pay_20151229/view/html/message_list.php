<?php
/**
 * message_list.php
 * 	
 */
?>

<?php if(empty($data_list)){?>
<div class="alert alert-info" role="alert"><p class="text-center">
	无消息提醒
</p></div>
<?php }else{?>
<div class="panel panel-default">
<div class="panel-heading" id="search-head-div">消息列表</div>
  <div class="panel-body">
		<table class="table table-hover" id="data-list-table">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>内容</th>
					<th>时间</th>
					<th id="th-operation-id">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data_list as $item){?>
				<tr>
					<td style="display:none"><?php echo $item['id'];?></td>
					<td><?php echo $conf[$item['number']]['msg'];?></td>
					<td><?php echo $item['add_timestamp'];?></td>
					<td><?php if($conf[$item['number']]['link']){?><button class="btn btn-primary">查看</button><?php }?></td>
				</tr>
				<?php }?>
		</tbody>
		</table>
		<nav>
		  <ul class="pager" id="pager-ul">
		    <li><a id="message-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
		    <li><span id="message-current-page"><?php echo $current_page;?></span> /  <span id="message-total-page"><?php echo $total_page;?></span></li>
		    <li><a id="message-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
		    <li><select class="form-control input-sm " id="message-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		  </ul>
		</nav>
  </div>
</div>
<?php }?>