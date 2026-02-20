<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    
            $notifications->transform(function($notification){
                $data = $notification->data;
    
                $translatedMessage = null;
                if (isset($data['message_key'])) {
                    $translatedMessage = __($data['message_key'], $data['message_params'] ?? []);
                }
    
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'message' => $translatedMessage,
                    
                    'is_read' => $notification->read(),
                    'created_at' => $notification->created_at->toIso8601String(),
                    'created_at_human' => $notification->created_at->diffForHumans(),
                ];
            });
    
            return apiSuccess('تم إرجاع الإشعارات بنجاح', $notifications);
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
