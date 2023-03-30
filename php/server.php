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
        foreach ($_SERVER as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
        }
    ?>
    </table>
</body>
</html>