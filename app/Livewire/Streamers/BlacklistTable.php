<?php

namespace App\Livewire\Streamers;

use App\Models\Blacklist;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BlacklistTable extends Component
{
    use WithPagination;

    // Disable bootstrap
    protected $paginationTheme = 'tailwind';

    public $channelName;
    public $searchTerm;

    public function mount()
    {
        $this->channelName = Auth::user()->name;
    }

    public function search()
    {
        $query = Blacklist::query();

        // check if the channelName is not null and then apply the where clause
        if (!is_null($this->channelName)) {
            $query->where('channel_name', $this->channelName);
        }

        // filter users by searchTerm
        $query->whereHas('user', function ($query) {
            $query->where('name', 'like', "%{$this->searchTerm}%");
        });

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function submit()
    {
        $this->render();
    }

    public function toggleBlacklistStatus($userId)
    {
        $blacklist = Blacklist::where('user_id', $userId)->where('channel_name', $this->channelName)->first();

        if($blacklist){
            $blacklist->status = !$blacklist->status; // negate the status
            $blacklist->save();
        }else{
            // user not found
        }
    }

    public function render()
    {
        $blacklists = $this->search();
        return view('livewire.streamers.blacklist-table', compact('blacklists'));
    }
}
