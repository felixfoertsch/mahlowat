var Handlebars = require('handlebars');
var fs = require('fs');
var templateHtml = fs.readFileSync("template.html", "utf8");
var questions = JSON.parse(fs.readFileSync("static-site-generator.json", "utf8"));

Handlebars.registerHelper('ifEquals', function(arg1, arg2, options) {
    return (arg1 == arg2) ? options.fn(this) : options.inverse(this);
});

var slug = function(str) {
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
  var to   = "aaaaaeeeeeiiiiooooouuuunc------";
  for (var i=0, l=from.length ; i<l ; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return str;
};


Object.keys(questions).forEach(function(key) {

    // if ( key == 15 )
    //  console.log(questions[key]);

    var question = questions[key];
    question.answers.forEach(function(answer){
        question[answer.answer]++;

        if (answer.answer == 'Zustimmung')
            question.ZustimmungParteien.push(answer.name);
        if (answer.answer == 'Enthaltung')
            question.EnthaltungParteien.push(answer.name);
        if (answer.answer == 'Ablehnung')
            question.AblehnungParteien.push(answer.name);
    })

    question.answers.sort(function(a, b) {
        return a.name.localeCompare(b.name);
    })

    question.ZustimmungParteienCount = question.ZustimmungParteien.length;
    question.EnthaltungParteienCount = question.EnthaltungParteien.length;
    question.AblehnungParteienCount = question.AblehnungParteien.length;

    question.ZustimmungParteien = question.ZustimmungParteien.join(", ");
    question.EnthaltungParteien = question.EnthaltungParteien.join(", ");
    question.AblehnungParteien = question.AblehnungParteien.join(", ");

    if (question.ZustimmungParteien == "")
        question.ZustimmungParteien = "-"

    if (question.EnthaltungParteien == "")
        question.EnthaltungParteien = "-"

    if (question.AblehnungParteien == "")
        question.AblehnungParteien = "-"

    var colors = ['#FE851A', '#edeeed', '#FE4136', '#00CC65' ];

    question.url = key + "_" + slug(question.text) + ".html";
    question.color = colors[Math.floor(Math.random() * colors.length)];
    question.textcolor = question.color == '#edeeed' ? 'black' : 'white';

    var template = Handlebars.compile(templateHtml);
    var result = template(question);
    var fs = require('fs');
        fs.writeFile("questions/" + question.url, result, function(err) {
        if(err) {
            return console.log(err);
        }
    });
});
