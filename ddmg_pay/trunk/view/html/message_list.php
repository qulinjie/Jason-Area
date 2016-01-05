<div class="right">
	<div class="class">
		<span class="nr">内容</span><span class="sj">时间</span><span class="caozuo">操作</span>
	</div>
	<?php foreach ($data_list as $key => $item){?>
	<div class="detail" <?php if($key%2){?>style="background:#f3f3f3;"<?php }?>>
		<span style="display:none"><?php echo $item['id'];?></span>
		<span class="nr"><?php echo $conf[$item['number']]['msg'];?></span>
		<span class="sj"><?php echo $item['add_timestamp'];?></span>
		<span class="caozuo"><a href="">查看</a></span>
	</div>
	<?php }?>
	<nav>
		<ul class="pager" id="pager-ul">
			<li><a id="message-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
			<li><span id="message-current-page"><?php echo $current_page;?></span> /  <span id="message-total-page"><?php echo $total_page;?></span></li>
			<li><a id="message-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
			<li><select class="form-control input-sm " id="message-custom-page" style="width: 60px;height:26px;display:inline;"></select></li>
		</ul>
	</nav>
</div>