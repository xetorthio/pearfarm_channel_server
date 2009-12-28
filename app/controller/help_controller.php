<?php
	
/**
	* @package controller
	*/
class HelpController extends \ApplicationController {

	public function before_filter() {
		$this->markdown_dir = FileUtils::join(NIMBLE_ROOT, 'app', 'view', 'help', 'markdown');
		$this->files = static::get_markdown_files($this->markdown_dir);
	}
	
	public static function get_markdown_files($dir) {
		$out = array();
		if (is_dir($dir)) {
	    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
	      if (preg_match('/\.markdown$/', $file)) {
	        $out[] = (string) $file;
	      }
	    }
	  }
	return $out;
	}


	public function show() {
		require_once(FileUtils::join(NIMBLE_ROOT, 'lib', 'markdown.php'));
		$filename = $_GET['name'] . '.markdown';
		if(array_include($filename, array_map(function($f){return basename($f);}, $this->files))) {
			$this->file = file_get_contents(FileUtils::join($this->markdown_dir, $filename));
		}else{
			Nimble::flash('notice', 'No page found for ' . $_GET['name']);
			$this->redirect_to(url_for('HelpController', 'index'));
		}
	}
	
	public function index() {
		ksort($this->files);
	}

}
?>