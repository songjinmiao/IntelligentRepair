<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<script src="{{asset('js/jquery.js') }}" ></script>
		<script src="{{asset('js/jquery-3.2.1.min.js') }}"></script>
		<script src="{{asset('js/ajaxfileupload.js') }}" ></script>
		<script src="{{asset('js/mui.min.js') }}"></script>
		<link href="{{asset('css/mui.min.css') }}" rel="stylesheet">
		<script type="text/javascript">
			mui.init()
		</script>
	</head>

	<body>

	    <header class="mui-bar mui-bar-nav" style="background-color: rgba(254, 201, 16, 1);">
	      <a class="mui-icon mui-icon-left-nav mui-pull-left mui-action-back" style="color: #FFFFFF;"></a>
	      <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修</b></h1>
	    </header>
	    <!-- 主页面内容容器 -->
	    <div class="mui-content">
			
	        <div class="mui-card">
	        	<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">任务信息</div>
				<ul id="renwu" class="mui-table-view">
					<!--任务模板-->
					<li class="mui-table-view-cell" >
						<img src="" style="width: 100%;"/>
					</li>
					<li class="mui-table-view-cell"  >
						<form  class="mui-input-group" style="background-color: rgba(255, 255, 255, 0);">
							<div id="allInformation">

							</div>
							<div id="changeButton">
								<div class="mui-button-row" >
									<button id="edit" type="button" class="mui-btn mui-btn-yellow" style="width:200px;background-color: rgba(254, 201, 16, 1);" >修改报修信息</button>
									<button id="InfoPost" type="button" class="mui-btn mui-btn-yellow" data-loading-text="提交中" style="display: none;width:200px;background-color: rgba(254, 201, 16, 1);" >确认提交</button>
								</div>
								<div class="mui-button-row" >
									<button id="delete" type="button" class="mui-btn mui-btn-danger" data-loading-text="删除中	" style="width:200px;" >删除报修信息</button>
									<button id="reset" type="button" onclick="resetbutton()" class="mui-btn mui-btn-yellow" style="display: none;width:200px;background-color: rgba(254, 201, 16, 1);" >取消更改</button>
								</div>
							</div>
						</form>
					</li>
					<!--任务模板-->
				</ul>
			</div>
			
			</div>

</body>
<script>
	//	需要ajax的地方：
	//	1.修改报修信息，确认提交 要把所有的信息用ajax发送
	//  2.删除报修信息，发送任务的ID
	$(document).ready(function(){
		showTaskInformation();
	});

	var allName=['大区域','系','建筑名称','楼层','房间号','物品','详细型号','报修说明','报修时间'];//所有提示信息
	var allValue=['large_area_name','part_area_name','building','floor','room','item','description','details','created_at'];//所有后台传过来的值的key
	var taskId=document.cookie.substring(0,document.cookie.length).split("taskId=")[1].split(";")[0];//从cookie获取taskId
	console.log(taskId);
	//显示任务详细信息
	function showTaskInformation(){
		$.ajax({
			url:'{{asset('/findTaskAllInformation')}}',
			async:false,
			type:"POST",
			data:{taskId:taskId},
			dataType:"json",
			success:function(data){
				console.log(data);
				if(data['result']==1){
					var information ='';//将构建的html代码存入此变量
					for(var k =0 ;k<allValue.length;k+=1){//循环，构建html代码
						var content = data['data'][allValue[k]] ;//通过allValue取得的后台的值如：教学区、桌子等等
						 information += '<div class="mui-input-row">\
											<label>'+allName[k]+'</label>\
											<input id= '+allValue[k]+'  type="text" class="mui-input-clear" value="'+content+'" readonly="readonly">\
										</div>';
					}
					if(data['state']!=0){
						$('#changeButton').html('<button type="button" class="mui-btn mui-btn-yellow" style="width:250px;margin-left:5%;background-color: rgba(254, 201, 16, 1);" >该任务已经进行，无法修改或删除</button>');
					}
					$('#allInformation').html(information);//将构建的代码植入相应div中
				}else{
					$('#allInformation').html('该任务已经被删除');
				}
			},
			error:function (data){
				console.log('getUserInformationError');
			}
		})
	}
	//事件函数：当编辑按钮被点击，表单变为可编辑状态
	$("#edit").click(function(){
		for(var i =0; i<allValue.length;i+=1){
			$("#"+allValue[i]+"").removeAttr("readonly");
		}
		$("#edit").hide();
		$("#delete").hide();
		$("#InfoPost").show();
		$("#reset").show();
	});
	
	//函数功能：将表单和按钮重置为初始状态
	function resetbutton(){
		for(var i =0; i<allValue.length;i+=1){
			$("#"+allValue[i]+"").attr("readonly","readonly");
		}
		$("#edit").show();
		$("#delete").show();
		$("#InfoPost").hide();
		$("#reset").hide();
	}


//事件函数：当提交按钮被点击，提交表单
	$("#InfoPost").click(function(){
		mui(this).button('loading');
		$("#reset").hide();
    	mui.ajax('{{asset('/taskUserChange')}}',{
			data:{
				large_area_name:$("#large_area_name").val(),
				part_area_name:$("#part_area_name").val(),
				building:$("#building").val(),
				floor:$("#floor").val(),
				room:$("#room").val(),
				item:$("#item").val(),
				description:$("#description").val(),
				details:$("#details").val(),
				taskId:taskId//从cookie获取taskId
			},
			dataType:'json',//服务器返回json格式数据
			type:'post',//HTTP请求类型
			timeout:10000,//超时时间设置为10秒；
			//headers:{'Content-Type':'application/json'},	              
			success:function(data){
				console.log(data);
				if(data['result']==1){
					//plus.nativeUI.toast('上传成功');
					console.log('上传成功');
					mui("#InfoPost").button('reset');
					$("#reset").show();
				}
				
			},
			error:function(xhr,type,errorThrown){
				//异常处理；
				console.log(type);
				mui("#InfoPost").button('reset');
				//plus.nativeUI.toast('请求超时或异常');
				console.log('请求超时或异常');
				$("#reset").show();
			}
		});
     	
	});
	
	//事件函数：当删除按钮被点击，发送请求到后台
	$("#delete").click(function(){
		//确认对话框
		console.log(taskId);
		mui.confirm("我确定要删除此任务","请确认操作",['取消','确认'],
		function (e){
			console.log(123123);
			if (e.index == 1) {
				//点了确认
				mui("#delete").button('loading');
				$("#edit").hide();
		    	mui.ajax('{{asset('/delectTask')}}',{
					data:{
						taskId:taskId//从此处往下，传的值还没有获取！！！！！！！！！1
					},
					dataType:'json',//服务器返回json格式数据
					type:'post',//HTTP请求类型
					timeout:10000,//超时时间设置为10秒；
					//headers:{'Content-Type':'application/json'},
					success:function(data){
						if(data['result']==1){
							plus.nativeUI.toast('上传成功');
							mui.openWindow({url: '{{asset('/userTaskView')}}'});
							mui("#delete").button('reset');
							$("#edit").show();
						}else if(data['result']==0){
							plus.nativeUI.toast(data['remind']);
							alert(data['remind']);
							mui("#delete").button('reset');
							$("#edit").show();
						}
					},
					error:function(xhr,type,errorThrown){
						//异常处理；
						console.log(type);
						mui("#delete").button('reset');
						plus.nativeUI.toast('请求超时或异常');
						$("#edit").show();
					}
				});
            } else {
                plus.nativeUI.toast('已取消操作');
            }
		},'div');
	});

</script>	
	

</html>