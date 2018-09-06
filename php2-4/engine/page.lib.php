<?php


function renderPage($pageName, $variables, $isJson = false)
{
    $fullResult = null;

    if(!$isJson) {

        $file = TPL_DIR . '/' . $pageName . '.tpl';

        if (!is_file($file)) {
            echo 'Template не найден!';
            exit(ERROR_NOT_FOUND);
        }

        if (filesize($file) === 0) {
            echo 'Template пуст!';
            exit(ERROR_TEMPLATE_EMPTY);
        }

        //Если переменных нет, просто возвращаем шаблон
        $templateContent = file_get_contents($file);
        if (!empty($variables)) {
            //Производим замену меток значениями переменных
            $templateContent = pasteValues($variables, $pageName, $templateContent);
        }
        $fullResult = $templateContent;
    } else {
        //AJAX and JSON
        $fullResult = $variables['response_json'];
        $fullResult = json_encode($fullResult);
    }
    return $fullResult;
}

function pasteValues($variables, $pageName, $templateContent)
{
    foreach ($variables as $key => $value) {
        $pKey = '{{' . strtoupper($key) . '}}';

        if(is_array($value)){
            $result = '';
            foreach ($value as $valueKey => $item){
                $itemTemplateContent = file_get_contents(TPL_DIR . '/' . $pageName . '_' . $key . '_item.tpl');

                //Замена переменных в подмакете
                foreach ($item as $itemKey => $itemValue){
                    $iKey = '{{' . strtoupper($itemKey) . '}}';
                    $itemTemplateContent = str_replace($iKey, $itemValue, $itemTemplateContent);
                }

                $result .= $itemTemplateContent;
            }
        } else{
            $result = $value;
        }

        $templateContent = str_replace($pKey, $result, $templateContent);
    }
    return $templateContent;
}