<?php

error_reporting(E_ALL);

class MiddleClass {

    const LOCAL = false;
    public $source = '';
    public $destination = '';
    public $file = '';
    public $baseFolder = '/home/soporteb/sac/';
    public $script = 'sac_replace_id.sh';
    public $param_one = ' -f ';
    public $param_two = ' -i ';
    public $param_three = ' -o ';
    public $prefix = '_new.tgz';
    public $folderResults = ' ./files_result/';

    public function __construct() {
        $this->enableCORS();
        $this->setHeaders();
        $this->getParams();
        $file = $this->uploadFile();
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

    public function execScript() {
    	// /home/soporteb/sac/sac_replace_id.sh -f FICHEIRO.tgz -i model_id_source -o model_id_destination
        $output = shell_exec($this->baseFolder . $this->script . $this->param_one . $this->file . $this->param_two . $this->source . $this->param_three);
        $cp = shell_exec('cp ' . $this->baseFolder . $this->file . $this->prefix . $this->folderResults . $this->baseFolder . $this->file . $this->prefix);
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
        $target_dir = "/home/soporteb/sac/";
        $fileName = basename($_FILES["dataType"]["name"]);
		$target_file = $target_dir . $fileName;
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if(isset($_POST["submit"])) {
		  $check = getimagesize($_FILES["dataType"]["tmp_name"]);
		  if($check !== false) {
		    echo "File is an image - " . $check["mime"] . ".";
		    $uploadOk = 1;
		  } else {
		    echo "File is not an image.";
		    $uploadOk = 0;
		  }
		}
		return $fileName;
    }
}

$class = new MiddleClass();
