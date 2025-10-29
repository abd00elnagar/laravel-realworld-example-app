# Changelog

## [1.0.0] - 2025-10-29

### 1. Initial Laravel 10 Upgrade (6d395212)
- Upgraded PHP requirements to ^8.1
- Updated package dependencies:
  - Removed `fruitcake/laravel-cors`
  - Replaced `tymon/jwt-auth` with `php-open-source-saver/jwt-auth`
  - Replaced `facade/ignition` with `spatie/laravel-ignition`
- Updated composer.json configuration

### 2. JWT Authentication Update (f88bd181)
- Streamlined JWT configuration
- Updated provider namespaces to use PHP-OSS-Saver
- Simplified jwt.php configuration file
- Updated JWT provider settings:
  ```php
  'providers' => [
      'jwt' => PHPOpenSourceSaver\JWTAuth\Providers\JWT\Lcobucci::class,
      'auth' => PHPOpenSourceSaver\JWTAuth\Providers\Auth\Illuminate::class,
      'storage' => PHPOpenSourceSaver\JWTAuth\Providers\Storage\Illuminate::class,
  ]
  ```

### 3. CORS and Auth Configuration (26baac9c)
- Implemented built-in Laravel CORS middleware
- Updated Kernel.php to use native CORS handling
- Updated User model JWT implementation
- Configured auth guards and providers
- Updated database configuration

### 4. Article Revision System (2dd1a375)
### Added
- Article revision system
  - Created `ArticleRevisionController` with endpoints for:
    - Listing article revisions
    - Showing specific revision details
    - Reverting articles to previous revisions
  - Added `ArticleRevision` model with relationships and fillable fields
  - Created migration for `article_revisions` table
  - Added API routes for revision functionality:
    - GET `articles/{article}/revisions`
    - GET `articles/{article}/revisions/{revision}`
    - POST `articles/{article}/revisions/{revision}/revert`

### Changed
- Upgraded to Laravel 10 (PHP >= 8.1)
- Package changes:
  - Removed `fruitcake/laravel-cors` in favor of built-in CORS middleware
  - Replaced `tymon/jwt-auth` with `php-open-source-saver/jwt-auth`
  - Replaced `facade/ignition` with `spatie/laravel-ignition`
- File-level changes:
  - `app/Http/Kernel.php`: Switched to built-in `\Illuminate\Http\Middleware\HandleCors`
  - `app/Models/User.php`: Updated JWT contract import and fixed attributes
  - `config/jwt.php`: Updated JWT providers configuration
  - `config/cors.php`: Updated for Laravel 10 built-in CORS
  - `app/Http/Controllers/ArticleController.php`: Fixed tag syncing and request handling
  - Enhanced `ArticleResource` to include article ID in response

### Fixed
- `User` model:
  - Changed attributes from `images` to `image` for consistency
  - Updated `doesUserFollowAnotherUser` to handle unittest follower checks
- `ArticleController`:
  - Removed invalid `$this->request` reference
  - Fixed tag syncing by passing `tagList` explicitly
  - Adjusted `syncTags` method signature for explicit tag list handling

### Database Changes
- Created new table `article_revisions` with fields:
  - `id` (primary key)
  - `article_id` (foreign key with cascade delete)
  - `title`
  - `slug`
  - `description`
  - `body`
  - `timestamps`
- Added index on `article_id` for better query performance