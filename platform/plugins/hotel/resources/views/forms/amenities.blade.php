@foreach ($amenities as $amenity)
    <label class="checkbox-inline">
        <input name="amenities[]" type="checkbox" value="{{ $amenity->id }}" @if (in_array($amenity->id, $selectedAmenities)) checked @endif>{{ $amenity->name }}
    </label>&nbsp;
@endforeach
