<?php

namespace exodus\translation;

use exodus\translation\Language;

use InvalidArgumentException;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

class Translation
{
  const MINECRAFT_LANGUAGES = [
		// See  ->  https://github.com/Mojang/bedrock-samples/blob/main/resource_pack/texts/language_names.json
		"en_US", // English (United States)
		"en_GB", // English (United Kingdom)
		"de_DE", // Deutsch (Deutschland)
		"es_ES", // Español (España)
		"es_MX", // Español (México)
		"fr_FR", // Français (France)
		"fr_CA", // Français (Canada)
		"it_IT", // Italiano (Italia)
		"ja_JP", // 日本語 (日本)
		"ko_KR", // 한국어 (대한민국)
		"pt_BR", // Português (Brasil)
		"pt_PT", // Português (Portugal)
		"ru_RU", // Русский (Россия)
		"zh_CN", // 中文(简体)
		"zh_TW", // 中文(繁體)
		"nl_NL", // Nederlands (Nederland)
		"bg_BG", // Български (България)
		"cs_CZ", // Čeština (Česko)
		"da_DK", // Dansk (Danmark)
		"el_GR", // Ελληνικά (Ελλάδα)
		"fi_FI", // Suomi (Suomi)
		"hu_HU", // Magyar (Magyarország)
		"id_ID", // Indonesia (Indonesia)
		"nb_NO", // Norsk bokmål (Norge)
		"pl_PL", // Polski (Polska)
		"sk_SK", // Slovenčina (Slovensko)
		"sv_SE", // Svenska (Sverige)
		"tr_TR", // Türkçe (Türkiye)
		"uk_UA", // Українська (Україна)
	];
  
  /** @var PluginBase **/
  private $plugin;
  
  /** @var Language **/
  private $defaultLanguage;
  
  /** @var Array **/
  private array $languages = [];
  
  function __construct(PluginBase $plugin)
  {
    $this->plugin = $plugin;
  }
  
  function getPlugin(): PluginBase
  {
    return $this->plugin;
  }
  
  function isRegistered(string $name): bool
  {
    return isset($this->languages[$name]);
  }
  
  function setLanguage(Language $language, bool $overwrite = false): void
  {
    $identifier = $language->getIdentifer();
    if (!in_array($identifier, self::MINECRAFT_LANGUAGES, true)) {
      throw new InvalidArgumentException("Language $identifier is not available in the library");
    }
    if (!$overwrite) {
      throw new InvalidArgumentException("[" . $this->plugin->getName() . ": Translation] You cannot overwrite an already added language");
    }
    $this->languages[$identifier] = $language;
  }
  
  function getLanguage(string $name): ?Language
  {
    return $this->languages[$name] ?? null;
  }
  
  function setDefaultLanguage(Language $language): void
  {
    if (!in_array($language->getIdentifer(), self::MINECRAFT_LANGUAGES, true) || empty($language)) {
      throw new InvalidArgumentException("[" . $this->plugin->getName() . ": Translation] the default language does not exist in the game languages or is empty");
    }
    $this->defaultLanguage = $defaultLanguage;
  }
  
  function getDefaultLanguage(): string
  {
    return $this->defaultLanguage;
  }
  
  function send(null|CommandSender|Player $player, string $key = "", array $parameters = []): string
  {
    if (empty($player)) {
      throw new InvalidArgumentException("[" . $this->plugin->getName() . ": Translation] The user or console is null");
    }
    $language = ($player instanceof Player ? ($this->getLanguage($player->getLocale()) ?? null) : null) ?? $this->defaultLanguage;
    if (!in_array($language->getIdentifer(), self::MINECRAFT_LANGUAGES, true)) {
      throw new InvalidArgumentException("[" . $this->plugin->getName() . ": Translation] the language you have written does not exist in the language of the game");
    }
    $translation = $language?->getTranslation($key);
    if ($translation === null) {
      $dTranslation = $this->getDefaultLanguage();
      if ($dTranslation === null) {
        throw new InvalidArgumentException("[" . $this->plugin->getName() . ": Translation] Unknown language");
      } else {
        $translation = $dTranslation->getTranslation($key);
      }
    }
    return str_replace(array_keys($parameters), array_values($parameters), $translation);
  }
  
}