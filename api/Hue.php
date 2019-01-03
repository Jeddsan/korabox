<?php
class Hue{
  private $ip,$username;

  public function __construct($ip,$username){
    $this->ip = $ip;
    $this->username = $username;
  }

  public function getAllLights(){
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights");
    return $data;
  }

  public function getLight($id){
    $data = self::getAllLights();
    $data = json_decode($data,true);
    return json_encode($data[$id]);
  }

  public function setLightState($id,$state){
    $state = boolval($state);
    $body = json_encode(array("on"=>$state));
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights/".$id."/state","PUT",$body);
    return $data;
  }

  public function setLightDim($id,$int){
    $body = json_encode(array("on"=>true,"bri"=>intval($int)));
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights/".$id."/state","PUT",$body);
    return $data;
  }

  public function setLightBreathe($id){
    $body = json_encode(array("on"=>true,"alert"=>"select"));
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights/".$id."/state","PUT",$body);
    return $data;
  }

  public function setLightLongBreathe($id){
    $body = json_encode(array("on"=>true,"alert"=>"lselect"));
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights/".$id."/state","PUT",$body);
    return $data;
  }

  public function setLightColorLoop($id){
    $body = json_encode(array("on"=>true,"effect"=>"colorloop"));
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights/".$id."/state","PUT",$body);
    return $data;
  }
  public function setLightColorLoopStop($id){
    $body = json_encode(array("on"=>true,"effect"=>"none"));
    $data = Hue::request("http://".$this->ip."/api/".$this->username."/lights/".$id."/state","PUT",$body);
    return $data;
  }

  public function getLightIdByName($name){
    $data = json_decode(self::getAllLights(),true);
    $totalLamps = count($data);
    $current_id=0;
    $not_found=true;
    for($i=0;$i<$totalLamps;$i++){
      $current_id++;
      if($data[$current_id]["name"]==null){
        $i--;
        continue;
      }else if(strtolower($data[$current_id]["name"])==strtolower($name)){
        $not_found=false;
        break;
      }
    }
    if($not_found){
      return false;
    }else{
      return $current_id;
    }
  }

  public static function requestUsername($ip,$device_type = "Korabox"){
    $data = Hue::request("http://$ip/api",'POST',json_encode(array("devicetype"=>$device_type)));
    return $data;
  }

  private static function request($url,$method='GET',$data=''){
    $context = stream_context_create(array (
				'http' => array (
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                    "User-Agent:Korabox/1.0\r\n",
						'method' => $method,
            'content' => $data
				)
		));
		$data = file_get_contents($url, false, $context);
    return $data;
  }
}
function rgbToHsl( $r, $g, $b ) {
  $oldR = $r;
  $oldG = $g;
  $oldB = $b;
  $r /= 255;
  $g /= 255;
  $b /= 255;
  $max = max( $r, $g, $b );
  $min = min( $r, $g, $b );
  $h;
  $s;
  $l = ( $max + $min ) / 2;
  $d = $max - $min;
      if( $d == 0 ){
          $h = $s = 0; // achromatic
      } else {
          $s = $d / ( 1 - abs( 2 * $l - 1 ) );
    switch( $max ){
              case $r:
                $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
                        if ($b > $g) {
                      $h += 360;
                  }
                  break;
              case $g:
                $h = 60 * ( ( $b - $r ) / $d + 2 );
                break;
              case $b:
                $h = 60 * ( ( $r - $g ) / $d + 4 );
                break;
          }
  }
  return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
}
function hslToRgb( $h, $s, $l ){
    $r;
    $g;
    $b;
    $c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
    $x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
    $m = $l - ( $c / 2 );
    if ( $h < 60 ) {
      $r = $c;
      $g = $x;
      $b = 0;
    } else if ( $h < 120 ) {
      $r = $x;
      $g = $c;
      $b = 0;
    } else if ( $h < 180 ) {
      $r = 0;
      $g = $c;
      $b = $x;
    } else if ( $h < 240 ) {
      $r = 0;
      $g = $x;
      $b = $c;
    } else if ( $h < 300 ) {
      $r = $x;
      $g = 0;
      $b = $c;
    } else {
      $r = $c;
      $g = 0;
      $b = $x;
    }
    $r = ( $r + $m ) * 255;
    $g = ( $g + $m ) * 255;
    $b = ( $b + $m  ) * 255;
    return array( floor( $r ), floor( $g ), floor( $b ) );
}
?>
