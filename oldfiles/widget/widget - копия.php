<?php
/**
 * Class Widget
 * example widget class
 */
class Widget extends \Helpers\Widgets
{
	private
		$data;
	
	public static function ExceptionHandler(\Exception $E)
	{
		\Helpers\Debug::vars(\Helpers\I18n::get('exceptions.error').': '.$E->getMessage());
		die;
	}
	
	protected function endpoint_add()
	{
		set_exception_handler(array('\AddContact\Widget','ExceptionHandler'));
		$this->GetData();
		$custom_fields=$this->GetCustomFieldsInfo();
		if($this->IsContactExists())
			throw new \Exception(\Helpers\I18n::get('exceptions.user_already_exists'));
		$this->AddNewContact($custom_fields);
	}
	
	private function GetData()
	{
		#Получаем данные из POST-запроса
		$data=array(
			'name'=>isset($_POST['name']) ? $_POST['name'] : '',
			'company'=>isset($_POST['company']) ? $_POST['company'] : '',
			'position'=>isset($_POST['position']) ? $_POST['position'] : '',
			'phone'=>isset($_POST['phone']) ? $_POST['phone'] : '',
			'email'=>isset($_POST['email']) ? $_POST['email'] : '',
			'web'=>isset($_POST['web']) ? $_POST['web'] : '',
			'jabber'=>isset($_POST['jabber']) ? $_POST['jabber'] : '',
			'scope'=>isset($_POST['scope']) && is_array($_POST['scope']) ? $_POST['scope'] : array()
		);
		
		#Если не указано имя или e-mail контакта - уведомляем
		if(empty($data['name']))
			throw new \Exception(\Helpers\I18n::get('exceptions.name'));
		if(empty($data['email']))
			throw new \Exception(\Helpers\I18n::get('exceptions.email'));
		$this->data=$data;
	}
	
	private function GetCustomFieldsInfo()
	{
		#Получаем информацию по текущему аккаунту
		$account=$this->account->current();
		#Поля, ID которых нам нужно собрать
		$need=array_flip(array('POSITION','PHONE','EMAIL','WEB','IM','SCOPE'));
		if(isset($account['custom_fields'],$account['custom_fields']['contacts']))
			do
			{
				foreach($account['custom_fields']['contacts'] as $field)
					if(is_array($field) && isset($field['id']))
					{
						if(isset($field['code']) && isset($need[$field['code']]))
							$fields[$field['code']]=(int)$field['id'];
						#SCOPE - нестандартное поле, поэтому обрабатываем его отдельно
						elseif(isset($field['name']) && $field['name']==\Helpers\I18n::get('custom_fields.scope.name'))
							$fields['SCOPE']=$field;
							
						$diff=array_diff_key($need,$fields);
						if(empty($diff))
							break 2;
					}
				if(isset($diff))
					throw new \Exception(\Helpers\I18n::get('exceptions.custom_fields.unknown').': '.join(', ',$diff));
				else
					throw new \Exception(\Helpers\I18n::get('exceptions.custom_fields.undefined'));
			}
			while(false);
		else
			throw new \Exception(\Helpers\I18n::get('exceptions.custom_fields.undefined'));
		return isset($fields) ? $fields : false;
	}
	
	private function IsContactExists()
	{			
		$params=array(
			'query'=>$this->data['email']
		);
		if($contacts=$this->contacts->get($params))
			return$contacts;
		return false;
	}
	
	private function AddNewContact($custom_fields)
	{
		$contact=array(
			'name'=>$this->data['name'],
			'custom_fields'=>array(
				array(
					'id'=>$custom_fields['EMAIL'],
					'values'=>array(
						array(
							'value'=>$this->data['email'],
							'enum'=>'WORK'
						)
					)
				)
			)
		);
		
		if(!empty($this->data['company']))
			$contact+=array('company_name'=>$this->data['company']);
		
		if(!empty($this->data['position']))
			$contact['custom_fields'][]=array(
				'id'=>$custom_fields['POSITION'],
				'values'=>array(
					array(
						'value'=>$this->data['position']
					)
				)
			);
		
		if(!empty($this->data['phone']))
			$contact['custom_fields'][]=array(
				'id'=>$custom_fields['PHONE'],
				'values'=>array(
					array(
						'value'=>$this->data['phone'],
						'enum'=>'OTHER'
					)
				)
			);
			
		if(!empty($this->data['web']))
			$contact['custom_fields'][]=array(
				'id'=>$custom_fields['WEB'],
				'values'=>array(
					array(
						'value'=>$this->data['web']
					)
				)
			);
			
		if(!empty($this->data['jabber']))
			$contact['custom_fields'][]=array(
				'id'=>$custom_fields['IM'],
				'values'=>array(
					array(
						'value'=>$this->data['jabber'],
						'enum'=>'JABBER'
					)
				)
			);
		
		if(!empty($this->data['scope']))
		{
			foreach($this->data['scope'] as &$enum)
				$enum=trim(\Helpers\I18n::get('custom_fields.scope.fields.'.$enum));
			unset($enum);
			
			$intersect=array_intersect($custom_fields['SCOPE']['enums'],$this->data['scope']);
			
			foreach($intersect as $k=>$v)
				$values[]=array(
					'value'=>$v,
					'enum'=>$k
				);
			
			$scope=array(
				'id'=>(int)$custom_fields['SCOPE']['id'],
				'values'=>$values
			);
			
			$contact['custom_fields'][]=$scope;
		}
		
		$request['add'][]=$contact;
		$this->contacts->set($request);
	}
}
