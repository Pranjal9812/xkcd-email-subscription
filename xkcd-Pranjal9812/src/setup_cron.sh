#!/bin/bash
# This script should set up a CRON job to run cron.php every 24 hours.
# You need to implement the CRON setup logic here.

echo "Setting up XKCD email CRON job..."

# Get the full path to cron.php
SCRIPT_PATH="$(pwd)/cron.php"

# Add CRON job to execute this script daily at midnight
(crontab -l 2>/dev/null; echo "0 0 * * * php $SCRIPT_PATH") | crontab -

echo "CRON job successfully added!"

