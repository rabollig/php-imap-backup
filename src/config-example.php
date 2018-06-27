<?php

$server             = 'secure.emailsrvr.com:993/imap/ssl';
$username           = 'user@domain.tld';
$password           = 'ReDaCt3d';
$box                = 'INBOX';
$storage_directory  = 'storage';
$pgp_key_id         = 'B8E85CDD14BA632FB442EFD2E690C606000702E9';
$database_directory = 'data';
$batch_limit        = 100; // Maximum number of messages per run (0 for unlimited)


ini_set("memory_limit", "256M");
