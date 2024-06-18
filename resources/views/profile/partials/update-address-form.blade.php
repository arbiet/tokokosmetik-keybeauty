<!-- resources\views\profile\partials\update-address-form.blade.php -->

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Address') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's address information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.address.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="street" :value="__('Street')" />
            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $address->street ?? '')" required autofocus autocomplete="street" />
            <x-input-error class="mt-2" :messages="$errors->get('street')" />
        </div>

        <div>
            <x-input-label for="province" :value="__('Province')" />
            <select id="province" name="province" class="mt-1 block w-full" required>
                <option value="">{{ __('Select Province') }}</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->province_id }}" {{ old('province', $address->province_id ?? '') == $province->province_id ? 'selected' : '' }}>
                        {{ $province->province_name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('province')" />
        </div>

        <div>
            <x-input-label for="city" :value="__('City')" />
            <select id="city" name="city" class="mt-1 block w-full" required>
                <option value="">{{ __('Select City') }}</option>
                @if($cities->isNotEmpty())
                    @foreach($cities as $city)
                        <option value="{{ $city->city_id }}" {{ old('city', $address->city_id ?? '') == $city->city_id ? 'selected' : '' }}>
                            {{ $city->city_name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <x-input-label for="postal_code" :value="__('Postal Code')" />
            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', $address->postal_code ?? '')" required readonly />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <div>
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" value="Indonesia" readonly />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'address-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const postalCodeInput = document.getElementById('postal_code');
        const selectedCityId = "{{ $address->city_id ?? '' }}";

        provinceSelect.addEventListener('change', function () {
            const provinceId = this.value;
            fetch(`/profile/cities/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">{{ __('Select City') }}</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.city_id}">${city.city_name}</option>`;
                    });

                    // Set the selected city if it exists
                    if (selectedCityId) {
                        citySelect.value = selectedCityId;
                        citySelect.dispatchEvent(new Event('change'));
                    }
                });
        });

        citySelect.addEventListener('change', function () {
            const cityId = this.value;
            fetch(`/profile/postal-code/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    postalCodeInput.value = data;
                });
        });

        // Trigger change event if a province is already selected
        if (provinceSelect.value) {
            provinceSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
