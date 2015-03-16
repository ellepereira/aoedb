<?php 

function dirRecursive($dir) {
  $handle = dir($dir);
  $files = array();
  while ($file = $handle->read()) {
    if ($file != '.' && $file != '..') {
      if (is_dir($dir . '/' . $file)) {
        $files = array_merge($files, dirRecursive($dir . '/' . $file));
      }
      else {
        $files[] = $dir . '/' . $file;
      }
    }
  }
  
  return $files;
}