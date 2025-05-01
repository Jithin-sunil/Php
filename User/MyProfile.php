<?php
ob_start();
include("../User/Header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyProfile</title>
</head>

<body>
    <form action="" method="post">
        <h1>My Profile</h1>
        <table>
            <tr>
                <td colspan="2" align="center">

                </td>
            </tr>
            <tr>
                <td>Name</td>
                <td></td>
            </tr>
            <tr>
                <td>Email</td>
                <td></td>
            </tr>
            <tr>
                <td>Contact</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </table>
    </form>
</body>

</html>
<?php
include("../User/Footer.php");
ob_end_flush();
?>