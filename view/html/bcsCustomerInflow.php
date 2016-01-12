<div class="right">
    <div class="class">
        <span>类型</span>
        <span>状态</span>
        <span>金额</span>
        <span>完成时间</span>
        <span>付款帐号</span>
        <span>付款户名</span>
        <span>收款行名</span>
        <span>收款行号</span>
        <span>收款帐号</span>
        <span>收款户名</span>
        <span>业务日期</span>
        <span>备注</span>
    </div>
    <?php foreach ($data['List'] as $key => $item){?>
        <div class="detail" <?php if($key%2){?>style="background:#f3f3f3;"<?php }?>>
            <span><?php echo $val['TRANS_TYPE']; ?></span>
            <span><?php echo $val['TRANS_STATE']; ?></span>
            <span><?php echo $val['TRANS_AMT']; ?></span>
            <span><?php echo $val['TRANS_TIME']; ?></span>
            <span><?php echo $val['PAYMENT_NO']; ?></span>
            <span><?php echo $val['PAYMENT_NAME']; ?></span>
            <span><?php echo $val['MBR_BANK_NAME']; ?></span>
            <span><?php echo $val['MBR_BANK_NO']; ?></span>
            <span><?php echo $val['PROCEEDS_NO']; ?></span>
            <span><?php echo $val['PROCEEDS_NAME']; ?></span>
            <span><?php echo $val['BUSI_DATE']; ?></span>
            <span><?php echo $val['RMRK']; ?></span>
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

