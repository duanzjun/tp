企业管理系统
目前还在初步开发阶段，不是一个完善的项目，请勿下载

标准化
使用jquery.js做为js封装插件
后台弹窗统一使用artDialog插件完成，依赖于jquery
编辑器目前使用百度编辑器（暂定）
css样式使用目前最流行的bootstrap3.0做为样式框架
后台共用js函数文件common.js
后台css函数使用admin.css
表单提交弹窗ajax.form.js,可阻止ajax表单提交自动跳转
公共函数文件app/common/common/function.php


后台模板结构
<div class="container-fluid">
	<!---操作管理---->
	<div class="mg-btm10"></a>
</div>

表格样式 <table class="table table-bordered table-hover">



模板测试选中状态
<volist name="role" id="r">
<eq name="category.is_active" value="$txt['key']">checked="checked"</eq>
</volist>

编辑/添加共用模板，取当前路径用于提交表单请求
action="__ACTION__"

ajax请求数据确认
<a class="confirmurl" data-msg="你确认需要删除吗" data-uri="index.php?a=del">删除</a>
返回数据为 $this->ajaxReturn(true/false)

复选框选中或取消
<input type="checkbox" onclick="$('input[name*=\'checked\']').prop('checked',this.checked);" />

