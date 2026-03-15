# SparkZone - Social Event Sharing Platform

A full-stack web application for sharing and discovering community events, built with PHP/MySQL backend and vanilla HTML/CSS/JS frontend.

## Features

- **User Authentication**: Secure signup/login system with password hashing
- **Event Sharing**: Upload photos and descriptions of community events
- **Event Discovery**: Browse and explore events shared by the community
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Modern UI**: Clean, gradient-based design with smooth interactions

## Technology Stack

- **Backend**: PHP 7.4+ with PDO
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Server**: Apache (XAMPP)
- **Deployment**: Local development environment

## Quick Start

1. **Setup XAMPP**: Install XAMPP on Windows
2. **Copy Files**: Place all files in `C:\xampp\htdocs\SparkZone\`
3. **Create Database**: Run the SQL schema from `database_schema.sql`
4. **Set Permissions**: Ensure `uploads/` directory is writable
5. **Start Services**: Launch Apache and MySQL in XAMPP Control Panel
6. **Visit**: `http://localhost/SparkZone/`

## Project Structure

```
SparkZone/
├── index.php              # Homepage with recent events
├── explore.php            # Browse all events
├── about.php              # About page
├── login.php              # User login
├── signup.php             # User registration
├── logout.php             # Logout handler
├── upload_post.php        # Event upload form
├── api/
│   └── get_posts.php      # JSON API for posts
├── inc/
│   ├── config.php         # Database config
│   ├── db.php             # PDO connection
│   ├── header.php         # Site header
│   └── footer.php         # Site footer
├── assets/
│   ├── style.css          # Main stylesheet
│   ├── main.js            # JavaScript functionality
│   └── logo2.jpg          # Site logo
├── uploads/               # Uploaded images
├── database_schema.sql    # Database setup
└── setup_instructions.md  # Detailed setup guide
```

## Security Features

- **Password Hashing**: Uses PHP's `password_hash()` and `password_verify()`
- **Prepared Statements**: All database queries use PDO prepared statements
- **Input Validation**: Server-side validation for all user inputs
- **File Upload Security**: Type and size validation for uploaded images
- **Session Management**: Secure session handling for user authentication

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Development Notes

This project follows modern web development best practices:

- **Responsive Design**: Mobile-first approach with CSS Grid and Flexbox
- **Progressive Enhancement**: Works without JavaScript, enhanced with it
- **Accessibility**: Semantic HTML and proper contrast ratios
- **Performance**: Optimized images, minified CSS/JS, lazy loading

## License

This is a educational/final year project. Feel free to use and modify for learning purposes.

## Author

Created as a final year project demonstrating full-stack web development skills with PHP, MySQL, and modern web technologies.
