<div id="spam-blocker-btns">
	<p class="text-right">
		<button onclick="initDeclareSpam()" id="not-spam-btn" class="alt">This Is Spam <i class="fa fa-thumbs-down"></i></button>
		<button onclick="initDeclareNotSpam()" id="is-spam-btn" class="alt">This Is NOT Spam <i class="fa fa-thumbs-up"></i></button>
	</p>
</div>

<div id="spam-training-modal" class="modal" style="display: none">
	<div class="modal-heading" id="spam-training-heading"></div>
	<div class="modal-body">

<div id="big-tick" style="display: none">
<div class="trigger"></div>
<svg version="1.1" id="tick" style="margin:  0 auto; width:  53.7%; transform: scale(0.5)" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 37 37" style="enable-background:new 0 0 37 37;" xml:space="preserve">
<path class="circ path" style="fill:none;stroke:#007700;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;" d="
	M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"
	/>
<polyline class="tick path" style="fill:none;stroke:#007700;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;" points="
	11.6,20 15.9,24.2 26.4,13.8 "/>
</svg>
</div>

<style>
.circ{
    opacity: 0;
    stroke-dasharray: 130;
    stroke-dashoffset: 130;
    -webkit-transition: all 1s;
    -moz-transition: all 1s;
    -ms-transition: all 1s;
    -o-transition: all 1s;
    transition: all 1s;
}
.tick{
    stroke-dasharray: 50;
    stroke-dashoffset: 50;
    -webkit-transition: stroke-dashoffset 1s 0.5s ease-out;
    -moz-transition: stroke-dashoffset 1s 0.5s ease-out;
    -ms-transition: stroke-dashoffset 1s 0.5s ease-out;
    -o-transition: stroke-dashoffset 1s 0.5s ease-out;
    transition: stroke-dashoffset 1s 0.5s ease-out;
}
.drawn + svg .path{
    opacity: 1;
    stroke-dashoffset: 0;
}
</style>





			<h3 class="text-center" id="spam-training-instructions"></h3>
			<div id="spam-text-selector-grid">
			<div>
			    <p class="text-left" style="text-align: left"><b>Sender Name: </b> <span class="selectable-text" id="selectable-sender-name"> Name To Go Here    </span></p>
			    <p class="text-left" style="text-align: left"><b>Sender Email: </b> <span class="selectable-text" id="selectable-sender-email"> Email To Go Here    </span></p>
			    <p class="text-left" style="text-align: left"><b>Message: </b></p>
			    <p class="selectable-text text-left" style="text-align: left" id="selectable-message"></p>
		    </div>
		    <div id="selected-indicators">
		    	<h3 id="selected-indicators-heading" class="text-center" style="display: none">Spam Indicators</h3>
		    </div>
	    </div>
	    <p class="text-center">
	    	<button class="alt" onclick="initCloseSpamTraining()">Cancel</button>
	    	<button id="indicators-submit" onclick="initSubmitIndicators()" style="display: none">Submit Negative Indicators</button>
	    </p>
	</div>
</div>

<style>
.selectable-text {
	padding:  0 1em;
}

.positive-text {
	color:  green;
}

.negative-text {
	color:  red;
}

.indicator-row {
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	justify-content: space-between;
	border-bottom:  1px #111 dotted;
	padding: 0.6em;
}

#selected-indicators-heading, .indicator-row {
	transition: 0.6s;
	opacity: 0;
}

.indicator-row button {
	margin: 0;
}

#spam-text-selector-grid {
	display:  grid;
	grid-template-columns: 6fr 5fr;
	grid-gap: 1em;
	max-height: 50vh;
	overflow: auto;
}

#spam-text-selector-grid > div {
	margin: 6px;
}

#spam-text-selector-grid > div {
	padding:  1em;
}

#spam-training-modal {
	min-width: 960px;
}

 #spam-blocker-btns {
 	padding:  0;
 }

 #spam-blocker-btns, #spam-blocker-btns p {
    margin:  0;
}

#spam-blocker-btns button {
	margin:  0 0 1em 0;
}

#selected-indicators > div > div:nth-child(2) > button {
	margin-left: 5px;
}

#big-tick {
	text-align: center;
}
</style>

<script>
var activateSelectables = false;

