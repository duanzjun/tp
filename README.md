企业管理系统
目前还在初步开发阶段，不是一个完善的项目，请勿下载

标准化
使用jquery.js做为js封装插件
后台弹窗统一使用artDialog v6.0.2插件完成，依赖于jquery v1.11.1
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
<volist name="radio_txt" id="txt">
<eq name="category.is_active" value="$txt['key']">checked="checked"</eq>
</volist>

编辑/添加共用模板，取当前路径用于提交表单请求
action="__ACTION__"

ajax请求数据确认
<a class="confirmurl" data-msg="你确认需要删除吗" data-uri="index.php?a=del">删除</a>
返回数据为 $this->ajaxReturn(true/false)

复选框选中或取消
<input type="checkbox" onclick="$('input[name*=\'checked\']').prop('checked',this.checked);" />

图片上传使用ajaxUpload无刷新上传图片
<span class="btn btn-success fileinput-button">
    <i class="glyphicon glyphicon-picture"></i>
	<span>上传图片</span>
	<input type="file" id="fileupload" multiple name="files[]" tabIndex="-1">
</span>
multiple 多图片上传
tabIndex="-1" 获取焦点会导致文字往上偏移bug，禁止上传按钮获取焦点
数据返回处理
$('#fileupload').fileupload({
        url: "{:U('adposition/adupload')}",
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                var str='<li><a href="'+file.url+'" target="_blank"><img src="'+file.url+'" /></a>'+
                '<input type="hidden" name="ad_img[]" value="'+file.url+'" />'+
                '<input type="hidden" name="ad_del[]" value="'+file.deleteUrl+'" />'+
                '<input class="form-control" style="display:inline" type="text" name="ad_url[]"/>'+
                '<a class="delete" data-type="DELETE" data-url="'+file.deleteUrl+'" href="javascript:void(0);">删除</a></li>';
                $('.ad-list').append(str);
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
后台上传附件统一调用adminController.class.php@ajaxUpload(),附件数据统一存放在attachment表里



判断数据不为空可以使用标签
<notempty name="list"><input type="hidden" name="id" value="{$list.ad_id}" /></notempty>
