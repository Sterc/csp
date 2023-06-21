Sterc Content Security Policy
---

### How to use

This is unusual extra that should be installed into MODX 3 **with composer** to resolve potential dependency conflicts.

If you use MODX without `composer.json`, you need to [download this file from official MODX repository][1] into the 
root of your project and run `composer update`.

### Install

Just run
```
composer require sterc/csp --with-all-dependencies
```

Then you need to add installed package into MODX with shipped console script
```
composer exec sterc-csp install
```

This will run database migrations and register everything needed.

### Remove

Almost the same commands but in reversed order:
```
composer exec sterc-csp remove
composer remove sterc/csp
```

Custom tables will be deleted along with all other package entities.

[1]: https://github.com/modxcms/revolution/blob/3.x/package.json