<?php
/**
 * Created by Chris on 9/29/2014 3:56 PM.
 */

class Token {
    public static function generate() {
        return Session::put(Config::get('sessions/token_name'), md5(uniqid()));
        // generate token every 10 minutes 
        //return Session::put(Config::get('sessions/token_name'), md5(uniqid()), 600);
    }
// 
    public static function check($token) {
        $tokenName = Config::get('sessions/token_name');
        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}