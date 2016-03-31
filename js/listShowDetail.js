function addClass(element, className) {
	if(!element.classList.contains(className))
		element.classList.add(className);
}

function removeClass(element, className) {
	if(element.classList.contains(className))
		element.classList.remove(className);
}



function showElements(elements) {
	for (var j = 0; j < elements.length; j++)
		removeClass(elements[j], hideClass);
}

function hideElements(elements) {
	for (var j = 0; j < elements.length; j++)
		addClass(elements[j], hideClass);
}

function copy(elements) {
	var array = [];
	for (var j = 0; j < elements.length; j++) {
		array[j] = elements[j];
	}
	return array;
}

function forEachListItems(listElements) {
	for (var i = 0; i < listElements.length; i++) {
		var elementsToHide = copy(listElements[i].getElementsByClassName(hideClass));
		addHideEvents(listElements[i], elementsToHide);
	}
}

function addHideEvents(elementListener, elementsDynamic) {
	elementListener.onmouseleave = function() {
		hideElements(elementsDynamic);
	}

	elementListener.onmouseenter = function() {
		showElements(elementsDynamic);
	}
}

var lists = document.getElementsByClassName("yu-card-list");
var hideClass = "yu-hide";

for (var i = 0; i < lists.length; i++) {
	forEachListItems(lists[i].children);
}
