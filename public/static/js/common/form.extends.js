$(document).ready(function(){
	$(".order-btn").click(function(){
			var a = this.href || this.rel || this.alt;
	
			$.loading("数据更新中");
			$.ajax({
				url : a,
				dataType:'json',
				success: function(p_msg){
					$.remove_loading();
					if(p_msg.status == 0){
						$.warn(p_msg.info);
					}else{
						$.success(p_msg.info, 'reload');
					}
				},
				error:function(){
					$.remove_loading();
					$.warn("系统错误");
				}
			});
			
			return false;
		});
	
});
/**
 * ajax自动上传图片
 */
function doUpload(u) {
    var formData = new FormData($("#form_init")[0]);
    $.ajax({  
         url: u,
         type: 'POST',
         data: formData,
         async: false,
         cache: false,  
         contentType: false,
         processData: false,
         beforeSend : function(){
        	 $("#img-loading").remove();
        	 $("#uploadify-container-host_record_image").append("<span id='img-loading' class='span_text'>图片上传中...<span>");
		},
         success: function (data) {
            if(data.status == 1){
            	var im = $("#uploadify-host_record_image").val();
            	var str;
            	if(im){
            		str = im+","+data.info.id;
            	}else{
            		str = data.info.id;
            	}            	
           		//$("#uploadify-container-host_record_image").append('<span class="uploadify-fileItms" id="uploadify-item-'+data.info.id+'"><a href="'+data.info.path+'" target="_blank" class="lightbox"><img src="'+data.info.path+'" width="'+data.info.pos.width+'" height="'+data.info.pos.height+'"></a></span>');
            	
            	$("#uploadify-host_record_image").val(str);
            	$("#img-loading").remove();
           		$("#uploadify-container-host_record_image").append('<span class="uploadify-fileItms" id="uploadify-item-'+data.info.id+'"><span class="del-item" onclick="del_image(this);"></span><a href="'+data.info.path+'" target="_blank" class="lightbox"><img src="'+data.info.path+'" width="100" height="100"></a></span>');
            }else{
            	$.error(data.info, data.url ,data.wait);
            }
         },
         error: function (data){
        	 $("#img-loading").remove();
        	 $("#uploadify-container-host_record_image").append("<span id='img-loading' class='span_text'>图片上传失败！<span>");
        	 $.error(data.info, data.url ,data.wait);
         }
    }); 
}

//删除图片
function del_image(obj){
	var idlist = $(obj).parent().attr('id');
	var old = $("#uploadify-host_record_image").val();
	var v = '';
	
	var id = idlist.replace(/uploadify-item-/i, "");
	
	if(old.indexOf(",") > 0){
		var list= new Array();
		list = old.split(",");
		for(i=0;i<list.length;i++){
			if(id != list[i]){
				v += list[i]+",";
			}
		}
		v = v.substr(0, v.length-1);
	}else{
		if(id == old){
			v = '';
		}else{
			v = old;
		}
		
	}
	$("#uploadify-host_record_image").val(v);

	$(obj).parent().remove();
}

