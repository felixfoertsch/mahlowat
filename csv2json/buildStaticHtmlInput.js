const request=require('request')
const csv=require('csvtojson')
var fs = require('fs');
//var parsedJSON = require('./sampleMahlowat.json');

var answers = {};
for ( var i = 0 ; i < 36 ; i ++ ) {
    answers[i] = {
        'text' : '',
        'Zustimmung' : 0,
        'Enthaltung' : 0,
        'Ablehnung' : 0,
        'ZustimmungParteien' : [],
        'EnthaltungParteien' : [],
        'AblehnungParteien' : [],
        answers : []
    };
}

function getParty(parteiname) {

    if ( parteiname == 'NPD' ) return "npd";
    if ( parteiname == 'Ökologisch-Demokratische Partei, ÖDP' ) return "ödp";
    if ( parteiname == 'FDP Landesverband Sachsen' ) return "fdp";
    if ( parteiname == 'BÜNDNIS 90/DIE GRÜNEN' ) return "grün";
    if ( parteiname == 'AfD' ) return "afd";
    if ( parteiname == 'Freie Wähler' ) return "fw";
    if ( parteiname == 'Die PARTEI' ) return "partei";
    if ( parteiname == 'Piratenpartei Deutschland' ) return "piraten";
    if ( parteiname == 'SPD' ) return "spd";
    if ( parteiname == 'Partei Mensch Umwelt Tierschutz' ) return "mut";
    if ( parteiname == 'CDU' ) return "cdu";
    if ( parteiname == 'Die LINKE' ) return "linke";

    console.log("error: " + parteiname);
}

var header = []

csv({noheader: true})
.fromStream(request.get('https://docs.google.com/spreadsheets/d/1RL2x9ocXS5et1g5teOYIsBAcIwmy1u-SPCUdsGr1BtM/pub?output=csv'))
.on('header',(header)=>{
	console.log(header);
})
.on('csv',(csvRow, index)=> {

    if ( index == 0)
        header = csvRow;
    else {
        // ingore the one from mucbkksfo@gmail.com (afd)
        var parteien = [0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 12, 13];

        // -1 because fo header shit
        if ( parteien.indexOf(index - 1) > -1 ) {

            for ( var i = 0 ; i < 36 ; i ++ ){

                answers[i].text = header[4 + (i * 2)]
                answers[i].answers.push({
                    'name' : csvRow[2],
                    'party' : getParty(csvRow[2]),
                    'answer' : csvRow[4 + (i * 2)],
                    'text' : !csvRow[4 + (i * 2) + 1] ? "Keine Erklärung angegeben" : csvRow[4 + (i * 2) + 1]
                });
            }
        }
        else {
            console.log("Ingore response from " + csvRow[2] + ", " + csvRow[3])
        }
    }
})
.on('done',(error)=>{

    fs.writeFile("/Users/gerb/Development/private/jef/euromat/static-site-generator/static-site-generator.json", JSON.stringify(answers, null, 4), function(err) {
        if(err) {
            return console.log(err);
        }

        console.log("The file was saved!");
    });
})
