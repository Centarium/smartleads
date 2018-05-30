<?php
namespace FileReader;

use Exception;
use SeekableIterator;
use OutOfBoundsException;

/**
 * (like SplFileObject)
 * Class TextReader
 */
class TextReader implements SeekableIterator
{
    private $position=0;

    private $fileName;
    private $fileHandler;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->rewind();

        if(false === $this->fileHandler)
        {
            throw new Exception("Can`t find file $fileName");
        }
    }

    /**
     * @param int $position
     */
    public function seek($position)
    {
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
        return fgets($this->fileHandler, 4096);
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
        $this->current();
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
