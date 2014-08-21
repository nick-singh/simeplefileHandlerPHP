<?php

class FileHandler{
	
	private $path;
	protected static $instance = null;		

	protected  function __construct(){
		
		// path to admin/
		$this_dir = dirname(__FILE__);
	
		// admin's parent dir path can be represented by admin/..
		$parent_dir = realpath($this_dir . '/..');
	
		// concatenate the target path from the parent dir path
		$this->path = $parent_dir . '/api/duplicates/';  		 
	}

	public static function getInstance(){
	
		if (!isset(static::$instance)) {
		static::$instance = new static;
		}
		return static::$instance;
	}


    public function createFile($userid){
	// timestamp when file was created
	$date = date('Y_m_d');
	
	// concatenate the target path from the parent dir path
	$target_path = $this->path.$date."_user_".$userid.".txt";		    	
	
	return $target_path;  		
    }


	public function addData($file, $data){		
	
		try {			
			// file_put_contents($file,json_encode($data),FILE_APPEND);
			file_put_contents($file,json_encode($data));
			return true;
		} catch (Exception $e) {
			echo $e;
			return false;
		}	
	}

	public function appendData($file, $data){		
	    $temp = json_decode(file_get_contents($file));
		foreach ($data as $key => $value) {
		    	array_push($temp, $value);	
		}    	
		try {			
			file_put_contents($file,json_encode($temp));			
			return true;
		} catch (Exception $e) {
			echo $e;
			return false;
		}	
	}

    public function openFile($file){
	return json_decode(file_get_contents($this->path.$file));
    }

    public function getLast(){

    	$files = scandir($this->path, 1);
	$newest_file = $files[0];				
	return $this->path.$newest_file;
    }

    public function findFile($name){
    	$ar = $this->readFiles();

    	if($ar[0] !== ".."){
	    	for($i = 0; $i< count($ar)-2; $i++){	    
	    		if($this->path.$ar[$i] === $name) return true;	    		
	    	}
	    	return false;
    	}else return false;
    }

    public function readFiles(){
    	$files = scandir($this->path,1);  
    	return $files;
    }


    public function removeFile($file){
    	unlink($this->path.$file);
    }
}
