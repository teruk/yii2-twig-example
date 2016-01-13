Yii 2 Basic Project Template with Twig Implementation and Internationalization
============================
This example shows a basic implementation of Twig in Yii2. It also show different ways to work with internationalization.

The goal of this example is to show for using twig and internationalization without using sessions.

In this example, a platform controller is used to determine the current domain which the used to access the page. Depending on that, the content of the page should be displayed in the language according to the domain name / ending. Every other controller should extend the platform controller so that language is checked on every request.

PlatformController
-------------
This controller should make sure, that every coonfiguration at beginning of each request. Every necessary should be called in the init function, which is called before every action.

Note: uncontrolled use can lead to performance issues, only necessary things should in that function

Configuration config/web.php
-------------
setting up twig
```php
'view' => [
    'class' => 'yii\web\View',
    'renderers' => [
        'twig' => [
            'class' => 'yii\twig\ViewRenderer',
            'cachePath' => '@runtime/Twig/cache', // cache patch
            // Array of twig options:
            'options' => [
                'auto_reload' => true,
            ],
            'globals' => [ // defining available classes
                'html' => '\yii\helpers\Html',
                'url' => '\yii\helpers\Url',
            ],
            'functions' => [ // defining available function
                't' => 'Yii::t',
                'cookieGet' => 'Yii::$app->request->cookies->get',
            ],
            'uses' => ['yii\bootstrap'],
        ],
    ],
],
```

setting up internationalization
```php
'i18n' => [
  'translations' => [
      'app*' => [
          'class' => 'yii\i18n\PhpMessageSource',
          'fileMap' => [
              'app' => 'app.php',
              'app/error' => 'error.php',
              'app/navigation' => 'navigation.php',
          ],
      ],
  ],
],
```

Internationalization
-------------
Currently the translated messages are stored in php files
```
messages
  \de-DE
    app.php
    navigation.php
  \en-US
    app.php
    navigation.php
  \fr-FR
    app.php
    navigation.php
```

At the moment there are two ways of using the internationalization in the views:

#### cookie based 

```php
/**
 * gets the language from cookies,
 * if it does not exists, detemine language from http host.
 *
 * @return String
 */
public function getLanguageFromCookie()
{
  if (!$this->_language) {
      $cookies = Yii::$app->request->cookies;
      if (($this->_language = $cookies->get('language')) === null) {
          $this->_language = $this->getLocale();
          $cookies = Yii::$app->response->cookies;
          $cookies->add(new Cookie([
              'name' => 'language',
              'value' => $this->_language,
              ]));
    }
  }

  return $this->_language;
}
```

accessing the cookie in view via cookieGet() (see config/web.php) - navigation.twig
```html
<li class="active"><a href="{{ url.to(['site/index']) }}">{{ t('app/navigation', 'home', null, cookieGet('language', 'en-US')) }}</a></li>
```

#### passing language variable during view rendering

render view and passing language
```php
// SiteController.php
public function actionAbout()
{
    return $this->render('about.twig', [
        'userLanguage' => $this->getLocale(), // getLocale() is defined in PlatformController.php
        ]);
}
```

accessing passed var in view
```html
<!-- title translated with user language passed by the controller -->
<h3>{{ t('app', 'About', null, userLanguage) }}</h3>
```

Installation
-------------
1. clone repository
2. run composer update

now it should work

Note: on a web server its maybe necessary to configure nginx or .htaccess file
