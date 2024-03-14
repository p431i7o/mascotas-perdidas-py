<?php

namespace App\Console\Commands;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Storage;

class OldReportsDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired-reports:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra reportes viejos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Borrando reportes viejos');
        $reports = Report::where('expiration','<=',Carbon::now()->subDays(config('app.days_until_deletion')))->get();
        if($reports->count() > 0){
            $this->line($reports->count().' registros a borrar');
            foreach($reports as $report){
                $attachments = json_decode($report->attachments);
                foreach($attachments as $attachment){
                    // $this->line('Borrando archivo: '.$attachment->file_name);
                    Storage::delete('report_uploads/'.$report->user_id.'/'.$report->id.'/'.$attachment->file_name);
                }
                // $this->line('Borrando reporte');
                $report->delete();
            }
        }
        return Command::SUCCESS;
    }
}
