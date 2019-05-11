<?php
/**
 * URLShortenerController
 * php version 7.x
 * 
 * @category Controllers
 * @package  App
 * @author   Michail Strokin <mstrokin@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     https://github.com/mstrokin/code_samples
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Hashids;
use Validator;

use App\ShortURL;

class URLShortenerController extends Controller
{
    /**
     * Redirect to expanded URL if it exists or redirect to google otherwise
     *
     * @param \Illuminate\Http\Request $request Request object
     * @param string                   $url     URL to decode
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(Request $request,$url="")
    {
        $shortURL = null;
        if ($url) {
            $id = Hashids::connection('shorturls')->decode($url);
            if (count($id) > 0) {
                $shortURL = ShortURL::find($id[0]);
                if ($shortURL) {
                    return redirect($shortURL->url);
                }
            }
        }
        return redirect('https://www.google.com/');;
    }
    
    /**
     * Generate short URL
     *
     * @param \Illuminate\Http\Request $request Request object
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate(Request $request)
    {
        $encoded = $this->_encode($request);
        if (is_string($encoded)) {
            return redirect()->route('urlshortener.index')
                ->with('url', $encoded);
        } else {
            return redirect()->route('urlshortener.index')
                ->withErrors($encoded);
        }
    }
    
    /**
     * Run validations and encode the URL using ShortURL model
     *
     * @param \Illuminate\Http\Request $request Request object
     * 
     * @return string
     */
    private function _encode(Request $request)
    {
        $this->validate(['url'=>'required|string|url']);
        return ShortURL::encodeURL($request->input('url'));
    }
}
