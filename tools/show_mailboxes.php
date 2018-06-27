<?php

require "config.php";

// Connect to mail server
$imap = imap_open('{' . $server . '}', $username, $password, OP_HALFOPEN)
    or die("can't connect: " . imap_last_error());;

$boxes = imap_list($imap, "{" . $server . "}", "*");

var_dump($boxes);

