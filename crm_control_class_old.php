<?php 
class CrmController
{
	public $crmTypes;
	public $crmObj=array();
	
	public function __construct(array $crms)
	{
		$this->crmTypes=$crms;		
		foreach($crms as $value){
			$filename = 'crm_classes/'.$value.'.php';
			if(file_exists($filename)) {
				require_once($filename);
				
				if(class_exists($value)){
					$obj = new $value;
					if($obj instanceof CrmFoundation){
						array_push($this->crmObj, $obj);
					}
					else 
						throw new \Exception('Подключенный класс '.$value.' должен быть наследником класса CrmFoundation. Предполагается добавить к классу '.$value.' extends CrmFoundation.');	
				}				
			}				
			else 
				throw new \Exception('Файл '.$filename.' не существует.');			
			
		}
	}

	public function LoginCrm($args)//логимся
	{
		$argmas = Arguments::getDataArg();
		foreach($this->crmObj as $obj){
			$classname = get_class($obj);
			if(array_key_exists($classname, $argmas)){
				$arguments=$argmas[$classname];
				// вызываем метод подключенных crm
				$obj->LoginCrm($arguments);
			}
				
		} 
	}
	public function CreateLidCrm()//создание лида
	{
		
	}
	public function EditLidCrm()//обновление лида
	{
		
	}
	public function EditCommentLidCrm()//обновить комментарий лида (отправить переписку)
	{
		
	}
	public function CreateBidCrm()//создание заявки
	{
		
	}
	public function EditBidCrm()//обновление заявки
	{
		
	}
	public function GetStaffListCrm()//получение списка сотрудников
	{
		
	}
	public function GetPipelineListCrm()//получение воронок заявок
	{
		
	}
	public function GetLidInfoCrm()//получение данных по лиду
	{
		
	}
	public function GetBidInfoCrm()//получение данных по заявке
	{
		
	}
	public function GetLidByTelefonCrm()//поиск лида по номеру телефона
	{
		
	}
}

class Arguments
{	
	
	static private $masArguments;
	
	public function __construct()
	{
		if(!empty(self::getDataArg())) 
			self::emptyDataArg();
	}
	
	static public function getDataArg(){
		return self::$masArguments;
	}
	static public function emptyDataArg(){
		self::$masArguments= array();
	}
	static public function setDataArg($classname, $position, $value){
		self::$masArguments[$classname][$position]= $value;
	}
	
	
	public function addArgument(string $classname, int $position, $value)
	{
		self::setDataArg($classname, $position, $value);
	}		
}


try{
	$obj = new CrmController($crmTypes);	
	$args = new Arguments();
	//addArgument подготавливает аргументы для класса (аргумент 1), позиция аргумента (аргумент 2). значение аргумента (аргумент 3). При создании нового экземпляра объекта new Arguments(), все предыдущие  аргументы обнуляются. 
	$args->addArgument('AmoCrm',0, 534);
	$args->addArgument('AmoCrm',1, 'Это второй аргумент для каласса AmoCrm');
	$args->addArgument('BitrixCrm',0, 'Это аргумент для каласса BitrixCrm');	
	$obj->LoginCrm($args);
}
catch (\Exception $e) {
echo ($e->__toString());
}


?>