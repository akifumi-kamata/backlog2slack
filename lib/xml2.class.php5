<?php
/**
* --------------------------------------------------------------------
* [File Information]
* 
* xmlを配列に展開するCLASS
* ※拡張版
*
* ex.
*   $obj_xml = new xml;
*   $ary_data = $obj_xml->xml2array($xml_data);
*   var_dump($ary_data);
* 
* 
* 
* 
* 
* 
* 
* 
* 
* 
* 
* 
* 
* --------------------------------------------------------------------
* [Attention]
* 
* 
* --------------------------------------------------------------------
*/


class xml2{

	/**
	*
	*/
	public function __construct(){

	} // function __construct

	/**
	*
	*/
	public function xml2array($xml_data){

		//var_dump($xml_data);//debug

		// エンコード取得
		$data_enc = mb_detect_encoding($xml_data);

		if($data_enc != "UTF-8"){

			$xml_data = mb_convert_encoding($xml_data,"UTF-8",$data_enc);
			//var_dump($result);//debug
		}

		//print nl2br(htmlspecialchars($xml_data));

		// 改行を取り除くよう変更
		$xml_data = str_replace("\r","",$xml_data);
		$xml_data = str_replace("\n","",$xml_data);

		@$xml_object = simplexml_load_string($xml_data);
		//var_dump($xml_object);//debug

		$ary_data = get_object_vars($xml_object);
		//var_dump($ary_data);//debug

		if(is_array($ary_data)){

			foreach($ary_data as $key=>$value){

				if(is_object($value)){
					$ary_data[$key] = $this->object2array($value);
				}

				if(is_array($value)){
					foreach($value as $key2=>$value2){
						$ary_data[$key][$key2] = $this->object2array($value2);
					}
				}

			}

		}

		return $ary_data;

	} // function xml2array

	/**
	*
	*/
	protected function object2array($obj){

		if(!is_object($obj)){
			return $obj;
		}

		$ary_data = get_object_vars($obj);

		foreach($ary_data as $key=>$value){

			if(is_object($value)){
				$tmp = $this->object2array($value);
				if(count($tmp) == 0){
					$ary_data[$key] = '';
				}
				else{
					$ary_data[$key] = $tmp;
				}
			}

			if(is_array($value)){
				foreach($value as $key2=>$value2){

					if(is_object($value2)){
						$ary_data[$key][$key2] = $this->object2array($value2);
					}

				}
			}

		}

		return $ary_data;

	} // function object2array

} // class xml2

?>
