<?php namespace JD\Cloudder\Facades;

use Illuminate\Support\Facades\Facade;

class Cloudder extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'cloudder'; }
}
