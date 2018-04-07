<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

?>
<div class="mfeedback">
	<form action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data" id="contactForm">
	<?=bitrix_sessid_post()?>
		<?if($arResult["FIELDS"]):?>
			<ul>
				<?foreach($arResult["FIELDS"] as $key => $val):?>
					<?if(!$val["CHECKBOX"]):?>
						<li>
						  <label for="<?=$key?>"><?=$val["NAME"]?><?if($val["REQUIRED"] == "Y"):?><span class="mf-req">*</span><?endif?></label>
						  <?=$val["DISPLAY_VALUE"]?>
						</li>
					<?else:?>
					 <?$checkbox[] = $key;?>
					<?endif;?>
				<?endforeach;?>
				<?if($arParams["USE_CAPTCHA"] == "Y"):?>
				<li>
					<label for="captcha_pict"><?=GetMessage("MFT_CAPTCHA")?></label>
					<input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
					<img id="captcha_pict" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
				</li>
				<li>			
					<label for="captcha_word"><?=GetMessage("MFT_CAPTCHA_CODE")?><span class="mf-req">*</span></label>
					<input type="text" id="captcha_word" name="captcha_word" maxlength="50" value="">
				</li>
				<?endif;?>
				<?if(is_array($checkbox)):?>
					<?foreach($checkbox as $val):?>
						<li class="checkbox">
							<?=$arResult["FIELDS"][$val]["NAME"]?>
							<?if($arResult["FIELDS"][$val]["REQUIRED"] == "Y"):?><span class="mf-req">*</span><?endif?>
							<?=$arResult["FIELDS"][$val]["DISPLAY_VALUE"]?>
						</li>	
					<?endforeach;?>
				<?endif;?>
				<li class="checkbox"><?=GetMessage("MFT_PERSONAL_DATA")?><input type="checkbox" name="PERSONAL_DATA"></li>
			</ul>
		<?endif;?>
		<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
		<?if(!empty($arResult["ERROR_MESSAGE"]))
		{
			foreach($arResult["ERROR_MESSAGE"] as $v)
				ShowError($v);
		}
		if(strlen($arResult["OK_MESSAGE"]) > 0)
		{
			?><div class="mf-ok-text"><?=$arResult["OK_MESSAGE"]?></div><?
		}
		?>
		<input type="submit" name="submit" value="<?=GetMessage("MFT_SUBMIT")?>">
	</form>
</div>