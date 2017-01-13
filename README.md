# HTTP Scan2Ban

## Introduction

My website was getting a lot of probes for vulnerable software and it was
starting to clog the logs, especially since I don't get much traffic it took up
the vast majority of the logs.

## Operation

Visiting the php file will get a user banned.

When a request comes to the web server, it will be checked against the
mod_rewrite rules in the .htaccess file for common vulnerability scans. If
there is a match then the request is redirected to the php file. The php file
logs some details to a separate log file - including the source ip address.
fail2ban jail rules will check the log file and adds an all ports iptables drop
rule, banning them.

## Installation

fail2ban is used to perform the actual ban, install that. e.g. for Debian-like
systems:

```
apt-get install fail2ban
```

The web server will need php and modrewrite support installed. Install those as
appropriate for your web server.

Ideally you should have some sacrificial vhost on the same server, that will
reduce the likely-hood legitimate traffic gets itself banned. Use that to host
the web content of this project.

Place the htaccess file in the webroot of the vhost as .htaccess

Place the .php file in the webroot along-side the .htaccess file. Rename it to
something random. This is to avoid someone accidentally visiting it directly
and getting themselves banned.

Copy the http-scan.conf file to fail2ban's `filter.d` configuration directory.

Copy the `[http-scan]` section of the jail.conf to your own fail2ban jail.conf.

Restart fail2ban and your web server as necessary.

## Configuration

Ensure your webserver configuration supports executing php and processing
htaccess files.

Edit the .htaccess file and update it with any new requests you think should
also be banned. Remove any lines that may conflict with legitimate files you
serve.

Edit the jail.conf file to adjust the `bantime` to how long you would like a
source IP address is banned.
