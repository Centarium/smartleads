<?php
namespace FileReader;

use Exception;
use SeekableIterator;
use OutOfBoundsException;
use Memcache;

/**
 * (like SplFileObject)
 * Class TextReader
 */
class TextReader implements SeekableIterator
{
    protected $position=0;

    protected $fileName;
    protected $fileHandler;
    protected $cacheKey='reader_string_';
    protected $cache;

    protected $cachedString = false;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;

        $this->rewind();

        $this->createCache();

        if(false === $this->fileHandler)
        {
            throw new Exception("Can`t find file $fileName");
        }
    }

    protected function createCache()
    {
        $memcache = new Memcache();
        $memcache->addServer('localhost', 11211);

        $this->cache = $memcache;

    }

    protected function getCached($position)
    {
        $offset = $this->cache->get( $this->cacheKey.$position );
        if($offset === false) return false;
        return fseek($this->fileHandler, $offset);

    }

    protected function setCache()
    {
        $offset = ftell($this->fileHandler);
        $this->cache->set($this->cacheKey.$this->position, $offset, false, 120);
    }


    /**
     * @param int $position
     */
    public function seek($position)
    {
        $this->cachedString = $this->getCached($position);
        if($this->cachedString !== false) return false;

        if($this->position < $position)
        {
            $this->rewind();
        }

        do{
            $this->next();
            if(!$this->valid())
            {
                throw new OutOfBoundsException("недействительная позиция ($position)");
            }

        } while ($this->position != $position);
    }

    public function current()
    {
        if( $this->cachedString ) return $this->cachedString;

        $this->setCache();
        $string = $this->currentString();
        return $string;
    }

    public function key()
    {
        return $this->position;
    }

    public function currentString()
    {
        return fgets($this->fileHandler, 4096);
    }

    public function next()
    {
        ++$this->position;
        $this->currentString();
    }

    public function rewind()
    {
        if( !empty($this->fileHandler) && is_resource($this->fileHandler) )
        {
            fclose($this->fileHandler);
        }

        $this->fileHandler = @fopen($this->fileName, 'r');
        $this->position = 0;
    }

    public function valid()
    {
        return !feof($this->fileHandler);
    }
}
