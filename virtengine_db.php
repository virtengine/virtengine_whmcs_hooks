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

function fetchByName( $userid ){
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

?>
