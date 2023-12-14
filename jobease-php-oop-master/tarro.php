<?php

abstract class Device{
    // you sould be use this function in the classes that's inherting from $this
    abstract public function test();
} 

class AppeleDevice extends Device {
    //properties 
    public $ram   ;
    public $inch  ;
    public $space ;
    public $color ;
    // constant
    const MaxRamSize = '10GB';
    // abstract method 
     public function test(){
        echo'i am an abstract methode from appel';
     }
    // methods
    public function getInfo(){
        echo $this->ram.'/'.
             $this->inch.'/'.
             $this->space.'/'.
             $this->color.'/'.
             self::MaxRamSize;
    }
    public function ChangeInfo($rm,$in,$sp,$clr){
        $this->ram   =$rm ; 
        $this->inch  =$in ;
        $this->space =$sp ;
        $this->color =$clr;

    }

}
class SonyDevice extends AppeleDevice{
    private $code='123' ;
    public function affcode ($aff){
        $aff =$this->code ;
        echo $aff;

    }




} 
// object 1
$iphone_11=new AppeleDevice;
$iphone_11->ram='150MB';

$iphone_11 -> ChangeInfo('1gb','5.6  inch','64gb','red');
echo'<pre>';
var_dump($iphone_11);
echo'</pre>';
echo '</br>';
echo '</>'.$iphone_11 -> test();

echo '</br>';

// object 2
// why this syntax do's not work $iphone_x=new AppeleDevice('2', '1 ','3','f');
$iphone_x=new AppeleDevice;
$iphone_x -> ChangeInfo('1.5gb','8 inch','64gb','red');
echo'<pre>';
var_dump($iphone_x);
echo'</pre>';
echo $iphone_x->getInfo();

// new object from sony class 
$sony = new SonyDevice();
$sony -> ChangeInfo('6ram','6.5 inch','128gb','black');
echo'<pre>';
var_dump($sony);
echo'</pre>';
echo'======================\n';
// echo $sony -> code ;
$aff='';
echo $sony -> affcode($aff);
echo'======================\n';
var_dump($_FILES);


?>
<!-- 
    strlen() : to count the caracters in string php
 -->
