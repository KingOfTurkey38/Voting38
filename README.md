# Voting38
This is a threaded high performance voting plugin for PocketMine-MP API 5.0

You can configure messages and voting rewards in the config.
There's also an "autoclaim" option in the config.
If that is set to true the server will check every 30 seconds if the player has voted or not.

# For developers
When a player votes there's a `PlayerVoteEvent` called.
