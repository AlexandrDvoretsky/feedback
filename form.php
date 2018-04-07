<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->IncludeComponent("dev:main.feedback","",Array(
        "USE_CAPTCHA" => "Y",
        "OK_TEXT" => "Спасибо, ваше сообщение принято.",
        "EVENT_NAME" => "FEEDBACK_FORM",
        "IBLOCK_ID" => 37,
		"HIDE_PROPS" => array("SURNAME"),
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>