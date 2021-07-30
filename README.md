yii2-i18n-yarcode
=========

Yii2 i18n(internationalization) module to manage project translation.

This is fork of [zelenin/yii2-i18n-module](https://raw.githubusercontent.com/zelenin/yii2-i18n-module) with more useful interface.

##Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require vaszloy/yii2-i18n-yarcode "dev-master"
```

or add

```
"vaszloy/yii2-i18n-yarcode": "dev-master"
```

to the require section of your ```composer.json```

##Usage

Configure i18n component in your config file(for advanced application, use common config)

```php
'components' => [
    ...,
    'i18n' => [
        'class' => vaszloy\i18n\components\I18N::className(),
        'languages' => [
            'en-EN' => 'English',
            'es-ES' => 'Español',
            'ru-RU' => 'Русский',
        ],
        'defaultLanguage' => 'en-EN',
        'on missingTranslation' => ['vaszloy\i18n\Module', 'missingTranslation']
    ],
    ...
]

```

And connect module at backend application:

```php
'modules' => [
    ...,
    'i18n' => vaszloy\i18n\Module::className(),
    ...
]
```

Run migration:

```
php yii migrate --migrationPath=@vendor/yii-dream-team/yii2-i18n-yarcode/src/migrations
```

Go to ```http://backend.yourdomain.com/translations``` for translating your messages

##Authors

[Valentin Konusov](https://github.com/BioSin), e-mail: [rlng-krsk@yandex.ru](mailto:rlng-krsk@yandex.ru)
