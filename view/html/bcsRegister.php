<h1 class="page-header">绑定银行卡</h1>
<div class="panel panel-primary" style="width:1100px;">
    <div class="panel-body">
        <form class="form-horizontal" id="bcsRegister">
            <div class="form-group">
                <label for="certType"  class="control-label col-lg-2">客户证件类型</label>
                <div class="col-lg-2">
                    <select class="form-control" id="certType" name="certType">
                        <option value="">请选择</option>
                        <option value="01">身份证</option>
                        <option value="20">营业执照</option>
                        <option value="21" selected>组织机构代码</option>
                    </select>
                </div>
                <label for="certNo"  class="control-label col-lg-2">客户证件号码</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="certNo" name="certNo" value="9800008107" placeholder="客户证件号码">
                </div>
            </div>

            <div class="form-group">
                <label for="custName"  class="control-label col-lg-2">客户名称</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custName" name="custName" value="湖南省领测试" placeholder="客户名称">
                </div>
                <label for="custAcctName"  class="control-label col-lg-2">客户账户名</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custAcctName" name="custAcctName" value="湖南省领测试" placeholder="客户账户名">
                </div>
            </div>

            <div class="form-group">
                <label for="custSpeAcctNo"  class="control-label col-lg-2">客户结算账户</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custSpeAcctNo" name="custSpeAcctNo" value="800052170901011" placeholder="客户结算账户">
                </div>
                <label for="custAcctBkType"  class="control-label col-lg-2">客户结算账户行别</label>
                <div class="col-lg-2">
                    <select class="form-control" id="custAcctBkType" name="custAcctBkType">
                        <option value="">请选择</option>
                        <option value="0" selected>长沙银行</option>
                        <option value="1">非长沙银行</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="enableEcds"  class="control-label col-lg-2">是否开通电票</label>
                <div class="col-lg-2">
                    <select class="form-control" id="enableEcds" name="enableEcds">
                        <option value="">请选择</option>
                        <option value="1" selected>是</option>
                        <option value="0">否</option>
                    </select>
                </div>
                <label for="isPerson"  class="control-label col-lg-2">是否个人</label>
                <div class="col-lg-2">
                    <select class="form-control" id="isPerson" name="isPerson">
                        <option value="">请选择</option>
                        <option value="1">是</option>
                        <option value="0" selected>否</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="custPhoneNum"  class="control-label col-lg-2">客户手机号码</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custPhoneNum" name="custPhoneNum" placeholder="客户手机号码(可不填)" value="13265431549" />
                </div>
                <label for="custTeleNum"  class="control-label col-lg-2">客户电话号码</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custTeleNum" name="custTeleNum" placeholder="客户电话号码(可不填)" value="13265431549" />
                </div>
            </div>

            <div class="form-group">
                <label for="custAddress"  class="control-label col-lg-2">客户地址</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custAddress" name="custAddress" placeholder="客户地址(可不填)" value="深圳科技" />
                </div>
                <label for="custMark"  class="control-label col-lg-2">备注</label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="custMark" name="custMark" placeholder="备注(可不填)" value="大汉测试" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-2">
                    <button type="button" class="btn btn-primary form-control" id="bcsRegisterSaveBtn">绑定</button>
                </div>
            </div>
            <div class="alert alert-danger  search-list-hint" id="setPayPasswordMsg"></div>
        </form>
    </div>
</div>
