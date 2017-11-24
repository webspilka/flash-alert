<?php namespace Webspilka\FlashAlert\Facades;

use Webspilka\FlashAlert\FlashAlert;
// use Illuminate\Support\Facades\Facade;

class Facade extends \Illuminate\Support\Facades\Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return FlashAlert::class; }

}
