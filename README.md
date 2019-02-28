> ## Archived 
> This repo has been archived and is no longer maintained.\
> If you want to use, edit or improve this code, you are welcome to fork it ***but*** we may not acknowledge this and pull requests may be ignored.

HelioBot is an IRC bot that can respond to many commands that may be communicated to it from the #heliohost channel. HelioBot mainly reponds to commands begining with an exclamation mark and can differentiate a normal user and an administrator, which allows it to provide different access levels for different types of users. HelioBot is operational usually active on the #heliohost channel on Freenode if you wish to experiment with him. HelioBot is constantly being developed on GitHub and it will synchronize it self with the GitHub source code regularily. HelioBot is opensource and therefore you can download the source code. If you have any suggestions or found any bugs then please report them using our GitHub Issues section.

If the user adds the @ symbol and then someone's username to the end of their command, it will be directed towards that specific user. For example: !help @ jje. This would send the results of the !help command to the user on the IRC chatroom called 'jje'.

## Commands ##
!about - This command displays information about this IRC bot including a link to GitHub.

!help - This command displays a list of commands that can be performed on HelioBot. This only shows the commands that can be run by normal users.

!status - This command will display how long HelioBot has been online for in form of days, hours, minutes. Not only is this commnad useful for seeing how long HelioBot has been offline but you can also see when the server's last downtime was.

!whoami - This command shows information about the user such as the nick, name, hostmask, and their privledges (ie. whether they are an admin or not).

!date - This command displays the current date and time of the current server in the PST timezone. This command will also send a greeting depending on whether it is morning, afternoon or evening.

!signup - This command displays how long users must wait until signups reset. It also displays whether signups for Stevie and Johnny are open or closed.

!wiki - This command has multiple purposes, but each one involves a link to the HelioHost Wiki. This command will accept a value, which can be placed after the !wiki command. The value will be checked on the wiki and if the value is a page name, then a link directly to that page will be displayed. If the value is detected as a search term, then a link will be displayed pointing directly to the Special:Search page with that search term entered. If there is no value, it will simply display a link to the main page of the HelioHost wiki.

!support - This command displays the different methods that a user may get support on HelioHost. It displays three out of the many possible methods: IRC, HelioNet and Wiki.

## Administration ##
If you have admin access to the bot, then the following commands are available to you. In the event that a normal user attempts to run the following commands, they will be denied access.

!shutdown - This will shut HelioBot down. This is useful if HelioBot is causing trouble or not working properly and you would like HelioBot to temporarily shutdown. Remember though that within an hour HelioBot will attempt to start up again after synchronizing with GitHub.

!echo - This command requires a value, and HelioBot will simply echo that value to the intended recipient. Remember that the value may have character limits.

!raw - This commnad is exactly the same as !echo except it does not have any recipient.

!system - This commnad is exactly the same as !raw except that it sends the message to the IRC system instead of the #heliohost channel.
