<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'check_session'){
	$save = $crud->check_session();
	if($save)
		echo $save;
}
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'new_student'){
	$save = $crud->new_student();
	if($save)
		echo $save;
}
if($action == 'import_excel'){
	$save = $crud->import_excel();
	if($save)
		echo $save;
}
if($action == 'export_excel'){
	$save = $crud->export_excel();
	if($save)
		echo $save;
}
if($action == 'export_excel_folder'){
	$save = $crud->export_excel_folder();
	if($save)
		echo $save;
}
if($action == 'export_excel_year'){
	$save = $crud->export_excel_year();
	if($save)
		echo $save;
}
if($action == 'new_folder'){
	$save = $crud->new_folder();
	if($save)
		echo $save;
}
if($action == 'delete_folder_second'){
	$save = $crud->delete_folder_second();
	if($save)
		echo $save;
}
if($action == 'new_file'){
	$save = $crud->new_file();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}

if($action == 'delete_record'){
	$delete = $crud->delete_record();
	if($delete)
		echo $delete;
}

if($action == 'restore_record'){
	$save = $crud->restore_record();
	if($save)
		echo $save;
}

if($action == 'trash_record'){
	$delete = $crud->trash_record();
	if($delete)
		echo $delete;
}

if($action == 'restore_file'){
	$save = $crud->restore_file();
	if($save)
		echo $save;
}

if($action == 'trash_file'){
	$delete = $crud->trash_file();
	if($delete)
		echo $delete;
}

if($action == 'trash_multiple_records'){
	$delete = $crud->trash_multiple_records();
	if($delete)
		echo $delete;
}

if($action == 'delete_multiple_records'){
	$delete = $crud->delete_multiple_records();
	if($delete)
		echo $delete;
}

if($action == 'restore_multiple_records'){
	$delete = $crud->restore_multiple_records();
	if($delete)
		echo $delete;
}

if($action == 'trash_multiple_files'){
	$delete = $crud->trash_multiple_files();
	if($delete)
		echo $delete;
}

if($action == 'delete_multiple_files'){
	$delete = $crud->delete_multiple_files();
	if($delete)
		echo $delete;
}

if($action == 'restore_multiple_files'){
	$delete = $crud->restore_multiple_files();
	if($delete)
		echo $delete;
}

if($action == 'update_status'){
	$save = $crud->update_status();
	if($save)
		echo $save;
}

if($action == 'approve_multiple_records'){
	$save = $crud->approve_multiple_records();
	if($save)
		echo $save;
}

if($action == 'upload_file'){
	$save = $crud->upload_file();
	if($save)
		echo $save;
	// var_dump($_FILES);
}
if($action == 'remove_file'){
	$delete = $crud->remove_file();
	if($delete)
		echo $delete;
}

if($action == 'save_upload'){
	$save = $crud->save_upload();
	if($save)
		echo $save;
}

if($action == 'update_record'){
	$save = $crud->update_record();
	if($save)
		echo $save;
}

if($action == 'reset_password'){
	$save = $crud->reset_password();
	if($save)
		echo $save;
}

if($action == 'delete_file'){
	$delete = $crud->delete_file();
	if($delete)
		echo $delete;
}

if($action == 'delete_folder'){
	$delete = $crud->delete_folder();
	if($delete)
		echo $delete;
}

if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}

ob_end_flush();
?>
