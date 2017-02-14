<?php
/*
 ==============================================================================
 导出文件库
 ==============================================================================
 */
/**
 ==============================================================================
 *	This is the export library for ZLMS.
 *	Include/require it in your code to use its functionality.
 *	@package zllms.library
 ==============================================================================
 */
require_once ('document.lib.php');
require_once("PHPExcel.php");
require_once("PHPExcel/Writer/PDF.php");
require_once("PHPExcel/Writer/Excel2007.php");
require_once("PHPExcel/Writer/Excel5.php");
require_once("PHPExcel/Writer/CSV.php");
require_once("PHPExcel/Reader/Excel2007.php");
require_once("PHPExcel/Reader/Excel5.php");
require_once("PHPExcel/Reader/CSV.php");

class Export
{
	static $alpha=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD');

	/**
	 * Export tabular data to CSV-file
	 * @param array $data
	 * @param string $filename
	 */
	function export_table_csv0($data, $filename = 'export')
	{
		$file = api_get_path(SYS_ARCHIVE_PATH).uniqid('').'.csv';
		$handle = fopen($file, 'a+');
		if(is_array($data))
		{
			foreach ($data as $index => $row)
			{
				//fwrite($handle, '"'.implode('", "', $row).'"'."\n");
				fwrite($handle, implode(',', $row)."\n");
			}
		}
		fclose($handle);
		DocumentManager :: file_send_for_download($file, true, $filename.'.csv');
		exit;
	}

	/**
	 * 导出CSV,解决逗号问题
	 *
	 * @param unknown_type $data
	 * @param unknown_type $filename
	 * @since 1.8.6
	 */
	function export_table_csv($data, $filename = 'export')
	{
		$file = api_get_path(SYS_ARCHIVE_PATH).uniqid('').'.csv';
		$handle = @fopen($file, 'a+');

		if(is_array($data))
		{
			foreach ($data as $index => $row)
			{
				$line='';
				if(is_array($row))
				{
					foreach($row as $value)
					{
						$line .= '"'.str_replace('"','""',$value).'",';
					}
				}
				@fwrite($handle, $line."\n");
			}
		}
		@fclose($handle);
		DocumentManager :: file_send_for_download($file, true, $filename.'.csv');
		exit();
	}


	/**
	 * Export tabular data to XLS-file
	 * @param array $data
	 * @param string $filename
	 */
	function export_table_xls($data, $filename = 'export')
	{
		$file = api_get_path(SYS_ARCHIVE_PATH).'/'.uniqid('').'.xls';
		$handle = fopen($file, 'a+');
		foreach ($data as $index => $row)
		{
			fwrite($handle, implode("\t", $row)."\n");
		}
		fclose($handle);
		DocumentManager :: file_send_for_download($file, true, $filename.'.xls');
		exit;
	}
	
	

	function export_data($data, $filename = 'export', $type = 'xls', $AutoSize = true) {
		
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setCreator ( "ZLMS System" );
		$objPHPExcel->getProperties ()->setLastModifiedBy ( api_get_user_name () );
		$objPHPExcel->getProperties ()->setTitle ( "ZLMS Export Document" );
		$objPHPExcel->getProperties ()->setSubject ( "ZLMS Export" );
		$objPHPExcel->getProperties ()->setDescription ( "WebCS Export" );
		$objPHPExcel->getProperties ()->setKeywords ( "office 2007 openxml document" );
		$objPHPExcel->getProperties ()->setCategory ( "ZLMS.ORG" );
		
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objActiveSheet = $objPHPExcel->getActiveSheet ();
		$objActiveSheet->setTitle ( "Export_" . date ( 'Y-m-d' ) );
		
		$index_y = 1;
		foreach ( $data as $index => $row ) { //$index 行索引号
			$index_x = 0;
			foreach ( $row as $key => $row_val ) { //一行
				$cell = self::$alpha [$index_x] . strval ( $index_y );
				$cell_val = strval ( $row [$key] );
				$objActiveSheet->setCellValueExplicit ( $cell, $cell_val, PHPExcel_Cell_DataType::TYPE_STRING );
				$objActiveSheet->getColumnDimension ( self::$alpha [$index_x] )->setAutoSize ( $AutoSize );
				$index_x ++;
			}
			$index_y ++;
		}
		
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		switch ($type) {
			case 'xlsx' :
				$objWriter = new PHPExcel_Writer_Excel2007 ( $objPHPExcel );
				$objWriter->setOffice2003Compatibility ( true );
				break;
			case 'csv' :
				$objWriter = new PHPExcel_Writer_CSV ( $objPHPExcel );
				$objWriter->setDelimiter ( ',' );
				$objWriter->setEnclosure ( '"' );
				$objWriter->setLineEnding ( "\r\n" );
				$objWriter->setSheetIndex ( 0 );
				break;
			case 'xls' :
				$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
				break;
			case 'pdf' :
				$objWriter = new PHPExcel_Writer_PDF ( $objPHPExcel );
				break;
		}
		
		//$file = api_get_path ( SYS_ARCHIVE_PATH ) . get_unique_name () . "." . $type;
		$file = api_get_path ( SYS_ARCHIVE_PATH ) . $filename . "." . $type;
		$objWriter->save ( $file );
		DocumentManager::file_send_for_download ( $file, true, $filename . "." . $type );
		exit ();
	}
	

