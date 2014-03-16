Cappa
=====

###Capitalism Simulator - built from laravel

- Upon user registration, an immortal 'soul' is created
- When the user is ready to play, a 'player' is born/created with their 'soul' possessing that new 'player'
- New 'player's are given X number of 'heart's to start with
- New 'player's are given X amount of 'money' to start with, generated as new 'money' (not from the pool of current 'money'), from the '[SOME COOL NAME FOR WHERE MAGIC MONEY IS CREATED]'
- To live in the world, 'player's are docked one 'heart' every X seconds
- When a 'player' has 0 'heart's the 'player' dies, and the user's 'soul' is sent to 'limbo'
	- Their 'money' is either sent to the other players? or automatically spent to buy more 'heart's to live
- If a user's 'soul' is in 'limbo' they can choose to be reincarnated, as a born again 'player' (same rules as any newborn 'player')
- A 'player' can perform an action (implying manual labor) which results in them aquiring X number of 'heart's
  - The action can be as simple as clicking a button, or as complicated as playing a mini game
- A 'player' can spend 'money' to aquire 'heart's
  - The price is based on a dynamic exchange rate calculated by the total number of all 'player's 'heart's to the sum of all 'player's 'money'
- A 'player' can sell 'heart's to aquire 'money'
  - The amount received is based on a dynamic exchange rate calculated by the total number of all 'player's 'heart's to the sum of all 'player's 'money'
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
    - When a heart is bought, and for how much, and by whom
    - When a heart is sold, and for how much, and by whom
- Webservice API
  - Public API for public/global stats, reporting
  - Private API for other user clients, phones, apps

###Model Schema

