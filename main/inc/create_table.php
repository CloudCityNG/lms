<?php
//网络拓扑模块的路由设备表
create_table("net_devices", "
   CREATE TABLE IF NOT EXISTS `net_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `ios` text NOT NULL,
  `ram` int(128) NOT NULL,
  `nvram` int(128) NOT NULL,
  `ethernet` int(128) NOT NULL,
  `serial` int(128) NOT NULL,
  `slot` varchar(128) NOT NULL,
  `picture` text NOT NULL,
  `conf_id` text NOT NULL,
  `vmdisks` varchar(256) DEFAULT NULL,
  `desc` text NOT NULL,
  `status` varchar(50),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; 
");
//课程与虚拟机模板关联表
create_table("course_connection_vmdisk","CREATE TABLE IF NOT EXISTS `course_connection_vmdisk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cid` varchar(50) NOT NULL COMMENT '课程编号',
  `cname` varchar(256) NOT NULL COMMENT '课程名称',
  `net_dev_id` INT( 11 ) NOT NULL COMMENT '虚拟模板id或路由设备id',
  `net_dev_name` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '虚拟模板名称或路由设备名称',
  `type` INT( 11 ) NOT NULL COMMENT '1为虚拟模板,2为路由设备',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

/**路由交换类型表 auth@changzf  2013/11/20  **/
create_table("labs_type","CREATE TABLE IF NOT EXISTS `labs_type` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
                                            `name` varchar(50) NOT NULL COMMENT '名称',
                                            `desc` varchar(128) NOT NULL COMMENT '描述',
                                            PRIMARY KEY (`id`)
                                          ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='路由交换类型表' AUTO_INCREMENT=0;");

/**路由运行表**/
create_table("task","CREATE TABLE IF NOT EXISTS `labs_run_devices` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `course_name` varchar(256) NOT NULL,
		  `labs_name` varchar(32) NOT NULL,
		  `p_id` int(11) NOT NULL,
		  `USERID` int(11) NOT NULL,
		  `GROUPID` int(11) DEFAULT NULL,
		  `LEADID` int(11) DEFAULT NULL,
		  `PORT` int(11) NOT NULL,
		  `DEVICEID` varchar(256) NOT NULL,
		  `DEVICEDNAME` varchar(256) NOT NULL,
		  `ROUTETYPE` varchar(256) NOT NULL,
		  `ROUTEMOD` varchar(256) NOT NULL,
		  `DEVICEDTYPE` varchar(256) NOT NULL,
		  `status` int(11) NOT NULL,
		  `uport` varchar(256) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MEMORY  DEFAULT CHARSET=utf8 COMMENT='路由运行表' AUTO_INCREMENT=0 ;");

/**监控平台任务表**/
create_table("task","CREATE TABLE IF NOT EXISTS `task` (
                      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
                      `name` varchar(128) NOT NULL COMMENT '任务名称',
                      `description` varchar(256) NOT NULL COMMENT '任务描述',
                      `group` int(11) NOT NULL COMMENT '用户组',
                      `status` int(11) NOT NULL DEFAULT '1' COMMENT '是否发布：1为发布，0为未发布',
                      `red_vm` varchar(256) NOT NULL COMMENT '靶机模板',
                      `blue_vm` varchar(256) NOT NULL COMMENT '渗透模板',
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='监控平台任务表' AUTO_INCREMENT=0 ;");

/**监控平台用户分组表**/
create_table("task_group","CREATE TABLE IF NOT EXISTS `task_group` (
                      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
                      `group` varchar(128) NOT NULL COMMENT '分组名称',
                      `task_id` varchar(128) NOT NULL COMMENT '任务编号',
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='监控平台任务分组表' AUTO_INCREMENT=0 ;");

/**试卷表**/
create_table("exam_type","CREATE TABLE IF NOT EXISTS `exam_type` (
                      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
                      `name` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '试卷名称',
                      `description` text CHARACTER SET utf8 NOT NULL COMMENT '描述',
                      `enable` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='试卷表' AUTO_INCREMENT=0 ;");

/**大赛简介表**/
create_table("summary","CREATE TABLE IF NOT EXISTS `summary` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `title` varchar(128) NOT NULL,
                      `created_user` int(11) DEFAULT NULL,
                      `visible` tinyint(1) NOT NULL DEFAULT '1',
                      `content` text NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='简介表' AUTO_INCREMENT=0 ;");

/**拓扑设备表  态势展示**/
create_table("topomap","CREATE TABLE IF NOT EXISTS `topomap` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `topo_id` int(128) NOT NULL,
                      `nodeId` int(128) NOT NULL,
                      `nodename` varchar(32) CHARACTER SET utf8 NOT NULL,
                      `nodeType` varchar(256) CHARACTER SET utf8 NOT NULL,
                      `offset` varchar(256) CHARACTER SET utf8 NOT NULL,
                      `nodeDesc` varchar(256) CHARACTER SET utf8 NOT NULL,
                      `desc_position` varchar(50) CHARACTER SET utf8 NOT NULL,
                      `ports` varchar(50) CHARACTER SET utf8 NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='拓扑设备表' AUTO_INCREMENT=0 ;");

/**监控平台小组用户表**/
create_table("group_user","CREATE TABLE IF NOT EXISTS `group_user` (
                      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
                      `name` varchar(128) NOT NULL COMMENT '分组名称',
                      `userId` varchar(128) NOT NULL COMMENT '用户编号',
                      `is_leader` int(11) NOT NULL COMMENT '是否组长',
                      `type` int(11) NOT NULL DEFAULT '1' COMMENT '用户类型,1为红方，2为蓝方',
                      `description` text NOT NULL COMMENT '分组描述',
                      `tasks_id` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='监控平台小组用户表' AUTO_INCREMENT=0");

/**导调工具表**/
create_table("tools","CREATE TABLE IF NOT EXISTS `tools` (
                      `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
                      `title` varchar(128) NOT NULL COMMENT '工具名称',
                      `created_user` int(11) DEFAULT NULL COMMENT '创建用户',
                      `date_start` datetime NOT NULL COMMENT '创建时间',
                      `visible` tinyint(1) NOT NULL COMMENT '是否开放',
                      `content` text NOT NULL COMMENT '工具描述',
                      `file` varchar(128) DEFAULT NULL COMMENT '工具文件',
                      `type` varchar(128) DEFAULT NULL COMMENT '类型',
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='导调工具表' AUTO_INCREMENT=0 ;");

/**评估名称**/
create_table("project","CREATE TABLE IF NOT EXISTS `project` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                       `name` varchar(32) NOT NULL,
                       `upfile` varchar(128) NOT NULL,
                       `release` int(11) NOT NULL DEFAULT 0,
                       `des` varchar(32) NOT NULL,
                       PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");

/**检查方法**/
create_table("assess","CREATE TABLE IF NOT EXISTS `assess` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `pro_id` int(128) DEFAULT NULL,
                      `class` varchar(128) DEFAULT NULL,
                      `check` text,
                      `risk_level` int(11) NOT NULL,
                      `reinforcement_suggestions` text NOT NULL,
                      `num` tinyint(4) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");

/**检查项**/
create_table("check_items","CREATE TABLE IF NOT EXISTS `check_items` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                        `item_id` int(11) DEFAULT NULL,
                        `name` varchar(128) DEFAULT NULL,
                        `des` text NOT NULL,
                        `assess_id` int(11) NOT NULL,
                        PRIMARY KEY (`id`,`assess_id`),
                        KEY `fk_check_items_assess1` (`assess_id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");


/**前台评估**/
create_table("assessment_result","CREATE TABLE IF NOT EXISTS `assessment_result` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                        `pro_id` int(11) DEFAULT NULL,
                        `user_id` int(11) DEFAULT NULL,
                        `assess_id` int(11) DEFAULT NULL,
                        `check_id` int(11) DEFAULT NULL,
                        `result` int(11) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");

/**夺旗、分组对抗报告表**/
create_table("reporting_info","CREATE TABLE IF NOT EXISTS `reporting_info` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `report_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '报告名称',
                          `user` varchar(128) NOT NULL COMMENT '用户',
                          `submit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间',
                          `screenshot_file` varchar(50) NOT NULL COMMENT '学生提交文件',
                          `status` int(11) NOT NULL COMMENT '学生提交状态',
                          `score` int(11) NOT NULL COMMENT '得分',
                          `comment` text NOT NULL COMMENT '评语',
                          `return` int(11) NOT NULL COMMENT '批改结果',
                          `marking_status` int(11) NOT NULL COMMENT '教师批改状态',
                          `description` text NOT NULL COMMENT '描述',
                          `type` int(11) NOT NULL DEFAULT '1' COMMENT '报告类型：1为夺旗报告，2为分组对抗报告',
                          `key` varchar(128) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='夺旗、分组对抗报告表' AUTO_INCREMENT=0;");

/**实验报告表**/
create_table("report","CREATE TABLE IF NOT EXISTS `report` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `report_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '报告名称',
                        `user` varchar(128) NOT NULL COMMENT '用户',
                        `code` varchar(128) NOT NULL COMMENT '学习课程',
                        `submit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间',
                        
                        `purpose` text  NOT NULL COMMENT '实验目的',
                        `equipment` text NOT NULL COMMENT '实验设备环境',
                        `content` text NOT NULL COMMENT '实验内容和步骤',
                        `result` text NOT NULL COMMENT '实验结果',
                        `analysis` text NOT NULL COMMENT '实验分析和讨论',
                        
                        `screenshot_file` varchar(50) NOT NULL COMMENT '学生提交内容',
                        `status` int(11) NOT NULL COMMENT '提交状态',
                        `score` int(11) NOT NULL COMMENT '得分',
                        `comment` text NOT NULL COMMENT '评语',
                        `return` int(11) NOT NULL COMMENT '批改结果',
                        `marking_status` int(11) NOT NULL COMMENT '教师批改状态',
                        `description` text NOT NULL COMMENT '描述',
                        `key` varchar(128) NOT NULL,
                        `type` int(11) NOT NULL DEFAULT '0' COMMENT '是否有课程，1为为有课程，0为没有课程',
                        PRIMARY KEY (`id`)
                      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='实验报告表' AUTO_INCREMENT=0;");
/**夺旗表**/
create_table("flag","CREATE TABLE IF NOT EXISTS `flag` (
                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
                          `date_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发布时间',
                          `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
                          `title` varchar(250) NOT NULL DEFAULT '' COMMENT '标题',
                          `content` text NOT NULL COMMENT '旗子位置描述',
                          `created_user` int(11) DEFAULT NULL,
                          `user` varchar(256) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='夺旗表' AUTO_INCREMENT=0");

/**分组任务表**/
create_table("renwu","CREATE TABLE IF NOT EXISTS `renwu` (
                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
                          `name` varchar(256) NOT NULL DEFAULT '' COMMENT '标题',
                          `description` text NOT NULL COMMENT '描述',
                          `red_group` varchar(256) DEFAULT NULL,
                          `blue_group` varchar(256) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='分组表' AUTO_INCREMENT=0");

/**对抗部署表**/
create_table("deploy","CREATE TABLE IF NOT EXISTS `deploy` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
                          `template_id` int(11) NOT NULL COMMENT '模板',
                          `user_id` int(11) NOT NULL COMMENT '用户',
                          `task_id` int(11) NOT NULL COMMENT '任务编号',
                          `ip` varchar(128) NOT NULL COMMENT 'IP',
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='对抗部署表' AUTO_INCREMENT=0");

/**课程体系表**/
create_table("setup","CREATE TABLE IF NOT EXISTS `setup` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `title` varchar(64) CHARACTER SET utf8 NOT NULL,
                        `description` text CHARACTER SET utf8 NOT NULL,
                        `subclass` varchar(128) CHARACTER SET utf8 NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='课程体系分类' AUTO_INCREMENT=0 ;");

/**信息传递表**/
create_table("message","CREATE TABLE IF NOT EXISTS  `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `recipient` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='信息传递';");


/**截屏录屏表**/
create_table("snapshot","CREATE TABLE IF NOT EXISTS `snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addres` varchar(128) NOT NULL ,
  `system` varchar(128) NOT NULL COMMENT '系统',
  `user_id`int(11) NOT NULL COMMENT '用户编号',
  `lesson_id` varchar(50) NOT NULL COMMENT '课程编号',
  `vmid` int(20) NOT NULL,
  `port` int(30) NOT NULL,
  `mac_id` varchar(128) NOT NULL,
  `proxy_port` int(11) NOT NULL  COMMENT '代理端口',
  `status` int(11) NOT NULL  COMMENT '状态：1为进行，0为关闭',
  `type` int(11) NOT NULL  COMMENT '类型：1为截屏，2为录屏',
  `filename` varchar(128) NOT NULL  COMMENT '文件',
  `time` varchar(20) NOT NULL  COMMENT '时间',
  `snapshotdesc` text NOT NULL  COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  COMMENT '截屏录屏表' AUTO_INCREMENT=0 ;");


/**课程license管理**/
create_table("course_license","CREATE TABLE IF NOT EXISTS `course_license` (
    `id` int(11) NOT NULL AUTO_INCREMENT, 
    `course_category` varchar(100) NOT NULL COMMENT '课程分类名称',
    `description` varchar(128) NOT NULL COMMENT '描述' ,
    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '时间' ,
    `filename` varchar(128) NOT NULL COMMENT '包名',
    `license` varchar(128) NOT NULL COMMENT 'license',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8  COMMENT '课程license管理' AUTO_INCREMENT=0;");

/**虚拟机vmstartinfo表**/
create_table("vmstartinfo","CREATE TABLE  IF NOT EXISTS `vmstartinfo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `addres` varchar(256) CHARACTER SET utf8 NOT NULL,
    `nicnum` int(11) NOT NULL,
    `system` varchar(32) CHARACTER SET utf8 NOT NULL,
    `user_id` int(11) NOT NULL,
    `lesson_id` bigint(20) NOT NULL,
    `stat_id` int(11) NOT NULL,
    `group_id` int(11) NOT NULL,
    `mac_id` varchar(256) NOT NULL,
    `stime` varchar(256) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=MEMORY  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");

/**虚拟机vmtotal表**/
create_table("vmtotal","CREATE TABLE IF NOT EXISTS `vmtotal` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `addres` varchar(256) CHARACTER SET utf8 NOT NULL,
    `nicnum` int(11) NOT NULL,
    `system` varchar(32) CHARACTER SET utf8 NOT NULL,
    `user_id` int(11) NOT NULL,
    `lesson_id` bigint(20) NOT NULL,
    `vmid` int(11) NOT NULL,
    `port` int(11) NOT NULL,
    `group_id` int(11) NOT NULL,
    `mac_id` varchar(256) NOT NULL,
    `proxy_port` varchar(256) NOT NULL,
    `manage` VARCHAR(5) NOT NULL,
    `stime` VARCHAR(40) NOT NULL,
    `monitor` int(3) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=MEMORY AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");

/**虚拟机开启最大数量**/
create_table("vm_max_num","CREATE TABLE IF NOT EXISTS `vm_max_num` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `number` varchar(256) CHARACTER SET utf8 NOT NULL, 
    `description` varchar(128) NOT NULL COMMENT '描述' ,
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT '虚拟机开启最大数量';");
  
  /**趋势图**/
create_table("run_chart","CREATE TABLE IF NOT EXISTS `run_chart` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `cpu` smallint(100) unsigned NOT NULL COMMENT 'cpu使用率',
		  `memory` smallint(100) unsigned NOT NULL COMMENT '内存使用率',
		  `virtual_number` int(11) unsigned NOT NULL COMMENT '在线虚拟机数量',
		  `disc_1` smallint(100) unsigned NOT NULL COMMENT '磁盘1使用率',
		  `disc_2` smallint(100) unsigned NOT NULL COMMENT '磁盘2使用率',
		  `ip_location` char(100) NOT NULL COMMENT 'ip地址',
		  `online_number` int(11) unsigned NOT NULL COMMENT '在线用户数',
		  `time` char(50) NOT NULL COMMENT '时间',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='趋势图表' AUTO_INCREMENT=0 ;");

  /**课程分类表**/
create_table("course_category","CREATE TABLE IF NOT EXISTS `course_category` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `parent_id` varchar(40) DEFAULT NULL COMMENT '上级分类id',
                `sn` varchar(64) DEFAULT NULL COMMENT '分类自动创建编号',
                `name` varchar(100) NOT NULL COMMENT '分类名',
                `code` varchar(40) DEFAULT NULL COMMENT '分类手动创建编号',
                `tree_pos` int(10) unsigned DEFAULT NULL COMMENT '显示顺序',
                `children_count` smallint(6) DEFAULT NULL,
                `auth_cat_child` enum('TRUE','FALSE') DEFAULT 'TRUE',
                `last_updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `org_id` int(11) DEFAULT '-1',
                `CourseDescription` blob NOT NULL,
                `CurriculumStandards` blob NOT NULL,
                `AssessmentCriteria` blob NOT NULL,
                `TeachingProgress` blob NOT NULL,
                `StudyGuide` blob NOT NULL,
                `TeachingGuide` blob NOT NULL,
                `InstructionalDesignEvaluation` blob NOT NULL,
                `status` int(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                KEY `parent_id` (`parent_id`),
                KEY `tree_pos` (`tree_pos`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='课程分类表' AUTO_INCREMENT=0;");

/**虚拟机监控表**/
create_table("vm_monitor","CREATE TABLE IF NOT EXISTS `vm_monitor` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `system` varchar(256) NOT NULL COMMENT '虚拟模板编号',
                `addres` varchar(256) NOT NULL COMMENT '虚拟机IP',
                `lesson_id` varchar(256) NOT NULL COMMENT '课程编号',
                `user_id` varchar(256) NOT NULL COMMENT '用户编号',
                `mem` varchar(256) NOT NULL COMMENT '内存使用率',
                `cpu` varchar(256) NOT NULL COMMENT 'cpu使用率',
                `diskread` varchar(256) NOT NULL COMMENT '磁盘读',
                `diskwrite` varchar(256) NOT NULL COMMENT '磁盘写',
                `mouse` varchar(256) NOT NULL COMMENT '鼠标响应时间',
                `netin` varchar(256) NOT NULL COMMENT '网络in',
                `netout` varchar(256) NOT NULL COMMENT '网络out',
                `maxdisk` varchar(256) NOT NULL COMMENT '磁盘最大使用',
                `maxmem` varchar(256) NOT NULL COMMENT '内存最大使用',
                `nettime` varchar(256) NOT NULL COMMENT '网络延迟',
                `times` varchar(256) NOT NULL COMMENT '时间',
                `manage` int(11) NOT NULL COMMENT '是否是后台0为后台1为前台',
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='虚拟机监控' AUTO_INCREMENT=1 ;");

/**虚拟机日志表**/
create_table("vmdisk_log", "CREATE TABLE IF NOT EXISTS `vmdisk_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `addres` varchar(256) NOT NULL,
            `nicnum` int(11) NOT NULL,
            `system` varchar(32) NOT NULL,
            `user_id` int(11) NOT NULL,
            `lesson_id` bigint(20) NOT NULL,
            `vmid` int(11) NOT NULL,
            `port` int(11) NOT NULL,
            `group_id` int(11) NOT NULL,
            `mac_id` varchar(256) NOT NULL,
            `proxy_port` varchar(256) NOT NULL,
            `manage` varchar(5) NOT NULL,
            `username` varchar(32) NOT NULL,
            `user_ip` varchar(32) NOT NULL,
            `start_time` varchar(32) NOT NULL,
            `end_time` varchar(32) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );

//课程评论表
create_table("Comment","CREATE TABLE IF NOT EXISTS `Comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cid` bigint(11) NOT NULL COMMENT '课程编号',
  `fid` bigint(20) NOT NULL COMMENT '回复的主信息id',
  `ffid` bigint(20) NOT NULL COMMENT '被评论的信息id',
  `text` text NOT NULL COMMENT '回复的内容',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `comtime` bigint(14) unsigned NOT NULL COMMENT '评论时间',
  `state` tinyint(1) NOT NULL COMMENT '是否显示1为显示0为不显示',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `cid` (`cid`),
  KEY `fid` (`fid`),
  KEY `ffid` (`ffid`),
  KEY `state` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;");
create_table("exam_rel_user","
    ALTER TABLE `exam_rel_user` CHANGE `available_start_date` `available_start_date` VARCHAR( 128 ) NULL DEFAULT NULL ;
    ALTER TABLE `exam_rel_user` CHANGE `available_end_date` `available_end_date` VARCHAR( 128 ) NULL DEFAULT NULL;
");


create_table("settings_current", "insert into settings_current(`enabled`, `variable`, `subkey`, `type`, `category`, `display_order`, `selected_value`, `title`, `comment`, `scope`, `subkeytext`)SELECT  1, 'login_logo_set', NULL, 'textfield', 'Company', 14, '135;60', 'login_logo_set_title', 'login_logo_set_comment', NULL, NULL  FROM settings_current where NOT EXISTS(SELECT * FROM settings_current where settings_current.variable='login_logo_set' )  LIMIT 1;");
create_table("settings_current", "insert into settings_current(`enabled`, `variable`, `subkey`, `type`, `category`, `display_order`, `selected_value`, `title`, `comment`, `scope`, `subkeytext`)SELECT  1, 'header_logo_set', NULL, 'textfield', 'Company', 15, '135;60', 'header_logo_set_title', 'header_logo_set_comment', NULL, NULL  FROM settings_current where NOT EXISTS(SELECT * FROM settings_current where settings_current.variable='header_logo_set' )  LIMIT 1;");
create_table("settings_current", "insert into settings_current(`enabled`, `variable`, `subkey`, `type`, `category`, `display_order`, `selected_value`, `title`, `comment`, `scope`, `subkeytext`)SELECT  1, 'system_date_set', NULL, 'textfield', 'Platform', 16, '2014-10-24;10:48:00', 'system_date_set_title', 'system_date_set_comment', NULL, NULL  FROM settings_current where NOT EXISTS(SELECT * FROM settings_current where settings_current.variable='system_date_set' )  LIMIT 1;");

//course 
create_table("course", "
    ALTER TABLE `course` ADD `netMap` TEXT NOT NULL;
    ALTER TABLE `course` ADD `devices_offset` TEXT NOT NULL;    
");

/**脚本执行日志表**/
create_table("sript_log","CREATE TABLE IF NOT EXISTS `sript_log` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `userName` varchar(256) NOT NULL COMMENT '用户名称',
                `exec_var` varchar(256) NOT NULL COMMENT '执行命令',
                `execution_date` varchar(30) NOT NULL COMMENT '执行时间',
                `page` varchar(256) NOT NULL COMMENT '执行页面',
                `sript_type` varchar(20) NOT NULL COMMENT '命令类型',
                `description` varchar(256) NOT NULL COMMENT '描述', 
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='脚本执行日志' AUTO_INCREMENT=1 ;");

/**职业技能表**/
create_table("skill_occupation", "CREATE TABLE IF NOT EXISTS `skill_occupation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `skill_name` VARCHAR(128) NULL COMMENT '职业技能名称',
  `skill_description` TEXT NULL COMMENT '职业技能描述',
  `position_description` TEXT NULL COMMENT '职位描述',
  `postition_requirement` TEXT NULL COMMENT '职位需求',
  `exam_desc`  TEXT NOT NULL ,
  `devices_offset` TEXT NOT NULL ,
  `netMap` TEXT NOT NULL,
  `occupat_picture`  VARCHAR(50)  NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;");
 /**职业技能与模板（路由）关联表**/
 create_table("skill_occupation_vmdisk", "CREATE TABLE IF NOT EXISTS `skill_occupation_vmdisk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `occupat_id` int(11) NOT NULL,
  `vm_id` int(11) NOT NULL,
  `vm_type` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
/**职业技能与课程关联表**/
create_table("skill_course_occupation","CREATE TABLE IF NOT EXISTS `skill_course_occupation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `course_id` VARCHAR(50)  NULL COMMENT '课程id',
  `skill_id` INT(11) NULL COMMENT '技能id',
  `sequentially` INT( 5 ) NULL DEFAULT NULL COMMENT '顺序' ,
  `step_id`   INT(11) NULL  COMMENT '学习阶段id',
  PRIMARY KEY (`id`))
ENGINE = MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;");
/**职业线路表**/
create_table("skill_line","CREATE TABLE IF NOT EXISTS `skill_line` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uid` INT(11) NULL COMMENT '用户id',
  `skill_id` INT(11) NULL COMMENT '技能id',
  `line_content` TEXT NULL COMMENT '技能线路状态',
  `comment` VARCHAR(256) NULL COMMENT '教师评语',
  `status` INT(11) NULL COMMENT '通过状态',
  PRIMARY KEY (`id`))
ENGINE = MyISAM   DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;");
/**职业技能测试**/
create_table("skill_examine","CREATE TABLE IF NOT EXISTS `skill_examine` (
  `id` INT(11) NOT NULL,
  `uid` INT(11) NULL COMMENT '用户id',
  `occupation_id` INT(11) NULL COMMENT '技能id',
  `question_id` INT(11) NULL COMMENT '试题id',
  `course_id`  VARCHAR(50)  NULL COMMENT '课程id',
  `user_answer` VARCHAR(128) NULL COMMENT '用户答案',
  `user_file` VARCHAR(50) NULL COMMENT '报告名称',
  `status` INT(11) NULL COMMENT '答题状态',
  PRIMARY KEY (`id`))
ENGINE = MyISAM   DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;");
/**技能测试题**/
create_table("skill_question","CREATE TABLE IF NOT EXISTS `skill_question` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `topic` VARCHAR(256) NULL COMMENT '题目',
  `contcat_id` VARCHAR( 128 )  NULL COMMENT '关联测试id',   
  `type` INT(5) NULL COMMENT '类型1为课程自测2为技能测试',
  `answer` VARCHAR(128) NULL COMMENT '正确答案',
  `score` INT(5) NULL COMMENT '分值',
  PRIMARY KEY (`id`))
ENGINE = MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;");
/**技能与学习阶段的关联表**/
create_table("skill_rel_step", "CREATE TABLE IF NOT EXISTS `skill_rel_step` (
  `step_id` int(11) NOT NULL AUTO_INCREMENT,
  `occupat_id` int(11) NOT NULL, 
  `step_desc` text NOT NULL,
  `step_time` varchar(50) NOT NULL,
  `step_sequentially` INT( 5 ) NOT NULL ,
  PRIMARY KEY (`step_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );

/**题库类型表**/
create_table("tbl_class","CREATE TABLE IF NOT EXISTS `tbl_class` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `className` varchar(50) NOT NULL COMMENT '分类名称',
  `fid` int(11) NOT NULL COMMENT '父类id',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='题库类型表' AUTO_INCREMENT=1 ;");

/**赛事表**/
create_table("tbl_contest","CREATE TABLE IF NOT EXISTS `tbl_contest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` INT( 11 ) NOT NULL COMMENT  '赛事状态1开启,0隐藏',
  `matchName` varchar(50) NOT NULL COMMENT '赛事名称',
  `matchDesc` text NOT NULL COMMENT '赛事描述',
  `matchStime` int(11) NOT NULL COMMENT '开启时间',
  `matchEtime` int(11) NOT NULL COMMENT '结束时间',
  `matchSelt` int(11) NOT NULL COMMENT '大赛评选',
  `matchAard` int(11) NOT NULL COMMENT '大赛颁奖',
  `matchSite` varchar(200) NOT NULL COMMENT '比赛场地',
  `matchRule` TEXT NOT NULL COMMENT  '比赛规程',
  `matchRewad` TEXT NOT NULL COMMENT  '比赛奖励',
  PRIMARY KEY (`id`),
  KEY `status`  (  `status` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='赛事表' AUTO_INCREMENT=1 ;");

/**比赛题目表**/
create_table("tbl_event","CREATE TABLE IF NOT EXISTS `tbl_event` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `examId` int(11) unsigned NOT NULL COMMENT '题库id',
  `eventState` tinyint(2) NOT NULL COMMENT '赛题状态',
  `isUser` int(11) NOT NULL COMMENT '创建用户',
  `isShow` int(5) NOT NULL COMMENT '显示顺序',
  `sTime` int(11) NOT NULL COMMENT '创建时间',
  `matchId` int(10) NOT NULL COMMENT '所属赛事',
  PRIMARY KEY (`id`),
  KEY `examId` (`examId`),
  KEY `eventState` (`eventState`,`isShow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='比赛题目表' AUTO_INCREMENT=1 ;");

/**题库表**/
create_table("tbl_exam","CREATE TABLE IF NOT EXISTS `tbl_exam` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `exam_Name` VARCHAR( 1000 ) NOT NULL COMMENT  '题目名称',
  `examStime` int(11) NOT NULL COMMENT '创建时间',
  `examBranch` int(11) NOT NULL COMMENT '分数',
  `examDesc` TEXT NOT NULL COMMENT '考题内容',
  `isKey` tinyint(2) NOT NULL COMMENT '是否有key',
  `examKey` varchar(100) NOT NULL COMMENT 'key值',
  `uploadText` varchar(100) NOT NULL COMMENT '上传文档地址',
  `isReport` tinyint(2) NOT NULL COMMENT '是否提交报告0为否',
  `isConso` tinyint(2) NOT NULL COMMENT '是否有控制台',
  `classId` int(11) unsigned NOT NULL COMMENT '所属分类',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `examStime` (`examStime`),
  KEY `classId` (`classId`),
  KEY `classId_2` (`classId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='题库表' AUTO_INCREMENT=1 ;");

/**FAQ表**/
create_table("tbl_faq","CREATE TABLE IF NOT EXISTS `tbl_faq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` TEXT NOT NULL COMMENT  '问题',
  `answer` TEXT NOT NULL COMMENT '答案',
  `sequence` INT( 11 ) NULL DEFAULT  '0' COMMENT  '显示顺序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='FAQ表' AUTO_INCREMENT=1 ;");

/**历史记录表**/
create_table("tbl_history","CREATE TABLE IF NOT EXISTS `tbl_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `history_name` varchar(50) NOT NULL COMMENT '题目',
  `history_text` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='历史记录表' AUTO_INCREMENT=1 ;");

/**比赛成绩表**/
create_table("tbl_match","CREATE TABLE IF NOT EXISTS `tbl_match` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `gid` int(11) unsigned NOT NULL COMMENT '战队id',
  `fraction` int(5) NOT NULL COMMENT '比赛得分',
  `state` tinyint(4) NOT NULL COMMENT '比赛状态',
  `event_id` int(11) unsigned NOT NULL COMMENT '比赛题目id',
  `stime` int(11) NOT NULL COMMENT '进入时间',
  `etime` int(11) NOT NULL COMMENT '提交时间',
  `answer` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '提交答案',
  `report` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '提交报告',
  `Even_tion` TINYINT( 4 ) NOT NULL COMMENT  '区分战队内分数',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`gid`,`fraction`,`event_id`,`stime`,`etime`,`Even_tion`),
  KEY `even_key` (`event_id`),
  KEY `grou_key` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='比赛成绩表' AUTO_INCREMENT=1 ;");

/**赛事奖励表**/
create_table("tbl_Reward","CREATE TABLE IF NOT EXISTS `tbl_Reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `match_id` int(11) unsigned NOT NULL COMMENT '赛事id',
  `grade` int(11) NOT NULL COMMENT '奖励等级',
  `reDesc` varchar(50) NOT NULL COMMENT '奖励中文描述',
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `grade` (`grade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='赛事奖励表' AUTO_INCREMENT=1 ;");

/**战队信息表**/
create_table("tbl_team","CREATE TABLE IF NOT EXISTS `tbl_team` (
  `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `teamNode` varchar(20) NOT NULL COMMENT '战队编号',
  `teamName` varchar(20) NOT NULL COMMENT '战队名称',
  `teamAdmin` int(11) NOT NULL COMMENT '战队负责人',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='战队信息表';");

/**申请战队表**/
create_table("tbl_cation","CREATE TABLE IF NOT EXISTS `tbl_cation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teamId` int(11) NOT NULL COMMENT '战队id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `status` int(2) NOT NULL COMMENT '0申请状态1:成功2:失败3:过期',
  PRIMARY KEY (`id`),
  KEY `teamId` (`teamId`,`user_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='申请战队表' AUTO_INCREMENT=1 ;");

/**赛事档案表**/
create_table("tbl_archives","CREATE TABLE IF NOT EXISTS `tbl_archives` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL COMMENT '标题',
  `files` varchar(50) NOT NULL COMMENT '文件',
  `archivesUser` varchar(50) DEFAULT NULL COMMENT '存档人',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='赛事档案表' AUTO_INCREMENT=1 ;");

create_table("cn_mation","CREATE TABLE IF NOT EXISTS `cn_mation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attack_ip` varchar(30) DEFAULT '' ,
  `attack_host_ip` varchar(30) DEFAULT '' ,
  `prote_ip` varchar(30) DEFAULT '' ,
  `status` tinyint(2) DEFAULT NULL ,
  `job_id` int(10) NOT NULL ,
  `attack_user` int(10) DEFAULT NULL,
  `steal_key` varchar(200) DEFAULT '' ,
  `steal_flag` varchar(200) DEFAULT '' ,
  `key_score` int(100) DEFAULT NULL ,
  `flag_score` int(100) DEFAULT NULL ,
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`job_id`,`steal_key`,`steal_flag`),
  KEY `attack_user` (`attack_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1  AUTO_INCREMENT=1 ;");

create_table("user","ALTER TABLE  `user` ADD  `teamId` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '所属战队id' AFTER  `status`,ADD INDEX ( `teamId` );");
create_table("tbl_match","ALTER TABLE  `tbl_match` CHANGE  `Even_tion`  `Even_tion` TINYINT( 4 ) NOT NULL COMMENT  '1:第一个答对,2:答对了但不是第一个,3:打错了,4:审核中';");

create_table("cn_massage","CREATE TABLE IF NOT EXISTS `cn_massage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL ,
  `rules` text NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
create_table("cn_org","CREATE TABLE IF NOT EXISTS `cn_org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org` varchar(128) NOT NULL,
  `passport` text NOT NULL,
  `roe` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
create_table("cn_vmmanage","CREATE TABLE IF NOT EXISTS `cn_vmmanage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nt` varchar(128) NOT NULL ,
  `vmdisk` varchar(128) NOT NULL ,
  `org` int(11) NOT NULL,
  `type` int(1) NOT NULL ,
  `ip` varchar(128) NOT NULL ,
  `luse` varchar(128) NOT NULL ,
  `lpasswd` varchar(128) NOT NULL ,
  `status` int(1) NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");