	function export_table_data($data,$filename='export',$type='xls',$AutoSize=true){

		$objPHPExcel=new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("WebCS System");
		$objPHPExcel->getProperties()->setLastModifiedBy(api_get_user_name());
		$objPHPExcel->getProperties()->setTitle("WebCS Export Document");
		$objPHPExcel->getProperties()->setSubject("WebCS Export");
		$objPHPExcel->getProperties()->setDescription("WebCS Export");
		$objPHPExcel->getProperties()->setKeywords("office 2007 openxml document");
		$objPHPExcel->getProperties()->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0);
		$objActiveSheet=$objPHPExcel->getActiveSheet();
		$objActiveSheet->setTitle("Export".date('Y-m-d'));

		$data_header=$data[0];
		$column_cnt=count($data_header);

		$header_index=0;
		foreach($data_header as $header_val){
			$cell=self::$alpha[$header_index]."1";
			$cell_val=strval($header_val);
			$objActiveSheet->setCellValue($cell, $cell_val);
			$objActiveSheet->getColumnDimension(self::$alpha[$header_index])->setAutoSize($AutoSize);
			$header_index++;
		}

		array_shift($data);

		$index_y=2;
		foreach ($data as $index => $row)
		{
			$index_x=0;

			foreach($row as $key=>$row_val){

				if($key==$data_header[$index_x]){
					$cell=self::$alpha[$index_x].strval($index_y);
					$cell_val=strval($row[$key]);
					//$objActiveSheet->setCellValue($cell, $cell_val);
					$objActiveSheet->setCellValueExplicit($cell,$cell_val,PHPExcel_Cell_DataType::TYPE_STRING);
					$objActiveSheet->getColumnDimension(self::$alpha[$index_x])->setAutoSize($AutoSize);
					$index_x++;
				}


			}
			$index_y++;
		}

		$objPHPExcel->setActiveSheetIndex(0);

		switch($type){
			case 'xlsx':
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->setOffice2003Compatibility(true);
				break;
			case 'csv':
				$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
				$objWriter->setDelimiter(',');
				$objWriter->setEnclosure('"');
				$objWriter->setLineEnding("\r\n");
				$objWriter->setSheetIndex(0);
				break;
			case 'xls':
				$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
				break;
			case 'pdf':
				$objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
				break;
		}

		$file = api_get_path(SYS_ARCHIVE_PATH).get_unique_name().".".$type;
		$objWriter->save($file);
		DocumentManager :: file_send_for_download($file, true, $filename.".".$type);
		exit;
	}


	/**
	 * Export tabular data to XML-file
	 * @param array $data
	 * @param string $filename
	 */
	function export_table_xml($data, $filename = 'export', $item_tagname = 'item', $wrapper_tagname = null)
	{
		$file = api_get_path(SYS_ARCHIVE_PATH).'/'.get_unique_name().'.xml';
		$handle = fopen($file, 'a+');
		// hgz 20070612
		/*fwrite($handle, '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n");*/
		fwrite($handle, "<?xml version='1.0' encoding='" . SYSTEM_CHARSET . "'?>" . "\n");
		if (!is_null($wrapper_tagname))
		{
			fwrite($handle, "\t".'<'.$wrapper_tagname.'>'."\n");
		}
		foreach ($data as $index => $row)
		{
			fwrite($handle, '<'.$item_tagname.'>'."\n");
			foreach ($row as $key => $value)
			{
				fwrite($handle, "\t\t".'<'.$key.'>'.$value.'</'.$key.'>'."\n");
			}
			fwrite($handle, "\t".'</'.$item_tagname.'>'."\n");
		}
		if (!is_null($wrapper_tagname))
		{
			fwrite($handle, '</'.$wrapper_tagname.'>'."\n");
		}
		fclose($handle);
		DocumentManager :: file_send_for_download($file, true, $filename.'.xml');
		exit;
	}
}
/*
 ==============================================================================
 FUNCTIONS
 ==============================================================================
 */
