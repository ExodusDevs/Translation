<?php

namespace exodus\translation;

use exodus\translation\TranslationException;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

class Translation
{
  public const DEFAULT_LANGUAGE = "en_US";
  
  public const MINECRAFT_LANGUAGES = [
  		"en_US",
  		"en_GB",
  		"de_DE",
  		"es_ES",
  		"es_MX",
  		"fr_FR",
  		"fr_CA",
  		"it_IT",
  		"ja_JP",
  		"ko_KR",
  		"pt_BR",
  		"pt_PT",
  		"ru_RU",
  		"zh_CN",
  		"zh_TW",
  		"nl_NL",
  		"bg_BG",
  		"cs_CZ",
  		"da_DK",
  		"el_GR",
  		"fi_FI",
  		"hu_HU",
  		"id_ID",
  		"nb_NO",
  		"pl_PL",
  		"sk_SK",
  		"sv_SE",
  		"tr_TR",
  		"uk_UA",
  	];
  
  /** @var PluginBase **/
  private $plugin;
  
  /** @var String **/
  private $defaultLanguage;
  
  public function __construct(PluginBase $plugin)
  {
    $this->plugin = $plugin;
  }
  
  public function getPlugin(): PluginBase
  {
    return $this->plugin;
  }
  
  public function setDefaultLanguage(string $defaultLanguage): void
  {
    if (!in_array($language, self::MINECRAFT_LANGUAGES, true) || empty($defaultLanguage)) {
      Server::getInstance()->getLogger()->error("[" . $this->plugin->getName() . ": TranslationAPI] the default language does not exist in the game languages or is empty");
      return;
    }
    $this->defaultLanguage = $defaultLanguage;
  }
  
  public function getDefaultLanguage(): string
  {
    return $this->defaultLanguage;
  }
  
  public function send(CommandSender|Player $player, string $key, array $parameters = []): string
  {
    /*if (empty($key)) {
      Server::getInstance()->getLogger()->error("[" . $this->plugin->getName() . ": TranslationAPI] the message cannot be empty");
      return "";
    }*/
    $language = ($player instanceof Player ? (self::MINECRAFT_LANGUAGES[$player->getLocale()] ?? null) : null) ?? $this->defaultLanguage;
    if (!in_array($language, self::MINECRAFT_LANGUAGES, true)) {
      Server::getInstance()->getLogger()->error("[" . $this->plugin->getName() . ": TranslationAPI] the language you have written does not exist in the language of the game");
      return "";
    }
    if (!is_file($this->plugin->getDataFolder() . "languages" . DIRECTORY_SEPARATOR . $language . ".ini")) {
      throw new TranslationException("[" . $this->plugin->getName() . ": TranslationAPI] sorry, there is no file with that language, this message is for you to add the file of this language");
    }
    $file = parse_ini_file($this->plugin->getDataFolder() . "languages" . DIRECTORY_SEPARATOR . $language . ".ini");
    $translation = $file[$key];
    if ($translation === null) {
      $dTranslation = parse_ini_file($this->plugin->getDataFolder() . "languages" . DIRECTORY_SEPARATOR . $this->defaultLanguage . ".ini")[$key];
      if ($dTranslation === null) {
        Server::getInstance()->getLogger()->error("[" . $this->plugin->getName() . ": TranslationAPI] Unknown translation key: " . $key);
        return "";
      } else {
        $translation = $dTranslation;
      }
    }
    return str_replace(array_keys($parameters), array_values($parameters), $translation);
  }
  
}