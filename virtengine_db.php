<?php
function fetchFieldByName( $name, $userid ){
        if( empty($name) || empty($userid) )
                return false;
        $query = "SELECT cfv.value FROM tblcustomfieldsvalues cfv INNER JOIN tblcustomfields cf ON cfv.fieldid = cf.id
                  WHERE cfv.relid = '".$userid."' AND cf.fieldname = '".$name."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row['value'];
        }
        return false;
}
function fetch_user( $userid ){
        if(empty($userid) )
                return false;
        $query = "SELECT vs.email FROM tblclients vs WHERE  id = '".$userid."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row['email'];
        }
        return false;
}
function fetch_by_id($tbl, $id ) {
        if(empty($id))
           return false;
        $query = "SELECT * FROM ".$tbl." WHERE  id = '".$id."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row;
        }
        return false;
}

function fetch_data_for_transaction($tbl, $id ) {
        if(empty($id))
           return false;
        $query = "SELECT * FROM ".$tbl." WHERE  invoiceid = '".$id."'";
        $res = full_query($query);
        if( mysql_num_rows($res) > 0 ) {
          $row = mysql_fetch_assoc($res);
          return $row;
        }
        return false;
}

function fetch_column_in_result($inp, $parent, $child) {
  $rows = json_decode($inp,true);
  $res=array();
  foreach($rows[$parent] as $row) {
    $res[$row[$child]] = $row[$child];
  }
  logActivity(json_encode($res));
  return $res;
}
?>
