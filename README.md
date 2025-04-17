# Music E-commerce Platform with User Management and Payment Integration

A comprehensive web-based music e-commerce platform that enables users to browse, purchase, and manage music tracks in multiple formats (CD, Vinyl, Cassette) with integrated payment processing through MoMo and VNPAY gateways.

The platform provides a complete solution for both users and administrators to manage music content, user accounts, and orders. Users can create accounts, browse music tracks by genre, maintain shopping carts, and complete purchases using multiple payment methods. Administrators can manage users, upload new music tracks, process orders, and view customer communications through a dedicated admin interface.

The system features robust user authentication, a dynamic music player, multiple payment gateway integrations (MoMo QR, MoMo ATM, VNPAY), and comprehensive order management capabilities. It supports various music formats and includes features like user profiles, contact forms, and detailed order tracking.

## Repository Structure
```
.
├── admin/                  # Admin interface files and functionality
│   ├── admin.php          # Main admin dashboard for user/music management
│   └── chucnang/          # Admin functions (orders, music upload, contacts)
├── dangky/                # User registration functionality
│   ├── captcha.php        # CAPTCHA verification for registration
│   └── dangky.php         # User registration processing
├── dangnhap/             # User authentication and account management
│   ├── giohang/          # Shopping cart and payment processing
│   │   ├── momo_atm.php  # MoMo ATM payment integration
│   │   ├── vnpay_php/    # VNPAY payment gateway integration
│   │   └── thanhtoan.php # Checkout processing
│   └── trang_user.php    # User dashboard/profile page
└── dsthanhvien.sql       # Database schema and initial data
```

## Usage Instructions
### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- SSL certificate (for payment gateway integration)
- MoMo payment gateway account
- VNPAY payment gateway account

### Installation
1. Clone the repository to your web server directory:
```bash
git clone [repository-url]
cd [repository-name]
```

2. Import the database schema:
```bash
mysql -u root -p dsthanhvien < dsthanhvien.sql
```

3. Configure database connection:
Edit connection parameters in relevant PHP files:
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dsthanhvien";
```

4. Configure payment gateway credentials:
Edit `dangnhap/giohang/vnpay_php/config.php` and MoMo configuration files with your credentials.

### Quick Start
1. Access the application through your web browser:
```
http://your-domain/
```

2. Register a new user account through the registration form

3. Log in with your credentials

4. Browse music tracks and add them to cart

5. Proceed to checkout and select payment method

### More Detailed Examples
1. Adding music to cart:
```php
// Select music format and quantity
$format = "CD"; // or "Vinyl" or "Cassette"
$quantity = 1;
addToCart($music_id, $format, $quantity);
```

2. Processing payments:
```php
// Initialize MoMo payment
$amount = 100000; // Amount in VND
$orderId = time(); // Unique order ID
initializeMoMoPayment($amount, $orderId);
```

### Troubleshooting
1. Payment Gateway Issues
- Error: "Payment gateway connection failed"
  - Check payment gateway credentials
  - Verify SSL certificate is valid
  - Ensure correct API endpoints

2. Database Connection Issues
- Error: "Connection failed"
  - Verify database credentials
  - Check MySQL service is running
  - Confirm database exists

3. File Upload Issues
- Error: "Upload failed"
  - Check directory permissions
  - Verify file size limits
  - Confirm allowed file types

## Data Flow
The platform processes user requests through a multi-step flow from browsing to purchase completion.

```ascii
User -> Authentication -> Browse Music -> Add to Cart -> Checkout
  |                                                        |
  |                                                        v
  |                                                    Payment Gateway
  |                                                        |
  v                                                        v
Profile/Orders <- Order Processing <- Payment Confirmation
```

Key component interactions:
1. User authentication validates credentials against database
2. Music catalog queries database for available tracks
3. Shopping cart maintains state in session and database
4. Payment processing integrates with external gateways
5. Order management updates status and inventory
6. Admin interface provides CRUD operations for all components
7. File system stores music and image files
8. Database maintains relational data for all entities