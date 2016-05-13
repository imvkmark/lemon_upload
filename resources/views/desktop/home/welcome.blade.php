@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		<div class="bar-fixed">
			<div class="title-bar">
				<h3>欢迎界面</h3>
			</div>
		</div>
		<div class="page clearfix">
			<div class="span-12">
				<table class="table prompt" id="J_prompt">
					<tbody>
					<tr class="odd">
						<th colspan="12" class="nobg">
							<div class="title">
								<h5>感谢您使用游戏代练管理系统</h5>
								<span class="arrow"></span>
							</div>
						</th>
					</tr>
					<tr>
						<td>
						<span class="blk-tips" style="display: block;">
							您现在使用的是一套用于外包业务的管理系统, 如果您有任何疑问请点左下角的QQ进行咨询,
							此程序操作简单, 支持先进浏览器, 采用ajax交互先进技术, 在先进的浏览器上有更佳的性能体验
						</span>
							<ul>
								<li>本程序由 Mark Zhao 全新制作</li>
								<li>本程序仅提供使用</li>
								<li>支持作者的劳动</li>
								<li>程序使用, 技术支持, 请联系: QQ: 408128151</li>
							</ul>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="span-12 last">
				<h2><label for="update">进度更新</label></h2>
				<div>
					<div id="update" style="width: 100%;height: 400px;overflow-y: auto">{!! $html  !!}</div>
				</div>
			</div>
		</div>
	</div>
@endsection
