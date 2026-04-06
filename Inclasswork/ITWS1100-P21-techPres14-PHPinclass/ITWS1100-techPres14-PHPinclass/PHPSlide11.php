<?php
  $inventory = array('doohickeys' => 200, 'gizmos' => 5);
  
  // will output 200:
  echo $inventory['doohickeys'].'<br/>';
  
  // will output all key/value pairs in the array:
  foreach ($inventory as $key => $value) {
    echo $key.' - '.$value.'<br/>';
  }
?>
