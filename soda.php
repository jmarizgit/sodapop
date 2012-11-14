<?php
/*
	SODAPOP - PHP done right
  created by MarizMelo (c) 2012
  http://github/jmarizgit/sodapop
*/

class Sodapop{
	//attributes
	private $filename;
	private $filearray;
	private $rawcontent;

	//methods
	function __construct($file){
		if( isset($file) && file_exists($file) ){
			$this->filename = $file;
			echo "Converting file \"".$this->filename."\"...\n";
			$this->rawcontent = file_get_contents($this->filename,"r");
		}else{
			echo "File not found\n";
			exit();
		}
	}

	function fileToArray(){
		$this->filearray = preg_split('/\r\n|\r|\n/', $this->rawcontent);
	}

	function displayArray(){
		$this->fileToArray();
	}

	function parseVariables(){
		//Special thanks to @stema for the regex
		//http://stackoverflow.com/questions/13374523/regex-match-character-in-between-strings-and-everywhere
		$this->rawcontent = preg_replace('/[^"\s]*@(?=[^"]*(?:"[^"]*"[^"]*)*$)/', "$", $this->rawcontent); 
		echo $this->rawcontent;
	}

	function parseEndLine(){
		$this->rawcontent = preg_replace('/\n/', ';', $this->rawcontent);
	}

	function compile(){
		$phpfile = preg_replace("/\.soda/",".php",$this->filename);
		$handle = fopen($phpfile, 'w') or die("can't open file");
		file_put_contents($phpfile, $this->rawcontent);
		fclose($handle);
	}

	function wrapper(){
		$this->rawcontent = "<?php\n".$this->rawcontent."\n?>";
	}

	function checkSodaExtension(){
		//check filename extension ".soda" otherwise ignore
	}

	function parsePrintEcho(){
		//replace print with echo
		$this->rawcontent = preg_replace("/print/","echo", $this->rawcontent);
	}

}//class

//console or webview
if($argv[1]){
	$file = $argv[1];
}else if($_GET['file']){
	$file = $_GET['file'];
}

$soda = new Sodapop($file);
$soda->parseVariables();
$soda->parseEndLine();
$soda->parsePrintEcho();
$soda->wrapper();
$soda->compile();
?>

