const request=require('request')
const csv=require('csvtojson')
var fs = require('fs');
var parsedJSON = require('./sampleMahlowat.json');

function getSelection(input) {

    if ( input == 'Zustimmung' )
        return 'a';
    if ( input == 'Ablehnung' )
        return 'b'
    if ( input == 'Enthaltung' )
        return 'c'
}

csv()
.fromStream(request.get('https://docs.google.com/spreadsheets/d/1RL2x9ocXS5et1g5teOYIsBAcIwmy1u-SPCUdsGr1BtM/pub?output=csv'))
.on('csv',(csvRow, index)=> {

    // ingore the one from mucbkksfo@gmail.com (afd)
    var parteien = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ];

    // load theses
    if (index === 0) {
        var theses = [];
        for (var i=4; i < csvRow.length; i++) {
            if (csvRow[i] !== "") {
                theses.push({"l": csvRow[i], "s": "", "x": ""});
            }
        }

        parsedJSON.theses = {};
        for (var i=0; i < theses.length; i++) {
            parsedJSON.theses[i] = theses[i];
        }
    }


    if ( parteien.indexOf(index) > -1 ) {

        parsedJSON.lists[index] = {
            name : csvRow[2],
            name_x : csvRow[2],
        }

        var parteiAnswers = {};


        for ( var i = 0 ; i < 20 ; i ++ ) {

            parteiAnswers[i] = {
                'selection' : getSelection(csvRow[4 + (i * 2)]),
                'statement' : csvRow[4 + (i * 2) + 1]
            }
        }

        parsedJSON.answers[index] = parteiAnswers;
    }
    else {
        console.log("Ingore response from " + csvRow[2] + ", " + csvRow[3])
    }
})
.on('done',(error)=>{

    fs.writeFile("./generated.json", JSON.stringify(parsedJSON, null, 4), function(err) {
        if(err) {
            return console.log(err);
        }

        console.log("The file was saved!");
    });
})
