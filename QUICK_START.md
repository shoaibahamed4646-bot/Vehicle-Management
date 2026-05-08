# 🚀 Quick Start Guide - Vehicle Management System

Get the Vehicle Management System up and running in 5 minutes!

## ⚡ Option 1: Docker (Recommended)

### Prerequisites
- Docker & Docker Compose installed

### Steps

```bash
# 1. Navigate to project directory
cd Vehicle-Management

# 2. Start containers
docker-compose up -d

# 3. Access the application
http://localhost
http://localhost:8080  # PhpMyAdmin

# 4. Stop containers
docker-compose down
```

**That's it!** Database is auto-configured.

---

## 🖥️ Option 2: XAMPP (Windows)

### Prerequisites
- XAMPP installed with Apache & MySQL

### Steps

```
1. Extract Vehicle_Management_System.zip to:
   C:\xampp\htdocs\Vehicle-Management\

2. Start XAMPP Control Panel:
   - Click "Start" on Apache
   - Click "Start" on MySQL

3. Open browser and navigate to:
   http://localhost/phpmyadmin/
   
4. Create database:
   - Click "New"
   - Database name: vehicle_management
   - Collation: utf8mb4_unicode_ci
   - Create

5. Run setup:
   http://localhost/Vehicle-Management/setup_db.php

6. Access the application:
   - Admin: http://localhost/Vehicle-Management/admin_login.php
   - Customer: http://localhost/Vehicle-Management/customer_login.php
```

---

## 🐧 Option 3: Linux/Mac (Manual)

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Apache or Nginx

### Steps

```bash
# 1. Extract project
unzip Vehicle_Management_System.zip -d /var/www/html/

# 2. Set permissions
sudo chown -R www-data:www-data /var/www/html/Vehicle-Management/
sudo chmod -R 755 /var/www/html/Vehicle-Management/

# 3. Create database
mysql -u root -p
CREATE DATABASE vehicle_management;
EXIT;

# 4. Configure database
# Edit: /var/www/html/Vehicle-Management/includes/db.php
# Update: host, username, password, database

# 5. Run setup
http://localhost/Vehicle-Management/setup_db.php

# 6. Access application
http://localhost/Vehicle-Management/
```

---

## 🔐 Login Credentials

| Role | Username | Password | URL |
|------|----------|----------|-----|
| Admin | admin | admin123 | `/admin_login.php` |
| Customer | customer | customer123 | `/customer_login.php` |
| Employee | employee | emp123 | `/employee_login.php` |

*Note: Change these after first login!*

---

## 📝 Database Configuration

Edit `includes/db.php`:

```php
<?php
$host = 'localhost';        // Database host
$username = 'root';         // MySQL username
$password = '';             // MySQL password
$database = 'vehicle_management';  // Database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

## ✅ Verification Checklist

- [ ] Database created
- [ ] Database connection successful
- [ ] Can access admin login page
- [ ] Can access customer login page
- [ ] Can access employee login page
- [ ] Can login with test credentials
- [ ] Dashboard loads without errors

---

## 🆘 Common Issues & Solutions

### "Connection refused" Error
```
Solution:
1. Ensure MySQL is running
2. Check database credentials in includes/db.php
3. Verify database exists
```

### "Class not found" Error
```
Solution:
1. Ensure MySQLi extension is installed
2. Check PHP version is 7.4+
```

### "Permission denied" Error (Linux)
```
Solution:
sudo chmod -R 755 /var/www/html/Vehicle-Management/
sudo chown -R www-data:www-data /var/www/html/Vehicle-Management/
```

### "Setup page not found"
```
Solution:
1. Verify file path is correct
2. Restart web server
3. Clear browser cache
```

---

## 📂 Important Files

```
includes/db.php              → Database connection (EDIT THIS)
setup_db.php                 → Database initialization
admin_login.php              → Admin entry point
customer_login.php           → Customer entry point
employee_login.php           → Employee entry point
```

---

## 🌐 Application URLs

After successful setup, access:

```
http://localhost/Vehicle-Management/admin_login.php
http://localhost/Vehicle-Management/customer_login.php
http://localhost/Vehicle-Management/employee_login.php
http://localhost/Vehicle-Management/admin.php (after login)
http://localhost/Vehicle-Management/customer_dashboard.php (after login)
```

---

## 🆕 Next Steps

1. ✅ Complete setup
2. 🔑 Login with test credentials
3. 📝 Change default passwords
4. 👥 Create admin account
5. 🚗 Add test vehicle data
6. 💡 Explore all features

---

## 📞 Need Help?

- Check the main README.md for detailed documentation
- Review database schema in setup_db.php
- Check browser console for JavaScript errors
- Review PHP error logs

---

**Ready to go!** 🎉
