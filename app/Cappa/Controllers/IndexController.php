<?php namespace Cappa\Controllers;

use \App;
use \View;
use \Input;
use \Redirect;
use \Exception;

use \CappaMan;
use Cappa\Entities\Player;
use Cappa\Entities\Player\Transaction;

class IndexController extends \Cappa\GenePool\Controller\Root {

	public $service;

	public function __construct()
	{
		$this->service = App::make('cappa.service.frontend');
		$this->beforeFilter('@filterRequest');
	}

	public function filterRequest($route, $request)
	{
		// Different from the regular auth filter, we'll
		//  call the frontend service, in case they need
		//  to check more things
		if (!$this->service->loggedInCheck())
			return Redirect::guest(route('user.login'));
	}

	protected function _getPlayer()
	{
		return $this->service->getPlayer();
	}

// == Simple View Actions ========================================

	public function getIndex()
	{
		// Player is requesting money info, make sure total pending pool is calculated
		$otherPlayers = $this->service->playerReceivesPendingDividends();

		$otherPlayers = $this->service->getAllOtherPlayers();
		$otherPlayers->orderBy('current_money', 'desc');

		$otherPlayers = $otherPlayers->get();

		return View::make('cappa.dashboard', array(
			'player'=>$this->_getPlayer(),
			'otherPlayers'=>$otherPlayers,
		));
	}

	public function getTransactionHistory()
	{
		$transactions_q = Transaction::with('player','receivingPlayer')
			->orderBy('created_at', 'desc')
		;
		$transactions_q->take(300);
		$transactions = $transactions_q->get();
		return View::make('cappa.transactions', array(
			'transactions'=>$transactions,
		));
	}


// == Redirect Actions ========================================

	public function getAddHeart()
	{
		if (!$this->service->canPlayerAccumulateHeart()) {
			return Redirect::route('cappa.dashboard')
				->with('flash_notice', "You're not allowed to get another heart (for some reason)");
		}
		$this->service->playerAccumulatesHeart();
		return Redirect::route('cappa.dashboard')
			->with('flash_notice', 'You have added a heart!');
	}

	public function postChangePoolShare()
	{
		$poolShare = Input::get('pool_share');
		//$this->service->canPlayerChangePoolShare()
		try {
			$this->service->playerChangesPoolShare($poolShare);
		} catch (Exception $e) {
			return Redirect::route('cappa.dashboard')
				->with('flash_notice', $e->getMessage());
		}
		return Redirect::route('cappa.dashboard')
			->with('flash_notice', "You have successfully changed your pool rate to {$poolShare}");
	}

	// TODO -- take out each case check, and replace with exception bubbling
	public function getGiveHeart($receivingPlayerId)
	{
		// First, check to make sure the user has enough hearts
		if (!$this->service->doesPlayerHaveHearts()) {
			return Redirect::route('cappa.dashboard')
				->with('flash_notice', "You don't have any hearts left to give");
		}

		// Second, ask if operation is possible in general
		if (!$this->service->canPlayerGiveHeartTo($receivingPlayerId)) {
			$receivingPlayer = CappaMan::player($receivingPlayerId);
			return Redirect::route('cappa.dashboard')
				->with('flash_notice', "Cannot give hearts to {$receivingPlayer->username}");
		}

		// Third, add a catch in case there is some new logic we don't know about
		try {
			$receivingPlayer = $this->service->playerGivesHeartTo($receivingPlayerId);
		} catch (Exception $e) {
			return Redirect::route('cappa.dashboard')
				->with('flash_notice', $e->getMessage());
		}
		return Redirect::route('cappa.dashboard')
			->with('flash_notice', "You have given a heart to {$receivingPlayer->username}");
	}


	public function getProcessQueue()
	{
		CappaMan::processDividendQueue();
return "Ran cappa process...";
	}

}
