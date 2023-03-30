<table>
<?php 
    foreach ($_REQUEST as $key => $value) {
        if (is_array($value)) {
            print_r("<tr><td>$key</td><td>");
            echo implode(" & ", $value);
            print_r("</td></tr>");
        }  else {
            print_r("<tr><td>$key</td><td>$value</td></tr>");
        }   
    }

    foreach ($_FILES as $key => $value) {
        print_r("<tr><td>$key</td><td>$value</td></tr>");
    }
    
?>
</table>