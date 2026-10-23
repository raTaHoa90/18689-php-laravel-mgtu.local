function cancel(event){
    event.stopPropagation();
    event.preventDefault();
    return false;
}


function ajaxPostForm(url, formData){
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
            location.reload();
        }
    });
}

function addProduct(idProduct){
    $.post('/add-product', {idProduct}, req => {
        if(req.ok)
            setProduct(req.count);
    }, 'json');
}

function setProduct(count){
    $('#countProduct').text('('+count+')');
}

function addProductForm(idProduct, formId){
    let form = new FormData($('#'+formId)[0]);
    form.append('idProduct', idProduct);
    ajaxPostForm('/add-product', form);
}


function sendRating(idProduct, stars){
    $.post('/rating-product', {idProduct, stars});
}
