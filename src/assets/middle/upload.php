<?php

error_reporting(E_ALL);

class MiddleClass {

    const LOCAL = false;
    public $source = '';
    public $destination = '';
    public $file = '';
    public $baseFolder = '/home/soporteb/sac/';
    public $baseFolderFile = 'uploads/';
    public $script = 'sac_replace_id.sh';
    public $param_one = ' -f ';
    public $param_two = ' -i ';
    public $param_three = ' -o ';
    public $prefix = '.tgz';
    public $prefix_new = '_new.tgz';
    public $folderResults = ' ./files_result/';

    public function __construct() {
        $this->enableCORS();
        $this->setHeaders();
        $this->getParams();
    }

    public function run() {
        $this->file = $this->uploadFile();
        $this->execScript();
    }

    public function getParams() {
        $this->source = $_POST['source'];
		$this->destination = $_POST['destination'];
        return true;
    }

    public function setHeaders() {
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Headers: msisdn, pin, action, X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
        return true;
    }

    public function changeHeaders($path_file_result) {
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$path_file_result");
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: binary");
    }

    public function execScript() {
    	$path_file_source = $this->baseFolder. $this->baseFolderFile . $this->file;
    	$name_new_file = str_replace($this->prefix,'',$this->file) . $this->prefix_new;
    	$path_file_result = $this->baseFolder. $this->baseFolderFile .$name_new_file;
    	$path_download = ' ./uploads/' . $name_new_file;
    	$command = 'sh ' . $this->baseFolder . $this->script . $this->param_one . $path_file_source . $this->param_two . $this->source . $this->param_three . $this->destination;
        $output = shell_exec($command);
        if (file_exists($path_file_result)) {
        	$output = shell_exec('cp ' . $path_file_result . $path_download);

        	$path_file_result = basename($path_file_result);
			   
			echo json_encode(array('status' => 'ok', 'msg' => 'downloading...', 'link' => $path_file_result));
			 
			die();
		}
    }

    public function generate_timestamp() {
        $time = time();
        $seconds = $time / 1000;
        $remainder = round($seconds - ($seconds >> 0), 3) * 1000;
        return date('YmdHis', $time) . $remainder;
    }

    public function enableCORS() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');// cache for 1 day
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");		 
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
        return true;
    }

    public function uploadFile() {
        $target_dir = $this->baseFolder . $this->baseFolderFile;
        $fileName = basename($_FILES["file"]["name"]);
		$target_file = $target_dir . $fileName;
		$uploadOk = 1;
		//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
		return $fileName;
    }
}

$class = new MiddleClass();
$class->run();
