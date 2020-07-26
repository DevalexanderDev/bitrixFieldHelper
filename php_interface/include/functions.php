<?php
function FieldBindToUser($fieldName, $defaultUserId){
    global $USER;
    $user = ($USER->GetByID($defaultUserId))->GetNext();
    $defaultUserName = $user['LAST_NAME']." ".$user['NAME'];

    require "html/FieldBindToUser.php";
}


function MultipleFieldBindToUser(string $fieldName, Array $arDefaultUserIds){
    global $USER;

    $arFilter = [
        'ID' => join(' | ', $arDefaultUserIds)
    ];
    $arParams = [
        'FIELD' => [
            'NAME', 'LAST_NAME', 'ID'
        ]
    ];

    $rsUsers = ($USER->GetList($by = [], $order = [], $arFilter));

    $arUsers = [];

    while($user = $rsUsers->GetNext()){
        $arUsers[] = $user;
    }

    d($arUsers);

    require "html/MultipleFieldBindToUser.php";
}