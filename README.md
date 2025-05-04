<p align="center">
    <img src="assets/logo.png">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/luissantiago/elogquent.svg?style=flat-square)](https://packagist.org/packages/luissantiago/elogquent)
[![Total Downloads](https://img.shields.io/packagist/dt/luissantiago/elogquent.svg?style=flat-square)](https://packagist.org/packages/luissantiago/elogquent)
[![Tests](https://github.com/luissantiago/elogquent/actions/workflows/run-tests.yml/badge.svg)](https://github.com/luissantiago/elogquent/actions/workflows/run-tests.yml)
[![Lint](https://github.com/luissantiago/elogquent/actions/workflows/code-quality.yml/badge.svg)](https://github.com/luissantiago/elogquent/actions/workflows/code-quality.yml)

Elogquent is a Laravel package that automatically tracks and stores all changes made to your Eloquent models. It
provides a complete history of modifications, allowing you to restore previous states of your models.

### Preview of restore:

<p align="center">
  <img src="assets/RestoreChange.gif" alt="RestoreChange"/>
</p>

## Features

- 🔄 Automatic tracking of model changes
- 📝 Configurable attribute inclusion/exclusion
- 🔍 Detailed change history
- ⏮️ Model state restoration
- 🧹 Duplicate change removal
- 👤 User attribution for changes
- 🛡️ Queue operations

## Installation

You can install the package via composer:

```bash
composer require elogquent/elogquent
```

Install the package:

```bash
php artisan elogquent:install
```

Run the migrations:

```bash
php artisan migrate
```

## Usage

### Basic Usage

Add the `Elogquent` trait to your model and any changes to your model will be automatically tracked:

```php
use Elogquent\Traits\Elogquent;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Elogquent;
}
```

### Accessing Model Changes History

You can access the change history through the `allChanges` relationship:

```php
$post = Post::find(1);
$changes = $post->allChanges;
```

### Restoring Previous States

Restore a model to a previous state:

```php
$modelChange = ModelChange::find(1);
$modelChange->restore();
```

You can also use the artisan command:
```php artisan elogquent:restore-changes```

## Configuration

Below are the key options you can configure in the `elogquent.php` configuration file:

---
⚙️ Basic Settings

| Option                | Description                                         | Default           |
|-----------------------|-----------------------------------------------------|-------------------|
| `enabled`             | Enable or disable Elogquent globally.               | `true`            |
| `store_user_id`       | Store the authenticated user's ID with each change. | `true`            |
| `database_connection` | The database connection used for change history.    | Laravel’s default |    


📋 Column Filtering

| Option             | Description                                                                                                                                             |
|--------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| `included_columns` | List of specific columns to track. If empty, all are tracked (except excluded).                                                                         |
| `excluded_columns` | Columns to ignore (e.g., sensitive or irrelevant ones). Defaults include:<br>`password`, `remember_token`, `api_token`, `secret`, `token`, `updated_at` |


🧹 History Optimization

| Option                       | Description                                                        |
|------------------------------|--------------------------------------------------------------------|
| `remove_previous_duplicates` | Avoid logging identical consecutive states. Keeps only the latest. |


📦 Change Limits

| Option                | Description                                                                                   |
|-----------------------|-----------------------------------------------------------------------------------------------|
| `changes_limit`       | Maximum number of total stored changes globally.                                              |
| `model_changes_limit` | Set limits per model class (overrides global).<br>Example:<br>`App\Models\Post::class => 100` |

🧵 Queue Settings

| Option             | Description                                   | Default                             |
|--------------------|-----------------------------------------------|-------------------------------------|
| `queue.delay`      | Delay (in seconds) before processing updates. | `0`                                 |
| `queue.connection` | The queue connection to use.                  | Laravel’s default (`queue.default`) |
| `queue.queue`      | Name of the queue to handle change jobs.      | `null`                              |


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Luis Santiago](https://github.com/luissantiago)

## Support

For support, please open an issue in the GitHub repository or contact soyluissantiagotorres@gmail.com
