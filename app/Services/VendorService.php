<?php


namespace App\Services;


use App\Vendor;

class VendorService
{
    /**
     * @param array $data
     *
     * @return Vendor
     */
    public function create($data)
    {
        $vendor = new Vendor($data);

        $vendor->save();

        return $vendor;
    }

    /**
     * @param Vendor $vendor
     * @param array $data
     *
     * @return Vendor
     */
    public function update($vendor, $data)
    {
        $vendor->fill($data);
        $vendor->save();

        return $vendor;
    }
}