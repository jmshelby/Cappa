<?php namespace Cappa;

use Cappa\Entities\Player;
use Cappa\Entities\Player\HeartActivity as PlayerHeartActivity;
use Cappa\Entities\Player\PoolActivity as PlayerPoolActivity;
use Cappa\Entities\Player\Transaction as PlayerTransaction;
use Cappa\Entities\Player\Transaction\Dividend as PlayerTransactionDividend;

class CappaManager {


	const AQUIRE_FACTOR_BASE		= 1;
	const AQUIRE_FACTOR_PERCENTAGE	= .0005;

/*
	public function __construct(Dispatch $dispatch)
	{
		$this->_dispatch = $dispatch;
	}
*/

	// Convenience function for converting scalar values representing a
	//  player, to an actual player object
	public function player($sourceIdentifier, $graceful=false)
	{
		$player = null;

		if ( $sourceIdentifier instanceof Player )
			return $sourceIdentifier;

		if (!is_object($sourceIdentifier))
			$player = $this->getPlayerById($sourceIdentifier);

		if (!$player && !$graceful) {
			// TODO -- Make custom exception for this
			throw new Exception("Cannot fetch unknown player ( TODO - Make custom exception for this)");
		}

		return $player;
	}

	public function getPlayerById($id)
	{
		return Player::find($id);
	}

// =================================================================
// === Heart Activity

    public function doesPlayerHaveHearts($player)
    {
		$player = $this->player($player);
        return ($player->current_hearts > 0);
    }

    public function canPlayerAccumulateHeart($hearts = 1)
    {
        // Currently no other reason to deny heart accumlating
        return true; 
    }

	public function playerAccumulatesHeart($player, $hearts = 1)
	{
		$player = $this->player($player);

		// Add amount to player model
		$player->increment('current_hearts',$hearts);

		// Create heart activity record
		$heartActivity = PlayerHeartActivity::newFromAcquire($player,$hearts);
	
		return $heartActivity;
	}

// =================================================================
// === Pool Settings Activity

    public function isPlayerInPool($player)
    {
		$player = $this->player($player);
        return $player->isInPool();
    }

    public function canPlayerChangePoolShare($player, $sharePercentage)
    {
		$player = $this->player($player);
		if (!$player->isPoolShareValueValid($sharePercentage))
			return false;
        // Currently no other reason to deny pool share amounts
        return true; 
    }

	public function playerChangesPoolShare($player, $sharePercentage)
	{
		$player = $this->player($player);

		// Get old amount
		$oldAmount = $player->getPoolShare();

		if ((float)$oldAmount === (float)$sharePercentage) {
			// Nothing Changed, return
			return;
		}

		// Set new amount
		$player->setPoolShare($sharePercentage);
		$player->save();

		// Create pool activity record
		$poolActivity = PlayerPoolActivity::newFromChange($player, $oldAmount, $sharePercentage);

		return $poolActivity;
	}

// =================================================================
// === Transactions

    public function canPlayerGiveHeartTo($player, $receivingPlayer)
    {
		$player = $this->player($player);
		$receivingPlayer = $this->player($receivingPlayer);
        if (!$this->doesPlayerHaveHearts($player))
			return false;
		return true;
    }

	public function playerGivesHeartTo($player, $receivingPlayer)
	{
		$heartsGiven = 1;
		$player = $this->player($player);
		$receivingPlayer = $this->player($receivingPlayer);

		// TODO - add custom exception class for this case
		if (!$this->doesPlayerHaveHearts($player))
			throw new \Exception('No hearts left to give');

		// Make sure giving player has been paid out for all pending pool transactions
		$this->playerReceivesPendingDividends($player);

		// Calculate the amount of new/generated money
		$newMoney = $this->_calculateNewMoneyFromGiver($player);

		// Calculate the amount of money that goes to the pool
		$poolAmount = $this->_calculateNewDonationToPool($receivingPlayer,$newMoney);

		// Calculate the amount of new money that goes to the receiver
		$receivingMoney = $newMoney - $poolAmount;

		// Get the sum all player's share percentages
		$poolDivisor = Player::sum('share_factor');

		// Get the count of all players
		$playerCount = Player::count();

		// Create New Transaction
		$trans = PlayerTransaction::newFromGiving(
			$player,
			$receivingPlayer,
			$heartsGiven,
			$newMoney,
			$receivingMoney,
			$poolDivisor,
			$playerCount
		);

		// Update Player Numbers
		$player->decrement('current_hearts',$heartsGiven);
		$receivingPlayer->increment('current_money', $receivingMoney);

		return $receivingPlayer;
	}

	protected function _calculateNewMoneyFromGiver($givingPlayer)
	{
		$givingPlayerMoney = ($givingPlayer->current_money) ? $givingPlayer->current_money : 0 ;
		$givingPlayerMoney = $givingPlayerMoney * 1.0;
		return ( self::AQUIRE_FACTOR_PERCENTAGE * $givingPlayerMoney ) + self::AQUIRE_FACTOR_BASE ;
	}

// =================================================================
// === Dividends

