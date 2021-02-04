<?php

(Dotenv\Dotenv::createImmutable(__DIR__ . '/../../')->safeLoad());

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@core', dirname(dirname(__DIR__)) . '/core');
Yii::setAlias('@cabinet', dirname(dirname(__DIR__)) . '/cabinet');
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
