<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");

global $APPLICATION;
global $USER;

$APPLICATION->RestartBuffer();

$request = json_decode(file_get_contents('php://input'),true);

if($request['field_type'] === 'bindToUser'){
    $arNames = explode(' ', preg_replace('/\s+/', ' ',  trim($request['field_value'])));
    $arFilter = [];

    $arFilter["NAME"] = join(' | ', $arNames);

    $arParams = [
        'FIELDS' => [
            'NAME', 'LAST_NAME','ID'
        ],
        'NAV_PARAMS' => [
            'nPageSize' => "20"
        ]
    ];

    $rsUsers = $USER->GetList($by = [],$order = [], $arFilter, $arParams);

    while($user = $rsUsers->GetNext()){
        foreach ($user as $key => $value){
            if(empty($value))
                $user[$key] = '';
        }
        $arUsers[] = $user;
    }

    echo json_encode([
        'status' => count($arUsers) > 0? true: false,
        'data' => $arUsers
    ]);
    die();
}
?>