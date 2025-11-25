import {ref} from 'vue';

export function useGeolocation(form, route) {
    const gettingLocation = ref(false);

    const getUserLocation = () => {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        gettingLocation.value = true;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                form.transform((data) => ({
                    ...data,
                    lat: position.coords.latitude,
                    lon: position.coords.longitude,
                    city: null
                })).get(route('weather.index'), {
                    onFinish: () => gettingLocation.value = false
                });
            },
            (error) => {
                console.error(error);
                alert('Could not access location.');
                gettingLocation.value = false;
            }
        );
    };

    return {
        gettingLocation,
        getUserLocation
    };
}
