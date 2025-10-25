<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Module extends Command
{
    protected $signature = 'app:module {path : Đường dẫn ví dụ: Management/Program} {--name= : Tên tiếng Việt cho module} {--remove : Xóa module thay vì tạo}';
    protected $description = 'Tạo hoặc xóa module chuẩn từ stub templates (repository, livewire, view, validation, trait...)';

    //php artisan app:module Management/Course --name="Khóa học"

    public function handle()
    {
        $path = $this->argument('path');
        $vietnameseName = $this->option('name');

        // Lấy nhóm (ví dụ Management) và tên module (ví dụ Program)
        $segments = explode('/', $path);
        $group = $segments[0] ?? 'Management';
        $module = $segments[1] ?? null;

        if (! $module) {
            $this->error('❌ Bạn cần truyền tên module. Ví dụ: php artisan app:module Management/Program');
            return;
        }

        $moduleStudly = Str::studly($module);
        $moduleLower = Str::snake($module);
        $moduleKebab = Str::kebab($module);

        if ($this->option('remove')) {
            $this->removeModule($group, $moduleStudly, $moduleLower, $moduleKebab);
            return;
        }

        $this->createModule($group, $moduleStudly, $moduleLower, $moduleKebab, $vietnameseName);
    }

    /**
     * Tạo module từ stub
     */
    protected function createModule(string $group, string $module, string $moduleLower, string $moduleKebab, ?string $vietnameseName = null)
    {
        $stubPath = resource_path('stubs/module');

        if (! File::exists($stubPath)) {
            $this->error("❌ Không tìm thấy thư mục stub: {$stubPath}");
            return;
        }

        $stubs = File::allFiles($stubPath);

        foreach ($stubs as $stub) {
            $relative = str_replace($stubPath . '/', '', $stub->getPathname());
            $targetPath = $this->replacePlaceholdersInPath($relative, $group, $module, $moduleLower, $moduleKebab);

            $targetFullPath = base_path($targetPath);
            File::ensureDirectoryExists(dirname($targetFullPath));

            if (File::exists($targetFullPath)) {
                $this->warn("⚠️  Bỏ qua vì đã tồn tại: {$targetPath}");
                continue;
            }

            $content = File::get($stub->getPathname());
            $content = $this->replaceStubVariables($content, $group, $module, $moduleLower, $moduleKebab, $vietnameseName);

            File::put($targetFullPath, $content);
            $this->info("✅ Tạo: {$targetPath}");
        }

        $this->info("🎉 Module [{$group}/{$module}] đã được tạo thành công!");
    }

    /**
     * Xóa module
     */
    protected function removeModule(string $group, string $module, string $moduleLower, string $moduleKebab)
    {
        $folders = [
            "app/Livewire/{$group}/{$module}",
            "app/Repositories/Eloquent/{$module}Repository.php",
            "app/Repositories/Interfaces/{$module}RepositoryInterface.php",
            "app/Traits/{$group}/Handles{$module}Form.php",
            "app/Validation/{$group}/{$module}Rules.php",
            "resources/views/livewire/" . Str::kebab($group) . "/{$moduleKebab}",
        ];

        foreach ($folders as $path) {
            $fullPath = base_path($path);
            if (File::exists($fullPath)) {
                File::isDirectory($fullPath)
                    ? File::deleteDirectory($fullPath)
                    : File::delete($fullPath);
                $this->info("🗑️ Đã xóa: {$path}");
            }
        }

        $this->info("🧹 Module [{$group}/{$module}] đã được xóa hoàn tất!");
    }

    /**
     * Thay placeholder trong nội dung stub
     */
    protected function replaceStubVariables(string $content, string $group, string $module, string $moduleLower, string $moduleKebab, ?string $vietnameseName = null): string
    {
        $groupLower = strtolower($group);
        $vietnameseName = $vietnameseName ?? $module; // Nếu không có tên tiếng Việt, dùng tên module
        
        return str_replace(
            ['{{ group }}', '{{ module }}', '{{ moduleLower }}', '{{ moduleKebab }}', '{{ groupLower }}', '{{ vietnameseName }}'],
            [$group, $module, $moduleLower, $moduleKebab, $groupLower, $vietnameseName],
            $content
        );
    }

    /**
     * Thay placeholder trong đường dẫn
     */
    protected function replacePlaceholdersInPath(string $path, string $group, string $module, string $moduleLower, string $moduleKebab): string
    {
        $groupLower = strtolower($group);
        
        // Mapping các file stub sang đường dẫn thực tế
        $pathMappings = [
            '{{ module }}s.php' => "app/Livewire/{$group}/{$module}/{$module}s.php",
            'Actions{{ module }}.php' => "app/Livewire/{$group}/{$module}/Actions{$module}.php",
            '{{ module }}Repository.php' => "app/Repositories/Eloquent/{$module}Repository.php",
            '{{ module }}RepositoryInterface.php' => "app/Repositories/Interfaces/{$module}RepositoryInterface.php",
            'Handles{{ module }}Form.php' => "app/Traits/{$group}/Handles{$module}Form.php",
            '{{ module }}Rules.php' => "app/Validation/{$group}/{$module}Rules.php",
            '{{ moduleKebab }}s.blade.php' => "resources/views/livewire/{$groupLower}/{$moduleKebab}/{$moduleKebab}s.blade.php",
            'actions-{{ moduleKebab }}.blade.php' => "resources/views/livewire/{$groupLower}/{$moduleKebab}/actions-{$moduleKebab}.blade.php",
        ];

        // Nếu có mapping cụ thể, sử dụng nó
        if (isset($pathMappings[$path])) {
            return $pathMappings[$path];
        }

        // Nếu không, thay thế placeholder như cũ
        return str_replace(
            ['{{ group }}', '{{ module }}', '{{ moduleLower }}', '{{ moduleKebab }}'],
            [$group, $module, $moduleLower, $moduleKebab],
            $path
        );
    }
}
