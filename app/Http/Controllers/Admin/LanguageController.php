<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Models\Language;
use App\Models\SiteData;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageController extends Controller
{
    function index() {
        $pageTitle = 'Language Setting';
        $languages = Language::get();

        return view('admin.language.index', compact('pageTitle', 'languages'));
    }

    function keywords() {
        $keys    = [];
        $dirname = resource_path('views');

        foreach ($this->getAllFiles($dirname) as $file) {
            $keys = array_merge($keys, $this->getKeys($file));
        }

        $siteData = SiteData::where('data_key', '!=', 'seo.data')->get();

        foreach ($siteData as $data) {
            foreach ($data->data_info as $key => $info) {
                if ($key != 'has_image' && !isImage($info) && !isHtml($info)) {
                    $keys[] = $info;
                }
            }
        }

        $keys    = array_unique($keys);
        $keyText = '';

        foreach ($keys as $langKey) {
            $keyText .= "$langKey \n";
        }

        return rtrim($keyText, "\n");
    }

    function store($id = 0) {
        $codeValidate = $id ? 'nullable' : 'required|string|max:40|unique:languages';

        $this->validate(request(), [
            'name' => 'required|string|max:40',
            'code' => $codeValidate,
        ]);

        if ($id) {
            $language     = Language::where('id', $id)->findOrFail($id);
            $notification = 'Language update success';

            if (request('is_default')) {
                $defaultLang =Language::where('is_default', ManageStatus::YES)->where('id', '!=', $language->id)->first();

                if ($defaultLang) {
                    $defaultLang->is_default = ManageStatus::NO;
                    $defaultLang->save();
                }

            } else {
                $defaultLang =Language::where('is_default', ManageStatus::YES)->where('id', '!=', $language->id)->exists();

                $toast[] = ['error', 'Set a new default language before unsetting this one'];
                return back()->withToasts($toast);
            }

        } else {
            $language       = new  Language();
            $language->code = strtolower(request('code'));
            $notification   = 'Language add success';
            $data           = file_get_contents(resource_path('lang/') . 'en.json');
            $jsonFile       = strtolower(request('code')) . '.json';
            $path           = resource_path('lang/') . $jsonFile;

            File::put($path, $data);

            if (request('is_default')) {
                $lang = $language->where('is_default', ManageStatus::YES)->first();

                if ($lang) {
                    $lang->is_default = ManageStatus::NO;
                    $lang->save();
                }
            }
        }

        $language->name = request('name');
        $language->is_default = request('is_default') ? ManageStatus::YES : ManageStatus::NO;
        $language->save();

        $toast[] = ['success', $notification];

        return back()->withToasts($toast);
    }

    function status($id) {
        return Language::changeStatus($id);
    }

    function delete($id) {
        $language = Language::where('id', '!=', 1)->findOrFail($id);

        fileManager()->removeFile(resource_path('lang/') . $language->code . '.json');
        $language->delete();

        $languageAll = Language::get();

        if ($languageAll->count() == 1) {
            $lang = Language::find(1);
            $lang->is_default = ManageStatus::YES;
            $lang->save();
        }

        $toast[] = ['success', 'Language delete success'];

        return back()->withToasts($toast);
    }

    function translateKeyword($id) {
        $language  = Language::findOrFail($id);
        $pageTitle = "Update $language->name Keywords";
        $json      = file_get_contents(resource_path('lang/') . $language->code . '.json');
        $allLang   = Language::all();
        $searchKey = trim(request('search'));

        if (empty($json)) {
            $toast[] = ['error', 'File doesn\'t exists'];

            return back()->withToasts($toast);
        }

        $json = json_decode($json, true);

        if ($searchKey) {
            $searchResult = [];

            foreach ($json as $key => $value) {
                if (stripos($key, $searchKey) !== false) $searchResult[$key] = $value;
            }

            $json = $searchResult;
        }

        $perPage     = getPaginate();
        $currentPage = request()->get('page', 1);
        $offset      = ($currentPage - 1) * $perPage;
        $items       = array_slice($json, $offset, $perPage, true);
        $json        = new LengthAwarePaginator(
            $items,
            count($json),
            $perPage,
            $currentPage,
            ['path' => url()->current()],
        );

        return view('admin.language.translate', compact('pageTitle', 'json', 'language', 'allLang'));
    }

    function languageImport() {
        $toLang = Language::find(request('toLangId'));

        if (request('id') != 999) {
            $fromLang = Language::find(request('id'));
            $json     = file_get_contents(resource_path('lang/') . $fromLang->code . '.json');
            $keywords = json_decode($json, true);
        } else {
            $text     = $this->keywords();
            $keywords = explode("\n", $text);
        }

        $items = file_get_contents(resource_path('lang/') . $toLang->code . '.json');

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);

            if (!array_key_exists($keyword, json_decode($items, true))) {
                $newArr[$keyword] = $keyword;
            }
        }

        if (isset($newArr)) {
            $itemData = json_decode($items, true);
            $result   = array_merge($itemData, $newArr);

            file_put_contents(resource_path('lang/') . $toLang->code . '.json', json_encode($result));
        }

        return 'success';
    }

    function languageKeyStore($id) {
        $language = Language::findOrFail($id);

        $this->validate(request(), [
            'key'   => 'required',
            'value' => 'required',
        ]);

        $json   = file_get_contents(resource_path('lang/') . $language->code . '.json');
        $reqKey = trim(request('key'));

        if (array_key_exists($reqKey, json_decode($json, true))) {
            $toast[] = ['error', 'This key has already been taken'];

            return back()->withToasts($toast);
        } else {
            $newArr[$reqKey] = trim(request('value'));
            $itemData        = json_decode($json, true);
            $result          = array_merge($itemData, $newArr);

            file_put_contents(resource_path('lang/') . $language->code . '.json', json_encode($result));

            $toast[] = ['success', 'Language key added success'];

            return back()->withToasts($toast);
        }
    }

    function languageKeyUpdate($id) {
        $this->validate(request(), [
            'key'   => 'required',
            'value' => 'required',
        ]);

        $key           = trim(request('key'));
        $language      = Language::find($id);
        $data          = file_get_contents(resource_path('lang/') . $language->code . '.json');
        $jsonArr       = json_decode($data, true);
        $jsonArr[$key] = request('value');

        file_put_contents(resource_path('lang/') . $language->code . '.json', json_encode($jsonArr));

        $toast[] = ['success', 'Language key update success'];

        return back()->withToasts($toast);
    }

    function languageKeyDelete($id) {
        $this->validate(request(), [
            'key'   => 'required',
            'value' => 'required',
        ]);

        $key      = request('key');
        $language = Language::find($id);
        $data     = file_get_contents(resource_path('lang/') . $language->code . '.json');
        $jsonArr  = json_decode($data, true);

        unset($jsonArr[$key]);

        file_put_contents(resource_path('lang/') . $language->code . '.json', json_encode($jsonArr));

        $toast[] = ['success', 'Language key delete success'];

        return back()->withToasts($toast);
    }

    private function getAllFiles($dir) {
        $root = $dir;

        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, \RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        foreach ($iter as $path => $dir) {
            if (!$dir->isDir() && substr($dir, -4) == '.php') {
                $files[] = $path;
            }
        }

        return $files;
    }

    private function getKeys($path) {
        $code      = file_get_contents($path);
        $exp       = explode("')", $code);
        $finalCode = '';

        foreach ($exp as $dd) $finalCode .= $dd . "')";

        preg_match_all("/@lang\(\\'(.*)\\'\)/", $finalCode, $keys);

        return $this->fixMultiIssue($keys[1]);
    }

    private function fixMultiIssue($arr) {
        $res = array();

        foreach ($arr as $keys) {
            $exp = explode("')", $keys);

            foreach ($exp as $child) {
                if (
                    !strpos($child, '@lang') &&
                    !strpos($child, '}') &&
                    !strpos($child, '<') &&
                    !strpos($child, '{') &&
                    !strpos($child, '>')
                ) {
                    $res[] = $child;
                }
            }
        }

        return $res;
    }
}
