<?php
/**
 * Laravel routes for url shortener code sample
 * php version 7.x
 * 
 * @category Routes
 * @package  App
 * @author   Michail Strokin <mstrokin@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     https://github.com/mstrokin/code_samples
 */
Route::name('shorturl')->get('/{url?}', 'URLShortenerController@redirect');
