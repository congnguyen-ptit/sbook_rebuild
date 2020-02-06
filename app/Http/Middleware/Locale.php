<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Contracts\UsermetaRepository;
use Auth;
use Cache;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $usermeta;

    public function __construct(UsermetaRepository $usermeta)
    {
        $this->usermeta = $usermeta;
    }

    public function handle($request, Closure $next)
    {
        $data = [
            'user_id' => Auth::id(),
            'key' => 'website-language',
        ];
        if (Auth::check()) {
            $setting = $this->usermeta->find($data);
            if (!isset($setting)) {
                if (\Session::get('website-language') == 'vi') {
                    $language = \Session::get('website-language', 'vi');
                    config(['app.locale' => $language]);
                } else {
                    $language = \Session::get('website-language', 'en');
                    config(['app.locale' => $language]);
                }
                $this->usermeta->settingLanguage(Auth::id(), $language);
            } else {
                $language = $setting->value;
                \Session::put('website-language', $language);
                config(['app.locale' => $language]);
            }
            $cache = Cache::put('language', $language, config('view.limit.minutes'));
        } else {
            $cache = Cache::get('language');
            if (isset($cache)) {
                \Session::put('website-language', $cache);
                config(['app.locale' => $cache]);
            } else {
                $language = \Session::get('website-language', config('app.locale'));
                config(['app.locale' => $language]);
            }
            Cache::forget('language');
        }

        return $next($request);
    }
}
