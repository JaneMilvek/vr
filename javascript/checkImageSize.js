let fileSizeLimit = 1.5 * 1024 * 1024;


// Lehe laadimisel deaktiveeritakse nupp
window.onload = function() {
    document.getElementById("photo_submit").disabled = true;
    document.getElementById("file_input").addEventListener("change", checkSize);
}

function checkSize() {
    if(document.getElementById("file_input").files[0].size <= fileSizeLimit) {
        document.getElementById("photo_submit").disabled = false;
        document.getElementById("notice").innerHTML = "";
    } else {
        document.getElementById("photo_submit").disabled = true;
        document.getElementById("notice").innerHTML = "Faili suurus ei ole sobiv";
    }
}