/**
 * Backup a db to a file
 *
 * @param ressource	$link			lien vers la base de donnees
 * @param string	$db_name		nom de la base de donnees
 * @param boolean	$structure		true => sauvegarde de la structure des tables
 * @param boolean	$donnees		true => sauvegarde des donnes des tables
 * @param boolean	$format			format des donnees
 'INSERT' => des clauses SQL INSERT
 'CSV' => donnees separees par des virgules
 * @param boolean	$insertComplet	true => clause INSERT avec nom des champs
 * @param boolean	$verbose 		true => comment are printed
 * @deprecated Function only used in deprecated function makeTheBackup(...)
 */
function backupDatabase($link, $db_name, $structure, $donnees, $format = 'SQL', $whereSave = '.', $insertComplet = '', $verbose = false)
{
	$errorCode = "";
	if (!is_resource($link))
	{
		GLOBAL $error_msg, $error_no;
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] link is not a ressource";
		$error_no["backup"][] = "1";
		return false;
	}
	mysql_select_db($db_name);
	$format = strtolower($format);
	$filename = $whereSave."/courseDbContent.".$format;
	$format = strtoupper($format);
	$fp = fopen($filename, "w");
	if (!is_resource($fp))
	return false;
	// liste des tables
	$res = mysql_list_tables($db_name, $link);
	$num_rows = mysql_num_rows($res);
	$i = 0;
	while ($i < $num_rows)
	{
		$tablename = mysql_tablename($res, $i);

		if ($format == "PHP")
		fwrite($fp, "\nmysql_query(\"");
		if ($format == "HTML")
		fwrite($fp, "\n<h2>$tablename</h2><table border=\"1\" width=\"100%\">");
		if ($verbose)
		echo "[".$tablename."] ";
		if ($structure === true)
		{
			if ($format == "PHP" || $format == "SQL")
			fwrite($fp, "DROP TABLE IF EXISTS `$tablename`;");
			if ($format == "PHP")
			fwrite($fp, "\");\n");
			if ($format == "PHP")
			fwrite($fp, "\nmysql_query(\"");
			// requete de creation de la table
			$query = "SHOW CREATE TABLE `".$tablename."`";
			$resCreate = mysql_query($query);
			$row = mysql_fetch_array($resCreate);
			$schema = $row[1].";";
			if ($format == "PHP" || $format == "SQL")
			fwrite($fp, "$schema");
			if ($format == "PHP")
			fwrite($fp, "\");\n\n");
		}
		if ($donnees === true)
		{
			// les donn�es de la table
			$query = "SELECT * FROM $tablename";
			$resData = mysql_query($query);
			if (mysql_num_rows($resData) > 0)
			{
				$sFieldnames = "";
				if ($insertComplet === true)
				{
					$num_fields = mysql_num_fields($resData);
					for ($j = 0; $j < $num_fields; $j ++)
					{
						$sFieldnames .= "`".mysql_field_name($resData, $j)."`, ";
					}
					$sFieldnames = "(".substr($sFieldnames, 0, -2).")";
				}
				$sInsert = "INSERT INTO `$tablename` $sFieldnames values ";
				while ($rowdata = mysql_fetch_assoc($resData))
				{
					if ($format == "HTML")
					{
						$lesDonnees = "\n\t<tr>\n\t\t<td>".implode("\n\t\t</td>\n\t\t<td>", $rowdata)."\n\t\t</td></tr>";
					}
					if ($format == "SQL" || $format == "PHP")
					{
						$lesDonnees = "<guillemet>".implode("<guillemet>,<guillemet>", $rowdata)."<guillemet>";
						$lesDonnees = str_replace("<guillemet>", "'", addslashes($lesDonnees));
						if ($format == "SQL")
						{
							$lesDonnees = $sInsert." ( ".$lesDonnees." );";
						}
						if ($format == "PHP")
						fwrite($fp, "\nmysql_query(\"");
					}
					fwrite($fp, "$lesDonnees");
					if ($format == "PHP")
					fwrite($fp, "\");\n");
				}
			}
		}
		$i ++;
		if ($format == "HTML")
		fwrite($fp, "\n</table>\n<hr />\n");
	}
	echo "fin du backup au  format :".$format;
	fclose($fp);
}

/**
 * @deprecated use function copyDirTo($origDirPath, $destination) in
 * fileManagerLib.inc.php
 */
