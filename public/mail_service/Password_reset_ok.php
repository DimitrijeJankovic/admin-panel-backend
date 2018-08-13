<?php
include '../../src/Backend/Helpers/LanguageMessages.php';
include '../../module/Application/src/Model/Config.php';

$messageProxy = new \Backend\Helpers\LanguageMessages();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $messageProxy->getMessage("reset_subject"); ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icon.png"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="animate/animate.css">
        <link rel="stylesheet" type="text/css" href="css-hamburgers/hamburgers.min.css">
        <link rel="stylesheet" type="text/css" href="select2/select2.min.css">
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>

        <div class="bg-contact2" style="background-image: url('');">
            <div class="container-contact2">
                <div class="wrap-contact2">
                    <h1 class="contact2-form-title"> <?php echo $messageProxy->getMessage("Reset_Password_Success"); ?> </h1>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="select2/select2.min.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>
