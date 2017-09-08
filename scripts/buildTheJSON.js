var fs = require('fs');

var dataJSON = require('./data.json');
var thesesLst = [], listsLst = [], answersLst = [];

for(var i=0; i < Object.keys(dataJSON.theses).length; i++) {
    if (i !== 7 && i !== 8 && i !== 13) {
        thesesLst.push(dataJSON.theses[i]);
    }
}

for(var i=0; i < Object.keys(dataJSON.lists).length; i++) {
    listsLst.push(dataJSON.lists[i]);
}

for(var i=0; i < Object.keys(dataJSON.answers).length; i++) {
    answersLst.push(dataJSON.answers[i]);

    var templistAnswers = [];
    for(var j=0; j < Object.keys(dataJSON.answers[i]).length; j++) {
        if (j !== 7 && j !== 8 && j !== 13) {
            templistAnswers.push(dataJSON.answers[i][j]);
        }
    }
    answersLst[i] = templistAnswers;
}

console.log(thesesLst, thesesLst.length);
//console.log(listsLst);
//console.log(answersLst);
dataJSON.lists = listsLst;
dataJSON.theses = thesesLst;
dataJSON.answers = answersLst;
    
fs.writeFile("./generated.json", JSON.stringify(dataJSON, null, 4), function(err) {
    if(err) {
        return console.log(err);
    }

    console.log("The file was saved!");
});
