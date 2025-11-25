export const getAqiInfo = (aqi) => {
    const map = {
        1: {label: 'Good', color: 'text-emerald-600 bg-emerald-50 border-emerald-200'},
        2: {label: 'Fair', color: 'text-yellow-600 bg-yellow-50 border-yellow-200'},
        3: {label: 'Moderate', color: 'text-orange-600 bg-orange-50 border-orange-200'},
        4: {label: 'Poor', color: 'text-red-600 bg-red-50 border-red-200'},
        5: {label: 'Very Poor', color: 'text-purple-600 bg-purple-50 border-purple-200'}
    };

    return map[aqi] || {label: 'Unknown', color: 'text-gray-400 bg-gray-50 border-gray-200'};
};

export const getDayName = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {weekday: 'short'}).format(date);
};

export const formatTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', {hour: 'numeric', hour12: true});
};
