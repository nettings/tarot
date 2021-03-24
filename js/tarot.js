document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
        domReady()
    }
};


function domReady() {
    console.log('We\'re good!');
    // automatically submit on image selection
    document.getElementById('image').addEventListener(
        'change',
        function() { handleSelectImage(event); },
        false
    );
    // "select all" checkbox for writer selection
    document.getElementById('selectAllWriters').addEventListener(
        'click',
        function() { handleSelectAllwriters(event); },
        false
    );
}


function handleSelectAllwriters(event) {
    var t = event.target;
    var s = t.form.getElementsByTagName('input');
    for (var i of s) {
        if (i.type == 'checkbox' && i.disabled != true) i.checked = t.checked;
    }
}


function handleSelectImage(event) {
    event.target.form.submit();
}