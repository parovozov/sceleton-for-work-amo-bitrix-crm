<?php 
class CrmController
{	
	private $crmobj;
	public function __construct(string $crm)
	{	
			$filename = 'crm_classes/'.$crm.'.php';
			if(file_exists($filename)) {
				require_once($filename);
				
				if(class_exists($crm)){
					$this->crmobj = new $crm;
					if($this->crmobj instanceof CrmFoundation){
						return;
					}
					else 
						throw new \Exception('Подключенный класс '.$value.' должен быть наследником класса CrmFoundation. Предполагается добавить к классу '.$value.' extends CrmFoundation.');	
				}				
			}				
			else 
				throw new \Exception('Файл '.$filename.' не существует.');
	}
	public function LoginCrm($args)//логимся
	{
		$this->crmobj->LoginCrm($args);
	}
	public function CreateLidCrm()//создание лида
	{
		$this->crmobj->CreateLidCrm();
	}
	public function EditLidCrm()//обновление лида
	{
		$this->crmobj->EditLidCrm();
	}
	public function EditCommentLidCrm()//обновить комментарий лида (отправить переписку)
	{
		$this->crmobj->EditCommentLidCrm();
	}
	public function CreateBidCrm()//создание заявки
	{
		$this->crmobj->CreateBidCrm();
	}
	public function EditBidCrm()//обновление заявки
	{
		$this->crmobj->EditBidCrm();
	}
	public function GetStaffListCrm()//получение списка сотрудников
	{
		$this->crmobj->GetStaffListCrm();
	}
	public function GetPipelineListCrm()//получение воронок заявок
	{
		$this->crmobj->GetPipelineListCrm();
	}
	public function GetLidInfoCrm()//получение данных по лиду
	{
		$this->crmobj->GetLidInfoCrm();
	}
	public function GetBidInfoCrm()//получение данных по заявке
	{
		$this->crmobj->GetBidInfoCrm();
	}
	public function GetLidByTelefonCrm()//поиск лида по номеру телефона
	{
		$this->crmobj->GetLidByTelefonCrm();
	}
	
	public function GetAccessRefreshToken()
	{
		$this->crmobj->GetAccessRefreshToken();
	}
}

class DataRetain
{
	static $access_token;
	static $instance;
	function __construct(){
		if(empty(self::$instance)) {
			self::$instance = new DataRetain();
		}
		return self::$instance;
	}
	
	public function GetAccessTocen()
	{
		return self::$access_token;
	}
	public function SetAccessTocen($accesstoken)
	{
		self::$access_token=$accesstoken;
	}
}
	

try{
	$obj = new CrmController('AmoCrm');
	$args=[1,2,3];
	//$obj->LoginCrm($args);
	//$obj->GetAccessRefreshToken();
	
	$obj = new CrmController('BitrixCrm');
	$args=[3,4,5];
	$obj->LoginCrm($args);
}
catch (\Exception $e) {
echo ($e->__toString());
}


?>