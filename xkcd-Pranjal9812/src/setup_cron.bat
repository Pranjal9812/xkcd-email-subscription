@echo off
echo Setting up XKCD email Task...
schtasks /create /tn "XKCD_Email_Task" /tr "php C:\Users\Hp\Documents\xkcd-Pranjal9812\src\cron.php" /sc daily /st 00:00
echo Task successfully added!
