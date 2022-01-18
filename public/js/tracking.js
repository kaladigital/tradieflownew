/**IE Support*/


class AcKdTrack {
    host = 'https://codebonapp.com/api/test';
    user_ip = null;
    ac_kd_uuid = null;
    constructor(document) {
        this.user_ip = document.currentScript.getAttribute('ip');
        this.ac_kd_uuid = document.currentScript.getAttribute('ac_kd_uuid');
        if (!this.ac_kd_uuid) {
            this.ac_kd_uuid = localStorage.getItem('ac_kd_uuid');
        }
    }

    addEvent(data) {
        console.log('sending');
        var xhr = new XMLHttpRequest();
            xhr.open('POST', this.host, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('ip=' + this.user_ip + '&ac_kd_uuid=' + this.ac_kd_uuid);
    }
}

document.currentScript = document.currentScript || (function() {
    var scripts = document.getElementsByTagName('script');
    return scripts[scripts.length - 1];
})();

window.ac_track_object = new AcKdTrack(document);
// let event = {
//     type : 'visit',
//     page: location.href
// }
// ac_track_object.addEvent(event);
