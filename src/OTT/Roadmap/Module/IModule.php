<?php

namespace OTT\Roadmap\Module;

/**
 * Interface IModule
 * @package OTT\Roadmap\Module
 */
interface IModule
{
    /**
     * Initialize the selected module
     * @return mixed
     */
    public function init();
}
