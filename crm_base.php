<?php 
define('TOKEN_FILE', __DIR__ .'/tmp' . DIRECTORY_SEPARATOR . 'token_info.json');
define('CLIENT_ID', '2ddadf31-c7eb-4654-a356-fe9f236c988f');
define('CLIENT_SECRET', 'R7DZbd1vRMXQqrgOrmyTTKUgWIXNK9qB7qsyBIHv62faacQSofdbv9V9Uf3lQsGw');
define('REDIRECT_URL', 'https://test.kuznecov.izzibot.com/');
abstract class CrmFoundation
{
	abstract public function LoginCrm(array $arg);//логимся
	abstract public function CreateLidCrm();//создание лида
	abstract public function EditLidCrm();//обновление лида
	abstract public function EditCommentLidCrm();//обновить комментарий лида (отправить переписку)
	abstract public function CreateBidCrm();//создание заявки
	abstract public function EditBidCrm();//обновление заявки
	abstract public function GetStaffListCrm();//получение списка сотрудников
	abstract public function GetPipelineListCrm();//получение воронок заявок
	abstract public function GetLidInfoCrm();//получение данных по лиду
	abstract public function GetBidInfoCrm();//получение данных по заявке
	abstract public function GetLidByTelefonCrm();//поиск лида по номеру телефона
}

?>