function initDeclareSpam() {
	openModal('spam-training-modal');
	var spamTrainingHeading = document.getElementById('spam-training-heading');
	spamTrainingHeading.innerHTML = 'This Is Spam';
	var spamTrainingInstructions = document.getElementById('spam-training-instructions');
	spamTrainingInstructions.innerHTML = 'Select the things that indicate that the message is spam';

	var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
	selectedIndicatorsHeading.innerHTML = 'Spam Indicators';
	changeSubmitBtnText('Submit Spam Indicators');
}

function initDeclareNotSpam() {
	openModal('spam-training-modal');
	var spamTrainingHeading = document.getElementById('spam-training-heading');
	spamTrainingHeading.innerHTML = 'This Is Not Spam';
	var spamTrainingInstructions = document.getElementById('spam-training-instructions');
	spamTrainingInstructions.innerHTML = 'Select the things that indicate that the message is not spam';

	var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
	selectedIndicatorsHeading.innerHTML = 'Positive Indicators';
	changeSubmitBtnText('Submit Positive Indicators');
}

function initCloseSpamTraining() {
	var indicatorRows = document.getElementsByClassName('indicator-row');
	for (var i = indicatorRows.length - 1; i >= 0; i--) {
		indicatorRows[i].remove();
	}

	var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
	selectedIndicatorsHeading.style.display = 'none';

	var indicatorsSubmitBtn = document.getElementById('indicators-submit');
	indicatorsSubmitBtn.style.display = 'none';

	var selectedIndicatorsDiv = document.getElementById('selected-indicators');
	var divShape = selectedIndicatorsDiv.getBoundingClientRect();
	var selectedIndicatorsDivHeight = divShape.height;
	selectedIndicatorsDiv.style.minHeight = 0;

	closeModal();
}

function changeSubmitBtnText(btnText) {
	var indicatorsSubmitBtn = document.getElementById('indicators-submit');
	indicatorsSubmitBtn.innerHTML = btnText;
}

function addToSelectedThings(ev, selectedText) {
	var elId = ev.target.id;
	var selectedKey = getSelectedKeyFromElId(elId);
    buildNewSelectedThing(ev, selectedKey, selectedText);
}

function getSelectedKeyFromElId(elId) {
	var selectedKey = elId.replace('selectable-', '')
	selectedKey = selectedKey.replace('-', ' ');
	selectedKey = selectedKey.charAt(0).toUpperCase() + selectedKey.slice(1);
	return selectedKey;
}

function findTargetSelectable(yPos) {
	var selectableRows = document.getElementsByClassName('selectable-text');

	for (var i = selectableRows.length - 1; i >= 0; i--) {
		var selectableRow = selectableRows[i];
		var selectableRowShape = selectableRow.getBoundingClientRect();
		var selectableRowTop = selectableRowShape.top;

		if (selectableRowTop < yPos) {
			var selectedKey = getSelectedKeyFromElId(selectableRow['id']);
			return selectedKey;
		}
	}
    
    if (typeof selectedKey == 'undefined') {
			var selectedKey = getSelectedKeyFromElId(selectableRow['id']);
			return selectedKey;				
	}
}

function buildNewSelectedThing(ev, selectedKey, selectedText) {

	var spamTrainingHeading = document.getElementById('spam-training-heading');
	if (spamTrainingHeading.innerHTML == 'This Is Spam') {
		var indicatorClass = 'negative-text';
	} else {
		var indicatorClass = 'positive-text';
	}

	var newRow = document.createElement('div');
	newRow.setAttribute('class', 'indicator-row');
	var leftDiv = document.createElement('div');

	if (selectedKey == '') {
		selectedKey = findTargetSelectable(clientYOnClick);
	}

	var leftDivText = `<b>${selectedKey}</b> contains '<span class="${indicatorClass}">${selectedText}</span>'`;
	leftDiv.innerHTML = leftDivText;
	newRow.appendChild(leftDiv);

	var rightDiv = document.createElement('div');
	var rightDivBtn = document.createElement('button');
	rightDivBtn.setAttribute('class', 'alt');

	var indicatorRows = document.getElementsByClassName('indicator-row');
	rightDivBtn.innerHTML = '<i class="fa fa-trash"></i>';
	rightDivBtn.style.opacity = 0;

	rightDivBtn.addEventListener("click", (ev) => {
		removeIndicator(ev.target);
	});

	rightDiv.appendChild(rightDivBtn);
	newRow.appendChild(rightDiv);

	var selectedIndicatiors = document.getElementById('selected-indicators');
	selectedIndicatiors.appendChild(newRow);

	setTimeout(() => {
		var indicatorRows = document.getElementsByClassName('indicator-row');
		if (indicatorRows.length>0) {
			var indicatorsSubmitBtn = document.getElementById('indicators-submit');
			indicatorsSubmitBtn.style.display = 'inline-block';
			var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
			selectedIndicatorsHeading.style.display = 'block';
			selectedIndicatorsHeading.style.opacity = 1;
		}
		newRow.style.opacity = 0.2;
		moveRowIntoPosition(ev, newRow);
	}, 10);
}

