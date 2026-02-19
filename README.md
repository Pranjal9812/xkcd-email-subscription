# 📬 XKCD Email Automation System

An automated email subscription platform that verifies users and sends a **random XKCD comic every 24 hours** using a CRON-based workflow.

---

## 🚀 Overview

This system allows users to:

- Register using their email
- Verify via a **6-digit code**
- Receive a **daily XKCD comic**
- Unsubscribe securely through email verification
- Experience a **fully automated delivery system**

User data is stored in a lightweight file-based database:  
`registered_emails.txt`

---

## ✨ Features

### 1. Email Verification
- 6-digit code generation  
- Email-based verification  
- Stores verified users securely  

### 2. Daily Comic Delivery
- Fetches random comics from the XKCD API  
- Sends formatted HTML emails  
- Runs automatically via CRON  

### 3. Unsubscribe System
- Unsubscribe link in every email  
- Verification required before removal  

---

## 🛠️ Tech Stack

- PHP 8.3  
- HTML  
- PHP mail() / SMTP  
- Linux CRON Jobs  
- XKCD Public API  

---

## ⚙️ Setup

1. Place the project inside: htdocs/

2. Start Apache (XAMPP)

3. Open in browser: http://localhost/project-folder/src/index.php

---

## ⏱️ CRON Setup

Run the following command:
bash src/setup_cron.sh


This schedules automatic comic delivery every 24 hours.

---

## 📂 Project Structure

src/
│── index.php
│── unsubscribe.php
│── cron.php
│── functions.php
│── setup_cron.sh
│── registered_emails.txt

---

## 🎯 Key Highlights

- Automated email workflow  
- API integration  
- Scheduled background processing  
- Secure verification system  
- Lightweight architecture (No database required)

---

## 👩‍💻 Author

**Pranjal Mohite**  
AI & Data Science Engineer  

**GitHub: https://github.com/Pranjal9812**

