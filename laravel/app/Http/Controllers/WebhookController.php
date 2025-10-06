<?php

namespace App\Http\Controllers;

use App\Models\Ades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WebhookController extends Controller
{
    public function handleGithubWebhook(Request $request)
    {
        // Verify the GitHub webhook secret if configured

        Ades::create([
            'title' => 'ahhha',
            'body' => 'ahhha',
            'link' => 'ahhha',
            'image' => 'ahhha',
            'alt' => 'ahhha',
        ]);
        $secret = '';
        if ($secret !== null && !$this->isValidGitHubSignature($request, $secret)) {
            abort(403, 'Unauthorized.');
        }

        $payload = json_decode($request->getContent());

        // Process the webhook event for the main branch
        if ($payload->ref === 'refs/heads/main') {

            $process = new Process(['git', 'pull', 'origin', 'main']);
            $process->setWorkingDirectory(base_path());
            try {
                $process->mustRun(function ($type, $buffer) {
                    Log::info($buffer);
                });

                Log::info('Git pull successful.');
            } catch (ProcessFailedException $exception) {
                Log::error('Failed to execute git pull command: ' . $exception->getMessage());
                return response()->json(['message' => 'Failed to pull changes.'], 500);
            }
        }

        return response()->json(['message' => 'Webhook received successfully.'], 200);
    }

    private function isValidGitHubSignature(Request $request, $secret)
    {
        $expectedSignature = 'sha1=' . hash_hmac('sha1', $request->getContent(), $secret);
        $actualSignature = $request->header('X-Hub-Signature');

        return hash_equals($expectedSignature, $actualSignature);
    }
}
