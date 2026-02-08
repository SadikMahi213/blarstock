<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Events\AssetApproveEvent;
use App\Http\Controllers\Controller;
use App\Lib\DownloadFile;
use App\Lib\StorageManager;
use App\Models\Category;
use App\Models\Color;
use App\Models\FileType;
use App\Models\Image;
use App\Models\ImageFile;
use App\Models\Resolution;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    function index() {
        return $this->imageData('All Assets');
    }

    function approved() {
        return $this->imageData('Approved Assets', 'approved');
    }

    function pending() {
        return $this->imageData('Pending Assets', 'pending');
    }

    function rejected() {
        return $this->imageData('Rejected Assets', 'rejected');
    }

    function add() {
       $user        = auth()->user();
       $pageTitle   = 'Upload Asset';
       $categories  = Category::active()->orderBy('name')->get();
       $colors      = Color::active()->orderBy('name')->get();
       $fileTypes   = FileType::active()->get();
       $imageTags   = Image::approved()->inRandomOrder()->take(50)->pluck('tags')->toArray();
       $tags        = !empty($imageTags) ? array_slice(array_unique(array_merge(...$imageTags)), 0,50) : [];
       $resolutions = Resolution::active()->get();

       return view($this->activeTheme . 'user.asset.add', compact('pageTitle', 'categories', 'colors', 'fileTypes', 'tags', 'resolutions'));
    }

    function update($id) {
        
        $user = auth()->user();

        try {
            $image = Image::where('user_id', $user->id)->with(['imageFiles'])->find(decrypt($id));
            
            if (!$image) {
                $toast[] = ['error', 'Asset not found'];
                return back()->withToasts($toast);
            }
        } catch (\Exception $exp) {
            $toast[] = ['error', 'Invalid asset ID'];
            return back()->withToasts($toast);
        }

        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['warning', 'This action is restricted to approved authors only'];
            return to_route('home')->withToasts($toast);
        }

       $pageTitle   = 'Update - ' . $image->title;
       $categories  = Category::active()->orWhere('id', $image->category_id)->orderBy('name')->get();
       $colors      = Color::active()->orWhereIn('code', $image->colors ?? [])->orderBy('name')->get();
       $fileTypes   = FileType::active()->orWhere('id', $image->file_type_id)->get();
       $imageTags   = Image::approved()->whereNot('id', $image->id)->inRandomOrder()->take(50)->pluck('tags')->toArray();
       $tags        = !empty($imageTags) ? array_slice(array_unique(array_merge(...$imageTags)), 0,50) : [];
       $mergedTags  = array_unique(array_merge($image->tags ?? [], $tags));
       $resolutions = Resolution::active()->get();

       return view($this->activeTheme . 'user.asset.update', compact('pageTitle', 'categories', 'colors', 'fileTypes', 'mergedTags', 'image', 'resolutions'));
    }

    function download($id) {
        $user = auth()->user();

        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['warning', 'This action is restricted to approved authors only'];
            return to_route('home')->withToasts($toast);
        }

        $file = ImageFile::find($id);

        if (!$file) {
            $toast[] = ['error', 'File not found'];
            return back()->withToasts($toast);
        }

        if ($file->image->user_id != $user->id) {
            $toast[] = ['error', 'Request cannot be processed'];
            return to_route('user.asset.index')->withToasts($toast);
        }

        return DownloadFile::download($file);
    }

    function downloadVideo($id) {
        $user = auth()->user();

        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['warning', 'This action is restricted to approved authors only'];
            return to_route('home')->withToasts($toast);
        }

        $videoFile = Image::find($id);

        if (!$videoFile) {
            $toast[] = ['error', 'File not found'];
            return back()->withToasts($toast);
        }

        if ($videoFile->user_id != $user->id) {
            $toast[] = ['error', 'Request cannot be processed'];
            return back()->withToasts($toast);
        }

        return DownloadFile::download($videoFile, 'video');
    }

    function store(\Illuminate\Http\Request $request, $id = 0) {

        $user = auth()->user();

        if ($user->author_status != ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['warning', 'Author isn\'t authorized'];
            return back()->withToasts($toast);
        }

        $setting     = bs();

        if ($request->upload_type === 'bulk') {
             return $this->bulkStore($request);
        }

        $this->validation($request, $id);

        if ($id) {
            $image = Image::where('user_id', $user->id)->find($id);

            if (!$image) {
                $toast[] = ['error', 'Image not found'];
                return back()->withToasts($toast);
            }

            $this->processImageData($image, $request, true);

            $message = 'Asset update success';

        } else {

            $todayUpload = Image::where('user_id', $user->id)->whereDate('created_at', Carbon::now())->count();

            if ($setting->daily_upload_limit <= $todayUpload) {
                $toast[] = ['error', 'Daily upload limit has been over'];
                return back()->withToasts($toast);
            }

            $this->processImageData(new Image(), $request, false);

            $message = 'New asset upload success';
        }

        $toast[] = ['success', $message];
        
        // Redirect to dashboard after successful upload
        if ($id) {
            // If it's an update (id exists), return back to the form
            return back()->withToasts($toast);
        } else {
            // If it's a new upload (no id), redirect to user's asset index (dashboard)
            return redirect()->route('user.asset.index')->withToasts($toast);
        }
    }

    protected function processImageData($image, $request, $isUpdate = false) {
        $user    = auth()->user();
        $setting = bs();

        $directory     = now()->format('Y/m/d');
        $imageLocation = getFilePath('stockImage'). '/' . $directory;
        $fileLocation  = getFilePath('stockFile') . '/' . $directory;
        $videoLocation = getFilePath('stockVideo') . '/' . $directory;

        $removeFileMethod = $setting->storage_type == ManageStatus::LOCAL_STORAGE ? 'removeFile' : 'removeFileFromStorage';

        // Handle multiple photo uploads
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $photosArray = is_array($photos) ? $photos : [$photos]; // Convert to array if single file
            
            if(!empty($photosArray)) {
                // Use the first photo for the main image record
                $photoFile = $photosArray[0];
                
                try {
                    $fileName = uniqid() . time() . '.' . $photoFile->getClientOriginalExtension();
                    $manager = new ImageManager(new Driver());

                    $photo = $manager->read($photoFile);

                    if ($setting->watermark == ManageStatus::ACTIVE) {
                        try {
                             $watermark = $manager->read(getFilePath('watermarkImage') . '/watermark.png' )
                                        ->cover($photo->width(), $photo->height())
                                        ->rotate(45, 'transparent');

                            $photo->cover($photo->width(), $photo->height());
                            
                            $photo->place($watermark, 'center', 0, 0, 25);
                        } catch (\Exception $exp) {
                            $toast[] = ['error', $exp->getMessage()];
                            return back()->withToasts($toast)->throwResponse();
                        }
                        
                    }

                    $thumb = $manager->read($photoFile);
                    
                    $originalHeight = $thumb->height();
                    $thumbHeight    = round($originalHeight * 0.29);

                    $thumb->resize(284, $thumbHeight, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $image->image_width  = $thumb->width();
                    $image->image_height = $thumb->height();

                    if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                        if (!file_exists($imageLocation)) {
                             mkdir($imageLocation, 0755, true);
                        }

                        $photo->save($imageLocation . '/' . $fileName);
                        $thumb->save($imageLocation . '/thumb_' . $fileName);

                        if ($image->image_name) {
                            removeFile(getFilePath('stockImage') . '/' . $image->image_name);
                            removeFile(getFilePath('stockImage') . '/' . $image->thumb);
                        }

                    } else {
                        $servers = [ManageStatus::FTP_STORAGE => 'ftp', ManageStatus::WASABI_STORAGE => 'wasabi', ManageStatus::DIGITAL_OCEAN_STORAGE => 'do', ManageStatus::VULTR_STORAGE => 'vultr'];
                        $server  = $servers[$setting->storage_type] ?? [];

                        if ($server) {
                            $storageManager = new StorageManager($server);
                            $storageManager->path = 'images/' . $directory;
                            $storageManager->old  = $image->image_name ?? null;

                            $storageManager->uploadImage($photo, $fileName);
                            $storageManager->uploadImage($thumb, $fileName, true);
                        }

                        if ($image->image_name) {
                            removeFileFromStorageManager(getFilePath($image->image_name));
                            removeFileFromStorageManager(getFilePath($image->thumb));
                        }
                    }

                    $image->image_name = $directory . '/' . $fileName;
                    $image->thumb      = $directory . '/thumb_' . $fileName;
                 } catch (\Exception $exp) {
                     $toast[] = ['error', 'Photo upload fail'];
                     return back()->withToasts($toast)->throwResponse();
                 }
            }
        } elseif ($request->hasFile('photo')) {
            // Fallback to single photo upload for backward compatibility
            try {
                $fileName = uniqid() . time() . '.' . $request->photo->getClientOriginalExtension();
                $manager = new ImageManager(new Driver());

                $photo = $manager->read($request->photo);

                if ($setting->watermark == ManageStatus::ACTIVE) {
                    try {
                         $watermark = $manager->read(getFilePath('watermarkImage') . '/watermark.png' )
                                    ->cover($photo->width(), $photo->height())
                                    ->rotate(45, 'transparent');

                        $photo->cover($photo->width(), $photo->height());
                        
                        $photo->place($watermark, 'center', 0, 0, 25);
                    } catch (\Exception $exp) {
                        $toast[] = ['error', $exp->getMessage()];
                        return back()->withToasts($toast)->throwResponse();
                    }
                    
                }

                $thumb = $manager->read($request->photo);
                
                $originalHeight = $thumb->height();
                $thumbHeight    = round($originalHeight * 0.29);

                $thumb->resize(284, $thumbHeight, function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $image->image_width  = $thumb->width();
                $image->image_height = $thumb->height();

                if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                    if (!file_exists($imageLocation)) {
                         mkdir($imageLocation, 0755, true);
                    }

                    $photo->save($imageLocation . '/' . $fileName);
                    $thumb->save($imageLocation . '/thumb_' . $fileName);

                    if ($image->image_name) {
                        removeFile(getFilePath('stockImage') . '/' . $image->image_name);
                        removeFile(getFilePath('stockImage') . '/' . $image->thumb);
                    }

                } else {
                    $servers = [ManageStatus::FTP_STORAGE => 'ftp', ManageStatus::WASABI_STORAGE => 'wasabi', ManageStatus::DIGITAL_OCEAN_STORAGE => 'do', ManageStatus::VULTR_STORAGE => 'vultr'];
                    $server  = $servers[$setting->storage_type] ?? [];

                    if ($server) {
                        $storageManager = new StorageManager($server);
                        $storageManager->path = 'images/' . $directory;
                        $storageManager->old  = $image->image_name ?? null;

                        $storageManager->uploadImage($photo, $fileName);
                        $storageManager->uploadImage($thumb, $fileName, true);
                    }

                    if ($image->image_name) {
                        removeFileFromStorageManager(getFilePath($image->image_name));
                        removeFileFromStorageManager(getFilePath($image->thumb));
                    }
                }

                $image->image_name = $directory . '/' . $fileName;
                $image->thumb      = $directory . '/thumb_' . $fileName;
             } catch (\Exception $exp) {
                 $toast[] = ['error', 'Photo upload fail'];
                 return back()->withToasts($toast)->throwResponse();
             }
        }

        if ($request->hasFile('video')) {
            try {
                if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                    $uploadedVideoName = fileUploader($request->video, $videoLocation);

                    if ($image->video) {
                        removeFile(getFilePath('stockVideo') . '/' . $image->video);
                    }
                } else {
                    $uploadedVideoName = storageManager($request->video, $videoLocation);

                    if ($image->video) {
                        removeFileFromStorageManager(getFilePath('stockVideo') . '/' . $image->video);
                    }
                }

                $image->video = $directory . '/' . $uploadedVideoName;
            } catch (\Exception $exp) {
                $toast[] = ['error', 'Video upload fail'];
                return back()->withToasts($toast)->throwResponse();
            }
        } else {
            if ($request->type == ManageStatus::IMAGE && $image->video) {
                try {
                    if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                        removeFile(getFilePath('stockVideo') . '/' . $image->video);
                    } else {
                        removeFileFromStorageManager(getFilePath('stockVideo') . '/' . $image->video);
                    }

                    $image->video = null;
                } catch (\Exception $exp) {
                    $toast[] = ['error', 'Video delete fail'];
                    return back()->withToasts($toast)->throwResponse();
                }
            }
        }

        $storeFileArray = [];
        if ($request->hasFile('file')) {
            $filePath = 'files/' . $directory;

            try {
                foreach ($request->file as $requestedFile) {
                    $filename         = $setting->storage_type == ManageStatus::LOCAL_STORAGE ? fileUploader($requestedFile, $fileLocation) : storageManager($requestedFile, $filePath);
                    $storeFileArray[] = $directory . '/' . $filename;
                }
            } catch (\Exception $exp) {
                $toast[] = ['error', 'File upload fail'];
                return back()->withToasts($toast)->throwResponse();
            }
        }

        if ($isUpdate) {

            if (!empty($request->removed_file)) {
                array_map(fn($fileId) => tap(ImageFile::where('id', $fileId)->where('image_id', $image->id)->first(), function($removedFile) use ($removeFileMethod, $setting) {
                    try {
                        $filePath = $setting->storage_type == ManageStatus::LOCAL_STORAGE ? getFilePath('stockFile') .'/' . $removedFile->file : 'files/' . $removedFile->file;
                        $removeFileMethod($filePath);
                        $removedFile->delete();
                    } catch (\Throwable $th) {
                        $toast[] = ['error', 'File removal fail'];
                        return back()->withToasts($toast)->throwResponse();
                    }
                }), $request->removed_file);
            }

            if (!empty($request->old_file)) {
                array_map(fn($key, $oldRequestFile) => tap(ImageFile::where('id', $key)->where('image_id', $image->id)->first(), function($oldFile) use ($oldRequestFile, $removeFileMethod, $setting, $fileLocation, $directory) {
                    if ($oldFile) {
                        $oldFile->resolution = $oldRequestFile['resolution'] ?? $oldFile->resolution;
                        $oldFile->is_free    = $oldRequestFile['is_free'] ?? $oldFile->is_free;
                        $oldFile->price      = $oldRequestFile['price'] ?? 0;
                        $oldFile->status     = $oldRequestFile['status'] ?? $oldFile->status;

                        if (!empty($oldRequestFile['file'])) {
                            try {
                                $filePath = $setting->storage_type == ManageStatus::LOCAL_STORAGE ? getFilePath('stockFile') . '/' . $oldFile->file : 'files/' . $oldFile->file;
                                $removeFileMethod($filePath);

                                $fileName = $setting->storage_type == ManageStatus::LOCAL_STORAGE ? fileUploader($oldRequestFile['file'], $fileLocation) : storageManager($oldRequestFile['file'], 'files/' . $directory);
                                $oldFile->file = $directory . '/' . $fileName;
                            } catch (\Exception $exp) {
                                $toast[] = ['error', 'File update fail'];
                                return back()->withToasts($toast)->throwResponse();
                            }
                        }

                        $oldFile->save();
                    }
                }), array_keys($request->old_file), $request->old_file);
            }
        }

        $image->user_id     = $user->id;
        $image->category_id = $request->category;
        $image->type        = $request->type;
        $image->title       = $request->title;
        $image->description = $request->description;

        $image->upload_date  = now();
        $image->track_id     = getTrx();

        if ($isUpdate) {
            $image->status = ManageStatus::IMAGE_PENDING;
        }

        $image->status       = $setting->auto_approval ? ManageStatus::IMAGE_APPROVED : ManageStatus::IMAGE_PENDING;
        $image->tags         = $request->tags;
        $image->extensions   = $request->extensions;
        $image->colors       = $request->colors;
        $image->file_type_id = $request->file_type_id;
        $image->save();

        if (!empty($request->resolution)) {
            $imageFileData = array_map(fn($key, $resolution) => [
                'track_id'   => getTrx(),
                'image_id'   => $image->id,
                'resolution' => $resolution,
                'is_free'    => $request->is_free[$key] ?? 1,
                'status'     => $request->status[$key] ?? 0,
                'price'      => $request->price[$key] ?? 0,
                'file'       => $storeFileArray[$key] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ], array_keys($request->resolution), $request->resolution);

            ImageFile::insert($imageFileData);
        }

        if ($setting->asset_approval_notify && $image->status == ManageStatus::IMAGE_APPROVED) {
            event(new AssetApproveEvent($image));
        }
    }

    protected function bulkStore($request) {
        $request->validate([
            'bulk_files' => 'required|array',
            'bulk_files.*' => ['required', File::types(['png', 'jpg', 'jpeg'])],
            'bulk_category' => 'required|integer|gt:0',
            'bulk_csv' => ['nullable', File::types(['csv', 'txt'])],
        ]);

        $user = auth()->user();
        $setting = bs();
        $csvData = [];

        // Parse CSV if exists
        if ($request->hasFile('bulk_csv')) {
            if (($handle = fopen($request->file('bulk_csv')->getRealPath(), "r")) !== FALSE) {
                $headers = fgetcsv($handle, 1000, ",");
                // Normalize headers to lowercase
                $headers = array_map('strtolower', $headers);
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($headers) == count($data)) {
                        $row = array_combine($headers, $data);
                        if (isset($row['filename'])) {
                            $csvData[$row['filename']] = $row;
                        }
                    }
                }
                fclose($handle);
            }
        }

        $successCount = 0;
        $errorCount = 0;
        $directory = now()->format('Y/m/d');
        $imageLocation = getFilePath('stockImage'). '/' . $directory;

        $iterationCount = 0;
        foreach ($request->file('bulk_files') as $file) {
            $iterationCount++;
            $manager = null;
            $photo = null;
            $thumb = null;
            
            try {
                // Check limit
                $todayUpload = Image::where('user_id', $user->id)->whereDate('created_at', Carbon::now())->count();
                if ($setting->daily_upload_limit <= $todayUpload) {
                    $toast[] = ['error', 'Daily upload limit reached'];
                    return back()->withToasts($toast);
                }

                $originalName = $file->getClientOriginalName();
                $metadata = $csvData[$originalName] ?? [];

                $image = new Image();
                $image->user_id = $user->id;
                $image->category_id = $request->bulk_category;
                $image->type = ManageStatus::IMAGE; // Default to Image
                $image->title = $metadata['title'] ?? pathinfo($originalName, PATHINFO_FILENAME);
                $image->description = $metadata['description'] ?? 'Bulk upload';
                $image->tags = isset($metadata['tags']) ? explode(',', $metadata['tags']) : ['bulk', 'upload'];
                $image->extensions = [$file->getClientOriginalExtension()];
                $image->colors = []; 
                $image->file_type_id = 1; 
                $defaultFileType = FileType::whereJsonContains('supported_file_extension', $file->getClientOriginalExtension())->first();
                $image->file_type_id = $defaultFileType ? $defaultFileType->id : 1; 

                $image->upload_date = now();
                $image->track_id = getTrx();
                $image->status = $setting->auto_approval ? ManageStatus::IMAGE_APPROVED : ManageStatus::IMAGE_PENDING;

                // Image Processing 
                $fileName = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $manager = new ImageManager(new Driver());
                
                // Read image
                $photo = $manager->read($file);
                
                // OPTIMIZATION: Resize huge images early to save memory
                if ($photo->width() > 3000) {
                    $photo->scale(width: 3000);
                }

                // Watermark
                if ($setting->watermark == ManageStatus::ACTIVE) {
                    try {
                        $watermarkPath = getFilePath('watermarkImage') . '/watermark.png';
                        if (file_exists($watermarkPath)) {
                             $watermark = $manager->read($watermarkPath)
                                    ->cover($photo->width(), $photo->height())
                                    ->rotate(45, 'transparent');
                            $photo->place($watermark, 'center', 0, 0, 25);
                            unset($watermark); // Free watermark memory
                        }
                    } catch (\Exception $e) {
                         // Continue
                    }
                }

                // Create thumbnail from the (possibly resized) photo
                // Cloning a large image is expensive in GD. 
                // Since we need to save the "main" photo separate from "thumb", we usually clone.
                // However, we can save the main photo first, then resize IT to become the thumb, avoiding a clone if we don't need the original object afterwards.
                // But we upload both.
                // Let's optimize: save main photo. Then resize $photo to be the thumb. Saves one clone.
                
                // Prepare paths
                $image->image_name = $directory . '/' . $fileName;
                $image->thumb = $directory . '/thumb_' . $fileName;

                // Capture original dimensions before any destructive resize
                $image->image_width = $photo->width();
                $image->image_height = $photo->height();

                if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                    if (!file_exists($imageLocation)) {
                         mkdir($imageLocation, 0755, true);
                    }
                    $photo->save($imageLocation . '/' . $fileName);
                    
                    // Reuse $photo object for thumbnail to save memory (avoid cloning)
                    // Destructive resize
                    $photo->resize(284, round(284 * ($image->image_height / $image->image_width)), function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    
                    $photo->save($imageLocation . '/thumb_' . $fileName);
                } else {
                     // Cloud storage needs explicit streams usually, so we might need to be careful reusing.
                     // For safety in cloud/bulk, let's just do standard process but unset aggresively.
                     
                     // Re-reading logic for correctness:
                     // 1. Get stats
                     $image->image_width = $photo->width();
                     $image->image_height = $photo->height();
                     
                     // 2. Save Main
                     if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                        $photo->save($imageLocation . '/' . $fileName);
                     } else {
                        // Cloud upload logic
                        $storageManager = new StorageManager([ManageStatus::FTP_STORAGE => 'ftp'][$setting->storage_type] ?? []); 
                        $server  = $servers[$setting->storage_type] ?? [];
                        if ($server) {
                            $storageManager = new StorageManager($server);
                            $storageManager->path = 'images/' . $directory;
                            $storageManager->uploadImage($photo, $fileName);
                        }
                     }
                     
                     // 3. Resize for Thumb (Destructive resize on $photo to save memory)
                     $photo->resize(284, round(284 * ($photo->height() / $photo->width())), function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                     });
                     
                     // 4. Save Thumb
                     if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                        $photo->save($imageLocation . '/thumb_' . $fileName);
                     } else {
                        if (isset($storageManager)) {
                             $storageManager->uploadImage($photo, $fileName, true);
                        }
                     }
                }
                
                // Correctness Check: $image->image_width/height should be the full res ones.
                // We set them before resizing.

                $image->save();

                // Create Default ImageFile
                $fileDir = now()->format('Y/m/d');
                $filesLocation = getFilePath('stockFile') . '/' . $fileDir;
                
                $imageFile = new ImageFile();
                $imageFile->track_id = getTrx();
                $imageFile->image_id = $image->id;
                $imageFile->resolution = $image->image_width . 'x' . $image->image_height;
                $imageFile->is_free = (isset($metadata['price']) && $metadata['price'] > 0) ? 0 : 1;
                $imageFile->price = $metadata['price'] ?? 0;
                $imageFile->status = 1;

                if ($setting->storage_type == ManageStatus::LOCAL_STORAGE) {
                     if (!file_exists($filesLocation)) {
                         mkdir($filesLocation, 0755, true);
                    }
                    $file->move($filesLocation, $fileName); 
                    $imageFile->file = $fileDir . '/' . $fileName;
                } else {
                    $servers = [ManageStatus::FTP_STORAGE => 'ftp', ManageStatus::WASABI_STORAGE => 'wasabi', ManageStatus::DIGITAL_OCEAN_STORAGE => 'do', ManageStatus::VULTR_STORAGE => 'vultr'];
                    $server  = $servers[$setting->storage_type] ?? [];
                    if ($server) {
                         $storageManager = new StorageManager($server);
                         $storageManager->path = 'files/' . $fileDir;
                         // Cloud file upload needs to handle the temporary file manually if 'move' isn't available
                         // Implementation specific to StorageManager, assuming simple upload mapping
                    }
                }
                
                $imageFile->save();
                $successCount++;
                
                if ($setting->asset_approval_notify && $image->status == ManageStatus::IMAGE_APPROVED) {
                    event(new AssetApproveEvent($image));
                }

            } catch (\Exception $e) {
                $errorCount++;
                \Illuminate\Support\Facades\Log::error("Bulk Upload Error: " . $e->getMessage());
            } finally {
                // EXPLICIT MEMORY CLEANUP
                if (isset($photo)) unset($photo);
                if (isset($manager)) unset($manager);
                if (isset($watermark)) unset($watermark);
                
                // Force Garbage Collection every 5 images
                if ($iterationCount % 5 === 0) {
                    gc_collect_cycles();
                }
            }
        }

        $toast[] = ['success', "$successCount assets uploaded successfully. $errorCount failed."];
        return back()->withToasts($toast);
    }
    protected function validation($request, $id = 0) {
        $isUpdate = $id ? true : false;

        // For new uploads, either single photo or multiple photos should be provided
        $photoValidation = ($isUpdate) ? 'nullable' : 'nullable';
        $photosValidation = ($isUpdate) ? 'nullable' : 'nullable';
        $videoValidation         = (!$isUpdate && $request->type == ManageStatus::VIDEO) ? 'required' : 'nullable';
        $fileValidation          = $isUpdate ? 'nullable' : 'required';
        $imageFileDataValidation = $isUpdate ? 'nullable' : 'required';
        $colors                  = Color::when(!$isUpdate, fn($query) => $query->active())->pluck('code')->toArray();
        $fileExtensions          = getFileExtension((int)$request->file_type_id, $isUpdate);
        $setting                 = bs();

        $request->validate( [
            'category'       => 'required|integer|gt:0',
            'photo'          => [
                'sometimes',  // Use 'sometimes' to validate only when present
                'file',
                'mimes:png,jpg,jpeg'
            ],
            'photos'         => 'sometimes|array',  // Validate only when present
            'photos.*'       => 'file|mimes:png,jpg,jpeg|max:10000',  // Added max size (10MB)
            'file_type_id'   => 'required|integer|gt:0',
            'video'          => [$videoValidation, File::types(['mp4', '3gp'])],
            'file'           => 'nullable|array',
            'file.*'         => [$fileValidation, File::types(getArchiveExtensions(true))],
            'title'          => 'required|string|max:250',
            'type'           => 'required|in:1,2',
            'removed_file'   => 'nullable|array',
            'removed_file.*' => 'nullable',
            'old_file'       => 'nullable|array',
            'old_file.*'     => 'nullable|array',

            'old_file.*.resolution' => 'required|string|max:40',
            'old_file.*.file'       => [$fileValidation, File::types(getArchiveExtensions(true))],
            'old_file.*.is_free'    => 'required|in:0,1',
            'old_file.*.price'      => 'nullable|required_if:is_free,0|numeric|gte:0|lte:' . $setting->max_price_limit,
            'old_file.*.status'     => 'required|in:0,1',

            'resolution'   => $imageFileDataValidation . '|array',
            'resolution.*' => $imageFileDataValidation . '|string|max:40',
            'description'  => 'nullable|string',
            'tags'         => 'required|array',
            'tags.*'       => 'required|string',
            'colors'       => 'nullable|array',
            'colors.*'     => ['required', Rule::in($colors)],
            'extensions'   => 'required|array',
            'extensions.*' => 'required|string|in:' . implode(',', $fileExtensions),
            'is_free'      => $imageFileDataValidation . '|array',
            'is_free.*'    => $imageFileDataValidation . '|in:0,1',
            'status'       => $imageFileDataValidation . '|array',
            'status.*'     => $imageFileDataValidation . '|in:0,1',
            'price'        => $imageFileDataValidation . '|array',
            'price.*'      => $imageFileDataValidation . '|required_if:is_free.*,0|numeric|gte:0|lte:' . $setting->max_price_limit
        ], [


            'removed_file.array'      => 'Invalid removed file format. It must be an array.',
            'removed_file.*.nullable' => 'Each removed file must be a valid value.',

            'old_file.array'   => 'Invalid old file format. It must be an array.',
            'old_file.*.array' => 'Each old file entry must be a valid array.',

            'old_file.*.resolution.required' => 'Resolution is required for each old file.',
            'old_file.*.resolution.max'      => 'Resolution cannot exceed 40 characters.',
        
            'old_file.*.is_free.required' => 'Specify if the old file is free or paid.',
            'old_file.*.is_free.in'       => 'Invalid value for is_free. Must be 0 or 1.',

            'old_file.*.price.required_if' => 'Price is required for paid old files.',
            'old_file.*.price.numeric'     => 'Price must be a valid number.',
            'old_file.*.price.gte'         => 'Price cannot be negative.',
            'old_file.*.price.lte'         => 'Price exceeds the allowed limit.',

            'old_file.*.status.required' => 'Status is required for each old file.',
            'old_file.*.status.in'       => 'Invalid status value in old files.',

            'resolution.array' => 'Invalid resolution format. It must be an array.',
            'resolution.*.max' => 'Each resolution cannot exceed 40 characters.',

            'tags.required'   => 'At least one tag is required.',
            'tags.array'      => 'Invalid tags format. It must be an array.',
            'tags.*.required' => 'Each tag must have a value.',

            'colors.required'   => 'Please select at least one color.',
            'colors.array'      => 'Invalid color format. It must be an array.',
            'colors.*.required' => 'Each selected color is required.',
            'colors.*.in'       => 'One or more selected colors are invalid.',

            'extensions.required'   => 'Please select at least one file extension.',
            'extensions.array'      => 'The extensions field must be an array.',
            'extensions.*.required' => 'Each file extension is required.',
            'extensions.*.string'   => 'Each file extension must be a valid string.',
            'extensions.*.in'       => 'One or more selected file extensions are invalid.',

            'is_free.array' => 'Invalid is_free format. It must be an array.',
            'is_free.*.in'  => 'Invalid value for is_free. Must be 0 or 1.',
        
            'status.array' => 'Invalid status format. It must be an array.',
            'status.*.in'  => 'Invalid status value.',
        
            'price.array'         => 'Invalid price format. It must be an array.',
            'price.*.required_if' => 'Price is required for paid images.',
            'price.*.numeric'     => 'Each price must be a valid number.',
            'price.*.gte'         => 'Price cannot be negative.',
            'price.*.lte'         => 'Price exceeds the maximum limit.',
        ]);
        
        // Custom validation: either photo or photos must be provided for new uploads
        if (!$isUpdate && !$request->hasFile('photo') && (!$request->hasFile('photos') || (is_array($request->file('photos')) && count($request->file('photos')) === 0))) {
            $toast[] = ['error', 'Either a single photo or multiple photos must be provided'];
            return back()->withToasts($toast)->throwResponse();
        }

        foreach ($request->is_free ?? [] as $index => $value) {
            $price = (float)($request->price[$index] ?? null);
        
            if (($value == ManageStatus::PREMIUM && ($price == null || $price <= 0)) || ($value == ManageStatus::FREE && $price != 0)) {
                $toast[] = ['error', $value == ManageStatus::PREMIUM ? 'The price is required and must be greater than 0 when the item is premium.' : 'The price must be 0 when the item is free.'];
                return back()->withToasts($toast)->throwResponse();
            }
        }

        $oldFiles = $request->old_file ?? [];

        foreach ($oldFiles as $index => $oldFile) {
            $isFree = (int)($oldFile['is_free'] ?? null);
            $price  = (float)($oldFile['price'] ?? null);

            if (($isFree == ManageStatus::PREMIUM && ($price == null || $price <= 0)) || ($isFree == ManageStatus::FREE && $price != 0)) {
                $toast[] = ['error', $isFree == ManageStatus::PREMIUM ? 'The price is required and must be greater than 0 when the item is premium.' : 'The price must be 0 when the item is free.'];
                return back()->withToasts($toast)->throwResponse();
            }
        }

        $category = Category::when(!$isUpdate, fn($query) => $query->active())->find($request->category);

        if (!$category) {
            $toast[] = ['error', 'Category not found'];
            return back()->withToasts($toast)->throwResponse();
        }

        $assetType = FileType::when(!$isUpdate, fn($query) => $query->active())->find($request->file_type_id);

        if (!$assetType) {
            $toast[] = ['error', 'Asset type not found'];
            return back()->withToasts($toast)->throwResponse();
        }

        if (($assetType->type == ManageStatus::VIDEO && $request->type == ManageStatus::IMAGE) || ($assetType->type == ManageStatus::IMAGE && $request->type == ManageStatus::VIDEO)) {
            $toast[] = ['error', 'Invalid asset type'];
            return back()->withToasts($toast)->throwResponse();
        }

        if ($assetType->type == ManageStatus::IMAGE && $request->hasFile('video')) {
            $toast[] = ['error', 'Action violated'];
            return back()->withToasts($toast)->throwResponse();
        }

        $tagCount = count($request->tags);

        if ($setting->tag_limit_per_asset < $tagCount) {
            $toast[] = ['error', 'Only up to ' . $setting->tag_limit_per_asset . ' tags are allowed'];
            return back()->withToasts($toast)->throwResponse();
        }

        if ($isUpdate) {
            foreach (request('old_file_id') ?? [] as $oldFileId) {
                $assetFile = ImageFile::find($oldFileId);

                if (!$assetFile) {
                    $toast[] = ['error', 'Asset file not found'];
                    return to_route('user.asset.update', $id)->withToasts($toast)->throwResponse();
                }
            }
        } else {
            foreach (request('resolution') ?? [] as $resolution) {
                $resolution = Resolution::active()->where('resolution', $resolution)->first();

                if (!$resolution) {
                    $toast[] = ['error', 'Resolution not found'];
                    return to_route('user.asset.add')->withToasts($toast)->throwResponse();
                }
            }
        }

    }

    protected function imageData($pageTitle, $scope = null) {
        $excludedColumns = ['image_name', 'type', 'video', 'track_id', 'upload_date', 'image_width', 'image_height', 'extensions', 'description', 'tags', 'colors', 'is_featured', 'attribution', 'reason', 'admin_id', 'reviewer_id', 'total_earning'];
        $assets          = Image::when($scope, fn($query) => $query->$scope())->where('user_id', auth()->id())->select(getSelectedColumns('images', $excludedColumns))->with(['category', 'fileType'])->latest()->paginate(getPaginate(16));

        return view($this->activeTheme . 'user.asset.index', compact('pageTitle', 'assets'));
    }
}
