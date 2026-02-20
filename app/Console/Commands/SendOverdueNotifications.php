<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Notifications\BookOverdueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOverdueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:send-overdue-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        # To-Do: Edit This to make it from settings with correct key
        #IDK the key currently so i set it by default to 0.01 from the figma design
        $dailyFine = 0.01;

        $this->info('Scanning for overdue transactions...');

        $overdueRecords = Transaction::with(['bill.customer.user', 'book'])
            ->whereNull('returned_at') 
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', 'received')
            ->get();

        if ($overdueRecords->isEmpty()) {
            $this->info('No overdue books found.');
            return Command::SUCCESS;
        }

        $notifiedCount = 0;

        foreach ($overdueRecords as $transaction) {
            $daysLate = now()->diffInDays($transaction->due_date);

            $transaction->processOverdueStep($dailyFine);

            $user = $transaction->bill->customer->user;

            if ($user && $transaction->book) {
                $user->notify(new BookOverdueNotification($transaction->book, (int) $daysLate));

                $notifiedCount++;
            }
        }

        Log::info("Overdue scan complete. Notifications sent: {$notifiedCount}");
        $this->info("Done! Notified {$notifiedCount} users.");

        return Command::SUCCESS;
    }
}
