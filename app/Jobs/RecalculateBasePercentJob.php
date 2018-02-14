<?php

namespace App\Jobs;

use App\Flavour;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RecalculateBasePercentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $flavour;

    /**
     * Create a new job instance.
     *
     * @param Flavour $flavour
     */
    public function __construct(Flavour $flavour)
    {
        $this->flavour = $flavour;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $percentages = $this->flavour->liquids->map(function ($liquid) {
            return $liquid->pivot->percent;
        });

        if (count($percentages) === 0) {
            $this->flavour->base_percent = 0;
        } else {
            $this->flavour->base_percent = $percentages->sum() / $percentages->count();
        }

        $this->flavour->save();
    }
}
