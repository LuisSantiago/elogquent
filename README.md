
<p align="center">
    <img src="assets/logo.png" alt="Elogquent Logo">
</p>

<p align="center">
  <a href="https://packagist.org/packages/luissantiago/elogquent"><img src="https://img.shields.io/packagist/php-v/luissantiago/elogquent" alt="PHP Version"></a>
  <a href="https://packagist.org/packages/luissantiago/elogquent"><img src="https://img.shields.io/packagist/v/luissantiago/elogquent.svg?style=flat-square" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/luissantiago/elogquent"><img src="https://img.shields.io/packagist/dt/luissantiago/elogquent.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://github.com/luissantiago/elogquent/actions/workflows/run-tests.yml"><img src="https://github.com/luissantiago/elogquent/actions/workflows/run-tests.yml/badge.svg" alt="Tests"></a>
  <a href="https://github.com/luissantiago/elogquent/actions/workflows/code-quality.yml"><img src="https://github.com/luissantiago/elogquent/actions/workflows/code-quality.yml/badge.svg" alt="Lint"></a>
  <a href="https://github.com/luissantiago/elogquent"><img src="https://img.shields.io/badge/coverage-91%25-brightgreen" alt="Coverage"></a>
</p>

---

**Elogquent** is a Laravel package that automatically tracks and stores all changes made to your Eloquent models.  
It provides a complete audit trail and allows restoring models to previous states with ease.

### Restore Preview

<p align="center">
  <img src="assets/RestoreChanges.gif" alt="RestoreChanges Animation" />
</p>

---

## Features

- Automatic model change tracking
- Configurable attribute inclusion/exclusion
- Detailed change history per model
- Restore previous model states
- Duplicate change optimization
- User attribution for changes
- Supports queue-based processing

---

## Installation

Install the package via Composer:

```bash
composer require elogquent/elogquent
```

Publish config and install:

```bash
php artisan elogquent:install
```

Run the database migrations:

```bash
php artisan migrate
```

---

## Usage

### Add Trait to Your Model

```php
use Elogquent\Traits\Elogquent;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Elogquent;
}
```

### Access Change History

```php
$post = Post::find(1);
$changes = $post->allChanges;
```

### Restore to a Previous State

```php
$modelChange = ElogquentEntry::find(1);
$modelChange->restore();
```

Or use the artisan command:

```bash
php artisan elogquent:restore-changes
```

---

## Configuration

Edit the `elogquent.php` config file for customization.

### Basic Settings

| Option                | Description                                         | Default           |
|-----------------------|-----------------------------------------------------|-------------------|
| `enabled`             | Enable or disable Elogquent globally.               | `true`            |
| `store_user_id`       | Store the authenticated user's ID with each change. | `true`            |
| `database_connection` | The database connection used for change history.    | Laravel default   |

### Column Filtering

| Option             | Description                                                                                                                 |
|--------------------|-----------------------------------------------------------------------------------------------------------------------------|
| `included_columns` | Specific columns to track. If empty, all columns are tracked (except excluded).                                             |
| `excluded_columns` | Columns to ignore (e.g. sensitive fields). Default: `password`, `remember_token`, `api_token`, `secret`, `token`, `updated_at` |

### History Optimization

| Option                       | Description                                                        |
|------------------------------|--------------------------------------------------------------------|
| `remove_previous_duplicates` | Prevent logging identical consecutive states. Keeps only the latest. |

### Change Limits

| Option                | Description                                                                                   |
|-----------------------|-----------------------------------------------------------------------------------------------|
| `changes_limit`       | Global limit on total number of stored changes.                                               |
| `model_changes_limit` | Limit changes per model. Example: `'App\Models\Post::class' => 100`                         |

### Queue Settings

| Option             | Description                                   | Default                             |
|--------------------|-----------------------------------------------|-------------------------------------|
| `queue.delay`      | Delay (in seconds) before processing updates. | `0`                                 |
| `queue.connection` | Queue connection to use.                      | Laravel default (`queue.default`)   |
| `queue.queue`      | Queue name for change jobs.                   | `null`                              |

---

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

---

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.

---

## Credits

- [Luis Santiago](https://github.com/luissantiago)

---

## Support

For support, open an issue in the GitHub repository or contact  
**soyluissantiagotorres@gmail.com**
