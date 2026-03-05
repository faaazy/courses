<?php
require_once 'connect.php';
require_once 'functions.php';

$_SESSION = [];
session_destroy();

redirect(base_url("/index.php"));