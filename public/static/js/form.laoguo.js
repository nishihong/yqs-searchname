$(document).ready(function(){

        $(".laoguo_form :input.required").each(function(){
            //var $required = $("<strong style='display:none;' class='high'> *</strong>"); //创建元素
            //$(this).parent().append($required); //然后将它追加到文档中
        });

         //文本框失去焦点后
        $('.laoguo_form :input').blur(function(){
             var $parent = $(this).parent();
             $parent.find(".formtips").remove();
             //验证用户名
             if( $(this).is('#username') ){
                    if( this.value=="" || this.value.length < 6 ){
                        var errorMsg = '请输入至少6位的用户名.';
                        $parent.append('<span class="formtips onError">'+errorMsg+'</span>');
                    }else{
                        var okMsg = '输入正确.';
                        $parent.append('<span class="formtips onSuccess">'+okMsg+'</span>');
                    }
             }

             //真实姓名
             if( $(this).is('.required') ){
                    if( this.value==""){
                        var errorMsg = '不能为空';
                        $parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                    }else{
                        var okMsg = 'ok';
                        $parent.find(".DESC").html('<span class="formtips onSuccess">'+okMsg+'</span>');
                    }
             }

             //验证邮件
             if( $(this).is('.email') ){
                if( this.value=="" || ( this.value!="" && !/.+@.+\.[a-zA-Z]{2,4}$/.test(this.value) ) ){
                      var errorMsg = '请输入正确的E-Mail地址';
                      $parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                }else{
                      var okMsg = 'ok';
                      $parent.find(".DESC").html('<span class="formtips onSuccess">'+okMsg+'</span>');
                }
             }

              //验证数字和小数点
             if( $(this).is('.double') ){
                if( this.value=="" || ( this.value!="" && !/^\d+(\.\d+)?$/.test(this.value) ) ){
                      var errorMsg = '请输入整数或者小数';
                      $parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                }else{
                      var okMsg = 'ok';
                      $parent.find(".DESC").html('<span class="formtips onSuccess">'+okMsg+'</span>');
                }
             }

             //手机号
             if( $(this).is('.mobile') ){
                if( this.value=="" || ( this.value!="" && !/^((\(\d{2,3}\))|(\d{3}\-))?(13|15|18|14|17)\d{9}$/.test(this.value) ) ){
                      var errorMsg = '手机号格式错误';
                      $parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                }else{
                      var okMsg = 'ok';
                      $parent.find(".DESC").html('<span class="formtips onSuccess">'+okMsg+'</span>');
                }
             }

             //密码
             if( $(this).is('.password') ){
            	
                if( this.value != "" ){
                	if(!/^.{6,20}$/.test(this.value)){
                		var errorMsg = '密码必须在6到20位之间';
                		$parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                	}else if(!/^([0-9]+[a-zA-Z]+)|([a-zA-Z][0-9]+)|(([a-zA-Z]+|[0-9]+)[^A-Za-z0-9]+)|([^A-Za-z0-9]+([a-zA-Z]+|[0-9]+)).*$/.test(this.value)){               		
                		var errorMsg = '密码格式有误';
                		$parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                	}else{                    	
                        var okMsg = 'ok';
                        $parent.find(".DESC").html('<span class="formtips onSuccess">'+okMsg+'</span>');
                	}
                	                	
                }else{
                	var errorMsg = '密码不能为空';
                	$parent.find(".DESC").html('<span class="formtips onError">'+errorMsg+'</span>');
                }
             }

        }).keyup(function(){
           $(this).triggerHandler("blur");
        }).focus(function(){
             //$(this).triggerHandler("blur");
        });//end blur

        
        //提交，最终验证。
         $('.send_1').click(function(){
                $(this).parents('.laoguo_form').find(":input.required").trigger('blur');
                var numError =  $(this).parents('.laoguo_form').find('.onError').length;
                if(numError){
                    return false;
                }
                $('.closed').show();
                //alert("注册成功,密码已发到你的邮箱,请查收.");
         });
        
        //重置
         $('#res').click(function(){
                $(".formtips").remove(); 
         });

});
