<?php
/**
 * ShortURL model
 * php version 7.x
 * 
 * @category Models
 * @package  App
 * @author   Michail Strokin <mstrokin@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     https://github.com/mstrokin/code_samples
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;
use Hashids;

class ShortURL extends Model
{
    use Rememberable, SoftDeletes;
    protected $rememberFor = 3600;
    protected $table = "short_urls";
    protected $rememberCacheTag = 'review';
    protected $fillable = [
        'url',
        'user_id'
    ];
    /**
     * Get owner of ShortURL
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    /**
     * Save URL (if it does not exist already) and encode ID using Hashids library.
     *
     * @param string $url     URL to encode
     * @param int    $user_id User ID
     * 
     * @return string
     */
    public static function encodeURL(string $url,int $user_id=0) 
    {
        $shortURL = ShortURL::where(['url'=>$url])->first();
        if (!$shortURL) {
            $shortURL = ShortURL::create(['url'=>$url,'user_id'=>$user_id]);
            ShortURL::flushCache();
        }
        return Hashids::connection('shorturls')->encode($shortURL->id);
    }
}

