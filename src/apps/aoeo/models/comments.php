<?
class comments extends model {
  public $info;
  
  function __construct(&$parent) {
    parent::__construct($parent);
    
  }
  
  public function get($id) {
    $rows = $this->db->query("select * from comments where page='{$id}' order by points desc limit 100")->results();
    if (count($rows) > 0) {
      foreach ($rows as $i => $row) {
        $comments[] = $row;
        $parent = $row['parent'];
        $parent = ($parent == null) ? 0 : $parent;
        $parents[$parent][] = $i;
      }
      $this->info['comments'] = $comments;
      $this->info['parents'] = $parents;
    }
    
    return $this->info;
  }
  
}
?>