function moveRowIntoPosition(ev, newRow) {
	var selectedIndicatorsDiv = document.getElementById('selected-indicators');
	var divShape = selectedIndicatorsDiv.getBoundingClientRect();
	var selectedIndicatorsDivHeight = divShape.height;
	selectedIndicatorsDiv.style.minHeight = selectedIndicatorsDivHeight + 'px';

	var newRowShape = newRow.getBoundingClientRect();

	var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
	var selectedIndicatorsHeadingShape = selectedIndicatorsHeading.getBoundingClientRect();
	var selectedIndicatorsHeadingTop = selectedIndicatorsHeadingShape.top;

	var targetDestX = newRowShape.left;
	var targetDestY = newRowShape.top;
	var targetDestWidth = newRowShape.width;
    
    newRow.style.transition = '0.3s';
    newRow.style.opacity = 1;

	newRow.style.position = 'fixed';
	newRow.style.top = clientYOnClick + 'px';
	newRow.style.left = clientXOnClick + 'px';
	newRow.style.width = targetDestWidth + 'px';

	setTimeout(() => {
		newRow.style.top = targetDestY + 'px';
	    newRow.style.left = targetDestX + 'px';
	    newRow.style.display = 'flex';
	}, 100);

	setTimeout(() => {
		newRow.removeAttribute('style');
		newRow.style.opacity = 1;
		var trashBtn = newRow.querySelector('.alt');
	    trashBtn.style.opacity = 1;
	}, 300);
}

function removeIndicator(clickedEl) {
	var indicatorParent = findAncestor(clickedEl, 'indicator-row');
	var rowShape = indicatorParent.getBoundingClientRect();
	indicatorParent.style.height = rowShape.height + 'px';
	indicatorParent.style.transition = '0s';
	indicatorParent.style.margin = 0;
	indicatorParent.style.padding = 0;
	indicatorParent.style.transition = '1s';
	indicatorParent.style.height = 0;
	setTimeout(() => {
		indicatorParent.style.opacity = 0;
		indicatorParent.remove();
		var selectedIndicatorsDiv = document.getElementById('selected-indicators');
		selectedIndicatorsDiv.style.minHeight = '0';

			var indicatorRows = document.getElementsByClassName('indicator-row');
			if (indicatorRows.length == 0) {
				var indicatorsSubmitBtn = document.getElementById('indicators-submit');
				indicatorsSubmitBtn.style.display = 'none';

				var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
				selectedIndicatorsHeading.style.display = 'none';
			}

	}, 1000);

	indicatorParent.innerHTML = '';
}

function removeIndicatorORIG(clickedEl) {
	var indicatorParent = findAncestor(clickedEl, 'indicator-row');

	indicatorParent.style.opacity = 0;
	setTimeout(() => {

		indicatorParent.remove();

		setTimeout(() => {
			var indicatorRows = document.getElementsByClassName('indicator-row');
			if (indicatorRows.length == 0) {
				var indicatorsSubmitBtn = document.getElementById('indicators-submit');
				indicatorsSubmitBtn.style.display = 'none';

				var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
				selectedIndicatorsHeading.style.display = 'none';
			}
		}, 10);

	}, 600);

}

function findAncestor (el, cls) {
    while ((el = el.parentElement) && !el.classList.contains(cls));
    return el;
}

var clientYOnClick;
var clientXOnClick;

window.addEventListener('mousedown', (ev) => {
	var clickedEl = ev.target;
	if (clickedEl.classList.contains('selectable-text')) {
		activateSelectables = true;
		clientYOnClick = ev.clientY;
        clientXOnClick = ev.clientX;
	} else {
		activateSelectables = false;
	}
})

window.addEventListener('mouseup', (ev) => {
	var clickedEl = ev.target;
	if (activateSelectables == true) {
		setTimeout(() => {

			selection = window.getSelection();
		    var selectedText = selection.toString();
		    selectedText = selectedText.trim();
		    if (selectedText.length>0) {

		    	//build up an array of all of the selected things
		    	var selectedThings = estSelectedThings();

		    	if (selectedThings.includes(selectedText)) {
		    		alert("You have already selected that!");
		    	} else {
		    		addToSelectedThings(ev, selectedText);
		    	}

		    }
		}, 200);
	}
});

