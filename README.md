# sobi
PHP Wrapper around SocialBicycles API

## Reference
This package supports all API methods listed on https://app.socialbicycles.com/developer.
Check the link for more information about the API.

## Installation
Install the package using composer
```
composer require tomaszbe/sobi
```

## Example usage

```php
// Get a list of bike hubs in the network I'm subscribed to.

$hash = 'dXNlcm5hbWU6cGFzc3dv'; // "username:password" encoded with Base64
$api = new Sobi\API($hash);

$myNetworks = $api->networks(true);
$networkId = $myNetworks[0]->id;
$hubs = $api->networkHubs($networkId, ['per_page' => 200]);

echo $hubs->total_entries; // echoes 153
```

## API methods
For now, inspect src/API.php for the list of supported methods.
