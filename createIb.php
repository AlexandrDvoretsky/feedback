<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
if (CModule::IncludeModule('iblock')) {   
	$obIBlockType =  new CIBlockType;
	$arFields = Array(
		"ID"=>"feedback",
		"SECTIONS"=>"Y",
		"LANG"=>Array(
			"ru"=>Array(
				"NAME"=>"Формы обратной связи",               
			)   
		)
   );
	$res = $obIBlockType->Add($arFields);
	if(!$res){ 
		$error = $obIBlockType->LAST_ERROR;
	}else {
		$obIblock = new CIBlock;
		$arFields = Array(
			"NAME"=> "Форма обратной связи",
			"CODE" => "feedback_form",
			"ACTIVE" => "Y",
			"IBLOCK_TYPE_ID" => "feedback",
			"SITE_ID" => SITE_ID
		);
		$newIblockID = $obIblock->Add($arFields);
	   
		if($newIblockID > 0) {
			$ibp = new CIBlockProperty;
			$arFields = Array(
				"NAME" => "Имя",
				"ACTIVE" => "Y",
				"SORT" => "100",
				"CODE" => "NAME",
				"PROPERTY_TYPE" => "S",
				"IS_REQUIRED" => "Y",
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Фамилия",
				"ACTIVE" => "Y",
				"SORT" => "150",
				"CODE" => "SURNAME",
				"PROPERTY_TYPE" => "S",
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Телефон",
				"ACTIVE" => "Y",
				"SORT" => "200",
				"CODE" => "PHONE",
				"PROPERTY_TYPE" => "S",
				"IS_REQUIRED" => "Y",
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Email",
				"ACTIVE" => "Y",
				"SORT" => "300",
				"CODE" => "EMAIL",
				"PROPERTY_TYPE" => "S",
				"IS_REQUIRED" => "Y",				
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Индекс",
				"ACTIVE" => "Y",
				"SORT" => "400",
				"CODE" => "POST_INDEX",
				"PROPERTY_TYPE" => "N",
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Адрес",
				"ACTIVE" => "Y",
				"SORT" => "500",
				"CODE" => "ADDRESS",
				"PROPERTY_TYPE" => "S",
				"IS_REQUIRED" => "Y",
				"DEFAULT_VALUE" => "Минск, пр.Независимости 1",
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Сообщение",
				"ACTIVE" => "Y",
				"SORT" => "600",
				"CODE" => "MESSAGE",
				"PROPERTY_TYPE" => "S",
				"USER_TYPE" => "HTML",
				"DEFAULT_VALUE" => array("TYPE" => "HTML"),				
				"IS_REQUIRED" => "Y",
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Тема обращения",
				"ACTIVE" => "Y",
				"SORT" => "700",
				"CODE" => "SUBJECT",
				"PROPERTY_TYPE" => "L",
				"IBLOCK_ID" => $newIblockID,
				"VALUES" => Array(
					Array(
						"VALUE" => "Другая",
						"DEF" => "N",
						"SORT" => "100"
					),
					Array(
						"VALUE" => "Вопрос",
						"DEF" => "N",
						"SORT" => "200"
					),
					Array(
						"VALUE" => "Жалоба",
						"DEF" => "N",
						"SORT" => "300"
					),
					Array(
						"VALUE" => "Предложение",
						"DEF" => "N",
						"SORT" => "400"
					),
				)
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Город",
				"ACTIVE" => "Y",
				"SORT" => "800",
				"CODE" => "CITY",
				"PROPERTY_TYPE" => "L",
				"IBLOCK_ID" => $newIblockID,
				"VALUES" => Array(
					Array(
						"VALUE" => "Москва",
						"DEF" => "N",
						"SORT" => "100"
					),
					Array(
						"VALUE" => "Тула",
						"DEF" => "N",
						"SORT" => "200"
					),
					Array(
						"VALUE" => "Тверь",
						"DEF" => "N",
						"SORT" => "300"
					),
					Array(
						"VALUE" => "Владимир",
						"DEF" => "N",
						"SORT" => "400"
					),
				)
			);
			$PropID = $ibp->Add($arFields);
			$arFields = Array(
				"NAME" => "Файл",
				"ACTIVE" => "Y",
				"SORT" => "900",
				"CODE" => "FILE",
				"PROPERTY_TYPE" => "F",
				"FILE_TYPE" => "jpg, gif, bmp, png, jpeg", 
				"IBLOCK_ID" => $newIblockID,
			);
			$PropID = $ibp->Add($arFields);
		}
	}
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>