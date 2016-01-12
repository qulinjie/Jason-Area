<div class="right" id="bcsCustomerInflowList">
    <div class="class">
        <span style="padding-right: 85px;">订单号</span>
        <span style="padding-right: 85px;">商户编号</span>
        <span style="padding-right: 85px;">流水号</span>
        <span style="padding-right: 85px;">业务日期</span>
        <span style="padding-right: 85px;">交易时间</span>
        <span style="padding-right: 85px;">状态</span>
        <span style="padding-right: 85px;">类型</span>
        <span style="padding-right: 85px;">金额</span>
    </div>
    <?php foreach ($data['List'] as $key => $item){?>
        <div class="detail" <?php if($key%2){?>style="background:#f3f3f3;"<?php }?>>
            <span style="padding-right: 15px;"><?php echo $item['CTRT_NO']; ?></span>
            <span style="padding-right: 15px;"><?php echo $item['MCH_NO']; ?></span>
            <span style="padding-right: 15px;"><?php echo $item['MCH_TRANS_NO']; ?></span>
            <span style="padding-right: 15px;"><?php echo $item['BUSI_DATE']; ?></span>
            <span style="padding-right: 15px;"><?php echo $item['TRANS_TIME']; ?></span>
            <span style="padding-right: 15px;"><?php echo is_array($item['TRANS_STS']) ? '-' : $item['TRANS_STS']; ?></span>
            <span style="padding-right: 15px;"><?php echo is_array($item['PAY_TYPE ']) ? '-' : $item['PAY_TYPE']; ?></span>
            <span style="padding-right: 15px;"><?php echo is_array($item['TX_AMT']) ? '-' : $item['TX_AMT']; ?></span>
        </div>
    <?php }?>
</div>