//Jquery常用扩展库，扩展jQuery对象本身，在jQuery命名空间上增加新函数，如：$.match_data(value, name)
jQuery.extend({
	_items : null,
	_form : null,
	_icos : {'0':'&#x22;','1':'&#x24;','2':'&#x23;','3':'&#x21;'},
	_submiting : false,
	
	CODE_SUCCESS	: 1,
	CODE_ERROR		: 0,
	CODE_WARN		: 2,
	CODE_INFO		: 3,
	
	//提交表单
	submitform : function(form){
		this._form = form;
		this._items = $(form).find(".unique");
		if(this._items.length > 0){
			this._chkunique(0);
		}else{
			this.toAjaxForm();
		}
	},
	//获得浮点数
	_get_float_value : function(value){
		if($.match_data(value, 'double')){
			value = value.substr(0, 1) == '-' ? -parseFloat(value.substr(1)) : parseFloat(value);
		}
		
		return value;
	},
	
	//ajax提交表单
	toAjaxForm : function(){
		if($("#form_init").hasClass("toAjaxForm")){
			var type = $("#form_init").attr('enctype') ? $("#form_init").attr('enctype') : "application/x-www-form-urlencoded";
			if($._submiting){
				alert('网络不给力，数据还在处理中，请勿进行重复提交');
				return false;
			}
			$.ajax({
				url : $("#form_init").attr("action"),
				type : "POST",
				dataType : "json",
				data : $("#form_init").serialize(),
//		        async: false,
//		        cache: false,
//		        contentType: type,
//		        processData: false,
				beforeSend : function(){
					$.loading("数据正在提交，请稍候...");
					$._submiting = true;
				},
				success : function(dat){
					$.remove_loading();
					$._submiting = false;
					$.msgbox(dat.info, dat.status, dat.url ,dat.wait);
				},
				error : function(){
					$.remove_loading();
					$._submiting = false;
					$.msgbox('系统出错，数据提交失败',this.CODE_ERROR);
				}
			});
		}else{
			$("#form_init").submit();
		}
	},
	
	showmsg : function(message, url , wait){
		if(typeof message == "object"){
			var len = message.length;
			var _msg = new Array;
			for(var i=0;i<len;i++){
				_msg[i] = $(message[i]).text();
			}
			
			message = _msg;
		}
		
		$.error(message, url , wait);
	},
	
	//检查数据的唯一性
	_chkunique : function(idx){
		idx = parseInt(idx);
		if(idx >= this._items.length - 1){
			this.toAjaxForm();
			return true;
		}
		
		var $this = $(this._items[idx]);
		var params = $this._getParams();
		var  url = params.url != "" ? params.url : ($(this._form).action ? $(this._form).action : location.href);
		var _value = $this.get_value();
		var title = $this._getTitle();
		$.ajax({
			url : url,
			data : 'field='+$this.attr('name')+"&value="+_value+"&unique=true"+params.data,
			dataType : 'json',
			type : 'POST',
			beforeSend : function(){
				$.loading("数据正在验证中，请稍候...");
			},
			success : function(dat){
				$.remove_loading();
				if(dat){
					if(dat.status == $.CODE_SUCCESS){
						$this._msg('OK!','success');
						$._chkunique(idx + 1);
					}else if(dat.status == $.CODE_ERROR){
						$this._msg("该"+title+'已存在','error');
						$.showmsg("该"+title+'“'+_value+'”已存在');
					}else{
						$this._msg(dat.info, 'warn');
						$.showmsg(dat.info);
					}
				}else{
					$.warn("系统异常，请求验证失败");
				}
			},
			error : function(){
				$.remove_loading();
				$.warn("系统异常，操作失败");
			}
		});
	},
	
	error : function(data ,url , wait){
		this.msgbox(data, this.CODE_ERROR ,url , wait);
	},
	
	warn : function(data ,url , wait){
		this.msgbox(data, this.CODE_WARN ,url , wait);
	},
	
	success : function(data ,url , wait){
		this.msgbox(data, this.CODE_SUCCESS ,url , wait);
	},
	//消息弹出框
	msgbox : function(data, code ,url , wait){
		var message = '';
		if(typeof data == "object"){
			for(var i in data){
				message += '<p>' + data[i] + '</p>';
			}
		}else{
			message = data;
		}
		
		if(typeof url == 'function'){
			var footer = '<span id="MSGBOX-OKBTN" class="ui-btn ui-btn-green">确 定</span>';
			if(wait!== false){
				footer += ' <span id="MSGBOX-CANCLE" class="ui-btn">取 消</span>';
			}
		}else if(url === false){
			var footer = '<span id="MSGBOX-CANCLE" class="ui-btn ui-btn-green">确 定</span>';
		}else if(url === 'reload'){
			var footer = '<span id="MSGBOX-CANCLE" class="ui-btn ui-btn-green" onclick="window.location.reload();">确 定</span>';
		}else if(url == 'domain_on_off'){

			var footer = '<span onclick="submit_click()" class="ui-btn ui-btn-green">提交</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			    footer += '<span id="MSGBOX-OKBTN" class="ui-btn ui-btn-green">取消</span>';

		}else{
			url = url ? url : document.location.href;
			wait = wait > 0 ? wait : 3;
			var footer = code == this.CODE_SUCCESS ? '系统 <b id="Alert_Wait">'+wait+'</b> 秒后将自动跳转， 不想等待请<a href="'+url+'">点击这里</a>' : '<span id="MSGBOX-CANCLE" class="ui-btn ui-btn-green">关 闭</span>';
		}
		//元素不存在
		if($("#Alert_Containter").length <= 0){
			var html = '<div class="Alert_Background"></div>' +
						 '<div class="Alert_Content">' + 
							'<div class="In_Content">' +
								'<div class="header header_t_' + code + '"><i class="glyphicon '+ (code!==1?"glyphicon-remove-sign":"glyphicon-ok-sign") +'"></i>信息提示</div>' +
								'<div class="container_alert">' +
									'<div id="Alert_Containter" class="color_' + code + '"></div><div class="footer_alert">' + footer + '</div>' +
								'</div>' +
							'</div>' +
						'</div>';
			$("body").append(html);
		}
		$(".Alert_Content .footer").html(footer);
		//信息填充
		$("#Alert_Containter").html(message);
		$(".Alert_Content .In_Content").css("marginTop",-($(".Alert_Content .In_Content").outerHeight()/2)+"px");
		
		$("#MSGBOX-OKBTN").length<=0 | $("#MSGBOX-OKBTN").unbind().click(function(){
			$._closeMsgbox();
			eval(url());
		});
		
		$("#MSGBOX-CANCLE").length<=0 | $("#MSGBOX-CANCLE").unbind().click(function(){
			$._closeMsgbox();
			typeof wait == 'function' ? eval(wait()) : '';
		});
		
		if(code == this.CODE_SUCCESS && typeof wait != 'function' && typeof url != 'function'){
			this._autofunc(url);
		}
	},
	
	_closeMsgbox : function(){
		$('.Alert_Content').remove();
		$('.Alert_Background').fadeOut(function(){
			$(this).remove();
		});
	},
	
	_autofunc : function(url){
		setTimeout(function(){
			var s = parseInt($("#Alert_Wait").text());
			if(s < 1){
				s = 1;
			}
			$("#Alert_Wait").html(''+(s-1));
			if(s == 1) document.location.href = url;
			$._autofunc(url);
		},1000);
	}
});
