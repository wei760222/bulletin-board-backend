<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Firestore;

class TestFirebaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Firebase connection';

    
    protected $firestore;

     /**
     * Create a new command instance.
     *
     * @param Firestore $firestore
     * @return void
     */
    public function __construct(Firestore $firestore)
    {
        parent::__construct();
        $this->firestore = $firestore;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
          try {
            // 使用 $this->firestore 而不是 $firestore
            $database = $this->firestore->database();
            $this->info('Successfully connected to Firestore!');
            
            // 測試讀取
            $documents = $database->collection('test')->documents();
            $this->info('Available collections: ' . json_encode(iterator_to_array($documents)));
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Connection failed: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            $this->error('Trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
