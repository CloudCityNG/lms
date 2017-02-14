{x2;if:!$userhash}
{x2;include:header}
<body>
{x2;include:nav}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
			{x2;include:menu}
		</div>
		<div class="span10" id="datacontent">
{x2;endif}
			<ul class="breadcrumb">
				<li><a href="index.php?{x2;$_app}-master">{x2;$apps[$_app]['appname']}</a> <span class="divider">/</span></li>
				{x2;if:$catid}
				<li><a href="index.php?{x2;$_app}-master-contents">本考场考试成绩</a> <span class="divider">/</span></li>
				<li class="active">{x2;$categories[$catid]['catname']}</li>
				{x2;else}
				<li class="active">本考场考试成绩</li>
				{x2;endif}
			</ul>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">本考场考试成绩</a>
				</li>
				<li >
					<a href="index.php?exam-master-export&id={x2;$basicid}">考试成绩导出</a>
				</li>
			</ul>
			
	        <form action="index.php?exam-master-basic-batdelbasic" method="post">
		        <table class="table table-hover">
		            <thead>
		                <tr>
		                    <th>排名</th>
					        <th>姓名</th>
					        <th>成绩</th>
					        <th>结果</th>
		                </tr>
		            </thead>
		            <tbody>
		                {x2;tree:$basicsStuScores,StuScore,bid}
				        <tr>
							<td>
								{x2;v:StuScore['userid']}
							</td>
							<td>
								{x2;v:StuScore['username']}
							</td>
							<td>
								{x2;v:StuScore['score']}
							</td>
							<td>
							    <a target = "_blank" href="index.php?exam-app-history-view&ehid={x2;v:StuScore['link']}">
							          查看
								<a>
							</td>
				        </tr>
				        {x2;endtree}
		        	</tbody>
		        </table>
		        
			</form>
	        <div class="pagination pagination-right">
				<ul>{x2;$basics['pages']}</ul>
	        </div>
{x2;if:!$userhash}
		</div>
	</div>
</div>
</body>
</html>
{x2;endif}