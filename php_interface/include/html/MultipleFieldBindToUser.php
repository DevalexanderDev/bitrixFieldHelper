<?$count = 0?>
<div id="multiple_<?=$fieldName?>_fields">
    <?foreach ($arUsers as $user):?>
        <div id="user_<?=$count?>">
            <span id="user_selected_<?= $fieldName?>_<?=$count?>">
                    <?= empty($user['NAME'])? '-': $user['LAST_NAME'].' '.$user['NAME'].'['.$user['ID'].']'?>
            </span>
            <input type="hidden" name="<?= $fieldName?>[<?=$count?>]" value="<?= $user['ID']?>">
            <input type="submit" value="Удалить" onclick="document.querySelector('#user_<?=$count?>').remove();return false">
        </div>
        <?$count++;?>
    <?endforeach;?>
    <div id="user_<?=$count?>">
        <span id="user_selected_<?= $fieldName?>_<?=$count?>">-</span>
        <input type="hidden" name="<?= $fieldName?>[<?=$count?>]" value="">

        <input placeholder="Вводите имя или фамилию" list="datalist-<?=$fieldName?>" type="text" name="__<?= $fieldName?>">
        <datalist id="datalist-<?=$fieldName?>"></datalist>
        <input id="add_new_<?=$fieldName?>" type="submit" value="Добавить еще" onclick="add_new_field_<?= $fieldName?>(); return false;">
    </div>
</div>
<script>
    let count<?=$fieldName?> = <?=$count?>;
    const inputField<?=$fieldName?> = document.querySelector("input[name='__<?= $fieldName?>']"),
          datalist<?=$fieldName?> = document.querySelector('#datalist-<?=$fieldName?>'),
          addNew<?=$fieldName?> = document.querySelector('#add_new_<?=$fieldName?>');

    function add_new_field_<?= $fieldName?>(){
        const inputCurrent = document.querySelector("input[name='<?= $fieldName?>[" + count<?=$fieldName?> + "]']");

        if(inputCurrent.getAttribute('value').length <= 0)
            return false;

        const submit_delete = document.createElement('input');
        submit_delete.setAttribute('value', 'Удалить');
        submit_delete.setAttribute('onclick', "document.querySelector('#user_" + count<?=$fieldName?> + "').remove();return false");
        submit_delete.setAttribute('type', 'submit');

        document.querySelector('#user_'+count<?=$fieldName?>).append(submit_delete);

        count<?=$fieldName?>++;

        const div_main = document.createElement('div'),
              user_selected = document.createElement('span'),
              input = document.createElement('input');

        div_main.setAttribute('id', 'user_'+count<?=$fieldName?>);

        user_selected.setAttribute('id', "user_selected_<?= $fieldName?>_"+count<?=$fieldName?>);
        user_selected.innerHTML = '-';

        input.setAttribute('type', 'hidden');
        input.setAttribute('name', "<?= $fieldName?>["+count<?=$fieldName?>+"]");

        inputField<?=$fieldName?>.value = '';
        datalist<?=$fieldName?>.innerHTML = '';

        div_main.append(user_selected);
        div_main.append(input);
        div_main.append(inputField<?=$fieldName?>);
        div_main.append(datalist<?=$fieldName?>);
        div_main.append(addNew<?=$fieldName?>);

        document.querySelector("#multiple_<?=$fieldName?>_fields").append(div_main);
    }

    var event<?=$fieldName?> = debounce(e=>{
        const outputField<?= $fieldName?> = document.querySelector("input[name='<?=$fieldName?>[" + count<?=$fieldName?> + "]']"),
              userSelected<?= $fieldName?> = document.querySelector('#user_selected_<?= $fieldName?>_' + count<?=$fieldName?>);

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