<?php
use AmoCRM\OAuth2\Client\Provider\AmoCRM;
//название класса должно совпадать с названием файла, регистрозависимо
class AmoCrm extends CrmFoundation {
	function __construct()
	{
		include_once(__DIR__ .'/externalclass/vendor/autoload.php');
		include_once __DIR__ . '/externalclass/vendor/amocrm/oauth2-amocrm/src/AmoCRM.php';	
		
		$provider = new AmoCRM( [
			'clientId' => CLIENT_ID,
			'clientSecret' => CLIENT_SECRET,
			'redirectUri' => REDIRECT_URL,
		] );
		
		$accessToken = getToken();
		try {
			/**Делаем запрос к АПИ*/
			$data = $provider->getHttpClient()->request( 'GET', 'https://' . $accessToken->getValues()[ 'baseDomain' ] . '/api/v2/account', [
				'headers' => $provider->getHeaders( $accessToken )
			] );

			$parsedBody = json_decode( $data->getBody()->getContents(), true );

			printf( 'ID аккаунта - %s, название - %s', $parsedBody[ 'id' ], $parsedBody[ 'name' ] );
		} catch ( GuzzleHttp\ Exception\ GuzzleException $e ) {
			var_dump( ( string )$e );
		}
		
		if (isset($_GET['referer'])) {
			$provider->setBaseDomain($_GET['referer']);
		}
		
	}
	public function LoginCrm( array $arg ) //логимся
	{
		
	}
	public function CreateLidCrm() //создание лида
	{

	}
	public function EditLidCrm() //обновление лида
	{

	}
	public function EditCommentLidCrm() //обновить комментарий лида (отправить переписку)
	{

	}
	public function CreateBidCrm() //создание заявки
	{

	}
	public function EditBidCrm() //обновление заявки
	{

	}
	public function GetStaffListCrm() //получение списка сотрудников
	{

	}
	public function GetPipelineListCrm() //получение воронок заявок
	{

	}
	public function GetLidInfoCrm() //получение данных по лиду
	{

	}
	public function GetBidInfoCrm() //получение данных по заявке
	{

	}
	public function GetLidByTelefonCrm() //поиск лида по номеру телефона
	{

	}
}


?>