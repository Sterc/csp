Sterc Content Security Policy
---
# The New Skeleton Structure

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
After installation, module appears in the menu.

There was an issue with the symlink used to link the assets/components/sterc-csp folder to its actual location inside the vendor folder. However, it all worked after sterc-scp/assets files were copied to the location inside webroot/assets/components/ folder.
```bash
cp -r ./webroot/core/vendor/sterc/csp/assets/* to ./webroot/assets/components/sterc-csp/
```
It appears issues is specific for MS Windows machines.

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

# The Old Project Skeleton

The old project skeleton has a different structure with webroot and private root folders

In order to install package on older skeleton, additional change is required:

In a file: `private/core/vendor/sterc/csp/core/src/App.php`
This line should be added at line 25: 
```php
$this->modx->services->add('mmxDatabase', new \MMX\Database\App($modx));
```

This is to load mmxDatabase, without this it is not possible to install or use CSP package.

The rest of the installation is the same as with the new skeleton.

There was an issue with frontend call and redirection: https://evbox.com.local/en/sterc-csp/admin/groups

This is a VUE application that loads in modx manager page. The url path was prefixed with /en/.
