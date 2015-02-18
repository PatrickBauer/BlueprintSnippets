<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 30.01.15
 * Time: 18:09
 */

namespace App\Services;


use App\Node;
use PhpSpec\Exception\Exception;

class BluePrintParser
{
    /**
     * All lines of the given blueprint file
     * @var array
     */
    private $lines = NULL;

    /**
     * Holds all node objects
     * @var array
     */
    private $objects = array();


    /**
     * Parses a single blueprint file
     */
    public function parse()
    {
        //sanity check for empty line array
        if(empty($this->lines)) {
            throw new Exception('No lines set.');
        }

        //run as long as there a more lines to process
        while($line = current($this->lines)) {
            $matches = array();

            //only react to lines which start new nodes
            //any other lines will be ignored
            if(preg_match('/Begin Object ?(.*)/', $line, $matches)) {
                $this->objects[] = $this->createNode($matches[1]);
            }

            //break if there are no more lines
            if(next($this->lines) === false)
                break;
        }
    }

    public function createNode($attributes) {
        $matches = array();
        $node = new Node();

        if(!empty($attributes) && preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $attributes, $matches)) {
            $node->setAttributes($matches[0]);
        }

        next($this->lines);
        while($line = current($this->lines)) {
            if (preg_match('/Begin Object ?(.*)/', $line, $matches)) {
                $node->addSubNode($this->createNode($matches[1]));
            } else if (preg_match('/End Object/', $line)) {
                break;
            } else {
                $node->setAttribute($line);
            }

            if(next($this->lines) === false) {
                break;
            }
        }

        return $node;
    }



    /**
     * Sets the lines that should be parsed
     * Trims all lines before storing them
     *
     * @param $lines
     * @throws Exception
     */
    public function setLines($lines)
    {
        if (!is_array($lines)) {
            throw new Exception('Lines must consist of an array of single lines.');
        }

        if (!count($lines)) {
            throw new Exception('Lines array is empty.');
        }

        $this->lines = array_map('trim', $lines);
    }

    /**
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }
}