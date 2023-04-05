window.onload=function(){
  navigation(window.innerWidth);

  window.addEventListener('resize', function() {
    // Get the current window width
    var windowWidth = window.innerWidth;
    //console.log(windowWidth);
    navigation(windowWidth);
  })

  function navigation(value){
    var hamburger_icon = document.getElementsByClassName("logo_container")[0]
    if (value < 900) {
      var close = true;
      if (hamburger_icon){
        hamburger_icon.addEventListener("click", 
          function(){ 
            if (close == true){
              expand();
              close = false;
            }
            else{
              shrink();
              close = true;
            }
          }
        );
      }
      shrink();
    }
    else{
      if (hamburger_icon){
        hamburger_icon.removeEventListener("click", expand);
        hamburger_icon.removeEventListener("click", shrink);
      }
      expandMore();
    }
  }

  function expand() {
    document.getElementsByClassName("navigation_bar")[0].style.width = "100%";
    document.getElementsByClassName("navigation_bar")[0].style.height = "auto";
    document.getElementsByClassName("navigation_bar")[0].style.padding = "0px";
    document.getElementsByClassName("navigation_bar")[0].style.margin = "0px";
    document.getElementsByClassName("logo_container")[0].style.margin = "30px 50px 20px 50px";
    document.getElementsByClassName("navigation_bar")[0].style.position = "static";
    document.getElementsByClassName("navigation_bar")[0].style.marginBottom = "40px";
    document.getElementsByClassName("logo_container")[0].innerHTML = `
    <span style="display:flex;">
      <span><i class="fa fa-xmark" id="hamburger_icon"></i></span>
      <span style="width:100%; position:relative; top:-2px; left:-30px;">
        <span style="display:flex; gap:5px;">
          <div><img style="width:150px" src="../images/naim.png" class="naim_logo"></img></div>
          <div style="display:flex;">
            <span class="logo" style="font-size: 1.8rem;" ><span class="logo_initial">V</span><span>ISION</span></span> 
            <span style="font-size: 0.6rem; color: #C5E5CC"><span>ANPR</span></span>
          </div>   
        </span>
      </span>
    </span>`;
    var angle_right = document.getElementsByClassName("fa-angle-right");
    for (var i = 0; i < angle_right.length; i++) {
      angle_right[i].style.float = "right";
    }
    var sub_menu = document.getElementsByClassName("sub_menu");
    for (var i = 0; i < sub_menu.length; i++) {
      sub_menu[i].style.paddingLeft = "85px";
    }
    document.getElementsByClassName("navigation_links_container")[0].style.display = "block";
    document.getElementsByClassName("navigation_links_container")[0].style.marginTop = "5%";
  }
  
  function shrink(){
    document.getElementsByClassName("navigation_bar")[0].style.width = "100%";
    document.getElementsByClassName("navigation_bar")[0].style.height = "auto";
    document.getElementsByClassName("navigation_bar")[0].style.padding = "0px";
    document.getElementsByClassName("navigation_bar")[0].style.margin = "0px";
    document.getElementsByClassName("logo_container")[0].style.margin = "30px 50px 20px 50px";
    document.getElementsByClassName("navigation_bar")[0].style.position = "static";
    document.getElementsByClassName("navigation_bar")[0].style.marginBottom = "40px";
    document.getElementsByClassName("logo_container")[0].innerHTML = `
    <span style="display:flex;">
      <span><i class="fa fa-bars" id="hamburger_icon"></i></span>
      <span style="width:100%; position:relative; top:-2px; left:-30px;">
        <span style="display:flex; gap:5px;">
          <div><img style="width:150px" src="../images/naim.png" class="naim_logo"></img></div>
          <div style="display:flex;">
            <span class="logo" style="font-size: 1.8rem;" ><span class="logo_initial">V</span><span>ISION</span></span> 
            <span style="font-size: 0.6rem; color: #C5E5CC"><span>ANPR</span></span>
          </div>   
        </span>
      </span>
    </span>`;
    var angle_right = document.getElementsByClassName("fa-angle-right");
    for (var i = 0; i < angle_right.length; i++) {
      angle_right[i].style.float = "right";
    }
    var sub_menu = document.getElementsByClassName("sub_menu");
    for (var i = 0; i < sub_menu.length; i++) {
      sub_menu[i].style.paddingLeft = "85px";
    }
    document.getElementsByClassName("navigation_links_container")[0].style.display = "none";
    document.getElementsByClassName("navigation_links_container")[0].style.marginTop = "5%";
  }

  function expandMore() {
    document.getElementsByClassName("navigation_bar")[0].style.width = "280px";
    document.getElementsByClassName("navigation_bar")[0].style.height = "100%";
    document.getElementsByClassName("navigation_bar")[0].style.position = "fixed";
    document.getElementsByClassName("navigation_bar")[0].style.marginBottom = "0px";
    document.getElementsByClassName("logo_container")[0].innerHTML = `
    <img src="../images/naim.png" class="naim_logo"></img>
    <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
    <div class="logo_tail"><span>ANPR</span></div> `;
    var angle_right = document.getElementsByClassName("fa-angle-right");
    for (var i = 0; i < angle_right.length; i++) {
      angle_right[i].style.float = "none";
    }
    var sub_menu = document.getElementsByClassName("sub_menu");
    for (var i = 0; i < sub_menu.length; i++) {
      sub_menu[i].style.paddingLeft = "57px";
      if (i == 1){
        document.getElementsByClassName("fa-angle-right")[i].style.paddingLeft = "20px";
      }  
    }
    document.getElementsByClassName("navigation_links_container")[0].style.display = "block";
    document.getElementsByClassName("navigation_links_container")[0].style.marginTop = "10%";
  }
}




