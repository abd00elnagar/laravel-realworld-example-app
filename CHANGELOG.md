# Changelog

## [Unreleased] - 2025-10-30

### Authorization & Testing Improvements
- **Problem**: Inconsistent authorization for article revisions and missing test coverage
- **Solution**: Implemented proper authorization and comprehensive test coverage
- **Key Changes**:
  - Added strict authorization checks in `ArticleRevisionController`
  - Updated `ArticleRevisionPolicy` for consistent permission handling
  - Implemented comprehensive test cases in `ArticleRevisionTest`
- **Security**:
  - Only article authors can view, create, or revert revisions
  - Proper 401/403 responses for unauthorized access
  - Improved error handling and validation

## [1.0.0] - 2025-10-29

## [1.0.0] - 2025-10-29

### 1. Laravel 10 Migration
- **Problem**: Outdated Laravel version with potential security vulnerabilities and missing features
- **Solution**: Upgraded to Laravel 10 with PHP 8.1+ requirement
- **Key Changes**:
  - Upgraded to Laravel 10 (PHP >= 8.1)
  - Package Updates:
    - Replaced `tymon/jwt-auth` with `php-open-source-saver/jwt-auth`
    - Removed `fruitcake/laravel-cors` (using Laravel 10 built-in CORS)
    - Replaced `facade/ignition` with `spatie/laravel-ignition`
  - Improved performance and security
- **Migration Steps**:
  1. Update PHP to 8.1 or higher
  2. Run `composer update`
  3. Clear application caches

### 2. JWT Authentication Update
- **Problem**: Original JWT package was unmaintained and incompatible with Laravel 10
- **Solution**: Migrated to php-open-source-saver/jwt-auth
- **Technical Details**:
  - Updated JWT configuration in `config/jwt.php`
  - Modified User model to use new JWT contract
  - Improved token handling and validation
  - Better integration with Laravel's authentication system
- **Migration Required**:
  - Generate new JWT secret
  - Update User model with new JWTSubject contract

### 3. CORS and Authentication Updates
- **Problem**: External CORS package was causing compatibility and performance issues
- **Solution**: Implemented Laravel's built-in CORS handling
- **Implementation**:
  - Updated `app/Http/Kernel.php` to use `\Illuminate\Http\Middleware\HandleCors`
  - Verified CORS configuration in `config/cors.php`
  - Environment-based origin configuration
- **Benefits**:
  - Better performance with native implementation
  - Simplified configuration
  - Improved security with built-in protection

### 4. Database and Model Improvements

#### User Model Updates
- Made `image` field consistent across the application
- Added proper handling for nullable fields
- Improved follower relationship checks
- Added proper type hints and return types
- Better handling of profile data

#### Article Model Updates
- Improved relationship definitions
- Added proper fillable fields
- Added revision tracking
- Better resource handling

### 5. Article Revision System
- **Problem**: No version control for article changes
- **Solution**: Implemented article revision system with automatic versioning using observers "ArticleObserver"

#### Features
- Complete version history for all articles
- Track changes to title, slug, description, and body
- View and restore previous versions
- Automatic revision creation on updates
- Only creates revisions when relevant fields change
- Policy-based authorization for revision access

#### Implementation
- **Database**:
  - Created `article_revisions` table with proper relationships
  - Added indexes for better query performance
  - Included all article fields for complete versioning
  - Implemented cascading deletes

- **ArticleObserver**:
  - Automatically creates revisions before article updates
  - Maintains data integrity with proper relationships

- **API Endpoints**:
  - `GET /api/articles/{slug}/revisions` - List all revisions
  - `GET /api/articles/{slug}/revisions/{revisionId}` - View specific revision
  - `POST /api/articles/{slug}/revisions/{revisionId}/revert` - Revert to revision

- **Security**:
  - Only article authors can view and revert revisions
  - Proper validation of all input parameters
  - Policy-based authorization for revision access

- **Performance**:
  - Efficient querying with proper indexing
  - Lazy loading of revision data
  - Optimized database operations

### 6. Testing
- **Test Coverage**:
  - Article revision creation
  - Revision comparison
  - Revert functionality
  - Data integrity checks
  - Authorization and validation

### 7. Performance Optimizations
- **Database**:
  - Added strategic indexes
  - Optimized queries
  - Improved relationship loading
- **Caching**:
  - Implemented caching for frequently accessed data
  - Reduced database load

### Migration Instructions
1. Run database migrations:
   ```bash
   php artisan migrate
   ```
2. Clear application caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```
3. Run the test suite:
   ```bash
   php artisan test
   ```
4. Update your API clients to handle new response formats
5. Review and update any custom configurations in `.env`