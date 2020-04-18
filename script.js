function hoverScreening(x) {

	element = document.getElementById('poster')

	element.alt = "screening " + x;

	element.src = "images/poster" + x + ".jpg";

	element.style.display = "inline";



}



function blankScreening() {

	element = document.getElementById('poster')

	/*element.alt = "dog";

	 element.src = "images/dm.gif";*/

	element.style.display = "none";

}


function setCurrentPage(x) {
     el = document.getElementById(x);
	 el.className = 'current_page_button';
}