
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>首页</title>
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/ajaxfileupload.js')}}"></script>
    <script src="{{asset('js/mui.min.js')}}"></script>
    <link href="{{asset('css/mui.min.css')}}" rel="stylesheet"/>
    <script type="text/javascript" charset="utf-8">
      	mui.init();
    </script>

</head>
<body>
	<!-- 侧滑导航根容器 -->
	<div class="mui-off-canvas-wrap mui-draggable mui-scalable">
	  <!-- 主页面容器 -->
	  <div class="mui-inner-wrap">
	     <!-- 菜单容器 -->
	    <aside class="mui-off-canvas-left">
	      <div class="mui-scroll-wrapper">
	        <div class="mui-scroll">
	          <!-- 菜单具体展示内容 -->
	          ...
	        </div>
	      </div>
	    </aside>
	    <!-- 主页面标题 -->
	    <header class="mui-bar mui-bar-nav" style="background-color: rgba(254, 201, 16, 1);">
	      <a class="mui-icon mui-action-menu mui-icon-bars mui-pull-left" style="color: #FFFFFF;"></a>
	      <h1 class="mui-title" style="color: #FFFFFF;font-size: 22.5px;"><b>智能报修</b></h1>
	    </header>
	    <!-- 主页面内容容器 -->
	    <div class="mui-content mui-scroll-wrapper">
	      <div class="mui-scroll">
	        <!-- 主界面具体展示内容 -->
	        <div class="mui-card">
				<div class="mui-card-header" style="color: #FFFFFF;background-color: rgba(254, 201, 16, 1);">用户信息</div>
				<div class="mui-card-content">
					<div id = 'userInformation' class="mui-card-content-inner">

					</div>
				</div>
			</div>
			
	       	<div class="mui-card">
				<div class="mui-card-content">
					<div class="mui-card-content-inner" style="height:62px;">
						
						<button onclick="createNewTask('{{asset('/userReportView')}}')" class="mui-btn mui-btn-yellow" style="position:absolute;left:16%;background-color: rgba(254, 201, 16, 1);">
							新建报修
						</button>
						<button onclick="createNewTask('{{asset('/userTaskView')}}')" class="mui-btn mui-btn-yellow" style="position:absolute;left:60%;background-color: rgba(254, 201, 16, 1);">
							查看任务
						</button>
					</div>
				</div>
			</div>
	      </div>
	    </div>  
	  </div>
	</div>
</body>
<script>
	$(document).ready(function(){
		/*rrr();*/
		getInformation();
	});

	function getInformation(){
		$.ajax({
			url:"{{asset('/findUserInformation')}}",
			async:false,
			type:'POST',
			dateType:'json',
			data:{openid:'openid1'},//此处需要修改！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
			success:function(data){
				var number = data['data']['number'];
				var telephone = data['data']['telephone'];
				var userInformation = '<span style="">学号：'+number+'</span></br>\
								<span style="">电话：'+telephone+'</span>';
				$('#userInformation').html(userInformation);

			},
			error:function(data){
				console.log('error');
			}
		})
	}

	//打开子页面新建报修任务
	function createNewTask(urlView){

		mui.openWindow({
		    url:urlView,
		    show:{
		      autoShow:true,//页面loaded事件发生后自动显示，默认为true
		      aniShow:'slide-in-right',//页面显示动画，默认为”slide-in-right“；
		      duration:100,//页面动画持续时间，Android平台默认100毫秒，iOS平台默认200毫秒；
		      event:'titleUpdate',//页面显示时机，默认为titleUpdate事件时显示
		      extras:{}//窗口动画是否使用图片加速
		    },
		    waiting:{
		      autoShow:true,//自动显示等待框，默认为true
		      title:'正在加载...',//等待对话框上显示的提示内容
		    }
		});
	}
</script>
</html>