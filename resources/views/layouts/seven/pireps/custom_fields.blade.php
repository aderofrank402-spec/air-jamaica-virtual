<tr>
  <td class="py-3 ps-0" style="width: 35%; border-bottom: 1px solid var(--surface-light);">
    <label class="form-label mb-0">
      {{ $field->name }}
      @if($field->required) <span class="text-danger">*</span> @endif
    </label>
    @if(filled($field->description))
      <div class="small text-muted mt-1">{{ $field->description }}</div>
    @endif
  </td>
  <td class="py-3 pe-0" style="border-bottom: 1px solid var(--surface-light);">
    @if(!empty($pirep) && $pirep->read_only)
      <div class="form-control border-0 px-0 bg-transparent">{{ $field->value }}</div>
    @else
      <input type="text" name="{{ $field->slug }}" id="{{ $field->slug }}" class="form-control" value="{{ $field->value }}">
      @error($field->slug) <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
    @endif
  </td>
</tr>