window.onload=function(){
  navigation(window.innerWidth);

  window.addEventListener('resize', function() {
    // Get the current window width
    var windowWidth = window.innerWidth;
    //console.log(windowWidth);
    navigation(windowWidth);
  })

  function navigation(value){
    if (value < 900) {
      document.getElementsByClassName("navigation_bar")[0].addEventListener("mouseover", expand);
      document.getElementsByClassName("navigation_bar")[0].addEventListener("mouseout", shrink);
      shrink();
    }
    else{
      document.getElementsByClassName("navigation_bar")[0].removeEventListener("mouseover", expand);
      document.getElementsByClassName("navigation_bar")[0].removeEventListener("mouseout", shrink);
      expand();
    }
  }

  function expand() {
    document.getElementsByClassName("navigation_bar")[0].style.transition = "0.6s ease";
    document.getElementsByClassName("navigation_bar")[0].style.width = "280px";
    document.getElementsByClassName("logo_container")[0].innerHTML = `
    <img src="../images/naim.png" class="naim_logo"></img>
    <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
    <div class="logo_tail"><span>ANPR</span></div> `;
    document.getElementsByClassName("navigation_links_container")[0].style.display = "block";
    document.getElementsByClassName("content-container")[0].style.transition = "0.6s ease";
    document.getElementsByClassName("content-container")[0].style.marginLeft = "300px";
  }
  
  function shrink(){
    document.getElementsByClassName("navigation_bar")[0].style.transition = "0.6s ease";
    document.getElementsByClassName("navigation_bar")[0].style.width = "100px";
    document.getElementsByClassName("logo_container")[0].innerHTML = `<i class="fa fa-bars" id="hamburger_icon"></i>`;
    document.getElementsByClassName("navigation_links_container")[0].style.display = "none";
    document.getElementsByClassName("content-container")[0].style.transition = "0.6s ease";
    document.getElementsByClassName("content-container")[0].style.marginLeft = "120px";
  }
}




