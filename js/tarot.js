document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
        domReady()
    }
};


function domReady() {
    console.log('We\'re good!');
    document.getElementById('selectAllWriters').addEventListener(
        'click', function() { handleSelectAllwriters(event); }, false
    );
}

function handleSelectAllwriters(event) {
    var t = event.target;
    var s = t.form.getElementsByTagName('input');
    for (var i of s) {
        if (i.type == 'checkbox') i.checked = t.checked;
    }
}