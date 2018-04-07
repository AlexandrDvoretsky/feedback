<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$res = CIBlockProperty::GetList(array("sort"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
while($ob = $res->fetch())
{
	$fields[] = $ob;
}
foreach ($fields as $val) {
	if(in_array($val["CODE"], $arParams["HIDE_PROPS"])) {
		continue;
	}
	$arResult["FIELDS"][$val["CODE"]]["NAME"] = $val["NAME"];
	if($val["DEFAULT_VALUE"] && !isset($val["DEFAULT_VALUE"]["TEXT"]))
		$arResult["FIELDS"][$val["CODE"]]["VALUE"] = $val["DEFAULT_VALUE"];
	elseif($val["DEFAULT_VALUE"]["TEXT"]) {
		$arResult["FIELDS"][$val["CODE"]]["VALUE"] = $val["DEFAULT_VALUE"]["TEXT"];
	}
	if($val["USER_TYPE"] == "HTML" || $val["DEFAULT_VALUE"]["TYPE"] == "HTML" || $val["DEFAULT_VALUE"]["TYPE"] == "TEXT") {
		$arResult["FIELDS"][$val["CODE"]]["TYPE"] = "T";
	} else {
		$arResult["FIELDS"][$val["CODE"]]["TYPE"] = $val["PROPERTY_TYPE"];
	}
	$arResult["FIELDS"][$val["CODE"]]["REQUIRED"] = $val["IS_REQUIRED"];
	if($val["IS_REQUIRED"] === "Y") {
		$arParams["REQUIRED_FIELDS"][$val["CODE"]] = $val["NAME"];
	}
	if($val["LIST_TYPE"] == "L") {
		$db_enum_list = CIBlockPropertyEnum::GetList(Array("sort"=>"asc"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>$val["CODE"]));
		while($ar_enum_list = $db_enum_list->fetch())
		{
		 	$arResult["FIELDS"][$val["CODE"]]["DEFAULT_VALUE"][$ar_enum_list["ID"]] = $ar_enum_list["VALUE"];
		}
	}
}
$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N") ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);
if($arParams["EVENT_NAME"] == '')
	$arParams["EVENT_NAME"] = "FEEDBACK_FORM";
$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if($arParams["EMAIL_TO"] == '')
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");
$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);
if($arParams["OK_TEXT"] == '')
	$arParams["OK_TEXT"] = GetMessage("MF_OK_MESSAGE");
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] <> '' && (!isset($_POST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_POST["PARAMS_HASH"]))
{
	$arResult["ERROR_MESSAGE"] = array();
	if(check_bitrix_sessid())
	{
		if(!$_POST["PERSONAL_DATA"]) {
			$arResult["ERROR_MESSAGE"][] = GetMessage("MF_PERSONAL_DATA_NOT_VALID");
		} else {
			$ibProps = array();
			foreach($_POST as $key => $prop) {
				if($key == "EMAIL" && strlen($prop) > 1 && !check_email($prop))
					$arResult["ERROR_MESSAGE"][] = GetMessage("MF_EMAIL_NOT_VALID");
				elseif(in_array($key, array_keys($arParams["REQUIRED_FIELDS"])) && strlen($prop) <= 3)
					$arResult["ERROR_MESSAGE"][] = str_replace("#PROPERTY_NAME#", $arParams["REQUIRED_FIELDS"][$key], GetMessage("MF_REQ_PROPERTY"));
				elseif($arResult["FIELDS"][$key] && $prop) {
					$arResult["FIELDS"][$key]["VALUE"] = htmlspecialcharsbx($prop);
					if($arResult["FIELDS"][$key]["TYPE"] == "L") {
						foreach ($arResult["FIELDS"][$key]["DEFAULT_VALUE"] as $id => $value) {
							if($id == $prop || $value == $prop)
								$ibProps[$key] = Array("VALUE" => $id);
						}
					}
					elseif($arResult["FIELDS"][$key]["TYPE"] == "T")
						$ibProps[$key][0] = Array("VALUE" => Array ("TEXT" =>htmlspecialcharsbx($prop), "TYPE" => "text"));
					else
						$ibProps[$key] = htmlspecialcharsbx($prop);
				}
			}
			foreach($_FILES as $key => $file) {
				if($arResult["FIELDS"][$key] && strlen($file["name"]) > 1)
					$ibProps[$key] = $file;
			}
			if($arParams["USE_CAPTCHA"] == "Y")
			{
				$captcha_code = $_POST["captcha_sid"];
				$captcha_word = $_POST["captcha_word"];
				$cpt = new CCaptcha();
				$captchaPass = COption::GetOptionString("main", "captcha_password", "");
				if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0)
				{
					if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
						$arResult["ERROR_MESSAGE"][] = GetMessage("MF_CAPTCHA_WRONG");
				}
				else
					$arResult["ERROR_MESSAGE"][] = GetMessage("MF_CAPTHCA_EMPTY");

			}	
			if(empty($arResult["ERROR_MESSAGE"]))
			{
				$el = new CIBlockElement;
				$arLoadElementArray = Array(
					"IBLOCK_ID"      => $arParams["IBLOCK_ID"],
					"PROPERTY_VALUES"=> $ibProps,
					"NAME"           => "Сообщение с формы обратной связи",
					"ACTIVE"         => "N",
				);
				if($elementId = $el->Add($arLoadElementArray)) {
					$arPostFields["ID"] = $elementId;
					if($_POST["EMAIL"])
						$arPostFields["EMAIL"] = $_POST["EMAIL"];
					if(!empty($arParams["EVENT_MESSAGE_ID"]))
					{
						foreach($arParams["EVENT_MESSAGE_ID"] as $v)
							if(IntVal($v) > 0)
								CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arPostFields, "N", IntVal($v));
					}
					else
						CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arPostFields);
				
					LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));
				}
				else 
					$arResult["ERROR_MESSAGE"][] = $elementId->LAST_ERROR;
			}
		}
	}
	else
		$arResult["ERROR_MESSAGE"][] = GetMessage("MF_SESS_EXP");
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = $arParams["OK_TEXT"];
}

if($arParams["USE_CAPTCHA"] == "Y")
	$arResult["capCode"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());

$this->IncludeComponentTemplate();
