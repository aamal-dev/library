<?php

namespace App\Console\Commands;

use App\Enums\WaitingListStatusEnum;
use App\Models\Book;
use App\Models\WaitingList;
use App\Notifications\BookAvailableNotification;
use Illuminate\Console\Command;

class NotifyWaitingList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:notify-waiting-list';

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
        $this->info('Scanning waiting lists for available books...');

        $books_with_wait_list = Book::where('stock', '>', 0 )
            ->whereHas('waitingLists', function ($q) {
                $q->where('status', WaitingListStatusEnum::PENDING);
            })
            ->get();

        if($books_with_wait_list->isEmpty()){
            $this->info('No pending notifications to send.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach($books_with_wait_list as $book){

            $waiting_list = WaitingList::with('customer.user')
                ->where('book_id', $book->id)
                ->where('status', WaitingListStatusEnum::PENDING)
                ->oldest()
                ->take($book->stock) #مشان آخذ بعدد الكتب المتاحة
                ->get();

            foreach($waiting_list as $entry){
                $user = $entry->customer->user;

                $user->notify(new BookAvailableNotification($book));

                $entry->update(['status' => WaitingListStatusEnum::PROCESSED]);
                
                $count++;
            }
        }

        $this->info('Queue processing complete.');
        $this->info("Notified {$count} users.");

        return Command::SUCCESS;
    }
}
