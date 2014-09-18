
(function($) {

    /**
     * Adds content to the displayer
     * @param {Object | Element} parent The parent to add the list text to.
     * @param {string} val The header string.
     */
    function listify(parent, val){
	if (typeof val == 'string' || val instanceof String){
	    $(parent).append('<li>' + val + '</li>');
	} else {
	    $(parent).append(val)
	    $(val).wrap('<li></li>');
	}
    }

    /**
     * Adds content to the displayer
     * @param {Object | Element} displayer The displayer to attach to.
     * @param {string} header The header string.
     * @param {Array.<string> | Array.{Object}} Either or a string or a jquery object.
     */
    function addContent(displayer, header, bullets){
	var $textDiv = jQuery('<div/>', {
	    id: 'challengetext',
	}).appendTo(displayer);
	$textDiv.fadeOut(0);
	$textDiv.addClass("challengetext");
	$textDiv.html('<b>' + header + '</b>');

	if (bullets instanceof Array) {
	    $.each(bullets, function(key, val){
		listify($textDiv, val);
	    })
        } else {
	    listify($textDiv, bullets);
	}
	$($textDiv).children('li').wrapAll('<ul></ul>');
	$($textDiv).children('b').addClass("md-font");
	$($textDiv).children('ul').addClass("sm-font");
	$textDiv.fadeIn();
    }

    /**
     * Trims the trailing and beginning whitespace of a string.
     * Selected as a result of here:
     *    http://blog.stevenlevithan.com/archives/faster-trim-javascript 
     * @param {!Array.<string>} arr
     * @return {!Array.<string>} The last name sorted array.
     */
    function trimStartAndEndWhiteSpace(str){
	return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    }

    /**
     * @param {!Array.<string>} arr
     * @return {!Array.<string>} The last name sorted array.
     */
    function sortByLastName(arr){
	return arr.sort(function(a,b){
	    var aLast = trimStartAndEndWhiteSpace(a).split(" ").pop();
	    var bLast = trimStartAndEndWhiteSpace(b).split(" ").pop();
	    return (aLast > bLast) ? 1 : (aLast < bLast) ? -1 : 0;
	})
    }



    /**
     *
     * CHALLENGE ONE
     *
     * @param {Element | Object} The display
     */
    function challengeOne(displayer){
	addContent(displayer, "Challenge 1", ["See source"]);
    }


    /**
     *
     * CHALLENGE TWO - Sorting
     *
     * @param {Element | Object} The display
     */
    function challengeTwo(displayer){
	var testers = [
	    ['Gabriel Ba','John Adams','Kieth Richards  ', 
	     " ", 'Prince', 'John Adams McKensie', '   Zulu  Abrams  '],
	    ['Gabriel Ba','John Adams','Kieth Richards', 
	     "_", 'Prince', 'John Adams McKensie', 'Zulu']
	]
	var results = [];
	for (var i = 0; i<testers.length; i++){
	    results.push("\nSorting:\t\"" 
			 + testers[i].toString() + "\"<ul><li type=circle>" +
			 "Result:\t" + sortByLastName(testers[i]).toString() + 
			 "</li></ul>");
	}
	addContent(displayer, "Challenge 2", results);
    }


    /**
     * For Challenge Two
     * @param {!String} text
     */
    function makeCrossoutable(text){
	var $div = $("<div>", {id: "crossoutable", class: "crossoutable"});
	$div.click(function(){
	    $(this).toggleClass("crossout");
	})
	$div.html(text);
	return $div;
    }


    /**
     *
     * CHALLENGE THREE
     *
     * @param {Element | Object} The display
     */
    function challengeThree(displayer){
	var names = ['John Adams', 'Gabriel Ba', 
		     'John Adams McKensie', 'Prince', 'Kieth Richards']
	var crossoutables = [];
	$.each(names, function(key, val){
	    //window.console.log(makeCrossoutable(val).html())
	    crossoutables.push(makeCrossoutable(val));
	})	    
	addContent(displayer, "Challenge 3", crossoutables);
    }



    /**
     *
     * CHALLENGE FOUR
     *
     * @param {Element | Object} The display
     */
    function challengeFour(displayer){
	countdown(5);
	addContent(displayer, "Challenge 4", "See console.");
    }

    /**
     * For Challenge 4 - Fixed countdown function.
     * @param {!number} num
     */
    function countdown(num){
	var counter = 0;
	for (var i=0; i<=num; i++){
	    setTimeout(function(){
		window.console.log((counter + 1) + ': ' + (num - counter));
		counter++;
	    }, i * 1000)
	}
    }




    /**
     *
     * CHALLENGE FIVE
     *
     * @param {Element | Object} The display
     */
    function challengeFive(displayer){
	var testers = [
	    [-200,-2,-3,-5,-1], [200,2,3,400], [200,2,3, 300, 400], 
	    [[141,151,161],2,3,[101,202,[303,404]]],
	    [[141,151,161],"disney",3,[101,202,[303,"404"]]],
	    [[141,151,161],2,3,[101,202,[303,404,[100,28282,14,[123123,32,1,0,-1]]]]]
	]

	var results = [];
	$.each(testers, function(key, val){
	    results.push("\nMax of:\t\"" 
			 + val.toString() + "\"<ul><li type=circle>" +
			 "Result:\t" + nestedMax(val).toString() + 
			 "</li></ul>");
	})

	addContent(displayer, "Challenge 5", results);
    }


    /**
     * For Challenge 5
     * Returns the maximum number contained in an array with 0 to N nested arrays.
     * @param {!Array.<number>} arr
     * @return {number | NaN} Either the max, or NaN if it encounters any Non-numbers.
     */
    function nestedMax(arr){
	return arr.reduce(function(previousValue, currentValue, index, array) {
	    return Math.max(previousValue, (currentValue instanceof Array) ? 
			    nestedMax(currentValue) : currentValue);
	}, Number.NEGATIVE_INFINITY);
    }


    /**
     *
     * CHALLENGE SIX
     *
     * @param {Element | Object} The display
     */
    function challengeSix(displayer){
	var testers = {
	    'No arguments.' : longestStringArgument(),
	    'Args (strings): \"asdfasdf\", "BBB" ,\'iweieiehhhhhhhh\'':
	    longestStringArgument("asdfasdf", "BBB", 'iweieiehhhhhhhh'),
	    'Args (mixed): \"asdfasdf\", 12312 ,\'b\'':
	    longestStringArgument("asdfasdf", "12312", 'b', "23"),
	    'Args (numbers): 1,2,3,4,5': longestStringArgument(1,2,3,4,5)
	}
	var results = [];

	$.each(testers, function(key, val){
	    results.push('<i>' + key + "</i><ul><li type=circle>" +
			 "Result:\t" + val + 
			 "</li></ul>");
	})
	addContent(displayer, "Challenge 6", results);
    }

    /**
     * For Challenge 6
     * @param {String...}
     * @return {number}
     */
    function longestStringArgument(){
	var i = 0;
	var max = 0;
	for (var i = 0; i<arguments.length; i++){
	    max = Math.max(max, String(arguments[i]).length);
	}
	return max;
    }




    /**
     *
     * CHALLENGE SEVEN
     *
     * @param {Element | Object} The display
     */
    function challengeSeven(displayer){
	var testers = [
	    "012-345 69",
	    "555-2424" ,
	    "1-800 555-2424" ,
	    "000-124-123-124" ,
	    "1-800a555-2424" ,
	    "012345",
	    "-012345 678",
	    "0123-  34566",
	    "123456788975432",
	    "1234x567"
	]

	var results = [];
	var errors = [];
	var result, resultStr, error;
	$.each(testers, function(key, val){
	    result = isPhoneNumberValid(val);
	    resultStr = "Is valid?\t\'" + val + "\': " +
			 "\tresult<b>\t" + result;
	    if (!result){
		error = new Error('Invalid Phone number!\t' + val);
		resultStr += "</b><ul><li>Error created but not thrown:<i>\"" + error + 
		    "\"</i></li></ul>";
		errors.push(error);
	    }
	    results.push(resultStr);
	})
	addContent(displayer, "Challenge 7", results);
    }

    /**
     * @param {!String}
     * @return {string | boolean} String if the string is valid, False otherwise
     */
    function isPhoneNumberValid(str){
	// Validate whether first and last chars are not numbers
	if (isNaN(str[0]) || isNaN(str[str.length -1])){
	    return false;
	}

	// Validate the remainder
	var integers = "";
	for (var i = 0; i<str.length; i++){
	    if (isNaN(parseInt(str[i]))){
		if(str[i] == "-" || str[i] == " "){
		    if (isNaN(parseInt(str[i+1]))){
			isValid = false;
			break;
		    }
		} else {
		    isValid = false;
		    break;
		}
	    } else {
		integers += str[i];
	    }
	}
	if (integers.length > 12 || integers.length < 7){
	    return false;
	}
	return integers;
    }



    /**
     *
     * CHALLENGE EIGHT
     *
     * @param {Element | Object} displayer The display
     */
    function challengeEight(displayer){
	addContent(displayer, "Challenge 8", 
	   "Completed w/o use of data persistence." + 
		   '<i>To run read the instructions ' + 
'<a href="http://skumar221.github.io/disney-challenge/challenge8/README.txt">here.</a></i>');
    }



    $.fn.challenges = function() {
	displayer = this[0];
	challengeOne(this);
	challengeTwo(this);
	challengeThree(this);
	challengeFour(this);
	challengeFive(this);
	challengeSix(this);
	challengeSeven(this);
	challengeEight(this);
    }

}(jQuery));
