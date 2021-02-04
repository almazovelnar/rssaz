<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <style type="text/css">
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Source Sans Pro", sans-serif;
            font-size: 16px;
        }

        .mail-wrapper {
            width: 100%;
            max-width: 640px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ***** --header ***** */
        .mail-header {
            background-color: #bb1d31;
            padding: 20px 30px;
        }

        /* ***** --body ***** */
        .mail-body {
            padding: 20px 30px;
        }

        .title {
            font-size: 26px;
        }

        .mail-body p {
            margin: 10px 0 0 0;
        }

        .code {
            font-size: 15px;
            font-family: "Source Code Pro", sans-serif;
            color: #fff;
            margin: 20px 0;
            padding: 20px 15px;
            border-radius: 3px;
            display: block;
            background-color: #2d2832;
            overflow-y: auto;
        }

        [class*="level-"] {
            padding: 2px 10px;
            border-radius: 3px;
            white-space: nowrap;
        }

        .level-2 {
            padding-left: 20px;
        }

        .level-3 {
            padding-left: 40px;
        }

        .level-4 {
            padding-left: 60px;
        }

        .error {
            background-color: #ff7373;
        }

        /* ***** --footer ***** */
        .mail-footer {
            background-color: #E4E4E4;
            padding: 20px 30px;
        }

        .mail-footer p {
            opacity: .8;
        }
    </style>
    <div class="mail-wrapper">
        <div class="mail-header">
            <img alt="logo" src="<?= Yii::$app->params['frontendHostInfo'] ?>/images/logo-white.png" >
        </div><!-- Header -->

        <div class="mail-body">
            <?= $content ?>
        </div>

        <div class="mail-footer">
            <p>This inbox is not attended so please don’t reply to this email. This is a service email. You will receive these no matter what your marketing communication preferences are.</p>
        </div><!-- Footer -->
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