function estSelectedThings() {
	var selectedThings = [];
	var spamTrainingHeading = document.getElementById('spam-training-heading');
	if (spamTrainingHeading.innerHTML == 'This Is Spam') {
		var indicatorClass = 'negative-text';
	} else {
		var indicatorClass = 'positive-text';
	}

	var indicatorRows = document.getElementsByClassName(indicatorClass);
	for (var i = 0; i < indicatorRows.length; i++) {
		selectedThings.push(indicatorRows[i].innerHTML);
	}

	return selectedThings;
}

function drawBigTick() {

	var spamTextSelectorGrid = document.getElementById('spam-text-selector-grid');
	spamTextSelectorGrid.style.display = 'none';

	var spamTrainingInstructions = document.getElementById('spam-training-instructions');
	spamTrainingInstructions.style.display = 'none';

	var buttonsPara = document.querySelector('#spam-training-modal > div.modal-body > p');
	buttonsPara.style.display = 'none';

	var bigTick = document.getElementById('big-tick');
	bigTick.style.display = 'block';
	
	setTimeout(() => {
		var things = document.getElementsByClassName('trigger')[0];
		things.classList.add('drawn');
	}, 100);
    
    setTimeout(() => {
    	hideBigTick();
    	closeModal();
    }, 1300);
}

function hideBigTick() {

	var things = document.getElementsByClassName('trigger')[0];
	things.classList.remove('drawn');

	var spamTextSelectorGrid = document.getElementById('spam-text-selector-grid');
	spamTextSelectorGrid.style.display = 'grid';

	var spamTrainingInstructions = document.getElementById('spam-training-instructions');
	spamTrainingInstructions.style.display = 'block';

	var buttonsPara = document.querySelector('#spam-training-modal > div.modal-body > p');
	buttonsPara.style.display = 'block';

	var bigTick = document.getElementById('big-tick');
	bigTick.style.display = 'none';	

	var indicatorRows = document.getElementsByClassName('indicator-row');
	for (var i = indicatorRows.length - 1; i >= 0; i--) {
		indicatorRows[i].remove();
	}

	var selectedIndicatorsHeading = document.getElementById('selected-indicators-heading');
	selectedIndicatorsHeading.style.display = 'none';

	var indicatorsSubmitBtn = document.getElementById('indicators-submit');
	indicatorsSubmitBtn.style.display = 'none';
}

function initSubmitIndicators() {

	drawBigTick();

	var spamTrainingHeading = document.getElementById('spam-training-heading');
	if (spamTrainingHeading.innerHTML == 'This Is Spam') {
		var scoreToAllocate = -1;
	} else {
		var scoreToAllocate = 1;
	}	

	var indicatorRows = document.getElementsByClassName('indicator-row');
	var indicatorHTML = '';
	for (var i = 0; i < indicatorRows.length; i++) {
		indicatorHTML+= indicatorRows[i].innerHTML;
	}


	for (var i = 0; i < indicatorRows.length; i++) {
		var indicatorObj = {
			scoreToAllocate,
			indicatorHTML
		}
	}

        var http = new XMLHttpRequest()
        http.open('POST', '<?= $post_indicators_url ?>')
        http.setRequestHeader('Content-type', 'application/json')
        http.setRequestHeader("trongateToken", token)
        console.log('sending ' + token);
        http.send(JSON.stringify(indicatorObj)) // Make sure to stringify
}

window.addEventListener('load', (ev) => {
	var thisSenderName = document.getElementById('this-sender-name');
	var starterSenderName = document.getElementById('selectable-sender-name').innerHTML;
	thisSenderName = starterSenderName.replace('Name To Go Here', thisSenderName.innerHTML);
	document.getElementById('selectable-sender-name').innerHTML = thisSenderName;

	var thisSenderEmail = document.getElementById('this-sender-email');
	var starterSenderEmail = document.getElementById('selectable-sender-email').innerHTML;
	thisSenderEmail = starterSenderEmail.replace('Email To Go Here', thisSenderEmail.innerHTML);
	document.getElementById('selectable-sender-email').innerHTML = thisSenderEmail;

	var thisSenderMessage = document.getElementById('this-sender-message').innerHTML;
	document.getElementById('selectable-message').innerHTML = ' ' + thisSenderMessage + ' ';
});


</script>