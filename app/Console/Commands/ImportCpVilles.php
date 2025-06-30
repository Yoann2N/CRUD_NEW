<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCpVilles extends Command
{
    protected $signature = 'import:cpvilles';
    protected $description = 'Importe toutes les villes de France et leurs codes postaux depuis un CSV';

    public function handle()
    {
        $filePath = storage_path('app/communes.csv');

        if (!file_exists($filePath)) {
            $this->error("Fichier introuvable : $filePath");
            return;
        }

        $this->info('Import en cours...');

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle, 1000, ';'); // ignore la première ligne

        $rows = [];
        $batchSize = 1000; // nombre d’insertions par lot

        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
            $rows[] = [
                'code_postal' => $data[0],
                'ville' => trim($data[1]),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($rows) >= $batchSize) {
                DB::table('cp_villes')->insert($rows);
                $this->info("Lot de $batchSize inséré...");
                $rows = [];
            }
        }

        if (count($rows) > 0) {
            DB::table('cp_villes')->insert($rows);
            $this->info("Dernier lot inséré...");
        }

        fclose($handle);

        $this->info('✅ Import terminé avec succès !');
    }
}
