<?php
declare(strict_types=1);

namespace exodus\translation;

use InvalidArgumentException;

final class Language
{
  /** @var string $identifier **/
  private string $identifier;
  
  /** @var string $name **/
  private string $name;
  
  /** @var array $translations **/
  private array $translations = [];
  
  public function __construct(string $identifier, string $name = "", ?array $translations = null)
  {
    if (empty($identifier)) {
      throw new TranslationException("$name variable is \"null\" or empty string");
    }
    if (empty($name)) {
      throw new TranslationException("The variable $name is an empty");
    }
    $this->identifier = $identifier;
    $this->name = $name;
    $this->translations = $translations ?? [];
  }
  
  function getIdentifier(): string
  {
    return $this->identifier;
  }
  
  function getName(): string
  {
    return $this->name;
  }
  
  /** 
   * Returns a language-specific translation
   */
  function getTranslation(string $key): ?string
  {
    return $this->translations[$key] ?? null;
  }
  
  /**
   * Returns all language translations
   */
  function getTranslations(): array
  {
    return $this->translations;
  }
  
}
