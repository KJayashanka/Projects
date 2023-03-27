var formDetails = {
    name: '',
    email: '',
    comment: '',
    rating:''
} ;

var rated = false ;

function checkedStar(form , starIndex ,type) {

	if(type === 'hoverIn' && !rated){
        for(var i=0 ; i<starIndex; i++){
            form.ratingStar[i].checked = true;
        }
	}else if(type === 'hoverOut' && !rated){
        for(var j =0 ; j<starIndex; j++){
            form.ratingStar[j].checked = false;
        }
	}else if(type === 'onClick'){
        formDetails.rating = '';
        for(var k=0 ; k<5; k++){
            form.ratingStar[k].checked = false;
        }
		if(rated ){
            for(var k=0 ; k<starIndex; k++){
                form.ratingStar[k].checked = false;
            }
		}else {
            for(var l =0 ; l<starIndex; l++){
                form.ratingStar[l].checked = true;
                formDetails.rating = (starIndex);
            }
		}
        rated = !rated ;
        // console.log(starIndex);
	}
}


function submitReviews(form) {
    formDetails.name = form.name.value;
    formDetails.email = form.email.value;
    formDetails.comment = form.comment.value;

    if (!validateName(formDetails.name).status ) {
        document.getElementById('nameError').innerHTML = validateName(formDetails.name).message
    }else {
        document.getElementById('nameError').innerHTML = '';
    }

    if(!validateEmail(formDetails.email).status){
        console.log('validateEmail');
        console.log(validateEmail(formDetails.email));
        document.getElementById('emailError').innerHTML = validateEmail(formDetails.email).message
    }else {
        document.getElementById('emailError').innerHTML = '';
    }

    // console.log(formDetails.comment.charCodeAt(6));
    if(!validateRating(formDetails.rating).status){
        console.log('validateRating');
        document.getElementById('ratingError').innerHTML = validateRating(formDetails.rating).message
    }else {
        document.getElementById('ratingError').innerHTML = '';
    }

    if(
        validateName(formDetails.name).status &&
        validateEmail(formDetails.email).status &&
        validateRating(formDetails.rating).status ){

        document.getElementById('emailError').innerHTML = '';
        document.getElementById('ratingError').innerHTML = '';
        document.getElementById('nameError').innerHTML = '';

        var message =
            '<p style="color: #292929 ; font-size: 18px; "> Dear '+formDetails.name +'</p>' +
            '<P style="color: #4b4b4b ; font-size: 16px; ">Thank you very much for your feedback.</p>' +
            '<p style="color: #4b4b4b ; font-size: 16px; ">You have rated our site as '+ formDetails.rating+' stars and </p>'+
            '<p style="color: #4b4b4b ; font-size: 16px; " >your comment was '+ formDetails.comment+'.</p>'+

            '<br> <p style="color: #006f73 ; font-size: 18px; " >Have a Nice Day.</p>';

        document.getElementById('popupWindow').style.display = 'block';
        document.getElementById('popupContent').innerHTML = message ;
    }

    // alert(isValidName('' + output.name));
}

var numbers = '1234567890';

function validateName(nameInput) {
	if(!nameInput){
        return {
            status: false ,
            message:'Name Required'
        };
	}else if(nameInput){
        for (var letter in nameInput){
			if(numbers.includes(nameInput[letter])){
				return {
				    status: false ,
                    message:'Invalid Name'
				} ;
			}
        }
        return {status: true} ;
	}
}

function validateEmail(emailInput){

    if(emailInput) {
        console.log(emailInput);
        var atPosFirst = emailInput.indexOf('@');
        var atPosLast = emailInput.lastIndexOf('@');
        var dotPosLast = emailInput.lastIndexOf('.');

        console.log(atPosLast);
        console.log(atPosLast);
        console.log(dotPosLast);
        if(atPosLast === -1 || !dotPosLast === -1 || atPosLast>dotPosLast || atPosFirst!== atPosLast ){
            return {status: false , message: 'Invalid Email'};
        }else {
            return {status: true }
        }
    }else {
        return {status: true }
    }

}

function validateRating(ratingInput) {
    if(!ratingInput) {
        return {status: false, message: 'Rating Required'};
    }else {
        return {status: true};
    }
}








