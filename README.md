# TerraLootbags

### PocketMine-MP plugin 🗃️ that lets you create your own lootbags and custom rewards for them (Items, Commands)
Need help?
❓ [Discord](https://discord.gg/Mfu9CER8X2) 👾



:robot: **Commands:**

- `lootbag give <player> <lootbag> <count>`: Give a specific player a certain number of lootbags.
- `lootbag giveall <lootbag> <count>`: Distribute a specified number of lootbags to all online players.
- `lootbag view <lootbag>`: View the contents of a particular lootbag in a chest interface.


📇 🤯
Example Lootbag:
```yml
types:
  # type of the loot bag
  common:
    # Name of the loot bag
    name: "Common Lootbag"
    # 0: Mining
    # 1: Killing / Slaying
    obtainable: [0, 1]
    # Chance X in 1000 (100 = 10%) (10 = 1%) (1 = 0.1%)
    chance: 50
    # Reward Count
    reward-count: 1
    # The possible rewards of a loot bag.
    # "Item:count:ItemName"
    #    Set the ItemName to false if you want to leave it default
    #    You can use {player} in command for the player name.
    # "command:DisplayText:YourCommand"
    rewards:
      - "Salmon:1:Regular Fish:sharpness:5"
      - "command:Says Hello:say Hello {player}"
      - "diamond:1:false"
```
