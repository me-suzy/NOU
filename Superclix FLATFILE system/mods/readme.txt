MODIFICATIONS FOR SUPERCLIX
8/10/99

If you want to use a clickthrough gateway then do the following:

Use enter.cgi in place of clickthrough.cgi for your affiliate link code.  i.e. affiliates send clicks to
http://www.yourdomain.com/cgi-bin/superclix/enter.cgi?$username

Then inside of the gateway page (say the order page or whatever) place getpaid.cgi inside an image tag or execute with
server sides includes.

When getpaid.cgi is executed 2 things happen.  The cookie is trashed so the affiliate cannot get paid again from that
surfer and the stats for that affiliate are incremented by one unique click.

