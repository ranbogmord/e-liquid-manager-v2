<?php


namespace App\Services;


use App\Flavour;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FlavourService
{
    /**
     * @var DatabaseManager
     */
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $flavour = new Flavour($data);
        $flavour->save();

        return $flavour;
    }

    /**
     * @param Flavour $flavour
     * @param array $data
     *
     * @return Flavour
     */
    public function update(Flavour $flavour, $data)
    {
        $flavour->fill($data);
        $flavour->save();

        return $flavour;
    }

    /**
     * @param $flavours
     *
     * @return Collection
     */
    public function formatForLiquidStorage($flavours)
    {
        return collect($flavours)->filter(function ($f) {
            return $f['percent'] > 0;
        })->mapWithKeys(function ($f) {
            return [
                $f['flavour_id'] => [
                    'percent' => $f['percent']
                ]
            ];
        });
    }

    /**
     * @param $value
     *
     * @return float|int
     */
    private function roundToNearestQuarter($value)
    {
        return round($value * 4) / 4;
    }

    /**
     * @param Flavour $flavour
     *
     * @return Flavour
     */
    public function recalculateBasePercent(Flavour $flavour)
    {
        if (collect($flavour->liquids)->count() === 0) {
            return $flavour;
        }

        $totalPercent = collect($flavour->liquids)->map(function ($l) {
            return $l->pivot->percent;
        })->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 0);

        $avg = $totalPercent / collect($flavour->liquids)->count();

        $flavour->base_percent = $this->roundToNearestQuarter($avg);
        return $flavour;
    }

    public function mergeFlavours(Flavour $from, Flavour $to)
    {
        try {
            $this->db->table('flavour_liquid')
                ->where('flavour_id', $from->id)
                ->update(['flavour_id' => $to->id]);
        } catch (\Exception $ex) {
            return false;
        }

        try {
            $from->delete();
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }
}