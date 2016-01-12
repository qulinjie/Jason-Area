<div class="right" id="bcsCustomerInflowList">
    <div class="class">
        <span style="padding-right: 35px;">类型</span>
        <span style="padding-right: 35px;">状态</span>
        <span style="padding-right: 35px;">金额</span>
        <span style="padding-right: 35px;">完成时间</span>
        <span style="padding-right: 35px;">付款帐号</span>
        <span style="padding-right: 35px;">付款户名</span>
        <span style="padding-right: 35px;">收款行名</span>
        <span style="padding-right: 35px;">收款行号</span>
        <span style="padding-right: 35px;">收款帐号</span>
        <span style="padding-right: 35px;">收款户名</span>
        <span style="padding-right: 35px;">业务日期</span>
        <span style="padding-right: 35px;">备注</span>
    </div>
    <?php foreach ($data['List'] as $key => $item){?>
        <div class="detail" <?php if($key%2){?>style="background:#f3f3f3;"<?php }?>>
            <span style="padding-right: 8px;"><?php echo $item['TRANS_TYPE']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['TRANS_STATE']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['TRANS_AMT']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['TRANS_TIME']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['PAYMENT_NO']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['PAYMENT_NAME']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['MBR_BANK_NAME']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['MBR_BANK_NO']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['PROCEEDS_NO']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['PROCEEDS_NAME']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['BUSI_DATE']; ?></span>
            <span style="padding-right: 8px;"><?php echo $item['RMRK']; ?></span>
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

