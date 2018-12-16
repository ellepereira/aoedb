<?

print_comments($data['comments'], $data['parents'], 0);
 
 
  function print_comments(&$comments, &$parents, $parent) {
    //if (!array_key_exists($parent, $parents)) return false;

    for ($i = 0; $i < count($parents[$parent]); $i++) {
      $comment = $comments[$parents[$parent][$i]];
      
      echo '<div>';
      echo "Vote: <a href='vote.php?cid={$comment['cid']}&uid=1&vote=1'>Up</a> <a href='vote.php?cid={$comment['cid']}&uid=1&vote=0'>Down</a> <br>";
      print_r($comment);
      if (array_key_exists($comment['cid'], $parents)) {
        print_comments($comments, $parents, $comment['cid']);
      }
      echo '</div>';
    }
    return true;
  }
 
 ?>
 
 