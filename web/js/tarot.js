document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
        domReady()
    }
};


function domReady() {
    console.log('We\'re good!');
    // automatically submit on image selection
    var elem = document.getElementById('image');
    if (elem) elem.addEventListener(
        'change',
        function() { handleSelectImage(event); },
        false
    );
    // "select all" checkbox for writer selection
    elem = document.getElementById('allwrt');
    if (elem) elem.addEventListener(
        'change',
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



function updateProgress() {
    var progress_bar = document.getElementById('progress_bar');
    var status = document.getElementById('status');
    var back_button = document.getElementById('back_button');
    var percent_done = 0;
    var time_remaining = 0;
    var resp;
    var time_remaining;
    var u = document.getElementsByTagName('script')[0].src;
    var url = u.substr(0, u.indexOf('js/')) + 'progress.php';
    var req = new XMLHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200) {
            var s;
            try {
                s = (JSON.parse(req.responseText));
                //console.log('We got: ' + req.responseText);
                percent_done = s.percent_done;
                time_remaining = s. time_remaining;
            } catch (err) {
                console.log(err.message + ' while parsing JSON from ' + s);
                return;
            }
            progress_bar.textContent = percent_done + '%';
            if (percent_done < 5)
                progress_bar.style.width = '5%'
            else if (percent_done > 99)
                progress_bar.style.width = '99.3%'
            else progress_bar.style.width = percent_done + '%';
            status.textContent =
                'Estimated time remaining: ' + s.time_remaining;
        }
    }
    var timer = setInterval(function() {
        if (percent_done >= 100) {
            back_button.style.display = 'block';
            //clearInterval(timer);
            return;
        }
        //console.log(url);
        req.open("GET", url, true);
        req.send();
    }, 1000);
}