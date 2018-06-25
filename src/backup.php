<?php

// Is config.php present?
if (!file_exists('config.php')) {
  die("No configuration file.  Copy config-example.php to config.php and edit as needed\n");
}

require "config.php";

// Check to make sure configuration makes sense



// Load databases



// Connect to mail server

<?php
$server = "{secure.emailsrvr.com}";
$username = "rabollig@randallbollig.com";
$password = 'NiHeevWyn5:::';
$box  = 'INBOX';
$date = date("Y-m-d");
// Get list of IDs already downloaded

$history = explode("\n", file_get_contents('inbox.log'));

$imap = imap_open('{secure.emailsrvr.com}', $username, $password, OP_READONLY);

imap_reopen($imap, "{secure.emailsrvr.com}INBOX");

$messages = imap_search($imap, "ALL", SE_UID);

rsort($messages);

foreach ($messages as $key => $id) {
  if (in_array($id, $history)) {
    continue; 
  }

  $header = imap_fetchheader($imap, $id, FT_UID);
  $body   = imap_body($imap, $id, FT_UID);

  $message = $header.$body;
  $hash    = md5($header);
  file_put_contents("{$date}-{$box}-{$id}-{$hash}.mail", $message);
  file_put_contents('inbox.log', "{$id}\n", FILE_APPEND);
  die();
}


imap_close($imap);

