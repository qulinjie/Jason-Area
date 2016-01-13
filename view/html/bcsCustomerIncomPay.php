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
            <span style="padding-right: 20px;"><?php echo $item['CTRT_NO']; ?></span>
            <span style="padding-right: 20px;"><?php echo $item['MCH_NO']; ?></span>
            <span style="padding-right: 20px;"><?php echo $item['MCH_TRANS_NO']; ?></span>
            <span style="padding-right: 20px;"><?php echo $item['BUSI_DATE']; ?></span>
            <span style="padding-right: 20px;"><?php echo $item['TRANS_TIME']; ?></span>
            <span style="padding-right: 20px;"><?php if($item['TRANS_STS'] == 1){echo '交易成功';}else if($item['TRANS_STS'] == 2){echo '交易失败';}else{echo '状态未知';} ; ?></span>
            <span style="padding-right: 20px;"><?php if($item['PAY_TYPE '] == 1){echo '部分付款';}else if($item['PAY_TYPE '] == 2){echo '完结付款';}else{echo '违约付款';} ; ?></span>
            <span style="padding-right: 20px;"><?php echo $item['TX_AMT'].'元'; ?></span>
        </div>
    <?php }?>
</div>

