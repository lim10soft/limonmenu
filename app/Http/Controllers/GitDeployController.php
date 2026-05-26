<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitDeployController extends Controller
{
    // Git ayarlarını oku
    public function getConfig()
    {
        return response()->json($this->readConfig());
    }

    // Git ayarlarını kaydet
    public function saveConfig(Request $request)
    {
        $data = $request->validate([
            'git_path' => 'nullable|string|max:500',
            'branch'   => 'nullable|string|max:100',
            'repo_url' => 'nullable|string|max:500',
        ]);

        $path = storage_path('app/git_config.json');
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return response()->json(['message' => 'Git ayarları kaydedildi.']);
    }

    // Manuel pull — superadmin token ile
    public function pull(Request $request)
    {
        $secret = config('app.deploy_secret');

        // Superadmin ise secret gerekmez
        if ($request->user()?->role === 'superadmin') {
            return $this->runPull();
        }

        // Diğer kullanıcılar için Deploy Secret zorunlu
        if (! $secret || $request->header('X-Deploy-Secret') !== $secret) {
            return response()->json(['success' => false, 'message' => 'Yetkisiz erişim.'], 403);
        }

        return $this->runPull();
    }

    // GitHub Webhook — otomatik
    public function webhook(Request $request)
    {
        $secret = config('app.github_webhook_secret');

        if ($secret) {
            $signature = $request->header('X-Hub-Signature-256', '');
            $expected  = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

            if (! hash_equals($expected, $signature)) {
                Log::warning('Git webhook: imza doğrulaması başarısız.');
                return response()->json(['message' => 'İmza geçersiz.'], 403);
            }
        }

        $payload = $request->json();
        $branch  = last(explode('/', $payload->get('ref', '')));
        $allowedBranch = config('app.deploy_branch', 'main');

        if ($branch && $branch !== $allowedBranch) {
            return response()->json(['message' => "Branch '$branch' için deploy yok."]);
        }

        Log::info('Git webhook: deploy tetiklendi.', ['branch' => $branch]);

        return $this->runPull();
    }

    private function readConfig(): array
    {
        $path = storage_path('app/git_config.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? [];
        }
        return [];
    }

    private function runPull(): \Illuminate\Http\JsonResponse
    {
        $config      = $this->readConfig();
        $projectPath = base_path();
        $branch      = $config['branch'] ?? config('app.deploy_branch', 'main');
        $gitBin      = $config['git_path'] ?? 'git';
        $repoUrl     = $config['repo_url'] ?? null;

        $commands = [];

        if ($repoUrl) {
            $commands[] = "cd \"{$projectPath}\" && \"{$gitBin}\" remote set-url origin {$repoUrl} 2>&1";
        }

        $commands[] = "cd \"{$projectPath}\" && \"{$gitBin}\" fetch --all 2>&1";
        $commands[] = "cd \"{$projectPath}\" && \"{$gitBin}\" reset --hard origin/{$branch} 2>&1";

        $output = [];
        $error  = false;

        foreach ($commands as $cmd) {
            $result = shell_exec($cmd);
            $output[] = trim($result ?? '');
            if (str_contains(strtolower($result ?? ''), 'error') || str_contains(strtolower($result ?? ''), 'fatal')) {
                $error = true;
            }
        }

        $fullOutput = implode("\n", array_filter($output));

        if ($error) {
            Log::error('Git pull hata:', ['output' => $fullOutput]);
            return response()->json(['success' => false, 'message' => 'Git pull hatası.', 'output' => $fullOutput]);
        }

        Log::info('Git pull başarılı.', ['output' => $fullOutput]);
        return response()->json(['success' => true, 'message' => 'Güncelleme tamamlandı.', 'output' => $fullOutput]);
    }
}
