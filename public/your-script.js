window.onload = function(){
    var flag = 1;

    // GET SCROLL EVENT
    window.onscroll = function () {
        if( ( flag == 1 && window.scrollY * 100 ) / document.body.scrollHeight >= 30){
          var xhttp = new XMLHttpRequest();
          // var locationHref = window.location.href;
          // if (localStorage.getItem("token") === null) {
          //   token = randomString(12);
          //   localStorage.setItem("token", token);
          // }else{
          //   token = localStorage.getItem("token");
          // }
          // console.log(token);

          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var response = JSON.parse(this.responseText);
              if(response.status == true){
                document.getElementById("vnbees-modal").innerHTML = response.body;
                flag = 0;
                // console.log(this.responseText)
                var modal = document.getElementById('vnbees-modal');

                // Get the button that opens the modal
                // var btn = document.getElementById("myBtn");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("vnbees-modal-close")[0];

                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }

                modal.style.display = "block";
              }
            }
          };
          xhttp.open("GET", "http://api.vnbees.com/generate-modal", true);
          xhttp.send();
          // show modal
        }
        // console.log(window.scrollY);
    };

    // When the user clicks the button, open the modal 
    // btn.onclick = function() {
    //     modal.style.display = "block";
    // }


  
  // code tracking sent to api
  var xhttp = new XMLHttpRequest();
  var locationHref = window.location.href;
  if (localStorage.getItem("token") === null) {
    token = randomString(12);
    localStorage.setItem("token", token);
  }else{
    token = localStorage.getItem("token");
  }
  console.log(token);

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // document.getElementById("demo").innerHTML =
      // this.responseText;
      console.log(this.responseText)
    }
  };
  xhttp.open("GET", "http://api.vnbees.com/tracking?url="+locationHref+"&token="+token, true);
  xhttp.send();
}


var randomString = function(length) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for(var i = 0; i < length; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}

// window.addEventListener("beforeunload", function (e) {
//     var confirmationMessage = 'It looks like you have been editing something. '
//                             + 'If you leave before saving, your changes will be lost.';

//     (e || window.event).returnValue = confirmationMessage; //Gecko + IE
//     return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
// });