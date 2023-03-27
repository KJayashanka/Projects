
var navBar = false ;
var options = false ;

function showNavBar(){
	var navBtns = document.getElementsByClassName("navUl");
	for (i = 0; i < navBtns.length; i++) {
		if(!navBar){
			navBtns[i].style.display = "inherit";
			navBar = true ;
		}else if(navBar) {
			navBtns[i].style.display = "none";
			navBar = false;
		}
	}
}


function showMoreOptions() {
    var moreOption = document.getElementById("more_option_container");
    if(!options){
        moreOption.style.display = "inherit";
        options = true ;
    }else if(options) {
        moreOption.style.display = "none";
        options = false;
    }
}

function closePopup(){
	document.getElementById('popupWindow').style.display = 'none';

}