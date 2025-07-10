# Laravel InertiaJS VueJS Challenge

This project is built with Laravel, Docker (Sail), PostgreSQL, InertiaJS, and VueJS 3.

## Prerequisites

- Docker Desktop
- Git
- Composer (optional, as we'll use Sail)

## Installation Steps

1. Clone the repository:
```bash
git clone https://github.com/jcguerra/laravel-inertiajs-vue-challenge.git
cd laravel-inertiajs-vue-challenge
```

2. Copy the environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
./vendor/bin/sail artisan key:generate
```

4. Configure PostgreSQL in .env file:
```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

5. Install PHP dependencies:
```bash
composer install
```

6. Start Laravel Sail:
```bash
./vendor/bin/sail up -d
```

7. Run database migrations and seed:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

This will create the initial database structure and seed it with test data, including an admin user:
- Email: admin@admin.com
- Password: password

9. Install Node.js dependencies:
```bash
./vendor/bin/sail npm install
```

10. Build assets:
```bash
./vendor/bin/sail npm run dev
```

## Development

- Start the development server:
```bash
./vendor/bin/sail up -d
```

- Watch for asset changes:
```bash
./vendor/bin/sail npm run dev
```

## Accessing the Application

The application will be available at: http://localhost

You can log in with the following credentials:
- Email: admin@admin.com
- Password: password

## Additional Commands

- Stop containers:
```bash
./vendor/bin/sail down
```

- Run tests:
```bash
./vendor/bin/pest
```

- Access PostgreSQL:
```bash
./vendor/bin/sail psql
```

## Troubleshooting

If you encounter any issues:

1. Make sure Docker Desktop is running
2. Check if all required ports are available (80, 5173, 5432)
3. Try rebuilding the containers:
```bash
./vendor/bin/sail down
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```




### API Documentation

The application provides a RESTful API with JWT authentication for secure access to resources.

#### Authentication Endpoints

- **Login**
  ```http
  POST /api/auth/login
  Content-Type: application/json

  {
    "email": "admin@admin.com",
    "password": "password"
  }
  ```
  Response:
  ```json
  {
    "data": {
        "message": "Ok!",
        "token": "token",
        "token_type": "bearer",
        "expires_in": 3600,
        "code": 200
    }
  }
  ```

- **Logout**
  ```http
  POST /api/auth/logout
  Authorization: Bearer {token}
  ```
  Response:
  ```json
  {
    "data": {
        "message": "Session closed successfully"
    }
  }
  ```

#### Product Endpoints

- **Get All Products**
  ```http
  GET /api/products
  Authorization: Bearer {token}
  Query Parameters:
  - page: number
  - perPage: number (10/25/50)
  - sort_by: string
  - sort_direction: string (asc/desc)
  - search: string
  ```
  Response:
  ```json
  {
    "data": {
        "products": [
            {
                "id": 2,
                "name": "Dr. Johnathan Stehr",
                "description": "Facere non qui aspernatur provident.",
                "price": "33.42",
                "updated_at": "2025-06-13T22:41:39.000000Z",
                "created_by": {
                    "id": 1,
                    "name": "Admin"
                }
            },
            {
                "id": 17,
                "name": "Mr. Aron Reichert MD",
                "description": "Mollitia ducimus quia tempore dolor.",
                "price": "26.68",
                "updated_at": "2025-06-13T22:41:39.000000Z",
                "created_by": {
                    "id": 1,
                    "name": "Admin"
                }
            },
            {
                "id": 78,
                "name": "Theodore Wunsch",
                "description": "Voluptas ex aliquam molestias.",
                "price": "73.57",
                "updated_at": "2025-06-13T22:41:39.000000Z",
                "created_by": {
                    "id": 1,
                    "name": "Admin"
                }
            }
        ],
        "pagination": {
            "total": 13,
            "per_page": 10,
            "current_page": 1,
            "last_page": 2,
            "from": 1,
            "to": 10
        }
      }
  } 
  ```

#### Column Sorting
- Server-side sorting with visual indicators
- Toggle between ascending and descending order
- Sort direction indicators (↑/↓)
- Maintains sort state during filtering and pagination

### JWT Authentication

The API uses JWT (JSON Web Tokens) for authentication:

- Tokens are issued upon successful login
- Tokens expire after 1 hour
- Tokens must be included in the Authorization header
- Format: `Authorization: Bearer {token}`

#### Security Features

- CSRF protection
- Rate limiting on authentication endpoints
- Token blacklisting on logout
- Secure password hashing
- Input validation and sanitization

### Technical Implementation
- Laravel query builder with dynamic filtering
- Vue 3 Composition API with reactive state management
- Inertia.js form handling with optimistic UI updates
- Comprehensive test coverage (unit and feature tests)

### Technical Architecture

The application follows modern best practices and architectural patterns:

#### Backend Architecture
- **Separation of Concerns**
  - Controllers: Handle HTTP requests and responses
    - Web Controllers:
      - `app/Http/Controllers/Products/GetAllProductsController.php`
      - `app/Http/Controllers/Products/DeleteProductController.php`
      - `app/Http/Controllers/Products/GetProductDetailsController.php`
    - API Controllers:
      - `app/Http/Controllers/Api/Auth/LoginController.php`
      - `app/Http/Controllers/Api/Auth/LogoutController.php`
      - `app/Http/Controllers/Api/Products/GetAllProductsApiController.php`
  - Services: Contain business logic
    - `app/Services/ProductsService.php`
  - Models: Define data structure and relationships
    - `app/Models/Product.php`
  - Repositories: Handle data access and persistence
    - `app/Repositories/ProductRepository.php`
    - `app/Interfaces/ProductRepositoryInterface.php`

- **Dependency Injection**
  - Service container for automatic dependency resolution
  - Interface-based design for loose coupling
  - Constructor injection for better testability
  ```php
  // Example from GetAllProductsController.php
  public function __construct(private readonly ProductsService $productService)
  ```

#### Frontend Architecture
- **Vue 3 Composition API**
  - Reactive state management
  - Reusable composables
  - Component-based architecture
  ```vue
  // Example from resources/js/pages/products/AllProducts.vue
  const sortBy = ref('id');
  const sortDirection = ref('asc');
  const perPage = ref(props.products.per_page);
  ```

- **Inertia.js Integration**
  - Seamless Laravel-Vue integration
  - Server-side routing with client-side navigation
  - State preservation during navigation
  ```php
  // Example from GetAllProductsController.php
  return Inertia::render('products/AllProducts', [
      'products' => $products
  ]);
  ```

#### Error Handling
- Consistent error handling patterns
  - Form validation in controllers
  - Error handling in Vue components
- User-friendly error messages
- Comprehensive test coverage
  - `tests/Unit/Services/ProductsServiceTest.php`
  - `tests/Feature/Controllers/Products/GetAllProductsControllerTest.php`

### Implemented Optimizations

The application includes several optimizations to ensure efficient performance:

#### Database Optimizations
- **Indexes Implementation**
  ```php
  // Simple indexes for searches and sorting
  $table->index('name');
  $table->index('price');
  $table->index('stock');
  $table->index('is_active');
  
  // Composite index for common searches
  $table->index(['name', 'is_active']);
  
  // Full-text index for text searches
  $table->fullText(['name', 'description']);
  ```

- **Query Optimizations**
  ```php
  // Eager loading to prevent N+1 queries
  $query = Product::with(['user']);
  
  // Using indexes for exact matches
  if (in_array($column, ['price', 'stock', 'is_active'])) {
      $query->where($column, $value);
  }
  
  // Optimized sorting using indexes
  if (in_array($sortBy, ['name', 'price', 'stock'])) {
      $query->orderBy($sortBy, $sortDirection);
  }
  ```

#### Frontend Optimizations
- **Data Transformation**
  - Using Vue's computed properties for data formatting
  - Client-side data manipulation for better performance
  - Leveraging Inertia.js for efficient data serialization

- **State Management**
  - Local component state for UI interactions
  - Efficient prop passing between components
  - Optimized re-rendering with Vue's reactivity system

## License

[Your License]
