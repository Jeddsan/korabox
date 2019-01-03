<?php
class Token{
  private $token,$con;
  public function __construct($token,$con){
    $this->token=$token;
    $this->con=$con;
  }
  public function isValid(){
    $sql = mysqli_query($this->con,"SELECT * FROM token WHERE token='".$this->token."'");
    if(mysqli_num_rows($sql)==1){
      return true;
    }else{
      return false;
    }
  }
}
?>
