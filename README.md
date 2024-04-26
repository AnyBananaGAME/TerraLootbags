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
  # Type of the loot bag
  common:
    # Name of the loot bag
    name: "Common Loot Bag"
    # Ways to obtain the loot bag
    obtainable: [0, 1] # 0: Mining, 1: Killing
    # Chance of obtaining the loot bag (1 in 1000)
    chance: 50
    # Number of rewards in each loot bag
    reward-count: 1
    # Possible rewards of a loot bag
    # Format: "Item:count:ItemName:enchant:level..."
    # Set ItemName to false for default
    # Use {player} for the player's name in commands
    # To add a command: "command:DisplayText:YourCommand"
    # To give a player an effect: "effect:EffectName:duration"
    rewards:
      - "Salmon:1:Regular Fish:sharpness:5:unbreaking:50"
      - "command:Says Hello:say Hello"
      - "diamond:1:false"
      - "effect:Haste:1000"
```
