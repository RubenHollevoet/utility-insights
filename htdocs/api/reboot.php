<?php
//----- DO THE REBOOT -----
//THIS SUDO COMMAND NEEDS TO BE AUTHORISED FOR APACHE TO USE IT IN THE FILE: sudo nano /etc/sudoers
//	# Special for this system - let apache run exes we use in the web interface
//	www-data ALL=NOPASSWD: /sbin/reboot
echo '<pre>';
system("(sleep 1 ; sudo /sbin/reboot ) > /dev/null 2>&1 & echo $!");