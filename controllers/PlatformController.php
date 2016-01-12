<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;

class PlatformController extends Controller
{
    protected $_language;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        // initializing cookie
        $this->getLanguageFromCookie();
        parent::init();
    }

    /**
     * get the locale for the user accessing the website.
     *
     * @return String
     */
    public function getLocale()
    {
        if (!$this->_language) {
            switch ($this->getUserHttpHost()) {
                case 'domain.fr':
                    $this->_language = 'fr-FR';
                    break;

                case 'domain.pl':
                    $this->_language = 'pl-PL';
                    break;

                case 'domain.de':
                case 'second-domain.de':
                case 'third-domain.de':
                default:
                    $this->_language = 'de-DE';
                    break;
            }
        }

        return $this->_language;
    }

    /**
     * determine via which host name the user access the website.
     *
     * @return String
     */
    public function getUserHttpHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

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
}
