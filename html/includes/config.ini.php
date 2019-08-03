; <?php die('Direct access not allowed ;') ?>
; The above is for security, do not remove

[general]
sitename = iCarey Testing Server
siteurl = https://mc.icarey.net/testing
owner = Smashedbotatos
discord = 9sFXgve
whitelist_channel = verify-channel
bcryptcost = 12

[minecraft]
worldpath = /home/minecraft/server/testing/world
servername = iCarey Testing Server
type = Survival
version = 1.14.4
connectaddress = testing.icarey.net
host = 127.0.0.1
port = 25565
qport = 25565
rport = 25566
rpass = securepassword

[groups]
default_group = member
member = member
trusted = trusted
donor = donor
moderator = mod
administrator = admin

[mysql]
user = minecraft
password = securepassword
host = localhost
port = 3306
database = minecraft

[addons]
discord_bot = false
luckperms = false
luckdata = luckperms_players

[email]
primaryemail = minecraft@icarey.net
primaryname = iCarey Admins
username = minecraft
password = securepassword
host = mail.icarey.net
port = 587
reply = minecraft@icarey.net
replyname = iCarey Admins

[captcha]
secretkey = secretkey
sitekey = sitekey

[registration]
subject = New Registration
from = iCarey Admins

[ban]
bansub = You are Banned
unbansub = You have no longer Banned
from = iCarey Admins

[appeal]
subject = Ban Appeal
from = iCarey Admins

[activation]
subject = Account Activation
from = iCarey Admins

[forgot]
subject = Password Recovery
from = iCarey Admins

[whitelist]
approvalsub = Whitelist Approval mc.icarey.net
denysub = Whitelist Denial mc.icarey.net
from = iCarey Admins
