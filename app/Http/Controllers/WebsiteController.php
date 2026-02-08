<?php

namespace App\Http\Controllers;

use App\Constants\ManageStatus;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Collection;
use App\Models\CollectionImage;
use App\Models\Color;
use App\Models\Contact;
use App\Models\FileType;
use App\Models\Follow;
use App\Models\GatewayCurrency;
use App\Models\Image;
use App\Models\Language;
use App\Models\Like;
use App\Models\Plan;
use App\Models\SiteData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    function home() {

        $reference = $_GET['reference'] ?? null;

        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle  = 'Home';
        $user       = auth()->user();

        return view($this->activeTheme . 'page.home', compact('pageTitle', 'user'));
    }

    function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();

        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    function cookieAccept() {
        Cookie::queue('gdpr_cookie', bs('site_name'), 43200);
    }

    function cookiePolicy() {
        $cookie    = SiteData::where('data_key', 'cookie.data')->first();
        $pageTitle = 'Cookie Policy';
        $subTitle  = $cookie->data_info->heading;

        return view($this->activeTheme . 'page.cookie',compact('pageTitle', 'subTitle', 'cookie'));
    }

    function maintenance() {
        if(bs('site_maintenance') == ManageStatus::INACTIVE) {
            return to_route('home');
        }

        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();
        $pageTitle   = $maintenance->data_info->heading;
        $subTitle    = $maintenance->data_info->subheading;

        return view($this->activeTheme . 'page.maintenance', compact('pageTitle', 'subTitle', 'maintenance'));
    }

    public function policyPages($slug,$id) {
        $policy    = SiteData::where('id', $id)->where('data_key', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_info->title;
        $subTitle  = $policy->data_info->sub_title;

        return view($this->activeTheme . 'page.policy', compact('policy', 'pageTitle', 'subTitle'));
    }

    function contact() {
        $siteData  = getSiteData('contact_us.content', true);
        $pageTitle = $siteData->data_info->breadcrumb_heading;
        $subTitle  = $siteData->data_info->breadcrumb_subheading;
        $user      = auth()->user();

        return view($this->activeTheme . 'page.contact', compact('pageTitle', 'subTitle', 'siteData', 'user'));
    }

    function contactStore() {
        $this->validate(request(), [
            'name'    => 'required|string|max:40',
            'email'   => 'required|string|max:40',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if(!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];
            return back()->withToasts($toast);
        }

        $user         = auth()->user();
        $email        = $user ? $user->email : request('email');
        $contactCheck = Contact::where('email', $email)->where('status', ManageStatus::NO)->first();

        if ($contactCheck) {
            $toast[] = ['warning', 'There is an existing contact on record, kindly await the admin\'s response'];
            return back()->withToasts($toast);
        }

        $contact          = new Contact();
        $contact->name    = $user ? $user->fullname : request('name');
        $contact->email   = $email;
        $contact->subject = request('subject');
        $contact->message = request('message');
        $contact->save();

        $toast[] = ['success', 'We register this contact in our record, kindly await the admin\'s response'];
        return back()->withToasts($toast);
    }

    function placeholderImage($size = null) {
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/RobotoMono-Regular.ttf');
        $fontSize = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) {
            $fontSize = 9;
        }

        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    function plan() {
        $siteData  = getSiteData('plan.content', true);
        $pageTitle = $siteData->data_info?->page_title;
        $subTitle  = $siteData->data_info?->subtitle;
        $cardBg    = $siteData->data_info?->card_bg;
        $user      = auth()->user();
        $planQuery = Plan::active()->latest();
        $plans     = [
            'daily'         => (clone $planQuery)->where('plan_duration', ManageStatus::DAILY_PLAN)->get(),
            'weekly'        => (clone $planQuery)->where('plan_duration', ManageStatus::WEEKLY_PLAN)->get(),
            'monthly'       => (clone $planQuery)->where('plan_duration', ManageStatus::MONTHLY_PLAN)->get(),
            'quarterAnnual' => (clone $planQuery)->where('plan_duration', ManageStatus::QUARTERLY_PLAN)->get(),
            'semiAnnual'    => (clone $planQuery)->where('plan_duration', ManageStatus::SEMI_ANNUAL_PLAN)->get(),
            'annual'        => (clone $planQuery)->where('plan_duration', ManageStatus::ANNUAL_PLAN)->get(),
        ];

        return view($this->activeTheme . 'page.plans', compact('pageTitle', 'subTitle', 'cardBg', 'plans', 'user'));
    }

    function assetDetail($assetId, $slug) {
        try {
            $asset = Image::find(decrypt($assetId));

            if (!$asset) {
                $toast = ['error', 'Asset not found'];
                return back()->withToasts($toast);
            }
        } catch (\Exception $exp) {
            $toast[] = ['error', 'Invalid Asset ID'];
            return back()->withToasts($toast);
        }

        if ($asset->status != ManageStatus::IMAGE_APPROVED) {
            $toast[] = ['error', 'Asset is not in approved status'];
            return back()->withToasts($toast);
        }

        if ($asset->category->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'Category of the asset is not active right now'];
            return back()->withToasts($toast);
        }

        if ($asset->fileType->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'File type of the asset is not active right now'];
            return back()->withToasts($toast);
        }
        
        if ($asset->user->status != ManageStatus::ACTIVE) {
            $toast[] = ['error', 'Author account is currently disabled'];
            return back()->withToasts($toast);
        }

        if ($asset->user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['error', 'Author account is currently disabled'];
            return back()->withToasts($toast);
        }

        $pageTitle       = $asset->title;
        $user            = auth()->user();
        $excludedColumns = ['image_name', 'file_type_id', 'track_id', 'upload_date', 'image_width', 'image_height', 'extension', 'description', 'tags', 'colors', 'total_like', 'is_featured', 'attribution', 'total_view', 'reason', 'admin_id', 'reviewer_id', 'total_earning'];
        $relatedAssets   = Image::activeCheck()->whereNot('id', $asset->id)->where('category_id', $asset->category_id)->select(getSelectedColumns('images', $excludedColumns))->with(['user', 'category', 'imageFiles' => fn($query) => $query->active()->premium(), 'likes' => fn($query) => $query->when($user, fn($q) => $q->where('user_id', $user->id))])->latest()->limit(10)->get();
        $authorAssets    = Image::activeCheck()->whereNot('id', $asset->id)->where('user_id', $asset->user_id)->select(getSelectedColumns('images', $excludedColumns))->with(['user', 'category', 'imageFiles' => fn($query) => $query->active()->premium(), 'likes' => fn($query) => $query->when($user, fn($q) => $q->where('user_id', $user->id))])->latest()->limit(9)->get();
        $isLiked         = $user ? Like::where('user_id', $user->id)->where('image_id', $asset->id)->exists() : false;
        $isFollowed      = $user ? Follow::where('follower_id', $user->id)->where('following_id', $asset->user_id)->exists() : false;
        $gatewayCurrency = bs('donation') ? GatewayCurrency::whereHas('method', fn($gate) => $gate->where('status', ManageStatus::ACTIVE))->with('method')->orderBy('method_code')->get() : [];
        $collections     = Collection::when($user, fn($query) => $query->where('user_id', $user->id))->orderBy('title')->get();
        $this->updateImageViewCount($asset);

        $seoContent['keywords']           = $asset->tags ?? [];
        $seoContent['social_title']       = $asset->title;
        $seoContent['description']        = strLimit($asset->description, 150);
        $seoContent['social_description'] = strLimit($asset->description, 150);
        $seoContent['image']              = imageUrl(getFilePath('stockImage'), $asset->thumb) ;
        $seoContent['image_size']         = "{$asset->image_width}x{$asset->image_height}";

        return view($this->activeTheme . 'page.assetDetail', compact('pageTitle', 'asset', 'relatedAssets', 'authorAssets', 'user', 'isLiked', 'isFollowed', 'gatewayCurrency', 'collections', 'seoContent'));
    }

    protected  function updateImageViewCount($asset) {
        $counter = session()->get('viewCounter', []);

        if (!isset($counter[$asset->id]) || $counter[$asset->id] < Carbon::now()) {
            $asset->total_view += 1;
            $asset->save();

            $counter[$asset->id] = Carbon::now()->addMinutes(120);
            session()->put('viewCounter', $counter);
        }
    }

    function allAssets() {

        if (request()->ajax())  {
            $validate = Validator::make(request()->all(), [
                'search_title' => 'nullable|string',
                'data_type'    => 'nullable|in:assets,collections',
                'file_type_id' => 'nullable|int|gt:0',
                'sort'         => 'nullable|in:old,popular,featured,download',
                'color_code'   => 'nullable|string|max:40',
                'license'      => 'nullable|in:free,premium',
                'publish'      => 'nullable|in:3,6,1',
                'extension'    => 'nullable|string|max:40',
                'shape'        => 'nullable|in:vertical,horizontal,square,panoramic',
                'category_id'  => 'nullable|int|gt:0',
                'tag'          => 'nullable|string'
            ]);

            if ($validate->fails()) {
                return response([
                    'success' => false,
                    'message' => $validate->errors()
                ]);
            }

        } else {
            $this->validate(request(), [
                'search_title' => 'nullable|string',
                'data_type'    => 'nullable|in:assets,collections',
                'file_type_id' => 'nullable|int|gt:0',
                'sort'         => 'nullable|in:old,popular,featured,download',
                'license'      => 'nullable|in:free,premium',
                'category_id'  => 'nullable|int|gt:0',
                'tag'          => 'nullable|string'
            ]);
        }

        $fileType = FileType::active()->find(request('file_type_id'));

        if (request('file_type_id') && !$fileType) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Filetype not found'
                ]);
            }

            $toast[] = ['error', 'Filetype not found'];
            return back()->withToasts($toast);
        }

        $category = Category::active()->find(request('category_id'));

        if (request('category_id') && !$category) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Category not found'
                ]);
            }

            $toast[] = ['error', 'Category not found'];
            return back()->withToasts($toast);
        }
        

        $pageTitle   = 'All Assets';
        $user        = auth()->user();
        $assetTypes  = FileType::active()->orderBy('name')->select(getSelectedColumns('file_types', ['image', 'collection_image', 'video', 'supported_file_extension', 'type', 'created_at', 'updated_at']))->get();
        $colors      = Color::active()->orderBy('name')->select(getSelectedColumns('colors', ['created_at', 'updated_at']))->get();
        $fileTypes   = Image::pluck('extensions')->toArray();
        $fileTypes   = !empty($fileTypes) ? array_unique(array_merge(...$fileTypes)) : [];
        $categories  = Category::active()->select(getSelectedColumns('categories', ['image', 'slug', 'created_at', 'updated_at']))->orderBy('name')->get();
        
        $assetsQuery = Image::select(getSelectedColumns('images', ['image_name', 'track_id', 'upload_date', 'description', 'total_like', 'attribution', 'reason', 'admin_id', 'reviewer_id', 'total_earning']))->with(['user', 'fileType', 'category', 'imageFiles' => fn($query) => $query->active()->premium(), 'likes' => fn($query) => $query->when($user, fn($q) => $q->where('user_id', $user->id))]);

        $assetsFilterQuery = $assetsQuery->when(request('search_title'), fn($query) => $query->where('title', 'like', "%" . request('search_title') . "%")->orWhereJsonContains('tags', request('search_title'))->orWhereHas('user', fn($q) => $q->where('author_name', 'like', "%" . request('search_title') . "%")))
        ->when(request('data_type') && request('data_type') == 'collections', fn($query) => $query->whereHas('collectionImages'))
        ->when(request('file_type_id'), fn($query) => $query->where('file_type_id', request('file_type_id')))
        ->when(request('sort') && request('sort') == 'old', fn($query) => $query->orderBy('id'))
        ->when(request('sort') && request('sort') == 'popular', fn($query) => $query->orderByDesc('total_view'))
        ->when(request('sort') && request('sort') == 'featured', fn($query) => $query->where('is_featured', ManageStatus::ACTIVE))
        ->when(request('sort') && request('sort') == 'download', fn($query) => $query->orderByDesc('total_download'))
        ->when(request('color_code'), fn($query) => $query->whereJsonContains('colors', request('color_code')))
        ->when(request('license') && request('license') == 'premium', fn($query) => $query->whereHas('imageFiles', fn($q) => $q->active()->premium()))
        ->when(request('license') && request('license') == 'free', fn($query) => $query->whereHas('imageFiles', fn($q) => $q->active()->free()))
        ->when(request('publish') && request('publish') == '3', fn($query) => $query->where('updated_at', '>=', Carbon::now()->subMonths(3)))
        ->when(request('publish') && request('publish') == '6', fn($query) => $query->where('updated_at', '>=', Carbon::now()->subMonths(6)))
        ->when(request('publish') && request('publish') == '1', fn($query) => $query->where('updated_at', '>=', Carbon::now()->subYear()))
        ->when(request('extension'), fn($query) => $query->whereJsonContains('extensions', request('extension')))
        ->when(request('shape') && request('shape') == 'horizontal', fn($query) => $query->whereColumn('image_width', '>', 'image_height')->whereRaw('image_width < image_height * 2'))
        ->when(request('shape') && request('shape') == 'vertical', fn($query) => $query->whereColumn('image_width', '<', 'image_height'))
        ->when(request('shape') && request('shape') == 'square', fn($query) => $query->whereColumn('image_width', '=', 'image_height'))
        ->when(request('shape') && request('shape') == 'panoramic', fn($query) => $query->whereRaw('image_width >= image_height * 2'))
        ->when(request('category_id'), fn($query) => $query->where('category_id', request('category_id')))
        ->when(request('tag'), fn($query) => $query->whereJsonContains('tags', request('tag')))
        ->when(!request('sort'), fn($query) => $query->orderByDesc('updated_at'))
        ->activeCheck();

        $assetsCount = (clone $assetsFilterQuery)->count();
        $assets      = $assetsFilterQuery->paginate(getPaginate(20));

        $isAssetOrCollectionAssets = request('data_type') == 'collections' ? 'collectedAssets' : 'allAssets';

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.assets', compact('assets', 'user'))->render();

            return response([
                'success'                    => true,
                'html'                       => $html,
                'assetsCount'                => $assetsCount,
                'isAssetsOrCollectionAssets' => $isAssetOrCollectionAssets
            ]);
        }

        return view($this->activeTheme . 'page.allAssets', compact('pageTitle', 'assetTypes', 'colors', 'fileTypes', 'user', 'categories', 'assets', 'assetsCount', 'isAssetOrCollectionAssets'));
    }
    
    function collections() {

        $validate = Validator::make(request()->all(), [
            'search' => 'nullable|string'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $userExcludedColumn = ['image', 'cover_image', 'firstname', 'lastname', 'email', 'country_code', 'country_name', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'status', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token'];

        $collections = Collection::active()->public()
                        ->whereHas('images')
                        ->with([
                            'user' => fn($query) => $query->select(getSelectedColumns('users', $userExcludedColumn)),
                            'images' => fn($query) => $query->select('images.id', 'images.title', 'images.image_name', 'images.thumb', 'images.video', 'images.status', 'images.tags')
                        ])
                        ->latest()
                        ->paginate(getPaginate(16));

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.collections', compact('collections'))->render();

            return response([
                'success' => true,
                'html'    => $html
            ]);
        }

        $pageTitle          = 'Collections';

        return view($this->activeTheme . 'page.collections', compact('pageTitle', 'collections'));
    }

    function collectionDetail($collectionId, $title) {
        try {
            $collection = Collection::find(decrypt($collectionId));

            if (!$collection) {
                if (request()->ajax()) {
                    return response([
                        'success' => false,
                        'message' => 'Collection not found'
                    ]);
                }
    
                $toast[] = ['error', 'Collection not found'];
                return back()->withToasts($toast);
            }
        } catch (\Throwable $th) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'Invalid collection ID'
                ]);
            }

            $toast[] = ['error', 'Invalid collection ID'];
            return back()->withToasts($toast);
        }

        $user = auth()->user();

        if ($collection->visibility != ManageStatus::PUBLIC_COLLECTION && !$user) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'The collection is not public'
                ]);
            }

            $toast[] = ['error', 'The collection is not public'];
            return back()->withToasts($toast);
        }

        if (!$user && ($user && $collection->user_id != $user->id) && ($collection->status != ManageStatus::ACTIVE)) {
            if (request()->ajax()) {
                return response([
                    'success' => false,
                    'message' => 'The collection is not active right now'
                ]);
            }

            $toast[] = ['error', 'The collection is not active right now'];
            return back()->withToasts($toast);
        }

        if ($user) {
            if ($collection->visibility != ManageStatus::PUBLIC_COLLECTION && $collection->user_id != $user->id) {
                if (request()->ajax()) {
                    return response([
                        'success' => false,
                        'message' => 'Unauthorized action'
                    ]);
                }

                $toast[] = ['error', 'Unauthorized action'];
                return back()->withToasts($toast);
            }
        }

        $collectionAssetIds = CollectionImage::where('collection_id', $collection->id)->pluck('image_id')->toArray();

        $excludedColumns = ['file_type_id', 'track_id', 'upload_date', 'image_width', 'image_height', 'extensions', 'description', 'tags', 'colors', 'total_like', 'is_featured', 'attribution', 'total_view', 'reason', 'admin_id', 'reviewer_id', 'total_earning'];
        $assets          = Image::activeCheck()
                                ->whereIn('id', $collectionAssetIds)
                                ->latest()
                                ->select(getSelectedColumns('images', $excludedColumns))
                                ->with(['category', 'user', 'imageFiles' => fn($query) => $query->active()->premium(), 'likes' => fn($query) => $query->when($user, fn($q) => $q->where('user_id', $user->id))])
                                ->paginate(getPaginate(16));

        if (request()->ajax()) {
            $html = view($this->activeTheme . 'ajax.collectionAssets', compact('assets', 'user'))->render();

            return response([
                'success' => true,
                'html'    => $html 
            ]);
        }

        $content   = getSiteData('breadcrumb.content', true);
        $pageTitle = $collection->title;

        return view($this->activeTheme . 'page.collectionDetail', compact('pageTitle', 'user', 'collection', 'assets', 'content'));
    }

    function addClick() {
        $validate = Validator::make(request()->all(), [
            'id' => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $advertisement = Advertisement::find(request('id'));

        if (!$advertisement) {
            return response([
                'success' => false,
                'message' => 'Advertisement not found'
            ]);
        }

        if ($advertisement->status != ManageStatus::ACTIVE) {
            return response([
                'success' => false,
                'message' => 'Clicked advertisement is not active right now'
            ]);
        }

        $advertisement->click += 1;
        $advertisement->save();

        return response([
            'success' => true,
            'data'    => $advertisement
        ]);
    }
}
