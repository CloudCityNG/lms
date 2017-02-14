<div class="row-fluid top">
    <div class="logo">
      <a href="/">
          <img style="" src="app/user/styles/img/theme/edu.png" />
      </a>
    </div>
    <ul  class="nav-top">
        <li><a href="/course/explore">课程 </a></li>
        <li><a href="/ems">综合评测 </a></li>
        <li><a href="http://www.cnhongke.org">红客训练营 </a></li>
        <li><a href="/page/knowledgeSystem">知识体系 </a></li>
        <li><a href="/page/service">安全服务 </a></li>
        <li><a href="/article">安全资讯  </a></li>
        <li><a href="/page/aboutus">关于我们  </a></li>
    </ul>
    <div>
    <ul class="nav-user">
        <?php if($this->tpl_var['_user']['userid']){ ?>
      
        <li><a href="index.php?exam-app-exam-paper"><em class="icon-user"></em> 我的考试</a></li>
        <?php if($this->tpl_var['_user']['teacher_subjects']){ ?>&nbsp;&nbsp;
        <li> <em class="icon-edit"></em> <a href="index.php?exam-teach">教师管理</a></li>
        <?php } elseif($this->tpl_var['_user']['groupid'] == 1){ ?>&nbsp;&nbsp;
          <li><em class="icon-edit"></em> <a href="index.php?core-master">后台管理</a></li>
        <?php } ?>&nbsp;&nbsp;
        <li>您好（<?php echo $this->tpl_var['_user']['username']; ?>）&nbsp;&nbsp;</li>
        <li><a href="index.php?user-app-logout">退出</a></li>
	   <?php } else { ?>
	     <li><a href="http://ctf.com/portal/sp/user_register.php">注册</a></li>
         <li><a href="http://ctf.com/portal/sp/login.php">登录</a></li>
       <?php } ?>
    </ul>
    </div>
</div>
<style>
    .logo{
        position: absolute;top: 0; left: 0;
    }
    .logo a{
        height: 60px;line-height: 50px;padding: 4px 32px;color: #fff;
    }
    .logo img{
    width: auto;padding-top: 6px;
    }
a{
color: #c1c1c1;
}
a:hover{
color:#fff;
}
.nav-top{
padding-left: 200px;padding-top: 16px;
}
 .nav-top li{
     float:left;list-style:none;
 }
.nav-top li a {
    padding: 20px 30px;
    line-height: 20px;
    color: #c1c1c1;
    background: none;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.nav-top li a:hover{
    color:#fff;
}
.nav-user li{
float:right;list-style: none;padding-right:30px; margin-top: -8px;
}
nav-user li a{
    padding: 20px 15px;
    color: #c1c1c1;
}
</style>