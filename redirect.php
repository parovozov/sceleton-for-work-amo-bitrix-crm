<?php
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

include_once( __DIR__ . '/crm_base.php' );
include_once(__DIR__ .'/externalclass/vendor/autoload.php');
include_once __DIR__ . '/externalclass/vendor/amocrm/oauth2-amocrm/src/AmoCRM.php';

use AmoCRM\OAuth2\Client\Provider\AmoCRM;
session_start();
/*Создаем провайдера*/
$provider = new AmoCRM( [
	'clientId' => CLIENT_ID,
	'clientSecret' => CLIENT_SECRET,
	'redirectUri' => REDIRECT_URL,
] );


//var_dump( $_POST );
//var_dump( $_GET );
if (isset($_GET['referer'])) {
    $provider->setBaseDomain($_GET['referer']);
}

if ( isset( $_GET[ 'code' ] ) ) {
	try {
        /** @var \League\OAuth2\Client\Token\AccessToken $access_token */
        $accessToken = $provider->getAccessToken(new League\OAuth2\Client\Grant\AuthorizationCode(), [
            'code' => $_GET['code'],
        ]);

        if (!$accessToken->hasExpired()) {
            saveToken([
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $provider->getBaseDomain(),
            ]);
        }
    } catch (Exception $e) {
        die((string)$e);
    }
	
	$ownerDetails = $provider->getResourceOwner($accessToken);
    printf('Hello, %s!', $ownerDetails->getName());
	header('Location: ' . '/');

}

function saveToken($accessToken)
{
    if (
        isset($accessToken)
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
        $data = [
            'accessToken' => $accessToken['accessToken'],
            'expires' => $accessToken['expires'],
            'refreshToken' => $accessToken['refreshToken'],
            'baseDomain' => $accessToken['baseDomain'],
        ];

        file_put_contents(TOKEN_FILE, json_encode($data));
    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}

function getToken()
{
    $accessToken = json_decode(file_get_contents(TOKEN_FILE), true);

    if (
        isset($accessToken)
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
        return new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $accessToken['accessToken'],
            'refresh_token' => $accessToken['refreshToken'],
            'expires' => $accessToken['expires'],
            'baseDomain' => $accessToken['baseDomain'],
        ]);
    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}

?>