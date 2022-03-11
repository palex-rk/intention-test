<?php

namespace Src\Controller;

use Src\Model;

abstract class AbstractController
{
    protected $dbh;

    public function __construct(Model $model)
    {
        echo 'hi from absctrl';
        $this->dbh = $model;
    }

    abstract public function view($template, $params = []);

}