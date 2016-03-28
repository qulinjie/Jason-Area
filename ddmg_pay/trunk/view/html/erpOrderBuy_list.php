<?php
/**
 * tradeRecord_list.php
 * 	
 */
?>

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group" style="margin-left: 15px;">
	    <label for="entity-search-fphm">业务单号</label>
	    <input type="email" class="form-control" id="entity-search-fphm" placeholder="" value="<?php echo $params['fphm'];?>" />
	  </div>
	  <div class="form-group" style="margin-left: 15px;">
	    <label for="entity-search-dwmc">单位名称</label>
	    <input type="email" class="form-control" id="entity-search-dwmc" <?php echo $params['lock']?'readonly="readonly"':'';?> placeholder="" value="<?php echo $params['dwmc'];?>" />
	  </div>
	  <div class="form-group" style="margin-left: 15px;">
	    <label for="entity-search-time">日期</label>
	    <input type="email" class="form-control form_datetime" id="entity-search-time1" placeholder="" value="<?php echo $params['ksrq'];?>" />
	    -
	    <input type="email" class="form-control form_datetime" id="entity-search-time2" placeholder="" value="<?php echo $params['jzrq'];?>" />
	  </div>
	  <div class="form-group" style="margin-left: 15px;">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
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
  <div class="panel-body">
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="display:none">id</th>
					<th>操作</th>
					<th>业务单号</th>
					<th>单位名称</th>
					<th>日期</th>
					<th>金额（元）</th>
					<th>仓库</th>
					<th>业务员</th>
				</tr>
			</thead>
			<tbody>
    <?php foreach ($data_list as $item){?>       
       <tr>
            <td style="display:none"><?php echo $item['fphm_'];?><input type="hidden" value="<?php echo $item['order_status'];?>"></td>
            <td> 	      
	  			<a id="entity-search-select-<?php echo $item['fphm_'];?>" class="entity-search-select" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">选择</a>
	        </td>
            <td><a href="#" id="td_a_fphm"><?php echo $item['fphm_'];?></a></td>
            <td><?php echo $item['dwmc_'];?></td>
            <td><?php echo date("Y-m-d h:i:s",strtotime($item['rq_']));?></td>
            <td><?php echo number_format($item['_cgddje'],2);?></td>
            <td><?php echo $item['_ckmc'];?></td>
            <td><?php echo $item['_ywymc'];?></td>
       </tr>
    <?php }?>
		</tbody>
		</table>
		
		<nav>
          <ul class="pager" id="entity-pager-ul">
            <li><a id="entity-list-prev" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
            <li><span id="entity-current-page"><?php echo $current_page;?></span> /  <span id="entity-total-page"><?php echo $total_page;?></span></li>
            <li><a id="entity-list-next" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
            <li><select class="form-control input-sm " id="entity-custom-page" style="width: 60px;height:26px;display:inline;border-radius: 5px;"></select></li>
          </ul>
        </nav>
  </div>
</div>

<?php }?>