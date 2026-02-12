<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Config\SatkerConfig;

class GenerateSatkerModelsLaravel10 extends Command
{
    protected $signature = 'satker:generate-models-l10';

    protected $description = 'Generate models for 26 satkers - Laravel 10';

    public function handle()
    {
        $this->info('========================================');
        $this->info('    GENERATE MODELS 26 SATKER');
        $this->info('    LARAVEL 10');
        $this->info('========================================');
        $this->newLine();

        $satkers = SatkerConfig::getConnections();
        $bar = $this->output->createProgressBar(count($satkers) * 2);
        $bar->start();

        foreach ($satkers as $satker) {
            $this->generatePerkaraBandingModel($satker);
            $bar->advance();

            $this->generatePerkaraKasasiModel($satker);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✓ Successfully generated ' . (count($satkers) * 2) . ' models');
        $this->newLine();

        $this->table(
            ['No', 'Satker', 'Model Banding', 'Model Kasasi'],
            $this->getModelList($satkers)
        );
    }

    protected function generatePerkaraBandingModel($satker)
    {
        $className = 'PerkaraBanding' . ucfirst($satker);
        $namaSatker = SatkerConfig::getNamaSatker($satker);
        $nomorUrut = SatkerConfig::getNomorUrut($satker);

        $content = "<?php

namespace App\Models\Satker;

use App\Models\Abstracts\PerkaraBanding;
use App\Traits\MultiDatabaseTrait;

class {$className} extends PerkaraBanding
{
    use MultiDatabaseTrait;
    
    protected \$connection = '{$satker}';
    
    protected \$table = 'perkara_banding';
    
    /**
     * Get nama pengadilan agama
     */
    public static function getNamaSatker(): string
    {
        return '{$namaSatker}';
    }
    
    /**
     * Get nomor urut
     */
    public static function getNomorUrut(): int
    {
        return {$nomorUrut};
    }
    
    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun(\$query, \$tahun)
    {
        return \$query->whereYear('permohonan_banding', \$tahun);
    }
    
    /**
     * Scope untuk perkara yang sudah kasasi
     */
    public function scopeSudahKasasi(\$query)
    {
        return \$query->whereHas('perkaraKasasi', function(\$q) {
            \$q->whereNotNull('nomor_perkara_kasasi');
        });
    }
}";

        $path = app_path("Models/Satker/{$className}.php");
        File::ensureDirectoryExists(app_path('Models/Satker'));
        File::put($path, $content);
    }

    protected function generatePerkaraKasasiModel($satker)
    {
        $className = 'PerkaraKasasi' . ucfirst($satker);

        $content = "<?php

namespace App\Models\Satker;

use App\Models\Abstracts\PerkaraKasasi;
use App\Traits\MultiDatabaseTrait;

class {$className} extends PerkaraKasasi
{
    use MultiDatabaseTrait;
    
    protected \$connection = '{$satker}';
    
    protected \$table = 'perkara_kasasi';
    
    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun(\$query, \$tahun)
    {
        return \$query->whereYear('tanggal_pendaftaran_kasasi', \$tahun);
    }
    
    /**
     * Get tanggal format Indonesia
     */
    public function getTanggalIndonesiaAttribute()
    {
        if (!\$this->tanggal_pendaftaran_kasasi) {
            return '-';
        }
        
        return \Carbon\Carbon::parse(\$this->tanggal_pendaftaran_kasasi)
            ->locale('id')
            ->isoFormat('D MMMM Y');
    }
}";

        $path = app_path("Models/Satker/{$className}.php");
        File::put($path, $content);
    }

    protected function getModelList($satkers)
    {
        $list = [];
        $no = 1;

        foreach ($satkers as $satker) {
            $list[] = [
                $no,
                strtoupper($satker),
                'PerkaraBanding' . ucfirst($satker),
                'PerkaraKasasi' . ucfirst($satker)
            ];
            $no++;
        }

        return $list;
    }
}
