# php-imap-backup
This is a lightweight script to backup IMAP accounts.  It is written in PHP.

*There is no restore script yet!*

I have a few non-tech-savvy people that I help wrangle their computers.  Some 
have stopped using regular computers and just use phones and tables with 
all of their data in the cloud.  This means they no longer have a workstation
backup.  I'm concerned that they might eventually have an event that deletes
all of their precious mail from the cloud - with no way to get it back.

This script does the following:
- Log into mail account
- Get a list of messages
- Ignore messages it has already seen
- Download new messages
- (Optionally) encrypt messages for storage with PGP


## Limitations
This is a quick script just to back up data in the event it is needed. It is
expeditious and incomplete.

*There is no restore function yet!!*  I will build that if we ever need to 
recover data.

The script does temporarily write mail to the disk unencrypted.  Given that
the account password is in plain text, I don't see this as the biggest threat.
I might button it up in a future version, but that would make restores more
complicated and I want to have restore functionality fully tested first.

The script is designed for one box on one account.  If you need multiple boxes,
install the script in separate directories.  It is clunky, but it is all I 
needed for my project.

Message IDs may also reset of a box is completely emptied.  This could cause
some new mail to not be downloaded because the new ID is the same as an old one.
None of my users ever throw anything away, so there's not much risk for me...
but a real quality program would do a better job.  

## Installation
- Clone the repository
- Copy the config-example.php to config.php
- Edit the configuration file
- Create some way to start the program automatically. A daily cron works nicely.
- If using a cron, add it to your favorite cron monitor so you get a notice if 
it fails.

### To enable PGP encryption
- Use a workstation or bare-metal server to generate PGP Keys
- Copy the *public* key to your server and import it (gpg --import < key.asc)
- Get the keyID and put it in your config file.  The command `gpg --list-keys`
In the following example, the keyID is C27D5BF2
```angular2html
4096R/C27D5BF2 2015-03-06
uid       [ultimate] Randall Bollig <rabollig@firstnamelastname.com>
sub   4096R/6FA58A3B 2015-03-06
```
## Recommendations

Good encryption relies on quality randomness.  If you do not have a physical 
random generator, you should install a package like haveged to extend your
random pool - especially if you are using a virtualized server, container,
VPS, cloud server or the like.  

Generate your PGP key on a bare-metal server or a workstation as they are more
likely to have randomness to draw from.  Copy just the public key to the server
this script runs on - keep the private key safely somewhere else.


