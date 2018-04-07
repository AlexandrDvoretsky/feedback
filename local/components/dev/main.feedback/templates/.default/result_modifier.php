<?
foreach ($arResult["FIELDS"] as $key => $val) {
	switch($val["TYPE"])
	{
		case "S":
			$arResult["FIELDS"][$key]["DISPLAY_VALUE"] = "<input type='text' name='".$key."' value='".$val["VALUE"]."' />";
			break;
		case "N":
			$arResult["FIELDS"][$key]["DISPLAY_VALUE"] = "<input type='number' name='".$key."' value='".$val["VALUE"]."' />";
			break;
		case "L":
			if(count($val["DEFAULT_VALUE"]) == "1") {
				$first = array_shift($val["DEFAULT_VALUE"]);
				if($first == "Y" || $first == "N") {
					$arResult["FIELDS"][$key]["DISPLAY_VALUE"] = "<input type='checkbox' name='".$key."' value='".$first."' ";
					if($first=="Y")
						$arResult["FIELDS"][$key]["DISPLAY_VALUE"] .=' checked';
					$arResult["FIELDS"][$key]["DISPLAY_VALUE"] .= ">";
					$arResult["FIELDS"][$key]["CHECKBOX"] = "Y";
				}
			}
			elseif(is_array($val["DEFAULT_VALUE"])) {
				$type_select = "<select name='".$key."'>";
				foreach ($val["DEFAULT_VALUE"] as $id => $value) {
					$type_select .= "<option value='".$id."'>".$value."</option>";
				}			
				$type_select .= "</select>";
				$arResult["FIELDS"][$key]["DISPLAY_VALUE"] = $type_select;
				unset($type_select);
			}
			break;
		case "T": 
			$arResult["FIELDS"][$key]["DISPLAY_VALUE"] = "<textarea name='".$key."' rows='5' cols='40'>".$val["VALUE"]."</textarea>";
			break;
		case "F":
			$arResult["FIELDS"][$key]["DISPLAY_VALUE"] = "<input type='file' name='".$key."'/>";
			break;
	}
}
?>