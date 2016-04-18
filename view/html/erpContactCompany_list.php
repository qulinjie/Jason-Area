
<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <input type="hidden" id="entity-search-referer" placeholder="" value="<?php echo $referer?>" />
      <div class="form-group" style="margin-left: 15px;">
	    <label for="entity-search-fphm">单位代码</label>
	    <input type="email" class="form-control" id="entity-search-dwdm" placeholder="" value="<?php echo $params['dwdm'];?>" />
	  </div>
	  <div class="form-group" style="margin-left: 15px;">
	    <label for="entity-search-dwmc">单位名称</label>	    
	    <input type="email" class="form-control" id="entity-search-dwmc" placeholder="" value="<?php echo $params['dwmc'];?>" style="width: 320px;"/>
	  </div>
	  
	  <div class="form-group" style="margin-left: 15px;">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn-contact">查询</button>
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
					<th>单位代码</th>
					<th>单位名称</th>	
					<th>地址</th>				
				</tr>
			</thead>
			<tbody>
	    <?php foreach ($data_list as $item){?>       
	       <tr>
	            <td> 
	            	<input type="hidden" id="entity-select-dwmc-<?php echo $item['dwdm'];?>" value="<?php echo $item['dwmc'];?>" />	      
		  			<a id="entity-select-dwdm-<?php echo $item['dwdm'];?>" class="<?php if($referer == 1){echo "entity-select-comp";}elseif($referer == 2){echo "entity-select-buyer";}?>" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">选择</a>
		        </td>
	            <td><?php echo $item['dwdm'];?></td>
	            <td><?php echo $item['dwmc'];?></td> 
	            <td><?php echo $item['kpaddr'];?></td>                   
	       </tr>
	    <?php }?>
		</tbody>
		</table>
		
		<nav>
          <ul class="pager" id="entity-pager-ul-contact">
            <li><a id="entity-list-prev-contact" href="#" <?php if($current_page == 1){?> class="disabled" <?php }?>>上一页</a></li>
            <li><span id="entity-current-page-contact"><?php echo $current_page;?></span> /  <span id="entity-total-page-contact"><?php echo $total_page;?></span></li>
            <li><a id="entity-list-next-contact" href="#" <?php if($current_page == $total_page){?> class="disabled" <?php }?>>下一页</a></li>
            <li><select class="form-control input-sm " id="entity-custom-page-contact" style="width: 60px;height:26px;display:inline;border-radius: 5px;"></select></li>
          </ul>
        </nav>
  </div>
</div>

<?php }?>