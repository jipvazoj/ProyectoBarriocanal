//LIKE
function likeButton() {
	var parentEl = this.parentElement;
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'like.php', true);
	// form data is sent appropriately as a POST request
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.onreadystatechange = function () {
		if(xhr.readyState == 4 && xhr.status == 200) {
			var result = xhr.responseText;
			console.log('Result: ' + result);
			if(result == "true"){
				parentEl.classList.add('liked');
			}
		}
	};
	xhr.send("id=" + parentEl.id);
}

var likebuttons = document.getElementsByClassName("like-button");
for(i=0; i < likebuttons.length; i++) {
	likebuttons.item(i).addEventListener("click", likeButton);
}

//UNLIKE
function unlikeButton() {
	var parentEl = this.parentElement;
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'unlike.php', true);
	// form data is sent appropriately as a POST request
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.onreadystatechange = function () {
		if(xhr.readyState == 4 && xhr.status == 200) {
			var result = xhr.responseText;
			console.log('Result: ' + result);
			if(result == "true"){
				parentEl.classList.remove('liked');
			}
		}
	};
	xhr.send("id=" + parentEl.id);
}

var unlikebuttons = document.getElementsByClassName("unlike-button");
for(i=0; i < unlikebuttons.length; i++) {
	unlikebuttons.item(i).addEventListener("click", unlikeButton);
}

//DISLIKE
function dislikeButton() {
	var parentEl = this.parentElement;
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'dislike.php', true);
	// form data is sent appropriately as a POST request
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.onreadystatechange = function () {
		if(xhr.readyState == 4 && xhr.status == 200) {
			var result = xhr.responseText;
			console.log('Result: ' + result);
			if(result == "true"){
				parentEl.classList.add('disliked');
			}
		}
	};
	xhr.send("id=" + parentEl.id);
}

var dislikebuttons = document.getElementsByClassName("dislike-button");
for(i=0; i < dislikebuttons.length; i++) {
	dislikebuttons.item(i).addEventListener("click", dislikeButton);
}

//UNDISLIKE
function undislikeButton() {
	var parentEl = this.parentElement;
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'undislike.php', true);
	// form data is sent appropriately as a POST request
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.onreadystatechange = function () {
		if(xhr.readyState == 4 && xhr.status == 200) {
			var result = xhr.responseText;
			console.log('Result: ' + result);
			if(result == "true"){
				parentEl.classList.remove('disliked');
			}
		}
	};
	xhr.send("id=" + parentEl.id);
}

var undislikebuttons = document.getElementsByClassName("undislike-button");
for(i=0; i < undislikebuttons.length; i++) {
	undislikebuttons.item(i).addEventListener("click", undislikeButton);
}