	protected function _calculateNewDonationToPool($receivingPlayer, $money)
	{
		$shareFactor = 0;
		if (is_numeric($receivingPlayer->getPoolShare()))
			$shareFactor = $receivingPlayer->getPoolShare();
		$donationMoney = $shareFactor * $money;
		return $donationMoney;
	}

	protected function _calculateScaledPercentageToPlayer($transaction, $player)
	{
		$poolDivisor = $transaction->pool_divisor;
		if ($poolDivisor == 0)
			return 0;

		// Get player pool share, from the time the transaction happend
		$poolShare = $player->getPoolShare($transaction->created_at);

		// Calculate
		$percentage =  $poolShare / $poolDivisor;
		return $percentage;
	}

	protected function _calculateDividendToPlayer($transaction, $player)
	{
		// [Player's Dividend] =
		//		( [Player's Share Percentage] / [Sum of all Players Share Percentage] ) *
		//			[Total Pool Donation] 
		$totalPoolDonation = $transaction->money_sent_to_pool;
		$playerDividend = $this->_calculateScaledPercentageToPlayer($transaction, $player) *
			$totalPoolDonation;
		return $playerDividend;
	}

	// To be called when a player needs to know their final amount (not cron)
	public function playerReceivesPendingDividends($player)
	{
		$player = $this->player($player);

		$playerPaidOut = false;

		// Check if player has ever been a part of the pool, if not, return
		if (!$player->getPoolShareStartDate())
			return;

		// Get all transactions that have not been fully paid out, since player was a part of the share
		$unfinishedTransactionIds = PlayerTransaction::where('dividends_paid_out_fl',false)
			->where('created_at', '>=', $player->getPoolShareStartDate())
			->lists('_id'); // TODO -- fix this bug in the mongodb package (pull request)

		// Get unique list of all above transaction ids from transaction dividends that player has been paid
		$myFinishedTransactionIds = $player->dividends()
			->whereIn('transaction_id', $unfinishedTransactionIds)
			->distinct('transaction_id')
			->get();
		$temps = $myFinishedTransactionIds;
		$myFinishedTransactionIds = array();
		foreach($temps as $temp) {
			$myFinishedTransactionIds[] = $temp[0];
		}

		// Get all transactions that have not been fully paid out, since player was a part of the share, and ones that the player hasn't been paid out for
		$myUnfinishedTransactions = PlayerTransaction::where('dividends_paid_out_fl',false)
			->where('created_at', '>=', $player->getPoolShareStartDate())
			->whereNotIn('_id', $myFinishedTransactionIds) // TODO -- fix this bug in the mongodb package (pull request)
			->get();

		// Loop through transactions
		foreach($myUnfinishedTransactions as $trans) {
			// figure out dividend amount
			$dividendAmount = $this->_calculateDividendToPlayer($trans, $player);
			$scaledPercent = $this->_calculateScaledPercentageToPlayer($trans, $player);
			// call create transaction dividend
			$div = PlayerTransactionDividend::createFromPayoutToPlayer(
				$dividendAmount,
				$trans,
				$player,
				$scaledPercent
			);
			// increment players money
			$player->increment('current_money', $dividendAmount);
			$playerPaidOut = true;
		}
		return $playerPaidOut;
	}


	// Process Dividend Queue Payouts
	public function processDividendQueue()
	{
		// Get all transactions that have not been marked as finished
		$unfinishedTransactions = PlayerTransaction::where('dividends_paid_out_fl',false)->get();

		// Loop Through
		foreach($unfinishedTransactions as $trans) {

			// If no money was directed to the pool, mark as paid out
			if ($trans->money_sent_to_pool <= 0) {
				$trans->dividends_paid_out_fl = true;
				$trans->save();
				continue;
			}

			// If count of all dividends is equal to finishing amount, mark as paid out
			if ($trans->dividends_count == $trans->dividends()->count()) {
				$trans->dividends_paid_out_fl = true;
				$trans->save();
				continue;
			}

			// Fetch all players, created before transaction, not yet paid out
			$paidOutPlayerIds = $trans->dividends()->lists('receiving_player_id');
			$paidOutPlayers = Player::whereNotIn('_id', $paidOutPlayerIds)
				->where('created_at', '<', $trans->created_at)
				->get();
			foreach($paidOutPlayers as $player) {
				// figure out dividend amount
				$dividendAmount = $this->_calculateDividendToPlayer($trans, $player);
				$scaledPercent = $this->_calculateScaledPercentageToPlayer($trans, $player);
				// call create transaction dividend
				$div = PlayerTransactionDividend::createFromPayoutToPlayer(
					$dividendAmount,
					$trans,
					$player,
					$scaledPercent
				);
				// increment players money
				$player->increment('current_money', $dividendAmount);
			}

			// Mark as paid out
			$trans->dividends_paid_out_fl = true;
			$trans->save();

		}
		
	}

}
