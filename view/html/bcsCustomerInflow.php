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
            <span style="padding-right: 2px;"><?php echo $item['TRANS_TYPE'];?></span>
            <span style="padding-right: 2px;"><?php if($item['TRANS_STATE'] == 1){echo '成功';}else if($item['TRANS_STATE'] == 2){echo '失败';}else if($item['TRANS_STATE'] == 3){echo '状态未知';}else{echo '已冲正';} ?></span>
            <span style="padding-right: 2px;"><?php echo $item['TRANS_AMT']; ?></span>
            <span style="padding-right: 2px;"><?php echo $item['TRANS_TIME']; ?></span>
            <span style="padding-right: 2px;"><?php echo $item['PAYMENT_NO']; ?></span>
            <span style="padding-right: 2px;"><?php echo is_array($item['PAYMENT_NAME']) ? '-' : $item['PAYMENT_NAME']; ?></span>
            <span style="padding-right: 2px;"><?php echo is_array($item['MBR_BANK_NAME']) ? '-' : $item['MBR_BANK_NAME']; ?></span>
            <span style="padding-right: 2px;"><?php echo is_array($item['MBR_BANK_NO']) ? '-' : $item['MBR_BANK_NO']; ?></span>
            <span style="padding-right: 2px;"><?php echo $item['PROCEEDS_NO']; ?></span>
            <span style="padding-right: 2px;"><?php echo $item['PROCEEDS_NAME']; ?></span>
            <span style="padding-right: 2px;"><?php echo $item['BUSI_DATE']; ?></span>
            <span style="padding-right: 2px;"><?php echo is_array($item['RMRK']) ? '-' : $item['RMRK']; ?></span>
        </div>
    <?php }?>
</div>

