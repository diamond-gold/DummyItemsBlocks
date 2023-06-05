![DummyItemsBlocks](https://socialify.git.ci/diamond-gold/DummyItemsBlocks/image?description=1&font=Inter&forks=1&issues=1&name=1&owner=1&pattern=Circuit%20Board&pulls=1&stargazers=1&theme=Light)

## ⚠️ Experimental - backup your worlds and player data! ⚠️

> Items and blocks added are not functional, they are just for decoration purposes.
> 
> * Except for lingering potions which are functional as normal splash potions.
> 
> Allows worlds with such items or blocks to be loaded

[![](https://poggit.pmmp.io/ci.shield/diamond-gold/DummyItemsBlocks/DummyItemsBlocks?style=for-the-badge)](https://poggit.pmmp.io/ci/diamond-gold/DummyItemsBlocks/~)

[![](https://poggit.pmmp.io/shield.api/DummyItemsBlocks?style=for-the-badge)](https://poggit.pmmp.io/p/DummyItemsBlocks)
[![](https://poggit.pmmp.io/shield.downloads/DummyItemsBlocks?style=for-the-badge)](https://poggit.pmmp.io/p/DummyItemsBlocks)
[![](https://poggit.pmmp.io/shield.downloads.total/DummyItemsBlocks?style=for-the-badge)](https://poggit.pmmp.io/p/DummyItemsBlocks)
[![](https://poggit.pmmp.io/shield.state/DummyItemsBlocks?style=for-the-badge)](https://poggit.pmmp.io/p/DummyItemsBlocks)

Encountered a bug? Want something added? Create an [issue](https://github.com/diamond-gold/DummyItemsBlocks/issues) (＃°Д°)

Have questions? Start a [discussion](https://github.com/diamond-gold/DummyItemsBlocks/discussions) ✍(◔◡◔)

Starring the GitHub repository is like sprinkling stardust—help me create magic! (。・∀・)ノ

Feeling generous? Buy me some [snacks](https://ko-fi.com/diamondgold)! (❤´艸｀❤)

[![Feature Requests](https://img.shields.io/github/issues-raw/diamond-gold/DummyItemsBlocks/Feature%20Request?label=Feature%20Requests&logo=github&style=for-the-badge)](https://github.com/diamond-gold/DummyItemsBlocks/issues)
[![Bug Reports](https://img.shields.io/github/issues-raw/diamond-gold/DummyItemsBlocks/bug?label=Bug%20Reports&logo=github&style=for-the-badge)](https://github.com/diamond-gold/DummyItemsBlocks/issues)

# Known issue when another plugin that add vanilla items is present
`
[Server thread/CRITICAL]: InvalidArgumentException: "Deserializer is already assigned for "minecraft:shield"" (EXCEPTION) in "pmsrc/src/data/bedrock/item/ItemDeserializer" at line 55
`

> If you encounter similar error in another plugin, please delete `minecraft:shield` in `config.yml`
> 
> Replace `minecraft:shield` with what you see in the error message