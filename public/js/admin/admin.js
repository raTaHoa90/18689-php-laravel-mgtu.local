
function cancel(event){
    event.stopPropagation();
    event.preventDefault();
    return false;
}


function ajaxPostForm(url, formData, dlgID){
    $.ajax({
        type: 'POST',
        url: url ,
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        dataType: 'json',
        success: req => {
            console.log(req);
            if(addError(req.error)) return;
            location.reload();
        }
    });
    $(dlgID)[0].close();
}

function closeError(button){
    button.parentElement.remove();
}

function addError(error){
    if(error){
        $('#error_display').append(`<div class="alert alert-danger d-flex align-items-center" role="alert">
    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
    <div style="margin-left: 10px">
    ${error}
    </div>
    <button type="button" class="btn-close" aria-label="Close" onclick="closeError(this)" style="position: absolute; right:10px"></button>
</div>`);
        return true;
    }
    return false;
}
