<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\Plugin;
use App\Models\SiteData;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SettingController extends Controller
{
    function basic() {
        $pageTitle   = 'Basic Setting';
        $timeRegions = json_decode(file_get_contents(resource_path('views/admin/partials/timeRegion.json')));
    
        return view('admin.setting.basic', compact('pageTitle', 'timeRegions'));
    }

    function basicUpdate() {
        $this->validate(request(), [
            'site_name'           => 'required|string|max:40',
            'site_cur'            => 'required|string|max:40',
            'cur_sym'             => 'required|string|max:40',
            'first_color'         => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'second_color'        => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'per_page_item'       => 'required|in:20,50,100',
            'fraction_digit'      => 'required|int|gte:0|max:9',
            'date_format'         => 'required|in:m-d-Y,d-m-Y,Y-m-d',
            'time_region'         => 'required',
            'max_price_limit'     => 'required|numeric|gt:0',
            'daily_upload_limit'  => 'required|integer|gt:0',
            'tag_limit_per_asset' => 'required|integer|gt:0',
            'authors_commission'  => 'required|numeric|gt:0',
            'max_referral_level'  => 'required|int|gt:0'
        ]);

        $setting                      = bs();
        $setting->site_name           = request('site_name');
        $setting->site_cur            = request('site_cur');
        $setting->cur_sym             = request('cur_sym');
        $setting->per_page_item       = request('per_page_item');
        $setting->fraction_digit      = request('fraction_digit');
        $setting->date_format         = request('date_format');
        $setting->first_color         = str_replace('#', '', request('first_color'));
        $setting->second_color        = str_replace('#', '', request('second_color'));
        $setting->max_price_limit     = request('max_price_limit');
        $setting->daily_upload_limit  = request('daily_upload_limit');
        $setting->tag_limit_per_asset = request('tag_limit_per_asset');
        $setting->authors_commission  = request('authors_commission');
        $setting->max_referral_level  = request('max_referral_level');
        $setting->save();

        $timeRegionFile = config_path('timeRegion.php');
        $setTimeRegion  = '<?php $timeRegion = '.request('time_region').' ?>';
        file_put_contents($timeRegionFile, $setTimeRegion);

        $toast[] = ['success', 'Basic setting update success'];
        return back()->withToasts($toast);
    }

    function systemPreference() {
        $pageTitle = 'Preference in Setting';

        return view('admin.setting.preference', compact('pageTitle'));
    }

    function systemUpdate() {
        $setting                             = bs();
        $setting->signup                     = request('signup')        ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->enforce_ssl                = request('enforce_ssl')   ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->agree_policy               = request('agree_policy')  ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->strong_pass                = request('strong_pass')   ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->kc                         = request('kc')            ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->ec                         = request('ec')            ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->ea                         = request('ea')            ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->sc                         = request('sc')            ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->sa                         = request('sa')            ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->language                   = request('language')      ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->watermark                  = request('watermark')     ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->auto_approval              = request('auto_approval') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->donation                   = request('donation')      ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->asset_approval_notify      = request('asset_approval_notify') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->reviewer_action_permission = request('reviewer_action_permission') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->save();

        $toast[] = ['success', 'System setting update success'];
        return back()->withToasts($toast);
    }

    function logoFaviconUpdate() {
        $this->validate(request(), [
            'logo_light' => [File::types(['png'])],
            'logo_dark'  => [File::types(['png'])],
            'favicon'    => [File::types(['png'])],
        ]);

        $path = getFilePath('logoFavicon');

        if (request()->hasFile('logo_light')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $manager = new ImageManager(new Driver());
                $image   = $manager->read(request('logo_light'));
                $image->save($path . '/logo_light.png');
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Unable to upload light logo'];
                return back()->withToasts($toast);
            }
        }

        if (request()->hasFile('logo_dark')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $manager = new ImageManager(new Driver());
                $image   = $manager->read(request('logo_dark'));
                $image->save($path . '/logo_dark.png');
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Unable to upload dark logo'];
                return back()->withToasts($toast);
            }
        }

        if (request()->hasFile('favicon')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', getFileSize('favicon'));
                $manager = new ImageManager(new Driver());
                $image   = $manager->read(request('favicon'));
                $image->resize($size[0], $size[1]);
                $image->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Unable to upload the favicon'];
                return back()->withToasts($toast);
            }
        }

        $toast[] = ['success', 'Logo and favicon update success'];
        return back()->withToasts($toast);
    }

    function plugin() {
        $pageTitle = 'Plugin Settings';
        $plugins   = Plugin::orderBy('name')->get();

        return view('admin.setting.plugin', compact('pageTitle', 'plugins'));
    }

    function pluginUpdate($id) {
        $plugin = Plugin::findOrFail($id);
        $validationRule = [];

        foreach ($plugin->shortcode as $key => $val) {
            $validationRule = array_merge($validationRule,[$key => 'required']);
        }

        request()->validate($validationRule);

        $shortCode = json_decode(json_encode($plugin->shortcode), true);

        foreach ($shortCode as $key => $value) {
            $shortCode[$key]['value'] = request($key);
        }

        $plugin->shortcode = $shortCode;
        $plugin->status    = request('status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $plugin->save();

        $toast[] = ['success', $plugin->name . ' updated success'];
        return back()->withToasts($toast);
    }

    function pluginStatus($id) {
        return Plugin::changeStatus($id);
    }

    function seo() {
        $pageTitle = 'SEO Setting';
        $seo       = SiteData::where('data_key', 'seo.data')->first();

        if(!$seo) {
            $data_info           = '{"keywords":[],"description":"","social_title":"","social_description":"","image":null}';
            $data_info           = json_decode($data_info, true);
            $siteData            = new SiteData();
            $siteData->data_key  = 'seo.data';
            $siteData->data_info = $data_info;
            $siteData->save();
        }

        return view('admin.site.seo', compact('pageTitle', 'seo'));
    }

    function cookie() {
        $pageTitle = 'Cookie Policy';
        $cookie    = SiteData::where('data_key', 'cookie.data')->first();

        return view('admin.site.cookie', compact('pageTitle', 'cookie'));
    }

    function cookieUpdate() {
        $this->validate(request(), [
            'heading'       => 'required',
            'short_details' => 'required',
            'details'       => 'required',
        ]);

        $cookie = SiteData::where('data_key', 'cookie.data')->first();
        $cookie->data_info = [
            'heading'      => request('heading'),
            'short_details' => request('short_details'),
            'details'       => request('details'),
            'status'        => request('status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE,
        ];
        $cookie->save();

        $toast[] = ['success', 'Cookie policy update success'];
        return back()->withToasts($toast);
    }

    function maintenance() {
        $pageTitle   = 'Under Maintenance Mode';
        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();

        return view('admin.site.maintenance', compact('pageTitle', 'maintenance'));
    }

    function maintenanceUpdate() {
        $this->validate(request(), [
            'heading'    => 'required',
            'subheading' => 'required',
            'details'    => 'required',
        ]);

        $setting = bs();
        $setting->site_maintenance = request('status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->save();

        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();
        $maintenance->data_info = [
            'heading'    => request('heading'),
            'subheading' => request('subheading'),
            'details'    => request('details'),
        ];
        $maintenance->save();

        $toast[] = ['success', 'Maintenance data update success'];
        return back()->withToasts($toast);
    }

    function kyc() {
        $pageTitle   = 'KYC Setting';
        $form        = Form::where('act','kyc')->first();
        $formHeading = 'KYC Form Data';

        return view('admin.setting.kyc',compact('pageTitle', 'form', 'formHeading'));
    }

    function kycUpdate() {
        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();

        request()->validate($generatorValidation['rules'], $generatorValidation['messages']);

        $exist    = Form::where('act','kyc')->first();
        $isUpdate = $exist ? true : false;

        $formProcessor->generate('kyc',$isUpdate,'act');

        $toast[] = ['success', 'KYC data update success'];
        return back()->withToasts($toast);
    }

    function cacheClear() {
        Artisan::call('optimize:clear');
        $toast[] = ['success', 'Clearing cache success'];
        return back()->withToasts($toast);
    }

    function updateInstruction() {
        $setting = bs();
        $instructionManualValidation = $setting->instruction_manual == null ? 'required' : 'nullable';

        $this->validate(request(), [
            'heading'            => 'required|string',
            'instruction'        => 'required',
            'instruction_manual' => $instructionManualValidation
        ]);

        if (request()->hasFile('instruction_manual')) {
            $extension = request('instruction_manual')->getClientOriginalExtension();

            if (!in_array($extension, ['txt', 'pdf'])) {
                $toast[] = ['error', 'Only txt and pdf files are acceptable'];
                return back()->withToasts($toast);
            }

            try {
                if ($setting->instruction_manual != null) {
                    fileManager()->removeFile(getFilePath('instructionManual') . '/' . $setting->instruction_manual);
                }
                
                $setting->instruction_manual = fileUploader(request('instruction_manual'), getFilePath('instructionManual'), null, $setting->instruction_manual);
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Instruction manual upload fail'];
                return back()->withToasts($toast);
            }
        }

        $setting->instruction = [
            'heading'     => request('heading'),
            'instruction' => request('instruction')
        ];
        $setting->save();

        $toast[] = ['success', 'Instruction update success'];
        return back()->withToasts($toast);
    }

    function watermarkImageUpload() {
        $validate = Validator::make(request()->all(), [
            'watermark_image' => ['required', File::types(['png'])]
        ]);

        if ($validate->fails()) {
            return response([
                'success' => false,
                'message' => $validate->errors()
            ]);
        }

        $setting = bs();

        if (request()->hasFile('watermark_image')) {
            try {
                $path = getFilePath('watermarkImage');

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $size    = explode('x', getFileSize('watermarkImage'));
                $manager = new ImageManager(new Driver());
                $manager->read(request('watermark_image'))->resize($size[0], $size[1])->save($path . '/watermark.png');

                $setting->watermark_image = 'watermark.png';
                $setting->save();

                return response([
                    'success'   => true,
                    'image_url' => getImage($path . 'watermark.png', getFileSize('watermarkImage')),
                    'message'   => 'Watermark image upload success'
                ]);
            } catch (\Exception $exp) {
                return response([
                    'success' => false,
                    'message' => 'Watermark image upload fail'
                ]);
            }
        }

        return response([
            'success' => false,
            'message' => 'No file uploaded'
        ]);
    }

    function configStorage() {
        $pageTitle = 'Storage Management';

        return view('admin.setting.storage', compact('pageTitle'));
    }

    function updateStorage() {
        $this->validate(request(), [
            'storage_type' => 'required|in:1,2,3,4,5',

            'ftp.host_domain' => 'required_if:storage_type,2',
            'ftp.host'        => 'required_if:storage_type,2',
            'ftp.username'    => 'required_if:storage_type,2',
            'ftp.password'    => 'required_if:storage_type,2',
            'ftp.port'        => 'required_if:storage_type,2',
            'ftp.root_path'   => 'required_if:storage_type,2',

            'wasabi.driver'   => 'required_if:storage_type,3',
            'wasabi.key'      => 'required_if:storage_type,3',
            'wasabi.secret'   => 'required_if:storage_type,3',
            'wasabi.region'   => 'required_if:storage_type,3',
            'wasabi.bucket'   => 'required_if:storage_type,3',
            'wasabi.endpoint' => 'required_if:storage_type,3',

            'digital_ocean.driver'   => 'required_if:storage_type,4',
            'digital_ocean.key'      => 'required_if:storage_type,4',
            'digital_ocean.secret'   => 'required_if:storage_type,4',
            'digital_ocean.region'   => 'required_if:storage_type,4',
            'digital_ocean.bucket'   => 'required_if:storage_type,4',
            'digital_ocean.endpoint' => 'required_if:storage_type,4',

            'vultr.driver'   => 'required_if:storage_type,5',
            'vultr.key'      => 'required_if:storage_type,5',
            'vultr.secret'   => 'required_if:storage_type,5',
            'vultr.region'   => 'required_if:storage_type,5',
            'vultr.bucket'   => 'required_if:storage_type,5',
            'vultr.endpoint' => 'required_if:storage_type,5',
        ], [
            'storage_type.required' => 'Please select a storage type.',
            'storage_type.in'       => 'The selected storage type is invalid.',
        
            'ftp.host_domain.required_if' => 'The FTP host domain is required when FTP storage is selected.',
            'ftp.host.required_if'        => 'The FTP host is required when FTP storage is selected.',
            'ftp.username.required_if'    => 'The FTP username is required when FTP storage is selected.',
            'ftp.password.required_if'    => 'The FTP password is required when FTP storage is selected.',
            'ftp.port.required_if'        => 'The FTP port is required when FTP storage is selected.',
            'ftp.root_path.required_if'   => 'The FTP root path is required when FTP storage is selected.',

            'wasabi.driver.required_if'   => 'The Wasabi driver is required when Wasabi storage is selected.',
            'wasabi.key.required_if'      => 'The Wasabi key is required when Wasabi storage is selected.',
            'wasabi.secret.required_if'   => 'The Wasabi secret key is required when Wasabi storage is selected.',
            'wasabi.region.required_if'   => 'The Wasabi region is required when Wasabi storage is selected.',
            'wasabi.bucket.required_if'   => 'The Wasabi bucket name is required when Wasabi storage is selected.',
            'wasabi.endpoint.required_if' => 'The Wasabi endpoint is required when Wasabi storage is selected.',

            'digital_ocean.driver.required_if'   => 'The Digital Ocean driver is required when Digital Ocean storage is selected.',
            'digital_ocean.key.required_if'      => 'The Digital Ocean key is required when Digital Ocean storage is selected.',
            'digital_ocean.secret.required_if'   => 'The Digital Ocean secret key is required when Digital Ocean storage is selected.',
            'digital_ocean.region.required_if'   => 'The Digital Ocean region is required when Digital Ocean storage is selected.',
            'digital_ocean.bucket.required_if'   => 'The Digital Ocean bucket name is required when Digital Ocean storage is selected.',
            'digital_ocean.endpoint.required_if' => 'The Digital Ocean endpoint is required when Digital Ocean storage is selected.',

            'vultr.driver.required_if'   => 'The Vultr driver is required when Vultr storage is selected.',
            'vultr.key.required_if'      => 'The Vultr key is required when Vultr storage is selected.',
            'vultr.secret.required_if'   => 'The Vultr secret key is required when Vultr storage is selected.',
            'vultr.region.required_if'   => 'The Vultr region is required when Vultr storage is selected.',
            'vultr.bucket.required_if'   => 'The Vultr bucket name is required when Vultr storage is selected.',
            'vultr.endpoint.required_if' => 'The Vultr endpoint is required when Vultr storage is selected.',
        ]);

        $setting = bs();
        $setting->storage_type = request('storage_type');

        if (request('storage_type') == '2') {
            $setting->ftp = request('ftp');
        } else if (request('storage_type') == '3') {
            $setting->wasabi = request('wasabi');
        } else if (request('storage_type') == '4') {
            $setting->digital_ocean = request('digital_ocean');
        } else if (request('storage_type') == '5') {
            $setting->vultr = request('vultr');
        }

        $setting->save();

        $toast[] = ['success', 'Storage configuration update success'];
        return back()->withToasts($toast);
    }

    function guideline() {
        $pageTitle   = 'Author Guidelines';
        $form        = Form::where('act', 'author')->first();
        $formHeading = 'Author form Data';

        return view('admin.author.guideline', compact('pageTitle', 'form', 'formHeading'));
    }

    function requirementsUpdate() {
        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();

        request()->validate($generatorValidation['rules'], $generatorValidation['messages']);

        $exist    = Form::where('act', 'author')->first();
        $isUpdate = $exist ? true : false;

        $formProcessor->generate('author', $isUpdate, 'act');

        $toast[] = ['success', 'Author requirements update success'];
        return back()->withToasts($toast);
    }

    function donationSetting() {
        $pageTitle = 'Donation Setting';

        return view('admin.setting.donation', compact('pageTitle'));
    }

    function updateDonationSetting() {
        $this->validate(request(), [
            'item'     => 'required|string|max:40',
            'subtitle' => 'required|string|max:255',
            'icon'     => 'required|string|max:240',
            'amount'   => 'required|numeric|gt:0',
            'unit'     => 'required|array',
            'unit.*'   => 'required|integer|gt:0|distinct'
        ], [
            'unit.required'   => 'You must provide at least one unit',
            'unit.array'      => 'The unit field must be an array',
            'unit.*.required' => 'Each unit value is required',
            'unit.*.integer'  => 'Each unit must be a valid integer',
            'unit.*.gt'       => 'Each unit must be greater than zero',
            'unit.*.distinct' => 'Each unit value must be unique'
        ]);

        $setting = bs();
        $setting->donation_setting = [
            'item'     => request('item'),
            'subtitle' => request('subtitle'),
            'icon'     => request('icon'),
            'amount'   => request('amount'),
            'unit'     => request('unit')
        ];

        $setting->save();

        $toast[] = ['success', 'Donation setting update success'];
        return back()->withToasts($toast);
    }
}
