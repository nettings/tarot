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

// look at first script tag, assume it's ours,
// derive base URL
function getBaseUrl() {
        var u = document.getElementsByTagName('script')[0].src;
        return u.substr(0, u.indexOf('js/'));
}

function updateProgress() {
    var pbar = document.getElementById('progress_bar');
    var stat = document.getElementById('status');
    var bbtn = document.getElementById('back_button');
    var trmn = document.getElementById('time_remaining');
    var url = getBaseUrl() + 'progress.php';
    var done = false;
    var req = new XMLHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200) {
            var s;
            try {
                s = (JSON.parse(req.responseText));
            }
            catch (err) {
                console.log(err.message + ' while parsing JSON from ' + s);
                return;
            }
            pbar.textContent = s.percent_done + '%\u00A0';
            if (s.percent_done < 10) {
                pbar.style.width = '10%'
            } else if (s.percent_done > 99.9) {
                pbar.style.width = '100%'
                done = true;
            } else {
//                pbar.style.width = s.percent_done + '%';
                pbar.style.width = ( s.data_done / s.data_total * 100 ) + '%';
            }
            stat.textContent =
                'Wrote ' + s.data_done + ' of ' + s.data_total + 'MB.';
            trmn.textContent =
                'Estimated time remaining: '
                + ((s.hours_remaining) ? s.hours_remaining.toString() + ' hours, ' : '')
                + ((s.hours_remaining || s.minutes_remaining) ? s.minutes_remaining.toString().padStart(2, ' ') + ' minutes, ' : '')
                + s.seconds_remaining.toString().padStart(3, ' ') + ' seconds.';
        } else if (req.status == 204) {
            pbar.textContent = '';
            pbar.style.width = '100%'
            stat.textContent = 'No write in progress';
            trmn.textContent = '';
            done = true;
        }
    }
    var timer = setInterval(function() {
        if (done) {
            bbtn.style.display = 'block';
            clearInterval(timer);
            return;
        }
        req.open("GET", url, true);
        req.send();
    }, 500);
}
