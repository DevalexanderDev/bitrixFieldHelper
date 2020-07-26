<span id="user_selected_<?= $fieldName?>"><?= empty($defaultUserName)? '-': $defaultUserName.'['.$defaultUserId.']'?></span>
<input placeholder="Вводите имя или фамилию" list="datalist-<?=$fieldName?>" type="text" name="__<?= $fieldName?>" value="<?= $defaultUserName?>">
<input type="hidden" name="<?= $fieldName?>" value="<?= $defaultUserID?>">
<datalist id="datalist-<?=$fieldName?>"></datalist>
<script>
    const inputField<?=$fieldName?> = document.querySelector("input[name='__<?= $fieldName?>']"),
          outputField<?= $fieldName?> = document.querySelector("input[name='<?=$fieldName?>']"),
          datalist<?=$fieldName?> = document.querySelector('#datalist-<?=$fieldName?>'),
          userSelected<?= $fieldName?> = document.querySelector('#user_selected_<?= $fieldName?>');

    var event<?=$fieldName?> = debounce(e=>{
        outputField<?= $fieldName?>.value = '';
        userSelected<?= $fieldName?>.innerHTML = '-';

        datalist<?=$fieldName?>.childNodes.forEach(option=>{
            if (option.getAttribute('value') === inputField<?=$fieldName?>.value){
                outputField<?= $fieldName?>.value = option.getAttribute('data_id');
                userSelected<?= $fieldName?>.innerHTML = option.getAttribute('value')+'['+option.getAttribute('data_id')+']';
            }
        });

        datalist<?=$fieldName?>.innerHTML = '';
        const data = {
            field_type: "bindToUser",
            field_value: e.target.value,
        };
        fetch(ajaxPathUrl, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response=>{return response.json()})
            .then(data=>{
                if(data['status']){
                    datalist<?=$fieldName?>.innerHTML = '';
                    data['data'].map(user=>{
                       let option = document.createElement('option');
                       option.setAttribute('data_id', user['ID']);
                       option.setAttribute('value', user['LAST_NAME']+" "+user['NAME']);
                       datalist<?=$fieldName?>.append(option);
                    });
                }
            });
    }, 500);

    inputField<?=$fieldName?>.addEventListener('input', event<?=$fieldName?>);
</script>