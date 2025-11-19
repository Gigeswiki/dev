<?php 

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
 $tarayici = 'Internet Explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE)
 $tarayici = 'Internet Explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
 $tarayici = 'Mozilla Firefox';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
 $tarayici = 'Google Chrome';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
 $tarayici = 'Opera Mini';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
 $tarayici = 'Opera';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
 $tarayici = 'Safari';
 else
 $tarayici = 'Bilinmiyor';

?>