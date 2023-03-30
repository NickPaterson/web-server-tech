<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - Env Variables</title>
</head>
<body>
    <table>
        <tr>
            <th>Variable</th>
            <th>Value</th>
        </tr>
    <?php
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                print_r("<tr><td>$key</td><td>");
                echo implode(" & ", $value);
                print_r("</td></tr>");
            }  else {
                print_r("<tr><td>$key</td><td>$value</td></tr>");
            } 
        }
    ?>
    </table>
</body>
</html>