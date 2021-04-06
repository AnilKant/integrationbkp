<?php
namespace backend\components;

use Yii;
use yii\web\User as BaseUser;

class User extends BaseUser
{
    const BACKEND_REDIRECT_URL_KEY = 'backend_redirect_url';

    /**
     * Updates the authentication status using the information from session and cookie.
     *
     * This method will try to determine the user identity using the [[idParam]] session variable.
     *
     * If [[authTimeout]] is set, this method will refresh the timer.
     *
     * If the user identity cannot be determined by session, this method will try to [[loginByCookie()|login by cookie]]
     * if [[enableAutoLogin]] is true.
     */
    protected function renewAuthStatus()
    {
        $session = Yii::$app->getSession();
        $id = $session->getHasSessionId() || $session->getIsActive() ? $session->get($this->idParam) : null;

        if ($id === null) {
            $identity = null;
        } else {
            /* @var $class IdentityInterface */
            $class = $this->identityClass;
            $identity = $class::findIdentity($id);
        }

        $this->setIdentity($identity);

        if ($identity !== null && ($this->authTimeout !== null || $this->absoluteAuthTimeout !== null)) {
            $expire = $this->authTimeout !== null ? $session->get($this->authTimeoutParam) : null;
            $expireAbsolute = $this->absoluteAuthTimeout !== null ? $session->get($this->absoluteAuthTimeoutParam) : null;
            if ($expire !== null && $expire < time() || $expireAbsolute !== null && $expireAbsolute < time()) {
                $this->setRedirectUrl();
                $this->logout(false);
            } elseif ($this->authTimeout !== null) {
                $session->set($this->authTimeoutParam, time() + $this->authTimeout);
            }
        }

        if ($this->enableAutoLogin) {
            if ($this->getIsGuest()) {
                $this->loginByCookie();
            } elseif ($this->autoRenewCookie) {
                $this->renewIdentityCookie();
            }
        }
    }

    public function getRedirectUrl()
    {
        $url = Yii::$app->cache->get(self::BACKEND_REDIRECT_URL_KEY);
        if($url) {
            Yii::$app->cache->delete(self::BACKEND_REDIRECT_URL_KEY);
        }

        return $url;
    }

    private function setRedirectUrl()
    {
        // $route = Yii::$app->requestedRoute;
        // $query_params = Yii::$app->request->queryParams;

        $url = Yii::$app->request->url;
        Yii::$app->cache->set(self::BACKEND_REDIRECT_URL_KEY, $url, 60*60);
    }
}
