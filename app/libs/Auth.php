<?php

class Auth
{
    protected static $user;

    protected static function getCollection()
    {
        $db  = Service::get("db");
        return $db->getCollection(
            Service::get("user_collection")
        ); 
    }

    public static function logout()
    {
        if (self::check()) {
            unset($_SESSION['user_id']);
            self::$user = null;
        }
    }

    public static function attempt(Array $filter)
    {
        $col = self::getCollection();
        $p   = Service::get("password_field");
        if (empty($filter[$p])) {
            /** missing pasword */
            return false;
        }
        $pass = $filter[$p];
        unset($filter[$p]);
        $user = $col->findOne($filter);
        if (empty($user)) {
            return false;
        }
        if (password_verify($pass, $user->password)) {
            return self::login($user);
        }

        return false;
    }

    public static function login($user)
    {
        $class  = Service::get('user_class');
        $userid = Service::get("user_id");
        if (!$user instanceof $class) {
            throw new \Exception("");
        }
        Service::get("session");
        $_SESSION['user_id'] = $user->$userid;
        self::$user = $user;
        return true;
    }

    public static function check()
    {
        Service::get("session");
        if (empty($_SESSION['user_id'])) {
            return false;
        }

        if (empty(self::$user)) {
            self::$user = self::getCollection()->findOne(['_id' => $_SESSION['user_id']]);
        }

        return !empty(self::$user);
    }

    public static function user()
    {
        self::check();
        return self::$user;
    }
}
