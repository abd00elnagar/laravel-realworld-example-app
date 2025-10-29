# Changelog

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
  - Ensured proper token handling and validation
  - Improved security with updated package
  - Updated JWT configuration for better security
  - Improved token handling and validation
  - Better integration with Laravel's authentication system
- **Migration Required**:
  - Generate new JWT secret
  - Update User model with new JWTSubject contract

### 3. CORS and Authentication Updates
- **Problem**: External CORS package was causing compatibility issues
- **Solution**: Switched to Laravel's built-in CORS middleware
- **Implementation**:
  - Updated `app/Http/Kernel.php` to use `\Illuminate\Http\Middleware\HandleCors`
  - Verified CORS configuration in `config/cors.php`
  - Ensured proper headers for cross-origin requests
- **Problem**: External CORS package was causing performance issues
- **Solution**: Implemented Laravel's built-in CORS handling
- **Benefits**:
  - Better performance with native implementation
  - Simplified configuration
  - Better security with built-in protection
- **Configuration**:
  - CORS settings available in config/cors.php
  - Environment-based origin configuration

### 4. Article Revision System
- **Problem**: No version control for article changes
- **Solution**: Implemented comprehensive article revision system
- **Features**:
  - Track all changes to articles
  - View revision history
  - Revert to previous versions
  - Automatic revision creation on updates
- **Database**:
  - Created `article_revisions` table with proper relationships
  - Added indexes for better query performance
  - Implemented cascading deletes
- **Problem**: No version control for article changes
- **Solution**: Implemented comprehensive revision tracking
- **Features**:
  - Complete version history for all articles
  - Ability to view and restore previous versions
  - Automatic revision creation on updates
  - Efficient storage of revision data
- **Endpoints**:
  - GET /articles/{article}/revisions - List all revisions
  - GET /articles/{article}/revisions/{revision} - View specific revision
  - POST /articles/{article}/revisions/{revision}/revert - Restore revision
   
### 5. Database and Model Improvements

#### User Model Updates
- Made `image` field consistent across the application
- Added proper handling for nullable fields
- Improved follower relationship checks
- Added proper type hints and return types

#### Article Model Updates
- Fixed tag synchronization
- Improved relationship definitions
- Added proper fillable fields
- Enhanced query performance with eager loading

#### Validation and Error Handling
- Implemented proper validation rules for user updates
- Added custom error messages
- Improved error responses for API consumers

#### Article Revisions
- **Problem**: Needed efficient storage and retrieval of article history
- **Solution**: Implemented article revisions with proper relationships
- **Database Schema**:
  - Created article_revisions table with foreign key to articles
  - Added indexes for performance optimization
  - Included all article fields for complete versioning

#### Model Relationships
- **User Model**:
  - Added default avatar image
  - Improved user following functionality
  - Better handling of profile data

- **Article Model**:
  - Added revision tracking
  - Improved tag synchronization
  - Better resource handling

### 6. API Endpoints
- **Article Revisions**:
  - GET /articles/{article}/revisions - List all revisions
  - GET /articles/{article}/revisions/{revision} - View specific revision
  - POST /articles/{article}/revisions/{revision}/revert - Restore revision

### 7. Testing
- **Test Coverage**:
  - Article revision creation
  - Revision comparison
  - Revert functionality
  - Data integrity checks

### 8. Performance Optimizations
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