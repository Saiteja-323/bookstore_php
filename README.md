# Online Bookstore Management System

![Bookstore Screenshot](screenshot.png) <!-- Add a screenshot later -->

A complete PHP-based online bookstore with user and admin interfaces, shopping cart, order management, and review system.

## Features

- **User Authentication**: Registration, login, password reset
- **Product Management**: Browse by categories, search functionality
- **Shopping Cart**: Add/remove items, quantity adjustment
- **Order Processing**: Checkout, payment status tracking
- **Admin Dashboard**: Manage products, orders, users
- **Review System**: Product ratings and reviews with images

## Technologies Used

- PHP 8.2
- MySQL/MariaDB
- HTML5, CSS3, JavaScript
- Bootstrap (optional, if used)
- DomPDF (for invoice generation)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/bookstore.git
   cd bookstore
   ```

2. **Set up database**:
   - Import `shop_db.sql` to your MySQL server
   - Configure database credentials in `config.php`

3. **Configure environment**:
   - Set up your web server (Apache/Nginx) to point to the project folder
   - Ensure PHP and MySQL are properly installed

4. **Install dependencies**:
   ```bash
   composer install
   ```

5. **Set up file permissions**:
   ```bash
   chmod -R 755 uploaded_img/
   chmod -R 755 review_images/
   ```

## Configuration

Create a `.env` file (based on `.env.example` if provided) with your settings:

```ini
DB_HOST=localhost
DB_NAME=bookstore
DB_USER=root
DB_PASS=
BASE_URL=http://localhost/bookstore
```

## Usage

- **User Access**: `http://localhost/bookstore`
- **Admin Access**: `http://localhost/bookstore/admin_page.php`
  - Admin credentials: admin@bookstore.com / password

## Project Structure

```
bookstore/
├── assets/               # CSS, JS, images
├── admin/                # Admin panel files
├── includes/             # Common PHP files
├── uploaded_img/         # Product images
├── review_images/        # Review images
├── vendor/               # Composer dependencies
├── config.php            # Database configuration
├── index.php             # Home page
└── README.md             # This file
```

## Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Contact

Your Name - your.email@example.com  
Project Link: [https://github.com/your-username/bookstore](https://github.com/your-username/bookstore)
