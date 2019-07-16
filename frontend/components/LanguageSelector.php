<?php

namespace frontend\components;

use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    /**
     * Load current language based on diven cookie if any
     * @param yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $supportedLanguages = $app->params['supportedLanguages'];
        $cookieLanguage = $app->request->cookies['language'];
        if (isset($cookieLanguage) && in_array($cookieLanguage, $supportedLanguages)) {
            $app->language = $app->request->cookies['language'];
        }
    }

}

