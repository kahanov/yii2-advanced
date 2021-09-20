function resultFunction(obj, formId) {
    /*	console.log(obj.geometry.getCoordinates().join(', '));
        console.log(obj.getCountry());
        console.log(obj.getAdministrativeAreas()[0]);
        console.log(obj.getAdministrativeAreas()[1]);
        console.log(obj.getLocalities().join(', '));
        console.log(obj.getThoroughfare());
        console.log(obj.getPremiseNumber());*/
    var country = obj.getCountry(),
        region = obj.getAdministrativeAreas()[0],
        district = obj.getAdministrativeAreas()[1],
        city = obj.getLocalities()[0] || obj.getAdministrativeAreas()[0],
        street = obj.getThoroughfare() || obj.getLocalities()[1],
        house_number = obj.getPremiseNumber(),
        coordinates = obj.geometry.getCoordinates().join(', '),
        data = {
            'GeoSearchForm': {
                'country': country,
                'region': region,
                'district': district,
                'city': city,
                'street': street,
                'house_number': house_number,
                'coordinates': coordinates,
                'is_yandex': 1,
            }
        };
    console.log(data);
    geoAjaxPost(data, formId);
}

function geoAjaxPost(data, formId) {
    var $countryInput = $('#country_id-input'),
        $regionInput = $('#region_id-input'),
        $districtInput = $('#district_id-input'),
        $cityInput = $('#city_id-input'),
        $streetIdInput = $('#street_id-input'),
        $houseInput = $('#house_number-input'),
        $coordinatesInput = $('#coordinates-input');
    $countryInput.val('');
    $regionInput.val('');
    $districtInput.val('');
    $cityInput.val('');
    $streetIdInput.val('');
    $houseInput.val('');
    $coordinatesInput.val('');
    $.post('/helper/search-address', data, function (response) {
        try {
            if (response.status == 'err') {
                $('#' + formId).yiiActiveForm('updateAttribute', 'address', [response.error]);
            } else {
                $countryInput.val(response.country_id);
                $regionInput.val(response.region_id);
                $districtInput.val(response.district_id);
                $cityInput.val(response.city_id);
                $streetIdInput.val(response.street_id);
                $houseInput.val(response.house_number);
                $coordinatesInput.val(response.coordinates);
                $('#' + formId).yiiActiveForm('validateAttribute', 'address');
            }
            $('.block__preloader').hide();
        } catch (e) {
            console.log('Error: ' + e.message);
        }
    });
}
