/*
  Translit JS class.
  v.1.2 (same as 1.1)
  12 November 2005

  ---------

  Транслитерация ссылок (приведение их в соответствие с форматом URL).
  Латинские буквы и цифры остаются, а русские + знаки препинания преобразуются
  одним из способов (способы нужны каждый для своей задачи)

  Подробнее: http://pixel-apes.com/translit

  ---------

  * UrlTranslit( str, allow_slashes ) 
    -- преобразовать строку в "красивый читаемый URL"

  * Supertag( str, allow_slashes )    
    -- преобразовать строку в "супертаг" -- короткий простой 
       идентификатор, состоящий из латинских букв и цифр.

  * BiDiTranslit( str, direction_decode, allow_slashes ) 
    -- преобразовать строку в "формально правильный URL"
       с возможностью восстановления.
       Установив второй параметр в значение "true", вы можете 
       восстановить строку обратно с незначительными потерями.

  * во всех функциях есть необязательный параметр "allow_slashes", который
    управляет тем, игнорировать ли символ "/", пропуская его неисправленным, 
    либо удалять его из строки.

=============================================================== (Mendokusee@pixeapes)
*/

function Translit()
{
  this.enabled = true;
}

// ------------------------------------------------------------------------
// ------------------------------------------------------------------------

Translit.prototype.UrlTranslit = function( str, allow_slashes )
{
   var slash = "";
   if (allow_slashes) slash = "\\/";
   
   var LettersFrom = "абвгдезиклмнопрстуфыэйхё";
   var LettersTo   = "abvgdeziklmnoprstufyejxe";
   var Consonant = "бвгджзйклмнпрстфхцчшщ";
   var Vowel = "аеёиоуыэюя";
   var BiLetters = {  
     "ж" : "zh", "ц" : "ts",  "ч" : "ch", 
     "ш" : "sh", "щ" : "sch", "ю" : "ju", "я" : "ja"
                   };

   str = str.replace( /[_\s\.,?!\[\](){}]+/g, "_");
   str = str.replace( /-{2,}/g, "--");
   str = str.replace( /_\-+_/g, "--");

   str = str.toLowerCase();


   //here we replace ъ/ь 
   str = str.replace( 
      new RegExp( "(ь|ъ)(["+Vowel+"])", "g" ), "j$2");
   str = str.replace( /(ь|ъ)/g, "");

   //transliterating
   var _str = "";
   for( var x=0; x<str.length; x++)
    if ((index = LettersFrom.indexOf(str.charAt(x))) > -1)
     _str+=LettersTo.charAt(index);
    else
     _str+=str.charAt(x);
   str = _str;

   var _str = "";
   for( var x=0; x<str.length; x++)
    if (BiLetters[str.charAt(x)])
     _str+=BiLetters[str.charAt(x)];
    else
     _str+=str.charAt(x);
   str = _str;

   str = str.replace( /j{2,}/g, "j");

   str = str.replace( new RegExp( "[^"+slash+"0-9a-z_\\-]+", "g"), "");

   return str;
}

Translit.prototype.Supertag = function( str, allow_slashes )
{
   var slash = "";
   if (allow_slashes) slash = "\\/";

   str = this.UrlTranslit( str, allow_slashes );

   str = str.replace( new RegExp( "[^"+slash+"0-9a-zA-Z\\-]+", "g"), "");
   str = str.replace( /[\-_]+/g, "-");
   str = str.replace( /-+$/g, "");

   return str;
}


