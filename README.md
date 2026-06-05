# haniya_modified_viva  

A lightweight PHP application that generates PDF reports from diagnostic data stored in a MySQL database. It leverages the **FPDF** library (included as a sub‑module) to create, style, and output PDFs on‑the‑fly.

---

## Overview  

`haniya_modified_viva` reads diagnostic information from the `diagnostice.sql` database dump, processes the data with PHP, and produces formatted PDF documents. The repository bundles a ready‑to‑use copy of **FPDF** (v1.81) together with its full documentation, so you can start generating PDFs without additional dependencies.

---

## Features  

- **PDF Generation** – Full‑featured PDF creation using FPDF (tables, images, multi‑cell text, page breaks, etc.).  
- **Database‑Driven** – Simple MySQL schema (`diagnostice.sql`) for storing diagnostic records.  
- **Modular Design** – Core logic is isolated from the PDF library, making future upgrades straightforward.  
- **Ready Documentation** – All FPDF documentation (`FPDF‑master/doc/*.htm`) is included for quick reference.  
- **Zero‑Configuration Start** – Minimal setup; just configure your DB credentials and you’re ready to go.

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| Language | PHP 7.4+ |
| PDF Engine | FPDF (bundled) |
| Database | MySQL / MariaDB |
| Dependency Management | Composer (for FPDF) |
| Documentation | HTML docs shipped with FPDF |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/yourusername/haniya_modified_viva.git
   cd haniya_modified_viva
   ```

2. **Install PHP dependencies** (FPDF is already included, but Composer will set up the autoloader)  

   ```bash
   composer install
   ```

3. **Create the database**  

   ```bash
   # Adjust credentials as needed
   mysql -u root -p -e "CREATE DATABASE diagnostice CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root -p diagnostice < Database/diagnostice.sql
   ```

4. **Configure the connection**  

   Edit `config.php` (or the appropriate config file) and set your database credentials:  

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'diagnostice');
   define('DB_USER', 'YOUR_DB_USER');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

5. **Web server setup**  

   - Ensure the project root is served by Apache/Nginx with PHP enabled.  
   - Point the document root to the folder containing the entry script (e.g., `public/index.php`).  

   > **Note**: If you are using the built‑in PHP server for testing, run:  

   ```bash
   php -S localhost:8000 -t public
   ```

---

## Usage  

### Generating a PDF  

```php
<?php
require 'vendor/autoload.php';
require 'config.php';

// Load data from the database
$records = getDiagnosticRecords(); // implement your own query logic

// Initialise FPDF
$pdf = new \FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '