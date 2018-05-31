<html>

<head>
    <link href="css/bootstrap.min.css" rel="stylesheet" >
    <link href="css/bootstrap-theme.min.css" rel="stylesheet" >
    <link href="css/main.css" rel="stylesheet" >
    <script src="js/jquery.1.11.1.min.js"></script>
</head>

<body>

    <?php
    include __DIR__.'/controller/FeedbackController.php';
    use Controllers\FeedbackController;

    $controller = new FeedbackController();
    $controller->index();?>

        <form method="post" id="feedback_form" role="form" class="form-horizontal" action="/">

            <div class="form-group">
                <?php $attrib = 'feedback_name'?>
                <label class="col-sm-2 control-label" for="feedback_name" >Name</label>
                <div class="col-sm-10 field_container">
                    <div class="error_message"><?=$controller->getFieldError($attrib)?></div>
                    <input name="<?=$attrib?>" required class="form-control" id="feedback_name" type="text" value="<?=$_POST[$attrib]?>" />
                </div>
            </div>

            <div class="form-group">
                <?php $attrib = 'feedback_email'?>
                <label class="col-sm-2 control-label" for="feedback_email" >Email</label>
                <div class="col-sm-10 field_container">
                    <div class="error_message"><?=$controller->getFieldError($attrib)?></div>
                    <input required name="<?=$attrib?>" class="form-control" id="feedback_email" type="email" value="<?=$_POST[$attrib]?>" />
                </div>
            </div>

            <div class="form-group">
                <?php $attrib = 'feedback_text'?>
                <label class="col-sm-2 control-label" for="feedback_text">Text</label>
                <div class="col-sm-10 field_container">
                    <div class="error_message"><?=$controller->getFieldError($attrib)?></div>
                    <textarea required name="<?=$attrib?>" class="form-control" id="feedback_text"><?=$_POST[$attrib]?></textarea>
                </div>
            </div>

            <div class="form-group" style="text-align:center;">
                <input type="submit" value="Send" id="feedback_send" onclick="return false" class="btn btn-default">
            </div>

        </form>


</body>

<footer>
    <script src="js/validator.js"></script>
    <script src="js/FormFeedback.js"></script>
</footer>

</html>

