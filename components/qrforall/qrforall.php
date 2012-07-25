<?php
/**
 * qrforall
 *
 * LICENSE
 *
 * This source file is subject to the new GPL3 license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to a.mucci@cingusoft.com so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2007-2008 Andrea Mucci aKa Cingusoft (http://blog.cingusoft.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt     GPL3
 */
class qrforall
{
	
	private $image_folder= ACTIVITIESROOT;
	private $bin_folder;
	
	public function __construct(){}
	
	public function getImageFolder(){
		return $this->image_folder.'qrcodes';
	}
	public function setImageFolder($folder=null){
		if(is_null($folder)){
			new Exception("please add a folder");
		}
		if(!is_dir($folder)){
			new Exception("please pass a correct folder");
		}
		$this->image_folder = $folder;
	}
	public function save($filename,$text){
			$this->callQrEncode($filename,$text);
	}
	/**
	 * private section
	 */
	private function callQrEncode($filename,$text){
		//die($this->image_folder.'qrcodes');
		$string_call = "bin/qrencode -o ".$this->image_folder.'qrcodes'."/".$filename.".png \"".$text."\"";
		if(shell_exec($string_call))
		return true;
	}
}
?>