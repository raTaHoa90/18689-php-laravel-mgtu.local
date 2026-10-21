
function requestProdJSON(req){
    if(req.error)
        console.log(req.error);
    else
        location.reload();
}

function incProduct(idProd){
    $.post('/inc-product', {idProduct: idProd}, requestProdJSON);
}

function decProduct(idProd){
    $.post('/dec-product', {idProduct: idProd}, requestProdJSON);
}

function setProduct(idProd, value){
    $.post('/set-product', {idProduct: idProd, value}, requestProdJSON);
}
