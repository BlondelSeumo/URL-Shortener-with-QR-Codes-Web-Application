<?php

/* Enabling debug mode is only for debugging / development purposes. */
const DEBUG = 0;

/* Enabling mysql debug mode is only for debugging / development purposes. */
const MYSQL_DEBUG = 0;

/* Enabling the file logging will store errors that occur, in the uploads/logs/ folder */
const LOGGING = 1;

/* Enabling the cache will use file caching where implemented for better performance */
const CACHE = 1;

/* Only meant for Demo purposes, don't change :) */
//ALTUMCODE:DEMO const DEMO = 1;

require_once realpath(__DIR__) . '/app/init.php';

$App = new Altum\App();
