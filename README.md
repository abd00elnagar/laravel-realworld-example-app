### Laravel RealWorld Example App

This Laravel app is part of the [RealWorld](https://github.com/gothinkster/realworld) project, implementing the backend API spec with added article revision functionality.

## Requirements
- PHP >= 8.1
- Composer
- SQLite (or your preferred database)

## Installation

1. Clone the repository
```bash
git clone https://github.com/abd00elnagar/laravel-realworld-example-app.git
cd laravel-realworld-example-app
```

2. Install dependencies
```bash
composer install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Configure your `.env` file:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

JWT_SECRET=your-generated-jwt-secret
JWT_TTL=60
```

5. Create SQLite database and run migrations
```bash
touch database/database.sqlite
php artisan migrate --seed
```

6. Start the development server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`

## Article Revision API Routes

All revision endpoints require authentication (`Authorization: Bearer <token>` header).

### List Article Revisions
```
GET /api/articles/{article}/revisions
```
Returns a list of all revisions for the specified article.

Response:
```json
{
    "revisions": [
        {
            "id": 1,
            "article_id": 1,
            "title": "Article Title",
            "slug": "article-slug",
            "description": "Description",
            "body": "Article content",
            "created_at": "2025-10-29T10:00:00Z",
            "updated_at": "2025-10-29T10:00:00Z"
        }
    ],
    "count": 1
}
```

### Get Specific Revision
```
GET /api/articles/{article}/revisions/{revision}
```
Returns details of a specific revision.

Response:
```json
{
    "revision": {
        "id": 1,
        "article_id": 1,
        "title": "Article Title",
        "slug": "article-slug",
        "description": "Description",
        "body": "Article content",
        "created_at": "2025-10-29T10:00:00Z",
        "updated_at": "2025-10-29T10:00:00Z"
    }
}
```

### Revert to Revision
```
POST /api/articles/{article}/revisions/{revision}/revert
```
Reverts the article to a specific revision state.

Response:
```json
{
    "message": "Article reverted to revision",
    "article": {
        "id": 1,
        "title": "Reverted Title",
        "slug": "reverted-slug",
        "description": "Reverted description",
        "body": "Reverted content"
    }
}
```

## Security Notes

1. Authentication
   - All revision endpoints require a valid JWT token
   - Token must be included in the `Authorization` header as `Bearer <token>`
   - Tokens expire after 60 minutes (configurable in `.env`)

2. Authorization
   - Only authenticated users can view revisions
   - Only article owners can revert to previous revisions
   - Article ownership is verified for each request

3. Data Protection
   - Revision history is maintained even after article updates
   - Cascade deletion ensures referential integrity
   - SQL injection protection via Laravel's query builder
   - XSS protection through Laravel's built-in security features

## Testing

Run the test suite:
```bash
php artisan test
```

## Additional Notes

1. Database Indexing
   - The `article_id` column in revisions table is indexed for better performance
   - Timestamps are included for audit trail

2. Error Handling
   - 401: Unauthorized (no/invalid token)
   - 403: Forbidden (not article owner)
   - 404: Article/Revision not found
   - 500: Server error (with logging)

3. Rate Limiting
   - API routes are rate-limited to prevent abuse
   - Configurable in `RouteServiceProvider.php`

Make sure you have PHP and Composer installed globally on your computer.

Clone the repo and enter the project folder

```
git clone https://github.com/alexeymezenin/laravel-realworld-example-app.git
cd laravel-realworld-example-app
```

Install the app

```
composer install
cp .env.example .env
```

Run the web server

```
php artisan serve
```

That's it. Now you can use the api, i.e.

```
http://127.0.0.1:8000/api/articles
```
