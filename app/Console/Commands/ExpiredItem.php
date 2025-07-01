<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\User;
use App\Notifications\ItemExpiryNotifiaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending expired items on email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = User::all();
        $threeMonthsFromNow = now()->addMonths(3);

        $expItems = Item::whereHas('latestCalibration', function ($query) use ($threeMonthsFromNow) {
            $query->where('expired_at', '<', $threeMonthsFromNow);
        })
            ->orWhere(function ($query) use ($threeMonthsFromNow) {
                $query->whereDoesntHave('latestCalibration')
                    ->where('expired_at', '<', $threeMonthsFromNow);
            })
            ->get();

        if ($expItems->count() < 1) {
            $this->info('No expiring items found.');
            return;
        }

        try {
            foreach ($admins as $admin) {
                $admin->notify(new ItemExpiryNotifiaction($expItems));
                $this->info('Email sent to ' . $admin->email);
            }

        }catch (\Exception $exception){
            $this->error('Got error: ' . $exception->getMessage());
        }
    }
}
