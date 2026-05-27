<?php

namespace App\Http\Controllers;

use App\Models\GitConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class GitDeployController extends Controller
{
    // Git ayarlarını oku
    public function getConfig()
    {
        $cfg = GitConfig::current();
        return response()->json([
            'git_path' => $cfg->git_path,
            'branch'   => $cfg->branch,
            'repo_url' => $cfg->repo_url,
        ]);
    }

    // Git ayarlarını kaydet
    public function saveConfig(Request $request)
    {
        $data = $request->validate([
            'git_path' => 'nullable|string|max:500',
            'branch'   => 'nullable|string|max:100',
            'repo_url' => 'nullable|string|max:500',
        ]);

        GitConfig::updateOrCreate(['id' => 1], $data);

        return response()->json(['success' => true, 'message' => 'Saved.']);
    }

    // Migrate calistir
    public function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output  = Artisan::output();
            $success = true;
        } catch (\Throwable $e) {
            $output  = $e->getMessage();
            $success = false;
        }

        Log::info('Migrate calistirildi.', ['success' => $success, 'output' => $output]);

        return response()->json([
            'success' => $success,
            'output'  => $output,
        ]);
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
        $cfg = GitConfig::current();
        return [
            'git_path' => $cfg->git_path,
            'branch'   => $cfg->branch,
            'repo_url' => $cfg->repo_url,
        ];
    }

    private function runPull(): \Illuminate\Http\JsonResponse
    {
        $config      = $this->readConfig();
        $projectPath = base_path();
        $branch      = $config['branch'] ?? config('app.deploy_branch', 'main');
        $gitBin      = $config['git_path'] ?? 'git';
        $repoUrl     = $config['repo_url'] ?? null;

        $isGitRepo = is_dir($projectPath . '/.git');

        $commands = [];

        if (! $isGitRepo) {
            if (! $repoUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Git repo bulunamadı. Önce Repo URL girin ve tekrar deneyin.',
                    'output'  => 'fatal: not a git repository — önce Repo URL kaydedin.',
                ]);
            }
            // İlk kurulum: init + remote + fetch + reset
            $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" init 2>&1";
            $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" remote add origin {$repoUrl} 2>&1 || \"{$gitBin}\" -C \"{$projectPath}\" remote set-url origin {$repoUrl} 2>&1";
            $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" fetch --all 2>&1";
            $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" reset --hard origin/{$branch} 2>&1";
        } else {
            if ($repoUrl) {
                $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" remote set-url origin {$repoUrl} 2>&1";
            }
            $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" fetch --all 2>&1";
            $commands[] = "\"{$gitBin}\" -C \"{$projectPath}\" reset --hard origin/{$branch} 2>&1";
        }

        $output = [];
        $error  = false;

        foreach ($commands as $cmd) {
            $result = shell_exec($cmd);
            $output[] = trim($result ?? '');
            if (str_contains(strtolower($result ?? ''), 'fatal')) {
                $error = true;
            }
        }

        $fullOutput = implode("\n", array_filter($output));

        if ($error) {
            Log::error('Git pull hata:', ['output' => $fullOutput]);
            return response()->json(['success' => false, 'message' => 'Git pull hatası.', 'output' => $this->toUtf8($fullOutput)]);
        }

        Log::info('Git pull basarili.', ['output' => $fullOutput]);
        return response()->json(['success' => true, 'message' => 'Done.', 'output' => $this->toUtf8($fullOutput)]);
    }

    private function toUtf8(string $text): string
    {
        if (mb_detect_encoding($text, 'UTF-8', true)) {
            return $text;
        }
        return mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
    }
}