function copydir($origine, $destination, $verbose = false)
{
	$dossier = @ opendir($origine) or die("<HR>impossible d'ouvrir ".$origine." [".__LINE__."]");
	if ($verbose)
	echo "<BR> $origine -> $destination";

	mkpath($destination, 0770);
	if ($verbose)
	echo "<strong>[".basename($destination)."]	</strong><OL>";
	$total = 0;
	while ($fichier = readdir($dossier))
	{
		$l = array ('.', '..');
		if (!in_array($fichier, $l))
		{
			if (is_dir($origine."/".$fichier))
			{
				if ($verbose)
				echo "<LI>";
				$total += copydir("$origine/$fichier", "$destination/$fichier", $verbose);
			}
			else
			{
				copy("$origine/$fichier", "$destination/$fichier");
				if ($verbose)
				echo "
				<LI>
				$fichier";
				$total ++;
			}
			if ($verbose)
			echo "</LI>";
		}
	}
	if ($verbose)
	echo "</OL>";
	return $total;
}


/**
 * Export a course to a zip file
 *
 * @param integer	$currentCourseID	needed		sysId Of course to be exported
 * @param boolean 	$verbose_backup		def FALSE	echo  step of work
 * @param string	$ignore				def NONE 	// future param  for selected bloc to export.
 * @param string	$formats			def ALL		ALL,SQL,PHP,XML,CSV,XLS,HTML
 *
 * @deprecated Function not in use (old backup system)
 *
 * 1� Check if all data needed are aivailable
 * 2� Build the archive repository tree
 * 3� Build exported element and Fill  the archive repository tree
 * 4� Compress the tree
 == tree structure ==				== here we can found ==
 /archivePath/						temporary files of export for the current claroline
 /$exportedCourseId				temporary files of export for the current course
 /$dateBackuping/			root of the future archive
 archive.ini				course properties
 readme.txt
 /originalDocs
 /html
 /sql
 /csv
 /xml
 /php
 ;

 about "ignore"
 As  we don't know what is  add in course  by the local admin  of  claroline,
 I  prefer follow the  logic : save all except ...


 */
