# E-commerce Application (Frontend & Backend)


## Tech Stack
- Backend: Laravel (PHP)
- Frontend: Vue 3, Vite, Pinia, Tailwind CSS
- Database: Configure in `.env` (e.g., MySQL/MariaDB)

## Local Setup
### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```

## API Endpoints
### Frontend API
- `GET /api/products`
- `GET /api/categories`
- `GET /api/products/slug/{slug}`
- `GET /api/products/{product}`
- `POST /api/orders`

### Admin API
- `POST /api/admin/login`
- `GET /api/admin/me`
- `POST /api/admin/logout`
- `GET /api/admin/category`
- `POST /api/admin/category`
- `GET /api/admin/category/{category}`
- `PUT /api/admin/category/{category}`
- `DELETE /api/admin/category/{category}`
- `DELETE /api/admin/category/multiple-delete`
- `PUT /api/admin/category/{category}/status`
- `GET /api/admin/products`
- `POST /api/admin/products`
- `GET /api/admin/products/{product}`
- `PUT /api/admin/products/{product}`
- `DELETE /api/admin/products/{product}`
- `DELETE /api/admin/products/multiple-delete`
- `PUT /api/admin/products/{product}/status`
- `GET /api/admin/products/create-data`
- `GET /api/admin/products/attribute-values`
- `POST /api/admin/editor-file/upload`
- `GET /api/admin/orders`
- `GET /api/admin/orders/{order}`
- `GET /api/admin/attributes`
- `POST /api/admin/attributes`
- `GET /api/admin/attributes/{attribute}`
- `PUT /api/admin/attributes/{attribute}`
- `DELETE /api/admin/attributes/{attribute}`
- `DELETE /api/admin/attributes/multiple-delete`
- `GET /api/admin/attribute-values`
- `POST /api/admin/attribute-values`
- `GET /api/admin/attribute-values/{attribute_value}`
- `PUT /api/admin/attribute-values/{attribute_value}`
- `DELETE /api/admin/attribute-values/{attribute_value}`
- `DELETE /api/admin/attribute-values/multiple-delete`

## Live Deployment URLs
- Frontend: Not deployed
- Admin: Not deployed
- API: Not deployed

## Admin Login
- Email: `admin@gmail.com`
- Password: `password`
