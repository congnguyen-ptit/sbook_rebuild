<?php

function bannerImg()
{
    try {
        $context = stream_context_create(['http' => ['header' => 'Connection: close\r\n']]);
        $url = 'https://viblo.asia/';
        $content = file_get_contents($url, false, $context);
        $data = preg_match('/<div class="container px-0"><img src="(.*?)"/is', $content, $match);
        if (isset($match[1])) {
            return $match[1];
        } else {
            $match[1] = config('links.banner');

            return $match[1];
        }
    } catch (Exception $e) {
        return view('error');
    }
}

function setTimeShort($value)
{
    if (Session::get('website-language') == 'vi') {
        \Carbon\Carbon::setLocale('vi');
    }
    if ($value) {
        $result = '';
        $time = $value->diffForHumans(\Carbon\Carbon::now());
        $split = explode(' ', $time);
        switch ($split[1]) {
            case __('settings.time.full.seconds'):
                return $split[0] . ' ' . __('settings.time.short.seconds');
                break;
            case __('settings.time.full.minutes'):
                return $split[0] . ' ' . __('settings.time.short.minutes');
                break;
            case __('settings.time.full.hours'):
                return $split[0] . ' ' . __('settings.time.short.hours');
                break;
            case __('settings.time.full.days'):
                return $split[0] . ' ' . __('settings.time.short.days');
                break;
            default:
                return $split[0] . ' ' . __('settings.time.short.weeks');
                break;
        }
    } else {
        return __('settings.book.not_date');
    }
}

function setTimeDefault($value)
{
    if (Session::get('website-language') == 'vi') {
        \Carbon\Carbon::setLocale('vi');
    }
    if ($value) {
        return $value->diffForHumans(\Carbon\Carbon::now());
    } else {
        return __('settings.book.not_date');
    }
}

function getDay($value1, $value2)
{
    return date('d/m/y', strtotime($value1) + $value2 * 86400);
}

function translate($value)
{
    switch ($value) {
        case 'waiting':
            return __('settings.book.waiting');
            break;
        case 'reading':
            return __('settings.book.reading');
            break;
        case 'returning':
            return __('settings.book.returning');
            break;
        case 'returned':
            return __('settings.book.returned');
            break;
        case 'cancel':
            return __('settings.book.cancel');
            break;
        case config('view.notifications.waiting'):
            return __('settings.notifications.waiting');
            break;
        case config('view.notifications.returning'):
            return __('settings.notifications.returning');
            break;
        case config('view.notifications.returned'):
            return __('settings.notifications.returned');
            break;
        default:
            return __('settings.notifications.reading');
            break;
    }
}

function splitAddressOffice($value)
{
    $address = explode(' ', $value);
    $office = '';
    foreach ($address as $add) {
        $office .= $add[0];
    }

    return $office;
}

function avatarUser($user) {
    return $user->avatar
            ? asset(config('view.image_paths.user') . $user->avatar)
            : asset(config('view.image_paths.detaultAvatar'));
}

function mediaBook($typeBook){
    return asset(config('view.image_paths.book') . ($typeBook->medias->first()->path ?? 'default.jpg'));
}

function coverUser($user) {
    return $user->cover
            ? asset(config('view.image_paths.cover') . $user->cover)
            : asset(config('view.image_paths.defaultCover'));
}
