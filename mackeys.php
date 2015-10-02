<?php

require_once('workflows.php');

class MacKeys
{

    /**
     * @var string
     */
    protected $title = 'Mackeys';

    /**
     * @var string
     */
    protected $icon = 'icon.png';

    /**
     * @var Workflows
     */
    protected $workflows;

    /**
     * @var array
     */
    protected $keys = array();

    /**
     * @param Workflows $workflows
     */
    public function __construct(Workflows $workflows)
    {
        $this->workflows = $workflows;
        $this->keys      = $this->getKeys();
    }

    /**
     * Retrieves list of keys from json file
     *
     * @return array
     */
    protected function getKeys()
    {
        if ( ! $keys = json_decode(file_get_contents('keyslist.json'), true)) {
            echo $this->quit('Could not get the keys from "keyslist.json" :(');
            exit();
        }

        return $keys;
    }

    /**
     * Quits with message
     *
     * @param $message
     * @return string
     */
    protected function quit($message)
    {
        $this->workflows->result("999", "", $message, $this->title, $this->icon, 'no');

        return $this->workflows->toxml();
    }

    /**
     * @param string $query
     * @return string
     */
    public function process($query)
    {
        $result = str_replace(array_keys($this->keys), array_values($this->keys), $query);

        $this->workflows->result(md5($query), $result, $result, $this->title, $this->icon, 'yes');

        return $this->workflows->toxml();
    }
}

// dependency
$workflows = new Workflows();

// query arg
if ( ! isset($query)) {
    $query = $argv[1];
}

// get the result
$mackeys = new MacKeys($workflows);
echo $mackeys->process($query);

