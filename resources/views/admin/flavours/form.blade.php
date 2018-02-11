
@include("partials.form.input", [
    'label' => 'Name',
    'value' => old('name', $flavour->name)
])

@include("partials.form.boolean-checkbox", [
    'label' => 'Is VG?',
    'name' => 'is_vg',
    'value' => old('is_vg', $flavour->is_vg)
])

@include("partials.form.select", [
    'label' => 'Vendor',
    'name' => 'vendor_id',
    'options' => $vendors,
    'value' => old('vendor_id', $flavour->vendor_id),
    'default' => 'Other'
])