<?php if(!$this->tpl_var['userhash']){ ?>
<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
			<?php $this->_compileInclude('menu'); ?>
		</div>
		<div class="span10" id="datacontent">
<?php } ?>
			<ul class="breadcrumb">
				<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master"><?php echo $this->tpl_var['apps'][$this->tpl_var['_app']]['appname']; ?></a> <span class="divider">/</span></li>
				<?php if($this->tpl_var['catid']){ ?>
				<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master-contents">本考场考试成绩</a> <span class="divider">/</span></li>
				<li class="active"><?php echo $this->tpl_var['categories'][$this->tpl_var['catid']]['catname']; ?></li>
				<?php } else { ?>
				<li class="active">本考场考试成绩</li>
				<?php } ?>
			</ul>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">本考场考试成绩</a>
				</li>
				<li >
					<a href="index.php?exam-master-export&id=<?php echo $this->tpl_var['basicid']; ?>">考试成绩导出</a>
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
		                <?php $bid = 0;
 foreach($this->tpl_var['basicsStuScores'] as $key => $StuScore){ 
 $bid++; ?>
				        <tr>
							<td>
								<?php echo $StuScore['userid']; ?>
							</td>
							<td>
								<?php echo $StuScore['username']; ?>
							</td>
							<td>
								<?php echo $StuScore['score']; ?>
							</td>
							<td>
							    <a target = "_blank" href="index.php?exam-app-history-view&ehid=<?php echo $StuScore['link']; ?>">
							          查看
								<a>
							</td>
				        </tr>
				        <?php } ?>
		        	</tbody>
		        </table>
		        
			</form>
	        <div class="pagination pagination-right">
				<ul><?php echo $this->tpl_var['basics']['pages']; ?></ul>
	        </div>
<?php if(!$this->tpl_var['userhash']){ ?>
		</div>
	</div>
</div>
</body>
</html>
<?php } ?>