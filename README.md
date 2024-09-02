Sterc Content Security Policy
---

### Dependencies

This package requires [mmxDatabase][mmx-database] to work with MODX database using Eloquent models.

The `mmx/database` dependency will be downloaded automatically by Composer, you need only install it inside MODX.

### Prepare

This package can be installed only with Composer.

If you are still not using Composer with MODX 3, just download the `composer.json` of your version:
```bash
cd /to/modx/root/
wget https://raw.githubusercontent.com/modxcms/revolution/v3.0.4-pl/composer.json
```

Then run `composer update` and you are ready to install this package.

### Install

```bash
composer require sterc/csp
composer exec mmx-database install # optional, if you haven't used Eloquent for MODX 3 before
composer exec sterc-csp install
```

### Update
```bash
composer update mmx/forms
composer exec mmx-forms install
```

### Remove

Almost the same commands but in reversed order:
```
composer exec sterc-csp remove
composer exec mmx-database remove # only if you don't want to use Eloquent for MODX 3 anymore
composer remove sterc/csp
```

Custom tables will be deleted along with all other package entities.

[mmx-database]: https://packagist.org/packages/mmx/database