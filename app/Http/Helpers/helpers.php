<?php
use App\Constants\ManageStatus;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\FileManager;
use App\Lib\GoogleAuthenticator;
use App\Lib\StorageManager;
use App\Models\Advertisement;
use App\Models\ArchiveManager;
use App\Models\CommissionLog;
use App\Models\Deposit;
use App\Models\FileType;
use App\Models\Plugin;
use App\Models\Setting;
use App\Models\SiteData;
use App\Models\ReferralSetting;
use App\Models\Transaction;
use App\Notify\Notify;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

function systemDetails()
{
    $system['name']          = 'TonaPik';
    $system['version']       = '1.0';
    $system['build_version'] = '0.0.1';
    return $system;
}

function verificationCode($length) {
    if ($length <= 0) return 0;
    $min = pow(10, $length - 1);
    $max = (int) ($min - 1).'9';
    return random_int($min,$max);
}

function navigationActive($routeName, $type = null, $param = null) {
    if ($type == 1) $class = 'active';
    else $class = 'active show';

    if (is_array($routeName)) {
        foreach ($routeName as $key => $name) {
            if (request()->routeIs($name)) return $class;
        }
    } elseif (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(request()->route()->parameters ?? []);
            if (isset($routeParam[0]) && strtolower($routeParam[0]) == strtolower($param)) return $class;
            else return;
        }
        return $class;
    }
}

function bs($fieldName = null) {
    $setting = cache()->get('setting');

    if (!$setting) {
        $setting = Setting::first();
        cache()->put('setting', $setting);
    }

    if ($fieldName) { return $setting->$fieldName; }

    return $setting;
}

function fileUploader($file, $location, $size = null, $old = null, $thumb = null) {
    $fileManager = new FileManager($file);
    $fileManager->path = $location;
    $fileManager->size = $size;
    $fileManager->old = $old;
    $fileManager->thumb = $thumb;
    $fileManager->upload();
    return $fileManager->filename;
}

function fileManager() {
    return new FileManager();
}

function getFilePath($key) {
    return fileManager()->$key()->path;
}

function getFileSize($key) {
    $fileInfo = fileManager()->$key();
    return isset($fileInfo->size) ? $fileInfo->size : null;
}

function getImage($image, $size = null, $avatar = false) {
    $clean = '';

    if (file_exists($image) && is_file($image)) return asset($image) . $clean;

    if ($avatar) {
        return asset('assets/universal/images/avatar.png');
    }

    if ($size) return route('placeholder.image', $size);

    return asset('assets/universal/images/default.png');
}

function isImage($string) {
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension = pathinfo($string, PATHINFO_EXTENSION);
    if (in_array($fileExtension, $allowedExtensions)) {
        return true;
    } else {
        return false;
    }
}

function isHtml($string) {
    if (preg_match('/<.*?>/', $string)) {
        return true;
    } else {
        return false;
    }
}

function getPaginate($paginate = 0) {
    return $paginate ? $paginate :  bs('per_page_item');
}

function paginateLinks($data) {
    return $data->appends(request()->all())->links();
}

function keyToTitle($text) {
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function titleToKey($text) {
    return strtolower(str_replace(' ', '_', $text));
}

function activeTheme($asset = false) {
    $theme = bs('active_theme');
    if ($asset) return 'assets/themes/' . $theme . '/';
    return 'themes.' . $theme . '.';
}


function getPageSections($arr = false) {
    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTheme()) . 'site.json';
    $sections = json_decode(file_get_contents($jsonUrl));

    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }

    return $sections;
}

