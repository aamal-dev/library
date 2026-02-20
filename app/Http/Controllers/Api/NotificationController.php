<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try{
            $user = $request->user();
    
            $notifications = $user->notifications()->latest()->get();
    
            
    
            return apiSuccess('تم إرجاع الإشعارات بنجاح', [
                'unread_count' => $user->unreadNotifications->count(),
                'notifications' => NotificationResource::collection($notifications),
            ]);
        }catch(Exception $e){
            Log::error('Failed to fetch notifications: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $request->user()?->id
            ]);

            return apiError('فشل إرجاع الإشعارات. الرجاء المحاولة لاحقاً.', 500);
        }
    }

    public function markAsRead(Request $request, string $id)
    {
        try {
            $notification = $request->user()->notifications()->findOrFail($id);
            
            $notification->markAsRead();

            return apiSuccess('تم تحديث الإشعار بنجاح');

        } catch (ModelNotFoundException $e) {
            return apiError('لم يتم العثور على الإشعار المطلوبة.', 404);

        } catch (Exception $e) {
            Log::error('Failed to mark notification as read: ' . $e->getMessage(), [
                'notification_id' => $id,
                'user_id' => $request->user()?->id
            ]);

            return apiError('لم يتم تحديث الإشعار. الرجاء المحاولة لاحقاً.', 500);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $request->user()->unreadNotifications->markAsRead();

            return apiSuccess("تم تحديث جميع الإشعارات بنجاح");

        } catch (Exception $e) {
            Log::error('Failed to mark all notifications as read: ' . $e->getMessage(), [
                'user_id' => $request->user()?->id
            ]);

            return apiError('لم يتم تحديث جميع الإشعارات. الرجاء المحاولة لاحقاً.', 500);
        }
    }
}
