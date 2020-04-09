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
    return date('d/m/Y', strtotime($value1) + $value2 * 86400);
}

function getDateReturn($book) {
    $types = ['reading', 'returning'];
    return is_null($book->date_return)
        ? (getDay($book->updated_at, $book->days_to_read) . (in_array($book->type, $types) ? ' ( '. trans('settings.book.expected') .' ) ' : '' ))
        : date('d/m/y', strtotime($book->date_return));
}

function translate($value, $userReceive = false)
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
        case 'abtExpire':
            return __('settings.book.abtExpire');
            break;
        case 'abtExpiring':
            return __('settings.book.abtExpiring');
            break;
        case 'cancelExtend':
            return __('settings.book.cancelExtend');
            break;
        case 'hasExtended':
            return $userReceive ? __('settings.notifications.hasExtended') : __('settings.notifications.abtExpire');
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
    $value = empty($value) ? 'N/A' : $value;
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

function bookBorrowed ($ownerId, $bookId) :bool {
    $condition = [
        'owner_id' => $ownerId,
        'book_id' => $bookId,
    ];
    $types = [
        config('view.request.reading'),
        config('view.request.returning'),
    ];
    $result = \App\Eloquent\BookUser::countByCondition($condition, $types);

    return $result > 0 ? true : false;
}

function activeTab ($type) {
    $typeParam = $_GET['type'] ?? false;
    if (ucfirst($typeParam) == $type) {
        return 'active';
    }
    if (!$typeParam && $type == config('view.request.grid')) {
        return 'active';
    }
}

function currentIndex($pageIndex, $pageSize, $index){
    return ($pageIndex - 1) * $pageSize + ($index + 1);
}

function getDateTime($timestamp = false, $format = "Y/m/d H:i:s"){
    $date = $timestamp ? date($format, $timestamp) : date($format);
    return new DateTime($date);   
}

function getDaysExpired($date1, $date2, $days_to_read){
    $totalDaysBorrow = $date1->diff($date2)->days;
    $daysExpired = $totalDaysBorrow - $days_to_read;
    if($daysExpired > 0){
        return __('settings.book.expired') . ' ' . $daysExpired . ' ' . __('settings.request.day');
    }

    return false;
}

function whenExpired($book){
    $dateReturn = getDateTime(strtotime($book->date_return));
    $dateBorrow = getDateTime(strtotime($book->updated_at));
    return getDaysExpired($dateReturn, $dateBorrow, $book->days_to_read);
}

function whenBorrowingExpired($book){
    if(!$book->date_return){
        $currentDate = getDateTime();
        $dateBorrow = getDateTime(strtotime($book->updated_at));
        return getDaysExpired($currentDate, $dateBorrow, $book->days_to_read);
    }

    return false;
}

function selectedRequest($types, $type){
    if(!$types) return;
    return in_array($type, $types);
}
