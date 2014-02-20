Cappa
=====

###Capitalism Simulator - built from laravel

- Players perform an action which results in them aquiring Hearts
  - The action can be as simple as clicking a button, or as complicated as playing a mini game
- Players can spend Hearts on another player, which results in the recieving player aquiring Money
  - The amount of Money aquired by the reciever is equal to [X percentage of the giver's amount of Money] + .01 (base factor)
- Players can opt into a sharing pool, by selecting a percentage of the wealth that they would like to share
  - When a player aquires Money (as a result of someone spending Hearts on them), a percentage of that income is directed to the pool
  - When Money is directed to the pool, everyone who is opted into the sharing pool (except the donor) receives a dividend based on their sharing percentage
    - [Player's Dividend] = ( [Player's Share Percentage] / [Sum of all Players Share Percentage] ) * [Total Pool Donation] 
- Players have access to several reports, feeds
  - List of all players
    - Includes Main Fields
    - Includes stats
      - Current Hearts
      - Current Money Amount
      - Total Hearts Acquired
      - Total Money Acquired
      - (other useful prospectus information)
  - List of all transactions, maybe feed of them happening
    - When a heart is spent, who received money, how much went to the pool, who received money from the pool and how much

###Model Schema

- Player
  - Current Hearts
  - Current Money
  - User Name
  - Global Dividend Rate
  - User Id (external)
  - Created Date


- Player Heart Activity
  - * When Hearts are aquired, not spent
  - Player ID
  - Hearts Aquired
  - Activity Type (or id of activity; future use when we have mini game activitys)
  - Created Date

- Player Pool Activity
  - * Track changes to pool numbers
  - Player ID
  - rate number before
  - rate number after
  - ? Pool ID (when there are multiple pools)
  - Created Date


- Player Transaction
  - * When Hearts are spent on another player
  - Giving Player ID
  - Receiving Player ID
  - Hearts Spent
  - Dollars Generated (total, before dividend/pool donation)
  - Dollars Received (after pool donation)
  - ? Receiving Player, Hearts Before
  - ? Giving Player, Money Before
  - Receiving Player, Pool Rate (at the time of transaction)
  - Giving Player's Total Hearts Resulting
  - Receiving Player's Total Dollars Resulting
  - Money sent to pool 
  - Created Date

- Player Transaction Dividend
  - * When a transaction results in money directed to a pool, and players are payed out
  - Transaction ID
  - Receiving Player
  - Money Received
  - Player Rate (Receiving Player's Pool rate at the time of Payout)
  - Dividend Rate (Calculated percentage of pool, dividend to single person represents)
  - ? Pool ID (when there are multiple pools)
  - Created Date

