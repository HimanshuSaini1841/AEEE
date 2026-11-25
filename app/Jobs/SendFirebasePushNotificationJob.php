<?php

namespace App\Jobs;

use App\Models\FcmDeviceToken;
use App\Models\PushNotificationLog;
use App\Models\Student;
use App\Services\FirebaseFcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendFirebasePushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes timeout for high-scale sending

    protected int $logId;
    protected string $title;
    protected string $body;
    protected ?string $imageUrl;
    protected array $targetCriteria;

    public function __construct(int $logId, string $title, string $body, ?string $imageUrl = null, array $targetCriteria = [])
    {
        $this->logId = $logId;
        $this->title = $title;
        $this->body = $body;
        $this->imageUrl = $imageUrl;
        $this->targetCriteria = $targetCriteria;
    }

    /**
     * Execute the job.
     * Chunks active FCM device tokens in batches of 500 to handle millions of devices
     * without hitting memory limits or network timeouts.
     */
    public function handle(FirebaseFcmService $fcmService): void
    {
        $logRecord = PushNotificationLog::find($this->logId);
        if ($logRecord) {
            $logRecord->update(['status' => 'processing']);
        }

        if (!$fcmService->isConfigured()) {
            if ($logRecord) {
                $logRecord->update([
                    'status' => 'failed',
                    'failure_count' => $logRecord->total_targets > 0 ? $logRecord->total_targets : 1,
                    'success_count' => 0,
                ]);
            }
            Log::warning("Push Notification #{$this->logId} aborted: Firebase FCM credentials (Service Account JSON or Server Key) are not configured.");
            return;
        }

        $totalSuccess = 0;
        $totalFailure = 0;

        // Build query matching target criteria
        $studentQuery = Student::query()->where('status', 'active');
        if (!empty($this->targetCriteria['class_id'])) {
            $studentQuery->where('class_id', $this->targetCriteria['class_id']);
        }
        if (!empty($this->targetCriteria['state_id'])) {
            $studentQuery->where('state_id', $this->targetCriteria['state_id']);
        }
        if (!empty($this->targetCriteria['preparing_for_id'])) {
            $studentQuery->where('preparing_for_id', $this->targetCriteria['preparing_for_id']);
        }

        $targetedStudentIds = $studentQuery->select('id');

        // Query active device tokens for these students in chunks of 500
        FcmDeviceToken::query()
            ->where('is_active', true)
            ->whereIn('student_id', $targetedStudentIds)
            ->chunkById(500, function ($tokensChunk) use ($fcmService, &$totalSuccess, &$totalFailure) {
                $tokensList = $tokensChunk->pluck('token')->toArray();
                if (!empty($tokensList)) {
                    $result = $fcmService->sendMulticast(
                        $tokensList,
                        $this->title,
                        $this->body,
                        $this->imageUrl,
                        ['notification_log_id' => (string) $this->logId]
                    );

                    $totalSuccess += $result['success'];
                    $totalFailure += $result['failure'];
                }
            });

        if ($logRecord) {
            $logRecord->update([
                'status' => 'completed',
                'total_targets' => $totalSuccess + $totalFailure,
                'success_count' => $totalSuccess,
                'failure_count' => $totalFailure,
            ]);
        }

        Log::info("Push Notification #{$this->logId} dispatch completed. Success: {$totalSuccess}, Failed: {$totalFailure}");
    }
}
