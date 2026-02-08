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
        $typeId = $request->get('type_id');
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'created_at');
        $page = $request->get('page', 1);
        
        $query = Image::where('user_id', $user->id)
            ->with(['fileType', 'category', 'imageFiles'])
            ->where('status', ManageStatus::IMAGE_APPROVED);
            
        if ($typeId) {
            $query->where('file_type_id', $typeId);
        }
        
        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
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
        $date = $content->created_at->format('Y-m-d');
        $displayDate = $content->created_at->format('F j, Y');
        
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
        'file_types' => $fileTypes,
        'total_count' => $totalCount
    ]);
}