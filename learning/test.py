import requests

def get_city_list(api_key, province_id=None, city_id=None):
    url = "https://api.rajaongkir.com/starter/city"
    headers = {
        "key": api_key
    }
    params = {}
    if province_id:
        params['province'] = province_id
    if city_id:
        params['id'] = city_id
    
    response = requests.get(url, headers=headers, params=params)
    
    if response.status_code == 200:
        return response.json()['rajaongkir']['results']
    else:
        return response.json()

# Contoh penggunaan
api_key = "f416a024129c0cea6c1351bf39ffb39d"
cities = get_city_list(api_key)
for city in cities:
    print(f"City ID: {city['city_id']} - {city['city_name']} ({city['type']}) - Province ID: {city['province_id']} - {city['province']} - Postal Code: {city['postal_code']}")

# Output contoh
# City ID: 1 - Banda Aceh (Kota) - Province ID: 21 - Aceh - Postal Code: 23000
