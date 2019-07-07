<?php
Class Scanner{
	var $target;
	var $new_target;
	function __construct(){
	echo "
__________                           ___ ___               __
\______   \_____     ____   ____    /   |   \ __ __  _____/  |_  ___________
 |     ___/\__  \   / ___\_/ __ \  /    ~    \  |  \/    \   __\/ __ \_  __ \
 |    |     / __ \_/ /_/  >  ___/  \    Y    /  |  /   |  \  | \  ___/|  | \/
 |____|    (____  /\___  / \___  >  \___|_  /|____/|___|  /__|  \___  >__|
                \//_____/      \/         \/            \/          \/
         		       	Tool for searching page\n";
	}
	public function target(){
	echo "target : ";
	$this->target = trim(fgets(STDIN));
	preg_match("^(?:https?:\/\/)?^",$this->target,$f);
	if(empty($f[0])){
	$this->new_target = "http://{$this->target}";
	}else{
	$this->new_target = $this->target;
	}
	}
	public function execute(){
	$this->target();
	$cek = $this->cek($this->new_target);
	if($cek != 200){
	echo "U'r Target is Down or invalid\nPlease cek u'r target manualy\n";
	return $this->execute();
	}else{
	return false;
	}
	}
	public function cek($target){
	$ch = curl_init($target);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_exec($ch);
	$respon = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $respon;
	}
	public function start(){
	echo "wordlist : ";
	$word = trim(fgets(STDIN));
	if(file_exists($word)){
	echo "Reading Wordlist..\n";
        $file = fopen($word,'r');
        $size = filesize($word);
        $read = fread($file,$size);
        $boom = explode("\n",$read);
	sleep(1);
	echo "Scaning target !\n";
	foreach($boom as $list){
	$victim =  $this->new_target ."/".$list;
	$respon =  $this->cek($victim);
	if($respon == 200){
	echo $victim ." --> [{$respon} OK]\n";
	}else{
	echo $victim ." --> [{$respon}]\n";
	}
	}
	}else{
	echo "wordlist not found\n";
	return $this->start();
	}
	}
}
$scan = new Scanner();
echo $scan->execute();
$scan->start();
?>