function makeTheBackup($exportedCourseId, $verbose_backup = FALSE, $ignore = "", $formats = "ALL")
{
	global $error_msg, $error_no, $db, $archiveRepositorySys, $archiveRepositoryWeb,
	$appendCourse, $appendMainDb, $archiveName, $_configuration, $_course, $TABLEUSER, $TABLECOURSUSER, $TABLECOURS, $TABLEANNOUNCEMENT;

	// ****** 1� 2. params.
	$errorCode = 0;
	$stop = FALSE;

	// ****** 1� 2. 1 params.needed
	if (!isset ($exportedCourseId))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] Course Id Missing";
		$error_no["backup"][] = "1";
		$stop = TRUE;
	}
	if (!isset ($_configuration['main_database']))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] Main Db name is Missing";
		$error_no["backup"][] = "2";
		$stop = TRUE;
	}
	if (!isset ($archiveRepositorySys))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] archive Path not found";
		$error_no["backup"][] = "3";
		$stop = TRUE;
	}
	if (!isset ($appendMainDb))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] where place course datas from main db in archive";
		$error_no["backup"][] = "4";
		$stop = TRUE;
	}
	if (!isset ($appendCourse))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] where place course datas in archive";
		$error_no["backup"][] = "5";
		$stop = TRUE;
	}
	if (!isset ($TABLECOURS))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] name of table of course not defined";
		$error_no["backup"][] = "6";
		$stop = TRUE;
	}
	if (!isset ($TABLEUSER))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] name of table of users not defined";
		$error_no["backup"][] = "7";
		$stop = TRUE;
	}
	if (!isset ($TABLECOURSUSER))
	{
		$error_msg["backup"][] = "[".basename(__FILE__)."][".__LINE__."] name of table of subscription of users in courses not defined";
		$error_no["backup"][] = "8";
		$stop = TRUE;
	}
	if ($stop)
	{
		return false;
	}

	// ****** 1� 2. 2 params.optional
	if (!isset ($verbose_backup))
	{
		$verbose_backup = false;
	}

	// ****** 1� 3. check if course exist
	//  not  done
	//////////////////////////////////////////////
	// ****** 2� Build the archive repository tree
	// ****** 2� 1. fix names
	$shortDateBackuping = date("YzBs"); // YEAR - Day in Year - Swatch - second
	$archiveFileName = "archive.".$exportedCourseId.".".$shortDateBackuping.".zip";
	$dateBackuping = $shortDateBackuping;
	$archiveDir .= $archiveRepositorySys.$exportedCourseId."/".$shortDateBackuping."/";
	$archiveDirOriginalDocs = $archiveDir."originalDocs/";
	$archiveDirHtml = $archiveDir."HTML/";
	$archiveDirCsv = $archiveDir."CSV/";
	$archiveDirXml = $archiveDir."XML/";
	$archiveDirPhp = $archiveDir."PHP/";
	$archiveDirLog = $archiveDir."LOG/";
	$archiveDirSql = $archiveDir."SQL/";
	$systemFileNameOfArchive = "claroBak-".$exportedCourseId."-".$dateBackuping.".txt";
	$systemFileNameOfArchiveIni = "archive.ini";
	$systemFileNameOfReadMe = "readme.txt";
	$systemFileNameOfarchiveLog = "readme.txt";
	###################
	if ($verbose_backup)
	{
		echo "<hr><u>", get_lang('ArchiveName'), "</u> : ", "<strong>", basename($systemFileNameOfArchive), "</strong><br><u>", get_lang('ArchiveLocation'), "</u> : ", "<strong>", realpath($systemFileNameOfArchive), "</strong><br><u>", get_lang('SizeOf'), " ", realpath("../../".$exportedCourseId."/"), "</u> : ", "<strong>", DirSize("../../".$exportedCourseId."/"), "</strong> bytes <br>";
		if (function_exists(diskfreespace))
		echo "<u>".get_lang('DiskFreeSpace')."</u> : <strong>".diskfreespace("/")."</strong> bytes";
		echo "<hr />";
	}
	mkpath($archiveDirOriginalDocs.$appendMainDb, $verbose_backup);
	mkpath($archiveDirHtml.$appendMainDb, $verbose_backup);
	mkpath($archiveDirCsv.$appendMainDb, $verbose_backup);
	mkpath($archiveDirXml.$appendMainDb, $verbose_backup);
	mkpath($archiveDirPhp.$appendMainDb, $verbose_backup);
	mkpath($archiveDirLog.$appendMainDb, $verbose_backup);
	mkpath($archiveDirSql.$appendMainDb, $verbose_backup);
	mkpath($archiveDirOriginalDocs.$appendCourse, $verbose_backup);
	mkpath($archiveDirHtml.$appendCourse, $verbose_backup);
	mkpath($archiveDirCsv.$appendCourse, $verbose_backup);
	mkpath($archiveDirXml.$appendCourse, $verbose_backup);
	mkpath($archiveDirPhp.$appendCourse, $verbose_backup);
	mkpath($archiveDirLog.$appendCourse, $verbose_backup);
	mkpath($archiveDirSql.$appendCourse, $verbose_backup);
	$dirCourBase = $archiveDirSqlCourse;
	$dirMainBase = $archiveDirSqlMainDb;
	/////////////////////////////////////////////////////////////////////////
	// ****** 3� Build exported element and Fill  the archive repository tree
	if ($verbose_backup)
	echo "
				build config file
				<hr>";
	// ********************************************************************
	// build config file
	// ********************************************************************
	$stringConfig = "<?php
		/*
		      +----------------------------------------------------------------------+
		      ZLMS version ".$ZLMS_version."
		      +----------------------------------------------------------------------+
		      This file was generate by script ".$_SERVER['PHP_SELF']."
		      ".date("r")."                  |
		      +----------------------------------------------------------------------+
		      |   This program is free software; you can redistribute it and/or      |
		      |   modify it under the terms of the GNU General Public License        |
		      |   as published by the Free Software Foundation; either version 2     |
		*/

		// ZLMS Version was :  ".$ZLMS_version."
		// Source was  in ".realpath("../../".$exportedCourseId."/")."
		// find in ".$archiveDir."/courseBase/courseBase.sql sql to rebuild the course base
		// find in ".$archiveDir."/".$exportedCourseId." to content of directory of course

		/**
		 * options
		 ";
	$stringConfig .= "
		 */";
	// ********************************************************************
	// Copy of  from DB main
	// fields about this course
	// ********************************************************************
	//  info  about cours
	// ********************************************************************
	if ($verbose_backup)
	echo "
				<LI>
				".get_lang('BUCourseDataOfMainBase')."  ".$exportedCourseId."
				<HR>
				<PRE>";
	$sqlInsertCourse = "
		INSERT INTO course SET ";
	$csvInsertCourse = "\n";
	$iniCourse = "[".$exportedCourseId."]\n";
	$sqlSelectInfoCourse = "Select * from `".$TABLECOURS."` `course` where code = '".$exportedCourseId."' ";
	$resInfoCourse = api_sql_query($sqlSelectInfoCourse, __FILE__, __LINE__);
	$infoCourse = mysql_fetch_array($resInfoCourse);
	for ($noField = 0; $noField < mysql_num_fields($resInfoCourse); $noField ++)
	{
		if ($noField > 0)
		$sqlInsertCourse .= ", ";
		$nameField = mysql_field_name($resInfoCourse, $noField);
		/*echo "
		 <BR>
		 $nameField ->  ".$infoCourse["$nameField"]." ";
		 */
		$sqlInsertCourse .= "$nameField = '".$infoCourse["$nameField"]."'";
		$csvInsertCourse .= "'".addslashes($infoCourse["$nameField"])."';";
	}
	// 	buildTheIniFile
	$iniCourse .= "name=".strtr($infoCourse['title'], "()", "[]")."\n"."official_code=".strtr($infoCourse['visual_code'], "()", "[]")."\n".// use in echo
	"adminCode=".strtr($infoCourse['code'], "()", "[]")."\n".// use as key in db
	"path=".strtr($infoCourse['code'], "()", "[]")."\n".// use as key in path
	"dbName=".strtr($infoCourse['code'], "()", "[]")."\n".// use as key in db list
	"titular=".strtr($infoCourse['titulaire'], "()", "[]")."\n"."language=".strtr($infoCourse['language'], "()", "[]")."\n"."extLinkUrl=".strtr($infoCourse['departementUrl'], "()", "[]")."\n"."extLinkName=".strtr($infoCourse['departementName'], "()", "[]")."\n"."categoryCode=".strtr($infoCourse['faCode'], "()", "[]")."\n"."categoryName=".strtr($infoCourse['faName'], "()", "[]")."\n"."visibility=". ($infoCourse['visibility'] == 2 || $infoCourse['visibility'] == 3)."registrationAllowed=". ($infoCourse['visibility'] == 1 || $infoCourse['visibility'] == 2);
	$sqlInsertCourse .= ";";
	//	echo $csvInsertCourse."<BR>";
	$stringConfig .= "
		# Insert Course
		#------------------------
		#	".$sqlInsertCourse."
		#------------------------
			";
	if ($verbose_backup)
	{
		echo "</PRE>";
	}
	$fcoursql = fopen($archiveDirSql.$appendMainDb."course.sql", "w");
	fwrite($fcoursql, $sqlInsertCourse);
	fclose($fcoursql);
	$fcourcsv = fopen($archiveDirCsv.$appendMainDb."course.csv", "w");
	fwrite($fcourcsv, $csvInsertCourse);
	fclose($fcourcsv);
	$fcourini = fopen($archiveDir.$systemFileNameOfArchiveIni, "w");
	fwrite($fcourini, $iniCourse);
	fclose($fcourini);
	echo $iniCourse, " ini Course";
	// ********************************************************************
	//  info  about users
	// ********************************************************************
	//	if ($backupUser )
	{
		if ($verbose_backup)
		echo "
								<LI>
									".get_lang('BUUsersInMainBase')." ".$exportedCourseId."
									<hR>
								<PRE>";
		// recup users
		$sqlUserOfTheCourse = "
					SELECT
						`user`.*
						FROM `".$TABLEUSER."`, `".$TABLECOURSUSER."`
						WHERE `user`.`user_id`=`".$TABLECOURSUSER."`.`user_id`
							AND `".$TABLECOURSUSER."`.`course_code`='".$exportedCourseId."'";
		$resUsers = api_sql_query($sqlUserOfTheCourse, __FILE__, __LINE__);
		$nbUsers = mysql_num_rows($resUsers);
		if ($nbUsers > 0)
		{
			$nbFields = mysql_num_fields($resUsers);
			$sqlInsertUsers = "";
			$csvInsertUsers = "";
			$htmlInsertUsers = "<table>\t<TR>\n";
			//
			// creation of headers
			//
			for ($noField = 0; $noField < $nbFields; $noField ++)
			{
				$nameField = mysql_field_name($resUsers, $noField);
				$csvInsertUsers .= "'".addslashes($nameField)."';";
				$htmlInsertUsers .= "\t\t<TH>".$nameField."</TH>\n";
			}
			$htmlInsertUsers .= "\t</TR>\n";
			//
			// creation of body
			//
			while ($users = mysql_fetch_array($resUsers))
			{
				$htmlInsertUsers .= "\t<TR>\n";
				$sqlInsertUsers .= "
										INSERT IGNORE INTO user SET ";
				$csvInsertUsers .= "\n";
				for ($noField = 0; $noField < $nbFields; $noField ++)
				{
					if ($noField > 0)
					$sqlInsertUsers .= ", ";
					$nameField = mysql_field_name($resUsers, $noField);
					/*echo "
						<BR>
						$nameField ->  ".$users["$nameField"]." ";
						*/
					$sqlInsertUsers .= "$nameField = '".$users["$nameField"]."' ";
					$csvInsertUsers .= "'".addslashes($users["$nameField"])."';";
					$htmlInsertUsers .= "\t\t<TD>".$users["$nameField"]."</TD>\n";
				}
				$sqlInsertUsers .= ";";
				$htmlInsertUsers .= "\t</TR>\n";
			}
			$htmlInsertUsers .= "</TABLE>\n";
			$stringConfig .= "
							# INSERT Users
							#------------------------------------------
							#	".$sqlInsertUsers."
							#------------------------------------------
								";
			$fuserssql = fopen($archiveDirSql.$appendMainDb."users.sql", "w");
			fwrite($fuserssql, $sqlInsertUsers);
			fclose($fuserssql);
			$fuserscsv = fopen($archiveDirCsv.$appendMainDb."users.csv", "w");
			fwrite($fuserscsv, $csvInsertUsers);
			fclose($fuserscsv);
			$fusershtml = fopen($archiveDirHtml.$appendMainDb."users.html", "w");
			fwrite($fusershtml, $htmlInsertUsers);
			fclose($fusershtml);
		}
		else
		{
			if ($verbose_backup)
			{
				echo "<HR><div align=\"center\">NO user in this course !!!!</div><HR>";
			}
		}
		if ($verbose_backup)
		{
			echo "</PRE>";
		}
	}
	/*  End  of  backup user */
	if ($saveAnnouncement)
	{
		// ********************************************************************
		//  info  about announcment
		// ********************************************************************
		if ($verbose_backup)
		{
			echo "
							<LI>
								".get_lang('BUAnnounceInMainBase')." ".$exportedCourseId."
								<hR>
							<PRE>";
		}
		// recup annonce
		$sqlAnnounceOfTheCourse = "
				SELECT
					*
					FROM  `".$TABLEANNOUNCEMENT."`
					WHERE course_code='".$exportedCourseId."'";
		$resAnn = api_sql_query($sqlAnnounceOfTheCourse, __FILE__, __LINE__);
		$nbFields = mysql_num_fields($resAnn);
		$sqlInsertAnn = "";
		$csvInsertAnn = "";
		$htmlInsertAnn .= "<table>\t<TR>\n";
		//
		// creation of headers
		//
		for ($noField = 0; $noField < $nbFields; $noField ++)
		{
			$nameField = mysql_field_name($resUsers, $noField);
			$csvInsertAnn .= "'".addslashes($nameField)."';";
			$htmlInsertAnn .= "\t\t<TH>".$nameField."</TH>\n";
		}
		$htmlInsertAnn .= "\t</TR>\n";
		//
		// creation of body
		//
		while ($announce = mysql_fetch_array($resAnn))
		{
			$htmlInsertAnn .= "\t<TR>\n";
			$sqlInsertAnn .= "
						INSERT INTO users SET ";
			$csvInsertAnn .= "\n";
			for ($noField = 0; $noField < $nbFields; $noField ++)
			{
				if ($noField > 0)
				$sqlInsertAnn .= ", ";
				$nameField = mysql_field_name($resAnn, $noField);
				/*echo "
				 <BR>
				 $nameField ->  ".$users["$nameField"]." ";
				 */
				$sqlInsertAnn .= "$nameField = '".addslashes($announce["$nameField"])."' ";
				$csvInsertAnn .= "'".addslashes($announce["$nameField"])."';";
				$htmlInsertAnn .= "\t\t<TD>".$announce["$nameField"]."</TD>\n";
			}
			$sqlInsertAnn .= ";";
			$htmlInsertAnn .= "\t</TR>\n";
		}
		if ($verbose_backup)
		{
			echo "</PRE>";
		}
		$htmlInsertAnn .= "</TABLE>\n";
		$stringConfig .= "
				#INSERT ANNOUNCE
				#------------------------------------------
				#	".$sqlInsertAnn."
				#------------------------------------------
					";
		$fannsql = fopen($archiveDirSql.$appendMainDb."annonces.sql", "w");
		fwrite($fannsql, $sqlInsertAnn);
		fclose($fannsql);
		$fanncsv = fopen($archiveDirCsv.$appendMainDb."annnonces.csv", "w");
		fwrite($fanncsv, $csvInsertAnn);
		fclose($fanncsv);
		$fannhtml = fopen($archiveDirHtml.$appendMainDb."annonces.html", "w");
		fwrite($fannhtml, $htmlInsertAnn);
		fclose($fannhtml);
		/*  End  of  backup Annonces */
	}
	// we can copy file of course
	if ($verbose_backup)
	{
		echo '<li>'.get_lang('CopyDirectoryCourse');
	}
	$nbFiles = copydir(api_get_path(SYS_COURSE_PATH).$_course['path'], $archiveDirOriginalDocs.$appendCourse, $verbose_backup);
	if ($verbose_backup)
	echo "
							<strong>
								".$nbFiles."
							</strong>
							".get_lang('FileCopied')."
							<br>
						</li>";
	$stringConfig .= "
		// ".$nbFiles." was in ".realpath($archiveDirOriginalDocs);
	// ********************************************************************
	// Copy of  DB course
	// with mysqldump
	// ********************************************************************
	if ($verbose_backup)
	echo "
						<LI>
							".get_lang('BackupOfDataBase')." ".$exportedCourseId."  (SQL)
							<hr>";
	backupDatabase($db, $exportedCourseId, true, true, 'SQL', $archiveDirSql.$appendCourse, true, $verbose_backup);
	if ($verbose_backup)
	echo "
						</LI>
						<LI>
							".get_lang('BackupOfDataBase')." ".$exportedCourseId."  (PHP)
							<hr>";
	backupDatabase($db, $exportedCourseId, true, true, 'PHP', $archiveDirPhp.$appendCourse, true, $verbose_backup);
	if ($verbose_backup)
	echo "
						</LI>
						<LI>
							".get_lang('BackupOfDataBase')." ".$exportedCourseId."  (CSV)
							<hr>";
	backupDatabase($db, $exportedCourseId, true, true, 'CSV', $archiveDirCsv.$appendCourse, true, $verbose_backup);
	if ($verbose_backup)
	echo "
						<LI>
							".get_lang('BackupOfDataBase')." ".$exportedCourseId."  (HTML)
							<hr>";
	backupDatabase($db, $exportedCourseId, true, true, 'HTML', $archiveDirHtml.$appendCourse, true, $verbose_backup);
	if ($verbose_backup)
	echo "
						<LI>
							".get_lang('BackupOfDataBase')." ".$exportedCourseId."  (XML)
							<hr>";
	backupDatabase($db, $exportedCourseId, true, true, 'XML', $archiveDirXml.$appendCourse, true, $verbose_backup);
	if ($verbose_backup)
	echo "
						<LI>
							".get_lang('BackupOfDataBase')." ".$exportedCourseId."  (LOG)
							<hr>";
	backupDatabase($db, $exportedCourseId, true, true, 'LOG', $archiveDirLog.$appendCourse, true, $verbose_backup);
	// ********************************************************************
	// Copy of DB course
	// with mysqldump
	// ********************************************************************
	$fdesc = fopen($archiveDir.$systemFileNameOfArchive, "w");
	fwrite($fdesc, $stringConfig);
	fclose($fdesc);
	if ($verbose_backup)
	echo "
						</LI>
					</OL>

					<br>";
	///////////////////////////////////
	// ****** 4� Compress the tree
	if (extension_loaded("zlib"))
	{
		$whatZip[] = $archiveRepositorySys.$exportedCourseId."/".$shortDateBackuping."/HTML";
		$forgetPath = $archiveRepositorySys.$exportedCourseId."/".$shortDateBackuping."/";
		$prefixPath = $exportedCourseId;
		$zipCourse = new PclZip($archiveRepositorySys.$archiveFileName);
		$zipRes = $zipCourse->create($whatZip, PCLZIP_OPT_ADD_PATH, $prefixPath, PCLZIP_OPT_REMOVE_PATH, $forgetPath);
		if ($zipRes == 0)
		{
			echo "<font size=\"+1\" color=\"#FF0000\">", $zipCourse->errorInfo(true), "</font>";
		}
		else
		for ($i = 0; $i < sizeof($zipRes); $i ++)
		{
			for (reset($zipRes[$i]); $key = key($zipRes[$i]); next($zipRes[$i]))
			{
				echo "File $i / [$key] = ".$list[$i][$key]."<br>";
			}
			echo "<br>";
		}
		$pathToArchive = $archiveRepositoryWeb.$archiveFileName;
		if ($verbose_backup)
		{
			echo '<hr>'.get_lang('BuildTheCompressedFile');
		}
		//		removeDir($archivePath);
	}
	return 1;
} // function makeTheBackup()
?>