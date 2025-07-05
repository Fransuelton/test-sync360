# 🧪 Technical Challenge - User Profile

A modern and robust REST API developed in Laravel for the **Technical Challenge - User Profile**. This API provides the complete backend for a user profile management application, with advanced validations, structured resources, and professional error handling.

## 🎯 About the Challenge

This project is part of a technical challenge that consists of developing a **complete web page** with the following functionalities:

- ✅ **Backend (This repository)**: REST API in Laravel with MySQL
- 🚧 **Frontend (In development)**: Responsive web interface for user profile

### Challenge Objective
Create a user profile interface with the possibility of **editing and saving** data, including:
- Profile image
- Full name  
- Age
- Address (street, neighborhood, state)
- Biography

### Technical Requirements Met
- ✅ API with GET and POST/PUT routes for users
- ✅ Structured MySQL database
- ✅ Robust data validations
- ✅ Professional error handling
- ✅ Complete API documentation
- ✅ Automated testing

## 📋 Table of Contents

- [About the Challenge](#-about-the-challenge)
- [Features](#-features)
- [Technologies](#-technologies)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [API Usage](#-api-usage)
- [Endpoints](#-endpoints)
- [Data Structure](#-data-structure)
- [Validations](#-validations)
- [Testing](#-testing)
- [Project Structure](#-project-structure)
- [Frontend (Next Steps)](#-frontend-next-steps)
- [Contributing](#-contributing)

## ✨ Features

- ✅ **Complete CRUD** - Full Create, Read, Update and Delete operations
- ✅ **User Profile** - Complete profile management according to challenge specifications
- ✅ **Robust Validation** - Custom validations with messages in Portuguese
- ✅ **API Resources** - Structured response data transformation
- ✅ **Error Handling** - Consistent responses for different scenarios
- ✅ **Complete Documentation** - Well-documented endpoints for frontend integration
- ✅ **Automated Testing** - Test coverage for critical functionalities
- ✅ **REST Standards** - Following REST API best practices
- ✅ **Calculated Address** - Automatic concatenation of complete address
- ✅ **MySQL Database** - Data persistence according to challenge requirements

## 🛠 Technologies

[![My Skills](https://skillicons.dev/icons?i=laravel,php,mysql)](https://skillicons.dev)

- **Laravel** `^12.0` - Modern PHP framework
- **PHP** `^8.2` - Programming language
- **Laravel Sanctum** `^4.0` - API authentication (ready for future use)
- **PHPUnit** `^11.5.3` - Testing framework
- **Laravel Pint** `^1.13` - Code styling
- **MySQL** - Database

## 📋 Prerequisites

Before starting, make sure you have installed:

- PHP >= 8.2
- Composer
- MySQL
- Git

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone git@github.com:Fransuelton/test-sync360.git
   cd test-sync360
   ```

2. **Access the backend directory**
   ```bash
   cd backend
   ```

3. **Install dependencies**
   ```bash
   composer install
   ```

4. **Configure the environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure the database in the `.env` file**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=perfil_usuario_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Start the server**
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000`

## ⚙️ Configuration

### Database

The application uses a `users` table with the following structure:

- `id` - Unique identifier (auto-increment)
- `profile_image` - Profile image URL
- `full_name` - User's full name
- `age` - Age (1-120 years)
- `street` - Street address
- `neighborhood` - Neighborhood
- `state` - State (2 characters)
- `biography` - User's biography
- `created_at` / `updated_at` - Timestamps

## 📖 API Usage

### Base URL
```
http://localhost:8000/api
```

### Required Headers
```
Content-Type: application/json
Accept: application/json
```

### Standard Response Format

**Success:**
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

**Error:**
```json
{
    "success": false,
    "message": "Error description",
    "error": "Technical details (if applicable)"
}
```

## 🔗 Endpoints

### 1. List All Users
```http
GET /api/users
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Users retrieved successfully.",
    "data": [
        {
            "id": 1,
            "full_name": "João Silva",
            "age": 30,
            "profile_image": "https://example.com/photo.jpg",
            "biography": "Developer passionate about technology",
            "full_address": "Flores Street, 123, Downtown, SP",
            "address_details": {
                "street": "Flores Street, 123",
                "neighborhood": "Downtown",
                "state": "SP"
            },
            "created_at": "04/07/2025 12:30",
            "updated_at": "04/07/2025 12:30"
        }
    ]
}
```

### 2. Get User by ID
```http
GET /api/users/{id}
```

**Parameters:**
- `id` (integer) - User ID

**Success Response (200):**
```json
{
    "success": true,
    "message": "User retrieved successfully.",
    "data": {
        "id": 1,
        "full_name": "João Silva",
        "age": 30,
        "profile_image": "https://example.com/photo.jpg",
        "biography": "Developer passionate about technology",
        "full_address": "Flores Street, 123, Downtown, SP",
        "address_details": {
            "street": "Flores Street, 123",
            "neighborhood": "Downtown",
            "state": "SP"
        },
        "created_at": "04/07/2025 12:30",
        "updated_at": "04/07/2025 12:30"
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "User not found."
}
```

### 3. Create New User
```http
POST /api/users
```

**Body (JSON):**
```json
{
    "profile_image": "https://example.com/photo.jpg",
    "full_name": "Maria Santos",
    "age": 25,
    "street": "Paulista Avenue, 1000",
    "neighborhood": "Bela Vista",
    "state": "SP",
    "biography": "UI/UX Designer with 5 years of experience"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "User created successfully.",
    "data": {
        "id": 2,
        "full_name": "Maria Santos",
        "age": 25,
        "profile_image": "https://example.com/photo.jpg",
        "biography": "UI/UX Designer with 5 years of experience",
        "full_address": "Paulista Avenue, 1000, Bela Vista, SP",
        "address_details": {
            "street": "Paulista Avenue, 1000",
            "neighborhood": "Bela Vista",
            "state": "SP"
        },
        "created_at": "05/07/2025 10:15",
        "updated_at": "05/07/2025 10:15"
    }
}
```

### 4. Update User
```http
PUT /api/users/{id}
```

**Parameters:**
- `id` (integer) - User ID

**Body (JSON):**
```json
{
    "full_name": "Maria Santos Silva",
    "age": 26,
    "biography": "Senior UI/UX Designer with 6 years of experience"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "User updated successfully.",
    "data": {
        "id": 2,
        "full_name": "Maria Santos Silva",
        "age": 26,
        "profile_image": "https://example.com/photo.jpg",
        "biography": "Senior UI/UX Designer with 6 years of experience",
        "full_address": "Paulista Avenue, 1000, Bela Vista, SP",
        "address_details": {
            "street": "Paulista Avenue, 1000",
            "neighborhood": "Bela Vista",
            "state": "SP"
        },
        "created_at": "05/07/2025 10:15",
        "updated_at": "05/07/2025 11:20"
    }
}
```

### 5. Delete User
```http
DELETE /api/users/{id}
```

**Parameters:**
- `id` (integer) - User ID

**Success Response (200):**
```json
{
    "success": true,
    "message": "User deleted successfully."
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "User not found."
}
```

## 📊 Data Structure

### User Model

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `id` | integer | Auto | Unique identifier |
| `profile_image` | string/url | No | Profile image URL |
| `full_name` | string | Yes | Full name (2-255 chars) |
| `age` | integer | Yes | Age (1-120 years) |
| `street` | string | Yes | Street address |
| `neighborhood` | string | Yes | Neighborhood |
| `state` | string | Yes | State (exactly 2 chars) |
| `biography` | text | Yes | Biography (max. 1000 chars) |
| `full_address` | string | Calculated | Complete concatenated address |

## ✅ Validations

### User Creation (POST)

- **profile_image**: Optional, must be a valid URL, maximum 500 characters
- **full_name**: Required, string, minimum 2 characters, maximum 255
- **age**: Required, integer between 1 and 120
- **street**: Required, string, maximum 255 characters
- **neighborhood**: Required, string, maximum 255 characters
- **state**: Required, exactly 2 characters
- **biography**: Required, string, maximum 1000 characters

### User Update (PUT)

The same validations apply, but all fields are optional.

### Custom Error Messages

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "full_name": ["The full name is required."],
        "age": ["The age must be at least 1 year."],
        "state": ["The state must be exactly 2 characters."]
    }
}
```

## 🧪 Testing

Run automated tests:

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run only feature tests
php artisan test tests/Feature

# Run specific test
php artisan test tests/Feature/UserTest.php
```

### Test Coverage

Tests cover:
- ✅ User listing
- ✅ User creation
- ✅ Search by ID
- ✅ User updates
- ✅ User deletion
- ✅ Input validations
- ✅ Error handling
- ✅ `full_address` accessor

## 📁 Project Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   └── UserController.php      # Main controller
│   │   ├── Requests/
│   │   │   ├── StoreUserRequest.php    # Creation validation
│   │   │   └── UpdateUserRequest.php   # Update validation
│   │   └── Resources/
│   │       └── UserResource.php        # Data transformation
│   ├── Models/
│   │   └── User.php                    # Model with accessor
│   └── Services/
│       └── UserService.php             # Business logic
├── database/
│   ├── migrations/
│   │   └── 2025_07_04_120659_create_users_table.php
│   ├── factories/
│   │   └── UserFactory.php             # Factory for tests
│   └── seeders/
├── routes/
│   └── api.php                         # Route definitions
└── tests/
    └── Feature/
        ├── UserTest.php                # Integration tests
        └── UserAccessorTest.php        # Accessor tests
```

## 🔄 HTTP Status Codes

| Code | Description |
|------|-------------|
| `200` | Operation completed successfully |
| `201` | Resource created successfully |
| `404` | Resource not found |
| `422` | Invalid data (validation error) |
| `500` | Internal server error |

## 🚀 Future Improvements

- [ ] Laravel Sanctum authentication implementation
- [ ] Pagination for user listing
- [ ] Advanced filters and search
- [ ] Local image upload
- [ ] Audit logs
- [ ] Query caching
- [ ] Rate limiting
- [ ] Swagger/OpenAPI documentation

## 🎨 Frontend (Next Steps)

The application frontend will be developed following the technical challenge requirements:

### 📱 Profile Interface
- **User profile viewing** page
- **Edit form** with all fields
- **Responsive design** (mobile and desktop)
- **Real-time validations**

### 🛠 Planned Frontend Technologies
- **Framework**: React/Vue.js/Angular (to be defined)
- **Styling**: Tailwind CSS or Bootstrap
- **Validation**: Native JavaScript or validation library
- **HTTP Client**: Axios or Fetch API
- **State Management**: Context API or Vuex/Redux (if needed)

### 🔗 API Integration
The frontend will consume this API through the endpoints:
- `GET /api/users/{id}` - Fetch user data
- `PUT /api/users/{id}` - Update user data
- `POST /api/users` - Create new user (if needed)

#### JavaScript Integration Example:
```javascript
// Load user data
async function loadUserProfile(userId) {
    try {
        const response = await fetch(`/api/users/${userId}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        
        if (data.success) {
            displayUserProfile(data.data);
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

// Update user data
async function updateUserProfile(userId, formData) {
    try {
        const response = await fetch(`/api/users/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccessMessage('Profile updated successfully!');
            displayUserProfile(data.data);
        } else {
            showErrors(data.errors);
        }
    } catch (error) {
        console.error('Error updating profile:', error);
    }
}
```

### 📋 Planned Features
- ✅ User information display
- ✅ Responsive edit form
- ✅ Frontend data validation
- ✅ Visual feedback for user
- ✅ API error handling
- ✅ Loading states during requests
- ✅ Save confirmation

### 🎯 Page Structure
```
┌─────────────────────────────────────┐
│           Header/Nav                │
├─────────────────────────────────────┤
│  ┌─────────────────────────────────┐ │
│  │       Profile Image             │ │
│  └─────────────────────────────────┘ │
│  ┌─────────────────────────────────┐ │
│  │       User Information          │ │
│  │  • Full Name                   │ │
│  │  • Age                         │ │
│  │  • Complete Address            │ │
│  │  • Biography                   │ │
│  └─────────────────────────────────┘ │
│  ┌─────────────────────────────────┐ │
│  │    [Edit Profile] Button       │ │
│  └─────────────────────────────────┘ │
└─────────────────────────────────────┘
```

### 📱 Responsiveness
- **Desktop**: Two-column layout (image + data)
- **Tablet**: Adapted layout with image on top
- **Mobile**: Optimized single-column layout

## 🤝 Contributing

1. Fork the project
2. Create a branch for your feature (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

## 📄 License

This project is under the MIT license. See the [LICENSE](LICENSE) file for more details.

## 👨‍💻 About the Development

This project was developed as part of a **technical challenge** to demonstrate skills in:

- ✅ **Backend Development** - Laravel, PHP, MySQL
- ✅ **API Design** - REST, validations, documentation
- ✅ **Database Design** - Efficient and normalized structure
- ✅ **Testing** - Automated tests and coverage
- ✅ **Documentation** - Professional and complete README
- 🚧 **Frontend Development** - In development

### 🎯 Challenge Objective
> *"Develop a web page with user profile functionalities with the possibility of editing and saving data in a MySQL database."*

**Status**: ✅ Complete backend | 🚧 Frontend in development

---

**API Version:** 1.0.0  
**Last Updated:** July 2025  
**Type:** Technical Challenge - Backend API