function getAmount($amount, $length = 2) {
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function removeElement($array, $value) {
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function notify($user, $templateName, $shortCodes = null, $sendVia = null) {
    $setting          = bs();
    $globalShortCodes = [
        'site_name'       => $setting->site_name,
        'site_currency'   => $setting->site_cur,
        'currency_symbol' => $setting->cur_sym,
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    $shortCodes          = array_merge($shortCodes ?? [], $globalShortCodes);
    $toast               = new Notify($sendVia);
    $toast->templateName = $templateName;
    $toast->shortCodes   = $shortCodes;
    $toast->user         = $user;
    $toast->userColumn   = isset($user->id) ? $user->getForeignKey() : 'user_id';
    $toast->send();
}

function showDateTime($date, $format = null) {
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return $format ? Carbon::parse($date)->translatedFormat($format) : Carbon::parse($date)->translatedFormat(bs('date_format') . ' h:i A');
}

function getIpInfo() {
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}

function osBrowser() {
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}

function getRealIP() {
    $ip = $_SERVER["REMOTE_ADDR"];

    //Deep detect ip
    if (isset($_SERVER['HTTP_FORWARDED']) && filter_var($_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (isset($_SERVER['HTTP_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (isset($_SERVER['HTTP_X_REAL_IP']) && filter_var($_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}

function loadReCaptcha() {
    return Captcha::reCaptcha();
}

function verifyCaptcha() {
    return Captcha::verify();
}

function loadExtension($key) {
    $plugin = Plugin::where('act', $key)->active()->first();
    return $plugin ? $plugin->generateScript() : '';
}

function urlPath($routeName, $routeParam = null) {
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path     = str_replace($basePath, '', $url);

    return $path;
}

function getSiteData($dataKeys, $singleQuery = false, $limit = null, $orderById = false) {
    if ($singleQuery) {
        $siteData = SiteData::where('data_key', $dataKeys)->first();
    } else {
        $article = SiteData::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $siteData = $article->where('data_key', $dataKeys)->orderBy('id')->get();
        } else {
            $siteData = $article->where('data_key', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $siteData;
}

function slug($string) {
    return Illuminate\Support\Str::slug($string);
}

function showMobileNumber($number) {
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email) {
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}

function verifyG2fa($user, $code, $secret = null) {
    $authenticator = new GoogleAuthenticator();

    if (!$secret) {
        $secret = $user->tsc;
    }

    $oneCode  = $authenticator->getCode($secret);
    $userCode = $code;

    if ($oneCode == $userCode) {
        $user->tc = ManageStatus::YES;
        $user->save();

        return true;
    } else {
        return false;
    }
}

function getTrx($length = 12) {
    $characters       = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function gatewayRedirectUrl($type = false) {
    $purchasedPlan = session()->get('plan_purchase');
    $payment       = $purchasedPlan ? Deposit::done()->where('user_id', auth()->id())->where('plan_id', $purchasedPlan['plan_id'])->first() : null;

    if ($type && $payment) {

        session()->forget('plan_purchase');
        return  'user.payment.history';
    } else if ($type) {
        if (auth()->check()) {
            return 'user.deposit.history';
        } else {
            return 'home';
        }
    } else {
        return 'user.deposit.index';
    }
}

function showAmount($amount, $decimal = 0, $separate = true, $exceptZeros = false) {
    $decimal = $decimal ? $decimal :  bs('fraction_digit');
    $separator = '';

    if ($separate) {
        $separator = ',';
    }

    $printAmount = number_format($amount, $decimal, '.', $separator);

    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    return $printAmount;
}

function cryptoQR($wallet) {
    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8";
}

function diffForHumans($date) {
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function appendQuery($key, $value) {
    return request()->fullUrlWithQuery([$key => $value]);
}

function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}

function ordinal($number) {
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');

    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $ends[$number % 10];
}

function getFileExtension($fileTypeId = 0, $isUpdate = false) {
    $activeFileTypesQuery = FileType::when(!$isUpdate, fn($query) => $query->active());

    if ($fileTypeId && is_int($fileTypeId)) {
        $fileType = $activeFileTypesQuery->find($fileTypeId);

        return $fileType ? $fileType->supported_file_extension : [];
    }

    $fileExtensions = $activeFileTypesQuery->whereNotNull('supported_file_extension')->pluck('supported_file_extension')->toArray();

    return !empty($fileExtensions) ? array_unique(array_merge(...$fileExtensions)) : [];
}

function getArchiveExtensions($controller = false) {
    $archiveManagers = ArchiveManager::active()->pluck('extension')->map(fn($extension) => ltrim($extension, '.'));

    return $controller ? $archiveManagers->toArray() : $archiveManagers->map(fn($extension) => '.' . $extension)->implode(',');
}

function storageManager($file, $location, $size = null, $old = null, $thumb = null) {
    $servers = [ManageStatus::FTP_STORAGE => 'ftp', ManageStatus::WASABI_STORAGE => 'wasabi', ManageStatus::DIGITAL_OCEAN_STORAGE => 'do', ManageStatus::VULTR_STORAGE => 'vultr'];
    $server  = $servers[bs('storage_type')];

    $storageManager        = new StorageManager($server, $file);
    $storageManager->path  = $location;
    $storageManager->size  = $size;
    $storageManager->old   = $old;
    $storageManager->thumb = $thumb;
    $storageManager->upload();

    return $storageManager->filename;
}

function removeFile($path) {
    fileManager()->removeFile($path);
}

function  removeFileFromStorageManager($path) {
    $servers = [ManageStatus::FTP_STORAGE => 'ftp', ManageStatus::WASABI_STORAGE => 'wasabi', ManageStatus::DIGITAL_OCEAN_STORAGE => 'do', ManageStatus::VULTR_STORAGE => 'vultr'];
    $server  = $servers[bs('storage_type')];

    $storageManager = new StorageManager($server);
    $storageManager->removeFile($path);
}

function getS3FileUri($fileName, $type = 'image') {
    $setting = bs();
    $servers = [ManageStatus::WASABI_STORAGE => 'wasabi', ManageStatus::DIGITAL_OCEAN_STORAGE => 'digital_ocean', ManageStatus::VULTR_STORAGE => 'vultr'];
    $server  = $servers[$setting->storage_type];

    $accessKey  = $setting?->{$server}?->key ?? '';
    $secretKey  = $setting?->{$server}?->secret ?? '';
    $bucketName = $setting?->{$server}?->bucket ?? '';

    $objectKey = $type == 'image' ? 'images/' . $fileName : 'files/' . $fileName;
    $endpoint  = $setting->{$server}->endpoint;

    $credentials = new Credentials($accessKey, $secretKey);
    $s3Client    = new S3Client([
        'version'     => 'latest',
        'region'      => $setting?->{$server}?->region ?? '',
        'endpoint'    => $endpoint,
        'credentials' => $credentials
    ]);

    $command = $s3Client->getCommand('GetObject', [
        'Bucket' => $bucketName,
        'key'    => $objectKey
    ]);

    try {
        return (string) $s3Client->createPresignedRequest($command, '+1 hour')->getUri();
    } catch (\Exception $exp) {
    }
}

function fileUrl($fileName) {
    $setting = bs();

    if ($setting->storage_type == ManageStatus::FTP_STORAGE) {
        return $setting->ftp?->host_domain . '/files/' . $fileName ?? '';
    } else if ($setting->storage_type == 3 || $setting->storage_type == 4 || $setting->storage_type == 5) {
        return getS3FileUri($fileName, 'file');
    } else {
        return getFilePath('stockFile') . '/' . $fileName;
    }
}

function videoFileUrl($fileName) {
    $setting = bs();

    if ($setting->storage_type == ManageStatus::FTP_STORAGE) {
        return $setting->ftp?->host_domain . '/files/' . $fileName ?? '';
    } else if ($setting->storage_type == ManageStatus::WASABI_STORAGE || $setting->storage_type == ManageStatus::DIGITAL_OCEAN_STORAGE || $setting->storage_type == ManageStatus::VULTR_STORAGE) {
        return getS3FileUri($fileName, 'file');
    } else {
        return asset(getFilePath('stockVideo') . '/' . $fileName);
    }
}

function imageUrl($directory = null, $image = null, $size = null) {
    if (!$image) {
        return getImage('/' . $size);
    }

    $setting = bs();

    switch ($setting->storage_type) {
        case ManageStatus::FTP_STORAGE:
            return ($setting->ftp?->host_domain ?? '') . '/images/' . $image;

        case ManageStatus::WASABI_STORAGE:
        case ManageStatus::DIGITAL_OCEAN_STORAGE:
        case ManageStatus::VULTR_STORAGE:
            return getS3FileUri($image);

        default:
            return getImage($directory ? $directory . '/' . $image : $image, $size);
    }
}

function getExtension($path) {
    return pathinfo($path, PATHINFO_EXTENSION);
}

function addPlanDuration($planDuration) {
    switch ($planDuration) {
        case ManageStatus::DAILY_PLAN:
            return Carbon::now()->addDay();

        case ManageStatus::WEEKLY_PLAN:
            return Carbon::now()->addWeek();

        case ManageStatus::MONTHLY_PLAN:
            return Carbon::now()->addMonth();

        case ManageStatus::QUARTERLY_PLAN:
            return Carbon::now()->addMonths(3);

        case ManageStatus::SEMI_ANNUAL_PLAN:
            return Carbon::now()->addMonths(6);

        case ManageStatus::ANNUAL_PLAN:
            return Carbon::now()->addYear();

        default:
            return Carbon::now();
    }
}

function getSelectedColumns($tableName, $excludedColumnArray) {
    static $cache = [];

    if (!isset($cache[$tableName])) {
        $cache[$tableName] = Schema::getColumnListing($tableName);
    }

    return array_diff($cache[$tableName], $excludedColumnArray);
}

function formatNumber($number) {
    if ($number >= 1000000000) {
        return number_format($number / 1000000000, 1) . 'b';
    } else if ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'm';
    } else if ($number >= 1000) {
        return number_format($number / 1000, 1) . 'k';
    }

    return $number;
}

function levelCommission($user, $amount, $type) {
    $referralSettings = ReferralSetting::where('commission_type', $type)->orderBy('level')->get();
    $tempUser         = $user;
    $transactions     = [];
    $commissionLogs   = [];

    foreach ($referralSettings as $commission) {
        $referrer = $tempUser->refBy;
        if (!$referrer) {
            break;
        }

        $commissionAmount = $amount * ($commission->percent / 100);
        $referrer->balance += $commissionAmount;
        $referrer->save();

        $trx = getTrx();

        $transactions[] = [
          'user_id'      => $referrer->id,
          'amount'       => $commissionAmount,
          'post_balance' => $referrer->balance,
          'charge'       => 0,
          'trx_type'     => '+',
          'details'      => 'Level ' . $commission->level . ' referral commission from ' . $user->username,
          'remark'       => 'referral_commission',
          'trx'          => $trx,
          'created_at'   => now(),
          'updated_at'   => now(),
        ];

        $commissionLogs[] = [
            'to_id'             => $referrer->id,
            'from_id'           => $user->id,
            'level'             => $commission->level,
            'amount'            => $amount,
            'commission_amount' => $commissionAmount,
            'details'           => 'Level ' . $commission->level . ' referral commission from ' . $user->username,
            'type'              => $type,
            'percent'           => $commission->percent,
            'trx'               => $trx,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        $tempUser = $referrer;
    }

    if (!empty($transactions)) {
        Transaction::insert($transactions);
    }

    if (!empty($commissionLogs)) {
        CommissionLog::insert($commissionLogs);
    }
}

function getAdvertisement($size) {
    $advertisement = Advertisement::active()->where('size', $size)->inRandomOrder()->first();

    if ($advertisement) {
        if ($advertisement->type == 1) {
            $advertise = '<a href="' . $advertisement->redirect_url . '" data-id="' . $advertisement->id .'" class="advertisement" ><img src="' . getImage(getFilePath('advertisements'). '/' . $advertisement->image, $advertisement->size) .'" alt="Image"></a>';
        } else {
            $advertise = '<div class="advertisement" data-id="' . $advertisement->id . '">' . $advertisement->script . '</div>';
        }

        $advertisement->impression += 1;
        $advertisement->save();

        return $advertise;
    }

    return false;
}