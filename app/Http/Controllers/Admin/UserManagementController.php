<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Controller;
use App\Events\UserStateChanged;
use App\Models\Enums\UserState;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepo
    ) {
    }

    /**
     * Approve a user's registration
     */
    public function approve(Request $request)
    {
        $userId = $request->input('user_id');
        $user = $this->userRepo->findWithoutFail($userId);
        
        if (!$user) {
            flash()->error('User not found');
            return redirect()->back();
        }

        $oldState = $user->state;
        $user->state = UserState::ACTIVE;
        $user->save();

        event(new UserStateChanged($user, $oldState));
        
        Log::info('User ' . $user->ident . ' was approved by ' . auth()->user()->ident);
        flash()->success('User ' . $user->name . ' has been approved');
        
        return redirect()->back();
    }

    /**
     * Reject a user's registration
     */
    public function reject(Request $request)
    {
        $userId = $request->input('user_id');
        $user = $this->userRepo->findWithoutFail($userId);
        
        if (!$user) {
            flash()->error('User not found');
            return redirect()->back();
        }

        $oldState = $user->state;
        $user->state = UserState::REJECTED;
        $user->save();

        event(new UserStateChanged($user, $oldState));
        
        Log::info('User ' . $user->ident . ' was rejected by ' . auth()->user()->ident);
        flash()->success('User ' . $user->name . ' has been rejected');
        
        return redirect()->back();
    }
}