Translit.prototype.BiDiTranslit = function( str, direction_decode, allow_slashes )
{
   var Tran = {
    "А" : "A",  "Б" : "B",  "В" : "V",  "Г" : "G",  "Д" : "D",  "Е" : "E",  "Ё" : "JO",  "Ж" : "ZH",  "З" : "Z",  "И" : "I",
    "Й" : "JJ", "К" : "K",  "Л" : "L",  "М" : "M",  "Н" : "N",  "О" : "O",  "П" : "P",   "Р" : "R",   "С" : "S",  "Т" : "T",
    "У" : "U",  "Ф" : "F",  "Х" : "KH",  "Ц" : "C",  "Ч" : "CH", "Ш" : "SH", "Щ" : "SHH", "Ъ" : "_~",   "Ы" : "Y",  "Ь" : "_'",
    "Э" : "EH", "Ю" : "JU", "Я" : "JA", "а" : "a",  "б" : "b",  "в" : "v",  "г" : "g",   "д" : "d",   "е" : "e",  "ё" : "jo",
    "ж" : "zh", "з" : "z",  "и" : "i",  "й" : "jj", "к" : "k",  "л" : "l",  "м" : "m",   "н" : "n",   "о" : "o",  "п" : "p",
    "р" : "r",  "с" : "s",  "т" : "t",  "у" : "u",  "ф" : "f",  "х" : "kh",  "ц" : "c",   "ч" : "ch",  "ш" : "sh", "щ" : "shh",
    "ъ" : "~",  "ы" : "y",  "ь" : "'",  "э" : "eh", "ю" : "ju", "я" : "ja", " " : "__", "_" : "__"
              };
   // note how DeTran is sorted. That is one of MAJOR differences btwn PHP & JS versions
   var DeTran = {
    "SHH"  : "Щ", // note this is tri-letter
    "CH"   : "Ч",  "SH"   : "Ш", "EH"   : "Э",  "JU"    : "Ю",  "_'"   : "Ь",  "_~"   : "Ъ", 
    "JO"   : "Ё",  "ZH"   : "Ж", "JJ"   : "Й",  "KH"    : "Х",  "JA"   : "Я",  // note they are bi-letters
    "A"    : "А",  "B"    : "Б",  "V"   : "В",  "G"     : "Г",  "D"    : "Д",  "E"    : "Е",  
    "Z"    : "З",  "I"    : "И",  "K"   : "К",  "L"     : "Л",  "M"    : "М",  "N"    : "Н",  
    "O"    : "О",  "P"    : "П",  "R"   : "Р",  "S"     : "С",  "T"    : "Т",  "U"    : "У",  
    "F"    : "Ф",  "C"    : "Ц",  "Y"   : "Ы",  
    "shh"  : "щ", // small tri-letters
    "jo"   : "ё",  "zh"   : "ж",   "jj"   : "й",  "kh"   : "х",  "ch"   : "ч",  "sh"   : "ш",
    "ju"   : "ю",  "ja"   : "я",   "__" : " ",  "eh"   : "э", // small bi-letters
    "a"    : "а",  "b"     : "б",  "v"    : "в",  "g"    : "г",  "d"    : "д",  "e"    : "е",  
    "z"    : "з",  "i"     : "и",  "k"    : "к",  "l"    : "л",  "m"    : "м",  "n"    : "н",
    "o"    : "о",  "p"     : "п",  "r"    : "р",  "s"    : "с",  "t"    : "т",  "u"    : "у",  
    "f"    : "ф",  "c"     : "ц",  "~"    : "ъ",  "y"    : "ы",  "'"    : "ь"
              };

   var result = "";
   if (!direction_decode)
   {
     str = str.replace( /[^\/\- _0-9a-zа-яА-ЯёЁ]/gi, "" );
     if (!allow_slashes) str = str.replace( /[^\/]/i, "");

     // пробел -- "русский" символ
     // все остальные не-буквы -- "английские" символы
     var is_rus = new RegExp( "[а-яА-ЯёЁ ]", "i" );

     // проходим по строке, разбивая её "русски-нерусски"
     var lang_eng = true;
     var _lang_eng = true;
     var temp;
     for (var i=0; i<str.length; i++)
     {
       _lang_eng = lang_eng;
       temp = String(str.charAt(i));
       if (temp.replace(is_rus, "") == temp) 
         lang_eng = true;
       else // convert; this conversion is the second MAJOR difference.
       {
         lang_eng = false;
         temp = Tran[ temp ]; 
       }
       if (lang_eng != _lang_eng) temp = "+"+temp;
       result += temp;
     }
   }
   else
   {
     var pgs = str.split("/");
     var DeTranRegex = new Array();
     for (var k in DeTran)
       DeTranRegex[k] = new RegExp( k, "g" );
     for (var j=0; j<pgs.length; j++)
     {
       var strings = pgs[j].split("+");
       for (var i=1; i<strings.length; i+=2)
         for (var k in DeTran)
           strings[i] = strings[i].replace( DeTranRegex[k], DeTran[k] );
       pgs[j] = strings.join("");
     }
     result = pgs.join( allow_slashes?"/":":" );
   }

   return result.replace( /\/+$/, "" );
}


