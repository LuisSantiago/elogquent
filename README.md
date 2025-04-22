![Elogquent](assets/logo.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/luissantiago/elogquent.svg?style=flat-square)](https://packagist.org/packages/luissantiago/elogquent)
[![Total Downloads](https://img.shields.io/packagist/dt/luissantiago/elogquent.svg?style=flat-square)](https://packagist.org/packages/luissantiago/elogquent)
[![Tests](https://github.com/luissantiago/elogquent/actions/workflows/run-tests.yml/badge.svg)](https://github.com/luissantiago/elogquent/actions/workflows/run-tests.yml)

Elogquent is a powerful Laravel package that automatically tracks and stores all changes made to your Eloquent models. It provides a complete history of modifications, allowing you to restore previous states of your models with ease.

## Features

- 🔄 Automatic tracking of model changes
- 📝 Configurable attribute inclusion/exclusion
- 🔍 Detailed change history
- ⏮️ Model state restoration
- 🧹 Duplicate change removal
- 👤 User attribution for changes
- 🛡️ Transaction-safe operations

## Installation

You can install the package via composer:

```bash
composer require elogquent/elogquent
```

Install the package:

```bash
php artisan elogquent:install"
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


## Configuration

This will create a config/elogquent.php file. Below are the key options you can configure:

---

### `included_columns`

```php
'included_columns' => [],
```

**Description:**  
A whitelist of attributes to be tracked in the change history.  
If empty, **all attributes** will be recorded (excluding those listed in `excluded_columns`).

**Example:**

```php
'included_columns' => ['name', 'email', 'status'],
```
---
### `excluded_columns`
```php
'excluded_columns' => [
    'password',
    'remember_token',
    'api_token',
    'secret',
    'token',
    'updated_at',
],
```

**Description:**  
Blacklist of fields that will be ignored from tracking. Useful for skipping sensitive or irrelevant fields.

---

### `remove_previous_duplicates`

```php
'remove_previous_duplicates' => env('ELOGQUENT_REMOVE_DUPLICATES', true),
```

**Description:**  
When enabled, repeated changes with identical values will not be stored again.  
This reduces redundancy and log noise by keeping only the latest entry for a given model state.




## Security

If you discover any security-related issues, please email security@elogquent.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Your Name](https://github.com/luissantiago)

## Support

For support, please open an issue in the GitHub repository or contact soyluissantiagotorres@gmail.com
