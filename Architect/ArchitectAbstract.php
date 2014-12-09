<?php

namespace Architect;

abstract class ArchitectAbstract
{
    protected $params = array();

    protected static $_app;

    public function setParams($params)
    {
        // @TODO: Add clean-up here
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }
}