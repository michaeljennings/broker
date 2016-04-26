<?php

if ( ! function_exists('broker')) {

    /**
     * A helper method to get the broker class, or retrieve an item from the
     * cache for the cacheable item.
     *
     * @param \Michaeljennings\Broker\Contracts\Cacheable|null $cacheable
     * @param null                                             $key
     * @return mixed
     */
    function broker(\Michaeljennings\Broker\Contracts\Cacheable $cacheable = null, $key = null) {
        if ( ! $cacheable) {
            return app('broker');
        }

        return app('broker')->get($cacheable, $key);
    }

}