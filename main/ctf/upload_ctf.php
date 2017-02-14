<?php

/* ==============================================================================
 CTF导入
 ==============================================================================*/
$language_file = array ('admin', 'registration' );
include ('../inc/global.inc.php');
api_protect_admin_script ();
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
function doAction() {
	if ($_FILES ['import_file'] ['size'] !== 0) {
		$save_path = $_FILES ['import_file'] ['tmp_name'];
		set_time_limit ( 0 );
		$file_type = getFileExt ( $_FILES ['import_file'] ['name'] );
		$file_type = strtolower ( $file_type );
		if ($file_type == 'bz2') {
			 parse_upload_data ( $save_path );
		} else {
			api_redirect ( 'upload_ctf.php?message=' . urlencode ( get_lang ( 'FileImported' ) ) );
		}
	}
}

function parse_upload_data($file) {
    $storage_url=URL_ROOT.'/www'.URL_APPEND.'storage/';
	$upload_ctf=move_uploaded_file($file,$storage_url.'ctf.tar.bz2');
    if($upload_ctf){
        $un_bz2='cd '.URL_ROOT.'/www'.URL_APPEND.'storage;rm -R attachment/ report/;chmod 777 ctf.tar.bz2;tar -jxf ctf.tar.bz2;rm ctf.tar.bz2';
        exec($un_bz2);
        $source="mysql -h".DB_HOST." -u".DB_USER." -p".DB_PWD." ".DB_NAME." < ".$storage_url."ctf.sql";
        exec($source);
        $del_ctf='rm '.$storage_url.'ctf.sql';
        exec($del_ctf);
        $download_url=URL_ROOT.'/www'.URL_APPEND.'storage/download_url';
        if(!file_exists($download_url)){
            mkdir($download_url,0777);
        }
        echo '<script>parent.location.href="exam_question.php"</script>';
    }

}

$form = new FormValidator ( 'upload_ctf' );

//选择文件
$form->addElement ( 'file', 'import_file', get_lang ( 'ImportFileLocation' ), array ('style' => "width:60%", 'class' => 'inputText' ) );
$form->addRule ( 'import_file', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$allowed_file_types = array ('bz2');
$form->addRule ( 'import_file', get_lang ( 'InvalidExtension' ) . ' (' . implode ( ',', $allowed_file_types ) . ')', 'filetype', $allowed_file_types );

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

Display::setTemplateBorder ( $form, '98%' );
$form->add_progress_bar(1);
if ($form->validate ()) {
    $errors = doAction ($import_dept);
    tb_close ( 'exam_question.php' );
}
$tool_name = get_lang ( 'ImportUserListXMLCSV' );
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name, FALSE );


$form->display ();

Display::display_footer ();
