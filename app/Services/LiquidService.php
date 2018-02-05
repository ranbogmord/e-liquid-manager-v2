<?php
namespace App\Services;

use App\Liquid;
use Illuminate\Support\Facades\DB;

class LiquidService
{
    /**
     * @var FlavourService
     */
    private $flavourService;

    public function __construct(FlavourService $flavourService)
    {
        $this->flavourService = $flavourService;
    }

    /**
     * @param array $data
     *
     * @return Liquid
     */
    public function create($data)
    {
        $flavours = $this->flavourService->formatForLiquidStorage($data['flavours']);
        unset($data['flavours']);
        $liquid = new Liquid($data);

        DB::transaction(function () use ($liquid, $flavours) {
            $liquid->save();

            $liquid->flavours()->sync($flavours);
        });

        return $liquid;
    }

    /**
     * @param Liquid $liquid
     * @param array $data
     *
     * @return Liquid
     */
    public function update($liquid, $data)
    {
        $flavours = $this->flavourService->formatForLiquidStorage($data['flavours']);
        unset($data['flavours']);
        $liquid->fill($data);


        DB::transaction(function () use ($liquid, $flavours) {
            $liquid->save();

            $liquid->flavours()->sync($flavours);
        });

        return $liquid;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getUserLiquids($id) {
        return Liquid::where('author_id', $id)->get();
    }

    public function getAllLiquids()
    {
        return Liquid::all();
    }
}
