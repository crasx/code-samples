<?php
class loader{
	function getCss(){
		global $db;			
		$styles=array();
		$res=$db->fetch_all_array("select * from ".T_STYLES);
		foreach($res as $r){
			$styles[$r['style']][$r['name']]['file']=$r['file'];
			$styles[$r['style']][$r['name']]['color']=$r['color'];
			$styles[$r['style']][$r['name']]['text']=$r['text'];
		}
		return $styles;
	}
	
}