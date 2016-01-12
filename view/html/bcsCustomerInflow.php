<div class="right">
    <h1 class="page-header">出入金明细列表</h1>
    <table class="table table-hover">
        <thead style="font: bold 12px 微软雅黑;color: #8b8b8b;line-height: 40px;">
        <tr>
            <th>类型</th>
            <th>状态</th>
            <th>金额</th>
            <th>完成时间</th>
            <th>付款帐号</th>
            <th>付款户名</th>
            <th>收款行名</th>
            <th>收款行号</th>
            <th>收款帐号</th>
            <th>收款户名</th>
            <th>业务日期</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['List'] as $val) { ?>
            <tr>
                <td><?php echo $val['TRANS_TYPE']; ?></td>
                <td><?php echo $val['TRANS_STATE']; ?></td>
                <td><?php echo $val['TRANS_AMT']; ?></td>
                <td><?php echo $val['TRANS_TIME']; ?></td>
                <td><?php echo $val['PAYMENT_NO']; ?></td>
                <td><?php echo $val['PAYMENT_NAME']; ?></td>
                <td><?php echo $val['MBR_BANK_NAME']; ?></td>
                <td><?php echo $val['MBR_BANK_NO']; ?></td>
                <td><?php echo $val['PROCEEDS_NO']; ?></td>
                <td><?php echo $val['PROCEEDS_NAME']; ?></td>
                <td><?php echo $val['BUSI_DATE']; ?></td>
                <td><?php echo $val['RMRK']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
