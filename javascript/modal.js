let modal;
let modalImg;
let captionText;
let photoId;
let photoDir = "../upload_photos_normal";

window.onload = function() {
    modal = document.getElementById("modalarea");
    modalImg = document.getElementById("modalimg");
    captionText = document.getElementById("modalcaption");

    // lisame kõigile thumbnailidele kliki kuulaja
    let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
    for(let i = 0; i < allThumbs.length; i ++) {
        allThumbs[i].addEventListener("click", openModal);
    }
    document.getElementById("modalclose").addEventListener("click", closeModal);
}

function opneModal() {
    modalImg.scr = photoDir + e.target.dataset.fn;
    photoId = e.target.dataset.id;
    captionText.innerHTML = e.target.alt;
    
    // nullin hindamise osa
    document.getElementById("avgRating").innerHTML = "";
    for(let i = 1; i < 6; i++) {
        document.getElementById("rate" +i).checked = false;
    }

    document.getElementById("storeRating").addEventListener("click", storeRating);
    modal.style.display = "block";
}

function closeModal() {
    //document.getElementById("modalclose").removeEventListener("click", closeModal);  // hetkel valja kommenteeritud et saaks kinni panna
    document.getElementById("storeRating").removeEventListener("click", storeRating);
    modal.style.display = "none";
    modalImg.src = "images/empty.png";
}

function storeRating() {
    let rating = 0;
    for(let i = 1; i < 6; i ++) {
        if(document.getElementById("rate + i").checked) {
            rating = i;
        }
        if(rating > 0) {
            // AJAX / hinnangu sidumine pildiga
            let webRequest = new XMLHttpRequest();
            webRequest.onreadystatechange = function() {
                // kas onnestus
                if(this.readyState == 4 && this.status == 200) {            // 4 tahendab et paring on serveri poolt toodeldud 
                    // mida teeme, kui onnestus
                    document.getElementById("avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
                }
            };
            webRequest.open("GET", "store_photorating.php?rating=" + rating + "&photoid=" + photoId, true);
            webRequest.send();

            //AJAX loppeb
        }
    }
}