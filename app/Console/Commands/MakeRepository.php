<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name} {--remove}';
    protected $description = 'Tạo hoặc xóa Repository Interface và Eloquent Repository';

    public function handle()
    {
        $name = $this->argument('name');
        $remove = $this->option('remove');

        if ($remove) {
            $this->removeRepository($name);
        } else {
            $this->createRepository($name);
        }

        return Command::SUCCESS;
    }

    protected function createRepository(string $name)
    {
        $interfacePath = app_path("Repositories/Interfaces/{$name}RepositoryInterface.php");
        $eloquentPath = app_path("Repositories/Eloquent/{$name}Repository.php");

        if (!File::exists($interfacePath)) {
            File::ensureDirectoryExists(dirname($interfacePath));
            File::put($interfacePath, <<<PHP
                <?php

                namespace App\Repositories\Interfaces;
                //use Illuminate\Interfaces\Pagination\LengthAwarePaginator;

                interface {$name}RepositoryInterface
                {
                    public function all();
                    public function find(int|string \$id);
                    public function paginate(int \$perPage = 15);
                    public function create(array \$data);
                    public function update(int|string \$id, array \$data);
                    public function delete(int|string \$id);
                }
                PHP);
            $this->info("✅ Interface: {$interfacePath}");
        } else {
            $this->warn("⚠️ Interface exists: {$interfacePath}");
        }

        if (!File::exists($eloquentPath)) {
            File::ensureDirectoryExists(dirname($eloquentPath));
            File::put($eloquentPath, <<<PHP
                <?php

                namespace App\Repositories\Eloquent;

                use App\Repositories\Interfaces\\{$name}RepositoryInterface;
                use App\Models\\{$name};
                use App\Repositories\BaseRepository;

                class {$name}Repository extends BaseRepository implements {$name}RepositoryInterface
                {
                    public function __construct({$name} \$model)
                    {
                        parent::__construct(\$model);
                    }
                }
                PHP);
            $this->info("✅ Eloquent: {$eloquentPath}");
        } else {
            $this->warn("⚠️ Eloquent exits: {$eloquentPath}");
        }

    }

    protected function removeRepository(string $name)
    {
        $interfacePath = app_path("Repositories/Interfaces/{$name}RepositoryInterface.php");
        $eloquentPath = app_path("Repositories/Eloquent/{$name}Repository.php");

        if (File::exists($interfacePath)) {
            File::delete($interfacePath);
            $this->info("✅ Deleted Interface: {$interfacePath}");
        } else {
            $this->warn("⚠️ Not found Interface: {$interfacePath}");
        }

        if (File::exists($eloquentPath)) {
            File::delete($eloquentPath);
            $this->info("✅ Deleted Eloquent: {$eloquentPath}");
        } else {
            $this->warn("⚠️ Not found Eloquent: {$eloquentPath}");
        }
    }
}
