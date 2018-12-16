<?php
  function imagecreatefromtga_alpha($filename) {
  
    $handle = fopen($filename, 'rb');
    $data = fread($handle,filesize($filename));
    fclose($handle);
    $pointer = 18;

    $w = fileint(substr($data,12,2));
    $h = fileint(substr($data,14,2));
    
    $x = 0;
    $y = 0;
    
    $img = imagecreatetruecolor($w,$h);
    
    imagealphablending($img, false);
    imagesavealpha($img, true);
    
    while ($pointer < strlen($data)) {
      $r = fileint(substr($data, $pointer+2, 1));
      $g = fileint(substr($data, $pointer+1, 1));
      $b = fileint(substr($data, $pointer+0, 1));
      $a = fileint(substr($data, $pointer+3, 1));
      
      $color = imagecolorallocatealpha($img, $r, $g, $b, 127-$a/2);

      imagesetpixel($img, $x, $w-$y-1, $color);
      $x++;
      
      if ($x == $w) {
        $y++;
        $x = 0;
      }
      
      $pointer += 4;
    }

    return $img;
  }
  
  function fileint($str)
  {
    return base_convert(bin2hex(strrev($str)),16,10);
  }
?>