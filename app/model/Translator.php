<?php

use Nette\Database\Connection;
use Nette\Caching\Cache;

class Translator extends Nette\Object implements Nette\Localization\ITranslator
{

    /**
     * @var Connection 
     */
    private $connection;

    /**
     * @var Cache
     */
    private $cache;
    private $lang = 'en';

    public function __construct(Connection $connection, Cache $cache = null)
    {
        $this->connection = $connection;
        $this->cache = $cache;
    }
    
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function translate($message, $count = NULL)
    {
        if (isset($this->cache)) {
            $langSpace = $this->cache->derive('en');
            $res = $langSpace->load($message);
            if ($res !== null) {
                return $res;
            }
        }

        $fromDb = $this->connection->table('translation')->where(array('origin' => $message, 'lang' => $this->lang))->fetch();

        if ($fromDb === false) {
            throw new RuntimeException('Translation for "' . $message . '" not found for language ' . $this->lang);
        }

        if (isset($this->cache)) {
            $langSpace->save($message, $fromDb->translation);
        }

        return $fromDb->translation;
    }

}
