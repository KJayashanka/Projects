

// chack for answers



function OnSubmit(){
	
	
	document.getElementById('spentTime').style.visibility = 'visible'; //dispay spent time tag
	document.getElementById('submit').style.visibility = 'hidden';     // hide submit button
	
	var marks = 0; //give marks
	var qTot = 10; // total questions
	
	// store correct answers in a array
	var answersC = ['a','b','b','c','d','b','a','c','d','a']; 
	
// Get selected answers from quize form	
var q1 = document.forms['quiz']['q1'].value;
var q2 = document.forms['quiz']['q2'].value;
var q3 = document.forms['quiz']['q3'].value;
var q4 = document.forms['quiz']['q4'].value;
var q5 = document.forms['quiz']['q5'].value;
var q6 = document.forms['quiz']['q6'].value;
var q7 = document.forms['quiz']['q7'].value;
var q8 = document.forms['quiz']['q8'].value;
var q9 = document.forms['quiz']['q9'].value;
var q10 = document.forms['quiz']['q10'].value;



	// check selected answers with correct answers

	for(var i = 1; i <= qTot; i++ ){
		if(eval('q'+i)== answersC[i-1]){
			marks=marks+2;	// give marks 
			document.getElementById('right'+i).style.visibility='visible'; // display correct image
			}
		else{
			document.getElementById("correctAnswer"+i).style.visibility = 'visible'; // display correct answer div
			document.getElementById("correctAnswer"+i).innerHTML = "Correct Answer is : "+answersC[i-1]; // display correct answer
			marks--; // -1 for the wrong answer
			document.getElementById('wrong'+i).style.visibility = 'visible'; // display wrong image
		}
	}
	
	// change backlayer color according to marks 
	
	if(marks <= 0){
	document.getElementById("backLayer").style.backgroundColor = "RGB(255, 102, 102)";
	}
	else if(marks<=10){
	document.getElementById("backLayer").style.backgroundColor = "rgb(255, 173, 51)";
	}
	else if(marks<=15){
	document.getElementById("backLayer").style.backgroundColor = "rgb(255, 219, 77)";
	}
	else {
	document.getElementById("backLayer").style.backgroundColor = "rgb(0, 204, 68)";
	}
	 
	
	// display results
	
	results.innerHTML="<h2>You Scored "+ marks + " points out of "+20+"</h2>"
	alert(" You Scored "+marks+" out of "+20+". Click ok to check the paper");
	return false;
		}
	
// display time
		var timeInSecs;
        var seconds;

        function startTimer(secs){
            timeInSecs = secs;
            seconds = setInterval("tikTok()",1000); 
			
            }

            function tikTok() {
            var secs = timeInSecs;
				if (secs>0) {
					--timeInSecs;
				}
				else {
				clearInterval(seconds);//stop the time
				alert(" Sorry Your Time is Up !! click ok for get results "); 
				topFunction();
				OnSubmit(); //check answers if time out
				}
            
            document.getElementById("timerDetails").innerHTML = secs; //display time
		    document.getElementById("spentTime").innerHTML = "Spent time for the quize: "+(60-secs) // display spent time
			
            }

        startTimer(60); //start timer
		
		

		
// got to top of the page to show results		
function topFunction() {
    document.body.scrollTop = 0;
    
}

// stop time
function stopTimer(){
clearInterval(seconds);
}	

