<?php 
  $myArray = array('lions', 'tigers', 'bears');
  
  // will output lions:
  echo $myArray[0] . '<br/>';

  // will output the whole array
  for($i=0; $i<count($myArray); $i++) {
    echo $myArray[$i].'<br/>';
  }
?>
