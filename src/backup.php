<?php

// Is config.php present?
if (!file_exists('config.php')) {
    die("No configuration file.  Copy config-example.php to config.php and edit as needed\n");
}

require "config.php";

// Get list of IDs already downloaded
$history = explode("\n", file_get_contents("{$database_directory}/download.log"));

// Connect to mail server
$imap = imap_open('{' . $server . '}', $username, $password, OP_READONLY);

// Connect to selected box
imap_reopen($imap, '{' . $server .'}' . $box);

// Search for messages
$messages = imap_search($imap, "ALL", SE_UID);

rsort($messages);  // Process newest mail first, so switch the array around

$n = 0; // For counting the number of messages processed

foreach ($messages as $key => $id) { // Loop through the mail
    // If we already have this ID in history, skip it.
    if (in_array($id, $history)) {
        continue;
    }

    // Get message information
    $info = imap_fetch_overview($imap, $id, FT_UID);

    // Skip message if not seen so that we don't change the message status
    if ($info[0]->seen === false) {
        continue;
    }

    // Actually get the message from the server
    $header = imap_fetchheader($imap, $id, FT_UID);
    $body   = imap_body($imap, $id, FT_UID);

    // Write the message to disk
    $message  = $header.$body;
    $hash     = md5($header);
    $filename = "{$storage_directory}/" . date("Y-m-d") . "-{$box}-{$id}-{$hash}.mail";
    file_put_contents($filename, $message);

    // Add the message ID to history so we can skip it next time.
    file_put_contents("{$database_directory}/download.log", "{$id}\n", FILE_APPEND);

    // If we have a PGP keyID, encrypt the message
    if (!empty($pgp_key_id)) {
        exec("gpg  -r {$pgp_key_id} --always-trust --encrypt " . escapeshellarg($filename));
        unlink($filename);
    }

    $n++; // Increment the counter.

    // If we set a limit, check to see if we reached it
    if ($batch_limit > 0 && $n >= $batch_limit) {
        break;
    }
}

// Officially close the connection
imap_close($imap);
