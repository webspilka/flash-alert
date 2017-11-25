<?php namespace Webspilka\FlashAlert;

// use Webspilka\FlashAlert\FlashAlert;
use Illuminate\Support\Facades\Facade;

class FlashAlertFacade extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { 
        return 'flashalert';
        //return FlashAlert::class; 
        
    }

}
