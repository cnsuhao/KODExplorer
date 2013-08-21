<div class='h1'><i class="font-icon icon-upload-alt"></i>最后更新</div>
<div class="section">
	<!-- 
	<fieldset>
		<legend>ver 1.3 --2012/6/19</legend>
		<span>update</span><div class="line"></div>
		<div class='update'></div>
		<span>fix bug</span><div class="line"></div>
		<div class='fixbug'></div>
	</fieldset>
	-->

	<fieldset>
		<legend>ver 1.01 --2012/6/20</legend>
		<span>update</span><div class="line"></div>
		<div class='update'>
			<li>打开文件夹增加动画效果，淡入淡出</li>
			<li>收藏夹功能整合到设置中，右键文件夹可以把该文件夹添加到收藏夹</li>
			<li>设置中：弹出对话框设置，增加实时预览效果</li>
		</div>
		<span>fix bug</span><div class="line"></div>
		<div class='fixbug'>
			<li>linux 下浏览器打开文件和文件夹，中文问题</li>
			<li>重命名&新建&上传 刷新列表使用动画，当前选中失效问题，不使用动画加载方式。</li>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>ver 1.0 --2012/6/19</legend>
		<span>update</span><div class="line"></div>
		<div class='update'>
			<li>整体优化，实现全部操作ajax本地化 ，进一步提升体验</li>
			<li>浏览器强刷新，保持之前最后所在路径</li>
			<li>重命名，新建,粘贴操作后添加选中状态</li>
			<li>代码主题列表优化,重新设计,提高可配置性</li>
			<li>自定义收藏夹操作实现,其他frame通信，服务器增删改</li>
			<li>优化代码，添加getTplList模版，简化关联配置获取</li>
			<li>用户名密码修改优化，md5加密,提升安全性</li>
		</div>
		<span>fix bug</span><div class="line"></div>
		<div class='fixbug'>
			<li>完善修改windows以及linux获取文件列表，中文路径属性获取失败问题。</li>
			<li>backspace快捷键导致，选中后快捷键事件失效问题</li>
			<li>复制，剪切。剪贴板内容覆盖判空处理</li>
			<li>修复一些地方ajax线程同步问题，重命名后选中失效问题解决</li>
			<li>修复返回上层目录，根目录检测</li>
			<li>修复编辑器 保存后失去焦点问题</li>
			<li>修改三个框架载入顺序，保证保证header先加载，main调用header的click</li>
			<li>修复linux下 音视频播放，中文路径问题</li>
		</div>
	</fieldset>


	<fieldset>
		<legend>ver 0.9 --2012/6/15</legend>
		<span>update</span><div class="line"></div>
		<div class='update'>
			<li>增加设置功能，ajax刷新设置。缩略图片增加tips预览(被注释frame/setting.php)</li>
			<li>增加重命名只选择名称部分功能</li>
			<li>增加iframe js api互操作ie支持。四大浏览器内核皆支持。</li>
			<li>文件夹打开，采用ajax实现。包括头部地址栏,父级目录,左边树目录以及收藏夹</li>
			<li>历史记录完美实现，前进后退按钮实时变化状态。</li>
			<li>快捷键backspace实现后退(left header main 分别加入函数，屏蔽默认history(-1)操作)</li>		
		</div>
		<span>fix bug</span><div class="line"></div>
		<div class='fixbug'>
			<li>修复firefox opear 的bug</li>
			<li>修复文档编辑显示区高度问题</li>
			<li>修复文件右键菜单位置出错问题</li>
			<li>修复firefox 下右键问题，针对firefox做js兼容性调整</li>
			<li>修复ie下frame js相互通信问题</li>
			<li>去掉ctrl shift按下情况下 文件【夹】多选问题。firefox问题。</li>
			<li>修复ie下 样式问题。（重命名textarea，代码编辑工具栏样式，）</li>
			<li>ajax更新文件列表下，各种bug修复。进一步提升操作友好性</li>
			<li>修复当前目录改变的情况下，播放器消失的问题。现在能使播放器始终保留</li>
			<li>修复一些sinaapp下的显著问题。路径不同的地方处理</li>		
		</div>
	</fieldset>
</div>
