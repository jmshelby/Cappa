<?php

use Carbon\Carbon;

class UserController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('auth', array('only' => array(
			'getLogout',
			'getProfile',
		)));
		$this->beforeFilter('guest', array('only' => array(
			'getLogin',
			'postLogin',
		)));
	}

	public function getLogin()
	{
    	return View::make('user.login');
	}

	public function postLogin()
	{
       $userParams = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        );

        if (Auth::attempt($userParams,true)) {

            $user = Auth::user();
            $user->increment('login_count');

            $login_events = $user->login_events;
            if (!is_array($login_events))
                $login_events = array();

            $login_events[] = array(
                'datetime' => Carbon::now(),
                'location' => 'frontend',
            );
            $user->login_events = $login_events;
            $user->save();

            return Redirect::route('home')
                ->with('flash_notice', 'You are successfully logged in.');
        }
        
        // authentication failure! lets go back to the login page
        return Redirect::route('login')
            ->with('flash_error', 'Your username/password combination was incorrect.')
            ->withInput();
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('home')->with('flash_notice', 'You are successfully logged out.');
	}

	public function getProfile()
	{
		return View::make('user.profile');
	}

}
