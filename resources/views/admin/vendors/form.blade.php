
@include("partials.form.input", [
    'label' => 'Name',
    'value' => old('name', $vendor->name)
])

@include("partials.form.input", [
    'label' => 'Abbreviation',
    'name' => 'abbr',
    'value' => old('abbr', $vendor->abbr)
])
