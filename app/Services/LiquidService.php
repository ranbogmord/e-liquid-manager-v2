<?php
namespace App\Services;

use App\Liquid;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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

    public function getAllLiquids($params = [])
    {
        if (empty($params)) {
            return Liquid::all();
        }

        $liquids = Liquid::query();
        if (!empty($params['sort'])) {
            if (!empty($params['order']) && in_array(strtolower($params['order']), ['asc', 'desc'])) {
                $order = $params['order'];
            } else {
                $order = "asc";
            }

            $liquids->orderBy($params['sort'], $order);
        }

        if (!empty($params['archived'])) {
            $liquids->withoutGlobalScope(SoftDeletingScope::class);
            $liquids->whereNotNull('deleted_at');
        }

        if (!empty($params['all-versions'])) {
            $liquids->withoutGlobalScope('only-latest-version');
        }

        return $liquids->get();
    }
}
