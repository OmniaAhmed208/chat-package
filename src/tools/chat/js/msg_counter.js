function fetchNewMessages(routeUrl,id) {
    setInterval(function() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', routeUrl, true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          var unSeenUsersCount = response.unSeenUsersCount;
          var count = document.getElementById(id);
          count.innerHTML = unSeenUsersCount;
        }
      };
      xhr.send();
    }, 3000);
}