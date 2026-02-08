<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\EarningRecord;
use App\Models\FileType;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class AuthorEarningsDashboardController extends Controller
{
    /**
     * Display the author earnings dashboard
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->author_status !== ManageStatus::AUTHOR_APPROVED) {
            $toast[] = ['error', 'Approved authors only'];
            return to_route('user.author.dashboard')->withToasts($toast);
        }

        $pageTitle = 'Author Earnings Dashboard';
        
        // Get initial monthly data
        $initialData = $this->getDashboardData('monthly');
        
        // Get author's content grouped by type
        $contentData = $this->getAuthorContentByType();
        
        return view($this->activeTheme . 'user.author.dashboard', compact('pageTitle', 'initialData', 'contentData'));
    }

    /**
     * Get dashboard data for specified time frame
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        $timeFrame = $request->get('time_frame', 'monthly');
        $data = $this->getDashboardData($timeFrame);
        
        return response()->json($data);
    }

    /**
     * Get dashboard data aggregated by time frame
     *
     * @param string $timeFrame
     * @return array
     */
    private function getDashboardData(string $timeFrame): array
    {
        $user = auth()->user();
        $userId = $user->id;

        // Define date ranges based on time frame
        $dateRange = $this->getDateRange($timeFrame);
        
        // Get earnings data grouped by period
        $earningsData = $this->getEarningsByPeriod($userId, $dateRange, $timeFrame);
        
        // Get downloads count for the period
        $totalDownloads = $this->getDownloadsCount($userId, $dateRange);
        
        // Get total earnings for the period
        $totalEarnings = $this->getTotalEarnings($userId, $dateRange);
        
        // Available earnings (current balance)
        $availableEarnings = $user->balance;

        return [
            'labels' => $earningsData['labels'],
            'earnings' => $earningsData['values'],
            'total_downloads' => $totalDownloads,
            'total_earnings' => $totalEarnings,
            'available_earnings' => $availableEarnings,
            'time_frame' => $timeFrame
        ];
    }

    /**
     * Get date range for the specified time frame
     *
     * @param string $timeFrame
     * @return array
     */
    /**
     * Get date range for the specified time frame
     *
     * @param string $timeFrame
     * @return array
     */
    private function getDateRange(string $timeFrame): array
    {
        $now = Carbon::now();
        
        return match ($timeFrame) {
            'daily' => [
                'start' => $now->copy()->startOfDay(),
                'end'   => $now->copy()->endOfDay()
            ],
            'weekly' => [
                'start' => $now->copy()->startOfWeek(),
                'end'   => $now->copy()->endOfWeek()
            ],
            'monthly' => [
                'start' => $now->copy()->startOfMonth(),
                'end'   => $now->copy()->endOfMonth()
            ],
            'yearly' => [
                'start' => $now->copy()->startOfYear(),
                'end'   => $now->copy()->endOfYear()
            ],
            'lifetime' => [
                'start' => $now->copy()->subYears(5)->startOfYear(), // Last 5 years approx
                'end'   => $now->copy()->endOfYear()
            ],
            default => [
                'start' => $now->copy()->startOfMonth(),
                'end'   => $now->copy()->endOfMonth()
            ]
        };
    }

    /**
     * Get earnings grouped by period
     *
     * @param int $userId
     * @param array $dateRange
     * @param string $timeFrame
     * @return array
     */
    private function getEarningsByPeriod(int $userId, array $dateRange, string $timeFrame): array
    {
        $query = EarningRecord::where('author_id', $userId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);

        // Group by appropriate time period
        switch ($timeFrame) {
            case 'daily':
                // Hourly aggregation
                $query->selectRaw('SUM(amount) as total_amount, HOUR(created_at) as time_group')
                      ->groupBy('time_group');
                break;
                
            case 'weekly':
                // Daily aggregation (Mon, Tue...)
                $query->selectRaw('SUM(amount) as total_amount, DATE(created_at) as time_group')
                      ->groupBy('time_group');
                break;
                
            case 'monthly':
                // Daily aggregation (1, 2, 3...)
                $query->selectRaw('SUM(amount) as total_amount, DAY(created_at) as time_group')
                      ->groupBy('time_group');
                break;
                
            case 'yearly':
                // Monthly aggregation (Jan, Feb...)
                $query->selectRaw('SUM(amount) as total_amount, MONTH(created_at) as time_group')
                      ->groupBy('time_group');
                break;
                
            case 'lifetime':
                // Yearly aggregation
                $query->selectRaw('SUM(amount) as total_amount, YEAR(created_at) as time_group')
                      ->groupBy('time_group');
                break;
                
            default:
                $query->selectRaw('SUM(amount) as total_amount, DATE(created_at) as time_group')
                      ->groupBy('time_group');
        }

        $results = $query->get()->pluck('total_amount', 'time_group');
        
        // Create complete timeline with zero values
        $timeline = $this->createTimeline($dateRange, $timeFrame);
        
        // Merge DB results into timeline (using keys)
        $finalValues = [];
        $finalLabels = [];

        foreach ($timeline as $key => $label) {
            $finalLabels[] = $label;
            // The key in timeline matches the grouping key from DB (hopefully)
            // We need to ensure the DB key format matches the timeline key format
            
            // Adjust mapping based on timeframe logic
            $dbKey = $key; // Default
            
            // For monthly/yearly/daily-hour/lifetime-year, the DB returns Integers (1, 2, 2024 etc).
            // For weekly, we used DATE(), so DB returns '2024-02-06'.
            
            $val = $results[$dbKey] ?? 0;
            $finalValues[] = (float) $val;
        }

        return [
            'labels' => $finalLabels,
            'values' => $finalValues
        ];
    }

    /**
     * Create complete timeline keys and labels
     *
     * @param array $dateRange
     * @param string $timeFrame
     * @return array [DB_KEY => LABEL_TO_SHOW]
     */
    private function createTimeline(array $dateRange, string $timeFrame): array
    {
        $timeline = [];
        $current = $dateRange['start']->copy();
        $end = $dateRange['end'];

        switch ($timeFrame) {
            case 'daily':
                // 00:00 to 23:00
                for ($i = 0; $i < 24; $i++) {
                    // DB returns HOUR() as int 0-23
                    // Label: 12 AM, 1 AM...
                    $label = Carbon::today()->setHour($i)->format('g A');
                    $timeline[$i] = $label; 
                }
                break;
                
            case 'weekly':
                // Mon to Sun
                while ($current->lte($end)) {
                    // DB returns DATE() 'Y-m-d'
                    // Label: Mon, Tue...
                    $dbKey = $current->format('Y-m-d');
                    $label = $current->format('D');
                    $timeline[$dbKey] = $label;
                    $current->addDay();
                }
                break;
                
            case 'monthly':
                // 1 to EndOfMonth
                while ($current->lte($end)) {
                    // DB returns DAY() as int 1-31
                    // Label: 1, 2, 3...
                    $dbKey = $current->day;
                    $label = $current->format('jS');
                    $timeline[$dbKey] = $label;
                    $current->addDay();
                }
                break;
                
            case 'yearly':
                // Jan to Dec
                // DB returns MONTH() as int 1-12
                for ($i = 1; $i <= 12; $i++) {
                    $label = Carbon::create()->month($i)->format('M');
                    $timeline[$i] = $label;
                }
                break;
                
            case 'lifetime':
                // Loop years in range
                while ($current->year <= $end->year) {
                    $year = $current->year;
                    $timeline[$year] = (string)$year;
                    // Move to next year
                     $current->addYear();
                }
                break;
        }

        return $timeline;
    }

    /**
     * Get total downloads count for the period
     *
     * @param int $userId
     * @param array $dateRange
     * @return int
     */
    private function getDownloadsCount(int $userId, array $dateRange): int
    {
        return Download::where('author_id', $userId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
    }

    /**
     * Get total earnings amount for the period
     *
     * @param int $userId
     * @param array $dateRange
     * @return float
     */
    private function getTotalEarnings(int $userId, array $dateRange): float
    {
        return (float) EarningRecord::where('author_id', $userId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('amount');
    }

    /**
     * Get author's content grouped by file type
     *
     * @param Request $request
     * @return JsonResponse|array
     */
    public function getContentByType(Request $request)
    {
        $user = auth()->user();
        
        if ($request->ajax()) {
            $typeId = $request->get('type_id');
            $search = $request->get('search');
            $page = $request->get('page', 1);
            
            $query = Image::where('user_id', $user->id)
                ->where('status', ManageStatus::IMAGE_APPROVED)
                ->with(['fileType', 'category']);
                
            if ($typeId) {
                $query->where('file_type_id', $typeId);
            }
            
            if ($search) {
                $query->where('title', 'LIKE', "%{$search}%");
            }
            
            $content = $query->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'page', $page);
                
            return response()->json([
                'success' => true,
                'content' => $content,
                'type_id' => $typeId
            ]);
        }
        
        return $this->getAuthorContentByType();
    }

    /**
     * Get author's content grouped by type for initial load
     *
     * @return array
     */
    private function getAuthorContentByType()
    {
        $user = auth()->user();
        
        // Get all active file types
        $fileTypes = FileType::active()->orderBy('name')->get();
        
        $contentGroups = [];
        
        foreach ($fileTypes as $fileType) {
            $content = Image::where('user_id', $user->id)
                ->where('file_type_id', $fileType->id)
                ->where('status', ManageStatus::IMAGE_APPROVED)
                ->with(['category'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            $contentGroups[] = [
                'type' => $fileType,
                'content' => $content,
                'total_count' => Image::where('user_id', $user->id)
                    ->where('file_type_id', $fileType->id)
                    ->where('status', ManageStatus::IMAGE_APPROVED)
                    ->count()
            ];
        }
        
        return $contentGroups;
    }

    /**
     * Delete author content
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteContent(Request $request, $id)
    {
        $user = auth()->user();
        
        $content = Image::where('user_id', $user->id)->findOrFail($id);
        
        // Delete associated files
        foreach ($content->imageFiles as $file) {
            if ($file->file_path) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }
        
        $content->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Content deleted successfully'
        ]);
    }

    /**
     * Get file URL for download/view
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getFileUrl($id)
    {
        $user = auth()->user();
        
        $content = Image::where('user_id', $user->id)->findOrFail($id);
        
        $primaryFile = $content->imageFiles()->first();
        
        if (!$primaryFile || !$primaryFile->file_path) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
        
        $fileUrl = Storage::disk('public')->url($primaryFile->file_path);
        
        return response()->json([
            'success' => true,
            'url' => $fileUrl,
            'filename' => $content->title . '.' . pathinfo($primaryFile->file_path, PATHINFO_EXTENSION)
        ]);
    }

    /**
     * Get author's gallery content with Google Photos style grouping
     *
     * @param Request $request
     * @return JsonResponse|array
     */
    public function getGalleryContent(Request $request)
    {
        $user = auth()->user();
        
        if ($request->ajax()) {
            try {
                $typeId = $request->get('type_id');
                $search = $request->get('search');
                $sortBy = $request->get('sort_by', 'created_at');
                $page = $request->get('page', 1);
                
                // Validate sort column to prevent SQL errors
                $allowedSorts = ['created_at', 'title', 'downloads', 'downloads_count'];
                if (!in_array($sortBy, $allowedSorts)) {
                    $sortBy = 'created_at';
                }

                $query = Image::where('user_id', $user->id)
                    ->with(['fileType', 'category', 'imageFiles'])
                    ->withCount('downloads')
                    ->where('status', ManageStatus::IMAGE_APPROVED);
                    
                if ($typeId) {
                    $query->where('file_type_id', $typeId);
                }
                
                if ($search) {
                    $query->where('title', 'LIKE', "%{$search}%");
                }

                if ($sortBy === 'downloads') {
                    $sortBy = 'downloads_count';
                }
                
                // Group by date for Google Photos style layout
                $content = $query->orderBy($sortBy, 'desc')
                    ->paginate(24, ['*'], 'page', $page); // 24 items per page for grid layout
                    
                return response()->json([
                    'success' => true,
                    'content' => $content,
                    'type_id' => $typeId,
                    'grouped_content' => $this->groupContentByDate($content->items())
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gallery API Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading gallery content'
                ], 500);
            }
        }
        
        return $this->getAuthorContentByType();
    }

    /**
     * Group content by date for gallery display
     *
     * @param array $contents
     * @return array
     */
    private function groupContentByDate($contents)
    {
        $grouped = [];
        
        foreach ($contents as $content) {
            $created = $content->created_at ?? now();
            $date = $created->format('Y-m-d');
            $displayDate = $created->format('F j, Y');
            
            if (!isset($grouped[$date])) {
                $grouped[$date] = [
                    'date' => $displayDate,
                    'items' => []
                ];
            }
            
            $grouped[$date]['items'][] = $content;
        }
        
        return array_values($grouped);
    }

    /**
     * Get gallery filters and statistics
     *
     * @return JsonResponse
     */
    public function getGalleryFilters()
    {
        $user = auth()->user();
        
        // Get file types with counts
        $fileTypes = FileType::active()
            ->withCount(['images' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', ManageStatus::IMAGE_APPROVED);
            }])
            ->orderBy('name')
            ->get()
            ->filter(function($type) {
                return $type->images_count > 0;
            });
            
        // Get total counts
        $totalCount = Image::where('user_id', $user->id)
            ->where('status', ManageStatus::IMAGE_APPROVED)
            ->count();
            
        return response()->json([
            'success' => true,
            'file_types' => $fileTypes->values(), // Convert to plain array with numeric keys
            'total_count' => $totalCount
        ]);
    }
}