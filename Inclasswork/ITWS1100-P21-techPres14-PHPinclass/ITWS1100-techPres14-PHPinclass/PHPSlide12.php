<pre>
<?php

echo 'Output from Print_r directly to output<br/>';

$a = array ('a' => 'apple', 'b' => 'banana', 'c' => array('x', 'y', 'z'));
print_r ($a);

echo '<br/>Output from Print_r from variable $results<br/>';

$b = array ('m' => 'monkey', 'foo' => 'bar', 'x' => array('x', 'y', 'z'));
$results = print_r($b, true);

echo $results;

?>
</pre>