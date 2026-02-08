<?php

namespace Database\Seeders;

use App\Constants\ManageStatus;
use App\Models\Download;
use App\Models\EarningRecord;
use App\Models\Image;
use App\Models\ImageFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardMetricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first approved author or fallback to admin/first user
        $author = User::where('author_status', ManageStatus::AUTHOR_APPROVED)->first();

        if (!$author) {
            $this->command->info('No approved author found. Creating one or using generic user...');
            // Fallback to first user and make them an author for testing
            $author = User::first();
            if ($author) {
                $author->author_status = ManageStatus::AUTHOR_APPROVED;
                $author->save();
            } else {
                 $this->command->error('No users found in database.');
                 return;
            }
        }

        $this->command->info("Seeding data for Author ID: {$author->id} ({$author->username})");

        // Prepare time ranges
        $now = Carbon::now();
        
        // 1. Seed DAILY metrics (Hourly for today)
        // Need entries for today at different hours
        $this->seedMetrics($author->id, $now->copy()->startOfDay(), $now->copy()->endOfDay(), 20, 'hour');

        // 2. Seed WEEKLY metrics (Daily for this week)
        $this->seedMetrics($author->id, $now->copy()->startOfWeek(), $now->copy()->endOfWeek(), 40, 'day');

        // 3. Seed MONTHLY metrics (Daily for this month)
        $this->seedMetrics($author->id, $now->copy()->startOfMonth(), $now->copy()->endOfMonth(), 60, 'day');

        // 4. Seed YEARLY metrics (Monthly for this year)
        $this->seedMetrics($author->id, $now->copy()->startOfYear(), $now->copy()->endOfYear(), 100, 'month');

        // 5. Seed LIFETIME metrics (Yearly for last 5 years)
        $this->seedMetrics($author->id, $now->copy()->subYears(5), $now->copy(), 200, 'year');
        
        $this->command->info('Dashboard metrics seeded successfully!');
    }

    private function seedMetrics($authorId, $start, $end, $count, $distribution)
    {
        // Get some random image files belonging to this author for downloads/earnings
        // If none exist, we might need to fake it or skip valid relationships (but logic requires proper relation usually)
        // Let's check if author has images
        $imageFileIds = ImageFile::whereHas('image', function($q) use ($authorId) {
            $q->where('user_id', $authorId);
        })->pluck('id')->toArray();

        // If no images, create a dummy one just for metrics linking (or skip if strict FK not needed for stats)
        // But EarningRecord uses author_id directly? Yes.
        // Downloads uses image_file_id.

        if (empty($imageFileIds)) {
             // Create a dummy image + file for stats
             $image = new Image();
             $image->user_id = $authorId;
             $image->title = 'Seeded Asset';
             $image->status = ManageStatus::IMAGE_APPROVED;
             $image->save();

             $file = new ImageFile();
             $file->image_id = $image->id;
             $file->status = 1;
             $file->save();
             $imageFileIds = [$file->id];
        }

        for ($i = 0; $i < $count; $i++) {
            $timestamp = $this->getRandomTimestamp($start, $end, $distribution);
            
            // Seed Earning
            if (rand(0, 1)) { // 50% chance
                EarningRecord::create([
                    'author_id' => $authorId,
                    'image_file_id' => $imageFileIds[array_rand($imageFileIds)], // Fixed column name
                    'amount' => rand(10, 500) / 100, // 0.10 to 5.00
                    'earning_date' => $timestamp->format('Y-m-d'), // Required field
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            // Seed Download
            if (rand(0, 1)) {
                Download::create([
                    'user_id' => 1, // Downloader
                    'image_file_id' => $imageFileIds[array_rand($imageFileIds)],
                    'author_id' => $authorId, // Crucially needed for dashboard query
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }
    }

    private function getRandomTimestamp($start, $end, $distribution)
    {
        $startTimestamp = $start->timestamp;
        $endTimestamp = $end->timestamp;
        $randomTimestamp = rand($startTimestamp, $endTimestamp);
        return Carbon::createFromTimestamp($randomTimestamp);
    }
}
